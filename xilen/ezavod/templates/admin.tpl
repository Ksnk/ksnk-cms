<!--begin:script-->
<script type="text/javascript" src="{::index}/js/jquery.colorbox.js"></script>
<script type="text/javascript">
//$.fn.colorbox.settings.width = {::get_param:pictute_xxwidth}+94;
//$.fn.colorbox.settings.height = {::get_param:pictute_xxheight}+172;
$.fn.colorbox.settings.current = "";
$.fn.colorbox.settings.opacity = 0.6;  
</script>
<link type="text/css" media="screen" rel="stylesheet" href="{::index}/colorbox.css" >
<!--[if IE]>
<link type="text/css" media="screen" rel="stylesheet" href="{::index}/colorbox-ie.css" title="example" />
<![endif]-->
<!--[if lt IE 7]>
<![if gte IE 5.5]>
<script type="text/javascript" src="{::index}/js/fixpng.js"></script>
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
	$('.tabs dt').click(function(){
		$('.tabs .over').removeClass('over');
		$(this).addClass('over');		
		$(this).next('dd').addClass('over');		
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
				//$(this).stop(true,true).slideDown("fast",function(){this.style.height='auto';})
				$(this).stop(false,true).css({display:'block','height':1}).animate({ height:100},'fast',function(){this.style.height='auto';});
			}
			,hide:function(){
				$(this).stop(false,true).animate({ height:1},'fast',function(){
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
		var x=$(this).children('li');
		x.children('ul').each(function(){
			dd_menu.call(this,level+1);
		});
		$(x[0]).addClass('first');
	}
	
	$('ul.ulmenu>li').each(function(){
		
		if($(this).find('ul').each(function(){
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
	$('#homemenu img')
		//.css({opacity:0.5})
		.mouseover(function(){
			$(this) //.css({opacity:0.99})
			.parent().parent().addClass('round');
		})
		.mouseout(function(){
			$(this) //.css({opacity:0.5})
			.parent().parent().removeClass('round');
		})
	/*$('#homemenu img').eq(0).each(function(){
			$(this).css({opacity:0.99})
			.parent().parent().addClass('round');
		})*/
});
</script>
<!--end:script-->

<!--begin:homemenu-->
		<div id="homemenu" style="position:absolute;width:192px;height:32px;top:42px;left:7%;">
			<table class="long wide fixed"><col width="auto"><col width="auto"><col width="auto"><tr>
			<td class="align_middle align_center"><a href="{::index}/"><img src="img/home_wt.gif" alt=""></a></td>
			<td class="align_middle align_center"><a href="{::curl:do:id}do=writeus"><img src="img/mailus_wt.gif" alt=""></a></td>
			<td class="align_middle align_center"><a href="{::curl:do:id}do=sitemap"><img src="img/sitemap_wt.gif" alt=""></a></td>
			</tr></table>
		</div>
<!--end:homemenu-->

<!--begin:bottom--><tr>
		<td  style="height:137px"><div class="wide" style="position:relative">
		<div class="link size12" style="position:absolute; height:74px;width:149px; top:-90px; left:77px; background:url(img/demteatr.gif) no-repeat;"></div>
	
		<div class="link size12" style="position:absolute; height:164px;width:213px; top:-162px; right:40px; background:url(img/phone.gif) no-repeat;">
		<div style="line-height: 16px;padding:63px 0 0 75px; color:#fed4b2;">
		Россия, СПб,<br>
		Невский пр., д.111<br>
        <a href="mailto:demid@nevski.ru">demid@nevski.ru</a>
		<a style="color:#fdee9a" href="mailto://demid@nevski.ru">{::_par:mail_admin}</a>
		</div>
		</div>
		<div  style="color:#fed4b2; position:absolute; height:185px;width:262px; top:-162px; right:253px; background:url(img/photogall.gif) no-repeat;">
		<a style="display:block;text-decoration:none;" class="long wide" href="{::index}/photo_gallery">&nbsp;</a>
		</div>
		<div style="position:absolute;height:84px;width:93px;top:-81px;right:581px;background:url(img/x2.gif)"></div>
		<div style="position:absolute;height:28px;width:67px;top:-1;right:673px;background:url(img/x1.gif)"></div>
		<div  style="position:absolute; top:50px; width:705px; margin-left:-350px; left:52%;">
			<table class="link size11"><tr><!--begin:::menu:head-->
			<td style="padding:0 16px 0 16px;"><a class="white {current}" href="{url|#}">{item}</a></td>
			{last|<td>|</td>}
			<!--end:::menu:head--></tr></tr></table>
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
</tr><!--end:bottom-->


<!--begin:counters-->


<!--SpyLOG-->
<span id="spylog2026286"></span><script type="text/javascript"> var spylog = { counter: 2026286, image: undefined, next: spylog }; document.write(unescape("%3Cscript src=%22http" +
(("https:" == document.location.protocol) ? "s" : "") +
"://counter.spylog.com/cnt.js%22 defer=%22defer%22%3E%3C/script%3E")); </script>
<!--SpyLOG--><!--end:counters-->


<!--
 Администрирование

 - форма ввода панели "Список загрузок файлов"

 -->
<!--begin:order_elm_start-->
<table class="compact"><tr><td><input type="button" class="win_max" style="background-position:0 -120px;"
 onclick="order(this,'+');"
>
</td><td>
<!--end:order_elm_start-->

<!--begin:order_elm_fin-->
</td><td>
<input type="button" class="win_max" style="background-position:0 -105px;"
 onclick="order(this,'-');"
>
</td></tr></table>
<!--end:order_elm_fin-->

<!--begin:align_elm-->
<table class="compact glass"><tr><td>cлева</td><td>центр</td><td>справа</td></tr>
<tr><td><input  type="radio" name="align" value="0"></td><td>
<input  type="radio" name="align" value="1"></td><td>
<input  type="radio" name="align"  value="2"></td></tr></table>
<!--end:align_elm-->

<!--begin:win_elm-->
	<div class="win_max open_close">&nbsp;</div>
<!--end:win_elm-->

<!--begin:delrec_elm-->
<input type="button" class="win_max" style="background-position:0 0;"
 onclick="delrec(this)">
<!--end:delrec_elm-->

<!--begin:psubm_elm-->
<input type="submit" class="win_max" style="background-position:0 -90px;" value='&nbsp;'>
<!--end:psubm_elm-->


<!--begin:href-->
<form action="" method="POST" name="href">

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
</th><td colspan=3>{::tpl:admin:align_elm}
</td></tr>
<tr><th colspan=5 class="align_center" style="background:white;height:30px">
</th></tr>
<tr>
<th>ссылка</th>
<th>текст</th>
<th>порядок</th>
<th>{::tpl:admin:win_elm}</th>
</tr>
<!--begin:list-->
<tr id="id_{id}">
<td >
<input type="text" name="filename_{id}">
</td>
<td ><input type="text" style="width:400px" name="text_{id}"></td>
<td class="align_center" style="width:55px;" nowrap>
{::tpl:admin:order_elm_start}
<input type="text" class="order" style="width:15px;" name="order_{id}">
{::tpl:admin:order_elm_fin}
</td>
<td>{::tpl:admin:delrec_elm}
</td>
</tr>
<!--end:list-->
<tr>
<td>
<input  type="text" name="filename">
</td>
<td><input type="hidden" name="id">
<input  type="text" name="text"></td>
<td></td><td>
{::tpl:admin:psubm_elm}
<input type="hidden" class="del" name="del">
</td>
</tr>
<tr><th colspan=5 height="5px;"></th></tr><tr>
<th colspan=5 class="align_center" style="background:white;height:30px">
<input type="submit" style="width:auto;"  name="save" value="Сохранить">
</th>
</table>
{pages}
</form>
<!--end:href-->

<!--begin:uploads-->
<form action="" method="POST" name="uploads">

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
</th><td colspan=3>{::tpl:admin:align_elm}
</td></tr>
<tr><th colspan=5 class="align_center" style="background:white;height:30px">
</th></tr>
<tr>
<th>файл</th>
<th>описание</th>
<th>порядок</th>
<th>{::tpl:admin:win_elm}</th>
</tr>
<!--begin:list-->
<tr id="id_{id}">
<td class="uploader" style="width:150px;height:auto;">
<input type="text" name="filename_{id}">
</td>
<td ><input type="text" style="width:400px" name="text_{id}"></td>
<td class="align_center" style="width:55px;">
{::tpl:admin:order_elm_start}
<input type="text" class="order" style="width:15px;" name="order_{id}">
{::tpl:admin:order_elm_fin}
</td>
<td>{::tpl:admin:delrec_elm}</td>
</tr>
<!--end:list-->
<tr>
<td class="uploader" style="height:auto;">
<input  type="text" name="filename">
</td>
<td><input type="hidden" name="id">
<input  type="text" name="text"></td>
<td></td><td>
{::tpl:admin:psubm_elm}
<input type="hidden" class="del" name="del">
</td>
</tr>
<tr><th colspan=5 height="5px;"></th></tr>
<tr><th colspan=5 class="align_center" style="background:white;height:30px">
<input type="submit" style="width:auto;"  name="save" value="Сохранить">
</th></tr>
</table>
{pages}
</form>
<!--end:uploads-->
<!--begin:callback-->
<form style="padding:0; margin:0" action="" method="POST" name="callback">
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
<span class="bold red">{error}</span>
<!--begin:hidden-->
<input class="hidden" ntype="text" name="{text}"><!--end:hidden-->
<input  class="hidden" type="text" name="hidden_value" value="secret">
<table class="table size12">
<!--begin:list-->	
<tr class="{even?even:odd}">
    <!--begin:text-->
    <td colspan=3 class="pad10 text bold align_center" >{text}</td>
    <!--end:text-->
    <!--begin:password-->
    <td class="pad10 text align_right">{tit}{nocol?::}&nbsp;{nostar?&nbsp;:<sup><b>*</b></sup>}</td>
    <td colspan=2 class="pad10 text"><input  class="input" type="password" name="{name}">{comment}</td>
    <!--end:password-->
<!--begin:scrolltext-->
<td colspan=3 class="pad10 text align_right" >
	{title}<div style="overflow:auto;">{text}</div></td>
<!--end:scrolltext-->	
<!--begin:input-->
<td class="pad10 text align_right">{tit}{nocol?::}&nbsp;{nostar?&nbsp;:<sup><b>*</b></sup>}</td>
<td colspan=2 class="pad10 text"><input  class="input" type="text" name="{name}">{comment}</td>
<!--end:input-->	
<!--begin:textarea-->	
<td class="pad10 text align_right">{tit}{nocol?::}&nbsp;{nostar?&nbsp;:<sup><b>*</b></sup>}</td><td colspan=2 class="pad10 text"><textarea  class="input" name="{name}"></textarea></td>
<!--end:textarea-->	
<!--begin:checkbox-->	
<td class="pad10 text align_right">{tit}{nocol?::}&nbsp;{nostar?&nbsp;:<sup><b>*</b></sup>}</td>
<td colspan=2 class="pad10 text">
	<!--begin:check-->
	<div><input type="checkbox" name="{name}" value="{value}">{text}</div>
	<!--end:check-->
	</td>
<!--end:checkbox-->	
<!--begin:radio-->	
<td class="pad10 text align_right">{tit}{nocol?::}&nbsp;{nostar?&nbsp;:<sup><b>*</b></sup>}</td>
<td colspan=2 class="pad10 text">
	<!--begin:check-->
	<div><input type="radio" name="{name}" value="{value}">{text}</div>
	<!--end:check-->
	</td>
<!--end:radio-->	
<!--begin:captcha-->	
<td class="pad10 text align_right" >Введите номер, изображенный на картинке:<br></td><td class="text" style="vertical-align:middle; padding-left:10px"><input class="input2"  type="text" name="captcha" /></td>
<td><img src="{::index}/captcha.php" alt="" />
</td>
<!--end:captcha-->	
<!--begin:submit-->	
<td class="text pad10" ></td><td colspan=2 class="pad10 text align_left" ><div style="width:100px;" class="button"><input type="submit" value="Отправить"></div></td>
<!--end:submit-->	
</tr>
<!--end:list-->
</table>
</form>
<!--end:callback-->
<!--begin:mail_callback-->
<table>
<!--begin:list-->	
<tr>
<!--begin:text-->	
<td colspan=3 ><b>{text}</b></td>
<!--end:text-->	
<!--begin:scrolltext-->	
<td colspan=3>
	{title}<hr>{text}<hr></td>
<!--end:scrolltext-->	
<!--begin:input-->
<td ><b>{tit}{nocol?::}&nbsp;</b></td><td colspan=2>{value}</td>
<!--end:input-->	
<!--begin:textarea-->	
<td><b>{tit}{nocol?::}&nbsp;</b></td><td colspan=2>{value}</td>
<!--end:textarea-->	
<!--begin:checkbox-->	
<td ><b>{tit}{nocol?::}&nbsp;</b></td>
<td colspan=2 >
	<!--begin:check-->
	<div>+ {text}</div>
	<!--end:check-->
	</td>
<!--end:checkbox-->	
</tr>
<!--end:list-->
</table>
<!--end:mail_callback-->