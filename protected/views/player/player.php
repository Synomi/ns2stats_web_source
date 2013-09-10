<?php
$this->pageTitle = $player->steam_name . ' - NS2Stats';
if (!$hidden)
    $this->widget('FilterForm', array(
//        'servers' => Player::getPlayedServers($player->id),
//        'builds' => Player::getPlayedBuilds($player->id),
//        'teams' => Team::getTeamsByPlayer($player->id),
        'servers' => All::getServers(),
        'builds' => All::getBuilds(),
        'mods' => All::getMods(),
    ));

echo CHtml::tag('h1', array('class' => 'steam-name'), CHtml::tag('a', array('href' => $player->steam_url), $player->steam_name));
?>
<div class="span-5">
    <div class="box">
        <?php
        echo CHtml::tag('a', array('href' => $player->steam_url), CHtml::tag('img', array('src' => $player->steam_image, 'alt' => $player->steam_name)))
        ?>
    </div>
</div>

<div class="span-10">
    <div class="box">
        <?php
        if (!$hidden)
            $this->widget('FilterPanel', array(
                'url' => 'player/general',
                    )
            );
        else
            echo "<p>This player has hidden his/hers stats from public</p>";
        ?>
    </div>
</div>
<div class="span-5">
    <div class="box">
        <?php
        if (!$hidden)
            $this->widget('FilterPanel', array(
                'url' => 'player/ingamenicks',
                    )
            );
        ?>
    </div>
</div>
<div class="span-10 last">
    <div class="box">
        <?php
        if (!$hidden)
            $this->widget('FilterPanel', array(
                'url' => 'player/recentrounds',
                    )
            );
        ?>
    </div>
</div>
<?php
if (!$hidden)
{
    ?>
    <div class="left" style="width: 100%">
        <?php
        $this->widget('zii.widgets.jui.CJuiTabs', array(
            'tabs' => array(
                'Overview' => array('ajax' => array('/player/overview', 'id' => $player->id)),
                'Marine' => array('class' => "marine", 'ajax' => array('/player/marine', 'id' => $player->id)),
                'Alien' => array('ajax' => array('/player/alien', 'id' => $player->id)),
                'Marine Commander' => array('ajax' => array('/player/marinecomm', 'id' => $player->id)),
                'Alien Commander' => array('ajax' => array('/player/aliencomm', 'id' => $player->id)),
            ),
            'options' => array(
                'cache' => true,
            ),
            'id' => 'player-tabs',
        ));
        ?>
    </div>
    <?php
} 