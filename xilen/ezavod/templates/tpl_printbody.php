<?php
class tpl_printbody extends tpl {

function _(&$par){		
		return '<table class="align_center" style="width:100%; border-top:3px solid #e3e6ea; border-bottom:3px solid #e3e6ea; border-left:none; border-right:none; margin-top:0px; margin-bottom:10px;">
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
					'.tpl::_a($par['llist'],array('tpl_printbody','llist')).'
					<tr>
						<th class="first_td" colspan=5 align=right>Итого:</td>
						<td align=left>'.(isset($par['summ'])?$par['summ']:'').'</td>
					</tr>
					<tr>
						<th class="first_td" colspan=5 align=right >В том числе НДС:</td>
						<td align=left >'.(isset($par['nds'])?$par['nds']:'').'</td>
					</tr>
				</table>
                </td></tr>
                </table>
<nobr>Всего '.(isset($par['inumb'])?$par['inumb']:'').' на сумму '.(isset($par['summ'])?$par['summ']:'').' руб.</nobr> <nobr>( <i>'.(isset($par['summprop'])?$par['summprop']:'').'</i>  )</nobr>';
}

function llist(&$par){		
		return '<tr>
						<td class="first_td">'.(isset($par['numb'])?$par['numb']:'').'</td>
						<td>'.(isset($par['name'])?$par['name']:'').'</td>
						<td>'.(isset($par['cnumb'])?$par['cnumb']:'').'</td>
						<td>шт.</td>
						<td>'.(isset($par['ccost'])?$par['ccost']:'').'</td>
						<td>'.(isset($par['cccost'])?$par['cccost']:'').'</td>
					</tr>';
}}
?>