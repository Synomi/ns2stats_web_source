<?php

class htmlHelper {

    public static function truncate($string, $length) {
        if (strlen($string) > $length)
            $string = substr($string, 0, $length - 3) . '...';
        return $string;
    }
}