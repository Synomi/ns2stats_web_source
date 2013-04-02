<?php
echo '<div class="span-10" style="width:340px;margin-left:100px;">';
echo CHtml::tag('h2', array(), 'Top rankings long');
$this->widget('StatsTable', array(
    'columns' => array(
        array(
            'title' => 'Ranking',
            'value' => '$data["ranking"] . ". "',
        ),        
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
            'title' => 'Rating',
            'value' => '$data["rating"]',
        ),
    ),
    'rows' => Ranking::getTopRankingsLong(1000),
        )
);
echo "</div>";
?>