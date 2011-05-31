<?php
/**
 * this file is created automatically at "17 May 2011 23:52". Never change anything, 
 * for your changes can be lost at any time.  
 */
include_once TEMPLATE_PATH.DIRECTORY_SEPARATOR.'tpl_base.php';

class tpl_jorders extends tpl_base {
function __construct(){
$this->macro=array();
$this->macro['searchform']=array($this,'_'.'searchform');
$this->macro['orderlist']=array($this,'_'.'orderlist');
$this->macro['customer_list']=array($this,'_'.'customer_list');
}
function _searchform(&$namedpar,$data=0 ,$pages=0 ){
extract($namedpar);
$result='<form name="searchform" action="" method="POST">
<table class="long tahoma tablex" style="margin:20px 0;"><tr>
<th>статус заказа</th><td><select name="status">
<option value="0">Все</option>
<option value="1">Активен</option>
<option value="2">Неактивный</option>
<option value="3">Оплачен</option>
</select></td><td></td>
<th>Способ оплаты</th><td><select name="type">
<option value="0">все</option>
<option value="1">Безналичный</option>
<option value="2">Квитанция</option>
<option value="3">Наличный</option>
</select></td><td></td>
<th>Период</th><td><select name="period">
<option value="0">все</option>
<option value="1">неделя</option>
<option value="2">месяц</option>
<option value="3">год</option>
</select></td>
<td><input type="submit" value="Искать">
</td></tr></table></form>
';
return $result;
}

function _orderlist(&$namedpar,$data=0 ,$pages=0 ){
extract($namedpar);
$result='';
if( $pages ) {
$result.='<div class="pages" >'
    .$pages
    .'</div> ';
};    	
$result.='<div style="width:100%;height:100%;overflow:auto;">
    ';
if( count($data) ) {
$result.='<table class="fixed long tahoma table"><col><col><col><col>
    <tr class="even align_left">'
    .'        <th>Дата заказа</th>
        <th>Состояние </th>
        <th>Тип заказа</th>
        <th>Цена</th>
    </tr>
    ';
$loop2_array=ps($data);
$loop2_cycle=array('odd','even');
if (!empty($loop2_array)){
foreach($loop2_array as $row){
$result.='    ';
$class=$this->loopcycle($loop2_cycle);    	
$result.='    <tr style="cursor:pointer;" class="'
    .$class
    .'" onclick="$(this).next(\'tr\').toggle();">'
    .'        '
    .'        '
    .'        '
    .'        <td>'
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
    </tr>
    <tr class="'
    .$class
    .'" style=\'display:none;\'>
        <td colspan=4 >'
    .(isset($row["descr"])?$row["descr"]:"")
    .'</td>
    </tr>
    ';
}}
;    	
$result.='</table>
    ';
} else {
$result.='    Нет сохраненных заказов
    ';
};    	
$result.='</div>
';
if( $pages ) {
$result.='<div class="pages" >'
    .$pages
    .'</div> ';
};    	
$result.='
</form>

        
';
return $result;
}

function _customer_list(&$namedpar,$data=0 ,$users=0 ,$pages=0 ,$root=0){
extract($namedpar);
$result='    ';
$loop_array=ps($data);
if (!empty($loop_array)){
foreach($loop_array as $file){
$result.='        <a  class="strelka" href="'
    .(isset($file["filename"])?$file["filename"]:"")
    .'">'
    .(isset($file["filedescr"])?$file["filedescr"]:"")
    .'</a><br>
    ';
}}
;    	
$result.='';
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

'
    .'
';
return $result;
}

}
