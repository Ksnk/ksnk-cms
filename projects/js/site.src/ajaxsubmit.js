	$("form.ajax").submit(function(){
		var form=this;
		$.post(
			this.action.replace('ajax=1','')+'&ajax=1',
			$(this).serialize(),
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
				if(!!data.data)
					alert(data.data);
			},
			'json'
		)
   		return false;	
	});
