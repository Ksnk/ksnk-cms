<?php
/**
 * this file is created automatically at "22 May 2011 0:00". Never change anything, 
 * for your changes can be lost at any time.  
 */
include_once TEMPLATE_PATH.DIRECTORY_SEPARATOR.'tpl_base.php';

class tpl_jelements extends tpl_base {
function __construct(){
$this->macro=array();
$this->macro['komment']=array($this,'_'.'komment');
$this->macro['basket_top']=array($this,'_'.'basket_top');
$this->macro['catalogue']=array($this,'_'.'catalogue');
$this->macro['basket_btn']=array($this,'_'.'basket_btn');
$this->macro['callback']=array($this,'_'.'callback');
}
function _komment(&$namedpar,$par=0 ,$data=0 ,$pages=0 ,$user=0 ,$root=0 ){
extract($namedpar);
$result='
<div class="tahoma size11" style="margin-top:25px;margin-bottom:10px;padding:10px 20px;background-color:#d7dee9;width:140px;text-align:center;">
<a href="javascript:show_komment('
    .(isset($par["id"])?$par["id"]:"")
    .')">
';
if( (isset($par["item_columns"]) && !empty($par["item_columns"])) ) {
$result.=' '
    .(isset($par["item_columns"])?$par["item_columns"]:"")
    .'<br>';
} else {
$result.='Комментарии';
};    	
$result.='- '
    .count($data)
    .' ответ'
    .$this->func_finnumb($data,'','а','ов')
    .'</a></div>
<div id="komment_'
    .(isset($par["id"])?$par["id"]:"")
    .'" class="tahoma size11" style="padding-top:25px;display:none;">
';
if( $pages ) {
$result.=$pages;
};    	
$result.='<table style="width:75%;margin-top:30px;">
';
$loop2_array=ps($data);
$loop2_index=0;
if (!empty($loop2_array)){
foreach($loop2_array as $comment){
$loop2_index++;
$result.='<tr style="color:#9eafc3;">
<td style="border-bottom:solid 1px #d7dee9;padding-bottom:2px;">'
    .(isset($comment["date"])?$comment["date"]:"")
    .'</td><td style="border-bottom:solid 1px #d7dee9;width:110px;">
</td><td style="border-bottom:solid 1px #eb8017;width:100px;">сообщение #'
    .$loop2_index
    .'</td>
</tr>
';
if( (isset($comment["quote"]) && !empty($comment["quote"])) ) {
$result.='<tr>
<td style="height:16px;"></td><td></td><td></td>
</tr>
<tr>
<td colspan="2" style="padding: 10px 0px 12px 25px;background-color:#d7dee9;">'
    .(isset($comment["quote"])?$comment["quote"]:"")
    .'</td><td style="background:url(img/strelka.gif) no-repeat center 8px"></td>
</tr>
';
};    	
$result.='<tr>
<td class="question" colspan="2" style="padding: 20px 0px 12px 25px;">'
    .(isset($comment["text"])?$comment["text"]:"")
    .'</td><td style="{style_str}"></td>
</tr>
<tr>
<td colspan="2" style="padding: 0px 0px 33px 25px;color:#225f9a;">
';
if( (isset($comment["info"]) && !empty($comment["info"])) ) {
$result.='<span onmouseover="showInfo(this)">'
    .(isset($comment["username"])?$comment["username"]:"")
    .'<div style="display:none;">'
    .(isset($comment["info"])?$comment["info"]:"")
    .'</div>
</span>
';
} else {
$result.='<span >'
    .(isset($comment["username"])?$comment["username"]:"")
    .'</span>
';
};    	
$result.='</td><td></td>
</tr>
';
}}
;    	
$result.='</table>
';
if( $pages ) {
$result.=$pages;
};    	
$result.='<table style="width:75%;margin-top:42px;">
<tr>
<td>
<table class="tahoma">
<tr><td style="background-color:#09304d;color:#FFFFFF;height:28px;width:215px;text-align:center;vertical-align:middle;font-weight:bold;font-family:Arial;font-size:11px;">ОСТАВИТЬ КОММЕНТАРИЙ</tr></td>
<tr><td style="height:10px;background:url(img/ugol_t.gif) left top no-repeat;"></tr></td>
</table>
</td>
<td style="font-size:12px;"><!--<a href="{all_topics}">Показать все темы</a>--></td>
<td></td>
</tr>
</table>

<div class="quote_div"></div>
<form method="post">
<input type="hidden" name="action" value="newkomment"/>
<div style="margin:10px 0px 10px 25px;">Ваше имя
<input type="hidden" name="user" value="'
    .(isset($user["id"])?$user["id"]:"")
    .'">
<input type="text" name="username" value="'
    .(isset($user["name"])?$user["name"]:"")
    .'" style="margin:0px 0px 0px 15px;border:solid 1px #d7dee9;"/></div>
<textarea name="newpost" style="margin:10px 0px 10px 25px;height:145px;width:425px;border:solid 1px #d7dee9;">
</textarea><br/>
<input type="hidden" name="comment" value="'
    .(isset($par["id"])?$par["id"]:"")
    .'"/>
<input type="hidden" name="quote_id" value=""/>
<input type="hidden" name="quote" value=""/>
<input type="submit" value="Отправить" style="background:url(img/button2.gif) no-repeat center right #FFFFFF;border:none;width:100px;color:#225f9a;margin-left:20px;"/>
</form>
</div>
';
return $result;
}

function _basket_top(&$namedpar,$pos=0 ,$root=0 ){
extract($namedpar);
$result='        ';
if( $pos ) {
$result.='<b>'
    .$pos
    .'</b> товар'
    .$this->func_finnumb($pos,'','a','ов')
    .' в корзине<br>
        <a class="basket" href="'
    .$root
    .'basket">Оформить заказ</a>
        ';
} else {
$result.='корзина пуста
        ';
};    	
$result.='';
return $result;
}

function _catalogue(&$namedpar,$button_val=0 ,$_table=0 ,$perpage=40,$root=0 ,$user=0 ,$pages=0){
extract($namedpar);
$result='        ';
$data=(isset($_table[0])?$_table[0]:"");    	
$result.='	<form name="basket" method="POST">
	<table class="long">
        ';
if( $pages ) {
$result.='		<tr><td>
			<table class="long">
				<tr>
					<td class="align_right" style="vertical-align:middle;padding-right:40px; padding-bottom:10px">
                    <nobr><u>Кол-во на странице</u>&nbsp;&nbsp;&nbsp;
						<select name="perpage" style="border:1px solid #424d5b; background:#424d5b; color:#FFFFFF">
                            ';
$loop_array=ps(array(10,20,40,60,100,200,500));
if (!empty($loop_array)){
foreach($loop_array as $item){
$result.='							<option value=" '
    .$item
    .'"';
if( ($perpage)==($item) ) {
$result.=' selected';
};    	
$result.='>'
    .$item
    .'</option>
                            ';
}}
;    	
$result.='                        </select>
						<input type="submit" style="background:url(img/btn_red.gif); border:0;width:13px; height:21px;" value="&nbsp;" src="{::index}/img/button3.gif">
					</td></tr>
					<tr><td style="padding-left:100px;">
					{pages}
					</td>
				</tr>
			</table>
		</td></tr>
        ';
};    	
$result.='        ';
$data=(isset($_table[0])?$_table[0]:"");    	
$result.='';
$loop_array=ps($_table);
if (!empty($loop_array)){
foreach($loop_array as $table){
$result.='		<tr><td>
    <table style="width:100%; border-top:3px solid #e3e6ea; border-bottom:3px solid #e3e6ea; border-left:none; border-right:none; margin-top:0px; margin-bottom:8px;">
		<tr><td style="padding-top:1px; padding-bottom:1px; padding-left:-1px; overflow:hidden;">
		    <table class="long size12 table tablex" style="margin-left:-1px;">
				<tr class="size11">'
    .(isset($table["headers"])?$table["headers"]:"")
    .'</tr>

	';
$loop_array=ps($table["data"]);
if (!empty($loop_array)){
foreach($loop_array as $row){
$result.='		<tr id="xx_'
    .(isset($row["id"])?$row["id"]:"")
    .'" class="{even??even::odd}">'
    .(isset($row["data"])?$row["data"]:"")
    .'</tr>
	';
}}
;    	
$result.='
			</table>
	    </td></tr>
    </table>

    </td></tr>
    ';
if( (isset($table["subtitle"]) && !empty($table["subtitle"])) ) {
$result.='    ';
$sub=(isset($table["subtitle"])?$table["subtitle"]:"");    	
$result.='	<tr><td class="menu size12" style="text-align:right">
			<div class="align_right" style="padding-right:10%; float:right">'
    .(isset($sub["title"])?$sub["title"]:"")
    .', руб:&nbsp;&nbsp;&nbsp;'
    .(isset($sub["value"])?$sub["value"]:"")
    .'</div>
	</td></tr>
	';
};    	
$result.='
';
}}
;    	
$result.='
	<tr><td  height=20px></td></tr>
	<tr><td  >
	<table class="long">
		<tr><td style="padding-left:100px;">
	'
    .$pages
    .'	</td>
	</tr>

	<tr><td class="align_left" style="padding-left:60px; padding-bottom:15px">
		<table><tr><td style="vertical-align:middle;"><input type="submit"
		    name="clear_bsk"
			style="border:0;background-color:#FFFFFF"
			class="basket_link"
			value="Очистить"></td>
		<td style="width:10px;"></td>
		<td class="white size11" style="vertical-align:middle;"><input type="submit"
			style="border:0;background-color:#FFFFFF"
			class="basket_link"
			value="'
    .$this->filter_default($button_val,'Добавить в корзину')
    .'">
        </td></tr></table>

	</td></tr></table>
	</td></tr>
		</table>
	</form>
';
return $result;
}

function _basket_btn(&$namedpar,$pos=0 ,$posx=0 ,$cost=0 ,$root=0 ){
extract($namedpar);
$result='    ';
if( $pos ) {
$result.='<div style="padding:10px 60px;" class="size12 menu">
Сейчас в корзине ';
if( $pos ) {
$result.=$pos
    .' товар'
    .$this->func_finnumb($pos,'','a','ов')
    .' на сумму '
    .$cost
    .' р.';
} else {
$result.=' нет товаров. ';
};    	
$result.='</div>
<div style="padding-left:60px;outline:none;" class="link tahoma menu">'
    .'<ul class="link tahoma menu ">
    <li><a class="" href="'
    .$root
    .'order">Оформить заказ</a></li>
</ul></div>
    ';
} else {
$result.='<div style=\'font-weight:bold;font-size:16px;padding:60px;\' class=\'align_center red\'>Корзина пуста</div>
    ';
};    	
$result.='
';
return $result;
}

function _callback(&$namedpar,$list=0,$hidden=0,$error=0,$root=0 ){
extract($namedpar);
$result='
<form style="padding:0; margin:0" action="" method="POST" name="callback">
<style>
.table td.pad10 {
    padding-top:10px;
    padding-bottom:10px;
}
.table .input {
	border:1px solid rgb(127,157,185);
	width:310px;

}
.table textarea.input {
	height:100px;
}

.table span.comment {
	font-size:10px; color:#cccccc;
}

</style>
<span class="bold red">'
    .$error
    .'</span>
    ';
$loop_array=ps($hidden);
if (!empty($loop_array)){
foreach($loop_array as $el){
$result.='<input class="hidden" ntype="text" name="'
    .(isset($el["text"])?$el["text"]:"")
    .'">';
}}
;    	
$result.='    '
    .'<input  class="hidden" type="text" name="hidden_value" value="secret">
    '
    .'<table class="table size12">
    ';
$loop_array=ps($list);
if (!empty($loop_array)){
foreach($loop_array as $el){
$result.='
<tr class="';
if( (isset($el["even"]) && !empty($el["even"])) ) {
$result.='even';
} else {
$result.='odd';
};    	
if( (isset($el["rule"]) && !empty($el["rule"])) ) {
$result.=' ruled '
    .(isset($el["rule"])?$el["rule"]:"");
};    	
$result.='">
    ';
if( ((isset($el["type"])?$el["type"]:""))==('text') ) {
$result.='    <td colspan=3 class="pad10 text bold align_center" >'
    .(isset($el["text"])?$el["text"]:"")
    .'</td>
    ';
};    	
$result.='    ';
if( ((isset($el["type"])?$el["type"]:""))==('password') ) {
$result.='    <td class="pad10 text align_right">'
    .(isset($el["tit"])?$el["tit"]:"");
if( (isset($el["nocol"]) && !empty($el["nocol"])) ) {
$result.='';
} else {
$result.=':';
};    	
$result.='&nbsp;';
if( (isset($el["nostar"]) && !empty($el["nostar"])) ) {
$result.='&nbsp;';
} else {
$result.='<sup><b>*</b></sup>';
};    	
$result.='</td>
    <td colspan=2 class="pad10 text"><input  class="input" type="password" name="'
    .(isset($el["name"])?$el["name"]:"")
    .'">'
    .(isset($el["comment"])?$el["comment"]:"")
    .'</td>
    ';
};    	
$result.='    ';
if( ((isset($el["type"])?$el["type"]:""))==('scrolltext') ) {
$result.='<td colspan=3 class="pad10 text align_right" >
	'
    .(isset($el["title"])?$el["title"]:"")
    .'<div style="overflow:auto;">'
    .(isset($el["text"])?$el["text"]:"")
    .'</div></td>
    ';
};    	
$result.='    ';
if( ((isset($el["type"])?$el["type"]:""))==('input') ) {
$result.='<td class="pad10 text align_right">'
    .(isset($el["tit"])?$el["tit"]:"");
if( (isset($el["nocol"]) && !empty($el["nocol"])) ) {
$result.='';
} else {
$result.=':';
};    	
$result.='&nbsp;';
if( (isset($el["nostar"]) && !empty($el["nostar"])) ) {
$result.='&nbsp;';
} else {
$result.='<sup><b>*</b></sup>';
};    	
$result.='</td>
<td colspan=2 class="pad10 text"><input  class="input" type="text" name="'
    .(isset($el["name"])?$el["name"]:"")
    .'">'
    .(isset($el["comment"])?$el["comment"]:"")
    .'</td>
    ';
};    	
$result.='    ';
if( ((isset($el["type"])?$el["type"]:""))==('textarea') ) {
$result.='<td class="pad10 text align_right">'
    .(isset($el["tit"])?$el["tit"]:"");
if( (isset($el["nocol"]) && !empty($el["nocol"])) ) {
$result.='';
} else {
$result.=':';
};    	
$result.='&nbsp;';
if( (isset($el["nostar"]) && !empty($el["nostar"])) ) {
$result.='&nbsp;';
} else {
$result.='<sup><b>*</b></sup>';
};    	
$result.='</td><td colspan=2 class="pad10 text"><textarea  class="input" name="'
    .(isset($el["name"])?$el["name"]:"")
    .'"></textarea></td>
    ';
};    	
$result.='    ';
if( ((isset($el["type"])?$el["type"]:""))==('checkbox') ) {
$result.='<td class="pad10 text align_right">'
    .(isset($el["tit"])?$el["tit"]:"");
if( (isset($el["nocol"]) && !empty($el["nocol"])) ) {
$result.='';
} else {
$result.=':';
};    	
$result.='&nbsp;';
if( (isset($el["nostar"]) && !empty($el["nostar"])) ) {
$result.='&nbsp;';
} else {
$result.='<sup><b>*</b></sup>';
};    	
$result.='</td>
<td colspan=2 class="pad10 text">
	';
$loop_array=ps($el["check"]);
if (!empty($loop_array)){
foreach($loop_array as $ch){
$result.='	<div><input type="checkbox" name="'
    .(isset($ch["name"])?$ch["name"]:"")
    .'" value="'
    .(isset($ch["value"])?$ch["value"]:"")
    .'">'
    .(isset($ch["text"])?$ch["text"]:"")
    .'</div>
	';
}}
;    	
$result.='	</td>
    ';
};    	
$result.='    ';
if( ((isset($el["type"])?$el["type"]:""))==('radio') ) {
$result.='<td class="pad10 text align_right">'
    .(isset($el["tit"])?$el["tit"]:"");
if( (isset($el["nocol"]) && !empty($el["nocol"])) ) {
$result.='';
} else {
$result.=':';
};    	
$result.='&nbsp;';
if( (isset($el["nostar"]) && !empty($el["nostar"])) ) {
$result.='&nbsp;';
} else {
$result.='<sup><b>*</b></sup>';
};    	
$result.='</td>
<td colspan=2 class="pad10 text">
	';
$loop_array=ps($el["check"]);
if (!empty($loop_array)){
foreach($loop_array as $ch){
$result.='	<div><input type="radio"';
if( (isset($el["onchange"]) && !empty($el["onchange"])) ) {
$result.=' onchange="'
    .(isset($el["onchange"])?$el["onchange"]:"")
    .'" ';
};    	
$result.='name="'
    .(isset($ch["name"])?$ch["name"]:"")
    .'" value="'
    .(isset($ch["value"])?$ch["value"]:"")
    .'">'
    .(isset($ch["text"])?$ch["text"]:"")
    .'</div>
	';
}}
;    	
$result.='	</td>
    ';
};    	
$result.='    ';
if( ((isset($el["type"])?$el["type"]:""))==('captcha') ) {
$result.='<td class="pad10 text align_right" >Введите номер, изображенный на картинке:<br></td><td class="text" style="vertical-align:middle; padding-left:10px"><input class="input2"  type="text" name="captcha" /></td>
<td><img src="'
    .$root
    .'captcha.php" alt="" />
</td>
    ';
};    	
$result.='    ';
if( ((isset($el["type"])?$el["type"]:""))==('submit') ) {
$result.='<td class="text pad10" ></td><td colspan=2 class="pad10 text align_left" ><div style="width:100px;" class="button"><input type="submit" value="Отправить"></div></td>
    ';
};    	
$result.='</tr>
';
}}
;    	
$result.='</table>
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
'
    .'';
return $result;
}

}
