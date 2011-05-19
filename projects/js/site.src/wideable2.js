/**
 * скроллируемый pane с перемещением элементами по краям
 */
	$(window).resize(function(){
		$('.wideable').css({width:200,visibility:'hidden'}).each(function(){
			var self=this;
			var interval=setInterval(function(){
				var papa=self.parentNode;
				var width=papa.clientWidth||papa.scrollWidth;
				if(!width){
					papa=papa.parent.node;
					width=papa.clientWidth;
				}
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
	
	$('.scroll_gal2').each(function(){
		var lock=false;
		function reclass(el){
			var ww= el.find('table').width(),
				scroll=el.scrollLeft(),
				sw=el.width();
			// автомат переходов 
			// passive - no posible moving
			//  - normal
			// hover - button hovered
			// clicked - button pressed
			if (scroll==0){
				$(el).parent().find('.move_left').addClass('passive');
			} else {
				$(el).parent().find('.move_left').removeClass('passive');
			}
			if (ww<=scroll+sw){
				$(el).parent().find('.move_right').addClass('passive');
			} else {
				$(el).parent().find('.move_right').removeClass('passive');
			}
		}
		reclass($(this));
		
		$(this).parent().parent().find('.move_left,.move_right').hover(
				function(){$(this).addClass('hover')},function(){$(this).removeClass('hover')}
		).bind('click',function(e){ 
			if(lock)return;
			// вычисление областей:
			//  0-30 - скролл вправо, 30 - середина-30 
			//  - скролл вправо середина - ничего
			//  0-30 - скролл вправо, 30 - середина-30 
			
			var self=this,
				x=$(this).addClass('clicked').parent().find('.wideable');
			var speed='small'
		    	,step=this.parentNode.clientWidth/2;
		    if($(this).hasClass('move_left')) step = -step;
		   // alert(x.scrollLeft());
		    lock=true;
		    x.stop(true,false)
		    	.animate({ scrollLeft:x.scrollLeft()+step}
		    			,speed,function(){
		    				$(self).removeClass('clicked');
		    				reclass($(this));
		    				lock=false});
		  });
		
	})
