<?php
$this->widget('StatsTable', array(
    'columns' => array(
        array(
            'title' => 'Name',
            'value' => 'CHtml::link($data["name"], array("player/player/", "id" => $data["id"]))',
        ),        
        array(
            'title' => 'Kills',
            'value' => 'Player::getKillsById($data["id"])',
        ),
        array(
            'title' => 'Deaths',
            'value' => 'Player::getDeaths($data["id"])',
        ),
        array(
            'title' => 'K:D',
            'value' => 'round(Player::getKD($data["id"]), 2)',
        ),

                
    ),
    'rows' => Player::getPlayers($namePhrase),
        )
);


