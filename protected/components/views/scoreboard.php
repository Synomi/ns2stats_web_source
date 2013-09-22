<?php

$page = 'http://' . $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
$sec = "15";
header("Refresh: $sec; url=$page");

$liveRound = LiveRound::model()->find(array(
    'condition' => 'server_id=:serverId',
    'params' => array(
        'serverId' => $server->id,
    ),
        ));

if ($server->country)
    $img = CHtml::image(Yii::app()->baseUrl . "/images/flags/" . strtolower($server->country) . ".png", $server->country);
else
    $img = '';

if (isset($liveRound))
{
    echo "<p>Gametime: " . gmdate("H:i:s", $liveRound->gametime) . ".</p>";
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
            'title' => 'K/D',
            'value' => 'LivePlayer::getKD($data["kills"], $data["deaths"])'
        ),
        array(
            'title' => 'K',
            'value' => '$data["kills"]'
        ),
        array(
            'title' => 'A',
            'value' => '$data["assists"]'
        ),
        array(
            'title' => 'D',
            'value' => '$data["deaths"]'
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
            'title' => 'Acc %',
            'value' => 'LivePlayer::getAccuracy($data["weapons"])'
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
}
else
{
    echo "<p>No live stats for server</p>";
}
?>