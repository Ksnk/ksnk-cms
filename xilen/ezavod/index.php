<?php
include_once('project.php');
include_once('basefolder.php');

if(isSet($_GET['url']))
	$_GET['url'] = str_replace(BASE_FOLDER.'/', '', $_GET['url']);

define('INDEX_PATH',dirname(__FILE__));
if(file_exists(INDEX_PATH.DIRECTORY_SEPARATOR.'engine'))
	define("ROOT_PATH",INDEX_PATH);
else
	define("ROOT_PATH",dirname(INDEX_PATH));
ini_set('include_path',
		ROOT_PATH.DIRECTORY_SEPARATOR.'engine'.DIRECTORY_SEPARATOR.PATH_SEPARATOR
		.ROOT_PATH.DIRECTORY_SEPARATOR.ADMIN.DIRECTORY_SEPARATOR.'engine'.DIRECTORY_SEPARATOR.PATH_SEPARATOR
		.ROOT_PATH.DIRECTORY_SEPARATOR.ADMIN.DIRECTORY_SEPARATOR.'engine'.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.PATH_SEPARATOR
		.'./'
	);

include_once('func.php');
include_once('main.php');
//echo $_GET['id'];
?>