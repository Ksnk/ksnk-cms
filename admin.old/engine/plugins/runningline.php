<?php
/**
 * плагин - бегущая строка
 */
define('RUNLN_TPL',TEMPLATE_PATH.DIRECTORY_SEPARATOR.'runningline.tpl');

class runningline extends ml_plugin {

	function runningline(&$parent){
		parent::ml_plugin($parent);
		parent::_init(
		array(
		'title'=>'Бегущая строка'
		,'fields'=>array(
					array('Текст бегущей строки','descr','text_edit'),
					array('Сорт.','order','win_order')
		)
		,'base'=>'runningline'
		,'prefix'=>'rl'));
	}

	function data($what,$from='',$perpage=''){
		static $updated;
		$node=$this->getNs(true);
		switch($what){
			case "cnt":
				$node=$this->getNs(true);
				if(empty($node))
					return null;
				else
					return count($node->el);
			case "data":
				$data=array();
				$i=1;
				foreach($node->el as $v){
					$data[]=array(
//					'trclass'=>evenodd($i),
					'order'=>($i++)-1,
					'id'=>$v->v['id'],
					'descr'=>$v->v['descr'],
					'page'=>$v->v['page']);
				}
				return $data;
			case "upd":
				if(empty($updated)){
					if(!empty($node->el)){
						$orderind=0;
						$order_new=array();
						$order_old=array();
						foreach($node->el as $v){
							if(isset($_POST['order_'.$v->v['id']])){
								$order=ppi($_POST['order_'.$v->v['id']]);
								unset($_POST['order_'.$v->v['id']]);
								debug($order);
								if($order!=$orderind){
									$order_new[$order]=$v->v['id'];
									$order_old[$v->v['id']]=$orderind;
								}
							}
							$orderind++;
						}
						if(!empty($order_new)){
							$ns=&new DBNestedSet($this->parent->database,TAB_PREF.'_menu');
							while(!empty($order_old)){
							//debug($order_new);debug($order_old);
								reset($order_old);
								$val=key($order_old);
								$id=current($order_old);
								$x=$order_new[$id];
								if ($x!=$val){
									$ns->SwitchNodes($val,$x);
									$xx=$order_old[$x];
									unset($order_old[$x],$order_new[$id]);
									$order_old[$val]=$xx;
								} else {
									unset($order_old[$val],$order_new[$id]);
								}
							}
						}
					}

					$node->serialize($_POST,true);
					$updated=true;
				}
				break;
			case "del":
				$x=$node->scan($from);
				$x->serialize($_POST,true);
				break;
			case "ins":
				$ns=&new DBNestedSet($this->parent->database,TAB_PREF.'_menu');
				$ns->AddNode($node->v['id'],array_merge(array('name'=>'running_line'),$from));
				//$node->newmenu($node->v['id'],'running_line');
		   		break;
		}
	}

	function do_create($killall=true){
		if(!$this->parent->has_rights(right_WRITE))
			return $this->parent->ffirst('_loginform');

		$ns=&new DBNestedSet($this->database,TAB_PREF.'_menu');
		$votes_root=$ns->GetNodeInfo('runningline');
		if(!empty($votes_root) && $killall){
			$x=$ns->DeleteNode('runningline');
			$votes_root=null;
		}
		if(empty($votes_root)){
			$votes_root=$ns->AddNode(0,array('name'=>'runningline','type'=>type_ARTICLE));
		}
		// create all
	}

	function getNs(){
		static $node; if (isset ($node)) return $node ;
		$x=$this->parent->readRecord(array('name'=>'runningline'));
		$node=$this->parent->nodeGet($this->parent->node($x));
		if(empty($node)) return null;
		$i=0;
		return $node=readElement($node,$i);
	}

	function admin_runningline(){
		return
		smart_template(array(ADMIN_TPL,'theheader'),array(
		'header'=>$this->getPluginName(),
		'data'=>parent::admin_plugin()));
	}

	function get_runningline(){
		static $x; if (isset($x)) return $x ;
		$x='';
		$node=$this->getNs(true);
		$data=array();
		if(!empty($node->el))
		foreach($node->el as $v){
			$data[]=array('id'=>$v->v['id'],'descr'=>preg_replace("/\s+|'/m",' ',strip_tags($v->v['descr'])),'page'=>$v->v['page']);
		}
		return $x=smart_template(array(ELEMENTS_TPL,'running_line'),array('data'=>$data));
	}
}
?>