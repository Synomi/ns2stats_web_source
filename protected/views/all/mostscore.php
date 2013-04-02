<?php

echo CHtml::tag('h2', array(), 'Most Commander Victories');

$this->widget('StatsTable', array(
    'columns' => array(
        array(
            'title' => 'Player',
            'value' => 'CHtml::tag("a", array("href" => Yii::app()->createUrl("player/player/", array("id" => $data["id"]))), htmlspecialchars($data["name"]))',
        ),
        array(
            'title' => 'Victories',
            'value' => '$data["wins"]',
        ),
        array(
            'title' => 'Losses',
            'value' => '$data["losses"]',
        ),
        array(
            'title' => 'Ratio',
            'value' => '""; echo ($data["losses"]) ? round($data["wins"] / $data["losses"], 2) : ""',
        ),
    ),
    'rows' => All::getCommanderList(),
        )
);
?>