<?php
/**
 *  раскраска прав юзера
 *
 *  битовая маска прав
 */

define("right_READ",1);
define("right_VIEW",right_READ);
define("right_WRITE",2);
define("right_DELETE",4);
define("right_EDIT",8);
define("right_LOGIN",16);
define("right_LOGOUT",32);
define("right_MULTY",64);
define("right_SPECIAL",128);
define("right_CHAT",128);
define("right_ADMIN",1024);
// and so on!!!
// идентификатор общей секции прав
define("rights_COMMON_SECTION","*");

define ('LOGIN_COOKIE','login');
if(!defined('LOGIN_SAVEINCOOKIE')){
	define ('LOGIN_SAVEINCOOKIE',false);
}

class rights
{
	var $_right;

	function __construct($allow=null,$deny=null)
	{
		$this->allow=array();$this->deny=array();
		$this->_right=array();
		$this->apply_rights($allow,$deny);
	}

    function list_right(){
        return $this->_right;
    }
/**
 *  внутренняя функция - проверка точной секции
 */
	function _has_right($s,$rights)
	{
		if(isset($this->_right[$s])){
			if($this->_right[$s] & $rights)
				return true ;
			else
				return false;
		}
		return 0;
	}
/**
 *  внешняя функция - проверка секции, если секции нет - проверка общей
 */
	function has_right($s,$right=right_READ)
	{
		$res=$this->_has_right($s,$right);
		if($res===0)
			$res=$this->_has_right(rights_COMMON_SECTION,$right);
		return $res ;
	}

/**
 *  подклеить права к объекту
 *   более частные права подклеиваются к более общим
 *   разрешение/запрет на частном бьет запрет/разрешение  на общем...
 */
	function apply_rights($allow,$deny)
	{
		if(!empty($allow))
		foreach($allow as $k=>$v) {
			$this->allow[$k]=(ppi($this->allow[$k])&& (~ppi($deny[$k]))) || $v;
		}
		if(!empty($deny))
		foreach($deny as $k=>$v) {
			$this->deny[$k]=(ppi($this->deny[$k])&& (~ppi($allow[$k]))) || $v;
		}
	}
}

class  Auth extends plugin{
	//var $names=array(TYPE_UNDEF=>'',TYPE_ARRAY=>'tval',TYPE_INT=>'ival',TYPE_TXT=>'tval');
	var $login_cookie = LOGIN_COOKIE;

	function init(){
		$this->login_cookie=pps($this->parent->login_cookie,LOGIN_COOKIE);
	}

	function get_parameters(&$par){
		$par['list'][]=array('sub'=>'Авторизация','title'=>'Количество дней хранения куки с паролем','name'=>'auth_cookie_age');
	}	
	
/**
 * Внешняя функция
 *	Выдать наружу массив о пользователе
 */
	function find_user($name='',$id=0,$create=false){
		//*---*/$this->parent->error( '#'.$name.'#'.$id.'#'.$create.'#');
		$nm=$id>=0?'user':'group';
		if($id>0)
			$user=$this->parent->readRecord(array('id'=>$id));
		else {
			$user=array('record'=>$nm,$nm=>$name);
	    	$u=$this->parent->readRecord($user);
//	    	print_r($u);
	    	if(!empty($u['id']))
	    		$user=$u ;
	    	else {
	    		$this->writePar($user);
		    	//$user=$this->readPar($user);
	    	}
		}
 //exit();
		return $user ;
	}

/**
 *	удалить группу с именем name
 */
	function del_user($name='',$id=0){
		//*---*/$this->error( '#'.$name.'#'.$id.'#'.$create.'#');
		$u=$this->find_user($name,$id);
		$this->delPar($u['id']);
//		exit();
	}

/**
 *  завести категорию с именем name
 */
	function new_subcat($name){
		$param=$this->find('param','category',true);
		$tval=array();
		if(isset($param['tval'])){
			$tval=unserialize($param['tval']);
		}
		$tval[$name]='1';
		$param['tval']=serialize($tval);
		$this->store_param($param);
	}

/**
 * разрегистрироваться
 */
	function _logout() {
		setcookie($this->login_cookie, "", time() - 3600,ROOT_INDEX);
		unset($_SESSION['USER_ID']);
	}

	function _loginform(){
        if(!function_exists('smart_template')) return 'improper installation/ no templater found';
		$form=new form('login');
		$form->scanHtml(smart_template(array(FORMS_TPL,'login'),
			array(
				'cansave'=>defined('LOGIN_CANSAVE')?LOGIN_CANSAVE:true,
				'error'=>pps($_SESSION['errormsg']))));
		if($form->handle()){
			//var_dump($form['var']);
			if($this->auth_check($form->var['login_name']
				,$form->var['login_pass']
				,pps($form->var['login_save'])))
			{
				$this->parent->go($this->parent->curl());
			}
			else
				$this->parent->error(self::_l(mess_wrong_pasword));
		}
		return $form->getHtml(' ');
	}

/**
 * проверить статус пары user-password
 */
	function auth_check($user='',$password='',$saveincookie=LOGIN_SAVEINCOOKIE) {
		//debug($_SESSION);
		$by_using_cookie = false ;
		if((''===$user) && isset($_SESSION['USER'])) {
            $this->parent->user=$_SESSION['USER'];
        } elseif((''===$user) && isset($_SESSION['USER_ID'])) {
			$this->parent->user=$this->parent->readRecord(array('id'=>$_SESSION['USER_ID']));
		} else {
			if((''===$user) && isset($_COOKIE[$this->login_cookie])) {
				$by_using_cookie=true ;
				list($user,$password)=explode('|',base64_decode($_COOKIE[$this->login_cookie]),2);
			}
			$res=array();
			if($user) {
				$this->parent->user=$this->parent->readRecord(array('record'=>'user','name'=>$user,'password'=>$password));
				//debug($this->parent->user);
			}
		}
        if(empty($this->parent->rights))
            $this->parent->rights=new rights();

		if(isset($this->parent->user['id'])){
			//print_r($this->parent->user);
			$_SESSION['USER_ID']=ppi($this->parent->user['id']);
			if ($by_using_cookie || $saveincookie) {
				if(pps($_GET['do'])!='logout') // xxx: оппа! :-)
				setcookie($this->login_cookie,
					base64_encode($this->parent->user['name'].'|'
						.pps($this->parent->user['password'])),
					time()+3600*24*14,
					ROOT_INDEX);
			}
			//debug(ROOT_INDEX);
			if(empty($this->parent->rights)) $this->parent->rights=new rights();
			$this->parent->rights->_right=&$this->parent->user['right'];
			return true ;
		} else
			return false;
	}

/**
 * хэндл для плагина - Аякс
 */
/*
	function ajax_request($par) {
		switch($par['mode']){
		case 'register':
			$row=$this->parent->database->selectRow("SELECT `USER_ID` FROM ?_users WHERE `user`=? LIMIT 1;",
				pps($_REQUEST['user']));
			if (count($row)) {
				$par['_RESULT']['login_error']='User already exists';
				break;
			}
	//            print_r($_REQUEST) ;
			$key=array();
			foreach($this->param as $k)
				$key[$k]=trim(pps($_REQUEST[$k]));
			if (!$key['user']){
				$par['_RESULT']['login_error']="Empty name isn't allowed";
				break;
			}
			elseif (preg_match('/<>\|/',$key['user'])){
				$par['_RESULT']['login_error']="name contain forbiden characters.";
				break;
			}
			elseif ($key['password']!=trim(pps($_REQUEST['password2']))){
				$par['_RESULT']['login_error']="Retype your password again.";
				break;
			}
			$this->parent->database->query('INSERT INTO ?_users (?#) VALUES(?a);',
				array_keys($key),array_values($key));
			// Ok! Let's register
			$_REQUEST['loginname']=$key['user'];
			$_REQUEST['loginpass']=$key['password'];
			$this->auth_check();
			break;
		case 'loginform':
		case 'regform':
		case 'forgetpsw':
			$par['_RESULT']['html_value']=smart_template(AUTH_TPL."#".$par['mode'],"@");
			break  ;
		case 'mailpsw':
		//justaname
			//print_r($_REQUEST); /*
			$x=trim(pps($_REQUEST['justaname']));
			$row=$this->parent->database->selectRow("SELECT `user`, `email`, `realname`, `password` FROM `tests_users` WHERE `user`=? OR `email`=? LIMIT 1;",
				$x,$x);
			if (!count($row))
				$par['_RESULT']['login_error']= 'Where is no such a user or email registered!';
			else if($row['email'])  {
				$this->parent->execute('mailme',array('mailto'=>$row['email'],'from'=>'nospam@mail.me',
							'subject'=>'You asking to remember your password',
							'body'=>'Hello '.$row['realname'].'! your name is '.$row['user'].
								', your password is '.$row['password'])
				);
			} else {
				$par['_RESULT']['login_error']= 'User "'.htmlspecialchars($row['user']).' have no email registered!';
			}
			break;
		case 'login':
			if ($this->error) {
				$par['_RESULT']['login_error']= $this->error;
				break;
			}
//        	$parent->execute('logout');
			$row=$this->parent->database->selectRow("SELECT `USER_ID` FROM ?_users WHERE `USER_ID`=? LIMIT 1;",
				pps($_SESSION['USER_ID']));
			if (!count($row)) {
				$par['_RESULT']['login_error']='Something wrong...';
				break;
			} else if(pps($_REQUEST['savebox'])){
				setcookie('login',base64_encode($this->user['user'].'|'.$this->user['password']),time()+3600*24*14);
			}
			break ;
		case 'logout':
			$this->parent->execute('logout');
			break ;
		default:
			return null;
		}
		return true;
	}
*/
}

?>