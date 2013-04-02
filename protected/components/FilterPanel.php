<?php

class FilterPanel extends CWidget {

    public $url;
    public $id;
    public $style="";
    public $params = array();

    public function init() {
        if (!$this->id)
            $this->id = uniqid();
        $this->render('filterpanel', array(
        ));
    }

}
