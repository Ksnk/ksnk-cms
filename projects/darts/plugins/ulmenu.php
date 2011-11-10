<style>
/* разметка некоторых участков шаблона */
<% point_start('css_site');

// меню 2-х уровневое, элементы разделяются палочками. 
// внизу строчка меню отчеркнута линией 
// ... некоторые цвета...

//$border_color = '';
$ulmenu_border_color='rgb(222,225,227)';//rgb(165,154,125)';
$ulmenu_text_color='rgb(99,111,125)';
$menu_height=44;//32;
$menu_padding='12px';
$ulmenu_hover_color = 'black';
/**
 *  пример вставки на страничку
 *
<div class="ulmenuContainer" STYLE="margin-bottom:32px;">
<ul class="ulmenu">{::menu:top}</ul>	
</div>
*/

/*
function translit($s)
{
	$s=urldecode($s);
	$s=str_replace('"',"&quot;",$s); // сохраняем кавычки
	
	$s= strtr(strtoupper($s),array(
                  "ЫА"=>"yha",
                  "ЫО"=>"yho",
                  "ЫУ"=>"yhu",
                  "Ё"=>"yo",
                  "Ж"=>"zh"));
	$s = strtr($s, "АБВГДЕЗИЙКЛМНОПРСТУФХЦ"
				 , "abvgdezijklmnoprstufxc");
	$s= strtr($s,array( 
                  "Ч"=>"ch",
                  "Ш"=>"sh",
                  "Щ"=>"shh",
                  "Ъ"=>"qh",
                  "Ы"=>"y",
                  "Ь"=>"q",
                  "Э"=>"eh",
                  "Ю"=>"yu",
                  "Я"=>"ya",
                  " "=>"_",
 				  "№"=>"n",
				  '"'=>"&quot;"
	)) ;
	$s= strtr($s,array(
		'&'=>'',
		'#'=>'',
		';'=>'',
		'*'=>'',
		'?'=>''
	)); 
	for($i=0;$i<strlen($s);$i++){
		if($s{$i}>"\x80")$s{$i}='_';
	}
	
    return strtolower($s);              
}

$x=glob('img/рус/*.gif');
foreach($x as $f){
	copy($f,'img/'.translit(basename($f)));
}
*/
 %>
 
div.ulmenuContainer {
	height:<%=$menu_height%>px;
	background-color:white;
	border-bottom:1px solid <%=$ulmenu_border_color%>;
	z-index:1000;
} 
 
ul.ulmenu {
	font-family: arial, sans-serif;  
	font-weight:bold;
	z-index:100;
	padding:0; margin:0; 
	list-style: none;
}

ul.ulmenu ul{
	list-style: none;
	z-index:1000;
}

ul.ulmenu li {
	float:left; position:relative;
	display:block;
	padding:0 <%=$menu_padding%>;
	height:<%=$menu_height%>px; 
	background: url(<%=$target_dir%>/img/menubg.gif) 100% 16px no-repeat;
}

ul.ulmenu>li.last {
	background:none;
}

ul.ulmenu li a {
	display:block ; /*float:left;*/
	text-decoration:none;
	padding:11px 0;
	cursor:pointer;	
	font-size:12px; line-height:20px; 
	color:<%=$ulmenu_hover_color%>;
}
ul.ulmenu li a img {
	margin:8px 0 4px 0;
}

ul.ulmenu li a:hover, ul.ulmenu li a.current {
	background:url(<%=$target_dir%>/img/regbg.gif) 0 <%=$menu_height%>px repeat-x;
}

ul.ulmenu li ul {
  background:rgb(235,238,242);
	padding:8px 8px 20px 8px;
	position:absolute;
	top:<%=$menu_height-6%>px; left:0;
	margin:6px 0 0 0;
	width:auto;
	display: none;
}
ul.ulmenu li ul li{
	float:none;
	display:block;
	height:auto; 
	background: url(<%=$target_dir%>/img/arr_gray.gif) 6px 14px no-repeat;
}

ul.ulmenu li li.first a {
	border-top:0;
}
ul.ulmenu li li a {
	float:none; display:block;
	border-right:none;
	border-top:1px solid <%=$ulmenu_border_color%>;
	white-space:nowrap;
	color:<%=$ulmenu_text_color%>;
	text-decoration:none;
	font-size:12px;
	line-height:20px; 
	margin: 0 11px;
	padding:4px 2px 4px 2px ;
	cursor:pointer;	
}
ul.ulmenu li ul li.level2 a{
	font-size:11px;
	line-height:16px; 
	padding:4px 2px 4px 22px 
}
ul.ulmenu li ul li.level3 a{
	font-size:10px;
	line-height:14px; 
	padding:4px 2px 4px 32px 
}
ul.ulmenu li li a:hover, ul.ulmenu li li a.current {
	background:transparent;
	color: <%=$ulmenu_hover_color%>;
}
ul.ulmenu li ul li ul{top:5px;left:100%; }

<% point_finish('css_site') %>
</style>
<script>
<% point_start('js_main');
include_once($env_common.'/common/js/site.src/menu.js');
include($env_common.'/common/js/site.src/ulmenu.js');

%>
$("ul.ulmenu li>a").hover(function(){
	$(this).css({backgroundPosition:'0px <%=$menu_height%>px'}).animate({backgroundPosition:'0px <%=$menu_height-4%>px'});
},function(){
	$(this).stop(true, true).animate({backgroundPosition:'0px <%=$menu_height%>px'});
})
//<% point_finish(); %>
</script>