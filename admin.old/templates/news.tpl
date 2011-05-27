<!--  ////////////////// плагин Новости /////////////////////// -->
<!--begin:news_b-->
<div style="padding-bottom:30px;">
<!--begin:news-->
<div class="ctext link tahoma" style="margin-bottom:34px;">
<div style="width:35%;color:#3f5773;font-size:24px;border-bottom:solid 1px #eb8017;padding-top:14px;padding-bottom:6px;margin:5px 0;float:left;">
{date>>Day}
</div>
<div style="width:64%;color:#3f5773;font-size:24px;border-bottom:solid 1px #d7dee9;padding-top:14px;padding-bottom:6px;margin:5px 0;float:left;">&nbsp;</div>
<div style="margin-top:0px;clear:left;" class="cltext">{date>>rusM} {date>>rusY}</div>
<p class="size12" style="padding-top:12px;padding-bottom:12px;margin-right:20px;"><a class="blue" href="?do=newslist&id={id}">{title}</a></p>
<p class="size11" style="margin-right:20px;">{text}</p>
</div>
<!--end:news-->
<div class="tahoma blue" style="margin-top:60px;">
<span style="font-weight:bold;font-size:11px;">Показать</span><br/>
<!--begin:years-->
<a href="{::curl:do:id}do=news&year={year}">{year}</a> {last|/}<br/>
<!--end:years-->
</div>
</div>
<!--end:news_b-->

<!--begin:newslist-->

<div class="tahoma ctext" style="margin-top:15px;">
<!--begin:news-->
<br>
<b>{date}</b><br>
<b>{title}</b><br>
<!--begin:img-->
<div class="gallery" style="padding:10px 0;">
<a href="{big_pict}"><img src="{small_pict}"/></a>
</div>
<!--end:img-->
<p>{text}</p><br>
<!--end:news-->
{pages}
</div>
<!--end:newslist-->