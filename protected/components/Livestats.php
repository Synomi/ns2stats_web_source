<?php

/**
 * StatsTables component
 * Usage:
 *
 */
class Livestats extends CWidget
{

    public $server;
    public $view = null;

    public function run()
    {
        if ($this->view == null)
            $this->render('livestats', array("server" => $this->server));
        else
            $this->render($this->view, array("server" => $this->server));
    }

    public function init()
    {
        
    }

}