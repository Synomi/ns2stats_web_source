<?php

echo CHtml::tag('h2', array(), 'Servers Running ' . $map->name);

$this->widget('StatsTable', array(
    'columns' => array(
        array(
            'title' => 'Server',
            'value' => 'CHtml::tag("a", array("href" => Yii::app()->createUrl("server/server/", array("id" => $data["server_id"]))), Helper::truncate($data["server_name"], 20))',
        ),
        array(
            'title' => 'Last time played',
            'value' => 'CHtml::tag("a", array("class" => "timeago", "title" => date("c", $data["round_end"]), "href" => Yii::app()->createUrl("round/round/", array("id" => $data["round_id"]))), $data["round_end"])',
        ),
    ),
    'rows' => Map::getServerList($map->id),
        )
);
?>