		<table class="align_center" style="width:100%; border-top:3px solid #e3e6ea; border-bottom:3px solid #e3e6ea; border-left:none; border-right:none; margin-top:0px; margin-bottom:10px;">
		<tr><td style="padding-top:1px; padding-bottom:1px">
            <table class="table tablex" style="width:100%; text-align:left">
					<tr>
						<th class="first_td">№</th>
						<th>Товар</th>
						<th>Кол-во</th>
						<th>Ед.</th>
						<th>Цена</th>
						<th>Сумма</th>
					</tr>
					<!--begin:llist-->
					<tr>
						<td class="first_td">{numb}</td>
						<td>{name}</td>
						<td>{cnumb}</td>
						<td>шт.</td>
						<td>{ccost}</td>
						<td>{cccost}</td>
					</tr>
					<!--end:llist-->
					<tr>
						<th class="first_td" colspan=5 align=right>Итого:</td>
						<td align=left>{summ}</td>
					</tr>
					<tr>
						<th class="first_td" colspan=5 align=right >В том числе НДС:</td>
						<td align=left >{nds}</td>
					</tr>
				</table>
                </td></tr>
                </table>
<nobr>Всего {inumb} на сумму {summ} руб.</nobr> <nobr>( <i>{summprop}</i>  )</nobr>