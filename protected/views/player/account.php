<div class="box">
    <?php
    echo CHtml::tag('h1', array(), 'My Account (' . $player->steam_name . ')');    
    echo CHtml::tag('p', array(), 'Code for in-game commands : ' . $player->code);
    echo CHtml::tag('p', array(), 'User level: ' . Player::getGroupName($player->group));
    echo CHtml::button('Update my profile data from steam pages', array('submit' => array('player/updateprofile')));
    ?> 
</div>
<div class="clear">

</div>
