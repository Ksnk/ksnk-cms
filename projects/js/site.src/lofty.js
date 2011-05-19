/**
 * поддержка расширения элементов на всю доступную броузеру высот
 * .lofty
 * 
 * элемент вынимается из лейаута(hide), после чего ему ставится нужный размер(parent.client.height)
 */

$(function(){
	setTimeout(function(){
		$('.lofty').css({display:'none',overflow:'auto'});
		setTimeout(function(){
			$('.lofty').each(function(){
				var prev,parent=this;
				while(true){
					prev=parent;
					parent=parent.parentNode;
					if(!parent) break;
					if(parent.style.height=='100%'){
						var $h=$(parent).innerHeight()-$(prev).innerHeight();
						if($h>0){
							$(this).css({height:$h,display:'block'})
						}
						break;
					}
				}
				var oldheight=$(document.body).height();
				$(window).bind('resize',function(){
					var newheight=$(document.body).height()
						,disp=newheight-oldheight
						;
					oldheight=newheight;
					if(!!disp)
					$('.lofty').each(function(){
						$(this).css('height',$(this).height()+disp/2);
					})
				});
			});
		},10);
	},10);
	
	
})