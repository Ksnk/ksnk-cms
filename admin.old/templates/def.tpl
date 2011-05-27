<!--begin:defvalues-->
<form name="defvalues" action="" method="post">
<input type="hidden" value="defaultValues" name="form">
<div class="stick " style="height:30px;"></div>
<div class="stick" style="width:740px;"></div>
<div style="margin-bottom:2px;" class="graypane">
<table class="grayline long"><tr>
<td style="line-height:22px;font-weight:bold; color:green;" class="align_center">Параметры по умолчанию</td>
<td style="width:120px;"><input type="button" class="savebutton">
</td>
</tr></table></div>
<div class='lofty' style="overflow:auto;">
 <!--begin:tabs-->
{data}
<!--end:tabs-->
</div>
<script type="text/javascript">

$(function(){
	var change_func=[];

	change_func[16]=function (){
	var o=change_func[16].option,options={
		width:parseInt(o.sizex) || 90	
		,height:parseInt(o.sizey) || 90
//		,borderWidth:'2px'
//		,borderColor:'red'
	};
	if (o.border!='0' && o.border!='') {
		options['border-style']='solid';
		options['border-top-width']=(o.by||'2')+'px';
		options['border-bottom-width']=(o.by||'2')+'px';
		options['border-left-width']=(o.bx||'2')+'px';
		options['border-right-width']=(o.bx||'2')+'px';
		options['border-color']=o.color||'red';
	} else {
		options['border']='0';
	}
	var texto={
			color:o.tcolor||'rgb(87,101,115)',
			'font-family':o.tfont||'Tahoma',
			'font-size':o.tsize||'11px',
			'font-weight':o.tpb==1?'bold':'normal',
			'font-style':o.tpi==1?'italic':'normal',
			'text-decoration':o.tpu==1?'underline':'none'
	};
	
	if(change_func[16].to){
		clearTimeout(change_func[16].to);
	}
	change_func[16].to=setTimeout(function(){
		change_func[16].to=false;
		$('#xTP_defpicture').css(options);
		$('#xTP_deftext').css(texto);
	},10);
};
change_func[27]=function (){
	var o=change_func[27].option,options={
		overflow:'hidden'
		,height:parseInt(o.height) || 2
		,backgroundColor:o.color||'red'	
	};
	if(o.width && o.width.match(/\%$/))
		options.width=o.width;
	else
		parseInt(o.width) || '100%';
	switch(parseInt(o.align)||0){
	case 1: // center
		options.margin='0 auto';
		break;
	case 2: // right
		options.margin='0 0 0 auto';
		break;
	default: // left
		options.margin='0 auto 0 0';
		break;
	}
	var brdsize=Math.round((options.height+2)/3);
	switch(parseInt(o.btype)||0){
	case 1:// solid
	case 2:// doted	
		options.border='0';
		break;
	case 3:// inset	
		options.border=brdsize+'px inset';
		break;
	case 4:// outset	
		options.border=brdsize+'px outset';
		break;
}
	
	if(change_func[27].to){
		clearTimeout(change_func[27].to);
	}
	change_func[27].to=setTimeout(function(){
		change_func[27].to=false;
		$('#TP_x_LINE').css(options);
	},10);
};
change_func[3]=function (){
	var o=change_func[3].option;
	var texto={
			color:o.color||'rgb(87,101,115)',
			'font-family':o.font||'Tahoma',
			'font-size':o.size||'11px',
			'font-weight':o.tpb==1?'bold':'normal',
			'font-style':o.tpi==1?'italic':'normal',
			'text-decoration':o.tpu==1?'underline':'none'
	};
	if(o.pic_small){
		texto['padding-left']='30px';
		texto['background']='url('+o.pic_small+') 0 60% no-repeat';
	} else {
		texto['background']="none";
		texto['padding-left']='0px';
	}
	
	if(!change_func[3].to){
		clearTimeout(change_func[3].to);
	}
	change_func[3].to=setTimeout(function(){
		change_func[3].to=false;
		$('#xLINKS_def').css(texto);
	},10);
};
change_func[20]=function (){
	var o=change_func[20].option;
	var texto={
			color:o.color||'rgb(87,101,115)',
			'font-family':o.font||'Tahoma',
			'font-size':o.size||'11px',
			'font-weight':o.tpb==1?'bold':'normal',
			'font-style':o.tpi==1?'italic':'normal',
			'text-decoration':o.tpu==1?'underline':'none'
	};
	if(o.pic_small){
		texto['padding-left']='30px';
		texto['background']='url('+o.pic_small+') 0 60% no-repeat';
	} else {
		texto['background']="none";
		texto['padding-left']='0px';
	}
	
	if(!change_func[20].to){
		clearTimeout(change_func[20].to);
	}
	change_func[20].to=setTimeout(function(){
		change_func[20].to=false;
		$('#xANCHOR_def').css(texto);
	},10);
};
//
change_func[6]=function (){
	var o=change_func[6].option,options={
		table:{
			border:'none',
			'border-collapse':'collapse'
		},
		td_th:{
			border:0,
			borderSpacing:0,
			padding:'3px 5px'
		},
		th:{
			backgroundColor:o.hcolor||'rgb(168,190,204)'	
		},
		even:{
			backgroundColor:o.lrcolor||'rgb(224,235,241)'	
		},
		odd:{
			backgroundColor:o.drcolor||'rgb(209,223,232)'	
		}
	}
	,size='2px'
	,style=' outset ';
	o.brcolor=(o.brcolor||'white')
	if(o.border==1){
		options.td_th.border=size+style+o.brcolor;
		//options.table['border-collapse']='separate';
		options.table.border=size+' inset '+o.brcolor;
		//options.table.backgroundColor=o.brcolor||'black';
	} else if(o.border==2){
		//options.td_th.borderSpacing='2px';
		//options.table['border-collapse']='separate';
		options.table.border=size+' inset '+o.brcolor;
		//options.table.backgroundColor=o.brcolor||'black';
	} else if(o.border==3){
		options.td_th.border=size+style+o.brcolor;
		options.td_th['border-left-width']='0';
		options.td_th['border-right-width']='0';
		
		//options.td_th.borderSpacing='2px';
		//options.table['border-collapse']='separate';
		options.table.border=size+' inset '+o.brcolor;
		//options.table.backgroundColor=o.brcolor||'black';
	}
	
	if(change_func[6].to){
		clearTimeout(change_func[6].to);
	}
	change_func[6].to=setTimeout(function(){
		change_func[6].to=false;
		$('#xTABLE_def').css(options.table);
		$('#xTABLE_def th').css(options.th);
		$('#xTABLE_def th,#xTABLE_def td').css(options.td_th);
		$('#xTABLE_def tr.even td').css(options.even);
		$('#xTABLE_def tr.odd td').css(options.odd);
	},10);
};
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

<!--begin:def_xTP-->
<div  class="greenpane">
<div class="arrow "></div>
<table class="pane long align_center align_middle">
<col width="90">
<col width="160">
<col width="160">
<col width="100">
<col>
<col>
<tr>
<td style="height:67px;" class="bgxx" ><img src="img/def_foto.gif"></td><td class="bgxx">Мал. фото.<br>
			<input class="digit6" name="sizex_{id}" value="{sizex>>html}" type="text">x<input class="digit6" name="sizey_{id}" value="{sizey}" type="text"></td><td class="bgxx">Бол. фото.<br>
			<input class="digit6" name="bsizex_{id}" value="{bsizex>>html}" type="text">x<input class="digit6" name="bsizey_{id}" value="{bsizey}" type="text"></td><td class="bgxx">Рамка.<br><input class="checkbox" value="{border>>html}" type="text" name="border_{id}"></td>
<td class="bgxx">Цвет рамки.<br><input class="box colorbox" value="{color>>html}" value="{color>>html}" name="color_{id}"></td><td class="bgxx">толщина рамки.<br>
			<input class="digit6" name="bx_{id}" value="{bx>>html}" type="text">x<input class="digit6" name="by_{id}" value="{by}" type="text"></td></tr>
</table>

<table class="tahoma long ctext size11  align_middle" style="margin-bottom:4px;">
	<col width="90px">
	<col width="150px">
	<col width="auto">
	<col width="1px">
	<tr>
		<td class="align_center align_middle bgray" >
		Пример фото</td>
		<td class="align_center bgdray" style="padding:10 8px;">
		<img id="xTP_defpicture" src="img/test.jpg">
		</td>
		<td class="align_center bgdray" >
		Фотографии будут автоматически вписываться в выставленные габариты.<br>
		Пропорции фотографий не изменятся.
		</td><td  class="bgdray"><div class="stick" style="height:60px;"></div></td>
	</tr>
</table>

</div>
<div  class="greenpane">
<div class="arrow "></div>
<table class="pane long align_center align_middle">
<col width="90">
<col width="160">
<col>
<col>
<col>
<col width="160">
<tr>
<td style="height:67px;" class="bgxx" ><img src="img/def_text.gif"></td><td class="bgxx">
			<input type="text"  name="tpb_{id}"class="_bold" value="{tpb>>html}">
			<input type="text"  name="tpu_{id}"class="_underline" value="{tpu}">
			<input type="text"  name="tpi_{id}"class="_italic" value="{tpi}">
			</td><td class="bgxx"><input class="box fontbox" value="{tfont>>html}" name="tfont_{id}"></td><td class="bgxx"><input class="box colorbox" value="{tcolor>>html}" value="{tcolor>>html}" name="tcolor_{id}"></td><td class="bgxx"><input class="box sizebox" value="{tsize>>html}" name="tsize_{id}"></td><td class="bgxx">доп описание.<div style="display:inline-block;text-align:left;"><input type="radio" name="doptarget_{id}" value="1"{doptarget=1?? checked}>Выпадающее<br><input type="radio" name="doptarget_{id}" value="2"{doptarget=2?? checked}>Новое окно</div></td></tr>
</table>
<table class="tahoma long ctext size11 fixed align_middle" style="margin-bottom:4px;">
	<col width="90px">
	<col width="auto">
	<tr>
		<td style="height:84px" class="align_center align_middle bgray" >
		Пример текста</td>
		<td class="align_center bgdray" id='xTP_deftext'>
		Фотографии будут автоматически вписываться в выставленные габариты.<br>
		Пропорции фотографий не изменятся.
		</td>
	</tr>
</table>
</div>
<!--end:def_xTP-->
<!--begin:def_xLinks-->
<div  class="greenpane">
<div class="arrow "></div>
<table class="pane long align_center align_middle">
<col width="90">
<col width="160">
<col>
<col>
<col>
<col width="80">
<tr>
<td style="height:67px;" class="bgxx" ><img src="img/def_links.gif"></td><td class="bgxx">
			<input type="text"  name="tpb_{id}"class="_bold" value="{tpb>>html}">
			<input type="text"  name="tpu_{id}"class="_underline" value="{tpu}">
			<input type="text"  name="tpi_{id}"class="_italic" value="{tpi}">
			</td><td class="bgxx"><input class="box fontbox" value="{font>>html}" name="font_{id}"></td><td class="bgxx"><input class="box colorbox" value="{color>>html}" value="{color>>html}" name="color_{id}"></td><td class="bgxx"><input class="box sizebox" value="{size>>html}" name="size_{id}"></td><td class="bgxx"><div class="picture_upload" alt="small" style="vertical-align:middle;display:inline-block;min-height:30px; min-width:30px; max-height:50px;overflow:hidden;margin-right:30px;">
			<img src="{pic_small|img/folderb.gif}">
			<input  name="pic_small_{id}" value="{pic_small>>html}" type="text" style="vertical-align:middle;display:none;"></div>
			</td></tr>
</table>

<table class="tahoma long ctext size11 fixed align_middle" style="margin-bottom:4px;">
	<col width="90px">
	<col width="auto">
	<tr>
		<td style="height:84px" class="align_center align_middle bgray" >
		Пример Cсылки</td>
		<td class="align_center bgdray" >
		<a id="xLINKS_def" href='#' onclick="return false;">так будет выглядеть ссылка по умолчанию</a>
		</td>
	</tr>
</table>
</div>

<!--end:def_xLinks-->
<!--begin:def_x_GALLERY--><!--end:def_x_GALLERY-->
<!--begin:def_x_COMMENT-->

<!--end:def_x_COMMENT-->
<!--begin:def_xCOLUMNS--><!--end:def_xCOLUMNS-->

