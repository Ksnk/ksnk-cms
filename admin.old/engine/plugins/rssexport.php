<?php
/**
 * Плагин RSS-feed для CMS ки
 * 
 * Основной смысл - при создании новой новости происходит 
 * обязательный вброс ее в feed, и перегенерация последних 
 * новостей 
 */
define('RSS_TPL','tpl_rss');

define('RSS_FILE','../rss.xml');

class rssexport extends ml_plugin {
	
	function get(){
		
		$interval=ppi($this->parent->getPar('rssfeed_timeout'),30) * 60;
		$lasttime=ppi($this->parent->getPar('rssfeed_lasttime'));
		if($lasttime+$interval<time()){
			$file=$this->parent->getPar('rss_addr');
			if(!empty($file)){
				$file=file_get_contents($file);
				if(preg_match_all('~<item.*?>.*?</item>~is',$file,$m)){
					$maxnews=ppi($this->parent->getPar('rssfeed_newsnum'),3);
					$result=array();
					for($i=0;$i<$maxnews;$i++){
						if(preg_match('~<description>(.*?)</description>.*?<pubDate>(.*?)</pubDate>~',$m[0][$i],$mm)){
							if(detectUTF8($mm[1])){
								$mm[1]=iconv('utf-8','cp1251//IGNORE',$mm[1]);
								$mm[2]=iconv('utf-8','cp1251//IGNORE',$mm[2]);
							}
						}
						if(!empty($mm[1]) && !empty($mm[2]))
							$result[]=array('date'=>$mm[2],'news'=>$mm[1]);
					};
					
				};
			}
			$this->parent->setPar('rssfeed_lastresult',$result);
			// читаем новую новость
			$this->parent->setPar('rssfeed_lasttime',time());
		} else {
			$result=$this->parent->getPar('rssfeed_lastresult',serialize($result));
		}
		return $result;
	}
	
	function getPluginName(){
		return 	'RSS-экспорт';
	}
	
	function get_parameters($par){
		$par['list'][]=array('sub'=>'RSS','title'=>'Адрес канала для экспорта','name'=>'rss_addr');
		$par['list'][]=array('title'=>'Количество новостей','name'=>'rssfeed_newsnum');
		$par['list'][]=array('title'=>'Интервал сканирования(мин.)','name'=>'rssfeed_timeout');
	}
	
	function admin_rssexport(){
		return
		smart_template(array(ADMIN_TPL,'theheader'),array('header'=>"Параметры экспорта новостей",
		'data'=>$this->parent->ffirst('do_siteparam',__CLASS__)));
	}
}
?>