<?php

/**
 * JSON utility functions
 */
class Json extends CComponent
{

    /**
     * Prints Turns a php array into JSON object and prints it
     * @param type $output 
     */
    public static function printJSON($output, $code = 200)
    {
        $json = json_encode($output);
        if (isset($_GET['jsonp']))
            $json = $_GET['jsonp'] . '(' . $json . ');';

        $codeMessage = self::getCodeMessage($code);
        header("HTTP/1.0 $code $codeMessage");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
        header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
        header("Pragma: no-cache"); // HTTP/1.0

        header("Content-Type: application/json");

        echo $json;
    }

    public static function getCodeMessage($code)
    {
        $codes = Array(

            100 => 'Continue',

            101 => 'Switching Protocols',

            200 => 'OK',

            201 => 'Created',

            202 => 'Accepted',

            203 => 'Non-Authoritative Information',

            204 => 'No Content',

            205 => 'Reset Content',

            206 => 'Partial Content',

            300 => 'Multiple Choices',

            301 => 'Moved Permanently',

            302 => 'Found',

            303 => 'See Other',

            304 => 'Not Modified',

            305 => 'Use Proxy',

            306 => '(Unused)',

            307 => 'Temporary Redirect',

            400 => 'Bad Request',

            401 => 'Unauthorized',

            402 => 'Payment Required',

            403 => 'Forbidden',

            404 => 'Not Found',

            405 => 'Method Not Allowed',

            406 => 'Not Acceptable',

            407 => 'Proxy Authentication Required',

            408 => 'Request Timeout',

            409 => 'Conflict',

            410 => 'Gone',

            411 => 'Length Required',

            412 => 'Precondition Failed',

            413 => 'Request Entity Too Large',

            414 => 'Request-URI Too Long',

            415 => 'Unsupported Media Type',

            416 => 'Requested Range Not Satisfiable',

            417 => 'Expectation Failed',

            500 => 'Internal Server Error',

            501 => 'Not Implemented',

            502 => 'Bad Gateway',

            503 => 'Service Unavailable',

            504 => 'Gateway Timeout',

            505 => 'HTTP Version Not Supported'

        );
        return (isset($codes[$code])) ? $codes[$code] : '';
    }

}