function WindowO(e,s,w,h)
{
	var $x=$(e).attr('href');
//	if($x && ($x.length>2)) { return true;}
	if(s.match(/\/$/)) return false ;
	try{
	  var par="location=no,toolbar=no,resizable=yes";
	  if((w+100>window.screen.width)||(h+100>window.screen.height))
	  	par+=",scrollbars=yes"
  	  par+=',width='+(w||100); 
 	  par+=',height='+(h||100);
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
};window.WindowO=WindowO;
    $first=null;
	$('.galleryX a').each(function(){
		if(this.href && this.href.match(/\.(jpe?g|png|gif)/)) {
		    this.__done=true;
		    $(this).click(function(){ 
				var t=$(this).parents('.galleryX').find('img')[0];
				t.src=this.href;
				return false
			})
			if(!$first){
				$first=true;
				$(this).click();
			}
			
		}			
	})
	$first=null;
	$('.gallery a, .cat_border a').removeAttr('onclick').each(function(){
		if(this.href && this.href.match(/\.(jpe?g|png|gif|flv|swf)/)) {
			if(!this.__done)
				if($.fn.colorbox)
					$(this).colorbox();
				else if($.fn.colorbox)
					$(this).colorbox();
				else				
					$(this).click(function(){return WindowO(this,this.href)});
		} else if(this.href && this.href.match(/#$|uploaded\/$/)) {
			$x=$(this).html();
			if($x)
				$(this).replaceWith($x);
		}
		$(this).find('img').bind('load',checkImg).each(function(){
			if(this.complete)
				checkImg(this);
		});
	});
