<script type="text/javascript">
    var highcharts = new Array();
</script>
<div class="alien">
    <div class="span-5">
        <?php
        $widget = $this->widget('Highchart', array(
            'type' => 'pie',
            'options' => array(
                'url' => $this->createUrl('player/lifeformroundresultspie', array('id' => $player->id, 'lifeform' => 'alien_commander')),
                'title' => array(
                    'text' => 'Win / Loss',
                    'style' => array(
                        'color' => '#FB6B01',
                    ),
                ),
                'legend' => array(
                    'itemStyle' => array(
                        'color' => '#FB6B01',
                    ),
                ),
            )
                ));
        $widget->renderContent();
        ?>
    </div>
    <div class="span-5">
        <?php
        $widget = $this->widget('Highchart', array(
            'type' => 'pie',
            'tooltipFormatter' => "function() { return this.point.name +': '+ Math.round(this.percentage*100, 4) / 100 + ' % (' + secondsToTime(this.y) + ')'; }",
            'options' => array(
                'url' => $this->createUrl('player/timeplayedaliencommanderpie', array('id' => $player->id)),
                'title' => array(
                    'text' => 'Time Played',
                    'style' => array(
                        'color' => '#FB6B01',
                    ),
                ),
                'legend' => array(
                    'itemStyle' => array(
                        'color' => '#FB6B01',
                    ),
                ),
            ),
                ));
        $widget->renderContent();
        ?>
    </div>
    <div class="span-5">
        <?php
        $widget = $this->widget('Highchart', array(
            'type' => 'pie',
            'options' => array(
                'url' => $this->createUrl('player/hiveupgradespie', array('id' => $player->id)),
                'title' => array(
                    'text' => 'First Hive Upgrade',
                    'style' => array(
                        'color' => '#FB6B01',
                    ),
                ),
                'legend' => array(
                    'itemStyle' => array(
                        'color' => '#FB6B01',
                    ),
                ),
            ),
                ));
        $widget->renderContent();
        ?>
    </div>


    <script type="text/javascript">
        loadHighcharts();
    </script>
    <div class="clear">

    </div>
</div>