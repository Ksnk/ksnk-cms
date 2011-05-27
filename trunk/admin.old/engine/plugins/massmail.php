<?php
/**
 * ������ - �������� �����
 */

class massmail extends ml_plugin {
/**
 * ���������������� ����..
 *
 * @param engine $parent
 * @return massmail
 */
	function massmail(&$parent){
		parent::ml_plugin($parent);
		$this->head_tpl=array('tpl_massmail','mm_head');
		parent::_init(
		array(
		'title'=>'�������� ��������'
		,'fields'=>array(
					array('�����','email','text_edit'),
					array('���','code','text_edit'),
		)
		,'base'=>'_masssend'
		//,'orderbystr'=>' order by `item_order` DESC'
		,'prefix'=>'mm'));
	}
	
	function admin_massmail(){
		return
			smart_template(array(ADMIN_TPL,'theheader'),array(
			'header'=>$this->getPluginName(),
			'data'=>parent::admin_plugin()));		
	}

	function get_parameters($par){
		$par['list'][]=array('sub'=>'��������','title'=>'����� ��������','name'=>'time_to_send');
		$par['list'][]=array('title'=>'���� ��������� ��������','name'=>'last_masssend');
		$par['list'][]=array('title'=>'�������� ����� ��� ������','name'=>'massmail_return');
		$par['list'][]=array('type'=>'button','title'=>'��������� ������','name'=>'Send'
			,'plugin'=>__CLASS__,'function'=>'do_sendnow');
	}
	
	function do_create($killall=true){
		if(!$this->parent->has_rights(right_WRITE))
			return $this->parent->ffirst('_loginform');
		if($killall){
			$this->database->query('DROP TABLE IF EXISTS ?'.$this->base);
		}
		$this->database->query(
		  'CREATE TABLE ?'.$this->base.' ('.
		  ' `id` int(5) NOT NULL auto_increment,
  `email` varchar(255) default NULL,
  `code` varchar(255) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `code` (`code`)
)');
	}
	
/**
 * �������� �� �������� ���� ����� ��� ��������. 
 * � ����������� �� ����������� ������� ����� ���� ������
 * ������ - ������� ��� ������� c ����� ����� par[last_masssend]
 *
 * @return string
 * 
 */	
	function getText(){
		return $this->parent->export('news','getNewsAfter',$this->parent->getPar("last_masssend",0));
	}
	
/**
 * ��������� ���������� ���
 */
	function do_sendnow(){
		// ������� ��� ������ �� ������� � ��������� �� ������
		debug('sending!!!');
		// ������� ��� ������
		$emails=$this->database->select('select * from ?'.$this->base);
		if(empty($emails)) return ;
		
		$text=$this->getText();
		if(empty($text)) return ;
		foreach($emails as $mail)
		if(!empty($mail['email']))
		{
			$xtext=smart_template(array('tpl_massmail','mail'),array(
				'text'=>$text,
				'id'=>$mail['id'],
				'site'=>$_SERVER['SERVER_NAME'].$this->parent->index(),
				'code'=>$mail['code']
			));
				
			$to=pps($mail['email']);
			$subj='������� � ����� "'.$_SERVER['SERVER_NAME'].'"';
			$headers='';
			$from=pps($this->parent->getPar('massmail_return'));
			if(!empty($from)){
				$headers.=html_mime_mail::mail_header('From: ',$from).
				pp($from,'Reply-To: ',"\r\n");
			}
			$mail=new html_mime_mail(
				$headers.
				'X-Mailer: PHP/' . trim(phpversion())."\r\n"
			);
			$res=array();
			$mail->add_html('<h2>'.$subj.'</h2><br>'.$xtext);
			$mail->build_message('win');
			if($mail->send(  $to, $subj)){
				$msg="";
			}
	        else {
	        	$this->error(sprintf("�� ������� �������� ������ �� ������ '%s'.",$to));
	        }
		}
		
		$this->parent->setPar('last_masssend',date(DATE_ATOM,time()));
	}

/**
 * ������ ����������� ������������. �������-������������� �� ��������� ���������.
 * ���������� ������� ���� do=unsubscribe&code=xxx
 */		
	function do_unsub(){
		$this->parent->sessionstart();
		$data=$this->data('row',$_GET['id']);
		if(isset($_SESSION['mm_Message'])) {
			$x=$_SESSION['mm_Message'];
			unset($_SESSION['mm_Message']);
		} elseif (empty($data)){
			$x="����� �� ������ � ���� ��������.";
		} elseif($data['code']!=$_GET['code']){
			$x="���������� ������.";
		} else {
			$x=array(
				sprintf("�� ������������� ������� ��������� ����� '%s' �� ������ ��������?",$data['email']),
			);
		}
		$form=$this->parent->export('MAIN','SimpleForm',$x);
		if(!is_string($form)){
			$this->data('del',$data['id']);
			$_SESSION['mm_Message']=sprintf("����� '%s' �������� �� ���� ��������",$data['email']);
			$this->parent->go($this->parent->curl());
		}
		return $form;
	}

	function subscribe($email){
		$email=strtolower($email);
		$res=$this->database->query('select `id` from ?'.$this->base.
			' where `email`=?',$email);
		if(!empty($res)){
			$this->parent->error(sprintf("����� '%s' ��� ���������� � ����",$email));
		} else {	
			$this->data("ins",array('code'=>rand(100000,900000),'email'=>$email));
			return sprintf("����� '%s' �������� � ����",$email);
		}
		return '';
	}
	
/**
 * �������� ������������ � ����. ����� ����� �����������. ������������ ������� - SimpleForm
 */		
	function do_massmail(){
		$this->parent->sessionstart();
		if(empty($_SESSION['mm_Message'])) {
			$x=$_SESSION['mm_Message'];
			unset($_SESSION['mm_Message']);
		} else {
			$x=array(
				"����������� �� ������� ����� ��������",
				"E-mail ��� ��������� ��������"=>array('email','require'=>true),
			);
		}
		$form=$this->parent->export('MAIN','SimpleForm',$x);
		if(!is_string($form)){
			$_SESSION['mm_Message']=$this->subscribe(strtolower($form->var['email']));
			$this->parent->go($this->parent->curl());
		}
		return $form;
	}	
	
}

?>