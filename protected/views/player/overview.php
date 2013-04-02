<script type="text/javascript">
    var highcharts = new Array();
</script>
<div class="overview">
    <div class="span-10 overview-chart">
        <?php
        $widget = $this->widget('Highchart', array(
            'type' => 'line',
            'options' => array(
                'url' => $this->createUrl('player/roundsplayedline', array('id' => $player->id)),
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
    <div class="span-5 overview-chart last">
        <?php
        $widget = $this->widget('Highchart', array(
            'type' => 'pie',
            'options' => array(
                'url' => $this->createUrl('player/roundresultspie', array('id' => $player->id)),
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
            'type' => 'pie',
            'options' => array(
                'url' => $this->createUrl('player/mapspie', array('id' => $player->id)),
                'title' => array(
                    'text' => 'Maps',
                    'style' => array(
                        'color' => '#FFF',
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
    <div class="span-5 overview-chart">
        <?php
        $widget = $this->widget('Highchart', array(
            'type' => 'pie',
            'options' => array(
                'url' => $this->createUrl('player/teamspie', array('id' => $player->id)),
                'title' => array(
                    'text' => 'Team',
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
            'type' => 'pie',
            'tooltipFormatter' => "function() { return this.point.name +': '+ Math.round(this.percentage*100, 4) / 100 + ' % (' + secondsToTime(this.y) + ')'; }",
            'options' => array(
                'url' => $this->createUrl('player/teamstimepie', array('id' => $player->id)),
                'title' => array(
                    'text' => 'Time in Team',
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
    <script type="text/javascript">
        loadHighcharts();
    </script>
    <div class="clear">

    </div>
</div>