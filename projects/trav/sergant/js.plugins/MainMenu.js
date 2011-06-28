/**
 *   отображает окошко, менюшку + добавляет обработчик AddMenuString
 *   менюшка находится в нижней првой части экрана
 */
function plugin_MainMenu(engine){
	
	var self= new create_plugin(engine,{ name: 'MainMenu'
		/**
		 * Вставить меню в окно
		 */
		,insertMenu:function(){
				
			engine.log(3,this.name+"insertMenu() called");
			var menu = document.createElement('div');
			menu.setAttribute('id', 'xx_mainmenu');
		
			menu.innerHTML='menu<hr><ul id="ul_mainmenu"></ul>';
			var parent=( document.body);
			parent.style.position='relative';
			menu=parent.appendChild(menu);
			menu.addEventListener('mouseover',self.showmenu, false);
			menu.addEventListener('mouseout',self.hidemenu, false);
			self.menu=menu;
			FM_log(3,"MainMenu: menu inserted");
		
		}
		,showmenu:function(e){
			self.menu.style.overflow="auto";
			self.menu.style.height="auto";
		}
		,hidemenu:function(e){
			self.menu.style.overflow="hidden";
			self.menu.style.height="30px";
		}
	
	});
	
	engine.style(
		'#xx_mainmenu {height:30px; overflow:hidden;z-index:1000;position:absolute; top:0; right:0;  width:100px; background:green;}; '+
		''
	);
	/**
	 * initialisation
	 */
	engine.handle('page_loaded',function (){
		if (!document.location.href.match(/manual\.php/))
			self.insertMenu();
	});
	
	/**
	 * To add a new menu line
	 */
	engine.handle('addMenu',function (par){
		var ul=(engine.find("//ul[@id='ul_mainmenu']"));
		if (ul) {
			var li=document.createElement('li');
			li.innerHTML=par&&par.line||'unknown menu line';
			li.addEventListener('click', function(){
				if(par.handler){
					if (par.self)
						par.handler.call(par.self);
					else
						par.handler();
				};
			},false);
			li=ul.appendChild(li);
		}
		
	});
	
	return self;
}

