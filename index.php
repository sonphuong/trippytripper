<?php

// change the following paths if necessary
$yii=dirname(__FILE__).'/../yiiframework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

// remove the following line when in production mode
$domain = $_SERVER['SERVER_NAME'];
if($domain==='www.trippytripper.local')
	defined('YII_DEBUG') or define('YII_DEBUG',true);

require_once($yii);
Yii::createWebApplication($config)->run();