
<?php

echo CHtml::tag('h2', array(), 'Servers');

$this->widget('StatsTable', array(
    'columns' => array(
        array(
            'title' => '',
            'value' => '""; if($data["country"]) echo CHtml::image(Yii::app()->baseUrl . "/images/flags/" . strtolower($data["country"]) . ".png", $data["country"])',
        ),
        array(
            'title' => 'Name',
            'value' => 'CHtml::tag("a", array("title" => "View server stats.","href" => Yii::app()->createUrl("server/server/", array("id" => $data["id"]))), $data["name"]) .
                                CHtml::tag("div", array("style" => "display:inline-block;float:right"),
                                CHtml::tag("a", array("href" => "steam://run/4920//connect " . $data["ip"] . ":" . $data["port"], "title" => "Connect to " . $data["name"]),"(Connect)")
                                )
                        ',
        ),
        array(
            'title' => 'IP Address',
            'value' => '$data["ip"]',
        ),
        array(
            'title' => 'Port',
            'value' => '$data["port"]',
        ),
        array(
            'title' => 'Active',
            'value' => 'CHtml::tag("a", array("class" => "timeago", "title" => date("c", $data["lastGame"]), "href" => Yii::app()->createUrl("round/round/", array("id" => $data["round_id"]))), $data["round_end"])',
        ),
        array(
            'title' => 'Players',
            'value' => '$data["last_player_count"]',
        ),
        array(
            'title' => 'Last map',
            'value' => 'CHtml::tag("a", array("href" => Yii::app()->createUrl("map/map/", array("id" => $data["map_id"]))), $data["map_name"])',
        ),
        array(
            'title' => 'Server Admin',
            'value' => 'CHtml::tag("a", array("href" => Yii::app()->createUrl("player/player/", array("id" => $data["admin_id"]))), $data["admin_name"])',
        ),
    ),
    'rows' => Server::getList(),
        )
);
?>    