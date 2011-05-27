<?php
class tpl_chat extends tpl {

function _(&$par){		
		return '<!DOCTYPE html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>'.tpl::_d($par['title'],'горячая линия').'</title>
<meta http-equiv="content-type"
	content="text/html; charset=windows-1251">
<META name="description" content="'.(isset($par['desc_words'])?$par['desc_words']:'').'">
<META name="keywords" lang="ru" content="'.(isset($par['key_words'])?$par['key_words']:'').'">

<script type="text/javascript" src="js/engine.pack.js"> </script>

<LINK  rel="stylesheet" type="text/css" href="css/chat.css">

<link rel="icon" href="'.tpl::_ax(tpl::_export('','index'),array('tpl_chat','__index')).'/favicon.ico" type="image/x-icon">
<link rel="shortcut icon" href="'.tpl::_ax(tpl::_export('','index'),array('tpl_chat','__index')).'/favicon.ico" >
<style type="text/css">
html, body {overflow:hidden;}
</style>
<script type=\'text/javascript\'>
</script>
</head>
<body>

'.(isset($par['data'])?$par['data']:'').'
'.tpl::_a($par['login'],array('tpl_chat','login')).'
'.tpl::_a($par['admin'],array('tpl_chat','admin')).'
'.tpl::_a($par['simple'],array('tpl_chat','simple')).'
<div id="debug"></div>

</body>

</html>';
}

function login(&$par){		
		return '<form name="login" action="" method="POST">
Представьтесь, пожалуйста
'.(isset($par['error'])?$par['error']:'').'
<input name=\'chat_login\' >
<input type=\'hidden\' name="form" value=\'simpleChat:login\'>
<input type=\'submit\' name="Ok" value="Ok">
</form>';
}

function admin(&$par){		
		return '<input name=\'chat_login\' value=\'admin\'>';
}

function simple(&$par){		
		return '<input name=\'chat_login\' value=\'simple\'>';
}}
?>