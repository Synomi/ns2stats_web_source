<?php
//die('maintenance');
ini_set('memory_limit', '150M');
 error_reporting(E_ALL);
    ini_set('display_errors', '1');

// change the following paths if necessary
$yii = dirname(__FILE__) . '/../frameworks/yii-1.1.15.022a51/framework/yii.php';

$config = dirname(__FILE__) . '/protected/config/production.php';

// remove the following lines when in production mode
if ($_SERVER['REMOTE_ADDR'] == '85.23.173.32' || (isset($_GET['enable_debug']) && $_GET['enable_debug'] == 1)) {
    defined('YII_DEBUG') or define('YII_DEBUG', true);   
} else
    defined('YII_DEBUG') or define('YII_DEBUG', false);

// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);

require_once($yii);
//Yii::createWebApplication($config)->run();
require_once(dirname(__FILE__) . '/protected/components/Ns2stats.php');
Yii::createApplication('Ns2stats', $config)->run();
