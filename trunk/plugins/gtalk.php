<?php
/**
 * 
 * модуль нотификации. 
 * по появлении события, пользователи-подписчики на событие получают 
 *  -- engine::exec('notify',$subscribe,$message);
 *  - все плагины со свойством нотификации обязаны сделать ку, если в сабскрайбе есть соответствующее поле  
  *  
 *  user['email'] - указан email - нотификация по почте
 *  user['gtalk'] - указан gtalk аккаунт - нотификация по gtalk
 * 
 */

<% point_start('plugin_body'); %>

class notifygtalk extends plugin {
	
	function do_test(){
		$subscriber=array('gtalk'=>'sergekoriakin@gmail.com');
		$this->notify($subscriber,'Hello! Привет из солнечной бразилии!');
		return 'Ok!';
	}
	
	function notify(&$subscribe,$message){
		if(!empty($subscribe['gtalk'])){

			include_once 'xmpphp/XMPP.php';
			set_time_limit (10);
			
			$conn = new XMPPHP_XMPP('talk.google.com', 5222 
				,'pepsodent.23075@gmail.com', 'ksnk17740481' // тестовый акк для проверки talk'а
				, 'xmpphp', 'gmail.com', $printlog=true, $loglevel=XMPPHP_Log::LEVEL_INFO);
			
			try {
			    $conn->connect();
			    $conn->processUntil('session_start');
			    $conn->presence();
			    $conn->message($subscribe['gtalk'], $message);
			    $conn->disconnect();
			} catch(XMPPHP_Exception $e) {
			    die($e->getMessage());
			}
		}
	}
}
//<% point_finish();	
/**
 * 
 *  включаем необходимые файлы 
 *  
 */
$this->xml_read(//?>
'<config>
	<files dstdir="$dst/xmpphp/" dir="$env_common/store/gtalk++/xmpphp/xmpphp-0.1rc2-r77/XMPPHP">
	<copy>*.php</copy>
	</files></config>
'); /**/ %>
