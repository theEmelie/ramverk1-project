<?php

namespace Anax\IpController;

use Anax\Models;
use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

/**
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class GeoWeatherController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    private $darksky;
    private $ipstack;

    public function initialize()
    {
        $this->darksky = $this->di->get('apiDarkSky');
        $this->ipstack = $this->di->get('apiIpStack');
    }

    public function indexActionGet()
    {
        $page = $this->di->get("page");
        $request = $this->di->get("request");

        $title = "Väderprognos";

        $json = [
            "ip" => $request->getServer("REMOTE_ADDR", ""),
            "status" => "",
        ];

        $page->add("weather/showWeather", [
            "weatherJson" => $json,
            "dataExists" => false,
            "status" => "Ingen data tillgänglig",
        ]);

        return $page->render([
            "title" => $title,
        ]);
    }

    /**
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function indexActionPost()
    {
        $title = "Väderprognos";
        $request = $this->di->get("request");
        $page = $this->di->get("page");
        $location = $request->getPost("location");
        $futureOrPast = $request->getPost("weather");

        $json = $this->ipstack->validate($location);

        $lat = "";
        $long = "";

        if ($json["valid"] == true) {
            $lat = $json["ipStackData"]->{"latitude"};
            $long = $json["ipStackData"]->{"longitude"};
            $weatherJson = $this->darksky->getWeatherGeo($lat, $long, $futureOrPast);
            $dataExists = true;
            $status = "";
        } else {
            $latLong = explode(",", $location);
            if (count($latLong) == 2 && is_numeric($latLong[0]) && is_numeric($latLong[1])) {
                if ($latLong[0] <= 90 && $latLong[0] >= -90 && $latLong[1] >= -180 && $latLong[1] <= 180) {
                    $lat = trim($latLong[0]);
                    $long = trim($latLong[1]);
                    $weatherJson = $this->darksky->getWeatherGeo($lat, $long, $futureOrPast);
                    $dataExists = true;
                    $status = "";
                } else {
                    $weatherJson = "";
                    $dataExists = false;
                    $status = "Okänd plats";
                }
            } else {
                $weatherJson = "";
                $dataExists = false;
                $status = "Användar error!";
            }
        }

        if ($dataExists == true) {
            $mapCode = "var latitude = $lat; var longitude = $long;";
        } else {
            $mapCode = "";
        }

        $page->add("weather/showWeather", [
            "weatherJson" => $weatherJson,
            "dataExists" => $dataExists,
            "status" => $status,
            "mapCode" => $mapCode,
        ]);

        return $page->render([
            "title" => $title,
        ]);
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
        return "404 Not Found";
    }
}
