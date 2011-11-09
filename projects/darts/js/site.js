/**
 * 
 */
<% 
	include_once('/xilen/common/js/site.src/menu.js'); 
    include_once('/xilen/common/js/site.src/debug.js'); 
    include_once('/xilen/common/js/site.src/cookie.js'); 
	
    echo point('js_head'); //%>
   
function komment(el,id){
	try{
		var $form,$quote=$(el).parents('tr').eq(0),$x;
		if (($x=$quote.next().find('.question')).length) $quote=$x.html();
		else if(($x=$quote.next().next().find('.question')).length) $quote=$x.html();
		else $quote=$quote.next().next().next().find('.question').html();
		$form=$(el).parents('div').eq(0);
		$form.find('.container')[0].scrollIntoView();
		$form.find('.quote_div').html($quote);
		$form.find('input[name=quote]').val($quote);
		$form.find('input[name=quote_id]').attr('value', id);
	} catch(e){;}
	return false;
}  

function reload(){
	if(!cookie('debug'))
		window.location.reload();
	else 
		win_alert({txt:'reload page, plz'});
}  

function _news(e,what){
	var x='' ;
	while (e && (!e.tagName || e.tagName.toLowerCase()!='div')) {e=e.parentNode; x+='^'; };
	while (e && (!e.tagName || e.tagName.toLowerCase()!='p')) {e=e.nextSibling;x+='>' };
	if (e && (x=e.getAttribute('newsid'))) //; alert(x) ; return ;
		document.location="?do="+what+"news&plugin=darts_News&nid="+x;
}

function win_confirm(par){
	if (confirm(par.txt)){
		if(typeof(par.yes)=='function')
			par.yes();
	}
}
function win_alert(par){
	alert(par.txt);
}

$(function(){
	// всплывающие менюшки (только над блоковыми элементами!!!)
	
	$('.menu').hover(
		function(){
			var $self=$(this);
			if(!$self.data('xmenu')){
				var $menu=$($self.attr('data-menu')).clone().removeAttr('id');
				$self.css({position:'relative'});
				$menu.css({position:'absolute'
					,top:0,right:0
				}).prependTo($self);
				$self.data('xmenu',$menu);
				var $item=$self.attr('data-item');
				$menu.find('a').each(function(){
					this.href=this.href.replace('~~',$item);
				})
			}
			$($self.data('xmenu')).show();
		},
		function(){
			$($(this).data('xmenu')).hide();
		}
	)
	
	
	
	<% include_once('/xilen/common/js/site.src/justajax.js'); %>
	<%=point("js_main"); %>

}) 
