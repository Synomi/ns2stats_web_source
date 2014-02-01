<?php
$this->pageTitle = 'NS2Stats - Statistics for Natural Selection 2 PC Game';
?>
<script type="text/javascript" >
    var filterpanels = new Array();
    function checkKey(event) {
        if (event.keyCode == 13)
        {
            loadPlayers();
            return false;
        }
        else
            return true;
    }

    var previousInput = '';
    function loadPlayers() {
        if ($('#s').val() != previousInput) {
            var i = 0;
            if (filterpanels[i].request)
                filterpanels[i].request.abort()
            $("#" + filterpanels[i].id).html('<img class="loading" src="<?php echo Yii::app()->baseUrl ?>/images/loading.gif" alt="loading" />');
            filterpanels[i].request = $.ajax({
                url: filterpanels[i].url,
                data: $('#search-form').serialize(),
                type: 'POST',
                success: function(result, textStatus, jqXHR) {
                    $('#players').show();
                    for (i in filterpanels) {
                        filterpanels[i] = filterpanels[i];
                        if (filterpanels[i].url == this.url) {
                            $("#" + filterpanels[i].id).html(result);
                        }
                    }
                    jQuery("#" + filterpanels[i].id + " a.timeago").timeago();
                }
            });

        }
        previousInput = $('#s').val();
    }
    function loadChatMessages()
    {
        $("#ajaxchat").load("/all/latestchatmessages");
    }
    $(document).ready(function() {
        window.setInterval("loadChatMessages()", 60000);

    });

</script>
<div style="padding:30px;">
    <p style="font-size: 12px;"><span style="color:gold;font-weight: bold;">NS2Stats.com dynamic signatures are now available!</span> To create yourself a signature go to <a href="http://ns2stats.com/player/signature">Signature page</a> and press create signature. More info at <a href="http://forums.unknownworlds.com/discussion/comment/2182858/#Comment_2182858">UWE forums</a>.<br />Example:</p>
    <a href="http://ns2stats.com/player/signature"><img style="width: 300px;height: auto;" src="http://ns2stats.com/player/getPlayerSignature/55" /></a>
</div>
<!--
<div id="featuredserverscontainer" style="clear:both;width:1200px;margin-left:auto;margin-right:auto;">

    <h2 style="padding-left:40px;color:gold;"> <a href="http://www.ensl.org">ENSL</a> custom map cup, featured servers</h2>
<?php
/*
  echo '<div class="featuredservers">';
  $server = Server::model()->findByPk(2303);
  $widget = $this->widget('Livestats', array(
  'server' => $server,
  ));
  echo "</div>";
  echo '<div class="featuredservers">';
  $server = Server::model()->findByPk(3248);
  $widget = $this->widget('Livestats', array(
  'server' => $server,
  ));
  echo "</div>";
  echo '<div class="featuredservers">';
  $server = Server::model()->findByPk(552);
  $widget = $this->widget('Livestats', array(
  'server' => $server,
  ));
  echo "</div>";
 */
?>

</div>-->


<div class="content-box">
    <?php
    echo CHtml::beginForm('', 'post', array(
        'id' => 'search-form',
        'style' => 'margin-left:10px;'
    ));
    echo CHtml::label('Search Player by steam name', 's', array('class' => 'label'));
    echo CHtml::textField('s', '', array('onkeypress' => 'return checkKey(event)'));
    echo CHtml::endForm();
    ?>
    <div id="players" style="display: none;">
        <?php
        $this->widget('FilterPanel', array(
            'url' => 'player/playerlist',
                )
        );
        ?>
    </div>
</div>
<?
$this->widget('FilterForm', array(
    'servers' => All::getServers(),
    'builds' => All::getBuilds(),
    'mods' => All::getMods(),
    'filter' => $filter,
));
?>
<div class="span-10">
    <?php
    $widget = $this->widget('Highchart', array(
        'type' => 'line',
        'options' => array(
            'url' => $this->createUrl('all/roundsplayedline'),
            'title' => array(
                'text' => 'Rounds Played',
                'style' => array(
                    'color' => '#FFF',
                ),
            ),
            'yAxis' => array(
                'title' => array(
                    'text' => 'Rounds',
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
        )
    ));
    $widget->renderContent();
    ?>
</div>
<div class="span-10">    
    <div id="ajaxchat" style="width: 380px;height:360px;margin:5px;margin-top: 20px;margin-bottom: 30px;overflow-y: auto;">
        <?php
        $this->renderPartial('/all/_latestChatMessages');
        ?>
    </div>
    <?php
    $widget = $this->widget('Highchart', array(
        'type' => 'line',
        'options' => array(
            'url' => $this->createUrl('all/playersline'),
            'title' => array(
                'text' => 'Players',
                'style' => array(
                    'color' => '#FFF',
                ),
            ),
            'yAxis' => array(
                'title' => array(
                    'text' => 'Players',
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
        )
    ));
    // $widget->renderContent();
    ?>
</div>
<div class="span-10 last">
    <div class="box">
        <?php
        $this->widget('FilterPanel', array(
            'url' => 'all/recentrounds',
                )
        );
        ?>
    </div>
</div>
<div class="span-10 overview-chart">
    <?php
    $widget = $this->widget('Highchart', array(
        'type' => 'column',
        'options' => array(
            'url' => $this->createUrl('all/roundresultslengthcolumn'),
            'title' => array(
                'text' => 'Wins By Round Length',
                'style' => array(
                    'color' => '#FFF',
                ),
            ),
            'legend' => array(
                'itemStyle' => array(
                    'color' => '#FFF',
                ),
            ),
            'plotOptions' => array(
                'column' => array(
                    'stacking' => 'percent',
                ),
            ),
        ),
        'tooltipFormatter' => "function() { return this.x + '<br />' + this.series.name + ' :<br />' + Math.round(this.percentage, 2) +'% (' + this.y + ')'; }",
    ));
    $widget->renderContent();
    ?>
</div>
<div class="span-10 overview-chart">
    <?php
    $widget = $this->widget('Highchart', array(
        'type' => 'column',
        'options' => array(
            'url' => $this->createUrl('all/roundlengthcolumn'),
            'title' => array(
                'text' => 'Round Lengths',
                'style' => array(
                    'color' => '#FFF',
                ),
            ),
            'legend' => array(
                'itemStyle' => array(
                    'color' => '#FFF',
                ),
            ),
            'plotOptions' => array(
                'column' => array(
                    'stacking' => 'normal',
                ),
            ),
        ),
        'tooltipFormatter' => "function() { return this.x + '<br />' + Math.round(this.percentage, 2) +'% (' + this.y + ')'; }",
    ));
    $widget->renderContent();
    ?>
</div>
<div class="span-5">
    <?php
    $widget = $this->widget('Highchart', array(
        'type' => 'pie',
        'options' => array(
            'url' => $this->createUrl('all/roundresultspie'),
            'title' => array(
                'text' => 'Wins',
                'style' => array(
                    'color' => '#FFF',
                ),
            ),
            'legend' => array(
                'itemStyle' => array(
                    'color' => '#FFF',
                ),
            ),
        )
    ));
    $widget->renderContent();
    ?>
</div> 
<div class="span-5 last">
    <?php
    $widget = $this->widget('Highchart', array(
        'type' => 'pie',
        'options' => array(
            'url' => $this->createUrl('all/mapspie'),
            'title' => array(
                'text' => 'Maps',
                'style' => array(
                    'color' => '#FFF',
                ),
            ),
            'legend' => array(
                'itemStyle' => array(
                    'color' => '#FFF',
                ),
            ),
        )
    ));
    $widget->renderContent();
    ?>
</div>
<div class="span-5">
    <?php
    $widget = $this->widget('Highchart', array(
        'type' => 'pie',
        'options' => array(
            'url' => $this->createUrl('all/playernationalitiespie'),
            'title' => array(
                'text' => 'Player Nationalities',
                'style' => array(
                    'color' => '#FFF',
                ),
            ),
            'legend' => array(
                'itemStyle' => array(
                    'color' => '#FFF',
                ),
            ),
        )
    ));
    $widget->renderContent();
    ?>
</div>
<div style="clear:both;width:980px;margin-left:auto;margin-right:auto;">
    <p style="color:white;font-size:11px;padding-top:20px;padding-left:10px;">
        Currently along with other data we have
        <?php
        $sql = "SELECT id as players FROM player ORDER BY id DESC limit 1";
        $command = Yii::app()->db->createCommand($sql);
        $results = $command->queryAll();
        if (isset($results) && isset($results[0]["players"]))
        {
            $players = (int) $results[0]["players"];
            echo $players . " players, ";
        }

        $sql = "SELECT id as deaths FROM death ORDER BY id DESC limit 1";
        $command = Yii::app()->db->createCommand($sql);
        $results = $command->queryAll();
        if (isset($results[0]["deaths"]))
        {
            $deaths = (int) $results[0]["deaths"];
            echo $deaths . " deaths, ";
        }

        $sql = "SELECT id as hits FROM hit ORDER BY id DESC limit 1";
        $command = Yii::app()->db->createCommand($sql);
        $results = $command->queryAll();
        if (isset($results[0]["hits"]))
        {
            $hits = (int) $results[0]["hits"];
            echo $hits . " damage trades, ";
        }
        $sql = "SELECT id as rounds FROM round ORDER BY id DESC limit 1";
        $command = Yii::app()->db->createCommand($sql);
        $results = $command->queryAll();
        if (isset($results[0]["rounds"]))
        {
            $rounds = (int) $results[0]["rounds"];
            echo $rounds . " rounds, ";
        }
        $sql = "SELECT id as resources FROM resources ORDER BY id DESC limit 1";
        $command = Yii::app()->db->createCommand($sql);
        $results = $command->queryAll();
        if (isset($results[0]["resources"]))
        {
            $resources = (int) $results[0]["resources"];
            echo $resources . " resource gains and ";
        }
        $sql = "SELECT id as pickups FROM pickable ORDER BY id DESC limit 1";
        $command = Yii::app()->db->createCommand($sql);
        $results = $command->queryAll();
        if (isset($results[0]["pickups"]))
        {
            $pickups = (int) $results[0]["pickups"];
            echo $pickups . " dropped pick-up-ables";
        }
        ?>
        in our database. 
    </p>
</div>

