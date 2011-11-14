<?php
/**
 * плагин интеграции с jQuery.UI
 */
//<% point_start('html_header') ;%>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>
<%	point_finish();

	point_start('css_site') ;
	readfile($env_common.'/common/jqUI/css/ui-lightness/jquery-ui-1.8.16.custom.css');
	point_finish();
	
	point_start('main_html'); 
 // ?> <html>%>
 
 <div id="dialog-confirm">
 <span>
 Вы уверены, что хотите чего-то там удалить?</span>
 </div>
 <div id="dialog-form" title="Введите данные">
	<form>
		<input type="text" name="text" id="name" class="text ui-widget-content ui-corner-all" />
	</form>
</div>
 <% point_start('js_main');// <script> %> 
window.win_input=function (par){
	$( "#dialog-form" ).attr('title',par.txt)
	.dialog({
		//height: 160,
		//width: 300,
		modal: true,
		buttons: {
			"Готово": function() {
				var txt=$( this ).find('input').val()
				$( this ).dialog( "close" );
				if(typeof(par.yes)=='function')
					par.yes(txt);
			},
			"Отмена": function() {
				$( this ).dialog( "close" );
			}
		}
	});
};

window.win_confirm=function (par){
	// диалог 
	$("#dialog-confirm").find('span').html(par.txt).end()
	.dialog({
		resizable: false,
		//height:140,
		modal: true,
		buttons: {
			'Да': function() {
				$(this).dialog('close');
				if(typeof(par.yes)=='function')
					par.yes();	
			},
			'Нет': function() {
				$(this).dialog('close');
			}
		}
	});
};
window.win_alert=function (par){
	// диалог 
	$("#dialog-confirm").find('span').html(par.txt).end()
	.dialog({
		resizable: false,
		//height:140,
		modal: true,
		buttons: {
			'Ok': function() {
				$(this).dialog('close');
				if(typeof(par.yes)=='function')
					par.yes();	
			}
		}
	});
};

<% point_finish(); %>
</script>	
	
<%
	$this->xml_read('
<config>
	<files dir="'.$env_common.'/common/jqUI/css/ui-lightness" dstdir="$dst/css/">
		<copy>images/*.*</copy>
	</files>
</config>
');
%>