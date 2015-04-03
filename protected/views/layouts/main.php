<?php
/* @var $this Controller */
$showAds = false;
//if (isset(Yii::app()->user->id))
//{
//    //check if adds need to be shown
//    $player = Player::model()->findByPk(Yii::app()->user->id);
//    if (isset($player))
//        $donation = Donation::model()->findByAttributes(array('custom' => $player->steam_id));
//
//    if (isset($donation))
//        $showAds = false;
//}

Yii::app()->clientScript->registerMetaTag('natural,selection,ns2,player,statistics,games,stats,rounds,wins,weapons,loses,lifeforms,maps', 'keywords');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="en" />

        <!-- blueprint CSS framework -->
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/grid-view.css" media="screen, projection" />
        <!--[if lt IE 8]>
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
        <![endif]-->

        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css?v9" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css?v1" />
        <meta name="msvalidate.01" content="FAE0564012F14A34B72316472197A245" />
        <link rel="icon" type="image/png" href="<?php echo Yii::app()->request->baseUrl; ?>/favicon.png" />
        <?php
        $curpage = Yii::app()->getController()->getAction()->controller->id;
        $curpage .= '/' . Yii::app()->getController()->getAction()->controller->action->id;
        if ($curpage == 'all/index')
        {
            ?>
            <link rel="canonical" href="http://ns2stats.com"/>
            <?php
        }
        ?>
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
        <script type="text/javascript">
            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', 'UA-12713891-6']);
            _gaq.push(['_trackPageview']);
            (function() {
                var ga = document.createElement('script');
                ga.type = 'text/javascript';
                ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0];
                s.parentNode.insertBefore(ga, s);
            })();
            jQuery(document).ready(function() {
                jQuery("a.timeago").timeago();
            });
        </script>
    </head>

    <body style="background-color: black; ">
        
        <img style="overflow: hidden; position: absolute;  left: 0px; top: 0px; z-index: -10" class="left" src="<?php echo Yii::app()->baseUrl; ?>/images/left.jpg" />

        <div class="container" id="page">
           
            <div class="border-relative">
                <div class="border-absolute border-left">
                    <img src="<?php echo Yii::app()->baseUrl ?>/images/border_left.png" alt="border" />
                </div>
            </div>
            <div class="border-relative">
                <div class="border-absolute border-right">
                    <img src="<?php echo Yii::app()->baseUrl ?>/images/border_right.png" alt="border" />
                    <?php
                    if ($showAds)
                    {
                        ?>
                        <div id="mainos1" style="position:absolute;z-index:-1;width: 120px;height:600px;margin-left:84px;top:200px;background-color:transparent">
                            <script type="text/javascript"><!--
                                google_ad_client = "ca-pub-5477320731266931";
                                /* ns2stats1pysty */
                                google_ad_slot = "4796074660";
                                google_ad_width = 120;
                                google_ad_height = 600;
                                //-->
                            </script>
                            <script type="text/javascript"
                                    src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
                            </script>

                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <div style=" background: #0A0D17; width: 1230px; z-index: 99999999999">
                
                <div id="header" style="position: relative">                     
                    
                    <img id="header-animation" src="<?php echo Yii::app()->baseUrl ?>/images/header.gif" alt="header" />

                    <div class="menu" id="mainmenu">
                        <?php
                        if (Yii::app()->user->isGuest)
                            echo CHtml::tag('a', array('id' => 'steam-login', 'class' => 'right', 'href' => $this->createUrl('/site/steamlogin')), CHtml::tag('img', array('src' => Yii::app()->baseUrl . '/images/steam_login.png', 'alt' => 'login')));
                        else
                        {
                            echo CHtml::tag('a', array('id' => 'steam-login', 'class' => 'right', 'href' => $this->createUrl('/site/logout')), CHtml::tag('div', array('class' => 'header-button header-button1', 'alt' => ''), 'Logout'));
                        }

                        $this->widget('zii.widgets.CMenu', array(
                            'encodeLabel' => false,
                            'items' => array(
                                array('label' => CHtml::tag('div', array('class' => 'header-button header-button-left', 'alt' => ''), 'Home'), 'url' => array('/all/index')),
                                array('label' => CHtml::tag('div', array('class' => 'header-button header-button2', 'alt' => ''), 'Rounds'), 'url' => array('/round/rounds')),
                                array('label' => CHtml::tag('div', array('class' => 'header-button header-button1', 'alt' => ''), 'Rankings'), 'url' => array('/ranking/index')),
                                array('label' => CHtml::tag('div', array('class' => 'header-button header-button2', 'alt' => ''), 'Servers'), 'url' => array('/server/index')),
                                array('label' => CHtml::tag('div', array('class' => 'header-button header-button1', 'alt' => ''), 'Maps'), 'url' => array('/map/index')),
                                array('label' => CHtml::tag('div', array('class' => 'header-button header-button2', 'alt' => ''), 'About'), 'url' => array('/site/about')),
                                array('label' => CHtml::tag('div', array('class' => 'header-button header-button1', 'alt' => ''), 'Installation'), 'url' => array('/site/install')),
                                array('label' => CHtml::tag('div', array('class' => 'header-button header-button2', 'alt' => ''), 'My Account'), 'url' => array('/player/account'), 'visible' => !Yii::app()->user->isGuest),
                            ),
                        ));
                        ?>
                    </div><!-- mainmenu -->
                </div><!-- header -->            
            </div>
            <?php if (isset($this->breadcrumbs)): ?>
                <?php
                $this->widget('zii.widgets.CBreadcrumbs', array(
                    'links' => $this->breadcrumbs,
                ));
                ?><!-- breadcrumbs -->
            <?php endif ?>

            <?php echo $content; ?>

            <div class="clear"></div>

            <div id="footer">
                <div>
                    <?php
                    if (false)
                    {
                        $cacheId = "playingCache";
                        if ($this->beginCache($cacheId, array('duration' => 200)))
                        {
                            $players = Player::getCurrentActivePlayers();
                            ?>
                            <p> Currently <?php echo count($players) ?> players are playing in ns2stats enabled servers:
                                <?php
                                foreach ($players as $player)
                                {
                                    if (isset($player->last_server_id) && is_numeric($player->last_server_id))
                                        $currentServer = Server::model()->findByAttributes(array('id' => $player->last_server_id));


                                    if (isset($currentServer))
                                    {
                                        if (!isset($currentServer->ip))
                                            $currentServer->ip = "n/a";

                                        echo "[<a style='text-decoration:none;color:gold' href='" . Yii::app()->baseUrl . '/player/player/' . $player->id . "' title='View profile'" .
                                        ">" . htmlspecialchars($player->steam_name) . "</a> " .
                                        CHtml::tag("a", array("style" => "text-decoration:none;", "href" => "steam://run/4920//-connect "
                                            . $currentServer->ip
                                            . ":" . $currentServer->port,
                                            "title" => "Join where "
                                            . htmlspecialchars($player->steam_name)
                                            . " is playing. ("
                                            . htmlspecialchars($currentServer->name)
                                            . ")"), "(join)") . "] ";
                                    }
                                }
                                $this->endCache();
                            }
                        }
                        ?>
                    </p>
                </div>
                <?php
                if ($showAds)
                {
                    ?>
                    <div id="mainos2" style="z-index:-1;width: 728px;height:90px;margin-left:auto;margin-right:auto;background-color:transparent;">
                        <script type="text/javascript"><!--
                            google_ad_client = "ca-pub-5477320731266931";
                            /* ns2statsvaaka */
                            google_ad_slot = "1703007462";
                            google_ad_width = 728;
                            google_ad_height = 90;
                            //-->
                        </script>
                        <script type="text/javascript"
                                src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
                        </script>

                    </div>
                    <?php
                }
                ?>
                
                <?php echo CHtml::tag('a', array('href' => 'http://www.naturalselection2.com/'), 'Natural Selection 2'); ?>
                <?php echo CHtml::tag('a', array('href' => 'http://hive.naturalselection2.com/'), 'HIVE'); ?>
                <?php echo CHtml::tag('a', array('href' => 'http://www.ensl.org/'), 'NSL'); ?>
                <?php echo CHtml::tag('a', array('href' => 'http://steamcommunity.com/app/4920'), 'NS2 Steam community'); ?>
                <?php echo CHtml::tag('a', array('href' => 'http://steampowered.com'), 'Powered by Steam'); ?>
            </div><!-- footer -->


        </div><!-- page -->
        <img style="overflow: hidden; position: absolute; right: 0px; top: 0px; z-index: -10" src="<?php echo Yii::app()->baseUrl; ?>/images/right.jpg" />
        <script type="text/javascript">
            var uvOptions = {};
            (function() {
                var uv = document.createElement('script');
                uv.type = 'text/javascript';
                uv.async = true;
                uv.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'widget.uservoice.com/JIfZCFvztLsVDCWWwN2FA.js';
                var s = document.getElementsByTagName('script')[0];
                s.parentNode.insertBefore(uv, s);
            })();
        </script>
    </body>
</html>
