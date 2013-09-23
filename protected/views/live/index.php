<div style="margin-left:auto;margin-right:auto;width:1000px;">
    <h1 style="font-family:Verdana;font-size:20px;color:gold;">
        CURRENTLY DISABLED, due lag issues, will be enabled later.
        <?php return; ?>
    </h1>    
</div>
<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

echo "<ul id='games'>";
foreach ($liveRounds as $liveRound) {
    $server = Server::model()->findByPk($liveRound->server_id);
    if($server->country)
        $img =  CHtml::image(Yii::app()->baseUrl . "/images/flags/" . strtolower($server->country) . ".png", $server->country);
    else
        $img = '';
    echo "<li>";
    echo "<h2>$img " . CHtml::tag("a", array("title" => "View server livestats.","href" => Yii::app()->createUrl("server/server/", array("id" => $server->id))), $server->name)  . " (" . $liveRound->players . ") players " . CHtml::tag("a", array("href" => "steam://run/4920//-connect " . $server->ip . ":" . $server->port, "title" => "Connect to " . $server->name),"(Connect)") . "</h2>";
    echo "<p>Gametime: " . gmdate("H:i:s", $liveRound->gametime) . " seconds.</p>";
    $columns = array(
        array(
            'title' => '',
            'value' => '""; if($data["player"]["country"]) echo CHtml::image(Yii::app()->baseUrl . "/images/flags/" . strtolower($data["player"]["country"]) . ".png", $data["player"]["country"])',
        ),
        array(
            'title' => 'Player',
            'value' => '\'<img src="\' . $data["player"][\'steam_image\'] . \'" style="width:14px;height:14px" />
                        <a \'; if($data["player"]["commander"]) echo \' class="commander" \'; echo \' title="View \' . htmlspecialchars($data["player"][\'steam_name\']) . \' profile page" href="\' . Yii::app()->baseUrl . \'/player/player/\' . $data["player"][\'id\'] . \'">\' . htmlspecialchars($data[\'name\']) . \'</a>\''
        ),     
        array(
            'title' => 'Score',
            'value' => '$data["score"]'
        ),
        array(
            'title' => 'K',
            'value' => '$data["kills"]'
        ),
        array(
            'title' => 'D',
            'value' => '$data["deaths"]'
        ),
        array(
            'title' => 'A',
            'value' => '$data["assists"]'
        ),
        array(
            'title' => 'P.dmg',
            'value' => 'LivePlayer::getPlayerDamage($data["weapons"])'
        ),
        array(
            'title' => 'S.dmg',
            'value' => 'LivePlayer::getStructureDamage($data["weapons"])'
        ),
        array(
            'title' => 'Ping',
            'value' => '$data["ping"]'
        ),
//        array(
//            'title' => 'Acc %',
//            'value' => 'round(PlayerWeapon::getPlayerAccuracyForRound($data["prid"]), 2)'
//        ),
//        array(
//            'title' => 'Played',
//            'value' => 'Helper::secondsToTime($data["playtime"])'
//        ),
    );
    echo "<h3>Marines</h3>";
    $widget = $this->widget('StatsTable', array(
                'columns' => $columns,
                'rows' => LiveRound::getPlayersForLiveRound($liveRound->id, 1),
            ));
    echo "<h3>Aliens</h3>";
    $widget = $this->widget('StatsTable', array(
                'columns' => $columns,
                'rows' => LiveRound::getPlayersForLiveRound($liveRound->id, 2),
            ));
    echo "<h3>Ready room / spectating</h3>";
    $widget = $this->widget('StatsTable', array(
                'columns' => $columns,
                'rows' => LiveRound::getPlayersForLiveRound($liveRound->id, 5),
            ));

    echo "</li>";
}
echo "</ul>";
?>