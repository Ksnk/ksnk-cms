<?php
class tpl_main extends tpl {

function _(&$par){		
		return '<!DOCTYPE html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>'.tpl::_d($par['title'],'Администрирование сайта').'</title>
<meta http-equiv="content-type"
	content="text/html; charset=windows-1251">
<META name="description" content="'.(isset($par['desc_words'])?$par['desc_words']:'').'">
<META name="keywords" lang="ru" content="'.(isset($par['key_words'])?$par['key_words']:'').'">

<script type="text/javascript" src="../js/jquery-1.4.4.js"> </script>
<script type="text/javascript" src="js/engine.pack.js"> </script>
<script type="text/javascript" src="js/nicedit.full.js"></script>

<LINK  rel="stylesheet" type="text/css" href="css/admin.css">

<link rel="icon" href="'.tpl::_ax(tpl::_export('','index'),array('tpl_main','__index')).'/favicon.ico" type="image/x-icon">
<link rel="shortcut icon" href="'.tpl::_ax(tpl::_export('','index'),array('tpl_main','__index')).'/favicon.ico" >
<style type="text/css">
html, body {overflow:hidden;}
</style>
<script type=\'text/javascript\' src="js/main.js">
</script>
</head>
<body>
'.tpl::_a($par['katalog_searchres'],array('tpl_main','katalog_searchres')).'
'.tpl::_a($par['searchres'],array('tpl_main','searchres')).'

'.tpl::_a($par['pages'],array('tpl_main','pages')).'
<table>
'.tpl::_a($par['text_edit_line'],array('tpl_main','text_edit_line')).'
'.tpl::_a($par['header_edit_line'],array('tpl_main','header_edit_line')).'

'.tpl::_a($par['column_line'],array('tpl_main','column_line')).'
'.tpl::_a($par['article_line'],array('tpl_main','article_line')).'

'.tpl::_a($par['article_list'],array('tpl_main','article_list')).'

'.tpl::_a($par['plugin_edit_line'],array('tpl_main','plugin_edit_line')).'

'.tpl::_a($par['katalogx_edit_line'],array('tpl_main','katalogx_edit_line')).'


'.tpl::_a($par['katalog_edit_line'],array('tpl_main','katalog_edit_line')).'
'.tpl::_a($par['common_line'],array('tpl_main','common_line')).'

'.tpl::_a($par['gallery_edit_line'],array('tpl_main','gallery_edit_line')).'
'.tpl::_a($par['links_edit_line'],array('tpl_main','links_edit_line')).'

'.tpl::_a($par['link_edit_line'],array('tpl_main','link_edit_line')).'

'.tpl::_a($par['komment_edit_line'],array('tpl_main','komment_edit_line')).'

'.tpl::_a($par['table_edit_line'],array('tpl_main','table_edit_line')).'
</table>
'.tpl::_a($par['edit_table'],array('tpl_main','edit_table')).'

'.tpl::_a($par['edit_row'],array('tpl_main','edit_row')).'

<table>
'.tpl::_a($par['row_edit_line'],array('tpl_main','row_edit_line')).'

'.tpl::_a($par['piconly_edit_line'],array('tpl_main','piconly_edit_line')).'

'.tpl::_a($par['menu_edit_line'],array('tpl_main','menu_edit_line')).'

'.tpl::_a($par['article_edit'],array('tpl_main','article_edit')).'

'.tpl::_a($par['menu_edit_list'],array('tpl_main','menu_edit_list')).'

'.tpl::_a($par['menu_edit_addnew'],array('tpl_main','menu_edit_addnew')).'
</table>
'.tpl::_a($par['sitemap'],array('tpl_main','sitemap')).'

'.tpl::_a($par['ermess'],array('tpl_main','ermess')).'
<!--  ////////////////// Основная страница /////////////////////// -->
<div class="long wide hidden align_center" style="position:absolute;top:0;left:0;z-index:6" id="wait">
<!-- выравнивание по центру -->
<table class="wide"><tr>
<td><div style="padding:40px;background:white;overflow:auto;">
<h1>Загружаем файл...</h1>
</div>
</td>
</tr></table>
</div>
<div class="long wide hidden align_center" style="position:absolute;top:0;left:0;z-index:6" id="progress">
<!-- выравнивание по центру -->
<table class="wide"><tr>
<td><div style="padding:40px;background:white;overflow:auto;">
<h2 id="prg_tit">Экспорт CSV...</h2>
<h3 >состояние: <span id="prg_compl">идет обработка</span></h3>
</div>
</td>
</tr></table>
</div>
<div class="long wide"
	style="display:none;background:gray;margin:0;padding:0;position:absolute;top:0;left:0;z-index:5;" id="shaddow">
</div>
<div class="long wide hidden align_center" style="position:absolute;top:0;left:0;z-index:6" id="html_Editor">
<!-- выравнивание по центру -->
<table class="wide"><tr>
<td style="padding:10px"><div style="height:470px;background:white;overflow:auto;">
 <TEXTAREA rows="10" cols="80" id="area1" style="width:700px;height:400px"></TEXTAREA>
</div>
<div class="align_center"><input type="button" onClick="htmlOk();" value="Ok"> <input type="button" onClick="htmlCancel();" value="Cancel"></div>
</td>
</tr></table>
</div>

<iframe src=\'about:blank\' id=\'uploadFrame\' name=\'uploadFrame\' class="hidden">
</iframe>

<div id="link_toolbox" class="tahoma toolbox menu cltext" style="z-index:1000;cursor:pointer;display:none;position:absolute;">
<a href="#ajax:get_menu_list">Ссылка на раздел сайта</a>
<a href="#ajax:get_file_list">Ссылка на файл</a>
<a href="#ajax:get_picture_list">Ссылка на картинку</a>
</div>

<div id=\'uploader\' class="nocontext"
style="z-index:2000;cursor:pointer;display:none;position:absolute;overflow:hidden;width:30px;height:30px;border:1px solid red;">
<form id="uploadForm" target="uploadFrame" action="?do=file_uploader" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="xwidth">
    <input type="hidden" name="xheight">
    <input type="hidden" name="xxwidth">
    <input type="hidden" name="xxheight">
    <input type="hidden" name="xaction">
    <input type="hidden" name="xurl">
	<input type="file" title="Загружаем файл" style="cursor:pointer;height:60px;margin-left:-150px;"
		name="file" onChange="do_upload(this);" onMouseDown="return do_menu(event,this);">
    <input type="submit" value="Submit" >
</form>
</div>
<div id="container" style="position:relative;width:100%; height:100%; overflow:auto;">

<div id="link_pages" class="tahoma toolbox menu size11 cltext" style="z-index:21;cursor:pointer;display:none;position:absolute;max-height:80%;overflow:auto;">
<a href="#">ссылка на файл</a>
</div>

<div id="contextmenu" class="toolbox tahoma menu cltext" style="width:200px;background:white; position:absolute; z-index:23;padding:5px; border: 1px solid #dddddd; display:none;"></div>
<table class="long wide tahoma ctext" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td colspan=3 style="height:39px;"><div class="wide" style="position:relative;">
<a style="display:block;text-decoration:none;top:10px;left:20px;position:absolute;width:100px; height:20px;"
href="http://xilen.ru"
>&nbsp;</a>
<a style="display:block;text-decoration:none;top:10px;left:150px;position:absolute;width:130px; height:20px;"
href="http://www.xilen.spb.ru/portfolio.php"
>&nbsp;</a>
<a style="text-decoration:none;display:block;top:10px;right:70px;position:absolute;width:130px; height:20px;"
href="#" onClick="this.setAttribute(\'href\',\'mailto:art@xilen.ru?subject=Сообщение%20с%20сайта%20\'+document.location.hostname);"
>&nbsp;</a>
<a style="text-decoration:none;display:block;top:10px;right:20px;position:absolute;width:30px; height:20px;"
href="'.tpl::_ax(tpl::_export('','index'),array('tpl_main','__index')).'/"
>&nbsp;</a>
		<table class="fixed wide compact"><col width=289px><col width="auto">
		<col width="231px"><col width="auto">
		<col width="132px"><col width="50px">
		<tr><td style="background: url(img/hat01.jpg);"></td>
		<td style="background: url(img/hat00.jpg) repeat-x"></td>
		<td style="padding-top:10px;background: url(img/hat001.jpg);vertical-align:top;" class="align_right">
		</td>
		<td style="background: url(img/hat00.jpg) repeat-x"></td>
		<td style="padding-top:10px;background: url(img/hat11.jpg);vertical-align:top;" class="align_right">
		</td>
		<td style="background: url(img/hat12.jpg)">
		</td>
		</tr>
		</table></div>
		</td>
	</tr>
	<tr>
		<td colspan=3 style="padding-left:20px;height:24pt;background: url(img/menu02.gif) repeat-x bottom;">
<span class="link menu tahoma size11 ctext"><a class="blue link" href="'.tpl::_ax(tpl::_export('','curl','do','id'),array('tpl_main','__curl_do_id')).'">Главная</a>
'.tpl::_ax(tpl::_export('','menu','head'),array('tpl_main','__menu_head')).'
</span>
<div style="float:right;height:30px;overflow:hidden; width:180px;">
<form name="adminsearch" action="" method="POST">
<input type="text" name="adminsearch">
<input type="hidden" name="form" value="MAIN:search">
<input type="image" src="img/search.gif">
</form></div>
</td>
	</tr>
	<tr>
		<td style="width:211px;padding: 10px ;background:url(img/side_z.gif) repeat-y transparent right;"  valign="top" >
		<div style="width:211px;">
		'.tpl::_ax(tpl::_export('','pluginlist'),array('tpl_main','__pluginlist')).'	<div id="main_menu" class="context">
		'.tpl::_ax(tpl::_export('','menu','right'),array('tpl_main','__menu_right')).'</div>
</div>
		</td>
		<td '.tpl::_bx(tpl::_export('','menu','has','left'),'','').' valign="top"
			style="padding: 10px; width:100%;">
		'.(isset($par['data'])?$par['data']:'').'
		'.tpl::_bx(tpl::_export('','menu','has','left'),'','</td><td style="padding: 20px;background:url(img/side_z.gif) repeat-y transparent;" valign="top">').'
			'.tpl::_ax(tpl::_export('','menu','left'),array('tpl_main','__menu_left')).'
		</td>
	</tr>
	<tr>
		<td colspan=3  style="height:30pt;background: url(img/menu20.gif) repeat-x;">
		'.tpl::_ax(tpl::_export('','menu','botom'),array('tpl_main','__menu_botom')).'
		</td>
	</tr>
</table>
</div>
<div class="hidden">

<form id="_goto" method="get" action=""></form>	
	
<input id="wincntr_tpl" type="button" title="выравнивание" class="win_align">
<textarea id="textcntr_tpl" rows=4 cols=80 style="border:0;overflow:auto;" class="tahoma size11 " onKeyDown="need_Save()">&nbsp;</textarea>
<textarea id="htmlcntr_tpl" rows=4 cols=80 style="border:0;overflow:auto;" class="hidden" onKeyDown="need_Save()">&nbsp;</textarea>
<input type="checkbox" class="glass" id="check_0_15" title="элемент включен в главное меню">

<table id="order_tpl" class="compact"><tr><th class="nopage"><input type="button" class="win_max p120">
</th><th class="nopage">
<input type="text" class="order" style="width:15px;">
</th><th class="nopage">
<input type="button" class="win_max p105">
</th></tr></table>

<input type="checkbox" value=\'1\' id="check_tpl">

<div class="tahoma toolbox cltext size11" style="position:absolute;z-index:1001;" id="menu_tpl"></div>

<div class="tahoma cltext size11" style="z-index:10;top:0px;left:0;padding:10px;border: 1px gray solid; background:white;position:absolute;top:1em;" id="xmenu">
<a class="long" style="margin:2px;display:block;" href="#1">Клиент/Партнер/VIP</a>
<a class="long" style="margin:2px;display:block;" href="#2">4 цены</a>
<a class="long" style="margin:2px;display:block;" href="#3">одна цена</a>
</div>

<div class="tahoma cltext size11" style="z-index:20;top:0px;left:0;padding:10px;border: 1px gray solid; background:white;position:absolute;top:1em;" id="xoptions">
'.tpl::_ax(tpl::_export('qa','getOptions'),array('tpl_main','qa__getOptions')).'
</div>

<div class="tahoma cltext size11" style="z-index:10;top:0px;left:0;padding:10px;border: 1px gray solid; background:white;position:absolute;top:1em;" id="xuser">
<a class="long" style="margin:2px;display:block;" href="#1">Клиент</a>
<a class="long" style="margin:2px;display:block;" href="#2">Партнер</a>
<a class="long" style="margin:2px;display:block;" href="#3">VIP</a>
</div>
'.tpl::_a($par['dd_menu'],array('tpl_main','dd_menu')).'

'.tpl::_a($par['katalog_back'],array('tpl_main','katalog_back')).'

'.tpl::_a($par['katalog_back_bt'],array('tpl_main','katalog_back_bt')).'

</div>
<div style="display:none;" id=\'xxMenu\'>'.(isset($par['xMenu'])?$par['xMenu']:'').'</div>

<div id="debug"></div>
<script type="text/javascript">
window.setup_menu_plus=[
	"xmenu"	
	'.(isset($par['js_string'])?$par['js_string']:'').'
]
</script>
</body>
'.tpl::_a($par['ajax'],array('tpl_main','ajax')).'

</html>';
}

function katalog_searchres(&$par){		
		return '<a href="?do=katalog&item='.(isset($par['id'])?$par['id']:'').'">
<span class="red">'.(isset($par['articul'])?$par['articul']:'').'</span>
&nbsp;&nbsp;'.(isset($par['descr'])?$par['descr']:'').'
</a>';
}

function searchres(&$par){		
		return '<span class="blue">'.(isset($par['id'])?$par['id']:'').' Строка поиска: `'.(isset($par['searchstr'])?$par['searchstr']:'').'`</span>
'.(isset($par['pages'])?$par['pages']:'').'
'.tpl::_a($par['list'],array('tpl_main','searchres_list')).'
'.(isset($par['pages'])?$par['pages']:'');
}

function searchres_list(&$par){		
		return '<div class="tahoma blue">'.(isset($par['page'])?$par['page']:'').'</div>
'.tpl::_a($par['items'],array('tpl_main','searchres_list_items'));
}

function searchres_list_items(&$par){		
		return '<div style="padding: 0 0px 0 50px;" class="tahoma size11">'.(isset($par['item'])?$par['item']:'').'</div>';
}

function pages(&$par){		
		return '<table class="link tahoma ctext"><tr>
'.tpl::_a($par['mmin'],array('tpl_main','pages_mmin')).'
'.tpl::_a($par['min'],array('tpl_main','pages_min')).'
'.tpl::_a($par['page'],array('tpl_main','pages_page')).'
'.tpl::_a($par['max'],array('tpl_main','pages_max')).'
'.tpl::_a($par['mmax'],array('tpl_main','pages_mmax')).'
</tr></table>';
}

function pages_mmin(&$par){		
		return '<td style="padding:5px;">
	<a href="'.tpl::_ax(tpl::_export('','curl','pg'),array('tpl_main','pages_mmin___curl_pg')).'pg=0">&lt;&lt;</a>
</td>';
}

function pages_min(&$par){		
		return '<td style="padding:5px;">
	<a href="'.tpl::_ax(tpl::_export('','curl','pg'),array('tpl_main','pages_min___curl_pg')).'pg='.(isset($par['m5'])?$par['m5']:'').'">&lt;</a>
</td>';
}

function pages_page(&$par){		
		return '<td style="padding:5px;">
'.tpl::_a($par['link'],array('tpl_main','pages_page_link')).'
'.tpl::_a($par['txt'],array('tpl_main','pages_page_txt')).'
</td>';
}

function pages_page_link(&$par){		
		return '<a href="'.tpl::_ax(tpl::_export('','curl','pg'),array('tpl_main','pages_page_link___curl_pg')).'pg='.(isset($par['url'])?$par['url']:'').'">'.(isset($par['txt'])?$par['txt']:'').'</a>';
}

function pages_page_txt(&$par){		
		return '<span style="line-height:21px;" class="red '.tpl::_b($par['current'],'','').'">'.(isset($par['txt'])?$par['txt']:'').'</span>';
}

function pages_max(&$par){		
		return '<td style="padding:5px;">
	<a href="'.tpl::_ax(tpl::_export('','curl','pg'),array('tpl_main','pages_max___curl_pg')).'pg='.(isset($par['m5'])?$par['m5']:'').'">&gt;</a>
</td>';
}

function pages_mmax(&$par){		
		return '<td style="padding:5px;">
	<a href="'.tpl::_ax(tpl::_export('','curl','pg'),array('tpl_main','pages_mmax___curl_pg')).'pg='.(isset($par['m5'])?$par['m5']:'').'">&gt;&gt;</a>
</td>';
}

function text_edit_line(&$par){		
		return '<tr id="pg_'.(isset($par['id'])?$par['id']:'').'">
<td class="text_edit" id="item_name_'.(isset($par['id'])?$par['id']:'').'" title="тип:text">'.tpl::_d($par['name'],'текст').'</td>
<td colspan=3 class="html_edit" id="item_text_'.(isset($par['id'])?$par['id']:'').'">'.(isset($par['text_breath'])?$par['text_breath']:'').'</td>
<td class="align_center" style="padding:0 2px;" nowrap>
'.tpl::_ax(tpl::_export('','tpl','admin','order_elm_start'),array('tpl_main','text_edit_line___tpl_admin_order_elm_start')).'
<input type="text" onKeyDown="need_Save()" class="order size11" style="width:15px;" name="item_order_'.(isset($par['id'])?$par['id']:'').'">
'.tpl::_ax(tpl::_export('','tpl','admin','order_elm_fin'),array('tpl_main','text_edit_line___tpl_admin_order_elm_fin')).'
</td>
<td style="padding:0 2px;">'.tpl::_ax(tpl::_export('','tpl','admin','delrec_elm'),array('tpl_main','text_edit_line___tpl_admin_delrec_elm')).'
</td>
</tr>';
}

function header_edit_line(&$par){		
		return '<tr class="'.tpl::_d($par['trclass'],'even').'" id="pg_'.(isset($par['id'])?$par['id']:'').'">
<td style="background:white;padding:0;">
<table class="tahoma ctext size11 long fixed">
<col width="23px">
<col width="90px">
<col width="auto">
<col width="23px">
<col width="50px">
<col width="23px"><tr>

<th>
</th>
<td class="text_edit" id="item_name_'.(isset($par['id'])?$par['id']:'').'" title="тип:Заголовок">'.tpl::_d($par['name'],'заголовок').'</td>

<td class="html_edit" id="item_text_'.(isset($par['id'])?$par['id']:'').'" title="Текст заголовка">
'.(isset($par['text'])?$par['text']:'').'
</td>
<td  class="align_center" style="padding:0 2px;">
<input type="text" name="item_align_'.(isset($par['id'])?$par['id']:'').'" class="align hidden"></td>
<th class="nopage align_center" style="padding:0 2px;" nowrap>
'.tpl::_ax(tpl::_export('','tpl','admin','order_elm_start'),array('tpl_main','header_edit_line___tpl_admin_order_elm_start')).'
<input type="text" class="order size11" onKeyDown="need_Save()" style="width:15px;" name="item_order_'.(isset($par['id'])?$par['id']:'').'">
'.tpl::_ax(tpl::_export('','tpl','admin','order_elm_fin'),array('tpl_main','header_edit_line___tpl_admin_order_elm_fin')).'
</th>
<th class="align_center" style="padding:0 2px;">'.tpl::_ax(tpl::_export('','tpl','admin','delrec_elm'),array('tpl_main','header_edit_line___tpl_admin_delrec_elm')).'
</th>
</tr></table></td></tr>';
}

function column_line(&$par){		
		return '<tr id="pg_'.(isset($par['id'])?$par['id']:'').'">
<td >
<input type="button" class="button green" value="Ред.колонку" onClick="window.location.replace(\''.tpl::_ax(tpl::_export('','curl','do','id'),array('tpl_main','column_line___curl_do_id')).'do=page&id='.(isset($par['id'])?$par['id']:'').'\')"></td>
<td class="text_edit" id="item_text_'.(isset($par['id'])?$par['id']:'').'"  title="Имя колонки">'.(isset($par['item_text'])?$par['item_text']:'').'</td>
<td class="text_edit" id="item_width_'.(isset($par['id'])?$par['id']:'').'"  title="ширина колонки в пикс.">'.(isset($par['item_width'])?$par['item_width']:'').'</td>
<th class="nopage align_center" style="padding:0 2px;" nowrap>
'.tpl::_ax(tpl::_export('','tpl','admin','order_elm_start'),array('tpl_main','column_line___tpl_admin_order_elm_start')).'
<input type="text" class="order size11" onKeyDown="need_Save()" style="width:15px;" name="item_order_'.(isset($par['id'])?$par['id']:'').'">
'.tpl::_ax(tpl::_export('','tpl','admin','order_elm_fin'),array('tpl_main','column_line___tpl_admin_order_elm_fin')).'
</th>
<th class="align_center" style="padding:0 2px;">'.tpl::_ax(tpl::_export('','tpl','admin','delrec_elm'),array('tpl_main','column_line___tpl_admin_delrec_elm')).'
</th></tr>';
}

function article_line(&$par){		
		return '<tr id="pg_'.(isset($par['id'])?$par['id']:'').'">
<td >
<input type="button" class="button green" value="Ред.Описание" onClick="window.location.replace(\''.tpl::_ax(tpl::_export('','curl','do','id'),array('tpl_main','article_line___curl_do_id')).'do=page&id='.(isset($par['id'])?$par['id']:'').'\')"></td>
<td class="text_edit" id="item_text_'.(isset($par['id'])?$par['id']:'').'"  title="Название ссылки">'.(isset($par['item_text'])?$par['item_text']:'').'</td>
<th class="nopage align_center" style="padding:0 2px;" nowrap>
'.tpl::_ax(tpl::_export('','tpl','admin','order_elm_start'),array('tpl_main','article_line___tpl_admin_order_elm_start')).'
<input type="text" class="order size11" onKeyDown="need_Save()" style="width:15px;" name="item_order_'.(isset($par['id'])?$par['id']:'').'">
'.tpl::_ax(tpl::_export('','tpl','admin','order_elm_fin'),array('tpl_main','article_line___tpl_admin_order_elm_fin')).'
</th>
<th class="align_center" style="padding:0 2px;">'.tpl::_ax(tpl::_export('','tpl','admin','delrec_elm'),array('tpl_main','article_line___tpl_admin_delrec_elm')).'
</th></tr>';
}

function article_list(&$par){		
		return '<tr class=" context '.tpl::_d($par['trclass'],'even').'" id="pg_'.(isset($par['id'])?$par['id']:'').'">
<td class="bwhite" style="padding:0;">
<table class="tahoma ctext size11 long fixed">
<col width="23px">
<col width="90px">
<col width="auto">
<col width="125px">
<col width="23px">
<col width="50px">
<col width="23px"><tr>

<th class="nopage align_center" style="padding:0 2px;">'.tpl::_ax(tpl::_export('','tpl','admin','win_elm2'),array('tpl_main','article_list___tpl_admin_win_elm2')).'</th>
<td class="text_edit" id="item_name_'.(isset($par['id'])?$par['id']:'').'" title="тип:Заголовок">'.tpl::_d($par['name'],'Список статей').'</td>

<td class="html_edit" id="item_text_'.(isset($par['id'])?$par['id']:'').'" title="Текст заголовка">
'.(isset($par['text'])?$par['text']:'').'
</td>
<td>
<input type="submit" name="new_article_'.(isset($par['id'])?$par['id']:'').'"
title="добавить новую статью" class="button green" value="Добавить">
</td><td  class="align_center" style="padding:0 2px;">
<input type="text" name="item_align_'.(isset($par['id'])?$par['id']:'').'" class="align hidden"></td>
<th class="nopage align_center" style="padding:0 2px;" nowrap>
'.tpl::_ax(tpl::_export('','tpl','admin','order_elm_start'),array('tpl_main','article_list___tpl_admin_order_elm_start')).'
<input type="text" class="order size11" onKeyDown="need_Save()" style="width:15px;" name="item_order_'.(isset($par['id'])?$par['id']:'').'">
'.tpl::_ax(tpl::_export('','tpl','admin','order_elm_fin'),array('tpl_main','article_list___tpl_admin_order_elm_fin')).'
</th>
<th class="align_center" style="padding:0 2px;">'.tpl::_ax(tpl::_export('','tpl','admin','delrec_elm'),array('tpl_main','article_list___tpl_admin_delrec_elm')).'
</th>
</tr>
<tr class="bwhite" style="display:none;">
<td colspan=2 ></td>
<td colspan=3 style="padding:0;">
<table class="tahoma ctext size11 fixed">
<col width="125px"><col width="auto"><col width="auto"><col width="50px"><col width="25px">
'.(isset($par['links'])?$par['links']:'').'</table>
</td>
<td colspan=2 ></td>
</tr></table></td></tr>';
}

function plugin_edit_line(&$par){		
		return '<tr class="bwhite context '.tpl::_d($par['trclass'],'even').'" id="pg_'.(isset($par['id'])?$par['id']:'').'">
<td style="padding:0;">
<table class="tahoma ctext size11 long fixed">
<col width="23px">
<col width="90px">
<col width="auto">
<col width="auto">
<col width="25px"><col width="50px">
<col width="23px"><tr>

<th class="nopage align_center" style="padding:0 2px;"></th>
<td class="text_edit" id="item_name_'.(isset($par['id'])?$par['id']:'').'" title="тип:text">'.tpl::_d($par['name'],'модуль').'</td>
<td class="text_edit" id="item_text_'.(isset($par['id'])?$par['id']:'').'" title="имя модуля">
'.(isset($par['text'])?$par['text']:'').'
</td>
<td class="text_edit" id="item_url_'.(isset($par['id'])?$par['id']:'').'" title="имя функции">
'.(isset($par['url'])?$par['url']:'').'
</td>
<td></td>
<th class="nopage align_center" style="padding:0 2px;" nowrap>
'.tpl::_ax(tpl::_export('','tpl','admin','order_elm_start'),array('tpl_main','plugin_edit_line___tpl_admin_order_elm_start')).'
<input type="text" class="order size11" onKeyDown="need_Save()" style="width:15px;" name="item_order_'.(isset($par['id'])?$par['id']:'').'">
'.tpl::_ax(tpl::_export('','tpl','admin','order_elm_fin'),array('tpl_main','plugin_edit_line___tpl_admin_order_elm_fin')).'
</th>
<th class="align_center" style="padding:0 2px;">'.tpl::_ax(tpl::_export('','tpl','admin','delrec_elm'),array('tpl_main','plugin_edit_line___tpl_admin_delrec_elm')).'
</th>
</tr></table></td></tr>';
}

function katalogx_edit_line(&$par){		
		return '<tr class="bwhite '.tpl::_d($par['trclass'],'even').'" id="pg_'.(isset($par['id'])?$par['id']:'').'">
<td style="padding:0;">
<table class="tahoma ctext size11 long fixed">
<col width="23px">
<col width="90px">
<col width="auto">
<col width="125px">
<col width="125px"><col width="25px"><col width="50px">
<col width="23px"><tr>

<th class="nopage align_center" style="padding:0 2px;">
<input name="item_clear_'.(isset($par['id'])?$par['id']:'').'" onClick="document.location=\''.tpl::_ax(tpl::_export('','curl','do','cat'),array('tpl_main','katalogx_edit_line___curl_do_cat')).'do=cat&cat='.(isset($par['id'])?$par['id']:'').'\'" type="button" class="win_max" value="&nbsp;"></th>
<td class="text_edit" id="article_'.(isset($par['id'])?$par['id']:'').'" title="код раздела для CSV">'.tpl::_d($par['name'],'каталог').'</td>
<td title="Тип раздела каталога">
<div class="wide long xmenu">
<input class="long" type="text" name="cat_type_'.(isset($par['id'])?$par['id']:'').'" value="'.tpl::_d($par['align'],'').'">
</div></td>
<td><div class="uploader action_both">
	<input type="button" class="button green"
		 value="Общее Фото" onClick="ReplaceImg(this);"></div>
		<input type="text" style="display:none;"
			name="pic_small_'.(isset($par['id'])?$par['id']:'').'">
		<input type="text" style="display:none;"
			name="pic_big_'.(isset($par['id'])?$par['id']:'').'">
</td>
<td><div class="uploader" id="item_text_'.(isset($par['id'])?$par['id']:'').'">
	<input onClick="loadCSV(this)" type="text" style="display:none;" autocomplete="off"
			name="item_csv_'.(isset($par['id'])?$par['id']:'').'">	<input type="button" class="button green"
		 value="Экспорт CSV">
	 </div>
		 </td>
<td></td>
<th class="nopage align_center" style="padding:0 2px;" nowrap>
'.tpl::_ax(tpl::_export('','tpl','admin','order_elm_start'),array('tpl_main','katalogx_edit_line___tpl_admin_order_elm_start')).'
<input type="text" class="order size11" onKeyDown="need_Save()" style="width:15px;" name="item_order_'.(isset($par['id'])?$par['id']:'').'">
'.tpl::_ax(tpl::_export('','tpl','admin','order_elm_fin'),array('tpl_main','katalogx_edit_line___tpl_admin_order_elm_fin')).'
</th>
<th class="align_center" style="padding:0 2px;">'.tpl::_ax(tpl::_export('','tpl','admin','delrec_elm'),array('tpl_main','katalogx_edit_line___tpl_admin_delrec_elm')).'
</th>
</tr></table></td></tr>';
}

function katalog_edit_line(&$par){		
		return '<tr class="bwhite '.tpl::_d($par['trclass'],'even').'" id="pg_'.(isset($par['id'])?$par['id']:'').'">
<td style="padding:0;">
<table class="tahoma ctext size11 long fixed">
<col width="23px">
<col width="90px">
<col width="auto">
<col width="125px">
<col width="125px"><col width="25px"><col width="50px">
<col width="23px"><tr>

<th class="nopage align_center" style="padding:0 2px;">
<input name="item_clear_'.(isset($par['id'])?$par['id']:'').'" onClick="document.location=\''.tpl::_ax(tpl::_export('','curl','do','cat'),array('tpl_main','katalog_edit_line___curl_do_cat')).'do=cat&cat='.(isset($par['id'])?$par['id']:'').'\'" type="button" class="win_max" value="&nbsp;"></th>
<td class="text_edit"  id="article_'.(isset($par['id'])?$par['id']:'').'"  title="код раздела для CSV">'.tpl::_d($par['name'],'каталог').'</td>
<td></td>
<td><div class="uploader action_both">
	<input type="button" class="button green"
		 value="Общее Фото" onClick="ReplaceImg(this);"></div>
		<input type="text" style="display:none;"
			name="pic_small_'.(isset($par['id'])?$par['id']:'').'">
		<input type="text" style="display:none;"
			name="pic_big_'.(isset($par['id'])?$par['id']:'').'">
</td>
<td><div class="uploader" id="item_text_'.(isset($par['id'])?$par['id']:'').'">
	<input onClick="loadCSV(this)" type="text" style="display:none;" autocomplete="off"
			name="item_csv_'.(isset($par['id'])?$par['id']:'').'">	<input type="button" class="button green"
		 value="Экспорт CSV">
	 </div>
		 </td>
<td></td>
<th class="nopage align_center" style="padding:0 2px;" nowrap>
'.tpl::_ax(tpl::_export('','tpl','admin','order_elm_start'),array('tpl_main','katalog_edit_line___tpl_admin_order_elm_start')).'
<input type="text" class="order size11" onKeyDown="need_Save()" style="width:15px;" name="item_order_'.(isset($par['id'])?$par['id']:'').'">
'.tpl::_ax(tpl::_export('','tpl','admin','order_elm_fin'),array('tpl_main','katalog_edit_line___tpl_admin_order_elm_fin')).'
</th>
<th class="align_center" style="padding:0 2px;">'.tpl::_ax(tpl::_export('','tpl','admin','delrec_elm'),array('tpl_main','katalog_edit_line___tpl_admin_delrec_elm')).'
</th>
</tr></table></td></tr>';
}

function common_line(&$par){		
		return '<tr class="context '.tpl::_d($par['trclass'],'even').'" id="pg_'.(isset($par['id'])?$par['id']:'').'">
<td class="bwhite" style="padding:0;">
<table class="tahoma ctext size11 long fixed">
<col width="23px">
<col width="90px">
'.tpl::_a($par['cols'],array('tpl_main','common_line_cols')).'
<col width="50px">
<col width="23px"><tr>
'.tpl::_b($par['minmax'],'<th></th>','<th class="nopage align_center" style="padding:0 2px;"><div class="win_max closed  open_close">&nbsp;</div></th>').'
<td class="text_edit" id="name_'.(isset($par['id'])?$par['id']:'').'" title="'.(isset($par['type'])?$par['type']:'').'">'.(isset($par['item_name'])?$par['item_name']:'').'</td>
'.tpl::_a($par['fields'],array('tpl_main','common_line_fields')).'

<th class="nopage align_center" style="padding:0 2px;" nowrap>
'.tpl::_ax(tpl::_export('','tpl','admin','order_elm_start'),array('tpl_main','common_line___tpl_admin_order_elm_start')).'
<input type="text" class="order size11" onKeyDown="need_Save()" style="width:15px;" name="item_order_'.(isset($par['id'])?$par['id']:'').'">
'.tpl::_ax(tpl::_export('','tpl','admin','order_elm_fin'),array('tpl_main','common_line___tpl_admin_order_elm_fin')).'
</th>
<th class="align_center" style="padding:0 2px;">'.tpl::_d($par['lock1'],'').tpl::_ax(tpl::_export('','tpl','admin','delrec_elm'),array('tpl_main','common_line___tpl_admin_delrec_elm')).tpl::_d($par['lock2'],'').'
</th>
</tr>
'.tpl::_a($par['pictures'],array('tpl_main','common_line_pictures')).'
'.tpl::_a($par['links'],array('tpl_main','common_line_links')).'
'.tpl::_a($par['komment'],array('tpl_main','common_line_komment')).'
'.tpl::_a($par['article'],array('tpl_main','common_line_article')).'
'.tpl::_a($par['column'],array('tpl_main','common_line_column')).'
</table></td></tr>';
}

function common_line_cols(&$par){		
		return '<col width="'.(isset($par['width'])?$par['width']:'').'">';
}

function common_line_fields(&$par){		
		return tpl::_a($par['txt'],array('tpl_main','common_line_fields_txt')).'
'.tpl::_a($par['html'],array('tpl_main','common_line_fields_html')).'
'.tpl::_a($par['csv'],array('tpl_main','common_line_fields_csv')).'
'.tpl::_a($par['checkbox'],array('tpl_main','common_line_fields_checkbox')).'
'.tpl::_a($par['button0'],array('tpl_main','common_line_fields_button0')).'
'.tpl::_a($par['button'],array('tpl_main','common_line_fields_button')).'
'.tpl::_a($par['link'],array('tpl_main','common_line_fields_link')).'
'.tpl::_a($par['img'],array('tpl_main','common_line_fields_img')).'
'.tpl::_a($par['align'],array('tpl_main','common_line_fields_align')).'
'.tpl::_a($par['smallinput'],array('tpl_main','common_line_fields_smallinput')).'
'.tpl::_a($par['stolb'],array('tpl_main','common_line_fields_stolb')).'
'.tpl::_a($par['mediuminput'],array('tpl_main','common_line_fields_mediuminput')).'
'.tpl::_a($par['razmer1'],array('tpl_main','common_line_fields_razmer1')).'
'.tpl::_a($par['razmer2'],array('tpl_main','common_line_fields_razmer2')).'
'.tpl::_a($par['dropdown'],array('tpl_main','common_line_fields_dropdown'));
}

function common_line_fields_txt(&$par){		
		return '<td class="text_edit" id="'.(isset($par['name'])?$par['name']:'').'_'.(isset($par['id'])?$par['id']:'').'" title="'.(isset($par['title'])?$par['title']:'').'">'.(isset($par['text'])?$par['text']:'').'</td>';
}

function common_line_fields_html(&$par){		
		return '<td id="'.(isset($par['name'])?$par['name']:'').'_'.(isset($par['id'])?$par['id']:'').'" class="html_edit" >
'.(isset($par['text_breath'])?$par['text_breath']:'').'
</td>';
}

function common_line_fields_csv(&$par){		
		return '<td><div class="uploader nocontext" id="'.(isset($par['name'])?$par['name']:'').'_'.(isset($par['id'])?$par['id']:'').'">
	<input onClick="loadCSV(this)" type="text" style="display:none;" autocomplete="off"
			name="'.(isset($par['name'])?$par['name']:'').'_'.(isset($par['id'])?$par['id']:'').'">	<input type="button" class="nocontext button green"
		 value="'.(isset($par['text'])?$par['text']:'').'">
</div></td>';
}

function common_line_fields_checkbox(&$par){		
		return '<td title="'.(isset($par['title'])?$par['title']:'').'" >'.(isset($par['text'])?$par['text']:'').'<input class="win_check" type="text" name="'.(isset($par['name'])?$par['name']:'').'_'.(isset($par['id'])?$par['id']:'').'">
</td>';
}

function common_line_fields_button0(&$par){		
		return '<td><input type="submit" name="goto_'.(isset($par['name'])?$par['name']:'').'_'.(isset($par['id'])?$par['id']:'').'"
title="'.(isset($par['title'])?$par['title']:'').'" class="button green" value="'.(isset($par['text'])?$par['text']:'').'">
</td>';
}

function common_line_fields_button(&$par){		
		return '<td>
<input type="submit" name="new_'.(isset($par['name'])?$par['name']:'').'_'.(isset($par['id'])?$par['id']:'').'"  onmousedown="window.el_open(this); return false;"
title="'.(isset($par['title'])?$par['title']:'').'" class="button green" value="'.(isset($par['text'])?$par['text']:'').'">
</td>';
}

function common_line_fields_link(&$par){		
		return '<td title="'.(isset($par['title'])?$par['title']:'').'" style="padding:2px 20px 2px 0;"><div style="width:100%;" ><nobr>
<div class="uploader" style="background-image:url(img/upload.gif);float:left;width:20px;height:20px;" >&nbsp;</div>
<input type="text" onKeyDown="need_Save()" class="nocontext long link_toolbox" name="'.(isset($par['name'])?$par['name']:'').'_'.(isset($par['id'])?$par['id']:'').'"></nobr></div>
</td>';
}

function common_line_fields_img(&$par){		
		return '<td><div class="uploader action_both">
<input type="button" class="button green" value="'.(isset($par['text'])?$par['text']:'').'"
onclick="NewGalleryImg(this,false);" ></div>
</td>';
}

function common_line_fields_align(&$par){		
		return '<td class="align_center" style="padding:0 2px;">
<input type="text" name="item_align_'.(isset($par['id'])?$par['id']:'').'" class="align hidden"></td>';
}

function common_line_fields_smallinput(&$par){		
		return '<td   id="item_name_'.(isset($par['id'])?$par['id']:'').'"  style="overflow:visible;"><nobr><b>'.(isset($par['title'])?$par['title']:'').'</b> <input type="text" onKeyDown="need_Save()" style="width:20px;" name="'.(isset($par['name'])?$par['name']:'').'_'.(isset($par['id'])?$par['id']:'').'"></nobr></td>';
}

function common_line_fields_stolb(&$par){		
		return '<td   id="item_name_'.(isset($par['id'])?$par['id']:'').'" style="width:90px;padding-right:0;padding-left:0px;margin:0;" title="Кол-во столбцов"><nobr><span style="margin-left:10px;">'.(isset($par['title'])?$par['title']:'').'</span> <input type="text" onKeyDown="need_Save()" style="width:20px;" name="'.(isset($par['name'])?$par['name']:'').'_'.(isset($par['id'])?$par['id']:'').'"></nobr></td>';
}

function common_line_fields_mediuminput(&$par){		
		return '<td   id="item_name_'.(isset($par['id'])?$par['id']:'').'"  ><nobr><b>'.(isset($par['title'])?$par['title']:'').'</b> <input type="text" onKeyDown="need_Save()" style="width:40px;" name="'.(isset($par['name'])?$par['name']:'').'_'.(isset($par['id'])?$par['id']:'').'"></nobr></td>';
}

function common_line_fields_razmer1(&$par){		
		return '<td   id="item_name_'.(isset($par['id'])?$par['id']:'').'" style="width:125px;border-right:none;padding-left:0;padding-right:0;margin:0;" title="'.(isset($par['vp'])?$par['vp']:'').'"><nobr><span style="margin-left:10px;">'.(isset($par['title'])?$par['title']:'').'</span> <input type="text" onKeyDown="need_Save()" style="width:35px;" name="'.(isset($par['name'])?$par['name']:'').'_'.(isset($par['id'])?$par['id']:'').'"></nobr>';
}

function common_line_fields_razmer2(&$par){		
		return '<nobr>'.(isset($par['title'])?$par['title']:'').' <input type="text" onKeyDown="need_Save()" style="width:35px;" name="'.(isset($par['name'])?$par['name']:'').'_'.(isset($par['id'])?$par['id']:'').'"></nobr></td><td style="width:0;padding:0;"></td>';
}

function common_line_fields_dropdown(&$par){		
		return '<td><div class="nocontext wide long '.(isset($par['xname'])?$par['xname']:'').'" title="'.(isset($par['title'])?$par['title']:'').'">
<input class="long" type="text" name="'.(isset($par['name'])?$par['name']:'').'_'.(isset($par['id'])?$par['id']:'').'">
</div></td>';
}

function common_line_pictures(&$par){		
		return '<tr class="bwhite" style="display:none;">
<td class="bwhite" colspan=2"></td>
<td colspan='.(isset($par['colnum'])?$par['colnum']:'').' style="padding:0;">
<table class="tahoma ctext size11 fixed">
<col width="auto">
<col width="50px">
<col width="25px">
'.(isset($par['links'])?$par['links']:'').'
</table>
</td><td class="bwhite"></td>
</tr>';
}

function common_line_links(&$par){		
		return '<tr  class="bwhite" style="display:none;">
<td class="bwhite" colspan=2 ></td>
<td colspan='.(isset($par['colnum'])?$par['colnum']:'').' style="padding:0;">
<table class="tahoma ctext size11 fixed">
<col width="auto"><col width="auto"><col width="50px"><col width="25px">
'.(isset($par['links'])?$par['links']:'').'</table>
</td><td class="bwhite"></td>
</tr>';
}

function common_line_komment(&$par){		
		return '<tr  class="bwhite" style="display:none;">
<td class="bwhite" colspan=2 ></td>
<td colspan='.(isset($par['colnum'])?$par['colnum']:'').' style="padding:0;">
<table class="tahoma ctext size11 fixed" style="background:#FFFFFF;">
<col width="auto"><col width="auto"><col width="auto"><col width="auto"><col width="50px"><col width="25px">
<tr id="pg_'.(isset($par['id'])?$par['id']:'').'">
<td title="Дата"><b>Дата</b></td>
<td title="Имя"><b>Имя</b></td>
<td title="Цитата"><b>Цитата</b></td>
<td title="Комментарий"><b>Комментарий</b></td>
<th class="nopage align_center" style="padding:0 2px;" nowrap></th>
<th class="align_center" style="padding:0 2px;">
</th></tr>
'.(isset($par['links'])?$par['links']:'').'</table>
</td><td class="bwhite"></td>
</tr>';
}

function common_line_article(&$par){		
		return '<tr  class="bwhite" style="display:none;">
<td class="bwhite" colspan=2 ></td>
<td colspan='.(isset($par['colnum'])?$par['colnum']:'').' style="padding:0;">
<table class="tahoma ctext size11 fixed">
<col width="125px"><col width="auto"><col width="50px"><col width="25px">
'.(isset($par['articles'])?$par['articles']:'').'</table>
</td><td class="bwhite"></td>
</tr>';
}

function common_line_column(&$par){		
		return '<tr class="bwhite" style="display:none;">
<td class="bwhite" colspan=2></td>
<td colspan='.(isset($par['colnum'])?$par['colnum']:'').' style="padding:0;">
<table class="tahoma ctext size11 fixed">
<col width="125px"><col width="auto"><col width="auto"><col width="50px"><col width="25px">
'.(isset($par['articles'])?$par['articles']:'').'</table>
</td><td class="bwhite"></td>
</tr>';
}

function gallery_edit_line(&$par){		
		return '<tr class="bwhite context '.tpl::_d($par['trclass'],'even').'" id="pg_'.(isset($par['id'])?$par['id']:'').'">
<td  style="padding:0;">
<table class="tahoma ctext size11 long fixed">
<col width="23px">
<col width="90px">
<col width="auto">
<col width="125px"><col width="25px"><col width="50px">
<col width="23px"><tr>
<th class="nopage align_center" style="padding:0 2px;">'.tpl::_ax(tpl::_export('','tpl','admin','win_elm2'),array('tpl_main','gallery_edit_line___tpl_admin_win_elm2')).'</th>
<td class="text_edit" id="item_name_'.(isset($par['id'])?$par['id']:'').'" title="тип:Галерея">'.tpl::_d($par['name'],'Галерея').'</td>
<td   id="item_name_'.(isset($par['id'])?$par['id']:'').'"  ><nobr><b>Количество столбцов:</b> <input type="text" onKeyDown="need_Save()" style="width:20px;" name="item_columns_'.(isset($par['id'])?$par['id']:'').'"></nobr></td>
<td><div class="uploader action_both">
<input type="button" class="button green" value="Доб. фото"
onclick="NewGalleryImg(this,false);" ></div>
</td>
<td class="align_center" style="padding:0 2px;">
<input type="text" name="item_align_'.(isset($par['id'])?$par['id']:'').'" class="align hidden">			</td>
<th class="nopage align_center" style="padding:0 2px;" nowrap>
'.tpl::_ax(tpl::_export('','tpl','admin','order_elm_start'),array('tpl_main','gallery_edit_line___tpl_admin_order_elm_start')).'
<input type="text" class="order size11" onKeyDown="need_Save()" style="width:15px;" name="item_order_'.(isset($par['id'])?$par['id']:'').'">
'.tpl::_ax(tpl::_export('','tpl','admin','order_elm_fin'),array('tpl_main','gallery_edit_line___tpl_admin_order_elm_fin')).'
</th>
<th class="align_center" style="padding:0 2px;">'.tpl::_ax(tpl::_export('','tpl','admin','delrec_elm'),array('tpl_main','gallery_edit_line___tpl_admin_delrec_elm')).'
</th>
</tr>

<tr class="bwhite" style="display:none;">
<td colspan=2 ></td>
<td colspan=3 style="padding:0;">
<table class="tahoma ctext size11 fixed">
<col width="auto"><col width="50px"><col width="25px">
'.(isset($par['links'])?$par['links']:'').'
</table>
</td>
<td colspan=2 ></td>
</tr></table></td></tr>';
}

function links_edit_line(&$par){		
		return '<tr class="bwhite context '.tpl::_d($par['trclass'],'odd').'" id="pg_'.(isset($par['id'])?$par['id']:'').'">
<td style="padding:0;">
<table class="thetable tahoma ctext size11 long fixed">
<col width="23px">
<col width="90px">
<col width="auto"><col width="125px">
<col width="25px">
<col width="50px">
<col width="23px">
<tr>
<th class="nopage align_center" style="padding:0 2px;">'.tpl::_ax(tpl::_export('','tpl','admin','win_elm2'),array('tpl_main','links_edit_line___tpl_admin_win_elm2')).'</th>
<td class="text_edit" id="item_name_'.(isset($par['id'])?$par['id']:'').'" title="тип:Ссылки и файлы">'.tpl::_d($par['name'],'ссылки и файлы').'</td>
<td id="item_name_'.(isset($par['id'])?$par['id']:'').'" nowrap><nobr><b>Количество столбцов:</b> <input type="text" onKeyDown="need_Save()" style="width:20px;" name="item_columns_'.(isset($par['id'])?$par['id']:'').'"></nobr></td>
<td class="align_left" nowrap><input type="submit" onMouseDown="window.el_open(this); return false;"
			title="добавить новую ссылку" class="button green" name="new_link_add_'.(isset($par['id'])?$par['id']:'').'" value="Доб. ссылку">
		</td>
<td class="align_center "  style="padding:0 2px;"><input type="text" name="item_align_'.(isset($par['id'])?$par['id']:'').'" class="align hidden"></td>
<th class="nopage align_center" style="padding:0 2px;" nowrap>
'.tpl::_ax(tpl::_export('','tpl','admin','order_elm_start'),array('tpl_main','links_edit_line___tpl_admin_order_elm_start')).'
<input type="text" class="order size11" onKeyDown="need_Save()" style="width:15px;" name="item_order_'.(isset($par['id'])?$par['id']:'').'">
'.tpl::_ax(tpl::_export('','tpl','admin','order_elm_fin'),array('tpl_main','links_edit_line___tpl_admin_order_elm_fin')).'
</th>
<th class="align_center" style="padding:0 2px;">'.tpl::_ax(tpl::_export('','tpl','admin','delrec_elm'),array('tpl_main','links_edit_line___tpl_admin_delrec_elm')).'
</th>
</tr>

<tr class="bwhite"  style="display:none;">
<td colspan=2></td>
<td colspan=3 style="padding:0;">
<table class="tahoma ctext size11 fixed">
<col width="auto"><col width="auto"><col width="50px"><col width="25px">
'.(isset($par['links'])?$par['links']:'').'</table>
</td>
<td colspan=2 ></td>
</tr></table></td></tr>';
}

function link_edit_line(&$par){		
		return '<tr id="pg_'.(isset($par['id'])?$par['id']:'').'">
<td title="Вставьте ссылку или файл" style="padding:2px 20px 2px 0;"><div style="width:100%;" ><nobr>
<div class="uploader" style="background-image:url(img/upload.gif);float:left;width:20px;height:20px;" >&nbsp;</div>
<input type="text" onKeyDown="need_Save()" class="nocontext long link_toolbox" name="item_url_'.(isset($par['id'])?$par['id']:'').'"></nobr></div></td>
<td class="text_edit" id="item_text_'.(isset($par['id'])?$par['id']:'').'"  title="Описание ссылки или файла">'.(isset($par['text'])?$par['text']:'').'</td>
<th class="nopage align_center" style="padding:0 2px;" nowrap>
'.tpl::_ax(tpl::_export('','tpl','admin','order_elm_start'),array('tpl_main','link_edit_line___tpl_admin_order_elm_start')).'
<input type="text" class="order size11" onKeyDown="need_Save()" style="width:15px;" name="item_order_'.(isset($par['id'])?$par['id']:'').'">
'.tpl::_ax(tpl::_export('','tpl','admin','order_elm_fin'),array('tpl_main','link_edit_line___tpl_admin_order_elm_fin')).'
</th>
<th class="align_center" style="padding:0 2px;">'.tpl::_ax(tpl::_export('','tpl','admin','delrec_elm'),array('tpl_main','link_edit_line___tpl_admin_delrec_elm')).'
</th></tr>';
}

function komment_edit_line(&$par){		
		return '<tr id="pg_'.(isset($par['id'])?$par['id']:'').'">
<td class="text_edit" id="item_date_'.(isset($par['id'])?$par['id']:'').'"  title="Дата">'.(isset($par['date'])?$par['date']:'').'</td>
<td class="text_edit" id="item_username_'.(isset($par['id'])?$par['id']:'').'"  title="Имя">'.(isset($par['username'])?$par['username']:'').'</td>
<td class="text_edit" id="item_quote_'.(isset($par['id'])?$par['id']:'').'"  title="Цитата">'.(isset($par['quote'])?$par['quote']:'').'</td>
<td class="text_edit" id="item_text_'.(isset($par['id'])?$par['id']:'').'"  title="Комментарий">'.(isset($par['text'])?$par['text']:'').'</td>
<th class="nopage align_center" style="padding:0 2px;" nowrap>
'.tpl::_ax(tpl::_export('','tpl','admin','order_elm_start'),array('tpl_main','komment_edit_line___tpl_admin_order_elm_start')).'
<input type="text" class="order size11" onKeyDown="need_Save()" style="width:15px;" name="item_order_'.(isset($par['id'])?$par['id']:'').'">
'.tpl::_ax(tpl::_export('','tpl','admin','order_elm_fin'),array('tpl_main','komment_edit_line___tpl_admin_order_elm_fin')).'
</th>
<th class="align_center" style="padding:0 2px;">'.tpl::_ax(tpl::_export('','tpl','admin','delrec_elm'),array('tpl_main','komment_edit_line___tpl_admin_delrec_elm')).'
</th></tr>';
}

function table_edit_line(&$par){		
		return '<tr class="context '.tpl::_d($par['trclass'],'odd').'" id="pg_'.(isset($par['id'])?$par['id']:'').'" ><td class="bwhite"  style="padding:0;">
<table class="tahoma ctext size11 long fixed">
<col width="23px">
<col width="90px"><col width="140px"><col width="auto"><col width="auto"><col width="auto">
<col width="25px"><col width="50px">
<col width="23px">
<tr>
<th class="nopage align_center" style="padding:0 2px;"><div class="win_max closed  open_close">&nbsp;</div></th>
<td class="text_edit" id="item_name_'.(isset($par['id'])?$par['id']:'').'" title="тип:Таблица">'.tpl::_d($par['name'],'таблица').'</td>
<td>
<nobr>
<!--<input  onkeydown="need_Save();" type="text"  title="ширина фото в пикселях" name="tab_width_'.(isset($par['id'])?$par['id']:'').'"  style="width:100px;" >
&nbsp;-->
<b>'.tpl::_d($par['name'],'Таблица').'</b>
<input  onkeydown="need_Save();" type="text" name="tab_rows_'.(isset($par['id'])?$par['id']:'').'"  style="width:30px;" title="строки">
x
<input  onkeydown="need_Save();" type="text" style="width:30px;" name="tab_colls_'.(isset($par['id'])?$par['id']:'').'" title="столбцы">
</nobr>
</td>

'.tpl::_a($par['vstavka'],array('tpl_main','table_edit_line_vstavka')).'

'.tpl::_a($par['img_options'],array('tpl_main','table_edit_line_img_options')).'

<td class="align_center" style="padding:0 2px;">
<input type="text" name="item_align_'.(isset($par['id'])?$par['id']:'').'" class="align hidden"></td>
<th class="nopage align_center" style="padding:0 2px;" nowrap>
'.tpl::_ax(tpl::_export('','tpl','admin','order_elm_start'),array('tpl_main','table_edit_line___tpl_admin_order_elm_start')).'
<input type="text" class="order size11" onKeyDown="need_Save()" style="width:15px;" name="item_order_'.(isset($par['id'])?$par['id']:'').'">
'.tpl::_ax(tpl::_export('','tpl','admin','order_elm_fin'),array('tpl_main','table_edit_line___tpl_admin_order_elm_fin')).'
</th>
<th class="align_center" style="padding:0 2px;">'.tpl::_ax(tpl::_export('','tpl','admin','delrec_elm'),array('tpl_main','table_edit_line___tpl_admin_delrec_elm')).'
</th>
</tr>

<tr class="bwhite"  style="display:none;">
<td class="bwhite" colspan=2></td>
<td colspan="6"><div style="height:100%;width:100%;overflow-y:visible; overflow-x:auto;">'.(isset($par['thetable'])?$par['thetable']:'').'</div>
</td></tr></table>
</td>
</tr>';
}

function table_edit_line_vstavka(&$par){		
		return '<td></td>
<td title="Сюда можно вставить скопированную таблицу." class="nocontext context" colspan="2" style="width:222px;"><textarea class="clipboard" style="width:210px;height:20px;overflow:hidden;"
onchange="tabLook(this);" onpaste="tabLook(this);"></textarea>
</td>';
}

function table_edit_line_img_options(&$par){		
		return '<td   id="img_s_'.(isset($par['id'])?$par['id']:'').'" style="width:170px;border-right:none;padding-left:0;padding-right:0;margin:0;" title="Размер мал. фото (px)"><nobr><span style="margin-left:10px;"><b>Фото:</b> &nbsp; Мал.</span>  <input name="w_s_'.(isset($par['id'])?$par['id']:'').'" type="text" value=""  onkeydown="need_Save()" style="width:35px;"> </nobr>
<nobr>x  <input name="h_s_'.(isset($par['id'])?$par['id']:'').'" type="text" value=""  onkeydown="need_Save()" style="width:35px;"> </nobr></td>
<td   id="img_b_'.(isset($par['id'])?$par['id']:'').'" style="width:125px;border-right:none;padding-left:0;padding-right:0;margin:0;" title="Размер бол. фото (px)"><nobr><span style="margin-left:10px;">Бол.</span>  <input name="w_b_'.(isset($par['id'])?$par['id']:'').'" type="text" value=""  onkeydown="need_Save()" style="width:35px;"> </nobr>
<nobr>x  <input name="h_b_'.(isset($par['id'])?$par['id']:'').'" type="text" value=""  onkeydown="need_Save()" style="width:35px;"> </nobr></td>
<td title="Рамка"> <input name="border_'.(isset($par['id'])?$par['id']:'').'" type="text" value=""  class="win_check"></td>';
}

function edit_table(&$par){		
		return '<table border=1 style="'.tpl::_b($par['notfixed'],'','').'" class="table tahoma size11">
'.tpl::_b($par['notfixed'],'','').'
<!--<col width="auto">'.(isset($par['cols'])?$par['cols']:'').'-->
'.tpl::_b($par['notfixed'],'','').'
<tr class="odd"><th ></th>'.(isset($par['inputs'])?$par['inputs']:'').'<th  colspan=2></th></tr>
<tr class="even"><th >№</th>'.(isset($par['header'])?$par['header']:'').'<th  colspan=2></th></tr>
'.tpl::_a($par['rows'],array('tpl_main','edit_table_rows')).'
</table>';
}

function edit_table_rows(&$par){		
		return '<tr id="pg_'.(isset($par['id'])?$par['id']:'').'" title="1" class="vnutr context '.(isset($par['class'])?$par['class']:'').'"><th>'.(isset($par['number'])?$par['number']:'').'</th>'.(isset($par['row'])?$par['row']:'').'
<th class="bblue align_center" style="width:50px;padding:0 2px;" nowrap>
<table class="compact table bblue"><tr><th class="nopage"><input type="button" class="win_max p120"
 onclick="order(this,\'+\');"
></th>
<th class="nopage">
<input type="text" onKeyDown="need_Save()" class="order size11" style="width:15px;" name="item_order_'.(isset($par['id'])?$par['id']:'').'">
</th><th class="nopage">
<input type="button" class="win_max p105"
 onclick="order(this,\'-\');"
>
</th></tr></table>
</th>
<th style="width:25px;padding:0 2px;">'.tpl::_ax(tpl::_export('','tpl','admin','delrec_elm'),array('tpl_main','edit_table_rows___tpl_admin_delrec_elm')).'
</th></tr>';
}

function edit_row(&$par){		
		return tpl::_a($par['cols'],array('tpl_main','edit_row_cols'));
}

function edit_row_cols(&$par){		
		return '<'.tpl::_d($par['td'],'td').tpl::_format(pps($par['colspan']),' colspan="%s"').tpl::_format(pps($par['rowspan']),' rowspan="%s"').' class="context '.(isset($par['class_r'])?$par['class_r']:'').'" id="pg_'.(isset($par['id'])?$par['id']:'').'">'.tpl::_x15(pps($par['text'])).'</'.tpl::_d($par['td'],'td').'>';
}

function row_edit_line(&$par){		
		return '<tr class="bwhite" id="pg_'.(isset($par['id'])?$par['id']:'').'">
<td></td>
<td class="text_edit" id="item_name_'.(isset($par['id'])?$par['id']:'').'">'.(isset($par['name'])?$par['name']:'').'</td>
<td class="align_left">
<input type="submit"
			title="добавить новую ссылку" class="win_max"
			style="float:right;background-position: 0 -90px;" name="new_row_add_'.(isset($par['id'])?$par['id']:'').'" value="&nbsp;">
			</td><td>
<input type="text" name="item_align_'.(isset($par['id'])?$par['id']:'').'" class="align hidden"></td>
<td class="align_center" style="padding:0 2px;" nowrap>
'.tpl::_ax(tpl::_export('','tpl','admin','order_elm_start'),array('tpl_main','row_edit_line___tpl_admin_order_elm_start')).'
<input type="text" onKeyDown="need_Save()" class="order size11" style="width:15px;" name="item_order_'.(isset($par['id'])?$par['id']:'').'">
'.tpl::_ax(tpl::_export('','tpl','admin','order_elm_fin'),array('tpl_main','row_edit_line___tpl_admin_order_elm_fin')).'
</td>
<td style="padding:0 2px;">'.tpl::_ax(tpl::_export('','tpl','admin','delrec_elm'),array('tpl_main','row_edit_line___tpl_admin_delrec_elm')).'
</td>
</tr>';
}

function piconly_edit_line(&$par){		
		return '<tr id="pg_'.(isset($par['id'])?$par['id']:'').'">
<td style="padding:0;">
<table class="fixed tahoma ctext size11">
<col width="130px"><col width="50px"><col width="auto"><col width="auto">
<tr>
<td rowspan=3 width="130px">
<img alt="" onload="checkImg(this,120,100)" title="просмотр маленькой картинки" alt="просмотр маленькой картинки" src="../'.(isset($par['pic_small'])?$par['pic_small']:'').'">
</td><td align="right">мал.</td>
<td  class="uploader">
<input style="overflow:hidden;width:100%" title="вставьте маленькую картинку" type="text" name="pic_small_'.(isset($par['id'])?$par['id']:'').'"></td>
<td width="100px">'.(isset($par['swidth'])?$par['swidth']:'').'x'.(isset($par['sheight'])?$par['sheight']:'').'</td>
</tr><tr>
<td align="right">бол.</td>
<td class="uploader">
<input  style="overflow:hidden;width:100%;" title="вставьте большую картинку" type="text" name="pic_big_'.(isset($par['id'])?$par['id']:'').'"></td>
<td width="100px">'.(isset($par['bwidth'])?$par['bwidth']:'').'x'.(isset($par['bheight'])?$par['bheight']:'').'</td>
</tr><tr>
<td align="right">Опис.</td>
<td  >
<input style="overflow:hidden;width:100%;" type="text" onKeyDown="need_Save()" title="название картинки" name="pic_comment_'.(isset($par['id'])?$par['id']:'').'"></td>
<td>
<input class="nocontext long link_toolbox" title="Ссылка" type="text" name="item_url_'.(isset($par['id'])?$par['id']:'').'"></td>
</tr></table></td>
<th class="nopage align_center" style="padding:0 2px;" nowrap>
'.tpl::_ax(tpl::_export('','tpl','admin','order_elm_start'),array('tpl_main','piconly_edit_line___tpl_admin_order_elm_start')).'
<input type="text" class="order size11" onKeyDown="need_Save()" style="width:15px;" name="item_order_'.(isset($par['id'])?$par['id']:'').'">
'.tpl::_ax(tpl::_export('','tpl','admin','order_elm_fin'),array('tpl_main','piconly_edit_line___tpl_admin_order_elm_fin')).'
</th>
<th class="align_center" style="padding:0 2px;">'.tpl::_ax(tpl::_export('','tpl','admin','delrec_elm'),array('tpl_main','piconly_edit_line___tpl_admin_delrec_elm')).'
</th>
</tr>';
}

function menu_edit_line(&$par){		
		return '<tr id="id_'.(isset($par['id'])?$par['id']:'').'"><td class="text_edit" id="name_'.(isset($par['id'])?$par['id']:'').'">
'.(isset($par['name'])?$par['name']:'').'
</td><td class="text_edit" id="url_'.(isset($par['id'])?$par['id']:'').'">'.(isset($par['url'])?$par['url']:'').'
</td>
<td class="text_edit" id="descr_'.(isset($par['id'])?$par['id']:'').'">'.(isset($par['descr'])?$par['descr']:'').'</td>
<td ><input type="text" class="check_0_15" name="type_'.(isset($par['id'])?$par['id']:'').'" style="width:20px">
</td>
<td class="align_center win_order" style="padding:0 2px;" nowrap>
<input type="text" class="order_0" name="order_'.(isset($par['id'])?$par['id']:'').'">
</td>
<td style="padding:0 2px;">'.tpl::_ax(tpl::_export('','tpl','admin','delrec_elm'),array('tpl_main','menu_edit_line___tpl_admin_delrec_elm')).'
</td>
</tr>';
}

function article_edit(&$par){		
		return '<tr >
		<td style="padding:2px 0px;border-collapse:collapse;"
			class="bblue align_left"><table
			class="long wide blue table tahoma size11">
	<tr>
		<td class="align_middle" style="width:auto;" width="auto"><div style="width:300px;">
		<input type="text" class="tahoma fills size11" style="width:100px;" title="Название раздела в адресной строке" 
	alt="Название раздела в адресной строке" onChange="need_Save()" name="alt_name_'.(isset($par['id'])?$par['id']:'').'"> 
		
		</div></td>
		<th class="bblue" style="padding:0;" width="250" style="width:250px;padding:0;">
			<table class="tahoma long" style="font-size:10px;height:12px;"><tr id="pg_'.(isset($par['id'])?$par['id']:'').'">
			<td width="30%" style="padding:0px;height:12px;overflow:hidden;" class="edited html_edit" title="TITLE (заголовок на синей полосе)"
				 id="article_title_'.(isset($par['id'])?$par['id']:'').'"><div style="padding:0px;height:12px;overflow:hidden;">'.tpl::_x15(pps($par['article_title'])).'</div></td>
			<td width="30%" style="padding:0px;height:12px;overflow:hidden;" title="Поле DESCRIPTION (описание раздела)" class="edited html_edit" id="article_descr_'.(isset($par['id'])?$par['id']:'').'"><div style="padding:0px;height:12px;overflow:hidden;">'.tpl::_x15(pps($par['article_descr'])).'</div></td>
			<td width="30%" style="padding:0px;height:12px;overflow:hidden;" class="edited html_edit" title="Значения KEYWORDS (слово, слово, слово, ...)" id="article_keywords_'.(isset($par['id'])?$par['id']:'').'"><div style="padding:0px;height:12px;overflow:hidden;">'.tpl::_x15(pps($par['article_keywords'])).'</div></td>
			</tr>
			</table>
		</th>
	</tr>
	<tr>
		<td colspan="2" style="height:30px;vertical-align:bottom;color:#000000;background:url(img/fon_vk.gif) repeat-x center center;padding:0px 4px;border-top:4px solid #FFFFFF;">
			<input type="hidden" name="new_item_type" id="new_item_type" value=""/>
			<input type="hidden" name="new_item_add" value="Добавить"/>
			<table class="verh_kn">
				<tr>
					<td><img src="img/zagol.gif" onMouseOver="this.src=\'img/zagol_a.gif\'" onMouseOut="this.src=\'img/zagol.gif\'" onMouseDown="this.src=\'img/zagol_n.gif\'" onMouseUp="this.src=\'img/zagol_a.gif\'" onClick="add_elm(20)"/></td>
					<td><img src="img/text.gif" onMouseOver="this.src=\'img/text_a.gif\'" onMouseOut="this.src=\'img/text.gif\'" onMouseDown="this.src=\'img/text_n.gif\'" onMouseUp="this.src=\'img/text_a.gif\'" onClick="add_elm(16)"/></td>
					<td><img src="img/gallery.gif" onMouseOver="this.src=\'img/gallery_a.gif\'" onMouseOut="this.src=\'img/gallery.gif\'" onMouseDown="this.src=\'img/gallery_n.gif\'" onMouseUp="this.src=\'img/gallery_a.gif\'" onClick="add_elm(7)"/></td>
					<td><img src="img/gallery2.gif" onMouseOver="this.src=\'img/gallery2_a.gif\'" onMouseOut="this.src=\'img/gallery2.gif\'" onMouseDown="this.src=\'img/gallery2_n.gif\'" onMouseUp="this.src=\'img/gallery2_a.gif\'" onClick="add_elm(26)"/></td>
					<td><img src="img/links.gif" onMouseOver="this.src=\'img/links_a.gif\'" onMouseOut="this.src=\'img/links.gif\'" onMouseDown="this.src=\'img/links_n.gif\'" onMouseUp="this.src=\'img/links_a.gif\'" onClick="add_elm(3)"/></td>
					<td><img src="img/table.gif" onMouseOver="this.src=\'img/table_a.gif\'" onMouseOut="this.src=\'img/table.gif\'" onMouseDown="this.src=\'img/table_n.gif\'" onMouseUp="this.src=\'img/table_a.gif\'" onClick="add_elm(6)"/></td>
					<td><img src="img/line.gif" onMouseOver="this.src=\'img/line_a.gif\'" onMouseOut="this.src=\'img/line.gif\'" onMouseDown="this.src=\'img/line_n.gif\'" onMouseUp="this.src=\'img/line_a.gif\'" onClick="add_elm(27)"/></td>
					<td><img src="img/col.gif" onMouseOver="this.src=\'img/col_a.gif\'" onMouseOut="this.src=\'img/col.gif\'" onMouseDown="this.src=\'img/col_n.gif\'" onMouseUp="this.src=\'img/col_a.gif\'" onClick="add_elm(19)"/></td>
					<td><img src="img/price.gif" onMouseOver="this.src=\'img/price_a.gif\'" onMouseOut="this.src=\'img/price.gif\'" onMouseDown="this.src=\'img/price_n.gif\'" onMouseUp="this.src=\'img/price_a.gif\'" onClick="add_elm(28)"/></td>
					<td><img src="img/csv.gif" onMouseOver="this.src=\'img/csv_a.gif\'" onMouseOut="this.src=\'img/csv.gif\'" onMouseDown="this.src=\'img/csv_n.gif\'" onMouseUp="this.src=\'img/csv_a.gif\'" onClick="add_elm(8)"/></td>
					<td><img src="img/kom.gif" onMouseOver="this.src=\'img/kom_a.gif\'" onMouseOut="this.src=\'img/kom.gif\'" onMouseDown="this.src=\'img/kom_n.gif\'" onMouseUp="this.src=\'img/kom_a.gif\'" onClick="add_elm(29)"/></td>
					<td><img src="img/save_d.gif" onMouseOver="save_over()" onMouseOut="save_out()" onClick="save_act()" onMouseDown="save_doun()" onMouseUp="save_up()" id="save_btn"/></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2" style="height:30px;vertical-align:bottom;color:#000000;"><span><input class="win_check" id="dostup_'.(isset($par['id'])?$par['id']:'').'" name="dostup_'.(isset($par['id'])?$par['id']:'').'" type="text" value="'.(isset($par['dostup'])?$par['dostup']:'').'" style="display:none;"></span> Только для зарегистрированных пользователей</td>
	</tr>
</table>
</td></tr>
	<tr>
		<td style="background: white; height: 10px;"></td>
	</tr>
	<tr><th  style="padding:0;background: white;">
	 <table class="fixed size11">
<col width="23px">
<col width="90px"><col width="auto">
<col width="50px">
<col width="23px">	 <tr>
		<th style="padding: 0 2px"></th>
		<th title="Не отображается на сайте" >Элемент</th>
		<th >Содержимое</th>
		<th >Сорт.</th><th></th>
		</tr></table></th>
	</tr>'.(isset($par['data'])?$par['data']:'').'
<tr><td  style="background:white; height:10px;">
</td></tr>';
}

function menu_edit_list(&$par){		
		return (isset($par['data'])?$par['data']:'');
}

function menu_edit_addnew(&$par){		
		return '<tr><td style="background:white; height:10px;">
</td></tr>
<tr><td>
	<input type="text" class="tahoma fills size11" title="Новый пункт меню" name="new_line">
	</td><td>
	<input type="text" class="link_toolbox tahoma fills size11" title="Адрес перехода или имя плагина" name="new_url">
	</td><td>
	<select class="tahoma fills size11" name="new_line_type"><option value="0">страница</option>
	<option value="1">ссылка</option>
	<option value="2">копия</option>
	<option value="3">плагин</option>
	</select>
	</td><td align="right"  colspan=3>
		<input type="submit" class="win_max p90" value="&nbsp;">

</td></tr>';
}

function sitemap(&$par){		
		return '<div style="padding:3px 0">
<a class="tahoma ctext menu blue"  href="?do=menu&id='.(isset($par['id'])?$par['id']:'').'">'.tpl::_d($par['menu'],'Главное меню').'</a>
<ul class="menu ctext tahoma size11">
'.(isset($par['data'])?$par['data']:'').'</ul></div>';
}

function ermess(&$par){		
		return '<div style="padding-top:60px;" class=\'red\'>
<p><b>Страница, которую вы запросили, отсутствует на сайте</b></p>
</div>';
}

function __menu_head(&$par){		
		return tpl::_d($par['first'],'&nbsp;/&nbsp;&nbsp;').'<a class="'.(isset($par['current'])?$par['current']:'').'" href="'.tpl::_ax(tpl::_export('','curl','do','id'),array('tpl_main','__menu_head___curl_do_id')).'do='.tpl::_d($par['menupage'],'menu').'&id='.(isset($par['id'])?$par['id']:'').'">'.(isset($par['name'])?$par['name']:'').'</a>';
}

function __pluginlist(&$par){		
		return '<span class="tahoma ctext menu blue">Список модулей</span>
<ul class="menu ctext tahoma size11">
'.tpl::_a($par['param'],array('tpl_main','__pluginlist_param')).'
'.tpl::_a($par['list'],array('tpl_main','__pluginlist_list')).'
</ul>';
}

function __pluginlist_param(&$par){		
		return '<li><a  href="?do=siteparam">Параметры</a></li>';
}

function __pluginlist_list(&$par){		
		return '<li><a  href="?do=menu&id='.(isset($par['plugin'])?$par['plugin']:'').'">'.tpl::_d($par['name'],'---').'</a></li>';
}

function qa__getOptions(&$par){		
		return '<a class="long" style="margin:2px;display:block;" href="#'.(isset($par['id'])?$par['id']:'').'">'.(isset($par['name'])?$par['name']:'').'</a>';
}

function dd_menu(&$par){		
		return '<div class="tahoma cltext size11" style="z-index:10;top:0px;left:0;padding:10px;border: 1px gray solid; background:white;position:absolute;top:1em;" id="'.(isset($par['name'])?$par['name']:'').'">
'.tpl::_a($par['list'],array('tpl_main','dd_menu_list')).'
</div>';
}

function dd_menu_list(&$par){		
		return '<a class="long" style="margin:2px;display:block;" href="#'.(isset($par['id'])?$par['id']:'').'">'.(isset($par['text'])?$par['text']:'').'</a>';
}

function katalog_back(&$par){		
		return '<p><a href="'.(isset($par['raddr'])?$par['raddr']:'').'">&lt;&lt; Назад</a></p>';
}

function katalog_back_bt(&$par){		
		return '<p><a href="'.(isset($par['raddr'])?$par['raddr']:'').'">&lt;&lt; Назад</a></p>';
}

function ajax(&$par){		
		return tpl::_a($par['data'],array('tpl_main','ajax_data'));
}

function ajax_data(&$par){		
		return (isset($par['result'])?$par['result']:'').(isset($par['tval'])?$par['tval']:'');
}}
?>