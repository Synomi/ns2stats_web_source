<?php

/**
 * StatsTables component
 * Usage:
 *
 */
class StatsTables extends CWidget {

    public $options = array();       
    public $optionsJson;
    public $data;
    public $type;
    
    public static function load() {
        //Load jQuery
        Yii::app()->clientScript->registerCoreScript('jquery');
        Yii::app()->clientScript->registerCoreScript('jquery.ui'); 
    }

    public function init() {
        $this->load();

        //Add renderTo to the default options
        if (!isset($this->options['general']['renderTo']))
            $this->options['general']['renderTo'] = 'statstables-' . uniqid();

        $this->optionsJson = json_encode($this->options);
        
    }
    public function createTableWith2NicksAndImages($data)
    {
        $html="";
        $html.="<div class='grid-view'>";
        $html.='<table class="items">';
        $html.='<thead>';
        $html.='<tr>';
        $html.='<th id="yw0_c0">Player</th></tr>';
        $html.='</thead>';
        $html.='<tbody>';
        $nextClass="odd";
        foreach( $data as $key => $value){
            $html.= '<tr class="' . $nextClass . '"><td>';
            $html.= '<img src="' . $value['steam_image'] . '" style="width:14px;height:14px" /> ';
            $html.= '<a title="View player round stats" href="#" onclick="openPlayerRoundStats(' . $value['id'] . ')">' . $value['name'] . '</a>';
            $html.= ' <a title="View player profile page" href="/player/player/' . $value['id'] . '">(';
            $html.= $value['steam_name'];
            $html.= ')</a></td></tr>';
            $nextClass = ($nextClass=="odd")? "even" : "odd";
            //$html.= "id" . $value['id'] . " name " . $value['name'] . " steamname: " . $value['steam_name'] . $value['steam_image'] . "<br>" ;
        }
        $html.="</tbody>";
        $html.="</table>";
        $html.="</div>";
        
        return $html;
    }

    public function test()
    {
        //$this->render('player', array(
//            'player' => $player,
//        ));
//        return $data;
    }

    public function run()
    {
        
        $this->render('statstables',array("data" => $this->data, "type" => $this->type));
    }    
}