<?php
class tpl_config extends tpl {

function _(&$par){		
		return tpl::_a($par['config'],array('tpl_config','config'));
}

function config(&$par){		
		return '<form action="" method="POST" name="config">
	<input type="submit" name="clear_empty_flesh" value="проверить ненужные элементы"><br>
	<input type="submit" name="clear_free_article" value="проверить ненужные статьи"><br>

	<input type="submit" name="clear_katalogue" value="почистить каталог"><br>

	<input type="submit" name="check_uploaded" value="проверить ссылки и файлы">
	<input type="submit" name="clear_unused" value="удалить ненужные файлы"><br>

	<input type="submit" name="clear_anchors" value="удалить все якоря"><br>
	<input type="submit" name="optimize" value="Оптимизировать таблицы"><br>
	<input type="submit" name="check_NS" value="Проверить целостность NS"><br>
	<input type="submit" name="heal_NS" value="Полечить NS"><br>
	<input type="text" name="delete_id" value="">
	<input type="submit" name="delete" value="удалить запчасти"><br>
<br>
	<label>Скопировать на сайт:<input type="text" name="host" ></label>
	<label>раздел(id):<input type="text" name="razdel" ></label>
	<input type="submit" name="copy" value="Скопировать"><br>

'.tpl::_a($par['listfile'],array('tpl_config','config_listfile')).'
'.tpl::_a($par['lostfile'],array('tpl_config','config_lostfile')).'
</form>';
}

function config_listfile(&$par){		
		return '<b>незагружены файлы</b>'.(isset($par['count'])?$par['count']:'').'<div style="clear:both;">
'.tpl::_a($par['list'],array('tpl_config','config_listfile_list')).'</div>
<div style="clear:both;"></div>';
}

function config_listfile_list(&$par){		
		return '<span style="float:left;">[<a title="found at: '.(isset($par['where'])?$par['where']:'').','.(isset($par['xid'])?$par['xid']:'').'" href="?do=find&id='.(isset($par['xid'])?$par['xid']:'').'">'.(isset($par['name'])?$par['name']:'').'</a>]&nbsp;&nbsp; </span>';
}

function config_lostfile(&$par){		
		return '<b>ненужные файлы</b>'.(isset($par['count'])?$par['count']:'').'<div>
'.tpl::_a($par['list'],array('tpl_config','config_lostfile_list')).'</div>
<div style="clear:both;"></div>';
}

function config_lostfile_list(&$par){		
		return '<span style="float:left;">[<a href="#">'.(isset($par['name'])?$par['name']:'').'</a>]&nbsp;&nbsp;</span>';
}}
?>