/**
 *  вывести на экран форму-таблицу
 *  
 */

function plugin_PropDialog(engine){
	FM_log(9,'plugin_PropDialog init');
	return new create_plugin(engine,{
		name:'FarmFactory'
	});
}