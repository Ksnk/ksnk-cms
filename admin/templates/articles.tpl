<!--begin:a_list-->
<form action="" method="POST" name="a_list">
<span style="color:red;font-size:16px;">{error}</span>
<input type='hidden' class="del" name="del">
<table class="thetable long tahoma ctext size11">
<tr>
<th>Дата</th><th>автор</th><th>заголовок</th><th></th>
<th>порядок</th>
<th></th>
</tr>
<!--begin:list-->
<tr id="ar_{id}">
	<td id="art_date_{id}"  class="text_edit">{date}</td>
	<td id="art_author_{id}" class="text_edit">{author}</td>
	<td id="art_title_{id}" class="html_edit">{title}</td>
	<td id="art_text_{id}" class="html_edit">{b_text}</td>
<td class="align_center" nowrap>
{::tpl:admin:order_elm_start}
<input type="text" class="order" style="width:15px;" name="order_{id}">
{::tpl:admin:order_elm_fin}
</td>
<td>{::tpl:admin:delrec_elm}</td>
</tr>
<!--end:list-->
<tr><td class="align_center" colspan=6 style="background:white;">
		<input type="submit" class="savebutton" value="сохранить">
	</td></tr>
<tr><td colspan=6>
<input type="submit"
			title="добавить новую статью" class="win_max"
			style="float:right;background-position: 0 -90px;" name="art_add" value="&nbsp;">
</td></tr>
<tr><td colspan=6  style="background:white;height:30px">
{pages}
</td></tr>
</table>
</form>
<!--end:a_list-->

<!--begin:a_list_script-->
<!--end:a_list_script-->
