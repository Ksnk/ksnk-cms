<?php
class tpl_tpl extends tpl {

function news(&$par){		
		return '<!--  ////////////////// плагин Новости /////////////////////// -->
'.tpl::_a($par['news_b'],array('tpl_tpl','news_news_b')).'

'.tpl::_a($par['news_x'],array('tpl_tpl','news_news_x')).'

'.tpl::_a($par['newslist'],array('tpl_tpl','news_newslist'));
}

function news_news_b(&$par){		
		return '<div style="position:relative;">
<table class="fixed" ><col width="252px"><col width="252px"><col width="16px"><tr>
<td colspan=2  style="background:url(img/thumb.gif) 0 0 repeat-x; height:57px;"></td>
<td   style="background:url(img/thumb.gif) -180px 0 repeat-x; height:57px;"></td>
</tr><tr>	
'.tpl::_a($par['news'],array('tpl_tpl','news_news_b_news')).'<td style="background:url(img/center.jpg);"></td>
</tr><tr><td colspan=3 style="padding-bottom:30px;background:url(img/down.gif) right bottom no-repeat;">
<div class="size14" style="clear:both;color:#8a5f3e;padding:10px 0 0 30px;"><nobr>
&nbsp;&nbsp;&nbsp;&nbsp;<span class="size11">Архив новостей</span>&nbsp;&nbsp;&nbsp;&nbsp;
'.tpl::_ax(tpl::_export('news','years'),array('tpl_tpl','news_news_b_news__years')).'
</nobr>
</div></td></tr></table>

<div style="z-index:2;position:absolute;left:248px;top:55px;  
			 width:4px;height:184px;background:url(img/cline.jpg) ;">
</div>

</div>';
}

function news_news_b_news(&$par){		
		return '<td style="background:url(img/center.jpg);">
<div class="ctext link tahoma" style="padding:2px 0 0 0;position:relative;width:252px;">

<div style="position:absolute; top:-46px; left:25px;color:#ffe9d2" class="size14 menu">'.tpl::_Day(pps($par['date'])).'/'.tpl::_M(pps($par['date'])).'</div>
<div  class="iePNG" style="z-index:2;position:absolute;left:99px;top:-65px;  
			 width:61px;height:64px;background:url(img/xx.png) ;">
</div>

'.tpl::_a($par['img'],array('tpl_tpl','news_news_b_news_img')).'

<div style="padding:16px 10px 0 30px;">
<a style="color:#c76a25;" href="?do=newslist&id='.(isset($par['id'])?$par['id']:'').'">
'.(isset($par['title'])?$par['title']:'').'</a>
<p style="color:#ffe6cc" class="size12">'.(isset($par['text_b'])?$par['text_b']:'').'</p>
</div>
</div></td>';
}

function news_news_b_news_img(&$par){		
		return '<table class="long"><tr><td class="align_center">
	'.(isset($par['pict'])?$par['pict']:'').'</td></tr></table>';
}

function news_news_b_news__years(&$par){		
		return '<span class="link" style=";margin-top:7px;">
<a style="color:'.tpl::_b($par['current'],'','').';" href="'.tpl::_ax(tpl::_export('','curl','do','id'),array('tpl_tpl','news_news_b_news__years___curl_do_id')).'do=newslist&year='.(isset($par['year'])?$par['year']:'').'">'.(isset($par['year'])?$par['year']:'').'</a></span> '.tpl::_d($par['last'],'/');
}

function news_news_x(&$par){		
		return '<div style="padding:0 0 150px 60px;">
'.tpl::_a($par['news'],array('tpl_tpl','news_news_x_news')).'
<div class="size11 menu" style="clear:both;color:#cc4711;padding:10px 0 10px 5px;">
Архив новостей</div>
<div class="link size12" style="color:#fdee9a;padding:7px 0 10px 5px;">
'.tpl::_ax(tpl::_export('news','years'),array('tpl_tpl','news_news_x_news__years')).'</div>

</div>';
}

function news_news_x_news(&$par){		
		return '<div class="ctext link tahoma" style="float:left;width:256px;">


<div style="background:url(img/newsdate.gif) no-repeat;padding:12px 10px 15px 22px;" class="size16 "><b>'.(isset($par['date_dm'])?$par['date_dm']:'').'</b>
<span class="size11" style="padding-left:40px">2008</span>
</div>

<div style="padding:24px 30px 0 5px;">
<p style="color:#ffe6cc" class="size12">'.(isset($par['text_b'])?$par['text_b']:'').'</p>
</div>
</div>';
}

function news_news_x_news__years(&$par){		
		return '<span class="link" style=";margin-top:7px;">
<a style="color:#fdee9a;" href="'.tpl::_ax(tpl::_export('','curl','do','id'),array('tpl_tpl','news_news_x_news__years___curl_do_id')).'do=newslist&year='.(isset($par['year'])?$par['year']:'').'">'.(isset($par['year'])?$par['year']:'').'</a></span> '.tpl::_d($par['last'],'/');
}

function news_newslist(&$par){		
		return '<div class="tahoma ctext" style="margin-top:15px;">
'.tpl::_a($par['news'],array('tpl_tpl','news_newslist_news')).'
<div style="padding-top:30px;clear:both;">
'.(isset($par['pages'])?$par['pages']:'').'
</div>
</div>';
}

function news_newslist_news(&$par){		
		return '<div style="clear:both; padding:10px 0;">
<b>'.(isset($par['date'])?$par['date']:'').'</b><br>
<b>'.(isset($par['title'])?$par['title']:'').'</b><br><br>
'.tpl::_a($par['img'],array('tpl_tpl','news_newslist_news_img')).'<p>'.(isset($par['text'])?$par['text']:'').'</p>
</div>';
}

function news_newslist_news_img(&$par){		
		return '<div class="float_left">
	'.tpl::_ax(tpl::_export('','tpl','admin','pictup'),array('tpl_tpl','news_newslist_news_img___tpl_admin_pictup')).'
	'.(isset($par['pict'])?$par['pict']:'').'
	'.tpl::_ax(tpl::_export('','tpl','admin','pictdn'),array('tpl_tpl','news_newslist_news_img___tpl_admin_pictdn')).'</div>';
}}
?>