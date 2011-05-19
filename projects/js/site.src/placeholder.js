/**
 * jQuery.placeholder - Placeholder plugin for input fields
 *
 * @author Serge Koriakin
 * @version 1.0.0
 *  
 * @additional class definition:
 * .placeholder {
 *   color: silver;
 * }
 * @sample
 * <input type="text" name="name" placeholder="Enter Name"/>
 * @sample
 * <select name="name" placehlder="2">
 * <option value="0"></option>
 * <option>1</option>
 * <option>2</option>
 * <option>3</option>
 * <option>4</option>
 * </select>
 *
 **/
new function($) {
		
	var setting={
		attr:	"placeholder",
		className:	"placeholder",
		wrapcss:{
			 padding:0
			,margin:0
	    	,display:'inline-block'	
	    	,position:'relative'
	    },	
	    placeholdercss:{
			 display:'block'
			,position:'absolute'
			,top:'0.2em'
			,left:'0.5em'
		}
	};
	$.fn.wrapdiv=function(o){
		this.each(function(){
			if(!this.parentNode.style || this.parentNode.style.position!='relative'){
				var $this=$(this);	
			    $this.wrap(
			    	$('<div/>').css(setting.wrapcss).css({
			    		marginRight:$this.css('marginRight'),
			    		marginLeft:$this.css('marginLeft'),
			    		marginTop:$this.css('marginTop'),
			    		marginBottom:$this.css('marginBottom')
			    	})
			    )
			    .css('margin','0');
			}
		});
	    return this;
	}
    $.fn.placeholder = function(o) {
    	if(!o) 
    		o={};
    	else if (typeof(o)=='string') 
    		o={attr:o};
		$.extend(o,setting);
		this
		//.filter('select, texarea, input:file, input:text, input:password').filter('['+o.attr+'!=]')
		.each(function(){
			var text,def='',$self=$(this);
			if(!(text=$self.attr(o.attr)))
				if (!(text=o.text)) 
					return;
			if($self.is('.checkbox')) return;
			if(this.tagName.toLowerCase()=='select'){
				def='0';
				text=$self.find('option[value='+text+']').html()||text;
			} 
			$self.wrapdiv()
		    .after(
		    	$('<span/>').addClass(o.className)
		    		.html(text)
		    		.css(o.placeholdercss)
		    		.hide()
		    		.attr('title',$self.attr('title'))
		    )
		    .removeAttr(o.attr)
		    .bind('change blur',function(){
		    	$(this).parent().find('span')
		    		[$.trim($(this).val())===def?'show':'hide']();
		    })
		    .parent().find('span').mousedown(function(){
	    		var self=$(this).hide().parent().find('input');
		    	setTimeout(function(){self.focus()});
		    }).end().end()
		    .focus(function(){
		    	$(this).parent().find('span').hide();
		    })
		    .trigger('blur');
			$self=null;
		});
		return this;
	}
}(jQuery);