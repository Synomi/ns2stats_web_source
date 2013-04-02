<?php

class FilterForm extends CWidget {

    public $servers = array();
    public $builds = array();
    public $private = array('0' => 'Public', '1' => 'Competitive');
    public $teams = array();
    public $mods = array();
    public $filter;

    public function init() {
        if (!$this->filter)
            $this->filter = new Filter();
        if (count($this->servers))
            $this->servers = CHtml::listData($this->servers, 'id', 'name');
        $this->builds = CHtml::listData($this->builds, 'build', 'build');
        if (count($this->teams))
            $this->teams = array_merge(array('0' => 'No Team'), CHtml::listData($this->teams, 'id', 'name'));
        if (count($this->mods))
            $this->mods = array_merge(array('0' => 'No Mods'), CHtml::listData($this->mods, 'id', 'name'));

        $this->filter->loadFromSession();
        $this->filter->loadDefaults();
        if (!isset($this->filter->server))
            $this->filter->server = array_keys($this->servers);
        if (!isset($this->filter->build))
            $this->filter->build = $this->builds;
        if (!isset($this->filter->team))
            $this->filter->team = array_keys($this->teams);
        if (!isset($this->filter->mod))
            $this->filter->mod = array_keys($this->mods);
        $savedFilter = Yii::app()->user->getState('filter');
        if (!$savedFilter)
            $this->filter->saveToSession();
        $this->render('filterform', array(
        ));
    }

}