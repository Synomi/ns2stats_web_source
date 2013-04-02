<?php
/**
 * JSON utility functions
 */
class Json extends CComponent {

    /**
     * Prints Turns a php array into JSON object and prints it
     * @param type $output 
     */
    public static function printJSON($output) {
        $json = json_encode($output);
        if(isset($_GET['jsonp']))
            $json = $_GET['jsonp'] . '(' . $json . ');';
        
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
        header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
        header("Pragma: no-cache"); // HTTP/1.0

        header("Content-Type: application/json");

        echo $json;
    }

}