<div class="alien">
    <?php
    $startLocations = Map::getStartLocations($map->id, 2);
    foreach ($startLocations as $startLocation) {
        ?>
        <div class="span-5">
            <?php
            $widget = $this->widget('Highchart', array(
                'type' => 'pie',
                'options' => array(
                    'url' => $this->createUrl('map/startlocationroundresultspie', array('id' => $map->id, 'team' => 2, 'startLocation' => $startLocation['start_location'])),
                    'title' => array(
                    'text' => 'Start in ' . $startLocation['start_location'] . '<br />Wins / Losses',
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
        <?php
    }
    ?>


    <div class="clear">

    </div>
</div>
<script type="text/javascript">
    loadHighcharts();
</script>