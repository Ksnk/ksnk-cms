<?php
class tpl_printbnal extends tpl {

function _(&$par){		
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
						Банк получателя</td>
						<td valign=top>БИК</td>
						<td rowspan=2 valign=top>'.(isset($par['spec_BIK'])?$par['spec_BIK']:'').'<br>
						<br>
						'.(isset($par['spec_SORDERNUM'])?$par['spec_SORDERNUM']:'').'<br>
						<br>
						</td>
					</tr>
					<tr>
						<td valign=top>Сч. №</td>
					</tr>
					<tr>
						<td valign=top>ИНН '.(isset($par['spec_INN'])?$par['spec_INN']:'').'</td>
						<td valign=top>КПП '.(isset($par['spec_KPP'])?$par['spec_KPP']:'').'</td>
						<td rowspan=2 valign=top>Сч. №</td>
						<td rowspan=2 valign=top>'.(isset($par['spec_ORDERNUM'])?$par['spec_ORDERNUM']:'').'</td>
					</tr>
					<tr>
						<td colspan=2 valign=top><b>'.(isset($par['spec_ORGANISATION'])?$par['spec_ORGANISATION']:'').'</b><br>
						<br>
						Получатель</td>
					</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td width=650 valign=top align=left>
				<table width=650 align=left valign=top cellpadding=0 cellspacing=0
					topmargin=0 leftmargin=0 marginwidth=0 marginheight=0 border=0>
					<tr>
						<td style="padding-top: 15px"><b style="font-size: 14px;"color:#000000>Счет
						№ '.(isset($par['ordernum'])?$par['ordernum']:'').'i от '.(isset($par['Date'])?$par['Date']:'').'</b></td>
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
						<td style="padding-top: 15px;" color:#000000 valign=middle>Поставщик:</td>
						<td style="padding-top: 15px;" padding-left:10px><b
							style="font-size: 11px;"color:#000000>ИНН '.(isset($par['spec_INN'])?$par['spec_INN']:'').' КПП
						'.(isset($par['spec_KPP'])?$par['spec_KPP']:'').' '.(isset($par['spec_ORGANISATION'])?$par['spec_ORGANISATION']:'').' <br>
						'.(isset($par['spec_ADDRESS'])?$par['spec_ADDRESS']:'').' '.(isset($par['spec_PHONE'])?$par['spec_PHONE']:'').'</b></td>
					</tr>
					<tr>
						<td style="padding-top: 15px;" color:#000000 valign=middle>Покупатель:</td>
						<td style="padding-top: 15px;" padding-left:10px><b
							style="font-size: 11px;"color:#000000>ИНН '.(isset($par['cust_INN'])?$par['cust_INN']:'').'
						КПП '.(isset($par['cust_KPP'])?$par['cust_KPP']:'').' '.(isset($par['cust_ORGANISATION'])?$par['cust_ORGANISATION']:'').' '.(isset($par['cust_ADDRESS'])?$par['cust_ADDRESS']:'').'</b></td>
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
						<td align=center class=table31><b style="font-size: 12px">№</td>
						<td align=center width=100% class=table31><b
							style="font-size: 12px">Товар</td>
						<td align=center class=table31 nowrap><b
							style="font-size: 12px">Кол-во</td>
						<td align=center class=table31><b style="font-size: 12px">Ед.</td>
						<td align=center class=table31><b style="font-size: 12px">Цена</td>
						<td align=center class=table31><b style="font-size: 12px">Сумма</td>
					</tr>
					'.tpl::_a($par['llist'],array('tpl_printbnal','llist')).'
					<tr>
						<td colspan=5 align=right style="padding-top: 10px"><b
							style="color: #000000;" font-size:12px>Итого:</b></td>
						<td align=left style="padding-top: 10px"><b
							style="color: #000000;" font-size:12px; padding-left:10px>'.(isset($par['summ'])?$par['summ']:'').'</b></td>
					</tr>
					<tr>
						<td colspan=5 align=right style="padding-top: 5px"><b
							style="color: #000000;" font-size:12px>В том числе НДС:</b></td>
						<td align=left style="padding-top: 5px"><b
							style="color: #000000;" font-size:12px; padding-left:10px>'.(isset($par['nds'])?$par['nds']:'').'</b></td>
					</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td align=left style="color: #000000"><br>
				<nobr>Всего '.(isset($par['inumb'])?$par['inumb']:'').' на сумму '.(isset($par['summ'])?$par['summ']:'').' руб.</nobr> <nobr>( <i>'.(isset($par['summprop'])?$par['summprop']:'').'</i>  )</nobr></td>
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
						<td style="color: #000000"><b style="font-size: 12px">Руководитель</b>__________________('.tpl::_d($par['spec_DIRECTOR'],'______________').')</td>
						<td style="color: #000000"><b style="font-size: 12px">Бухгалтер</b>__________________('.tpl::_d($par['spec_BUHGALTER'],'______________').')</td>
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

function llist(&$par){		
		return '<tr>
						<td class=table31>'.(isset($par['numb'])?$par['numb']:'').'</td>
						<td class=table31>'.(isset($par['descr'])?$par['descr']:'').'</td>
						<td class=table31>'.(isset($par['cnumb'])?$par['cnumb']:'').'</td>
						<td class=table31>шт.</td>
						<td class=table31>'.(isset($par['ccost'])?$par['ccost']:'').'</b></td>
						<td class=table31>'.(isset($par['cccost'])?$par['cccost']:'').'</td>
					</tr>';
}}
?>