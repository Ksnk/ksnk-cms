<?php
/**
 * $Id$
 */
//  Set flag that this is a parent file
define('_DARTS',1);
header('Content-type: text/html; charset=<%=$target_charset%>');
define ('IS_ADMIN',false);

define('INDEX_PATH',dirname(__FILE__));
if(file_exists(INDEX_PATH.DIRECTORY_SEPARATOR.'engine'))
	define("ROOT_PATH",INDEX_PATH);
else
	define("ROOT_PATH",dirname(INDEX_PATH));
	
ini_set('include_path',ini_get('include_path').PATH_SEPARATOR
		.ROOT_PATH.'/engine'.PATH_SEPARATOR
//		.'\\workspace\\common\\cms'
);

define("TEMPLATE_PATH",ROOT_PATH.DIRECTORY_SEPARATOR.'templates');

//include_once("chkmod.php");
//ob_start('ob_mmyhandle');
//include_once("htmlopt.php");
include_once("func.php");
include_once("engine/language.php");
SUPER::classes(array(
    'tournament'=>'engine/tournaments.php',
    'plugin'=>'engine/engine.php',
    'engine_Main'=>'engine/syspar.php',
    'darts_Main'=>'engine/darts.php',
    'template_compiler'=>'engine/compiler.class.php',
	'ml_plugin'=>'engine/news.php',
    'form'=>'engine/html.class.php',
    'Auth'=>'engine/rights.php',
));
SUPER::set_option(array(
    'path'=>'engine/',
    'language'=>'ru',
//    'jinja2'=>true,
));
	
########## Check if database exist #################################################
// Код обработчика ошибок SQL.

/*<% insert_point('site_includes');%>*/

// проверка - если darts.html - вешаем темплейтер
function darts_template($outp){
	// открываем языковой файл
	$data=array('lang'=>'ru');
	$data['translation']=preg_replace('~^.*<body>|</body>.*$~im','',file_get_contents('ru.html',true));
	return smart_template('xxx',$data,$outp);
//	return $output;
}
$it=pps($_GET['it']);
if (false!==strpos($it,'darts.2000.html')) ob_start('darts_template');
//require("aliaces.php");

if (false!==strpos($it,'darts.2000.html')) {
	ob_start('html_optimize');
	readfile('darts.html');
	exit;
}

// инициализация дефолтных параметров
/**
 *  Let's define plugins
 */
$engine=new darts_Main(
            'darts_Auth','darts_Tourn','darts_Players'
        );
$engine->defcat=pp($engine->user['tir'],'tir_','','*');
$_GLOBAL['engine']=&$engine;
/**
 *   Just a simple call
 */

DO_IT_ALL();
?>