/**
 * Двигл для Драг нах остен... Драг & дроп
 * @param {Object} opt
 */
var $DDR=(function(opt){
	/**
	 * Функция, вызывающаяся по окончании DDR. Дегает on_dragfin, on_resizefin
	 * @param {Object} e
	 * @param {Object} opt
	 */
	var mouseup_func = function(e, opt, actx, acty){
		element.clear_events(opt.id[actx]);
		if (opt.on_movefin)if (actx == "left")opt.on_movefin(opt);
		if (opt.on_resizefin)if (actx=="width")opt.on_resizefin(opt);
	};
	/**
	 * собственно обработка движения мышки
	 * @param {Object} e
	 * @param {Object} opt
	 * @param {Object} actx
	 * @param {Object} acty
	 */
	var mouseact_func = function(e, opt, actx, acty){
		var dx = opt.posx - e.clientX, dy = opt.posy - e.clientY;
		opt.posx = e.clientX;
		opt.posy = e.clientY;
		opt['_' + actx] -= dx;opt['_' + acty] -= dy;
		for (var xx in opt.t) {
			var x=opt.t[xx];
			x.style[actx] = Math.min(Math.max(opt['_' + actx],opt.min[actx]),opt.max[actx]) + 'px';
			x.style[acty] = Math.min(Math.max(opt['_' + acty],opt.min[acty]),opt.max[acty]) + 'px';
		}
		if (opt.on_move)if (actx == "left")opt.on_move(opt,e);
		if (opt.on_resize)if (actx=="width")opt.on_resize(opt);
		return element.clearEv(e)
	}
	/**
	 * инициализация. Вызывается по началу операции ddr
	 * @param {Object} opt
	 * @param {Object} actx
	 * @param {Object} acty
	 */
	function t2eq(opt,actx,acty){
		var offset =element.getBounds(opt.t[0]);
		for(var i=0;i<opt.t.length;i++){var x=opt.t[i];
			opt['_'+actx]=offset[actx];//offset[actx];
			opt['_'+acty]=offset[acty];
			if(i) element.css(x,offset);
		}
	}
	/**
	 * act - "затравка". Выставляет обработчики событий.
	 * @param {Object} opt
	 * @param {Object} o
	 * @param {Object} actx
	 * @param {Object} acty
	 */
	function act(opt,o, actx, acty){
		// фенечка для выравнивания границ всех движимых объектов
		setTimeout(t2eq.bind(opt[o],opt, actx, acty),10);
		// сочиняем уникальный id, если надо
		if(!opt.id)opt.id=[];
		opt.id[actx]=opt.id[actx]||opt[o].id || '_dd_' + (element.unique++);
		element.add_event(opt[o], 'mousedown', function(e, opt){
			// очистить старые хандлы
			element.clear_events(opt.id).set(opt.id);
			if (opt.on_start)opt.on_start(opt);
			opt.posx = e.clientX;
			opt.posy = e.clientY;
			t2eq.call(opt[o],opt, actx, acty)
			element.set(opt.id[actx])
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
		var x =element.getBounds();
		opt.min={left:-100,top:-100,width:20,height:20};
		opt.max={left:x.width,top:x.height,width:x.width,height:x.height};
		if (opt.drag)	act(opt,'drag', 'left', 'top');
		if (opt.resize) act(opt, 'resize', 'width', 'height');
	}
})();
