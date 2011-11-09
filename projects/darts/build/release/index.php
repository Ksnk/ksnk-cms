<?php
// Set flag that this is a parent file
define('_DARTS',1);
header('Content-type: text/html; charset=UTF-8');
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

define('ROOT_URL',"/darts");

define("TEMPLATE_PATH",ROOT_PATH.'/templates/');

function _l($mess,$par=array()){
	static $lang=array(
		 'wrong password'=>'Неверный пароль'
		,'page not found, sorry!'=>'Страница не найдена ;-('
		,'Hello "%s"'=>'Вы авторизованы<br>как &laquo;<a href="?do=logout" 
	 onclick="if(!confirm(\'Хотите закончить сеанс?\')) return false;">%s</a>&raquo;'
		,"\n<!-- (%s) page was built for %f sec -->"=>
	 "<!-- (%s) страница генерировалась %f сек  -->"
	 	,'query'=>"запрос||а|ов"
	 );

	if(isset($lang[$mess])) $mess=$lang[$mess];

	if($par!==null)
		return vsprintf($mess,$par);
	else
		return $mess ;
}

//include_once("chkmod.php");
//ob_start('ob_mmyhandle');
//include_once("htmlopt.php");
include_once("func.php");
//include_once("templater.php");
include_once("engine.php");
include_once("syspar.php");
include_once("rights.php");
//include_once("tests.php");
//include_once("mail.php");
include_once("html.class.php");

//include_once("functions/chkmod.php");
// Подключаем библиотеку.
require_once "darts.php";
include_once("darts_News.php");
require_once "Generic.php";
	
########## Check if database exist #################################################
// Код обработчика ошибок SQL.

/*/****** point site_includes */
include_once 'compiler.class.php';
template_compiler::checktpl();/****finish point site_includes *//*
*/

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