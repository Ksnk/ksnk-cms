
function flv_player_start(file_name) {
//alert('11111');
	document.getElementById('flv_player').style.position = 'fixed';
	if(window.navigator.appName == 'Microsoft Internet Explorer') {
		document.getElementById('flv_player').style.position = 'absolute';
		document.getElementById('flv_player').style.top = document.documentElement.scrollTop + 100 + 'px';
		document.getElementById('flv_player').style.left = '250px';
	}
	else {
		document.getElementById('flv_player').style.position = 'fixed';
		document.getElementById('flv_player').style.top = '100px';
		document.getElementById('flv_player').style.left = '250px';
	}
	document.getElementById('flv_player').move_this='true';
	document.getElementById('flv_player').innerHTML = '<div id="verh_panel"><a href="javascript:flv_player_stop();"><img src="img/krest.gif"/></a></div><object type="application/x-shockwave-flash" data="uflvplayer_500x375.swf" height="300" width="400"><param name="bgcolor" value="#FFFFFF" /><param name="allowFullScreen" value="true" /><param name="allowScriptAccess" value="always" /><param name="movie" value="uflvplayer_500x375.swf" /><param name="FlashVars" value="way=' + file_name + '&amp;swf=uflvplayer_500x375.swf&amp;w=400&amp;h=300&amp;pic=http://&amp;autoplay=1&amp;tools=1&amp;skin=white&amp;volume=70&amp;q=&amp;comment=" /></object>';
}

function flv_player_stop() {
	document.getElementById('flv_player').innerHTML = '';
}

var inX=0;
var inY=0;

function qwe(e){
if (document.all) e=window.event;
inX=e.clientX;
inY=e.clientY;
dX=ind.style.left.replace('px','')*1;
dY=ind.style.top.replace('px','')*1;
flo=1;
}

function move(e){
if (document.all) e=window.event;
if (flo==1){
posX=e.clientX-inX+dX;
posY=e.clientY-inY+dY;
if (posX<0) posX=0;
if (posY<0) posY=0;
ind.style.top=posY+'px';
ind.style.left=posX+'px';
}
};


function stopQWE(){
flo=0;
}