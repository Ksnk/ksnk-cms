<?php
/**
 * сервис - вопрос-ответ с комментариями
 * ведущий вопрос задает тему, каждые его чилд - вопрос, его первый чилд - ответ.
 * 
 *  - интерфейс - 
 *  -- добавить вопрос,
 *  -- добавить ответ на вопрос
 */
class darts_Qa extends class_tree {
	
	var 
	
	 $options=array(
	
		  'qa_perpage'=>20
		, 'qa_sortbydate'=>true
	
	);
	
	function __construct($options){
		$this->options=array_merge($this->options,$options);
	}
	
	
	function newQuestion($data){
		
	}
	
/**
 * интерфейсные процедуры
 */	
	
/**
 * вывести список вопрос-ответ к нужному разделу
 * Enter description here ...
 */	
	function do_qa(){
		$id   = intval($_GET['id']);
		$page = intval($_GET['page']);
		
		$par = array();
		
		$tree=class_tree::get($id);
		if($tree){
			$par['header']=$tree->data;
			$tree->prepChilds($page*$this->options['qa_perpage'],$this->options['qa_perpage']);
			foreach($tree->childs as $child){
				$child->prepChilds();
				$qa=array($child->data);
				if($child->child)
					$qa[]=$child->child->data;
				$par['qa'][]=$qa;	
			}
		}
		return $this->parent->tpl('tpl_main','tpl_qa',$par);
	}

	/**
	 * добавить вопрос с ajax формы 
	 */
	function do_new_q(){
		$id   = intval($_GET['id']);
		$page = intval($_GET['page']);
		
		$par = array();
		
		$tree=class_tree::get($id);
		if($tree){
			$par['header']=$tree->data;
			$tree->prepChilds($page*$this->options['qa_perpage'],$this->options['qa_perpage']);
			foreach($tree->childs as $child){
				$child->prepChilds();
				$qa=array($child->data);
				if($child->child)
					$qa[]=$child->child->data;
				$par['qa'][]=$qa;	
			}
		}
		return $this->parent->tpl('tpl_main','tpl_qa',$par);
	}
	
	
}