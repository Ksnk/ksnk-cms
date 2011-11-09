<?php //<%
/**
 * при действиях с активным элементом происходят события, 
 * описываемые data-атрибутами
 * @var unknown_type
 */
$class='active'; 
 
point_start('js_main');//?><script type="text/javascript"> // %>

function active(e){
	var $self=$(this);
	// проверяем что делать перед нажатием
	if()
}

$( ".active" ).each(function(){
	var $self=$(this);
	switch(this.tagName.toLowerCase()){
	case 'input':
		var type=$self.attr('type');
		if(type=='button'){
			$self.click(function(e){
				active.apply(this,'click');
			});
		}
		break;
	}
});

<% point_finish(); %>
</script>