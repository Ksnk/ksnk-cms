<?php
/**
 * ������ RSS-feed ��� CMS ��
 * 
 * �������� ����� - ��� �������� ����� ������� ���������� 
 * ������������ ����� �� � feed, � ������������� ��������� 
 * �������� 
 */
define('RSS_TPL','tpl_rss');

define('RSS_FILE','../rss.xml');

class rss extends ml_plugin {
	
	function do_get(){
		
		$x=$this->parent->export('news','data','data',0,ppi($this->parent->getPar('rssfeed_num'),12));
		$items=array();
		foreach($x as $k=>$v){
			$item=array();
			if(!empty($v['picsmall'])){
				$item['picture']=array('picture'=>$v['picsmall']);
			}
			$item['title']=$v['the_header'];
			$item['link']='http://'.$_SERVER['SERVER_NAME'].$this->parent->index().'/?do=newslist&amp;id='.$v['id'];
			$txt=trim(strip_tags(str_replace('&nbsp;','',$v['the_text'])));
			if(preg_match('/(?:\S+\s+){'.ppi($this->parent->getPar('rssfeed_words'),200).'}/',$txt,$m)){
				$item['description']=trim($m[0]).' ...';
			} else {
				$item['description']=$txt;
			}			
			$item['pubdate']=gmdate ('',strtotime ($v['news_date']));
			
			$items[]=$item;
		};
		return smart_template(array(RSS_TPL,'_'),
			array(
				'title'=>pps($this->parent->getPar('rss_title')),
				'description'=>pps($this->parent->getPar('rss_descr')),
				'link'=>'http://'.$_SERVER['SERVER_NAME'].$this->parent->index(),
				'items'=>$items
			)
		);
	}
	
	function do_rebuild(){
		file_put_contents(RSS_FILE,$this->do_get());
		return ' ';
	}

	function getPluginName(){
		return 	'RSS';
	}
	
	function get_parameters($par){
		$par['list'][]=array('sub'=>'RSS','title'=>'���������� �������� � RSS','name'=>'rssfeed_num');
		$par['list'][]=array('title'=>'��������� ������','name'=>'rss_title');
		$par['list'][]=array('title'=>'�������� ������','name'=>'rss_descr');
		$par['list'][]=array('title'=>'���������� ���� � ������ �������','name'=>'rssfeed_words');
	}
	
	function admin_rss(){
		return
		smart_template(array(ADMIN_TPL,'theheader'),array('header'=>"��������� �����������",
		'data'=>$this->parent->ffirst('do_siteparam',__CLASS__)));
	}
}
?>