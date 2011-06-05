var $must_save=false;

function need_Save(){
	$must_save=true ;
	element.allClass(document,'savebutton',function(e){
		e.disabled=false;
		//e.setAttribute('disabled',false)
	})
	//document.getElementById('save_btn').src = 'img/save.gif'
}

function trimStr(s){
    reg=/^\s+|^<br\s*\/?>|\s+$|<br\s*\/?>$/ig;
    while (s.match(reg)) s=s.replace(reg,'');
    return s;
}

function delrec(e,id){
	if(confirm('Хотите удалить запись?')){
		var x ;
		if(!id){
			x=e.parentNode;
			while(x && !id){
				if(x.tagName && (x.tagName.toLowerCase()=='tr'))
					id=x.id ;
				x = x.parentNode;
			}
		}
		x=e.form||e.parentNode;
		while(x && (x.tagName.toLowerCase()!='form')) x = x.parentNode;
		element.firstClass(x,'del').value=id;
		x.submit();
	};
}

function order(e,s){
	//alert(e.parentNode.parentNode);
	var v=element.firstClass(e.parentNode.parentNode,'order');
	//alert(v);
	var x = v.value.match(/\d+/);
	v.value=s+(x && x[0]||1);
	$must_save=false ;
	e.form.submit();
}

function order_h(s,id){
	if(s == '+') {
		element.Ajax.getJSON('?do=ajax_move','x=page&disp=1&obj=Cells&id=' + id,function(){
			__goto();
		});	
	}
	if(s == '-') {
		element.Ajax.getJSON('?do=ajax_move','x=page&disp='+encodeURIComponent('-1')+'&obj=Cells&id=' + id,function(){
			__goto();
		});	
	}
}


$x=null;$complete=true;$upload_results=null;
function do_menu(event,el) {
	if(!$x) return ;
	event=event||window.event;
	window.$curelement=el;
	if (event.button & 2) {
	    var inp=$x.getElementsByTagName('input'),il=inp.length,
			data=[1, 'ajax', 'get_file_list'];
		var test=[];	
		while(il--){
			if(inp[il].type=='text') {
				if (inp[il].name.match(/^pic_|text_/i))	
					data[2]='get_picture_list';
			} else if(inp[il].type=='button' ||inp[il].type=='submit')
				if(inp[il].value.match(/csv/i)) 
					data[2]='get_csv_list';
				else if (inp[il].value.match(/фото/i))	
					data[2]='get_picture_list';
		}
		 el.__menu=null;
		menuOverHandle.call(el, event, data);
	}	
	//debug.trace(e).alert();
	//ajax:get_file_list
}

function do_upload(f) {
	if(!$x) return ;
	try{
		var ff=$x, w;
		f=element.$('uploadForm');
		while(ff && ff.tagName.toLowerCase()!='form') ff=ff.parentNode ;
		f.xaction.value = '';
		if (element.cls('action_both').have($x)) {
			f.xaction.value = 'both';
		}
		else if (element.cls('action_small').have($x)) {
			f.xaction.value = 'small';
		}
		if (element.cls('users_avatar').have($x)) {
			f.xurl.value = 'avatar';
		}
		toggleWait();
	}catch(e){
		debug.trace(e).alert()	;
	}
	element.$('uploadFrame').onload=upload_OnComplete;
	element.$('uploadForm').submit();
	$complete=false;
}

function upload_OnComplete(){
	if(!$complete){
	    toggleWait(null,true);
		alert('Загрузка прервана!');
	}
	element.$('uploadForm').reset();
}

function upload_OnSuccess(data) {
 	$complete=true;
	if(data.error) alert(data.error);
 	if(data.debug) alert(data.debug);
    toggleWait(null,true);
 	//alert([data.data,data.error]);
	$upload_results=data.result||data;
    var inp=$x && $x.getElementsByTagName('input');
	if(!inp || !inp[0])
		inp=$x && $x.parentNode.getElementsByTagName('input');
	if (inp && inp[0]) {
		inp=inp[0];
		if (data.data.match(/javascript/i))
			inp.value='';
		else	
			inp.value = data.data;
		if (!!inp.onclick) {
			if (typeof(inp.onclick) == 'function') {
				//debug.trace('function');
				inp.onclick.apply(inp);
			}
			else {
				//debug.trace('eval');
				eval(inp.onclick);
			}
		}
		else 
			inp.click() ;
	}
    inp=$x && $x.parentNode.getElementsByTagName('img');
	if(!inp)
		inp=$x && $x.parentNode.parentNode.getElementsByTagName('img');
    if(inp && inp[0]){
       //var xx=inp[0].src;
    	inp[0].src=inp[0].src.replace(/\/uploaded.*$/,'/'+data.data);
    	inp[0].src=inp[0].src.replace(/\/avatar.*$/,'/'+data.data);
    	//alert([xx,inp[0].src]);
    	}
    inp=null;
 	need_Save();
}

function NewGalleryImg(el){
	var form01 = document.getElementById('uploadForm');
	//var qwert = form01.getChild('input');
	//form01.elements.item(0).value = '123'
	//alert(form01.elements.item(0).value);
	// запрос на сервер - добавить новую строку с картинками
	var ff = el,last_rl=el,data='';
	while(ff && (ff.tagName.toLowerCase()!='tr' || !ff.id)) ff=ff.parentNode ;

	if (!ff) return alert('oops!');

	element.Ajax.getJSON('?do=ajax_gallery_img&id='+ff.id, $upload_results, function(data){
		$must_save=false ;
		window.el_open(last_rl);
		__goto();
	})
	
}
/**
 * Обслуживание загрузки картинок. Ищем первые картинки и заменяем им чего-то...
 * @param {Object} this
 * @param {Object} false
 */
function ReplaceImg(el){
	var x = el.parentNode.parentNode.parentNode,
		inputs=x.getElementsByTagName('input'),
		n,ff=el;
	while(ff && ff.tagName.toLowerCase()!='form' && ff.tagName.toLowerCase()!='td') ff=ff.parentNode ;
	var i=inputs.length
	// scan for el element
	while(i--){
		if (n = inputs[i].getAttribute('name')) {
			if (n.match(/pic.*?small/)) {
				inputs[i].value = $upload_results.small||$upload_results.data;
				break;
			}
		}
	}
	i=inputs.length
	while(i--){
		if (n = inputs[i].getAttribute('name')) {
			if (n.match(/pic.*?big/)){
				inputs[i].value = $upload_results.big;
				break;
			}
		}
	}
	$must_save=false;
	//alert(inputs[0].value);
	inputs[0].form.submit();
}

var $htmleditor=null;
var $editcntr=null;

element.add_event(window,'load',function(){
	
/**
 *
 * Исче одно менюшко. Select-like
 * <xx class="menu_xxx">
 * 
 */	
 
function setup_menu(menu){
 	var x = element.$(menu), to = null;
 	if(!x) return;
	/**
	 * ставим обработчики на меню
	 */
	function hide_menu(e){
		/*
		var o = e.srcElement || e.target;
		while (o && o!=x) {o=o.parentNode;}
		if(o)return ;
		*/
		clearTimeout(to);
		to = setTimeout(function(){
			x.style.display = 'none'
		}, 100);
	}
	
	function show_menu(e){
		clearTimeout(to);
		to = setTimeout(function(){
			x.style.display = ''
		}, 100);
	}
	
	element.add_event(x, 'mouseout', hide_menu);
	element.add_event(x, 'mouseover', show_menu);
	
	var menu_elements=[];
	
	var xx=x.getElementsByTagName('a'),yy,menu_handle=function(e,y){
			if(x.__handle){
				var yyy=x.__handle.getElementsByTagName('input')[0],
					span=x.__handle.getElementsByTagName('span')[0]
				yyy.value=y;
				span.innerHTML=menu_elements[yyy.value]||' ';
				need_Save();
			}
			hide_menu();
			return element.clearEv(e);				
		};
	for(a in xx){
		//alert(xx[a].href.match(/#(.*)$/))
		if(xx[a].href && (yy=xx[a].href.match(/#(.*)$/))){
			menu_elements[yy[1]]=xx[a].innerHTML;
			xx[a].href='#';
			element.add_event(xx[a],'click',menu_handle.bindEH(element,yy[1]))
		}
	}
	
 	function xmenu(el){
	/**
	 * поставить  менюшный хэндл на элемент el.
	 * менём будет menu
	 */
		// установка необходимых стилей 
		el.style.position = 'relative';
		var inp=el.getElementsByTagName('input')[0],span=document.createElement('span');
		span.style.display="block";
		span.className="long wide";
		span=el.appendChild(span);
		span.innerHTML=(inp.value && menu_elements[inp.value])||'&nbsp;';
		inp.style.display='none';
		//el.create
		element.add_event(el, 'click', function(){
			x.__handle=el;
			if (x.parentNode != el) {
				element.clear_events(menu);
				x = el.appendChild(x);
			}
			element.set(menu)
				.add_event(el, 'mouseout', hide_menu)
				.set('');
			show_menu();
		})
	}
	// установка
	element.allClass(document, menu, function(el){
		new xmenu(el);
	});
}
setup_menu('xrights');	
setup_menu('xuser');
setup_menu('xoptions');
    
/* <% insert_point('js_admin');%>
*/

		
if(window.setup_menu_plus) {
	var x= window.setup_menu_plus.length;
	while(x--){
		setup_menu(window.setup_menu_plus[x]);
	}
}	
/**
 * Дальше!!!
 */
	window.onbeforeunload=function(){
		if($must_save)
		return 'Данные не сохранены !!';
	}

	// лики прочищаем...
	element.add_event(window,'unload',function(){
		$x=null; // чистим за загрузчиком
		if($htmleditor)
			$htmleditor.removeInstance('area1');// чистим за редактором
		$htmleditor=null;
		$editcntr=null;// --//--
	});

	element.css(element.$('shaddow'),{opacity:0.4});

	function do_edit(cntr){
    	$editcntr=cntr;
    	toggleSelect();
    	element.$('shaddow').style.display='block';
    	element.$('html_Editor').style.display='block';
    	if(!$htmleditor){
			element.$('area1').value=$editcntr.value;
	 		$htmleditor=new nicEditor({iconsPath : 'img/nicEditorIcons.gif',fullPanel : true}).panelInstance('area1');
 		} else {
	   		var $editor=$htmleditor.instanceById('area1');
 			$editor.setContent($editcntr.value);
 		}
 		$htmleditor.instanceById('area1').elm.focus();
  	}
// контрол - загрузчик файлов
	element.css(element.$('uploader'),{opacity:0.02});
	
	function init_uploader(e){
	var self=e;
	element.add_event(e,'mousemove',function(e){
		var posx=e.clientX;
		if($complete) {
		  	var qwert = this;
			for(var i=0; i<1000; i++) {
				qwert = qwert.parentNode;
				if (qwert.id != undefined && qwert.id !='' && qwert.className.indexOf('vnutr') < 0)	{
					//alert(qwert.id);
					//alert(qwert.className.indexOf('vnutr'));
					break;	
				}
			}
			
			pid = qwert.id.replace('pg_','');
			//alert(pid);
			//alert(document.getElementById('menuedit').elements.item(2).name);
			var menuedit = document.getElementById('menuedit');
			if(menuedit) {
				var elm = menuedit.elements;
				i = 0;
				while(i < elm.length) {
					if(elm.item(i).name == 'w_s_'+ pid)
						document.getElementById('uploadForm').xwidth.value = elm.item(i).value;
					if(elm.item(i).name == 'h_s_'+ pid)
						document.getElementById('uploadForm').xheight.value = elm.item(i).value;
					if(elm.item(i).name == 'w_b_'+ pid)
						document.getElementById('uploadForm').xxwidth.value = elm.item(i).value;
					if(elm.item(i).name == 'h_b_'+ pid)
						document.getElementById('uploadForm').xxheight.value = elm.item(i).value;
					i++;
				}
			}
			//alert(document.getElementById('menuedit').elements.item(0).value);
			//alert(this.parentNode.parentNode.parentNode.parentNode);
			element.$('uploader').style.display='block';
			$x=this;
			
			var p=element.getBounds(this);
			var container=element.$('container');
			var ff=self;
			while(ff && (!ff.id || ff.id!='container')) ff=ff.parentNode;
	
			if(ff){
				p.left-=container.scrollLeft,
				p.top-=container.scrollTop
			}

			if(posx && (posx>p.left))
				element.css(element.$('uploader'),{left:posx-5,top:p.top,height:p.height});
			else
				element.css(element.$('uploader'),{left:p.left,top:p.top,height:p.height});
		}
	})}
$init_uploader=init_uploader;

    element.allClass(document,'uploader',function(e){
    	init_uploader(e);
	})

	function reorder(order){
		var i = 0 ;
		element.allClass(document,order,function(el){
			el.value=i++;
		});
	}

	function moverow(row,up){
		var i=row.parentNode,j;
		if(up==0) return;
		while(i &&  i.tagName.toLowerCase()!='tr') i=i.parentNode;
		if(!i) return ;j=i;
		if(up>0){
			do {
				j = j.nextSibling;
			}
			while (j && (!j.tagName || j.tagName.toLowerCase() != 'tr'));
			if(!j) return;
			i.parentNode.insertBefore(j,i);
			up--;
		} else {
			do {
				j = j.previousSibling;
			}while (j && (!j.tagName || j.tagName.toLowerCase() != 'tr'));
			if(!j || !j.id) return;
			j.parentNode.insertBefore(i,j);
			up++;
		}
		if(up!=0) moverow(row,up);
	}
	var t;

	function win_order(cntr){
		var input=document.getElementById('order_tpl').cloneNode(true);
		input.removeAttribute('id');
		var _order, i=cntr.getElementsByTagName('input');
		if (i && i[0]) {
			_order=i[0];
			_order.style.display = 'none';
		}
		else
			return ;
		input=cntr.appendChild(input);
		i=input.getElementsByTagName('input');

		// setup handlers
		element.add_event(i[0],'click', function(){
			// move row down
			var v=parseInt(i[1].value);i[1].value='';
			moverow(cntr,v||1);
			reorder(_order.className);
			need_Save();
		});
		element.add_event(i[2],'click', function(){			// move row up
			var v=parseInt(i[1].value);i[1].value='';
			moverow(cntr,v?-v:-1);
			reorder(_order.className);
			need_Save();
		});
		t= _order.className;
	}
// установка
	element.allClass(document,'win_order',function(el){
		new win_order(el)
	});
	reorder(t);

// контрол - чекбокс - 0/1
	function win_check(cntr){
		var input=document.getElementById('check_tpl').cloneNode(true);
		input.removeAttribute('id');
		cntr.style.display = 'none';
		input=cntr.parentNode.appendChild(input);
		if (cntr.value && cntr.value != "0") {
			input.setAttribute('checked', 'checked');
		}	
		
		// setup handlers
		element.add_event(input,'click', function(){
			if(input.checked)
				cntr.value=1;
			else	
				cntr.value=0;
			need_Save();
		});
		//t= _check.className;
	}
// установка
	element.allClass(document,'win_check',function(el){
		new win_check(el)
	});
// контрол - меню
	function showtooltip(el,tooltip,down){
		tooltip.style.vizibility='hidden';
		element.css(tooltip, {
			left: 0,
			top: 0
		});
		tooltip.style.display='block';
		var p=element.getBounds(el),pp=element.getBounds(tooltip),start;
		var container=element.$('container');
		var ff=el;
		while(ff && (!ff.id || ff.id!='container')) ff=ff.parentNode;

		if (down) { // вставляемся в реальный элемент контейнера
			if(ff)
				start={left: p.left-container.scrollLeft,
						top: p.top + el.offsetHeight-container.scrollTop
					};
			else
				start={left: p.left,
						top: p.top + el.offsetHeight
					};
		} else {
			start={
				left: p.left + el.offsetWidth - 5,//-container.scrollLeft,
				top: p.top //-container.scrollTop
			}
		}
		if (start.top + pp.height > document.body.clientHeight) {
			start.top = document.body.clientHeight - pp.height;
			if (start.top<50){
				tooltip.style.overflow='auto';
				tooltip.style.height=(document.body.clientHeight-50)+'px';
				start.top=50;
			}
		}
		if (start.left + pp.width > document.body.clientWidth)
				start.left = document.body.clientWidth - pp.width;

		element.css(tooltip, {
			left: start.left,
			top: start.top
		});

		element.set('tooltip').add_event(document.body,'mousedown',function(e){
			var el = e.srcElement || e.target,tgt;
			while(el) {
				if ((el.id && el.id== 'link_toolbox')||(el.style && el.style.zIndex==1001))
					break;
				if(el.tagName && el.tagName.toLowerCase()=='a') tgt=el;
				el=el.parentNode;
			}
			if(!el){
				tooltip.style.display='none';
				element.clear_events('tooltip');
			}
		}).set();
	}

	function menuHide(e){
		if(e.__parent){
			if (e.__parent.__shown) e.__parent.__shown.style.display='none';
			e.__parent.__shown=e.__menu;
		}
    }
	function menuShow(e){
		menuHide(e);

		showtooltip(e,e.__menu);
    }

	function menuOverHandle(e,x){
		if (this.__menu)
			menuShow(this)
		else {
			var el=this;
			element.Ajax.getJSON('?do=ajax_'+x[2], null, function(data){
				var m=document.getElementById('menu_tpl').cloneNode(false);
				m.removeAttribute('id');
				m.innerHTML=data.data;
				m=document.body.appendChild(m);
				element.add_event(m,'click',function(e){
					if(e.button && e.button!=1) return ;
					var el = e.srcElement || e.target;
					while(el &&( !el.tagName || el.tagName.toLowerCase()!='a'))
						el=el.parentNode;
					if(el)
						if(typeof(window.$curelement)!='undefined' && !!window.$curelement) {
							//alert(1);
							need_Save();//debug.trace($curelement.value).alert();
							if(window.$curelement.type=='file'){ // upload complete
								var t=el.href.match(/http:\/\/[^\/]+(.*)$/i);
							    var t =(t && t[1])||'';//||el.href;
								upload_OnSuccess({data:t,result:{small:t,big:t}});
								setTimeout(function(){window.$curelement.__menu.style.display='none';},500);
							} else
							if (el.href.match(/javascript/i))
								window.$curelement.value='';
							else if(el.href.match(/\?do/))
								window.$curelement.value = el.href.match(/\?.*$/)[0];
							else	
								window.$curelement.value = el.href;
						}
						else
							alert(el.href)
					return element.clearEv(e);
				})
				el.__menu=m;
				menuShow(el);
			})
		}
		return element.clearEv(e);
	}
	
	window.menuOverHandle=menuOverHandle;

	function m_menu(id){
		// формируем меню
		var $menu=element.$(id);
		element.scanDomTree($menu,function(el){
			if(el.tagName && el.tagName.toLowerCase()=='a'){
				if(x = el.href.match(/#(.*?):(.*?)(:(.*?))?$/)){
					el.__parent=$menu;
					element.add_event(el,'mouseover', menuOverHandle.bindEH(el,x))
				}
			}
		},1)
		this.display=function(el){
			clearTimeout($menu.__timeout);
			window.$curelement=el;
			showtooltip(el,$menu,true);
		}
		this.hide=function(){
			$menu.__timeout=setTimeout(function(){
				if ($menu.__shown) $menu.__shown.style.display='none';
				$menu.style.display='none';
				window.$curelement=null;
			},200);
		}
	}

	var links=new m_menu('link_toolbox');
$init_link=init_links; // global function
	function init_links(el){
		var tag=el.tagName && el.tagName.toLowerCase()||'';
		if ('input'==tag || 'textarea'==tag){
		element.add_event(el,'focus', function(){			
			links.display(this);
		})
		.add_event(el,'blur', function(){			
			links.hide(this);
		})
		.add_event(el,'keydown', function(e){
			if (e.keyCode == 27) {
				links.hide(this);
			}
		})
		}
	}

// установка
	element.allClass(document,'link_toolbox',init_links);

// котрол - html-редактор большого текстового поля
	function html_control(cntr){
		var input,span;
		cntr.onclick=function(){
			if (!input) {
				input=document.getElementById('htmlcntr_tpl').cloneNode(false);
				input.removeAttribute('id');
				input.setAttribute('name',this.id);
				// get contents from Ajax
				input.value=cntr.innerHTML;
				$editcntr=input;
				// наворачиваем Ajax
				var parent=this.parentNode;
				while(parent && !parent.id) parent=parent.parentNode;
				if (parent && parent.id && parent.id.match(/^\w+_\d+$/)) {
					element.Ajax.getJSON('?do=ajax_get_contents&var=' + this.id + '&id=' + parent.id, null, function(data){
						data.data = data.data.substr(1);
						$editcntr.value = data.data;
						do_edit($editcntr);
					})
				} else {
					do_edit($editcntr);
				}
				parent=null;
				span=document.createElement('div');
				span.innerHTML=cntr.innerHTML;
				cntr.innerHTML='';
				input=cntr.appendChild(input);
				span=cntr.appendChild(span);
			} else {
				$editcntr=input;
				do_edit($editcntr);
			}
			$span=span ;span=null;
		}
		element.add_event(window,'unload',function(){
			input=null;span=null;cntr=null;$span=null;
		})
	}
// установка
	element.allClass(document,'html_edit',function(el){
		new html_control(el)
	});
// котрол - простой редактор короткого текстового поля
	function txt_control(cntr){
		var input,span;
		cntr.onclick=function(e){
			e=e||window.event;
			if(e && e.button && e.button!=1) return ;

			if (!input) {
				input=document.getElementById('textcntr_tpl').cloneNode(false);
				input.removeAttribute('id');
				input.setAttribute('name',this.id);
				input.value=cntr.innerHTML.replace('&amp;','&');
				span=document.createElement('div');
				span.innerHTML=cntr.innerHTML;
				// calc padding
				var st=cntr.currentStyle
						?cntr.currentStyle
						:document.defaultView.getComputedStyle(cntr, null),
					css={
						paddingTop:parseInt(st.paddingTop)
						,paddingLeft:parseInt(st.paddingLeft)
						,paddingRight:parseInt(st.paddingRight)
						,paddingBottom:parseInt(st.paddingBottom)
					};
				element.css(cntr,{paddingTop:0,paddingLeft:0,paddingRight:0,paddingRight:0,paddingBottom:0});
				//debug.trace(css).alert();
				cntr.innerHTML='';
				input.style.display='none';
				span=cntr.appendChild(span);
				input=cntr.appendChild(input);
				//span=cntr.appendChild(span);
				element.css(input,css);
				element.css(span,css);
				input.onblur=function(){
					span.innerHTML=input.value;//.replace('>','&gt;').replace('<','&lt;'); // XXX: порнушко!!!
					input.style.display='none';
					span.style.display='block';
					setTimeout(function(){
						span.innerHTML=input
							.value;//.replace('>','&gt;').replace('<','&lt;'); // XXX: порнушко!!!
					},300)
				}
			}
			if(!element.cls('long').have(input))
				input.style.width=Math.max(40,cntr.offsetWidth)+'px';
			if(!element.cls('wide').have(input))
				input.style.height=cntr.offsetHeight+'px';
			var wid = cntr.offsetWidth;
			span.style.display='none';
			var focused=input.style.display=='block';
			input.style.display='block';
			cntr.style.width=wid;
			if(element.cls('link_toolbox').have(cntr)){
				init_links(input);
			} else if(input.value.match('<[^>]*>') || (e && e.ctrlKey)){
				return do_edit(input);
			}
			input.focus();
			if (!focused) {
				// select all
				if ('function' == typeof input.setSelectionRange) {
					//debug.trace(input.value.length)
					try {
						input.setSelectionRange(0, input.value.length)
					}
					catch (e) {
						debug.trace(e).wait();
					}
				//debug.wait();
				}
				else {
					/*
		 *  for IE
		 */
					var range;
					/*
		 *  just try to create a range....
		 */
					try {
						range = input.createTextRange();
					}
					catch (e) {
						try {
							range = document.body.createTextRange();
							range.moveToElementText(input);
						}
						catch (e) {
							return false;
						}
					}
					range.collapse(true);
					range.moveStart("character", 0);
					range.moveEnd("character", input.value.length);
					range.select();
				}
			}
			//debug.trace(e).alert();
			//alert(input.value);
		}
		element.add_event(window,'unload',function(){
			input=null;span=null;cntr=null;
		})

	}
// установка
	element.allClass(document,'text_edit',function(el){
		new txt_control(el)
	});

// контрол - выравнивание. Класс - cm win_align в .css
	var NoCenter=element.cls('nocenter');
	function win_align(cntr){
		var input=document.getElementById('wincntr_tpl').cloneNode(false);
		input.removeAttribute('id');
		input=cntr.parentNode.appendChild(input);
		input.onclick=function(){
	   		var v = Number(cntr.value)||0;
	   		cntr.value=(v=(v==2?0:v+1));
			if ((cntr.value==1) && NoCenter.have(cntr))
		   		cntr.value=(v==2?0:v+1);
	   		setAlign(this);
	   		need_Save();
		}
	   	function setAlign(e){
	   		input.style.backgroundPosition=win_align.aligns[Number(cntr.value)];
	   	}
		setAlign(this);
		element.add_event(window,'unload',function(){
			input=null;cntr=null;
		})
	}
	win_align.aligns=
		[	'-162px 0px', // left
	   		'-72px 0px',
	   		'-234px 0px'
	   	] ;// right

// установка
	element.allClass(document,'align',function(el){
		new win_align(el)
	});

// прочистка мозгов у кнопок submit
	var inputs=document.getElementsByTagName('input'),
		i=inputs.length;
	while(i--){
		if(inputs[i].getAttribute('type')=='submit'){
			inputs[i].onclick=function(){$must_save=false;}
		}
	}
// контрол - табулятор
	function Tabs(cntr){
    	var  tabs_a=cntr.getElementsByTagName('a')
    		,a_cnt=tabs_a.length
    		,tabs=[]
    		,cls=element.cls('tabs-selected')
    		,tcls=element.cls('tabs')
    		,current=null;
    // поиск табов
    	while(a_cnt--){
    		var li=tabs_a[a_cnt].parentNode;
    		while((li.tagName.toLowerCase()!='li')&&(!tcls.have(li))) li=li.parentNode;
    		var tab={a:tabs_a[a_cnt],li:li,id:tabs_a[a_cnt].href.replace(/^.*#/,'')};
			tabs.push(tab);
			if(cls.have(li)){
				current=tab;
				cls.remove(li);
			}
			element.$(tab.id).style.display='none';
			li=null;tab=null;
    	}
    	tabs_a=null;
    // установка
    	if(!current)current=tabs[0];
    	cls.add(current.li);
    	element.$(current.id).style.display='block';
    // обработчики событий
    	a_cnt=tabs.length
    	while(a_cnt--){
    		tabs[a_cnt].a.onclick=function(){return false;}
    		element.add_event(tabs[a_cnt].a,'click',function(e){
    			if(current!=this){
    				element.cookie('curtab',this.a.href.toString().match(/.$/)[0]);
	    			element.$(current.id).style.display='none';
	    			cls.remove(current.li);
	    			current=this;
			    	cls.add(current.li);
			    	element.$(current.id).style.display='block';
		    	}
    			return false;
    		}.bindEH(tabs[a_cnt]))
    	}
		element.add_event(window,'unload',function(){
			current=null;tabs=null;
		})
	}
// установка
	element.allClass(document,'tabs-nav',function(el){
		new Tabs(el);
	});

	function go_next(e,What){
		if (!e) return null;
		do{
			e=e[What+'Sibling'];
		} while (e &&!e.tagName);
		return e;
	}
// передвижение по таблице как по гриду
	element.allClass(document,'table',function(el){
		element.add_event(el,'keydown',function(e){
			var src=e.target || e.srcElement,shift=e.shiftKey,xsrc=src;
			switch(e.keyCode){
				case 40: case 38: // key down pressed
					while(!src.tagName.match(/^t[d|h]$/i)) src=src.parentNode;
					// count number of prev elements
					var i = 0,y;
					while(src.previousSibling){ src=src.previousSibling; i++ }
					while(src && (!src.tagName || src.tagName.toLowerCase()!='tr')) src=src.parentNode;
					if (e.keyCode==40) y='next' ;else y='previous';
					src=go_next(src,y); src=src && src.firstChild;
					while(src && i--) src=src.nextSibling;
					if (src && src.onclick) {
						if(xsrc.onblur) xsrc.onblur();
						src.onclick();
					}
					return false ;
				//case 37:
				//	shift=true;
				case 9: //case 39: // tab pressed
					while(!src.tagName.match(/^t[d|h]$/i)) src=src.parentNode;
					if (shift)
						// select next - move right
						src=go_next(src,'previous');
					else {
						// select next - move right
						src=go_next(src,'next');
					}
					if (src && src.onclick) {
						if(xsrc.onblur) xsrc.onblur();
						src.onclick();
					}
					return element.clearEv(e);
				case 16:
					// just a shift
					return true;
				default:
		//			alert(e.keyCode);
			}
		})
	});
// конвертирование инпутов в чекбокс
	element.allClass(document,'check_0_15',function(cntr){
		var input=document.getElementById('check_0_15').cloneNode(true);
		input.removeAttribute('id');
		cntr.style.display="none";
		input=cntr.parentNode.insertBefore(input,cntr);

		if(cntr.value==0){
			input.checked=true
		}

		element.add_event(input,'click',function(){
			cntr.value=this.checked?0:15;
		})
	})
// **********************************************************
// контекстное меню!!!!!!!!!

	function contextAction(act,p1,p2,tgt){
		p2=p2? p2='&obj='+encodeURIComponent(p2):'';
		switch (act){
			case 'itemMoveUp':
				alert('x=page&disp='+encodeURIComponent('-1')+p2+'&id=' +p1);
				if(p1)
					element.Ajax.getJSON('?do=ajax_move','x=page&disp='+encodeURIComponent('-1')+p2+'&id=' +p1,function(){
						__goto();
					});
				break;
			case 'itemMoveDn':
				if(p1)
					element.Ajax.getJSON('?do=ajax_move','x=page&disp=1'+p2+'&id=' +p1,function(){
						__goto();
					});
				break;
			case 'menuMoveUp':
				if(p1)
					element.Ajax.getJSON('?do=ajax_move','x=menu&disp=-1&id=' +p1,function(){
						__goto();
					});
				break;
			case 'menuTest1':
				if(p1)
					element.Ajax.getJSON('?do=ajax_test1','x=menu&id=' +p1,function(){
						__goto();
					});
				break;
			case 'menuTest2':
				if(p1)
					element.Ajax.getJSON('?do=ajax_test2','x=menu&id=' +p1,function(){
						__goto();
					});
				break;
			case 'menuShowMenu':
				if(p1)
					element.Ajax.getJSON('?do=ajax_hide','x=menu&disp=0&id=' +p1,function(){
						__goto();
					});
				break;
			case 'menuHideMenu':
				if(p1)
					element.Ajax.getJSON('?do=ajax_hide','x=menu&disp=1&id=' +p1,function(){
						__goto();
					});
				break;
			case 'menuCopyToCTA':
				if(p1)
					element.Ajax.getJSON('?do=ajax_copyCTA','x=menu&disp=1&id=' +p1,function(data){
						element.$('debug').innerHTML=data.error ||data.debug;
						alert(data.data);
					});
				break;
			case 'menuMoveDn':
				if(p1)
					element.Ajax.getJSON('?do=ajax_move','x=menu&disp=1&id=' +p1,function(){
						__goto();
					});
				break;
			case 'copyMenuItem':
				if(p1)
					element.Ajax.getJSON('?do=ajax_copyItem','x=menu&id=' +p1);
				break;
			case 'copyItem':
				if(p1)
					element.Ajax.getJSON('?do=ajax_copyItem','x=page&id=' +p1);
				break;
			case 'cutMenuItem':
				if (p1)
					if (confirm('Раздел будет удален. Вы уверены?')) {
						element.Ajax.getJSON('?do=ajax_copyItem','x=menu&id=' +p1,function(){
							element.Ajax.getJSON('?do=ajax_delItem', 'x=menu&id=' + p1, function(){
								__goto();
							});
						});
					}
				break;
			case 'cutItem':
				if (p1)
					if (confirm('Элемент будет удален. Вы уверены?')) {
						element.Ajax.getJSON('?do=ajax_copyItem','x=page&id=' +p1,function(){
							element.Ajax.getJSON('?do=ajax_delItem', 'x=page&id=' + p1, function(){
								__goto();
							});
						});
					}
				break;
			case 'deleteMenuItem':
				if(p1)
					if(confirm('Раздел будет удален. Вы уверены?'))
						element.Ajax.getJSON('?do=ajax_delItem','x=menu&id=' +p1,function(){
							__goto();
						});
				break;
			case 'deleteItem':
				if (p1)
					if (confirm('Элемент будет удален. Вы уверены?')) {
						element.Ajax.getJSON('?do=ajax_delItem', 'x=page&id=' + p1+p2, function(){
							__goto();
						});
					}
				break;
			case 'pasteMenuItem':
				if(p1)
					element.Ajax.getJSON('?do=ajax_pasteItem','x=menu&id=' +p1,function(data){
						//element.$('debug').innerHTML=data.error ||data.debug;
						__goto();
					});
				break;
			case 'pasteItem':
				if(p1)
					element.Ajax.getJSON('?do=ajax_pasteItem','x=page&id=' +p1,function(data){
						element.$('debug').innerHTML=data.error ||data.debug;
						//alert(data.data);
						__goto();
					});
				break;
			case 'renameMenuItem':
				if(p1){
					var s = prompt("Введите новое имя раздела", tgt.text||"")
					if(s!=null)
						element.Ajax.getJSON('?do=ajax_renameItem','x=menu&id=' +p1+'&name='+encodeURIComponent(s),function(data){
							__goto();
						});
				}
				break;
			case 'SelectMenuItem':	
				if(p1){
					element.Ajax.getJSON('?do=ajax_SelectMenuItem','x=menu&id=' +p1,function(data){
						__goto();
					});
				}
				break;
			case 'newMenuItem':
				if(p1){
					var s = prompt("Введите имя раздела", "")
					if(s!=null)
						element.Ajax.getJSON('?do=ajax_newItem','x=menu&id=' +p1+'&name='+encodeURIComponent(s),function(data){
							__goto();
						});
				}
				break;
			case 'newItem':
				if(p1){
					element.Ajax.getJSON('?do=ajax_newItem','x=page&id=' +p1+p2,function(){
						__goto();
					});
				}
				break;
			default:
				alert(act);
		}
		//debug.trace(act,p1,p2).alert();
	}

	function $context(name, value,el){
		if(typeof(value)=='undefined' || value==null){
			if (!$context.content[name])
				return null;
			else {
				if (!$context.content[name])
					return null;
				if (!$context.content[name].txt)
					return null;
				if (typeof($context.content[name].txt) == 'string')
					return $context.content[name].txt;
				else
					return $context.content[name].txt(el);
			}
		} else {
			if (name instanceof Array) {
				var i=name.length;
				while(i--) $context(name[i],value);
			}
			else {
				$context.content[name] = {
					txt: value
				};
			}
		}
	}
	$context.content = {
		
		'main_menu': {
			txt: function(el){
				//if(!el && !el.innerHTML) return ' ';
				var h='',x=el && el.href && el.href.match(/menu&id=(\w+)/)||'';
				if (x[1]) {
					x = ':' + x[1];
					var Sel=element.cls('selected'),yyy='';
					
					if (Sel.have(el.parentNode)) {
						yyy='class="selected"'
					}	
					h='<div style="background:#dddddd;"'+yyy+'>'+
						'<a href="#SelectMenuItem'+x+'" title="отметить пункт меню" onclick="return false;">'+
						'раздел: '+el.innerHTML+'</a></div>';
				} else {
					return ' ';
				}
				var hmenu=element.cls('hmenu');
				//debug.trace(el && el.tagName ||'xxx',h).wait();
				//if(h)
				var xMenu = element.$('xxMenu');
				if (xMenu) xMenu=xMenu.innerHTML.replace('%ID%',x);
				
				return h+'<a href="#cutMenuItem'+x+'" onclick="return false;">Вырезать раздел</a>' +
					'<a href="#copyMenuItem'+x+'" onclick="return false;">Копировать раздел</a>' +
					'<a href="#pasteMenuItem'+x+'" onclick="return false;">Вставить раздел</a>'+
					'<hr>'+
					'<a href="#newMenuItem'+x+'" onclick="return false;">Новый раздел</a>'+
					'<a href="#renameMenuItem'+x+'" onclick="return false;">Переименовать раздел</a>'+
					'<a href="#deleteMenuItem'+x+'" onclick="return false;">Удалить раздел</a>'+
					'<hr>'+
					'<a href="#menuMoveUp'+x+'" onclick="return false;">Переместить вверх</a>'+
					'<a href="#menuMoveDn'+x+'" onclick="return false;">Переместить вниз</a>' +
					'<hr>'+
					(hmenu.have(el)?
					'<a href="#menuShowMenu'+x+'" onclick="return false;">Показать пункт</a>'
					:
					'<a href="#menuHideMenu'+x+'" onclick="return false;">Скрыть пункт</a>')
					+(xMenu||'')
					 +
					(location.search.match(/[\?&]adv=.*/)?
					'<hr>'+
					'<a href="#menuTest1'+x+'" onclick="return false;">Сгенерировать 12 разделов</a>'+
					'<a href="#menuTest2'+x+'" onclick="return false;">Заполнить тестовыми данными</a>' 
					:'')
				//else return '';
			}
		}
	}
	$context(['contextmenu','container','fragment-2','fragment-1'],' ');

	function showMenu(x,y,data,el){
		element.clearEv('contextmenu');
		var menu=element.$('contextmenu');

		var hdata=[],d;
		if(d=$context('header'))hdata.push(d);
		var i = data.length;
		while(i--)
			if(d=$context(data[i],null,el)) 
				if(d!=' ')hdata.push(d);

		menu.innerHTML=hdata.join('<hr>');
		if(!menu.innerHTML) return false;

		var	start={
				left: x,
				top: y
			}
		var container=element.$('container'),
			pp=element.getBounds(menu);
		if(pp.width && pp.height){
			if (start.top+ pp.height > container.scrollHeight)
				start.top=container.scrollHeight- pp.height;
			if (start.left+ pp.width > container.scrollWidth)
				start.left=container.scrollWidth- pp.width;
//				debug.trace(pp).trace(start).alert();
		}
		menu.style.display='block';
		element.css(menu, {
			left: start.left,
			top: start.top
		});
		menu.focus();
		// ставим клик на весь докумеnt
		element.set('contextmenu').add_event(element.$('container'),'click',function(e){
			var elm = e.srcElement || e.target,tgt,x;
			while(elm) {
				if (elm.id && elm.id== 'contextmenu')
					break;
				if(elm.tagName && elm.tagName.toLowerCase()=='a')
					 tgt=elm;
				elm=elm.parentNode;
			}
			if(elm){
				if (tgt && tgt.href) {
					par=tgt.href.match('#([^:]+)(:([^:]+))?(:([^:]+))?$');
					//debug.trace(par).alert();
					//alert(el);
					contextAction(par[1],par[3],par[5],el);
				}
			}
			menu.style.display='none';
			element.clear_events('contextmenu');
		}).set();

		return true;
	}

// контекстное меню!!!!!!!!!
/*    element.add_event(element.$('contextmenu'),'click',function(e){
		this.style.display='none';
	})*/
	var NoContext=element.cls('nocontext');
	var HaveContext=element.cls('context');
    element.add_event(document.body,'contextmenu',function(e){
		if(e.ctrlKey) return;
		var elstart=e.srcElement || e.target, el = elstart,data_menu=[],fireajax=false,x;
		var haveContent=false;
		while(el) {
			if (NoContext.have(el)) {
				if(HaveContext.have(el))
					return ;
				else		
					return element.clearEv(e);
			}
			if(HaveContext.have(el)){
				haveContent=true;
				data_menu.push(el.id);
				if (!$context(el.id)) {
					fireajax=true;
				}
			}
			el=el.parentNode;
		}
		if(!haveContent) return;
		var xx=e.clientX+element.$('container').scrollLeft
			,yy=e.clientY+element.$('container').scrollTop;
		if (data_menu.length) {
			if (fireajax) {
				element.Ajax.getJSON('?do=ajax_contextmenu', 'x[]=' + data_menu.join('&x[]='), function(data){
					$context('header', data.data);
					if (data.result)
						for (var i in data.result) {
							$context(i, data.result[i]);
						}
					showMenu(xx, yy, data_menu, elstart);
				});
				return element.clearEv(e);
			}
			else {
				if (showMenu(xx, yy, data_menu, elstart))
					return element.clearEv(e);

			}
		}
	})

// контрол - закрывающая кнопка
	var Closed=element.cls('closed');
	var closedState=element.cookie('closedState')
	if(closedState){
		closedState=element.Ajax.unserialize(closedState);
	} else {
		closedState={}
	}

	window.el_open=function(el,state){ // обеспечить открытие этой строки
	    while(el.tagName.toLowerCase()!='tr') el=el.parentNode;
	    element.allClass(el,'open_close',function(el){
			closedState[el.__id]=state||0;			
		});
	}

	element.add_event(window,'beforeunload',function(){
	//	alert(1);
		date = new Date();
		date.setTime(date.getTime() + 30000); // + 30 seconds?
		//debug.trace(closedState).alert();
		element.cookie('closedState',element.Ajax.serialize(closedState),{expires:date});
	})

	function doClose(el){
		var first = true,d ;
		if(Closed.have(el)){ // open all
			Closed.remove(el);d=''
		} else {
			Closed.add(el);d='none'
		}
		element.scanDomTree(el.parentNode.parentNode,function(e){
			if(e.tagName && e.tagName.toLowerCase()=='tr'){
				if(first)
					first = false ;
				else
					e.style.display=d;
			}
		},3)
	}

	function openClose(el){
		var x=el,xx;
		while(x && (!x.id || !(xx=x.id.match(/^.._(\d+)$/)))) x=x.parentNode;
		if(xx){
			el.__id=xx[1];
			if (closedState )
				if(((closedState[el.__id]==1) && !Closed.have(el))
				   ||((closedState[el.__id]==0) && Closed.have(el)))
					doClose(el);
		}
		element.add_event(el,'click',function(){
			doClose(this)
			if(this.__id)
				closedState[this.__id]=Closed.have(el)?1:0;
		})
	}

// установка
	element.allClass(document,'open_close',function(el){
		new openClose(el);
	});
	
	
	

var myNicEditor = new nicEditor();

//myNicEditor.setPanel('myNicPanel');

	element.allClass(document,'clipboard',function(el){
		var area;
		element.add_event(el.parentNode,'mouseover',function(e){
			if (!area) {
				area = myNicEditor.addInstance(el);
				area.instanceById(el).selected(e, el);
				element.set('nicxxx').add_event(document.body, 'mousemove', function(e){
					if (!area) return;
					var elm = e.srcElement || e.target, tgt;
					while (elm && elm != el.parentNode) {
						elm = elm.parentNode;
					}
					if (!elm) {
						area.removeInstance(el);
						area = null;
						element.clear_events('nicxxx');
						var v=el.value ; el.value='';
						//alert(v);
						v=trimStr(CleanWord(v, true, true));

						// Определение типа браузера
						var type_br = false, br_version = false;
						//alert(navigator.userAgent);
						//Если это Гугл Хром
						if (navigator.userAgent.toLowerCase().indexOf('chrome') > -1) {
						       type_br = 'chrome';
						       br_version = navigator.userAgent.replace(/^.*Chrome\/([\d\.]+).*$/i, '$1')
						}
						//Если это Фаерфокс
						if (navigator.userAgent.toLowerCase().indexOf('firefox') > -1) {
						       type_br = 'firefox';
						}
						
						//Если это Интернет Эксплорер
						if (navigator.userAgent.toLowerCase().indexOf('msie') > -1) {
						       type_br = 'ie';
						}
						//Если это Интернет Опера
						if (navigator.userAgent.toLowerCase().indexOf('opera') > -1) {
						       type_br = 'opera';
						}
						
						if(type_br == 'ie') {
							// Установка кавычек на значения атрибутов для IE
							var atr = v.match(/[^\s]+=[^\s>]+/ig);
							var atr_s = '';
							var atr_n = '';
							if(atr)
								for(i=0; i<atr.length; i++) {
									re = /.+=\".+\"/i;
									if(re.test(atr[i]) == false) {
										atr_s = atr[i];
										atr_n = atr[i].replace('=', '="') + '"';;
										v = v.split(atr_s).join(atr_n);
									}
								}
						}

						var per = false;

						if (v.match(/<table/i)) {
							per = true;
						}
						else if (type_br == 'chrome' &&  v.match(/<col.*>(.|\n)*<tr.*/i)) {
							v = '<table>' + v + '</table>';
							per = true;
						}
						else if (type_br == 'opera' &&  v.match(/<tr.*/i)) {
							v = '<table>' + v + '</table>';
							per = true;
						}
						
						if (per) {
		element.Ajax.getJSON('?do=ajax_clear_text', 'txt=' + encodeURIComponent(v), function(data){
			var x = el,y,p1;
			while(x && (!x.id || !x.id.match(/pg_(\d+)/))) x = x.parentNode;
			if(x)
				if (y=x.id.match(/pg_(\d+)/)){
					p1=y[1];	
				}
			if(p1 && confirm('Заменить таблицу?')){
			   	window.el_open(x);
							
				// замещаем таблицу
				element.Ajax.getJSON('?do=ajax_pasteItem','x=page&id=' +p1,function(data){
				element.Ajax.getJSON('?do=ajax_delItem', 'x=page&id=' + p1, function(){
						   __goto();
						});
				});				
			} else {
				alert(data.data);
			};
		})
	} else if(v!=''){
		alert('Во вставленном фрагменте таблица не обнаружена');
	}
					}
				}).set();
			}
		})
	});
		
/**
 *  Открытие-закрытие меню
 */
	(function(){
	
		function retr_id(el){
			var ah=el.getElementsByTagName('a');
    	  	if(ah) ah=ah[0];
    	  	if(ah && ah.href){
    	  		m=ah.href.match(/id=(\w*)/);
    	  		if(m[1])
    	  			return parseInt(m[1])||m[1];
    	  	}
    	  	return false;
		}
	
		var x=(element.cookie('menuelm')||'').split('|'),curelm={};
		for(var i=0;i<x.length;i++){
			curelm[x[i]]=true;
		}

	    var collapsed=element.cls('collapsed'),
	    	expanded=element.cls('expanded') ;
	    	current=element.cls('current') ;
	    var x=element.$('main_menu').getElementsByTagName('li'),cx=x.length;
	    while(cx--){
    	  	var tt= x[cx].getElementsByTagName('ul'),ttc=tt.length,
    	  		m=retr_id(x[cx]);
    	  	if(ttc){
	    	  	if(!m || !curelm[m]) {
	    			collapsed.add(x[cx]);
		    	 	while(ttc--){
		    	  		tt[ttc].style.display="none";
		    	 	}
	    	 	}
    	 		else {
    	 			expanded.add(x[cx]);
					element.scanDomTree(x[cx].firstChild,function(e){
						if(e.tagName && e.tagName.toLowerCase()=='ul')
					   		e.style.display="block";
					},1);
    	 		}
    	 	}
    	 			
	    }
	    cx=x.length;
	    while(cx--){
	    	if(current.have(x[cx])){
		    	var tt= x[cx].getElementsByTagName('ul'),ttc=tt.length;
	    		if(ttc){
	    			expanded.add(x[cx]);
	    			collapsed.remove(x[cx]);
					element.scanDomTree(x[cx].firstChild,function(e){
						if(e.tagName && e.tagName.toLowerCase()=='ul')
					   		e.style.display="block";
					},1);
		    	}
	    	}
	    }
	    
		element.add_event(window,'beforeunload',function(){
			var date = new Date();
			date.setTime(date.getTime() + 30000); // + 30 seconds?
			var arr=[];
			for(a in curelm){
				if(a && curelm[a]) arr.push(a);
			}
			element.cookie('menuelm',arr.join('|'),{expires:date});
		})
	    	
		element.$('main_menu').onclick=function(e){
			e=e||window.event;
			var el=e.target || e.srcElement;
			//try{
			while(el && (!el.tagName ||
				(el.tagName.toLowerCase()!='li') && el.tagName.toLowerCase()!='a'))
				el=el.parentNode;
			if(!el || el.tagName && el.tagName.toLowerCase()=="a") return ;
			var m=retr_id(el);
			if(collapsed.have(el))
			{
				curelm[m]=true;
				collapsed.remove(el);
				expanded.add(el);
				element.scanDomTree(el.firstChild,function(e){
					if(e.tagName && e.tagName.toLowerCase()=='ul')
				   		e.style.display="block";
				},1);
			}
			else if(expanded.have( el))
			{
				curelm[m]=false;
				expanded.remove(el);
				collapsed.add(el);
				element.scanDomTree(el.firstChild,function(e){
					if(e.tagName && e.tagName.toLowerCase()=='ul')
				   		e.style.display="none";
				},1);
			}
		}; 
	})();	

})

function checkImg(el,width,height){
	var Img=new Image();
	Img.onload=function(){
		if(Img.width && Img.height) {
			if (!width) width=parseInt(el.style.width);
			if (!height) height=parseInt(el.style.height);
			// looks like loaded!
			var k=Math.max(Img.width/width,Img.height/height);
			el.style.width=Math.round(Img.width/k)+'px';
			el.style.height=Math.round(Img.height/k)+'px';
		}
		Img=null;
	}
	Img.src=el.src;
}

function toggleSelect(){
	var selects=document.getElementsByTagName('select'),
		i =  selects.length;
	while(i--){
		if(toggleSelect.cls.have(selects[i]))
			toggleSelect.cls.remove(selects[i])
		else
			toggleSelect.cls.add(selects[i])
	}
}
toggleSelect.cls=element.cls('tempHide')

// This function will be called from the PasteFromWord dialog (fck_paste.html)
// Input: oNode a DOM node that contains the raw paste from the clipboard
// bIgnoreFont, bRemoveStyles booleans according to the values set in the dialog
// Output: the cleaned string
function CleanWord( oNode, bIgnoreFont, bRemoveStyles )
{
	var html = oNode,reg ;

	html = html.replace( /<meta\s(\n|\r|.)*?>/gi,'');
	html = html.replace( /<link\s(\n|\r|.)*?>/gi,'');
	html = html.replace( /<style>(\n|\r|.)*?<\/style>/gi,'');
	// Remove mso-xxx styles (2).
	reg=/<!--\[if\s+gte\s+mso(\n|\r|.)*?endif\]-->/gmi; reg.multiline=true;
	html = html.replace(reg, '' ) ;

	// Remove mso-xxx styles.
	html = html.replace(/<o:p>\s*<\/o:p>/g, '') ;
	html = html.replace(/<o:p>.*?<\/o:p>/g, '&nbsp;') ;

	html = html.replace( /\s*mso-[^:]+:[^;"]+;?/gi, '' ) ;

	// Remove margin styles.
	html = html.replace( /\s*MARGIN: 0cm 0cm 0pt\s*;/gi, '' ) ;
	html = html.replace( /\s*MARGIN: 0cm 0cm 0pt\s*"/gi, "\"" ) ;

	html = html.replace( /\s*TEXT-INDENT: 0cm\s*;/gi, '' ) ;
	html = html.replace( /\s*TEXT-INDENT: 0cm\s*"/gi, "\"" ) ;

	html = html.replace( /\s*TEXT-ALIGN: [^\s;]+;?"/gi, "\"" ) ;

	html = html.replace( /\s*PAGE-BREAK-BEFORE: [^\s;]+;?"/gi, "\"" ) ;

	html = html.replace( /\s*FONT-VARIANT: [^\s;]+;?"/gi, "\"" ) ;

	html = html.replace( /\s*tab-stops:[^;"]*;?/gi, '' ) ;
	html = html.replace( /\s*tab-stops:[^"]*/gi, '' ) ;

	// Remove FONT face attributes.
	if ( bIgnoreFont )
	{
		html = html.replace( /\s*face="[^"]*"/gi, '' ) ;
		html = html.replace( /\s*face=[^ >]*/gi, '' ) ;

		html = html.replace( /\s*FONT-FAMILY:[^;"]*;?/gi, '' ) ;
	}

	// Remove Class attributes
	html = html.replace(/<(\w[^>]*) class=([^ |>]*)([^>]*)/gi, "<$1$3") ;

	// Remove styles.
	if ( bRemoveStyles )
		html = html.replace( /<(\w[^>]*) style="([^\"]*)"([^>]*)/gi, "<$1$3" ) ;

	// Remove empty styles.
	html =  html.replace( /\s*style="\s*"/gi, '' ) ;

	html = html.replace( /<SPAN\s*[^>]*>\s*&nbsp;\s*<\/SPAN>/gi, '&nbsp;' ) ;

	html = html.replace( /<SPAN\s*[^>]*><\/SPAN>/gi, '' ) ;

	// Remove Lang attributes
	html = html.replace(/<(\w[^>]*) lang=([^ |>]*)([^>]*)/gi, "<$1$3") ;

	html = html.replace( /<SPAN\s*>(.*?)<\/SPAN>/gi, '$1' ) ;

	html = html.replace( /<FONT\s*>(.*?)<\/FONT>/gi, '$1' ) ;

	// Remove XML elements and declarations
	html = html.replace(/<\\?\?xml[^>]*>/gi, '' ) ;

	// Remove Tags with XML namespace declarations: <o:p><\/o:p>
	html = html.replace(/<\/?\w+:[^>]*>/gi, '' ) ;

	// Remove comments [SF BUG-1481861].
	html = html.replace(/<\!--.*-->/g, '' ) ;

	html = html.replace( /<(U|I|STRIKE)>&nbsp;<\/\1>/g, '&nbsp;' ) ;

	html = html.replace( /<H\d>\s*<\/H\d>/gi, '' ) ;

	// Remove "display:none" tags.
	html = html.replace( /<(\w+)[^>]*\sstyle="[^"]*DISPLAY\s?:\s?none(.*?)<\/\1>/ig, '' ) ;

		html = html.replace( /<H1([^>]*)>/gi, '<div$1><b><font size="6">' ) ;
		html = html.replace( /<H2([^>]*)>/gi, '<div$1><b><font size="5">' ) ;
		html = html.replace( /<H3([^>]*)>/gi, '<div$1><b><font size="4">' ) ;
		html = html.replace( /<H4([^>]*)>/gi, '<div$1><b><font size="3">' ) ;
		html = html.replace( /<H5([^>]*)>/gi, '<div$1><b><font size="2">' ) ;
		html = html.replace( /<H6([^>]*)>/gi, '<div$1><b><font size="1">' ) ;

		html = html.replace( /<\/H\d>/gi, '<\/font><\/b><\/div>' ) ;

		// Transform <P> to <DIV>
		var re = new RegExp( '(<P)([^>]*>.*?)(<\/P>)', 'gi' ) ;	// Different because of a IE 5.0 error
		html = html.replace( re, '<div$2<\/div>' ) ;

		// place &nbsp; into empty td th.
		html = html.replace( /<(td|th)(\s[^>]*)?>\s*?<\/\1>/gi, '<$1>&nbsp;</$1>' ) ;
		// Remove empty tags (three times, just to be sure).
		// This also removes any empty anchor
		html = html.replace( /<([^\s>]+)(\s[^>]*)?>\s*<\/\1>/g, '' ) ;
		html = html.replace( /<(td|th)(\s[^>]*)?>\s*?<\/\1>/gi, '<$1>&nbsp;</$1>' ) ;
		html = html.replace( /<([^\s>]+)(\s[^>]*)?>\s*<\/\1>/g, '' ) ;
		html = html.replace( /<(td|th)(\s[^>]*)?>\s*?<\/\1>/gi, '<$1>&nbsp;</$1>' ) ;
		html = html.replace( /<([^\s>]+)(\s[^>]*)?>\s*<\/\1>/g, '' ) ;

	return html ;
}

function tabLook(el){
	var v=el.value;
	el.value='';
	if (v.match(/<table/i)) {
		element.Ajax.getJSON('?do=ajax_clear_text', 'txt=' + encodeURIComponent(v), function(data){
			$editcntr.value = data.result.txt;
			alert(data.data);
		})
	}
}

function htmlOk(){
	var $editor=$htmleditor.instanceById('area1');
	var v=trimStr($editor.getContent());
	
	v=v.replace(/<a[^>]+nicTemp[^>]+>/gi,'');//alert(v);
	var re = /<\w[^>]* (class="?MsoNormal"?|="mso-)|<!--\[if gte mso/gi ;

	if (re.test(v)) {
		v=CleanWord(v, true, true);
	}
	$editcntr.value=v;
	/*
	if (v.match(/<table/i)){
		if (confirm('В тексте обнаружена таблица. Почистить текст?')){
			element.Ajax.getJSON('?do=ajax_clear_text','txt='+encodeURIComponent(v),function(data){
				$editcntr.value=data.result.txt;
				alert(data.data);
			})
		}
	}
	*/
	if(typeof($span)!='undefined' && $span!==null){
		var v=$editcntr.value; // XXX: порнушко!!!

		// strip tags
		v=v.replace(/<.*?>/g,'');
		// leave 30 words
		var x= v.match(/(\S+\s+){30}/);
		v=(x && (x[0]+' ...'))||v;
		$span.innerHTML=v;
//		$span.style.display='block';
	} 
	htmlCancel();
	need_Save();
	$must_save=false;
	//alert($editcntr.form.method);
	$editcntr.form.submit();
};

function toggleWait(Wait,hide){
	if (!Wait) Wait='wait'
	var x=element.$('shaddow')
	if(hide || x.style.display!="none"){
		x.style.display="none";
		element.$(Wait).style.display="none";
	} else {
		x.style.display="block";
		element.$(Wait).style.display="block";
	}
}

function loadCSV(inp,startfrom){
	if ($must_save) {
		if(!confirm('Данные не сохранены!!! продолжить без сохранения?')) return ;
	}
	$must_save=false;
	
	toggleWait('progress');
	data = {
		file: inp.value,
		id: inp.name.replace(/[^0-9]/g,'')
		
	};
	if(startfrom)
		data['startfrom']=startfrom ;
		
	//inp.value='';
	element.Ajax.getJSON('?do=ajax_csv_export',data,function(data,error){
	    if(data && data.data=='complete!'){
			toggleWait('progress', true);
			clearTimeout(loadCSV.interval);
			$must_save=false ;
			__goto();
			return ;
	    } else if(data && data.data=='nave no time!'){
	    	loadCSV.lastskip=0;
	    } else if(!!data){
	    	clearTimeout(loadCSV.interval);
			toggleWait('progress', true);
			if(!!data){
			    var x=data.responseText || data.data;
			    if (x.match(/complete/)){
			      setTimeout(function(){
			      	$must_save=false ;
			      	__goto()},2000);
			    }
			    if(x)alert(x);
			} if(!!error)
				debug.trace(error).wait();
		}
	})
	loadCSV.lastskip=1;
	if(!loadCSV.timerHandle)
	loadCSV.timerHandle=function(){
		element.Ajax.getJSON('?do=ajax_csv_report',data,function(data,error){
			debug.trace(data).wait();
			if(data && data.data){
		
				var x = data.data.match(/\d+/g);
				element.$('prg_compl').innerHTML= (x && x[1])?x[1]+'%':data.data;

				if((loadCSV.lastdata==data.data)&&(loadCSV.lastskip--<0)){
					loadCSV.lastskip=1;
					loadCSV.lastdata='';
					toggleWait('progress', true);
					$must_save=false ;
					loadCSV(inp, x[2]);
					return ;
				}	
				loadCSV.lastdata=data.data;
			};
			if (data && data.data && !data.data.match(/\.\.\.$/)) {
				if(!data.data.match(/complete/))
					alert(data.data);
				else {
			      setTimeout(__goto,2000);
			    }
				toggleWait('progress', true);
				$must_save=false ;
				__goto();
				return ;
			}
			loadCSV.interval=setTimeout(loadCSV.timerHandle,5000);
		})
	};
	loadCSV.interval=setTimeout(loadCSV.timerHandle,10000);
}

function htmlCancel(){
	element.$('shaddow').style.display="none";
	element.$('html_Editor').style.display="none";
	if($editcntr && $editcntr.onblur)$editcntr.onblur();
 	toggleSelect();
}

/**
 *  функция определения смысла действия в каталоге
 */
function mean(el,val){
    if (el.value=='move'){ 
      // нужно вывести менюшку со списком каталогов
      return false;
    }
	element.$('doIt['+val+']').value=1;el.form.submit();
} 

function _goto(x,y){
	var xx=document.location.toString(),$reg=new RegExp('(&|\\b)(pg|'+x+')=.*?(&|$)','g');
	__goto((xx.replace($reg,'&')+'&'+x+'='+y).replace(/&+/g,'&').replace('?&','?'));
}

function __goto(){
	window.location.replace(window.location.toString());
}

function add_elm(tp){
	if(tp != 0)
		document.getElementById('new_item_type').value = tp;
	else
		$must_save=false;
	document.getElementById('menuedit').submit();
}

function save_act() {
	if($must_save==true) {
		$must_save=false;
		document.getElementById('menuedit').submit();
	}
}

function save_over() {
	if($must_save==true) {//'savebutton'
		//document.getElementById('save_btn').src = 'img/save_a.gif'
	}
}

function save_out() {
	if($must_save==true) {
		//document.getElementById('save_btn').src = 'img/save.gif'
	}
}

function save_doun() {
	if($must_save==true) {
		//document.getElementById('save_btn').src = 'img/save_n.gif'
	}
}

function save_up() {
	if($must_save==true) {
		//document.getElementById('save_btn').src = 'img/save_a.gif'
	}
}

function z_width (el) {
	if(el.value == 'Ширина (px)')
		el.value = '';
}

function dop_har_hide(id) {
	$('.subkat_' + id).css('display','none');
	$('#subkat_hide_' + id).css('display','none');
	$('#subkat_show_' + id).css('display','block');
	$('#newsubkat_' + id).css('display','none');
}

function dop_har_show(id) {
	$('.subkat_' + id).css('display','table-row');
	$('#subkat_hide_' + id).css('display','block');
	$('#subkat_show_' + id).css('display','none');
}

function kat_opisanie(id) {
	element.Ajax.getJSON('?do=ajax_cat_opisanie','id=' + id, function(data){
		location.replace('?do=catalog&id=' + data.data);
	});
}