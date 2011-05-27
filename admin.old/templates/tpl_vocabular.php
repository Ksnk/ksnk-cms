<?php
class tpl_vocabular extends tpl {

function _(&$par){		
		return '///// Вывод таблицы каталога

'.tpl::_a($par['admin_vocabular'],array('tpl_vocabular','admin_vocabular')).'


<td style="background:white;width:20px;padding: 0 2px;"><input type="checkbox" id="aaa" value="0"></td></tr>
<th style="background:white;width:20px;padding: 0 2px;"><input type="checkbox" class="select" name="aaa" value="'.(isset($par['id'])?$par['id']:'').'"></th></tr>';
}

function admin_vocabular(&$par){		
		return '<form name="admin'.(isset($par['name'])?$par['name']:'').'" action="" method="POST">
<span style="color:red;font-size:16px;">'.(isset($par['error'])?$par['error']:'').'</span>
<input type=\'hidden\' class="del" name="del">
'.(isset($par['additional'])?$par['additional']:'').'

<table class="table long ctext size11">
<tr>
<th class="bblue" style="width:20px;padding: 0 2px;">№</th>
'.tpl::_a($par['head'],array('tpl_vocabular','admin_vocabular_head')).'
<th class="bblue"></th>
</tr>
'.tpl::_a($par['list'],array('tpl_vocabular','admin_vocabular_list')).'
<tr class="odd" id="newrow" style="display:none;">
<th>&raquo;</th>
'.tpl::_a($par['plus'],array('tpl_vocabular','admin_vocabular_plus')).'
<th ></th>
</tr>

</table>
'.tpl::_d($par['additional2'],'<table class="table long ctext size11">
<tr>
<th class="bblue align_center" STYLE="padding:2px;">
<input class="button savebutton" type="submit"  disabled="disabled" value=" Сохранить ">
<input type="button" class="button" onclick="element.$(\'newrow\').style.display=\'\';this.disabled=\'disabled\';" name="newRecord" value="Добавить">
</th>
</tr></table>').'

<table class="table long ctext size11">
<tr><th style="background:white;">
'.(isset($par['pages'])?$par['pages']:'').'
</th></tr></table>

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

function admin_vocabular_head(&$par){		
		return '<th class="bblue '.(isset($par['class'])?$par['class']:'').'">'.(isset($par['title'])?$par['title']:'').'</th>';
}

function admin_vocabular_list(&$par){		
		return '<tr class="'.tpl::_d($par['trclass'],'odd').'" id="'.tpl::_d($par['prefix'],'rl').'_'.(isset($par['id'])?$par['id']:'').'"><th class="nopage" style="width:20px;">'.(isset($par['numb'])?$par['numb']:'').'</th>
'.tpl::_a($par['row'],array('tpl_vocabular','admin_vocabular_list_row')).'
'.tpl::_a($par['sort'],array('tpl_vocabular','admin_vocabular_list_sort')).'
<th style="width:20px;padding: 0 2px;">'.tpl::_ax(tpl::_export('','tpl','admin','delrec_elm'),array('tpl_vocabular','admin_vocabular_list___tpl_admin_delrec_elm')).'</th>
</tr>

'.tpl::_a($par['subkat'],array('tpl_vocabular','admin_vocabular_list_subkat')).'
<tr class="subkat" id="newsubkat_'.(isset($par['id'])?$par['id']:'').'" style="display:none;">
<th>&raquo;</th>
'.tpl::_a($par['plus'],array('tpl_vocabular','admin_vocabular_list_plus')).'
<th ></th>
</tr>';
}

function admin_vocabular_list_row(&$par){		
		return '<t'.tpl::_d($par['d'],'d').' style="'.(isset($par['style'])?$par['style']:'').'" class="'.tpl::_d($par['class'],'text_edit').'" id="'.(isset($par['name'])?$par['name']:'').'_'.(isset($par['id'])?$par['id']:'').'">'.(isset($par['value'])?$par['value']:'').'</t'.tpl::_d($par['d'],'d').'>';
}

function admin_vocabular_list_sort(&$par){		
		return '<th class="nopage align_center" style="padding:0 2px;" nowrap>
'.tpl::_ax(tpl::_export('','tpl','admin','order_elm_start'),array('tpl_vocabular','admin_vocabular_list_sort___tpl_admin_order_elm_start')).'
<input type="text" class="order size11" onkeydown="need_Save()" style="width:15px;" name="item_order_'.(isset($par['id'])?$par['id']:'').'">
'.tpl::_ax(tpl::_export('','tpl','admin','order_elm_fin'),array('tpl_vocabular','admin_vocabular_list_sort___tpl_admin_order_elm_fin')).'
</th>';
}

function admin_vocabular_list_subkat(&$par){		
		return '<tr class="subkat subkat_'.(isset($par['parent_id'])?$par['parent_id']:'').'" id="'.tpl::_d($par['prefix'],'rl').'_'.(isset($par['id'])?$par['id']:'').'"><th class="nopage" style="width:20px;">'.(isset($par['numb'])?$par['numb']:'').'</th>
'.tpl::_a($par['row'],array('tpl_vocabular','admin_vocabular_list_subkat_row')).'
'.tpl::_a($par['sort'],array('tpl_vocabular','admin_vocabular_list_subkat_sort')).'
<th style="width:20px;padding: 0 2px;">'.tpl::_ax(tpl::_export('','tpl','admin','delrec_elm'),array('tpl_vocabular','admin_vocabular_list_subkat___tpl_admin_delrec_elm')).'</th>
</tr>';
}

function admin_vocabular_list_subkat_row(&$par){		
		return '<t'.tpl::_d($par['d'],'d').' style="'.(isset($par['style'])?$par['style']:'').'" class="'.tpl::_d($par['class'],'text_edit').'" id="'.(isset($par['name'])?$par['name']:'').'_'.(isset($par['id'])?$par['id']:'').'">'.(isset($par['value'])?$par['value']:'').'</t'.tpl::_d($par['d'],'d').'>';
}

function admin_vocabular_list_subkat_sort(&$par){		
		return '<th class="nopage align_center" style="padding:0 2px;" nowrap>
'.tpl::_ax(tpl::_export('','tpl','admin','order_elm_start'),array('tpl_vocabular','admin_vocabular_list_subkat_sort___tpl_admin_order_elm_start')).'
<input type="text" class="order size11" onkeydown="need_Save()" style="width:15px;" name="item_order_'.(isset($par['id'])?$par['id']:'').'">
'.tpl::_ax(tpl::_export('','tpl','admin','order_elm_fin'),array('tpl_vocabular','admin_vocabular_list_subkat_sort___tpl_admin_order_elm_fin')).'
</th>';
}

function admin_vocabular_list_plus(&$par){		
		return '<td  class="'.tpl::_d($par['class'],'text_edit').'" id="'.(isset($par['name'])?$par['name']:'').'"></td>';
}

function admin_vocabular_plus(&$par){		
		return '<td  class="'.tpl::_d($par['class'],'text_edit').'" id="'.(isset($par['name'])?$par['name']:'').'"></td>';
}}
?>