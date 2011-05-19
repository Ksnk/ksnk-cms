/*!
 * fb-Menu-jQuery JavaScript Library v1.4.2
 * http://walterdalmut.com/
 * http://fb.clienti.walterdalmut.com
 *
 * Copyright 2010, Walter Dal Mut
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://jquery.org/license
 *
 * Date: Sat Dec 13 20:15:48 2010 +0100
 */
(function( $, undefined ) {

$.fn.fbmenu = function(usr){

	if (typeof usr != 'object') {
		var options = {
			submenuClass: 'fb-simple-class',
			selectedClass: 'selected',
			left: false,
			zIndex: 1000
		};
	} else {
		var options = usr;
	}
	
	//Fixed, used for closing system
	options.fixedClass = 'fb-class-fixed';
	options.fixedSelectedClass = 'fb-selected-class-fixed';
	
	var a = function(elem, options){
		elem.find('ul').attr('style', 'display:none');
		
		var closeOpenedMenu = function() {
			$('.'+options.fixedClass).remove();
			$('li.'+options.fixedSelectedClass).removeClass(options.selectedClass);
		};
		
		elem.children('li').click(function(event){
			
			closeOpenedMenu();
			
			event.stopImmediatePropagation();	//Stop event propagation
			
			var submenu = $(this).children('ul').clone();
			submenu.addClass(options.submenuClass); //For remove system
			submenu.addClass(options.fixedClass);
			$('body').append(submenu);
			
			var li = $(this);
			
			if (submenu.size() > 0) {
				
				li.addClass(options.selectedClass);
				li.addClass(options.fixedSelectedClass);
				
				var left = li.offset().left;
				if (!options.left) {
					left += li.outerWidth() - submenu.outerWidth();
				} else {
					
				}
				
				var top = li.offset().top;
				
				submenu.attr(
					'style', 
					'position: absolute;'+
					'z-index: ' + options.zIndex + ';'+
					'left: '+ left +'px; '+
					'top: ' + (top + li.outerHeight()) + 'px;'
				);
				
				$('body').click(function(){
					closeOpenedMenu();
					$('body').unbind('click');
				});
			}
		});
	};
	a($(this), options);
}

})(jQuery);