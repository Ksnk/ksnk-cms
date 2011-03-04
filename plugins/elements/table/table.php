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
$elements[type_ROW]=array(
	'type_name'=>'type_ROW',
	'class_name'=>'xROW',
	'name'=>'строка',
	'internal'=>true
);
/**
 * Заголовок нового элемента с автоматическим подписыванием его в основные функции СМС
 */
$elements[type_EMPTYCELL]=array(
	'type_name'=>'type_EMPTYCELL',
	'class_name'=>'xEMPTYCELL',
	'name'=>'пустая строка',
	'internal'=>true
);
/**
 * Заголовок нового элемента с автоматическим подписыванием его в основные функции СМС
 */
$elements[type_CELL]=array(
	'type_name'=>'type_CELL',
	'class_name'=>'xCELL',
	'name'=>'ячейка',
	'internal'=>true
);
/**
 * Заголовок нового элемента с автоматическим подписыванием его в основные функции СМС
 */
$elements[type_TABLE]=array(
	'type_name'=>'type_TABLE',
	'class_name'=>'xTable',
	'name'=>'Таблица',
	'title_name'=>'Таблица',
	'picture'=>'table_4s.gif',
	'deftpl'=>'tpl_elements',
);
// шаблон поля xTP для админки  ?> %>

<% 
/**
 * шаблон дефолтных значений
 */
point_start('css_admin')%>
.bgdgreen { background-color:rgb(150,173,140); border: 2px outset white;}
.bggreen  { background-color:rgb(177,208,144); border: 2px outset white;}
.bglgreen { background-color:rgb(204,232,175); border: 2px outset white; }

<% point_start('default_jtpl');  %>
{% block def_xTable %}
<div  class="greenpane">
<%=jTPL_elDefault(array(
	'logo'=>array('img'=>'def_table.gif'),
	'radio'=>array('width'=>'160','txt'=>array(1=>'Рамка<br>',2=>'без линий<br>',3=>'без боковых линий'),'vname'=>'border'),
	'color'=>array('txt'=>'Цвет линий.<br>','vname'=>'brcolor','noborder'=>1),
	'color_'=>array('txt'=>'Фон шапки.<br>','vname'=>'hcolor','noborder'=>1),
	'_color'=>array('txt'=>'Светлая полоса.<br>','vname'=>'lrcolor','noborder'=>1),
	'_color_'=>array('txt'=>'Темная полоса.<br>','vname'=>'drcolor','noborder'=>1),
	'align'=>array('vname'=>'align','noborder'=>1),
));%>

<table class="tahoma long ctext size11  align_middle" style="margin-bottom:4px;">
	<col width="90px">
	<col width="auto">
	<tr>
		<td class="align_center align_middle bgray" >
		Пример оформления таблиц</td>
		<td class="align_center bgdray" style="padding:10px 8px;">
		<table id="xTABLE_def"><col><col><col><col>
		<tr>{% for x in ['№','Фон','заголовок1','Заголовок2'] %}
		<th>{{x}}</th>{% endfor %}</tr>
		{% for x in [1,2,3,4] %}
		<tr class="{{loop.cycle('odd','even')}}"><td>{{loop.index}}</td><td>
		{{ lipsum (1,0,2,4) }}</td><td>{{ lipsum (1,0,2,4) }}</td><td>{{ lipsum (1,0,2,4) }}</td></tr>
		{% endfor %}
		</table>
		</td>
	</tr>
</table>

</div>
{% endblock %} 
<% point_finish () ; 
// шаблон поля xTP для админки  ?> %>
<script type="text/javascript">
<% point_start('changefunc_def'); 
%>
change_func[<%=type_TABLE%>]=function (){
	var o=change_func[<%=type_TABLE%>].option,options={
		table:{
			border:'none',
			'border-collapse':'collapse'
		},
		td_th:{
			border:0,
			borderSpacing:0,
			padding:'3px 5px'
		},
		th:{
			backgroundColor:o.hcolor||'rgb(168,190,204)'	
		},
		even:{
			backgroundColor:o.lrcolor||'rgb(224,235,241)'	
		},
		odd:{
			backgroundColor:o.drcolor||'rgb(209,223,232)'	
		}
	}
	,size='2px'
	,style=' outset ';
	o.brcolor=(o.brcolor||'white')
	if(o.border==1){
		options.td_th.border=size+style+o.brcolor;
		//options.table['border-collapse']='separate';
		options.table.border=size+' inset '+o.brcolor;
		//options.table.backgroundColor=o.brcolor||'black';
	} else if(o.border==2){
		//options.td_th.borderSpacing='2px';
		//options.table['border-collapse']='separate';
		options.table.border=size+' inset '+o.brcolor;
		//options.table.backgroundColor=o.brcolor||'black';
	} else if(o.border==3){
		options.td_th.border=size+style+o.brcolor;
		options.td_th['border-left-width']='0';
		options.td_th['border-right-width']='0';
		
		//options.td_th.borderSpacing='2px';
		//options.table['border-collapse']='separate';
		options.table.border=size+' inset '+o.brcolor;
		//options.table.backgroundColor=o.brcolor||'black';
	}
	
	if(change_func[<%=type_TABLE%>].to){
		clearTimeout(change_func[<%=type_TABLE%>].to);
	}
	change_func[<%=type_TABLE%>].to=setTimeout(function(){
		change_func[<%=type_TABLE%>].to=false;
		$('#xTABLE_def').css(options.table);
		$('#xTABLE_def th').css(options.th);
		$('#xTABLE_def th,#xTABLE_def td').css(options.td_th);
		$('#xTABLE_def tr.even td').css(options.even);
		$('#xTABLE_def tr.odd td').css(options.odd);
	},10);
};
<% point_finish();  %>
</script> 
<% point_start('default_jtpl');  %>
{% block xTable %}
<div  id="pg_{{id}}" class="droppable greenpane collapsed">
<%=jTPL_elTable(0,array(
	'logo'=>array('img'=>'el_table.gif'),
	'2xdigit6__'=>array('noborder'=>1,'txt'=>'таблица.<br>','vname'=>'bx,by'),
	'direct'=>array('noborder'=>1,'data'=>'<iframe class="clipframe" style="width:80%;border:0;height:30px;" src="javascript: true;"></iframe>'),
	'table'=>array('width'=>130,'noborder'=>1),
	'foto'=>array('width'=>130,'noborder'=>1),
	'align'=>array('width'=>30)
));%>

<div style="margin-top: 0px;" class="datapane">
<div id="newElem_{{id}}" class="tablepane_pane droppable">
<table class="tahoma long ctext size11 fixed align_center align_middle">
<col width="90">
<col width="45">
{% for r in rows[0] %}<col>{% endfor %}
<col width="72">
<col width="44">
<!-- заголовки -->
<tr>
<td style="height:35px;" class="bgray">
		Настройки таблицы</td>
		
<td class="bgdray"></td>
{% for  r in rows[0] %}
<td class="bgdray">  
<input type="button" class="arrowlf"
><input type="text" class="digit6" placeholder="Шир."
><input type="button" class="arrowrt"
><input type="button" class="remrec ajax"
	data-confirm="Действительно удалить столбец" 
	data-href="?do=remcells&plugin=xTable&id={{r.id}}" 
	data-complete="/*window.location.reload()*/;" >
</td>
{% endfor %}
<td class="bgdray" rowspan=2 colspan=2><input type="button" class="button" value="Доб столб"><br>
<input type="button" class="button" value="Доб строк"></td>
</tr>
<tr>
<td style="height:35px;"></td>
		
<th class="bgdgreen" style="border-left:none;">№</th>
{% for  r in rows[0] %}
<th class="bgdgreen "><div id="item_text_{{r.id}}" class="text_edit">{{r.text|default('&nbsp;')}}</div> </th>
{% endfor %}
</tr>
{% for  rr in rows %} {% if not loop.first %}
<tr>
<td style="height:35px;"></td>
{% set bg=loop.cycle('bglgreen','bggreen') %}
{% if loop.last %}{%set last='border-bottom:none;' %}
{% else %}{%set last='' %}{% endif %}		
<th class="{{bg}}" style="border-left:none;{{last}}">{{loop.index0}}</th>
{% for  r in rr %}
<td class="{{bg}}" {% if last %}style="{{last}}"{%endif%}>
<div id="item_text_{{r.id}}" class="text_edit">{{r.text|default('&nbsp;')}}</div></td>
{% endfor %}
<td class="bgdray">
<input type="button" class="arrowup"
><input type="text" class="digit2"
><input type="button" class="arrowdn"
></td>
<td class="bgdray">
<input type="button" class="remrec ajax"
	data-confirm="Действительно удалить строку?" 
	data-href="?do=remrow&plugin=xTable&id={{r.id}}" 
	data-complete="/*window.location.reload()*/;">
</td>
</tr>

{% endif %}
{% endfor %}

</table>
</div>

<!-- фото -->
<div class="fotopane_pane">
<%=jTPL_elTable1(array(
	'logo'=>array('txt'=>'Настройки фото'),
	'2xdigit6'=>array('width'=>'160','txt'=>'Мал:','vname'=>'sizex,sizey','alt'=>'{{alt_sizex}},{{alt_sizey}}'),
	'2xdigit6_'=>array('width'=>'160','txt'=>'Бол:','vname'=>'bsizex,bsizey','alt'=>'{{alt_bsizex}},{{alt_bsizey}}'),
	'check'=>array('width'=>'80','txt'=>'Рамка','name'=>'border_{id}','alt'=>'{{alt_border}}'),
	'check_'=>array('width'=>'180','txt'=>'Показывать одно фото','name'=>'ftpl_{{id}}'),
	'align'=>array('width'=>'50','name'=>'pic_align'),
));%>

{% for pic in picture %}
<%=jTPL_elTable1(array(
	'raw'=>array('width'=>90),
	'picture'=>array('light'=>1),
	'updn'=>array('width'=>80),
	'del'=>array('width'=>30),
),'picture_{{pic.id}}');%>
{% endfor %}

</div>
</div>

</div>
{% endblock %}
<% point_finish() %>
<script type="text/javascript">
<% point_start('js_admin_function'); %>
//This function will be called from the PasteFromWord dialog (fck_paste.html)
//Input: oNode a DOM node that contains the raw paste from the clipboard
//bIgnoreFont, bRemoveStyles booleans according to the values set in the dialog
//Output: the cleaned string
function CleanWord( oNode, bIgnoreFont, bRemoveStyles )
{
	var html = oNode,reg ;

	html = html.replace( /<meta\s(\n|\r|.)*?>/gi,'');
	html = html.replace( /<link\s(\n|\r|.)*?>/gi,'');
	html = html.replace( /<style>(\n|\r|.)*?<\/style>/gi,'');
	// Remove mso-xxx styles (2).
	reg=/<!--\[if\s+gte\s+mso(\n|\r|.)*?endif\]-->/gmi; reg.multiline=true;
	html = html.replace(reg, '' ) ;

	// Remove mso-xxx styles.
	html = html.replace(/<o:p>\s*<\/o:p>/g, '') ;
	html = html.replace(/<o:p>.*?<\/o:p>/g, '&nbsp;') ;

	html = html.replace( /\s*mso-[^:]+:[^;"]+;?/gi, '' ) ;

	// Remove margin styles.
	html = html.replace( /\s*MARGIN: 0cm 0cm 0pt\s*;/gi, '' ) ;
	html = html.replace( /\s*MARGIN: 0cm 0cm 0pt\s*"/gi, "\"" ) ;

	html = html.replace( /\s*TEXT-INDENT: 0cm\s*;/gi, '' ) ;
	html = html.replace( /\s*TEXT-INDENT: 0cm\s*"/gi, "\"" ) ;

	html = html.replace( /\s*TEXT-ALIGN: [^\s;]+;?"/gi, "\"" ) ;

	html = html.replace( /\s*PAGE-BREAK-BEFORE: [^\s;]+;?"/gi, "\"" ) ;

	html = html.replace( /\s*FONT-VARIANT: [^\s;]+;?"/gi, "\"" ) ;

	html = html.replace( /\s*tab-stops:[^;"]*;?/gi, '' ) ;
	html = html.replace( /\s*tab-stops:[^"]*/gi, '' ) ;

	// Remove FONT face attributes.
	if ( bIgnoreFont )
	{
		html = html.replace( /\s*face="[^"]*"/gi, '' ) ;
		html = html.replace( /\s*face=[^ >]*/gi, '' ) ;

		html = html.replace( /\s*FONT-FAMILY:[^;"]*;?/gi, '' ) ;
	}

	// Remove Class attributes
	html = html.replace(/<(\w[^>]*) class=([^ |>]*)([^>]*)/gi, "<$1$3") ;

	// Remove styles.
	if ( bRemoveStyles )
		html = html.replace( /<(\w[^>]*) style="([^\"]*)"([^>]*)/gi, "<$1$3" ) ;

	// Remove empty styles.
	html =  html.replace( /\s*style="\s*"/gi, '' ) ;

	html = html.replace( /<SPAN\s*[^>]*>\s*&nbsp;\s*<\/SPAN>/gi, '&nbsp;' ) ;

	html = html.replace( /<SPAN\s*[^>]*><\/SPAN>/gi, '' ) ;

	// Remove Lang attributes
	html = html.replace(/<(\w[^>]*) lang=([^ |>]*)([^>]*)/gi, "<$1$3") ;

	html = html.replace( /<SPAN\s*>(.*?)<\/SPAN>/gi, '$1' ) ;

	html = html.replace( /<FONT\s*>(.*?)<\/FONT>/gi, '$1' ) ;

	// Remove XML elements and declarations
	html = html.replace(/<\\?\?xml[^>]*>/gi, '' ) ;

	// Remove Tags with XML namespace declarations: <o:p><\/o:p>
	html = html.replace(/<\/?\w+:[^>]*>/gi, '' ) ;

	// Remove comments [SF BUG-1481861].
	html = html.replace(/<\!--.*-->/g, '' ) ;

	html = html.replace( /<(U|I|STRIKE)>&nbsp;<\/\1>/g, '&nbsp;' ) ;

	html = html.replace( /<H\d>\s*<\/H\d>/gi, '' ) ;

	// Remove "display:none" tags.
	html = html.replace( /<(\w+)[^>]*\sstyle="[^"]*DISPLAY\s?:\s?none(.*?)<\/\1>/ig, '' ) ;

		html = html.replace( /<H1([^>]*)>/gi, '<div$1><b><font size="6">' ) ;
		html = html.replace( /<H2([^>]*)>/gi, '<div$1><b><font size="5">' ) ;
		html = html.replace( /<H3([^>]*)>/gi, '<div$1><b><font size="4">' ) ;
		html = html.replace( /<H4([^>]*)>/gi, '<div$1><b><font size="3">' ) ;
		html = html.replace( /<H5([^>]*)>/gi, '<div$1><b><font size="2">' ) ;
		html = html.replace( /<H6([^>]*)>/gi, '<div$1><b><font size="1">' ) ;

		html = html.replace( /<\/H\d>/gi, '<\/font><\/b><\/div>' ) ;

		// Transform <P> to <DIV>
		var re = new RegExp( '(<P)([^>]*>.*?)(<\/P>)', 'gi' ) ;	// Different because of a IE 5.0 error
		html = html.replace( re, '<div$2<\/div>' ) ;

		// place &nbsp; into empty td th.
		html = html.replace( /<(td|th)(\s[^>]*)?>\s*?<\/\1>/gi, '<$1>&nbsp;</$1>' ) ;
		// Remove empty tags (three times, just to be sure).
		// This also removes any empty anchor
		html = html.replace( /<([^\s>]+)(\s[^>]*)?>\s*<\/\1>/g, '' ) ;
		html = html.replace( /<(td|th)(\s[^>]*)?>\s*?<\/\1>/gi, '<$1>&nbsp;</$1>' ) ;
		html = html.replace( /<([^\s>]+)(\s[^>]*)?>\s*<\/\1>/g, '' ) ;
		html = html.replace( /<(td|th)(\s[^>]*)?>\s*?<\/\1>/gi, '<$1>&nbsp;</$1>' ) ;
		html = html.replace( /<([^\s>]+)(\s[^>]*)?>\s*<\/\1>/g, '' ) ;

	return html ;
};
<% point_start('js_admin'); %>
$('iframe.clipframe')
.placeholder({text:'<center style="font-size:11px;"><b>введите таблицу</b><br>Ctrl-V</center>'})
.each(function(){
	var doc = this.contentWindow.document;
    doc.open();
    doc.write('<html><body style="height:100%;padding:0;margin:0;font-size:12px;overflow:hidden;background:white;">'
    	    +'</body></html>');
    doc.close();
    doc=null;	
    try{
		this.contentWindow.document.body.contentEditable = true;
	    this.contentWindow.document.designMode = 'on';
    }
    // Firefox 1.5 throws an exception that can be ignored
    // when toggling designMode from off to on.
    catch (ex) {;}
	var self=this,ready=false;
	$(this).mouseover(function(){
    	if(ready) return;
    	ready=true;
		$(document.body).bind('mousemove.clip',function(e){
			var elm = e.target, tgt;
			while (elm && elm != self) {
				elm = elm.parentNode;
			}
			if (!elm) { // выбрались за пределы фрейма
				$(document.body).unbind('mousemove.clip');
				ready=false;
				var v=CleanWord($(self).contents().find('html body').html()||'', true, true);
				$(self).contents().find('html body').html('');
				$(self).parent().find('span:hidden').show();
				if(v){
				if (v.match(/<table|<tr/i)) {
					$.post('?do=ajax_clear_text', 'txt=' + encodeURIComponent($.trim(v)),function(data){
						var x = self,y,p1;
						while(x && (!x.id || !x.id.match(/pg_(\d+)/))) 
							x = x.parentNode;
						if(x && !!(y=x.id.match(/pg_(\d+)/))) 	// = - правильно!
							p1=y[1];	
						win_confirm({
							txt:'Заменить таблицу?('+p1+')',
							yes:function(){
				//				window.el_open(x);
								
								// замещаем таблицу
								$.post('?do=ajax_pasteItem','x=page&id=' +p1,function(data){
									$.post('?do=ajax_delItem', 'x=page&id=' + p1, function(){
										   __goto();
										});
								});		
							}
						});
					});
				} else if(v!=''){
					alert('Во вставленном фрагменте таблица не обнаружена');
				}}
			}
		});
    });
});
<% point_finish() %>
 </script>
<!-- <% point_start('classes_body') ; //--> <?php // %>

class xTable extends xElement {

	function do_remcells(){ // row or cells
		global $engine;
		$id=intval($_GET['id']);
		if($id){
			$this->delCellRow($id,'Cells');
			return 'ok';
		} else 
			$this->parrent->error('bad index');
		return 'fail';	
	}
	function do_remrow(){ // row or cells
		global $engine;
		$id=intval($_GET['id']);
		
		if($id){
			$this->delCellRow($id,'Rows');
			return 'ok';
		} else 
			$this->parrent->error('bad index');
		return 'fail';	
	}
	
	function delCellRow($id,$what){
		global $engine;
		// id = cell
		$items=$engine->nodeGetBackPath($engine->node($id));
		//print_r($items); return '';
		if(!is_array($items)) return ;
		$cell=array_pop($items);
		$row=array_pop($items);
		$table=array_pop($items);
		$table=$engine->nodeGet($engine->node($table));
		// считаем количество ячеек
		$row_start=-1;
		foreach ($table as $i=>$v){
			if ($v['level']==$row['level']){
				$row_start=$i;
			} else if ($v['id']==$cell['id']) {
				$cell_disp=$i-$row_start;
			} else if (($row_start>=0) && ($v['level']==$row['level'])) {
				$cell_cnt=$i-$row_start;
			}
		}
		if(empty($cell_cnt))$cell_cnt=$i-$row_start;
		//printf('cells %s; disp %s',$cell_cnt,$cell_disp);
		if($what=='Rows'){
			$engine->nodeDelete($engine->node($row));
		} else {
			foreach ($table as $i=>$v){
				if ($v['level']==$row['level']){
					$row_start=$i;
				} else if (($row_start>=0) && ($v['level']==$cell['level'])) {
					if(($i-$row_start)==$cell_cnt){
						$engine->nodeDelete($engine->node($v));
					}
				}
			}
		}
	}
	
	function do_moverow(){
		global $engine;
		$list=$this->propList();$idx=trim($_GET['prop']);
		echo $idx;
		if(!!$idx){
			$list[]=$idx;
			$engine->export('defaultValues','change',type_KATALOGEL,'properties',array_values($list));
		}
		return 'ok';
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
		$text=
			'color:'.pps($o['tcolor'],'<%=$color_text_darkgray%>').';'.
			($o['tpb']?'font-weight:bold;':'').
			($o['tpi']?'font-style:italic;':'').
			($o['tpu']?'text-decoration:underline;':'').
			pp($o['tfont'],'font-family:',';').
			pp($o['tsize'],'font-size:',';');
		$style=preg_replace('#(\.text\s+{[^}]*/\*\*/)[^}]*(})#mi','\\1'.$text.'\\2',$style);
		
		/**
		 * корректируем правилo .text .cat_border img
		 */
		// значение по умолчанию
		// $border="	border: 10px solid <%=$color_silver_bg%>;"
		if($o.border){
			$border=
			'border-style:solid;'.
			'border-top-width:'.ppi($o['by'],10).'px;'.
			'border-bottom-width:'.ppi($o['by'],10).'px;'.
			'border-left-width:'.ppi($o['bx'],10).'px;'.
			'border-right-width:'.ppi($o['bx'],10).'px;'.
			'border-color:'.pps($o['color'],'<%=$color_silver_bg%>').';';
		} else {
			$border='border:none;';
		}
		$style=preg_replace('#(\.text\s+\.cat_border\s+img\s+{[^}]*/\*\*/)[^}]*(})#mi','\\1'.$border.'\\2',$style);
		/*
	var o=change_func[<%=type_TABLE%>].option,options={
		table:{
			border:'none',
			'border-collapse':'collapse'
		},
		td_th:{
			border:0,
			borderSpacing:0,
			padding:'5px 3px'
		},
		th:{
			backgroundColor:o.hcolor||'rgb(168,190,204)'	
		},
		even:{
			backgroundColor:o.lrcolor||'rgb(224,235,241)'	
		},
		odd:{
			backgroundColor:o.drcolor||'rgb(209,223,232)'	
		}
	}
	,size='2px'
	,style=' inset ';
	o.brcolor=(o.brcolor||'white')
	if(o.border==1){
		options.td_th.border=size+style+o.brcolor;
		//options.table['border-collapse']='separate';
		options.table.border=size+style+o.brcolor;
		//options.table.backgroundColor=o.brcolor||'black';
	} else if(o.border==2){
		//options.td_th.borderSpacing='2px';
		//options.table['border-collapse']='separate';
		options.table.border=size+style+o.brcolor;
		//options.table.backgroundColor=o.brcolor||'black';
	} else if(o.border==3){
		options.td_th.border=size+style+o.brcolor;
		options.td_th['border-left-width']='0';
		options.td_th['border-right-width']='0';
		
		//options.td_th.borderSpacing='2px';
		//options.table['border-collapse']='separate';
		options.table.border=size+style+o.brcolor;
		//options.table.backgroundColor=o.brcolor||'black';
	}
	
	if(change_func[<%=type_TABLE%>].to){
		clearTimeout(change_func[<%=type_TABLE%>].to);
	}
	change_func[<%=type_TABLE%>].to=setTimeout(function(){
		change_func[<%=type_TABLE%>].to=false;
		$('#xTABLE_def').css(options.table);
		$('#xTABLE_def th').css(options.th);
		$('#xTABLE_def th,#xTABLE_def td').css(options.td_th);
		$('#xTABLE_def tr.even td').css(options.even);
		$('#xTABLE_def tr.odd td').css(options.odd);
	},10);*/
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
		$nodex=$x->Create(array('type'=>$tp),$artnode);
		return ' ';
	}
	
	function getForm($lev=0){
		global $engine;
		$par=$this->v; 
		$par['level']=$lev;
		$header=array();
		$rows=array();
		$pictures=array();
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
				$pictures[]=$v->v;
 				
			} else	{
				$row=array();
				foreach($v->el as $vv){
					$row[]=$vv->v;
				}
 				$rows[]=$row;
			}
  		}
  		$par['picture']=$pictures;
		
		$par['rows']=$rows;
		//debug('xxx',$par);
		return	smart_template(array('tpl_elements','_xTable'),$par);
	}
	
	function newCellRow($id,$what){
		global $engine;
		// id = cell
		$items=$engine->nodeGetBackPath($engine->node($id));
		$cell=array_pop($items);
		$row=array_pop($items);
		$table=array_pop($items);
		$table=$engine->nodeGet($engine->node($table));
		// считаем количество ячеек
		$row_start=-1;
		foreach ($table as $i=>$v){
			if ($v['level']==$row['level']){
				$row_start=$i;
			} else if ($v['id']==$cell['id']) {
				$cell_disp=$i-$row_start;
			} else if (($row_start>=0) && ($v['level']==$row['level'])) {
				$cell_cnt=$i-$row_start;
			}
		}
		if(empty($cell_cnt))$cell_cnt=$i-$row_start;
		//printf('cells %s; disp %s',$cell_cnt,$cell_disp);
		if($what=='Rows'){
			$row=$engine->nodeAdd(-$engine->node($row),array('type'=>type_ROW,'name'=>'Row'));
			for($i=0;$i<$cell_cnt;$i++){
				$engine->nodeAdd($engine->node($row),array('text'=>'','type'=>type_CELL));
			}
		} else {
			//print('Hello!');
			foreach ($table as $i=>$v){
				if ($v['level']==$row['level']){
					$row_start=$i;
				} else if (($v['level']==$cell['level'])&&($cell_disp==$i-$row_start)) {
				//	print($v['id']);
					$engine->nodeAdd(-$engine->node($v),array('type'=>type_CELL,'text'=>''));
					//$cell_disp=$i-$row_start;
				}
			}
		}
	}


	function moveItem($id,$what,$disp){
		// id = cell
		global $engine;
		$items=$engine->nodeGetBackPath($engine->node($id));
		$cell=array_pop($items);
		if($what=='Rows'){
			$row=array_pop($items);
			if ($disp=="-1")
				$engine->nodeMoveUp($engine->node($row));
			else
				$engine->nodeMoveDn($engine->node($row));
		} else {
			$row=array_pop($items);
			$table=array_pop($items);
			$table=$engine->nodeGet($engine->node($table));
			// считаем какой мы столбец
			$cell_disp=-1;
			foreach ($table as $i=>$v){
				if ($v['id']==$row['id'])
					$row_start=$i;
				else if	($v['id']==$cell['id']){
					$cell_disp=$i-$row_start;
					break;
				}
			}
			foreach ($table as $i=>$v){
				if ($v['level']==$row['level'])
					$row_start=$i;
				else if	($v['level']==$cell['level'] && (($i-$row_start)==$cell_disp)){
					if ($disp=="-1")
						$engine->nodeMoveUp($engine->node($v));
					else
						$engine->nodeMoveDn($engine->node($v));
				}
			}
		}
	}

	function pasteData(&$data){
		global $engine;
		if(!count($data)) return;
		if ($data[0]['type']==type_ROW){
			$i=0;
			ajax::insertPage($this->v['id'],$data[0]['level'],$data,$i);
		} else
			parent::pasteData($data);
	}

	function newData($data){
		if ($data=='Cells'){
			for($i=0;$i<count($this->el);$i++){
				$engine->nodeAdd($engine->node($this->el[$i]->v),array('text'=>'','type'=>type_CELL));
			}
		} else
			parent::newData($data);
	}

	function getText(&$keys){
		$aligns=array('left','center','right');

	// row - header
		$inputs='';
		$cnt=count($this->el[0]->el);
		$cols='';
		$isfixed=false;
		$width=array();
		if(!empty($this->el[0]->el)){
		foreach($this->el[0]->el as $k=>$v){
			//debug($v);
			$inputs.='<th class="nopage" title="ширина столбца"><input onkeydown="need_Save()" name="item_url_'.$v->v['id'].'" type="text" class="long"></th>';
			$cols.='<col'.pp($v->v['url'],' width="','"',' width="auto"').'>';
			if(!empty($v->v['url']))
				$isfixed=true;
			$width[$k]=$v->v['url'];	
		}
		//debug($this->el);		
		$header=$this->el[0]->getText($keys,'th');//,'',$width);
		}
		$rows=array();$colnumber=1;
		for($i=1;$i<count($this->el);$i++){
			$colnumber=count($this->el[$i]->el);
			$rows[]=array(
				 'id'=>$this->el[$i]->v['id']
				,'class'=>$i%2?'odd':'even'
				,'number'=>$i
				,'colnumber'=>$colnumber
				,'xcolnumber'=>$colnumber*2-1
				,'row'=>$this->el[$i]->getText($keys,'td')//,'',$width)
			);
		}
		$rows[count($rows)-1]['class'].=' last';
		
		return smart_template(array('tpl_jelements','_xTable'),array(
			'header'=>$header,
			'cols'=>$isfixed?$cols:'',
			'inputs'=>$inputs,
			'notfixed'=>!$isfixed,
			'align'=>$aligns[ppi($this->v['align'])],
			'rows'=>$rows,'colnumber'=>$colnumber,'xcolnumber'=>$colnumber*2-1));
		//'<table border=1>'.$table.'</table>';
	}


	function serialize(&$formvar,$dir=false){
		global $engine;
		xElement::serialize_var($formvar,$dir,
			array(
				'item_name_'=>'name'
				,'item_align_'=>'align'
				,'tab_width_'=>'tab_width'
				,'tab_foto_'=>'tab_foto'
			)
		);
		xElement::serialize_order_menu($formvar,$dir,'item_order_');
		if($dir){
			if(!empty($_POST['new_row_add_'.$this->v['id']]))
			{
				$engine->addNode($this->node(),array('name'=>'row','type'=>type_ROW));
			}
//			debug($formvar);
			if(isset($formvar['tab_rows_'.$this->v['id']])){
				$d_row=$formvar['tab_rows_'.$this->v['id']]-count($this->el);
				$d_cell=$formvar['tab_colls_'.$this->v['id']]-count($this->el[0]->el);
				$lastrow=$this->el[count($this->el)-1];
				$lastcell=$lastrow->el[count($lastrow->el)-1];
				while($d_row>0){
					$d_row--;
					$this->newCellRow($lastcell->v['id'],'Rows');
				}
				while($d_cell>0){
					$d_cell--;
					$this->newCellRow($lastcell->v['id'],'Cells');
				}
				if($d_row<0){
					$rowcnt=count($this->el);
					//$lastcell=$lastrow->el[count($lastrow)-1];
					while($d_row<0){
						$d_row++;$rowcnt--;
						$this->delCellRow($this->el[$rowcnt]->el[0]->v['id'],'Rows');
					}
				}
				if($d_cell<0){
					$cellcnt=count($this->el[0]->el);
					//$lastcell=$lastrow->el[count($lastrow)-1];
					while($d_cell<0){
						$d_cell++;$cellcnt--;
						$this->delCellRow($this->el[0]->el[$cellcnt]->v['id'],'Cells');
					}
				}
			}
//			debug('hello!');
			if(!empty($this->el))
			foreach($this->el[0]->el as $v){
				if(isset($formvar['item_url_'.$v->v['id']]))
					if(preg_match('/^(\d+)(px|pt|%|(.*))$/i',$formvar['item_url_'.$v->v['id']],$m)){
				//debug($m);
						if(!empty($m[3]) || empty($m[2])) 	
							$m[2]='px';
						else
							$m[2]=strtolower($m[2]);
						$formvar['item_url_'.$v->v['id']]=$m[1].$m[2];
						$_POST['item_url_'.$v->v['id']]=$m[1].$m[2];
					}
			}
		} else {
			$formvar['tab_rows_'.$this->v['id']]=count($this->el);
			if(isset($this->el[0]))
				$formvar['tab_colls_'.$this->v['id']]=count($this->el[0]->el);
			else
				$formvar['tab_colls_'.$this->v['id']]=0;	
		}
		if(!empty($this->el))
		foreach($this->el as $v){
			$v->serialize($formvar,$dir);
		}
	}
	function Create($key,$parent) {
		global $engine;
		$id= $engine->nodeAdd($parent,$key);
		$row= &new xRow();
		$row->Create(array('name'=>'Header','type'=>type_ROW),$id);
		$row= &new xRow();
		$row->Create(array('name'=>'Header','type'=>type_ROW),$id);
		return $id;
	}
}

class xRow extends xElement {
	// состоит из text
	function getText(&$keys,$td='td',$class='',$width=''){
		static $invert=array();
	// row - header
		if (empty($width)) $width=array();
		$cols=array();
		for($i=0;$i<count($this->el);$i++){
			$v=&$this->el[$i]->v;
			if (isset($v['text']))
			{
				$x=array('td'=>$td,
					'id'=>$v['id'],
					//'width'=>pp($width[$k],'width:',';'),
					'text'=>$v['text'],
				);
				if(isset($v['colspan'])) {
					$x['colspan']=$v['colspan'];
				}
				if($i & 1){
					$x['class']=pp($x['class'],'',' ').'xodd';
				}
				if(!empty($invert[$i])){
					$invert[$i]=0; //debug('xxx');
					$x['class']=pp($x['class'],'',' ').'invert';
				}
				if(isset($v['rowspan'])){
					$x['rowspan']=ppi($v['rowspan']);
					if(($x['rowspan']>2) && (($x['rowspan'] & 1) ==0)){
						$invert[$i]=1; 
						//debug($invert);
					}
				};
				$cols[]=$x;
			}
		}
		$cols[count($cols)-1]['class']=pp($cols[count($cols)-1]['class'],'',' ').'last';
		return smart_template(array(ELEMENTS_TPL,'edit_row'),array('cols'=>$cols));
	}

	function getForm(){
		$x=smart_template(array(ELEMENTS_TPL,'row_edit_line'),$this->v);
		if(!empty($this->el))
		foreach( $this->el as $v) {
			$x.=$v->getForm();
		}
		return	$x;
	}

	function serialize(&$formvar,$dir=false){
		global $engine;
		xElement::serialize_order_menu($formvar,$dir,'item_order_');
		if($dir){
			if(!empty($_POST['new_row_add_'.$this->v['id']]))
			{
				$engine->nodeAdd($engine->node($this->v),array('text'=>'','type'=>type_CELL));
			}
		}
		if(!empty($this->el))
		foreach($this->el as $v){
			$v->serialize($formvar,$dir);
		}
	}
	function Create($key,$parent) {
		global $engine;
		$id= $engine->nodeAdd($parent,$key);
		$engine->nodeAdd($id,array('type'=>type_CELL,'text'=>''));
		return $id;
	}

	function getContextMenu(){
		return ' ';
	}

}
	

class xEMPTYCELL extends xCell {
	
}
//<% point_finish() %>