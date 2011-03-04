<?php //<%
/**
 * плагин табуляции на страничке
 */
 
point_start('js_main');
//?><script type="text/javascript"> 
// %>
var opentabs=cookie('opentabs')||{0:1};
$('dl.tabs dt').click(function(){
	$(this)
		.siblings().removeClass('selected').end()
		.next('dd').andSelf().addClass('selected');
}).each(function(index){
	if(opentabs[index])
		$(this).trigger('click');
});

$(window).unload(function(){
	opentabs={};
	$('dl.tabs dt').each(function(index){
		if($(this).hasClass('selected'))
			opentabs[index]=1;
	});
	cookie('opentabs',opentabs);
});

<% point_finish(); %>

</script>
<style type="text/css">
<% point_start('css_site'); %>

 .tabs {
 	width:100%;
 	overflow:hidden;
 	font-size:0.9em;
 	margin:2em 0;
 	zoom:1;
 	padding:1px;
 	position:relative;
 }
 
 .tabs dt {
 	float:left;
 	line-height:2;
 	height:2em;
 	background:#e8f0f5;
 	border:1px solid #e8f0f5;
 	border-bottom:0;
 	padding:0 1em;
 	position:relative;
 	left:35px;
 	margin-right:1px;
 	cursor:pointer;
 	
 	-webkit-border-top-left-radius:10px;
 	-webkit-border-top-right-radius:10px;
 	-moz-border-radius-topleft:10px;
 	-moz-border-radius-topright:10px;
 }
 
 .tabs dt:hover {
 	background-color:#bfdff4;
 }
 
 .tabs dt.selected {
 	border-color:#b0d0e9;
 	background:#fff;
 	z-index:3;
 	cursor:auto;
 }
 
 .tabs dd {
  	background:#fff;
 	display:none;
 	float:right;
 	width:100%;
 	margin:2em 0 0 -100%;
 	position:relative;
 	z-index:2;
 }
 
 .tabs dd.selected {
 	display:block;
 }
 
 .tabs .tab-content {
 	border:1px solid #b0d0e9;
 	padding:20px;
 	
 	-webkit-border-radius:20px;
 	-moz-border-radius:20px;
 }
 
<% point_finish(); %>
</style>		