<?php
/**
 * класс - модельная оболочка над tree
 */
class comment {
	
	/**
	 * Вставка корня комментария в дерево и выдача индекса корня
	 */ 
	function initComment() {
		return tree::insertRoot();
	}
	
	/**
	 * выдать все комментарии по сохраненному ID
	 */
	function getComments($id,$page,&$total){
		return tree::getChilds($id,$page,$this->options['perpage'],$total);
	}

	/**
	 * вставить комментарий
	 * @param int $id - рут комментария
	 * @param array $key - комплект параметров комментария
	 */
	function insertComment($id,$key){
		return tree::getChilds($id,$page,$this->options['perpage'],$total);
	}
	
/*****************************************************************************/
	
	/**
	 * интерфейсная часть
	 */	
	function getText(){
		global $engine;
		if(!empty($_POST['action']) && $_POST['action'] == 'newkomment'
			&& (!isset($_POST['comment']) || $_POST['comment']==$this->v['id'])
		) {
			$key = array(
				'topic'=>$this->v['id']
				,'date'=>date('Y-m-d H:i:s')
				,'username'=>strip_tags($_POST['username'])
				,'user'=>intval($_POST['user'])
				,'text'=>post2comment($_POST['newpost'])
				,'quote'=>post2comment($_POST['quote'])
			);
			comments::add($key);
			//debug($engine->curl());
			
			$engine->go('',$_SERVER["REQUEST_URI"]);
		}
		
		return $engine->_tpl('tpl_jelements','_komment',array(
			'par'=>$this->v,
			'data'=>comments::get($this->v['id'])
		));
	}
	
}