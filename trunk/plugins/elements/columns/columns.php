<?php
//<% 
/**
 * 
 *  Элемент - колонки
 *  включает в себя собственно текст,  
 *  шаблоны редактирования в админке
 *  примерные шаблоны вывода на сайте.
 * 
 */

/**
 * Заголовок нового элемента с автоматическим подписыванием его в основные функции СМС
 */
$elements[type_COLUMNS]=array(
	'type_name'=>'type_COLUMNS',
	'class_name'=>'xCOLUMNS',
	'name'=>'Колонки',
	'title_name'=>'Колонки',
	'picture'=>'columns_4s.gif'
);

// шаблон поля xCOLUMNS для админки  ?> 
 point_start('default_tpl');  %>
<!--begin:def_xCOLUMNS--><!--end:def_xCOLUMNS--> 
<% // шаблон поля xCOLUMNS для админки  ?> 
 point_start('admin_template');  
  %>
<!--begin:xCOLUMNS-->
<div  id="pg_{id}" class="droppable greenpane collapsed">
<%=TPL_elTable(0,array(
	'logo'=>array('img'=>'el_column.gif'),
	'digit6'=>array('txt'=>'Расстояние между колонками(px)','vname'=>'xdisp','noborder'=>1),
	'article'=>array('txt'=>'Колонка1{article_count0}','width'=>130,'noborder'=>1),
	'article_'=>array('data-number'=>'eq(1)','txt'=>'Колонка2{article_count1}','width'=>130,'noborder'=>1),
	'align'=>array('width'=>30)
));%>
<div style="margin-top: 0px;" class="datapane">
<!--begin:article-->
<div id="newElem_{id}" class="notepane_pane droppable">
<%=TPL_elTable1(array(
	'logo'=>array('rowspan'=>2,'txt'=>'Настройка колонки {x}'),
	'select'=>array('vname'=>'type1','noborder'=>1,'title'=>'Линия<br> ','txt'=>array(1=>'cпошная',2=>'точками',3=>'inset',4=>'outset'),'name'=>'type'),
	'digit6'=>array('width'=>80,'txt'=>'Толщина, px<br> ','vname'=>'height','noborder'=>1,'alt'=>'{alt_height}'),
	'digit6_'=>array('width'=>80,'txt'=>'длина, px<br>','vname'=>'width','noborder'=>1,'alt'=>'{alt_height}'),
	'color'=>array('width'=>120,'txt'=>'Цвет линии<br>','vname'=>'color','noborder'=>1,'alt'=>'{alt_color}'),
	'_digit6'=>array('txt'=>'Ширина колонки<br> ','vname'=>'width','noborder'=>1,'alt'=>'{alt_height}'),
	'align'=>array('width'=>30,'vname'=>'align'),
));%>
<div class="greenpane">{article}</div>
</div>
<!--end:article-->
</div>
</div>
<!--end:xCOLUMNS-->
 
<% point_start('classes_body') ; // <?php // %>

class xCOLUMNS extends xCommon {
	
	function getForm($lev=0){
		global $engine;
		$par=$this->v; 
		$par['level']=$lev;
		$rows=array();
		$article=array();
		$i=count($this->el);
		while($i++<2){
			$engine->nodeAdd(
				$this->node(),array('name'=>'article','type'=>type_ARTICLE)
			);
		}
		$i=0;
		foreach($this->el as $v){
			$data='';
			foreach($v->el as $vv){
				$data.=$vv->getForm(true);
			}
			$article[]=array('id'=>$v->v['id'],'article'=>$data);
			$par['article_count'.$i++]='&nbsp;(+)';
  		}
  		$par['article']=$article;
  		debug($par);
		return	smart_template(array(ELEMENTS_TPL,'xCOLUMNS'),$par);
	}
	
	function xCOLUMNS(){
		global $engine;
		xCommon::xCommon(
			array(
				'fields'=>array(
					'width'=>array('title'=>'ширина') // 50,70,100
					,'xtype'=>array('width'=>170,'type'=>'dropdown'
						,'list'=>array(
								array('id'=>1,'text'=>'новая колонка')
								,array('id'=>2,'text'=>'закончить колонки')
						),'xname'=>'xcolumns','title'=>'начать-продолжить/закончить') // 50,70,100
				) 
			) 
		);
	}	
	
	function getText(&$keys){
		if(!!$this->v['hidden'])
			return '';
		//debug($keys);debug('xxx');
		//	debug($this->v);
		$width=	pps($this->v['width']);
		//debug($this->parent->columns);
		if(ctype_digit($width)) $width.='px';
		if(isset($this->parent->columns)){
			if( ppi($this->v['xtype'])==2 || $width<0){
				// конец таблицы
				unset($this->parent->columns);
				return '</td></tr></table>';
			} else {
				return '</td><td'.pp($width,' style="width:','"').'>';
			}
		} else {	
			// начало таблицы
			$this->parent->columns=&$this;
			return '<table class="columns"><tr><td'.pp($width,' style="width:','"').'>';	
			// продолжение
		}
	}
	
}

//<% point_finish() %>
