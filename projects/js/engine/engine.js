/**
 * @author	Serge Koriakin <SergeKoriakin@mail.ru>
 * @link 	http://forum.dklab.ru/users/ksnk/
 *
 * @copyright	Copyright (c) 2007 Serge Koriakin <SergeKoriakin@mail.ru>
 * @license 	Licensed under the MIT License: http://www.opensource.org/licenses/mit-license.php
 *
 * @version: 2007.11.27 +r1
 */

/**
 * содержит объекты
 *  - debug - обеспечивает хранение и выдачу по требованию отладочной информации
 *  - element - обеспечивает OOP-видимость
 *  - events (наследник от element) - обеспечивает работу с событиями
 */


/**
 * Базовый класс.
 * Маленький TVision.
 * TObject назван element, TApplication неопределен :-(
 * Все наследуются от одного и того-же объекта. для простоты -
 * от единственного!
 * та-дамммм!!! Вот он!
 */
var element ={
	/**
	 * Фенечка для генерации чего-нибудь такoго - уникального
	 * @alias unique
	 * @method element
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
	 * Жалкий аналог HandleEvent'а от TVision
	 * @param {Object} ev
	 */
	handle: function(method){
		if(this[method]) this[method].apply(this,arguments);
		if(this.elements)
			for(var i=0;i<this.elements.length;i++){
				var x=this.elements[i];
				if(x[method])
					x[method].apply(x,arguments);
				else if (x.handle)
					x.handle.apply(x,arguments);

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
};

