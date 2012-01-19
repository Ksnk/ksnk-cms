	$('a.url_page').click(function(){
		if(this.href && !this.href.match(/javascript/i)){
			var self=this,parent=$(this).closest('.para')[0];
			function clickit(e){
				if(e && self) return false;
			    $(this).find('.back').toggleClass('hidden');
			  	$(this.container).toggle('normal');
				return false;
			}
			var href=this.href.toString();
			if (href.match(/do=/))
				href=href.replace('ajax=1','')+'&ajax=1';
			else
				href=href+'?ajax=1';
			$.get(href,function(data){
				if(typeof(data)=='string'){
					try{
						data=(new Function('return '+data))();
					} catch(e){;
						alert(['fault',data])
					}
				}
				if(data.error) alert(data.error);
				if(data.debug) alert(data.debug);
				if(data.data) {
				    self.container=$('<div class="ainfo"></div>').insertAfter($(parent)).hide().html(data.data)[0];
				    clickit.apply(self);
				    self=null;
				} 
			})
			this.href='javascript:;';
			$(this).click(clickit);
		}
   		return false;	
	});