<?php
/**
 * плагин - список банеров
 * Хранение банеров и вывод их наружу.
 * 
 */

/**
 * класс - плагин. 
 */
class bannerlist extends ml_plugin {
/**
 * Кеш локальный списка банеров
 *
 * @var unknown_type
 */
	var $node;
/**
 * Инициализация
 *
 * @param unknown_type $parent
 * @return bannerlist
 */
	function bannerlist(&$parent){
		parent::ml_plugin($parent);
		parent::_init(
		array(
		'title'=>'Список банеров'
		,'fields'=>array(
					array('Картинка','pic_small','picture'),
					array('Ссылка','item_url','text_edit'),
					array('описание','pic_comment','text_edit'),
					array('Сорт.','item_order','dontshow'=>true)
		)
		,'sort'=>true
		,'base'=>'bannerlist' // XXX: база не нужна, но для автоинициализации положено
		,'prefix'=>'bl'));
	}

	function data($what,$from='',$perpage=''){
		static $updated;
		if(empty($this->node))
			$this->node=$this->getNs(true);
		if(empty($this->node))
			return null ;
			
		switch($what){
			case "cnt":
				return count($this->node->el);
			case "data":
				$data=array();
				$i=1;
				foreach($this->node->el as $v){
					$data[]=array(
						'id'=>$v->v['id'],
						'pic_small'=>$v->v['pic_small'],
						'pic_comment'=>$v->v['pic_comment'],
						'item_url'=>$v->v['item_url']
					);
				}
				return $data;
			case "upd":
				$this->node->serialize_order_menu($_POST,true);
				if(empty($updated)){
					$this->node->serialize($_POST,true);
					$updated=true;
				}
				break;
			case "del":
				$this->parent->nodeDelete($this->parent->nodeScanId($from));
				break;
			case "ins":
				$this->parent->nodeAdd($this->node->node(),
					array_merge(array('name'=>'банер','type'=>type_PIC),$from));
		   		break;
		}
	}

	function do_create($killall=true){
		if(!$this->parent->has_rights(right_WRITE))
			return $this->parent->ffirst('_loginform');

		$votes_root=$this->getNs();
		if(!empty($votes_root) && $killall){
			$x=$this->parent->nodeDelete($this->parent->node('bannerlist'));
			$votes_root=null;
			$votes_root=$this->getNs(false);
		}
		if(empty($votes_root)){
			$votes_root=$this->parent->nodeAdd(0,array('name'=>'bannerlist','type'=>type_ARTICLE));
		}
		// create all
	}
/**
 * Типо кеш
 *
 * @return unknown
 */
	function getNs($clear=true){
		static $node; if ($clear && !empty ($node)) return $node ;
		$x=$this->parent->readRecord(array('name'=>'bannerlist'));
		$node=$this->parent->nodeGet($this->parent->node($x));
		if(empty($node)) return null;
		$i=0;
		return $node=readElement($node,$i);
	}

	function admin_bannerlist(){
		return
		smart_template(array(ADMIN_TPL,'theheader'),array(
		'header'=>$this->getPluginName(),
		'data'=>parent::admin_plugin()));
	}

	function get_bannerlist(){
		static $x; if (isset($x)) return $x ;
		$x='';
		$node=$this->getNs(true);
		$data=array();
		if(!empty($node->el))
		foreach($node->el as $v){
			$vv=$v->v;
			$vv['pic_small']=TO_URL($vv['pic_small']);
			$data[]=$vv;
		}
		return $x=$data;
	}
}
?>