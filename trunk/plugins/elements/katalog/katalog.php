<?php
//<% 
/**
 * 
 *  Элемент - Каталог
 * 
 */

/**
 * Заголовок нового элемента с автоматическим подписыванием его в основные функции СМС
 */
$elements[type_KATALOGEL]=array(
	'type_name'=>'type_KATALOGEL',
	'class_name'=>'xKatalog',
	'name'=>'Каталог',
	'title_name'=>'Каталог',
	'picture'=>'katalog_4s.gif',
	'deftpl'=>'tpl_elements',
);

/**
 * шаблон дефолтных значений
 */
point_start('css_admin')
// шаблон поля xTP для админки  ?> %>

.bgdgreen { background-color:rgb(150,173,140); border: 2px outset white;}
.bggreen  { background-color:rgb(177,208,144); border: 2px outset white;}
.bglgreen { background-color:rgb(204,232,175); border: 2px outset white;}
.dlg-short {display:inline-block;width:100px;}

<% point_start('default_jtpl');  %>
{% block def_xKatalog %}
<div  class="greenpane">
<%=jTPL_elDefault(array(
	'logo'=>array('img'=>'def_katalog.gif'),
	'2xdigit6'=>array('txt'=>'Мал. фото.<br>','vname'=>'sizex,sizey'),
	'2xdigit6_'=>array('txt'=>'Бол. фото.<br>','vname'=>'bsizex,bsizey'),
	'check'=>array('width'=>'80','txt'=>'Рамка.<br>','vname'=>'border','noborder'=>1),
	'color'=>array('width'=>'110','txt'=>'Цвет рамки.<br>','vname'=>'color','noborder'=>1),
	'2xdigit6__'=>array('txt'=>'толщина рамки.<br>','vname'=>'bx,by'),
	'align'=>array('vname'=>'align','noborder'=>1),
));%>

<table id="xKatalog_def" class="bluetab align_center tahoma long ctext size11  align_middle" style="margin-bottom:2px;">
	<col width="90px">
	{% set properties=callex('xKatalog','propList') -%}
	{% set zzz=['№','Характ','файл синх.','доп хар.'
			,'сел. (сайт)','Скр. (сайт)','Сорт. (админ)','только зарег.','Ширина поля(px)']-%}
	{% for x in zzz %}<col>{% endfor-%}
	<col width=50><col width=30>	
		
	<tr>
		<td class="align_middle bgray" >
		Настройки каталога</td>
		{% for x in zzz %}
		<th class="bgdray">{{x}}</th>{% endfor %}
		<th style="padding:3px;" class="bgray" colspan=2>
		<input type="button" class="ajax button" value="Доб. характ."
			data-dialog="#defineharact" 
			data-href="?do=addprop&plugin=xKatalog" 
			data-complete="reload();" 
		></th>
	</tr>
{% for x in properties %}
		{% if loop.first %}<tr class="head">
		{% else %}
		<tr class="{{loop.cycle('odd','even')}}">
		{% endif %}
		<td></td>
		<td  class="cell">
		{{loop.index}}</td><th  class="cell">{{ x }}</th>
		{% set xxx=loop.index %}
		{% for y in zzz %}{% if loop.index==7  %}
		<td class="cell"><input type="button" class="arrowup"><input type="button" class="arrowdn"></td>
		{% elif loop.index==9 %}
		<td class="cell"><input type="text" class="digit6" style="margin:0;"></td>
		{% elif loop.index>2 %}
		<td class="cell"><input type="text" class="checkbox" 
		value="{{self['kat_'~xxx~'_'~loop.index]}}" name="kat_{{xxx}}_{{loop.index}}_{{id}}"></td>
		{% endif %} 
		{% endfor %}
		{% if loop.first %}<td class="bgray"></td><td class="bgray"></td>
		{% else %}
		<td class="bgray">
		<input type="button" class="arrowup"
><input type="text" class="digit2" style="margin:0;"
><input type="button" class="arrowdn"
>		</td><td class="bgray">
		<input type="button" class="ajax remrec"
			data-confirm="Действительно удалить атрибут?" 
			data-href="?do=remprop&plugin=xKatalog&prop={{xxx}}" 
			data-complete="reload();" 
		>
	</td>
{%- endif %}
	</tr>
{% endfor %}	
</table>
<table class="tahoma long ctext size11  align_middle" style="margin-bottom:2px;">
	<col width="90px">
	<col width="auto">
	<tr>
		<td class="align_center align_middle bgray" >
		Отображать на сайте</td>
		<td class="bgdray" style="padding:10px 8px;">
		{% set ZZZ=['Табличная форма','Блочная форма','Галерея'] %}
		<table class="long align_left">{% for x in ZZZ %}<col>{%endfor%}
		<tr>{% for x in ZZZ %}
		<td ><input type="radio" name="kat_form_{{id}}" value="{{loop.index}}" {%if kat_form==loop.index%}checked {%endif%}><b> {{x}}</b><br>
		<input type="text" class="digit2" name="kat_col{{loop.index}}_{{id}}" value="{{self['kat_col'~loop.index]}}"> Кол-во столбцов<br>
		</td>{% endfor %}
		</tr>
		</table>
		</td>
	</tr>
</table>
<table class="tahoma long ctext size11  align_middle" style="margin-bottom:2px;">
<col width="90px"><col>
	<tr>
		<td class="align_center bgray" >
		Настройки описания</td>
		<td class=" bgdray" style="padding:10px 8px;">
		<table class="long align_left"><col width="200"><col><col>
		<tr>
		<td>Наз. ссылки <input type="text" class="digit6" name="kat_link_{{id}}" value="{{kat_link}}"></td>
		<td><input type="radio" name="kat_link_style_{{id}}" value="1" {%if kat_link_style==1%}checked {%endif%}> Выпадающее</td>
		<td><input type="radio" name="kat_link_style_{{id}}" value="2" {%if kat_link_style==2%}checked {%endif%}> В новом окне</td>
		</tr>
		</table>
		</td>
	</tr>
</table>
<table class="tahoma long ctext size11  align_middle" style="margin-bottom:4px;">
<col width="90px"><col>
	<tr>
		<td class="align_center align_middle bgray" >
		Настройки доп хар-ки</td>
		<td class="bgdray" style="padding:10px 8px;">
		<table class="long align_left"><col width="200"><col><col><col>
		<tr>
		<td>Наз. ссылки <input type="text" class="digit6" name="kat_dop_{{id}}" value="{{kat_dop}}"></td>
		<td><input type="radio" name="kat_dop_style_{{id}}" value="1" {%if kat_dop_style==1%}checked {%endif%}> Выпадающее</td>
		<td><input type="radio" name="kat_dop_style_{{id}}" value="2" {%if kat_dop_style==2%}checked {%endif%}> В новом окне</td>
		<td><input type="radio" name="kat_dop_style_{{id}}" value="3" {%if kat_dop_style==3%}checked {%endif%}> Скрыть</td>
		</tr>
		</table>
		</td>
	</tr>	
</table>

</div>
{% endblock %} 
<% point_start('admin_html');%>
<div id="defineharact" style="width:350px;" title="Добавить характеристику">
<label class="dlg-short"> название</label><input type="text" name="name" class="text ui-widget-content ui-corner-all">
<br>
<label class="dlg-short"> тип поля</label><select name="type" class="text ui-widget-content ui-corner-all">
<option value=0>короткое текстовое</option>
<option value=1>большое текстовое</option>
<option value=2>цена</option>
<option value=3>целое</option>
<option value=4>дробное</option>
</select>
</div>
<%   point_finish() ;  %>

<style type="text/css">
<% point_start('css_admin'); %>
.greentab th, .greentab td {
	padding:5px 10px;
}
.greentab .cell {
	border: 2px outset white; 
}
.greentab .odd .cell {
	background-color: #B1D090;
}
.greentab .even .cell {
	background-color: #CCE8AF;
}
.greentab .head .cell {
	background-color: #96AD8C;
}

.bluetab th, .bluetab td {
	padding:5px 10px;
}
.bluetab .cell {
	border: 2px outset white; 
}
.bluetab .odd .cell {
	background-color: rgb(180,219,227);
}
.bluetab .even .cell {
	background-color: rgb(150,193,202);
}
.bluetab .head .cell {
	background-color: rgb(81,121,131);
}
<% point_finish () ; %>
</style>
 <%
// шаблон поля xKatalog для админки  
  point_start('default_jtpl');  %>
{% block xKatalog %}
<div  id="pg_{{id}}" class="droppable greenpane collapsed">
<%=jTPL_elTable(0,array(
	'logo'=>array('img'=>'el_katalog.gif'),
	'loadimg'=>array('width'=>'120','txt'=>' ','title'=>'Иконка','noborder'=>1),
	'2input'=>array('noborder'=>1,'txt'=>'','vname'=>'articulx,descrx','alt'=>'Артикул,Название'),
	'haract'=>array('width'=>130,'noborder'=>1),
	'article'=>array('width'=>130,'noborder'=>1),
	'align'=>array('width'=>30)
)); %>

<div style="margin-top: 0px;" class="datapane">
<div id="newElem_{{id}}" class="notepane_pane droppable">
<%=jTPL_elTable1(array(
	'logo'=>array('txt'=>'Настройки описания'),
	'input'=>array('width'=>'auto','noborder'=>1,'txt'=>'Название ссылки:','name'=>'comment_{{id}}', 'alt'=>'Подробнее...'),
	'radio'=>array('width'=>'auto','noborder'=>1,'txt'=>array(1=>'выпадающее',2=>'в новом окне'),'name'=>'target_{{id}}'),
));%>
<div class="greenpane">{{article}}</div>
</div>
<!-- фото -->
<div class="haractpane_pane">
{% set properties=callex('xKatalog','propList') %}
<table class="greentab align_center tahoma long ctext size11  align_middle" style="margin-bottom:2px;">
	{% for x in properties -%}
		{% if loop.first %}<col width="90px;">{% endif %}{% endfor -%}
		<col width=30>	
<tr><th class="bgray">Фото</th>
	{% for x in properties %}
		{% if loop.index>1 %}
		<th class="bgdray">{{ x }}</th>
		{% endif %}
	{% endfor %}
		<th class="bgdray" ></th>
</tr>
<tr class="odd">
	<td id="loadpic_{{id}}" class="cell align_center align_middle" title="">
		<div class="picture_upload" alt="small" >
		<img src="{{pic_small|default('img/folderb.gif')}}"></div>
		<input name="pic_small_{{id}}" type="text" style="vertical-align:middle;display:none;">
	</td>	
	{% set keys=properties|keys %}	
	{% for x in keys %}
		{% if loop.index>1 %}
	<td  class="cell"><input type="text" style="margin:0;" class="digit6" name="{{x}}_{{id}}"></td>
		{% endif %}
	{% endfor %}
	<th class="bgdray" ></th>
</tr>
</table>
<table class="align_center tahoma long ctext size11  align_middle" style="margin-bottom:2px;">
<tr class="odd">
	<td class="bgray" style="width:90px;">Настройки доп. хар-ки</td>
	<td class="bgdray">Название ссылки
	<input type="text" class="input"></td>
	<td class="bgdray">
	<input type="radio" name="har_type" value=1> Выпад
	<input type="radio" name="har_type" value=1> В новом окне
	<input type="radio" name="har_type" value=1> Скрыть
	</td>
	<th class="bgdray">
	<input type="button" class="ajax longbutton" value="Доб. доп. xар"
		data-href="?do=addharact&plugin=xKatalog&prop={{katalog_id}}" 
		data-complete="reload();" 
	></td>
	</th>
</tr>	
</table>
{% if katchilds %}
<table class="greentab align_center tahoma long ctext size11  align_middle" style="margin-bottom:2px;">
	{% for x in properties -%}
		{% if loop.first %}<col width="90px;">{% endif %}{% endfor -%}
		<col width=30>	
<tr><th class="bgray">Фото</th>
	{% for x in properties %}
		{% if loop.index>1 %}
		<th class="bgdray">{{ x }}</th>
		{% endif %}
	{% endfor %}
		<th class="bgdray" ></th>
</tr>
{% for child in katchilds %}
<tr class="odd">
	<td id="loadpic_{{child.id}}" class="cell align_center align_middle" title="">
		<div class="picture_upload" alt="small" >
		<img src="{{child.pic_small|default('img/folderb.gif')}}"></div>
		<input name="pic_small_{{child.id}}_{{id}}" type="text" style="vertical-align:middle;display:none;">
	</td>	
	{% set keys=properties|keys %}	
	{% for x in keys %}
		{% if loop.index>1 %}
	<td  class="cell"><input type="text" style="margin:0;" class="digit6" name="{{x}}_{{child.id}}_{{id}}"></td>
		{% endif %}
	{% endfor %}
	<th class="bgdray" >
		<input type="button" class="remrec"></th>
</tr>
{% endfor %}
</table>{% endif %}
</div>
</div>

</div>
{% endblock %}

## <?php
//<% point_start('plugin_control1') ; %>

// обработка дополнительного поля ввода в форме администрирования статьи
	if(!!$element->article && $form->var['katalogid_'.$element->article->v['id']]){
	   $this->export('katalog_code','setCode',
	   	array('code'=>$form->var['katalogid_'.$element->article->v['id']]
	   		,'article'=>$element->article->v['id']
	   		,'menu'=>$item->v['id']));
	}
//<% point_start('plugin_control2');%>

// обработка дополнительного поля ввода в форме администрирования статьи
	$x=$this->export('katalog_code','getCode',$element->article->v['id']);
	if(!empty($x) && !empty($x['code'])){
  		$x=$x['code'];
	} else {
  		$x='';
	}
  $form->var['katalogid_'.$element->article->v['id']]=$x;
//<% point_start('plugin_body') %>


	/*
	 * вставка в шаблон названия категории - вызывает обработку в xKatalog 
	 */

include_once 'katalog_self.php';

/**
 * собственно товар.
 *
 */
class xKItem extends xCommon {
	
	
	function __construct($v,$parent){
		$this->v=$v;
		$this->parent=$parent;
		$this->el=array();
	}
	/**
	 * (non-PHPdoc)
	 * @see xCommon::getForm()
	 */
	function getForm($lev=0){
		global $engine;
		$par=$this->v; 
		$par['level']=$lev;
		$header=array();
		$rows=array();
		$pictures=array();
		$def=$engine->export('defaultValues','defVal',type_KATALOGEL);
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
				$article=$v;
			}
  		}
		if(!empty($article)){
			$data='';
			foreach($article->el as $v){
				$data.=$v->getForm(true);
			}
  			$par['article']=$data;
  		//	$par['article_count']='&nbsp;(+)';
  		}
  		$par['picture']=$pictures;
		$par['properties']=xKatalog::propList();
		$par['rows']=$rows;
		if(empty($this->katalog) && !empty($this->v['katalog_id'])) {
			$this->katalog=katalog_store::get($this->v['katalog_id']);
		}
		if(!empty($this->katalog)){	
			$this->katalog->prepChilds();
			$par['katchilds']=array();	
			if(!empty($this->katalog->childs))
				foreach($this->katalog->childs as $c){
					$par['katchilds'][]=$c->data;
				}
		}
		$par['id']=ppi($par['id']).'_'.$this->parent;
		return	smart_template(array('tpl_elements','_xKatalog'),$par);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see xCommon::serialize()
	 */
	function serialize(&$formvar,$dir=false){
		global $engine;
		$id='_'.$this->v['id'];
		if(empty($this->katalog) && !empty($this->v['katalog_id'])) {
			$this->katalog=katalog_store::get($this->v['katalog_id']);
		}		
		if($dir && (empty($this->katalog)) && (!empty($formvar['articulx'.$id])||!empty($formvar['descrx'.$id]))){
			$key=array(
					'articul'=>$formvar['articulx'.$id],
					'descr'=>$formvar['descrx'.$id]
				);
			$this->katalog=katalog_store::find($key);
			if(!empty($this->katalog)){
				$_POST['katalog_id'.$id]=$this->katalog->getId();
			} else {
				$this->katalog=katalog_store::create($key);
				$this->katalog->save();
				$_POST['katalog_id'.$id]=$this->katalog->getId();
			}
		};

		xCommon::serialize($formvar,$dir); // сериализуем часть от элемента
		
		if(!empty($this->katalog)){
			foreach ($this->katalog->data as $k=>&$v) {
				if($dir){
					if(isset($_POST[$k.$id]) && $_POST[$k.$id]!=$v){
						$v=$_POST[$k.$id];
						$this->katalog->changed=true;
					}
				} else {
					if(($k!='parent') && ($k!='level') && ($k!='id')){
						$formvar[$k.$id]=$v;
					}
					$formvar['articulx'.$id]=$this->katalog->data['articul'];
					$formvar['descrx'.$id]=$this->katalog->data['descr'];
				}
			}
			$this->katalog->prepChilds();
			if(!empty($this->katalog->childs))
			foreach ($this->katalog->childs as &$child) {
				$childid='_'.$child->getId();
				foreach ($child->data as $k=>&$v) {
					if($dir){
						if(isset($_POST[$k.$childid.$id]) && $_POST[$k.$childid.$id]!=$v){
							$v=$_POST[$k.$childid.$id];
							$child->changed=true;
						}
					} else {
						if(($k!='parent') && ($k!='level') && ($k!='id')){
							$formvar[$k.$childid.$id]=$v;
						}
					}
				}
			}
		}
	}
	
}

/**
 * элемент каталог - служит для демонстрации элементов из таблицы каталога 
 *
 */

class xKatalog extends xCommon {
	
	var $katalog; // ссылка на элемент, реально хранящий данные
	var $IDS=array(); // список ID элементов каталог
	
	function delete($pg){
		debug($pg);
		if(preg_match('/_(\d+)$/',$pg,$m)){
			$elm=katalog_store::get($m[1]);
			// удаляем что-то
			if(!empty($elm)){
				$elm->delete();
			}
			
			// удаляемся только если все элементы тоже удалены
			if(katalog_store::countByCode($this->v['code']))
				return ;
		} else {
			// удаляем пустой элемент
			// ничего не нужно делать
		} 
		// выкидываем себя из записи code_store
		$x=katalog_code::getByElem($this->v['id']);
		if(!empty($x)){
			$x['elem']=0;
			katalog_store::setCode($x);	
		}
		xCommon::delete($pg);
	}
	/**
	 * список полей таблицы katalog для админки 
	 */
	static function propList($el=null){
		global $engine;
		static $prop;
		if(!is_null($el)){
			$prop=$el;
		};
		if(empty($prop)){
			$prop=array('id'=>'Код раздела'
			,'articul'=>'Артикул','descr'=>'Название'
			,'edism'=>'Ед. изм.','cost'=>'Цена. руб.','descr2'=>'Описание');
		}
		return $prop;
	}

	/**
	 * добавить товар-характеристику в таблицу каталог
	 */
	function do_addharact(){
		global $engine;
		if(empty($this->katalog)) {
			$this->katalog=katalog_store::get($_GET['prop']);
		}	
		if($this->katalog)	{
			$this->katalog->cloneAsChild();
			return 'ok';
		} else {
			//print_r($_GET);
			return 'fault!';
		}
	}
	
	/**
	 * убрать поле из таблицы каталог 
	 */
	function do_remprop(){
		global $engine;
		$list=$this->propList();$idx=intval($_GET['prop']);
		//echo ($idx); print_r($list);
		if (($idx!=0) && isset($list[$idx-1])){ 
			unset($list[$idx-1]);
			
			$engine->export('defaultValues','change',type_KATALOGEL,'properties',array_values($list));
		}
		return 'ok';
	}

	/**
	 * добавить новое поле в таблицу каталог
	 */
	function do_addprop(){
		global $engine;
		$list=$this->propList();
		$idx=trim($_POST['name']);
		$type=trim($_POST['type']);
		echo $idx.' '.$type;
		if(!!$idx){
			$list[]=$idx;
			//$engine->export('defaultValues','change',type_KATALOGEL,'properties',array_values($list));
		}
		return 'ok';
	}
	
	/**
	 * сериализуем все вставленные элементы
	 * @see xCommon::serialize()
	 */
	function serialize(&$formvar,$dir=false){
		global $engine;
		$id='_'.$this->v['id'];
		if(empty($this->katalog) && !empty($this->v['katalog_id'])) {
			$this->katalog=katalog_store::get($this->v['katalog_id']);
		}		
		if($dir && (empty($this->katalog)) && (!empty($formvar['articulx'.$id])||!empty($formvar['descrx'.$id]))){
			$key=array(
					'articul'=>$formvar['articulx'.$id],
					'descr'=>$formvar['descrx'.$id]
				);
			$this->katalog=katalog_store::find($key);
			if(!empty($this->katalog)){
				$_POST['katalog_id'.$id]=$this->katalog->getId();
			} else {
				$this->katalog=katalog_store::create($key);
				$this->katalog->save();
				$_POST['katalog_id'.$id]=$this->katalog->getId();
			}
		};

		xCommon::serialize($formvar,$dir); // сериализуем часть от элемента
		
		if(!empty($this->katalog)){
			foreach ($this->katalog->data as $k=>&$v) {
				if($dir){
					if(isset($_POST[$k.$id]) && $_POST[$k.$id]!=$v){
						$v=$_POST[$k.$id];
						$this->katalog->changed=true;
					}
				} else {
					if(($k!='parent') && ($k!='level') && ($k!='id')){
						$formvar[$k.$id]=$v;
					}
					$formvar['articulx'.$id]=$this->katalog->data['articul'];
					$formvar['descrx'.$id]=$this->katalog->data['descr'];
				}
			}
			$this->katalog->prepChilds();
			if(!empty($this->katalog->childs))
			foreach ($this->katalog->childs as &$child) {
				$childid='_'.$child->getId();
				foreach ($child->data as $k=>&$v) {
					if($dir){
						if(isset($_POST[$k.$childid.$id]) && $_POST[$k.$childid.$id]!=$v){
							$v=$_POST[$k.$childid.$id];
							$child->changed=true;
						}
					} else {
						if(($k!='parent') && ($k!='level') && ($k!='id')){
							$formvar[$k.$childid.$id]=$v;
						}
					}
				}
			}
		}
	}
	
	function xKatalog(){
		global $engine;
		xCommon::xCommon(array(
			'fields'=>array(
				'item_name'=>array('type'=>'txt')
				,'katalog_id'=>array('type'=>'txt') // 50,70,100
		// фото панель
				,'code'=>array('type'=>'txt') // 50,70,100
				,'border'=>array('type'=>'txt') // 50,70,100
				,'ftpl'=>array('type'=>'txt') // 50,70,100
				,'sizex'=>array('type'=>'txt') // 50,70,100
				,'sizey'=>array('type'=>'txt') // 50,70,100
				,'bsizex'=>array('type'=>'txt') // 50,70,100
				,'bsizey'=>array('type'=>'txt') // 50,70,100
				,'pic_align'=>array('type'=>'txt') // 50,70,100
				,'pic_small'=>array('type'=>'txt') // 50,70,100
				//,'item_name'=>array('type'=>'txt') // 50,70,100
				//,'articul'=>array('type'=>'txt') // 50,70,100
				,'target'=>array('type'=>'txt') // 50,70,100
				,'hidden'=>array('title'=>'Текст заголовка') // верхний заголовок
			) 
			,'inner'=>array(
				'picture'=>array('width'=>125,'type'=>type_PIC,'button'=>'Доб.Фото')
				,'article'=>array('width'=>125,'type'=>type_ARTICLE,'button'=>'Доб.Описание')
			)
			,'align'=>true
		));
		// проверка значений по умолчанию
		$def=$engine->export('defaultValues','defVal',type_KATALOGEL);
		if(!isset($def['properties'])){
			$engine->export('defaultValues','change',type_KATALOGEL,'properties',$this->propList());
		} else {
			$this->propList($def['properties']);
		}
		//if properties
	}	
	
	/**
	 * вставить новый элемент в статью xTP
	 * Enter description here ...
	 * @param $tp
	 */
	function insertInto($tp){
		global $engine;
		//debug('insert into');
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
//		error_log(__FILE__.' '.__LINE__."\n".print_r($article,true).$tp,3,'z:/logs/debug.log');
		return ' ';
	}
	
	/**
	 * вывести список всех элементов +1
	 * @see xCommon::getForm()
	 */
	function getForm($lev=0){
		global $engine;
		if (!empty($this->v['code'])){
			$elm=katalog_store::getByCode($this->v['code']);
		//	debug($elm);
		} 
		if(empty($elm)) {
			$elm=array(array());
		}
		$result='';
		foreach($elm as $e){
			$x=new xKItem($e,$this->v['id']);
			$result.= $x->getForm($lev,$this->v['id']);
		}
		return $result;
		
	}
	
	function getText(&$keys){
		if(empty($this->katalog) && !empty($this->v['katalog_id'])) {
			$this->katalog=katalog_store::get($this->v['katalog_id']);
		}
		$par=array();
		
		if(!empty($this->katalog)){
			$par['par']=array_merge($this->v,$this->katalog->data);
			$this->katalog->prepChilds();
			$par['katchilds']=array();	
			if(!empty($this->katalog->childs))
				foreach($this->katalog->childs as $c){
					$par['katchilds'][]=$c->data;
				}
		}		
		//debug($par);
		return smart_template(array('tpl_jelements','_xKatalog'),$par);
		//'<table border=1>'.$table.'</table>';
	}
	
	function Create($key,$parent,$id) {
		global $engine;
		$x=$engine->export('katalog_code','getCode',$id);
		if (!empty($x) && !empty($x['code'])){
			$key['code']=$x['code'];	
		}
		$self= $engine->nodeAdd($parent,$key);
		if (!empty($x) && empty($x['elem'])){
			$x['elem']=$self;
			$engine->export('katalog_code','setCode',$x);
		}
		return $self;
	}
}


//<% point_finish() %>