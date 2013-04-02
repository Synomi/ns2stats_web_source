<?php
echo CHtml::tag('h1', array(), 'Installing NS2Stats to your server');

echo CHtml::tag('h2', array(), 'To be able to see the stats from your server in ns2stats.org follow these instructions:');
?><ol><?php
echo CHtml::tag('li', array(), 'Add -mods "5fd7a38" to executable line (or your server.txt file)');
echo CHtml::tag('li', array(), 'Add "mods": ["5fd7a38"] to your MapCycle.json file');
echo CHtml::tag('li', array(), 'Run server.');
echo CHtml::tag('li', array(), 'If you see "NS2Stats setup is complete in server console", then stats have been set up on your server.');
echo CHtml::tag('li', array(), 'After running server you should have file called ns2stats_config.json file in your -config_path folder. This is main NS2Stats configuration file.');
echo CHtml::tag('li', array(), 'You can configure NS2Stats with above config file or using admin commands in sv_help.');
echo CHtml::tag('li', array(), 'Play a round of NS2 with 10 or more people in the server (not counting people in ready room and spectators)');
echo CHtml::tag('li', array(), 'Your server stats will show up on the website!');
echo CHtml::tag('li', array(), 'After this you can join to your server ingame and type sv_verify_server in console, this will make you admin of the server in http://ns2stats.org.');
?></ol><?php
    echo CHtml::tag('p', array('class' => 'alert'), 'If you are running multiple servers make sure they all use have different config_path or otherwise the stats will not be shown on the website.');

    echo CHtml::tag('p', array(), 'If you encounter any problems you can make a forum post ' . CHtml::tag('a', array('href' => 'http://www.unknownworlds.com/ns2/forums/index.php?showtopic=119419'), 'here') . ', send a Steam message to ' . CHtml::tag('a', array('href' => 'http://steamcommunity.com/profiles/76561197961466749/'), 'Zups') . ', ' . CHtml::tag('a', array('href' => 'http://steamcommunity.com/profiles/76561197960490558/'), 'Synomi') . ' or ' . CHtml::tag('a', array('href' => 'http://steamcommunity.com/id/zeikko'), 'Zeikko') . ' or send an email to zeikko@teamarchaea.net and synomi66@gmail.com');
?>
<div>
    <br />
    <h3>Setting up message of the day</h3>
    <p>To change MOTD go into ns2stats_config.json file and edit "motdLine1": "Welcome!"-part. There are total of 3 configurable lines available. You can set motdLine1 and other lines to "motdLine1": "", if you dont want to print anything.
        Using \n for extra lines is possible. If you want to change "Use 'stats' in console for commands and help"-line it can be changed in ns2stats_advanced_settings.json file.
    </p>
    <br />
    <h3>Setting up tournament mode</h3>
    <p>To enable tournament mode in server use admin command sv_tournamentmode. This toggles if tournament mode is enabled or disabled. After command use sv_ns2stats_save to make changes stay after map change.
        Another way to enable tournament mode is to edit ns2stats_config.json file and set: "tournamentMode": true.</p>
    <p style="color:orange"><br /><br/>If you want to install server only version of NS2Stats which will allow you to run NS2Stats on unmodded looking server, you can find installation instructions at <a href="http://forums.unknownworlds.com/discussion/comment/2088664/#Comment_2088664">http://forums.unknownworlds.com/discussion/comment/2088664/#Comment_2088664</a>
    Download: <a href="http://ns2stats.org/downloads/ns2stats_server_b241_v0.41.zip">B241 V0.41</a></p>
</div>
<div class="clear">

</div>
