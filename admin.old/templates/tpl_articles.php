<?php
class tpl_articles extends tpl {

function _(&$par){		
		return tpl::_a($par['a_list'],array('tpl_articles','a_list')).'

'.tpl::_a($par['a_list_script'],array('tpl_articles','a_list_script'));
}

function a_list(&$par){		
		return '<form action="" method="POST" name="a_list">
<span style="color:red;font-size:16px;">'.(isset($par['error'])?$par['error']:'').'</span>
<input type=\'hidden\' class="del" name="del">
<table class="thetable long tahoma ctext size11">
<tr>
<th>Дата</th><th>автор</th><th>заголовок</th><th></th>
<th>порядок</th>
<th></th>
</tr>
'.tpl::_a($par['list'],array('tpl_articles','a_list_list')).'
<tr><td class="align_center" colspan=6 style="background:white;">
		<input type="submit" class="savebutton" value="сохранить">
	</td></tr>
<tr><td colspan=6>
<input type="submit"
			title="добавить новую статью" class="win_max"
			style="float:right;background-position: 0 -90px;" name="art_add" value="&nbsp;">
</td></tr>
<tr><td colspan=6  style="background:white;height:30px">
'.(isset($par['pages'])?$par['pages']:'').'
</td></tr>
</table>
</form>';
}

function a_list_list(&$par){		
		return '<tr id="ar_'.(isset($par['id'])?$par['id']:'').'">
	<td id="art_date_'.(isset($par['id'])?$par['id']:'').'"  class="text_edit">'.(isset($par['date'])?$par['date']:'').'</td>
	<td id="art_author_'.(isset($par['id'])?$par['id']:'').'" class="text_edit">'.(isset($par['author'])?$par['author']:'').'</td>
	<td id="art_title_'.(isset($par['id'])?$par['id']:'').'" class="html_edit">'.(isset($par['title'])?$par['title']:'').'</td>
	<td id="art_text_'.(isset($par['id'])?$par['id']:'').'" class="html_edit">'.(isset($par['b_text'])?$par['b_text']:'').'</td>
<td class="align_center" nowrap>
'.tpl::_ax(tpl::_export('','tpl','admin','order_elm_start'),array('tpl_articles','a_list_list___tpl_admin_order_elm_start')).'
<input type="text" class="order" style="width:15px;" name="order_'.(isset($par['id'])?$par['id']:'').'">
'.tpl::_ax(tpl::_export('','tpl','admin','order_elm_fin'),array('tpl_articles','a_list_list___tpl_admin_order_elm_fin')).'
</td>
<td>'.tpl::_ax(tpl::_export('','tpl','admin','delrec_elm'),array('tpl_articles','a_list_list___tpl_admin_delrec_elm')).'</td>
</tr>';
}

function a_list_script(&$par){		
		return ;
}}
?>