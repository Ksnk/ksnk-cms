<?php
class tpl_runningline extends tpl {

function _(&$par){		
		return '///// панель редактирования голосования
'.tpl::_a($par['admin_runningline'],array('tpl_runningline','admin_runningline'));
}

function admin_runningline(&$par){		
		return '<form name=\'admin_runningline\' action="" method="POST">
<span style="color:red;font-size:16px;">'.(isset($par['error'])?$par['error']:'').'</span>
<input type=\'hidden\' class="del" name="del">
<table class="fixed thetable long tahoma ctext size11">
<col width="0*"><col width="50px"><col width="20px">
<tr>
<th colspan=1 class="bblue">Текст бегущей строки</th>
<th>поря<br>док</th><th></th>
</tr>
'.tpl::_a($par['list'],array('tpl_runningline','admin_runningline_list')).'
<tr><th class="align_center" colspan=3 >
	<input type="submit" class="button savebutton" value="сохранить">
	<input type="submit" class="button savebutton" value="Добавить">
</th></tr>
<tr class="even">
<td colspan=3>
<textarea rows=1 cols=80 class="long tahoma fills size11" title="Новый вопрос" name="new_descr"></textarea>
</td>
</tr><tr><td colspan=3 style="background:white;height:30px">
'.(isset($par['pages'])?$par['pages']:'').'
</td></tr></table>
</form>';
}

function admin_runningline_list(&$par){		
		return '<tr class="'.tpl::_d($par['trclass'],'even').'" id="rl_'.(isset($par['id'])?$par['id']:'').'">
<td class="long text_edit" id="descr_'.(isset($par['id'])?$par['id']:'').'">'.(isset($par['descr'])?$par['descr']:'').'</td>
<td style="padding:5px 0;" class="align_center" nowrap>
'.tpl::_ax(tpl::_export('','tpl','admin','order_elm_start'),array('tpl_runningline','admin_runningline_list___tpl_admin_order_elm_start')).'
<input type="text" class="order" style="border:0;width:15px;" name="order_'.(isset($par['id'])?$par['id']:'').'">
'.tpl::_ax(tpl::_export('','tpl','admin','order_elm_fin'),array('tpl_runningline','admin_runningline_list___tpl_admin_order_elm_fin')).'
</td><td  style="padding:5px 0;">'.tpl::_ax(tpl::_export('','tpl','admin','delrec_elm'),array('tpl_runningline','admin_runningline_list___tpl_admin_delrec_elm')).'</td>
</tr>';
}}
?>