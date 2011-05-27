///// панель редактирования голосования
<!--begin:admin_runningline-->
<form name='admin_runningline' action="" method="POST">
<span style="color:red;font-size:16px;">{error}</span>
<input type='hidden' class="del" name="del">
<table class="fixed thetable long tahoma ctext size11">
<col width="0*"><col width="50px"><col width="20px">
<tr>
<th colspan=1 class="bblue">Текст бегущей строки</th>
<th>поря<br>док</th><th></th>
</tr>
<!--begin:list-->
<tr class="{trclass|even}" id="rl_{id}">
<td class="long text_edit" id="descr_{id}">{descr}</td>
<td style="padding:5px 0;" class="align_center" nowrap>
{::tpl:admin:order_elm_start}
<input type="text" class="order" style="border:0;width:15px;" name="order_{id}">
{::tpl:admin:order_elm_fin}
</td><td  style="padding:5px 0;">{::tpl:admin:delrec_elm}</td>
</tr>
<!--end:list-->
<tr><th class="align_center" colspan=3 >
	<input type="submit" class="button savebutton" value="сохранить">
	<input type="submit" class="button savebutton" value="Добавить">
</th></tr>
<tr class="even">
<td colspan=3>
<textarea rows=1 cols=80 class="long tahoma fills size11" title="Новый вопрос" name="new_descr"></textarea>
</td>
</tr><tr><td colspan=3 style="background:white;height:30px">
{pages}
</td></tr></table>
</form>
<!--end:admin_runningline-->