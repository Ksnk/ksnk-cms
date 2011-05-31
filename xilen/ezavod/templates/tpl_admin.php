<?php
class tpl_admin extends tpl {

function _(&$par){		
		return tpl::_a($par['script'],array('tpl_admin','script')).'

'.tpl::_a($par['homemenu'],array('tpl_admin','homemenu')).'

'.tpl::_a($par['bottom'],array('tpl_admin','bottom')).'


'.tpl::_a($par['counters'],array('tpl_admin','counters')).'


<!--
 Администрирование

 - форма ввода панели "Список загрузок файлов"

 -->
'.tpl::_a($par['order_elm_start'],array('tpl_admin','order_elm_start')).'

'.tpl::_a($par['order_elm_fin'],array('tpl_admin','order_elm_fin')).'

'.tpl::_a($par['align_elm'],array('tpl_admin','align_elm')).'

'.tpl::_a($par['win_elm'],array('tpl_admin','win_elm')).'

'.tpl::_a($par['delrec_elm'],array('tpl_admin','delrec_elm')).'

'.tpl::_a($par['psubm_elm'],array('tpl_admin','psubm_elm')).'


'.tpl::_a($par['href'],array('tpl_admin','href')).'

'.tpl::_a($par['uploads'],array('tpl_admin','uploads')).'
'.tpl::_a($par['callback'],array('tpl_admin','callback')).'
'.tpl::_a($par['mail_callback'],array('tpl_admin','mail_callback'));
}

function script(&$par){		
		return '<script type="text/javascript" src="'.tpl::_ax(tpl::_export('','index'),array('tpl_admin','script___index')).'/js/jquery.colorbox.js"></script>
<script type="text/javascript">
//$.fn.colorbox.settings.width = '.tpl::_ax(tpl::_export('','get_param','pictute_xxwidth'),array('tpl_admin','script___get_param_pictute_xxwidth')).'+94;
//$.fn.colorbox.settings.height = '.tpl::_ax(tpl::_export('','get_param','pictute_xxheight'),array('tpl_admin','script___get_param_pictute_xxheight')).'+172;
$.fn.colorbox.settings.current = "";
$.fn.colorbox.settings.opacity = 0.6;  
</script>
<link type="text/css" media="screen" rel="stylesheet" href="'.tpl::_ax(tpl::_export('','index'),array('tpl_admin','script___index')).'/colorbox.css" >
<!--[if IE]>
<link type="text/css" media="screen" rel="stylesheet" href="'.tpl::_ax(tpl::_export('','index'),array('tpl_admin','script___index')).'/colorbox-ie.css" title="example" />
<![endif]-->
<!--[if lt IE 7]>
<![if gte IE 5.5]>
<script type="text/javascript" src="'.tpl::_ax(tpl::_export('','index'),array('tpl_admin','script___index')).'/js/fixpng.js"></script>
<style type="text/css">
.iePNG { filter:expression(fixPNG(this)); }
</style>
<![endif]>
<![endif]-->
<style  type="text/css">
ul.ulmenu li ul {
    background:
      url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAIAAACQd1PeAAAADElEQVQImWO4eGkrAATQAlkJHN2FAAAAAElFTkSuQmCC==);
   background:rgba(249,240,215, 0.95);
}
 </style>
 <!--[if lte IE 8]>
  <style type="text/css">
ul.ulmenu li ul {
     background:rgb(249,240,215);
    filter:alpha(opacity=95);
   }
   
   ul.ulmenu li ul * {
    position:relative;
   }
  </style>
 <![endif]-->
 <script type="text/javascript">

$(document).ready(function()
{
	$(\'.tabs dt\').click(function(){
		$(\'.tabs .over\').removeClass(\'over\');
		$(this).addClass(\'over\');		
		$(this).next(\'dd\').addClass(\'over\');		
	}).eq(0).click();
	
	var lastopen=[];
	
	function in_array(x){
		for(var i=0;i<lastopen.length;i++){
			if (lastopen[i]==x)
				return i+1;
		};
		return 0;
	}
	
	function dd_menu(level){
		if(level>0)
			menu(this);
		else
		menu(this,{
			show:function(){
				//$(this).stop(true,true).slideDown("fast",function(){this.style.height=\'auto\';})
				$(this).stop(false,true).css({display:\'block\',\'height\':1}).animate({ height:100},\'fast\',function(){this.style.height=\'auto\';});
			}
			,hide:function(){
				$(this).stop(false,true).animate({ height:1},\'fast\',function(){
					clearTimeout(this.__timeout);
					$(this).hide();
					if (lastopen[level]==this){
						lastopen.pop();
					}
				})
			}
		});
		var self=this;
		$(this).parent().hover(function(){
			//console.log(lastopen);
			clearTimeout(self.__timeout);
			if(lastopen && lastopen.length>level){
				if(lastopen[level]!=self){
					while(lastopen.length>level)
						lastopen.pop().hide_menu();
				}
			};
			if(lastopen[level]!=self){
				self.show_menu();
				lastopen[level]=self;
			}
		},function(){
			clearTimeout(self.__timeout);
			self.__timeout=setTimeout(function(){
				self.hide_menu();
			},1000);
		});
		var x=$(this).children(\'li\');
		x.children(\'ul\').each(function(){
			dd_menu.call(this,level+1);
		});
		$(x[0]).addClass(\'first\');
	}
	
	$(\'ul.ulmenu>li\').each(function(){
		
		if($(this).find(\'ul\').each(function(){
			dd_menu.call(this,0);
		}).length==0) {
			$(this).mouseover(function(){
				if(lastopen.length>0){
					lastopen.pop().hide_menu()
				}
			})
			// hide curmenu
		}
	});
	
});


$(function(){
	$(\'#homemenu img\')
		//.css({opacity:0.5})
		.mouseover(function(){
			$(this) //.css({opacity:0.99})
			.parent().parent().addClass(\'round\');
		})
		.mouseout(function(){
			$(this) //.css({opacity:0.5})
			.parent().parent().removeClass(\'round\');
		})
	/*$(\'#homemenu img\').eq(0).each(function(){
			$(this).css({opacity:0.99})
			.parent().parent().addClass(\'round\');
		})*/
});
</script>';
}

function homemenu(&$par){		
		return '<div id="homemenu" style="position:absolute;width:192px;height:32px;top:42px;left:7%;">
			<table class="long wide fixed"><col width="auto"><col width="auto"><col width="auto"><tr>
			<td class="align_middle align_center"><a href="'.tpl::_ax(tpl::_export('','index'),array('tpl_admin','homemenu___index')).'/"><img src="img/home_wt.gif" alt=""></a></td>
			<td class="align_middle align_center"><a href="'.tpl::_ax(tpl::_export('','curl','do','id'),array('tpl_admin','homemenu___curl_do_id')).'do=writeus"><img src="img/mailus_wt.gif" alt=""></a></td>
			<td class="align_middle align_center"><a href="'.tpl::_ax(tpl::_export('','curl','do','id'),array('tpl_admin','homemenu___curl_do_id')).'do=sitemap"><img src="img/sitemap_wt.gif" alt=""></a></td>
			</tr></table>
		</div>';
}

function bottom(&$par){		
		return '<tr>
		<td  style="height:137px"><div class="wide" style="position:relative">
		<div class="link size12" style="position:absolute; height:74px;width:149px; top:-90px; left:77px; background:url(img/demteatr.gif) no-repeat;"></div>
	
		<div class="link size12" style="position:absolute; height:164px;width:213px; top:-162px; right:40px; background:url(img/phone.gif) no-repeat;">
		<div style="line-height: 16px;padding:63px 0 0 75px; color:#fed4b2;">
		Россия, СПб,<br>
		Невский пр., д.111<br>
        <a href="mailto:demid@nevski.ru">demid@nevski.ru</a>
		<a style="color:#fdee9a" href="mailto://demid@nevski.ru">'.tpl::_ax(tpl::_export('','_par','mail_admin'),array('tpl_admin','bottom____par_mail_admin')).'</a>
		</div>
		</div>
		<div  style="color:#fed4b2; position:absolute; height:185px;width:262px; top:-162px; right:253px; background:url(img/photogall.gif) no-repeat;">
		<a style="display:block;text-decoration:none;" class="long wide" href="'.tpl::_ax(tpl::_export('','index'),array('tpl_admin','bottom___index')).'/photo_gallery">&nbsp;</a>
		</div>
		<div style="position:absolute;height:84px;width:93px;top:-81px;right:581px;background:url(img/x2.gif)"></div>
		<div style="position:absolute;height:28px;width:67px;top:-1;right:673px;background:url(img/x1.gif)"></div>
		<div  style="position:absolute; top:50px; width:705px; margin-left:-350px; left:52%;">
			<table class="link size11"><tr>'.tpl::_ax(tpl::_export('','menu','head'),array('tpl_admin','bottom___menu_head')).'</tr></tr></table>
            <table><tr><td style="color:#cd7b27; padding-top:22px; padding-left:16px">&copy; 2008. Любое использование размещенных на сайте материалов <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;возможно только с личного согласия Григория Демидовцева.</td></td></tr></table>
		</div>	

		<table class="fixed long wide link" style="background:url(img/bg_dn.jpg)">
			<col width="auto">
			<col width="240px">
			<col width="28px">
			<tr>
				<td class="size11 link"
					style="color:#cd7b27;padding:92px 0 0 330px;">
				<td style="padding:39px 0 0 96px;">
				<a href="http://www.xilen.ru"><img src="img/xilenru.gif" alt="xilen.ru"></a><br>
				<a class="size11" style="display:block; margin-top:5px" href="http://www.xilen.ru">Cоздание сайтов</a>

				</td>
				<td >&nbsp;</td>
			</tr>
		</table></div>
		</td>
</tr>';
}

function bottom___menu_head(&$par){		
		return '<td style="padding:0 16px 0 16px;"><a class="white '.(isset($par['current'])?$par['current']:'').'" href="'.tpl::_d($par['url'],'#').'">'.(isset($par['item'])?$par['item']:'').'</a></td>
			'.tpl::_d($par['last'],'<td>|</td>');
}

function counters(&$par){		
		return '<!--SpyLOG-->
<span id="spylog2026286"></span><script type="text/javascript"> var spylog = { counter: 2026286, image: undefined, next: spylog }; document.write(unescape("%3Cscript src=%22http" +
(("https:" == document.location.protocol) ? "s" : "") +
"://counter.spylog.com/cnt.js%22 defer=%22defer%22%3E%3C/script%3E")); </script>
<!--SpyLOG-->';
}

function order_elm_start(&$par){		
		return '<table class="compact"><tr><td><input type="button" class="win_max" style="background-position:0 -120px;"
 onclick="order(this,\'+\');"
>
</td><td>';
}

function order_elm_fin(&$par){		
		return '</td><td>
<input type="button" class="win_max" style="background-position:0 -105px;"
 onclick="order(this,\'-\');"
>
</td></tr></table>';
}

function align_elm(&$par){		
		return '<table class="compact glass"><tr><td>cлева</td><td>центр</td><td>справа</td></tr>
<tr><td><input  type="radio" name="align" value="0"></td><td>
<input  type="radio" name="align" value="1"></td><td>
<input  type="radio" name="align"  value="2"></td></tr></table>';
}

function win_elm(&$par){		
		return '<div class="win_max open_close">&nbsp;</div>';
}

function delrec_elm(&$par){		
		return '<input type="button" class="win_max" style="background-position:0 0;"
 onclick="delrec(this)">';
}

function psubm_elm(&$par){		
		return '<input type="submit" class="win_max" style="background-position:0 -90px;" value=\'&nbsp;\'>';
}

function href(&$par){		
		return '<form action="" method="POST" name="href">

<table class="thetable">
<tr><th colspan=2>
Название элемента (не отображается на сайте)
</th><td colspan=3><input type="text" name="name">
</td></tr>
<tr><th colspan=2>
Количество столбцов для вывода списка
</th><td colspan=3><input  type="text" name="columns">
</td></tr>
<tr><th colspan=2>
Выравнивание
</th><td colspan=3>'.tpl::_ax(tpl::_export('','tpl','admin','align_elm'),array('tpl_admin','href___tpl_admin_align_elm')).'
</td></tr>
<tr><th colspan=5 class="align_center" style="background:white;height:30px">
</th></tr>
<tr>
<th>ссылка</th>
<th>текст</th>
<th>порядок</th>
<th>'.tpl::_ax(tpl::_export('','tpl','admin','win_elm'),array('tpl_admin','href___tpl_admin_win_elm')).'</th>
</tr>
'.tpl::_a($par['list'],array('tpl_admin','href_list')).'
<tr>
<td>
<input  type="text" name="filename">
</td>
<td><input type="hidden" name="id">
<input  type="text" name="text"></td>
<td></td><td>
'.tpl::_ax(tpl::_export('','tpl','admin','psubm_elm'),array('tpl_admin','href___tpl_admin_psubm_elm')).'
<input type="hidden" class="del" name="del">
</td>
</tr>
<tr><th colspan=5 height="5px;"></th></tr><tr>
<th colspan=5 class="align_center" style="background:white;height:30px">
<input type="submit" style="width:auto;"  name="save" value="Сохранить">
</th>
</table>
'.(isset($par['pages'])?$par['pages']:'').'
</form>';
}

function href_list(&$par){		
		return '<tr id="id_'.(isset($par['id'])?$par['id']:'').'">
<td >
<input type="text" name="filename_'.(isset($par['id'])?$par['id']:'').'">
</td>
<td ><input type="text" style="width:400px" name="text_'.(isset($par['id'])?$par['id']:'').'"></td>
<td class="align_center" style="width:55px;" nowrap>
'.tpl::_ax(tpl::_export('','tpl','admin','order_elm_start'),array('tpl_admin','href_list___tpl_admin_order_elm_start')).'
<input type="text" class="order" style="width:15px;" name="order_'.(isset($par['id'])?$par['id']:'').'">
'.tpl::_ax(tpl::_export('','tpl','admin','order_elm_fin'),array('tpl_admin','href_list___tpl_admin_order_elm_fin')).'
</td>
<td>'.tpl::_ax(tpl::_export('','tpl','admin','delrec_elm'),array('tpl_admin','href_list___tpl_admin_delrec_elm')).'
</td>
</tr>';
}

function uploads(&$par){		
		return '<form action="" method="POST" name="uploads">

<table class="thetable">
<tr><th colspan=2>
Название элемента (не отображается на сайте)
</th><td colspan=3><input type="text" name="name">
</td></tr>
<tr><th colspan=2>
Количество столбцов для вывода списка
</th><td colspan=3><input  type="text" name="columns">
</td></tr>
<tr><th colspan=2>
Выравнивание
</th><td colspan=3>'.tpl::_ax(tpl::_export('','tpl','admin','align_elm'),array('tpl_admin','uploads___tpl_admin_align_elm')).'
</td></tr>
<tr><th colspan=5 class="align_center" style="background:white;height:30px">
</th></tr>
<tr>
<th>файл</th>
<th>описание</th>
<th>порядок</th>
<th>'.tpl::_ax(tpl::_export('','tpl','admin','win_elm'),array('tpl_admin','uploads___tpl_admin_win_elm')).'</th>
</tr>
'.tpl::_a($par['list'],array('tpl_admin','uploads_list')).'
<tr>
<td class="uploader" style="height:auto;">
<input  type="text" name="filename">
</td>
<td><input type="hidden" name="id">
<input  type="text" name="text"></td>
<td></td><td>
'.tpl::_ax(tpl::_export('','tpl','admin','psubm_elm'),array('tpl_admin','uploads___tpl_admin_psubm_elm')).'
<input type="hidden" class="del" name="del">
</td>
</tr>
<tr><th colspan=5 height="5px;"></th></tr>
<tr><th colspan=5 class="align_center" style="background:white;height:30px">
<input type="submit" style="width:auto;"  name="save" value="Сохранить">
</th></tr>
</table>
'.(isset($par['pages'])?$par['pages']:'').'
</form>';
}

function uploads_list(&$par){		
		return '<tr id="id_'.(isset($par['id'])?$par['id']:'').'">
<td class="uploader" style="width:150px;height:auto;">
<input type="text" name="filename_'.(isset($par['id'])?$par['id']:'').'">
</td>
<td ><input type="text" style="width:400px" name="text_'.(isset($par['id'])?$par['id']:'').'"></td>
<td class="align_center" style="width:55px;">
'.tpl::_ax(tpl::_export('','tpl','admin','order_elm_start'),array('tpl_admin','uploads_list___tpl_admin_order_elm_start')).'
<input type="text" class="order" style="width:15px;" name="order_'.(isset($par['id'])?$par['id']:'').'">
'.tpl::_ax(tpl::_export('','tpl','admin','order_elm_fin'),array('tpl_admin','uploads_list___tpl_admin_order_elm_fin')).'
</td>
<td>'.tpl::_ax(tpl::_export('','tpl','admin','delrec_elm'),array('tpl_admin','uploads_list___tpl_admin_delrec_elm')).'</td>
</tr>';
}

function callback(&$par){		
		return '<form style="padding:0; margin:0" action="" method="POST" name="callback">
<style>
.table td.pad10 {
 padding-top:10px;
 padding-bottom:10px;
}
.table .input {
	border:1px solid rgb(127,157,185);
	width:310px;
	
}
.table textarea.input {
	height:100px;
}

.table span.comment {
	font-size:10px; color:#cccccc;
}

</style>
<span class="bold red">'.(isset($par['error'])?$par['error']:'').'</span>
'.tpl::_a($par['hidden'],array('tpl_admin','callback_hidden')).'
<input  class="hidden" type="text" name="hidden_value" value="secret">
<table class="table size12">
'.tpl::_a($par['list'],array('tpl_admin','callback_list')).'
</table>
</form>';
}

function callback_hidden(&$par){		
		return '<input class="hidden" ntype="text" name="'.(isset($par['text'])?$par['text']:'').'">';
}

function callback_list(&$par){		
		return '<tr class="'.tpl::_b($par['even'],'','').'">
    '.tpl::_a($par['text'],array('tpl_admin','callback_list_text')).'
    '.tpl::_a($par['password'],array('tpl_admin','callback_list_password')).'
'.tpl::_a($par['scrolltext'],array('tpl_admin','callback_list_scrolltext')).'	
'.tpl::_a($par['input'],array('tpl_admin','callback_list_input')).'	
'.tpl::_a($par['textarea'],array('tpl_admin','callback_list_textarea')).'	
'.tpl::_a($par['checkbox'],array('tpl_admin','callback_list_checkbox')).'	
'.tpl::_a($par['radio'],array('tpl_admin','callback_list_radio')).'	
'.tpl::_a($par['captcha'],array('tpl_admin','callback_list_captcha')).'	
'.tpl::_a($par['submit'],array('tpl_admin','callback_list_submit')).'	
</tr>';
}

function callback_list_text(&$par){		
		return '<td colspan=3 class="pad10 text bold align_center" >'.(isset($par['text'])?$par['text']:'').'</td>';
}

function callback_list_password(&$par){		
		return '<td class="pad10 text align_right">'.(isset($par['tit'])?$par['tit']:'').tpl::_b($par['nocol'],'','').'&nbsp;'.tpl::_b($par['nostar'],'','').'</td>
    <td colspan=2 class="pad10 text"><input  class="input" type="password" name="'.(isset($par['name'])?$par['name']:'').'">'.(isset($par['comment'])?$par['comment']:'').'</td>';
}

function callback_list_scrolltext(&$par){		
		return '<td colspan=3 class="pad10 text align_right" >
	'.(isset($par['title'])?$par['title']:'').'<div style="overflow:auto;">'.(isset($par['text'])?$par['text']:'').'</div></td>';
}

function callback_list_input(&$par){		
		return '<td class="pad10 text align_right">'.(isset($par['tit'])?$par['tit']:'').tpl::_b($par['nocol'],'','').'&nbsp;'.tpl::_b($par['nostar'],'','').'</td>
<td colspan=2 class="pad10 text"><input  class="input" type="text" name="'.(isset($par['name'])?$par['name']:'').'">'.(isset($par['comment'])?$par['comment']:'').'</td>';
}

function callback_list_textarea(&$par){		
		return '<td class="pad10 text align_right">'.(isset($par['tit'])?$par['tit']:'').tpl::_b($par['nocol'],'','').'&nbsp;'.tpl::_b($par['nostar'],'','').'</td><td colspan=2 class="pad10 text"><textarea  class="input" name="'.(isset($par['name'])?$par['name']:'').'"></textarea></td>';
}

function callback_list_checkbox(&$par){		
		return '<td class="pad10 text align_right">'.(isset($par['tit'])?$par['tit']:'').tpl::_b($par['nocol'],'','').'&nbsp;'.tpl::_b($par['nostar'],'','').'</td>
<td colspan=2 class="pad10 text">
	'.tpl::_a($par['check'],array('tpl_admin','callback_list_checkbox_check')).'
	</td>';
}

function callback_list_checkbox_check(&$par){		
		return '<div><input type="checkbox" name="'.(isset($par['name'])?$par['name']:'').'" value="'.(isset($par['value'])?$par['value']:'').'">'.(isset($par['text'])?$par['text']:'').'</div>';
}

function callback_list_radio(&$par){		
		return '<td class="pad10 text align_right">'.(isset($par['tit'])?$par['tit']:'').tpl::_b($par['nocol'],'','').'&nbsp;'.tpl::_b($par['nostar'],'','').'</td>
<td colspan=2 class="pad10 text">
	'.tpl::_a($par['check'],array('tpl_admin','callback_list_radio_check')).'
	</td>';
}

function callback_list_radio_check(&$par){		
		return '<div><input type="radio" name="'.(isset($par['name'])?$par['name']:'').'" value="'.(isset($par['value'])?$par['value']:'').'">'.(isset($par['text'])?$par['text']:'').'</div>';
}

function callback_list_captcha(&$par){		
		return '<td class="pad10 text align_right" >Введите номер, изображенный на картинке:<br></td><td class="text" style="vertical-align:middle; padding-left:10px"><input class="input2"  type="text" name="captcha" /></td>
<td><img src="'.tpl::_ax(tpl::_export('','index'),array('tpl_admin','callback_list_captcha___index')).'/captcha.php" alt="" />
</td>';
}

function callback_list_submit(&$par){		
		return '<td class="text pad10" ></td><td colspan=2 class="pad10 text align_left" ><div style="width:100px;" class="button"><input type="submit" value="Отправить"></div></td>';
}

function mail_callback(&$par){		
		return '<table>
'.tpl::_a($par['list'],array('tpl_admin','mail_callback_list')).'
</table>';
}

function mail_callback_list(&$par){		
		return '<tr>
'.tpl::_a($par['text'],array('tpl_admin','mail_callback_list_text')).'	
'.tpl::_a($par['scrolltext'],array('tpl_admin','mail_callback_list_scrolltext')).'	
'.tpl::_a($par['input'],array('tpl_admin','mail_callback_list_input')).'	
'.tpl::_a($par['textarea'],array('tpl_admin','mail_callback_list_textarea')).'	
'.tpl::_a($par['checkbox'],array('tpl_admin','mail_callback_list_checkbox')).'	
</tr>';
}

function mail_callback_list_text(&$par){		
		return '<td colspan=3 ><b>'.(isset($par['text'])?$par['text']:'').'</b></td>';
}

function mail_callback_list_scrolltext(&$par){		
		return '<td colspan=3>
	'.(isset($par['title'])?$par['title']:'').'<hr>'.(isset($par['text'])?$par['text']:'').'<hr></td>';
}

function mail_callback_list_input(&$par){		
		return '<td ><b>'.(isset($par['tit'])?$par['tit']:'').tpl::_b($par['nocol'],'','').'&nbsp;</b></td><td colspan=2>'.(isset($par['value'])?$par['value']:'').'</td>';
}

function mail_callback_list_textarea(&$par){		
		return '<td><b>'.(isset($par['tit'])?$par['tit']:'').tpl::_b($par['nocol'],'','').'&nbsp;</b></td><td colspan=2>'.(isset($par['value'])?$par['value']:'').'</td>';
}

function mail_callback_list_checkbox(&$par){		
		return '<td ><b>'.(isset($par['tit'])?$par['tit']:'').tpl::_b($par['nocol'],'','').'&nbsp;</b></td>
<td colspan=2 >
	'.tpl::_a($par['check'],array('tpl_admin','mail_callback_list_checkbox_check')).'
	</td>';
}

function mail_callback_list_checkbox_check(&$par){		
		return '<div>+ '.(isset($par['text'])?$par['text']:'').'</div>';
}}
?>