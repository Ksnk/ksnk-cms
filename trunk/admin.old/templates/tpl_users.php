<?php
class tpl_users extends tpl {

function _(&$par){		
		return '<!--  ////////////////// плагин Пользователи /////////////////////// -->

'.tpl::_a($par['admin_user'],array('tpl_users','admin_user')).'

'.tpl::_a($par['user_list'],array('tpl_users','user_list'));
}

function admin_user(&$par){		
		return '<form action="" method="POST" name="admin_user">
<span style="color:red;font-size:16px;">'.(isset($par['error'])?$par['error']:'').'</span>
<input type=\'hidden\' class="del" name="del">
<table class="thetable long tahoma size11" >
<tr>
<th class="bblue">свойство</th>
<th class="bblue">значение</th>
</tr>
'.tpl::_a($par['list'],array('tpl_users','admin_user_list')).'
<tr><th class="align_center" colspan=3 >
	<input type="submit" class="button savebutton" disabled="disabled" value="сохранить">
	<input type="submit" class="button" name="delete_user" value="Удалить">
</th></tr>
</table>
</form>';
}

function admin_user_list(&$par){		
		return tpl::_a($par['common'],array('tpl_users','admin_user_list_common')).'
'.tpl::_a($par['avatar'],array('tpl_users','admin_user_list_avatar')).'
'.tpl::_a($par['xrights'],array('tpl_users','admin_user_list_xrights')).'
'.tpl::_a($par['xuser'],array('tpl_users','admin_user_list_xuser')).'
'.tpl::_a($par['xmanager'],array('tpl_users','admin_user_list_xmanager'));
}

function admin_user_list_common(&$par){		
		return '<tr class="'.tpl::_d($par['trclass'],'odd').'" id="us_'.(isset($par['prop'])?$par['prop']:'').'">
<td ><b>'.(isset($par['the_text'])?$par['the_text']:'').'</b></td>
<td class="text_edit" id="'.(isset($par['prop'])?$par['prop']:'').'" >'.(isset($par['val'])?$par['val']:'').'</td>
</tr>';
}

function admin_user_list_avatar(&$par){		
		return '<tr class="'.tpl::_d($par['trclass'],'odd').'" >
<td ><b>Фото (аватар)</b></td>

<!--<td style="" class="nopage uploader action_both" id="avatar"><div style="display:none;"><div> <input type="button" onclick="ReplaceImg(this)"> </div></div> <input name="avatar" type="text" value="'.(isset($par['val'])?$par['val']:'').'"  style="display:none;"> <img src="'.tpl::_d($par['val'],'img/1x1t.gif').'" alt="" onload="checkImg(this,80,60)"></td>-->

<td ><div class="wide long uploader users_avatar">
<input class="long" type="" value="'.(isset($par['val'])?$par['val']:'').'" name="avatar"><br><br>
<img src="../'.tpl::_d($par['val'],'img/1x1t.gif').'" onload="checkImg(this,80,60)" />
</div></td>
</tr>';
}

function admin_user_list_xrights(&$par){		
		return '<tr class="'.tpl::_d($par['trclass'],'odd').'" >
<td ><b>Права пользователя</b></td>
<td ><div class="wide long xrights">
<input class="long" type="text" name="us_right">
</div></td>
</tr>';
}

function admin_user_list_xuser(&$par){		
		return '<tr class="'.tpl::_d($par['trclass'],'odd').'" >
<td ><b>'.(isset($par['the_text'])?$par['the_text']:'').'</b></td>
<td ><div class="wide long xuser">
<input class="long" type="text" name="'.(isset($par['prop'])?$par['prop']:'').'">
</div></td>
</tr>';
}

function admin_user_list_xmanager(&$par){		
		return '<tr class="'.tpl::_d($par['trclass'],'odd').'" >
<td ><b>'.(isset($par['the_text'])?$par['the_text']:'').'</b></td>
<td ><div class="wide long xmanager">
<input class="long" type="text" name="'.(isset($par['prop'])?$par['prop']:'').'">
</div></td>
</tr>';
}

function user_list(&$par){		
		return '<table class="menu ctext tahoma">
<col width="30%"><col width="30%"><col width="30%">
<tr><td><ul class="menu ctext tahoma">
'.tpl::_a($par['list'],array('tpl_users','user_list_list')).'
</ul></td>
<tr><td colspan=3 style="padding-top:30px;"
>
<a
style="display:block; padding:4px 15px" class="button"
href="'.tpl::_ax(tpl::_export('','curl','user'),array('tpl_users','user_list___curl_user')).'user=0">Добавить</a></td></tr>
</table>';
}

function user_list_list(&$par){		
		return '<li style="cursor:pointer;" onclick="document.location=\''.tpl::_ax(tpl::_export('','curl','user'),array('tpl_users','user_list_list___curl_user')).'user='.(isset($par['id'])?$par['id']:'').'\';" class="'.(isset($par['class'])?$par['class']:'').'">'.(isset($par['name'])?$par['name']:'').'</li>
'.tpl::_b($par['break'],'</ul></td><td><ul class="menu ctext tahoma">','');
}}
?>