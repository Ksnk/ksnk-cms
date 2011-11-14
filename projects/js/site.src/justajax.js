/**
 *  настройка таймаута на соединение 
 */
	$.ajaxSetup({
		timeout:5000
		,complete: function(xht,textStatus){
			
			if(!xht.status) { // looks like timeout!
				debug('timeout '+textStatus);
			} else if(xht.status==200) {// looks like Ok!
				debug('complete '+textStatus);
			} else { // looks like 500 status and so on
				debug('unknown '+textStatus+'('+(xht.status||0)+')');
			}
			
			var data=xht.responseText;
			if(typeof(data)=='string'){
				try{
					data=(new Function('return '+data))();
				} catch(e){
					debug('fault',data);
				}
			} 
			if(data && data.error){
				win_alert({
					txt:data.error
				});
			}
			if(data && data.debug){
				debug(data.debug);
			}
            if(data && data.result && data.result.complete){
				//var x = new Function('','try{'+data.result.complete+'}; catch(e){debug("fault");}');
                setTimeout(data.result.complete,100);
			}
			
		}

		,error: function (XMLHttpRequest, textStatus, errorThrown){
			debug('error',textStatus);
		}

	}); // 5 seconds, no more

	function justajax(e,href,repl){
		var $self = this,confirm,ajaxit
			,compl=$self.attr('data-complete')||$self.attr('complete');
		try{
		if(typeof(compl)=='string'){
			try{
				compl=(new Function('data','try{data=(new Function("return "+data))();} catch(e){debug("fault",data);};'+compl));
			} catch(e){
				compl=function(){;};
			}
		} else compl=function(){;}; 
		var form=$self.attr('data-form')||$self.attr('data-dialog');
		if(form && form.match(/\$|\(/)){
			form=new Function("return "+form);
			form=form.apply(this);
		} else if(form)
			form=$(form.toString())
		if(form)	
			form=form.find('input,select,textarea');
		if(!form && ($self[0].tagName.toLowerCase()=='form')){
			form=$self;
			if(!href)
				href=location.href;
		}
		if(form){
			ajaxit=function($repl){
				if($repl) href=href.replace('~~',decodeURIComponent($repl));
				if(href){
					$.post(href.replace('ajax=1','').replace(/(do=\w*)/,'$1&ajax=1')
						,form.serialize()
						,compl);
				}
			};
		} else {
			ajaxit=function($repl){
				if($repl) href=href.replace('~~',decodeURIComponent($repl));
				if(href){
					$.post(href.replace('ajax=1','').replace(/(do=\w*)/,'$1&ajax=1')
						,compl);
				}
			};
		}
		if(confirm=$self.attr('data-confirm')) {
			win_confirm({
				txt:confirm,
				yes:function(){
					ajaxit(repl);
				}
			});
		} else if(confirm=$self.attr('data-dialog')) {
				win_dialog({
					dialog:confirm,
					yes:function(){
						ajaxit(repl);
					}
				});
		} else if(confirm=$self.attr('data-input')) {
			win_input({
				txt:confirm,
				yes:function(txt){
					ajaxit(txt);
				}
			});
		} else 
			ajaxit(repl);	
		}catch(e){
			debug(e.toString())
			;
		}
		return false;	
	}
	
	$.fn.justajax=function(){
		this.each(function(){
			var $self=$(this);
			switch(this.tagName.toLowerCase()){
			case 'input':
				if($self.attr('type')=='button'){
					$self.click(function (e){
						justajax.call($self,e,$self.attr('data-href'));
					});
				}
				break;
			case 'form':
				$self.submit(function (e){
					return justajax.call($self,e,$self.attr('action'));
				});
				break;
			case 'select':
				$self.change(function (e){justajax.call($self,e,$self.attr('data-href')||$self.attr('href')||'',$self.val());});
				break;
			case 'a':
				var href=$self.attr('data-href');
				if(!href){
					href=$self.attr('href');
					$self.attr('data-href',href);
				}
				$self.click(function (e){return justajax.call($self,e,$self.attr('data-href')||$self.attr('href')||'');});
				break;
			}
		});
		return this;
	};

	$('.ajax').justajax();
