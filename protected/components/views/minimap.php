<div class="minimap" style="width:1024px;height:1024px;margin-left: auto;margin-right:auto;display: block;clear:both;z-index:1000">
    <canvas width="1024" height="1024" id="minimap">
        Your browser does not support canvas element. Please get newer browser if you want to view minimap stats.
    </canvas>
    <p>Legend: Shades of blue = marines, shared of red = aliens. (minimap test version)</p>
</div>

<script type="text/javascript" src="/js/round/minimap_object.js?v=5"></script>
<script type="text/javascript">
    var map
    $(document).ready(
    function(){
        map = new Minimap_object(document.getElementById('minimap'), "<?php echo $map->name ?>" , '<?php echo $map->jsonvalues ?>');

        //map.startLines = function()
        //{
<?php
$hits = Hit::getAllHitsForRound($round->id);
$timeout = 1000;
foreach ($hits as $hit)
{
    $timeout+=20;
    //$hit['attacker_x']},{$hit['attacker_z']},{$hit['target_x']},{$hit['target_z']
    $hit_json = json_encode($hit, JSON_UNESCAPED_UNICODE);
    echo
    <<<EOF
map.addHit({$hit_json});

EOF;
}
//setTimeout("map.drawLine({)",{$timeout});
?>
    });
</script>
