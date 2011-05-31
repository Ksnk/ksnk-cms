<?php
/**
 * this file is created automatically at "18 May 2011 0:48". Never change anything, 
 * for your changes can be lost at any time.  
 */
include_once TEMPLATE_PATH.DIRECTORY_SEPARATOR.'tpl_base.php';

class tpl_jusers extends tpl_base {
function __construct(){
$this->macro=array();
$this->macro['toplogin']=array($this,'_'.'toplogin');
$this->macro['login']=array($this,'_'.'login');
$this->macro['userinfo']=array($this,'_'.'userinfo');
$this->macro['authform']=array($this,'_'.'authform');
$this->macro['qathanks']=array($this,'_'.'qathanks');
$this->macro['line']=array($this,'_'.'line');
$this->macro['onlinereg']=array($this,'_'.'onlinereg');
}
function _toplogin(&$namedpar,$root=0,$user=0,$error=0){
extract($namedpar);
$result='        <div style="margin-top:17px;">
';
if( (isset($user["id"]) && !empty($user["id"])) ) {
$result.=' '
    .'<a class="login" href="'
    .($root).('logout')
    .'" title="Пользователь \''
    .htmlspecialchars((isset($user["name"])?$user["name"]:""))
    .'\'">Выход</a>
<div class="fills" style="top: 50px; right: 0px; position: absolute; overflow: hidden; height: 80px; ">
Вы зарегистрированы как \''
    .htmlspecialchars((isset($user["name"])?$user["name"]:""))
    .'\'</div>
';
} else {
$result.='<a class="login" href="'
    .($root).('login')
    .'" onclick="return showloginbar();">Вход</a>
';
if( $error ) {
$result.='<div class="red fills" style="top: 50px; right: 0px; position: absolute; overflow: hidden; height: 80px; ">
            '
    .$error
    .'</div>';
};    	
$result.='<div class="fills" id="loginbar" style="top: 30px; right: 0px; position: absolute; overflow: hidden;  display: none; height: 80px; ">
    <form method="post" href="">
    <div style="padding:15px 10px;width:160px;">
    <div style="float:right;margin-top:15px;"><input class="submit" align="middle" type="image" src="'
    .($root).('img/search1.gif')
    .'"></div>
    <input alt="имя пользователя" title="имя пользователя" id=\'login\' name="login_name" type="text"><br>
    <input alt="пароль" title="пароль" name="login_pass" type="password"><br>
        <a class="login" href="'
    .($root).('onlinereg')
    .'">Нет логина?</a>
    </div></form>
</div>

<script type="text/javascript">
    $(function(){
    menu(\'#loginbar\',{
        show:function(){$(this).show(\'hormal\',function(){
            $(\'#login\').focus();
        })},
        hide:function(){$(this).hide(\'hormal\')}
    });
    })
window.showloginbar=function (){
var x=$(\'#loginbar\')[0];
if(!x.shown)
    x.show_menu();
return false;
};
</script>
';
};    	
$result.='</div>
';
return $result;
}

function _login(&$namedpar,$error=0,$cansave=0,$names=0,$root=0){
extract($namedpar);
$result='<form action="" method="POST" name="login">
<div class="red">'
    .$error
    .'</div>
    <table height="400" style="width:60%;height:1px;" class="table tahoma ctext">
<tr>
<td style="padding:10px;" colspan="2">
 <p style="font-size:14px;"> Если у вас есть аккаунт на сайте
        - введите имя и пароль, если нет - <a  href="'
    .($root).('onlinereg')
    .'">зарегистрируйтесь</a>, пожалуйста</p></td>
</tr>
	<tr class="borderdn odd">
			<td class="pad10 text">Логин</td><td class="pad10 text"> <input type="text" class="input long" name="login_name"> </td>
		</tr>
	<tr class="borderdn even">
			<td class="pad10 text">Пароль</td><td class="pad10 text"> <input type="password" name="login_pass" class="input long"> </td>
		</tr>
<tr style="border: 1px solid #dddddd; border-width:1px 0;">
<td class="text pad10"></td><td class="pad10 text align_left"><div class="button" style="width:100px;"> <input type="submit" value="Отправить"> </div></td>
</tr>
</tbody></table>

</form>
';
return $result;
}

function _userinfo(&$namedpar,$visible=0,$user=0,$names=0,$root=0){
extract($namedpar);
$result='<b>'
    .(isset($user["first_name"])?$user["first_name"]:"");
if( (isset($visible["patronymic"]) && !empty($visible["patronymic"])) ) {
$result.=' '
    .(isset($user["patronymic"])?$user["patronymic"]:"");
};    	
$result.=' '
    .(isset($user["surname"])?$user["surname"]:"")
    .'</b>
<span style="display:block;clear:both;margin-top:10px;"></span>
';
if( (isset($user["avatar"]) && !empty($user["avatar"])) ) {
$result.='<img style="float:left; margin-right:15px;" onload="checkImg(this,100,100)" width=100 hight=100 src="'
    .($root).((isset($user["avatar"])?$user["avatar"]:""))
    .'">
';
};    	
$result.='';
$loop_array=ps($user["visible"]);
if (!empty($loop_array)){
foreach($loop_array as $v){
$result.='';
if( ((isset($user[$v]) && !empty($user[$v]))) && (!(($v)==('patronymic'))) ) {
$result.='<b>'
    .(isset($names[$v])?$names[$v]:"")
    .':</b> '
    .(isset($user[$v])?$user[$v]:"")
    .'<br/>';
};    	
$result.='';
}}
;    	
$result.='
';
return $result;
}

function _authform(&$namedpar,$user=0 ,$userhello=0 ){
extract($namedpar);
$result='
	<div id="auth" onMouseOut="hide_auth_pre();"  onMouseOver="show_auth();">
		<form name="login" method="POST" style="margin:0;">
			<table style="table-layout:fixed;margin:0px;" class="long size11 tahoma">
				<col width="auto">
				<col width="30">
				<tr style="border-left:solid 1px #1f4fb7;border-right:solid 1px #1f4fb7;">
					<td colspan=2 style="background:#1f4fb7;color:#FFFFFF;font-weight:bold;letter-spacing:1px;height:28px;vertical-align:middle;padding-left:20px;">
					<p>АВТОРИЗАЦИЯ</p></td>
				</tr>
		';
if( (isset($user["name"]) && !empty($user["name"])) ) {
$result.='				<tr class="out_hide">
					<td colspan="2" style="border-bottom:solid 1px #d7dee9;border-right:solid 1px #d7dee9;">
						<a href="?do=profile">Профиль пользователя</a>
					</td>
				</tr>
				<tr class="out_hide">
					<td colspan="2" style="padding-bottom:16px;border-bottom:solid 1px #d7dee9;border-right:solid 1px #d7dee9;">
						<a href="?do=logout"
	 onclick="if(!confirm(\'Хотите закончить сеанс?\')) return false;">Завершение сеанса &laquo;'
    .(isset($user["name"])?$user["name"]:"")
    .'&raquo;</a>
					</td>
				</tr>
		';
} else {
$result.='				<tr class="ugol" style="border-left:solid 1px #1f4fb7;">
					<td colspan=2 style="height:7px;background:url(img/auth_form_bg.gif) transparent no-repeat top left;">
					</td>
				</tr>
				<tr class="out_hide">
				<td style="padding-top:22px;"><input name="login_name" type="text"></td>
				<td style="border-right:solid 1px #d7dee9;"></td>
				</tr>
				<tr class="out_hide">
					<td><input name="login_pass" type="password"></td>
					<td style="border-right:solid 1px #d7dee9;"><input type="submit" style="border:0;padding:0;margin:0;width:12px; height:19px; background:url(img/button2.gif);" value="&nbsp;"></td>
				</tr>
				<tr class="out_hide">
					<td colspan="2" style="border-right:solid 1px #d7dee9;">
						<a href="?do=onlinereg">Регистрация</a>
					</td>
				</tr>
				<tr class="out_hide">
					<td colspan="2" style="padding-bottom:16px;border-right:solid 1px #d7dee9;">
						<a href="?do=writeus">забыли пароль?</a>
					</td>
				</tr>
		';
};    	
$result.='			</table>
		</form>
	</div>
';
return $result;
}

function _qathanks(&$namedpar,$url=0 ,$result=0 ,$user=0 ){
extract($namedpar);
$result='<div class="para tahoma ctext link">
'
    .'<p>Вы успешно зарегистрированы. Подтверждение регистрации будет в течение 24 часов. 
</p><p>Нажмите на <a href="'
    .$url
    .'">ссылку</a> для возврата.
</p>
<p class="red size11">'
    .$result
    .'</p>
</div>
';
return $result;
}

function _line(&$namedpar,$title=0,$name=0,$star=0 ,$type='text',$user=0 ){
extract($namedpar);
$result='<tr class="even borderdn">
 <td class="pad10 text">'
    .$title;
if( $star ) {
$result.='<span class="red">'
    .$star
    .'</span>';
};    	
$result.='</td><td  class="pad10 text"><input class="input long"  type="'
    .(isset($par['typpe'])?$par['typpe']:"")
    .'" name="'
    .$name
    .'"></td>
</tr>
';
return $result;
}

function _onlinereg(&$namedpar,$fields=0 ,$error=0 ,$result=0 ,$user=0 ,$root=0 ){
extract($namedpar);
$result='<form action="" enctype="multipart/form-data" method="POST" name="onlinereg">
<style>
.table td.pad10 {
 padding-top:10px;
 padding-bottom:10px;
}
.table input.long, textarea.long {width:400px;}
</style>
<div class="align_center" style="padding-top:60px;">
<table class="table tahoma ctext" style="width:80%;height:1px;" height="613">
<tr>
';
if( $error ) {
$result.='<td colspan=2>
 <p class="red" style="font-size:16px;">'
    .$error
    .'</p></td>
</tr>';
};    	
$result.='<td colspan=2 style="padding:10px;">
 <p style="font-size:14px;"><span class="red">*</span> - Поля, обязательные для заполнения</p></td>
</tr>
';
$loop2_array=ps($fields);
$loop2_cycle=array(' odd',' even');
if (!empty($loop2_array)){
foreach($loop2_array as $field){
$result.='	<tr class="borderdn'
    .$this->loopcycle($loop2_cycle)
    .'">
	';
if( ((isset($field["type"])?$field["type"]:""))==('password') ) {
$result.='		<td class="pad10 text">'
    .(isset($field["title"])?$field["title"]:"");
if( (isset($field["star"]) && !empty($field["star"])) ) {
$result.='<span class="red">*</span>';
};    	
$result.='</td><td  class="pad10 text"><input class="input long"  type="'
    .(isset($field["type"])?$field["type"]:"")
    .'" name="'
    .(isset($field["name"])?$field["name"]:"")
    .'"></td>
	';
} elseif( ((isset($field["type"])?$field["type"]:""))==('title') ) {
$result.='		<td class="pad10 text" colspan=2><b>'
    .(isset($field["title"])?$field["title"]:"")
    .'</b></td>
	';
} elseif( ((isset($field["type"])?$field["type"]:""))==('textarea') ) {
$result.='		<td class="pad10 text">
		<input style="float:right;" title="показывать '
    .htmlspecialchars((isset($field["title"])?$field["title"]:""))
    .'" type="checkbox" value="1" name="show_'
    .(isset($field["name"])?$field["name"]:"")
    .'">
		'
    .(isset($field["title"])?$field["title"]:"");
if( (isset($field["star"]) && !empty($field["star"])) ) {
$result.='<span class="red">*</span>';
};    	
$result.='</td>
		<td class="pad10 text"><textarea name="'
    .(isset($field["name"])?$field["name"]:"")
    .'" style="height:60px;" rows=5 cols=40 class="long input"></textarea></td>
	';
} elseif( ((isset($field["type"])?$field["type"]:""))==('avatar') ) {
$result.='		';
if( !((isset($user["avatar"]) && !empty($user["avatar"]))) ) {
$result.='			 <td class="pad10 text">Фото (аватар)</td><td  class="pad10 text"><input class="input long" type="file" name="avatar"></td>
		';
} else {
$result.='			 <td class="pad10 text">сменить аватар </td><td  class="pad10 text">
			 <input class="input long" type="file" name="avatar"></td>
			</tr>
			<tr class="even borderdn">
			 <td class="pad10 text align_center" colspan=2>
			 <img onload="chkImg(80,80)" width=80 height=80 src="'
    .($root).((isset($user["avatar"])?$user["avatar"]:""))
    .'" alt="'
    .htmlspecialchars((isset($user["name"])?$user["name"]:""))
    .'">
		</td>
		';
};    	
$result.='	';
} else {
$result.='		<td class="pad10 text">';
if( !((isset($field["nocheck"]) && !empty($field["nocheck"]))) ) {
$result.='		<input style="float:right;" title="показывать '
    .htmlspecialchars((isset($field["title"])?$field["title"]:""))
    .'" value="1" type="checkbox" name="show_'
    .(isset($field["name"])?$field["name"]:"")
    .'">';
};    	
$result.='		'
    .(isset($field["title"])?$field["title"]:"");
if( (isset($field["star"]) && !empty($field["star"])) ) {
$result.='<span class="red">*</span>';
};    	
$result.='</td><td  class="pad10 text"><input class="input long"  type="text" name="'
    .(isset($field["name"])?$field["name"]:"")
    .'"></td>
	';
};    	
$result.='	</tr>
';
}}
;    	
$result.='';
if( !($user) ) {
$result.='	<tr class="even">
	<td class="pad10 text">Введите номер, изображенный на картинке:<br></td><td class="text"><img src="captcha.php" style="float:right;" alt="" />
	<input  class="input" type="pad10 text" name="captcha" /></td>
	</tr>
';
};    	
$result.='<tr style="border: 1px solid #dddddd; border-width:1px 0;">
<td class="text pad10"></td><td class="pad10 text align_left"><div style="width:100px;" class="button"><input type="submit" value="Отправить"></div></td>
</tr>
</table>
</div>
</form>
';
return $result;
}

function _ (&$par){
$result='
'
    .'
'
    .'
'
    .'
'
    .'
';
return $result;
}

}
