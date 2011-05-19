/**
 * Инициализация  цепочки  исполняемых  функций.
 *
 * !!!!!!!!!интерфейсные соглашения о "функциях анимации"!!!!!!
 * каждая  функция  анимации  обязана  по  своему  завершению обеспечить вызов
 *     функции  arguments.callee.next  либо передав ее callback'ом кому нибудь
 *     либо явно вызвав if(arguments.callee.next)arguments.callee.next();
 *
 * Все  функции  анимации, ОБЯЗАНЫ использовать setInterval вместо setTimeout.
 *     Делить  и  разбираться  сразу  с  двумя типами хандлеров мне показалось
 *     слишком круто.
 *
 * Все  функции  анимации,  использующие  setInterval обязаны выдавать его как
 *     результат своей работы.
 *
 * Если  функция использует setInterval и не выдает его в качестве результата,
 *     исполнение этой функции не будет остановлено вызовом chain.clear();
 *
 * Никакой другой результат выдаваться не должен
 *
 * !!!!!!!!Описание внешних функций Chain!!!!!!!!!
 *
 * Chain() - вызов без параметров инициализирует новый объект Chain
 * Chain(a,b) эквивалентен Chain().chain(a,b)
 * chain.chain(a,b)  -  поместить  в  очередь  вызов функции a с праметрами b.
 *     Если   функция   принимает   несколько   параметров  -  все  параметры,
 *     необходимые  для  нее  передаются  в  массиве b. если функция принимает
 *     единственный параметр - массив - он ОБЯЗАН быть передан как [b]
 * chain.run()  -  запуск  очереди на выполнение. Можно вызывать неоднократно.
 *     Если процесс уже идет вреда не будет.
 * chain.wait(ms) - вставить в цепочку команду задержки на ms миллисекунд
 * chain.clear() - остановить выполнение цепочки
 *
 * Каждая  внешняя  функция  возвращает  собственный объект как результат и по
 *     этому можно писать цепочки таких вызовов...
 *
 * @param {Object} a
 * @param {Object} b
 * @param  {Object}  flag  - установка этого параметра помечает ОБЯЗАТЕЛЬНОСТЬ
 *     вызова этого и последующих методов даже в случае clear()
 * @example var x=new Chain(); x.wait(3000).chain(exec,'xxx').run();
 * @example Chain(show,'xxx').wait(3000).chain(exec,'xxx').run();
 * @example примеры функций анимации
 *
 * function collapse(o,_to){
 * 	   return animate(o,_to,0,arguments.callee.next);
 * }
 *
 * function hideit(o){
 *     o.style.display='none';
 *     o.style.width='0px';
 *     if(arguments.callee.next)arguments.callee.next();
 * }
 */
function chain(a,b,c,flag){
    var x =new Array();
    x.running = false;
	// совершенно приватная функция
	// остановим таймаут, если есть...
    function stop(){
        if(x.timeout)
            clearInterval(x.timeout);
        x.running=false;
        x.timeout=null;
    }
	// выполним следующий элемент
    function exec(xx,a,b){
        if ((x.running = (xx && xx.a))) {
            if(xx=xx.a(a||xx.b,b||xx.c,x.next))
				x.timeout=xx;// экономим на идентификаторах... ;-)
        }
    };
	// common callback handler
    x.next = function(a,b){
        stop();
        if (x.length) {
            exec(x.shift(),a,b);
        }
        return x;
    }

	// main chain function
    x.chain=function(a,b,c,flag){
        if(!a) return this;
       // if(!(b instanceof Array)) b=[b];
        this.push({a:a,b:b,c:c,flag:flag});
        return this;
    }

	// perform waiting in a row
    x.wait=function(ms){
        var xx=this;
        return this.chain(function (ms,N,callback){
	// так сложно потому что setInterval норовит вызвать функцию с параметром, а мне нужно - без.
                return setInterval(function(){
					callback()
				},ms)
            },ms)
    }

	// stop all pending animation
	// убираем все элементы сначала и до первого с установленным параметром flag.
	// С этого элемента продолжаем исполнение цепочки
    x.clear=function(){
        stop();
        var x={};
        while(this.length && (!x.flag)) x=this.shift();
        if(x && x.flag) exec(x);
        return this;
    }

	// run the chain in case it stopped
    x.run=function(){
        if(this.running) return this;
        else return this.next();
    };

    return x.chain(a,b,c,flag);
};
