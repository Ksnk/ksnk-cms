<!--  ////////////////// плагин Статьи /////////////////////// -->
<!--begin:articles_b-->
<div class="tahoma ctext size11"> <!-- id="a_brief" -->
<div class="article red" ><span>CТАТЬИ</span></div>
<!--begin:list-->
<p><b>{title}</b><br>
{text}</p>
<div class="tahoma ctext link size11 align_right" style="margin: 10px 0 10px 0;">
<a href="?do=articles&id={id}">&raquo;
Подробнее</a></div>
<!--end:list-->
<div class="tahoma ctext link size11" style="margin: 10px 0 10px 0;">
<a href="?do=articles">&raquo;
Список статей</a></div>
</div>
<!--end:articles_b-->

<!--begin:articles-->
<div class="tahoma ctext" style="padding-top:40px;"> <!-- id="a_brief" -->
<p style="font-size:16px;"><b>Список статей</b></p><br>
<ul class='ctext'>
<!--begin:list-->
<li><a class="atitle" href="?do=articles&id={id}"><b>{title}</b></a></li>
<!--end:list-->
{pages}
</div>
<!--end:articles-->

<!--begin:article-->
<div class="tahoma ctext" style="padding-top:40px;"> <!-- id="a_brief" -->
<!--begin:list-->
<div style="width:60%"><b><p style="font-size:16px;">{title}
</p></b></div>
<br>
<p>{text}</p>
<br>
<!--end:list-->
</div>
<!--end:article-->
