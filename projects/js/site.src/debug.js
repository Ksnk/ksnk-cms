/**
 * debug function
 */
function debug(){
	 this.length = debug.arguments.length;
	  for (var i = 0; i < this.length; i++){
	    this[i] = debug.arguments[i];
	    var text='x3';
	    if(typeof(debug.arguments[i])=='undefined')
	    	text='undefined';
	    else if (text=debug.arguments[i].toString)
	    	text=debug.arguments[i].toString()	;
	    $('<span>').html(text).appendTo('#debug');
	  }
	  $('<hr>').appendTo('#debug');
};

