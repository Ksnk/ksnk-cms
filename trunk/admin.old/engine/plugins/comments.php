<?php
/**
 * собрать все комментарии в одну кучу и показать все новые
 */

class comments extends ml_plugin {
	
	static $table='_comment';
	
/**
 * добавить комментарий
 */	
	static function add($key){
		global $engine;
		$key['new']=0;
		return $engine->database->query('INSERT INTO ?'.self::$table.' (?#) VALUES(?a);',
		   			array_keys($key),array_values($key));
	}
	
/**
 * получить данные из коммента по топику
 */	
	static function get($topic){
		global $engine;
		$v=$engine->database->select('select * from ?'.self::$table.' where `topic`=?d',$topic);
		if(!empty($v)){
			foreach($v as &$vv){
				$vv['info'] = $engine->export('MAIN','userinfo',$vv['user']);
				//debug($tmp);
			}
		}
		if(empty($v))
			return array();
		return 
			$v;
	}
	
/**
 * создание таблиц
 */
	function do_create($killall=true){
		if(!$this->parent->has_rights(right_WRITE))
			return $this->parent->ffirst('_loginform');
		if($killall){
			$this->database->query('DROP TABLE IF EXISTS ?'.$this->base.';');
		}
		$this->database->query("CREATE TABLE ?'.self::$table.' (
		  `id` int(11) NOT NULL auto_increment,
		  `topic` int(11) NOT NULL default '0',
		  `new` varchar(80) character set cp1251 NOT NULL default '',
		  `username` varchar(80) character set cp1251 NOT NULL default '',
		  `user` int(11) NOT NULL default 0,
		  `date` datetime default NULL,
		  `quote` text character set cp1251 NOT NULL,
		  `text` text character set cp1251 NOT NULL,
		  PRIMARY KEY  (`id`),
		  KEY `topic` (`topic`)
		);");
	}
	
/**
 * конструктор - описание полей комментария, определение формы админки
 */
	function comments($parent){
		parent::ml_plugin($parent);
		$par=array(
			'title'=>'Комментарии'
			,'fields'=>array(
				array('Проверено','new','check01'),
				array('Дата','date'),
				array('Имя','username'),
				array('Текст','text'),
				array('Цитата','quote'),
				array('раздел','topicname','dontshow'=>1),
				//array('','id','button'),
			)
			,'options'=>array('group'=>'topicname')
			,'group'=>'topicname'
			,'base'=>''.self::$table.''
			,'prefix'=>'cm'
		);
		parent::_init($par); 	
	}
	
/**
 * Парамеры плагина
 * @param unknown_type $par
 *//*
	function get_parameters($par){
		$par['list'][]=array('sub'=>'Результаты поиска','title'=>'Количество результатов на страницу','name'=>'search_per_page');
		$par['list'][]=array('sub'=>'Форум','title'=>'Количество сообщений на страницу','name'=>'forum-perpage');
	}
*/
/**
 * форма администрирования
 */
	function admin_comments(){
		$topic_name = $this->getPluginName();
		return
		smart_template(array(ADMIN_TPL,'theheader'),array(
		'header'=>$topic_name,
		'data'=>parent::admin_form()));
	}

	function topicname($topic){
		global $engine;
		static $cache;
		if(empty($cache)) $cache=array();
		if(!empty($cache[$topic])) return $cache[$topic];
		$tmp=$engine->nodeGetBackPath($engine->node($topic));
		$name='';
		if(isset($tmp[1]))
			$name=pp($tmp[1]['item_columns'],'-',' ');
		if($tmp[0]['type']==type_ARTICLE){
			$t=$engine->readRecord(array('page'=>$tmp[0]['node']));
			if(!empty($t))
				return $cache[$topic]=$t['name'].$name;
		}	
		return '';
	}
/**
 * работа с данными
 * @see ml_plugin::data()
 */	
	function data($what,$from='',$perpage=''){
		global $engine;
		switch($what){
			case "cnt":
				return @$this->database->selectCell('select count(*) from ?'.$this->base.' where `new`=0;');
			case "row":
				return $this->database->selectRow('select * from ?'.$this->base.' where `id`=?d',$from);
			case "data": 
				$data=$this->database->query('select * from ?'.$this->base.' where `new`=0 order by `topic`,`date` LIMIT ?d,?d',$from,$perpage);
				foreach($data as &$d){
					$d['topicname']=$this->topicname($d['topic']);
				}
				return $data;
			case "del":
				$this->database->query('DELETE from ?'.$this->base.' where `id`=?d;',$from);
				break;
			case "upd":
				$this->database->query('update ?'.$this->base.' set ?a where `id`=?;',$from,$perpage);
				break;
			case "ins":
				return $this->database->query('INSERT INTO ?'.$this->base.' (?#) VALUES(?a);',
		   			array_keys($from),array_values($from));
		}
	}
}
?>