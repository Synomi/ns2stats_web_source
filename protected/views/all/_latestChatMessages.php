<?php

$chatMessages = All::getLatestChatMessages();
if (isset($chatMessages) && count($chatMessages) > 0)
{
    ?>
    <b style="text-align: center;width: 100%;display: block;">Latest in-game chat messages</b>
    <div id="latestchat">        
        <ul>
            <?php
            foreach ($chatMessages as $chatMessage)
            {
                if ($chatMessage['hidden'] == 0)
                {
                    echo '<li>';

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
                        echo ' <span> (teamchat)';

                    echo ' : ' . htmlspecialchars($chatMessage['message']);

                    echo '</span></li>';
                }
            }
            ?>
        </ul>
    </div>
    <?php
}
?>