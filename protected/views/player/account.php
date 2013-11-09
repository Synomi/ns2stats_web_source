<div class="box">
    <?php
    echo CHtml::tag('h1', array(), 'My Account (' . $player->steam_name . ')');
    echo CHtml::tag('p', array(), 'Code for in-game commands : ' . $player->code);
    echo CHtml::tag('p', array(), 'User level: ' . Player::getGroupName($player->group));
    echo CHtml::button('Update my profile data from steam pages', array('submit' => array('player/updateprofile')));
    if ($player->hidden)
        echo CHtml::button('Make my stats visible for everyone.', array('submit' => array('player/togglehidden')));
    else
        echo CHtml::button('Hide my stats from public.', array('submit' => array('player/togglehidden')));
    ?> 
    <h3 style="padding-top:30px;">Nationality</h3>
    <form action="<?php echo Yii::app()->createUrl('player/updateNationality') ?>" method="POST">
        <select name="nationality">
            <?php
            $sql = 'SELECT DISTINCT country FROM player ORDER BY country';
            $connection = Yii::app()->db;
            $command = $connection->createCommand($sql);
            $nationalities = $command->queryAll();
            
            foreach ($nationalities as $nat)
            {
                if ($player->country == $nat['country'])
                    echo '<option selected="selected" value="' . $nat['country'] . '">' . $nat['country'] . '</option>';                        
                else
                    echo '<option value="' . $nat['country'] . '">' . $nat['country'] . '</option>';                        
                
            }
            ?>
        </select>
        <input type="submit" value="Change nationality" />
    </form>
</div>
<div class="clear">

</div>
