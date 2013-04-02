<?php

/**
 * StatsTables component
 * Usage:
 *
 */
class Livestats extends CWidget {

    public $server;
    public function run()
    {
        $this->render('livestats',array("server" => $this->server));
    }
     public function init()
     {
         
     }
}