/**
 * @author	Serge Koriakin <SergeKoriakin@mail.ru>
 * @link 	http://forum.dklab.ru/users/ksnk/
 *
 * @copyright	Copyright (c) 2007 Serge Koriakin <SergeKoriakin@mail.ru>
 * @license 	Licensed under the MIT License: http://www.opensource.org/licenses/mit-license.php
 *
 * @version: 2007.11.27 +r1
 *
 * содержит объекты
 *  - debug - обеспечивает хранение и выдачу по требованию отладочной информации
 *  - element - обеспечивает OOP-видимость
 *  - events (наследник от element) - обеспечивает работу с событиями
 */

// Поиск в Eclips'е можно делать по регулярке '[^/\s]\s*debug\.'
/**
 * отладка - хранитель отладочной информации и функций ее вывода
 * в окончательной версии должна быть вымарана...
 *  P.S. Окончательные версии бывают ?!?
 *  @example debug.dump(arguments,'Парам:').alert() - вывод информации alert'ом
 *  @example debug.trace('xxx') ; ... debug.trace(yyy);... debug.wait();
 *  	- выплюнуть информацию в элемент с ID'ом debug
 *  @alias debug
 */
debug={
	s:[],
	/**
	 * рекурсивный обход объекта на требуемый уровень вложенности
	 *  заголовок, приписываемый спереди,объект, который обходим,уровень.
	 *  Если уровень=0 - каждое поле-объект еще обходим
	 * @alias tostr
	 * @param {Object} tit
	 * @param {Object} o
	 * @param {Object} lev
	 */
	tostr: function(tit,o,lev){
		var s=[]
		try{
			if (typeof(o) == 'undefined') {// нету параметра?
				s.push(tit);
			}else 	if(o instanceof Array) {// массив ?
				s.push(tit+'['+o.toString()+']');
			}else 	if(typeof (o)=='function') {// функция ? Не люблю FF-ные простыни... 
				s.push(tit+'(function)');  
			}else if ((typeof(o) == 'object')&&(lev>0)) {
				s.push(tit + '{');
				for (var i in o)
					s.push(this.tostr(i + ':', o[i], lev - 1));
				s.push('}'+o.toString());
			} else
				s.push(tit + o);
		} catch (ex){
			s.push(typeof(o))
		}
		return s;
	},
	/**
	 * вбросить в трассу распечатку объекта на 1 уровень
	 * @alias dump
	 * @param {Object} o
	 * @param {Object} tit
	 * @return {debug}
	 */
	dump:function(o,tit) {
		return this.trace(this.tostr(tit||'',o,1).join("\t"));
	},
	/**
	 * вбросить в трассу строку
	 * @param {Object} s
	 * @alias trace
	 * @return {debug}
	 */
	trace:function (s){
		if (this.s.length>100) new Error('Переполнение трассы');// типа - неча!
		this.s.push(s);
		return this;
	},
	/**
	 * Забрать из трассы всю информацию и вывалить ее в виде строки
	 * в качестве разделителя используется br
	 * @alias get
	 * @param {Object} br
	 * @return string
	 */
	get:function(br){
		if(!br)br='<br/>';
		var s=this.s.join(br);
		this.s=[];
		return s;
	},
	/**
	 * вывалить результат в окно alert'а
	 * @alias  alert
	 * @return {debug}
	 */
	alert:function(){
		alert(this.get('\n'));
		return this;
	},
	/**
	 * вывалить результат в окно alert'а
	 * @alias  alert
	 * @return {debug}
	 */
	wait:function(showid){
		this.showid=showid || 'debug';
		var th=this
		if(!this.waitto)
			this.waitto=setTimeout(function(){
				th.show();th.waitto=null;
			},1000)
		return this;
	},
	show:function(_id){
		_id=_id ||this.showid || 'debug';
		document.getElementById(_id).innerHTML=this.get('<br>')+document.getElementById(_id).innerHTML;
		return this;
	}
}

/**
 * Базовый класс
 * должен содержать только функции
 * маленький TVision
 * TObject назван element TApplication неопределен :-(
 * Все наследуются от одного и того-же объекта. для простоты - от единственного!
 */
var element ={
	/**
	 * Фенечка для генерации чего-нибудь такoго - уникального
	 * @param {Object} el
	 */
	unique:10000,
	/**
	 * добавить элемент в коллекцию
	 * @alias addElement
	 * @param {Object} el
	 */
	addElement: function(el){
		if (!this.hasOwnProperty('elements'))this.elements=[];
		this.elements.push(el);
	},
	/**
	 * пропустить событие по всем своим элементам.
	 * @param {Object} ev
	 */
	handle: function(method){
		if(this[method]) this[method].apply(this,arguments);
		if(this.elements)
			for(var i=0;i<this.elements.length;i++){
				var x=this.elements[i];
				if (x.handle)
					x.handle.apply(x,arguments);
				else if(x[method])	
					x[method].apply(x,arguments);
			}
	},
/**
 * функция подклеивает к (объекту || к себе) еще немного свойств
 * @alias merge
 * @alias element.merge
 * @param {object} prop - какие свойства клеим
 * @param {object} to - куда клеим свойства || или с себе
 * @return {Object}  this
 */
	merge: function(prop,to){
		if(!to)to=this;
		if(!prop) return to;
		for (var k in prop) to[k] = prop[k];
		if(prop.hasOwnProperty('toString'))to.toString = prop.toString;
		return to;
	},
/**
 * функция создания наследника
 * @alias newClass
 * @param {function} parent
 * @param {Object} prop
 * @return {function}  функция, с prototype от нужного объекта и нужным конструктором
 */
	newClass:function(prop,par) {
		if(!par)par=this; // наследуемся от себя, если не от кого
	  // Dynamically create class constructor.
		var clazz = function(){
			var cname = "constructor";
			this.merge(prop).prototype=par;
			if (prop[cname] && prop[cname] != Object){
				prop[cname].apply(this, arguments);
			}
		};

		if(typeof(par)=='function') par=new par();
		clazz.prototype = par;// gain object instead function!!!!

		return clazz;
	},
/**
 * Внутренняя механика - наследование и протчие радости жизни
 * @method NewClass.inherited
 */
	inherited:function (method,par){
		var m=this[method];
		if(!m) return;
		var el=this;
		while(el && !el.hasOwnProperty(method))	el=el.prototype ;
		if(el){
			delete(el[method]);
			var res=this[method]&& this[method].apply(this,par);
			el[method]=m;
			return res;
		}
		return ;
	}
}

/**
 * Функция добавляет базовому элементу возможность управления событиями
 * @example MyObj.set('MyHandle').addevent($('x'),'mousedown').set();
 */
function initElEvents(){
	/**
	 * модификация объекта Function
	 */
	element.merge({
		bind: function(bind,a,b,c){
			var fn = this
			return function(){
				return fn.call(bind, a,b,c)
			}
		},
		bindEH: function(bind,a,b,c){
			var fn = this; if(fn.binded) return fn;
			var f=function(e){
				return fn.call(bind, e||window.event,a,b,c)
			};f.binded=true;
			return f;
		},
		delay: function(ms, bind,a,b,c){
			return setTimeout(this.bind(bind ,a,b,c), ms)
		},
		period: function(ms, bind,a,b){
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
		set:function(x){if(!x)x='0';this._curhandle=x;return this},
		$:function(id){return document.getElementById(id)},
	/**
	 * А вот!
	 * @alias add_event
	 * @param {Object} a
	 * @param {Object} e
	 * @param {Object} o
	 */
		add_event: function(a,e,o){
			if (e instanceof Array) {
				for (var i in e)
					this.add_event(a, e[i], o);
				return this;
			}
			if(!o) o=this[e];//.bind(this,a,c,d); // польза автобинда пока сомнительна есть!
			o=o.bindEH(a);
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
			return (e.returnValue = false);
		},
		/**
		 *  вычисление координат элемента в "абсолютных" числах
		 *  @alias getBounds
		 */
		getBounds:function (e)
		{
			if(!e)e=window.innerHeight?window:document.body;	
			left=e.offsetLeft;	
		//    left=(e.tagName.toLowerCase()=='div')?0:e.offsetLeft
			for (var parent = e.offsetParent,top=e.offsetTop; parent;
				parent = parent.offsetParent)
			{
				left  += parent.offsetLeft;
				top  += parent.offsetTop;
			}
			return {left: left||0, top: top||0, width: e.offsetWidth||e.innerWidth||e.availWidth, height: e.offsetHeight||e.innerHeight||e.availHeight};
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
						date.setTime(date.getTime() + (opt.expires * 24 * 60 * 60 * 1000));
	//					debug.dump(date).wait();
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
				e.style[i]=(o[i]===null?'':o[i]+'px')
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
}
/**
 * Двигл для Драг нах остен... Драг & дроп
 * @param {Object} opt
 */
var $DDR=(function(opt){
	var mouseup_func = function(e, opt){
		element.clear_events(this.id);
		if (opt.on_df)
			opt.on_df.call(this);
	};
	var mouseact_func = function(e, opt, actx, acty){
		var dx = this.posx - e.clientX, dy = this.posy - e.clientY;
		this.posx = e.clientX;
		this.posy = e.clientY;
		for (var xx in opt.t) { var x=opt.t[xx];
			x.style[actx] = (x['_' + actx] -= dx) + 'px';
			x.style[acty] = (x['_' + acty] -= dy) + 'px';
		}
		return element.clearEv(e)
	}
	function t2eq(opt,actx,acty){
		var offset =element.getBounds(opt.t[0]);
//		offset['width']=$(opt.t[0]).width();//offset[actx];
//		offset['height']=$(opt.t[0]).height();
		for(var i=0;i<opt.t.length;i++){var x=opt.t[i];
			x['_'+actx]=offset[actx];//offset[actx];
			x['_'+acty]=offset[acty];
			if(i) element.css(x,offset);
		}
	}
	function act(opt,o, actx, acty){
		setTimeout(t2eq.bind(opt[o],opt, actx, acty),10);
//		t2eq.call(opt[o],opt, actx, acty);
		if (opt[o] && !opt[o].id)
			opt[o].id = '_dd_' + (element.unique++);
		element.add_event(opt[o], 'mousedown', function(e, opt){
			// очистить старые хандлы
			element.clear_events(this.id).set(this.id);
			this.posx = e.clientX;
			this.posy = e.clientY;
			t2eq.call(opt[o],opt, actx, acty)
			element.set(opt[o].id)
				.add_event(element.mousetgt, 'mousemove', mouseact_func.bindEH(opt[o], opt, actx, acty))
				.add_event(element.mousetgt, 'mouseup', mouseup_func.bindEH(opt[o], opt, actx, acty))
				.set();
			return element.clearEv(e);
		}
		.bindEH(opt[o], opt));
	}
	return function(opt){ // exаmple {drag:el,resize:el1,on_df:ondragfin,t:tgt,t2:tgt1)
		if (!opt.t)
			opt.t = opt.drag || opt.resize;
		if (!(opt.t instanceof Array)) {
			var t=[]; t.push(opt.t);opt.t=t;
		}
		if (opt.drag)
			act(opt,'drag', 'left', 'top');
		if (opt.resize)
			act(opt,'resize', 'width', 'height');
	}
})();

/*

var J=({
	init:function(alias){
		this.alias=alias;
		var x=document.getElementsByTagName("head")[0].firstChild;
		do {
			if (x.tagName && (x.tagName.toLowerCase() == 'script')){
				this.loaded[x.getAttribute('src')]={complete:true};
			}
		} while((x = x.nextSibling))
		return this;
	},
	loaded:{},
	func:[],
	alias:{},
	dependence:[],
	loadJS:function(s){
		var x = document.createElement("script");
		x.setAttribute("type", "text/javascript");
		x.setAttribute("language", "javascript");
		x.setAttribute("src", s)
		cont.appendChild(x);
		x.onload=function(){J.checkdep(s);}
		this.loaded[s]={complete:false};
		return x;
	},
	require:function(s,f){
		//for (var i = 0; i < ss.length; i++) {
			if (!this.loaded[s])
				return this.loadJS(s);
		//}
	},
	checkdep:function(s){
		J.loaded[s].complete='true';

	}
}).init({'jqModal.js':'js/jqModal.js'});

function requireJS(ss,f){
	for (var i = 0; i < ss.length; i++) {
		var s=alias[ss[i]]||ss[i];
		// checkif exists
		var cont=document.getElementsByTagName("head")[0];
		var x= cont.firstChild;
	//	debug.trace('scanfor "'+s+'"');
		do {
			if (x.tagName && (x.tagName.toLowerCase() == 'script')&& (x.getAttribute('src') == s))
				break;
		} while((x = x.nextSibling))
		if (!x) {
			x = document.createElement("script");
			x.setAttribute("type", "text/javascript");
			x.setAttribute("language", "javascript");
			x.setAttribute("src", s)
			cont.appendChild(x);
			x.onload=function(){J.checkdep(s);}
		}
	}
}

requireJS(['jqModal.js','js/jqDnR.js']);

//debug.alert();
// */

