<?php
/*
 * @var player
 * @var playerImages
 * @model SignatureForm
 */
?>
<h2>Your dynamic signatures and other images</h2>

<div class="signature">
    <h2>Current signatures</h2>
    <?php
    if (isset($playerImages) && count($playerImages) > 0)
    {
        foreach ($playerImages as $pi)
        {
            ?>

            <h3 style="padding-top:20px;">Signature #<?php echo $pi['id']; ?></h3>
            <?php
            if ($pi->default == true)
            {
                echo '<span style="color:gold"><b>Currently default signature</b></span><br />';
                echo 'Default signature src url: <a href="' . Yii::app()->params['siteurl'] . Yii::app()->createUrl('player/getPlayerSignature/' . $pi['player_id']) . '" target="_blank">' . Yii::app()->params['siteurl'] . Yii::app()->createUrl('player/getPlayerSignature/' . $pi['player_id']) . '</a> Your default signature url is always same. ';
            }
            else
                echo '<a href="' . Yii::app()->params['siteurl'] . Yii::app()->createUrl('player/setDefaultSignature/' . $pi['id']) . '">Set as default signature</a>';
            ?>
            <br />
            <?php echo 'Signature src url: <a href="' . Yii::app()->params['siteurl'] . Yii::app()->createUrl('player/getSignature/' . $pi['id']) . '" target="_blank">' . Yii::app()->params['siteurl'] . Yii::app()->createUrl('player/getSignature/' . $pi['id']) . '</a> Be aware that if you recreate this signature, url changes if you use this address.' ?>
            <div style="padding:10px;">
                <img src="<?php echo Yii::app()->createUrl('player/getSignature/' . $pi['id']) ?>"/>
            </div>
            <p>Embedding as Unknown worlds forum signature</p>
            <pre style="padding:0;margin: 0;">
            [url=<?php echo Yii::app()->params['siteurl'] . Yii::app()->createUrl('player/player/' . $player->id) ?>][img]<?php echo ($pi['default'] == 1) ? Yii::app()->params['siteurl'] . Yii::app()->createUrl('player/getPlayerSignature/' . $player->id) : Yii::app()->params['siteurl'] . Yii::app()->createUrl('player/getSignature/' . $pi['id']) ?>[/img][/url]</pre>
            <a href="<?php echo Yii::app()->createUrl('player/deleteSignature/' . $pi['id']) ?>">Delete signature</a>


            <?php
        }
    }
    else
    {
        echo 'You do not have any created signatures yet.';
    }
    ?>
</div>
<?php
if (isset($playerImages) && count($playerImages) < 3)
{
    ?>

    <div class="signature">
        <h3>Create new signature / image</h3>
        <?php
        echo CHtml::beginForm('', 'post', array('enctype' => 'multipart/form-data'));
        ?>
        <?php echo CHtml::errorSummary($model); ?>

        <div class="row">
            <?php
            if (!isset($model->logo))
                $model->logo = true;
            ?>
            <?php echo CHtml::activeLabel($model, 'logo'); ?>
            <?php echo CHtml::activeCheckBox($model, 'logo') ?>
        </div>
        <div class="row">
            <?php
            if (!isset($model->steam_image))
                $model->steam_image = true;
            ?>
            <?php echo CHtml::activeLabel($model, 'steam_image'); ?>
            <?php echo CHtml::activeCheckBox($model, 'steam_image') ?>
        </div>
        <div class="row">
            <?php
            if (!isset($model->border))
                $model->border = true;
            ?>
            <?php echo CHtml::activeLabel($model, 'border'); ?>
            <?php echo CHtml::activeCheckBox($model, 'border') ?>
        </div>            
        <div class="row">
            <?php
            if (!isset($model->flag))
                $model->flag = true;
            ?>
            <?php echo CHtml::activeLabel($model, 'flag'); ?>
            <?php echo CHtml::activeCheckBox($model, 'flag') ?>
        </div>            
        <div class="row">
            <?php
            if (!isset($model->background_number))
                $model->background_number = 1;
            ?>
            <?php echo CHtml::activeLabel($model, 'background_number'); ?>
            <?php
            $backgroundOptions = array('1' => 'Marine', '2' => 'Alien', '3' => 'Overview');
            echo CHtml::activeRadioButtonList($model, 'background_number', $backgroundOptions, array('separator' => ' '));
            ?>
        </div>

        <div class = "row">
            <?php
            if (!isset($model->width))
                $model->width = 600;
            ?>
            <?php echo CHtml::activeLabel($model, 'width');
            ?>
            <?php echo CHtml::activeTextField($model, 'width') ?>
        </div>

        <div class="row">
            <?php
            if (!isset($model->height))
                $model->height = 160;
            ?>
            <?php echo CHtml::activeLabel($model, 'height'); ?>
            <?php echo CHtml::activeTextField($model, 'height') ?>
        </div>
        <div class="row">
            <?php echo CHtml::activeLabel($model, 'data'); ?>
            <?php
            if ($model->data == null)
                $model->data = '[SKIPSTEAM]Player: [steam_name]
[SKIPSTEAM]Rank: #[rank] of #[ranked_players]
[SKIPSTEAM]Rounds played: [rounds_played] 
[SKIPSTEAM]Kills per death: [kpd]
[SKIPSTEAM]Kills: [kills], deaths: [deaths]
[SKIPSTEAM]Best killstreak: [best_kill_streak]
[SKIPSTEAM]Longest survival: [longest_survival]
[SKIPSTEAM]Score: [score]
[SKIPSTEAM]Score/death: [score_per_death]

[SKIPSTEAM]-- Elo rankings --
[SKIPSTEAM]Marine: #[marine_ranking], com: #[marine_commander_ranking]
[SKIPSTEAM]Alien: #[alien_ranking], com: #[alien_commander_ranking]
';

            echo CHtml::activeTextArea($model, 'data');
            ?>                
            <pre>             
    Your calculated values: (more later)
    [rank] [ranked_players] [rounds_played] [score_per_minute] [score_per_death] [score]
    [kpd] [best_kill_streak] [kills] [deaths] [longest_survival] [time_played] [rounds_played]
    [alien_ranking] [marine_ranking]  [marine_commander_ranking] [alien_commander_ranking]
    [kills_by_rifle] [kills_by_shotgun] [kills_by_pistol] [kills_by_bite] [kills_by_swipe] [kills_by_spit]
    [last_server]

    Your raw values:   
    [id] [rating] [marine_commander_elo] [steam_id] [steam_name] [steam_url] [steam_image]
    [country] [rating] [kill_elo_rating] [win_elo_rating] [commander_elo_rating] [marine_win_elo] 
    [alien_win_elo] [marine_commander_elo] [alien_commander_elo] [last_seen] [last_server_id]

    Specials:
    [ns2stats.com_time] 
    Easier placement of texts: (you can use each once per row. Row means line before line break)
    [SKIP100] = Skip 100 pixels on beginning of row
    [SKIPSTEAM] = Skip 161 pixels on beginning of row
    [SKIP200] = Skip 100 pixels on beginning of row
    [SKIP300] = Skip 100 pixels on beginning of row
    [SKIP400] = Skip 100 pixels on beginning of row
    [SKIP500] = Skip 100 pixels on beginning of row
    [SKIP600] = Skip 100 pixels on beginning of row

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
    <?php
}
else
    echo '<b>Maximum number of signature images per account is 3.</b>';
?>
