<?php
$cacheId = "url" . $this->url .  "filterpanel_url" . Yii::app()->createAbsoluteUrl($this->url, array_merge($_GET, $this->params)) . "_id" . $this->id;

if($this->beginCache($cacheId, array('duration'=>1800))) {
?>
<script type="text/javascript" >
    $(document).ready( function() {
        filterpanels.push({
            'url' : '<?php echo Yii::app()->createAbsoluteUrl($this->url, array_merge($_GET, $this->params)); ?>',
            'id' : '<?php echo $this->id; ?>',
            'request' : 0
        });
    });
</script>

<div id="<?php echo $this->id ?>" style="<?php echo $this->style ?>" >
    <?php Yii::app()->runController($this->url); ?>
</div>
<?php $this->endCache(); } ?>