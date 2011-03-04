<?php
/**
 * плагин - Фото - видео для Andreasgroup
 */

class fotovideo extends ml_plugin {
	function fotovideo(&$parent){
		parent::ml_plugin($parent);
		$this->head_tpl=array('tpl_fotovideo','fv_head');
		parent::_init(
		array(
		'title'=>'Фото и видео'
		,'fields'=>array(
					array('Фото','pic_small','picture'),
					array('Описание','text','text_edit'),
					array('ссылка','link','link'),
					array('Сорт.','item_order','dontshow'=>true)
		)
		,'sort'=>true
		,'base'=>'_fotovideo'
		,'orderbystr'=>' order by `item_order` DESC'
		,'prefix'=>'fv'));
	}
	
	function admin_fotovideo(){
		return
			smart_template(array(ADMIN_TPL,'theheader'),array(
			'header'=>$this->getPluginName(),
			'data'=>parent::admin_plugin()));		
	}

	function getdata(){
		$res=$this->database->select('select * from ?_fotovideo  order by `item_order` DESC');
		foreach($res as $k=>$v){
			$sm=TO_URL($v['pic_small'],' ');
			if(!empty($sm)){
				$pic= new xPic();
				$pic->v=array(
					'pic_comment'=>'',
					'item_url'=>$res[$k]['link'],
					'pic_small'=>$v['pic_small'],
					'pic_big'=>$v['pic_big']
				);
				$res[$k]['img']=$pic->getData();
			}
		}
				debug($res);
		
		return smart_template(array('tpl_fotovideo','fv_list'),array('list'=>$res));
	}
	
	function do_create($killall=true){
		if(!$this->parent->has_rights(right_WRITE))
			return $this->parent->ffirst('_loginform');
		if($killall){
			$this->database->query('DROP TABLE IF EXISTS ?_news;');
		}
		$this->database->query("CREATE TABLE ?_fotovideo (
  `id` int(5) NOT NULL auto_increment,
  `pic_small` varchar(255),
  `link` varchar(255),
  `item_order` int(11),
  `text` TEXT,
  PRIMARY KEY  (`id`)
);");
	}

}
