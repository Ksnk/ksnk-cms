<?php
/**
 * Собрание всяких полезных и не очень функций, засоряющих начала файлов index и main  
 */
/**
 * замер времени выполнения
 */
if(version_compare("5.0.0", phpversion() , "<")){
function mkt($store=false){
	static $tm ;  $x = microtime(true);
	if($store)$tm=$x;
	return $x-$tm ;
} ; 
}
else {
function mkt($store=false){
	static $tm ;  list($usec, $sec) = explode(" ", microtime());
	if($store)$tm=(float)$usec + (float)$sec; // microtime(1) ;
	return ((float)$usec + (float)$sec)-$tm ;
} ; 
}
mkt(true);

include_once(ROOT_PATH.'/engine/hosts.php');

$connect="mysql://$the_login:$the_pass@$the_host/$the_db";
$db_setup='set NAMES "cp1251";';
if(!defined('TEMPLATE_PATH')){
	define("TEMPLATE_PATH",ROOT_PATH.DIRECTORY_SEPARATOR.'templates');
	define('ELEMENTS_TPL','tpl_elements');
} else { 
	define('ELEMENTS_TPL','tpl_main');
}

define('MAIN_TPL','tpl_main');
define('ADMIN_TPL','tpl_admin');
define('FORMS_TPL','tpl_forms');
define('FORUM_TPL','tpl_forum');
define('ROOT_INDEX',toUrl(INDEX_PATH));

//echo ini_get('include_path');
function _l($mess,$par=array()){
	static $lang=array(
		 'wrong password'=>'Неверный пароль'
		,'page not found, sorry!'=>'Страница не найдена ;-('
		,'Hello "%s"'=>'<a href="?do=logout"
	 onclick="if(!confirm(\'Хотите закончить сеанс?\')) return false;">Завершение сеанса &laquo;%s&raquo;</a>'
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

########## Check if database exist #################################################
require_once "Generic.php";
// Устанавливаем соединение.
/*
*/
// Код обработчика ошибок SQL.
function databaseErrorHandler($message, $info)
{
	// Если использовалась @, ничего не делать.
	if (!error_reporting()) return;
	// Выводим подробную информацию об ошибке.
	$s= "SQL Error: $message<br><pre>".print_r($info,true)."</pre>";
	if (isset($_GET['JsHttpRequest']))
		$_RESULT['debug'].= $s;
	else
		echo $s.'<hr>';
	exit();
}
function myLogger($db, $sql)
{
  global $engine,$DATABASE;
  $engine->req_cnt++;
  if(!empty($DATABASE->do_log)){
	  // Находим контекст вызова этого запроса.
	  $caller = $DATABASE->findLibraryCaller();
	  $tip = "at ".@$caller['file'].' line '.@$caller['line'];
	  // Печатаем запрос (конечно, Debug_HackerConsole лучше).
	  //echo "<xmp title=\"$tip\">";
	  if(preg_match('/$\s*--/',$sql))
	  	debug($sql."\n".$tip);
	  else
	  	debug($sql);
	  //echo "</xmp>";
  }
}
function DATABASE(){
	global $DATABASE,$connect,$db_setup;
	if(isset($DATABASE)) return $DATABASE;
	//echox 'test1';//$connect; //mysql://dbu_xilen_7:Ivnsqk0eCru@mysql.xilen.z8.ru
	$DATABASE = DbSimple_Generic::connect($connect);
	$DATABASE->setIdentPrefix(TAB_PREF.'_');
	$DATABASE->setErrorHandler('databaseErrorHandler');
	$DATABASE->setLogger('myLogger');
	$DATABASE->select($db_setup);// High  magic!!!!!!!!!!!!!!!!!
	//echo 'test2';
	return $DATABASE;
}

/**
 * Фабрика объектов. 
 * Польза: 
 * -- ленивая загрузка кода
 * -- собственно фабрика.
 * 
 * конфигурация
 * 
 * LoadClass (array(
 *   'class'=>'path'                    # Класс 'class' описан в файле 'path'
 * 	 'class'=>array('newClass','path')  # Класс 'newClass' создается при запросе 'class' и описан в файле 'path'
 *   'class'=>array('newClass')         # Класс 'newClass' создается при запросе 'class'
 * ))
 */
function includeit($x,&$y){
	if(!empty($y))
		include_once($y);
	else
		include_once($x.'.php');	
}

/* usefull stuff */
//*******************************************************
// пользительные функции!!!
if ( !function_exists('htmlspecialchars_decode') )
{
	function htmlspecialchars_decode($text)
	{
		return strtr($text, array_flip(get_html_translation_table(HTML_SPECIALCHARS)));
	}
}
if(!function_exists('http_build_query')) {
	function http_build_query($data,$prefix=null,$sep='',$key='') {
		$ret = array();
		foreach((array)$data as $k => $v) {
			$k = urlencode($k);
			if(is_int($k) && $prefix != null) {
				$k = $prefix.$k;
			};
			if(!empty($key)) {
				$k = $key."[".$k."]";
			};
			if(is_array($v) || is_object($v)) {
				array_push($ret,http_build_query($v,"",$sep,$k));
			}
			else {
				array_push($ret,$k."=".urlencode($v));
			};
		};
		if(empty($sep)) {
			$sep = '&';//ini_get("arg_separator.output");
		};
		return implode($sep, $ret);
	};
};
//if(!function_exists('json_encode')){
   /**
    * Convert PHP scalar, array or hash to JS scalar/array/hash.
    */
function detectUTF8($string)
{
       return preg_match('%(?:
       [\xC2-\xDF][\x80-\xBF]        		# non-overlong 2-byte
       |\xE0[\xA0-\xBF][\x80-\xBF]          # excluding overlongs
       |[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}   # straight 3-byte
       |\xED[\x80-\x9F][\x80-\xBF]          # excluding surrogates
       |\xF0[\x90-\xBF][\x80-\xBF]{2}    	# planes 1-3
       |[\xF1-\xF3][\x80-\xBF]{3}           # planes 4-15
       |\xF4[\x80-\x8F][\x80-\xBF]{2}    	# plane 16
       )+%xs', $string);
}

function php2js($a)
   {
       if (is_null($a)) return 'null';
       if ($a === false) return 'false';
       if ($a === true) return 'true';
       if (is_scalar($a)) {
           $a = addslashes($a);
           $a = str_replace("\n", '\n', $a);
           $a = str_replace("\r", '\r', $a);
           $a = preg_replace('{(</)(script)}i', "$1'+'$2", $a);
           return "'$a'";
       }
       $isList = true;
       for ($i=0, reset($a); $i<count($a); $i++, next($a))
           if (key($a) !== $i) { $isList = false; break; }
       $result = array();
       if ($isList) {
           foreach ($a as $v) $result[] = php2js($v);
           return '[ ' . join(', ', $result) . ' ]';
       } else {
           foreach ($a as $k=>$v)
               $result[] = php2js($k) . ': ' . php2js($v);
           return '{ ' . join(', ', $result) . ' }';
       }
   }
//}
//**********************************************************************************
//*** 1 - неопределенные переменные
//***     pp($_POST['xxx'],'') - без надобности проверки на существование
//*** 2 - оформление с обрамлением и дефолтом
//***     pp($a,'[',']','<а нету>')
//**********************************************************************************
/**
 * @param  $x - значение для проверки
 * @param string $pre - префикс, если значение не пусто
 * @param string $post - суффикс - если значение не пусто
 * @param string $def - умолчание, если значение пусто
 * @return string
 */
function pp(&$x,$pre=' ',$post='',$def='') { return (!empty($x))?$pre.$x.$post:$def;}
function pps(&$x,$def='') { return (!empty($x))?$x:$def;}
function ppi(&$x,$def=0) { return (!empty($x))?intval($x):$def;}
function ppx($x,$def='') { return (!empty($x))?$x:$def;}

function toUrl($z=''){
	$dr = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
    $z = str_replace('\\', '/', $z);        
    return preg_replace('~^(.\:|/usr)?'.preg_quote($dr,'~:').'|index.php(\?|\b)~is', '', $z);
}

function toUrl_sf($z){
	$out = ereg_replace("^/+", "", $z);
	$out = ereg_replace("^".BASE_FOLDER, "", $out);
	$out = ereg_replace("^/+", "", $out);
	return $out;
}

//define('ROOT_INDEX',toUrl(__FILE__));
//print_r($_GET);
if((isSet($_POST['xaction']) && $_POST['xaction'] == "users_avatar") || (isSet($_GET['do']) && $_GET['do'] == "onlinereg"))
	define('TMP_DIR',ROOT_PATH.DIRECTORY_SEPARATOR.'avatar'.DIRECTORY_SEPARATOR);
else {
	if(file_exists(ROOT_PATH.DIRECTORY_SEPARATOR.'uploaded'))
	define('TMP_DIR',ROOT_PATH.DIRECTORY_SEPARATOR.'uploaded'.DIRECTORY_SEPARATOR); // фенечка для htmlform
	else
	define('TMP_DIR',ROOT_PATH.DIRECTORY_SEPARATOR.'www'.DIRECTORY_SEPARATOR.'uploaded'.DIRECTORY_SEPARATOR); // фенечка для htmlform
}
ini_set('session.use_trans_sid','0');
ini_set('session.use_cookies','1');
ini_set('session.bug_compat_42','0');
ini_set('allow_call_time_pass_reference','1');

if(!function_exists('filter_var')){
define('FILTER_VALIDATE_EMAIL',1000);
define('FILTER_DEFAULT',0);

function filter_var($var,$filter= FILTER_DEFAULT){
	if ($filter==FILTER_VALIDATE_EMAIL){
		return preg_match(
			'/^([a-z0-9][a-z0-9-]*[a-z0-9]\.?)*[a-z0-9]@([a-z0-9]\.|[a-z0-9][a-z0-9-]*[a-z0-9]\.)+[a-z]{2,6}$/i',
			$var);
	}
	return true;
}
}

function UpLow($string,$registr='up'){
	$upper = 'АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$lower = 'абвгдеёжзийклмнопрстуфхцчшщъыьэюяabcdefghijklmnopqrstuvwxyz';
	if($registr == 'up') $string = strtr($string,$lower,$upper); 
	else $string = strtr($string,$upper,$lower);
	return $string;
}

/**
 * функция преобразования текста в комментарий
 * 
 */
function post2comment($s){
	$s=preg_replace(array('/^    */m','/^  /m','/^ /m')
		,array('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;','&nbsp;&nbsp;&nbsp;&nbsp;','&nbsp;&nbsp;'),$s);
	return nl2br(strip_tags($s));
}

function translit($s)
{
/*	$s=str_replace(" ","_",$s); // сохраняем пробел от перехода в %20
	$s=str_replace(",",".h",$s); // сохраняем запятую
	$s=str_replace('"',"&quot;",$s); // сохраняем кавычки
*/
	$s=urldecode($s);
	$s=str_replace('"',"&quot;",$s); // сохраняем кавычки
	
	$s= strtr(strtoupper($s),array(
                  "ЫА"=>"yha",
                  "ЫО"=>"yho",
                  "ЫУ"=>"yhu",
                  "Ё"=>"yo",
                  "Ж"=>"zh"));
	$s = strtr($s, "АБВГДЕЗИЙКЛМНОПРСТУФХЦ"
				 , "abvgdezijklmnoprstufxc");
	$s= strtr($s,array( 
                  "Ч"=>"ch",
                  "Ш"=>"sh",
                  "Щ"=>"shh",
                  "Ъ"=>"qh",
                  "Ы"=>"y",
                  "Ь"=>"q",
                  "Э"=>"eh",
                  "Ю"=>"yu",
                  "Я"=>"ya",
                  " "=>"_",
 				  "№"=>"n",
				  '"'=>"&quot;"
	)) ;
	$s= strtr($s,array(
		'&'=>'',
		'#'=>'',
		';'=>'',
		'*'=>'',
		'?'=>''
	)); 
	for($i=0;$i<strlen($s);$i++){
		if($s{$i}>"\x80")$s{$i}='_';
	}
	
    return strtolower($s);              
}
/**
 * Сканирование параметров в реьд элементе
 *
 * @param string $s - строка с параметрами
 * @param массив имен параметров $arr
 * @return array массив параметров
 */
function scanPar($s,$arr){
	preg_match_all("/(\w*)=(['\"])([^\\2]*)\\2/U", $s,$x);
	//print_r($x);
	$par=array('par'=>'');
	foreach($x[1] as $k=>$v){$v=strtolower($v);
		if(isset($arr[$v]))
			$par[$arr[$v]]=$x[3][$k];
		else
			$par['par'].=' '.$v.'='.$x[2][$k].$x[3][$k].$x[2][$k];
	}
	return $par;
}


function toRusDate($daystr=null,$format="j F, Y г."){
	//print_r($datstr);
	if ($daystr) $daystr=strtotime($daystr);
	else $daystr=time();
	return	str_replace( //XXX: нужно проверить английские имена месяцев
		array('january','february','march','april','may','june','july',
				'august','september','october','november','december'),
		array('января','февраля','марта','апреля','мая','июня','июля',
				'августа','сентября','октября','ноября','декабря'),
		strtolower(date($format,
			$daystr)));
}

function &LoadClass($cls,$param=0){
	static  $class_record=array(),
			$path_record=array();
	
	if(is_string($cls)){ 
		// checking dependence
		$class_name = $cls;
		if(!class_exists($class_name))
			includeit($class_name,$path_record[$class_name]);
		if (isset($class_record[$cls])){
			// checking class itself
			$class_name = $class_record[$cls];	
			if(!class_exists($class_name))
				includeit($class_name,$path_record[$class_name]);
		} 
		$result=null;	
		// creating an object
		if(!class_exists($class_name))
			return $result;
		if(is_array($param))
		switch(count($param)){
			case 1:  
				$result=&new $class_name(&$param[0]) ;
				break; 
			case 2:  
				$result=&new $class_name(&$param[0],&$param[1]) ;
				break;
			default:
				$result=&new $class_name() ;
		}
		else
			$result=&new $class_name() ;
		return $result;
		
	}
	
	// is_array($cls) - configuration parcing
	
	foreach($cls as $k=>$v){
		if(is_string($v))
			$v=array($k,$v);
		
		if($v[0])
			$class_record[$k]=$v[0];
		if(isset($v[1])){
			if(substr($v[1],-1,1)=='/')
				$path_record[$v[0]]=$v[1].$v[0].'.php';
			else
				$path_record[$v[0]]=$v[1];
		}
	}
	return $reg_record ;
}

/**
 * настройки на двигл CMS
 */
LoadClass(array(
// модули
	'Auth'=>ROOT_PATH.'/'.ADMIN.'/engine/users.php',
	'html_mime_mail'=>ROOT_PATH.'/'.ADMIN.'/engine/sendmail.php',
	'form'=>ROOT_PATH.'/'.ADMIN.'/engine/users.php',

// плагины
	'sitemap'=>ROOT_PATH.'/'.ADMIN.'/engine/plugins/',
	'bannerlist'=>ROOT_PATH.'/'.ADMIN.'/engine/plugins/',
	'fileman'=>ROOT_PATH.'/'.ADMIN.'/engine/plugins/',
	'massmail'=>ROOT_PATH.'/'.ADMIN.'/engine/plugins/',

	'news'=>ROOT_PATH.'/'.ADMIN.'/engine/plugins/news.php',
	'PhotoSizes'=>ROOT_PATH.'/'.ADMIN.'/engine/plugins/news.php',
	'search'=>ROOT_PATH.'/'.ADMIN.'/engine/plugins/news.php',
	'vocabular'=>ROOT_PATH.'/'.ADMIN.'/engine/plugins/news.php',

//	'qa'=>ROOT_PATH.'/'.ADMIN.'/engine/plugins/q_a.php',
//	'writeus'=>ROOT_PATH.'/'.ADMIN.'/engine/plugins/q_a.php',
	'rss'=>ROOT_PATH.'/'.ADMIN.'/engine/plugins/',
	'rssexport'=>ROOT_PATH.'/'.ADMIN.'/engine/plugins/',
	'runningline'=>ROOT_PATH.'/'.ADMIN.'/engine/plugins/',
	'votes'=>ROOT_PATH.'/'.ADMIN.'/engine/plugins/',

	'basket'=>ROOT_PATH.'/'.ADMIN.'/engine/plugins/katalog.php',
	'katalog'=>ROOT_PATH.'/'.ADMIN.'/engine/plugins/katalog.php',
	'novinki'=>ROOT_PATH.'/'.ADMIN.'/engine/plugins/katalog.php',
	'spec'=>ROOT_PATH.'/'.ADMIN.'/engine/plugins/katalog.php',
	'csv_reader'=>ROOT_PATH.'/'.ADMIN.'/engine/plugins/katalog.php',
	'csv'=>ROOT_PATH.'/'.ADMIN.'/engine/plugins/katalog.php',

));

/**
 * Вялая попытка вставить файловое кэширование
 */
if (!defined('NOCACHE') && !defined('INTERNAL')){
//*
include_once(ROOT_PATH.'/'.ADMIN.'/engine/FileCache.php');
// построение группы по GET'у
$x=$_GET;
// исключение некоторых ключей
unset($x[session_name()],$x['debug'],$x['cache']);
ksort($x);
$page_hash=md5(serialize($x));
$group=isset($_REQUEST[session_name()])?'x':'s';
$cache = LoadClass('FileCache',array(array(
	'is_enabled' => ($_SERVER['REQUEST_METHOD']=='GET'
			&& !in_array(pps($_GET['do']),array('search','basket','writeus', 'logout'))
			&& !in_array(pps($_GET['id']),array('search','basket','writeus'))
		),
	'dir'   => 'cache/',
	'ttl'   => 60 * 60 * 3,  #3 часа
	'cleaning_probability' => 100,
	'group' => $group,
	'hash'  => $page_hash,
	'user_id' => ppi($_SESSION['USER_ID']),
)));
if(isset($_GET['cache'])?$_GET['cache']:true){
//if(false){
	$gz_content = $cache->read($last_modified, $content_type);
	if(!empty($gz_content)) {
		echo $gz_content ;
		exit;
	}
}
$_GLOBAL['cache']=&$cache;
}
// FileCache
//*/
?>