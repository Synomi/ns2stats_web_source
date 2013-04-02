<?php

echo CHtml::tag('h2', array(), 'Recent rounds');

        $this->widget('StatsTable', array(
    'columns' => array(
        array(
            'title' => 'Ended',
            'value' => 'CHtml::tag("a", array("class" => "timeago", "title" => date("c", $data["end"]), "href" => Yii::app()->createUrl("round/round/", array("id" => $data["id"]))), $data["end"])',
        ),
        array(
            'title' => 'Server',
            'value' => 'CHtml::tag("a", array("href" => Yii::app()->createUrl("server/server/", array("id" => $data["server_id"]))), Helper::truncate($data["server_name"], 40))',
        ),
    ),
    'rows' => Player::getRoundsList($player->id),
        )
);
?>