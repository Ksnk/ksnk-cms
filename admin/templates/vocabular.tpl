///// Вывод таблицы каталога

<!--begin:admin_vocabular-->
<form name="admin{name}" action="" method="POST">
<span style="color:red;font-size:16px;">{error}</span>
<input type='hidden' class="del" name="del">
{additional}

<table class="table long ctext size11">
<tr>
<th class="bblue" style="width:20px;padding: 0 2px;">№</th>
<!--begin:head-->
<th class="bblue {class}">{title}</th>
<!--end:head-->
<th class="bblue"></th>
</tr>
<!--begin:list-->
<tr class="{trclass|odd}" id="{prefix|rl}_{id}"><th class="nopage" style="width:20px;">{numb}</th>
<!--begin:row-->
<t{d|d} style="{style}" class="{class|text_edit}" id="{name}_{id}">{value}</t{d|d}>
<!--end:row-->
<!--begin:sort-->
<th class="nopage align_center" style="padding:0 2px;" nowrap>
{::tpl:admin:order_elm_start}
<input type="text" class="order size11" onkeydown="need_Save()" style="width:15px;" name="item_order_{id}">
{::tpl:admin:order_elm_fin}
</th>
<!--end:sort-->
<th style="width:20px;padding: 0 2px;">{::tpl:admin:delrec_elm}</th>
<!--end:list-->
</tr>
<tr class="odd" id="newrow" style="display:none;">
<th>&raquo;</th>
<!--begin:plus-->
<td  class="{class|text_edit}" id="{name}"></td>
<!--end:plus-->
<th ></th>
</tr>

</table>
{additional2|
<table class="table long ctext size11">
<tr>
<th class="bblue align_center" STYLE="padding:2px;">
<input class="button savebutton" type="submit"  disabled="disabled" value=" Сохранить ">
<input type="button" class="button" onclick="element.$('newrow').style.display='';this.disabled='disabled';" name="newRecord" value="Добавить">
</th>
</tr></table>
}

<table class="table long ctext size11">
<tr><th style="background:white;">
{pages}
</th></tr></table>

<script type="text/javascript">
element.add_event(element.$('aaa'),'click',function(){
	var e = this;
	element.allClass(this.form,'select',function(el){
		el.checked = e.checked;
	})
	e=null;
})
</script>
</form>
<!--end:admin_vocabular-->


<td style="background:white;width:20px;padding: 0 2px;"><input type="checkbox" id="aaa" value="0"></td></tr>
<th style="background:white;width:20px;padding: 0 2px;"><input type="checkbox" class="select" name="aaa" value="{id}"></th></tr>
