<?php
class tpl_votes extends tpl {

function _(&$par){		
		return '/////������ ������ �����������
'.tpl::_a($par['votes_list'],array('tpl_votes','votes_list')).'
///// ������ �������������� �����������
'.tpl::_a($par['admin_vote'],array('tpl_votes','admin_vote'));
}

function votes_list(&$par){		
		return '<table class="tahoma"><tr><td>
<ul class="menu ctext tahoma">
'.tpl::_a($par['list'],array('tpl_votes','votes_list_list')).'
</ul>
<hr style="margin-top:20px;">
<a style="display:block; padding:4px 15px"
class=\'button link menu\' href="'.tpl::_ax(tpl::_export('','curl','vote'),array('tpl_votes','votes_list___curl_vote')).'vote=new">��������</a>
</td></tr></table>';
}

function votes_list_list(&$par){		
		return '<li><a href="'.(isset($par['url'])?$par['url']:'').'">'.(isset($par['name'])?$par['name']:'').'</a></li>';
}

function admin_vote(&$par){		
		return '<form name=\'admin_vote\' action="" method="POST">
<span style="color:red;font-size:16px;">'.(isset($par['error'])?$par['error']:'').'</span>
<input type=\'hidden\' class="del" name="del">
<table class="thetable long tahoma ctext size11">
<tr>
<th>������</th>
<th>1 ���.</th>
<th>�������</th>
<th>���<br>���.</th>
<th></th>
</tr><tr class="even" id=\'vt_'.(isset($par['id'])?$par['id']:'').'\'>
<td class="long text_edit" id="descr_'.(isset($par['id'])?$par['id']:'').'">
'.(isset($par['descr'])?$par['descr']:'').'
</td><td class="glass">
<input type="checkbox" disabled="disabled" title="����������� �� ����� �� ���������� ���������?" name="radio_'.(isset($par['id'])?$par['id']:'').'">
</td><td class="glass">
<input type="checkbox" onchange="need_Save()" name="active_'.(isset($par['id'])?$par['id']:'').'" value="1">
</td><td>
'.(isset($par['page'])?$par['page']:'').'
</td>
<td style="width:20px">'.tpl::_ax(tpl::_export('','tpl','admin','delrec_elm'),array('tpl_votes','admin_vote___tpl_admin_delrec_elm')).'</td></tr>
</table>

<table class="fixed thetable long tahoma ctext size11">
<col width="auto"><col width="60px"><col width="50px"><col width="35px">
<tr>
<th>������</th>
<th>�����</th>
<th></th>
<th></th>
</tr>
'.tpl::_a($par['list'],array('tpl_votes','admin_vote_list')).'
<tr >
<th colspan=4>
<textarea rows=1 cols=80 class="long tahoma fills size11" title="����� ������" name="new_descr"></textarea>
</th>
</tr><tr><th class="align_center" colspan=4 >
	<input type="submit" class="button savebutton" disabled="disabled" value="���������">
	<input type="submit" class="button " value="��������">
</th></tr>
<tr><td colspan=4  style="background:white;height:30px">
'.(isset($par['pages'])?$par['pages']:'').'
</td></tr></table>
</form>';
}

function admin_vote_list(&$par){		
		return '<tr class="'.tpl::_d($par['trclass'],'odd').'" id="vt_'.(isset($par['id'])?$par['id']:'').'">
<td class="long text_edit" id="descr_'.(isset($par['id'])?$par['id']:'').'">'.(isset($par['descr'])?$par['descr']:'').'</td>
<td>
'.(isset($par['page'])?$par['page']:'').'
</td>
<td style="width:40px;padding:0;" class="align_center" nowrap>
'.tpl::_ax(tpl::_export('','tpl','admin','order_elm_start'),array('tpl_votes','admin_vote_list___tpl_admin_order_elm_start')).'
<input type="text" class="order" style="width:15px;" name="order_'.(isset($par['id'])?$par['id']:'').'">
'.tpl::_ax(tpl::_export('','tpl','admin','order_elm_fin'),array('tpl_votes','admin_vote_list___tpl_admin_order_elm_fin')).'
</td><td style="width:20px">'.tpl::_ax(tpl::_export('','tpl','admin','delrec_elm'),array('tpl_votes','admin_vote_list___tpl_admin_delrec_elm')).'</td>
</tr>';
}}
?>