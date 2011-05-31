<?php
class tpl_articles extends tpl {

function _(&$par){		
		return '<!--  ////////////////// плагин Статьи /////////////////////// -->
'.tpl::_a($par['articles_b'],array('tpl_articles','articles_b')).'

'.tpl::_a($par['articles'],array('tpl_articles','articles')).'

'.tpl::_a($par['article'],array('tpl_articles','article'));
}

function articles_b(&$par){		
		return '<div class="tahoma ctext size11"> <!-- id="a_brief" -->
<div class="article red" ><span>CТАТЬИ</span></div>
'.tpl::_a($par['list'],array('tpl_articles','articles_b_list')).'
<div class="tahoma ctext link size11" style="margin: 10px 0 10px 0;">
<a href="?do=articles">&raquo;
Список статей</a></div>
</div>';
}

function articles_b_list(&$par){		
		return '<p><b>'.(isset($par['title'])?$par['title']:'').'</b><br>
'.(isset($par['text'])?$par['text']:'').'</p>
<div class="tahoma ctext link size11 align_right" style="margin: 10px 0 10px 0;">
<a href="?do=articles&id='.(isset($par['id'])?$par['id']:'').'">&raquo;
Подробнее</a></div>';
}

function articles(&$par){		
		return '<div class="tahoma ctext" style="padding-top:40px;"> <!-- id="a_brief" -->
<p style="font-size:16px;"><b>Список статей</b></p><br>
<ul class=\'ctext\'>
'.tpl::_a($par['list'],array('tpl_articles','articles_list')).'
'.(isset($par['pages'])?$par['pages']:'').'
</div>';
}

function articles_list(&$par){		
		return '<li><a class="atitle" href="?do=articles&id='.(isset($par['id'])?$par['id']:'').'"><b>'.(isset($par['title'])?$par['title']:'').'</b></a></li>';
}

function article(&$par){		
		return '<div class="tahoma ctext" style="padding-top:40px;"> <!-- id="a_brief" -->
'.tpl::_a($par['list'],array('tpl_articles','article_list')).'
</div>';
}

function article_list(&$par){		
		return '<div style="width:60%"><b><p style="font-size:16px;">'.(isset($par['title'])?$par['title']:'').'
</p></b></div>
<br>
<p>'.(isset($par['text'])?$par['text']:'').'</p>
<br>';
}}
?>