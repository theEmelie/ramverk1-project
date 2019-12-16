<?php

namespace Anax\IpController;

use Anax\Models;
use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

/**
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class GeoWeatherJsonController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    private $darksky;
    private $ipstack;

    public function initialize()
    {
        $this->darksky = $this->di->get('apiDarkSky');
        $this->ipstack = $this->di->get('apiIpStack');
    }

    public function indexAction()
    {
        $page = $this->di->get("page");
        $request = $this->di->get("request");

        $title = "Väderprognos";

        $json = [
            "ip" => $request->getServer("REMOTE_ADDR", ""),
            "status" => "",
        ];

        $page->add("weather/weatherJson", [
            "weatherJson" => $json,
            "dataExists" => false,
            "status" => "Ingen data tillgänglig",
        ]);

        return $page->render([
            "title" => $title,
        ]);
    }

    public function indexActionPost()
    {
        $request = $this->di->get("request");
        $location = $request->getPost("location");
        $futureOrPast = $request->getPost("weather");

        $darkSkyData = $this->getWeather($location, $futureOrPast);

        return json_encode($darkSkyData);
    }

    public function weatherCheckActionGet()
    {
        $request = $this->di->get("request");
        $location = $request->getGet("location");
        $futureOrPast = $request->getGet("weather");

        $darkSkyData = $this->getWeather($location, $futureOrPast);

        return json_encode($darkSkyData);
    }

    private function getWeather($location, $futureOrPast)
    {
        $lat = "";
        $long = "";
        $json = $this->ipstack->validate($location);

        if ($json["valid"] == true) {
            $darkSkyData = $this->darksky->getDarkSkyWeather($json["ipStackData"]->{"latitude"}, $json["ipStackData"]->{"longitude"}, $futureOrPast);
        } else {
            $latLong = explode(",", $location);
            if (count($latLong) == 2 && is_numeric($latLong[0]) && is_numeric($latLong[1])) {
                $lat = trim($latLong[0]);
                $long = trim($latLong[1]);
                $darkSkyData = $this->darksky->getDarkSkyWeather($lat, $long, $futureOrPast);
            } else {
                $darkSkyData = [
                    "weatherJson" => "",
                    "dataExists" => false,
                    "status" => "Användar error!",
                ];
            }
        }
        return $darkSkyData;
    }

    /**
     * Adding an optional catchAll() method will catch all actions sent to the
     * router. You can then reply with an actual response or return void to
     * allow for the router to move on to next handler.
     * A catchAll() handles the following, if a specific action method is not
     * created:
     * ANY METHOD mountpoint/**
     *
     * @param array $args as a variadic parameter.
     *
     * @return mixed
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function catchAll(...$args)
    {
        // Deal with the request and send an actual response, or not.
        //return __METHOD__ . ", \$db is {$this->db}, got '" . count($args) . "' arguments: " . implode(", ", $args);
        return "404 Not Found";
    }
}
