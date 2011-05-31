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



<!--<table border=1>

<!--begin:listpar

<tr><td>name{cust_ORGANISATION}</td><td>val{user}</td></tr>

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

                                <td style="padding-top: 15px"><span style="font-size:14px;"><b>{cust_ORGANISATION}</b></span><b style="font-size: 14px;"color:#000000><br>
Заказ № {ordernum}i от {Date}</b></td>

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

                                        <!--begin:llist-->

                                        <tr>

                                                <td class=table31>{articul}</td>

                                                <td class=table31>{descr}</td>

                                                <td class=table31>{cnumb}</td>

                                                <td class=table31>{edism}</td>

                                                <td class=table31>{ccost}</b></td>

                                                <td class=table31>{cccost}</td>

                                        </tr>

                                        <!--end:llist-->

                                        <tr>

                                                <td colspan=5 align=right style="padding-top: 10px"><b

                                                        style="color: #000000;" font-size:12px>Итого:</b></td>

                                                <td align=left style="padding-top: 10px"><b

                                                        style="color: #000000;" font-size:12px; padding-left:10px>{summ}</b></td>

                                        </tr>

                                        <tr>

                                                <td colspan=5 align=right style="padding-top: 5px"><b

                                                        style="color: #000000;" font-size:12px>В том числе НДС:</b></td>

                                                <td align=left style="padding-top: 5px"><b

                                                        style="color: #000000;" font-size:12px; padding-left:10px>{nds}</b></td>

                                        </tr>

                                </table>

                                </td>

                        </tr>

                        <tr>

                                <td align=left style="color: #000000"><br>

                                <nobr>Всего {inumb} на сумму {summ} руб.</nobr> <nobr>( <i>{summprop}</i>  )</nobr></td>

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
                                    <p><b style="font-size: 12px">Руководитель</b>__________________({cust_DIRECTOR|______________})</p>
                                </td>

                                <td style="color: #000000">
                                    <p><b style="font-size: 12px">Бухгалтер</b>__________________({cust_BUHGALTER|______________})</p>
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

</html>