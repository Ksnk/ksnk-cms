function show_opisanie(id) {
	$("#opisanie_" + id).slideToggle(400);
	$("#opisanie_btn_" + id).toggle();
}

function hide_opisanie(id) {
	$("#opisanie_" + id).slideUp(400);
	$("#opisanie_btn_" + id).hide();
}