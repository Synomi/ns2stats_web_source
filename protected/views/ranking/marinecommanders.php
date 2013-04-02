<?php

echo CHtml::tag('h2', array(), 'Top Marine Commanders');

$this->widget('StatsTable', array(
    'columns' => array(
        array(
            'title' => '',
            'value' => '""; if($data["country"]) echo CHtml::image(Yii::app()->baseUrl . "/images/flags/" . strtolower($data["country"]) . ".png", $data["country"])',
        ),
        array(
            'title' => 'Player',
            'value' => '\'
                        <img src="\' . $data[\'steam_image\'] . \'" style="width:14px;height:14px" />                        
                        <a title="View \' . htmlspecialchars($data[\'name\']) . \' profile page" href="\' . Yii::app()->baseUrl . \'/player/player/\' . $data[\'id\'] . \'">\' . Helper::truncate(htmlspecialchars($data[\'name\']), 25) . \'</a>\''
        ),
        array(
            'title' => 'Wins',
            'value' => '$data["wins"]',
        ),
        array(
            'title' => 'Losses',
            'value' => '$data["losses"]',
        ),
        array(
            'title' => 'ELO',
            'value' => '$data["marine_commander_elo"]',
        ),
    ),
    'rows' => Ranking::getMarineCommanderList(),
        )
);
?>