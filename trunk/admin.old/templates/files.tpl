<!--begin:fileman-->
<form method="POST" action="">
<table class="long thetable tahoma" ><tr>
<th class="bblue">показать <input class="button" onclick="showPict(this)" type="button"  value="{picts|Файлы}"></th>
<th class="bblue align_center"><table class="tahoma"><tr><td class="uploader"><b>загрузить</b><input type="button" onclick="$must_save=false;__goto();" style="display:none;"></td></tr></table>
</th>
<th class="bblue align_left">
<input type="checkbox" class="glass" id="aaa" name="ff">
<input class="button" type="submit" name="delete" value="Удалить">
</th>
</tr>
<tr><td colspan=2>
</td></tr></table>
<div id="pages"></div>
<div id="fman_panel" style="width:100%;height:100%;overflow:auto;">
</div>

<script type="text/javascript">
element.add_event(element.$('aaa'),'click',function(){
	var e = this;
	element.allClass(this.form,'select',function(el){
		el.checked = e.checked;
	})
	e=null;
})

function showPict(el){
	var action='pictures';
	if(el && el.value && el.value!="Картинки")
		action='files';

	element.Ajax.getJSON('?plugin=fileman&do=getFiles&act='+action+'&ajax=1','',function(data){
		if(data && data.result && data.result[action]){
			var x=data.result[action];
			filelist=[];
			for(var i=0; i<x.length;i++){
				var xx=x[i];
				filelist.push({
					url:xx && xx.url||'',
					size:xx && xx.size||'',
					info:xx && xx.info||''
				})
			}
			filelist.push({url:"",size:"",info:""});
			pages=data.result['pages']||'';
		}
		//debug.trace(data.result.pictures).alert();
		wrFiles(2,18);
		el.value=action=='pictures'?"Файлы":"Картинки";
	})
}

function wrFiles(maxcols,cells){
 // вывод списка файлов по columns элементов
    element.$('pages').innerHTML=pages;
	var ee=element.$('fman_column').getElementsByTagName('tr')[0].innerHTML,
		headers=element.$('fman_tpl').getElementsByTagName('tr')[0].innerHTML,rheader='',
		columns=Math.ceil((filelist.length-1)/cells);
		if (columns>maxcols){
			columns=maxcols;
			cells=Math.ceil((filelist.length-1)/columns);
		}
//	alert(columns);
//	alert(ee);
	for(var i=0;i<columns;i++){
		rheader+=headers+'<th style="background:white">&nbsp;</th>';
	}
	var e_data='';
	// копируем все в первый столбик
	for(var i=0; i<cells; i++){
	//i<filelist.length-1;
		e_data+='<tr class="'+(i & 1?'even':'odd')+'">';
		for(var j=0;j<columns;j++){
			var el=(i+(j*cells))>=filelist.length
				?filelist[filelist.length-1]
				:filelist[i+(j*cells)];
			var _row=ee
				.replace(/%odd%/g,i & 1?'even':'odd')
				.replace(/%name%/g,el.url)
				.replace(/%url%/g,encodeURIComponent(el.url))
				.replace(/%size%/g,el.size)
				.replace(/%info%/g,el.info)+
				'<td style="background:white">&nbsp;</td>';
			if(!el.url)
				_row=_row.replace(/<label.*\/label>/i,'');
			e_data+=_row;
		}
		e_data+='</tr>';
	}

//	alert(element.$('fman_tpl').innerHTML);
	element.$('fman_panel').innerHTML=
		'<table class="tahoma size11 thetable"><tr>'+rheader+'</tr>'+
		e_data
		'</table>';
}

element.add_event(window,'load',function(){
	wrFiles(2,18);
})

var filelist=[
<!--begin:list-->
{url:"{url}",size:"{size}",info:"{info}"},
<!--end:list-->
{url:"",size:"",info:""}
]
var pages={pages};
</script>
<div  style="display:none;">
<div style="float:left;" id="fman_tpl">
<table class="tahoma thetable"><tr>
<th>Имя</th><th>размер</th><th>info</th>
</tr><tr><td>%data%</td></tr>
</table>
</div>
<div id="fman_column">
<table>
<tr class="%odd%">
<td ><nobr><label><input type="checkbox" class="glass select" value="%url%" name="ff[]">%name%</label></nobr></td><td>%size%</td><td>%info%</td>
</tr>
</table>
</div>
</div>

</form>
<!--end:fileman-->
