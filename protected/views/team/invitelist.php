<?php

$this->widget('StatsTable', array(
    'columns' => array(
        array(
            'title' => 'Name',
            'value' => 'CHtml::link($data["name"], array("player/player/", "id" => $data["id"]))',
        ),
        array(
            'title' => 'Invite',
                'value' => 'CHtml::link("Invite to " . $team["name"], array("inviteplayer", "team" => $team["id"], "player" => $data["id"]))',
        ),
    ),
    'rows' => Player::getPlayers($namePhrase),
    'data' => array('team' => $team),
        )
);