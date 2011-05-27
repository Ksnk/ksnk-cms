<?php
/**
 * плагин - Рассылка почты
 */

class massmail extends ml_plugin {
/**
 * Инициализирующая муть..
 *
 * @param engine $parent
 * @return massmail
 */
	function massmail(&$parent){
		parent::ml_plugin($parent);
		$this->head_tpl=array('tpl_massmail','mm_head');
		parent::_init(
		array(
		'title'=>'Почтовая рассылка'
		,'fields'=>array(
					array('адрес','email','text_edit'),
					array('код','code','text_edit'),
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
		$par['list'][]=array('sub'=>'Рассылка','title'=>'Время рассылки','name'=>'time_to_send');
		$par['list'][]=array('title'=>'Дата последней рассылки','name'=>'last_masssend');
		$par['list'][]=array('title'=>'Обратный адрес для письма','name'=>'massmail_return');
		$par['list'][]=array('type'=>'button','title'=>'Разослать сейчас','name'=>'Send'
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
 * Получить из внешнего мира текст для рассылки. 
 * В зависимости от надобностей проекта может быть разным
 * сейчас - выбрать все новости c датой после par[last_masssend]
 *
 * @return string
 * 
 */	
	function getText(){
		return $this->parent->export('news','getNewsAfter',$this->parent->getPar("last_masssend",0));
	}
	
/**
 * разослать немедленно все
 */
	function do_sendnow(){
		// выбрать все адреса из таблицы и разослать по письму
		debug('sending!!!');
		// выбрать все адреса
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
			$subj='Новости с сайта "'.$_SERVER['SERVER_NAME'].'"';
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
	        	$this->error(sprintf("Не удалось отослать письмо по адресу '%s'.",$to));
	        }
		}
		
		$this->parent->setPar('last_masssend',date(DATE_ATOM,time()));
	}

/**
 * убрать регистрацию пользователя. Функция-подтверждение из почтового сообщения.
 * Вызывается ссылкой вида do=unsubscribe&code=xxx
 */		
	function do_unsub(){
		$this->parent->sessionstart();
		$data=$this->data('row',$_GET['id']);
		if(isset($_SESSION['mm_Message'])) {
			$x=$_SESSION['mm_Message'];
			unset($_SESSION['mm_Message']);
		} elseif (empty($data)){
			$x="адрес не найден в базе рассылки.";
		} elseif($data['code']!=$_GET['code']){
			$x="Устаревшая ссылка.";
		} else {
			$x=array(
				sprintf("Вы действительно желаете исключить адрес '%s' из списка рассылки?",$data['email']),
			);
		}
		$form=$this->parent->export('MAIN','SimpleForm',$x);
		if(!is_string($form)){
			$this->data('del',$data['id']);
			$_SESSION['mm_Message']=sprintf("Адрес '%s' исключен из базы рассылки",$data['email']);
			$this->parent->go($this->parent->curl());
		}
		return $form;
	}

	function subscribe($email){
		$email=strtolower($email);
		$res=$this->database->query('select `id` from ?'.$this->base.
			' where `email`=?',$email);
		if(!empty($res)){
			$this->parent->error(sprintf("Адрес '%s' уже содержится в базе",$email));
		} else {	
			$this->data("ins",array('code'=>rand(100000,900000),'email'=>$email));
			return sprintf("Адрес '%s' добавлен в базу",$email);
		}
		return '';
	}
	
/**
 * записать пользователя в базу. Вывод формы регистрации. Используется фенечка - SimpleForm
 */		
	function do_massmail(){
		$this->parent->sessionstart();
		if(empty($_SESSION['mm_Message'])) {
			$x=$_SESSION['mm_Message'];
			unset($_SESSION['mm_Message']);
		} else {
			$x=array(
				"Подпишитесь на расылку наших новостей",
				"E-mail для получения новостей"=>array('email','require'=>true),
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