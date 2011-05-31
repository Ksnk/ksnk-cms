<?php
class tpl_gb extends tpl {

function _(&$par){		
		return '<!DOCTYPE html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>'.tpl::_d($par['title'],'Театр-студия').'</title>
<meta http-equiv="content-type"
	content="text/html; charset=windows-1251">
<META name="description" content="'.(isset($par['desc_words'])?$par['desc_words']:'').'">
<META name="keywords" lang="ru" content="'.(isset($par['key_words'])?$par['key_words']:'').'">
<script type="text/javascript" src="js/jquery.pack.js"> </script>
<LINK  rel="stylesheet" type="text/css" href="common.css">
<LINK  rel="stylesheet" type="text/css" href="theater.css">

<link rel="icon" href="'.tpl::_ax(tpl::_export('','index'),array('tpl_gb','__index')).'/favicon.ico" type="image/x-icon">
<link rel="shortcut icon" href="'.tpl::_ax(tpl::_export('','index'),array('tpl_gb','__index')).'/favicon.ico" >

'.tpl::_ax(tpl::_export('','tpl','admin','script'),array('tpl_gb','__tpl_admin_script')).'

<!--[if lt IE 7]>
<![if gte IE 5.5]>
<script type="text/javascript" src="js/fixpng.js"></script>
<style type="text/css">
.iePNG { filter:expression(fixPNG(this)); }
</style>
<![endif]>
<![endif]-->

<style>
td.round {
  background:url(img/round.gif) 16px 0 no-repeat;
}
</style></head>
<body class="tahoma ctext" style="background:url(img/bg.jpg) 0 480px;">
<!--  ////////////////// Основная страница /////////////////////// -->
<div style="text-align:center;">
<table class="align_left wide long" style="margin-left:auto;
	margin-right:auto;;background:url(img/sec-top.jpg) no-repeat;">
    <tr><td><div style="width:950px;overflow:hidden;height:1px;"></div></td></tr>
	<tr><td style="height:174px;">
		<div class="long wide" style="position:relative;">
		
		<div class="long wide" style="position:absolute;top:-12px;">
		'.tpl::_ax(tpl::_export('','tpl','admin','homemenu'),array('tpl_gb','__tpl_admin_homemenu')).'
		</div>
		<div  style="position:absolute; top:0px; left:654px; 
					 width:104px;height:88px;background:url(img/list0.jpg) no-repeat;">
		</div>
		<div  style="position:absolute; top:90px; left:585px;
					 width:200px;height:17px;background:url(img/masterskaya.gif) no-repeat;">
		</div>
		<div  style="position:absolute; top:45px; left:335px;  
					 width:352px;height:36px;background:url(img/gdemid2.gif) no-repeat;">
		</div>

		<div  style="position:absolute; top:129px; width:576px; left:313px;">
			<table  style="color:#fcbc8a;" class="size16"><tr>'.tpl::_ax(tpl::_export('','menu','head'),array('tpl_gb','__menu_head')).'</tr></table>
		</div>

		<div  class="iePNG" style="position:absolute; top:500px; left:0px;  
					 width:66px;height:114px;background:url(img/xx1.png) no-repeat;">
		</div>
		<div  class="iePNG" style="position:absolute; top:760px; left:0px;  
					 width:66px;height:114px;background:url(img/xx1.png) no-repeat;">
		</div>

		</div>
		</td>
	</tr>
	<tr style="z-index:10;"><td style="z-index:10;">
		
	<table class="long fixed"><col width="136px"><col width="auto"><tr>
	<td>
	</td><td style="padding-bottom:200px;padding-right:80px;">
		<div id=\'main_context\' style="z-index:1;background:url(img/line.gif) bottom repeat-x;padding:0 0 60px 0;">
		'.(isset($par['data'])?$par['data']:'').'
		</div>
		</td></tr></table>
		</td>
	</tr>

	'.tpl::_ax(tpl::_export('','tpl','admin','bottom'),array('tpl_gb','__tpl_admin_bottom')).'	
</table>
</div>
<div id="debug"></div>
</body>
</html>';
}

function __menu_head(&$par){		
		return '<td style="padding:0 15px 0 15px;"><a class="white size16 '.(isset($par['current'])?$par['current']:'').'" href="'.tpl::_d($par['url'],'#').'">'.(isset($par['item'])?$par['item']:'').'</a></td>
			'.tpl::_d($par['last'],'<td>|</td>');
}}
?>