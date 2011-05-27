<?php
/**
 * ���������� ������� �������
 * ������� �����, ������������ ��������� �������� ������ � 
 * ������ ���������
 * @author RMO
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
	 * ����������� �������� �� �� ����
	 * ������ ������ �� ��� ID
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
