<!--begin:qathanks-->
<div style="padding-top:60px; padding-right:0; padding-bottom:0; padding-left:60px;" class="tahoma ctext link">
<p>�� ������� ����������������. ������������� ����������� ����� � ������� 24 �����. 
</p><p>������� �� <a href="{url}">������</a> ��� ��������.
</p>
<p class="red size11">{result}</p>
</div>
<!--end:qathanks-->
<!--begin:onlinereg-->
<form action="" enctype="multipart/form-data" method="POST" name="onlinereg">
<style>
.table td.pad10 {
 padding-top:10px;
 padding-bottom:10px;
}
</style>
<div class="align_center" style="padding-top:60px;">
<table class="table tahoma ctext" style="width:80%;height:1px;" height="613">
<tr>
<td colspan=2>
 <p class="red" style="font-size:16px;">{error}</p></td>
</tr>
<td colspan=2 style="padding:10px;">
 <p style="font-size:14px;"><span class="red">*</span> - ����, ������������ ��� ����������</p></td>
</tr>
<tr class="even borderdn">
 <td class="pad10 text">����� <span class="red">*</span></td><td  class="pad10 text"><input class="input" style="width:310px;" type="text" name="newlogin"></td>
</tr>
<tr class="borderdn">
 <td class="pad10 text">�����</td><td  class="pad10 text"><input class="input" style="width:310px;" type="password" name="newpassword"></td>
</tr>
<tr class="even borderdn">
 <td class="pad10 text">������������� ������</td><td  class="pad10 text"><input class="input" style="width:310px;" type="password" name="newpassword2"></td>
</tr>
<tr class="borderdn">
 <td class="pad10 text">������� <span class="red">*</span></td><td  class="pad10 text"><input class="input" style="width:310px;" type="text" name="surname"></td>
</tr>
<tr class="even borderdn">
 <td class="pad10 text">��� <span class="red">*</span></td><td  class="pad10 text"><input class="input" style="width:310px;" type="text" name="first_name"></td>
</tr>
<tr class="borderdn">
 <td class="pad10 text">�������� <span class="red">*</span></td><td  class="pad10 text"><input class="input" style="width:310px;" type="text" name="patronymic"></td>
</tr>
<tr class="even borderdn">
 <td class="pad10 text">���� (������)</td><td  class="pad10 text"><input class="input" style="width:310px;" type="file" name="avatar"></td>
</tr>
<tr class="borderdn">
 <td class="pad10 text">���� �������� <span class="red">*</span></td><td  class="pad10 text"><input class="input" style="width:310px;" type="text" name="birthday"></td>
</tr>
<tr class="even borderdn">
 <td class="pad10 text">����� ���������� <span class="red">*</span></td><td  class="pad10 text"><input class="input" style="width:310px;" type="text" name="address"></td>
</tr>
<tr class="borderdn">
 <td class="pad10 text"><b>����� ������</b></td><td  class="pad10 text">&nbsp;</td>
</tr>
<tr class="even borderdn">
<td class="pad10 text">���������� <span class="red">*</span></td><td class="pad10 text"><input  name="cust_ORGANISATION" class="long input" type="text"></td>
</tr>
<tr class="borderdn">
<td class="pad10 text">�����</td><td class="pad10 text"><textarea name="cust_ADDRESS" style="height:60px;" rows=5 cols=40 class="long input"></textarea></td>
</tr>
<tr class="even borderdn">
<td class="pad10 text">���������� ����������</td><td class="pad10 text"><textarea name="cust_INFO" style="height:60px;" rows=5 cols=40 class="long input"></textarea></td>
</tr>
<tr class="borderdn">
<td class="pad10 text">���������</td><td class="pad10 text"><input  name="cust_POSITION" class="long input" type="text"></td>
</tr>
<tr class="even borderdn">
<td class="pad10 text"><b>��������</b></td><td class="pad10 text">&nbsp;</td>
</tr>
<tr class="borderdn">
<td class="pad10 text">����� (e-mail) <span class="red">*</span></td><td class="pad10 text"><input  name="cust_EMAIL" class="long input" type="text"></td>
</tr>
<tr class="even borderdn">
<td class="pad10 text">�������</td><td class="pad10 text"><input name="cust_PHONE" class="long input" type="text"></td>
</tr>
<tr class="borderdn">
<td class="pad10 text">�������� �����</td><td class="pad10 text"><input  name="cust_POSTADDR" class="long input" type="text"></td>
</tr>
<tr class="even">
<td class="pad10 text">������� �����, ������������ �� ��������:<br></td><td class="text"><img src="captcha.php" style="float:right;" alt="" />
<input  class="input" type="pad10 text" name="captcha" /></td>
</tr>
<tr style="border: 1px solid #dddddd; border-width:1px 0;">
<td class="text pad10"></td><td class="pad10 text align_left"><div style="width:100px;" class="button"><input type="submit" value="���������"></div></td>
</tr>
</table>
</div>
</form>
<!--end:onlinereg-->

