// JavaScript Document

$(function(){

	$(document).ready(function(){
	
		var flat = 0;
		var area_flat = 0;
		var flat = 0;
		var level = 0;
		var level_f = 0;
		var num_level = 0;
					
		$('#Map').mouseover(function(e){
			var run_area = $(e.target);  		
			var coords = run_area.attr("coords").split(",");
			var id = run_area.attr("id").substring(4);
			var id_level = '.level' + id;
			level = $(id_level);
			$('#a1').css({ 'top' : coords[1] });
			$('#levels td').css({'color' : '#ffffff', 'background-image' : 'url(img/bg_level1.gif)'});
			level.css({'color' : '#2f2f2f', 'background-image' : 'url(img/bg_level2.gif)'});  
		});
		

		$('#Map').click(function(f){
			var clicked_level = $(f.target);  
			var id = clicked_level.attr("id").substring(4);
			num_level = 10 - id;

			$("#n_level").empty();
			$("#n_level").append(num_level + ' этаж');
			$("#cover2 img").animate({ opacity: "hide" }, "normal");
			$("#Map2").find(".flat_free").removeClass("flat_free");
			$.each(flat_free, function (n, val) { 
				flat = val%100;
				level = (val - flat)/100;
				if (num_level == level) 
					{
						$("#flat" + flat).animate({ opacity: "show" }, "fast");
						flat = flat + 20;
						$('#area' + flat).addClass('flat_free');
					}		
		      });
 		});	  

		$("#Map2").click(function(d){ 
				var clicked_flat = $(d.target);
	
				if(clicked_flat.attr('class') == 'flat_free')
					{
						flat = clicked_flat.attr('id').substring(5);
						$("#f" + flat).modal();
						$("#f" + flat + " .level").empty();
						$("#f" + flat + " .level").append(num_level + ' этаж');
					}
			});
		

	});

})	