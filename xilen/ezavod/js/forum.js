function komment(id) {
	$("#quote_div").replaceWith('<div id="quote_div" class="quote_div">' + $("#question_" + id).html() + '</div>');
	$("#quote").attr('value', $("#question_" + id).html());
	$("#quote_id").attr('value', id);
}

function show_sub(id) {
	if ($("#subtopic_" + id).css('display') == 'none') {
		if(window.navigator.appName == 'Microsoft Internet Explorer')
			$("#subtopic_" + id).css('display', 'block');
		else	
			$("#subtopic_" + id).css('display', 'table-row-group');
		$("#cnt_otv_" + id + " > span").css('visibility', 'hidden');
		$("#cnt_otv_" + id).css('background', 'url(img/strelka.gif) no-repeat 25px 10px');
	}
	else {
		$("#subtopic_" + id).css('display', 'none');
		$("#cnt_otv_" + id + " > span").css('visibility', 'visible');
		$("#cnt_otv_" + id).css('background', 'none');
	}
}