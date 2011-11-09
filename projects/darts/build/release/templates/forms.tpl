<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Шаблоны форм для разработки вручную</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<style>

html,body{margin:0;padding:0}
body{font: 76% arial,sans-serif}
p{margin:0 10px 10px}

input, textarea, .long  {border:0px; background:#f0f0f0 ;width:100%;}
input[type="button"], input[type="submit"] {width:auto;}
table{
	border:0px; /* border="0" */
	border-collapse:collapse; /* cellspacing="0" */
	padding:0px;
}
table td,table th{
	padding:1px; /* cellspadding="0" */
}
.green {background:#C0ffC0; }

form table {width:100%;}

</style>

</head>
<body>


<!--begin:games-->
<form action="" method="POST" name="games">

		<table style="width: 600px; table-layout: fixed;">
			<COLGROUP span="8" width="10%">
			<tr>
				<td>дата</td>
				<td>игра</td>
				<th colspan="2">{player1}</th>
				<th colspan="2">{player2}</th>
				<td>&nbsp</td>
			</tr>
		</table>
<!--row:begin-->
		<input
			name="ID_GAME[]" type="hidden">
		<table style="width: 600px; table-layout: fixed;">
			<COLGROUP span="8" width="15%">
			<tr>
				<td><input name="DATE[]" type="text"></td>
				<td><select name="RULE[]" class="long">
					<option value="0">
					<option value="1">201
					<option value="2">301
					<option value="3">501
					<option value="4">Американский крикет
					<option value="5">крикет
					<option value="6">Набор очков
					<option value="7">Полный круг
				</select></td>
				<td><input name="SCOREGAME1[]" type="text" ></td>
				<td><input name="TRACE1[]" type="text"></td>
				<td><input name="SCOREGAME[]" type="text"></td>
				<td><input name="TRACE[]" type="text" ></td>
				<td><input type="submit" name="xxx2[]" class="long"
					value="&raquo;"></td>
				<td><input type="submit" name="del2[]" class="long" value="X"></td>
			</tr>
		</table>
<!--row:end-->
		<a href="?do=playdarts&id=6"> Калькулятор </a>
<!--end:games-->
</body>