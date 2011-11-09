<?php
/**
 * поддержка деревянных записей в таблице с forum-like содержимым
 * 
 * внешние надобности
 *  self::$database - база данных, открытая и рабочая 
 * 
 */

class tree {
	
	var $database;
	
	static $keys=array(
		'id'=>0,
		'parent'=>0,
		'level'=>0,
		'date'=>0,
		'username'=>0,
		'flags'=>0,
		'user'=>0,
		'quote_id'=>0,
		'text'=>0
	);
/*************интерфейс******************************************/
	
	function __construct(){
		$this->database=DATABASE();
	}
	
	/**
	 * вставить запись с уровнем 0 и выдать индекс вставки
	 */
	function insertRoot($key=null){
		if(is_null($key))
			$key=array();
		$key['level']=0;
		$key['parent']=0;
		return $this->insertRecord($key);	
	}
	
	/**
	 * вставить запись в конец списка чилдов записи $id
	 */
	function insertAsChild($id,$key){
		if(is_null($key))
			$key=array();
		$record=$this->getRow($id,'level');	
		$key['level']=$record['level']+1; // TODO: уточнить нужен ли нам level !!!!
		$key['parent']=$id;
		return $this->insertRecord($key);	
	}
	
	/**
	 * выдать список чилдов от корня id постранично? сотрировка по дате
	 * @param int $id - индекс рута
	 * @param int $page - номер страницы
	 * @param int $perpage - количество на странице
	 * @param int $total - всего элементов. Вычисляется автоматически в mysql запросе.
	 */
	function getChilds($id,$page,$perpage,&$total){
		$total=$this->database->selectCell('select count(*) from ?_tree where `parent`='.intval($id).';');
		return $this->filter(
			'select * from ?_tree where `parent`='.intval($id)
			.' order by `date` limit '.($page*$perpage).','.$perpage.';'
		);
	}
	/**
	 * выдать список чилдов глубины 2
	 * @param int $id - индекс рута
	 * @param int $page - номер страницы
	 * @param int $perpage - количество на странице
	 * @param int $total - всего элементов. Вычисляется автоматически в mysql запросе.
	 */
	function getDeep2($id,$page,$perpage,&$total){
		$total=$this->database->selectCell('select count(*) from ?_tree where `parent`='.intval($id).';');
		$arr= $this->getChilds($id,$page,$perpage,$total);
		foreach($arr as &$v){
			$v['childs_total']=0;
			$v['childs']= $this->getChilds($v['id'],0,$perpage,$v['childs_total']);
		}
		return $arr;
	}
	
/*************внутренности***************************************/
	function filter($sql){
		$arr=$this->database->select($sql);
		foreach( $arr as &$v){
			if(!empty($v['info']))
				$v=array_merge($v,unserialize($v['info']));
			unset($v['info']);	
		}
		return $arr;
	}
	
	function do_create(){
		$this->database->query("CREATE TABLE ?_tree (
  `id` int(10) NOT NULL auto_increment,
  `parent` int(10) NOT NULL default '0',
  `level` int(10) NOT NULL default '0',
  `date` timestamp NOT NULL default CURRENT_TIMESTAMP ,
  `username` varchar(255) NOT NULL default '',
  `flags` int(10) NOT NULL default '0',
  `user` int(10) NOT NULL default '0',
  `quote_id` int(10) NOT NULL default '0',
  `text` text  NOT NULL,
  `info` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `parent` (`parent`),
  KEY `level` (`level`),
  KEY `quote_id` (`quote_id`),
  KEY `username` (`username`),
  KEY `user` (`user`)
);");
	}
	
/*********************************************************************************/
	function getRow($id,$list='*'){
		return $this->database->selectRow('select '.$list.' from ?_tree where `id`=?d',$id);
	}
	
	/**
	 * список одноранговый от $id 
	 * @param unknown_type $id
	 * @param unknown_type $list
	 */
	function getList($id,$list='*'){
		return $this->selectRow(
			'select '.$list.' from ?_tree where `id`=?d'
			,$id
		);
	}
	
	function insertRecord($key){
		$rkey=array_intersect_key($key,self::$keys);
		$vkeys=array_diff_key($key,self::$keys);
		if(empty($vkeys)) 
			$rkeys['info']=serialize($vkeys);
		else	
			$rkeys['info']='';
		return $this->database->query(
			'INSERT INTO ?_tree (?#) VALUES(?a);',
		   	array_keys($key),array_values($key)
		);
	}	
}