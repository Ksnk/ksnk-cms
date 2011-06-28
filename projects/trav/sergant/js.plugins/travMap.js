/**
 *  плагин Мар для хранения карты в игре
 */
function plugin_travMap(engine){
	
	var self= new create_plugin(engine,{ name: 'travian Map'
	});

	// расширения engine
	engine.map=function(){
		
	}
	
	return self;
}