function WindowO(e,s,w,h)
{
	if(typeof(e)!='string'){
	var $x=$(e).attr('href');
	if($x && ($x.length>2)) { return true;}
	if(s.match(/\/$/)) return false ;
	} else {
		h=w;w=s;s=e;
	}
	try{
	  var par="location=no,toolbar=no,resizable=yes";
	  if((w+100>window.screen.width)||(h+100>window.screen.height))
	  	par+=",scrollbars=yes"
	  if(w) par+=',width='+w;
 	  if(h) par+=',height='+h;
	  wind=open("","win",par);
	  wind.document.writeln('<html>'+
		'<head><title><'+'/title><style>body,html{width:100%;height:100%;padding:0;margin:0;}\n'+
		'body{overflow:auto;}\n</'+'style>\n<'+'script>function fitPic(){'+
	 	'iWidth = window.innerWidth||document.body.clientWidth;'+
	 	'iHeight = window.innerHeight||document.body.clientHeight;'+
		'iWidth = document.images[0].width - iWidth;'+
		'iHeight = document.images[0].height - iHeight;'+
		'if(iWidth && iHeight)window.resizeBy(iWidth,iHeight);self.focus();'+
		//'alert([window.screen.height,window.screen.width, iWidth,iHeight,document.images[0].width,window.innerWidth,document.body.clientWidth]);'+
		'};</'+'script>'+
		'<'+'/head><'+'body onload="fitPic()">'+
		'<img src="'+s+'"><button style="position:absolute;right:10px;bottom:10px;" '+
		'onclick="self.close();">close</button><'+'/body><'+'/html>');
	  wind.document.close();
	} catch(e) {
	  alert('Всплывающие окна заблокированы! Разрешите всплывающие окна для нормального функционирования.')
	}
	return false;
};