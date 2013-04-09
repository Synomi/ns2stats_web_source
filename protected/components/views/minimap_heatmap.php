<div class="minimap" style="width:1024px;height:1024px;margin-left: auto;margin-right:auto;padding-top:5px;display: block;clear:both;z-index:1000">
    <canvas width="1024" height="1024" id="minimap">
        Your browser does not support canvas element. Please get newer browser if you want to view minimap stats.
    </canvas>
<p>If filters are changed, page will need to be reloaded for minimap to update. Minimap shows latest deaths, maximum of 3000.</p>
</div>

<script type="text/javascript" src="/js/round/minimap_object.js?v=2"></script>
<script type="text/javascript">
    var map;
    $(document).ready(
            function() {
                map = new Minimap_object(document.getElementById('minimap'), "<?php echo $map->name ?>", '<?php echo $map->jsonvalues ?>');

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
            });
</script>
