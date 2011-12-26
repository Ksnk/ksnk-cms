/**
 * debug function
 * <% if($target!='debug') echo '
window.debug=function(){};
';
 else { %>
 */

if (window.console && window.console.debug){
    window.debug=function(){
	  for (var i = 0; i < debug.arguments.length; i++){
	    var text='x3';
	    if(typeof(debug.arguments[i])=='undefined')
	    	text='undefined';
	    else if (debug.arguments[i].toString)
	    	text=debug.arguments[i].toString()	;
	    console.debug(text);
	  }
    };
} else {
    window.debug=function (){
	  for (var i = 0; i < debug.arguments.length; i++){
	    var text='x3';
	    if(typeof(debug.arguments[i])=='undefined')
	    	text='undefined';
	    else if (debug.arguments[i].toString)
	    	text=debug.arguments[i].toString()	;
	    $('<span>').html(text).appendTo('#debug');
	  }
	  $('<hr>').appendTo('#debug');
    };
}
//<% } %>
