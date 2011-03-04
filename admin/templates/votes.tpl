/////Список идущих голосований
<!--begin:votes_list-->
<table class="tahoma"><tr><td>
<ul class="menu ctext tahoma">
<!--begin:list-->
<li><a href="{url}">{name}</a></li>
<!--end:list-->
</ul>
<hr style="margin-top:20px;">
<a style="display:block; padding:4px 15px"
class='button link menu' href="{::curl:vote}vote=new">Добавить</a>
</td></tr></table>
<!--end:votes_list-->
///// панель редактирования голосования
<!--begin:admin_vote-->
<form name='admin_vote' action="" method="POST">
<span style="color:red;font-size:16px;">{error}</span>
<input type='hidden' class="del" name="del">
<table class="thetable long tahoma ctext size11">
<tr>
<th>Вопрос</th>
<th>1 отв.</th>
<th>Активен</th>
<th>Уже<br>глс.</th>
<th></th>
</tr><tr class="even" id='vt_{id}'>
<td class="long text_edit" id="descr_{id}">
{descr}
</td><td class="glass">
<input type="checkbox" disabled="disabled" title="Допускается ли ответ из нескольких вариантов?" name="radio_{id}">
</td><td class="glass">
<input type="checkbox" onchange="need_Save()" name="active_{id}" value="1">
</td><td>
{page}
</td>
<td style="width:20px">{::tpl:admin:delrec_elm}</td></tr>
</table>

<table class="fixed thetable long tahoma ctext size11">
<col width="auto"><col width="60px"><col width="50px"><col width="35px">
<tr>
<th>ответы</th>
<th>Всего</th>
<th></th>
<th></th>
</tr>
<!--begin:list-->
<tr class="{trclass|odd}" id="vt_{id}">
<td class="long text_edit" id="descr_{id}">{descr}</td>
<td>
{page}
</td>
<td style="width:40px;padding:0;" class="align_center" nowrap>
{::tpl:admin:order_elm_start}
<input type="text" class="order" style="width:15px;" name="order_{id}">
{::tpl:admin:order_elm_fin}
</td><td style="width:20px">{::tpl:admin:delrec_elm}</td>
</tr>
<!--end:list-->
<tr >
<th colspan=4>
<textarea rows=1 cols=80 class="long tahoma fills size11" title="Новый вопрос" name="new_descr"></textarea>
</th>
</tr><tr><th class="align_center" colspan=4 >
	<input type="submit" class="button savebutton" disabled="disabled" value="сохранить">
	<input type="submit" class="button " value="добавить">
</th></tr>
<tr><td colspan=4  style="background:white;height:30px">
{pages}
</td></tr></table>
</form>
<!--end:admin_vote-->