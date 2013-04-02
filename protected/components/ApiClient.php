<?php

//Builds urls, performs queries and parses responses
class ApiClient extends CComponent {

    //Performs a request with query parameters in the url and parses the return JSON to array
    public static function requestjson($requestUrl, $data = null) {
        $response = ApiClient::request($requestUrl, $data);
        $json = CJSON::decode($response, true);
        return $json;
    }

    //Performs a request with query parameters in the url.
    //Does not parse the response, xml will have to be parsed separately
    public static function requestxml($requestUrl, $data = null) {
        $response = ApiClient::request($requestUrl, $data);
        return $response;
    }

    //Builds url, requests the page with curl and returns the response
    public static function request($requestUrl, $data) {
        if ($data) {
            $requestData = ApiClient::createQueryString($data);
            if (strpos($requestUrl, '?'))
                $requestUrl .= '&';
            else
                $requestUrl .= '?';
            $requestUrl .= $requestData;
        }
        //open connection
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $requestUrl);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        //execute 
        $response = curl_exec($ch);

        //close connection
        curl_close($ch);

//        var_dump($response);
        return $response;
    }

    //Builds url query string
    private static function createQueryString($post) {
        if ($post) {
            $fields_string = '';
            foreach ($post as $key => $value) {
                if (is_array($value))
                    foreach ($value as $value2)
                        $fields_string .= $key . '=' . urlencode($value2) . '&';
                else
                    $fields_string .= $key . '=' . urlencode($value) . '&';
            }
        }
        else
            $fields_string = '';
        return $fields_string;
    }

}

