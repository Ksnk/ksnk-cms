<?php
/**
 * Плагин - поиск
 */

class full_search extends ml_plugin{ // неймспейс для всякой пользы
	
	var $link_table;
	
	function init(){
		// проверка существования таблицы
		$search_version=$this->parent->getPar('search_plugin_version',0);
		if($search_version<=0){
			// удаляем таблицу
			$this->database->select('DROP TABLE IF EXISTS ?_search;');
			// создаем таблицу
			$this->database->select('CREATE TABLE IF NOT EXISTS ?_search ('.
				' `id` INT NOT NULL ,'.
				' `url` VARCHAR( 255 ) NOT NULL ,'.
				' `text` TEXT NOT NULL ,'.
				' `date` DATETIME NOT NULL ,'.
				'PRIMARY KEY ( `id` ) ,'.
				'UNIQUE index(`url`),'.
				'FULLTEXT (`text`)'.
			');');
			$this->parent->setPar('search_plugin_version',1);
		} 
		
		$this->link_table=array();
		
	}
	
	function getPage($pageToGet) {
		if(empty($this->ch)){ 
			$this->ch= curl_init();
			$this->VAR_CURLOPT_COOKIEJAR = TMP_DIR."/cookies";  // place to put cookies
			$this->VAR_CURLOPT_COOKIEFILE = TMP_DIR."/cookies";  // place to get cookies from
		}
		
		curl_setopt($this->ch, CURLOPT_URL, $pageToGet); // set url to post to
		curl_setopt($this->ch, CURLOPT_FAILONERROR, $this->VAR_CURLOPT_FAILONERROR);
		curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, $this->VAR_CURLOPT_FOLLOWLOCATION);
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, $this->VAR_CURLOPT_RETURNTRANSFER);
		curl_setopt($this->ch, CURLOPT_TIMEOUT, $this->VAR_CURLOPT_TIMEOUT);
		curl_setopt($this->ch, CURLOPT_COOKIEJAR, $this->VAR_CURLOPT_COOKIEJAR);
		curl_setopt($this->ch, CURLOPT_COOKIEFILE, $this->VAR_CURLOPT_COOKIEFILE);
		if(strlen($this->VAR_CURLOPT_USERAGENT)>0) {
			curl_setopt($this->ch, CURLOPT_USERAGENT, $this->VAR_CURLOPT_USERAGENT);
			}
		if(strlen($this->VAR_CURLOPT_USERPWD)>2) {
			curl_setopt($this->ch, CURLOPT_USERPWD, $this->VAR_CURLOPT_USERPWD);
			}
		$strPageContents = curl_exec($this->ch); // go get the page
		$this->timeLapsed = substr((microtime(true) - $this->timeStart),0,5);
		return(array($strPageContents, "error"=>curl_errno($this->ch), "errortext"=>curl_error($this->ch)));

	} 
	
	function index($url){
		$x=$this->getPage($url);
		if($x[0]){
			$x=strip_tags($x);
			// вставить в базу
			$this->database->select('insert IGNORE into ?_search (date,url,text) values'.
			'(NOW(),"'.mysql_real_escape_string($url).'","'.mysql_real_escape_string($x).'")');
		};
	}
	
	function reindex($start){
		// чистим базу напрочь
		$this->database->select('delete * from ?_search;');
		
	}

}
?>