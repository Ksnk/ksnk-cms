<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ksnk
 * Date: 12.04.11
 * Time: 20:55
 * To change this template use File | Settings | File Templates.
 */
 /**
  * индексный файл для проверки и отладки объекта доступа к данным
  */
$yii="/home/localhost/www/yii/framework/yii.php";
$config='config.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($yii);
/** @define "null" "VALUE" */
include '../CMultyData.php';

Yii::createConsoleApplication($config);
$data= new CMultyData();

echo($data->readRecord('version'));