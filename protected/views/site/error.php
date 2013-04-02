<?php
/* @var $this SiteController */
/* @var $error array */

?>
<div class="content-box" style="padding-left:3em">
    <h2>Error <?php echo $code; ?></h2>
    
    <div class="error">
        <?php echo CHtml::encode($message); ?>
    </div>
    
</div>