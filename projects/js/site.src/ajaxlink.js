/** настройка таймаута на соединение */
	$.ajaxSetup({timeout:5000}); // 5 second, no more

	$('a.ajax').each(function(){
		$(this).data('click',$(this).attr('onclick'))
	}).removeAttr('onclick').bind('click',function(){
		var $self = $(this);
		if(this.href){
			$.get(this.href.replace('ajax=1','').replace(/(do=\w*)/,'$1&ajax=1'),function(data){
				if(typeof(data)=='string'){
					try{
						data=(new Function('return '+data))();
					} catch(e){;
						alert(['fault',data]);
					}
				}
				if(data.error) alert(data.error);
				if(data.debug) alert(data.debug);
				if(data.result) {
					if(data.result.event){
						var s = $self.data('click');
						if(typeof(s)=='string'){
							try{
								s=(new Function('return '+data));
								s.apply(this,null,data);
							} catch(e){;}
						} else if (typeof(s)=='function')
							s.apply(this,null,data);
						delete data.result.event;
					} 
					for(a in data.result)
						$('#'+a).html(data.result[a]);
					// reroute
				} else if(data.data && data.data!=' '){	
					alert(data.data);
				}
			})
		}
   		return false;	
	})
