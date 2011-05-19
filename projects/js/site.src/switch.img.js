	$('.switch_img .hidden').removeClass('hidden').css('opacity',0.01);

	var flag=0,current=0,cont=false;
	$('.switch_img').hover(function(){
		cont=true;
		if(flag++>0) return false;
		var self=this;
		var handle=function(){
			var x=$(self).find('div.img');
			x.eq(current).animate({opacity:0.01,speed:'normal'},function(){
				if(++current>=x.length) current=0;
				x.eq(current).animate({opacity:0.99,speed:'normal'},function(){
					flag=0;
					if(cont)
						setTimeout(handle,1000);
				})
			});
		};
		setTimeout(handle,1000);
	},function(){
		cont=false;
	})