<html>
<head>
<style type=text/css>
td {
	FONT-FAMILY: verdana;
	font-size: 11px;
	color: #000000;
}

td.table30 {
	FONT-FAMILY: verdana;
	font-size: 12px;
	color: #000000;
	background-color: #ffffff;
	padding: 0px;
	border: 1px solid #000000
}

td.table31 {
	FONT-FAMILY: verdana;
	font-size: 12px;
	color: #000000;
	background-color: #ffffff;
	padding: 5 10 5 10;
	border: 1px solid #000000
}
</style>
</head>
<body width=650 align=left valign=top cellpadding=0 cellspacing=0
	topmargin=0 leftmargin=20 marginwidth=0 marginheight=0 border=0>
<table width=650 align=left valign=top cellpadding=0 cellspacing=0
	topmargin=0 leftmargin=0 marginwidth=0 marginheight=0 border=0>
	<tr>
		<td width=650 valign=top align=left style="padding-top: 20">
		<table width=650 align=left valign=top cellpadding=0 cellspacing=0
			topmargin=0 leftmargin=0 marginwidth=0 marginheight=0 border=0>
			<tr>
				<td width=650 valign=top align=left style="padding-top: 20">
				<table width=650 align=left valign=top cellpadding=0 cellspacing=0
					topmargin=0 leftmargin=0 marginwidth=0 marginheight=0 border=1>
					<tr>
						<td rowspan=2 colspan=2 valign=top>{spec_BANK}<br>
						<br>
						<br>
						���� ����������</td>
						<td valign=top>���</td>
						<td rowspan=2 valign=top>{spec_BIK}<br>
						<br>
						{spec_SORDERNUM}<br>
						<br>
						</td>
					</tr>
					<tr>
						<td valign=top>��. �</td>
					</tr>
					<tr>
						<td valign=top>��� {spec_INN}</td>
						<td valign=top>��� {spec_KPP}</td>
						<td rowspan=2 valign=top>��. �</td>
						<td rowspan=2 valign=top>{spec_ORDERNUM}</td>
					</tr>
					<tr>
						<td colspan=2 valign=top><b>{spec_ORGANISATION}</b><br>
						<br>
						����������</td>
					</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td width=650 valign=top align=left>
				<table width=650 align=left valign=top cellpadding=0 cellspacing=0
					topmargin=0 leftmargin=0 marginwidth=0 marginheight=0 border=0>
					<tr>
						<td style="padding-top: 15px"><b style="font-size: 14px;"color:#000000>����
						� {ordernum}i �� {Date}</b></td>
					</tr>
					<tr>
						<td style="padding-top: 15px"><img src=img/black.gif
							width=650 height=3></td>
					</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td width=650 valign=top align=left>
				<table width=650 align=left valign=top cellpadding=0 cellspacing=0
					topmargin=0 leftmargin=0 marginwidth=0 marginheight=0 border=0>
					<tr>
						<td style="padding-top: 15px;" color:#000000 valign=middle>���������:</td>
						<td style="padding-top: 15px;" padding-left:10px><b
							style="font-size: 11px;"color:#000000>��� {spec_INN} ���
						{spec_KPP} {spec_ORGANISATION} <br>
						{spec_ADDRESS} {spec_PHONE}</b></td>
					</tr>
					<tr>
						<td style="padding-top: 15px;" color:#000000 valign=middle>����������:</td>
						<td style="padding-top: 15px;" padding-left:10px><b
							style="font-size: 11px;"color:#000000>��� {cust_INN}
						��� {cust_KPP} {cust_ORGANISATION} {cust_ADDRESS}</b></td>
					</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td width=650 style="padding-top: 15px">
				<table width=650 align=left valign=top cellpadding=0 cellspacing=0
					topmargin=0 leftmargin=0 marginwidth=0 marginheight=0 border=0
					style="border-collapse: collapse">
					<tr>
						<td align=center class=table31><b style="font-size: 12px">�</td>
						<td align=center width=100% class=table31><b
							style="font-size: 12px">�����</td>
						<td align=center class=table31 nowrap><b
							style="font-size: 12px">���-��</td>
						<td align=center class=table31><b style="font-size: 12px">��.</td>
						<td align=center class=table31><b style="font-size: 12px">����</td>
						<td align=center class=table31><b style="font-size: 12px">�����</td>
					</tr>
					<!--begin:list-->
					<tr>
						<td class=table31>{numb}</td>
						<td class=table31>{descr}</td>
						<td class=table31>{cnumb}</td>
						<td class=table31>��.</td>
						<td class=table31>{ccost}</b></td>
						<td class=table31>{cccost}</td>
					</tr>
					<!--end:list-->
					<tr>
						<td colspan=5 align=right style="padding-top: 10px"><b
							style="color: #000000;" font-size:12px>�����:</b></td>
						<td align=left style="padding-top: 10px"><b
							style="color: #000000;" font-size:12px; padding-left:10px>{summ}</b></td>
					</tr>
					<tr>
						<td colspan=5 align=right style="padding-top: 5px"><b
							style="color: #000000;" font-size:12px>� ��� ����� ���:</b></td>
						<td align=left style="padding-top: 5px"><b
							style="color: #000000;" font-size:12px; padding-left:10px>{nds}</b></td>
					</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td align=left style="color: #000000"><br>
				<nobr>����� {inumb} �� ����� {summ} ���.</nobr> <nobr>( <i>{summprop}</i>  )</nobr></td>
			</tr>
			<tr>
				<td style="padding-top: 15px"><img src=img/black.gif
					width=650 height=3></td>
			</tr>
			<tr>
				<td width=100% valign=top align=left style="padding-top: 15px">
				<table width=100% align=left valign=top cellpadding=0 cellspacing=0
					topmargin=0 leftmargin=0 marginwidth=0 marginheight=0 border=0>
					<tr>
						<td style="color: #000000"><b style="font-size: 12px">������������</b>__________________({spec_DIRECTOR|______________})</td>
						<td style="color: #000000"><b style="font-size: 12px">���������</b>__________________({spec_BUHGALTER|______________})</td>
					</tr>
				</table>
				</td>
			</tr>
		</table>
		</td>
	</tr>
</table>
</body>
</html>
