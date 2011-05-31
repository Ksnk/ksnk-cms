<!DOCTYPE html public "-//W3C//DTD HTML 4.01 Transitional//EN"
            "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>{title|Айрис}</title>
<meta http-equiv="content-type"
	content="text/html; charset=windows-1251">
<META name="description" content="{desc_words}">
<META name="keywords" lang="ru" content="{key_words}">
<script type="text/javascript" src="js/jquery.pack.js"> </script>
<script type="text/javascript" src="js/site.js"> </script>
<LINK  rel="stylesheet" type="text/css" href="airis.css">

<link rel="icon" href="{::index}/favicon.ico" type="image/x-icon">
<link rel="shortcut icon" href="{::index}/favicon.ico" >

</head>
<body class="tahoma ctext">
<!--  ////////////////// Основная страница /////////////////////// -->

<table class="wide long" style="background:url(img/rbot.bg.jpg) no-repeat 0px bottom ;">
	<tr>
		<td colspan=2 style="height:139px;background:url(img/hat.bg.jpg) repeat-x 4px 0px;">
		<table class="fixed size12">
		<col width="342px"><col width='auto'>
		<col width="128px">
		<col width="220px"><col width='auto'>
		<col width="90px">
		<col width="140px"><col width='auto'>
		<tr>
		<td style="background:url(img/airis_lk.jpg) no-repeat">
		<div style="position:relative;z-index:10;">
		<table style="width:150px;position:absolute;top:90px;right:0px;" class="size11"><tr>
		<th><a href="{::index}/" alt="На главную" title="На главную"><img style="border:0;" src="img/home_w.gif"></a></th><th>&nbsp;</th>
		<th><a href="{::curl:do}do=sitemap" alt="Карта сайта" title="Карта сайта"><img style="border:0;" src="img/sitemap_w.gif"></a></th><th>&nbsp;</th>
		<th><a href="{::curl:do}do=writeus" alt="Обратная связь" title="Написать нам"><img style="border:0;" src="img/writeus_w.gif"></a></th>
		</table></div>
<div class="wide" style="position:relative;">
		<a href="{::index}/"><div style="cursor:pointer;position:absolute;width:100px;height:100px;top:10px; left:25px;" >&nbsp;</div></a>
		</div>		</td><td style="background:url(img/airis_ck.jpg) repeat-x;">
		</td>
		<td style="background:url(img/airis_rk.gif) no-repeat; "><div style="position:relative;">
			<a href="?id=4&amp;do=writeus"><img alt="" style="position:absolute;border:0; top:67px; left:55px;" src="img/hotline.jpg"></a>
		</div></td>
		<td align="right" style="padding:0 0 0 10px;">
					<form name="login" method="POST">
		<table style="table-layout:fixed;" class="long size11">
		<col width="auto">
		<col width="50px">
		<tr><td colspan=2
			style="padding:25px 20px 0 0;background:url(img/authorisation.gif) no-repeat 0px 5px;">
			<p>{userhello|для оптовых покупателей}</p></td></tr>
		<tr><td style="height:30px;padding-right:10px;"><input class="input long" name="login_name" type="text"></td>
		<td></td></tr>
		<tr><td style="height:30px;padding-right:10px;"><input class="input long" name="login_pass" type="password"></td>
		<td><input type="submit" style="border:0;padding:0;width:13px; height:24px; background:url(img/button2.gif)" value="&nbsp;"></td></tr>
		<tr><td colspan=2 class="cltext link"><table class="long size11"><tr><td>
		<a href="{::curl:do}do=writeus">забыли пароль?</a>
		</td><td colspan=2 class="cltext link">
		<a href="{::curl:do}do=onlinereg">регистрация</a>
		</td></tr></table></td></tr>
		</table>
		</form>

		</td><td></td>
		{::menu:has:basket??<td></td><td></td><td></td>::
		<td style="border:0px;"><div style="position:relative;"><a href="?do=menu&id=basket" style="border:0px;"><img alt='' style="position:absolute;border:0; top:67px; left:20px;" src="img/basket.jpg"></a></div></td>
			<td style="padding:38px 20px 0 10px;background:url(img/yourbasket.gif) no-repeat 13px 13px;">
			}
			{::menu:basket}
		{::menu:has:basket??::
			<br><br><br><nobr class="red link" >
				<a href="?do=menu&id=basket">
				оформить заказ</a>&nbsp;<img alt="" src="img/arr_gray.gif"></nobr>
		</td>
		<td></td>
		}
		</tr>
		</table>
		</td>
	</tr>
	<tr><td style="height:45px;vertical-align:middle;padding: 0 20px 0 20px;" colspan=2 >
	<table class="long link size12" >
		<!--begin:::menu:head:1-->
		<td style="width:auto;padding:0 20px 0 0;"><a class="{current}" href="{url|#}">{item}</a></td>
		<!--end:::menu:head:1-->
	<td class="align_right" style="width:auto;height:45px;padding: 15px 40px 0 40px;">
		<form name="search_form" method="POST" action="?do=search">
		<table class="tahoma " style="width:180px;">
		<tr>
		<td width="80%" style="padding-right:10px;">
		<input name="search_string" class="input long" type="text"></td>
		<td width="25px" class="button"><input type="submit" name="search" value="-&gt;"></td>
		</tr>
		<tr>
		<td colspan=2 class="link cltext"></td>
		</tr>
		</table>
		</form></td></tr></table>
		</td>
	</tr><tr>
		<td style="width:230px;padding:0 10px 0 20px;">
	<div style="width:200px;overflow:hidden;">
		{::menu:left}
	<div style="padding-top:35px;">
		{votes::vote}
		</div>
	<div  style="padding-top:35px;">
	{::tpl:admin:counters}
	</div>	
	</div>
		</td>
		<td
		{::menu:has:right?colspan=2}
		style="width:100%;overflow:hidden;padding:0 40px 0 20px;">
		{runningline::get_runningline}
		<div id="nav_bar">
		{::_first}
		</div>
		<div id='main_context' style="padding:0 0 60px 0;">
		{data}
		</div>
		{::menu:spec}
		</td>
	</tr>
	<tr>
		<td></td><td style="padding-top:40px;height:123px;" >
		<table class="link size11"><tr>
		<!--begin:::menu:head-->
		<td style="padding:0 10px 0 10px;{first?:background:url(img/1x1.gif) repeat-y;}">
		<a class="{current}" href="{url|#}">{item}</a><td>
		{break?</tr></table><table class="size11 link"><tr>}
		<!--end:::menu:head-->
		</tr></table>
		
		</td>
	</tr><tr>
		<td></td>
		<td   height="130px">
		<table class="fixed link">
			<col width="auto">
			<col width="240px">
			<col width="28px">
			<tr>
				<td class="size11 link"
					style="background:url(img/1x3.gif) repeat-x;padding:25px 0 0 10px;">
				&copy;2003-2008. OOO &laquo;АЙРИС&raquo;. Все права зарезервированы.</td>
				<td style="padding:12px 0 0 3px;">
				<a style="border:0" href="http://xilen.ru"><img style="border:0;" src="img/xilenru.gif" alt="xilen.ru"></a><br>
				<a class="size11" href="http://www.xilen.spb.ru/portfolio.php">Cоздание сайтов</a>

				</td>
				<td >&nbsp;</td>
			</tr>
		</table>
		</td>
	</tr>
</table>
<div id="debug"></div>
</body>
<!-- {pagegen} -->
</html>

