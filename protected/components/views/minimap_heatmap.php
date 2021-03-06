<?php
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile(Yii::app()->baseUrl . '/js/map/webgl-heatmap.js');
$cs->registerScriptFile(Yii::app()->baseUrl . '/js/map/simple-slider.min.js');
$cs->registerCssFile(Yii::app()->baseUrl . '/css/simple-slider.css');
?>



<div style="width:1024px;height:1054px;margin-left: auto;margin-right:auto;padding-top:10px;display: block;clear:both;z-index:1000;position: relative">    
    <canvas width="1024" height="1024" id="minimap">
        Your browser does not support canvas element. Please get newer browser if you want to view minimap stats.
    </canvas>
    <canvas style="position: absolute;top:10px;left:0px;" width="1024" height="1024" id="minimapoverlay"></canvas>
    <div style="position: absolute;top:10px;left:10px">
        Options: Marines <input title="Shows only marine deaths (or marine killers if selected)." type="checkbox" checked="checked" id="marines" name="marines"/>
        | Aliens <input title="Shows only alien deaths (or alien killers if selected)." name="aliens" type="checkbox" checked="checked" id="aliens" /> 
        | Killer <input title="Shows only killers, normally only deaths are shown. If marine team is selected alien killers are shown and wiseversa." name="killer" type="checkbox" id="killer" /> 
        | Marine wins <input title="Deaths/killer shown if marine side won" checked="checked" name="marineWins" type="checkbox" id="marineWins" />
        | Alien wins <input title="Deaths/killer shown if alien side won" checked="checked" name="alienWins" type="checkbox" id="alienWins" /><br /><br />
        Intensity: <input value="0.05"  type="text" data-slider="true" id="intensity_slider"><br />
        Point Size: <input value="0.7"  type="text" data-slider="true" id="size_slider">         
    </div>
    <p>If filters are changed, page will need to be reloaded for minimap to update. Minimap shows latest deaths, maximum of 3000.</p>
</div>

<script type="text/javascript" src="/js/map/minimap_object.js?v=4"></script>
<script type="text/javascript">
    var map;
    $(document).ready(
            function() {
                var drawNormal = false;

                try
                {
                    var heatmap = createWebGLHeatmap({canvas: document.getElementById('minimapoverlay'), height: 1024, width: 1024});
                }
                catch (ex)
                {
                    drawNormal = true;
                }
                map = new Minimap_object(document.getElementById('minimap'), heatmap, "<?php echo $map->name ?>", '<?php echo $map->jsonvalues ?>', drawNormal);

                //map.startLines = function()
                //{
<?php
$timeout = 1000;
foreach ($deaths as $death)
{
    //$hit['attacker_x']},{$hit['attacker_z']},{$hit['target_x']},{$hit['target_z']
    $hit_json = json_encode($death, JSON_UNESCAPED_UNICODE);
    echo
    <<<EOF
map.addDeath({$hit_json});

EOF;
}
//setTimeout("map.drawLine({)",{$timeout});
?>

                $("#intensity_slider").bind("slider:changed", function(event, data) {
                    // The currently selected value of the slider
                    map.heatmap.clear();
                    map.intensity = data.value;
                    map.drawLines();


                    // The value as a ratio of the slider (between 0 and 1)

                });
                $("#size_slider").bind("slider:changed", function(event, data) {
                    // The currently selected value of the slider
                    map.heatmap.clear();
                    map.pointSize = data.value * 100;
                    map.drawLines();

                    // The value as a ratio of the slider (between 0 and 1)

                });

                $('#marines').change(function () {                    
                    map.marines =this.checked;
                    map.heatmap.clear();
                    map.drawLines();
                 });

                $('#aliens').change(function () {                    
                    map.aliens =this.checked;    
                    map.heatmap.clear();
                    map.drawLines();
                 });
                $('#killer').change(function () {                    
                    map.killer =this.checked;    
                    map.heatmap.clear();
                    map.drawLines();
                 });
                $('#marineWins').change(function () {                    
                    map.marineWins =this.checked;    
                    map.heatmap.clear();
                    map.drawLines();
                 });
                $('#alienWins').change(function () {                    
                    map.alienWins =this.checked;    
                    map.heatmap.clear();
                    map.drawLines();
                 });

            });


</script>   
