$(function(){
    //ajax control support
    
    var scroll= cookie('scroll');
    if(scroll){
    	window.scrollTo(0,scroll);
    	cookie('scroll',0);
    };
	$('.storepos').mousedown(function(){
	    cookie('scroll'
	    	,(window.scrollY) ? window.scrollY : document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop
	    	,{expires:10000})
	});
	$("#debug").ajaxError(function(event, request, settings){
		$(this).append("<li>Error requesting page " + settings.url + "<"+"/li>");
	});
	// Вставка из Тойхобби
	$(".ajaxform").each(function(){
		var form = this;
		$(this).find('.submit').click(function(){
			// в jQuery дурная ашипка с сериализацией select'ов
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
			}).insertAfter($(this)).html('Товар<br>добавляется');
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
						xx.html('Товар<br>добавлен');	
					} else {	
						alert(data.data);
						xx.html('Ашипка :-( ');
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
	// Конец вставки из Тойхобби
function WindowO(e,s,w,h)
{
	var $x=$(e).attr('href');
//	if($x && ($x.length>2)) { return true;}
	if(s.match(/\/$/)) return false ;
	try{
	  var par="location=no,toolbar=no,resizable=yes";
	  if((w+100>window.screen.width)||(h+100>window.screen.height))
	  	par+=",scrollbars=yes"
  	  par+=',width='+(w||100); 
 	  par+=',height='+(h||100);
	  wind=open("","win",par);
	  wind.document.writeln('<html>'+
		'<head><title><'+'/title><style>body,html{width:100%;height:100%;padding:0;margin:0;}\n'+
		'body{overflow:auto;}\n</'+'style>\n<'+'script>function fitPic(){'+
	 	'iWidth = window.innerWidth||document.body.clientWidth;'+
	 	'iHeight = window.innerHeight||document.body.clientHeight;'+
		'iWidth = document.images[0].width - iWidth;'+
		'iHeight = document.images[0].height - iHeight;'+
		'if(iWidth && iHeight)window.resizeBy(iWidth,iHeight);self.focus();'+
		//'alert([window.screen.height,window.screen.width, iWidth,iHeight,document.images[0].width,window.innerWidth,document.body.clientWidth]);'+
		'};</'+'script>'+
		'<'+'/head><'+'body onload="fitPic()">'+
		'<img src="'+s+'"><button style="position:absolute;right:10px;bottom:10px;" '+
		'onclick="self.close();">close</button><'+'/body><'+'/html>');
	  wind.document.close();
	} catch(e) {
	  alert('Всплывающие окна заблокированы! Разрешите всплывающие окна для нормального функционирования.')
	}
	return false;
};window.WindowO=WindowO;
    $first=null;
	$('.galleryX a').each(function(){
		if(this.href && this.href.match(/\.(jpe?g|png|gif)/)) {
		    this.__done=true;
		    $(this).click(function(){ 
				var t=$(this).parents('.galleryX').find('img')[0];
				t.src=this.href;
				return false
			})
			if(!$first){
				$first=true;
				$(this).click();
			}
			
		}			
	})
	$first=null;
	$('.gallery a, .cat_border a').removeAttr('onclick').each(function(){
		if(this.href && this.href.match(/\.(jpe?g|png|gif|flv)/)) {
			if(!this.__done)
				if($.fn.colorbox)
					$(this).colorbox();
				else				
					$(this).click(function(){return WindowO(this,this.href)});
		} else if(this.href && this.href.match(/#$|uploaded\/$/)) {
			$x=$(this).html();
			if($x)
				$(this).replaceWith($x);
		}
		$(this).find('img').bind('load',checkImg).each(function(){
			if(this.complete)
				checkImg(this);
		});
	});
	var lastopen=[];
	
	function dd_menu(level){
		if(level>0)
			menu(this);
		else
		menu(this,{
			show:function(){
				$(this).stop(false,true).css({display:'block','height':1})
					.animate({ height:100},'fast',function(){this.style.height='auto';})
					.parent().addClass('open');
			}
			,hide:function(){
				$(this).stop(false,true).animate({ height:1},'fast',function(){
					clearTimeout(this.__timeout);
					$(this).hide();
					if (lastopen[level]==this){
						lastopen.pop();
					}
				}).parent().removeClass('open');
			}
		});
		var self=this;
		$(this).parent().hover(function(){
			//console.log(lastopen);
			clearTimeout(self.__timeout);
			if(lastopen.length>level){
				if(lastopen[level]!=self){
					while(lastopen.length>level)
						lastopen.pop().hide_menu();
				}
			};
			if(lastopen[level]!=self){
				self.show_menu();
				lastopen[level]=self;
			}
		},function(){
			clearTimeout(self.__timeout);
			self.__timeout=setTimeout(function(){
				self.hide_menu();
			},1000);
		});
		var x=$(this).children('li');
		x.children('ul').each(function(){
			dd_menu.call(this,level+1);
		});
		$(x[0]).addClass('first');
	}
	
	$('ul.ulmenu>li').each(function(){
	
		if($(this).find('ul').each(function(){
			dd_menu.call(this,0);
		}).length==0) {
			$(this).mouseover(function(){
				if(lastopen.length>0){
					lastopen.pop().hide_menu()
				}
			})
		}
	});
	$('a.url_page').click(function(){
		if(this.href && !this.href.match(/javascript/i)){
			var self=this,parent=$(this).parents('.para')[0];
			function clickit(e){
				if(e && self) return false;
			    $(this).find('.back').toggleClass('hidden');
			  	$(this.container).toggle('normal');
				//    $('#debug').append("<li>click "+ "<"+"/li>");
				return false;
			}
			$.getJSON(this.href.replace('ajax=1','').replace(/(do=\w*)/,'$1&ajax=1'),function(data){
				if(data.error) alert(data.error);
				if(data.debug) alert(data.debug);
				if(data.data) {
				    self.container=$('<div class="ainfo"></div>').insertAfter($(parent)).hide().html(data.data)[0];
//				    $('#debug').append("<li>click "+ "<"+"/li>");
				    clickit.apply(self);
				    self=null;
				} 
			})
			this.href='javascript:;';
			$(this).click(clickit);
		}
   		return false;	
	})// init menu
menu('#searchbar',{
	show:function(){$(this).stop(true,true).show('hormal',function(){
		$('#search_string').focus();
	})},
	hide:function(){$(this).stop(true,true).hide('hormal')}
});

window.showsearchbar=function (){
	var x=$('#searchbar')[0];
	if(!x.shown)
		x.show_menu();
	return false;
};
    /* <% insert_point('js_main');%> */	
})

/* <% insert_point('js_body');%> */

// поставить куку cookie.
function cookie(name,value,opt){
	if (typeof value != 'undefined') { // name and value given, set cookie
		opt = opt || {};
		if (value === null) {
			value = '';
			opt.expires = -1;
		}
		var expires = '';//expires:10
		if (opt.expires && (typeof (opt.expires) == 'number' || opt.expires.toUTCString)) {
			var date;
			if (typeof opt.expires == 'number') {
				date = new Date();
				date.setTime(date.getTime() + Math.round(opt.expires * 24 * 60 * 60 * 1000));
			}
			else {
				date = opt.expires;
			}
			expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
		}
		document.cookie = name + '=' + encodeURIComponent(value) + expires +
			(opt.path ? '; path=' + opt.path : '') +
			(opt.domain ? '; domain=' + opt.domain : '') +
			(opt.secure ? '; secure' : '')
	}
	else { // only name given, get cookie
		if (document.cookie && document.cookie != '') {
			var cook = (new RegExp(";\\s*" + name + "\\s*=([^;]+)")).exec(';' + document.cookie);
			return cook && decodeURIComponent(cook[1]);
		}
		return null;
	}
};function checkImg(el,width,height){
    if(!el || !el.src) return ;
	var Img=new Image();
	var load_function= function(){
		Img.onload = null;
		if(!!this.__lastTry && (this.width==0 || this.height==0)){
			this.__lastTry=true;
			setTimeout(load_function,100); return;
		}
		if(this.width && this.height) {
			if(!width) if(el.style.width) width=parseInt(el.style.width);
			if(!height) if(el.style.height) height=parseInt(el.style.height);
			// looks like loaded!
			var k=1; // 1 - вписываем 0 - расширяем по габаритам
			if(width) k=Math.max(k,Img.width/width); else width=Img.width;
			if(height) k=Math.max(k,Img.height/height); else height=Img.height;
			if(!k) k=1;
	//		if (Img.width>width || Img.height>height){
				el.style.width=Math.round(Img.width/k)+'px';
				el.style.height=Math.round(Img.height/k)+'px';
	//		}
		}
		Img=null;
		load_function=null;
	}
	Img.onload=load_function;
	Img.src=el.src;
}/**
 * Установка выпадающего меню
 */
function menu(_self,param){
    if(!param) param={};
    else if(typeof(param)=='function')
    	param={show:param};
    if(!(_self=$(_self)[0])) return;	
    	
	function checkMouse (e){
	     var el = e.target;
	     while (true){
			if (el == _self) {
				return true;
			} else if (el == document) {
				hide_menu();
				return false;
			} else {
				el = el.parentNode;
			}
		}
	};
	
	function show_menu(){
	  if(param.show) param.show.apply(_self);
	  else $(_self).show();
	  _self.shown=true;
	  $(document).bind('mousedown', checkMouse);
	  return false;
	};
	
	function hide_menu(){
	  $(document).unbind('mousedown', checkMouse);	
	  if(param.hide)
	  	param.hide.apply(_self);
	  else
	  	$(_self).hide();
	  setTimeout(function(){_self.shown=false},500);
	  return false;
	};
	_self.show_menu=show_menu;
	_self.hide_menu=hide_menu;
	$(window).bind('unload', function(){_self=null});
};

function show_auth() {
	$('#auth table tr').show();
	$('#auth table tr.ugol td').css('background-color', '#FFFFFF');
	$('#auth table tr.ugol').css('border-right', 'solid 1px #d7dee9');
}

function hide_auth() {
	$('#auth table tr.out_hide').hide();
	$('#auth table tr.ugol td').css('background-color', 'transparent');
	$('#auth table tr.ugol').css('border-right', 'none');
}

function poisk() {
	if($('#search_string').attr('value') == 'Найти...')
		$('#search_string').attr('value', '');
}

function show_op(id) {
	$("#opisanie_" + id).slideToggle(400);
}

function dop_har(id) {
	$(".dop_har_" + id).toggle();
}

// поддержка подгрузки формы закащза
function submitform(el){
    // скрываем все, которые не подходят по форме
    $(el.form).find('.ruled').hide();
    $(el.form).find('.r'+el.value).show();
    //alert(el.value);
    return false;
}