<?php
include_once('../project.php');

define('INDEX_PATH',dirname(__FILE__));
if(file_exists(INDEX_PATH.DIRECTORY_SEPARATOR.'uploaded'))
	define("ROOT_PATH",INDEX_PATH);
else
	define("ROOT_PATH",dirname(INDEX_PATH));

define("TEMPLATE_PATH",INDEX_PATH.DIRECTORY_SEPARATOR.'templates');

ini_set('include_path',
		'.'.DIRECTORY_SEPARATOR.PATH_SEPARATOR.
		INDEX_PATH.DIRECTORY_SEPARATOR.'engine'.DIRECTORY_SEPARATOR.PATH_SEPARATOR.
		INDEX_PATH.DIRECTORY_SEPARATOR.'engine'.DIRECTORY_SEPARATOR.'plugins'.PATH_SEPARATOR.
		ROOT_PATH.DIRECTORY_SEPARATOR.'engine'.DIRECTORY_SEPARATOR.PATH_SEPARATOR.'./'
	);
define('NOCACHE',true);
include_once('func.php');
include_once('main.php');

?>