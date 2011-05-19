
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

