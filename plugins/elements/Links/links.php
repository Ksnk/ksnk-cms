<?php
//<% 
/**
 * 
 *  Элемент - Text + картинка
 *  включает в себя собственно текст,  
 *  шаблоны редактирования в админке
 *  примерные шаблоны вывода на сайте.
 * 
 */

/**
 * Заголовок нового элемента с автоматическим подписыванием его в основные функции СМС
 */
$elements[type_LINK]=array(
	'type_name'=>'type_LINK',
	'class_name'=>'xLINK',
	'name'=>'Ссылка',
	'internal'=>true
);
/**
 * Заголовок нового элемента с автоматическим подписыванием его в основные функции СМС
 */
$elements[type_LINKS]=array(
	'type_name'=>'type_LINKS',
	'class_name'=>'xLinks',
	'name'=>'Cсылки и файлы',
	'title_name'=>'Cсылки',
	'picture'=>'link_4s.gif'
);

// шаблон поля xTP для админки  ?> 
 point_start('default_tpl');  %>
<!--begin:def_xLinks-->
<div  class="greenpane">
<%=TPL_elDefault(array(
	'logo'=>array('img'=>'def_links.gif'),
	'bui'=>array('width'=>'160','txt'=>'Мал. фото.<br>','vname'=>'tpb,tpu,tpi'),
	'font'=>array('noborder'=>1,'vname'=>'font'),
	'color'=>array('noborder'=>1,'vname'=>'color'),
	'size'=>array('noborder'=>1,'vname'=>'size'),
	'loadimg'=>array('width'=>'80','noborder'=>1,'vname'=>'pic_small'),
));%>

<table class="tahoma long ctext size11 fixed align_middle" style="margin-bottom:4px;">
	<col width="90px">
	<col width="auto">
	<tr>
		<td style="height:84px" class="align_center align_middle bgray" >
		Пример Cсылки</td>
		<td class="align_center bgdray" >
		<a id="xLINKS_def" href='#' onclick="return false;">так будет выглядеть ссылка по умолчанию</a>
		</td>
	</tr>
</table>
</div>

<!--end:def_xLinks--> 
<% point_finish () ; %> 
<script type="text/javascript">
<% point_start('changefunc_def'); 
%>
change_func[<%=type_LINKS%>]=function (){
	var o=change_func[<%=type_LINKS%>].option;
	var texto={
			color:o.color||'<%=$color_text_darkgray%>',
			'font-family':o.font||'Tahoma',
			'font-size':o.size||'11px',
			'font-weight':o.tpb==1?'bold':'normal',
			'font-style':o.tpi==1?'italic':'normal',
			'text-decoration':o.tpu==1?'underline':'none'
	};
	if(o.pic_small){
		texto['padding-left']='30px';
		texto['background']='url('+o.pic_small+') 0 60% no-repeat';
	} else {
		texto['background']="none";
		texto['padding-left']='0px';
	}
	
	if(!change_func[<%=type_LINKS%>].to){
		clearTimeout(change_func[<%=type_LINKS%>].to);
	}
	change_func[<%=type_LINKS%>].to=setTimeout(function(){
		change_func[<%=type_LINKS%>].to=false;
		$('#xLINKS_def').css(texto);
	},10);
};
<% point_finish();  %>


</script><% // шаблон поля xLinks для админки  ?> 
 point_start('admin_template');  
  %>
<!--begin:xLinks-->
<div  id="pg_{id}" class="droppable greenpane collapsed">
<%=TPL_elTable(0,array(
	'logo'=>array('img'=>'el_link.gif'),
	'columns'=>array('width'=>'auto','noborder'=>1),
	'loadimg'=>array('width'=>'auto','name'=>'small_pic_{id}','noborder'=>1),
	'links'=>array('width'=>'160','noborder'=>1)
));%>

<div style="margin-top: 0px;" class="datapane">
<div class="linkpane_pane" style="margin-bottom:2px;">
<%=TPL_elTable1(array(
	'logo'=>array('txt'=>'Настройки ссылок'),
	'radio'=>array('name'=>'ftpl','noborder'=>1,'width'=>400,'txt'=>array(1=>'выпадающее',2=>'в новом окне',3=>'вкладками'),'name'=>'target_{id}'),
	'empty'=>array(),
	'align'=>array('noborder'=>1,'width'=>30)
));%>

</div>
<!-- фото -->
<!--begin:links-->
<!--begin:article-->
<%=TPL_elTable1(array(
	'raw'=>array('width'=>90),
	'loadimg'=>array('light'=>1,'noborder'=>1,'width'=>50,'txt'=>' '),
	'input'=>array('name'=>'item_text_{id}','light'=>1,'noborder'=>1,),
	'input_'=>array('name'=>'item_url_{id}','light'=>1),
	'article'=>array('width'=>130),
	'updn'=>array('width'=>80),
	'del'=>array('width'=>30),
),'pg_{id}','insertable ');%>
<div style="margin-top: 0px;" class="datapane"><div  class="notepane_pane">
{article}</div></div>
<!--end:article-->
<!--begin:link-->
<%=TPL_elTable1(array(
	'raw'=>array('width'=>90),
	'loadimg'=>array('light'=>1,'noborder'=>1,'width'=>50,'txt'=>' '),
	'input'=>array('name'=>'item_text_{id}','light'=>1,'noborder'=>1,),
	'input_'=>array('name'=>'item_url_{id}','light'=>1),
	'updn'=>array('width'=>80),
	'del'=>array('width'=>30),
),'pg_{id}','insertable ');%>
<!--end:link--><!--end:links-->
</div>
</div>

<!--end:xLinks-->
 
<% point_start('classes_body') ; // <?php // %>

class xLink extends xElement {
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
	
	function getForm(){
		$x=smart_template(array(ELEMENTS_TPL,'link_edit_line'),$this->v);
		return	$x;
	}
	function serialize(&$formvar,$dir=false){
		xElement::serialize_var($formvar,$dir,
			array(
				'item_name_'=>'name'
				,'item_text_'=>'text'
				,'item_url_'=>'url'
				,'pic_small_'=>'pic_small'
				)
		);
		xElement::serialize_order_menu($formvar,$dir,'item_order_');
	}
}

class xLinks extends xCommon {
	
	static function get_style(&$o){
		$result=
			'color:'.pps($o['color'],'<%=$color_text_darkgray%>').';'.
			($o['tpb']?'font-weight:bold;':'').
			($o['tpi']?'font-style:italic;':'').
			($o['tpu']?'text-decoration:underline;':'').
			pp($o['font'],'font-family:',';').
			pp($o['size'],'font-size:',';');
		if($o['pic_small'])	{
			$result.='padding-left:30px;background:url('.$o['pic_small'].') 0 60% no-repeat';
		} else {
			$result.='padding-left:0;background:none;';
		}	
		return $result;
	}
	/**
	 * обновить правила css для ответственного стиля
	 * Enter description here ...
	 * @param $style
	 */
	function style_update(&$style,&$o){
		/**
		 * корректируем правилo .link {
		 */
		// значение по умолчанию
		$style=preg_replace('#(\.links\s+a\.xlink\s+{[^}]*/\*\*/)[^}]*(})#mi','\\1'.$this->get_style($o).'\\2',$style);
		
	}
	/**
	 * вставить новую ссылку  в xLinks
	 * Enter description here ...
	 * @param $tp
	 */
	function insertInto($tp){
		global $engine;
		$artnode = $this->node();
		$x=itemByType(type_LINK);
		$x=&new $x();
		//debug($this->node());
		$nodex=$x->Create(array('type'=>type_LINK),$artnode);
		error_log(__FILE__.' '.__LINE__."\n".print_r($article,true).type_LINK,3,'z:/logs/debug.log');
		return ' ';
	}
	
	function getForm($lev=0){
		$par=$this->v; 
		$par['level']=$lev;
		$data=array();
		foreach($this->el as $v){
			if($v->v['type']==type_ARTICLE){
				$article='';
				foreach($v->el as $vv){
					$article.=$vv->getForm(true);
				}
 				$data[]=array('article'=>array('id'=>$v->v['id'],'article'=>$article));
			}else {
				$data[]=array('link'=>$v->v);
			}
  		}
  		if(!empty($data)){
  			$par['links']=$data;
  			$par['links_count']='&nbsp;('.count($data).')';
  		}
  		//debug($par);
		return	smart_template(array(ELEMENTS_TPL,'xLinks'),$par);
	}
	
	function xLinks (){
		xCommon::xCommon(
		array(
			'fields'=>array(
				'item_columns'=>array('type'=>'smallinput','title'=>'Количество столбцов:'),
				'pic_small'=>array()
				,'hidden'=>array('title'=>'Текст заголовка') // верхний заголовок
				,'ftpl'=>array()
				,'target'=>array()
			) 
			,'inner'=>array(
//				'picture'=>array('width'=>125,'type'=>type_PIC,'button'=>'Доб.Фото')
				'article'=>array('width'=>125,'type'=>type_ARTICLE,'button'=>'Доб.Описание')
				,'link'=>array('width'=>125,'type'=>type_LINK,'button'=>'Доб.Ссылку')
			)
			,'align'=>true
		));		
	}

	function getText(&$keys){
		if(!!$this->v['hidden'])
			return '';
		$id=ppi($this->v['id']);
		$par='';
		$base='href';

  		$_cols=ppi($this->v['item_columns'],1);
  		$_align=$this->aligns(ppi($this->v['item_align']));

  		$row=array();
  		$row_article=array();
 		foreach($this->el as $v){
 			$iid=$v->v['id'];
 			if($v->v['type']==type_ARTICLE) {
 			$x=array(
 				 'url'=>'?do=page&id='.$v->v['id']
 				,'class'=>'url_page'
 				,'text'=>$v->v['item_text']);
 			} else {
 				$url=TO_URL($v->v['url']);
 				$x=array(
 				 'url'=>$url
 				,'class'=>pps($v->v['class'])
 				,'text'=>$v->v['text']);
 			}
 			//debug($url);	
 			if((strpos($url,'?do=')===false)
 			  && !preg_match('/\....?$/',$url)
 			  ) {
 				$x['target']=' target="_blank"';
 			}
 			//else debug($keys['item_url_'.$iid]);
 			if($v->v['type']==type_ARTICLE) {
 				$row_article[]=$x;
 			} else {	
 				$row[]=$x;
 			}
 		}
 		$rows=array();
 		$row=array_chunk(array_merge($row_article,$row),$_cols);
 		
 		if(!empty($row))
 			$row[count($row)-1][0]['last']=true;
 		//debug($row);	
 			
 		for($i=0;$i<count($row);$i++){
 			$rows[]=array('link'=>$row[$i]);
 		}
 		
 		if(!empty($rows))
 		$par.=smart_template(array(ELEMENTS_TPL,'katalogx'),
 				array('align'=>$_align,$base=>$rows)
 			);
  		return $par;
	}
}
//<% point_finish() %>
