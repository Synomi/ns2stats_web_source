<?php
$this->widget('FilterForm', array(
    'servers' => All::getServers(),
    'filter' => $filter,
));
?>
<div class="span-15 overview-chart">
    <?php
    $widget = $this->widget('Highchart', array(
        'type' => 'column',
        'options' => array(
            'url' => $this->createUrl('balance/roundresultscolumn'),
            'title' => array(
                'text' => 'Wins',
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
                    'stacking' => 'percent',
                ),
            ),
            'xAxis' => array(
                'labels' => array(
                    'enabled' => true,
                ),
            ),
        ),
        'tooltipFormatter' => "function() { return this.series.name + ' : ' + Math.round(this.percentage, 2) +'% (' + this.y + ')'; }",
        'xAxisLabelFormatter' => "function() { return this.value; }",
            ));
    $widget->renderContent();
    ?>
</div>
<div class="span-15 overview-chart last">
    <?php
    $widget = $this->widget('Highchart', array(
        'type' => 'column',
        'options' => array(
            'url' => $this->createUrl('balance/killsbyteamcolumn'),
            'title' => array(
                'text' => 'Kills',
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
                    'stacking' => 'percent',
                ),
            ),
            'xAxis' => array(
                'labels' => array(
                    'enabled' => true,
                ),
            ),
        ),
        'tooltipFormatter' => "function() { return this.series.name + ' : ' + Math.round(this.percentage, 2) +'% (' + this.y + ')'; }",
        'xAxisLabelFormatter' => "function() { return this.value; }",
            ));
    $widget->renderContent();
    ?>
</div>
<div class="span-15 overview-chart">
    <?php
    $widget = $this->widget('Highchart', array(
        'type' => 'column',
        'options' => array(
            'url' => $this->createUrl('balance/timeplayedalienlifeformcolumn'),
            'title' => array(
                'text' => 'Lifeform usage',
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
                    'stacking' => 'percent',
                ),
            ),
            'xAxis' => array(
                'labels' => array(
                    'enabled' => true,
                ),
            ),
        ),
        'tooltipFormatter' => "function() { return this.series.name + ' : ' + Math.round(this.percentage, 2) +'%'; }",
        'xAxisLabelFormatter' => "function() { return this.value; }",
            ));
    $widget->renderContent();
    ?>
</div>
<div class="span-15 overview-chart last">
    <?php
    $widget = $this->widget('Highchart', array(
        'type' => 'column',
        'options' => array(
            'url' => $this->createUrl('balance/averagelifetimealienlifeformcolumn'),
            'title' => array(
                'text' => 'Average Lifeform Lifetime',
                'style' => array(
                    'color' => '#FFF',
                ),
            ),
            'legend' => array(
                'itemStyle' => array(
                    'color' => '#FFF',
                ),
            ),
            'xAxis' => array(
                'labels' => array(
                    'enabled' => true,
                ),
            ),
        ),
        'tooltipFormatter' => "function() { return this.series.name + ' : ' + secondsToTime(this.y); }",
        'xAxisLabelFormatter' => "function() { return this.value; }",
            ));
    $widget->renderContent();
    ?>
</div>