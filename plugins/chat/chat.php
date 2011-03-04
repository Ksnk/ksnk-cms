<?php
/**
 * чат-плагин
 * простой чат на простые разговоры,
 * онлайн поддержка
 */
//<%  
// включение плагина в список автоматических плагинов
$auto_plugins[]='simpleChat';
 point_start('user_rule');//%>
				array('id'=>103,'text'=>'клиент чата'),
//<% point_start('plugin_body'); // %>

define ('CHAT_TPL','tpl_chat');

class simpleChat extends ml_plugin {
	
	function simpleChat(&$parent){
		parent::ml_plugin($parent);
		parent::_init(array(
			'title'=>'Горячая линия'
			,'perpage'=>100
			,'prefix'=>'si')
		);
	}
	
	/**
	 * интерфейсная функция - зарегистрироваться в системе чата
	 */
	function _login(){
		$form=new form('login');
		$form->scanHtml(smart_template(array(CHAT_TPL,'login'),
			array(
				'cansave'=>defined('LOGIN_CANSAVE')?LOGIN_CANSAVE:true,
				'error'=>pps($_SESSION['errormsg']))));
		if($form->handle()){
/*		if(!isset($this->parent->right)){
				$this->parent->ffirst('auth_check','_CHAT_USER_','',true);
			}
*/			
			if(isset($this->parent->right)){
				$this->parent->right->apply_rights(right_CHAT);
			}
		
			$this->parent->go($this->parent->curl());
		}
		return $form->getHtml(' ');
	}
	
	function do_simpleChat(){
		$this->parent->tpl=CHAT_TPL;
		if(!$this->parent->has_rights(right_CHAT))
			return $this->_login();
		
		if(!$this->parent->has_rights(right_WRITE))
			return smart_template(array(CHAT_TPL,'admin'),
				array());
				
		return smart_template(array(CHAT_TPL,'simple'),
				array());		
	}
	
	/**
	 * послать сообшение.
	 * Enter description here ...
	 */
	function do_sendmess(){
	}

	function do_create($killall=true){
		if(!$this->parent->has_rights(right_WRITE))
			return $this->parent->ffirst('_loginform');
		if($killall){
			$this->database->query('DROP TABLE IF EXISTS ?_chat;');
			$this->database->query('DROP TABLE IF EXISTS ?_chatrooms;');
			$this->database->query('DROP TABLE IF EXISTS ?_chatclients;');
		}
		$this->database->query("CREATE TABLE ?_chat (
`from` INT  NOT NULL ,
`to`   INT  NOT NULL ,
`time` TIMESTAMP,
`room_id` INT  NOT NULL ,
KEY `room_id`(`room_id`), 
KEY `from`(`from`),
KEY `to`(`to`)
);");
		$this->database->query("CREATE TABLE ?_chatrooms (
`room_id` INT NOT NULL AUTO_INCREMENT ,
`name`  VARCHAR( 100 ) NOT NULL ,
PRIMARY KEY ( `room_id` )
);");	
		$this->database->query("CREATE TABLE ?_clientonline (
`client_id` INT NOT NULL AUTO_INCREMENT ,
`client`  VARCHAR( 100 ) NOT NULL ,
`time`  TiMESTAMP ,
PRIMARY KEY ( `client_id` ),
KEY `client`(`client`)
);");	
	}
	
	function get_parameters($par){
		$par['list'][]=array('sub'=>'RSS','title'=>'Количество новостей в RSS','name'=>'rssfeed_num');
		$par['list'][]=array('title'=>'Заголовок канала','name'=>'rss_title');
		$par['list'][]=array('title'=>'Описание канала','name'=>'rss_descr');
		$par['list'][]=array('title'=>'Количество слов в тексте новости','name'=>'rssfeed_words');
	}
	
	function admin_simpleChat() {
		if(!$this->parent->has_rights(right_WRITE)){
			$this->parent->error('Нужно авторизоваться!!!!!');
			return ' ';
		}
		
		return
		smart_template(array(ADMIN_TPL,'theheader'),array('header'=>"Горячая линия",
		'data'=>$this->parent->ffirst('do_siteparam',__CLASS__)));
	}
	
}
//<%point_finish('plugin_body');  %>