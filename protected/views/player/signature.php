<?php
/*
 * @var player
 * @var playerImages
 * @model SignatureForm
 */
?>
<h2>Your dynamic signatures and other images</h2>
<?php


if (isset($playerImages) && count($playerImages) < 3)
{
    ?>
    <h3>Create new signature / image</h3>
    <div style="padding:10px;">
        <div class="form">
            <?php
            echo CHtml::beginForm('', 'post', array('enctype'=>'multipart/form-data'));
            ?>
            <?php echo CHtml::errorSummary($model); ?>

            <div class="row">
                <?php echo CHtml::activeLabel($model, 'width'); ?>
                <?php echo CHtml::activeTextField($model, 'width') ?>
            </div>

            <div class="row">
                <?php echo CHtml::activeLabel($model, 'height'); ?>
                <?php echo CHtml::activeTextField($model, 'height') ?>
            </div>
            <div class="row">
                <?php echo CHtml::activeLabel($model, 'data'); ?>
                <?php if ($model->data == null) $model->data = 'My rating : [rating]' . PHP_EOL . 'My rank : [rank]';
                echo CHtml::activeTextArea($model, 'data') ?>                
                <pre style="padding-top:0px;margin-top:0px;">      
Your raw values:          
[id] [rating] [marine_commander_elo] [steam_id] [steam_name] [steam_url] [steam_image]
[country] [rating] [kill_elo_rating] [win_elo_rating] [commander_elo_rating] [marine_win_elo] 
[alien_win_elo] [marine_commander_elo] [alien_commander_elo] [last_seen] [last_server_id]

Your calculated values: (to come later)

Your server statistics: (to come later)

                </pre>
            </div>
            
            <div class="row">
                <?php echo CHtml::activeLabel($model, 'background_image'); ?>
                <?php echo CHtml::activeFileField($model, 'background_image'); ?>
            </div>
            <div class="row submit">
                <?php echo CHtml::submitButton('Create signature'); ?>
            </div>

            <?php echo CHtml::endForm(); ?>
        </div><!-- form -->
    </div>
    <?php
}
else
    echo '<b>Maximum number of signature images per account is 3.</b>';


if (isset($playerImages) && count($playerImages) > 0)
{
    foreach ($playerImages as $pi)
    {
        ?>
        <div style="padding:20px;">
            <b>Signature #<?php echo $pi['id']; ?></b><br />
            <?php echo 'Image src url: <a href="' . Yii::app()->params['siteurl'] . Yii::app()->createUrl('player/getSignature/' . $pi['id']) . '" target="_blank">' . Yii::app()->params['siteurl'] . Yii::app()->createUrl('player/getSignature/' . $pi['id']) . '</a>' ?>
            <div style="padding:10px;">
                <img src="<?php echo Yii::app()->createUrl('player/getSignature/' . $pi['id']) ?>"/>
            </div>
            <a href="<?php echo Yii::app()->createUrl('player/deleteSignature/' . $pi['id']) ?>">Delete signature</a>
        </div>
        <?php
    }
}
else
{
    echo 'You do not have any created signatures yet.';
}

?>
