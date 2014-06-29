<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'defaultController' => 'all/index',
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'NS2 Stats',
    // preloading 'log' component
    'preload' => array('log'),
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
    ),
    'modules' => array(
        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => 'abc123',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
//            'ipFilters' => array('79.85.107.49', '::1'),
            'ipFilters' => array('86.69.15.244', '::1'),
        ),
    ),
    // application components
    'components' => array(
        'cache' => array(
            'class' => 'system.caching.CApcCache',
        ),
        'request' => array(
            'baseUrl' => '',
        ),
        'user' => array(
// enable cookie-based authentication
            'allowAutoLogin' => true,
            'class' => 'WebUser', //use custom user class, extended
        ),
        // uncomment the following to enable URLs in path-format
        'urlManager' => array(
            'showScriptName' => false,
            'urlFormat' => 'path',
            'rules' => array(
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ),
        'db' => array(
            'connectionString' => 'mysql:host=localhost;dbname=tocome',
            'emulatePrepare' => true,
            'username' => 'tocome',
            'password' => 'tocome',
            'charset' => 'utf8',
            'enableProfiling'=>true,
//            'tablePrefix' => 'test',
        ),
        'session' => array(
            'sessionName' => 'ns2stats',
            'class' => 'CDbHttpSession',
            'autoCreateSessionTable' => false,
            'connectionID' => 'db',
            'sessionTableName' => 'YiiSession',
            'autoStart' => 'false',
            'cookieMode' => 'only',
            'timeout' => 86400 * 7,
        ),
        'errorHandler' => array(
// use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning, info',
                ),
                'file' => array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning, watch',
                    'categories' => 'system.*',
                ),
                'profile' => array(
                    'class' => 'CProfileLogRoute',
                    'report' => 'summary',
                ),
//              array(
//              'class'=>'CWebLogRoute',
//              ),
            ),
        ),
        //Light open id for steam login
        'loid' => array(
            'class' => 'application.extensions.lightopenid.loid',
        ),        
        'widgetFactory' => array(
            'widgets' => array(
                'CGridView' => array(
                    'cssFile' => (strlen(dirname($_SERVER['SCRIPT_NAME'])) > 1 ? dirname($_SERVER['SCRIPT_NAME']) : '' ) . '/css/grid-view.css',
                ),
            ),
        ),
    ),
    // application-level parameters that can be accessed
// using Yii::app()->params['paramName']
    'params' => array(
        'steamApiKey' => '',
        'currentStatsVersion' => '0.42',
        'minimumStatsVersion' => 0.37,
        'logDirectory' => 'protected/data/round-logs/',
        'defaultDescription' => 'NS2Stats offers wide variety of statistics for players of PC game Natural Selection 2. NS2Stats includes ELO ranking, build orders, and various other player, mod, map, server and round statistics.'
    ),
);
