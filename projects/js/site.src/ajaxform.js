	$(".ajaxform").each(function(){
		var form = this;
		$(this).find('a.submit').click(function(){
			// � jQuery ������ ������ � ������������� select'��
			var ser=[];
			$(form).find('select').each(function(){
				ser.push(this.name.replace(/_\d*/,'')+'='+encodeURIComponent(this.options[this.selectedIndex].value));
			}) 
			$(form).find('input').each(function(){
				ser.push(this.name.replace(/_\d*/,'')+'='+encodeURIComponent(this.value));
				if (this.name.match(/^item_?/))
					this.value='';
			})
			$(this).parent().css({position:'relative'});
			var xx=$('<div></div>').css({
				backgroundColor:'white',
				width:'70px',
				border:'1px solid gray',
				padding:'10px 20px',
				position:'absolute',
				top:'1.5em',
				left:0
			}).insertAfter($(this)).html('�����<br>�����������'); 
			$.post(
				this.href.replace('ajax=1','').replace(/(\/#?$|\/\?do=\w*)/,'/?do=add&ajax=1'),
				ser.join('&'),
				function(data){
					if(data.error) alert(data.error);
					if(data.debug) alert(data.debug);
					if(data.session){ // just session started
					  if(!cookie(data.session.name)) {
					    var reg=new RegExp('\&'+data.session.name+'=\\w*|'+data.session.name+'=\\w*\&','ig')
					  	document.location=
					  		document.location.href.replace(reg,'')+
					  		'&'+data.session.name+'='+data.session.value;
					  }
					}
					if(data.result) {
						for(a in data.result)
							$('#'+a).html(data.result[a]);
						xx.html('�����<br>��������');	
					} else {	
						alert(data.data);
						xx.html('������ :-( ');
					}
					setTimeout(function(){
						xx.remove();
						xx=null;
					},1000);
				},
				'json'
			)
	   		return false;	
	})});
