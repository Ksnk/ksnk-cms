<?php
class tpl_katalog extends tpl {

function _(&$par){		
		return '///// Вывод таблицы каталога

'.tpl::_a($par['additional'],array('tpl_katalog','additional')).'

///// Замена предыдушего шаблона

'.tpl::_a($par['additional1'],array('tpl_katalog','additional1')).'

'.tpl::_a($par['admin_katalog'],array('tpl_katalog','admin_katalog')).'
'.tpl::_a($par['category_list'],array('tpl_katalog','category_list'));
}

function additional(&$par){		
		return '<table class="bblue table long tahoma size11 "><tr >
<td>&nbsp;</td>
<td class="align_right">отмеченные</td>
<td class="align_left">
	<input type="hidden" name="doIt['.(isset($par['i'])?$par['i']:'').']" id="doIt['.(isset($par['i'])?$par['i']:'').']">
<select class="tahoma fills size11" title="" onchange="return mean(this,'.(isset($par['i'])?$par['i']:'').');" name="sel_act['.(isset($par['i'])?$par['i']:'').']">
	<option value=""></option>
	<option value="spec">в Спецпредложения</option>
	<option value="new">в Новинки</option>
	<option value="del">Удалить</option>
</select>
<!-- <input type="submit" class="button " name="doIt['.(isset($par['i'])?$par['i']:'').']" value="Ввести">  --> </td>
<td><div class="uploader" >
	<input onclick="loadCSV(this)" type="text" style="display:none;"
			name="item_csv_0">	<input type="button" class="button"
		 value="Экспорт CSV">
	 </div>
</td>
		 
<td class="align_right">
<input type="button" class="button" onclick="element.$(\'newrow\').style.display=\'\';this.disabled=\'disabled\';" name="newRecord" value=" Добавить '.(isset($par['what'])?$par['what']:'').'">
</td><td>
<input type="submit" class="button savebutton" disabled="disabled" value="Сохранить">
</td>
<td>&nbsp;</td>
</tr></table>';
}

function additional1(&$par){		
		return '<table class="bblue table long tahoma size11 "><tr >
<td>&nbsp;</td>
<td class="align_right">отмеченные</td>
<td class="align_left">
	<input type="hidden" name="doIt['.(isset($par['i'])?$par['i']:'').']" id="doIt['.(isset($par['i'])?$par['i']:'').']">
<select class="tahoma fills size11" title="" onchange="return mean(this,'.(isset($par['i'])?$par['i']:'').');" name="sel_act['.(isset($par['i'])?$par['i']:'').']">
	<option value=""></option>
	'.tpl::_a($par['options'],array('tpl_katalog','additional1_options')).'
	<option value="del">Удалить</option>
</select>
<!-- <input type="submit" class="button " name="doIt['.(isset($par['i'])?$par['i']:'').']" value="Ввести">  --> </td>
<td><div class="uploader" >
	<input onclick="loadCSV(this)" type="text" style="display:none;"
			name="item_csv_0">	<input type="button" class="button"
		 value="Экспорт CSV">
	 </div>
</td>
		 
<td class="align_right">
<input type="button" class="button" onclick="element.$(\'newrow\').style.display=\'\';this.disabled=\'disabled\';" name="newRecord" value=" Добавить '.(isset($par['what'])?$par['what']:'').'">
</td><td>
<input type="submit" class="button savebutton" disabled="disabled" value="Сохранить">
</td>
<td>&nbsp;</td>
</tr></table>';
}

function additional1_options(&$par){		
		return '<option value="'.(isset($par['id'])?$par['id']:'').'">'.(isset($par['value'])?$par['value']:'').'</option>';
}

function admin_katalog(&$par){		
		return '<form name=\'admin_katalog\' action="" method="POST">
<div class="align_center menu long"><span class="red">'.(isset($par['paragr'])?$par['paragr']:'').'</span>
</div>
<span style="color:red;font-size:16px;">'.(isset($par['error'])?$par['error']:'').'</span>
<input type=\'hidden\' class="del" name="del">
<table class="table long ctext size11"><tr style="display:'.tpl::_d($par['xxx'],'none').';">
<td>показать</td>
<td>
<select class="tahoma size11" onchange="_goto(\'cat\',this.value)"
	 name="selcat">
	'.tpl::_a($par['option'],array('tpl_katalog','admin_katalog_option')).'
	<option value="null">&raquo; без категорий &laquo;</option>
	<option value="all">&raquo; все товары &laquo;</option>
</select></td>
</tr></table>

<table class="table long ctext size11 bblue"><tr >
<td>&nbsp;</td>
<td nowrap  class="align_right">с отмеченным</td>
<td  class="align_left">
<select class="tahoma size11" name="selact">
	<option value="null">в Спецпредложения</option>
	<option value="null">в Новинки</option>
	<option value="all">Удалить</option>
</select>
<input type="submit" class="button " value="Поехали"></td>
<td class="align_center">
<input type="submit" class="button savebutton" disabled="disabled" value="Сохранить">
</td>
<td>&nbsp;</td>
</tr></table>
<div style="width:100%;overflow:auto;">
<table class="table long ctext size11">
<tr><td style="background:white;"><input type="checkbox" id="aaa" value="0"></td>
<th style="padding:10px 0;" >№</th>
'.tpl::_a($par['head'],array('tpl_katalog','admin_katalog_head')).'
<th></th>
</tr>
'.tpl::_a($par['list'],array('tpl_katalog','admin_katalog_list')).'
</table>

<table class="thetable long tahoma ctext size11">
<tr>
<th class="align_center" >
	<input class="button savebutton"  type="submit" disabled="disabled" value="сохранить">
	<input class="button"  type="submit" name="add_item" value="Добавить">
</th></tr>
<tr><td  style="background:white;height:30px">
'.(isset($par['pages'])?$par['pages']:'').'
</td></tr></table>
</div>
<script type="text/javascript">
element.add_event(element.$(\'aaa\'),\'click\',function(){
	var e = this;
	element.allClass(this.form,\'select\',function(el){
		el.checked = e.checked;
	})
	e=null;
})
</script>
</form>';
}

function admin_katalog_option(&$par){		
		return '<option value="'.(isset($par['id'])?$par['id']:'').'">'.(isset($par['name'])?$par['name']:'').'</option>';
}

function admin_katalog_head(&$par){		
		return '<th class="'.(isset($par['class'])?$par['class']:'').'">'.(isset($par['title'])?$par['title']:'').'</th>';
}

function admin_katalog_list(&$par){		
		return '<tr class="'.tpl::_d($par['trclass'],'odd').'" id="rl_'.(isset($par['id'])?$par['id']:'').'"><th style="background:white;padding:0;"><input type="checkbox" class="select" name="aaa" value="'.(isset($par['id'])?$par['id']:'').'"></th><th class="nopage">'.(isset($par['numb'])?$par['numb']:'').'</th>
'.tpl::_a($par['row'],array('tpl_katalog','admin_katalog_list_row')).'
<td style="width:20px">'.tpl::_ax(tpl::_export('','tpl','admin','delrec_elm'),array('tpl_katalog','admin_katalog_list___tpl_admin_delrec_elm')).'</td>
</tr>';
}

function admin_katalog_list_row(&$par){		
		return '<td class="'.tpl::_d($par['class'],'text_edit').'" id="'.(isset($par['name'])?$par['name']:'').'_'.(isset($par['id'])?$par['id']:'').'">'.(isset($par['value'])?$par['value']:'').'</td>';
}

function category_list(&$par){		
		return '<div class="menu size11">Спиcок категорий</div>
<ul class="menu size11">'.(isset($par['data'])?$par['data']:'').'</ul>
<hr>
<div class="menu size11"><a href="'.tpl::_ax(tpl::_export('','curl','cat'),array('tpl_katalog','category_list___curl_cat')).'cat=new">новинки</a> </div>
<div class="menu size11"><a href="'.tpl::_ax(tpl::_export('','curl','cat'),array('tpl_katalog','category_list___curl_cat')).'cat=spec">Спецпредложения</a> </div>
<div class="menu size11"><a href="'.tpl::_ax(tpl::_export('','curl','cat'),array('tpl_katalog','category_list___curl_cat')).'cat=null">Без категорий</a> </div>';
}}
?>