<?php
class tpl_login extends tpl {

function _(&$par){		
		return '<!DOCTYPE html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>'.tpl::_d($par['title'],'Администрирование сайта').'</title>
<meta http-equiv="content-type"
	content="text/html; charset=windows-1251">
<META name="description" content="'.(isset($par['desc_words'])?$par['desc_words']:'').'">
<META name="keywords" lang="ru" content="'.(isset($par['key_words'])?$par['key_words']:'').'">

<link rel="icon" href="'.tpl::_ax(tpl::_export('','index'),array('tpl_login','__index')).'/favicon.ico" type="image/x-icon">
<link rel="shortcut icon" href="'.tpl::_ax(tpl::_export('','index'),array('tpl_login','__index')).'/favicon.ico" >
<LINK  rel="stylesheet" type="text/css" href="css/admin.css">
<script type="text/javascript" src="../js/jquery-1.4.2.min.js"></script>
<style type="text/css">
label {
	margin:0px auto;
	display:block; text-align:left; height:30px;width:200px;color:white; 
	font-weight:bold; font-family: tahoma, arial;
	font-size:12px;
	clear:both;
}
input {
	float:right;width:129px;height:20px;background:white;border:0px;
}
</style>
</head>
<body>

<table style="width:100%; height:100%;">
<tr><td style="height:8px;background:rgb(241,241,241);"></td></tr>
<tr><td class="align_center align_middle">
<div style="position:relative;top:-70px; left:0;width:290px;height:316px;background:url(img/xilen2.gif) right 0 no-repeat">
<div style="height:135px;color:red; font-weight:bold;">'.(isset($par['error'])?$par['error']:'').'</div>
<div style="padding:9px 11px; background:rgb(241,241,241);">
<div style="background:url(img/xbg.gif);height:155px;">
<form id="loginform" action="" method="post">
<div style="background:url(img/avtoriz.gif) 28px 14px no-repeat;height:59px;"></div>
<label><input type="text" name="login_name">Имя</label>
<label><input type="password" name="login_pass">Пароль</label>
<input type="hidden" name="form" value="Auth:loginform">
<div style="clear:both;float:right;margin:0px 34px 0 0;" class="state3 part1">
<a href="#" onclick="$(\'#loginform\').submit();"><img alt="Отправить" src="img/arr_4s.gif"></a>
</div>
<input class="hiddensubmit" type="submit" value=">>">
</form>
</div>
</div>
</div>
</td></tr>
<tr><td style="height:8px;background:rgb(241,241,241);"></td></tr>
</table>

<div id="debug"></div>

</body>
</html>';
}}
?>