<?php
/**
 * this file is created automatically at "01 Apr 2011 11:58". Never change anything, 
 * for your changes can be lost at any time.  
 */
include_once TEMPLATE_PATH.DIRECTORY_SEPARATOR.'tpl_base.php';

class tpl_jfiles extends tpl_base {
function __construct(){
$this->macro=array();
$this->macro['fileman']=array($this,'_'.'fileman');
}
function _fileman(&$namedpar,$list=1,$pages=1,$type=1,$filter=0 ,$columns=2,$colsize=20){
extract($namedpar);
$result='<form method="POST" action="">
<table class="long thetable tahoma" ><tr>
<th class="bblue">показать 
<a class="button';
if( ($type)==(1) ) {
$result.=' pressed';
};    	
$result.='" href="'
    .($this->callex('MAIN','curl','item','pg')).('item=1')
    .'">Файлы</a>
<a class="button';
if( ($type)==(2) ) {
$result.=' pressed';
};    	
$result.='" href="'
    .($this->callex('MAIN','curl','item','pg')).('item=2')
    .'">Картинки</a>
<a class="button';
if( ($type)==(4) ) {
$result.=' pressed';
};    	
$result.='" href="'
    .($this->callex('MAIN','curl','item','pg')).('item=4')
    .'">Иконки</a>
';
if( $filter ) {
$result.='<a class="button';
if( ($type)==(3) ) {
$result.=' pressed';
};    	
$result.='" href="'
    .($this->callex('MAIN','curl','item','pg')).('item=3')
    .'">фильтр('
    .htmlspecialchars($filter)
    .')</a>
';
};    	
$result.='</th>
<th class="bblue align_center">
	<table class="tahoma"><tr><td class="uploader"><b>загрузить</b><input type="button" onclick="$must_save=false;__goto();" style="display:none;"></td></tr></table>
</th>
<th class="bblue align_left">
<input type="checkbox" class="glass" id="aaa" name="ff">
<input class="button" type="submit" name="delete" value="Удалить">
</th>
</tr>
<tr><td colspan=2>
</td></tr></table>
<div id="pages">'
    .$pages
    .'</div>
<div id="fman_panel" style="width:100%;height:100%;overflow:auto;">
<table class="long tahoma thetable"><tr>
';
$xlist=$this->func_slice($list,$colsize);    	
$result.='';
$loop2_array=ps($this->func_range($columns));
$loop2_index=0;
$loop2_last=count($loop2_array);
if (!empty($loop2_array)){
foreach($loop2_array as $cc){
$loop2_index++;
$result.='';
if( ($type)!=(4) ) {
$result.='<th>Имя</th><th>размер</th><th>info</th>';
if( !($loop2_index==$loop2_last) ) {
$result.='<td></td>';
};    	
$result.='';
} else {
$result.='<th></th><th>изображение</th>';
if( !($loop2_index==$loop2_last) ) {
$result.='<td></td>';
};    	
$result.='';
};    	
$result.='';
}}
;    	
$result.='';
$loop2_array=ps($this->func_range($colsize));
$loop2_index=0;
$loop2_cycle=array('even','odd');
if (!empty($loop2_array)){
foreach($loop2_array as $xx){
$loop2_index++;
$result.='<tr class="'
    .$this->loopcycle($loop2_cycle)
    .'">
';
$idx=($loop2_index-1);    	
$result.='';
$loop3_array=ps($this->func_range($columns));
$loop3_index=0;
$loop3_last=count($loop3_array);
if (!empty($loop3_array)){
foreach($loop3_array as $cc){
$loop3_index++;
$result.='';
$col=(isset($xlist[($loop3_index-1)])?$xlist[($loop3_index-1)]:"");    	
$result.='';
$l=(isset($col[$idx])?$col[$idx]:"");    	
$result.='';
if( ($type)!=(4) ) {
$result.='';
if( $l ) {
$result.='<td ><nobr><label><input type="checkbox" class="glass select" value="'
    .htmlspecialchars((isset($l["url"])?$l["url"]:""))
    .'" name="ff[]">'
    .htmlspecialchars((isset($l["url"])?$l["url"]:""))
    .'</label></nobr></td><td>'
    .(isset($l["size"])?$l["size"]:"")
    .'</td><td>'
    .(isset($l["info"])?$l["info"]:"")
    .'</td>
';
} else {
$result.='<td >&nbsp;</td><td></td><td></td>
';
};    	
$result.='';
} else {
$result.='';
if( $l ) {
$result.='<td ><input type="checkbox" class="glass select" value="'
    .htmlspecialchars((isset($l["url"])?$l["url"]:""))
    .'" name="ff[]"></td>
<td> <img alt="'
    .htmlspecialchars((isset($l["url"])?$l["url"]:""))
    .'" width="80" height="80" onload="checkImg(this,80,80)" src="../uploaded/'
    .htmlspecialchars((isset($l["url"])?$l["url"]:""))
    .'"</td>
';
} else {
$result.='<td >&nbsp;</td><td></td>
';
};    	
$result.='';
};    	
$result.='';
if( !($loop3_index==$loop3_last) ) {
$result.='<td style="background-color:white"></td>';
};    	
$result.='';
}}
;    	
$result.='</tr>
';
}}
;    	
$result.='</table>
</div>

<script type="text/javascript">
element.add_event(element.$(\'aaa\'),\'click\',function(){
	var e = this;
	element.allClass(this.form,\'select\',function(el){
		el.checked = e.checked;
	})
	e=null;
})

</script>
<div  style="display:none;">
<div style="float:left;" id="fman_tpl">
<table class="tahoma thetable"><tr>
<th>Имя</th><th>размер</th><th>info</th>
</tr><tr><td>%data%</td></tr>
</table>
</div>
<div id="fman_column">
<table>
<tr class="%odd%">
<td ><nobr><label><input type="checkbox" class="glass select" value="%url%" name="ff[]">%name%</label></nobr></td><td>%size%</td><td>%info%</td>
</tr>
</table>
</div>
</div>

</form>
';
return $result;
}

function _ (&$par){
$result='';
return $result;
}

}
