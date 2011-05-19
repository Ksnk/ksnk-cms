// init menu
menu('#searchbar',{
	show:function(){$(this).show('hormal',function(){
		$('#search_string').focus();
	})},
	hide:function(){$(this).hide('hormal')}
});
window.showsearchbar=function (){
	var x=$('#searchbar')[0];
	if(!x.shown)
		x.show_menu();
	return false;
};