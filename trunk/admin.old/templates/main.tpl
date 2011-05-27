<!DOCTYPE html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>{title|Администрирование сайта}</title>
<meta http-equiv="content-type"
	content="text/html; charset=windows-1251">
<META name="description" content="{desc_words}">
<META name="keywords" lang="ru" content="{key_words}">

<script type="text/javascript" src="../js/jquery-1.4.4.js"> </script>
<script type="text/javascript" src="js/engine.pack.js"> </script>
<script type="text/javascript" src="js/nicedit.full.js"></script>

<LINK  rel="stylesheet" type="text/css" href="css/admin.css">

<link rel="icon" href="{::index}/favicon.ico" type="image/x-icon">
<link rel="shortcut icon" href="{::index}/favicon.ico" >
<style type="text/css">
html, body {overflow:hidden;}
</style>
<script type='text/javascript' src="js/main.js">
</script>
</head>
<body>
<!--begin:katalog_searchres-->
<a href="?do=katalog&item={id}">
<span class="red">{articul}</span>
&nbsp;&nbsp;{descr}
</a>
<!--end:katalog_searchres-->
<!--begin:searchres-->
<span class="blue">{id} Строка поиска: `{searchstr}`</span>
{pages}
<!--begin:list-->
<div class="tahoma blue">{page}</div>
<!--begin:items-->
<div style="padding: 0 0px 0 50px;" class="tahoma size11">{item}</div>
<!--end:items-->
<!--end:list-->
{pages}
<!--end:searchres-->

<!--begin:pages-->
<table class="link tahoma ctext"><tr>
<!--begin:mmin-->
<td style="padding:5px;">
	<a href="{::curl:pg}pg=0">&lt;&lt;</a>
</td>
<!--end:mmin-->
<!--begin:min-->
<td style="padding:5px;">
	<a href="{::curl:pg}pg={m5}">&lt;</a>
</td>
<!--end:min-->
<!--begin:page-->
<td style="padding:5px;">
<!--begin:link-->
	<a href="{::curl:pg}pg={url}">{txt}</a>
<!--end:link-->
<!--begin:txt-->
	<span style="line-height:21px;" class="red {current?current}">{txt}</span>
<!--end:txt-->
</td>
<!--end:page-->
<!--begin:max-->
<td style="padding:5px;">
	<a href="{::curl:pg}pg={m5}">&gt;</a>
</td>
<!--end:max-->
<!--begin:mmax-->
<td style="padding:5px;">
	<a href="{::curl:pg}pg={m5}">&gt;&gt;</a>
</td>
<!--end:mmax-->
</tr></table>
<!--end:pages-->
<table>
<!--begin:text_edit_line-->
<tr id="pg_{id}">
<td class="text_edit" id="item_name_{id}" title="тип:text">{name|текст}</td>
<td colspan=3 class="html_edit" id="item_text_{id}">{text_breath}</td>
<td class="align_center" style="padding:0 2px;" nowrap>
{::tpl:admin:order_elm_start}
<input type="text" onKeyDown="need_Save()" class="order size11" style="width:15px;" name="item_order_{id}">
{::tpl:admin:order_elm_fin}
</td>
<td style="padding:0 2px;">{::tpl:admin:delrec_elm}
</td>
</tr>
<!--end:text_edit_line-->
<!--begin:header_edit_line-->
<tr class="{trclass|even}" id="pg_{id}">
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
<td class="text_edit" id="item_name_{id}" title="тип:Заголовок">{name|заголовок}</td>

<td class="html_edit" id="item_text_{id}" title="Текст заголовка">
{text}
</td>
<td  class="align_center" style="padding:0 2px;">
<input type="text" name="item_align_{id}" class="align hidden"></td>
<th class="nopage align_center" style="padding:0 2px;" nowrap>
{::tpl:admin:order_elm_start}
<input type="text" class="order size11" onKeyDown="need_Save()" style="width:15px;" name="item_order_{id}">
{::tpl:admin:order_elm_fin}
</th>
<th class="align_center" style="padding:0 2px;">{::tpl:admin:delrec_elm}
</th>
</tr></table></td></tr>
<!--end:header_edit_line-->

<!--begin:column_line-->
<tr id="pg_{id}">
<td >
<input type="button" class="button green" value="Ред.колонку" onClick="window.location.replace('{::curl:do:id}do=page&id={id}')"></td>
<td class="text_edit" id="item_text_{id}"  title="Имя колонки">{item_text}</td>
<td class="text_edit" id="item_width_{id}"  title="ширина колонки в пикс.">{item_width}</td>
<th class="nopage align_center" style="padding:0 2px;" nowrap>
{::tpl:admin:order_elm_start}
<input type="text" class="order size11" onKeyDown="need_Save()" style="width:15px;" name="item_order_{id}">
{::tpl:admin:order_elm_fin}
</th>
<th class="align_center" style="padding:0 2px;">{::tpl:admin:delrec_elm}
</th></tr>
<!--end:column_line-->
<!--begin:article_line-->
<tr id="pg_{id}">
<td >
<input type="button" class="button green" value="Ред.Описание" onClick="window.location.replace('{::curl:do:id}do=page&id={id}')"></td>
<td class="text_edit" id="item_text_{id}"  title="Название ссылки">{item_text}</td>
<th class="nopage align_center" style="padding:0 2px;" nowrap>
{::tpl:admin:order_elm_start}
<input type="text" class="order size11" onKeyDown="need_Save()" style="width:15px;" name="item_order_{id}">
{::tpl:admin:order_elm_fin}
</th>
<th class="align_center" style="padding:0 2px;">{::tpl:admin:delrec_elm}
</th></tr>
<!--end:article_line-->

<!--begin:article_list-->
<tr class=" context {trclass|even}" id="pg_{id}">
<td class="bwhite" style="padding:0;">
<table class="tahoma ctext size11 long fixed">
<col width="23px">
<col width="90px">
<col width="auto">
<col width="125px">
<col width="23px">
<col width="50px">
<col width="23px"><tr>

<th class="nopage align_center" style="padding:0 2px;">{::tpl:admin:win_elm2}</th>
<td class="text_edit" id="item_name_{id}" title="тип:Заголовок">{name|Список статей}</td>

<td class="html_edit" id="item_text_{id}" title="Текст заголовка">
{text}
</td>
<td>
<input type="submit" name="new_article_{id}"
title="добавить новую статью" class="button green" value="Добавить">
</td><td  class="align_center" style="padding:0 2px;">
<input type="text" name="item_align_{id}" class="align hidden"></td>
<th class="nopage align_center" style="padding:0 2px;" nowrap>
{::tpl:admin:order_elm_start}
<input type="text" class="order size11" onKeyDown="need_Save()" style="width:15px;" name="item_order_{id}">
{::tpl:admin:order_elm_fin}
</th>
<th class="align_center" style="padding:0 2px;">{::tpl:admin:delrec_elm}
</th>
</tr>
<tr class="bwhite" style="display:none;">
<td colspan=2 ></td>
<td colspan=3 style="padding:0;">
<table class="tahoma ctext size11 fixed">
<col width="125px"><col width="auto"><col width="auto"><col width="50px"><col width="25px">
{links}</table>
</td>
<td colspan=2 ></td>
</tr></table></td></tr>
<!--end:article_list-->

<!--begin:plugin_edit_line-->
<tr class="bwhite context {trclass|even}" id="pg_{id}">
<td style="padding:0;">
<table class="tahoma ctext size11 long fixed">
<col width="23px">
<col width="90px">
<col width="auto">
<col width="auto">
<col width="25px"><col width="50px">
<col width="23px"><tr>

<th class="nopage align_center" style="padding:0 2px;"></th>
<td class="text_edit" id="item_name_{id}" title="тип:text">{name|модуль}</td>
<td class="text_edit" id="item_text_{id}" title="имя модуля">
{text}
</td>
<td class="text_edit" id="item_url_{id}" title="имя функции">
{url}
</td>
<td></td>
<th class="nopage align_center" style="padding:0 2px;" nowrap>
{::tpl:admin:order_elm_start}
<input type="text" class="order size11" onKeyDown="need_Save()" style="width:15px;" name="item_order_{id}">
{::tpl:admin:order_elm_fin}
</th>
<th class="align_center" style="padding:0 2px;">{::tpl:admin:delrec_elm}
</th>
</tr></table></td></tr>
<!--end:plugin_edit_line-->

<!--begin:katalogx_edit_line-->
<tr class="bwhite {trclass|even}" id="pg_{id}">
<td style="padding:0;">
<table class="tahoma ctext size11 long fixed">
<col width="23px">
<col width="90px">
<col width="auto">
<col width="125px">
<col width="125px"><col width="25px"><col width="50px">
<col width="23px"><tr>

<th class="nopage align_center" style="padding:0 2px;">
<input name="item_clear_{id}" onClick="document.location='{::curl:do:cat}do=cat&cat={id}'" type="button" class="win_max" value="&nbsp;"></th>
<td class="text_edit" id="article_{id}" title="код раздела для CSV">{name|каталог}</td>
<td title="Тип раздела каталога">
<div class="wide long xmenu">
<input class="long" type="text" name="cat_type_{id}" value="{align|0}">
</div></td>
<td><div class="uploader action_both">
	<input type="button" class="button green"
		 value="Общее Фото" onClick="ReplaceImg(this);"></div>
		<input type="text" style="display:none;"
			name="pic_small_{id}">
		<input type="text" style="display:none;"
			name="pic_big_{id}">
</td>
<td><div class="uploader" id="item_text_{id}">
	<input onClick="loadCSV(this)" type="text" style="display:none;" autocomplete="off"
			name="item_csv_{id}">	<input type="button" class="button green"
		 value="Экспорт CSV">
	 </div>
		 </td>
<td></td>
<th class="nopage align_center" style="padding:0 2px;" nowrap>
{::tpl:admin:order_elm_start}
<input type="text" class="order size11" onKeyDown="need_Save()" style="width:15px;" name="item_order_{id}">
{::tpl:admin:order_elm_fin}
</th>
<th class="align_center" style="padding:0 2px;">{::tpl:admin:delrec_elm}
</th>
</tr></table></td></tr>
<!--end:katalogx_edit_line-->


<!--begin:katalog_edit_line-->
<tr class="bwhite {trclass|even}" id="pg_{id}">
<td style="padding:0;">
<table class="tahoma ctext size11 long fixed">
<col width="23px">
<col width="90px">
<col width="auto">
<col width="125px">
<col width="125px"><col width="25px"><col width="50px">
<col width="23px"><tr>

<th class="nopage align_center" style="padding:0 2px;">
<input name="item_clear_{id}" onClick="document.location='{::curl:do:cat}do=cat&cat={id}'" type="button" class="win_max" value="&nbsp;"></th>
<td class="text_edit"  id="article_{id}"  title="код раздела для CSV">{name|каталог}</td>
<td></td>
<td><div class="uploader action_both">
	<input type="button" class="button green"
		 value="Общее Фото" onClick="ReplaceImg(this);"></div>
		<input type="text" style="display:none;"
			name="pic_small_{id}">
		<input type="text" style="display:none;"
			name="pic_big_{id}">
</td>
<td><div class="uploader" id="item_text_{id}">
	<input onClick="loadCSV(this)" type="text" style="display:none;" autocomplete="off"
			name="item_csv_{id}">	<input type="button" class="button green"
		 value="Экспорт CSV">
	 </div>
		 </td>
<td></td>
<th class="nopage align_center" style="padding:0 2px;" nowrap>
{::tpl:admin:order_elm_start}
<input type="text" class="order size11" onKeyDown="need_Save()" style="width:15px;" name="item_order_{id}">
{::tpl:admin:order_elm_fin}
</th>
<th class="align_center" style="padding:0 2px;">{::tpl:admin:delrec_elm}
</th>
</tr></table></td></tr>
<!--end:katalog_edit_line-->
<!--begin:common_line-->
<tr class="context {trclass|even}" id="pg_{id}">
<td class="bwhite" style="padding:0;">
<table class="tahoma ctext size11 long fixed">
<col width="23px">
<col width="90px">
<!--begin:cols-->
<col width="{width}">
<!--end:cols-->
<col width="50px">
<col width="23px"><tr>
{minmax??<th></th>::
<th class="nopage align_center" style="padding:0 2px;"><div class="win_max closed  open_close">&nbsp;</div></th>}
<td class="text_edit" id="name_{id}" title="{type}">{item_name}</td>
<!--begin:fields-->
<!--begin:txt-->
<td class="text_edit" id="{name}_{id}" title="{title}">{text}</td>
<!--end:txt-->
<!--begin:html-->
<td id="{name}_{id}" class="html_edit" >
{text_breath}
</td>
<!--end:html-->
<!--begin:csv-->
<td><div class="uploader nocontext" id="{name}_{id}">
	<input onClick="loadCSV(this)" type="text" style="display:none;" autocomplete="off"
			name="{name}_{id}">	<input type="button" class="nocontext button green"
		 value="{text}">
</div></td>
<!--end:csv-->
<!--begin:checkbox-->
<td title="{title}" >{text}<input class="win_check" type="text" name="{name}_{id}">
</td>
<!--end:checkbox-->
<!--begin:button0-->
<td><input type="submit" name="goto_{name}_{id}"
title="{title}" class="button green" value="{text}">
</td>
<!--end:button0-->
<!--begin:button-->
<td>
<input type="submit" name="new_{name}_{id}"  onmousedown="window.el_open(this); return false;"
title="{title}" class="button green" value="{text}">
</td>
<!--end:button-->
<!--begin:link-->
<td title="{title}" style="padding:2px 20px 2px 0;"><div style="width:100%;" ><nobr>
<div class="uploader" style="background-image:url(img/upload.gif);float:left;width:20px;height:20px;" >&nbsp;</div>
<input type="text" onKeyDown="need_Save()" class="nocontext long link_toolbox" name="{name}_{id}"></nobr></div>
</td>
<!--end:link-->
<!--begin:img-->
<td><div class="uploader action_both">
<input type="button" class="button green" value="{text}"
onclick="NewGalleryImg(this,false);" ></div>
</td><!--end:img-->
<!--begin:align-->
<td class="align_center" style="padding:0 2px;">
<input type="text" name="item_align_{id}" class="align hidden"></td>
<!--end:align-->
<!--begin:smallinput-->
<td   id="item_name_{id}"  style="overflow:visible;"><nobr><b>{title}</b> <input type="text" onKeyDown="need_Save()" style="width:20px;" name="{name}_{id}"></nobr></td>
<!--end:smallinput-->
<!--begin:stolb-->
<td   id="item_name_{id}" style="width:90px;padding-right:0;padding-left:0px;margin:0;" title="Кол-во столбцов"><nobr><span style="margin-left:10px;">{title}</span> <input type="text" onKeyDown="need_Save()" style="width:20px;" name="{name}_{id}"></nobr></td>
<!--end:stolb-->
<!--begin:mediuminput-->
<td   id="item_name_{id}"  ><nobr><b>{title}</b> <input type="text" onKeyDown="need_Save()" style="width:40px;" name="{name}_{id}"></nobr></td>
<!--end:mediuminput-->
<!--begin:razmer1-->
<td   id="item_name_{id}" style="width:125px;border-right:none;padding-left:0;padding-right:0;margin:0;" title="{vp}"><nobr><span style="margin-left:10px;">{title}</span> <input type="text" onKeyDown="need_Save()" style="width:35px;" name="{name}_{id}"></nobr>
<!--end:razmer1-->
<!--begin:razmer2-->
<nobr>{title} <input type="text" onKeyDown="need_Save()" style="width:35px;" name="{name}_{id}"></nobr></td><td style="width:0;padding:0;"></td>
<!--end:razmer2-->
<!--begin:dropdown-->
<td><div class="nocontext wide long {xname}" title="{title}">
<input class="long" type="text" name="{name}_{id}">
</div></td>
<!--end:dropdown-->
<!--end:fields-->

<th class="nopage align_center" style="padding:0 2px;" nowrap>
{::tpl:admin:order_elm_start}
<input type="text" class="order size11" onKeyDown="need_Save()" style="width:15px;" name="item_order_{id}">
{::tpl:admin:order_elm_fin}
</th>
<th class="align_center" style="padding:0 2px;">{lock1|}{::tpl:admin:delrec_elm}{lock2|}
</th>
</tr>
<!--begin:pictures-->
<tr class="bwhite" style="display:none;">
<td class="bwhite" colspan=2"></td>
<td colspan={colnum} style="padding:0;">
<table class="tahoma ctext size11 fixed">
<col width="auto">
<col width="50px">
<col width="25px">
{links}
</table>
</td><td class="bwhite"></td>
</tr>
<!--end:pictures-->
<!--begin:links-->
<tr  class="bwhite" style="display:none;">
<td class="bwhite" colspan=2 ></td>
<td colspan={colnum} style="padding:0;">
<table class="tahoma ctext size11 fixed">
<col width="auto"><col width="auto"><col width="50px"><col width="25px">
{links}</table>
</td><td class="bwhite"></td>
</tr>
<!--end:links-->
<!--begin:komment-->
<tr  class="bwhite" style="display:none;">
<td class="bwhite" colspan=2 ></td>
<td colspan={colnum} style="padding:0;">
<table class="tahoma ctext size11 fixed" style="background:#FFFFFF;">
<col width="auto"><col width="auto"><col width="auto"><col width="auto"><col width="50px"><col width="25px">
<tr id="pg_{id}">
<td title="Дата"><b>Дата</b></td>
<td title="Имя"><b>Имя</b></td>
<td title="Цитата"><b>Цитата</b></td>
<td title="Комментарий"><b>Комментарий</b></td>
<th class="nopage align_center" style="padding:0 2px;" nowrap></th>
<th class="align_center" style="padding:0 2px;">
</th></tr>
{links}</table>
</td><td class="bwhite"></td>
</tr>
<!--end:komment-->
<!--begin:article-->
<tr  class="bwhite" style="display:none;">
<td class="bwhite" colspan=2 ></td>
<td colspan={colnum} style="padding:0;">
<table class="tahoma ctext size11 fixed">
<col width="125px"><col width="auto"><col width="50px"><col width="25px">
{articles}</table>
</td><td class="bwhite"></td>
</tr>
<!--end:article-->
<!--begin:column-->
<tr class="bwhite" style="display:none;">
<td class="bwhite" colspan=2></td>
<td colspan={colnum} style="padding:0;">
<table class="tahoma ctext size11 fixed">
<col width="125px"><col width="auto"><col width="auto"><col width="50px"><col width="25px">
{articles}</table>
</td><td class="bwhite"></td>
</tr>
<!--end:column-->
</table></td></tr>
<!--end:common_line-->

<!--begin:gallery_edit_line-->
<tr class="bwhite context {trclass|even}" id="pg_{id}">
<td  style="padding:0;">
<table class="tahoma ctext size11 long fixed">
<col width="23px">
<col width="90px">
<col width="auto">
<col width="125px"><col width="25px"><col width="50px">
<col width="23px"><tr>
<th class="nopage align_center" style="padding:0 2px;">{::tpl:admin:win_elm2}</th>
<td class="text_edit" id="item_name_{id}" title="тип:Галерея">{name|Галерея}</td>
<td   id="item_name_{id}"  ><nobr><b>Количество столбцов:</b> <input type="text" onKeyDown="need_Save()" style="width:20px;" name="item_columns_{id}"></nobr></td>
<td><div class="uploader action_both">
<input type="button" class="button green" value="Доб. фото"
onclick="NewGalleryImg(this,false);" ></div>
</td>
<td class="align_center" style="padding:0 2px;">
<input type="text" name="item_align_{id}" class="align hidden">			</td>
<th class="nopage align_center" style="padding:0 2px;" nowrap>
{::tpl:admin:order_elm_start}
<input type="text" class="order size11" onKeyDown="need_Save()" style="width:15px;" name="item_order_{id}">
{::tpl:admin:order_elm_fin}
</th>
<th class="align_center" style="padding:0 2px;">{::tpl:admin:delrec_elm}
</th>
</tr>

<tr class="bwhite" style="display:none;">
<td colspan=2 ></td>
<td colspan=3 style="padding:0;">
<table class="tahoma ctext size11 fixed">
<col width="auto"><col width="50px"><col width="25px">
{links}
</table>
</td>
<td colspan=2 ></td>
</tr></table></td></tr>
<!--end:gallery_edit_line-->
<!--begin:links_edit_line-->
<tr class="bwhite context {trclass|odd}" id="pg_{id}">
<td style="padding:0;">
<table class="thetable tahoma ctext size11 long fixed">
<col width="23px">
<col width="90px">
<col width="auto"><col width="125px">
<col width="25px">
<col width="50px">
<col width="23px">
<tr>
<th class="nopage align_center" style="padding:0 2px;">{::tpl:admin:win_elm2}</th>
<td class="text_edit" id="item_name_{id}" title="тип:Ссылки и файлы">{name|ссылки и файлы}</td>
<td id="item_name_{id}" nowrap><nobr><b>Количество столбцов:</b> <input type="text" onKeyDown="need_Save()" style="width:20px;" name="item_columns_{id}"></nobr></td>
<td class="align_left" nowrap><input type="submit" onMouseDown="window.el_open(this); return false;"
			title="добавить новую ссылку" class="button green" name="new_link_add_{id}" value="Доб. ссылку">
		</td>
<td class="align_center "  style="padding:0 2px;"><input type="text" name="item_align_{id}" class="align hidden"></td>
<th class="nopage align_center" style="padding:0 2px;" nowrap>
{::tpl:admin:order_elm_start}
<input type="text" class="order size11" onKeyDown="need_Save()" style="width:15px;" name="item_order_{id}">
{::tpl:admin:order_elm_fin}
</th>
<th class="align_center" style="padding:0 2px;">{::tpl:admin:delrec_elm}
</th>
</tr>

<tr class="bwhite"  style="display:none;">
<td colspan=2></td>
<td colspan=3 style="padding:0;">
<table class="tahoma ctext size11 fixed">
<col width="auto"><col width="auto"><col width="50px"><col width="25px">
{links}</table>
</td>
<td colspan=2 ></td>
</tr></table></td></tr>
<!--end:links_edit_line-->

<!--begin:link_edit_line-->
<tr id="pg_{id}">
<td title="Вставьте ссылку или файл" style="padding:2px 20px 2px 0;"><div style="width:100%;" ><nobr>
<div class="uploader" style="background-image:url(img/upload.gif);float:left;width:20px;height:20px;" >&nbsp;</div>
<input type="text" onKeyDown="need_Save()" class="nocontext long link_toolbox" name="item_url_{id}"></nobr></div></td>
<td class="text_edit" id="item_text_{id}"  title="Описание ссылки или файла">{text}</td>
<th class="nopage align_center" style="padding:0 2px;" nowrap>
{::tpl:admin:order_elm_start}
<input type="text" class="order size11" onKeyDown="need_Save()" style="width:15px;" name="item_order_{id}">
{::tpl:admin:order_elm_fin}
</th>
<th class="align_center" style="padding:0 2px;">{::tpl:admin:delrec_elm}
</th></tr>
<!--end:link_edit_line-->

<!--begin:komment_edit_line-->
<tr id="pg_{id}">
<td class="text_edit" id="item_date_{id}"  title="Дата">{date}</td>
<td class="text_edit" id="item_username_{id}"  title="Имя">{username}</td>
<td class="text_edit" id="item_quote_{id}"  title="Цитата">{quote}</td>
<td class="text_edit" id="item_text_{id}"  title="Комментарий">{text}</td>
<th class="nopage align_center" style="padding:0 2px;" nowrap>
{::tpl:admin:order_elm_start}
<input type="text" class="order size11" onKeyDown="need_Save()" style="width:15px;" name="item_order_{id}">
{::tpl:admin:order_elm_fin}
</th>
<th class="align_center" style="padding:0 2px;">{::tpl:admin:delrec_elm}
</th></tr>
<!--end:komment_edit_line-->

<!--begin:table_edit_line-->
<tr class="context {trclass|odd}" id="pg_{id}" ><td class="bwhite"  style="padding:0;">
<table class="tahoma ctext size11 long fixed">
<col width="23px">
<col width="90px"><col width="140px"><col width="auto"><col width="auto"><col width="auto">
<col width="25px"><col width="50px">
<col width="23px">
<tr>
<th class="nopage align_center" style="padding:0 2px;"><div class="win_max closed  open_close">&nbsp;</div></th>
<td class="text_edit" id="item_name_{id}" title="тип:Таблица">{name|таблица}</td>
<td>
<nobr>
<!--<input  onkeydown="need_Save();" type="text"  title="ширина фото в пикселях" name="tab_width_{id}"  style="width:100px;" >
&nbsp;-->
<b>{name|Таблица}</b>
<input  onkeydown="need_Save();" type="text" name="tab_rows_{id}"  style="width:30px;" title="строки">
x
<input  onkeydown="need_Save();" type="text" style="width:30px;" name="tab_colls_{id}" title="столбцы">
</nobr>
</td>

<!--begin:vstavka-->
<td></td>
<td title="Сюда можно вставить скопированную таблицу." class="nocontext context" colspan="2" style="width:222px;"><textarea class="clipboard" style="width:210px;height:20px;overflow:hidden;"
onchange="tabLook(this);" onpaste="tabLook(this);"></textarea>
</td>
<!--end:vstavka-->

<!--begin:img_options-->
<td   id="img_s_{id}" style="width:170px;border-right:none;padding-left:0;padding-right:0;margin:0;" title="Размер мал. фото (px)"><nobr><span style="margin-left:10px;"><b>Фото:</b> &nbsp; Мал.</span>  <input name="w_s_{id}" type="text" value=""  onkeydown="need_Save()" style="width:35px;"> </nobr>
<nobr>x  <input name="h_s_{id}" type="text" value=""  onkeydown="need_Save()" style="width:35px;"> </nobr></td>
<td   id="img_b_{id}" style="width:125px;border-right:none;padding-left:0;padding-right:0;margin:0;" title="Размер бол. фото (px)"><nobr><span style="margin-left:10px;">Бол.</span>  <input name="w_b_{id}" type="text" value=""  onkeydown="need_Save()" style="width:35px;"> </nobr>
<nobr>x  <input name="h_b_{id}" type="text" value=""  onkeydown="need_Save()" style="width:35px;"> </nobr></td>
<td title="Рамка"> <input name="border_{id}" type="text" value=""  class="win_check"></td>
<!--end:img_options-->

<td class="align_center" style="padding:0 2px;">
<input type="text" name="item_align_{id}" class="align hidden"></td>
<th class="nopage align_center" style="padding:0 2px;" nowrap>
{::tpl:admin:order_elm_start}
<input type="text" class="order size11" onKeyDown="need_Save()" style="width:15px;" name="item_order_{id}">
{::tpl:admin:order_elm_fin}
</th>
<th class="align_center" style="padding:0 2px;">{::tpl:admin:delrec_elm}
</th>
</tr>

<tr class="bwhite"  style="display:none;">
<td class="bwhite" colspan=2></td>
<td colspan="6"><div style="height:100%;width:100%;overflow-y:visible; overflow-x:auto;">{thetable}</div>
</td></tr></table>
</td>
</tr>
<!--end:table_edit_line-->
</table>
<!--begin:edit_table-->
<table border=1 style="{notfixed?:table-layout: fixed;}" class="table tahoma size11">
{notfixed?:<col width="30px">}
<!--<col width="auto">{cols}-->
{notfixed?:<col width="50px"><col width="25px">}
<tr class="odd"><th ></th>{inputs}<th  colspan=2></th></tr>
<tr class="even"><th >№</th>{header}<th  colspan=2></th></tr>
<!--begin:rows-->
<tr id="pg_{id}" title="1" class="vnutr context {class}"><th>{number}</th>{row}
<th class="bblue align_center" style="width:50px;padding:0 2px;" nowrap>
<table class="compact table bblue"><tr><th class="nopage"><input type="button" class="win_max p120"
 onclick="order(this,'+');"
></th>
<th class="nopage">
<input type="text" onKeyDown="need_Save()" class="order size11" style="width:15px;" name="item_order_{id}">
</th><th class="nopage">
<input type="button" class="win_max p105"
 onclick="order(this,'-');"
>
</th></tr></table>
</th>
<th style="width:25px;padding:0 2px;">{::tpl:admin:delrec_elm}
</th></tr>
<!--end:rows-->
</table>
<!--end:edit_table-->

<!--begin:edit_row-->
<!--begin:cols-->
	<{td|td}{colspan>>format>> colspan="%s"}{rowspan>>format>> rowspan="%s"} class="context {class_r}" id="pg_{id}">{text>>x15}</{td|td}>
<!--end:cols-->
<!--end:edit_row-->

<table>
<!--begin:row_edit_line-->
<tr class="bwhite" id="pg_{id}">
<td></td>
<td class="text_edit" id="item_name_{id}">{name}</td>
<td class="align_left">
<input type="submit"
			title="добавить новую ссылку" class="win_max"
			style="float:right;background-position: 0 -90px;" name="new_row_add_{id}" value="&nbsp;">
			</td><td>
<input type="text" name="item_align_{id}" class="align hidden"></td>
<td class="align_center" style="padding:0 2px;" nowrap>
{::tpl:admin:order_elm_start}
<input type="text" onKeyDown="need_Save()" class="order size11" style="width:15px;" name="item_order_{id}">
{::tpl:admin:order_elm_fin}
</td>
<td style="padding:0 2px;">{::tpl:admin:delrec_elm}
</td>
</tr>
<!--end:row_edit_line-->

<!--begin:piconly_edit_line-->
<tr id="pg_{id}">
<td style="padding:0;">
<table class="fixed tahoma ctext size11">
<col width="130px"><col width="50px"><col width="auto"><col width="auto">
<tr>
<td rowspan=3 width="130px">
<img alt="" onload="checkImg(this,120,100)" title="просмотр маленькой картинки" alt="просмотр маленькой картинки" src="../{pic_small}">
</td><td align="right">мал.</td>
<td  class="uploader">
<input style="overflow:hidden;width:100%" title="вставьте маленькую картинку" type="text" name="pic_small_{id}"></td>
<td width="100px">{swidth}x{sheight}</td>
</tr><tr>
<td align="right">бол.</td>
<td class="uploader">
<input  style="overflow:hidden;width:100%;" title="вставьте большую картинку" type="text" name="pic_big_{id}"></td>
<td width="100px">{bwidth}x{bheight}</td>
</tr><tr>
<td align="right">Опис.</td>
<td  >
<input style="overflow:hidden;width:100%;" type="text" onKeyDown="need_Save()" title="название картинки" name="pic_comment_{id}"></td>
<td>
<input class="nocontext long link_toolbox" title="Ссылка" type="text" name="item_url_{id}"></td>
</tr></table></td>
<th class="nopage align_center" style="padding:0 2px;" nowrap>
{::tpl:admin:order_elm_start}
<input type="text" class="order size11" onKeyDown="need_Save()" style="width:15px;" name="item_order_{id}">
{::tpl:admin:order_elm_fin}
</th>
<th class="align_center" style="padding:0 2px;">{::tpl:admin:delrec_elm}
</th>
</tr>

<!--end:piconly_edit_line-->

<!--begin:menu_edit_line-->
<tr id="id_{id}"><td class="text_edit" id="name_{id}">
{name}
</td><td class="text_edit" id="url_{id}">{url}
</td>
<td class="text_edit" id="descr_{id}">{descr}</td>
<td ><input type="text" class="check_0_15" name="type_{id}" style="width:20px">
</td>
<td class="align_center win_order" style="padding:0 2px;" nowrap>
<input type="text" class="order_0" name="order_{id}">
</td>
<td style="padding:0 2px;">{::tpl:admin:delrec_elm}
</td>
</tr>
<!--end:menu_edit_line-->

<!--begin:article_edit--><tr >
		<td style="padding:2px 0px;border-collapse:collapse;"
			class="bblue align_left"><table
			class="long wide blue table tahoma size11">
	<tr>
		<td class="align_middle" style="width:auto;" width="auto"><div style="width:300px;">
		<input type="text" class="tahoma fills size11" style="width:100px;" title="Название раздела в адресной строке" 
	alt="Название раздела в адресной строке" onChange="need_Save()" name="alt_name_{id}"> 
		
		</div></td>
		<th class="bblue" style="padding:0;" width="250" style="width:250px;padding:0;">
			<table class="tahoma long" style="font-size:10px;height:12px;"><tr id="pg_{id}">
			<td width="30%" style="padding:0px;height:12px;overflow:hidden;" class="edited html_edit" title="TITLE (заголовок на синей полосе)"
				 id="article_title_{id}"><div style="padding:0px;height:12px;overflow:hidden;">{article_title>>x15}</div></td>
			<td width="30%" style="padding:0px;height:12px;overflow:hidden;" title="Поле DESCRIPTION (описание раздела)" class="edited html_edit" id="article_descr_{id}"><div style="padding:0px;height:12px;overflow:hidden;">{article_descr>>x15}</div></td>
			<td width="30%" style="padding:0px;height:12px;overflow:hidden;" class="edited html_edit" title="Значения KEYWORDS (слово, слово, слово, ...)" id="article_keywords_{id}"><div style="padding:0px;height:12px;overflow:hidden;">{article_keywords>>x15}</div></td>
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
					<td><img src="img/zagol.gif" onMouseOver="this.src='img/zagol_a.gif'" onMouseOut="this.src='img/zagol.gif'" onMouseDown="this.src='img/zagol_n.gif'" onMouseUp="this.src='img/zagol_a.gif'" onClick="add_elm(20)"/></td>
					<td><img src="img/text.gif" onMouseOver="this.src='img/text_a.gif'" onMouseOut="this.src='img/text.gif'" onMouseDown="this.src='img/text_n.gif'" onMouseUp="this.src='img/text_a.gif'" onClick="add_elm(16)"/></td>
					<td><img src="img/gallery.gif" onMouseOver="this.src='img/gallery_a.gif'" onMouseOut="this.src='img/gallery.gif'" onMouseDown="this.src='img/gallery_n.gif'" onMouseUp="this.src='img/gallery_a.gif'" onClick="add_elm(7)"/></td>
					<td><img src="img/gallery2.gif" onMouseOver="this.src='img/gallery2_a.gif'" onMouseOut="this.src='img/gallery2.gif'" onMouseDown="this.src='img/gallery2_n.gif'" onMouseUp="this.src='img/gallery2_a.gif'" onClick="add_elm(26)"/></td>
					<td><img src="img/links.gif" onMouseOver="this.src='img/links_a.gif'" onMouseOut="this.src='img/links.gif'" onMouseDown="this.src='img/links_n.gif'" onMouseUp="this.src='img/links_a.gif'" onClick="add_elm(3)"/></td>
					<td><img src="img/table.gif" onMouseOver="this.src='img/table_a.gif'" onMouseOut="this.src='img/table.gif'" onMouseDown="this.src='img/table_n.gif'" onMouseUp="this.src='img/table_a.gif'" onClick="add_elm(6)"/></td>
					<td><img src="img/line.gif" onMouseOver="this.src='img/line_a.gif'" onMouseOut="this.src='img/line.gif'" onMouseDown="this.src='img/line_n.gif'" onMouseUp="this.src='img/line_a.gif'" onClick="add_elm(27)"/></td>
					<td><img src="img/col.gif" onMouseOver="this.src='img/col_a.gif'" onMouseOut="this.src='img/col.gif'" onMouseDown="this.src='img/col_n.gif'" onMouseUp="this.src='img/col_a.gif'" onClick="add_elm(19)"/></td>
					<td><img src="img/price.gif" onMouseOver="this.src='img/price_a.gif'" onMouseOut="this.src='img/price.gif'" onMouseDown="this.src='img/price_n.gif'" onMouseUp="this.src='img/price_a.gif'" onClick="add_elm(28)"/></td>
					<td><img src="img/csv.gif" onMouseOver="this.src='img/csv_a.gif'" onMouseOut="this.src='img/csv.gif'" onMouseDown="this.src='img/csv_n.gif'" onMouseUp="this.src='img/csv_a.gif'" onClick="add_elm(8)"/></td>
					<td><img src="img/kom.gif" onMouseOver="this.src='img/kom_a.gif'" onMouseOut="this.src='img/kom.gif'" onMouseDown="this.src='img/kom_n.gif'" onMouseUp="this.src='img/kom_a.gif'" onClick="add_elm(29)"/></td>
					<td><img src="img/save_d.gif" onMouseOver="save_over()" onMouseOut="save_out()" onClick="save_act()" onMouseDown="save_doun()" onMouseUp="save_up()" id="save_btn"/></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2" style="height:30px;vertical-align:bottom;color:#000000;"><span><input class="win_check" id="dostup_{id}" name="dostup_{id}" type="text" value="{dostup}" style="display:none;"></span> Только для зарегистрированных пользователей</td>
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
	</tr>{data}
<tr><td  style="background:white; height:10px;">
</td></tr>
<!--end:article_edit-->

<!--begin:menu_edit_list-->
{data}
<!--end:menu_edit_list-->

<!--begin:menu_edit_addnew-->
<tr><td style="background:white; height:10px;">
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

</td></tr>
<!--end:menu_edit_addnew-->
</table>
<!--begin:sitemap-->
<div style="padding:3px 0">
<a class="tahoma ctext menu blue"  href="?do=menu&id={id}">{menu|Главное меню}</a>
<ul class="menu ctext tahoma size11">
{data}</ul></div>
<!--end:sitemap-->

<!--begin:ermess-->
<div style="padding-top:60px;" class='red'>
<p><b>Страница, которую вы запросили, отсутствует на сайте</b></p>
</div>
<!--end:ermess-->
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

<iframe src='about:blank' id='uploadFrame' name='uploadFrame' class="hidden">
</iframe>

<div id="link_toolbox" class="tahoma toolbox menu cltext" style="z-index:1000;cursor:pointer;display:none;position:absolute;">
<a href="#ajax:get_menu_list">Ссылка на раздел сайта</a>
<a href="#ajax:get_file_list">Ссылка на файл</a>
<a href="#ajax:get_picture_list">Ссылка на картинку</a>
</div>

<div id='uploader' class="nocontext"
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
href="#" onClick="this.setAttribute('href','mailto:art@xilen.ru?subject=Сообщение%20с%20сайта%20'+document.location.hostname);"
>&nbsp;</a>
<a style="text-decoration:none;display:block;top:10px;right:20px;position:absolute;width:30px; height:20px;"
href="{::index}/"
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
<span class="link menu tahoma size11 ctext"><a class="blue link" href="{::curl:do:id}">Главная</a>
<!--begin:::menu:head-->
{first|&nbsp;/&nbsp;&nbsp;}<a class="{current}" href="{::curl:do:id}do={menupage|menu}&id={id}">{name}</a>
<!--end:::menu:head-->
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
		<!--begin:::pluginlist-->
<span class="tahoma ctext menu blue">Список модулей</span>
<ul class="menu ctext tahoma size11">
<!--begin:param-->	
<li><a  href="?do=siteparam">Параметры</a></li>
<!--end:param-->
<!--begin:list-->
<li><a  href="?do=menu&id={plugin}">{name|---}</a></li>
<!--end:list-->
</ul>
<!--end:::pluginlist-->	<div id="main_menu" class="context">
		{::menu:right}</div>
</div>
		</td>
		<td {::menu:has:left?colspan=2} valign="top"
			style="padding: 10px; width:100%;">
		{data}
		{::menu:has:left??::</td><td style="padding: 20px;background:url(img/side_z.gif) repeat-y transparent;" valign="top">}
			{::menu:left}
		</td>
	</tr>
	<tr>
		<td colspan=3  style="height:30pt;background: url(img/menu20.gif) repeat-x;">
		{::menu:botom}
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

<input type="checkbox" value='1' id="check_tpl">

<div class="tahoma toolbox cltext size11" style="position:absolute;z-index:1001;" id="menu_tpl"></div>

<div class="tahoma cltext size11" style="z-index:10;top:0px;left:0;padding:10px;border: 1px gray solid; background:white;position:absolute;top:1em;" id="xmenu">
<a class="long" style="margin:2px;display:block;" href="#1">Клиент/Партнер/VIP</a>
<a class="long" style="margin:2px;display:block;" href="#2">4 цены</a>
<a class="long" style="margin:2px;display:block;" href="#3">одна цена</a>
</div>

<div class="tahoma cltext size11" style="z-index:20;top:0px;left:0;padding:10px;border: 1px gray solid; background:white;position:absolute;top:1em;" id="xoptions">
<!--begin:qa::getOptions-->
<a class="long" style="margin:2px;display:block;" href="#{id}">{name}</a>
<!--end:qa::getOptions-->
</div>

<div class="tahoma cltext size11" style="z-index:10;top:0px;left:0;padding:10px;border: 1px gray solid; background:white;position:absolute;top:1em;" id="xuser">
<a class="long" style="margin:2px;display:block;" href="#1">Клиент</a>
<a class="long" style="margin:2px;display:block;" href="#2">Партнер</a>
<a class="long" style="margin:2px;display:block;" href="#3">VIP</a>
</div>
<!--begin:dd_menu-->
<div class="tahoma cltext size11" style="z-index:10;top:0px;left:0;padding:10px;border: 1px gray solid; background:white;position:absolute;top:1em;" id="{name}">
<!--begin:list-->
<a class="long" style="margin:2px;display:block;" href="#{id}">{text}</a>
<!--end:list-->
</div>
<!--end:dd_menu-->

<!--begin:katalog_back-->
<p><a href="{raddr}">&lt;&lt; Назад</a></p>
<!--end:katalog_back-->

<!--begin:katalog_back_bt-->
<p><a href="{raddr}">&lt;&lt; Назад</a></p>
<!--end:katalog_back_bt-->

</div>
<div style="display:none;" id='xxMenu'>{xMenu}</div>

<div id="debug"></div>
<script type="text/javascript">
window.setup_menu_plus=[
	"xmenu"	
	{js_string}
]
</script>
</body>
<!--begin:ajax--><!--begin:data-->{result}{tval}<!--end:data--><!--end:ajax-->

</html>

