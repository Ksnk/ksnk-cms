<?php
//<% 
/**
 * 
 *  Элемент - заголовок
 *  включает в себя собственно текст,  
 *  шаблоны редактирования в админке
 *  примерные шаблоны вывода на сайте.
 * 
 */

/**
 * Заголовок нового элемента с автоматическим подписыванием его в основные функции СМС
 */
$elements[type_ANCHOR]=array(
	'type_name'=>'type_ANCHOR',
	'class_name'=>'xANCHOR',
	'name'=>'Заголовок',
	'title_name'=>'Заголовок',
	'picture'=>'a_4s.gif',
	'deftpl'=>'tpl_elements'
);
/**************************************************************************/
// шаблон поля xANCHOR для админки   
 point_start('default_jtpl'); // ?> %>
{% block def_xANCHOR %}
<div  class="greenpane">
<%=jTPL_elDefault(array(
	'logo'=>array('img'=>'def_header.gif'),
	'bui'=>array('width'=>'160','txt'=>'Мал. фото.<br>','vname'=>'tpb,tpu,tpi'),
	'font'=>array('noborder'=>1,'vname'=>'font'),
	'color'=>array('noborder'=>1,'vname'=>'color'),
	'size'=>array('noborder'=>1,'vname'=>'size'),
	'loadimg'=>array('width'=>'80','noborder'=>1,'vname'=>'pic_small'),
));
%>

<table class="tahoma long ctext size11 fixed align_middle"  style="margin-bottom:4px;">
	<col width="90px">
	<col width="auto">
	<tr>
		<td style="height:84px" class="align_center align_middle bgray" >
		Пример заголовка</td>
		<td class="align_center bgdray" >
		<span id="xANCHOR_def" href='#' onclick="return false;">так будет выглядеть заголовок по умолчанию</span>
		</td>
	</tr>
</table>
</div>
{% endblock %}  
<%  point_finish(); /***************************************************/ %>

<script type="text/javascript">
<% 
/**************************************************************************/
point_start('changefunc_def'); %>

change_func[<%=type_ANCHOR%>]=function (){
	var o=change_func[<%=type_ANCHOR%>].option;
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
	
	if(!change_func[<%=type_ANCHOR%>].to){
		clearTimeout(change_func[<%=type_ANCHOR%>].to);
	}
	change_func[<%=type_ANCHOR%>].to=setTimeout(function(){
		change_func[<%=type_ANCHOR%>].to=false;
		$('#xANCHOR_def').css(texto);
	},10);
};
//<%  point_finish(); /***************************************************/ %>

</script><% // шаблон поля xANCHOR для админки  ?> 

/**************************************************************************/
point_start('site_jtpl'); %>
{% block anchor %}
<a name="anc_{{xitem_name}}"></a>
{% if header %}	
<div class="header align_{{align|default('left')}}">
{% if pic_small %}<img src="{{pic_small}}" alt="">{% endif %}
<div class="inner">{{header}}</div>
</div>	
{% endif %}
{% endblock %}

<%
/**************************************************************************/
 point_start('default_jtpl');  %>
{% block xANCHOR %}
<div  id="pg_{{id}}" class="droppable greenpane collapsed">
<%=jTPL_elTable(0,array(
	'logo'=>array('img'=>'el_head.gif'),
	'html'=>array('width'=>'auto','noborder'=>1),
	'loadimg'=>array('width'=>'260','title'=>'Добавить значек','noborder'=>1),
	'align'=>array('width'=>30)
));%>
</div>
{% endblock %}
 
<%
/**************************************************************************/
 point_start('classes_body') ; // <?php // %>

class xANCHOR extends xCommon {
	
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
		$style=preg_replace('#(\.header\s+\.inner\s*{[^}]*/\*\*/)[^}]*(})#mi','\\1'.$this->get_style($o).'\\2',$style);
		
	}
	
	function getForm($lev=0){
		$par=$this->v; 
		$par['level']=$lev;
		$par['text_x']=str_replace('&nbsp;',' ',strip_tags(pps($par['item_text'])));
		if(preg_match('/(?:\S+\s+){30}/',$par['text_x'],$m)){
			$par['text_x']=$m[0].' ...';
		}
		$rows=array();
		return	smart_template(array('tpl_elements','_xANCHOR'),$par);
	}
	
	function serialize(&$formvar,$dir=false){
		global $engine;
		$id=$this->v['id'];
		if($dir && isset($_POST['item_text_'.$id]) && $_POST['item_text_'.$id]!=$this->v['item_text']){
			// find parent
			$tmp=$engine->nodeGetBackPath($engine->node($id));
			//debug($tmp);
			$engine->cache('anchor',$tmp[0]['node']);
			//debug('!!!!!!!!!!!!!!!!');
		}
		xCommon::serialize($formvar,$dir);
	}

	function xANCHOR(){
		xCommon::xCommon(
		array(
			'name'=>'anchor'
			,'fields'=>array(
				'item_text'=>array('title'=>'Текст заголовка'), // верхний заголовок
				'pic_small'=>array('title'=>'Текст заголовка'), // верхний заголовок
				'hidden'=>array('title'=>'Текст заголовка') // верхний заголовок
			)
			,'align'=>true 
		) 
		);
	}	
	
	function getText(&$keys){
		if(!!$this->v['hidden'])
			return '';
		if (!empty($this->v['item_text']))
			$header=array(
				'align'=>$this->aligns($this->v['item_align']),
				'header'=>$this->v['item_text']
			);
		else
			$header=array();	
		if (!empty($this->v['pic_small']))
			$header['pic_small']=$this->v['pic_small'];

		$header['xitem_name']=$this->v['id'];	
			//debug($header);	
		return smart_template(array('tpl_jelements','_anchor'),$header);
	}
	
}

//<%  point_finish(); /***************************************************/ %>
