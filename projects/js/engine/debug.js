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
    _type:function(e){
          var t=typeof e;
          if(t=='object'){
            t=Object.prototype.toString.apply(e);
            return(t.substring(8,t.length-1));
          }
         return(t);
    },
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
		if(typeof lev=='undefined') lev=1;
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
	 * вбросить в трассу строку
	 * @param {Object} s
	 * @alias trace
	 * @return {debug}
	 */
	trace:function (){
		for(var i=0;i<arguments.length;i++){
			if (this.s.length>100) new Error('Переполнение трассы');// типа - неча!
			this.s.push(this.tostr(this._type(arguments[i]),arguments[i]));
		}
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
};
