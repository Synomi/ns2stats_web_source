<?php
$this->pageTitle = $team->name . ' - NS2Stats';
$this->widget('FilterForm', array(
    'servers' => Team::getPlayedServers($team->id),
    'builds' => Team::getPlayedBuilds($team->id),
    'filter' => $filter,
));
?>
<div class="content-box">
    <?php
    echo CHtml::tag('h1', array(), $team->name);
    ?>
</div>
<div class="span-10">
    <div class="box">
        <?php
        echo CHtml::tag('h2', array(), 'Players');

        $this->widget('StatsTable', array(
            'columns' => array(
                array(
                    'title' => '',
                    'value' => '""; if($data["country"]) echo CHtml::image(Yii::app()->baseUrl . "/images/flags/" . strtolower($data["country"]) . ".png", $data["country"])',
                ),
                array(
                    'title' => 'Player',
                    'value' => 'CHtml::tag("a", array("href" => Yii::app()->createUrl("player/player/", array("id" => $data["id"]))), htmlspecialchars($data["steam_name"]))',
                ),
            ),
            'rows' => Team::getPlayers($team['id']),
                )
        );
        ?>
    </div>
</div>
<div class="span-10">
    <div class="box">
        <?php
        $this->widget('FilterPanel', array(
            'url' => 'team/recentrounds',
                )
        );
        ?>
    </div>
</div>
<div class="span-5 overview-chart">
    <?php
    $widget = $this->widget('Highchart', array(
        'type' => 'pie',
        'options' => array(
            'url' => $this->createUrl('team/roundresultspie', array('id' => $team->id)),
            'title' => array(
                'text' => 'Wins / Losses',
                'style' => array(
                    'color' => '#FFF',
                ),
            ),
            'legend' => array(
                'itemStyle' => array(
                    'color' => '#FFF',
                ),
            ),
        )
            ));
    $widget->renderContent();
    ?>
</div>