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
</div>
<div class="clear">

</div>
