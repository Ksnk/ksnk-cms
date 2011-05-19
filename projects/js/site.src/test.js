$(function(){

	$("#debug").ajaxError(function(event, request, settings){
		$(this).append("<li>Error requesting page " + settings.url + "<"+"/li>");
	});

	$(".ajaxform").each(function(){
		var form = this;
		$(this).find('a.submit').click(function(){
			// в jQuery дурная ашипка с сериализацией select'ов
			var ser=[];
			$(form).find('select').each(function(){
				ser.push(this.name.replace(/_\d*/,'')+'='+encodeURIComponent(this.options[this.selectedIndex].value));
			}) 
			$(form).find('input').each(function(){
				ser.push(this.name.replace(/_\d*/,'')+'='+encodeURIComponent(this.value));
				if (this.name.match(/^item_/))
					this.value='';
			}) 
			
			$.post('{::curl:do:id}do=add&ajax=1',
				ser.join('&'),
				function(data){
					if(data.error) alert(data.error);
					if(data.debug) alert(data.debug);
					if(data.result) {
						for(a in data.result)
							$('#'+a).html(data.result[a]);
					} else {	
						alert(data.data);
					}
				},
				'json'
			)
	   		return false;	
	})})
	
	$('a.ajax').click(function(){
		if(this.href){
			$.getJSON(this.href.replace('ajax=1','').replace(/(do=\w*)/,'$1&ajax=1'),function(data){
				if(data.error) alert(data.error);
				if(data.debug) alert(data.debug);
				if(data.result) {
					for(a in data.result)
						$('#'+a).html(data.result[a]);
				} else {	
					alert(data.data);
				}
			})
		}
   		return false;	
	})

	$('a.url_page').click(function(){
		if(this.href && !this.href.match(/javascript/i)){
			var self=this;
			$.getJSON(this.href.replace('ajax=1','').replace(/(do=\w*)/,'$1&ajax=1'),function(data){
				if(data.error) alert(data.error);
				if(data.debug) alert(data.debug);
				if(data.data) {
					$(self).after('<div>'+data.data+'</div>');
				} 
			})
			$(this).click(function(){
				$(this).next().toggle();
				return false;
			})
			this.href='javascript:;';
		}
   		return false;	
	})
})


function Wopen(url,w,h){
if(w) w='width='+w;
if(h) h='height='+h;
try{
	wind=open(url,"",w+','+h);
}catch(e) {
	alert('Всплывающие окна заблокированы! Разрешите всплывающие окна для нормального функционирования.')
}
}
