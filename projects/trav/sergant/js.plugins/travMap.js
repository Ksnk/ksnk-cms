/**
 *  ������ ��� ��� �������� ����� � ����
 */
function plugin_travMap(engine){
	
	var self= new create_plugin(engine,{ name: 'travian Map'
	});

	// ���������� engine
	engine.map=function(){
		
	}
	
	return self;
}