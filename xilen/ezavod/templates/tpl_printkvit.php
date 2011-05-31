<?php
class tpl_printkvit extends tpl {

function _(&$par){		
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
}}
?>