<?php
/**
 * �������� ������ �������� � �� ����� �������, ���������� ������ ������ index � main  
 */
/**
 * ����� ������� ����������
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

class SUPER {
    private static
        $path_record=array(),
        $lang=array();
    protected static
        $EXPORTS=array();

     /**
      * ��������� ������ � ����
      * @var engine_Main
      */
    public static
           $engine=null;
    public static
           $debug  ='';

    //**********************************************************************
    /**
     * ������ ������ � �����������
     *
     * ����:
     * -- ��������� ������ ���������� �/��� ��������� - self::set_option($options as array,$handler as string)
     * -- ��������� ������ ��������� �/��� ��������� - self::set_option($name as string,$value as mixed,$handler as string)
     * -- ��������� ��������� - self::option($name as string,$def as mixed)
     * -- ��������� ������ ���������� �� ��������
     *
     */
    /**
     * ��������� - name=>0:value,1:handler,2:changed
     * @var array
     */
    private static
        $options=array();

    static function set_option($opt,$value=null,$handler=null){
        if(is_array($opt)){
            foreach($opt as $k=>$v)
                self::set_option($k,$v,$value);
            return;
        }
        if(isset(self::$options[$opt])){
            if(self::$options[$opt][0]!=$value){
                self::$options[$opt][2]=true;
                if (!empty($handler))
                    self::$options[$opt][1]=$handler;
                self::$options[$opt][0]=$value;
            }
        } else {
            self::$options[$opt]=array($value,$handler,false);
        }
    }
    
    /**
     * @static
     * @param mixed $opt - ��� ��������
     * @param null $def - �������� �� ���������, ���� ��������� ���
     * @return null
     */
    static function option($opt=null,$def=null){
        if(is_null($opt))
            return self::$options;
        elseif (isset(self::$options[$opt]))
            return self::$options[$opt][0];
        else
            return $def;
    }
    //**********************************************************************

    static function include_file_with_class($cls){
        if(!class_exists($cls))
            //var_dump(array(self::$path_record[$cls],getcwd()));
            if(isset(self::$path_record[$cls])){
                include_once(self::$path_record[$cls]);
                return;
            }
            $x=self::option('path','engine/').$cls.self::option('class_ext','.php');
            if(is_readable($x)){
                include_once($x);
                return ;
            }
    }

    /**
     * autoload support, ��������� ������������ �������
     * @static
     * @param  $classes
     * @return void
     */
    static function classes($classes){
        foreach($classes as $k=>$v){
            if(is_string($v))
                $v=array($k,$v);

            if(is_object($v)) //TODO:wtf?
                continue;

            if(isset($v[1])){
                if(substr($v[1],-1,1)=='/')
                    self::$path_record[$v[0]]=$v[1].$v[0].'.php';
                else
                    self::$path_record[$v[0]]=$v[1];
            }
        }
    }

    static function setlang($lang){
        self::$lang=array_merge(self::$lang,$lang);
    }
    /**
     * �������������
     * @static
     * @param  $mess
     * @param array $par
     * @return string
     */
    static function _l($mess,$par=array()){

        if(isset(self::$lang[$mess]))
            $mess=self::$lang[$mess];
        if(is_array($mess))
            $mess=$mess[self::option('language','en')];
        if($par!==null)
            return vsprintf($mess,$par);
        else
            return $mess ;
    }

    /**
     * @static
     * @param $x
     * @return SysPar
     */
    static function _DO($x){
        if(isset($x['classes']))
            SUPER::classes($x['classes']);
        if(isset($x['options']))
            SUPER::set_option($x['options']);
        $main=pps($x['main'],'engine_Main');
        $plugins=array();
        if(isset($x['plugins']))
            $plugins=$x['plugins'];
        $engine=new $main($plugins);
        $_GLOBAL['engine']=&$engine;
        SUPER::$engine=&$engine;
/**
 *   Just a simple call
 */
        return $engine;
    }
}

/**
  * autoload suppert/ internal function
  * @static
  * @param  $cls
  * @return
  */

 function __autoload($cls){
    SUPER::include_file_with_class($cls);
}

include_once(ROOT_PATH.'/engine/hosts.php');

$connect="mysql://$the_login:$the_pass@$the_host/$the_db";

if(empty($db_setup)) {
    if(!defined('DB_SETUP'))
        $db_setup='set NAMES "cp1251";';
    else
        $db_setup=DB_SETUP;
}
if(!defined('TEMPLATE_PATH')){
	define("TEMPLATE_PATH",ROOT_PATH.DIRECTORY_SEPARATOR.'templates');
	if(!defined('ELEMENTS_TPL'))
		define('ELEMENTS_TPL','tpl_elements');
} else if(!defined('ELEMENTS_TPL')){
	define('ELEMENTS_TPL','tpl_main');
}
if(!defined('MAIN_TPL'))
	define('MAIN_TPL','tpl_main');
if(!defined('ADMIN_TPL'))
	define('ADMIN_TPL','tpl_admin');

define('FORMS_TPL','tpl_forms');
define('FORUM_TPL','tpl_forum');
define('ROOT_INDEX',toUrl(INDEX_PATH));

//echo ini_get('include_path');
if(!function_exists('_l')){
    function _l($mess,$par=array()){
        static $lang=array(
             'wrong password'=>'�������� ������'
            ,'page not found, sorry!'=>'�������� �� ������� ;-('
        ,'Hello "%s"'=>'�� ������������<br>��� &laquo;<a href="?do=logout"
     onclick="if(!confirm(\'������ ��������� �����?\')) return false;">%s</a>&raquo;'
             ,"\n<!-- (%s) page was built for %f sec -->"=>
         "<!-- (%s) �������� �������������� %f ���  -->"
            ,'query'=>"������||�|��"
         );

        if(isset($lang[$mess])) $mess=$lang[$mess];
    
        if($par!==null)
            return vsprintf($mess,$par);
        else
            return $mess ;
    }
}
########## Check if database exist #################################################
require_once "Generic.php";
// ������������� ����������.
/*
*/
// ��� ����������� ������ SQL.
function databaseErrorHandler($message, $info)
{
	// ���� �������������� @, ������ �� ������.
	if (!error_reporting()) return;
	// ������� ��������� ���������� �� ������.
	$s= "SQL Error: $message<br><pre>".print_r($info,true)."</pre>";
	echo $s.'<hr>';
	exit();
}
function myLogger($db, $sql)
{
  global $DATABASE;
  if(!empty($DATABASE->do_log)){
	  // ������� �������� ������ ����� �������.
	  // �������� ������ (�������, Debug_HackerConsole �����).
	  //echo "<xmp title=\"$tip\">";
	  if(preg_match('/$\s*--/',$sql)){
            debug($sql);
      } else {
            $caller = $DATABASE->findLibraryCaller();
            $tip = "at ".@$caller['file'].' line '.@$caller['line'];
            debug($sql);
      }
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
    @$DATABASE->select($db_setup);// High  magic!!!!!!!!!!!!!!!!!
	//echo 'test2';
	return $DATABASE;
}

/**
 * ������� ��������. 
 * ������: 
 * -- ������� �������� ����
 * -- ���������� �������.
 * 
 * ������������
 * 
 * LoadClass (array(
 *   'class'=>'path'                    # ����� 'class' ������ � ����� 'path'
 * 	 'class'=>array('newClass','path')  # ����� 'newClass' ��������� ��� ������� 'class' � ������ � ����� 'path'
 *   'class'=>array('newClass')         # ����� 'newClass' ��������� ��� ������� 'class'
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
// ������������� �������!!!
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
//*** 1 - �������������� ����������
//***     pp($_POST['xxx'],'') - ��� ���������� �������� �� �������������
//*** 2 - ���������� � ����������� � ��������
//***     pp($a,'[',']','<� ����>')
//**********************************************************************************
/**
 * @param  $x - �������� ��� ��������
 * @param string $pre - �������, ���� �������� �� �����
 * @param string $post - ������� - ���� �������� �� �����
 * @param string $def - ���������, ���� �������� �����
 * @return string
 */
function pp(&$x,$pre=' ',$post='',$def='') { return (!empty($x))?$pre.$x.$post:$def;}
function pps(&$x,$def='') { return (!empty($x))?$x:$def;}
function ppi(&$x,$def=0) { return (!empty($x))?intval($x):$def;}
function ppx($x,$def='') { return (!empty($x))?$x:$def;}

function toUrl($z=''){
	$dr = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
    $z = str_replace('\\', '/', $z);        
    return preg_replace('~^(.\:|/usr)?'.preg_quote($dr,'~:').'|index.jtpl(\?|\b)~is', '', $z);
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
	define('TMP_DIR',ROOT_PATH.DIRECTORY_SEPARATOR.'uploaded'.DIRECTORY_SEPARATOR); // ������� ��� htmlform
	else
	define('TMP_DIR',ROOT_PATH.DIRECTORY_SEPARATOR.'www'.DIRECTORY_SEPARATOR.'uploaded'.DIRECTORY_SEPARATOR); // ������� ��� htmlform
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
	$upper = '�����Ũ��������������������������ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$lower = '��������������������������������abcdefghijklmnopqrstuvwxyz';
	if($registr == 'up') $string = strtr($string,$lower,$upper); 
	else $string = strtr($string,$upper,$lower);
	return $string;
}

/**
 * ������� �������������� ������ � �����������
 * 
 */
function post2comment($s){
	$s=preg_replace(array('/^    */m','/^  /m','/^ /m')
		,array('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;','&nbsp;&nbsp;&nbsp;&nbsp;','&nbsp;&nbsp;'),$s);
	return nl2br(strip_tags($s));
}

function translit($s)
{
/*	$s=str_replace(" ","_",$s); // ��������� ������ �� �������� � %20
	$s=str_replace(",",".h",$s); // ��������� �������
	$s=str_replace('"',"&quot;",$s); // ��������� �������
*/
	$s=urldecode($s);
	$s=str_replace('"',"&quot;",$s); // ��������� �������
	
	$s= strtr(strtoupper($s),array(
                  "��"=>"yha",
                  "��"=>"yho",
                  "��"=>"yhu",
                  "�"=>"yo",
                  "�"=>"zh"));
	$s = strtr($s, "����������������������"
				 , "abvgdezijklmnoprstufxc");
	$s= strtr($s,array( 
                  "�"=>"ch",
                  "�"=>"sh",
                  "�"=>"shh",
                  "�"=>"qh",
                  "�"=>"y",
                  "�"=>"q",
                  "�"=>"eh",
                  "�"=>"yu",
                  "�"=>"ya",
                  " "=>"_",
 				  "�"=>"n",
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
 * ������������ ���������� � ���� ��������
 *
 * @param string $s - ������ � �����������
 * @param ������ ���� ���������� $arr
 * @return array ������ ����������
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


function toRusDate($daystr=null,$format="j F, Y �."){
	//print_r($datstr);
	if ($daystr) $daystr=strtotime($daystr);
	else $daystr=time();
	return	str_replace( //XXX: ����� ��������� ���������� ����� �������
		array('january','february','march','april','may','june','july',
				'august','september','october','november','december'),
		array('������','�������','�����','������','���','����','����',
				'�������','��������','�������','������','�������'),
		strtolower(date($format,
			$daystr)));
}

/**
 * ��������� �� ����� CMS
 */
if(defined('ADMIN')){
    SUPER::classes(array(
    // ������
        'Auth'=>ROOT_PATH.'/'.ADMIN.'/engine/users.php',
        'html_mime_mail'=>ROOT_PATH.'/'.ADMIN.'/engine/sendmail.php',
        'form'=>ROOT_PATH.'/'.ADMIN.'/engine/users.php',

    // �������
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
 * ����� ������� �������� �������� �����������
 */
if (!defined('NOCACHE') && !defined('INTERNAL')
	&& is_readable(ROOT_PATH.'/'.ADMIN.'/engine/FileCache.php'))
{
    //*
    include_once(ROOT_PATH.'/'.ADMIN.'/engine/FileCache.php');
    // ���������� ������ �� GET'�
    $x=$_GET;
    // ���������� ��������� ������
    unset($x[session_name()],$x['debug'],$x['cache']);
    ksort($x);
    $page_hash=md5(serialize($x));
    $group=isset($_REQUEST[session_name()])?'x':'s';

    $cache = new FileCache(array(
        'is_enabled' => ($_SERVER['REQUEST_METHOD']=='GET'
                && !in_array(pps($_GET['do']),array('search','basket','writeus', 'logout'))
                && !in_array(pps($_GET['id']),array('search','basket','writeus'))
            ),
        'dir'   => 'cache/',
        'ttl'   => 60 * 60 * 3,  #3 ����
        'cleaning_probability' => 100,
        'group' => $group,
        'hash'  => $page_hash,
        'user_id' => ppi($_SESSION['USER_ID']),
    ));

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
        };
// FileCache
//*/
?>