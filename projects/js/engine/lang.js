	/**
	 * Функция, заменяющая Ajax,
	 * chain-технологию в жизнь 
	 * chain + 20 секунд на перформ запроса
	 */
	function Ajax(mod,content,callback){
		url="index.html?query[]="+mod;
	    var req = new JsHttpRequest();
	    req.caching = false;
		
	    req.onreadystatechange = function() {
	        if (req.readyState == 4) {
				callback(req.responseJS, req.responseText);
	        }
	    }
	    var method = null;
	    req.open(method, url, true);
	    req.send(content);
		return setInterval(function(){callback(null,'timeout');},20000);
	};

	function _l(h,v){
		var hash=[];
		return (_l=function (h,v){
			if(v) hash[h]=v;
			else if(hash[h]) return hash[h];
			else return null; 
		})(h,v)
	};
