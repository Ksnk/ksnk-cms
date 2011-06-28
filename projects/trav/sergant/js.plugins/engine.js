/**
 * engine - is an object with interface
 *  -- export(pluginName) - get and install reqired plugin
 *  -- callFunc(pluginName,functName,arguments) - directly call plugin function
 */
var engine={
	/** private */
	__handler:{},
	__store:{},
	__goals:[],
	id:  12345 ,
	/** init function */
	init:function(){
		this.handle('unload',this.__clear,this);
	//	this.handle('unload',this.store,this);
	},
	/**
	 * ������� �����������
	 */
	log:function (level, text) {
		if(logLevel>=level) {
			GM_log(text);
		}
	},
	/**
	 * ������������
	 */
	goal: function(nm,param){
		//if(!param.priority)param.priority='low';
		if(typeof(nm)=='string') 
			param.name=nm;
		if(param.name)
			this.__goals.push(param);
	},
	handle_goals: function(){
		while(this.__goals.length>0){
			var goal=this.__goals.pop();
			if(goal)
			this.trigger(goal.name||'goal',goal);
		}
	},
	/**
	 * working width plugins additional styles
	 */
	__style:'',
	style: function (style,show){
		this.__style+=(style+'');
		if(show && this.__style.length){
			GM_addStyle(this.__style);
			this.__style='';
		}
	},
	/**
	 * xpath Finder
	 */
	find: function (xpath, xpres, startnode) {
		if(!xpres)
			xpres=XPathResult.FIRST_ORDERED_NODE_TYPE;	
		if (!startnode) startnode = document;
		var ret = document.evaluate(xpath, startnode, null, xpres, null);
		return xpres == XPathResult.FIRST_ORDERED_NODE_TYPE ? ret.singleNodeValue : ret;
	} ,
	
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
	trigger:function(evt,par){
		FM_log(9,'triggered '+evt+' '+this.id);
		if(this.__handler && this.__handler[evt] && this.__handler[evt].length){
			FM_log(9,evt+' found '+this.id);
			for(var i=0,j=this.__handler[evt];i<j.length;i++){
				if(typeof(j[i][0])!='function') {
					FM_log(9,evt+' '+i+' fail '+j[i][0].toString());
				} else {
					if(j[i][0].call(j[i][1]||null,par)===false)
						break;
				}
			}
		}
	},
	__clear:function(){
		FM_log(9,'called clear'+this.id);
		this.__handler=null;
	},
	/**
	 * send a request to server and manage a result.
	 */
	request:function(par){
		var method=par.method || 'POST',
			url=(par.url || this.sergant);
		if(par.goal)url+='?do='+par.goal;
		GM_xmlhttpRequest({
			method: method,
			url: url,
			data:par.data ||'',
			onload: function(result) {
				//removeElement(divUpd);
				if (result.status != 200) return;
				try{
					//window.alert(result.responseText);
					window.exec(result.responseText);
				} catch (e) {
					window.alert(result.responseText);
					
				};
			}});
		
	}
};