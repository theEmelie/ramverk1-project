<?php

namespace Anax\Models;

use Anax\Config\apiTokens;

class Weather
{
    private $keys;

    public function setKeys($keyData)
    {
        $this->keys = $keyData;
    }

    public function getWeatherGeo($lat, $long, $weather)
    {
        $accessKey = $this->keys['darksky'];

        if ($weather == "futureWeather") {
            $darkSkyUrl = 'https://api.darksky.net/forecast/' . $accessKey . "/" . $lat . "," . $long . "?units=si&lang=sv";
            $curlObj = new Curl;
            $darkSkyData = json_decode($curlObj->curl($darkSkyUrl));
        } else {
            // Past weather, change $numberOfDays to how many past days you wanna see the weather for.
            $numberOfDays = 30;
            for ($i = 0; $i < $numberOfDays; $i++) {
                $timestr = "-" . $i . " day";
                $time = strtotime($timestr, time());
                $urls[$i] = 'https://api.darksky.net/forecast/' . $accessKey . "/" . $lat . "," . $long . "," . $time . "?units=si&lang=sv";
            }
            // var_dump($urls);
            $curlObj = new Curl;
            $curlOutput = $curlObj->multiCurl($urls);
            for ($i = 0; $i < $numberOfDays; $i++) {
                $darkSkyData[$i] = json_decode($curlOutput[$i]);
            }
        }

        //Get location data
        $locationUrl = "https://nominatim.openstreetmap.org/reverse?lat=$lat&lon=$long&email=theem@live.se&format=json";
        $curlObj = new Curl;
        $locationData = json_decode($curlObj->curl($locationUrl));

        $json = [
            "weather" => $weather,
            "darkSkyData" => $darkSkyData,
            "locationData" => $locationData,
        ];

        return $json;
    }

    public function getDarkSkyWeather($lat, $long, $futureOrPast)
    {
        if ($lat <= 90 && $lat >= -90 && $long >= -180 && $long <= 180) {
            $weatherJson = $this->getWeatherGeo($lat, $long, $futureOrPast);
            $dataExists = true;
            $status = "";
        } else {
            $weatherJson = "";
            $dataExists = false;
            $status = "Okänd plats";
        }

        return [
            "weatherJson" => $weatherJson,
            "dataExists" => $dataExists,
            "status" => $status,
        ];
    }
}
