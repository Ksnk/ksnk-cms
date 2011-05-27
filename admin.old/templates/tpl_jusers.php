<?php
/**
 * this file is created automatically at "22 May 2011 0:00". Never change anything, 
 * for your changes can be lost at any time.  
 */
include_once TEMPLATE_PATH.DIRECTORY_SEPARATOR.'tpl_base.php';

class tpl_jusers extends tpl_base {
function __construct(){
$this->macro=array();
$this->macro['admin_user']=array($this,'_'.'admin_user');
$this->macro['user_list']=array($this,'_'.'user_list');
}
function _admin_user(&$namedpar,$root=0 ,$list=0 ,$user=0 ,$error=0 ){
extract($namedpar);
$result='<form action="" method="POST" name="admin_user"><div style="height:100%;ovrflow:auto;">
';
if( $error ) {
$result.='<span style="color:red;font-size:16px;">'
    .$error
    .'</span>';
};    	
$result.='<input type=\'hidden\' class="del" name="del">
<table class="thetable long tahoma size11" >
<tr>
<th class="bblue">свойство</th>
<th class="bblue">значение</th>
</tr>
';
$loop2_array=ps($list);
$loop2_cycle=array('odd','even');
if (!empty($loop2_array)){
foreach($loop2_array as $row){
$result.='	<tr class="'
    .$this->loopcycle($loop2_cycle)
    .'"';
if( (isset($row["prop"]) && !empty($row["prop"])) ) {
$result.=' id="us_'
    .(isset($row["prop"])?$row["prop"]:"");
};    	
$result.='">
	';
if( ((isset($row["type"])?$row["type"]:""))==('common') ) {
$result.='		<td ><b>'
    .(isset($row["the_text"])?$row["the_text"]:"")
    .'</b></td>
		<td class="text_edit" id="'
    .(isset($row["prop"])?$row["prop"]:"")
    .'" >'
    .(isset($row["val"])?$row["val"]:"")
    .'</td>
	';
} elseif( ((isset($row["type"])?$row["type"]:""))==('avatar') ) {
$result.='		<td ><div style="float:right;">
		';
if( (isset($row["val"]) && !empty($row["val"])) ) {
$result.='		<img src="'
    .($root).((isset($row["val"])?$row["val"]:""))
    .'" height="80" width="80" onload="checkImg(this,80,60)" />
		';
};    	
$result.='		</div><b>Фото (аватар)</b>
		</td>
		<td ><div class="wide long uploader action_small users_avatar">
		<input class="long" type="" value="'
    .(isset($row["val"])?$row["val"]:"")
    .'" name="avatar"><br><br>
		</div></td>
	';
} elseif( ((isset($row["type"])?$row["type"]:""))==('xrights') ) {
$result.='		<td ><b>Права пользователя</b></td>
		<td ><div class="wide long xrights">
		<input class="long" type="text" name="us_right">
		</div></td>
	';
} elseif( ((isset($row["type"])?$row["type"]:""))==('xuser') ) {
$result.='		<td ><b>{the_text}</b></td>
		<td ><div class="wide long xuser">
		<input class="long" type="text" name="{prop}">
		</div></td>
	';
} elseif( ((isset($row["type"])?$row["type"]:""))==('xmanager') ) {
$result.='		<td ><b>{the_text}</b></td>
		<td ><div class="wide long xmanager">
		<input class="long" type="text" name="'
    .(isset($row["prop"])?$row["prop"]:"")
    .'">
		</div></td>
	';
};    	
$result.='		</tr>
';
}}
;    	
$result.='<tr><th class="align_center" colspan=3 >
	<input type="submit" class="button savebutton" disabled="disabled" value="сохранить">
	<input type="submit" class="button" name="delete_user" value="Удалить">
</th></tr>
</table></div>
</form>
';
return $result;
}

function _user_list(&$namedpar,$list=1,$columns=5){
extract($namedpar);
$result='<div style="height:100%;overflow:hidden;overflow-y:auto;">
<table class="menu ctext tahoma">
';
$loop_array=ps($this->func_range($columns));
if (!empty($loop_array)){
foreach($loop_array as $x){
$result.=' <col width="'
    .(100)/($columns)
    .'%">';
}}
;    	
$result.='';
$cols=$this->func_slice($list,(1)+(((count($list))-(1))/($columns)));    	
$result.='';
$loop2_array=ps($cols[0]);
$loop2_index=0;
if (!empty($loop2_array)){
foreach($loop2_array as $c){
$loop2_index++;
$result.='';
$col=($loop2_index-1);    	
$result.='<tr>
';
$loop3_array=ps($this->func_range($columns));
$loop3_index=0;
if (!empty($loop3_array)){
foreach($loop3_array as $xx){
$loop3_index++;
$result.='';
$u=(isset($cols[($loop3_index-1)])?$cols[($loop3_index-1)]:"");    	
$result.='';
$user=(isset($u[$col])?$u[$col]:"");    	
$result.='<td>';
if( (isset($user["id"]) && !empty($user["id"])) ) {
$result.='<a class="arrow';
if( !($this->func_rights($user)) ) {
$result.=' lock_user';
};    	
if( (isset($user["new"]) && !empty($user["new"])) ) {
$result.=' new_user';
};    	
$result.='" href="'
    .(($this->callex('MAIN','curl','user')).('user=')).((isset($user["id"])?$user["id"]:""))
    .'">';
if( (isset($user["new"]) && !empty($user["new"])) ) {
$result.='*';
};    	
$result.=$this->filter_default((isset($user["name"])?$user["name"]:""),'-----')
    .'</a>
';
};    	
$result.='</td>
';
}}
;    	
$result.='</tr>
';
}}
;    	
$result.='<tr><td colspan='
    .$columns
    .' style="padding-top:30px;"
>Всего: '
    .count($list)
    .' пользовател'
    .$this->func_finnumb($list,'ь','я','ей')
    .'. 
<a
 class="button"
href="'
    .($this->callex('MAIN','curl','user')).('user=0')
    .'">Добавить</a></td></tr>
</table></div>
';
return $result;
}

function _ (&$par){
$result='
'
    .'
';
return $result;
}

}
