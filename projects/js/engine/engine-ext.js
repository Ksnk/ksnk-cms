/**
 * Функция добавляет базовому элементу возможность управления событиями
 * @example MyObj.set('MyHandle').addevent($('x'),'mousedown').set();
 */
;(function (){
	/**
	 * модификация объекта Function
	 */
	element.merge({
		bind: function(bind,a,b,c){
			var fn = this
			if(fn)
			return function(){
				return fn.call(bind||window, a,b,c)
			}
			else return null;
		},
		bindEH: function(bind,a,b,c){
			var fn = this; if(fn.binded) return fn;
			var f=function(e){
				return fn.call(bind, e||window.event,a,b,c)
			};f.binded=true;
			return f;
		},
		delay: function(ms, bind,a,b,c){
			return setTimeout(this.bind(bind||this ,a,b,c), ms)
		},
		period: function(ms, bind,a,b,c){
			return setInterval(this.bind(bind ,a,b,c), ms)
		}
	},Function.prototype);

	element.merge({
	/**
	 *  хранилка установленных хандлов
	 *  @alias _handlers
	 */
		_handlers:{},
		_curhandle:'0',
		set:function(x){if(!x)x='0';element._curhandle=x;return this},
		$:function(id){return (typeof id=='string')?document.getElementById(id):id},
	/**
	 * А вот!
	 * @alias add_event
	 * @param {Object} a
	 * @param {Object} e
	 * @param {Object} o
	 */
		add_event: function(a,e,o,c,d){
			if(!a)return this;
			if(!o)o=this[e].bindEH(this,a,c,d);
			else o=o.bindEH(a,c,d);
			if (e instanceof Array) {
				for (var i in e)
					this.add_event(a, e[i], o);
				return this;
			}
			if (a.addEventListener)
				a.addEventListener(e,o,false)
			else if (a.attachEvent) {
				try {
					a.attachEvent( e='on'+e, o);
				} catch (aEx) {}
			}
	// для автоматической чистки хендлов!!!
			var ind=element._curhandle,h=element._handlers;
			if(!h[ind])h[ind]=[];
			h[ind].push({a:a,e:e,o:o})
			return this;
		},
		/**
		 * Зачистить собщения с хандлом ind
		 * @alias clear_events
		 * @param {Object} ind
		 */
		clear_events:function(ind) {
			var h,o,hh=element._handlers[ind||0];
			if(hh){
				while(h=hh.pop()){
					if (h.a.removeEventListener) {
						h.a.removeEventListener(h.e, h.o, false);
					} else if (o=h.a.detachEvent) {
						try {
							o( /*'on'   */ h.e, h.o);
						} catch (aEx) {}
					}
				}
			}
			return this;
		},
		/**
		 * объект, служащий таргетом для установщика обработчика мышы
		 * @alias mousetgt
		 * @param {Object} e
		 */
		mousetgt : (window.execScript || window.opera)?document:window,

		/**
		 * кроссбраузерно и надежно очистить сообщение
		 * @alias clearEv
		 * @param {Object} e
		 */
		clearEv:function(e){
			if (e.preventDefault) e.preventDefault()
			e.cancelBubble = true;
			if(e.stopPropagation) e.stopPropagation();
			return (e.returnValue = false);
		},

		/**
		 *  вычисление координат элемента в "абсолютных" числах
		 *  @alias getBounds
		 */
		getBounds:function (e)
		{
			if (!e) {
				if (window.innerWidth) {
					return{left:0,top:0,width:window.innerWidth,height:window.innerHeight};
//				  " pixels\nouter: " + window.outerWidth + " x " + window.outerHeight + " pixels");
				} else
				with (document.body) {
					return{left:0,top:0,width:clientWidth ,height:clientHeight};
//				  window.alert(clientWidth + " x " + clientHeight + " pixels");
				}
			}
			left=e.offsetLeft;
			for (var parent = e.offsetParent,top=e.offsetTop; parent;
				parent = parent.offsetParent)
			{
				left  += parent.offsetLeft;//-parent.scrollLeft;
				top  += parent.offsetTop;//-parent.scrollTop;
			}
			return {left: left, top: top, width: e.offsetWidth||e.innerWidth, height: e.offsetHeight||e.innerHeight||600};
		},
		scanDomTree:function (e,callback,lvl){
	        var o= e,
	            stack=[null]
			if(typeof(lvl)=='undefined') lvl=1000; // never climb so deap;
	        do {
	            if(!o) {
	                o=stack.pop(); continue  // go up
	            }
				if(callback.call(this,o))
					return o;
	            if (o.firstChild && stack.length<=lvl) {
	                stack.push(o.nextSibling);
	                o=o.firstChild; // go deep
	            } else
	                o=o.nextSibling
	        } while(stack.length)
			return null;
		},
		cls:function(cl){
	        var reg; (reg=new RegExp("(^|\\b)"+cl+"(\\b|$)")).compile;
			return {
				add:function(x){
					var r=this.have(x);
					if (r==false)
						x.className=cl
					else if(!r)
						x.className+=(x.className&&' '||'')+cl;
				},
				have:function(x){
					if (typeof x.className =="string")
						return (x.className.match(reg))
					else
						return false;
				},
				remove:function(x){
					if (typeof x.className =="string") {
						if (x.className.match(reg))
							x.className=x.className.replace(reg,'')
					}
				}
			}
		},
		allClass:function (o,cl,callback){
	        var cl=this.cls(cl);
			return this.scanDomTree(o,function(x){
	            if (cl.have(x))	callback.call(this,x);
				return false;
			})
		},
		firstClass:function (o,cl){
	        var cl=this.cls(cl);
			return this.scanDomTree(o,cl.have)
		},
		// поставить куку cookie.
		cookie: function(name,value,opt){
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
		},

		css:	function (e,o){
			for(var i in o){
				if(i=='opacity'){
					if (o[i] == 0){
						if(e.style.visibility != "hidden") e.style.visibility = "hidden";
					} else {
						if(e.style.visibility != "visible") e.style.visibility = "visible";
					}
					e.style.filter = "alpha(opacity=" + Math.round(o[i]*100) + ")";
					e.style.opacity = o[i];
				} else
					e.style[i]=(o[i]===null?'auto':o[i]+'px')
			}
		},
		/**
		 * функция, вызывающаяся по unload. Ее цель - зачистить мусор для IE
		 */
		unload:function(){
//			debug.dump(element._handlers).alert();
			for(var i in element._handlers)
				element.clear_events(i);
		}
	}).add_event(window,'unload');
})();

