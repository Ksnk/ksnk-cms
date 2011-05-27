<?php
class tpl_rss extends tpl {

function _(&$par){		
		return '<?xml version="1.0" encoding="windows-1251"?>
<rss version="2.0">
  <channel>
    <title>'.tpl::_d($par['title'],'RSS-feed').'</title>
    <link>'.(isset($par['link'])?$par['link']:'').'</link>
    <description>'.tpl::_d($par['description'],'Последние 12 новостей с сайта').'</description>
'.tpl::_a($par['items'],array('tpl_rss','items')).' 
  </channel>
</rss>';
}

function items(&$par){		
		return '<item>
      <title>'.(isset($par['title'])?$par['title']:'').'</title>
	  <guid>'.(isset($par['link'])?$par['link']:'').'</guid>
      <link>'.(isset($par['link'])?$par['link']:'').'</link>
      <description>'.(isset($par['description'])?$par['description']:'').'</description>
      <pubDate>'.(isset($par['pubdate'])?$par['pubdate']:'').'</pubDate>
    </item>';
}}
?>