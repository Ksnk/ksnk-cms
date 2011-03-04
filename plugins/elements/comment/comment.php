<?php
//<% 
/**
 * 
 *  Элемент - комментарий
 *  список комментариев с сортировкой автоматической по дате.
 *  отросток к плагину qa
 * 
 */

/**
 * Заголовок нового элемента с автоматическим подписыванием его в основные функции СМС
 */
$elements[type_COMMENT]=array(
	'type_name'=>'type_COMMENT',
	'class_name'=>'x_COMMENT',
	'name'=>'Комментарий',
	'title_name'=>'Коммент',
	'picture'=>'comment_4s.gif'
);

// шаблон поля xTP для админки  ?> 
 point_start('default_tpl');  %>
<!--begin:def_x_COMMENT-->

<!--end:def_x_COMMENT--> 
<% // шаблон поля x_COMMENT для админки  ?> 
 point_start('admin_template');  
  %>
<!--begin:x_COMMENT-->
<div  id="pg_{id}" class="droppable greenpane collapsed">
<%=TPL_elTable(0,array(
	'logo'=>array('img'=>'el_comment.gif'),
	'empty'=>array('noborder'=>1),
	'digit6'=>array('noborder'=>1,'width'=>130,'txt'=>'Количество на странице<br>','vname'=>'perpage'),
	'comment'=>array('noborder'=>1,'width'=>130,'txt'=>'Доб. коммент{comment_cnt}'),
	'align'=>array('width'=>30,'vname'=>'align'),
));%>
<div style="margin-top: 0px;" class="datapane">
<!-- фото -->
<div class="commentpane_pane">
<%=TPL_elTable1(array(
	'logo'=>array('txt'=>'Комментарии'),
	'empty'=>array('dark'=>1,'txt'=>'Дата'),
	'empty_'=>array('dark'=>1,'txt'=>'Имя'),
	'_empty'=>array('dark'=>1,'txt'=>'Цитата'),
	'empty__'=>array('dark'=>1,'txt'=>'Комментарий'),
	'align'=>array('width'=>'50','name'=>'pic_align'),
));%>

<!--begin:comment-->
<%=TPL_elTable1(array(
	'raw'=>array('width'=>90),
	'picture'=>array('light'=>1),
	'updn'=>array('width'=>80),
	'del'=>array('width'=>30),
));%>

<!--end:comment-->
</div></div>
</div>
<!--end:x_COMMENT-->
 
<% point_start('classes_body') ; // <?php // %>

class x_COMMENT extends xCommon {
	
	function getForm($lev=0){
		global $engine;
		$par=$this->v; 
		$def=$engine->export('defaultValues','defVal',type_NEWTEXTPIC);
		foreach(array(
				'sizex'=>'-X-',
				'sizey'=>'-Y-',
				'bsizex'=>'-X-',
				'bsizey'=>'-Y-',
		) as $k=>$v)
		{
			$par['alt_'.$k]=pps($def[$k],$v);
		}
		foreach($this->el as $v){
			if($v->v['type']==type_PIC){
 				if(isset($v->v['pic_small']) && strlen($v->v['pic_small'])>120)$v->v['pic_small']='xxx';
				if(isset($v->v['pic_big']) && strlen($v->v['pic_big'])>120)$v->v['pic_big']='xxx';
				$rows[]=$v->v;
 				
			} 
  		}
  		if(!empty($rows)){
  			$par['picture']=$rows;
  			$par['foto_count']='&nbsp;('.count($rows).')';
  		}
		return	smart_template(array(ELEMENTS_TPL,'x_COMMENT'),$par);
	}
	
	/**
	 * вставить новый элемент в комментарий
	 * Enter description here ...
	 * @param $tp
	 */
	// вставка сюда дополнительных элементов + модифицируем тип на Артикл
	function insertInto($tp){
		global $engine;
		$artnode = $this->node();
		$x=itemByType($tp);
		$x=&new $x();
		//debug($this->node());
		$nodex=$x->Create(array('type'=>$tp),$artnode);
		$this->v['type']=type_ARTICLE;
		$engine->writeRecord($this->v);
		error_log(__FILE__.' '.__LINE__."\n".print_r($article,true).$tp,3,'z:/logs/debug.log');
		return ' ';
	}
	
	function x_COMMENT(){
		xCommon::xCommon(
		array(
			'fields'=>array(
				'item_columns'=>array('type'=>'smallinput','title'=>'Количество столбцов:')
				,'border'=>array('type'=>'txt') // 50,70,100
				,'ftpl'=>array('type'=>'txt') // 50,70,100
				,'sizex'=>array('type'=>'txt') // 50,70,100
				,'sizey'=>array('type'=>'txt') // 50,70,100
				,'bsizex'=>array('type'=>'txt') // 50,70,100
				,'bsizey'=>array('type'=>'txt') // 50,70,100
				,'pic_align'=>array('type'=>'txt') // 50,70,100
				,'comment'=>array('type'=>'txt') // 50,70,100
				,'target'=>array('type'=>'txt') // 50,70,100
				,'hidden'=>array('title'=>'Текст заголовка') // верхний заголовок
		) 
			,'inner'=>array(
				'picture'=>array('width'=>125,'type'=>type_PIC,'button'=>'Доб.Фото')
//				,'article'=>array('width'=>125,'type'=>type_ARTICLE,'button'=>'Доб.Описание')
			)
			,'align'=>true
		));
	}

	function getText(&$keys){
		global $engine;
		if(!!$this->v['hidden'])
			return '';
		$key = array();
		$this->serialize($key);
		$row=array();
		foreach($this->el as $v){
 			//$row[]=$res;
 			if($v->v['type']==type_PIC){
 				$vv=$v->getData();
 				$vv['border']=ppi($this->v['border'])!=0;
 				$row[]=$vv;
 			}
 			if(count($row)>=ppi($this->v['item_columns'],1)){
			$x=array('pict'=>$row) ;$x['row1']=&$x;$x['row2']=&$x;
 			$rows[]=$x ;
 				$row=array();
 			}
		}

		if(!empty($row)){
			$x=array('pict'=>$row) ;$x['row1']=&$x;$x['row2']=&$x;
 			$rows[]=$x ;
		}
 		//debug($rows);
 		return smart_template(array(ELEMENTS_TPL,'katalog'),
 			array(
 			'gallery'=>array(
 				'align'=>$this->aligns(ppi($this->v['item_align'])),
 				'row'=>$rows
 			))
 		);
	}

}

//<% point_finish() %>
