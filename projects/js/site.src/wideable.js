	$(window).resize(function(){
		$('.wideable').css({width:200,visibility:'hidden'}).each(function(){
			var self=this;
			var interval=setInterval(function(){
				var width=self.parentNode.clientWidth||self.parentNode.scrollWidth;
				if(!width)
					width=self.parentNode.parentNode.clientWidth;
				if(!width){
					//$('#debug').html($('#debug').html()+self.parentNode.tagName);
					clearInterval(interval);
					return;
				}
				//$('#debug').html($('#debug').html()+'<br>'+width)
				clearInterval(interval);
				$(self).css({visibility:'visible',width: width});
				self=null;
			},50)
		})		
	}).trigger('resize');
	$('.scroll_gal').each(function(){
		
		var div,lock;
		/**
		 * поведение - 30px с краев
		 */
		var  mousemove =function(e){ 
			if(lock)return;
			// вычисление областей:
			//  0-30 - скролл вправо, 30 - середина-30 
			//  - скролл вправо середина - ничего
			//  0-30 - скролл вправо, 30 - середина-30 
		    var speed,left = Math.max(0,
		    	(e.pageX - div.offset().left) 
		    	/ this.clientWidth);
		    // left - 0..1;
		    if      (left<0.1) {left=0;speed="fast";}
	/*	    
		    else if (left<0.3) {left=(left-0.1)*2.5;speed="slow";}
		    else if (left<0.7) return ;
	 */	    
		    else if (left<0.9) {left=(left-0.1)*(1/0.8);speed="slow";}
		    else  {left=1 ;speed="fast";}
		    
		    left = left * (this.scrollWidth-this.clientWidth); 
		    	
			if(Math.abs(div.scrollLeft()-left)>30){
				lock=true;
				div.stop(true,false).animate({ scrollLeft:left},speed,function(){lock=false});
			} else
		    	div.scrollLeft(left);
		  };
		
		$(this).hover(
			function(){ div=$(this);$(this).bind('mousemove',mousemove)},
			function(){div=null;$(this).unbind('mousemove')}
		);		
	})
