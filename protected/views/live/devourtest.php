<?php
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile(Yii::app()->baseUrl . '/js/live/devour-engine.js');
$cs->registerScriptFile(Yii::app()->baseUrl . '/js/live/devour-actors.js');
$cs->registerScriptFile(Yii::app()->baseUrl . '/js/live/devour-extensions.js');
$cs->registerScriptFile(Yii::app()->baseUrl . '/js/live/webgl-heatmap.js');
//$cs->registerCssFile(Yii::app()->baseUrl . '/css/simple-slider.css');
$map = Map::model()->findByAttributes(array('name' => 'ns2_summit'));
?>
<div class="wide">
    <h1>Devour testing</h1>

    <div style="position: relative;width: 1024px;height:1024px;background-color:white;padding:0;margin:30px;">

        <canvas width="1024" height="1024" id="devour">
            Your browser does not support canvas element. Please get newer browser if you want to view devour.
        </canvas>
        <canvas style="position: absolute;top:0px;left:0px;" width="1024" height="1024" id="devourOverlay"></canvas>
        <canvas style="position: absolute;top:0px;left:0px;" width="1024" height="1024" id="interactiveOverlay"></canvas>
    </div>
    <?php
    
        ?>
    <script type="text/javascript">
        var devour;
        $(document).ready(
                function() {
                    var heatmap = null;

                    try
                    {
                        heatmap = createWebGLHeatmap({canvas: document.getElementById('devourOverlay'), height: 1024, width: 1024});
                    }
                    catch (ex)
                    {
                        heatmap = null;
                    }
                    devour = new DevourEngine(document.getElementById('devour'), heatmap, document.getElementById('interactiveOverlay'), {data: <?php echo $map->jsonvalues ?>,map: '<?php echo $map->name ?>'});
                });
    </script>
</div>