<script type="text/javascript">
    $(document).ready(function() {
        calculateTotals();
    });
</script>
<?php
$this->pageTitle = 'Game played in server ' . $round->server->name . ' in ' . date('d.m.Y H:i:s', $round->added) . ' (GMT+2) - NS2Stats';

?>
<div style="padding-left:1em;padding-top:0.5em">
    <?php
    //TODO CSS should be moved to external css files
    $mapname = Round::getMapNameByRoundId($round->id);
    $map = Map::model()->findByAttributes(array('name' => $mapname));
    echo CHtml::tag('div', array("style" => "height:128px;"), CHtml::tag('div', array("style" => "display:inline-block;width:1034px; vertical-align: top;"), CHtml::tag('h1', array(), 'Round played <a href="#" class="timeago" title="' . date("c", $round->added) . '" style="color:white;text-decoration:none"></a>' . ' @ ' . CHtml::link($round->server->name, array('server/server', 'id' => $round->server_id)))
                    . CHtml::tag('h4', array(), "Round duration " . Helper::secondsToTime($round->end - $round->start) . ".")
                    . CHtml::tag('h4', array(), "Map: " . $mapname))
            . CHtml::tag('div', array("style" => "margin-top:-10Yii::app()->baseUrl . px;width:128px;display:inline-block;background-color:black;border-radius:20px"), CHtml::tag('img', array("src" => Yii::app()->baseUrl . "/images/minimaps/$mapname.png",
                        "style" => "width:128px;height:128px;", "alt" => "No image available."))
    ));


    $winnerSpan = '<span style="color:gold;font-weight:bold;">';
    $marines = ($round->winner == 1) ? 'Marines ' . $winnerSpan . 'Winner</span>' : "Marines";
    $aliens = ($round->winner == 2) ? 'Aliens ' . $winnerSpan . 'Winner</span>' : "Aliens";
    ?>
</div>
<?php
if ($round->parse_status < 3 && $round->parse_status > 0)
{
    ?>
    <div class="loadingText">
        <?php
        echo CHtml::tag('h2', array(), 'Round data is still being processed. Scoreboard will appear in few of seconds. Please wait.');
        echo CHtml::image('/images/loading.gif', 'loading');
        ?>
    </div>
    <?php
}
if ($round->parse_status >= 3 || $round->parse_status == 0)
{
    ?>
    <div class="span-15">
        <div class="content-box">
            <?php
            echo CHtml::tag('h2', array(), $marines);


            $columns = array(
                array(
                    'title' => '',
                    'value' => '""; 
                    if($data["country"] && $data["hidden"]==0) 
                        echo CHtml::image(Yii::app()->baseUrl . "/images/flags/" . strtolower($data["country"]) . ".png", $data["country"])',
                ),
                array(
                    'title' => 'Player',
                    'value' => '\'\';
                    if ($data["hidden"])
                    {
                        echo htmlspecialchars("<hidden>");
                    }
                    else 
                    {
                        echo \'<img src="\' . $data[\'steam_image\'] . \'" style="width:14px;height:14px" /><a \'; 
                            if($data["commander"])
                                echo \' class="commander" \';

                        echo 
                            \' 
                                title="View \' . htmlspecialchars($data[\'steam_name\']) . \' profile page" 
                                href="\' . Yii::app()->baseUrl . \'/player/player/\' . $data[\'id\'] . \'">
                                \' . htmlspecialchars($data[\'name\'])
                            . \'
                            </a>\';
                    }'
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
                    'value' => 'PlayerWeapon::getPlayerDamageForRound($data["prid"], 1)'
                ),
                array(
                    'title' => 'S.dmg',
                    'value' => 'PlayerWeapon::getPlayerDamageForRound($data["prid"], 2)'
                ),
                array(
                    'title' => 'Acc %',
                    'value' => 'round(PlayerWeapon::getPlayerAccuracyForRound($data["prid"]), 2)'
                ),
                array(
                    'title' => 'Played',
                    'value' => 'Helper::secondsToTime($data["playtime"])'
                ),
            );

            $widget = $this->widget('StatsTable', array(
                'columns' => $columns,
                'rows' => Round::getPlayersFromRound($round->id, 1),
            ));
            ?>
        </div>
    </div>
    <div class="span-15 last">
        <div class="content-box">
            <?php
            echo CHtml::tag('h2', array(), $aliens);

            $widget = $this->widget('StatsTable', array(
                'columns' => $columns,
                'rows' => Round::getPlayersFromRound($round->id, 2),
            ));
            ?>


        </div>
    </div>
    <div class="clear">
    </div>
    <?php
}
if ($round->parse_status == 3)
{
    ?>
    <div class="loadingText">
        <?php
        echo CHtml::tag('h2', array(), 'Round data is still being processed. More data will appear in few minutes. Please wait.');
        echo CHtml::image('/images/loading.gif', 'loading');
        ?>
    </div>
    <?php
}
if ($round->parse_status >= 4 || $round->parse_status == 0)
{
    $cacheId = "roundCache" . $round->id;

    if ($this->beginCache($cacheId, array('duration' => 1))) //TODO CHANGE
    {
        ?>
<!--        <div class="wide">
            <div class="marine_commander half">
                <?php
                $columnsArray = array('id', 'name', 'lastname', 'tel', 'email');
                $rowsArray = array(
                    array(1, 'Jose', 'Rullan', '123-123-1234', 'jose@email.com'),
                    array(2, 'Fred', 'Frederick', '123-123-1234', 'fred@email.com'),
                    array(3, 'Paul', 'Horstmann', '123-123-1234', 'phor@email.com'),
                    array(4, 'Kim', 'Guptha', '123-123-1234', 'kgup@email.com'),
                    array(5, 'Fred', 'Frederick', '123-123-1234', 'fred@email.com'),
                    array(6, 'Querty', 'Uiop', '123-123-1234', 'querty@email.com'),
                    array(7, 'Albert', 'Febensburg', '123-123-1234', 'a@email.com'),
                    array(8, 'Dan', 'Sieg', '123-123-1234', 'da@email.com'),
                    array(9, 'Janice', 'Breyfogle', '123-123-1234', 'janice@email.com'),
                    array(10, 'Cornelious', 'Ape', '123-123-1234', 'potapes@email.com'),
                );

//                $this->widget('ext.htmltableui.htmlTableUi', array(
//                    'collapsed' => true,
//                    'enableSort' => true,
//                    'title' => 'View marine commander statistics',
//                    'subtitle' => 'Rev 1.3.3',
//                    'columns' => $columnsArray,
//                    'rows' => $rowsArray,
//                    'footer' => 'Total rows: ' . count($rowsArray) . ' By: José Rullán'
//                ));
                ?>
            </div>
            <div class="alien_commander half">
                <?php
                $columnsArray = array('id', 'name', 'lastname', 'tel', 'email');
                $rowsArray = array(
                    array(1, 'Jose', 'Rullan', '123-123-1234', 'jose@email.com'),
                    array(2, 'Fred', 'Frederick', '123-123-1234', 'fred@email.com'),
                    array(3, 'Paul', 'Horstmann', '123-123-1234', 'phor@email.com'),
                    array(4, 'Kim', 'Guptha', '123-123-1234', 'kgup@email.com'),
                    array(5, 'Fred', 'Frederick', '123-123-1234', 'fred@email.com'),
                    array(6, 'Querty', 'Uiop', '123-123-1234', 'querty@email.com'),
                    array(7, 'Albert', 'Febensburg', '123-123-1234', 'a@email.com'),
                    array(8, 'Dan', 'Sieg', '123-123-1234', 'da@email.com'),
                    array(9, 'Janice', 'Breyfogle', '123-123-1234', 'janice@email.com'),
                    array(10, 'Cornelious', 'Ape', '123-123-1234', 'potapes@email.com'),
                );

//                $this->widget('ext.htmltableui.htmlTableUi', array(
//                    'collapsed' => true,
//                    'enableSort' => true,
//                    'title' => 'View alien commander statistics',
//                    'subtitle' => 'Rev 1.3.3',
//                    'columns' => $columnsArray,
//                    'rows' => $rowsArray,
//                    'footer' => 'Total rows: ' . count($rowsArray) . ' By: José Rullán'
//                ));
                ?>
            </div>
        </div>-->
        <div class="span-30">
            <div class="content-box">
                <?php
                $widget = $this->widget('Highchart', array(
                    'type' => 'area',
                    'tooltipFormatter' => "function() { if(typeof this.point.icon != 'undefined') return '<img class=\"icon\" src=\"' + this.point.icon + '\" />' + '<div>' + secondsToTime(this.x) + ': ' + this.point.text + '</div>'; }",
                    'xAxisLabelFormatter' => "function() { return secondsToTime(this.value); }",
                    'options' => array(
                        'url' => $this->createUrl('round/timeline', array('id' => $round->id)),
                        'title' => array(
                            'text' => 'Timeline',
                            'style' => array(
                                'color' => '#FFF',
                            ),
                        ),
                        'yAxis' => array(
                            'title' => array(
                                'text' => 'Resources Used on Tech and Buildings',
                                'style' => array(
                                    'color' => '#FFF',
                                ),
                            ),
                        ),
                        'xAxis' => array(
                            'title' => array(
                                'text' => 'Round Time',
                                'style' => array(
                                    'color' => '#FFF',
                                ),
                            ),
                        ),
                        'legend' => array(
                            'itemStyle' => array(
                                'color' => '#FFF',
                            ),
                        ),
                        'plotOptions' => array(
                            'area' => array(
                                'marker' => array(
                                    'enabled' => false,
                                )
                            ),
                        ),
                    ),
                ));
                $widget->renderContent();
                ?>
            </div>
        </div>
        <?php
        if ($round->end > strtotime('1.9.2012'))
        {
            ?>
            <div class="span-10">
                <?php
                $widget = $this->widget('Highchart', array(
                    'type' => 'area',
                    'xAxisLabelFormatter' => "function() { return secondsToTime(this.value); }",
                    'options' => array(
                        'url' => $this->createUrl('round/rtcountline', array('id' => $round->id)),
                        'title' => array(
                            'text' => 'Resource Towers',
                            'style' => array(
                                'color' => '#FFF',
                            ),
                        ),
                        'yAxis' => array(
                            'title' => array(
                                'text' => 'Number of Resource Towers',
                                'style' => array(
                                    'color' => '#FFF',
                                ),
                            ),
                        ),
                        'xAxis' => array(
                            'title' => array(
                                'text' => 'Round Time',
                                'style' => array(
                                    'color' => '#FFF',
                                ),
                            ),
                        ),
                        'legend' => array(
                            'itemStyle' => array(
                                'color' => '#FFF',
                            ),
                        ),
                    ),
                ));
                $widget->renderContent();
                ?>
            </div>
        <?php } ?>
        <div class="span-10">
            <?php
            $widget = $this->widget('Highchart', array(
                'type' => 'area',
                'xAxisLabelFormatter' => "function() { return secondsToTime(this.value); }",
                'options' => array(
                    'url' => $this->createUrl('round/resourcescountline', array('id' => $round->id)),
                    'title' => array(
                        'text' => 'Resources',
                        'style' => array(
                            'color' => '#FFF',
                        ),
                    ),
                    'yAxis' => array(
                        'title' => array(
                            'text' => 'Resources Gathered',
                            'style' => array(
                                'color' => '#FFF',
                            ),
                        ),
                    ),
                    'xAxis' => array(
                        'title' => array(
                            'text' => 'Round Time',
                            'style' => array(
                                'color' => '#FFF',
                            ),
                        ),
                    ),
                    'legend' => array(
                        'itemStyle' => array(
                            'color' => '#FFF',
                        ),
                    ),
                ),
            ));
            $widget->renderContent();
            ?>
        </div>
        <div class="span-10 last">
            <?php
            $widget = $this->widget('Highchart', array(
                'type' => 'area',
                'xAxisLabelFormatter' => "function() { return secondsToTime(this.value); }",
                'options' => array(
                    'url' => $this->createUrl('round/killcountline', array('id' => $round->id)),
                    'title' => array(
                        'text' => 'Kills',
                        'style' => array(
                            'color' => '#FFF',
                        ),
                    ),
                    'yAxis' => array(
                        'title' => array(
                            'text' => 'Kills',
                            'style' => array(
                                'color' => '#FFF',
                            ),
                        ),
                    ),
                    'xAxis' => array(
                        'title' => array(
                            'text' => 'Round Time',
                            'style' => array(
                                'color' => '#FFF',
                            ),
                        ),
                    ),
                    'legend' => array(
                        'itemStyle' => array(
                            'color' => '#FFF',
                        ),
                    ),
                ),
            ));
            $widget->renderContent();
            ?>
        </div>
        <?php
        if (isset($map) && isset($map->jsonvalues))
        {
            $minimap = $this->widget('Minimap', array(
                'map' => $map,
                'round' => $round,
            ));
        }
        else
        {
            echo "<p>Minimap for $mapname is not yet available.</p>";
        }
        $this->renderPartial('roundScript');

        /*
         * Chat messages         
         */

        $chatMessages = Round::getChatMessagesForRound($round->id);
        if (isset($chatMessages) && count($chatMessages) > 0)
        {
            ?>
            <div id="chatbox">
                <h3>Chat</h3>
                <ul>
                    <?php
                    foreach ($chatMessages as $chatMessage)
                    {
                        if ($chatMessage['hidden'] == 0)
                        {
                            echo '<li>';
                            echo '<img title="Said in ' . $chatMessage['gametime'] . ' seconds." src="' . $chatMessage['steam_image'] . '" style="width:24px;height:24px" />';
                            echo '<span>';
                            //echo CHtml::image(Yii::app()->baseUrl . "/images/flags/" . strtolower($chatMessage["country"]) . ".png", $chatMessage["country"]);
                            if ($chatMessage['team'] == 1)
                                echo '<span style="font-weight:bold;color:#0066A4;">';
                            else if ($chatMessage['team'] == 2)
                                echo '<span style="font-weight:bold;color:gold;">';
                            else
                                echo '<span style="font-weight:bold;color:white;">';
                            $link = '<a style="color:inherit;" title="View ' . htmlspecialchars($chatMessage['steam_name']) . ' profile page" href="' . Yii::app()->baseUrl . '/player/player/' . $chatMessage['prid'] . '">';
                            echo $link . htmlspecialchars($chatMessage['player_name']) . '</a></span>';

                            if ($chatMessage['to_team'] == 1)
                                echo ' (teamchat)';

                            echo ' : ' . htmlspecialchars($chatMessage['message']);
                            echo '</span>';
                            echo '</li>';
                        }
                    }
                    ?>
                </ul>
                <p style="font-size:10px;padding-left:20px;">Mouseovering the portrait shows when message was said.</p>
            </div>
            <?php
        }
        $this->endCache();
    }
}
?>



