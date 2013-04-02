<?php

class IpInfoDB extends CComponent {

    private $apiKey = '9a82d14f75fda8700cb035581b7d512125e800a68a7c5d94a9dd4869a85c4a96';
    private $baseUrl = 'http://api.ipinfodb.com/v3/ip-country/';

    public function getCountry($ip) {
        $data = ApiClient::requestjson($this->baseUrl, array(
            'key' => $this->apiKey,
            'ip' => $ip,
            'format' => 'json',
        ));
        if($data['statusCode'] == 'OK') {
            return $data['countryCode'];
        }
    }

}
