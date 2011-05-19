/** лесколово и византия - сменяющееся изображение при наведении на строку меню **/
$("#menu_id a").mouseover(function(){
	var img=new Image(),x=$(this).html(),src=this.getAttribute('pic_big');
	function oncompl(){
		$("#loading").hide();
		$("#xxx")[0].src=img.src;
		$("#xxx").stop().show(); /*fadeTo("fast", 1,function(){
			this.style.filter='';
		});*/
		this.onload=null;
	};
	img.onload=oncompl;
	img.src=src;
	if(!img.complete){
		$("#loading").show();
		$("#xxx").stop().hide(); //fadeTo("fast", 0.01);
	} else {
		if(img.onload) img.onload.apply(img);
	}
	$("#tit_place").html(x);
})
$("#menu_id a").eq(0).trigger('mouseover');
