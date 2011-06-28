// ==UserScript==
// @name 		Browser automationю
// @author		ksnk
// @namespace 	Automaition
// @version 	0.0.0.4
// @description	Just automate some actions
// @source 		http://fck.me/trav
// @identifier 	http://fck.me/trav/trav.user.js
// @include 	http://fck.me/trav/*.*
// @exclude 	*.css
// @exclude 	*.js
// ==/UserScript==
/**
 * engine - is an object with interface
 *  -- export(pluginName) - get and install reqired plugin
 *  -- callFunc(pluginName,functName,arguments) - directly call plugin function
 */
var engine={
	/** private */
	__handler:{},
	__store:{},
	id:  12345 ,
	/** init function */
	init:function(){
		this.handle('unload',this.__clear,this);
	//	this.handle('unload',this.store,this);
	},
	
	/**
	 *  parameter - plugin function or array of plugin function 
	 */
	plugin:function(plugins){
		if (plugins instanceof Array){
			while(plugins.length){
				FM_log(9,'-- length '+plugins.length);
				this.plugin(plugins.pop());
			}
			return;
		}
		if(typeof(plugins)=='function'){
			plugins(this);
		} else {
			FM_log(0,'Abnormal parameters');
		}
	},
	/**
	 * store and retrieve info from load to load
	 * depth  && 1 == 0 - per run, no store at all
	 * depth  && 2 == 1 - per run, no store at all
	 * 
	 */
	store:function(index,info,depth){
	    //if(!depth || depth<1)
		GM_setValue(this.index+' '+index||'',serialize(info));
		this.__store[index]=info;
	},
	get:function(index){
		var t =GM_getValue(this.index+' '+index||'');
		if(!t) return '';
		return unserialize(t);
	},
	/**
	 * event handling function
	 */
	handle:function(evt,func,clsr){
		if(!this.__handler[evt]) this.__handler[evt]=[];
		this.__handler[evt].push([func,clsr]);
	},
	trigger:function(evt){
		FM_log(9,'triggered '+evt+' '+this.id);
		if(this.__handler && this.__handler[evt] && this.__handler[evt].length){
			FM_log(9,evt+' found '+this.id);
			for(var i=0,j=this.__handler[evt];i<j.length;i++){
				if(typeof(j[i][0])!='function') {
					FM_log(9,evt+' '+i+' fail '+j[i][0].toString());
				} else {
					j[i][0].call(j[i][1]||null);
				}
			}
		}
	},
	__clear:function(){
		FM_log(9,'called clear'+this.id);
		this.__handler=null;
	}
};

/**
 *  Заполнить форму на экране нужными значениями
 */
function plugin_FormFiller(engine){
	var plugin_name='FormFiller',
		form_page='test.html';
	
	FM_log(9,'plugin_FormFiller init');
	var self= new create_plugin(engine,{
		name: plugin_name
		,state : engine.get(plugin_name+'_state')
	});
	engine.handle('page_loaded',function(){
		FM_log(9,'plugin_FormFiller. found page!!!');
		var reg= new RegExp("(.*)"+form_page);
		if (location.href.match(reg)){
			FM_log(9,'plugin_FormFiller. found page!!!');
			// form found.. fill em
			// search a task and fill
			
			engine.store(plugin_name+'_lasttime',Date());
		};
		// check if last time checking more than 5 minutes ago
		var lastTime=engine.get(plugin_name+'_lasttime');
		if(lastTime-Date() > '5 минут'){
			//window.location=window.location.host+'/'+form_page;
		}
	},self);
	return self;
}
	/**
	 * serialize - unserialize
	 */
	function serialize( mixed_value ) {
	    // http://kevin.vanzonneveld.net
	    // +   original by: Arpad Ray (mailto:arpad@php.net)
	    // +   improved by: Dino
	    // +   bugfixed by: Andrej Pavlovic
	    // +   bugfixed by: Garagoth
	    // +      input by: DtTvB (http://dt.in.th/2008-09-16.string-length-in-bytes.html)
	    // +   bugfixed by: Russell Walker (http://www.nbill.co.uk/)
	    // %          note: We feel the main purpose of this function should be to ease the transport of data between php & js
	    // %          note: Aiming for PHP-compatibility, we have to translate objects to arrays
	    // *     example 1: serialize(['Kevin', 'van', 'Zonneveld']);
	    // *     returns 1: 'a:3:{i:0;s:5:"Kevin";i:1;s:3:"van";i:2;s:9:"Zonneveld";}'
	    // *     example 2: serialize({firstName: 'Kevin', midName: 'van', surName: 'Zonneveld'});
	    // *     returns 2: 'a:3:{s:9:"firstName";s:5:"Kevin";s:7:"midName";s:3:"van";s:7:"surName";s:9:"Zonneveld";}'
	 
	    var _getType = function( inp ) {
	        var type = typeof inp, match;
	        var key;
	        if (type == 'object' && !inp) {
	            return 'null';
	        }
	        if (type == "object") {
	            if (!inp.constructor) {
	                return 'object';
	            }
	            var cons = inp.constructor.toString();
	            match = cons.match(/(\w+)\(/);
	            if (match) {
	                cons = match[1].toLowerCase();
	            }
	            var types = ["boolean", "number", "string", "array"];
	            for (key in types) {
	                if (cons == types[key]) {
	                    type = types[key];
	                    break;
	                }
	            }
	        }
	        return type;
	    };
	    var type = _getType(mixed_value);
	    var val, ktype = '';
	    
	    switch (type) {
	        case "function": 
	            val = ""; 
	            break;
	        case "boolean":
	            val = "b:" + (mixed_value ? "1" : "0");
	            break;
	        case "number":
	            val = (Math.round(mixed_value) == mixed_value ? "i" : "d") + ":" + mixed_value;
	            break;
	        case "string":
	            val = "s:" + encodeURIComponent(mixed_value).replace(/%../g, 'x').length + ":\"" + mixed_value + "\"";
	            break;
	        case "array":
	        case "object":
	            val = "a";
	            /*
	            if (type == "object") {
	                var objname = mixed_value.constructor.toString().match(/(\w+)\(\)/);
	                if (objname == undefined) {
	                    return;
	                }
	                objname[1] = serialize(objname[1]);
	                val = "O" + objname[1].substring(1, objname[1].length - 1);
	            }
	            */
	            var count = 0;
	            var vals = "";
	            var okey;
	            var key;
	            for (key in mixed_value) {
	                ktype = _getType(mixed_value[key]);
	                if (ktype == "function") { 
	                    continue; 
	                }
	                
	                okey = (key.match(/^[0-9]+$/) ? parseInt(key, 10) : key);
	                vals += serialize(okey) +
	                        serialize(mixed_value[key]);
	                count++;
	            }
	            val += ":" + count + ":{" + vals + "}";
	            break;
	        case "undefined": // Fall-through
	        default: // if the JS object has a property which contains a null value, the string cannot be unserialized by PHP
	            val = "N";
	            break;
	    }
	    if (type != "object" && type != "array") {
	        val += ";";
	    }
	    return val;
	};
	function unserialize(data){
	    // http://kevin.vanzonneveld.net
	    // +     original by: Arpad Ray (mailto:arpad@php.net)
	    // +     improved by: Pedro Tainha (http://www.pedrotainha.com)
	    // +     bugfixed by: dptr1988
	    // +      revised by: d3x
	    // +     improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	    // +        input by: Brett Zamir (http://brett-zamir.me)
	    // +     improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	    // +     improved by: Chris
	    // +     improved by: James
	    // %            note: We feel the main purpose of this function should be to ease the transport of data between php & js
	    // %            note: Aiming for PHP-compatibility, we have to translate objects to arrays
	    // *       example 1: unserialize('a:3:{i:0;s:5:"Kevin";i:1;s:3:"van";i:2;s:9:"Zonneveld";}');
	    // *       returns 1: ['Kevin', 'van', 'Zonneveld']
	    // *       example 2: unserialize('a:3:{s:9:"firstName";s:5:"Kevin";s:7:"midName";s:3:"van";s:7:"surName";s:9:"Zonneveld";}');
	    // *       returns 2: {firstName: 'Kevin', midName: 'van', surName: 'Zonneveld'}
	 
	    var error = function (type, msg, filename, line){throw new this.window[type](msg, filename, line);};
	    var read_until = function (data, offset, stopchr){
	        var buf = [];
	        var chr = data.slice(offset, offset + 1);
	        var i = 2;
	        while (chr != stopchr) {
	            if ((i+offset) > data.length) {
	                error('Error', 'Invalid');
	            }
	            buf.push(chr);
	            chr = data.slice(offset + (i - 1),offset + i);
	            i += 1;
	        }
	        return [buf.length, buf.join('')];
	    };
	    var read_chrs = function (data, offset, length){
	        var buf;
	 
	        buf = [];
	        for(var i = 0;i < length;i++){
	            var chr = data.slice(offset + (i - 1),offset + i);
	            buf.push(chr);
	        }
	        return [buf.length, buf.join('')];
	    };
	    var _unserialize = function (data, offset){
	        var readdata;
	        var readData;
	        var chrs = 0;
	        var ccount;
	        var stringlength;
	        var keyandchrs;
	        var keys;
	 
	        if(!offset) {offset = 0;}
	        var dtype = (data.slice(offset, offset + 1)).toLowerCase();
	 
	        var dataoffset = offset + 2;
	        var typeconvert = new Function('x', 'return x');
	 
	        switch(dtype){
	            case 'i':
	                typeconvert = function (x) {return parseInt(x, 10);};
	                readData = read_until(data, dataoffset, ';');
	                chrs = readData[0];
	                readdata = readData[1];
	                dataoffset += chrs + 1;
	            break;
	            case 'b':
	                typeconvert = function (x) {return parseInt(x, 10) !== 0;};
	                readData = read_until(data, dataoffset, ';');
	                chrs = readData[0];
	                readdata = readData[1];
	                dataoffset += chrs + 1;
	            break;
	            case 'd':
	                typeconvert = function (x) {return parseFloat(x);};
	                readData = read_until(data, dataoffset, ';');
	                chrs = readData[0];
	                readdata = readData[1];
	                dataoffset += chrs + 1;
	            break;
	            case 'n':
	                readdata = null;
	            break;
	            case 's':
	                ccount = read_until(data, dataoffset, ':');
	                chrs = ccount[0];
	                stringlength = ccount[1];
	                dataoffset += chrs + 2;
	 
	                readData = read_chrs(data, dataoffset+1, parseInt(stringlength, 10));
	                chrs = readData[0];
	                readdata = readData[1];
	                dataoffset += chrs + 2;
	                if(chrs != parseInt(stringlength, 10) && chrs != readdata.length){
	                    error('SyntaxError', 'String length mismatch');
	                }
	            break;
	            case 'a':
	                readdata = {};
	 
	                keyandchrs = read_until(data, dataoffset, ':');
	                chrs = keyandchrs[0];
	                keys = keyandchrs[1];
	                dataoffset += chrs + 2;
	 
	                for(var i = 0;i < parseInt(keys, 10);i++){
	                    var kprops = _unserialize(data, dataoffset);
	                    var kchrs = kprops[1];
	                    var key = kprops[2];
	                    dataoffset += kchrs;
	 
	                    var vprops = _unserialize(data, dataoffset);
	                    var vchrs = vprops[1];
	                    var value = vprops[2];
	                    dataoffset += vchrs;
	 
	                    readdata[key] = value;
	                }
	 
	                dataoffset += 1;
	            break;
	            default:
	                error('SyntaxError', 'Unknown / Unhandled data type(s): ' + dtype);
	            break;
	        }
	        return [dtype, dataoffset - offset, typeconvert(readdata)];
	    };
	    
	    return _unserialize((data+''), 0)[2];
};

/**
 * main constants
 */
var 
	logLevel = 10,
	server = location.hostname,
	rootPath = "http://" + server + "/",
	suffixLocal, suffixGlobal,
	last_but_notleast=0;

/** 
 * just a log function
 */
function FM_log(level, text) {
	if(logLevel>=level) {
		GM_log(text);
	}
}

/**
 *  plugins integration
 *  module is a function(engine) (dont use new module directly) returnning object width common intrafce
 *  -- function init - just call once per reload
 */
// this is a template only. never use it directly, just 4 creating plugin body 
function create_plugin(engine, extend){
	var self={
		init:function(engine){
			this.engine=engine;
		},
		name:'template'
	};
	for(a in extend){
		self[a]=extend[a];
	};
	return self;
}
// plugin - Farm Factory
function plugin_FarmFactory(engine){
	FM_log(9,'FarmFactory init');
	return new create_plugin(engine,{
		name:'FarmFactory'
	});
}

/**
 * main function
 */
function main() {
	engine.init();
	engine.plugin([
	    plugin_FarmFactory
	    ,plugin_FormFiller
	]);
	FM_log(9,'main- finish ');
	
	engine.trigger('page_loaded');
}

// let's get rollin'

if (window.addEventListener) {
	FM_log(9,'just start! ');
	window.addEventListener('load', main, false);
	window.addEventListener('unload', function(){engine.trigger('unload');}, false);
} else {
	window.attachEvent('onload', main);
}

