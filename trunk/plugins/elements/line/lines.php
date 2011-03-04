<?php
//<% 
/**
 * 
 *  Элемент - линия
 *  включает в себя собственно текст,  
 *  шаблоны редактирования в админке
 *  примерные шаблоны вывода на сайте.
 * 
 */

/**
 * Заголовок нового элемента с автоматическим подписыванием его в основные функции СМС
 */
$elements[type_LINE]=array(
	'type_name'=>'type_LINE',
	'class_name'=>'x_LINE',
	'name'=>'Гориз. полоса',
	'title_name'=>'Линия',
	'picture'=>'line_4s.gif',
	'deftpl'=>'tpl_elements',
);

// шаблон поля xTP для админки  ?> %>
<script type="text/javascript">
<% point_start('changefunc_def'); 
%>
change_func[<%=type_LINE%>]=function (){
	var o=change_func[<%=type_LINE%>].option,options={
		overflow:'hidden'
		,height:parseInt(o.height) || 2
		,backgroundColor:o.color||'red'	
	};
	if(o.width && o.width.match(/\%$/))
		options.width=o.width;
	else
		parseInt(o.width) || '100%';
	switch(parseInt(o.align)||0){
	case 1: // center
		options.margin='0 auto';
		break;
	case 2: // right
		options.margin='0 0 0 auto';
		break;
	default: // left
		options.margin='0 auto 0 0';
		break;
	}
	var brdsize=Math.round((options.height+2)/3);
	switch(parseInt(o.btype)||0){
	case 1:// solid
	case 2:// doted	
		options.border='0';
		break;
	case 3:// inset	
		options.border=brdsize+'px inset';
		break;
	case 4:// outset	
		options.border=brdsize+'px outset';
		break;
}
	
	if(change_func[<%=type_LINE%>].to){
		clearTimeout(change_func[<%=type_LINE%>].to);
	}
	change_func[<%=type_LINE%>].to=setTimeout(function(){
		change_func[<%=type_LINE%>].to=false;
		$('#TP_x_LINE').css(options);
	},10);
};
<% point_finish();  %>
</script>
<% point_start('default_jtpl');  %>
{% block def_x_LINE %}
<div  class="greenpane">
<%=jTPL_elDefault(array(
	'logo'=>array('img'=>'def_line.gif'),
	'digit6'=>array('txt'=>'Толщина, px<br>','vname'=>'height'),
	'digit6_'=>array('txt'=>'Длина, px, %<br>','vname'=>'width'),
	'color'=>array('txt'=>'Цвет линии<br>','vname'=>'color'),
	'select'=>array('width'=>160,'vname'=>'btype','title'=>'доп описание.<br>','txt'=>$LINE_TYPES_ARRAY,'name'=>'btype'),
	'align'=>array('vname'=>'align','noborder'=>1),
));%>
<table class="tahoma long ctext size11 fixed align_middle" style="margin-bottom:4px;">
	<col width="90px">
	<col width="auto">
	<tr>
		<td style="height:84px" class="align_center align_middle bgray" >
		Пример линии</td>
		<td class="align_center bgdray" >
		<div id="TP_x_LINE"></div>
		</td>
	</tr>
</table>
</div>
{% endblock %}
<% // шаблон поля x_LINE для админки  ?> 
 point_start('default_jtpl');  
  %>
{% block x_LINE %}
<div  id="pg_{{id}}" class="droppable greenpane collapsed">
<%=jTPL_elTable(0,array(
	'logo'=>array('img'=>'el_line.gif'),
	'digit6'=>array('txt'=>'Толщина, px<br>','vname'=>'height','noborder'=>1,'alt'=>'{{alt_height}}'),
	'digit6_'=>array('txt'=>'Длина, px, %<br>','vname'=>'width','noborder'=>1,'alt'=>'{{alt_width}}'),
	'color'=>array('txt'=>'Цвет линии<br>','vname'=>'color','noborder'=>1,'alt'=>'{{alt_color}}'),
	'select'=>array('width'=>160,'vname'=>'btype','alt'=>'{{alt_type}}','noborder'=>1,'title'=>'Вид линии<br>','txt'=>$LINE_TYPES_ARRAY),
	'align'=>array('width'=>30,'vname'=>'align'),
));%>
</div>
{% endblock %}
 
<% point_start('classes_body') ; // <?php // %>

class x_LINE extends xCommon {
	
	/**
	 * обновить правила css для ответственного стиля
	 * Enter description here ...
	 * @param $style
	 */
	function style_update(&$style,&$o){
		/**
		 * корректируем правилo .xline {
		 */
		// значение по умолчанию
		$text=
			'background-color:'.pps($o['tcolor'],'<%=$color_text_darkgray%>').
			';height:'.$o['height'].'px'.
			';';
		if(preg_match('/^\d+%$/',$o['width']))
			$text.='width:'.$o['width'].';';
		else	
			$text.='width:'.pps($o['width'],'100%').';';
		switch(ppi($o['align'])){	
			case 1:
				$text.='margin:0 auto; ';
				break;
			case 2:
				$text.='margin:0 0 0 auto;';
				break;
			default:
				$text.='margin:0 auto 0 0;';
		};
		$brdsize=round(($o['height']+2)/3);
		switch(ppi($o['btype'])){
			case 1:// solid
			case 2:// doted	
				$text.='border:0;';
			case 3:// inset	
				$text.='border:'.$brdsize.'px inset;';
				break;
			case 4:// outset	
				$text.='border:'.$brdsize.'px outset;';
				break;
		}

		$style=preg_replace('#(\.xline\s+{[^}]*/\*\*/)[^}]*(})#mi','\\1'.$text.'\\2',$style);
		
	}
	
	function getForm($lev=0){
		global $engine;
		$par=$this->v; 
		$def=$engine->export('defaultValues','defVal',type_LINE);
		foreach(array(
				'width'=>'&lt;--&gt;',
				'height'=>'^-_',
				'color'=>'-X-',
				'btype'=>''
		) as $k=>$v)
		{
			$par['alt_'.$k]=pps($def[$k],$v);
		}
		return	smart_template(array('tpl_elements','_x_LINE'),$par);
	}
	
	function x_LINE(){
		xCommon::xCommon(
			array(
				'fields'=>array(
					'height'=>array('title'=>'толщина (20px, 5pt , 30%)'), // 50,70,100
					'width'=>array('title'=>'длина (20px, 5pt , 30%)'), // 50,70,100
					'color'=>array('title'=>'цвет #FFFFFF, rgb(255,255,255), white'), // 50,70,100
					'btype'=>array('title'=>'цвет #FFFFFF, rgb(255,255,255), white'), // 50,70,100
					'align'=>array('title'=>'цвет #FFFFFF, rgb(255,255,255), white') // 50,70,100
				) 
			)
		);
	}	
	
	function getText(&$keys){
		if(!!$this->v['hidden'])
			return '';
		$style=array();
		foreach(array('background-color'=>'color','height','width') as $k=>$a){
			if(!empty($this->v[$a]))
				$style[]=sprintf('%s:%s;',is_int($k)?$a:$k,$this->v[$a]);
		}
		$style=implode('',$style);
		return '<div class="xline"'.pp($style,' style="','"').'></div>';
	}
}
	
//<% point_finish() %>
