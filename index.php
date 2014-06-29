<?php

error_reporting(E_WARNING);
ini_set('display_errors', '1');
ini_set('memory_limit', '256M');

// change the following paths if necessary
$yii = dirname(__FILE__) . '/../framework/yii.php';

$config = dirname(__FILE__) . '/protected/config/production.php';

// remove the following lines when in production mode

defined('YII_DEBUG') or define('YII_DEBUG', false);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);

require_once($yii);
//Yii::createWebApplication($config)->run();
require_once(dirname(__FILE__) . '/protected/components/Ns2stats.php');
Yii::createApplication('Ns2stats', $config)->run();
