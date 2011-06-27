
<!--begin:anchor-->
<a name="anc_{xitem_name}"></a>
<!--begin:header-->	
<table style="width:100%;"><tr><td><div class="header align_{align|left}">
<h1>{header}</h1>
</div></td></tr></table>	
<!--end:header-->
<!--end:anchor-->

<!--begin:flash-->	
<OBJECT classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0"
width="{width}" height="{height}">
<PARAM NAME=movie VALUE="{::index}/img/{swf}.swf">
<PARAM NAME=quality VALUE=high> <PARAM NAME=bgcolor VALUE=#FFFFFF>
<PARAM NAME=wmode VALUE=transparent>
<EMBED src="{::index}/img/{swf}.swf"
quality=high bgcolor=#FFFFFF
width="{width}" height="{height}" wmode="transparent"
NAME="mtown" ALIGN="" TYPE="application/x-shockwave-flash"
PLUGINSPAGE="http://www.macromedia.com/go/getflashplayer">
</EMBED>
</OBJECT>
<!--end:flash-->	
<!--  ////////////////// вывод сгенерированной статьи /////////////////////// -->

<!--begin:edit_table-->
<table style="width:100%;margin-top:20px; margin-bottom:20px;">
<tr><td style="padding-top:1px; padding-bottom:1px" class="align_{align|center}">
<table class="table tablex" style="border-top:3px solid #e3e6ea; border-bottom:3px solid #e3e6ea; border-left:none; border-right:none;">
{cols}
<tr>{header}</tr>
<!--begin:rows-->
<tr class="{class}">{row}</tr>
<!--begin:opisanie-->
<tr class="{class}"><td colspan="{colnumber}" style="padding:0;border-right:none;border-left:none;"><div id="opisanie_{id}" style="display:none;padding:5px;">{opisanie_text}</div></td></tr>
<!--end:opisanie-->
<!--end:rows-->
</table>
</td></tr></table>
<!--end:edit_table-->
<table><tr>
<!--begin:edit_row-->
<!--begin:cols-->
	<{td}{colspan>>format>> colspan="%s"}{rowspan>>format>> rowspan="%s"} style="{width}" class="{class22}">{text|&nbsp;}</{td}>
<!--end:cols-->
<!--end:edit_row-->
</tr></table>
<!--begin:katalogx-->
<table class="align_{align|center}" style="width:100%;border-spacing:0;"><tr><td>
<table  style="border-spacing:0;">

	<!--begin:upload-->
	<tr>
	<!--begin:link-->
		<td style='padding:0 10px;' class="tahoma ctext link align_left">
		<img alt="" src='{::index}/img/li_red.gif'>&nbsp;&nbsp;<a href="{url}" {target}>{text|xxx}</a>
		</td>
	<!--end:link-->
	</tr>
	<!--end:upload-->

	<!--begin:href-->
	<tr>
	<!--begin:link-->
		<td style='padding:5px 10px;' class="tahoma ctext link align_left">
		<a {target} href="{url}" class="strelka">{text|xxx}</a>
		<span id="opisanie_btn_{id}" class="skryt"><a href="javascript:hide_opisanie('{id}');">(Скрыть)</a></span>
		<div id="opisanie_{id}" style="display:none;">{opisanie|}</div>
		</td>
	<!--end:link-->
	</tr>
	<!--end:href-->
</table>
</td></tr>
</table>
<!--end:katalogx-->

<!--begin:katalog-->
<table style="border-spacing:0;width: 100%; margin: 0px 0 10px 0;">



	<!--begin:text-->
		<tr>
			<td class="text tahoma bgray">
				<!--begin:pic-->
					<div class="float_{align?right:left}"><a href="{the_href}" onclick="return WindowO(this,'{pict2}',{bwidth|100},{bheight|100})"><img alt="{comment}" title="{comment}" style="border:0px" src="{pict1}"></a></div>
				<!--end:pic-->
				<p>{the_text}</p>
			</td>
		</tr>
	<!--end:text-->


	<!--begin:textpic-->
		<tr>
			<td class="text textpict tahoma">

				<!--begin:row-->
        			<div class="float_{align}  gallery" >
						<!--begin:pict-->
							{::tpl:admin:imgpre}
                            
                            	
    							<table class="box" ><tr><td class="picture_box">
										 <a rel="g{pid}" href="{xhref|#}" {comment>>format>> title="%s"}><img class="text_picture"  src="{small_pict}" alt="{comment>>html}" {border>>brd}></a>
										</td></tr>
								</table>
	
                            <p style="text-align: center;margin-bottom:16px;">{comment>>html}</p>{::tpl:admin:imgpost}
						<!--end:pict-->
                   	</div>
				<!--end:row-->
                
			<div style="padding-right:20px;">{the_text}</div>
        
				<!--begin:articles-->
					<!--begin:links-->
                    <br><a title="{item_text>>striptags}" href="javascript:show_opisanie('{id}');" class="link1">{item_text>>striptags}</a>
                    <span id="opisanie_btn_{id}" class="skryt"><a href="javascript:hide_opisanie('{id}');">(Скрыть)</a></span>
					<div id="opisanie_{id}" style="display:none;">{opisanie}</div>
					<!--end:links-->
				<!--end:articles-->		
        	</td>
		</tr>
	<!--end:textpic-->


	<!--begin:header-->
		<tr>
			<td style="font-size:18px;" class="header tahoma ctext size12  align_{align}">
				<div><b>{the_header}</b></div>
			</td>
		</tr>
	<!--end:header-->

	<!--begin:upload-->
		<tr>
			<td class="tahoma ctext link align_{align}">
				<img alt="" src='{::index}/img/li_red.gif'>&nbsp;&nbsp;<a href="{::index}/uploaded/{the_upload}">{the_text}</a>
			</td>
		</tr>
	<!--end:upload-->

	<!--begin:href-->
		<tr>
			<td class="tahoma ctext link align_{align}">
				<img alt="" src='{::index}/img/li_red.gif'>&nbsp;&nbsp;<a href="{the_href}">{the_text}</a>
			</td>
		</tr>
	<!--end:href-->

	<!--begin:table-->
		<tr>
            <td class="table align_{align}">
				<table class="tahoma bgray size11 {border| border}">{table}</table>
			</td>
		</tr>
	<!--end:table-->

	<!--begin:catalogue-->
		<tr><td>
				<form name="basket" method="POST">
				<table class="long table size12 ctext align_left"><tr><td colspan="{::rightb:1?8:4}">
					<table class="size11 long">
						<tr>
							<td class="align_right" style="vertical-align:middle;padding-right:40px;">
                            	<nobr><u>Кол-во на странице</u>&nbsp;&nbsp;&nbsp;&nbsp;
								<select name="perpage" style="border:1px solid #dddddd;">
								<option value="10">10</option><option value="20">20</option>
								<option value="40">40</option><option value="60">60</option>
								<option value="100">100</option><option value="200">200</option><option value="500">500</option></select>
								<input type="submit" style="background:url(img/btn_red.gif); border:0;width:13px; height:21px;" value="&nbsp;" src="{::index}/img/button3.gif">
								</nobr>
							</td></tr>
							<tr><td style="height:15px;"></td></tr>
							<tr><td style="padding-left:100px;">{pages}</td></tr>
					</table>
				</td></tr>
				<tr>
					<th style="height:40px;text-align:center;" class="nopage">фото</th><th>Артикул</th><th>Наименование</th>
					<th style="text-align:center;" class="nopage">Ед.изм.</th><th style="padding:0;width:2px;">&nbsp;</th>
					{::rightb:1:1?<th>Цена,<br>&nbsp;руб.</th><th class="nopage" width="30px">Наличие&nbsp;</th><th class="nopage" width="80px">&nbsp;Корзина&nbsp;</th>}
				</tr>
				<tr class="odd"><td style="border-bottom:1px solid #dddddd;height:1px;" colspan={::rightb:1?8:4}></td></tr>
	<!--begin:data-->
		<tr class="{even?even:odd}"><td style="height:11px;" colspan={::rightb:1?8:4}></td></tr>
		<tr id="xx_{id}" class="{even?even:odd}">
		<td class="">
		<div style="position:relative;"><div style="position:absolute;top:-10px;left:-5px;height:65px;width:65px;">
		<img onload="checkImg(this,45,45);" height="45px" {nobigpic?:class="hand" onclick="WindowO(this,'}{pic_big}{nobigpic?:')"} src="{pic_small|uploaded/pict.jpg}">
		</div></div></td>
		<td class="border text">
		{articul}</td>
		<td class="long border text" title="{sdescr2}">
		<!--begin:xx-->
		<a href="{::index}/?do=ch&item={id}">
		<!--end:xx-->
		{descr}
		<!--begin:xx-->
		</a>
		<!--end:xx-->
		</td>
		<td class="text"><a name="item_{id}"></a>
		{edism}</td>
		<!--begin:xright-->
		<td class="text border" style="width:2px;padding-left:0;padding-right:0"></td>
		<td style="font-size:14" class="red menu  text border">{cost}</td>
		<td class="align_center red size16" style="vertical-align:middle;padding:0 8px;width:30px;"><b>{sign?+:-}</b></td>
		<td class="align_right" width="80px" style="vertical-align:middle;padding:0 10px 0 0px;">
			<input style="width:50px;" {disabled} name="item_{id}" value="{value}" class="input" type="text" ></td>
		<!--end:xright-->
		</tr>
		<tr class="{even?even:odd}"><td style="border-bottom:1px solid #dddddd;height:10px;" colspan={::rightb:1?8:4}></td></tr>
	<!--end:data-->
		<tr><td colspan={::rightb:1?8:4} height=20px></td></tr>
		<tr><td  colspan={::rightb:1?8:4}>
		<table class="long">
		<tr><td style="padding-left:100px;">
		{pages}
		</td>
		</tr>

        <tr><td class="align_right">
		<table {::rightb:1:4?:style="display:none"}><tr>
		<td class="round white size11" style="vertical-align:middle;"><input type="submit"
		    name="clear_bsk"
			style="border:0; background:transparent;"
			class="white size11"
			onmouseover="this.style.textDecoration='underline';"
			onmouseout="this.style.textDecoration='none';"
		value="Очистить"></td><td class="rback"></td>
		<td style="width:30px;"></td>
		<td class="round white size11" style="vertical-align:middle;"><input type="submit"
			style="border:0; background:transparent;"
			class="white size11"
			onmouseover="this.style.textDecoration='underline';"
			onmouseout="this.style.textDecoration='none';"
		value="{button_val|Добавить в корзину}"></td><td class="rback"></td>
		
		</tr></table>

	</td></tr></table>
	</td></tr>
		</table>
	</form>
	<script type="text/javascript">
	var x = document.forms['basket']['perpage'];
	if(x){
	var i =x.options.length;
	while(i--){
		if(x.options[i].value=={perpage})
			x.selectedIndex=i
	}}
	//document.forms['basket']['perpage'].value={perpage};
	</script>
	</td></tr>
	<!--end:catalogue-->

<!--begin:gallery-->
	<tr>
		<td class="box gallery tahoma ctext size11" >
		<input type="hidden" id="w_{id}" name="w_{id}" value="{w_width}">
		<input type="hidden" id="h_{id}" name="h_{id}" value="{w_height}">
		<div class="align_{align}">
		<div class="wideable scroll_gal">	
		<!--begin:row-->
		<table class="align_center">
			<tr>
			<!--begin:pict-->
				<td  style="vertical-align:bottom;padding:0 25px 5px 0;">
                

                
				{::tpl:admin:imgpre}<table>
                						<tr><td class="picture_box"><a rel="g{pid}" href="{xhref|#}" {comment>>format>> title="%s"}><img src="{::index}/{small_pict}" alt="{comment>>html}" {border>>brd} class="text_picture"></a></td></tr>
                                    </table>
				</td>	
	 		<!--end:pict-->
			</tr>
			<tr>
			<!--begin:pictcom-->
				<td style="vertical-align:top;padding:0 25px 20px 0;">
					<p style="text-align: center;">{comment>>html}</p>{::tpl:admin:imgpost}	
				</td>	
	 		<!--end:pictcom-->
			</tr>		</table>
		<!--end:row--></div>
		</div>
		</td>
	</tr>
	<!--end:gallery-->	
    <!--begin:galleryX-->
	<tr>
    	<td class="gallery">
        
        <script type="text/javascript"> 

$(function(){
    //Get our elements for faster access and set overlay width
    var div = $('div.sc_menu{pid}'),
                 ul = $('ul.sc_menu{pid}'),
                 // unordered list's left margin
                 ulPadding = 15;

    //Get menu width
    var divWidth = div.width();

    //Remove scrollbars
    div.css({overflow: 'hidden'});

    //Find last image container
    var lastLi = ul.find('li:last-child');

    //When user move mouse over menu
    div.mousemove(function(e){

      //As images are loaded ul width increases,
      //so we recalculate it each time
      var ulWidth = lastLi[0].offsetLeft + lastLi.outerWidth() + ulPadding;

      var left = (e.pageX - div.offset().left) * (ulWidth-divWidth) / divWidth;
      div.scrollLeft(left);
    });
});

		</script>
      
			<div id="sc_menu_id" class="sc_menu{pid}" style="{rp_sheight} {float_p}">
			<ul class="sc_menu{pid}" style="{rp_sheight}">
		<!--begin:row-->

			<!--begin:pict-->
            	<li>{::tpl:admin:imgpre}<a rel="g{gid}" href="{xhref|#}" {comment>>format>> title="%s"}>
 				     <div><img src="{::index}/{small_pict}" alt="{comment>>html}"></div>{::tpl:admin:imgpost}
					 <span style="{swidth_b}">{comment}</span>
			    </a></li>
	 		<!--end:pict-->

		<!--end:row-->
        		</div>
			</ul>
        </div> 
		
        </td>
	</tr>
	<!--end:galleryX-->
</table>
<!--end:katalog-->

<!--begin:katalog_searchres-->
<a href="{::index}/?do=katalog&item={id}">
<span class="red">{articul}</span>
&nbsp;&nbsp;{descr}
</a>
<!--end:katalog_searchres-->

<!--begin:pages-->
<table class="link tahoma "><tr>
<td class="size12 " style="padding:9px 25px 9px 9px;">Страницы</td>
<!--begin:mmin-->
<td style="padding:9px;">
	<a href="{::curl:pg}pg=0"><img style="border:0;" src="img/arr_blue_left.gif"></a>
</td>
<!--end:mmin-->
<!--begin:min-->
<td style="padding:5px;width:20px;">
	<a class="blue link" href="{::curl:pg}pg={m5}">предыдущая</a>
</td>
<!--end:min-->
<!--begin:page-->
<!--begin:link-->
<td class=" align_center align_middle" style="padding: 5px;width:25px;">
	<a class="yellow" href="{::curl:pg}pg={url}">{txt}</a>
</td>
<!--end:link-->
<!--begin:txt-->
<td class="align_center align_middle" style="padding: 5px;width:25px;background:url(img/round.gif) 2px 2px no-repeat;">
	{txt}
</td>

<!--end:txt-->
<!--end:page-->
<!--begin:max-->
<td style="padding:5px;width:20px;">
	<a class="blue link" href="{::curl:pg}pg={m5}">следующая</a>
</td>
<!--end:max-->
<!--begin:mmax-->
<td style="padding:9px;">
	<a href="{::curl:pg}pg={m5}"><img style="border:0;" src="img/arr_blue_right.gif"></a>
</td>
<!--end:mmax-->
</tr></table>
<!--end:pages-->
<!-- ////////////////// Шаблон - sitemap /////////////////////// -->

<!--begin:subcat-->
<div class="article menu red tahoma size11" ><span>ПОДРАЗДЕЛЫ</span></div>
<ul class="menu ctext tahoma size11">{data}</ul>
<!--end:subcat-->

<!--begin:sitemap-->
<ul class="oglav">{data}</ul>
<!--end:sitemap-->

<!--begin:leftmenu-->
<div class="blue" style="width:220px;margin-bottom:25px;">
<ul id="menu_left1" style="clear:both;">{data}</ul>
</div>
<!--end:leftmenu-->

<!--begin:topmenu-->
<table style="margin-left:auto;margin-right:auto;"><tr><td>
<ul class="topmenu link ctext tahoma">
<li {class} style="border-left:none;"><a {class} href="{::index}/">Главная</a></li>
{data}
<li {class}>
	<form name="search_form" method="POST" action="?do=search" style="margin:1px 0 0 30px;">
		<table style="vertical-align:middle;">
			<tr>
				<td><input id="search_string" name="search_string" title="Поиск!" type="text" value="Найти..." onClick="poisk()" style="width:93px;height:15px;border:solid 1px #b8bdc9;margin-right:4px;font-size:11px;padding-left:6px;"/></td>
				<td><input type="submit" value="" style="background:url(img/button2.gif) no-repeat center center #FFFFFF;border:none;width:12px;height:19px;"/></td>
			</tr>
		</table>
	</form>
</li></ul>
</td></tr></table>
<!--end:topmenu-->

<!--begin:ermess-->
<div style="padding-top:60px;" class='red'>
<p><b>Страница, которую вы запросили, отсутствует на сайте</b></p>
</div>

<!--end:ermess-->

<!--begin:nfirst-->
<div class="nfirst1">
<a href="{::index}/">Главная</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;
<!--begin:list-->
<a href="{::curl:do:id:topic}do=menu&id={id}">{name}</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
<!--end:list-->
</div>
<!--end:nfirst-->

<!--begin:nfirst2-->
<div class="nfirst2">
<h1>{sub}</h1>
</div>
<!--end:nfirst2-->

<!--begin:ajax-->{data}<!--end:ajax-->
<!--begin:vote-->
<form method="POST" name="vote" id="vote" action="">
<input type="hidden" name="votes_identifier" value="oops!">
<div id="votes">
<table class="size11 ctext">
<tr><td style="height:80px;"><img src="{::index}/img/votes.gif"></td></tr>
<tr><td style="padding:0 10px 0 25px;height:40px;">{question}</td></tr>
<!--begin:list-->
<tr><td style="padding-left:25px;vertical-align:top;height:30px;" class="checkbox">
<label><input style="margin:0;border:0;" type="checkbox" name="aaa" value="{id}">{asc}</label></td></tr>
<!--end:list-->
</table>
<table style="margin:30px 0 0 25px"><tr><td class="round white size11" style="vertical-align:middle;"><input type="submit"
	style="border:0; background:transparent;"
	class="white size11"
	onmouseover="this.style.textDecoration='underline';"
	onmouseout="this.style.textDecoration='none';"
value="выбрать"></td><td class="rback"></td></tr></table>
</div></form>
<!--end:vote-->

<!--begin:running_line-->
<div style="height:20pt;position:relative;overflow:hidden;">
		<div style="width:100%;position:absolute; clip:auto;overflow:hidden;">
		<table class="menu red size16" id="running_line"><tr><td nowrap>
		</td></tr></table></div></div>
<script type="text/javascript">
// running line
$(function(){
	var e =document.getElementById('running_line');
	var disp=5,x=0,prev,
		lines=[''<!--begin:data-->,'{descr}'<!--end:data-->];
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
			$(e).find('td').html(prev);
		}
		e.style.marginLeft=(-x)+'px';
		e.style.marginRight=x+'px';
	},100);
})
</script>
<!--end:running_line-->
<!--begin:novinki-->
<div style="padding-right:40px">
<div style="padding:0 0 8px 0;"><img src="{::index}/img/novinki.gif"></div>
		<!--begin:row-->
		<table style="background:url(img/1x3.gif) bottom repeat-x"
			class="link tahoma ctext size11">
			<tr>
				<td colspan=2 class="align_left" style="padding:30px 0 10px 0;" >
				<img onload="checkImg(this,140,140)"  src="{pic_small|uploaded/pict.jpg}"
				{nobigpic??::class="hand" onclick="WindowO(this,'}{pic_big}{nobigpic??::')"}
				>
				</td></tr>
			<tr><td  colspan=2  style="padding-bottom:6px;"><a style="line-height:15px;" class="blue size12" href="{::index}/?do=katalog&item={id}">{descr}</a></td></tr>
			<tr><td  colspan=2>артикул {articul}</td></tr>
			<tr><td style="height:10px;"></td></tr>
			<!--begin:xright-->
			<tr><th class="button2">
				<input type="submit"  style="vertical-align:bottom;" value="{cost}">
				</th><td style="vertical-align:bottom;padding:0 0 5px 10px;">
				{cost2|руб.}/{edism}
			</td></tr>
			<!--end:xright-->
			<tr><td style="height:15px;"></td></tr>
		</table>
		<!--end:row-->
		<table style="margin:30px 0 0 25px"><tr><td class="round white size11" style="vertical-align:middle;">
<a href="{::index}/?do=menu&id=novinki" type="button">Новинки</a>
</td><td class="rback"></td></tr></table></div>
<!--end:novinki-->
<!--begin:shortspec-->
<table style="table-layout:fixed;" class="long tahoma link">
<col width="40px"><col width="210px"><col width="130px">
<col width="auto"><col width="120px"><col width="130px"><col width="40px">
<tr>
<td style="width:10%"></td>
<td colspan=2 style="padding-bottom:20px;border-top:1px #dddddd solid;">
<img src="{::index}/img/hxx1.gif">
</td>
<td style="width:100%"></td>
<td colspan=2 style="padding-bottom:20px;border-top:1px #dddddd solid;">
<img src="{::index}/img/hxx2.gif">
</td>
<td style="width:10%"></td>
</tr>
<tr>
<td></td><td style="padding:0 0 0 20px">
<img src="{::index}/img/specpredl.gif"></td><td style="vertical-align:middle;"><nobr>&nbsp;&nbsp;&nbsp;\&nbsp;&nbsp;
<a style="color:#EC4700" href="{::index}/?do=menu&id=spec">Показать все</a></nobr>
</td>
<td></td><td style="padding:0 0 0 20px">
<img src="{::index}/img/novinki.gif"></td><td style="vertical-align:middle;"><nobr>&nbsp;&nbsp;&nbsp;\&nbsp;&nbsp;
<a style="color:#EC4700" href="{::index}/?do=menu&id=novinki">Показать все</a></nobr>
</td>
<td></td></table>
<!--end:shortspec-->

<!--begin:specpredl-->
<table style="margin-top:40px;" ><tr><td style="padding-right:30px;padding-bottom:20px;">
<img src="{::index}/img/specpredl.gif"></td>
<td>
<table><tr><td class="round white size11"><a href="{::index}/?}do=menu&id=spec">все спецпредложения</a></td><td class="rback"></td></tr></table>
</td></tr></table>
<div class="long"    style="height:2px;background:url(img/1x1.gif) top repeat-x"></div>
<table class="long"><tr>
		<!--begin:row-->
		<td style="padding:30px 20px 0 0;">
		<table class="link tahoma ctext size11">
			<tr>
				<td rowspan=3 class="align_center" style="padding:0 20px 5px 0;">
				<img onload="checkImg(this,110,125)" src="{pic_small|uploaded/pict.jpg}"
				{nobigpic?:class="hand" onclick="WindowO(this,'}{pic_big}{nobigpic??::')"}
				>
				</td><td style="padding-bottom:6px;" colspan=2><a style="line-height:15px;" class="blue size12" href="{::index}/?do=katalog&item={id}">{descr}</a></td></tr>
			<tr><td  colspan=2>артикул {articul}</td></tr>
			<!--begin:xright-->
			<tr>
			<th style="vertical-align:bottom;"><div class="button2 size12"><span>{cost}</span></div></th>
			<td style="padding:0 0 5px 10px;vertical-align:bottom;">{cost2|руб.}/{edism}</td>
			</tr>
			<!--end:xright-->
			<tr><td  colspan=2 style="height:15px;"></td></tr>
		</table></td>
		{break??</tr><tr>}
		<!--end:row-->
</tr></table>
<!--end:specpredl-->

<!--begin:basket_top-->
<nobr><span class="red"><b>{pos|нет}</b></span> {posx|товаров.}<br>
Сумма <span class="red"><b>{cost}</b></span> руб.</nobr>
<!--end:basket_top-->

<!--begin:field_kvit-->
<form name="field_kvit" method="POST" action="">
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
</form>
<!--end:field_kvit-->
<!--begin:field_bnal-->
<form name="field_bnal" method="POST" action="">
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
</form>
<!--end:field_bnal-->
<!--begin:searchres-->
{pages}
<!--begin:list-->
<div class="tahoma blue">{page}</div>
<!--begin:items-->
<div style="padding: 0 0px 0 50px;" class="tahoma size11">{item}</div>
<!--end:items-->
<!--end:list-->
{pages}
<!--end:searchres-->
<!--begin:charact-->
<table class="long"><tr><td>
<!--begin:first-->
<div style="padding:35px 0;">
<table class="tahoma" ><tr><td style="height:2px;background:url(img/1x3.gif) 0 0 repeat-x"></td>
<td rowspan=3 style="padding:10px 0px 0px 40px">{descr2}</td>
</tr>
<tr><td><a href='#' onclick="WindowO(this,'{pic_big}'); return false;">
<img style="border:0;" height="160px" src="{pic_big|uploaded/pict.jpg}">
</a></td></tr>
<tr><td style="height:7px;background:url(img/1x3.gif) 0 bottom repeat-x"></td></tr>
</table></div>
<!--end:first--></td></tr><tr><td style="padding-left:35px;">
{list}

</td></tr>
<tr>
<td class="size12 link">
<div style="width:300px;position:relative;top:-40px; left:300px;"><img src="{::index}/img/arr_red_lf.gif"> <a class="blue " href="{::index}/?do=katalog&item={item}">Вернуться в раздел</a></div>
		  </td>
		</tr></table>

<!--end:charact-->
Элемент - запчасти для таблицы
<!--begin:table_parts-->

<!--begin:th_nopage-->
<th style="text-align:left;">
{name}</th>
<!--end:th_nopage-->

<!--begin:th_left-->
<th  style="background-image:none;">
&nbsp;{name}&nbsp;</th>
<!--end:th_left-->

<!--begin:th_cost-->
	<th style="font-weight:normal;background-image:none;" class="align_center text" >
	<b>{name},<br>руб.</b></th>
<!--end:th_cost-->


<!--begin:td_img-->
<td  style="height:45px;">
<div style="position:relative;"><div style="position:absolute;top:-7px;height:59px;width:65px;">
	<img 
	style="border-left:1px solid #e5e5e3; border-right:1px solid #e5e5e3; "
	onload="checkImg(this,55,59);" height="59px" {nobigpic??::class="hand" onclick="WindowO(this,'}{pic_big}{nobigpic??::')"} 
	src="{pic_small|uploaded/pict.jpg}">
	</div></div></td>
<!--end:td_img-->
<!--begin:td_imgxx-->
<td style="height:45px;" ><img 
	style="border:1px solid #e5e5e3; margin:0 5px;"
	onload="checkImg(this,45,45);" height="45px" {nobigpic??::class="hand" onclick="WindowO(this,'}{pic_big}{nobigpic??::')"} 
	src="{pic_small|uploaded/pict.jpg}"></td>
<!--end:td_imgxx-->

<!--begin:td_border-->
	<td class="border text">
	{text}</td>
<!--end:td_border-->
<!--begin:td_borderxx-->
	<td class="border text">
	{text}</td>
<!--end:td_borderxx-->

<!--begin:td_descr-->
<td class="border text">{text}</td>
<!--end:td_descr-->
<!--begin:td_xsumm-->
<td class="border text">
	{xcost}</td>
<!--end:td_xsumm-->

<!--begin:td_cost_red-->
<td class="border text" title="{sdescr2}">
	{text}
	</td><!--end:td_cost_red-->
<!--begin:td_cost_redxx-->
<td class="border text" title="{sdescr2}">
	{text}
	</td><!--end:td_cost_redxx-->
<!--begin:td_cost-->
	<td class="text {last|border}">
	{text}</td>
<!--end:td_cost-->

<!--begin:td_cost_red-->
<td  class="align_right size14 menu border text red" title="{sdescr2}">
	{text}
	</td><!--end:td_cost_red-->
<!--begin:td_cost_redxx-->
<td  class="align_right size14 menu border text red" title="{sdescr2}">
	
	</td><!--end:td_cost_redxx-->


<!--begin:td_border_red-->
<td style="background-color:#fffde6;" class="size11 border text" title="{sdescr2}">
	{text}
	</td><!--end:td_border_red-->
	
<!--begin:td_item-->
<td  class="text align_right" style="padding:0 10px 0 30px;" >
	{text}
	</td><!--end:td_item-->	
<!--begin:td_itemxx-->
<td  class="text" >
	{text}
	</td><!--end:td_itemxx-->	
<!--begin:td_item_long-->
<td  class="long border text" title="{sdescr2}">
	{text}
	</td><!--end:td_item_long-->	
<!--begin:td_item_longxx-->
<td  class="long border text" title="{sdescr2}">

	<a href="{::index}/?id={cat}&do=ch&item={id}">{text}</a>
	</td><!--end:td_item_longxx-->	

<!--begin:td_sign-->
<td class="align_center size16 border red" style="vertical-align:middle;padding:0 8px;width:30px;">{sign??+::-}</td>
<!--end:td_sign-->

<!--begin:td_signxx-->
<td class="align_center size16 border red" style="vertical-align:middle;padding:0 8px;width:30px;"></td>
<!--end:td_signxx-->

<!--begin:td_input-->
<td class="align_right" style="vertical-align:middle; text-align:left;">
		<input name="item_{id}" value="{value}" style="border:1px solid #657382; background-color:#424d5b; width:60px; color:#FFFFFF" type="text"></td>
<!--end:td_input-->
<!--begin:td_inputxx-->
<td class="align_right" width="80px" style="vertical-align:middle;padding:0 10px 0 0px;">
		</td>
<!--end:td_inputxx-->
<!--begin:td_xinput-->
<td class="align_right" width="80px" style="vertical-align:middle;padding:0 10px 0 0px;">
		<input style="width:50px;"  name="xitem_{xid}" value="{value}" class="input" type="text" ></td>
<!--end:td_xinput-->
<!--begin:td_xinputxx-->
<td class="align_right" width="80px" style="vertical-align:middle;padding:0 10px 0 0px;">
		</td>
<!--end:td_xinputxx-->

<!--end:table_parts-->

<!--begin:catalogue-->
	<form name="basket" method="POST">
	<table class="long">
		<tr><td>
			<table class="long">
				<tr>
					<td class="align_right" style="vertical-align:middle;padding-right:40px; padding-bottom:10px">
                    <nobr><u>Кол-во на странице</u>&nbsp;&nbsp;&nbsp;
						<select name="perpage" style="border:1px solid #424d5b; background:#424d5b; color:#FFFFFF">
							<option value="10">10</option><option value="20">20</option>
							<option value="40">40</option><option value="60">60</option>
							<option value="100">100</option><option value="200">200</option><option value="500">500</option></select>
						<input type="submit" style="background:url(img/btn_red.gif); border:0;width:13px; height:21px;" value="&nbsp;" src="{::index}/img/button3.gif">
					</td></tr>
					<tr><td style="padding-left:100px;">
					{pages}
					</td>
				</tr>
			</table>
		</td></tr>
	<!--begin:_table-->
		<tr><td>
    <table style="width:100%; border-top:3px solid #e3e6ea; border-bottom:3px solid #e3e6ea; border-left:none; border-right:none; margin-top:0px; margin-bottom:8px;">
		<tr><td style="padding-top:1px; padding-bottom:1px; padding-left:-1px; overflow:hidden;">
		    <table class="long size12 table tablex" style="margin-left:-1px;">
				<tr class="size11">{headers}</tr>

	<!--begin:data-->
		<tr id="xx_{id}" class="{even??even::odd}">{data}</tr>
	<!--end:data-->

			</table>	
	    </td></tr>
    </table>
    
    <!--begin:subtitle-->
	<tr><td class="menu size12" style="text-align:right">
			<div class="align_right" style="padding-right:10%; float:right">{title}, руб:&nbsp;&nbsp;&nbsp;{value}</div>
	</td></tr>
	<!--end:subtitle-->
    
    
    
	</td></tr>
	<!--end:_table-->
    
	<tr><td  height=20px></td></tr>
	<tr><td  >
	<table class="long">
		<tr><td style="padding-left:100px;">
	{pages}
	</td>
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
			value="{button_val|Добавить в корзину}">
        </td></tr></table>

	</td></tr></table>
	</td></tr>
		</table>
	</form>
	<script type="text/javascript">
	var x = document.forms['basket']['perpage'];
	if(x){
	var i =x.options.length;
	while(i--){
		if(x.options[i].value=={perpage})
			x.selectedIndex=i
	}}
	//document.forms['basket']['perpage'].value={perpage};
	</script>
<!--end:catalogue-->



<!--begin:xcatalogue-->
<form method="POST">
	<div class="para">
		<table width="100%" class="katalog_sales align_left">
<!--begin:_table-->
<!--begin:data-->
			<tr>
				<td>
					<table class="box">
                    	<tr><td>&nbsp;</td><td width="100%" class="name">{name}</td></tr>
                        <tr style="height:18px"><td>&nbsp;</td><td>&nbsp;</td></tr>
						<tr class="ajaxform">
							<td class="gallery">
								<input type="hidden" name="id_{id}" value="{id}">
									{::tpl:admin:imgpre}<div style="margin-right:50px;"><a rel="g{id}" href="{xhref|#}" {comment>>format>> title="%s"}>
									<img src="{::index}/{small_pict| img/empty_pic.gif}" alt="{comment>>html}" class="pic"></a></div>{::tpl:admin:imgpost}
							</td>
							<td><table><tr><td class="descr">{descr}</td></tr></table>
                            
                            <table style="margin-top:15px">
								<tr>
									<td><a href="javascript:dop_har({id});" {subkat_s??class="strelka"::}>{subkat_s??Купить...::}</a></td>
									<td><a href="{url}/{the_href}" {show_podr??class="strelka"::}>{show_podr??Описание...::}</a></td>
								</tr>
							</table>
                            </td>
						</tr>
					</table>
				</td>
			</tr>

<!--begin:s_data-->
<tr><td>
	<table class="align_{align|center}" style="width:90%; border-top:3px solid #e3e6ea; border-bottom:3px solid #e3e6ea; border-left:none; border-right:none; margin-top:20px; margin-bottom:30px;">
		<tr><td style="padding-top:1px; padding-bottom:1px">
			<table class="table tablex" style="width:100%">

				<tr>
					<th class="first_td">Наименование товара</th>
					<th>Цена, руб.</th>
					<th>Наличие на складе</th>
					<th>Кол-во</th>
					<th>&nbsp;</th>
				</tr>
				<tr class="ajaxform">
					<td class="first_td" style="padding-right:20px">{name}</td>
					<td style="color:#b41414; font-size:14px; font-weight:600; padding-right:30px; background:url({::index}/img/rubles.gif) 100% 51% no-repeat">{cost}</td>
					<td>{ostatok|нет(заказать)}</td>
					<td><input type="hidden" name="id_{id}" value="{id}"><input style="border:1px solid #e0e0e0" type="text" style="width:45px;" name="item_{id}" value="{value}"></td>
					<td style="padding-right:35px"><div><a href='#' class="submit blue"><img src="{::index}/img/basket.gif"></a></div></td>
				</tr>

			</table>
		</td></tr>
	</table>
</td></tr>
<!--end:s_data-->

</td></tr>

<tr><td style="padding-top:20px; padding-bottom:30px;">
	<table class="align_{align|center} dop_har dop_har_{id}" style="width:90%; border-top:3px solid #e3e6ea; border-bottom:3px solid #e3e6ea; border-left:none; border-right:none;">
		<tr><td style="padding-top:1px; padding-bottom:1px;">
			<table class="table tablex" style="width:100%;">

<!--begin:subkat_h-->
				<tr class="dop_har dop_har_{id}">
					<th class="first_td">Тип</th>
					<th>Цена, руб.</th>
					<th>Наличие на складе</th>
					<th>Кол-во</th>
					<th>&nbsp;</th>
				</tr>
<!--end:subkat_h-->

<!--begin:subkat-->
				<tr class="ajaxform dop_har dop_har_{parent_id}">
					<td class="first_td">{name}</td>
					<td style="color:#b41414; font-size:14px; font-weight:600; padding-right:20px; background:url({::index}/img/rubles.gif) 100% 51% no-repeat">{cost}</td>
					<td>{ostatok}</td>	
					<td><input type="hidden" name="id_{id}" value="{id}"><input style="border:1px solid #e0e0e0" type="text" style="width:45px;" name="item_{id}" value="{value}"></td>
					<td style="padding-right:35px"><div><a href='#' class="submit blue"><img src="{::index}/img/basket.gif"></a></div></td>
				</tr>
<!--end:subkat-->

			</table>
		</td></tr>
	</table>
</td></tr>

<!--end:data-->
<!--end:_table-->
</table></div></form>
<!--end:xcatalogue-->

<!--begin:xxcatalogue-->
<form method="POST"><div class="spec"><div class="c1"><div class="c2"><div class="c3">
<!--begin:_table-->
<!--begin:data-->
<table style="margin:0 20px;" class="ajaxform size12 ctext align_left">
<tr >
<td style="padding:10px 0 0 0;">
	<input type="hidden" name="id_{id}" value="{id}">
	
<div>{::tpl:admin:imgpre}<a rel="g{pid}" href="{xhref|#}" {comment>>format>> title="%s"}>
					<img src="{small_pict| 'www.xln.su/test/ezavod/img/empty_pic.'}" alt="{comment>>html}"></a>{::tpl:admin:imgpost}</div>
</td></tr><tr>
<td style="padding:10px 0 0 20px;"><table  class="fixed c2text size11"><col width=145px><col width=auto><tr>
	<td class="menu">{name}</td></tr>
	<tr><td class="menu">{descr}</td></tr>
	<tr><td class="menu red">{cost} руб.</td></tr>

<tr><td colspan=2 style="line-height:16px;padding:12px 0 0 0 ;">{sdescr}
<!--begin:add-->
<br><a class="url_page" href="{::curl:do:id}do=page&id={descr7}"><span class="back hidden">Скрыть </span>подробнее...</a>
<!--end:add-->
</td></tr>
<tr><td >
<table class="pad5 size11"><tr>
	<td ><div><a href='#' class="submit blue">в&nbsp;корзину</a></div></td>
	<td ><div><a href='#' class="submit"><img src="img/basket.gif" alt=''></a></div></td></tr></table>
</td></tr>
<!--begin:info-->
<tr><td class="menu"><a href="{the_href}">подробнее</a></td></tr>
<!--end:info-->
</table></td></tr>

<tr><td colspan=3 style="height:30px;"></td></tr>
</table><!--end:data-->
<!--end:_table-->
</div></div></div></div></form>
<!--end:xxcatalogue-->

<!--begin:katalog_back-->

<div class="name">{name}</div>
<div style="margin-top:30px; margin-bottom:20px; float:none; "><a href="{addr}">&lt;&lt; Назад</a></div>
<!--end:katalog_back-->

<!--begin:katalog_back_bt-->
<div style="margin-top:8px; float:none; "><a href="{addr}">&lt;&lt; Назад</a></div>
<!--end:katalog_back_bt-->

