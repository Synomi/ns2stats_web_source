<?php
$this->pageTitle = $map->name . ' - NS2Stats';
$this->widget('FilterForm', array(
    'servers' => All::getServers(),
    'builds' => All::getBuilds(),   
));
?>
<div class="content-box">
    <?php
    echo CHtml::tag('h1', array(), $map->name);
    ?>
</div>
<div class="span-5 overview-chart">
    <?php
    $widget = $this->widget('Highchart', array(
                'type' => 'pie',
                'options' => array(
                    'url' => $this->createUrl('map/roundresultspie', array('id' => $map->id)),
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
<div class="span-5 overview-chart">
    <?php
    $widget = $this->widget('Highchart', array(
                'type' => 'column',
                'options' => array(
                    'url' => $this->createUrl('map/roundlengthspie', array('id' => $map->id)),
                    'title' => array(
                        'text' => 'Round Length',
                        'style' => array(
                            'color' => '#FFF',
                        ),
                    ),
                    'legend' => array(
                        'itemStyle' => array(
                            'color' => '#FFF',
                        ),
                    ),
                    'plotOptions' => array(
                        'column' => array(
                            'stacking' => 'normal',
                        ),
                    ),
                ),
                'tooltipFormatter' => "function() { return this.x + '<br />' + this.series.name + ' :<br />' + Math.round(this.percentage, 2) +'% (' + this.y + ')'; }",
            ));
    $widget->renderContent();
    ?>
</div>
<div class="span-10 overview-chart">
    <?php
    $widget = $this->widget('Highchart', array(
                'type' => 'line',
                'options' => array(
                    'url' => $this->createUrl('map/roundsplayedline', array('id' => $map->id)),
                    'title' => array(
                        'text' => 'Rounds Played',
                        'style' => array(
                            'color' => '#FFF',
                        ),
                    ),
                    'yAxis' => array(
                        'title' => array(
                            'text' => 'Rounds',
                            'style' => array(
                                'color' => '#FFF',
                            ),
                        ),
                    ),
                    'legend' => array(
                        'itemStyle' => array(
                            'color' => '#FFF',
                        ),
                    ),
                ),
            ));
    $widget->renderContent();
    ?>
</div>
<div class="span-10 last">
    <div class="box">
        <?php
        $this->widget('FilterPanel', array(
            'url' => 'map/serverlist',
                )
        );
        ?>
    </div>
</div>
<div class="left" style="width: 100%">
    <?php
        $this->widget('zii.widgets.jui.CJuiTabs', array(
            'tabs' => array(
//            'Overview' => array('ajax' => array('/player/overview', 'id' => $player->id)),
                'Marine' => array('class' => "marine", 'ajax' => array('/map/marine', 'id' => $map->id)),
                'Alien' => array('ajax' => array('/map/alien', 'id' => $map->id)),
            ),
            'options' => array(
                'cache' => true,
            ),
            'id' => 'player-tabs',
        ));
    ?>
    </div>
<?php
        $minimap = $this->widget('Minimap', array(
                    'map' => $map,
                    'deaths' => Death::getLatestsDeathsForMap($map->name),
                    'type' => 2,
                ));        
?>
