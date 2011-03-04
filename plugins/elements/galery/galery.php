<?php
//<% 
/**
 * 
 *  ������� - �����
 *  �������� � ���� ���������� �����,  
 *  ������� �������������� � �������
 *  ��������� ������� ������ �� �����.
 * 
 */

/**
 * ��������� ������ �������� � �������������� ������������� ��� � �������� ������� ���
 */
$elements[type_GALLERY]=array(
	'type_name'=>'type_GALLERY',
	'class_name'=>'x_GALLERY',
	'name'=>'�������',
	'title_name'=>'�������',
	'picture'=>'galery_4s.gif'
);

// ������ ���� xTP ��� �������  ?> %>

<% point_start('default_tpl');  %>
<!--begin:def_x_GALLERY--><!--end:def_x_GALLERY--> 
<% // ������ ���� x_GALLERY ��� �������  ?> 
 point_start('admin_template');  
  %>
<!--begin:x_GALLERY-->
<div  id="pg_{id}" class="droppable greenpane collapsed">
<%=TPL_elTable(0,array(
	'logo'=>array('img'=>'el_galery.gif'),
	'columns'=>array('txt'=>'�������<br>','vname'=>'item_columns','alt'=>'{alt_column}'),
	'2xdigit6'=>array('txt'=>'���. ����<br>','vname'=>'sizex,sizey','alt'=>'{alt_sizex},{alt_sizey}'),
	'2xdigit6_'=>array('txt'=>'���. ����<br>','noborder'=>1,'vname'=>'bsizex,bsizey','alt'=>'{alt_bsizex},{alt_bsizey}'),
	'foto'=>array('width'=>130,'noborder'=>1),
	'align'=>array('width'=>30,'vname'=>'align'),
));%>
<div style="margin-top: 0px;" class="datapane">
<!-- ���� -->
<div class="fotopane_pane">
<%=TPL_elTable1(array(
	'logo'=>array('txt'=>'��������� ����'),
	'radio'=>array('txt'=>array(1=>'��� ����',2=>'���� ����',3=>'�������'),'vname'=>'ftpl'),
	'check'=>array('width'=>'80','txt'=>'�����','vname'=>'border','alt'=>'{alt_border|off}'),
	'2xdigit6'=>array('width'=>'160','txt'=>'','vname'=>'bx,by','alt'=>'{alt_bx},{alt_by}'),
	'color'=>array('width'=>'150','txt'=>'����:','vname'=>'color','alt'=>'{alt_color}'),
//	'align'=>array('width'=>'50','name'=>'pic_align'),
));%>

<!--begin:picture-->
<%=TPL_elTable1(array(
	'raw'=>array('width'=>90),
	'picture'=>array('light'=>1),
	'updn'=>array('width'=>80),
	'del'=>array('width'=>30),
));%>

<!--end:picture-->
</div></div>
</div>
<!--end:x_GALLERY-->
 
<% point_start('classes_body') ; // <?php // %>

class x_GALLERY extends xCommon {
	
	function getForm($lev=0){
		global $engine;
		$par=$this->v; 
		$def=$engine->export('defaultValues','defVal',type_NEWTEXTPIC);
		foreach(array(
				'bx'=>'',
				'by'=>'',
				'border'=>'',
				'color'=>'',
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
  		//debug($par);
		return	smart_template(array(ELEMENTS_TPL,'x_GALLERY'),$par);
	}
	
	/**
	 * �������� ����� ������� � ������ xTP
	 * @param $tp
	 */
	// ������� ���� �������������� ��������� + ������������ ��� �� ������
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
	
	function x_GALLERY(){
		xCommon::xCommon(
		array(
			'fields'=>array(
				'item_columns'=>array('type'=>'smallinput','title'=>'���������� ��������:')
				,'border'=>array('type'=>'txt') // 50,70,100
				,'ftpl'=>array('type'=>'txt') // 50,70,100
				,'sizex'=>array('type'=>'txt') // 50,70,100
				,'sizey'=>array('type'=>'txt') // 50,70,100
				,'bsizex'=>array('type'=>'txt') // 50,70,100
				,'bsizey'=>array('type'=>'txt') // 50,70,100
				,'pic_align'=>array('type'=>'txt') // 50,70,100
				,'comment'=>array('type'=>'txt') // 50,70,100
				,'target'=>array('type'=>'txt') // 50,70,100
				,'hidden'=>array('title'=>'����� ���������') // ������� ���������
			) 
			,'inner'=>array(
				'picture'=>array('width'=>125,'type'=>type_PIC,'button'=>'���.����')
//				,'article'=>array('width'=>125,'type'=>type_ARTICLE,'button'=>'���.��������')
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
 				$vv['style']=xTP::get_style($this->v,'border');
 				$row[]=$vv;
 			}
		}

 		if(!empty($row)){
 			$row[0]['first']=true;
 		}
		//debug($rows);
 		return smart_template(array(ELEMENTS_TPL,'katalog'),
 			array(
 			'gallery'=>array(
 				'align'=>$this->aligns(ppi($this->v['item_align'])),
 				'pict'=>$row,
 				'ftpl'=>ppi($this->v['ftpl'],2),
 			))
 		);
	}

}

//<% point_finish() %>
