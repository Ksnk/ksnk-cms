<?php
/**
 * this file is created automatically at "18 May 2011 12:47". Never change anything, 
 * for your changes can be lost at any time.  
 */
include_once TEMPLATE_PATH.DIRECTORY_SEPARATOR.'tpl_base.php';

class tpl_jorderhistory extends tpl_base {
function __construct(){
$this->macro=array();
$this->macro['searchform']=array($this,'_'.'searchform');
$this->macro['adminlist']=array($this,'_'.'adminlist');
$this->macro['admincust']=array($this,'_'.'admincust');
}
function _searchform(&$namedpar,$data=0 ,$pages=0 ,$users=0 ){
extract($namedpar);
$result='<form name="searchform" action="" method="POST">
<table class="long tahoma thetable" style="margin:20px 0;"><tr>
<td>статус заказа</td><td><select name="status">
<option value="0">Все</option>
<option value="1">Активен</option>
<option value="2">Неактивный</option>
<option value="3">Оплачен</option>
</select></td><td></td>
<td>Способ оплаты</td><td><select name="type">
<option value="0">все</option>
<option value="1">Безналичный</option>
<option value="2">Квитанция</option>
<option value="3">Наличный</option>
</select></td><td></td>
<td>Период</td><td><select name="period">
<option value="0">все</option>
<option value="1">неделя</option>
<option value="2">месяц</option>
<option value="3">год</option>
</select></td>
    ';
if( $users ) {
$result.='<td>пользователи</td><td><select name="user">
    <option value="0">все</option>
    ';
$loop_array=ps($users);
if (!empty($loop_array)){
foreach($loop_array as $user){
$result.='<option value="'
    .(isset($user["userid"])?$user["userid"]:"")
    .'">'
    .htmlspecialchars((isset($user["user"])?$user["user"]:""))
    .'</option>
    ';
}}
;    	
$result.='</select></td>
    ';
};    	
$result.='<td><input type="submit" value="Искать">
</td></tr></table></form>
';
return $result;
}

function _adminlist(&$namedpar,$data=0 ,$pages=0 ,$users=0){
extract($namedpar);
$result='
<form method="POST" action="">
    <div class="pages" >'
    .$pages
    .'</div>
<table class="long thetable tahoma" >
    <tr>
        <th class="bblue align_left">
        <input type="checkbox" class="glass" id="aaa" name="ff">
        <input class="button" type="submit" name="delete" value="Удалить">
        </th>
<th class="bblue">статус заказа<select name="status">
<option value="0">Все</option>
<option value="1">Активен</option>
<option value="2">Неактивный</option>
<option value="3">Оплачен</option>
</select></th>
<th class="bblue">Способ оплаты<select name="type">
<option value="0">все</option>
<option value="1">Безналичный</option>
<option value="2">Квитанция</option>
<option value="3">Наличный</option>
</select></th>
<th class="bblue">Период<select name="period">
<option value="0">все</option>
<option value="1">неделя</option>
<option value="2">месяц</option>
<option value="3">год</option>
</select></th>
    ';
if( $users ) {
$result.='<th class="bblue">пользователи<select name="user">
    <option value="0">все</option>
    ';
$loop_array=ps($users);
if (!empty($loop_array)){
foreach($loop_array as $user){
$result.='<option value="'
    .(isset($user["userid"])?$user["userid"]:"")
    .'">'
    .htmlspecialchars((isset($user["user"])?$user["user"]:""))
    .'</option>
    ';
}}
;    	
$result.='</select></td>
    ';
};    	
$result.='<th class="bblue"><input type="submit" value="Искать">
</th>    </tr>
</table>

<div style="width:100%;height:100%;overflow:auto;">
<table class="blue thetable tahoma size11">

    ';
$loop2_array=ps($data);
$loop2_cycle=array('odd','even');
if (!empty($loop2_array)){
foreach($loop2_array as $row){
$result.='    <tr ><td style="padding:0;" id="tab_'
    .(isset($row["id"])?$row["id"]:"")
    .'">'
    .'        <table class="'
    .$this->loopcycle($loop2_cycle)
    .' fixed long tahoma"><col width="23"><col width="50"><col><col><col><col width="100">
        <tr>
            '
    .'            '
    .'            '
    .'            <th class="nopage align_center" style="padding:0 2px;"><div class="win_max closed  open_close">&nbsp;</div></th>
            <td class="blue"><nobr><label><input type="checkbox" class="glass select" value="'
    .htmlspecialchars((isset($par['l']["url"])?$par['l']["url"]:""))
    .'" name="ff[]">'
    .htmlspecialchars((isset($par['l']["url"])?$par['l']["url"]:""))
    .'</label></nobr></td>
            <td>'
    .(isset($row["date"])?$row["date"]:"")
    .'</td>
            <td>'
    .(isset($row["status"])?$row["status"]:"")
    .'</td>
            <td>'
    .(isset($row["type"])?$row["type"]:"")
    .'</td>
            <td>'
    .(isset($row["cost"])?$row["cost"]:"")
    .'</td>
            <td>'
    .(isset($row["user"])?$row["user"]:"")
    .'</td>
        </tr>
        <tr style="display: none;" >
            <td colspam=6 >'
    .(isset($row["descr"])?$row["descr"]:"")
    .'</td>
        </tr>
        </table></td></tr>
    ';
}}
;    	
$result.='</table>
</div>
<div class="pages" >'
    .$pages
    .'</div>

<script type="text/javascript">
element.add_event(element.$(\'aaa\'),\'click\',function(){
	var e = this;
	element.allClass(this.form,\'select\',function(el){
		el.checked = e.checked;
	})
	e=null;
})

</script>

</form>

';
return $result;
}

function _admincust(&$namedpar,$data=0 ,$pages=0 ,$users=0 ){
extract($namedpar);
$result='<form method="POST" action="">

<div class="pages" >'
    .$pages
    .'</div>
    ';
$xxx='';    	
$result.='<div style="width:100%;height:100%;overflow:auto;">
<table class="fixed  thetable tahoma size11"><col width="100"><col><col>
    <tr><th  style="padding:2px;" colspan=3 class="bblue">
        <table class="long thetable tahoma" >
    <tr>
         <th class="bblue" style="padding:0;" id="newrow">
            <select name="newuser">
                ';
$loop_array=ps($users);
if (!empty($loop_array)){
foreach($loop_array as $user){
$result.='                <option value="'
    .(isset($user["id"])?$user["id"]:"")
    .'">'
    .(isset($user["name"])?$user["name"]:"")
    .'</option>
                ';
}}
;    	
$result.='            </select>
        </th><th class="bblue" >
            <nobr>
<div style="background-image:url(img/upload.gif);float:left;width:20px;height:20px;" class="uploader">&nbsp;</div>
 <input type="text" class="nocontext long link_toolbox" onkeydown="need_Save()" value="" name="newfile" > </nobr>
        </th><th class="bblue" >
            <input type="text" title="Описание" class="nocontext long" onkeydown="need_Save()" value="" name="newdescr" >
        </th>
        <th class="bblue align_left">
            <input type="submit" value=" Сохранить " class="button savebutton">
        </th>
        <th class="bblue align_left">
        <input type="checkbox" class="glass" id="aaa" name="ff">
        <input class="button" type="submit" name="delete" value="Удалить">
        </th>
    </tr>
</table>
    </th></tr>';
$loop2_array=ps($data);
$loop2_cycle=array('odd','even');
if (!empty($loop2_array)){
foreach($loop2_array as $row){
$result.='';
if( ($xxx)!=((isset($row["name"])?$row["name"]:"")) ) {
$result.='';
if( ($xxx)!=('') ) {
$result.='</td></tr>';
};    	
$result.='<tr class ="'
    .$this->loopcycle($loop2_cycle)
    .'" >
            <td class="align_center" style="padding:0;" id="tab_'
    .(isset($row["id"])?$row["id"]:"")
    .'">'
    .'        '
    .(isset($row["name"])?$row["name"]:"");
$xxx=(isset($row["name"])?$row["name"]:"");    	
$result.='</td>
            <td colspan=2>
    ';
};    	
$result.='       <div style="float:left;width:300px;margin:0 10px;"><nobr><label title="'
    .htmlspecialchars((isset($row["filename"])?$row["filename"]:""))
    .'">
                <input type="checkbox" class="glass select" value="'
    .(isset($row["id"])?$row["id"]:"")
    .'" name="ff[]">
                '
    .(isset($row["filedescr"])?$row["filedescr"]:"")
    .'</label></nobr></div>
    ';
}}
;    	
$result.='        </td>
    </tr>

    <tr>
    '
    .'    <th colspan=3 style="padding:2px;" class="bblue align_center">
        <input type="submit" value=" Сохранить " class="button savebutton">
    </th></tr>
</table>
</div>
<div class="pages" >'
    .$pages
    .'</div>

<script type="text/javascript">
element.add_event(element.$(\'aaa\'),\'click\',function(){
	var e = this;
	element.allClass(this.form,\'select\',function(el){
		el.checked = e.checked;
	})
	e=null;
})

</script>

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
