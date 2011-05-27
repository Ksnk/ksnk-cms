<!--  ////////////////// плагин Пользователи /////////////////////// -->

<!--begin:admin_user-->
<form action="" method="POST" name="admin_user">
<span style="color:red;font-size:16px;">{error}</span>
<input type='hidden' class="del" name="del">
<table class="thetable long tahoma size11" >
<tr>
<th class="bblue">свойство</th>
<th class="bblue">значение</th>
</tr>
<!--begin:list-->
<!--begin:common-->
<tr class="{trclass|odd}" id="us_{prop}">
<td ><b>{the_text}</b></td>
<td class="text_edit" id="{prop}" >{val}</td>
</tr>
<!--end:common-->
<!--begin:avatar-->
<tr class="{trclass|odd}" >
<td ><b>Фото (аватар)</b></td>

<!--<td style="" class="nopage uploader action_both" id="avatar"><div style="display:none;"><div> <input type="button" onclick="ReplaceImg(this)"> </div></div> <input name="avatar" type="text" value="{val}"  style="display:none;"> <img src="{val|img/1x1t.gif}" alt="" onload="checkImg(this,80,60)"></td>-->

<td ><div class="wide long uploader users_avatar">
<input class="long" type="" value="{val}" name="avatar"><br><br>
<img src="../{val|img/1x1t.gif}" onload="checkImg(this,80,60)" />
</div></td>
</tr>
<!--end:avatar-->
<!--begin:xrights-->
<tr class="{trclass|odd}" >
<td ><b>Права пользователя</b></td>
<td ><div class="wide long xrights">
<input class="long" type="text" name="us_right">
</div></td>
</tr>
<!--end:xrights-->
<!--begin:xuser-->
<tr class="{trclass|odd}" >
<td ><b>{the_text}</b></td>
<td ><div class="wide long xuser">
<input class="long" type="text" name="{prop}">
</div></td>
</tr>
<!--end:xuser-->
<!--begin:xmanager-->
<tr class="{trclass|odd}" >
<td ><b>{the_text}</b></td>
<td ><div class="wide long xmanager">
<input class="long" type="text" name="{prop}">
</div></td>
</tr>
<!--end:xmanager--><!--end:list-->
<tr><th class="align_center" colspan=3 >
	<input type="submit" class="button savebutton" disabled="disabled" value="сохранить">
	<input type="submit" class="button" name="delete_user" value="Удалить">
</th></tr>
</table>
</form>
<!--end:admin_user-->

<!--begin:user_list-->
<table class="menu ctext tahoma">
<col width="30%"><col width="30%"><col width="30%">
<tr><td><ul class="menu ctext tahoma">
<!--begin:list-->
<li style="cursor:pointer;" onclick="document.location='{::curl:user}user={id}';" class="{class}">{name}</li>
{break??</ul></td><td><ul class="menu ctext tahoma">}
<!--end:list-->
</ul></td>
<tr><td colspan=3 style="padding-top:30px;"
>
<a
style="display:block; padding:4px 15px" class="button"
href="{::curl:user}user=0">Добавить</a></td></tr>
</table>
<!--end:user_list-->
