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
$elements[type_NEWTEXTPIC]=array(
	'type_name'=>'type_NEWTEXTPIC',
	'class_name'=>'xTP',
	'name'=>'Текст+Фото',
	'title_name'=>'Текст',
	'picture'=>'text_4s.gif'
);
// шаблон поля xTP для админки  ?> %>

<% 
/**
 * шаблон дефолтных значений
 */

point_start('default_tpl');  %>
 <!--begin:def_xTP-->
<div  class="greenpane">
<%=TPL_elDefault(array(
	'logo'=>array('img'=>'def_foto.gif'),
	'2xdigit6'=>array('width'=>'160','txt'=>'Мал. фото.<br>','vname'=>'sizex,sizey'),
	'2xdigit6_'=>array('width'=>'160','txt'=>'Бол. фото.<br>','vname'=>'bsizex,bsizey'),
	'check'=>array('width'=>'100','txt'=>'Рамка.<br>','vname'=>'border','noborder'=>1),
	'color'=>array('txt'=>'Цвет рамки.<br>','vname'=>'color','noborder'=>1),
	'2xdigit6__'=>array('txt'=>'толщина рамки.<br>','vname'=>'bx,by'),
));%>

<table class="tahoma long ctext size11  align_middle" style="margin-bottom:4px;">
	<col width="90px">
	<col width="150px">
	<col width="auto">
	<col width="1px">
	<tr>
		<td class="align_center align_middle bgray" >
		Пример фото</td>
		<td class="align_center bgdray" style="padding:10 8px;">
		<img id="xTP_defpicture" src="img/test.jpg">
		</td>
		<td class="align_center bgdray" >
		Фотографии будут автоматически вписываться в выставленные габариты.<br>
		Пропорции фотографий не изменятся.
		</td><td  class="bgdray"><div class="stick" style="height:60px;"></div></td>
	</tr>
</table>

</div>
<div  class="greenpane">
<%=TPL_elDefault(array(
	'logo'=>array('img'=>'def_text.gif'),
	'bui'=>array('width'=>'160','txt'=>'Мал. фото.<br>','vname'=>'tpb,tpu,tpi'),
	'font'=>array('noborder'=>1,'vname'=>'tfont'),
	'color'=>array('noborder'=>1,'vname'=>'tcolor'),
	'size'=>array('noborder'=>1,'vname'=>'tsize'),
	'radio'=>array('width'=>160,'vname'=>'doptarget','title'=>'доп описание.','txt'=>array(1=>'Выпадающее<br>',2=>'Новое окно'),'name'=>'dopop'),
));%>
<table class="tahoma long ctext size11 fixed align_middle" style="margin-bottom:4px;">
	<col width="90px">
	<col width="auto">
	<tr>
		<td style="height:84px" class="align_center align_middle bgray" >
		Пример текста</td>
		<td class="align_center bgdray" id='xTP_deftext'>
		Фотографии будут автоматически вписываться в выставленные габариты.<br>
		Пропорции фотографий не изменятся.
		</td>
	</tr>
</table>
</div>
<!--end:def_xTP--> 
<% point_finish () ; %> 
<script type="text/javascript">
<% point_start('changefunc_def'); 
%>
change_func[<%=type_NEWTEXTPIC%>]=function (){
	var o=change_func[<%=type_NEWTEXTPIC%>].option,options={
		width:parseInt(o.sizex) || 90	
		,height:parseInt(o.sizey) || 90
//		,borderWidth:'2px'
//		,borderColor:'red'
	};
	if (o.border!='0' && o.border!='') {
		options['border-style']='solid';
		options['border-top-width']=(o.by||'2')+'px';
		options['border-bottom-width']=(o.by||'2')+'px';
		options['border-left-width']=(o.bx||'2')+'px';
		options['border-right-width']=(o.bx||'2')+'px';
		options['border-color']=o.color||'red';
	} else {
		options['border']='0';
	}
	var texto={
			color:o.tcolor||'<%=$color_text_darkgray%>',
			'font-family':o.tfont||'Tahoma',
			'font-size':o.tsize||'11px',
			'font-weight':o.tpb==1?'bold':'normal',
			'font-style':o.tpi==1?'italic':'normal',
			'text-decoration':o.tpu==1?'underline':'none'
	};
	
	if(change_func[<%=type_NEWTEXTPIC%>].to){
		clearTimeout(change_func[<%=type_NEWTEXTPIC%>].to);
	}
	change_func[<%=type_NEWTEXTPIC%>].to=setTimeout(function(){
		change_func[<%=type_NEWTEXTPIC%>].to=false;
		$('#xTP_defpicture').css(options);
		$('#xTP_deftext').css(texto);
	},10);
};
<% point_finish();  %>


</script>
<% // шаблон поля xTP для админки  ?> 
 point_start('admin_template');  %>
<!--begin:xTP-->
<div  id="pg_{id}" class="droppable greenpane collapsed">
<%=TPL_elTable(0,array(
	'logo'=>array('img'=>'el_text.gif'),
	'html'=>array('width'=>'auto','noborder'=>1),
	'article'=>array('width'=>130,'noborder'=>1),
	'foto'=>array('width'=>130,'noborder'=>1),
	'align'=>array('width'=>30)
));%>

<div style="margin-top: 0px;" class="datapane">
<div id="newElem_{id}" class="notepane_pane droppable">
<%=TPL_elTable1(array(
	'logo'=>array('txt'=>'Настройки описания'),
	'input'=>array('width'=>'auto','noborder'=>1,'txt'=>'Название ссылки:','name'=>'comment_{id}', 'alt'=>'Подробнее...'),
	'radio'=>array('width'=>'auto','noborder'=>1,'txt'=>array(1=>'выпадающее',2=>'в новом окне'),'name'=>'target_{id}'),
));%>
<div class="greenpane">{article}</div>
</div>
<!-- фото -->
<div class="fotopane_pane">
<%=TPL_elTable1(array(
	'logo'=>array('txt'=>'Настройки фото'),
	'2xdigit6'=>array('width'=>'160','txt'=>'Мал:','vname'=>'sizex,sizey','alt'=>'{alt_sizex},{alt_sizey}'),
	'2xdigit6_'=>array('width'=>'160','txt'=>'Бол:','vname'=>'bsizex,bsizey','alt'=>'{alt_bsizex},{alt_bsizey}'),
	'check'=>array('width'=>'80','txt'=>'Рамка','vname'=>'border','alt'=>'{alt_border|off}'),
	'check_'=>array('width'=>'180','txt'=>'Показывать одно фото','name'=>'ftpl_{id}'),
	'align'=>array('width'=>'50','name'=>'pic_align'),
));%>

<!--begin:picture-->
<%=TPL_elTable1(array(
	'raw'=>array('width'=>90),
	'picture'=>array('light'=>1),
	'updn'=>array('width'=>80),
	'del'=>array('width'=>30),
));%>

<!--end:picture-->
</div>
</div>

</div>
<!--end:xTP-->
 
<% point_start('classes_body') ; // <?php // %>

class xTP extends xCommon {
	static function get_style(&$o,$type='text'){
		if ($type=='text')
		return 
			'color:'.pps($o['tcolor'],'<%=$color_text_darkgray%>').';'.
			($o['tpb']?'font-weight:bold;':'').
			($o['tpi']?'font-style:italic;':'').
			($o['tpu']?'text-decoration:underline;':'').
			pp($o['tfont'],'font-family:',';').
			pp($o['tsize'],'font-size:',';');
		else
		if($o['border']){
			return 
			'border-style:solid;'.
			'border-top-width:'.ppi($o['by'],10).'px;'.
			'border-bottom-width:'.ppi($o['by'],10).'px;'.
			'border-left-width:'.ppi($o['bx'],10).'px;'.
			'border-right-width:'.ppi($o['bx'],10).'px;'.
			'border-color:'.pps($o['color'],'<%=$color_silver_bg%>').';';
		} else {
			return 'border:none;';
		}
		
	}
	/**
	 * обновить правила css для ответственного стиля
	 * Enter description here ...
	 * @param $style
	 */
	function style_update(&$style,&$o){
		/**
		 * корректируем правилo .text {
		 */
		// значение по умолчанию
		$style=preg_replace('#(\.text\s+{[^}]*/\*\*/)[^}]*(})#mi','\\1'.$this->get_style($o).'\\2',$style);
		
		/**
		 * корректируем правилo .text .cat_border img
		 */
		// значение по умолчанию
		// $border="	border: 10px solid <%=$color_silver_bg%>;"
		$style=preg_replace('#(\.cat_border\s+img\s+{[^}]*/\*\*/)[^}]*(})#mi','\\1'.$this->get_style($o,'picture').'\\2',$style);
	}
	/**
	 * вставить новый элемент в статью xTP
	 * Enter description here ...
	 * @param $tp
	 */
	function insertInto($tp){
		global $engine;
		$article=false;
		foreach($this->el as $v){
			if($v->v['type']!=type_PIC){
 				$article=$v;
 				break;
			}
  		}
  		if(empty($article)){
  			$artnode=$engine->nodeAdd($this->node(),array('name'=>'article','type'=>type_ARTICLE));
  		} else {
  			$artnode = $article->node();
  		}
		$x=itemByType($tp);
		$x=&new $x();
		//debug($this->node());
		$nodex=$x->Create(array('type'=>$tp),$artnode);
//		error_log(__FILE__.' '.__LINE__."\n".print_r($article,true).$tp,3,'z:/logs/debug.log');
		return ' ';
	}
	
	function getForm($lev=0){
		global $engine;
		$par=$this->v; 
		$par['level']=$lev;
		$par['text_x']=strip_tags(pps($par['item_text']));
		if(preg_match('/(?:\S+\s+){30}/',$par['text_x'],$m)){
			$par['text_x']=$m[0].' ...';
		}
		$rows=array();
		$article=array();
		$def=$engine->export('defaultValues','defVal',type_NEWTEXTPIC);
		foreach(array(
				'sizex'=>'-X-',
				'sizey'=>'-Y-',
				'bsizex'=>'-X-',
				'border'=>'-X-',
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
 				
			} else	
 				$article=$v;
  		}
  		if(!empty($rows)){
  			$par['picture']=$rows;
  			$par['foto_count']='&nbsp;('.count($rows).')';
  		}
		if(!empty($article)){
			$data='';
			foreach($article->el as $v){
				$data.=$v->getForm(true);
			}
  			$par['article']=$data;
  			$par['article_count']='&nbsp;(+)';
  		}
  		//debug($par);
		return	smart_template(array(ELEMENTS_TPL,'xTP'),$par);
	}
	
	function getText(&$keys,$nlast=false){
		global $engine;
		if(!!$this->v['hidden'])
			return '';
		$key = array();
		$param=array(
				'the_text'=>$this->v['item_text'],
 			);
		$rows=array();
		$articles=array();
		foreach($this->el as $v){
 			if($v->v['type']==type_PIC){
 				$vv=$v->getData();
 				$vv['style']=$this->get_style($this->v,'border');
 				$rows['pict'][]=$vv;
 			}
 			else {
 				$articles['links'][]=$v->getData() ;
 			}	
  		}
 		if(!empty($rows)){
 			$rows['align']=$this->aligns($this->v['item_align']);
 			$rows['pict'][0]['first']=true;
 			$param['ftpl'.pps($this->v['ftpl'],2)]=$rows;
 			
 		}
 		if(!empty($articles)){
 			$param['articles']=$articles;
 			$param['articles']['links'][0]['item_text']=$this->v['comment'];
 		}
 		//debug($param);
 		return smart_template(array(ELEMENTS_TPL,pps($engine->tpl_elements,'katalog')),
 			array('last'=>!$nlast,
 			'ajax'=>!!$engine->is_ajax,
 			'textpic'=>$param)
 		);
 	}
		
	function xTP(){
		xCommon::xCommon(array(
			'fields'=>array(
				'item_text'=>array('type'=>'html','title'=>'текст для списка') // 50,70,100
		// фото панель
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
				,'article'=>array('width'=>125,'type'=>type_ARTICLE,'button'=>'Доб.Описание')
			)
			,'align'=>true
		));
	}	
}
//<% point_finish() %>