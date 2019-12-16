<?php

namespace Anax\IpController;

use Anax\Models;
use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

/**
* @SuppressWarnings(PHPMD.TooManyPublicMethods)
*/
class JsonController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    private $ipstack;

    public function initialize()
    {
        $this->ipstack = $this->di->get('apiIpStack');
    }

    public function indexAction()
    {
        $page = $this->di->get("page");
        $request = $this->di->get("request");

        $title = "Ip Validering";

        $json = [
            "ip" => $request->getServer("REMOTE_ADDR", ""),
            "domain" => "",
            "valid" => "",
            "status" => "",
        ];

        $page->add("ip/apiCheck", [
            "json" => $json,
        ]);

        return $page->render([
            "title" => $title,
        ]);
    }

    public function indexActionPost()
    {
        $request = $this->di->get("request");
        $ips = $request->getPost("ip");

        $json = $this->ipstack->validate($ips);

        return json_encode($json);
    }

    public function apiCheckActionGet()
    {
        $request = $this->di->get("request");
        $ips = $request->getGet("ip");

        $json = $this->ipstack->validate($ips);

        return json_encode($json);
    }

    /**
    * @SuppressWarnings(PHPMD.UnusedFormalParameter)
    */
    public function catchAll(...$args)
    {
        // Deal with the request and send an actual response, or not.
        //return __METHOD__ . ", \$db is {$this->db}, got '" . count($args) . "' arguments: " . implode(", ", $args);
        return "404 Not Found";
    }
}
