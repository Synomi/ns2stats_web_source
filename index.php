<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('memory_limit', '256M');

// change the following paths if necessary
$yii = dirname(__FILE__) . '/../../frameworks/yii-1.1.15.022a51/framework/yii.php';

$config = dirname(__FILE__) . '/protected/config/production.php';

// remove the following lines when in production mode
if ($_SERVER['REMOTE_ADDR'] == '195.165.182.19')
    defined('YII_DEBUG') or define('YII_DEBUG', true);
else
    defined('YII_DEBUG') or define('YII_DEBUG', false);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);

require_once($yii);
//Yii::createWebApplication($config)->run();
require_once(dirname(__FILE__) . '/protected/components/Ns2stats.php');
Yii::createApplication('Ns2stats', $config)->run();
