<?php
class tpl_print extends tpl {

function airis(&$par){		
		return (isset($par['Date'])?$par['Date']:'').';     (����)
'.(isset($par['Time'])?$par['Time']:'').';        (�����)
'.(isset($par['ordernum'])?$par['ordernum']:'').'i;          (����� ������) 
'.(isset($par['user'])?$par['user']:'').';        (��� ������������)
'.(isset($par['cust_PHONE'])?$par['cust_PHONE']:'').';    (�������)
'.(isset($par['cust_ADDRESS'])?$par['cust_ADDRESS']:'').';  (��.�����)
'.(isset($par['cust_EMAIL'])?$par['cust_EMAIL']:'').'; (E-mail)
'.(isset($par['cust_FIO'])?$par['cust_FIO']:'').';     (���������� ����)
'.(isset($par['spec_INN'])?$par['spec_INN']:'').';     (���)
'.(isset($par['spec_KPP'])?$par['spec_KPP']:'').';   (���)
'.(isset($par['cust_OGRN'])?$par['cust_OGRN']:'').';        (����)
'.(isset($par['cust_DIRECTOR'])?$par['cust_DIRECTOR']:'').';(��� ���-���������)
'.(isset($par['spec_ORGANISATION'])?$par['spec_ORGANISATION']:'').';        (�������� �����������)
'.(isset($par['spec_ORDERNUM'])?$par['spec_ORDERNUM']:'').';    (� �����)
'.(isset($par['spec_BANK'])?$par['spec_BANK']:'').';  (�������� �����)
'.(isset($par['spec_BIK'])?$par['spec_BIK']:'').';    (��� ����)
'.(isset($par['cust_BANK_INN'])?$par['cust_BANK_INN']:'').';    (��� �����)
'.(isset($par['cust_BANK_KPP'])?$par['cust_BANK_KPP']:'').';    (��� �����)
'.(isset($par['spec_SORDERNUM'])?$par['spec_SORDERNUM']:'').';    (� ������������������ �����)
������� ������;������������;���-��;����
'.tpl::_a($par['list'],array('tpl_print','airis_list'));
}

function airis_list(&$par){		
		return (isset($par['articul'])?$par['articul']:'').';'.(isset($par['descr'])?$par['descr']:'').';'.(isset($par['cnumb'])?$par['cnumb']:'').';'.(isset($par['ccost'])?$par['ccost']:'');
}

function bnal(&$par){		
		return '<html>
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
						<td rowspan=2 colspan=2 valign=top>'.(isset($par['spec_BANK'])?$par['spec_BANK']:'').'<br>
						<br>
						<br>
						���� ����������</td>
						<td valign=top>���</td>
						<td rowspan=2 valign=top>'.(isset($par['spec_BIK'])?$par['spec_BIK']:'').'<br>
						<br>
						'.(isset($par['spec_SORDERNUM'])?$par['spec_SORDERNUM']:'').'<br>
						<br>
						</td>
					</tr>
					<tr>
						<td valign=top>��. �</td>
					</tr>
					<tr>
						<td valign=top>��� '.(isset($par['spec_INN'])?$par['spec_INN']:'').'</td>
						<td valign=top>��� '.(isset($par['spec_KPP'])?$par['spec_KPP']:'').'</td>
						<td rowspan=2 valign=top>��. �</td>
						<td rowspan=2 valign=top>'.(isset($par['spec_ORDERNUM'])?$par['spec_ORDERNUM']:'').'</td>
					</tr>
					<tr>
						<td colspan=2 valign=top><b>'.(isset($par['spec_ORGANISATION'])?$par['spec_ORGANISATION']:'').'</b><br>
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
						� '.(isset($par['ordernum'])?$par['ordernum']:'').'i �� '.(isset($par['Date'])?$par['Date']:'').'</b></td>
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
							style="font-size: 11px;"color:#000000>��� '.(isset($par['spec_INN'])?$par['spec_INN']:'').' ���
						'.(isset($par['spec_KPP'])?$par['spec_KPP']:'').' '.(isset($par['spec_ORGANISATION'])?$par['spec_ORGANISATION']:'').' <br>
						'.(isset($par['spec_ADDRESS'])?$par['spec_ADDRESS']:'').' '.(isset($par['spec_PHONE'])?$par['spec_PHONE']:'').'</b></td>
					</tr>
					<tr>
						<td style="padding-top: 15px;" color:#000000 valign=middle>����������:</td>
						<td style="padding-top: 15px;" padding-left:10px><b
							style="font-size: 11px;"color:#000000>��� '.(isset($par['cust_INN'])?$par['cust_INN']:'').'
						��� '.(isset($par['cust_KPP'])?$par['cust_KPP']:'').' '.(isset($par['cust_ORGANISATION'])?$par['cust_ORGANISATION']:'').' '.(isset($par['cust_ADDRESS'])?$par['cust_ADDRESS']:'').'</b></td>
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
					'.tpl::_a($par['list'],array('tpl_print','bnal_list')).'
					<tr>
						<td colspan=5 align=right style="padding-top: 10px"><b
							style="color: #000000;" font-size:12px>�����:</b></td>
						<td align=left style="padding-top: 10px"><b
							style="color: #000000;" font-size:12px; padding-left:10px>'.(isset($par['summ'])?$par['summ']:'').'</b></td>
					</tr>
					<tr>
						<td colspan=5 align=right style="padding-top: 5px"><b
							style="color: #000000;" font-size:12px>� ��� ����� ���:</b></td>
						<td align=left style="padding-top: 5px"><b
							style="color: #000000;" font-size:12px; padding-left:10px>'.(isset($par['nds'])?$par['nds']:'').'</b></td>
					</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td align=left style="color: #000000"><br>
				<nobr>����� '.(isset($par['inumb'])?$par['inumb']:'').' �� ����� '.(isset($par['summ'])?$par['summ']:'').' ���.</nobr> <nobr>( <i>'.(isset($par['summprop'])?$par['summprop']:'').'</i>  )</nobr></td>
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
						<td style="color: #000000"><b style="font-size: 12px">������������</b>__________________('.tpl::_d($par['spec_DIRECTOR'],'______________').')</td>
						<td style="color: #000000"><b style="font-size: 12px">���������</b>__________________('.tpl::_d($par['spec_BUHGALTER'],'______________').')</td>
					</tr>
				</table>
				</td>
			</tr>
		</table>
		</td>
	</tr>
</table>
</body>
</html>';
}

function bnal_list(&$par){		
		return '<tr>
						<td class=table31>'.(isset($par['numb'])?$par['numb']:'').'</td>
						<td class=table31>'.(isset($par['descr'])?$par['descr']:'').'</td>
						<td class=table31>'.(isset($par['cnumb'])?$par['cnumb']:'').'</td>
						<td class=table31>��.</td>
						<td class=table31>'.(isset($par['ccost'])?$par['ccost']:'').'</b></td>
						<td class=table31>'.(isset($par['cccost'])?$par['cccost']:'').'</td>
					</tr>';
}

function kvit(&$par){		
		return '<html> <head>
<style type=text/css>
	td{ 	FONT-FAMILY: verdana;font-size:11px;color:#000000; }
	td.table30{ 	FONT-FAMILY: verdana;font-size:12px;color:#000000; 	background-color:#ffffff; 	padding:0px; 	border:1px solid #000000 }
	td.table31{ 	FONT-FAMILY: verdana;font-size:12px;color:#000000; 	background-color:#ffffff; 	padding:5 10 5 10; 	border:1px solid #000000 }
	.black {background:black;}
</style> </head>
<body width=650 align=left valign=top cellpadding=0 cellspacing=0
	topmargin=20 leftmargin=20 marginwidth=0 marginheight=0 border=0>
<table width=650 align=left valign=top cellpadding=0 cellspacing=0
	topmargin=0 leftmargin=0 marginwidth=0 marginheight=0 border=0>
	<tr>
		<td width=620 valign=top align=left>
		<table width=620 align=left valign=top cellpadding=0 cellspacing=0
			topmargin=0 leftmargin=0 marginwidth=0 marginheight=0 border=1>
			<tr>
				<td width=618 valign=top align=left>
				<table width=618 align=left valign=top cellpadding=0 cellspacing=0
					topmargin=0 leftmargin=0 marginwidth=0 marginheight=0 border=0>
					<tr>
						<td width=30% height=100% valign=top align=left
							style="padding-left: 40">
						<table width=100% height=100% align=left valign=top cellpadding=0
							cellspacing=0 topmargin=0 leftmargin=0 marginwidth=0
							marginheight=0 border=0>
							<tr>
								<td style="color: #000000;" font-size:10px><br>
								<b>���������</b></td>
							</tr>
							<tr>
								<td height=100% valign=bottom style="color: #000000;"font-size:10px; >������<br>
								<br>
								</td>
							</tr>
						</table>
						</td>
						<td height=100% class="black"></td>
						<td width=100% valign=top align=left style="padding-right: 20">
						<table width=100% align=left valign=top cellpadding=0
							cellspacing=0 topmargin=0 leftmargin=0 marginwidth=0
							marginheight=0 border=0>
							<tr>
								<td colspan=2 align=right style="color: #000000;"font-size:10px; ><br>
								����� � <b>��-4</b></td>
							</tr>
							<tr>
								<td colspan=2 align=center style="color: #000000;"
									font-size:10px; ; padding-top:6px><b>
								'.tpl::_d($par['spec_ORGANISATION'],'��� ����������������� ����� �����').'</b></td>
							</tr>
							<tr>
								<td colspan=2 align=center  style="color: #000000;"
									font-size:10px; ; padding-top:3px><img src=img/black.gif
									width=95% height=1></td>
							</tr>
							<tr>
								<td colspan=2 align=center style="color: #000000;"
									font-size:10px; ; padding-top:0px; font-size:10px>(������������
								���������� �������)</td>
							</tr>
							<tr>
								<td align=center style="color: #000000;"
									font-size:10px; ; padding-top:6px>'.(isset($par['spec_INN'])?$par['spec_INN']:'').'</td>
								<td align=center style="color: #000000;"
									font-size:10px; ; padding-top:6px>'.(isset($par['spec_KPP'])?$par['spec_KPP']:'').'</td>
							</tr>
							<tr>
								<td width=50% align=center style="color: #000000;"
									font-size:10px; ; padding-top:3px><img src=img/black.gif
									width=95% height=1></td>
								<td width=50% align=center style="color: #000000;"
									font-size:10px; ; padding-top:3px><img src=img/black.gif
									width=95% height=1></td>
							</tr>
							<tr>
								<td align=center style="color: #000000;"
									font-size:10px; ; padding-top:0px; font-size:10px>(���
								���������� �������)</td>
								<td align=center style="color: #000000;"
									font-size:10px; ; padding-top:0px; font-size:10px>(���
								���������� �������)</td>
							</tr>
							<tr>
								<td colspan=2 align=center style="color: #000000;"
									font-size:10px; ; padding-top:6px>�'.(isset($par['spec_ORDERNUM'])?$par['spec_ORDERNUM']:'').'</td>
							</tr>
							<tr>
								<td colspan=2 align=center style="color: #000000;"
									font-size:10px; ; padding-top:3px><img src=img/black.gif
									width=95% height=1></td>
							</tr>
							<tr>
								<td colspan=2 align=center style="color: #000000;"
									font-size:10px; ; padding-top:0px; font-size:10px>(�����
								����� ���������� �������)</td>
							</tr>
							<tr>
								<td colspan=2 align=center style="color: #000000;"
									font-size:10px; ; padding-top:6px>'.(isset($par['spec_BANK'])?$par['spec_BANK']:'').'</td>
							</tr>
							<tr>
								<td colspan=2 align=center style="color: #000000;"
									font-size:10px; ; padding-top:3px><img src=img/black.gif
									width=95% height=1></td>
							</tr>
							<tr>
								<td colspan=2 align=center style="color: #000000;"
									font-size:10px; ; padding-top:0px; font-size:10px>(������������
								����� � ���������� ���������)</td>
							</tr>
							<tr>
								<td colspan=2 align=center style="color: #000000;"
									font-size:10px; ; padding-top:6px>�/� '.(isset($par['spec_SORDERNUM'])?$par['spec_SORDERNUM']:'').'</td>
							</tr>
							<tr>
								<td colspan=2 align=center style="color: #000000;"
									font-size:10px; ; padding-top:3px><img src=img/black.gif
									width=95% height=1></td>
							</tr>
							<tr>
								<td colspan=2 align=center style="color: #000000;"
									font-size:10px; ; padding-top:6px>&nbsp;</td>
							</tr>
							<tr>
								<td colspan=2 align=center style="color: #000000;"
									font-size:10px; ; padding-top:3px><img src=img/black.gif
									width=95% height=1></td>
							</tr>
							<tr>
								<td colspan=2 align=center style="color: #000000;"
									font-size:10px; ; padding-top:6px>��� '.(isset($par['spec_BIK'])?$par['spec_BIK']:'').'</td>
							</tr>
							<tr>
								<td colspan=2 align=center style="color: #000000;"
									font-size:10px; ; padding-top:3px><img src=img/black.gif
									width=95% height=1></td>
							</tr>
							<tr>
								<td colspan=2 align=center style="color: #000000;"
									font-size:10px; ; padding-top:6px; padding-left:10px>������
								������ � '.(isset($par['ordernum'])?$par['ordernum']:'').'i<br>
								</td>
							</tr>
							<tr>
								<td colspan=2 align=center style="color: #000000;"
									font-size:10px; ; padding-top:3px><img src=img/black.gif
									width=95% height=1></td>
							</tr>
							<tr>
								<td colspan=2 align=center style="color: #000000;"
									font-size:10px; ; padding-top:0px; font-size:10px>(������������
								�������)</td>
							</tr>
							<tr>
								<td align=left style="color: #000000;"
									font-size:10px; ; padding-top:6px; padding-left:10px><br>
								���� '.(isset($par['Date'])?$par['Date']:'').'</td>
								<td align=right style="color: #000000;"
									font-size:10px; ; padding-top:6px; padding-right:15px><br>
								����� �������: <b>'.(isset($par['summ'])?$par['summ']:'').' ���.</b></td>
							</tr>
							<tr>
								<td colspan=2 align=center style="color: #000000;"
									font-size:10px; padding:6 100 20 10 nowrap>����������
								(�������)&nbsp<img src=img/black.gif width=200 height=1></td>
							</tr>
							<tr>
								<td colspan=2 align=center><br>
								</td>
							</tr>
						</table>
						</td>
					</tr>
					<tr>
						<td colspan=3><img src=img/black.gif width=600 height=2></td>
					</tr>
					<tr>
						<td width=30% height=100% valign=top align=left
							style="padding-left: 40">
						<table width=100% height=100% align=left valign=top cellpadding=0
							cellspacing=0 topmargin=0 leftmargin=0 marginwidth=0
							marginheight=0 border=0>
							<tr>
								<td style="color: #000000;"font-size:10px; ><br>
								<b>���������</b></td>
							</tr>
							<tr>
								<td height=100% valign=bottom style="color: #000000;"font-size:10px; >������<br>
								<br>
								</td>
							</tr>
						</table>
						</td>
						<td style="height:100%;" class="black"><img width="1px" src="img/black.gif"></td>
						<td width=100% valign=top align=left style="padding-right: 20">
						<table width=100% align=left valign=top cellpadding=0
							cellspacing=0 topmargin=0 leftmargin=0 marginwidth=0
							marginheight=0 border=0>
							<tr>
								<td colspan=2 align=right style="color: #000000;"font-size:10px; ><br>
								����� � <b>��-4</b></td>
							</tr>
							<tr>
								<td colspan=2 align=center style="color: #000000;"
									font-size:10px; ; padding-top:6px>
									<b>'.tpl::_d($par['spec_ORGANISATION'],'��� ����������������� ����� �����').'</b></td>
							</tr>
							<tr>
								<td colspan=2 align=center style="color: #000000;"
									font-size:10px; ; padding-top:3px><img src=img/black.gif
									width=95% height=1></td>
							</tr>
							<tr>
								<td colspan=2 align=center style="color: #000000;"
									font-size:10px; ; padding-top:0px; font-size:10px>(������������
								���������� �������)</td>
							</tr>
							<tr>
								<td align=center style="color: #000000;"
									font-size:10px; ; padding-top:6px>'.(isset($par['spec_INN'])?$par['spec_INN']:'').'</td>
								<td align=center style="color: #000000;"
									font-size:10px; ; padding-top:6px>'.(isset($par['spec_KPP'])?$par['spec_KPP']:'').'</td>
							</tr>
							<tr>
								<td width=50% align=center style="color: #000000;"
									font-size:10px; ; padding-top:3px><img src=img/black.gif
									width=95% height=1></td>
								<td width=50% align=center style="color: #000000;"
									font-size:10px; ; padding-top:3px><img src=img/black.gif
									width=95% height=1></td>
							</tr>
							<tr>
								<td align=center style="color: #000000;"
									font-size:10px; ; padding-top:0px; font-size:10px>(���
								���������� �������)</td>
								<td align=center style="color: #000000;"
									font-size:10px; ; padding-top:0px; font-size:10px>(���
								���������� �������)</td>
							</tr>
							<tr>
								<td colspan=2 align=center style="color: #000000;"
									font-size:10px; ; padding-top:6px>�'.(isset($par['spec_ORDERNUM'])?$par['spec_ORDERNUM']:'').'</td>
							</tr>
							<tr>
								<td colspan=2 align=center style="color: #000000;"
									font-size:10px; ; padding-top:3px><img src=img/black.gif
									width=95% height=1></td>
							</tr>
							<tr>
								<td colspan=2 align=center style="color: #000000;"
									font-size:10px; ; padding-top:0px; font-size:10px>(�����
								����� ���������� �������)</td>
							</tr>
							<tr>
								<td colspan=2 align=center style="color: #000000;"
									font-size:10px; ; padding-top:6px>'.(isset($par['spec_BANK'])?$par['spec_BANK']:'').'</td>
							</tr>
							<tr>
								<td colspan=2 align=center style="color: #000000;"
									font-size:10px; ; padding-top:3px><img src=img/black.gif
									width=95% height=1></td>
							</tr>
							<tr>
								<td colspan=2 align=center style="color: #000000;"
									font-size:10px; ; padding-top:0px; font-size:10px>(������������
								����� � ���������� ���������)</td>
							</tr>
							<tr>
								<td colspan=2 align=center style="color: #000000;"
									font-size:10px; ; padding-top:6px>�/� '.(isset($par['spec_SORDERNUM'])?$par['spec_SORDERNUM']:'').'</td>
							</tr>
							<tr>
								<td colspan=2 align=center style="color: #000000;"
									font-size:10px; ; padding-top:3px><img src=img/black.gif
									width=95% height=1></td>
							</tr>
							<tr>
								<td colspan=2 align=center style="color: #000000;"
									font-size:10px; ; padding-top:6px>&nbsp;</td>
							</tr>
							<tr>
								<td colspan=2 align=center style="color: #000000;"
									font-size:10px; ; padding-top:3px><img src=img/black.gif
									width=95% height=1></td>
							</tr>
							<tr>
								<td colspan=2 align=center style="color: #000000;"
									font-size:10px; ; padding-top:6px>��� '.(isset($par['spec_BIK'])?$par['spec_BIK']:'').'</td>
							</tr>
							<tr>
								<td colspan=2 align=center style="color: #000000;"
									font-size:10px; ; padding-top:3px><img src=img/black.gif
									width=95% height=1></td>
							</tr>
							<tr>
								<td colspan=2 align=center style="color: #000000;"
									font-size:10px; ; padding-top:6px; padding-left:10px>������
								������ � '.(isset($par['ordernum'])?$par['ordernum']:'').'i<br>
								</td>
							</tr>
							<tr>
								<td colspan=2 align=center style="color: #000000;"
									font-size:10px; ; padding-top:3px><img src=img/black.gif
									width=95% height=1></td>
							</tr>
							<tr>
								<td colspan=2 align=center style="color: #000000;"
									font-size:10px; ; padding-top:0px; font-size:10px>(������������
								�������)</td>
							</tr>
							<tr>
								<td align=left style="color: #000000;"
									font-size:10px; ; padding-top:6px; padding-left:10px><br>
								���� '.(isset($par['Date'])?$par['Date']:'').'</td>
								<td align=right style="color: #000000;"
									font-size:10px; ; padding-top:6px; padding-right:15px><br>
								����� �������: <b>'.(isset($par['summ'])?$par['summ']:'').' ���.</b></td>
							</tr>
							<tr>
								<td colspan=2 align=center style="color: #000000;"
									font-size:10px; ;  padding:6 100 20 10 nowrap>����������
								(�������)&nbsp<img src=img/black.gif width=200 height=1></td>
							</tr>
							<tr>
								<td colspan=2 align=center><br>
								</td>
							</tr>
						</table>
						</td>
					</tr>
				</table>
				</td>
			</tr>
		</table>
		</td>
	</tr>
</table>
</body>';
}

function txt(&$par){		
		return '<html>

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



<!--<table border=1>

<!--begin:listpar

<tr><td>name'.(isset($par['cust_ORGANISATION'])?$par['cust_ORGANISATION']:'').'</td><td>val'.(isset($par['user'])?$par['user']:'').'</td></tr>

<!--end:listpar

</table>-->



<table width=650 align=left valign=top cellpadding=0 cellspacing=0

        topmargin=0 leftmargin=0 marginwidth=0 marginheight=0 border=0>

        <tr>

                <td width=650 valign=top align=left style="padding-top: 20">

                <table width=650 align=left valign=top cellpadding=0 cellspacing=0

                        topmargin=0 leftmargin=0 marginwidth=0 marginheight=0 border=0>

      

                        <tr>

                                <td width=650 valign=top align=left>

                                <table width=650 align=left valign=top cellpadding=0 cellspacing=0

                                        topmargin=0 leftmargin=0 marginwidth=0 marginheight=0 border=0>

                            <tr>

                                <td style="padding-top: 15px"><span style="font-size:14px;"><b>'.(isset($par['cust_ORGANISATION'])?$par['cust_ORGANISATION']:'').'</b></span><b style="font-size: 14px;"color:#000000><br>
����� � '.(isset($par['ordernum'])?$par['ordernum']:'').'i �� '.(isset($par['Date'])?$par['Date']:'').'</b></td>

                            </tr>

                                        <tr>

                                                <td style="padding-top: 15px"><img src=img/black.gif

                                                        width=650 height=3></td>

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

                                        '.tpl::_a($par['list'],array('tpl_print','txt_list')).'

                                        <tr>

                                                <td colspan=5 align=right style="padding-top: 10px"><b

                                                        style="color: #000000;" font-size:12px>�����:</b></td>

                                                <td align=left style="padding-top: 10px"><b

                                                        style="color: #000000;" font-size:12px; padding-left:10px>'.(isset($par['summ'])?$par['summ']:'').'</b></td>

                                        </tr>

                                        <tr>

                                                <td colspan=5 align=right style="padding-top: 5px"><b

                                                        style="color: #000000;" font-size:12px>� ��� ����� ���:</b></td>

                                                <td align=left style="padding-top: 5px"><b

                                                        style="color: #000000;" font-size:12px; padding-left:10px>'.(isset($par['nds'])?$par['nds']:'').'</b></td>

                                        </tr>

                                </table>

                                </td>

                        </tr>

                        <tr>

                                <td align=left style="color: #000000"><br>

                                <nobr>����� '.(isset($par['inumb'])?$par['inumb']:'').' �� ����� '.(isset($par['summ'])?$par['summ']:'').' ���.</nobr> <nobr>( <i>'.(isset($par['summprop'])?$par['summprop']:'').'</i>  )</nobr></td>

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

                                <td style="color: #000000">
                                    <p><b style="font-size: 12px">������������</b>__________________('.tpl::_d($par['cust_DIRECTOR'],'______________').')</p>
                                </td>

                                <td style="color: #000000">
                                    <p><b style="font-size: 12px">���������</b>__________________('.tpl::_d($par['cust_BUHGALTER'],'______________').')</p>
                                </td>

                            </tr>

                                </table>

                                </td>

                        </tr>

                </table>

                </td>

        </tr>

</table>

</body>

</html>';
}

function txt_list(&$par){		
		return '<tr>

                                                <td class=table31>'.(isset($par['articul'])?$par['articul']:'').'</td>

                                                <td class=table31>'.(isset($par['descr'])?$par['descr']:'').'</td>

                                                <td class=table31>'.(isset($par['cnumb'])?$par['cnumb']:'').'</td>

                                                <td class=table31>'.(isset($par['edism'])?$par['edism']:'').'</td>

                                                <td class=table31>'.(isset($par['ccost'])?$par['ccost']:'').'</b></td>

                                                <td class=table31>'.(isset($par['cccost'])?$par['cccost']:'').'</td>

                                        </tr>';
}}
?>