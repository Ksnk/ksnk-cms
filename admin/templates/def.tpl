<!--begin:defvalues-->
<form name="defvalues" action="" method="post">
<input type="hidden" value="defaultValues" name="form">
<div class="stick " style="height:30px;"></div>
<div class="stick" style="width:740px;"></div>
<%=TPL_insert_pluginheader('Параметры по умолчанию')%>
<div class='lofty' style="overflow:auto;">
 <!--begin:tabs-->
{data}
<!--end:tabs-->
</div>
<script type="text/javascript">

$(function(){
	var change_func=[];

	<%=point('changefunc_def')%>

	$('input, radio, select')
	.change(function(){
		var m,$self=$(this);
		if( m=$self.attr('name').match(/(\w+)_(\d+)/)
			&& (this.tagName.toLowerCase()!='radio' || $self.attr('checked')))
		{
			var ch_func=parseInt(m[2]);
			if (ch_func && change_func[ch_func]){
				if(!change_func[ch_func].option) change_func[ch_func].option={};
				change_func[ch_func].option[m[1]]=$self.val();
				change_func[ch_func]();
			}
			need_Save();
		}
	}).trigger('change');
	need_Save(false);
})
</script>
</form>
<!--end:defvalues-->

<%=point('default_tpl')%>


