<!--begin:config-->
<form action="" method="POST" name="config">
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

<!--begin:listfile-->
<b>незагружены файлы</b>{count}<div style="clear:both;">
<!--begin:list-->
<span style="float:left;">[<a title="found at: {where},{xid}" href="?do=find&id={xid}">{name}</a>]&nbsp;&nbsp; </span>
<!--end:list--></div>
<div style="clear:both;"></div>
<!--end:listfile-->
<!--begin:lostfile-->
<b>ненужные файлы</b>{count}<div>
<!--begin:list-->
<span style="float:left;">[<a href="#">{name}</a>]&nbsp;&nbsp;</span> 
<!--end:list--></div>
<div style="clear:both;"></div>
<!--end:lostfile-->
</form>
<!--end:config-->


