<?php
/**
 * this file is created automatically at "01 Apr 2011 11:58". Never change anything, 
 * for your changes can be lost at any time.  
 */
include_once TEMPLATE_PATH.DIRECTORY_SEPARATOR.'tpl_base.php';

class tpl_jvocabular extends tpl_base {
function __construct(){
$this->macro=array();
$this->macro['admin_vocabular']=array($this,'_'.'admin_vocabular');
$this->macro['admin_form']=array($this,'_'.'admin_form');
$this->macro['komment_edit']=array($this,'_'.'komment_edit');
}
function _admin_vocabular(&$namedpar,$plus=0 ,$additional2=0 ,$head=0 ,$list=0 ,$name=0 ,$error=0 ,$root=0 ,$additional=0 ){
extract($namedpar);
$result='<form name="admin'
    .$name
    .'" action="" method="POST">
';
if( $error ) {
$result.='<span style="color:red;font-size:16px;">'
    .$error
    .'</span>';
};    	
$result.='<input type=\'hidden\' class="del" name="del">
'
    .$additional
    .'
<table class="table long ctext size11">
<tr>
<th class="bblue" style="width:20px;padding: 0 2px;">№</th>
';
$loop_array=ps($head);
if (!empty($loop_array)){
foreach($loop_array as $h){
$result.='<th class="bblue';
if( (isset($h["class"]) && !empty($h["class"])) ) {
$result.=' '
    .(isset($h["class"])?$h["class"]:"");
};    	
$result.='">'
    .(isset($h["title"])?$h["title"]:"")
    .'</th>
';
}}
;    	
$result.='<th class="bblue"></th>
</tr>
';
$loop2_array=ps($list);
$loop2_index=0;
$loop2_cycle=array('odd','even');
if (!empty($loop2_array)){
foreach($loop2_array as $l){
$loop2_index++;
$result.='<tr class="'
    .$this->loopcycle($loop2_cycle)
    .'" id="'
    .$this->filter_default((isset($l["prefix"])?$l["prefix"]:""),'rl')
    .'_'
    .(isset($l["id"])?$l["id"]:"")
    .'"><th class="nopage" style="width:20px;">'
    .$loop2_index
    .'</th>
';
$loop_array=ps($l["row"]);
if (!empty($loop_array)){
foreach($loop_array as $r){
$result.='<t'
    .$this->filter_default((isset($r["d"])?$r["d"]:""),'d')
    .' ';
if( (isset($r["style"]) && !empty($r["style"])) ) {
$result.='style="'
    .(isset($r["style"])?$r["style"]:"")
    .'" ';
};    	
$result.='class="'
    .$this->filter_default((isset($r["class"])?$r["class"]:""),'text_edit')
    .'" id="'
    .(isset($r["name"])?$r["name"]:"")
    .'_'
    .(isset($r["id"])?$r["id"]:"")
    .'">'
    .(isset($r["value"])?$r["value"]:"")
    .'</t'
    .$this->filter_default((isset($r["d"])?$r["d"]:""),'d')
    .'>
';
}}
;    	
$result.='';
if( (isset($l["sort"]) && !empty($l["sort"])) ) {
$result.='<th class="nopage align_center" style="padding:0 2px;" nowrap>
'
    .$this->callex('MAIN','tpl','admin','order_elm_start')
    .'<input type="text" class="order size11" onkeydown="need_Save()" style="width:15px;" name="item_order_{id}">
'
    .$this->callex('MAIN','tpl','admin','order_elm_fin')
    .'</th>
';
};    	
$result.='<th style="width:20px;padding: 0 2px;">'
    .$this->callex('MAIN','tpl','admin','delrec_elm')
    .'</th>
';
}}
;    	
$result.='</tr>
<tr class="odd" id="newrow" style="display:none;">
<th>&raquo;</th>
';
$loop_array=ps($plus);
if (!empty($loop_array)){
foreach($loop_array as $r){
$result.='<td  class="'
    .$this->filter_default((isset($r["class"])?$r["class"]:""),'text_edit')
    .'" id="'
    .(isset($r["name"])?$r["name"]:"")
    .'"></td>
';
}}
;    	
$result.='<th ></th>
</tr>

</table>
';
if( $additional2 ) {
$result.=' '
    .$additional2;
} else {
$result.='<table class="table long ctext size11">
<tr>
<th class="bblue align_center" STYLE="padding:2px;">
<input class="button savebutton" type="submit"  disabled="disabled" value=" Сохранить ">
<input type="button" class="button" onclick="element.$(\'newrow\').style.display=\'\';this.disabled=\'disabled\';" name="newRecord" value="Добавить">
</th>
</tr></table>
';
};    	
$result.='';
if( (isset($par['pages']) && !empty($par['pages'])) ) {
$result.='<table class="table long ctext size11">
<tr><th style="background:white;">
'
    .(isset($par['pages'])?$par['pages']:"")
    .'</th></tr></table>
';
};    	
$result.='
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

function _admin_form(&$namedpar,$pages=0,$data=0,$root=0,$options=0,$fields=0,$error=0,$additional=0,$name=0,$additional2=0){
extract($namedpar);
$result='<form name="admin'
    .$name
    .'" action="" method="POST">
';
if( $error ) {
$result.='<span style="color:red;font-size:16px;">'
    .$error
    .'</span>';
};    	
$result.='<input type=\'hidden\' class="del" name="del">
'
    .$additional
    .'<table class="table long ctext size11">
<tr>
<th class="bblue" style="width:20px;padding: 0 2px;">№</th>
';
$colnum=0;    	
$result.='';
$loop_array=ps($fields);
if (!empty($loop_array)){
foreach($loop_array as $h){
$result.='';
if( !((isset($h["dontshow"]) && !empty($h["dontshow"]))) ) {
$result.='    ';
$colnum=($colnum)+(1);    	
$result.='	<th class="bblue';
if( (isset($h["class"]) && !empty($h["class"])) ) {
$result.=' '
    .(isset($h["class"])?$h["class"]:"");
};    	
$result.='">'
    .(isset($h[0])?$h[0]:"")
    .'</th>
';
};    	
$result.='';
}}
;    	
$result.='<th class="bblue"></th>
</tr>
';
$oldgroup='';    	
$result.='';
$loop2_array=ps($data);
$loop2_index=0;
$loop2_cycle=array('odd','even');
if (!empty($loop2_array)){
foreach($loop2_array as $d){
$loop2_index++;
$result.='    ';
if( ((isset($options["group"]) && !empty($options["group"]))) && (((isset($d[(isset($options["group"])?$options["group"]:"")])?$d[(isset($options["group"])?$options["group"]:"")]:""))!=($oldgroup)) ) {
$result.='    <tr class="bblue"><th></th><th colspan="'
    .$colnum
    .'">'
    .(isset($d[(isset($options["group"])?$options["group"]:"")])?$d[(isset($options["group"])?$options["group"]:"")]:"")
    .'</th><th></th></tr>
    ';
$oldgroup=(isset($d[(isset($options["group"])?$options["group"]:"")])?$d[(isset($options["group"])?$options["group"]:"")]:"");    	
$result.='    ';
};    	
$result.='	<tr class="'
    .$this->loopcycle($loop2_cycle)
    .'" id="'
    .$this->filter_default((isset($par['l']["prefix"])?$par['l']["prefix"]:""),'rl')
    .'_'
    .(isset($d['id'])?$d['id']:"")
    .'"><th class="nopage" style="width:20px;">'
    .$loop2_index
    .'</th>
	';
$loop_array=ps($fields);
if (!empty($loop_array)){
foreach($loop_array as $r){
$result.='		';
if( (isset($r["dontshow"]) && !empty($r["dontshow"])) ) {
$result.='';
} elseif( (isset($r["dontedit"]) && !empty($r["dontedit"])) ) {
$result.=' '
    .'			<td ';
if( (isset($r["style"]) && !empty($r["style"])) ) {
$result.='style="'
    .(isset($r["style"])?$r["style"]:"")
    .'" ';
};    	
$result.='>'
    .(isset($d[(isset($r[1])?$r[1]:"")])?$d[(isset($r[1])?$r[1]:"")]:"")
    .'</td>
		';
} elseif( ((isset($r[2])?$r[2]:""))==('check01') ) {
$result.='			<td class="check01 align_center nopage" id="'
    .(isset($r["name"])?$r["name"]:"")
    .'_'
    .(isset($d[(isset($par['id'])?$par['id']:"")])?$d[(isset($par['id'])?$par['id']:"")]:"")
    .'"> 
			<input name="'
    .(isset($r[1])?$r[1]:"")
    .'_'
    .(isset($d['id'])?$d['id']:"")
    .'" type="text" value="1" class="win_check"> </td> 
		';
} elseif( (((isset($r[2])?$r[2]:""))==('text_edit')) || (!((isset($r[2])?$r[2]:""))) ) {
$result.='			<td class="text_edit" id="'
    .(isset($r[1])?$r[1]:"")
    .'_'
    .(isset($d['id'])?$d['id']:"")
    .'">'
    .(isset($d[(isset($r[1])?$r[1]:"")])?$d[(isset($r[1])?$r[1]:"")]:"")
    .'</td>
		';
};    	
$result.='	';
}}
;    	
$result.='	';
if( (isset($par['l']["sort"]) && !empty($par['l']["sort"])) ) {
$result.='	<th class="nopage align_center" style="padding:0 2px;" nowrap>
	'
    .$this->callex('MAIN','tpl','admin','order_elm_start')
    .'	<input type="text" class="order size11" onkeydown="need_Save()" style="width:15px;" name="item_order_{id}">
	'
    .$this->callex('MAIN','tpl','admin','order_elm_fin')
    .'	</th>
	';
};    	
$result.='	<th style="width:20px;padding: 0 2px;">'
    .$this->callex('MAIN','tpl','admin','delrec_elm')
    .'</th>
	</tr>
';
}}
;    	
$result.='';
if( !((isset($options["noadd"]) && !empty($options["noadd"]))) ) {
$result.='<tr class="odd" id="newrow" style="display:none;">
<th>&raquo;</th>
';
$loop_array=ps($par['plus']);
if (!empty($loop_array)){
foreach($loop_array as $r){
$result.='<td  class="'
    .$this->filter_default((isset($r["class"])?$r["class"]:""),'text_edit')
    .'" id="'
    .(isset($r["name"])?$r["name"]:"")
    .'"></td>
';
}}
;    	
$result.='<th ></th>
</tr>
';
};    	
$result.='</table>
';
if( $additional2 ) {
$result.=' '
    .$additional2;
} else {
$result.='<table class="table long ctext size11">
<tr>
<th class="bblue align_center" STYLE="padding:2px;">
<input class="button savebutton" type="submit"  disabled="disabled" value=" Сохранить ">
';
if( !((isset($options["noadd"]) && !empty($options["noadd"]))) ) {
$result.='<input type="button" class="button" onclick="element.$(\'newrow\').style.display=\'\';this.disabled=\'disabled\';" name="newRecord" value="Добавить">
';
};    	
$result.='</th>
</tr></table>
';
};    	
$result.='';
if( $pages ) {
$result.='<table class="table long ctext size11">
<tr><th style="background:white;">
'
    .$pages
    .'</th></tr></table>
';
};    	
$result.='
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

function _komment_edit(&$namedpar,$data=0,$trclass=0,$id=0,$name=0,$item_columns=0){
extract($namedpar);
$result='<tr class="context '
    .$this->filter_default($trclass,'odd')
    .'" id="pg_'
    .$id
    .'" ><td class="bwhite"  style="padding:0;">
<table class="tahoma ctext size11 long fixed">
<col width="23px">
<col width="90px"><col width="auto"><col width="150px">
'
    .'<col width="50px">
<col width="23px">
<tr>
<th class="nopage align_center" style="padding:0 2px;"><div class="win_max closed  open_close">&nbsp;</div></th>
<td class="text_edit" id="item_name_'
    .$id
    .'" title="тип:Комментарий">'
    .$this->filter_default($name,'комментарий')
    .'</td>
<td class="text_edit" id="item_columns_'
    .$id
    .'" title="">'
    .$this->filter_default($item_columns,'')
    .'</td>
<td>
 <input type="submit" onmousedown="window.el_open(this); return false;" title="" class="button green" 
 name="new_row_com_'
    .$id
    .'" value="Доб.Коммент."> 
</td>
'
    .'<th class="nopage align_center" style="padding:0 2px;" nowrap>
'
    .$this->callex('MAIN','tpl','admin','order_elm_start')
    .'<input type="text" class="order size11" onkeydown="need_Save()" style="width:15px;" name="item_order_'
    .$id
    .'">
'
    .$this->callex('MAIN','tpl','admin','order_elm_fin')
    .'</th>
<th class="align_center" style="padding:0 2px;">
'
    .$this->callex('MAIN','tpl','admin','delrec_elm')
    .'</th>
</tr>

<tr class="bwhite"  style="display:none;">
<td class="bwhite" colspan=2></td>
<td colspan="4"><div style="height:100%;width:100%;overflow-y:visible; overflow-x:auto;">
'
    .'<table class="tahoma ctext size11 long">
<col width="40">
<col width="80">
<col width="60">
<col width="auto">
<col width="auto">
<col width="25">

<tr><th>Пров.</th><th>Дата</th><th>имя</th><th>Текст</th><th>Цитата</th><th></th></tr>
';
$loop2_array=ps($data);
$loop2_cycle=array('even','odd');
if (!empty($loop2_array)){
foreach($loop2_array as $d){
$result.='<tr class="'
    .$this->loopcycle($loop2_cycle)
    .'" id="del'
    .$id
    .'['
    .(isset($d['id'])?$d['id']:"")
    .']">
<td class="check01 align_center nopage" > 
	<input name="new'
    .(isset($d['id'])?$d['id']:"")
    .'_'
    .$id
    .'" type="text" value="1" class="win_check"></td>
<td  class="text_edit" id="date'
    .(isset($d['id'])?$d['id']:"")
    .'_'
    .$id
    .'">'
    .(isset($d["date"])?$d["date"]:"")
    .'</td>
<td  class="text_edit" id="username'
    .(isset($d['id'])?$d['id']:"")
    .'_'
    .$id
    .'">'
    .(isset($d["username"])?$d["username"]:"")
    .'</td>
<td  class="text_edit" id="text'
    .(isset($d['id'])?$d['id']:"")
    .'_'
    .$id
    .'">'
    .(isset($d["text"])?$d["text"]:"")
    .'</td>
<td  class="text_edit" id="quote'
    .(isset($d['id'])?$d['id']:"")
    .'_'
    .$id
    .'">'
    .(isset($d["quote"])?$d["quote"]:"")
    .'</td>
<td >'
    .$this->callex('MAIN','tpl','admin','delrec_elm')
    .'</td>
</tr>
';
}}
;    	
$result.='</table>
'
    .'</div>
</td></tr></table>
</td>
</tr>
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
    .'';
return $result;
}

}
