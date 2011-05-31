<?php
class tpl_print extends tpl {

function airis(&$par){		
		return (isset($par['Date'])?$par['Date']:'').';     (Дата)
'.(isset($par['Time'])?$par['Time']:'').';        (Время)
'.(isset($par['ordernum'])?$par['ordernum']:'').'i;          (Номер заказа) 
'.(isset($par['user'])?$par['user']:'').';        (ник пользователя)
'.(isset($par['cust_PHONE'])?$par['cust_PHONE']:'').';    (Телефон)
'.(isset($par['cust_ADDRESS'])?$par['cust_ADDRESS']:'').';  (Юр.адрес)
'.(isset($par['cust_EMAIL'])?$par['cust_EMAIL']:'').'; (E-mail)
'.(isset($par['cust_FIO'])?$par['cust_FIO']:'').';     (Контактное лицо)
'.(isset($par['spec_INN'])?$par['spec_INN']:'').';     (ИНН)
'.(isset($par['spec_KPP'])?$par['spec_KPP']:'').';   (КПП)
'.(isset($par['cust_OGRN'])?$par['cust_OGRN']:'').';        (ОГРН)
'.(isset($par['cust_DIRECTOR'])?$par['cust_DIRECTOR']:'').';(ФИО ген-директора)
'.(isset($par['spec_ORGANISATION'])?$par['spec_ORGANISATION']:'').';        (Название организации)
'.(isset($par['spec_ORDERNUM'])?$par['spec_ORDERNUM']:'').';    (№ счета)
'.(isset($par['spec_BANK'])?$par['spec_BANK']:'').';  (Название банка)
'.(isset($par['spec_BIK'])?$par['spec_BIK']:'').';    (БИК банк)
'.(isset($par['cust_BANK_INN'])?$par['cust_BANK_INN']:'').';    (ИНН банка)
'.(isset($par['cust_BANK_KPP'])?$par['cust_BANK_KPP']:'').';    (КПП банка)
'.(isset($par['spec_SORDERNUM'])?$par['spec_SORDERNUM']:'').';    (№ корреспондентского счета)
артикул товара;наименование;кол-во;Цена
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
					'.tpl::_a($par['list'],array('tpl_print','bnal_list')).'
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

function bnal_list(&$par){		
		return '<tr>
						<td class=table31>'.(isset($par['numb'])?$par['numb']:'').'</td>
						<td class=table31>'.(isset($par['descr'])?$par['descr']:'').'</td>
						<td class=table31>'.(isset($par['cnumb'])?$par['cnumb']:'').'</td>
						<td class=table31>шт.</td>
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
								<b>Извещение</b></td>
							</tr>
							<tr>
								<td height=100% valign=bottom style="color: #000000;"font-size:10px; >Кассир<br>
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
								Форма № <b>ПД-4</b></td>
							</tr>
							<tr>
								<td colspan=2 align=center style="color: #000000;"
									font-size:10px; ; padding-top:6px><b>
								'.tpl::_d($par['spec_ORGANISATION'],'ООО Экспериментальный завод трейд').'</b></td>
							</tr>
							<tr>
								<td colspan=2 align=center  style="color: #000000;"
									font-size:10px; ; padding-top:3px><img src=img/black.gif
									width=95% height=1></td>
							</tr>
							<tr>
								<td colspan=2 align=center style="color: #000000;"
									font-size:10px; ; padding-top:0px; font-size:10px>(наименование
								получателя платежа)</td>
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
									font-size:10px; ; padding-top:0px; font-size:10px>(ИНН
								получателя платежа)</td>
								<td align=center style="color: #000000;"
									font-size:10px; ; padding-top:0px; font-size:10px>(КПП
								получателя платежа)</td>
							</tr>
							<tr>
								<td colspan=2 align=center style="color: #000000;"
									font-size:10px; ; padding-top:6px>№'.(isset($par['spec_ORDERNUM'])?$par['spec_ORDERNUM']:'').'</td>
							</tr>
							<tr>
								<td colspan=2 align=center style="color: #000000;"
									font-size:10px; ; padding-top:3px><img src=img/black.gif
									width=95% height=1></td>
							</tr>
							<tr>
								<td colspan=2 align=center style="color: #000000;"
									font-size:10px; ; padding-top:0px; font-size:10px>(номер
								счета получателя платежа)</td>
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
									font-size:10px; ; padding-top:0px; font-size:10px>(наименование
								банка и банковские реквизиты)</td>
							</tr>
							<tr>
								<td colspan=2 align=center style="color: #000000;"
									font-size:10px; ; padding-top:6px>к/с '.(isset($par['spec_SORDERNUM'])?$par['spec_SORDERNUM']:'').'</td>
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
									font-size:10px; ; padding-top:6px>БИК '.(isset($par['spec_BIK'])?$par['spec_BIK']:'').'</td>
							</tr>
							<tr>
								<td colspan=2 align=center style="color: #000000;"
									font-size:10px; ; padding-top:3px><img src=img/black.gif
									width=95% height=1></td>
							</tr>
							<tr>
								<td colspan=2 align=center style="color: #000000;"
									font-size:10px; ; padding-top:6px; padding-left:10px>Оплата
								заказа № '.(isset($par['ordernum'])?$par['ordernum']:'').'i<br>
								</td>
							</tr>
							<tr>
								<td colspan=2 align=center style="color: #000000;"
									font-size:10px; ; padding-top:3px><img src=img/black.gif
									width=95% height=1></td>
							</tr>
							<tr>
								<td colspan=2 align=center style="color: #000000;"
									font-size:10px; ; padding-top:0px; font-size:10px>(наименование
								платежа)</td>
							</tr>
							<tr>
								<td align=left style="color: #000000;"
									font-size:10px; ; padding-top:6px; padding-left:10px><br>
								Дата '.(isset($par['Date'])?$par['Date']:'').'</td>
								<td align=right style="color: #000000;"
									font-size:10px; ; padding-top:6px; padding-right:15px><br>
								Сумма платежа: <b>'.(isset($par['summ'])?$par['summ']:'').' руб.</b></td>
							</tr>
							<tr>
								<td colspan=2 align=center style="color: #000000;"
									font-size:10px; padding:6 100 20 10 nowrap>Плательщик
								(подпись)&nbsp<img src=img/black.gif width=200 height=1></td>
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
								<b>Квитанция</b></td>
							</tr>
							<tr>
								<td height=100% valign=bottom style="color: #000000;"font-size:10px; >Кассир<br>
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
								Форма № <b>ПД-4</b></td>
							</tr>
							<tr>
								<td colspan=2 align=center style="color: #000000;"
									font-size:10px; ; padding-top:6px>
									<b>'.tpl::_d($par['spec_ORGANISATION'],'ООО Экспериментальный завод трейд').'</b></td>
							</tr>
							<tr>
								<td colspan=2 align=center style="color: #000000;"
									font-size:10px; ; padding-top:3px><img src=img/black.gif
									width=95% height=1></td>
							</tr>
							<tr>
								<td colspan=2 align=center style="color: #000000;"
									font-size:10px; ; padding-top:0px; font-size:10px>(наименование
								получателя платежа)</td>
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
									font-size:10px; ; padding-top:0px; font-size:10px>(ИНН
								получателя платежа)</td>
								<td align=center style="color: #000000;"
									font-size:10px; ; padding-top:0px; font-size:10px>(КПП
								получателя платежа)</td>
							</tr>
							<tr>
								<td colspan=2 align=center style="color: #000000;"
									font-size:10px; ; padding-top:6px>№'.(isset($par['spec_ORDERNUM'])?$par['spec_ORDERNUM']:'').'</td>
							</tr>
							<tr>
								<td colspan=2 align=center style="color: #000000;"
									font-size:10px; ; padding-top:3px><img src=img/black.gif
									width=95% height=1></td>
							</tr>
							<tr>
								<td colspan=2 align=center style="color: #000000;"
									font-size:10px; ; padding-top:0px; font-size:10px>(номер
								счета получателя платежа)</td>
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
									font-size:10px; ; padding-top:0px; font-size:10px>(наименование
								банка и банковские реквизиты)</td>
							</tr>
							<tr>
								<td colspan=2 align=center style="color: #000000;"
									font-size:10px; ; padding-top:6px>к/с '.(isset($par['spec_SORDERNUM'])?$par['spec_SORDERNUM']:'').'</td>
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
									font-size:10px; ; padding-top:6px>БИК '.(isset($par['spec_BIK'])?$par['spec_BIK']:'').'</td>
							</tr>
							<tr>
								<td colspan=2 align=center style="color: #000000;"
									font-size:10px; ; padding-top:3px><img src=img/black.gif
									width=95% height=1></td>
							</tr>
							<tr>
								<td colspan=2 align=center style="color: #000000;"
									font-size:10px; ; padding-top:6px; padding-left:10px>Оплата
								заказа № '.(isset($par['ordernum'])?$par['ordernum']:'').'i<br>
								</td>
							</tr>
							<tr>
								<td colspan=2 align=center style="color: #000000;"
									font-size:10px; ; padding-top:3px><img src=img/black.gif
									width=95% height=1></td>
							</tr>
							<tr>
								<td colspan=2 align=center style="color: #000000;"
									font-size:10px; ; padding-top:0px; font-size:10px>(наименование
								платежа)</td>
							</tr>
							<tr>
								<td align=left style="color: #000000;"
									font-size:10px; ; padding-top:6px; padding-left:10px><br>
								Дата '.(isset($par['Date'])?$par['Date']:'').'</td>
								<td align=right style="color: #000000;"
									font-size:10px; ; padding-top:6px; padding-right:15px><br>
								Сумма платежа: <b>'.(isset($par['summ'])?$par['summ']:'').' руб.</b></td>
							</tr>
							<tr>
								<td colspan=2 align=center style="color: #000000;"
									font-size:10px; ;  padding:6 100 20 10 nowrap>Плательщик
								(подпись)&nbsp<img src=img/black.gif width=200 height=1></td>
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
Заказ № '.(isset($par['ordernum'])?$par['ordernum']:'').'i от '.(isset($par['Date'])?$par['Date']:'').'</b></td>

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

                                                <td align=center class=table31><b style="font-size: 12px">№</td>

                                                <td align=center width=100% class=table31><b

                                                        style="font-size: 12px">Товар</td>

                                                <td align=center class=table31 nowrap><b

                                                        style="font-size: 12px">Кол-во</td>

                                                <td align=center class=table31><b style="font-size: 12px">Ед.</td>

                                                <td align=center class=table31><b style="font-size: 12px">Цена</td>

                                                <td align=center class=table31><b style="font-size: 12px">Сумма</td>

                                        </tr>

                                        '.tpl::_a($par['list'],array('tpl_print','txt_list')).'

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

                                <td style="color: #000000">
                                    <p><b style="font-size: 12px">Руководитель</b>__________________('.tpl::_d($par['cust_DIRECTOR'],'______________').')</p>
                                </td>

                                <td style="color: #000000">
                                    <p><b style="font-size: 12px">Бухгалтер</b>__________________('.tpl::_d($par['cust_BUHGALTER'],'______________').')</p>
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