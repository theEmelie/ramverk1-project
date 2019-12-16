<?php

namespace Anax\Models;

class Curl
{
    public function curl($url)
    {
          // Initialize CURL:
          $curlHandle = curl_init($url);
          curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);

          // Store the data:
          $json = curl_exec($curlHandle);
          curl_close($curlHandle);

          return $json;
    }

    /**
     *
     * @SuppressWarnings(PHPMD.ShortVariable)
     */
    public function multiCurl($urls)
    {
        $cHandles = [];

        //create the multiple cURL handle
        $mh = curl_multi_init();

        $count = count($urls);

        // set URL and other appropriate options
        for ($i = 0; $i < $count; $i++) {
            $cHandles[$i] = curl_init();
            curl_setopt($cHandles[$i], CURLOPT_RETURNTRANSFER, true);
            curl_setopt($cHandles[$i], CURLOPT_URL, $urls[$i]);
            curl_setopt($cHandles[$i], CURLOPT_HEADER, 0);

            curl_multi_add_handle($mh, $cHandles[$i]);
        }

        //execute the multi handle
        do {
            $status = curl_multi_exec($mh, $active);
            if ($active) {
                curl_multi_select($mh);
            }
        } while ($active && $status == CURLM_OK);

        $json = [];
        $countCHandles = count($cHandles);
        for ($i=0; $i < $countCHandles; $i++) {
            array_push($json, curl_multi_getcontent($cHandles[$i]));
        }

        for ($i = 0; $i < $count; $i++) {
            curl_multi_remove_handle($mh, $cHandles[$i]);
        }

        curl_multi_close($mh);

        return $json;
    }
}
