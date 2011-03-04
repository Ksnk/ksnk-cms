<?php
/**
 * плагин для редактирования юзеров и помощи в заполнении разных ордеров
 */

define ('USERS_TPL','tpl_users');

class users extends plugin {
	
	var $default_rights = 0;

	var $fields=array(
		'name'=>'Ник пользователя',
		'password'=>'Пароль пользователя',
		'cust_PHONE'=>'Телефон',
		'right'=>false, // редактирование запрещено
		'type'=>false, // редактирование запрещено
		'id'=>false, // редактирование запрещено
		'record'=>false, // редактирование запрещено
		'cust_TYPE'=>USER_TYPE,
		'cust_ADDRESS'=>'Юр. адрес',
		'cust_EMAIL'=>'E-mail',
		'cust_FIO'=>'Контактное лицо',
		'cust_INN'=>'ИНН',
		'cust_KPP'=>'КПП',
		'cust_OGRN'=>'ОГРН',
		'cust_DIRECTOR'=>'ФИО ген-директора',
		'cust_ORGANISATION'=>'Название организации',
		'cust_SCHET'=>'№ счета',
		'cust_BANK'=>'Название банка',
		'cust_BANK_BIK'=>'БИК банка',
		'cust_BANK_INN'=>'ИНН банка',
		'cust_BANK_KPP'=>'КПП банка',
		'cust_CORSCHET'=>'№ корреспондентского счета',
		'cust_MANAGER'=>'<span class="red">e-mail менеджера ЮТЕКС</span>',
	);
	
	function users(&$parent){
		plugin::plugin($parent);
		if(defined('IS_ADMIN')){
		$this->parent->par['dd_menu'][]=array(
			'name'=>'xrights',
			'list'=>array(
				array('id'=>1,'text'=>'Не разрешено'),
				array('id'=>2,'text'=>'Пользователь'),
				array('id'=>3,'text'=>'Администратор'),
//<% insert_point('user_rule'); %>
				)
		);
		}
		
	}
	
	function get_ParamNames(){
		return $this->fields;
	}

	function getPluginName(){
		return 	pps($this->pluginname,'Пользователи');
	}

	function user_list($columns=0){
		$users=$this->parent->readRecords(array('record'=>'user'),1000,6000);
		usort($users,create_function('&$aa, &$bb','
		  $a=pps($aa["name"]); $b=pps($bb["name"]);
		  if ($a == $b) return 0;
   			return ($a < $b) ? -1 : 1;
		'));
		if(!empty($columns)){
			$step=round(count($users) / ppi($columns,2));
			for($i=$step;$i<count($users)-1;$i+=$step)
				$users[$i]['break']=true;
		}
		return smart_template(array(USERS_TPL,'user_list'),array('list'=>array_values($users)));
	}
	
	function do_onlinereg(){
		$this->parent->sessionstart();
		$this->pluginname='Регистрация';
		ml_plugin::setupmenu();

		if (pps($_GET['step'])){
			$x=	smart_template(array(USERS_TPL,'qathanks')
				,array('result'=>pps($_SESSION['errormsg'])
					,'url'=>pps($_SESSION['qa_returnaddress'],'/')
				)
			);
			unset($_SESSION['errormsg']);
			return $x;
		}
		$form=new form('onlinereg');
		//$form->nostore=true;
		
		$x=pps($_SERVER['HTTP_REFERER']);
		if ($x && strpos($x,'=onlinereg')===false)
			$_SESSION['qa_returnaddress']=$_SERVER['HTTP_REFERER'];

		$form->scanHtml(smart_template(array(USERS_TPL,'onlinereg'),
			array('error'=>pps($_SESSION['errormsg']))));
		if($form->handle()){
			if (!isset($_SESSION["captcha"]) || $_SESSION["captcha"]!==$form->var["captcha"]) {
				$this->parent->error('Неверно введен номер');
				$this->parent->go($this->parent->curl('do').'do=onlinereg');
			}	
			if (trim($form->var["newpassword"])!==trim($form->var["newpassword2"])) {
				$this->parent->error('Пароли не совпадают');
				$this->parent->go($this->parent->curl('do').'do=onlinereg');
			}	
			$key=array('record'=>'user'
				,'name'=>trim(strip_tags($form->var['newlogin']))
			);
			$user=$this->parent->readRecord($key);
			if(isset($user['id'])) {
				$this->parent->error('Пользователь с таким именем уже существует');
				$this->parent->go($this->parent->curl('do').'do=onlinereg');
			}	
			
			$key=array_merge($key,array(
				'right'=>array('*'=>$this->default_rights))
			);
			$key['password']=$form->var['newpassword'];
				
			foreach($this->fields as $k=>$v){
				if($v && isset($_POST[$k])){
					$key[$k]=trim(strip_tags($_POST[$k]));
				}
			}
			//debug($this->fields); 	
			//debug($form->var);	
			//debug($key);	
			$id=$this->parent->writeRecord($key);
		//майл Одмину
			$from=pps($this->parent->getPar('mail_useradmin'),
				pps($this->parent->getPar('mail_admin'),
				'art@xilen.ru'));
			
			$to=pps($this->parent->getPar('mail_admin'),
				'art@xilen.ru');
			
			$cc=$this->parent->getPar('mail_admin2');
			
			$subj='Новый пользователь на сайте "'.$_SERVER['SERVER_NAME'].'"';
			$mail=new html_mime_mail($headers,
				pp($from,'From: ',"\r\n").
				pp($cc,'Cc: ',"\r\n").
				pp($to,'Reply-To: ',"\r\n").
	   			'X-Mailer: PHP/' . phpversion()
			);
			$mail->add_html(sprintf(
				'Зарегистрирован новый пользователь на сайте "%s"<br>
				"%s"',$_SERVER['SERVER_NAME'],$key['name']));
			$mail->build_message('win');
			
			$smtp= $this->parent->getPar('smtp_server');
			$this->parent->sessionclose();
			if(!$smtp)
				$mail->send(  $to, $subj);
			else 
				$mail->sendSmtp($smtp,$to,$from,$subj);	
			
			$this->parent->go($this->parent->curl('step').'&step=1');
		}
		return $form->getHtml(' ');
	}

	function search($s){
		$s=strtolower($s);
//debug($s);
			//return array(page,item)
		$users=$this->parent->readRecords(array('record'=>'user'),1000,6000);
		if (empty($users)) return array();

		$result=array(); $lastparent=0;
		//debug($users);
		
		foreach($users as $v){
			if(strpos(strtolower(implode('~',
			 array(
			 	$v['name'],$v['cust_EMAIL'],$v['cust_ADDRESS'],
			 	$v['cust_FIO'],$v['cust_ORGANISATION'],
			 	$v['cust_PHONE'],
			 ))),$s)!==false) {
				
				if(!empty($v['res']))
					$x=strip_tags(preg_replace('/^[^<]*>|<[^>]*$/','',substr($v['the_text'],max(0,$v['res']-200),200)));
				else
					$x=strip_tags($v['the_header']);
	
				$result[]=array(
					'tag'=>'Пользователи',
					'text'=>'<a href="?do=menu&id=users	&user='.$v['id'].'">'.$v['name'].'</a>'
				);
			 }
		}
		debug($result);
		
		return $result;
	}
	
	function admin_users(){
		$this->parent->sessionstart();
		if(!$this->parent->has_rights(right_ADMIN))
			return $this->parent->ffirst('_loginform');

		$this->parent->menu['head']=array('MAIN','_modules',$this->getPluginName(),get_class($this));
		
		if(isset($_GET['user']) && ($_GET['user']==0)){
			$user=$this->parent->readRecord(array('record'=>'user','name'=>'Введите новое имя'));
			//var_dump($user);
			if(!isset($user['id'])){
				$id=$this->parent->writeRecord(array('record'=>'user'
					,'name'=>'Введите новое имя'
					,'password'=>'Введите новый пароль'
					,'right'=>array('*'=>(right_READ))));
			} else
				$id=$user['id'];
			$this->parent->go($this->parent->curl('user').'user='.$id);
		}
		$user=pps($_GET['user']);

		if(!empty($user))
			$user=$this->parent->readRecord(array('id'=>$user));

		if(empty($user) || empty($user['name']))
			return 
			smart_template(array(ADMIN_TPL,'theheader'),array(
			'header'=>$this->getPluginName(),
			'data'=>$this->user_list(3)
			));
		$this->parent->menu['left']=array(__CLASS__,'user_list',0);
		$xpar=$user;
		$par=array();
		$i=1;
		foreach($this->fields as $k=>$v){
			if(($this->fields[$k]===false))continue;
			//if (isset($xpar[$k])){
				$par[$k]=pps($xpar[$k]);
			//}
		}
		foreach($xpar as $k=>$v){
			if(isset($this->fields[$k])&&($this->fields[$k]===false))continue;
			if (!isset($par[$k]) && $k!='txt'){
				$par[$k]=$xpar[$k];
			}
		}
		
		$form = new form('admin_user');
		$form->nostore=true;
		// права юзера
		$xpar=$par;$par=array();
		//debug($xpar);
		
		foreach($xpar as $k=>$v){
			$par[]=array(
				($k=='cust_TYPE'?'xuser':'common') //'$k=='cust_MANAGER'?'xmanager':'common')
				=>array(
			'prop'=>$k,
			'trclass'=>evenodd(($i++) -1),
			'val'=>$v,
			'the_text'=>pps($this->fields[$k],$k)));
		}
		if($user['right']['*']>1)
			$right=3;
		elseif	($user['right']['*']==1)
			$right=2;
		else
			$right=1;
		$par[]=array('xrights'	=>array(
			'trclass'=>evenodd(($i++) -1),
			'val'=>$right
			));
		$form->scanHtml(smart_template(array(USERS_TPL,'admin_user'),array(
				'list'=>$par,
				'u_right'=>$right,
				'error'=>$this->parent->error()))
		);
		if($form->handle()){
			if(isset($_POST['delete_user'])){
				if(!empty($user['id']))
					if($user['right']['*']>1)
						$this->parent->error('Нехорошо удалять пользователя-администратора');
					else
						$this->parent->delRecord(array('id'=>$user['id']));

				$this->parent->go($this->parent->curl());
			}

			$changed=false;
			if($right!=ppi($_POST['us_right']))
			{
				switch ($_POST['us_right']){
					case 1: // no rights
						$user['right']=array('*'=>0);
						break;
					case 2:
						$user['right']=array('*'=>1);
						break;
					case 3:
						$user['right']=array('*'=>1027);
						break;		
				}
				if ($right<=1){
					// письмо об активации аккаунта
					$to=pps($user['cust_EMAIL']);
					if(!empty($to)){
						$subj='Ваш аккаунт активирован на сайте "'.$_SERVER['SERVER_NAME'].'"';
						$from=pps($this->parent->getPar('mail_useradmin'),
							pps($this->parent->getPar('mail_admin'),
							'art@xilen.ru'));
						$mail=new html_mime_mail(
				   			pp($from,'From: ',"\r\n").
				   			'X-Mailer: PHP/' . phpversion()
						);
						$mail->add_html(sprintf('
							Ваша регистрация на сайте "%s" завершена<br>
							&nbsp;login: "%s"<br>
							&nbsp;password: "%s"<br>
							'
							,$_SERVER['SERVER_NAME']
							,pps($user['name'])
							,pps($user['password'])));
							
						$mail->build_message('win');
						$mail->send(  $to, $subj);
					}
					
				}
				
				$changed=true;
			}
			foreach($this->fields as $v=>$vv){
				if(isset($_POST[$v])){
					$user[$v]=trim(strip_tags($_POST[$v]));
					$changed=true;
				}
			}
			if($changed){
				$this->parent->writeRecord($user);
			}	

			$this->parent->go($this->parent->curl());
		}
		$form->var['us_right']=$right;
		$form->var['cust_TYPE']=ppi($xpar['cust_TYPE'],1);
		$form->var['cust_MANAGER']=pps($xpar['cust_MANAGER']);
		return smart_template(array(ADMIN_TPL,'theheader'),array(
		'header'=>$this->getPluginName(),
		'data'=>$form->getHtml(' ')));
	}
	
}
?>