<script type="text/javascript">
    var highcharts = new Array();
</script>
<div class="marine">
    <div class="span-5">
        <?php
        $widget = $this->widget('Highchart', array(
            'type' => 'pie',
            'options' => array(
                'url' => $this->createUrl('player/lifeformroundresultspie', array('id' => $player->id, 'lifeform' => 'marine_commander')),
                'title' => array(
                    'text' => 'Win / Loss',
                    'style' => array(
                        'color' => '#3CB7FE',
                    ),
                ),
                'legend' => array(
                    'itemStyle' => array(
                        'color' => '#3CB7FE',
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
                'url' => $this->createUrl('player/timeplayedmarinecommanderpie', array('id' => $player->id)),
                'title' => array(
                    'text' => 'Time Played',
                    'style' => array(
                        'color' => '#3CB7FE',
                    ),
                ),
                'legend' => array(
                    'itemStyle' => array(
                        'color' => '#3CB7FE',
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