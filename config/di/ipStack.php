<?php
/**
 * Configuration file for DI container.
 */
return [
    // Services to add to the container.
    "services" => [
        "apiIpStack" => [
            "shared" => true,
            "callback" => function () {
                $ipStackModel = new \Anax\Models\IpValidate();

                // Load the configuration files
                $cfg = $this->get("configuration");
                $config = $cfg->load("apiTokens.php");
                $ipStackModel->setKeys($config['config']);
                return $ipStackModel;
            }
        ],
    ],
];
