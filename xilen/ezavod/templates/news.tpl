<!--  ////////////////// плагин Новости /////////////////////// -->
<!--begin:news_b-->
			<div style="float:none; clear:both; width:100%; background: url({::index}/img/bg_news.gif) repeat-x top #eff1f4; margin-top:20px;">

				<div style="float:none; clear:both; width:600px; right:0px; background:url({::index}/img/logo_news.gif) no-repeat 15% 0%; padding: 43px 30px 0px 0px; margin-left:auto">


<div id="examples" align="right">
<div id="slider">
						<ul>
<!--begin:news2-->
							<li>                    
                            	<table style="background:url({::index}/img/line_news.gif) no-repeat center 5px">
 					               <tr><td style="padding: 4px 17px 0px 0px;">
                                   		<div class="news_date1">{date>>Day}</div>
                                   		<div class="news_year1">{date>>M}/{date>>Y}</div>
                                   </td><td width="50%" align="left" style="padding: 0px 10px 25px 0px">
                                    	<a href="?do=newslist&id={id}" class="news_link1">{text_b}</a>
                                   </td><td style="padding: 4px 17px 0px 0px;">
                                   		<div class="news_date1">{date2>>Day}</div>
                                   		<div class="news_year1">{date2>>M}/{date2>>Y}</div>
                                   </td><td width="50%" align="left" style="padding: 0px 10px 25px 0px">
                                    	<a href="?do=newslist&id={id2}" class="news_link1">{text_b2}</a>
                                   </td></tr>
                  				</table>  

                    		</li>
<!--end:news2-->
  						</ul>
</div>
</div>

                </div>
			</div>
            

            <div style="float:right; clear:both; width:600px; right:0px; height:40px; text-align:left; background:url({::index}/img/news_arch.gif) top left no-repeat;">
            <div style="margin-left:130px; padding-top:11px; color:#5586ba; ">


<!--begin:news::years-->
<a class="news_year2" href="{::curl:do:id}do=newslist&year={year}">{year}</a></span> {last|/&nbsp;}
<!--end:news::years-->
			
            </div>
            </div>

<!--end:news_b-->

<!--begin:news_x-->
<div style="padding:0 0 0px 60px;">
<!--begin:news-->
<div class="ctext link tahoma" style="float:left;width:245px;">


<div style="background:url(/img/newsdate.gif) no-repeat;padding:12px 10px 15px 22px;" class="size16 "><b>{date>>Day}/{date>>M}</b>
<span class="size11" style="padding-left:40px">{date>>Y}</span>
</div>
<div style="padding-left:5px; padding-right:30px;"><br><a href="?do=newslist&id={id}" class="news_header">{title}</a></div>
<div style="padding:20px 30px 30px 5px;">
<p style="color:#ffe6cc" class="size12">{text_b}</p>
</div>
</div>
<!--end:news-->
<div class="size11 menu" style="clear:both;color:#bb580e;padding:0px 0 10px 5px;">
Архив новостей</div>
<div class="link size12" style="color:#fdee9a;padding:7px 0 10px 5px;">
<!--begin:news::years-->
<span class="link" style=";margin-top:7px;">
<a href="{::curl:do:id}do=newslist&year={year}">{year}</a></span> {last|/}
<!--end:news::years--></div>

</div>
<!--end:news_x-->



<!--begin:newslist-->

<div class="tahoma ctext">
<!--begin:news-->

<table style="background:url({::index}/img/line_news.gif) no-repeat center 5px; margin-bottom: 50px">
 					               <tr><td style="padding: 4px 27px 0px 0px;">
                                   		<div class="news_date1">{date>>Day}</div>
                                   		<div class="news_year1">{date>>M}/{date>>Y}</div>
                                   </td><td align="left" style="padding: 0px 0px 25px 0px">
                                    	


<!--begin:img-->

<div class="float_left box">
		{pict}
</div> 
    

<!--end:img-->
								   </td><td width="100%" style="padding: 4px 0px 0px 10px;">
 <div style="padding: 0px 0px 25px 0px; float:none; font-size:15px; font-weight:600">{title}</div>
 
 <div style="">{text}</div>
                                   </td></tr>

</td></tr></table> 
<!--end:news-->

<div style="padding-top:30px;clear:both;">
{pages}
</div>
</div>
<!--end:newslist-->