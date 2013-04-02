<?php

echo CHtml::tag('h2', array(), 'Ingame nicks');

$this->widget('StatsTable', array(
    'columns' => array(
        array(
            'title' => 'Name',
            'value' => '$data["name"]',
        ),
    ),
    'rows' => Player::getNickList($player->id),
        )
);