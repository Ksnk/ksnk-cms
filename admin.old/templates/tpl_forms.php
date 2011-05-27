<?php
class tpl_forms extends tpl {

function _(&$par){		
		return '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Шаблоны форм для разработки вручную</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8">

</head>
<body>

'.tpl::_a($par['login'],array('tpl_forms','login')).'

'.tpl::_a($par['menuedit'],array('tpl_forms','menuedit')).'

</body>';
}

function login(&$par){		
		return '<form action="" method="POST" name="login">
<div class="align_center">
<div class="red">'.(isset($par['error'])?$par['error']:'').'</div>
<table class="thetable tahoma blue size11" style="table-layout:fixed;"><col width="70pt"><col width="160pt"><col width="30pt">
<tr>
	<th  align=\'right\'>Имя:</th>
	<td colspan="2"><input name="login_name" type="text" value="" class="long"></td>
</tr><tr>
	<th  align=\'right\'>пароль:</th>
	<td colspan="2"><input name="login_pass" type="password" value="" class="long"></td>
</tr><tr>
	<th  align=\'right\'>'.tpl::_b($par['cansave'],'','').'</th>
	<td class="glass">'.tpl::_b($par['cansave'],'<input type="checkbox" value="1" name="login_save">','').'</td>
	<td class="align_right"><input type="submit" value="&raquo;"></td>
</tr></table></div>
</form>';
}

function menuedit(&$par){		
		return '<form action="" method="POST" name="menuedit" id="menuedit">
<div style="height:1px;width: 740px;"></div>
<div style="width:100%;">
<div class="red">'.(isset($par['error'])?$par['error']:'').'</div>
<div style="position: relative;">
<div class="long align_center" style="position: relative;">
<table class="compact tahoma blue size16" style="position:absolute;left:35%;width:30%;">
	<tr>
		<th class="red"><u>'.(isset($par['name'])?$par['name']:'').'</u></th>
	</tr>
</table>
</div>

'.tpl::_a($par['has_content'],array('tpl_forms','menuedit_has_content')).'</div>
</div>
<input type="hidden" class="del" name="del"></form>';
}

function menuedit_has_content(&$par){		
		return '<div id="fragment-2" style="padding: 0;" class="tabs-container">
<table class="long blue thetable tahoma size11">
	'.(isset($par['data'])?$par['data']:'').'
	<tr><th class="align_center" >
		<input type="submit" disabled="disabled" class="savebutton button" value="сохранить">
	</th></tr>
</table>
</div>';
}}
?>