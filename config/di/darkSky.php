<?php
/**
 * Configuration file for DI container.
 */
return [
    // Services to add to the container.
    "services" => [
        "apiDarkSky" => [
            "shared" => true,
            "callback" => function () {
                $darkSkyModel = new \Anax\Models\Weather();

                // Load the configuration files
                $cfg = $this->get("configuration");
                $config = $cfg->load("apiTokens.php");
                $darkSkyModel->setKeys($config['config']);
                return $darkSkyModel;
            }
        ],
    ],
];
