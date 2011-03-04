<?php
// Функции. Можно вынести в дpугой файл.

class html_mime_mail {
  var $headers;
  var $multipart;
  var $mime;
  var $html;
  var $parts = array();

function html_mime_mail($headers="") {
    $this->headers=$headers;
}

function add_html($html="") {
    $this->html.=$html;
}

function mail_header($header,$from,$fio=''){
	if(empty($from)) return '';
	if($fio!=''){
		$xfio=convert_cyr_string($fio, "w","k");
		if($fio!=$xfio)
			$fio='=?koi8-r?B?'.base64_encode($xfio).'?=';
		$from=$fio.' <'.$from.'>';		
	}
	return $header.$from."\r\n";
}

function build_html($orig_boundary,$kod) {
    $this->multipart.="--$orig_boundary\n";
    if ($kod=='w' || $kod=='win' || $kod=='windows-1251') $kod='windows-1251';
    else $kod='koi8-r';
    $this->multipart.="Content-Type: text/html; charset=$kod\n";
 //   $this->multipart.="BCC: sergekoriakin@mail.ru\n";
    $this->multipart.="Content-Transfer-Encoding: Quot-Printed\n\n";
    $this->multipart.="$this->html\n\n";
}


function add_attachment($path="", $name = "", $c_type="application/octet-stream") {
    if (!file_exists($path.$name)) {
      $this->add_html( "File $path.$name dosn't exist.");
      return;
    }
    $fp=fopen($path.$name,"r");
    if (!$fp) {
      $this->add_html( "File $path.$name coudn't be read.");
      return;
    }
    $file=fread($fp, filesize($path.$name));
    fclose($fp);
    $this->parts[]=array("body"=>$file, "name"=>$name,"c_type"=>$c_type);
}

function add_new_html($name,$html = "", $c_type="text/html") {
    $this->parts[]=array("body"=>$html, "name"=>$name,"c_type"=>$c_type);
}

function build_part($i) {
    $message_part="";
    $message_part.="Content-Type: ".$this->parts[$i]["c_type"];
    if ($this->parts[$i]["name"]!="")
       $message_part.="; name = \"".$this->parts[$i]["name"]."\"\n";
    else
       $message_part.="\n";
    $message_part.="Content-Transfer-Encoding: base64\n";
    $message_part.="Content-Disposition: attachment; filename = \"".
       $this->parts[$i]["name"]."\"\n\n";
    $message_part.=chunk_split(base64_encode($this->parts[$i]["body"]))."\n";
    return $message_part;
}


function build_message($kod) {
    $boundary="=_".md5(uniqid(time()));
    $this->headers.="MIME-Version: 1.0\n";
    $this->headers.="Content-Type: multipart/mixed; boundary=\"$boundary\"\n";
    $this->multipart="";
    $this->multipart.="This is a MIME encoded message.\n\n";
    $this->build_html($boundary,$kod);
    for ($i=(count($this->parts)-1); $i>=0; $i--)
      $this->multipart.="--$boundary\n".$this->build_part($i);
    $this->mime = "$this->multipart--$boundary--\n";
}

function send( $to, $subject="", $headers="") {

	// тему в koi8
	$subject = '=?koi8-r?B?'.base64_encode(convert_cyr_string($subject, "w","k")).'?=';

	return @mail($to,$subject,"",$this->headers.$this->mime);
}

function sendSMTP($server, $to, $from, $subject="", $headers="") {
	global $engine;
    $headers="To: $to\nFrom: $from\nSubject: $subject\n".$this->headers;
    $fp = fsockopen($server, 25, &$errno, &$errstr, 30);
    if (!$fp) {
    	if($engine) {
    		$engine->error("Server $server. Connection failed: $errno, $errstr");
    		return;
    	}
    }
    fputs($fp,"HELO $server\n");
    fputs($fp,"MAIL FROM: $from\n");
    fputs($fp,"RCPT TO: $to\n");
    fputs($fp,"DATA\n");
    fputs($fp,$this->headers);
    if (strlen($headers))
      fputs($fp,"$headers\n");
    fputs($fp,$this->mime);
    fputs($fp,"\n.\nQUIT\n");
    while(!feof($fp))
      $resp.=fgets($fp,1024);
    fclose($fp);
  }
}

class tunnel_mail extends html_mime_mail {

	function send( $to, $subject="", $headers="") {
		global $engine;
		$subject = '=?koi8-r?B?'.base64_encode(convert_cyr_string($subject, "w","k")).'?=';
		// тунель через другой сайт
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL,$engine->getPar('mail_tunnel','http://test-x.ru/airis/admin/index.php?do=ajax_sendmail'));
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, 
		"to=".urlencode($to)."&subj=".urlencode($subj)."&body=".$this->headers.$this->mime);
		//--- Start buffering
		//ob_start();
		curl_exec ($ch);
		//--- End buffering and clean output
		//ob_end_clean();
		curl_close ($ch); 
	
	}

}
	

?>