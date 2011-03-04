<?php

/**
 * �������������� ����� ��� ��������� ������� ������������
 * ����� ������� �������� ��������� ����
 */
class katalog_code {
	/**
	 * �� ������� ������ ������ ������ � ����� �������
	 * @param $code string -  ��� �������
	 * @param $article int - ������������� ������
	 * @param $menu int - ������������� ������ ���� 
	 * 
	 */
	function setCode($key){
		global $engine;
		// �������� - ��� �� ������?
		$x=$this->getCode($key['article']);
		if(empty($x)){ // ��������
			$engine->database->select(
				'insert INTO ?_katcode (?#) VALUES(?a);'
				,array_keys($key)
				,array_values($key)
			);
		} else { // �������� 
			if(!empty($x['elem'])){
				$k=$engine->readRecord(array('id'=>$x['elem']));
				if(!isset($k['type'])){ // ������� �� ������
					$key['elem']=0;
				} else {
					$k['code']=$key['code'];
					$engine->writeRecord($k);
				}
			}
			$engine->database->select(
				'UPDATE ?_katcode set ?a where `ID`=?;'
				,$key,$x['id']
			);
		}
	}
	
	/**
	 * get saved code by article id
	 * @param int $article
	 */
	function getCode($article){
		global $engine;
		//debug($article);
		if(is_null($x=@$engine->database->selectRow('select * from ?_katcode where article=?d',$article))){
			@$this->create();
			$x=$engine->database->selectRow('select * from ?_katcode where article=?d',$article);
		};
		return $x;
	}
	/**
	 * get saved code by article id
	 * @param int $article
	 */
	function getByElem($elem){
		return DATABASE()->selectRow('select * from ?_katcode where elem=?d',$elem);
	}
	
	function create(){
		global $engine;
		return $engine->database->selectRow('CREATE TABLE ?_katcode (
  `id` int(11) NOT NULL auto_increment,
  `code` varchar(30) NOT NULL,
  `menu` int(11) NOT NULL,
  `article` int(11) NOT NULL,
  `elem` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `code_2` (`code`),
  KEY `code` (`code`,`article`,`elem`)
);');
	}
}
/**
 * ���������� ������� �������
 * ������� �����, ������������ ��������� �������� ������ � 
 * ������ ���������
 *
 */
class katalog_store {
	var $changed = false
		,$id =0  // ������������� ��������� ��������
		,$data  // ������
		,$parent
		,$childs
		;
	static $store=array();	
	/**
	 * ��������������� �������� �� ������������ ������
	 * @param array $trn
	 */
	function __construct($data){
		$this->data=$data;
	}
	
	function __destruct(){
		if($this->changed)
			$this->save();
	}
	
	function getId(){
		if(!empty($this->data['id']))
			return $this->data['id'];
		else 
			return 0;	
	}
	
	/**
	 * ��������� ������ �������
	 */
	function save(){
		$key=$this->data;
		if(!!($this->getId())){
			unset($key['id']);
			DATABASE()->query('UPDATE ?_katalog set ?a where `ID`=?;',$key,$this->getId());
		} else {
			$this->data['id']=
			DATABASE()->query('INSERT INTO ?_katalog (?#) VALUES(?a);',array_keys($key),array_values($key));
		}
		$this->changed = false;
	}
	
	/**
	 * ������ ������� �� ��� ID
	 * @param int $id
	 */
	static function &get($id){
		if(!isset(self::$store[$id])){
			$data = DATABASE()->selectRow('select * from ?_katalog where `ID`=?;',$id) ;
			if(empty($data))
				self::$store[$id]=null;
			else	
				self::$store[$id]=katalog_store::create($data);
		}
		return self::$store[$id];
	}
	
	/**
	 * @param int $id
	 */
	static function countByCode($id){
		return DATABASE()->selectCell('select count(*) from ?_katalog where `code`=?;',$id) ;
	}
	
	/**
	 * ������ �������� �� ���� �������
	 * @param int $id
	 */
	static function &getByCode($code){
		$data = DATABASE()->select('select * from ?_katalog where `code`=?;',$code) ;
/*		if(!empty($data))
		foreach($data as $d){
			self::$store[$d['id']]=$d;
		}*/
		return $data;
	}
	
	/**
	 * ���� ������� �� ��������
	 * //TODO: ������� ����� �� ���������� �����, ���� ����� ����.
	 */
	static function &find($keys){
		$empty=array();
		if(!is_array($keys)){
			$where = 'articul="'.mysql_escape_string($keys).'"';
		} else {
			$where=array();
			if (!empty($keys['articul']))
				$where[] = 'articul="'.mysql_escape_string($keys['articul']).'"';
			if (!empty($keys['descr']))
				$where[] = 'descr LIKE %'.mysql_escape_string($keys['descr']).'%';
			$where=implode(' and ',$where);	
		}
		if(!empty($where)) $where = ' WHERE '.$where;
		$data = DATABASE()->select('select * from ?_katalog '.$where.' order by level limit 2;',$keys) ;
		if(empty($data))
			return $empty;
		else	
			return katalog_store::create($data[0]);
	}
	
	static function create($data){
		if(isset($data['id'])){
			self::$store[ppi($data['id'])]=new katalog_store($data);
			return self::$store[ppi($data['id'])];
		};
		return new katalog_store($data);
	}
	
	function delete(){
		$this->deleteChilds();
		DATABASE()->query(
			'DELETE FROM ?_katalog where `id`=?;',$this->getId()
		);
	}
	
	function deleteChilds(){
		$this->prepChilds();
		$child_idx=array();
		foreach($this->childs as $child){
			$child_idx[]=$child->getId();
			$child->deleteChilds();
		}
		DATABASE()->query(
			'DELETE FROM ?_katalog where `parent`=?;',$this->getId()
		);
	}
	/**
	 * ���������� ������ ���� child-�������� 
	 */
	function prepChilds(){
		if(is_null($this->childs)){
			$childs = DATABASE()->select('select * from ?_katalog where `parent`=?;'
			,$this->getId()) ;
			foreach($childs as $ch){
				$this->childs[]=katalog_store::create($ch);
			}
		}
	}
	/**
	 * �������� ���� ��� ����� � ������ ����� 
	 */
	function cloneAsChild(){
		if($this->changed) $this->save();
		$key=$this->data;
		$key['parent']=$this->getId();
		unset($key['id']);
		DATABASE()->query('INSERT INTO ?_katalog (?#) VALUES(?a);',array_keys($key),array_values($key));
		$this->childs=null;
	}
	/**
	 * ���������� ������ �������
	 */
	function prepParent(){
		if(empty($this->parent))
			$this->parent=katalog_store::get($this->data['parent']);
	}
}
