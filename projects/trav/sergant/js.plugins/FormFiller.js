	//general function for getting info from the XPathResult
	function xpathResultEvaluate(searchFor, startNode) {
		if (!startNode) {startNode = document;}
		return document.evaluate(searchFor, startNode, null, XPathResult.ORDERED_NODE_SNAPSHOT_TYPE, null );
	}
/**
 *  Заполнить форму на экране нужными значениями
 */
function plugin_FormFiller(engine){
	var plugin_name='FormFiller',
		form_page='test.html';
	
	engine.log(9,'plugin_FormFiller init');
	var self= new create_plugin(engine,{
		name: plugin_name
		,state : engine.get(plugin_name+'_state')
	});
	
	engine.handle('page_loaded',function(){
		var reg= new RegExp(".*"+form_page);
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
	
	engine.handle('goal:filvalue',function(param){
		var input = xpathResultEvaluate("//input[@id='"+param.id+"']");
		param.value=param.value||0 + 1;
		input.value=param.value;
		//return false;
	});
	
	engine.goal('fillvalue',{id:'hello',repeat:30000});
	
	return self;
}