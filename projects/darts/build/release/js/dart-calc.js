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
 * глобальный объект - хранилище ХЕЛП - информации
 * lang -  выбор языка файла-подсказки
 * используется совместно с функциями ShowAlert ShowHint
 */
_l('lang','ru');
/**
 * глобальный объект
 */
var dart_pane;
/*
function _l(h,v){
	var hash=[];
	window._l=function (h,v){
		if(v) hash[h]=v;
		else if(hash[h]) return hash[h];
		else return null; 
	};
	return window._l(h,v);
};*/
/**
 * Универсальный хандл для форм
 * @param {Object} o
 */
function ch(el){
	if (!el) return false;
	if ((el.tagName.toLowerCase() == "input") && (el.name == 'connect')) {
		var f=function(t,e){
			if (e) {
				showAlert(e);
			}
			if(_l('connect')!=t.data)
				_l('connect',t.data);
			if(t){ //alert(t.data);
				showAlert('connect');
			}
			if(arguments.callee.next)arguments.callee.next();
		};
		if(!(_l('connect'))){
			 chain(Ajax,'form')
			.chain(f).run();
		} else {
			f({
				data: _l('connect')
			});
		}
	} else if ((el.tagName.toLowerCase()=="select")&&(el.name=='language')) {
		var str=$(el.form).serialize();
		if(str)	element.cookie('start',str,{expires:10});
		document.location.reload();
	} else if (el.tagName.toLowerCase()=="form") {
	/**
	 * вызывается обработчик формы
	 */
		switch(el.name){
		case 'new_game_form':
			var error=function(s){
				$(el).find('th').eq(0).html(s);
				return false;
			};
			// проверка на корректность
			// 1 - ИГРА ВЫБРАНА
			if (el.game.value<=0) return error(_l('nogamechanged'));
			var str=$(el).serialize();
			showHint();
			// старт игры!!!
//			debug.dump(el,'форма').alert(); //alert([el.form.plnumber.options,el.form.plnumber.selectedIndex,el.form.plnumber.options[el.form.plnumber.selectedIndex-1].value]);
			if(str)	element.cookie('start',str,{expires:10});
			try{
			dart_pane.startgame(parseInt(el.game.value),
			   parseInt(el.plnumber.selectedIndex)+1);
			} catch(e){;}
			break;
		}
	}
	return false;
}


$(function(){// типа - onload!!!!!!
	/**
	 * разновеликая польза
	 */
	/**
	 * циклический сдвиг стрелок
	 * @param {Object} x
	 */
	function rshift(x){if(x)x.push(x.shift());}

	/**
	 * пометить значение как disable
	 * @param {Object} x
	 */
	function disable(x){
		if (x && x._val && x._val.disabled) return ;
		x._val.htmlval=x._val.htmlval.strike();
		x._val.disabled=true;
	}

	/**
	 * Функция, восстанавливающая объект из куки
	 * @param {Object} cook
	 * @return Object - объект из строковых атрибутов
	 */
	function obj_load(cook){
		var s='&'+element.cookie(cook)||cook,
			cook=new RegExp("[\b|&]([^=]+)=([^&]+)","g"),resa=[],res={game:0};
		while((resa=cook.exec(s))){
			res[resa[1]]=resa[2];
		};
		return res;
	}

	function rebuild_lang(data,error){
		var reg=/\[(\w+)\]([^\[]+)/g,
			res;
		if(error)alert(error);
		if(!data)
			data={data:$('#lang').html()};
		while((res=reg.exec(data.data)))
			_l(res[1],res[2]);
		var x=_l('newgame').split('|'),
			y=_l('gamename').split('|'),
			hng=$('#newgame'),
			html=hng.html();hng.html('');
		for(var i=0;i<y.length;i++){
			_l('game'+i,y[i]);
		}

		for (var i=0;i<pane.games.length;i++){
			x[1]+='</option><option value="'+(i+ 1)+'">'+
			(_l(pane.games[i].toString())||'xxx');
		}
		html=html.replace(/\$(\d+)/g,function(a,b){return x[b - 1];}) ;
		_l('newgame_html',html);
		_l('newgame_title',x[0]);

		var canplay=true;
		if (!!startgame){
			if (!startgame.rule || !dart_pane.games[startgame.rule-1])
				canplay = false;
		} else
			canplay=false;

		if (!canplay) {
			showHint("newgame_html", _l('newgame_title'));
			dart_pane.form_load('new_game_form', new_game_form);
			document.forms['new_game_form'].sub.focus();
			return false;
		} else {
	//		showHint();
			dart_pane.startgame(startgame.rule);
		}
	}

	/**
	 * загрузка языкового файла
	 */
	var new_game_form=obj_load('start');

	if(new_game_form.language)_l('lang',new_game_form.language);

	var data = {
		id: $('#lang').attr('lang'),
		data: $('#lang').html()
	};

	if(_l('lang')!=data.id)
		 chain(Ajax,_l('lang')+'.html')
		.chain(rebuild_lang).run();
	else
		chain().wait(5)
		.chain(rebuild_lang,data).run();
	/**
	 *  arrow - объект -стрелка. Плагин для установки в PANE
	 *   ищем таблицу результатов и запоминаем ячейки.
	 *   @param {string} a
	 */
	var arrow=element.newClass({
		constructor:function(a){this.arrow=$(a).get(0);}
	,
		BEFORETURN:function(){
			if(this.arrow) this.arrow.style.top="-1000px";
			this._val={htmlval:"",rval:0};
		}
	,
		REFRESH:function(){
			$(this.arrow).find('p').html(this._val && this._val.htmlval||'');
		}
	});

	/**
	 * вычисления по точке попадания количества очков.
	 * Функция выдает функцию, занимающуюся подсчетом очков...
	 * Функция вручную настраивается на контретное изображение доски.
	 */
	var Board=function(x){
		var
		/**
		 *	не хочется очень часто повторять 600 - габариты картинки,
		 *  etalon, типа, кароче!
		 */
		etalon=600
		/**
		 * для картинки 600x600 точный центр располагается по таким координатам
		 * + уменьшаем массив обсчета до реального размера картинки
		 */
		,center={x:300*x/etalon,y:300*x/etalon}
		/**
		 * расстояния по картинке от центра до двойного була,була,
		 * границы утроения и удвоения
		 */
		,circles=[300-280,300-260,300-180,300-151,300-85,300-49]
		,values=[6,13,4,18,1,
				20,5,12,9,14,
				11,8,16,7,19,
				3,17,2,15,10,
				6] // numbers per sectors
		,i=circles.length;
		do {
			circles[i]=circles[i]*x/etalon;
		} while (--i);

		var
		/**
		 * Вычисление угла между точками
		 * @param {Number} a
		 * @param {Number} b
		 */
		angle=function (a,b) {
			var dy=a.y-b.y;
			if(dy==0) dy=1;  // на ноль делить низяяяяя
			return Math.atan((a.x-b.x)/dy)+Math.PI*(dy>0?1.5:0.5);
		};
		/**
		 * Вычисление значения по координате попадания
		 * @param {Number} x
		 * @param {Number} y
		 */
		return function (x,y) {
			var d=Math.sqrt(Math.pow(x-center.x,2)+Math.pow(y-center.y,2)),
				res={val:0,m:1,x:x,y:y};
			if (d>circles[5]) {
				res.val=0;
			} else if (d<circles[0]) {
				res.m=2; res.val=25;
			} else if (d<circles[1]) {
				res.val=25;
			} else {
				var i=values[Math.round(angle({x:x,y:y},center)*10/Math.PI)]; // 1/20 круга
				res.val=i;
				if (d<circles[3] &&d>circles[2]) res.m=3;
				else if (d<circles[5] &&d>circles[4]) res.m=2;
			}
			return res;
		};
	};

	/**
	 * объект pane - хранитель доски и стрелок
	 *  доска - картинка с ID=dart
	 *  стрелки - конструкты следующего вида
	 *  <div class="arrow" tagx=5 tagy=11> <img alt="" src='...' />
	 *		<p>&nbsp;</p>
	 *	</div>
	 *  tagx tagy - смещение по картинке до "жала" стрелки
	 *  будет только единственный экземпляр объекта
	 *
	 *  Схема расположения "точек взаимодействия"
	 *  NewGame
	 *  	NEXTTURN, переход на GameComplete если игра кончилась
	 *  	BeforeTurn
	 *  		UNDO+stike+RECALC, многократно
	 *  	NextPlayer, переход на BeforeTurn если не последний игрок, иначе - NextTurn
	 *  GameComplete
	 *
	 *  итого:
	 *  - UNDO - возврат сохраненного состояния за предыдущий ход
	 *  - strike - влепить стрелку в новое место
	 *  - RECALC - пересчитать заново результаты по всем стрелкам
	 */
	var pane= function (){

		this.arrows=[]; // изображения стрелок на доске

		// найдем и оживим все стрелки
		var arrowId=0, offset={},
			th=this,
			xx= element.$('dart'),
			xxh= element.$('dart_handle');
			$(xxh).css({
				opacity: 0
			});
/*
		function to_reanimate(clear){
			if(th.rollturn_to) clearTimeout(th.rollturn_to);
			th.rollturn_to=(!clear)
				? setTimeout(th.nextturn.bind(th),10000)
				: 0;
		};
*/
		this.shift=function(){rshift(this.arrows);};

		this.nextturn=function(){
			this.GrantRefresh(function(){
			this.handle('NEXTTURN');
			this.handle('BEFORETURN');
			});
			//to_reanimate(true)
		};

		$('.arrow')
			.each(function(){
				var x='arrow'+(arrowId++),xx=$(this);
				xx.attr('id',x);
				element.add_event(this,'contextmenu',function(e) {
					return element.clearEv(e);
				});
				x=new arrow('#'+x);
				//******** сохраним в массиве!!!
				th.arrows.push(x);
				th.addElement(x) ;//,$('<div></div>').appendTo($("#turnn"))));
				//******** подрагаем!!!

				$DDR({
					drag: this,
					on_movefin: function(opt){
						var dx = parseInt(opt.drag.getAttribute('tagx') || 0), dy = parseInt(opt.drag.getAttribute('tagy') || 0);
						pane.strike(opt._left + dx - offset.left, opt._top + dy - offset.top, x);
						// реанимация таймаута передачи хода
						//to_reanimate();
					}
				});
				element.add_event(window,'unload',function(){xx=null;});
			});
		function ChkWindow(){
			var wo=element.getBounds();
			wo={
				top:0,
				left:((wo.width-wo.height)/2),
				width:wo.height,
				height:wo.height
			};
			th.value= Board(wo.height);
			element.css(xx.parentNode,wo);
			element.css(xxh,wo);
			element.merge(wo,offset);
			wo.top=null;wo.left=null;
			element.css(xx,wo);
			$('#score').css({height:wo.height-50});
		}

		ChkWindow();

		this.add_event(window,['resize','scroll'],ChkWindow);

		this.add_event(xxh,'contextmenu',function(e) {
				dart_pane.nextturn();
				return element.clearEv(e);
			})
			.add_event(xxh,'mousedown',function(e){
				//debug.dump(e,'x').alert();
				if (e.button==2) return ;
				$(xxh).offset({ scroll: false }, offset);
				var x=e.pageX||e.clientX,y=e.pageY||e.clientY;
				dart_pane.strike(x-offset.left,y-offset.top,dart_pane.arrows[0]);
				dart_pane.shift();
				//to_reanimate();
			});
		/**
		 * соответствие между доской и числом в ячейке
		 * @param {Object} xx
		 * @param {Object} y
		 */
		this.strike=function (xx,y,aa) {
			this.GrantRefresh(function(){
				pane.handle('UNDO');
				//debug.trace(' x:'+xx+' yy:'+y).wait();
				var xval=this.value(xx,y),a=$(aa.arrow);
				this.handle('_strike',xval);
				if(!xval.htmlval)xval.htmlval=xval.m*xval.val;
				var dx=parseInt(a.attr('tagx')||0),
					dy=parseInt(a.attr('tagy')||0);
				a.css({top:y-dy+offset.top,left:xx-dx+offset.left});
				//a.css({top:y-dy,left:xx-dx});
				aa._val=xval;
				pane.handle('RECALC');
				a=null;xval=null;
			});
		};
		/**
		 * частичная блокировка refresh'а от плагинов.
		 */
		this.GrantRefresh=function(f){
			var sr=this._skiprefresh; this._skiprefresh=true;
			f.call(this);
			this._skiprefresh=sr;
			if(!sr) pane.refresh();
		};
		this._skiprefresh=false;
		this.refresh=function(){
			if(this._skiprefresh)return;
			pane.handle('REFRESH');
		};
		this.rollback=function(){
			this.handle('ROLLBACK');
		};
		this.unload=function(){
			this.handle('UNLOAD');
			th=null;
		};
		this.form_load=function (form,obj){
			var f;
			if((f=document.forms[form]))
			for(var i in obj){
				if (f[i] && f[i].tagName && (f[i].tagName.toLowerCase() == "select")) {
					for (var j = 0; j < f[i].options.length; j++) {
						var val=f[i].options[j].value||f[i].options[j].text||'';
						if (val == obj[i].toString()) {
							f[i].selectedIndex = j;
						} else {
							if(f[i].options[j].selected)f[i].options[j].selected='';
						}
					}
				}
				else if(f[i]) {
					f[i].value = obj[i];
				}
			}
		};
	};
	pane.prototype=element;
	pane=new pane();
	// кнопка "пробел" - начало нового хода!
	$().keypress(function(e){
		// 32 - space 27 - Esc
		if ((e.charCode || e.keyCode) == 32) {
			pane.nextturn();
			return element.clearEv(e);
		}
	});
	$(window).unload(function(){pane.unload();});
/**
 *  Еще немного объектов для игры
 */
/**
 *   player - объект для хранения результатов игры
 *   ищем таблицу результатов и запоминаем ячейки.
 *   одна таблица на всех
 */
	var player=element.newClass({
		el:{name:'',id:0},
		sav:[],
		nameit:function(pln){
			if (pln) {
				this.el=pln;
			}
		},
/**
 * по значению this.numb переустанавливает ячейки таблицы.
 */
		initarrow:function(){
			this.scorepane='#scorepane'+this.numb;
			var x=$("#content tr").eq(1).find('.score'),y=this.numb*3;
			for(var j=0;j<pane.arrows.length;j++) {
				this.arrows[j]={arr:pane.arrows[j],el:x.get(y+j)};
			}
			return this;
		},
		init:function(i){
			this.numb=i;
			// generate
			$('<th class="score" colspan=3><div id="scorepane'+this.numb+'" >&nbsp;</div></th><td></td>').appendTo($("#content tr").eq(0));
			this.arrows=new Array(pane.arrows.length);
			for(var j=0;j<pane.arrows.length;j++) {
				$('<td  class="score"></td>').appendTo($("#content tr").eq(1).addClass('odd'));
			}
			$('<td></td>').appendTo($("#content tr").eq(1)); // для

			this.initarrow();
		},
		refresh:function(){
			$(this.scorepane).html(this.el.name+this.score);
			for(var i=0;i<pane.arrows.length;i++) {
				var x=this.arrows[i],	y=(x.arr._val && x.arr._val.htmlval)||"&nbsp;";
				if(y!=x.el.innerHTML)x.el.innerHTML=y;
			}
			return this;
		}
	});
	var player_kriket=element.newClass({
		init:function(i){
			this.inherited('init',[i]);
			this.kriket=[];
			this.kriketpane=$('<th class="score" colspan=4></th>').appendTo($("#kriket tr").eq(0));
			for(var i=1;i<8;i++) { // in {25:1,20:2,19:3,18:4,17:5,16:6,15:7}){
				for(var j=0;j<3;j++) {
					this.kriket.push({el:$('<td  class="kriket">&nbsp;</td>').appendTo($("#kriket tr").eq(i))});
				}
				$('<td></td>').appendTo($("#kriket tr").eq(i)); // для
			}
		},
		refresh:function(){
			this.inherited('refresh',[i]);
			var th=this,cross=element.cls('cross');
			function check(x,set){
				if (set){
					th.kriket[x].el.each(function(){
						if (!cross.have(this)) cross.add(this);
					});
				} else {
					th.kriket[x].el.each(function(){
						if (cross.have(this)) cross.remove(this);
					});
				}
			}
			var x=0;
			for(var i in this.sectors){
				check(x,this.sectors[i]>0);
				check(x+ 1,this.sectors[i]>1);
				check(x+ 2,this.sectors[i]>2);
				x+=3;
			}
			return this;
		}
	},player);
/**
 *  game - собственно правила игры
 *   - общий предок для всех игр
 */
	var game =new(element.newClass({
		pl:[],
		init:function(pln,pl_func){
			if(!pl_func)pl_func=player;
			pane.addElement(this);
			this.pl=[];
			for (i = 0; i < pln.length; i++) {
				var p=new pl_func();
				p.nameit(pln[i]);
				this.pl.push(p);
			}

			this.pl[0].firstplayer=true;
			$('#content').css({opacity:0.80});
			var numb=0;
			//this.allpl(function(){this.numb=numb++});
			this.turn=1;
		},
		allpl:function(f){
			var a=jQuery.makeArray(arguments) ;//a.shift();
			for(var i=0;i<this.pl.length;i++) {
				a[0]=i;f.apply(this.pl[i],a);
			}
		},
		_strike:function(idx,val){
			if(!val.val){
				val.htmlval="&nbsp;";
				val.rval=0;
			} else {
				val.htmlval=val.val+'';
				if(val.m>1)
					val.htmlval+="<sup>"+val.m+"</sup>";
				val.rval=val.val*val.m;
			}
		},
		serialize:function(pl,restore){
			if(!restore){
				var x=pane.arrows,res={score:pl.score,arrow:[]};
				for(var i=0;i<x.length;i++) if(x[i]._val.val) res.arrow.push({v:x[i]._val.val,m:x[i]._val.m});
				return res;
			} else
				pl.score=restore.score;
		},
		NEXTTURN:function(){
			rshift(this.pl);
			if(this.pl[0].firstplayer) pane.handle('NEWTURN');
		},
		BEFORETURN:function(id,nostore){
			this.turnpane=$("#content tr").eq(1).find('th').get(0);
			if(!nostore)this.pl[0].sav.push(this.serialize(this.pl[0]));
			$(this.pl[0].scorepane).addClass('current');
			for(var i=1;i<this.pl.length;i++){
				$(this.pl[i].scorepane).removeClass('current');
			}
		},
		REFRESH:function(idx,all){
			if(this.turnpane)this.turnpane.innerHTML=this.turn;
			this.pl[0].refresh();
		},
		UNDO:function(){
			var p=this.pl[0];
			this.serialize(p,p.sav.pop());
			p.sav.push(this.serialize(p));
			var x=pane.arrows;
			for(var i=0;i<x.length;i++) x[i]._val.disabled=false;
		},
		NEWTURN:function(){
			this.turn++;
			var xx=$("#content tr").eq(1);
			xx  .clone()
				.insertAfter(xx);
			xx	.removeClass(!(this.turn & 1)?'odd':'even')
				.addClass((this.turn & 1)?'odd':'even');
			do{
				pane.handle('BEFORETURN',true);
				rshift(this.pl);
				this.pl[0].refresh();
			}while(!this.pl[0].firstplayer);
			pane.refresh();
		},
		ROLLBACK:function(){
			var p=this.pl[0];
			this.serialize(p,p.sav.pop());
			if(!(p.firstplayer && this.turn==1)){
				if(p.firstplayer){
					$("#content tr").eq(1).remove();
					this.turn--;
					for(var i=0;i<this.pl.length;i++){
						this.pl[i].initarrow();
					}
				}
				if (this.pl[1]) while(this.pl[1]!=p)rshift(this.pl);
				var p=this.pl[0];
				this.serialize(p,p.sav.pop());
			} else { // меняем местами игроков
				this.pl[0].firstplayer=false;
				rshift(this.pl);
				this.pl[0].firstplayer=true;
				var i=0;
				do {
					this.pl[0].numb=i++;
					this.pl[0].initarrow().refresh();
					rshift(this.pl);
//					this.pl[0].refresh();
				} while (!this.pl[0].firstplayer);
			}
			pane.handle('BEFORETURN');
			pane.refresh();
		},
		UNLOAD:function(){
			this.allpl(function(){
				if(this.arrows)
				for(var i=0;i<this.arrows.length;i++) {
					this.arrows[i].el=null;
				}
			});
		}
	}))();
	/**
	 * Набор очков
	 * @param {Object} nm
	 * @param {Object} MScore
	 */
	var game_Points=game.newClass({
		constructor:function(nm){
			this.toString=function(){return nm;};
		},
		NEWGAME: function(){
			this.turn = 1;
			this.allpl(function(i){
				this.init(i);
				this.score = 0;
				this.refresh();
			});//this.pl[i].initarrow().refresh();
		},

		RECALC: function(idx, all){
			// суммируем с счетом!!!
			var player = this.pl[0];
			var x = pane.arrows, score = player.score;
			for (var i = 0; i < x.length; i++)
				if (!x[i]._val.disabled) {
					score += x[i]._val.rval;
				}
			player.score = score;
			pane.refresh();
		}
	});
/**
 *   - реализация игры Полный круг
 */
	/**
	 * Набор очков
	 * @param {Object} nm
	 * @param {Object} MScore
	 */
	var game_FR=game.newClass({
		constructor:function(nm){
			this.toString=function(){return nm;};
		},
		NEWTURN:function(){
			if(this.turn<25) this.inherited('NEWTURN');
			if(this.turn>20)this.turn=25;
		},
		NEWGAME: function(){
			this.turn = 1;
			this.allpl(function(i){
				this.init(i);
				this.score = 0;
			});
		},

		RECALC: function(idx, all){
			// суммируем с счетом!!!
			var player = this.pl[0];
			var x = pane.arrows, score = player.score;
			for (var i = 0; i < x.length; i++)
				if(x[i]._val.val==this.turn)
					score+=x[i]._val.rval;
				else
					disable(x[i]);
			player.score = score;
			pane.refresh();
		}
	});

/**
 *   - реализация игры 501
 */
	var game_501=game.newClass({
		constructor:function(nm,MScore){
			if(MScore)this.maxscore=MScore;
			this.toString=function(){return nm;};
		},
		maxscore: 501,
		NEWGAME: function(){
			this.turn = 1;
			var max=this.maxscore;
			this.allpl(function(i){
				this.init(i);
				this.score = max;
				this.refresh();
			});
		},

		RECALC: function(idx, all){
			// если общий счет меньше 0 - то все запрещается!!!
			var player = this.pl[0];
			var x = pane.arrows, score = player.score;
			for (var i = 0; i < x.length; i++)
				if (!x[i]._val.disabled) {
					score -= x[i]._val.rval;
				}
			if (score < 0) {
				for (var i = 0; i < x.length; i++) disable(x[i]);
			}
			else
				player.score=(score != 0)?score:"WINNER";
			pane.refresh();
		}
	});
/**
 *   - реализация игры Американский крикет
 *   первый игрок, набравший 3xCектор очков в секторе - "закрывает" его
 *   играются сектора 25,20,19,18,17,16,15.
 *   игра ведется пока все сектора не закрыты
 */
	var game_Akriket=game.newClass({
		constructor:function(nm,noscore){
			//$('#content').hide();
			this.toString=function(){return nm;};
			this.noscore=!!noscore;
		},
		init: function(pln){
			this.inherited('init',[pln,player_kriket]);
		},
		NEWGAME:function(){
			this.turn=1;
			if(this.noscore)$('#score').hide();
			this.allpl(function(i){
				this.init(i,true);
				this.sectors={x15:0,x16:0,x17:0,x18:0,x19:0,x20:0,x25:0};
				this.score=0;
			});
			$('#kriket').css({display:'',opacity:0.80});
			// only 2 player!!!
			this.pl[0].asect=this.pl[1].sectors;
			this.pl[1].asect=this.pl[0].sectors;
		},
		serialize:function(pl,restore){
			var res=this.inherited('serialize',[pl,restore]);
			if(!restore)res.sectors={};
			for(var i in pl.sectors ){
				if(restore)
					pl.sectors[i]=restore.sectors[i];
				else
					res.sectors[i]=pl.sectors[i];
			}
			return res;
		},
		RECALC:function(){
			var player=	this.pl[0];
			var x=pane.arrows,score=player.score;
			for(var i=0;i<x.length;i++) {
				var t='x'+x[i]._val.val;
				if (player.sectors.hasOwnProperty(t)){
					var k = x[i]._val.m;
					while(k--){
						if((player.asect[t]<3)&&(player.sectors[t]>=3)){
							player.score+=x[i]._val.val;
						}
						if(player.sectors[t]<3){
							player.sectors[t]++;
						}
					}
				} else
					disable(x[i]);
			}
			player.refresh();
		}

	});
	pane.games=[ new game_501('game0',201)
				,new game_501('game1',301)
				,new game_501('game2',501)
				,new game_Akriket('game3')
				,new game_Akriket('game4',true)
				,new game_Points('game5')
				,new game_FR('game6')
				];
	pane.startgame=function(g,pln){
		var i,pl=[];
		if (startgame.players)pln=startgame.players;
		if(!(pln instanceof Array))
			pln=new Array(parseInt(pln));
		(this.games[g - 1]).init(pln);

	//    new game_501([new player(),new player()]);
	// начальные телодвижения
		this.handle('NEWGAME');
		this.handle('BEFORETURN');
		this.refresh();
	};
	dart_pane=pane;
	var x=pane.$('ng-button');
	if (startgame) {
		var resultsaved=false;
		window.onbeforeunload=function(){
			if(!resultsaved )return 'Состояние игры не сохранено!';
		};
		$('#ng-button').html('Save');
		//x.innerHtml='Save';
		pane.add_event(x,'click',function(e){
			var obj={
				tour:startgame.tour,
				rule:startgame.rule,
				score:[],
				trace:[]
			};

			// заполняем очки игроков
			var gm=pane.games[startgame.rule - 1],winscore=10000;
			gm.allpl(function(){
				obj.trace[this.el.id]=this.sav;
				obj.score[this.el.id]=this.score;
				if (this.score<winscore){
					obj.winner=this.el.id;
					winscore=this.score;
				}
			});
//			alert(1);
			chain(Ajax,'save',obj)
			.chain(
				function(result, errors,cb){
//                    alert([result, errors]);
					if(errors)debug.trace(errors).wait();
					resultsaved=true;
//					history (свойство объекта window) содержит список адресов документов HTML, ранее загружавшихся в браузер.
					if (history.length) {
						history.go(-1);
					}
					else {
						alert('Результаты сохранены! Перейдите на "предыдущую страницу" (Back) для выбора новой игры.');
					}
					cb();
					//pane.$('ng-button').setAttribute('disabled','true');
				}
			).run();
			return element.clearEv(e);
		});
	} else {
		pane.add_event(x,'click',function(){
			window.location.reload();
			// if nothing happen - make simple reload
		});
	}
	x=null;


});
