<?php
class tpl_elements extends tpl {

function _(&$par){		
		return tpl::_a($par['anchor'],array('tpl_elements','anchor')).'

'.tpl::_a($par['flash'],array('tpl_elements','flash')).'	
<!--  ////////////////// вывод сгенерированной статьи /////////////////////// -->

'.tpl::_a($par['edit_table'],array('tpl_elements','edit_table')).'
<table><tr>
'.tpl::_a($par['edit_row'],array('tpl_elements','edit_row')).'
</tr></table>
'.tpl::_a($par['katalogx'],array('tpl_elements','katalogx')).'

'.tpl::_a($par['katalog'],array('tpl_elements','katalog')).'

'.tpl::_a($par['katalog_searchres'],array('tpl_elements','katalog_searchres')).'

'.tpl::_a($par['pages'],array('tpl_elements','pages')).'
<!-- ////////////////// Шаблон - sitemap /////////////////////// -->

'.tpl::_a($par['subcat'],array('tpl_elements','subcat')).'

'.tpl::_a($par['sitemap'],array('tpl_elements','sitemap')).'

'.tpl::_a($par['leftmenu'],array('tpl_elements','leftmenu')).'

'.tpl::_a($par['topmenu'],array('tpl_elements','topmenu')).'

'.tpl::_a($par['ermess'],array('tpl_elements','ermess')).'

'.tpl::_a($par['nfirst'],array('tpl_elements','nfirst')).'

'.tpl::_a($par['nfirst2'],array('tpl_elements','nfirst2')).'

'.tpl::_a($par['ajax'],array('tpl_elements','ajax')).'
'.tpl::_a($par['vote'],array('tpl_elements','vote')).'

'.tpl::_a($par['running_line'],array('tpl_elements','running_line')).'
'.tpl::_a($par['novinki'],array('tpl_elements','novinki')).'
'.tpl::_a($par['shortspec'],array('tpl_elements','shortspec')).'

'.tpl::_a($par['specpredl'],array('tpl_elements','specpredl')).'

'.tpl::_a($par['basket_btn'],array('tpl_elements','basket_btn')).'

'.tpl::_a($par['basket_top'],array('tpl_elements','basket_top')).'

'.tpl::_a($par['field_kvit'],array('tpl_elements','field_kvit')).'
'.tpl::_a($par['field_bnal'],array('tpl_elements','field_bnal')).'
'.tpl::_a($par['searchres'],array('tpl_elements','searchres')).'
'.tpl::_a($par['charact'],array('tpl_elements','charact')).'
Элемент - запчасти для таблицы
'.tpl::_a($par['table_parts'],array('tpl_elements','table_parts')).'

'.tpl::_a($par['catalogue'],array('tpl_elements','catalogue')).'



'.tpl::_a($par['xcatalogue'],array('tpl_elements','xcatalogue')).'

'.tpl::_a($par['xxcatalogue'],array('tpl_elements','xxcatalogue')).'

'.tpl::_a($par['basket_btn'],array('tpl_elements','basket_btn')).'

'.tpl::_a($par['basket_top'],array('tpl_elements','basket_top')).'

'.tpl::_a($par['katalog_back'],array('tpl_elements','katalog_back')).'

'.tpl::_a($par['katalog_back_bt'],array('tpl_elements','katalog_back_bt'));
}

function anchor(&$par){		
		return '<a name="anc_'.(isset($par['xitem_name'])?$par['xitem_name']:'').'"></a>
'.tpl::_a($par['header'],array('tpl_elements','anchor_header'));
}

function anchor_header(&$par){		
		return '<table><tr><td><div class="header align_'.tpl::_d($par['align'],'left').'">
<h1>'.(isset($par['header'])?$par['header']:'').'</h1>
</div></td></tr></table>';
}

function flash(&$par){		
		return '<OBJECT classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0"
width="'.(isset($par['width'])?$par['width']:'').'" height="'.(isset($par['height'])?$par['height']:'').'">
<PARAM NAME=movie VALUE="'.tpl::_ax(tpl::_export('','index'),array('tpl_elements','flash___index')).'/img/'.(isset($par['swf'])?$par['swf']:'').'.swf">
<PARAM NAME=quality VALUE=high> <PARAM NAME=bgcolor VALUE=#FFFFFF>
<PARAM NAME=wmode VALUE=transparent>
<EMBED src="'.tpl::_ax(tpl::_export('','index'),array('tpl_elements','flash___index')).'/img/'.(isset($par['swf'])?$par['swf']:'').'.swf"
quality=high bgcolor=#FFFFFF
width="'.(isset($par['width'])?$par['width']:'').'" height="'.(isset($par['height'])?$par['height']:'').'" wmode="transparent"
NAME="mtown" ALIGN="" TYPE="application/x-shockwave-flash"
PLUGINSPAGE="http://www.macromedia.com/go/getflashplayer">
</EMBED>
</OBJECT>';
}

function edit_table(&$par){		
		return '<div class="long align_'.tpl::_d($par['align'],'center').'">
<table style="margin-top:20px; margin-bottom:20px; " class="table tablex">
'.(isset($par['cols'])?$par['cols']:'').'
<tr>'.(isset($par['header'])?$par['header']:'').'</tr>
'.tpl::_a($par['rows'],array('tpl_elements','edit_table_rows')).'
</table></div>';
}

function edit_table_rows(&$par){		
		return '<tr class="'.(isset($par['class'])?$par['class']:'').'">'.(isset($par['row'])?$par['row']:'').'</tr>
'.tpl::_a($par['opisanie'],array('tpl_elements','edit_table_rows_opisanie'));
}

function edit_table_rows_opisanie(&$par){		
		return '<tr class="'.(isset($par['class'])?$par['class']:'').'"><td colspan="'.(isset($par['colnumber'])?$par['colnumber']:'').'" style="padding:0;border-right:none;border-left:none;"><div id="opisanie_'.(isset($par['id'])?$par['id']:'').'" style="display:none;padding:5px;">'.(isset($par['opisanie_text'])?$par['opisanie_text']:'').'</div></td></tr>';
}

function edit_row(&$par){		
		return tpl::_a($par['cols'],array('tpl_elements','edit_row_cols'));
}

function edit_row_cols(&$par){		
		return '<'.(isset($par['td'])?$par['td']:'').tpl::_format(pps($par['colspan']),' colspan="%s"').tpl::_format(pps($par['rowspan']),' rowspan="%s"').' style="'.(isset($par['width'])?$par['width']:'').'" class="'.(isset($par['class22'])?$par['class22']:'').'">'.tpl::_d($par['text'],'&nbsp;').'</'.(isset($par['td'])?$par['td']:'').'>';
}

function katalogx(&$par){		
		return '<table class="align_'.tpl::_d($par['align'],'center').'" style="width:100%;border-spacing:0;"><tr><td>
<table  style="border-spacing:0;">

	'.tpl::_a($par['upload'],array('tpl_elements','katalogx_upload')).'

	'.tpl::_a($par['href'],array('tpl_elements','katalogx_href')).'
</table>
</td></tr></table>';
}

function katalogx_upload(&$par){		
		return '<tr>
	'.tpl::_a($par['link'],array('tpl_elements','katalogx_upload_link')).'
	</tr>';
}

function katalogx_upload_link(&$par){		
		return '<td style=\'padding:0 10px;\' class="tahoma ctext link align_left">
		<img alt="" src=\''.tpl::_ax(tpl::_export('','index'),array('tpl_elements','katalogx_upload_link___index')).'/img/li_red.gif\'>&nbsp;&nbsp;<a href="'.(isset($par['url'])?$par['url']:'').'" '.(isset($par['target'])?$par['target']:'').'>'.tpl::_d($par['text'],'xxx').'</a>
		</td>';
}

function katalogx_href(&$par){		
		return '<tr>
	'.tpl::_a($par['link'],array('tpl_elements','katalogx_href_link')).'
	</tr>';
}

function katalogx_href_link(&$par){		
		return '<td style=\'padding:5px 10px;\' class="tahoma ctext link align_left">
		<a '.(isset($par['target'])?$par['target']:'').' href="'.(isset($par['url'])?$par['url']:'').'" class="strelka">'.tpl::_d($par['text'],'xxx').'</a>
		<span id="opisanie_btn_'.(isset($par['id'])?$par['id']:'').'" class="skryt"><a href="javascript:hide_opisanie(\''.(isset($par['id'])?$par['id']:'').'\');">(Скрыть)</a></span>
		<div id="opisanie_'.(isset($par['id'])?$par['id']:'').'" style="display:none;">'.tpl::_d($par['opisanie'],'').'</div>
		</td>';
}

function katalog(&$par){		
		return '<table style="border-spacing:0;width: 100%; margin: 10px 0 10px 0;">



	'.tpl::_a($par['text'],array('tpl_elements','katalog_text')).'


	'.tpl::_a($par['textpic'],array('tpl_elements','katalog_textpic')).'


	'.tpl::_a($par['header'],array('tpl_elements','katalog_header')).'

	'.tpl::_a($par['upload'],array('tpl_elements','katalog_upload')).'

	'.tpl::_a($par['href'],array('tpl_elements','katalog_href')).'

	'.tpl::_a($par['table'],array('tpl_elements','katalog_table')).'

	'.tpl::_a($par['catalogue'],array('tpl_elements','katalog_catalogue')).'

'.tpl::_a($par['gallery'],array('tpl_elements','katalog_gallery')).'	'.tpl::_a($par['galleryX'],array('tpl_elements','katalog_galleryX')).'
</table>';
}

function katalog_text(&$par){		
		return '<tr>
			<td class="text tahoma bgray">
				'.tpl::_a($par['pic'],array('tpl_elements','katalog_text_pic')).'
				<p>'.(isset($par['the_text'])?$par['the_text']:'').'</p>
			</td>
		</tr>';
}

function katalog_text_pic(&$par){		
		return '<div class="float_'.tpl::_b($par['align'],'','').'"><a href="'.(isset($par['the_href'])?$par['the_href']:'').'" onclick="return WindowO(this,\''.(isset($par['pict2'])?$par['pict2']:'').'\','.tpl::_d($par['bwidth'],100).','.tpl::_d($par['bheight'],100).')"><img alt="'.(isset($par['comment'])?$par['comment']:'').'" title="'.(isset($par['comment'])?$par['comment']:'').'" style="border:0px" src="'.(isset($par['pict1'])?$par['pict1']:'').'"></a></div>';
}

function katalog_textpic(&$par){		
		return '<tr>
			<td class="text textpict tahoma">

				'.tpl::_a($par['row'],array('tpl_elements','katalog_textpic_row')).'
                
			<div class="size12 link ctext border_table" style="padding-right:20px;">'.(isset($par['the_text'])?$par['the_text']:'').'</div>
        
				'.tpl::_a($par['articles'],array('tpl_elements','katalog_textpic_articles')).'		
        	</td>
		</tr>';
}

function katalog_textpic_row(&$par){		
		return '<div class="float_'.(isset($par['align'])?$par['align']:'').'  gallery" >
						'.tpl::_a($par['pict'],array('tpl_elements','katalog_textpic_row_pict')).'
                   	</div>';
}

function katalog_textpic_row_pict(&$par){		
		return tpl::_ax(tpl::_export('','tpl','admin','imgpre'),array('tpl_elements','katalog_textpic_row_pict___tpl_admin_imgpre')).'
                            
                            	
    							<table ><tr><td  style="padding: 6px 7px; background: none repeat scroll 0% 0% rgb(253, 198, 142);">
										 <a rel="g'.(isset($par['pid'])?$par['pid']:'').'" href="'.tpl::_d($par['xhref'],'#').'" '.tpl::_format(pps($par['comment']),' title="%s"').'><img class="text_picture"  src="'.(isset($par['small_pict'])?$par['small_pict']:'').'" alt="'.tpl::_html(pps($par['comment'])).'" '.tpl::_brd(pps($par['border'])).'></a>
										</td></tr>
    									<tr><td class="align_right">
   										 <img class="iePNG" style="width: 77px; height: 8px;" src="'.tpl::_ax(tpl::_export('','index'),array('tpl_elements','katalog_textpic_row_pict___index')).'/img/xx2.png">
										</td></tr>
								</table>
	
                            <p style="text-align: center;margin-bottom:16px;">'.tpl::_html(pps($par['comment'])).'</p>'.tpl::_ax(tpl::_export('','tpl','admin','imgpost'),array('tpl_elements','katalog_textpic_row_pict___tpl_admin_imgpost'));
}

function katalog_textpic_articles(&$par){		
		return tpl::_a($par['links'],array('tpl_elements','katalog_textpic_articles_links'));
}

function katalog_textpic_articles_links(&$par){		
		return '<br><a title="'.tpl::_striptags(pps($par['item_text'])).'" href="javascript:show_opisanie(\''.(isset($par['id'])?$par['id']:'').'\');" class="link1">'.tpl::_striptags(pps($par['item_text'])).'</a>
                    <span id="opisanie_btn_'.(isset($par['id'])?$par['id']:'').'" class="skryt"><a href="javascript:hide_opisanie(\''.(isset($par['id'])?$par['id']:'').'\');">(Скрыть)</a></span>
					<div id="opisanie_'.(isset($par['id'])?$par['id']:'').'" style="display:none;">'.(isset($par['opisanie'])?$par['opisanie']:'').'</div>';
}

function katalog_header(&$par){		
		return '<tr>
			<td style="font-size:18px;" class="header tahoma ctext size12  align_'.(isset($par['align'])?$par['align']:'').'">
				<div><b>'.(isset($par['the_header'])?$par['the_header']:'').'</b></div>
			</td>
		</tr>';
}

function katalog_upload(&$par){		
		return '<tr>
			<td class="tahoma ctext link align_'.(isset($par['align'])?$par['align']:'').'">
				<img alt="" src=\''.tpl::_ax(tpl::_export('','index'),array('tpl_elements','katalog_upload___index')).'/img/li_red.gif\'>&nbsp;&nbsp;<a href="'.tpl::_ax(tpl::_export('','index'),array('tpl_elements','katalog_upload___index')).'/uploaded/'.(isset($par['the_upload'])?$par['the_upload']:'').'">'.(isset($par['the_text'])?$par['the_text']:'').'</a>
			</td>
		</tr>';
}

function katalog_href(&$par){		
		return '<tr>
			<td class="tahoma ctext link align_'.(isset($par['align'])?$par['align']:'').'">
				<img alt="" src=\''.tpl::_ax(tpl::_export('','index'),array('tpl_elements','katalog_href___index')).'/img/li_red.gif\'>&nbsp;&nbsp;<a href="'.(isset($par['the_href'])?$par['the_href']:'').'">'.(isset($par['the_text'])?$par['the_text']:'').'</a>
			</td>
		</tr>';
}

function katalog_table(&$par){		
		return '<tr>
            <td class="table align_'.(isset($par['align'])?$par['align']:'').'">
				<table class="tahoma bgray size11 '.tpl::_d($par['border'],'border').'">'.(isset($par['table'])?$par['table']:'').'</table>
			</td>
		</tr>';
}

function katalog_catalogue(&$par){		
		return '<tr><td>
				<form name="basket" method="POST">
				<table class="long table size12 ctext align_left"><tr><td colspan="'.tpl::_bx(tpl::_export('','rightb',1),'','').'">
					<table class="size11 long">
						<tr>
							<td class="align_right" style="vertical-align:middle;padding-right:40px;">
                            	<nobr><u>Кол-во на странице</u>&nbsp;&nbsp;&nbsp;&nbsp;
								<select name="perpage" style="border:1px solid #dddddd;">
								<option value="10">10</option><option value="20">20</option>
								<option value="40">40</option><option value="60">60</option>
								<option value="100">100</option><option value="200">200</option><option value="500">500</option></select>
								<input type="submit" style="background:url(img/btn_red.gif); border:0;width:13px; height:21px;" value="&nbsp;" src="'.tpl::_ax(tpl::_export('','index'),array('tpl_elements','katalog_catalogue___index')).'/img/button3.gif">
								</nobr>
							</td></tr>
							<tr><td style="height:15px;"></td></tr>
							<tr><td style="padding-left:100px;">'.(isset($par['pages'])?$par['pages']:'').'</td></tr>
					</table>
				</td></tr>
				<tr>
					<th style="height:40px;text-align:center;" class="nopage">фото</th><th>Артикул</th><th>Наименование</th>
					<th style="text-align:center;" class="nopage">Ед.изм.</th><th style="padding:0;width:2px;">&nbsp;</th>
					'.tpl::_bx(tpl::_export('','rightb',1,1),'','').'
				</tr>
				<tr class="odd"><td style="border-bottom:1px solid #dddddd;height:1px;" colspan='.tpl::_bx(tpl::_export('','rightb',1),'','').'></td></tr>
	'.tpl::_a($par['data'],array('tpl_elements','katalog_catalogue_data')).'
		<tr><td colspan='.tpl::_bx(tpl::_export('','rightb',1),'','').' height=20px></td></tr>
		<tr><td  colspan='.tpl::_bx(tpl::_export('','rightb',1),'','').'>
		<table class="long">
		<tr><td style="padding-left:100px;">
		'.(isset($par['pages'])?$par['pages']:'').'
		</td>
		</tr>

        <tr><td class="align_right">
		<table '.tpl::_bx(tpl::_export('','rightb',1,4),'','').'><tr>
		<td class="round white size11" style="vertical-align:middle;"><input type="submit"
		    name="clear_bsk"
			style="border:0; background:transparent;"
			class="white size11"
			onmouseover="this.style.textDecoration=\'underline\';"
			onmouseout="this.style.textDecoration=\'none\';"
		value="Очистить"></td><td class="rback"></td>
		<td style="width:30px;"></td>
		<td class="round white size11" style="vertical-align:middle;"><input type="submit"
			style="border:0; background:transparent;"
			class="white size11"
			onmouseover="this.style.textDecoration=\'underline\';"
			onmouseout="this.style.textDecoration=\'none\';"
		value="'.tpl::_d($par['button_val'],'Добавить в корзину').'"></td><td class="rback"></td>
		
		</tr></table>

	</td></tr></table>
	</td></tr>
		</table>
	</form>
	<script type="text/javascript">
	var x = document.forms[\'basket\'][\'perpage\'];
	if(x){
	var i =x.options.length;
	while(i--){
		if(x.options[i].value=='.(isset($par['perpage'])?$par['perpage']:'').')
			x.selectedIndex=i
	}}
	//document.forms[\'basket\'][\'perpage\'].value='.(isset($par['perpage'])?$par['perpage']:'').';
	</script>
	</td></tr>';
}

function katalog_catalogue_data(&$par){		
		return '<tr class="'.tpl::_b($par['even'],'','').'"><td style="height:11px;" colspan='.tpl::_bx(tpl::_export('','rightb',1),'','').'></td></tr>
		<tr id="xx_'.(isset($par['id'])?$par['id']:'').'" class="'.tpl::_b($par['even'],'','').'">
		<td class="">
		<div style="position:relative;"><div style="position:absolute;top:-10px;left:-5px;height:65px;width:65px;">
		<img onload="checkImg(this,45,45);" height="45px" '.tpl::_b($par['nobigpic'],'','').(isset($par['pic_big'])?$par['pic_big']:'').tpl::_b($par['nobigpic'],'','').' src="'.tpl::_d($par['pic_small'],'uploaded/pict.jpg').'">
		</div></div></td>
		<td class="border text">
		'.(isset($par['articul'])?$par['articul']:'').'</td>
		<td class="long border text" title="'.(isset($par['sdescr2'])?$par['sdescr2']:'').'">
		'.tpl::_a($par['xx'],array('tpl_elements','katalog_catalogue_data_xx')).'
		'.(isset($par['descr'])?$par['descr']:'').'
		'.tpl::_a($par['xx'],array('tpl_elements','katalog_catalogue_data_xx')).'
		</td>
		<td class="text"><a name="item_'.(isset($par['id'])?$par['id']:'').'"></a>
		'.(isset($par['edism'])?$par['edism']:'').'</td>
		'.tpl::_a($par['xright'],array('tpl_elements','katalog_catalogue_data_xright')).'
		</tr>
		<tr class="'.tpl::_b($par['even'],'','').'"><td style="border-bottom:1px solid #dddddd;height:10px;" colspan='.tpl::_bx(tpl::_export('','rightb',1),'','').'></td></tr>';
}

function katalog_catalogue_data_xx(&$par){		
		return '</a>';
}

function katalog_catalogue_data_xright(&$par){		
		return '<td class="text border" style="width:2px;padding-left:0;padding-right:0"></td>
		<td style="font-size:14" class="red menu  text border">'.(isset($par['cost'])?$par['cost']:'').'</td>
		<td class="align_center red size16" style="vertical-align:middle;padding:0 8px;width:30px;"><b>'.tpl::_b($par['sign'],'','').'</b></td>
		<td class="align_right" width="80px" style="vertical-align:middle;padding:0 10px 0 0px;">
			<input style="width:50px;" '.(isset($par['disabled'])?$par['disabled']:'').' name="item_'.(isset($par['id'])?$par['id']:'').'" value="'.(isset($par['value'])?$par['value']:'').'" class="input" type="text" ></td>';
}

function katalog_gallery(&$par){		
		return '<tr>
		<td class="box gallery tahoma ctext size11" >
		<input type="hidden" id="w_'.(isset($par['id'])?$par['id']:'').'" name="w_'.(isset($par['id'])?$par['id']:'').'" value="'.(isset($par['w_width'])?$par['w_width']:'').'">
		<input type="hidden" id="h_'.(isset($par['id'])?$par['id']:'').'" name="h_'.(isset($par['id'])?$par['id']:'').'" value="'.(isset($par['w_height'])?$par['w_height']:'').'">
		<div class="align_'.(isset($par['align'])?$par['align']:'').'">
		<div class="wideable scroll_gal">	
		'.tpl::_a($par['row'],array('tpl_elements','katalog_gallery_row')).'</div>
		</div>
		</td>
	</tr>';
}

function katalog_gallery_row(&$par){		
		return '<table class="align_center">
			<tr>
			'.tpl::_a($par['pict'],array('tpl_elements','katalog_gallery_row_pict')).'
			</tr>
			<tr>
			'.tpl::_a($par['pictcom'],array('tpl_elements','katalog_gallery_row_pictcom')).'
			</tr>		</table>';
}

function katalog_gallery_row_pict(&$par){		
		return '<td  style="vertical-align:bottom;padding:0 25px 5px 0;">
                

                
				'.tpl::_ax(tpl::_export('','tpl','admin','imgpre'),array('tpl_elements','katalog_gallery_row_pict___tpl_admin_imgpre')).'<table>
                						<tr><td class="picture_box"><a rel="g'.(isset($par['pid'])?$par['pid']:'').'" href="'.tpl::_d($par['xhref'],'#').'" '.tpl::_format(pps($par['comment']),' title="%s"').'><img src="'.(isset($par['small_pict'])?$par['small_pict']:'').'" alt="'.tpl::_html(pps($par['comment'])).'" '.tpl::_brd(pps($par['border'])).'></a></td></tr>
                                        <tr><td class="align_right"><img class="iePNG" style="width: 77px; height: 8px;" src="'.tpl::_ax(tpl::_export('','index'),array('tpl_elements','katalog_gallery_row_pict___index')).'/img/xx2.png"></td></tr>
                                    </table>
				</td>';
}

function katalog_gallery_row_pictcom(&$par){		
		return '<td style="vertical-align:top;padding:0 25px 20px 0;">
					<p style="text-align: center;">'.tpl::_html(pps($par['comment'])).'</p>'.tpl::_ax(tpl::_export('','tpl','admin','imgpost'),array('tpl_elements','katalog_gallery_row_pictcom___tpl_admin_imgpost')).'	
				</td>';
}

function katalog_galleryX(&$par){		
		return '<tr>
		<td class="gallery galleryX tahoma ctext size11 align_'.tpl::_d($par['align'],'center').'">
		<div class="long" style="background:#fffef3;">
		<div style="padding:7px 12px;">	
		<div style="padding-bottom:10px;" class="align_center align_middle">
		<div style="'.(isset($par['width'])?$par['width']:'').(isset($par['height'])?$par['height']:'').'" >
		<img class="long" style="'.(isset($par['width'])?$par['width']:'').(isset($par['height'])?$par['height']:'').'" onload="checkImg(this,'.(isset($par['widthx'])?$par['widthx']:'').','.(isset($par['heightx'])?$par['heightx']:'').')" onclick="$(this).parent().colorbox({ href:this.src});return false;" alt=\'\' src="javascript:;"></div>
		</div>
		<div class="wideable scroll_gal">	
		'.tpl::_a($par['row'],array('tpl_elements','katalog_galleryX_row')).'</div>
		</div></div>
		</td>
	</tr>';
}

function katalog_galleryX_row(&$par){		
		return '<table >
			<tr>
			'.tpl::_a($par['pict'],array('tpl_elements','katalog_galleryX_row_pict')).'
			</tr>
		</table>';
}

function katalog_galleryX_row_pict(&$par){		
		return '<td class="align_center" style="padding:1px 10px;">
				'.tpl::_ax(tpl::_export('','tpl','admin','imgpre'),array('tpl_elements','katalog_galleryX_row_pict___tpl_admin_imgpre')).'<a rel="g'.(isset($par['pid'])?$par['pid']:'').'" href="'.tpl::_d($par['big_pict'],'#').'" '.tpl::_format(pps($par['comment']),' title="%s"').'>
					<img style="'.tpl::_format(pps($par['shortheight']),'height:%s;').tpl::_format(pps($par['shortwidth']),'width:%s;').'" src="'.(isset($par['small_pict'])?$par['small_pict']:'').'" alt="'.tpl::_html(pps($par['comment'])).'"></a>'.tpl::_ax(tpl::_export('','tpl','admin','imgpost'),array('tpl_elements','katalog_galleryX_row_pict___tpl_admin_imgpost')).'	<div style="padding-bottom:10px;" class="align_center tahoma size11">'.(isset($par['comment'])?$par['comment']:'').'</div>
				</td>';
}

function katalog_searchres(&$par){		
		return '<a href="'.tpl::_ax(tpl::_export('','index'),array('tpl_elements','katalog_searchres___index')).'/?do=katalog&item='.(isset($par['id'])?$par['id']:'').'">
<span class="red">'.(isset($par['articul'])?$par['articul']:'').'</span>
&nbsp;&nbsp;'.(isset($par['descr'])?$par['descr']:'').'
</a>';
}

function pages(&$par){		
		return '<table class="link tahoma "><tr>
<td class="size12 " style="padding:9px 25px 9px 9px;">Страницы</td>
'.tpl::_a($par['mmin'],array('tpl_elements','pages_mmin')).'
'.tpl::_a($par['min'],array('tpl_elements','pages_min')).'
'.tpl::_a($par['page'],array('tpl_elements','pages_page')).'
'.tpl::_a($par['max'],array('tpl_elements','pages_max')).'
'.tpl::_a($par['mmax'],array('tpl_elements','pages_mmax')).'
</tr></table>';
}

function pages_mmin(&$par){		
		return '<td style="padding:9px;">
	<a href="'.tpl::_ax(tpl::_export('','curl','pg'),array('tpl_elements','pages_mmin___curl_pg')).'pg=0"><img style="border:0;" src="img/arr_blue_left.gif"></a>
</td>';
}

function pages_min(&$par){		
		return '<td style="padding:5px;width:20px;">
	<a class="blue link" href="'.tpl::_ax(tpl::_export('','curl','pg'),array('tpl_elements','pages_min___curl_pg')).'pg='.(isset($par['m5'])?$par['m5']:'').'">предыдущая</a>
</td>';
}

function pages_page(&$par){		
		return tpl::_a($par['link'],array('tpl_elements','pages_page_link')).'
'.tpl::_a($par['txt'],array('tpl_elements','pages_page_txt'));
}

function pages_page_link(&$par){		
		return '<td class=" align_center align_middle" style="padding: 5px;width:25px;">
	<a class="yellow" href="'.tpl::_ax(tpl::_export('','curl','pg'),array('tpl_elements','pages_page_link___curl_pg')).'pg='.(isset($par['url'])?$par['url']:'').'">'.(isset($par['txt'])?$par['txt']:'').'</a>
</td>';
}

function pages_page_txt(&$par){		
		return '<td class="align_center align_middle" style="padding: 5px;width:25px;background:url(img/round.gif) 2px 2px no-repeat;">
	'.(isset($par['txt'])?$par['txt']:'').'
</td>';
}

function pages_max(&$par){		
		return '<td style="padding:5px;width:20px;">
	<a class="blue link" href="'.tpl::_ax(tpl::_export('','curl','pg'),array('tpl_elements','pages_max___curl_pg')).'pg='.(isset($par['m5'])?$par['m5']:'').'">следующая</a>
</td>';
}

function pages_mmax(&$par){		
		return '<td style="padding:9px;">
	<a href="'.tpl::_ax(tpl::_export('','curl','pg'),array('tpl_elements','pages_mmax___curl_pg')).'pg='.(isset($par['m5'])?$par['m5']:'').'"><img style="border:0;" src="img/arr_blue_right.gif"></a>
</td>';
}

function subcat(&$par){		
		return '<div class="article menu red tahoma size11" ><span>ПОДРАЗДЕЛЫ</span></div>
<ul class="menu ctext tahoma size11">'.(isset($par['data'])?$par['data']:'').'</ul>';
}

function sitemap(&$par){		
		return '<ul class="oglav">'.(isset($par['data'])?$par['data']:'').'</ul>';
}

function leftmenu(&$par){		
		return '<div class="blue" style="width:220px;margin-bottom:25px;">
<ul id="menu_left1" style="clear:both;">'.(isset($par['data'])?$par['data']:'').'</ul>
</div>';
}

function topmenu(&$par){		
		return '<table style="margin-left:auto;margin-right:auto;"><tr><td>
<ul class="topmenu link ctext tahoma">
<li '.(isset($par['class'])?$par['class']:'').' style="border-left:none;"><a '.(isset($par['class'])?$par['class']:'').' href="'.tpl::_ax(tpl::_export('','index'),array('tpl_elements','topmenu___index')).'/">Главная</a></li>
'.(isset($par['data'])?$par['data']:'').'
<li '.(isset($par['class'])?$par['class']:'').'>
	<form name="search_form" method="POST" action="?do=search" style="margin:1px 0 0 30px;">
		<table style="vertical-align:middle;">
			<tr>
				<td><input id="search_string" name="search_string" title="Поиск!" type="text" value="Найти..." onClick="poisk()" style="width:93px;height:15px;border:solid 1px #b8bdc9;margin-right:4px;font-size:11px;padding-left:6px;"/></td>
				<td><input type="submit" value="" style="background:url(img/button2.gif) no-repeat center center #FFFFFF;border:none;width:12px;height:19px;"/></td>
			</tr>
		</table>
	</form>
</li></ul>
</td></tr></table>';
}

function ermess(&$par){		
		return '<div style="padding-top:60px;" class=\'red\'>
<p><b>Страница, которую вы запросили, отсутствует на сайте</b></p>
</div>';
}

function nfirst(&$par){		
		return '<div style="margin:35px 0px 30px 2px; float:none; display:block; padding:0px; white-space:nowrap; color:#fdd3b4" class="tahoma size11">
<a class="link_nfirst" href="'.tpl::_ax(tpl::_export('','index'),array('tpl_elements','nfirst___index')).'/">Главная</a>&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;
'.tpl::_a($par['list'],array('tpl_elements','nfirst_list')).'

</div>';
}

function nfirst_list(&$par){		
		return '<a class="link_nfirst" href="'.tpl::_ax(tpl::_export('','curl','do','id'),array('tpl_elements','nfirst_list___curl_do_id_topic')).'do=menu&id='.(isset($par['id'])?$par['id']:'').'">'.(isset($par['name'])?$par['name']:'').'</a>&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;';
}

function nfirst2(&$par){		
		return '<div style="position:relative; height:25px; margin-top:0px; padding-top:0px;">
<div class="nfirst" style="position:absolute; float:none; top:1px; left:1px; z-index:51; height:25px">
<h1 style="color:#fbd1b2; white-space:nowrap">'.(isset($par['sub'])?$par['sub']:'').'</h1>
</div>
<div class="nfirst" style="position:absolute; float:none; top:0px; left:0px; z-index:55; height:25px">
<h1 style="color: #9f4909; white-space:nowrap">'.(isset($par['sub'])?$par['sub']:'').'</h1>
</div>
</div>';
}

function ajax(&$par){		
		return (isset($par['data'])?$par['data']:'');
}

function vote(&$par){		
		return '<form method="POST" name="vote" id="vote" action="">
<input type="hidden" name="votes_identifier" value="oops!">
<div id="votes">
<table class="size11 ctext">
<tr><td style="height:80px;"><img src="'.tpl::_ax(tpl::_export('','index'),array('tpl_elements','vote___index')).'/img/votes.gif"></td></tr>
<tr><td style="padding:0 10px 0 25px;height:40px;">'.(isset($par['question'])?$par['question']:'').'</td></tr>
'.tpl::_a($par['list'],array('tpl_elements','vote_list')).'
</table>
<table style="margin:30px 0 0 25px"><tr><td class="round white size11" style="vertical-align:middle;"><input type="submit"
	style="border:0; background:transparent;"
	class="white size11"
	onmouseover="this.style.textDecoration=\'underline\';"
	onmouseout="this.style.textDecoration=\'none\';"
value="выбрать"></td><td class="rback"></td></tr></table>
</div></form>';
}

function vote_list(&$par){		
		return '<tr><td style="padding-left:25px;vertical-align:top;height:30px;" class="checkbox">
<label><input style="margin:0;border:0;" type="checkbox" name="aaa" value="'.(isset($par['id'])?$par['id']:'').'">'.(isset($par['asc'])?$par['asc']:'').'</label></td></tr>';
}

function running_line(&$par){		
		return '<div style="height:20pt;position:relative;overflow:hidden;">
		<div style="width:100%;position:absolute; clip:auto;overflow:hidden;">
		<table class="menu red size16" id="running_line"><tr><td nowrap>
		</td></tr></table></div></div>
<script type="text/javascript">
// running line
$(function(){
	var e =document.getElementById(\'running_line\');
	var disp=5,x=0,prev,
		lines=[\'\''.tpl::_a($par['data'],array('tpl_elements','running_line_data')).'];
	lines.shift(); // skip a line
	prev = lines.pop();
	x=e.scrollWidth+ 2+disp;
//	debug;
	setInterval(function(){
		x+=disp;
		if(x>e.scrollWidth) {
			x=-e.parentNode.offsetWidth;
			lines.push(prev);
			prev = lines.shift();
			$(e).find(\'td\').html(prev);
		}
		e.style.marginLeft=(-x)+\'px\';
		e.style.marginRight=x+\'px\';
	},100);
})
</script>';
}

function running_line_data(&$par){		
		return ',\''.(isset($par['descr'])?$par['descr']:'').'\'';
}

function novinki(&$par){		
		return '<div style="padding-right:40px">
<div style="padding:0 0 8px 0;"><img src="'.tpl::_ax(tpl::_export('','index'),array('tpl_elements','novinki___index')).'/img/novinki.gif"></div>
		'.tpl::_a($par['row'],array('tpl_elements','novinki_row')).'
		<table style="margin:30px 0 0 25px"><tr><td class="round white size11" style="vertical-align:middle;">
<a href="'.tpl::_ax(tpl::_export('','index'),array('tpl_elements','novinki___index')).'/?do=menu&id=novinki" type="button">Новинки</a>
</td><td class="rback"></td></tr></table></div>';
}

function novinki_row(&$par){		
		return '<table style="background:url(img/1x3.gif) bottom repeat-x"
			class="link tahoma ctext size11">
			<tr>
				<td colspan=2 class="align_left" style="padding:30px 0 10px 0;" >
				<img onload="checkImg(this,140,140)"  src="'.tpl::_d($par['pic_small'],'uploaded/pict.jpg').'"
				'.tpl::_b($par['nobigpic'],'','class="hand" onclick="WindowO(this,\'').(isset($par['pic_big'])?$par['pic_big']:'').tpl::_b($par['nobigpic'],'','\')"').'
				>
				</td></tr>
			<tr><td  colspan=2  style="padding-bottom:6px;"><a style="line-height:15px;" class="blue size12" href="'.tpl::_ax(tpl::_export('','index'),array('tpl_elements','novinki_row___index')).'/?do=katalog&item='.(isset($par['id'])?$par['id']:'').'">'.(isset($par['descr'])?$par['descr']:'').'</a></td></tr>
			<tr><td  colspan=2>артикул '.(isset($par['articul'])?$par['articul']:'').'</td></tr>
			<tr><td style="height:10px;"></td></tr>
			'.tpl::_a($par['xright'],array('tpl_elements','novinki_row_xright')).'
			<tr><td style="height:15px;"></td></tr>
		</table>';
}

function novinki_row_xright(&$par){		
		return '<tr><th class="button2">
				<input type="submit"  style="vertical-align:bottom;" value="'.(isset($par['cost'])?$par['cost']:'').'">
				</th><td style="vertical-align:bottom;padding:0 0 5px 10px;">
				'.tpl::_d($par['cost2'],'руб.').'/'.(isset($par['edism'])?$par['edism']:'').'
			</td></tr>';
}

function shortspec(&$par){		
		return '<table style="table-layout:fixed;" class="long tahoma link">
<col width="40px"><col width="210px"><col width="130px">
<col width="auto"><col width="120px"><col width="130px"><col width="40px">
<tr>
<td style="width:10%"></td>
<td colspan=2 style="padding-bottom:20px;border-top:1px #dddddd solid;">
<img src="'.tpl::_ax(tpl::_export('','index'),array('tpl_elements','shortspec___index')).'/img/hxx1.gif">
</td>
<td style="width:100%"></td>
<td colspan=2 style="padding-bottom:20px;border-top:1px #dddddd solid;">
<img src="'.tpl::_ax(tpl::_export('','index'),array('tpl_elements','shortspec___index')).'/img/hxx2.gif">
</td>
<td style="width:10%"></td>
</tr>
<tr>
<td></td><td style="padding:0 0 0 20px">
<img src="'.tpl::_ax(tpl::_export('','index'),array('tpl_elements','shortspec___index')).'/img/specpredl.gif"></td><td style="vertical-align:middle;"><nobr>&nbsp;&nbsp;&nbsp;\\&nbsp;&nbsp;
<a style="color:#EC4700" href="'.tpl::_ax(tpl::_export('','index'),array('tpl_elements','shortspec___index')).'/?do=menu&id=spec">Показать все</a></nobr>
</td>
<td></td><td style="padding:0 0 0 20px">
<img src="'.tpl::_ax(tpl::_export('','index'),array('tpl_elements','shortspec___index')).'/img/novinki.gif"></td><td style="vertical-align:middle;"><nobr>&nbsp;&nbsp;&nbsp;\\&nbsp;&nbsp;
<a style="color:#EC4700" href="'.tpl::_ax(tpl::_export('','index'),array('tpl_elements','shortspec___index')).'/?do=menu&id=novinki">Показать все</a></nobr>
</td>
<td></td></table>';
}

function specpredl(&$par){		
		return '<table style="margin-top:40px;" ><tr><td style="padding-right:30px;padding-bottom:20px;">
<img src="'.tpl::_ax(tpl::_export('','index'),array('tpl_elements','specpredl___index')).'/img/specpredl.gif"></td>
<td>
<table><tr><td class="round white size11"><a href="'.tpl::_ax(tpl::_export('','index'),array('tpl_elements','specpredl___index')).'/?}do=menu&id=spec">все спецпредложения</a></td><td class="rback"></td></tr></table>
</td></tr></table>
<div class="long"    style="height:2px;background:url(img/1x1.gif) top repeat-x"></div>
<table class="long"><tr>
		'.tpl::_a($par['row'],array('tpl_elements','specpredl_row')).'
</tr></table>';
}

function specpredl_row(&$par){		
		return '<td style="padding:30px 20px 0 0;">
		<table class="link tahoma ctext size11">
			<tr>
				<td rowspan=3 class="align_center" style="padding:0 20px 5px 0;">
				<img onload="checkImg(this,110,125)" src="'.tpl::_d($par['pic_small'],'uploaded/pict.jpg').'"
				'.tpl::_b($par['nobigpic'],'','').(isset($par['pic_big'])?$par['pic_big']:'').tpl::_b($par['nobigpic'],'','\')"').'
				>
				</td><td style="padding-bottom:6px;" colspan=2><a style="line-height:15px;" class="blue size12" href="'.tpl::_ax(tpl::_export('','index'),array('tpl_elements','specpredl_row___index')).'/?do=katalog&item='.(isset($par['id'])?$par['id']:'').'">'.(isset($par['descr'])?$par['descr']:'').'</a></td></tr>
			<tr><td  colspan=2>артикул '.(isset($par['articul'])?$par['articul']:'').'</td></tr>
			'.tpl::_a($par['xright'],array('tpl_elements','specpredl_row_xright')).'
			<tr><td  colspan=2 style="height:15px;"></td></tr>
		</table></td>
		'.tpl::_b($par['break'],'</tr><tr>','');
}

function specpredl_row_xright(&$par){		
		return '<tr>
			<th style="vertical-align:bottom;"><div class="button2 size12"><span>'.(isset($par['cost'])?$par['cost']:'').'</span></div></th>
			<td style="padding:0 0 5px 10px;vertical-align:bottom;">'.tpl::_d($par['cost2'],'руб.').'/'.(isset($par['edism'])?$par['edism']:'').'</td>
			</tr>';
}

function basket_btn(&$par){		
		return '<div style="padding:10px 60px;" class="size12 menu">
Сейчас в корзине '.tpl::_d($par['pos'],'нет').' '.tpl::_d($par['posx'],'товаров.').' на сумму '.(isset($par['cost'])?$par['cost']:'').' р.
</div>
<div style="padding-left:60px;outline:none;" class="link tahoma menu">Для оформления перейдите по ссылке:
<ul class="link tahoma menu ">
	<li><a class="" href="'.tpl::_ax(tpl::_export('','curl','do','id'),array('tpl_elements','basket_btn___curl_do_id')).'do=order">Оформить заказ</a></li>
</ul></div>';
}

function basket_top(&$par){		
		return '<b>'.tpl::_d($par['pos'],'нет').'</b> '.tpl::_d($par['posx'],'товаров').' в корзине';
}

function field_kvit(&$par){		
		return '<form name="field_kvit" method="POST" action="">
<div class="align_center" style="padding:40px 0;">
<table style="width:400px;" class="tahoma size12">
<tr class="even">
<td class="align_right text" width="30%"><b>Ф. И. О. *</b></td><td colspan=2><input  name="cust_FIO" class="long input" type="text"></td></tr><tr class="even">
<td class="align_right text" width="30%"><b>Адрес *</b></td><td colspan=2><textarea name="cust_ADDRESS" style="height:60px;" rows=5 cols=40 class="long input" ></textarea></td></tr><tr class="even">
<td class="align_right text" width="30%"><b>Телефон *</b></td><td colspan=2><input name="cust_PHONE" class="long input" type="text"></td></tr><tr class="even">
<td class="align_right text" width="30%"><b>Е-майл *</b></td><td colspan=2><input  name="cust_EMAIL" class="long input" type="text"></td>
</tr><tr class="even"><td colspan=3>&nbsp;</td>
</tr><tr class="even">
<td></td><td class="button"><input type="submit" value="Отправить"></td><td  width="36%"></td>
</tr>
</table>
</div>
</form>';
}

function field_bnal(&$par){		
		return '<form name="field_bnal" method="POST" action="">
<div class="align_center" style="padding:40px 0;">
<table style="width:600px;" class="tahoma  size12">
<tr class="even">
<td class="align_right text" width="30%"><b>Название организации *</b></td><td colspan=2><input  name="cust_ORGANISATION" class="long input" type="text"></td></tr><tr class="even">
<td class="align_right text" width="30%"><b>Контактное лицо *</b></td><td colspan=2><input  name="cust_FIO" class="long input" type="text"></td></tr><tr class="even">
<td class="align_right text" width="30%"><b>Юр. адрес *</b></td><td colspan=2><textarea name="cust_ADDRESS" style="height:60px;" rows=5 cols=40 class="long input" ></textarea></td></tr><tr class="even">
<td class="align_right text" width="30%"><b>Телефон *</b></td><td colspan=2><input name="cust_PHONE" class="long input" type="text"></td></tr><tr class="even">
<td class="align_right text" width="30%"><b>Е-майл *</b></td><td colspan=2><input  name="cust_EMAIL" class="long input" type="text"></td></tr><tr class="even">
<td class="align_right text" width="30%"><b>ИНН *</b></td><td colspan=2><input  name="cust_INN" class="long input" type="text"></td></tr><tr class="even">
<td class="align_right text" width="30%"><b>КПП *</b></td><td colspan=2><input  name="cust_KPP" class="long input" type="text"></td></tr><tr class="even">
<td class="align_right text" width="30%"><b>Название банка *</b></td><td colspan=2><input  name="cust_BANK" class="long input" type="text"></td></tr><tr class="even">
<td class="align_right text" width="30%"><b>ИНН банка *</b></td><td colspan=2><input  name="cust_BANK_INN" class="long input" type="text"></td></tr><tr class="even">
<td class="align_right text" width="30%"><b>КПП банка *</b></td><td colspan=2><input  name="cust_BANK_KPP" class="long input" type="text"></td></tr><tr class="even">
<td class="align_right text" width="30%"><b>БИК банка *</b></td><td colspan=2><input  name="cust_BANK_BIK" class="long input" type="text"></td></tr><tr class="even">
<td class="align_right text" width="30%"><b>№ счета *</b></td><td colspan=2><input  name="cust_SCHET" class="long input" type="text"></td></tr><tr class="even">
<td class="align_right text" width="30%"><nobr><b>№ корреспондентского счета *</b></nobr></td><td colspan=2><input  name="cust_CORSCHET" class="long input" type="text"></td></tr><tr class="even">
</tr><tr class="even"><td colspan=3>&nbsp;</td>
</tr><tr class="even">
<td></td><td class="button"><input type="submit" value="Отправить"></td><td  width="36%"></td>
</tr>
</table>
</div>
</form>';
}

function searchres(&$par){		
		return (isset($par['pages'])?$par['pages']:'').'
'.tpl::_a($par['list'],array('tpl_elements','searchres_list')).'
'.(isset($par['pages'])?$par['pages']:'');
}

function searchres_list(&$par){		
		return '<div class="tahoma blue">'.(isset($par['page'])?$par['page']:'').'</div>
'.tpl::_a($par['items'],array('tpl_elements','searchres_list_items'));
}

function searchres_list_items(&$par){		
		return '<div style="padding: 0 0px 0 50px;" class="tahoma size11">'.(isset($par['item'])?$par['item']:'').'</div>';
}

function charact(&$par){		
		return '<table class="long"><tr><td>
'.tpl::_a($par['first'],array('tpl_elements','charact_first')).'</td></tr><tr><td style="padding-left:35px;">
'.(isset($par['list'])?$par['list']:'').'

</td></tr>
<tr>
<td class="size12 link">
<div style="width:300px;position:relative;top:-40px; left:300px;"><img src="'.tpl::_ax(tpl::_export('','index'),array('tpl_elements','charact___index')).'/img/arr_red_lf.gif"> <a class="blue " href="'.tpl::_ax(tpl::_export('','index'),array('tpl_elements','charact___index')).'/?do=katalog&item='.(isset($par['item'])?$par['item']:'').'">Вернуться в раздел</a></div>
		  </td>
		</tr></table>';
}

function charact_first(&$par){		
		return '<div style="padding:35px 0;">
<table class="tahoma" ><tr><td style="height:2px;background:url(img/1x3.gif) 0 0 repeat-x"></td>
<td rowspan=3 style="padding:10px 0px 0px 40px">'.(isset($par['descr2'])?$par['descr2']:'').'</td>
</tr>
<tr><td><a href=\'#\' onclick="WindowO(this,\''.(isset($par['pic_big'])?$par['pic_big']:'').'\'); return false;">
<img style="border:0;" height="160px" src="'.tpl::_d($par['pic_big'],'uploaded/pict.jpg').'">
</a></td></tr>
<tr><td style="height:7px;background:url(img/1x3.gif) 0 bottom repeat-x"></td></tr>
</table></div>';
}

function table_parts(&$par){		
		return tpl::_a($par['th_nopage'],array('tpl_elements','table_parts_th_nopage')).'

'.tpl::_a($par['th_left'],array('tpl_elements','table_parts_th_left')).'

'.tpl::_a($par['th_cost'],array('tpl_elements','table_parts_th_cost')).'


'.tpl::_a($par['td_img'],array('tpl_elements','table_parts_td_img')).'
'.tpl::_a($par['td_imgxx'],array('tpl_elements','table_parts_td_imgxx')).'

'.tpl::_a($par['td_border'],array('tpl_elements','table_parts_td_border')).'
'.tpl::_a($par['td_borderxx'],array('tpl_elements','table_parts_td_borderxx')).'

'.tpl::_a($par['td_descr'],array('tpl_elements','table_parts_td_descr')).'
'.tpl::_a($par['td_xsumm'],array('tpl_elements','table_parts_td_xsumm')).'

'.tpl::_a($par['td_cost_red'],array('tpl_elements','table_parts_td_cost_red')).'
'.tpl::_a($par['td_cost_redxx'],array('tpl_elements','table_parts_td_cost_redxx')).'
'.tpl::_a($par['td_cost'],array('tpl_elements','table_parts_td_cost')).'

'.tpl::_a($par['td_cost_red'],array('tpl_elements','table_parts_td_cost_red')).'
'.tpl::_a($par['td_cost_redxx'],array('tpl_elements','table_parts_td_cost_redxx')).'


'.tpl::_a($par['td_border_red'],array('tpl_elements','table_parts_td_border_red')).'
	
'.tpl::_a($par['td_item'],array('tpl_elements','table_parts_td_item')).'	
'.tpl::_a($par['td_itemxx'],array('tpl_elements','table_parts_td_itemxx')).'	
'.tpl::_a($par['td_item_long'],array('tpl_elements','table_parts_td_item_long')).'	
'.tpl::_a($par['td_item_longxx'],array('tpl_elements','table_parts_td_item_longxx')).'	

'.tpl::_a($par['td_sign'],array('tpl_elements','table_parts_td_sign')).'

'.tpl::_a($par['td_signxx'],array('tpl_elements','table_parts_td_signxx')).'

'.tpl::_a($par['td_input'],array('tpl_elements','table_parts_td_input')).'
'.tpl::_a($par['td_inputxx'],array('tpl_elements','table_parts_td_inputxx')).'
'.tpl::_a($par['td_xinput'],array('tpl_elements','table_parts_td_xinput')).'
'.tpl::_a($par['td_xinputxx'],array('tpl_elements','table_parts_td_xinputxx'));
}

function table_parts_th_nopage(&$par){		
		return '<th class="nopage" style="text-align:center;background-image:none;">
&nbsp;'.(isset($par['name'])?$par['name']:'').'&nbsp;</th>';
}

function table_parts_th_left(&$par){		
		return '<th  style="background-image:none;">
&nbsp;'.(isset($par['name'])?$par['name']:'').'&nbsp;</th>';
}

function table_parts_th_cost(&$par){		
		return '<th style="font-weight:normal;background-image:none;" class="align_center text" >
	<b>'.(isset($par['name'])?$par['name']:'').',<br>руб.</b></th>';
}

function table_parts_td_img(&$par){		
		return '<td  style="height:45px;">
<div style="position:relative;"><div style="position:absolute;top:-7px;height:59px;width:65px;">
	<img 
	style="border-left:1px solid #e5e5e3; border-right:1px solid #e5e5e3; "
	onload="checkImg(this,55,59);" height="59px" '.tpl::_b($par['nobigpic'],'','class="hand" onclick="WindowO(this,\'').(isset($par['pic_big'])?$par['pic_big']:'').tpl::_b($par['nobigpic'],'','\')"').' 
	src="'.tpl::_d($par['pic_small'],'uploaded/pict.jpg').'">
	</div></div></td>';
}

function table_parts_td_imgxx(&$par){		
		return '<td style="height:45px;" ><img 
	style="border:1px solid #e5e5e3; margin:0 5px;"
	onload="checkImg(this,45,45);" height="45px" '.tpl::_b($par['nobigpic'],'','class="hand" onclick="WindowO(this,\'').(isset($par['pic_big'])?$par['pic_big']:'').tpl::_b($par['nobigpic'],'','\')"').' 
	src="'.tpl::_d($par['pic_small'],'uploaded/pict.jpg').'"></td>';
}

function table_parts_td_border(&$par){		
		return '<td class="border text">
	'.(isset($par['text'])?$par['text']:'').'</td>';
}

function table_parts_td_borderxx(&$par){		
		return '<td class="border text">
	'.(isset($par['text'])?$par['text']:'').'</td>';
}

function table_parts_td_descr(&$par){		
		return '<td class="border text">'.(isset($par['text'])?$par['text']:'').'</td>';
}

function table_parts_td_xsumm(&$par){		
		return '<td class="border text">
	'.(isset($par['xcost'])?$par['xcost']:'').'</td>';
}

function table_parts_td_cost_red(&$par){		
		return '<td  class="align_right size14 menu border text red" title="'.(isset($par['sdescr2'])?$par['sdescr2']:'').'">
	'.(isset($par['text'])?$par['text']:'').'
	</td>';
}

function table_parts_td_cost_redxx(&$par){		
		return '<td  class="align_right size14 menu border text red" title="'.(isset($par['sdescr2'])?$par['sdescr2']:'').'">
	
	</td>';
}

function table_parts_td_cost(&$par){		
		return '<td class="text '.tpl::_d($par['last'],'border').'">
	'.(isset($par['text'])?$par['text']:'').'</td>';
}

function table_parts_td_border_red(&$par){		
		return '<td style="background-color:#fffde6;" class="size11 border text" title="'.(isset($par['sdescr2'])?$par['sdescr2']:'').'">
	'.(isset($par['text'])?$par['text']:'').'
	</td>';
}

function table_parts_td_item(&$par){		
		return '<td  class="text align_right" style="padding:0 10px 0 30px;" >
	'.(isset($par['text'])?$par['text']:'').'
	</td>';
}

function table_parts_td_itemxx(&$par){		
		return '<td  class="text" >
	'.(isset($par['text'])?$par['text']:'').'
	</td>';
}

function table_parts_td_item_long(&$par){		
		return '<td  class="long border text" title="'.(isset($par['sdescr2'])?$par['sdescr2']:'').'">
	'.(isset($par['text'])?$par['text']:'').'
	</td>';
}

function table_parts_td_item_longxx(&$par){		
		return '<td  class="long border text" title="'.(isset($par['sdescr2'])?$par['sdescr2']:'').'">

	<a href="'.tpl::_ax(tpl::_export('','index'),array('tpl_elements','table_parts_td_item_longxx___index')).'/?id='.(isset($par['cat'])?$par['cat']:'').'&do=ch&item='.(isset($par['id'])?$par['id']:'').'">'.(isset($par['text'])?$par['text']:'').'</a>
	</td>';
}

function table_parts_td_sign(&$par){		
		return '<td class="align_center size16 border red" style="vertical-align:middle;padding:0 8px;width:30px;">'.tpl::_b($par['sign'],'+','-').'</td>';
}

function table_parts_td_signxx(&$par){		
		return '<td class="align_center size16 border red" style="vertical-align:middle;padding:0 8px;width:30px;"></td>';
}

function table_parts_td_input(&$par){		
		return '<td class="align_right" width="80px" style="vertical-align:middle;padding:0 10px 0 0px;">
		<input style="width:50px;"  name="item_'.(isset($par['id'])?$par['id']:'').'" value="'.(isset($par['value'])?$par['value']:'').'" class="input" type="text" ></td>';
}

function table_parts_td_inputxx(&$par){		
		return '<td class="align_right" width="80px" style="vertical-align:middle;padding:0 10px 0 0px;">
		</td>';
}

function table_parts_td_xinput(&$par){		
		return '<td class="align_right" width="80px" style="vertical-align:middle;padding:0 10px 0 0px;">
		<input style="width:50px;"  name="xitem_'.(isset($par['xid'])?$par['xid']:'').'" value="'.(isset($par['value'])?$par['value']:'').'" class="input" type="text" ></td>';
}

function table_parts_td_xinputxx(&$par){		
		return '<td class="align_right" width="80px" style="vertical-align:middle;padding:0 10px 0 0px;">
		</td>';
}

function catalogue(&$par){		
		return '<form name="basket" method="POST">
	<table class="long table size12 ctext align_left">
	<tr><td >
	<table class="size11 long">
	<tr>
		<td class="align_right" style="vertical-align:middle;padding-right:40px;"><nobr><u>Кол-во на странице</u>&nbsp;&nbsp;&nbsp;&nbsp;
		<select name="perpage" style="border:1px solid #dddddd;">
			<option value="10">10</option><option value="20">20</option>
			<option value="40">40</option><option value="60">60</option>
			<option value="100">100</option><option value="200">200</option><option value="500">500</option></select>
		<input type="submit" style="background:url(img/btn_red.gif); border:0;width:13px; height:21px;" value="&nbsp;" src="'.tpl::_ax(tpl::_export('','index'),array('tpl_elements','catalogue___index')).'/img/button3.gif">
	</td></tr>
	<tr><td style="height:15px;"></td></tr>
	<tr><td style="padding-left:100px;">
	'.(isset($par['pages'])?$par['pages']:'').'
	</td>
	</tr>
	</table>
	</td></tr>
	'.tpl::_a($par['_table'],array('tpl_elements','catalogue__table')).'
	<tr><td  height=20px></td></tr>
	<tr><td  >
	<table class="long">
		<tr><td style="padding-left:100px;">
	'.(isset($par['pages'])?$par['pages']:'').'
	</td>
	</tr>

	<tr><td class="align_right">
		<table><tr>
		<td class="round white size11" style="vertical-align:middle;"><input type="submit"
		    name="clear_bsk"
			style="border:0; background:transparent;"
			class="white size11"
			onmouseover="this.style.textDecoration=\'underline\';"
			onmouseout="this.style.textDecoration=\'none\';"
		value="&nbsp;Очистить&nbsp;"></td><td class="rback"></td>
		<td style="width:30px;"></td>
		<td class="round white size11" style="vertical-align:middle;"><input type="submit"
			style="border:0; background:transparent;"
			class="white size11"
			onmouseover="this.style.textDecoration=\'underline\';"
			onmouseout="this.style.textDecoration=\'none\';"
		value="'.tpl::_d($par['button_val'],'Добавить в корзину').'"></td><td class="rback"></td></tr></table>

	</td></tr></table>
	</td></tr>
		</table>
	</form>
	<script type="text/javascript">
	var x = document.forms[\'basket\'][\'perpage\'];
	if(x){
	var i =x.options.length;
	while(i--){
		if(x.options[i].value=='.(isset($par['perpage'])?$par['perpage']:'').')
			x.selectedIndex=i
	}}
	//document.forms[\'basket\'][\'perpage\'].value='.(isset($par['perpage'])?$par['perpage']:'').';
	</script>';
}

function catalogue__table(&$par){		
		return '<tr><td><table class="long size12">
	<tr class="size11">'.(isset($par['headers'])?$par['headers']:'').'
	</tr>
<tr><td style="height:8px;" colspan="'.tpl::_d($par['colspan'],13).'"></td></tr>
	
<tr class="odd"><td style="border-bottom:1px solid #dddddd;height:1px;" colspan="'.tpl::_d($par['colspan'],13).'"></td></tr>
	'.tpl::_a($par['data'],array('tpl_elements','catalogue__table_data')).'
	'.tpl::_a($par['subtitle'],array('tpl_elements','catalogue__table_subtitle')).'
	<tr><td colspan='.tpl::_d($par['colspan'],13).' height=20px></td></tr>
	</table>
	</td></tr>';
}

function catalogue__table_data(&$par){		
		return '<tr class="'.tpl::_b($par['even'],'even','odd').'" ><td style="height:11px;" colspan="'.tpl::_d($par['colspan'],13).'"><div style="overflow:hidden;height:100%;">
	<a name="item_'.(isset($par['id'])?$par['id']:'').'">&nbsp;</a></div>
	</td></tr>
		<tr id="xx_'.(isset($par['id'])?$par['id']:'').'" class="'.tpl::_b($par['even'],'even','odd').'">'.(isset($par['data'])?$par['data']:'').'
		</tr>
		<tr class="'.tpl::_b($par['even'],'even','odd').'"><td style="border-bottom:1px solid #dddddd;height:10px;" colspan="'.tpl::_d($par['colspan'],13).'"></td></tr>';
}

function catalogue__table_subtitle(&$par){		
		return '<tr><td colspan="'.tpl::_d($par['colspan'],13).'" class="menu size12" style="padding-left:30px;">
	<div class="long" style="background-image:none;height:16px;">
	<div CLASS=\'align_right\' style="padding-right:30px;background-color:#fffde6;height:16px;width:150px;float:right">'.(isset($par['value'])?$par['value']:'').'</div>
	'.(isset($par['title'])?$par['title']:'').'
	</div>
	</td></tr>';
}

function xcatalogue(&$par){		
		return '<form method="POST"><div class="para">
<table class="size12 ctext align_left">
'.tpl::_a($par['_table'],array('tpl_elements','xcatalogue__table')).'
</table></div></form>';
}

function xcatalogue__table(&$par){		
		return tpl::_a($par['data'],array('tpl_elements','xcatalogue__table_data'));
}

function xcatalogue__table_data(&$par){		
		return '<tr>
<td class="menu">'.(isset($par['name'])?$par['name']:'').'</td>
</tr>
<tr>
<td>
<table>
<tr class="ajaxform">
<td >
	<input type="hidden" name="id_'.(isset($par['id'])?$par['id']:'').'" value="'.(isset($par['id'])?$par['id']:'').'">
	
<div>'.tpl::_ax(tpl::_export('','tpl','admin','imgpre'),array('tpl_elements','xcatalogue__table_data___tpl_admin_imgpre')).'<a rel="g'.(isset($par['pid'])?$par['pid']:'').'" href="'.tpl::_d($par['xhref'],'#').'" '.tpl::_format(pps($par['comment']),' title="%s"').'>
					<img src="'.(isset($par['small_pict'])?$par['small_pict']:'').'" alt="'.tpl::_html(pps($par['comment'])).'"></a>'.tpl::_ax(tpl::_export('','tpl','admin','imgpost'),array('tpl_elements','xcatalogue__table_data___tpl_admin_imgpost')).'</div>
</td>
<td class="menu">'.(isset($par['descr'])?$par['descr']:'').'</td>
</tr>
</table>
</td>
</tr>

'.tpl::_a($par['s_data'],array('tpl_elements','xcatalogue__table_data_s_data')).'

<tr><td><table>
<tr>
<td><a href="javascript:dop_har('.(isset($par['id'])?$par['id']:'').');">'.tpl::_b($par['subkat_s'],'Доп. характеристики...','').'</a></td>
<td><a href="'.(isset($par['url'])?$par['url']:'').'/'.(isset($par['the_href'])?$par['the_href']:'').'">'.tpl::_b($par['show_podr'],'Подробнее...','').'</a></td>
</tr>
</table></td></tr>

<tr><td><table>

'.tpl::_a($par['subkat_h'],array('tpl_elements','xcatalogue__table_data_subkat_h')).'

'.tpl::_a($par['subkat'],array('tpl_elements','xcatalogue__table_data_subkat')).'

</table></td></tr>';
}

function xcatalogue__table_data_s_data(&$par){		
		return '<tr><td><table>
<tr class="ajaxform">
<td></td>
<td>'.(isset($par['cost'])?$par['cost']:'').'</td>
<td>'.(isset($par['ostatok'])?$par['ostatok']:'').'</td>
<td><input type="hidden" name="id_'.(isset($par['id'])?$par['id']:'').'" value="'.(isset($par['id'])?$par['id']:'').'"><input type="text" style="width:45px;" name="item_'.(isset($par['id'])?$par['id']:'').'" value="'.(isset($par['value'])?$par['value']:'').'"></td>
<td><div><a href=\'#\' class="submit blue">в&nbsp;корзину</a></div></td>
</tr>
</table></td></tr>';
}

function xcatalogue__table_data_subkat_h(&$par){		
		return '<tr class="dop_har dop_har_'.(isset($par['id'])?$par['id']:'').'">
<td>Тип</td>
<td>цена, руб.</td>
<td>Наличие на складе</td>
<td>Кол-во</td>
<td>&nbsp;</td>
</tr>';
}

function xcatalogue__table_data_subkat(&$par){		
		return '<tr class="ajaxform dop_har dop_har_'.(isset($par['parent_id'])?$par['parent_id']:'').'">
<td>'.(isset($par['name'])?$par['name']:'').'</td>
<td>'.(isset($par['cost'])?$par['cost']:'').'</td>
<td>'.(isset($par['ostatok'])?$par['ostatok']:'').'</td>
<td><input type="hidden" name="id_'.(isset($par['id'])?$par['id']:'').'" value="'.(isset($par['id'])?$par['id']:'').'"><input type="text" style="width:45px;" name="item_'.(isset($par['id'])?$par['id']:'').'" value="'.(isset($par['value'])?$par['value']:'').'"></td>
<td><div><a href=\'#\' class="submit blue">в&nbsp;корзину</a></div></td>
</tr>';
}

function xxcatalogue(&$par){		
		return '<form method="POST"><div class="spec"><div class="c1"><div class="c2"><div class="c3">
'.tpl::_a($par['_table'],array('tpl_elements','xxcatalogue__table')).'
</div></div></div></div></form>';
}

function xxcatalogue__table(&$par){		
		return tpl::_a($par['data'],array('tpl_elements','xxcatalogue__table_data'));
}

function xxcatalogue__table_data(&$par){		
		return '<table style="margin:0 20px;" class="ajaxform size12 ctext align_left">
<tr >
<td style="padding:10px 0 0 0;">
	<input type="hidden" name="id_'.(isset($par['id'])?$par['id']:'').'" value="'.(isset($par['id'])?$par['id']:'').'">
	
<div>'.tpl::_ax(tpl::_export('','tpl','admin','imgpre'),array('tpl_elements','xxcatalogue__table_data___tpl_admin_imgpre')).'<a rel="g'.(isset($par['pid'])?$par['pid']:'').'" href="'.tpl::_d($par['xhref'],'#').'" '.tpl::_format(pps($par['comment']),' title="%s"').'>
					<img src="'.(isset($par['small_pict'])?$par['small_pict']:'').'" alt="'.tpl::_html(pps($par['comment'])).'"></a>'.tpl::_ax(tpl::_export('','tpl','admin','imgpost'),array('tpl_elements','xxcatalogue__table_data___tpl_admin_imgpost')).'</div>
</td></tr><tr>
<td style="padding:10px 0 0 20px;"><table  class="fixed c2text size11"><col width=145px><col width=auto><tr>
	<td class="menu">'.(isset($par['name'])?$par['name']:'').'</td></tr>
	<tr><td class="menu">'.(isset($par['descr'])?$par['descr']:'').'</td></tr>
	<tr><td class="menu red">'.(isset($par['cost'])?$par['cost']:'').' руб.</td></tr>

<tr><td colspan=2 style="line-height:16px;padding:12px 0 0 0 ;">'.(isset($par['sdescr'])?$par['sdescr']:'').'
'.tpl::_a($par['add'],array('tpl_elements','xxcatalogue__table_data_add')).'
</td></tr>
<tr><td >
<table class="pad5 size11"><tr>
	<td ><div><a href=\'#\' class="submit blue">в&nbsp;корзину</a></div></td>
	<td ><div><a href=\'#\' class="submit"><img src="img/basket.gif" alt=\'\'></a></div></td></tr></table>
</td></tr>
'.tpl::_a($par['info'],array('tpl_elements','xxcatalogue__table_data_info')).'
</table></td></tr>

<tr><td colspan=3 style="height:30px;"></td></tr>
</table>';
}

function xxcatalogue__table_data_add(&$par){		
		return '<br><a class="url_page" href="'.tpl::_ax(tpl::_export('','curl','do','id'),array('tpl_elements','xxcatalogue__table_data_add___curl_do_id')).'do=page&id='.(isset($par['descr7'])?$par['descr7']:'').'"><span class="back hidden">Скрыть </span>подробнее...</a>';
}

function xxcatalogue__table_data_info(&$par){		
		return '<tr><td class="menu"><a href="'.(isset($par['the_href'])?$par['the_href']:'').'">подробнее</a></td></tr>';
}

function katalog_back(&$par){		
		return '<p>'.(isset($par['name'])?$par['name']:'').'</p>
<p><a href="'.(isset($par['addr'])?$par['addr']:'').'">&lt;&lt; Назад</a></p>';
}

function katalog_back_bt(&$par){		
		return '<p><a href="'.(isset($par['addr'])?$par['addr']:'').'">&lt;&lt; Назад</a></p>';
}}
?>