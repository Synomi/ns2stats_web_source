<script type="text/javascript">
    var highcharts = new Array();
</script>
<div class="alien">
    <div class="span-5">
        <?php
        $widget = $this->widget('Highchart', array(
            'type' => 'pie',
            'tooltipFormatter' => "function() { return this.point.name +': '+ Math.round(this.percentage*100, 4) / 100 + ' % (' + secondsToTime(this.y) + ')'; }",
            'options' => array(
                'url' => $this->createUrl('player/timeplayedbylifeformpie', array('id' => $player->id)),
                'title' => array(
                    'text' => 'Lifeforms',
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
            'tooltipFormatter' => "function() { return this.point.name +': '+ Math.round(this.percentage*100, 4) / 100 + ' % (' + secondsToTime(this.y) + ')'; }",
            'options' => array(
                'url' => $this->createUrl('player/weaponspie', array('id' => $player->id, 'team' => 2)),
                'title' => array(
                    'text' => 'Selected Attack',
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
                'url' => $this->createUrl('player/killsbyweaponpie', array('id' => $player->id, 'team' => 2)),
                'title' => array(
                    'text' => 'Kills By Weapon',
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


    <script type="text/javascript">
        loadHighcharts();
    </script>
    <div class="clear">

    </div>
</div>