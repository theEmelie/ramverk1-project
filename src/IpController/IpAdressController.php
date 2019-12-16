<?php

namespace Anax\IpController;

use Anax\Models;
use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

/**
* @SuppressWarnings(PHPMD.TooManyPublicMethods)
*/
class IpAdressController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    private $ipstack;

    public function initialize()
    {
        $this->ipstack = $this->di->get('apiIpStack');
    }

    public function indexActionGet()
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

        $page->add("ip/ipCheck", [
            "json" => $json,
        ]);

        return $page->render([
            "title" => $title,
        ]);
    }

    public function indexActionPost()
    {
        $title = "Ip Validering";
        $request = $this->di->get("request");
        $page = $this->di->get("page");
        $ips = $request->getPost("ip");

        $json = $this->ipstack->validate($ips);

        $page->add("ip/ipCheck", [
            "json" => $json,
        ]);

        return $page->render([
            "title" => $title,
        ]);
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
