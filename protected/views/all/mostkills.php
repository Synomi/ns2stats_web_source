<?php
echo CHtml::tag('h2', array(), 'Most Kills');

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
                        <a title="View \' . htmlspecialchars($data[\'name\']) . \' profile page" href="\' . Yii::app()->baseUrl . \'/player/player/\' . $data[\'id\'] . \'">\' . htmlspecialchars($data[\'name\']) . \'</a>\''
        ),
        array(
            'title' => 'Kills',
            'value' => '$data["kills"]',
        ),
        array(
            'title' => 'Streak',
            'value' => 'Player::getKillStreak($data["id"])',
        ),
    ),
    'rows' =>  All::getKillsList(),
        )
);
?>