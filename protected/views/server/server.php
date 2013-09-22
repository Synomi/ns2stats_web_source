
<div id="livestatus">
    <?php
    $widget = $this->widget('Livestats', array(
                'server' => $server,
            ));
    ?>
</div>
<p style="padding-left:20px;font-size:10px;">Livestats update once per 30 seconds.</p>
<?php
    $this->pageTitle = $server->name . ' - NS2Stats';
    $this->widget('FilterForm', array(
        'builds' => All::getBuilds(),
        'filter' => $filter,
    ));
?>
    <div class="span-10 last">
        <div class="box">
        <?php
        $this->widget('FilterPanel', array(
            'url' => 'server/recentrounds',
                )
        );
        ?>
    </div>
</div>
<div class="span-10">
    <?php
        $widget = $this->widget('Highchart', array(
                    'type' => 'line',
                    'options' => array(
                        'url' => $this->createUrl('server/playersline', array('id' => $server->id)),
                        'title' => array(
                            'text' => 'Average Number of Players',
                            'style' => array(
                                'color' => '#FFF',
                            ),
                        ),
                        'yAxis' => array(
                            'title' => array(
                                'text' => 'Players',
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
                        'url' => $this->createUrl('server/mapspie', array('id' => $server->id)),
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
                    )
                ));
        $widget->renderContent();
    ?>
</div>
<script type="text/javascript">
    function updateLivestats()
    {
        $.get('<?php echo Yii::app()->baseUrl . "/live/livestats/" . $server->id?> ', function(data) {
            $('#livestatus').html(data);
        });        
    }
    $(document).ready(function(){
        var t=self.setInterval(function(){updateLivestats()},15000);
    });
</script>