<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Шаблоны форм для разработки вручную</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8">

</head>
<body>

<!--begin:login-->
<form action="" method="POST" name="login">
<div class="align_center">
<div class="red">{error}</div>
<table class="thetable tahoma blue size11" style="table-layout:fixed;"><col width="70pt"><col width="160pt"><col width="30pt">
<tr>
	<th  align='right'>Имя:</th>
	<td colspan="2"><input name="login_name" type="text" value="" class="long"></td>
</tr><tr>
	<th  align='right'>пароль:</th>
	<td colspan="2"><input name="login_pass" type="password" value="" class="long"></td>
</tr><tr>
	<th  align='right'>{cansave??сохр:}</th>
	<td class="glass">{cansave??<input type="checkbox" value="1" name="login_save">}</td>
	<td class="align_right">
	<input name="form" type="hidden" value="Auth:loginform">
	<input type="submit" value="&raquo;"></td>
</tr></table></div>
</form>
<!--end:login-->

<!--begin:menuedit-->
<form action="" method="POST" name="menuedit">
<div style="height:1px;width: 740px;"></div>
<div style="width:100%;">
<div class="red">{error}</div>
<div style="position: relative;">
<div class="long align_center">
<table class="compact tahoma blue size16">
	<tr>
		<th class="red"><u>{name}</u></th>
	</tr>
		<tr>
		<th style="height:10px;"></th></tr>
</table>
</div>

<!--begin:has_content-->
<div id="fragment-2" style="padding: 0;" class="tabs-container">
<table class="long blue thetable tahoma size11">
	{data}
	<tr><th class="align_center" >
		<input type="submit" disabled="disabled" class="savebutton button" value="сохранить">
	</th></tr>
</table>
</div>
<!--end:has_content--></div>
</div>
<input type="hidden" class="del" name="del"></form>
<!--end:menuedit-->

</body>