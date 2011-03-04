<style>
/* горизональное меню admin - шаблона */
<% point_start('css_admin');

// горизонтальное меню. 
// ... некоторые цвета...

$hmenu_text_color = 'rgb(37,37,37)';
$hmenu_curent_color='green';

/**
 *  пример вставки на страничку
 *
<ul class="hmenu" style="width:230px;">
<li class="expanded">{::_menu}</li>
</ul>
*/
 %>
 
/********************************************************************
 * tree menu - обычное раздвигающееся по +- меню на UL-LI
 * ******************************************************************
 */
ul.hmenu li {
	background: none;
	display:block;
	margin:0;
	padding:0px 0 2px 24px;
}
ul.hmenu li li{
	background: transparent url(../img/arr_red.gif) no-repeat scroll left 5pt;
	padding:0 0 0px 16px;
	font-size:11px;
	line-height:16px;
}

ul.hmenu{
  height:100%;
  overflow:auto;
}
ul.hmenu li {
	list-style-image:none;
	list-style-position:outside;
	list-style-type:none;
}

ul.hmenu li a, ul.hmenu li span {
 	background:url(../img/xbg.gif);
	display:block;
	padding:10px 0 0 10px;
	height:32px;
	color:white;
	font-size:16px;
	font-weight:bold;
	
}
ul.hmenu li span {
	text-decoration:underline;
	cursor:pointer;
}

ul.hmenu li.expanded, ul.hmenu li.collapsed {
	background:transparent url(../img/menu1.gif) no-repeat left -60px;
}

ul.hmenu li.collapsed {
	background-position:0 0;
}


ul.hmenu a.current, ul.hmenu span.current {
	color:<%=$hmenu_curent_color%>;
}

ul.hmenu {
	padding-left:0;
}
ul.hmenu ul {
	padding:10px 0 12px 0px;
}
ul.hmenu ul ul{
	padding:4px 0 ;
}
/* expanded */
ul.hmenu ul li a, ul.hmenu ul li span {
 	background:none;
 	padding:0;
	display:inline;
	color:<%=$hmenu_text_color%>;
	font-size:12px;
}
ul.hmenu ul li.green a {
	color:rgb(177,223,1);
}
ul.hmenu ul ul li a, ul.hmenu ul ul li span {
 	font-weight:normal;
	font-size:11px;
	
}

ul.hmenu ul li.expanded, ul.hmenu ul li.collapsed {
	background:url(../img/plusminus_green.gif) 0 -25px no-repeat;
}

ul.hmenu ul li.collapsed {
	background-position:0 5px;
}

<% point_finish() %>
</style>
<script>
<% 
point_start('js_admin'); 
%>
$('ul.hmenu li').not('.current').find('ul').hide().parent().addClass('collapsed');
$('ul.hmenu li.current').parents('li').addClass('current');
$('ul.hmenu li.current, li.expanded').children('ul').show().parent().removeClass('collapsed').addClass('expanded');
$('ul.hmenu').click(function(e){
	var el=e.target || e.srcElement;
	//try{
	while(el && (!el.tagName ||
		(el.tagName.toLowerCase()!='li') && el.tagName.toLowerCase()!='a'))
		el=el.parentNode;
	if(!el || el.tagName && el.tagName.toLowerCase()=="a") return ;
	if($(el).hasClass('collapsed'))
	{
		$(el).removeClass('collapsed').addClass('expanded').children('ul').show();
	}
	else if($(el).hasClass('expanded'))
	{
		$(el).removeClass('expanded').addClass('collapsed').children('ul').hide();
	}
	//}//catch(e){;}
});

<% point_finish(); %>
</script>