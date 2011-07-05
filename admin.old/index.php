<?php
include_once('../project.php');

define('INDEX_PATH',str_replace('\\', '/', dirname(__FILE__)));
if(file_exists(INDEX_PATH.'/uploaded'))
	define("ROOT_PATH",INDEX_PATH);
else
	define("ROOT_PATH",dirname(INDEX_PATH));

$x=explode(
     str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT'])
    ,str_replace('\\', '/', ROOT_PATH)
);
if(isset($x[1]))
    define('BASE_FOLDER',trim($x[1],'/'));
else
    define('BASE_FOLDER',INDEX_PATH);

define("TEMPLATE_PATH",INDEX_PATH.'/templates');

ini_set('include_path',
        './'.PATH_SEPARATOR
		.INDEX_PATH.'/engine/'.PATH_SEPARATOR
		.INDEX_PATH.'/engine/plugins/'.PATH_SEPARATOR
        .ROOT_PATH.'/engine/'.PATH_SEPARATOR
		.'./'
	);

define('NOCACHE',true);
include_once('func.php');
include_once('main.php');

