<?php
class tpl_news extends tpl {

function _(&$par){		
		return '<!--  ////////////////// плагин Новости /////////////////////// -->
'.tpl::_a($par['news_b'],array('tpl_news','news_b')).'

'.tpl::_a($par['newslist'],array('tpl_news','newslist'));
}

function news_b(&$par){		
		return '<div style="padding-bottom:30px;">
'.tpl::_a($par['news'],array('tpl_news','news_b_news')).'
<div class="tahoma blue" style="margin-top:60px;">
<span style="font-weight:bold;font-size:11px;">Показать</span><br/>
'.tpl::_a($par['years'],array('tpl_news','news_b_years')).'
</div>
</div>';
}

function news_b_news(&$par){		
		return '<div class="ctext link tahoma" style="margin-bottom:34px;">
<div style="width:35%;color:#3f5773;font-size:24px;border-bottom:solid 1px #eb8017;padding-top:14px;padding-bottom:6px;margin:5px 0;float:left;">
'.tpl::_Day(pps($par['date'])).'
</div>
<div style="width:64%;color:#3f5773;font-size:24px;border-bottom:solid 1px #d7dee9;padding-top:14px;padding-bottom:6px;margin:5px 0;float:left;">&nbsp;</div>
<div style="margin-top:0px;clear:left;" class="cltext">'.tpl::_rusM(pps($par['date'])).' '.tpl::_rusY(pps($par['date'])).'</div>
<p class="size12" style="padding-top:12px;padding-bottom:12px;margin-right:20px;"><a class="blue" href="?do=newslist&id='.(isset($par['id'])?$par['id']:'').'">'.(isset($par['title'])?$par['title']:'').'</a></p>
<p class="size11" style="margin-right:20px;">'.(isset($par['text'])?$par['text']:'').'</p>
</div>';
}

function news_b_years(&$par){		
		return '<a href="'.tpl::_ax(tpl::_export('','curl','do','id'),array('tpl_news','news_b_years___curl_do_id')).'do=news&year='.(isset($par['year'])?$par['year']:'').'">'.(isset($par['year'])?$par['year']:'').'</a> '.tpl::_d($par['last'],'/').'<br/>';
}

function newslist(&$par){		
		return '<div class="tahoma ctext" style="margin-top:15px;">
'.tpl::_a($par['news'],array('tpl_news','newslist_news')).'
'.(isset($par['pages'])?$par['pages']:'').'
</div>';
}

function newslist_news(&$par){		
		return '<br>
<b>'.(isset($par['date'])?$par['date']:'').'</b><br>
<b>'.(isset($par['title'])?$par['title']:'').'</b><br>
'.tpl::_a($par['img'],array('tpl_news','newslist_news_img')).'
<p>'.(isset($par['text'])?$par['text']:'').'</p><br>';
}

function newslist_news_img(&$par){		
		return '<div class="gallery" style="padding:10px 0;">
<a href="'.(isset($par['big_pict'])?$par['big_pict']:'').'"><img src="'.(isset($par['small_pict'])?$par['small_pict']:'').'"/></a>
</div>';
}}
?>