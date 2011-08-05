
/**
 * main constants
 */
var 
	logLevel = 10,
	server = location.hostname,
	rootPath = "http://" + server + "/",
//	suffixLocal, suffixGlobal,
	last_but_notleast=0;

function FM_log(level, text) {
	if(logLevel>=level) {
		GM_log(text);
	}
}

//language functions
function T(str) { //String Translation
	var name = str.toUpperCase();
	if ((typeof lang!='undefined') && lang[name]){
		return lang[name];
	} else {
		str = str.toLowerCase();
		return "^" + str.substr(0, 1).toUpperCase() + str.substr(1);
	}
}

/**
 *  plugins integration
 *  module is a function(engine) (never use new module directly) return an object width common intrface
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

function plugin_SoundAlarm(engine) {
    var self = { name: 'soundAlarm'
        ,init:function(engine) {
            this.engine = engine;
        }
        ,evt_page_loaded:function(par) {
            engine.trigger('addMenu', {line:'Sound',
                self:self,
                handler:self.start_alarm
            });
        }
        ,clear: function() {
            if (self.mpdiv) {
                document.body.removeChild(self.mpdiv);
                self.mpdiv = false;
            }
        }
        ,start_alarm : function(snd) {
            self.clear();
            if (!self.mpdiv) {
                var mpdiv = document.createElement('div');
                if (!snd) snd = 'http://ksnk.dpb.ru/ELPHRG01.WAV';
                mpdiv.innerHTML = "<embed id='snd' src='" + snd + "' autostart=true height=42 loop=false controller=true hidden=true></embed>";
                self.mpdiv = document.body.appendChild(mpdiv);
            }
        }
    };
    return self;
}

/**
 * main function
 */
function main() {
	engine.init();
	engine.plugin([
        plugin_MainMenu
	//    ,plugin_FarmingMachine
	    ,plugin_SoundAlarm
	]);
	engine.log(9,'main- finish ');

	engine.trigger('page_loaded',null);
	engine.style('',true); // apply all styles;
	
	// let's do all goals
	engine.handle_goals();
}

// let's get rollin'

if (window.addEventListener) {
	FM_log(9,'just start! ');
	window.addEventListener('load', main, false);
	window.addEventListener('unload', function(){engine.trigger('unload');}, false);
} else {
	window.attachEvent('onload', main);
}
