<?php
/**
 * Класс для хранения деревьев
 * Базовый класс для турниров, новостей, QA и протч и протч
 * -- поля level,parent обязательны в базе данных.
 */

/**
 * базовый класс, определяющий интерфейс с узлом дерева
 * статические методы предназначены для работы с деревом вообще
 *
 */
class class_tree {
	
	static 
		$cache = array();
		
	public	
	/**
	 * дерево-предок
	 */
		$parent
		
	/**
	 * собствено данные из таблицы
	 */	
		,$data
		
	/**
	 * детки
	 */	
		,$childs=null
		
	/**
	 * изменился ли узел, надо ли сохранять
	 */	
		,$changed = false
		
		;	
		
	/**
	 * Конструктор. Как правило вызывается из статического конструктора
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
	
	function delete($childonly=true){
		$this->prepChilds();
		foreach($this->childs as $child){
			$child->deleteChilds();
		}
		DATABASE()->query(
			'DELETE FROM ?_tree where parent=?;',$this->getId()
		);
		if (!$childonly){
			DATABASE()->query('DELETE FROM ?_tree where id=?;',$this->getId());
		}
		unset(self::$cache[$this->getId()]);
	}
	
	/**
	 * сохранить турнир обратно
	 */
	function save(){
		$key=$this->data;
		if(!!$this->getId()){
			unset($key['id'],$key['parent']);
			DATABASE()->query('UPDATE  ?_tree set ?a where `id`=?;',$key,$this->getId());
		} else {
			$this->data['id']=
			DATABASE()->query('INSERT INTO ?_tree (?#) VALUES(?a);',array_keys($key),array_values($key));
		}
		//$id=$this->getId();
		$this->changed = false;
	}
	
	/**
	 * конструктор турниров по их типу
	 * читать турнир по его ID
	 * @param unknown_type $id
	 */
	static function &get($id) {
		if(empty(self::$cache[$id])) {
			$tournament = DATABASE()->selectRow('select * from ?_tree where id=?;',$id) ;
			self::$cache[$id]=self::create($tournament);
		}
		return self::$cache[$id];
	}
	
	static function create($data) {
		$classname='class_'.pps($data['class'],'tree');
		if (class_exists($classname)) {
			$x=new $classname($data);
			if(isset($data['id']))
				self::$cache[$data['id']]=$x;
			return $x;
		} else {
			debug('Отсутствует класс '.$classname);
			return null;
		}
	}
	
	/**
	 * обеспечить чтение всех child-турниров 
	 */
	function prepChilds($offset=0,$limit=-1){
		if(is_null($this->childs)){
			if($limit>0)
				$limit=sprintf('limit %s,%s',$offset,$limit);
			else
				$limit='';	
			$childs = DATABASE()->select('select * from ?_tree where '.$this->parent.'=?'.$limit.';'
				,$this->getId()) ;
			$this->totalChilds = DATABASE()->selectCol('SELECT FOUND_ROWS();');
			foreach($childs as $ch){
				$this->childs[]=self::create($ch);
			}
		}
	}
	/**
	 * обеспечить чтение парента
	 */
	function prepParent(){
		if(empty($this->parent))
			$this->parent=tournament::get($this->data[$this->parent]);
	}
	
}
