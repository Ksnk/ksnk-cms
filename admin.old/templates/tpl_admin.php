<?php
class tpl_admin extends tpl {

function _(&$par){		
		return tpl::_a($par['paramedit'],array('tpl_admin','paramedit')).'
'.tpl::_a($par['theheader'],array('tpl_admin','theheader')).'
'.tpl::_a($par['theusers'],array('tpl_admin','theusers')).'
'.tpl::_a($par['thecounters'],array('tpl_admin','thecounters')).'

'.tpl::_a($par['techonline_pref'],array('tpl_admin','techonline_pref')).'

<!--
 Администрирование

 - форма ввода панели "Список загрузок файлов"

 -->
'.tpl::_a($par['order_elm_start'],array('tpl_admin','order_elm_start')).'

'.tpl::_a($par['order_elm_fin'],array('tpl_admin','order_elm_fin')).'

'.tpl::_a($par['align_elm'],array('tpl_admin','align_elm')).'

'.tpl::_a($par['win_elm'],array('tpl_admin','win_elm')).'
'.tpl::_a($par['win_elm2'],array('tpl_admin','win_elm2')).'

'.tpl::_a($par['delrec_elm'],array('tpl_admin','delrec_elm')).'

'.tpl::_a($par['psubm_elm'],array('tpl_admin','psubm_elm')).'


'.tpl::_a($par['href'],array('tpl_admin','href')).'

'.tpl::_a($par['uploads'],array('tpl_admin','uploads'));
}

function paramedit(&$par){		
		return '<form action="" method="POST" name="paramedit">
	<div class="red">'.(isset($par['error'])?$par['error']:'').'</div>
<table  class="thetable tahoma">
'.tpl::_a($par['list'],array('tpl_admin','paramedit_list')).'
<tr  ><th class="bblue" style="padding:2px;"colspan=2 ><input type="submit" class="button" value="Сохранить">
</th></tr>
</table>
</form>';
}

function paramedit_list(&$par){		
		return tpl::_a($par['subx'],array('tpl_admin','paramedit_list_subx')).'
<tr  class="odd"><th class="align_right" >'.(isset($par['title'])?$par['title']:'').'</th>
'.tpl::_a($par['input'],array('tpl_admin','paramedit_list_input')).'

'.tpl::_a($par['button'],array('tpl_admin','paramedit_list_button')).'
'.tpl::_a($par['textarea'],array('tpl_admin','paramedit_list_textarea')).'

</tr>';
}

function paramedit_list_subx(&$par){		
		return '<tr><th  class="bblue align_center" colspan=2>'.(isset($par['sub'])?$par['sub']:'').'</th></tr>';
}

function paramedit_list_input(&$par){		
		return '<th ><input class=\'fills\' type="'.(isset($par['type1'])?$par['type1']:'').'" style="width:auto;" name="'.(isset($par['name'])?$par['name']:'').'" value="'.(isset($par['value'])?$par['value']:'').'" '.(isset($par['type2'])?$par['type2']:'').'></th>';
}

function paramedit_list_button(&$par){		
		return '<th ><input class=\'button\' type="submit" name="'.(isset($par['function'])?$par['function']:'').'" value="'.(isset($par['name'])?$par['name']:'').'"></th>';
}

function paramedit_list_textarea(&$par){		
		return '<th ><textarea class=\'fills\' style="height:3em;width:auto;" name="'.(isset($par['name'])?$par['name']:'').'"></textarea></th>';
}

function theheader(&$par){		
		return '<div style="padding:5px 0;"
class="size16 long menu align_center red"><u>'.(isset($par['header'])?$par['header']:'').'</u></div>
'.tpl::_a($par['descr'],array('tpl_admin','theheader_descr')).'
<div style="padding:5px 0;" class="align_center">'.(isset($par['data'])?$par['data']:'').'</div>';
}

function theheader_descr(&$par){		
		return '<div style="padding:0px;" class="size16 long align_center">'.(isset($par['descr'])?$par['descr']:'').'</div>';
}

function theusers(&$par){		
		return '<div style="padding:5px 0;"
class="size16 long menu align_center red"><u>'.(isset($par['header'])?$par['header']:'').'</u><form action="" method="post">
<input type="text" name="searchuser"><input type="image" src="img/search.gif" name="search" >
</input></form>
</div>
'.tpl::_a($par['descr'],array('tpl_admin','theusers_descr')).'
<div style="padding:5px 0;" class="align_center">'.(isset($par['data'])?$par['data']:'').'</div>';
}

function theusers_descr(&$par){		
		return '<div style="padding:0px;" class="size16 long align_center">'.(isset($par['descr'])?$par['descr']:'').'</div>';
}

function thecounters(&$par){		
		return '<div style="padding:5px 0;"
class="size16 long menu align_center red"><u>'.(isset($par['header'])?$par['header']:'').'</u></div>
'.tpl::_a($par['data'],array('tpl_admin','thecounters_data'));
}

function thecounters_data(&$par){		
		return '<hr>
	Счетчик : '.(isset($par['counter'])?$par['counter']:'').'; всего уникальных кликов:'.(isset($par['total'])?$par['total']:'').'<br>
	За последний месяц:<br>
'.tpl::_a($par['list'],array('tpl_admin','thecounters_data_list'));
}

function thecounters_data_list(&$par){		
		return (isset($par['date'])?$par['date']:'').': '.(isset($par['cnt'])?$par['cnt']:'').'<br>';
}

function techonline_pref(&$par){		
		return ;
}

function order_elm_start(&$par){		
		return '<table class="compact"><tr><td><input type="button" class="win_max p120"
 onclick="order(this,\'+\');"
>
</td><td>';
}

function order_elm_fin(&$par){		
		return '</td><td>
<input type="button" class="win_max p105"
 onclick="order(this,\'-\');"
>
</td></tr></table>';
}

function align_elm(&$par){		
		return '<table class="compact glass"><tr><td>cлева</td><td>центр</td><td>справа</td></tr>
<tr><td><input  type="radio" name="align" value="0"></td><td>
<input  type="radio" name="align" value="1"></td><td>
<input  type="radio" name="align"  value="2"></td></tr></table>';
}

function win_elm(&$par){		
		return '<div class="win_max open_close">&nbsp;</div>';
}

function win_elm2(&$par){		
		return '<div class="win_max closed  open_close">&nbsp;</div>';
}

function delrec_elm(&$par){		
		return '<input type="button" class="win_max p00"
 onclick="delrec(this)">';
}

function psubm_elm(&$par){		
		return '<input type="submit" name="add_new_line" class="win_max p90" value=\'&nbsp;\'>';
}

function href(&$par){		
		return '<form action="" method="POST" name="href">

<table class="thetable">
<tr><th colspan=2>
Название элемента (не отображается на сайте)
</th><td colspan=3><input type="text" name="name">
</td></tr>
<tr><th colspan=2>
Количество столбцов для вывода списка
</th><td colspan=3><input  type="text" name="columns">
</td></tr>
<tr><th colspan=2>
Выравнивание
</th><td colspan=3>'.tpl::_ax(tpl::_export('','tpl','admin','align_elm'),array('tpl_admin','href___tpl_admin_align_elm')).'
</td></tr>
<tr><th colspan=5 class="align_center" style="background:white;height:30px">
</th></tr>
<tr>
<th>ссылка</th>
<th>текст</th>
<th>порядок</th>
<th>'.tpl::_ax(tpl::_export('','tpl','admin','win_elm'),array('tpl_admin','href___tpl_admin_win_elm')).'</th>
</tr>
'.tpl::_a($par['list'],array('tpl_admin','href_list')).'
<tr>
<td>
<input  type="text" name="filename">
</td>
<td><input type="hidden" name="id">
<input  type="text" name="text"></td>
<td></td><td>
'.tpl::_ax(tpl::_export('','tpl','admin','psubm_elm'),array('tpl_admin','href___tpl_admin_psubm_elm')).'
<input type="hidden" class="del" name="del">
</td>
</tr>
<tr><th colspan=5 height="5px;"></th></tr><tr>
<th colspan=5 class="align_center" style="background:white;height:30px">
<input type="submit" style="width:auto;"  name="save" value="Сохранить">
</th>
</table>
'.(isset($par['pages'])?$par['pages']:'').'
</form>';
}

function href_list(&$par){		
		return '<tr id="id_'.(isset($par['id'])?$par['id']:'').'">
<td >
<input type="text" name="filename_'.(isset($par['id'])?$par['id']:'').'">
</td>
<td ><input type="text" style="width:400px" name="text_'.(isset($par['id'])?$par['id']:'').'"></td>
<td class="align_center" style="width:55px;" nowrap>
'.tpl::_ax(tpl::_export('','tpl','admin','order_elm_start'),array('tpl_admin','href_list___tpl_admin_order_elm_start')).'
<input type="text" class="order" style="width:15px;" name="order_'.(isset($par['id'])?$par['id']:'').'">
'.tpl::_ax(tpl::_export('','tpl','admin','order_elm_fin'),array('tpl_admin','href_list___tpl_admin_order_elm_fin')).'
</td>
<td>'.tpl::_ax(tpl::_export('','tpl','admin','delrec_elm'),array('tpl_admin','href_list___tpl_admin_delrec_elm')).'
</td>
</tr>';
}

function uploads(&$par){		
		return '<form action="" method="POST" name="uploads">

<table class="thetable">
<tr><th colspan=2>
Название элемента (не отображается на сайте)
</th><td colspan=3><input type="text" name="name">
</td></tr>
<tr><th colspan=2>
Количество столбцов для вывода списка
</th><td colspan=3><input  type="text" name="columns">
</td></tr>
<tr><th colspan=2>
Выравнивание
</th><td colspan=3>'.tpl::_ax(tpl::_export('','tpl','admin','align_elm'),array('tpl_admin','uploads___tpl_admin_align_elm')).'
</td></tr>
<tr><th colspan=5 class="align_center" style="background:white;height:30px">
</th></tr>
<tr>
<th>файл</th>
<th>описание</th>
<th>порядок</th>
<th>'.tpl::_ax(tpl::_export('','tpl','admin','win_elm'),array('tpl_admin','uploads___tpl_admin_win_elm')).'</th>
</tr>
'.tpl::_a($par['list'],array('tpl_admin','uploads_list')).'
<tr>
<td class="uploader" style="height:auto;">
<input  type="text" name="filename">
</td>
<td><input type="hidden" name="id">
<input  type="text" name="text"></td>
<td></td><td>
'.tpl::_ax(tpl::_export('','tpl','admin','psubm_elm'),array('tpl_admin','uploads___tpl_admin_psubm_elm')).'
<input type="hidden" class="del" name="del">
</td>
</tr>
<tr><th colspan=5 height="5px;"></th></tr>
<tr><th colspan=5 class="align_center" style="background:white;height:30px">
<input type="submit" style="width:auto;"  name="save" value="Сохранить">
</th></tr>
</table>
'.(isset($par['pages'])?$par['pages']:'').'
</form>';
}

function uploads_list(&$par){		
		return '<tr id="id_'.(isset($par['id'])?$par['id']:'').'">
<td class="uploader" style="width:150px;height:auto;">
<input type="text" name="filename_'.(isset($par['id'])?$par['id']:'').'">
</td>
<td ><input type="text" style="width:400px" name="text_'.(isset($par['id'])?$par['id']:'').'"></td>
<td class="align_center" style="width:55px;">
'.tpl::_ax(tpl::_export('','tpl','admin','order_elm_start'),array('tpl_admin','uploads_list___tpl_admin_order_elm_start')).'
<input type="text" class="order" style="width:15px;" name="order_'.(isset($par['id'])?$par['id']:'').'">
'.tpl::_ax(tpl::_export('','tpl','admin','order_elm_fin'),array('tpl_admin','uploads_list___tpl_admin_order_elm_fin')).'
</td>
<td>'.tpl::_ax(tpl::_export('','tpl','admin','delrec_elm'),array('tpl_admin','uploads_list___tpl_admin_delrec_elm')).'</td>
</tr>';
}}
?>