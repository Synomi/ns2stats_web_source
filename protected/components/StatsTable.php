<?php

/**
 * StatsTables component
 * Usage:
 * Example input:
 *        $this->columns = array(
            array(
                'title' => 'name',
                'value' => 'CHtml::tag("a", array("href" => Yii::app()->createUrl("player/player/", array("id" => $data["id"]))), $data["name"]);',
            ),
            array(
                'title' => 'Rounds Played',
                'value' => 'round($data["rounds"]);',
            )
        );
        $this->rows = array(
            array(
                'id' => 1,
                'name' => 'Zeikko',
                'rounds' => 3,
            ),
            array(
                'id' => 2,
                'name' => 'Synomi',
                'rounds' => 6,
            ),
            array(
                'id' => 3,
                'name' => 'Zups',
                'rounds' => 0,
            ),
        );
 */
class StatsTable extends CWidget {

    public $columns;
    public $rows;
    public $data = array();

    public static function load() {
        //Load jQuery
        Yii::app()->clientScript->registerCoreScript('jquery');
        Yii::app()->clientScript->registerCoreScript('jquery.ui');
    }

    public function init() {
        $this->load();
        $this->data = array_merge($this->data, array("data" => $this->columns, "rows" => $this->rows));

        $this->render('statstable', $this->data);
    }

}