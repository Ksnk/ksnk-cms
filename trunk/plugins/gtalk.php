<?php
/**
 * 
 * ������ �����������. 
 * �� ��������� �������, ������������-���������� �� ������� �������� 
 *  -- engine::exec('notify',$subscribe,$message);
 *  - ��� ������� �� ��������� ����������� ������� ������� ��, ���� � ���������� ���� ��������������� ����  
  *  
 *  user['email'] - ������ email - ����������� �� �����
 *  user['gtalk'] - ������ gtalk ������� - ����������� �� gtalk
 * 
 */

<% point_start('plugin_body'); %>

class notifygtalk extends plugin {
	
	function do_test(){
		$subscriber=array('gtalk'=>'sergekoriakin@gmail.com');
		$this->notify($subscriber,'Hello! ������ �� ��������� ��������!');
		return 'Ok!';
	}
	
	function notify(&$subscribe,$message){
		if(!empty($subscribe['gtalk'])){

			include_once 'xmpphp/XMPP.php';
			set_time_limit (10);
			
			$conn = new XMPPHP_XMPP('talk.google.com', 5222 
				,'pepsodent.23075@gmail.com', 'ksnk17740481' // �������� ��� ��� �������� talk'�
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
 *  �������� ����������� ����� 
 *  
 */
$this->xml_read(//?>
'<config>
	<files dstdir="$dst/xmpphp/" dir="$env_common/store/gtalk++/xmpphp/xmpphp-0.1rc2-r77/XMPPHP">
	<copy>*.php</copy>
	</files></config>
'); /**/ %>
