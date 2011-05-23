function komment(el,id){
	try{
		var $quote=$(el).parents('tr').eq(0),$x;
		if (($x=$quote.next().find('.question')).length) $quote=$x.html();
		else if(($x=$quote.next().next().find('.question')).length) $quote=$x.html();
		else $quote=$quote.next().next().next().find('.question').html();
		$form=$(el).parents('div').eq(0);
		$form.find('.quote_div').html($quote)[0].scrollIntoView();
		$form.find('input[name=quote]').val($quote);
		$form.find('input[name=quote_id]').attr('value', id);
	} catch(e){;}
	return false;
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