<?php
//<% 
/**
 * 
 *  ������� - �������
 *  �������� � ���� ���������� �����,  
 *  ������� �������������� � �������
 *  ��������� ������� ������ �� �����.
 * 
 */

/**
 * ��������� ������ �������� � �������������� ������������� ��� � �������� ������� ���
 */
$elements[type_COLUMNS]=array(
	'type_name'=>'type_COLUMNS',
	'class_name'=>'xCOLUMNS',
	'name'=>'�������',
	'title_name'=>'�������',
	'picture'=>'columns_4s.gif'
);

// ������ ���� xCOLUMNS ��� �������  ?> 
 point_start('default_tpl');  %>
<!--begin:def_xCOLUMNS--><!--end:def_xCOLUMNS--> 
<% // ������ ���� xCOLUMNS ��� �������  ?> 
 point_start('admin_template');  
  %>
<!--begin:xCOLUMNS-->
<div  id="pg_{id}" class="droppable greenpane collapsed">
<%=TPL_elTable(0,array(
	'logo'=>array('img'=>'el_column.gif'),
	'digit6'=>array('txt'=>'���������� ����� ���������(px)','vname'=>'xdisp','noborder'=>1),
	'article'=>array('txt'=>'�������1{article_count0}','width'=>130,'noborder'=>1),
	'article_'=>array('data-number'=>'eq(1)','txt'=>'�������2{article_count1}','width'=>130,'noborder'=>1),
	'align'=>array('width'=>30)
));%>
<div style="margin-top: 0px;" class="datapane">
<!--begin:article-->
<div id="newElem_{id}" class="notepane_pane droppable">
<%=TPL_elTable1(array(
	'logo'=>array('rowspan'=>2,'txt'=>'��������� ������� {x}'),
	'select'=>array('vname'=>'type1','noborder'=>1,'title'=>'�����<br> ','txt'=>array(1=>'c������',2=>'�������',3=>'inset',4=>'outset'),'name'=>'type'),
	'digit6'=>array('width'=>80,'txt'=>'�������, px<br> ','vname'=>'height','noborder'=>1,'alt'=>'{alt_height}'),
	'digit6_'=>array('width'=>80,'txt'=>'�����, px<br>','vname'=>'width','noborder'=>1,'alt'=>'{alt_height}'),
	'color'=>array('width'=>120,'txt'=>'���� �����<br>','vname'=>'color','noborder'=>1,'alt'=>'{alt_color}'),
	'_digit6'=>array('txt'=>'������ �������<br> ','vname'=>'width','noborder'=>1,'alt'=>'{alt_height}'),
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
					'width'=>array('title'=>'������') // 50,70,100
					,'xtype'=>array('width'=>170,'type'=>'dropdown'
						,'list'=>array(
								array('id'=>1,'text'=>'����� �������')
								,array('id'=>2,'text'=>'��������� �������')
						),'xname'=>'xcolumns','title'=>'������-����������/���������') // 50,70,100
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
				// ����� �������
				unset($this->parent->columns);
				return '</td></tr></table>';
			} else {
				return '</td><td'.pp($width,' style="width:','"').'>';
			}
		} else {	
			// ������ �������
			$this->parent->columns=&$this;
			return '<table class="columns"><tr><td'.pp($width,' style="width:','"').'>';	
			// �����������
		}
	}
	
}

//<% point_finish() %>
