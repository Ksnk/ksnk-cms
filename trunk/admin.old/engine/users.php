<?php
/**
 * ������ ��� �������������� ������ � ������ � ���������� ������ �������
 */

define ('USERS_TPL','tpl_users');

class users extends plugin {
	
	var $default_rights = 0;
/**
 * ������ ����� ������������� � ��������������� �������
 * 1 - ������,  2 - ������, 128 - �����������1, 1024 - �����
 *
 * @var array
 */
    static $USERS=array(
        '�� ���������'=>0,
		'������������'=>1,
        '�������������'=>1027,
        '������� ����������'=>129,
    );

	var $fields=array(
		'name'=>'��� ������������',
		'password'=>'������ ������������',
        // ������ ����������
		'surname'=>'�������',
		'first_name'=>'���',
		'patronymic'=>'��������',
		'avatar'=>false,
		'birthday'=>'���� ��������',
		'address'=>'����� ����������',
		// ���������� �� ����������� ?? wtf
		'cust_ORGANISATION'=>'����������',
		'cust_ADDRESS'=>'�����',
		'cust_INFO'=>'���������� ����������',
        'cust_FIO'=>'���������� ����',
//		'cust_POSITION'=>'���������',
		
		'cust_EMAIL'=>'E-mail',		
		'cust_PHONE'=>'�������',
//		'cust_POSTADDR'=>'�������� �����',
		'cust_ADDITIONALINFO'=>'�������������� ����������',

        // ���� ��� ���������� ������ ���  ������������ �������
        'cust_BANK'=>'�������� �����',
        'cust_BANK_INN'=>'��� �����',
        'cust_BANK_KPP'=>'��� �����',
        'cust_BANK_OREDER'=>'� �����',
        'cust_BANK_KORSCHET'=>'� ������������������ �����',
        'cust_BANK_BIK'=>'��� �����',
        'cust_BANK_OKG'=>'O�� �����',

		'right'=>false, // �������������� ���������
		'type'=>false, // �������������� ���������
		'id'=>false, // �������������� ���������
		'record'=>false, // �������������� ���������
		'new'=>false, // �������������� ���������
		'cust_TYPE'=>USER_TYPE,
		/*
		'cust_MANAGER'=>'<span class="red">e-mail ��������� �����</span>',*/
	);
	
	function users(&$parent){
		plugin::plugin($parent);
		if(defined('IS_ADMIN')){
            $list=array('');
            $i=0;
            foreach(self::$USERS as $k=>$v)
                $list[]=array('id'=>$v,'text'=>$k);
		    $this->parent->par['dd_menu'][]=array(
                'name'=>'xrights',
                'list'=>$list
            );
		}
		
	}
	
	function get_ParamNames(){
		return $this->fields;
	}

	function getPluginName(){
		return 	pps($this->pluginname,'������������');
	}

	/**
	 * callback ���������� ������� �� �����
	 */
	function _user_sort(&$aa, &$bb) {
		return strcasecmp($aa["name"],$bb["name"]);
	}

    function getUserList(){
        $users=$this->parent->readRecords(
            array('record'=>'user'),4000,18000
        );
        usort($users,array($this,'_user_sort'));
		return $users;
    }

	function user_list($columns=0){
        $users=$this->getUserList();
		return $this->parent->_tpl('tpl_jusers','_user_list'
			,array(
				'columns'=>ppi($columns,3)
				,'list'=>array_values($users))
		);
	}
	
	function do_profile(){
		return $this->do_onlinereg('profile');
	}
	
	function do_onlinereg($profile=null){
		if(empty($profile))
			$profile='onlinereg';
		
		$this->parent->sessionstart();
		$newuser=empty($this->parent->user);
		
		$form=new form('onlinereg');
        $form->upload_dir=ROOT_DIR.'/avatar';
		if($newuser){
			$this->pluginname='�����������';
			$fields=array(
			array('name'=>'newlogin','title'=>'�����','star'=>true,'nocheck'=>true),
			array('name'=>'password','title'=>'������','star'=>true,'type'=>'password'),
			array('name'=>'newpassword2','title'=>'������������� ������','star'=>true,'type'=>'password')
            );
        } else {
            $this->pluginname='������� ������������';
            $fields=array();
        }
        $fields=array_merge($fields,array(
        	array('name'=>'surname','title'=>'�������','star'=>true,'nocheck'=>true),
			array('name'=>'first_name','title'=>'���','star'=>true,'nocheck'=>true),
			array('name'=>'patronymic','title'=>'��������','nocheck'=>true),
			array('type'=>'avatar','nocheck'=>true),
			array('name'=>'birthday','title'=>'���� ��������','nocheck'=>true),
			array('name'=>'address','title'=>'����� ����������','nocheck'=>true),
			array('title'=>'����� ������','type'=>'title','nocheck'=>true),
			array('name'=>'cust_ORGANISATION','title'=>'����������','nocheck'=>true),
			array('name'=>'cust_ADDRESS','title'=>'�����','type'=>'textarea','nocheck'=>true),
			array('name'=>'cust_INFO','title'=>'���������� ����������','type'=>'textarea','nocheck'=>true),
			array('name'=>'cust_POSITION','title'=>'���������','nocheck'=>true),
			array('title'=>'��������','type'=>'title','nocheck'=>true),
			array('name'=>'cust_EMAIL','title'=>'����� (e-mail)','nocheck'=>true),
			array('name'=>'cust_PHONE','title'=>'�������','nocheck'=>true),
			array('name'=>'cust_ADDITIONALINFO','title'=>'�������������� ����������','nocheck'=>true,'type'=>'textarea')
		));

		ml_plugin::setupmenu();

		if (pps($_GET['step'])){
			$x=	$this->parent->_tpl('tpl_jusers','_qathanks'
				,array('result'=>pps($_SESSION['errormsg'])
					,'url'=>pps($_SESSION['qa_returnaddress'],'/')
				)
			);
			unset($_SESSION['errormsg']);
			return $x;
		}
		
		$x=pps($_SERVER['HTTP_REFERER']);
		if ($x && strpos($x,'onlinereg')===false)
			$_SESSION['qa_returnaddress']=$_SERVER['HTTP_REFERER'];

		$form->scanHtml($this->parent->_tpl('tpl_jusers','_onlinereg',
			array('error'=>pps($_SESSION['errormsg']),'fields'=>$fields)));
		if($form->handle()){
			//debug($form);
			if($newuser){
				if (!isset($_SESSION["captcha"]) || $_SESSION["captcha"]!==$form->var["captcha"]) {
					$this->parent->error('������� ������ �����');
					$this->parent->go($this->parent->curl('do').'do='.$profile);
				}	
				if (trim($form->var["password"])!==trim($form->var["newpassword2"])) {
					$this->parent->error('������ �� ���������');
					$this->parent->go($this->parent->curl('do').'do='.$profile);
				}
			} 
			//������������ ����
	/*		$obz = array(
				"surname" => "�� ������� �������",
				"first_name" => "�� ������� ���",
				"patronymic" => "�� ������� ��������",
				"birthday" => "�� ������� ���� ��������",
				"address" => "�� ������� ����� ����������",
				"cust_ORGANISATION" => "�� ������� ����������",
				"cust_EMAIL" => "�� ������� ����������� �����",
				);

			foreach ($obz as $k => $v) {
				if (trim($form->var[$k]) == "") {
					$this->parent->error($v);
					$this->parent->go($this->parent->curl('do').'do='.$profile);
					break;
				}	
			}
	*/
			if (!ereg("^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9_\.\-]+\.[a-zA-Z0-9_\.\-]+$", $form->var["cust_EMAIL"])) {
				$this->parent->error('�� ���������� ����� ����������� �����');
				$this->parent->go($this->parent->curl('do').'do='.$profile);
			}
			if($newuser){
				$key=array('record'=>'user','new'=>1
					,'name'=>trim(strip_tags($form->var['newlogin']))
				);
				$user=$this->parent->readRecord($key);
				if(isset($user['id'])) {
					$this->parent->error('������������ � ����� ������ ��� ����������');
					$this->parent->go($this->parent->curl('do').'do='.$profile);
				}	
				$key=array_merge($key,array(
					'right'=>array('*'=>$this->default_rights))
				);
				$key['password']=$form->var['newpassword'];
			} else {
				$key=$this->parent->user;
			}
			if(!isset($key['visible']))
				$key['visible']=array();
			if(!empty($key['visible']))
				$key['visible']=array_flip($key['visible'])	;
 			foreach($this->fields as $k=>$v){
 				if(isset($_POST[$k])){
					$key[$k]=trim(strip_tags($_POST[$k]));
				}
			}
			$key['visible']=array_keys($key['visible']);
			if($form->havenewfile) {
				$key['avatar'] = toUrl_sf(toUrl($form->var['~avatar']));
			}
			debug($this->fields);
			debug($form->var);
			if(!$newuser){
				$key['id']=$this->parent->user['id'];
				$key['name']=$this->parent->user['name'];
				$key['password']=$this->parent->user['password'];
				$key['right']=$this->parent->user['right'];
			}	
			debug($key);
			$id=$this->parent->writeRecord($key);
		//���� ������
			if($newuser){
				$from=pps($this->parent->getPar('mail_useradmin'),
					pps($this->parent->getPar('mail_admin'),
					'art@xilen.ru'));
				
				$to=pps($this->parent->getPar('mail_admin'),
					'art@xilen.ru');
				
				$cc=$this->parent->getPar('mail_admin2');
                $headers=array();
				
				$subj='����� ������������ �� ����� "'.$_SERVER['SERVER_NAME'].'"';
				$mail=new html_mime_mail($headers,
					pp($from,'From: ',"\r\n").
					pp($cc,'Cc: ',"\r\n").
					pp($to,'Reply-To: ',"\r\n").
		   			'X-Mailer: PHP/' . phpversion()
				);
				$mail->add_html(sprintf(
					'��������������� ����� ������������ �� ����� "%s"<br>
					"%s"',$_SERVER['SERVER_NAME'],$key['name']));
				$mail->build_message('win');
				
				$smtp= $this->parent->getPar('smtp_server');
				$this->parent->sessionclose();
				if(!$smtp)
					$mail->send(  $to, $subj);
				else 
					$mail->sendSmtp($smtp,$to,$from,$subj);	
				$this->parent->go($this->parent->curl('step').'&step=1');
			} else
				$this->parent->go($this->parent->curl('step'));
		}
		if(!empty($this->parent->user)){
			$form->var=array_merge($form->var,$this->parent->user);
			$form->var['newlogin']=$form->var['name'];
		}
		if(!empty($this->parent->user['visible']))
			foreach($this->parent->user['visible'] as $v){
				$form->var['show_'.$v]=1;
			}	
		
		debug($form->var);	
		return $form->getHtml(' ');
	}

	function search($s){
		$s=strtolower($s);

		$users=$this->parent->readRecords(array('record'=>'user'),1000,6000);
		if (empty($users)) return array();

		$result=array(); $lastparent=0;
		
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
					'tag'=>'������������',
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
			$user=$this->parent->readRecord(array('record'=>'user','name'=>'������� ����� ���'));
			//var_dump($user);
			if(!isset($user['id'])){
				$id=$this->parent->writeRecord(array('record'=>'user'
					,'name'=>'������� ����� ���'
					,'password'=>'������� ����� ������'
					,'right'=>array('*'=>(right_READ))));
			} else
				$id=$user['id'];
			$this->parent->go($this->parent->curl('user').'user='.$id);
		}
		$user=pps($_GET['user']);

		if(!empty($user))
			$user=$this->parent->readRecord(array('id'=>$user));
		// ����� ����� ������������ �������� ���� �����.	
		if(!empty($user['new']) && $user['new']) {
			$user['new'] = 0;
			$this->parent->writeRecord($user);
		}

		if(empty($user))
			return 
			smart_template(array(ADMIN_TPL,'theheader'),array(
			'header'=>$this->getPluginName(),
			'data'=>$this->user_list(4)
			));
		$this->parent->menu['left']=array(__CLASS__,'user_list',1);
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
		unset($par['visible']);
		
		$form = new form('admin_user');
		$form->nostore=true;
		// ����� �����
		$xpar=$par;$par=array();
		
		foreach($xpar as $k=>$v){
			$par[]=array(
			'type'=>($k=='cust_TYPE'?'xuser':'common'), //'$k=='cust_MANAGER'?'xmanager':'common')
			'prop'=>$k,
			'val'=>$v,
			'the_text'=>pps($this->fields[$k],$k));
		}
		$right=$user['right']['*'];

		$par[]=array(
			'type'=>'avatar',
			'val'=>pps($user['avatar']),
			'url'=>toUrl(ROOT_PATH.'/'.pps($user['avatar']))
		);
		$par[]=array(
			'type'=>'xrights',
			'val'=>$right
		);
		//debug($par);
		$form->scanHtml($this->parent->_tpl('tpl_jusers','_admin_user',array(
				'list'=>$par,
				'u_right'=>$right,
				'error'=>$this->parent->error()))	
		);
		
		if($form->handle()){
			if(isset($_POST['delete_user'])){
				if(!empty($user['id']))
					if(0!=(right_ADMIN &  $user['right']['*']))
						$this->parent->error('�������� ������� ������������-��������������');
					else
						$this->parent->delRecord(array('id'=>$user['id']));

				$this->parent->go($this->parent->curl());
			}

			$changed=false;
			if($right!=ppi($_POST['us_right']))
			{
                $right=ppi($_POST['us_right']);
                $user['right']['*']=$right;
				if ($right<=1){
					// ������ �� ��������� ��������
					$to=pps($user['cust_EMAIL']);
					if(!empty($to)){
						$subj='��� ������� ����������� �� ����� "'.$_SERVER['SERVER_NAME'].'"';
						$from=pps($this->parent->getPar('mail_useradmin'),
							pps($this->parent->getPar('mail_admin'),
							'art@xilen.ru'));
						$mail=new html_mime_mail(
				   			pp($from,'From: ',"\r\n").
				   			'X-Mailer: PHP/' . phpversion()
						);
						$mail->add_html(sprintf('
							���� ����������� �� ����� "%s" ���������<br>
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
		//return $_POST['us_right'];
		return smart_template(array(ADMIN_TPL,'theheader'),array(
			'header'=>$this->getPluginName(),
			'data'=>$form->getHtml(' ')));
		}
	
}
?>