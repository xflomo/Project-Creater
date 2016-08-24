<?php
/**
 * Created by PhpStorm.
 * User: Abing
 * Date: 24.08.2016
 * Time: 14:08
 */

namespace MainBundle\Service;

class Valdiator
{
    function checkUrl($url) {
        // Simple check
        if (!$url) { return FALSE; }
        if (!filter_var($url, FILTER_VALIDATE_URL) === false) {
            // Create cURL resource using the URL string passed in
            $curl_resource = curl_init($url);
            // Set cURL option and execute the "query"
            curl_setopt($curl_resource, CURLOPT_RETURNTRANSFER, true);
            curl_exec($curl_resource);
            // Check for the 404 code (page must have a header that correctly display 404 error code according to HTML standards
            if(curl_getinfo($curl_resource, CURLINFO_HTTP_CODE) == 404) {
                // Code matches, close resource and return false
                curl_close($curl_resource);
                return FALSE;
            } else {
                // No matches, close resource and return true
                curl_close($curl_resource);
                return TRUE;
            }
            // Should never happen, but if something goofy got here, return false value
            return FALSE;
        }
    }
}