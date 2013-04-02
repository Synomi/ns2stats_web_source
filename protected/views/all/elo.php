<?php
echo '<div class="span-10">';
echo CHtml::tag('h2', array(), 'Kill ELO rankings');
$this->widget('StatsTable', array(
    'columns' => array(
//        array(
//            'title' => 'Ranking',
//            'value' => '$data["ranking"] . ". "',
//        ),        
        array(
            'title' => 'Player',
            'value' => 'CHtml::tag("a", array("href" => Yii::app()->createUrl("player/player/", array("id" => $data["id"]))), htmlspecialchars($data["name"]))',
        ),
        array(
            'title' => 'Rating',
            'value' => '$data["kill_elo_rating"]',
        ),
    ),
    'rows' => All::getTopKillEloRating(1000),
        )
);
echo "</div>";
echo '<div class="span-10">';
echo CHtml::tag('h2', array(), 'Win ELO rankings');
$this->widget('StatsTable', array(
    'columns' => array(
//        array(
//            'title' => 'Ranking',
//            'value' => '$data["ranking"] . ". "',
//        ),        
        array(
            'title' => 'Player',
            'value' => 'CHtml::tag("a", array("href" => Yii::app()->createUrl("player/player/", array("id" => $data["id"]))), htmlspecialchars($data["name"]))',
        ),
        array(
            'title' => 'Rating',
            'value' => '$data["win_elo_rating"]',
        ),
    ),
    'rows' => All::getTopWinEloRating(1000),
        )
);
echo "</div>";
echo '<div class="span-10 last">';
echo CHtml::tag('h2', array(), 'Commander ELO rankings');
$this->widget('StatsTable', array(
    'columns' => array(
//        array(
//            'title' => 'Ranking',
//            'value' => '$data["ranking"] . ". "',
//        ),        
        array(
            'title' => 'Player',
            'value' => 'CHtml::tag("a", array("href" => Yii::app()->createUrl("player/player/", array("id" => $data["id"]))), htmlspecialchars($data["name"]))',
        ),
        array(
            'title' => 'Rating',
            'value' => '$data["commander_elo_rating"]',
        ),
    ),
    'rows' => All::getTopCommanderEloRating(1000),
        )
);
echo "</div>";