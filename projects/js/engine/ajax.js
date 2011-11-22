;(function(){
	/**
	 * выдать XmlHTTP Request
	 */
	function getReq(){
		/* Создание нового объекта XMLHttpRequest для общения с Web-сервером */
		var req = false;
		/*@cc_on @*/
		/*@if (@_jscript_version >= 5)
		try {
		  req = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
		  try {
		    req = new ActiveXObject("Microsoft.XMLHTTP");
		  } catch (e2) {
		    req = false;
		  }
		}
		@end @*/
		
		if (!req && typeof XMLHttpRequest != 'undefined') {
		  req = new XMLHttpRequest();
		}
		return req;
	}
/**
 * загрузить чего-то откуда-то
 * @param {Object} url
 * @param {Object} data
 * @param {Object} callback
 */
	function getUrl(url,data,callback) {
		var req=getReq();
		if (req) {
			var reqTimeout;
			req.onreadystatechange = function(){
				if (req.readyState == 4) {
					clearTimeout(reqTimeout);
					if(req.status == 200)
					  callback(req, '');
					else
					  callback(req,'status '+req.status||'null');//Text||'error');
					req.onreadystatechange=function(){};
					req=null;
				}
			}
			req.open("POST", url, true);
			req.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			if(data){
				if(typeof(data)!='string')
					req.send(element.Ajax.serialize(data));
				else
					req.send(data);
			} else {
				req.send('');
			}
			reqTimeout = setTimeout(function(){
				if (req && req.abort)req.abort();
				if (req && req.stop)req.stop();
				callback(null,'timeout')
			}, 40000);
		} else {
			throw "Браузер не поддерживает AJAX";
		}
	}

element.Ajax={
serialize: function (o){
	var s=[];
	for(a in o)
		s.push(a+'='+encodeURIComponent(o[a]))
	return s.join('&');
},
unserialize: function(s){
    var reg=/(\w*)=(.*?)(&|$)/g;
    var o={}
    while(reg.exec(s)){
       o[''+RegExp.$1]=''+RegExp.$2;
    }
    return o;
},
get: getUrl,
getJSON: function (url,data,callback){
	return getUrl(url,data,function(data,status){
		if(status)
			return callback(data,status);
		try {
			var data = eval("(" + data.responseText + ")");
			if(!data)data={};
			if (data.debug)
				alert(data.debug);
			if (data.error)
				alert(data.error);
		} catch(e){
			if(!data)data={};
			//alert([data.responseText||'']);
		}
		if(callback){
			callback(data,data.error);
		}

	});
}
}
})() // автоматическое выполнение
/**
 * Поддержка глубокомысленного реквеста. Один реквест на пачку запросов
 * Основная суть - поле query реквеста является массивом - списком запросов,
 * остальные поля реквеста дополняются нужными данными "внахлест"
 * Получившийся монстр затем отсылается за один раз.
 * По приходу ответа, все callback'и частных реквестов вызываются.
 * Это - !!!ЕДИНСТВЕННАЯ!!! точка соприкосновения с JsHttpRequest'ом
 */
element.merge({
	req_add:function(req,par,callback) {
		this.req.query.push(req);
		this.merge(par,this.req);
		if(callback) {
			if(this.req_callback){
				var past_callback=this.req_callback;
				this.req_callback=function(r,e){
					past_callback(r,e);	callback(r,e);
				}
			}else
				this.req_callback=function(r,e){
					callback(r,e);
				}
		}
		if (!this.req_to) this.req_to=this.request.delay(50,this);
	},

	request:function () {
		this.req_to=0;
		var th=this;
		if (this.req) {
			var cb=this.req_callback;
			JsHttpRequest.query("GET index.html", this.req, function(result, errors){
				try {
					if (cb)	cb(result, errors)
				}
				catch (e) {
					;
				}
				if (errors && th.req_error)
					th.req_error(errors);
				if (th.req.mod.length)
					if (!th.req_to)
						th.req_to = th.request.delay(50, th);
			}, true);
		}
		this.req={query:[]};
		this.req_callback=null;
	}
}).request();// инициализация !!!
