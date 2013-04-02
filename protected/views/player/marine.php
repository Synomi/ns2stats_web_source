<script type="text/javascript">
    var highcharts = new Array();
</script>
<div class="marine">
    <div class="span-5">
        <?php
        $widget = $this->widget('Highchart', array(
            'type' => 'pie',
            'tooltipFormatter' => "function() { return this.point.name +': '+ Math.round(this.percentage*100, 4) / 100 + ' % (' + secondsToTime(this.y) + ')'; }",
            'options' => array(
                'url' => $this->createUrl('player/weaponspie', array('id' => $player->id, 'team' => 1)),
                'title' => array(
                    'text' => 'Selected Weapon',
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
    <div class="span-5">
        <?php
        $widget = $this->widget('Highchart', array(
            'type' => 'pie',
            'options' => array(
                'url' => $this->createUrl('player/killsbyweaponpie', array('id' => $player->id, 'team' => 1)),
                'title' => array(
                    'text' => 'Kills By Weapon',
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
            'options' => array(
                'url' => $this->createUrl('player/killedlifeformspie', array('id' => $player->id, 'team' => 1)),
                'title' => array(
                    'text' => 'Killed Lifeforms',
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

    <script type="text/javascript">
        loadHighcharts();
    </script>
    <div class="clear">

    </div>
</div>