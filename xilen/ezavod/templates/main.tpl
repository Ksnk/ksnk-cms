<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
            "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>{title|}</title>
<meta http-equiv="content-type"	content="text/html; charset=windows-1251">
<META name="description" content="{desc_words|}">
<META name="keywords" lang="ru" content="{key_words|}">
<base href="{::index}/">
<script type="text/javascript" src="{::index}/js/jquery.pack.js"> </script>
<script type="text/javascript" src="{::index}/js/jquery.easing.1.3.js" ></script>
<script type="text/javascript" src="{::index}/js/flv_player.js"></script>
<script type="text/javascript" src="{::index}/js/site.js"> </script>
<script type="text/javascript" src="{::index}/js/opisanie.js"></script>
<script type="text/javascript" src="{::index}/js/jquery.innerfade.js"> </script>

{::tpl:admin:script}

<LINK REL="STYLESHEET" TYPE="text/css" HREF="{::index}/style/main.css">
<link rel="icon" href="{::index}/favicon.ico" type="image/x-icon">
<link rel="shortcut icon" href="{::index}/favicon.ico" >

<script>


$(document).ready(function () {
     
    $('#first_menu li').hover(
        function () {
            //show its submenu
            $('ul', this).slideDown(300);
            $('a.red_line', this).animate({height: '40px'}, 150);
 
        },
        function () {
            //hide its submenu
            $('ul', this).slideUp(300);       
			$('a.red_line', this).animate({height: '44px'}, 150);
 
        }
    );
     
});



</script>

<script type="text/javascript" src="{::index}/js/easySlider2.js"></script>

<script type="text/javascript"> 
$(document).ready(function(){
$("#slider").easySlider({
loop: true,                            // Looping
autogeneratePagination: true,          // Automatically create links
nextId: 'nextA',
nextText: '<img src="{::index}/img/news_menu.gif" border="0" />',
prevId: 'prevA',
prevText: '<img src="{::index}/img/news_menu2.gif" border="0" />',
speed: 400,
orientation: 'vertical'
});

});

</script>

<script type="text/javascript">
	   $(document).ready(
				function(){

					$('ul#banner').innerfade({
						
						speed: 2000,
						timeout: 5000,
						type: 'random',
						containerheight: '383px'
					});
					
			});
  	</script>



</HEAD>

<BODY  BGCOLOR="#ffffff" TEXT="#ffffff" TOPMARGIN="0" LEFTMARGIN="0" RIGHTMARGIN="0" BOTTOMMARGIN="0"  MARGINWIDTH="0" MARGINHEIGHT="0">

	<TABLE width="100%" height="100%" style="background:#FFFFFF" class="main">

<!-- First row -->

			<TR height="227px">
            		<TD style=" background-color:#495663; background:url({::index}/img/bg1.gif) repeat;">
            
						<div style="position:relative; padding:0px; width:100%; height:227px;">
							
                            <!-- Flash block  -->   
							<div class="iePNG" style="position:absolute; z-index:9; margin-left:-275px; left:35%; background:url({::index}/img/bg2.png); width:631px; height:227px">
                            
<!--	                          <object vspace="0px" hspace="0px" style="margin:0px 0px 0px 0xp; padding:0px; display:block; border:none; background:none" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0" width="304px" height="227px"> 
										<param name=movie value="{::index}/flash/logo04.swf"> 
										<param name=quality value=high>
			                            <param name=wmode value=transparent>  
			                			<embed src="{::index}/flash/logo04.swf" wmode=transparent style="margin:0px 0px 0px 0px; padding:0px; border:none; display:block;" vspace="0px" hspace="0px"  width="304px" height="227px" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer"></embed>
		     					  </object>  
-->
                            
                            </div>
                            <!-- Left banner block  -->
							<div style="position:absolute; z-index:10; top:87px; left:0px; background:url({::index}/img/{::holiday_theme}) no-repeat bottom right; width:35%; height:140px; text-align:right;"><a title="На главную страницу" href="{::index}/" style="display:block; margin-top:50px"><img width="355px" height="90px" class="iePNG" src="{::index}/img/bg3_empty.gif"></a></div>
                            <!-- Top menu block (mail,search,sitemap,home) -->
                            <div style="position:absolute; z-index:11; margin-left:0px; top:0px; left:8%;">
                            		<table class="topmenu">
                                    	<tr>
                                        	<td><a title="Поиск" onclick="return showsearchbar();" class="search" onclick="return showsearchbar();" style="display: block; cursor: pointer;"><img src="{::index}/img/empty_topmenu.gif"></a></td>
                                        	<td class="empty"></td>
                                            <td class="home"><a title="На главную страницу"  href="{::index}/" class="home"><img class="iePNG" width="30px" height="47px" src="{::index}/img/bg5.png"></a></td>
                                            <td class="empty"></td>
                                            <td><a  title="Карта сайта"  href="{::index}/sitemap" class="sitemap"><img src="{::index}/img/empty_topmenu.gif"></a></td>
                                            <td class="empty"></td>
                                            <td><a title="Обратная связь" href="{::index}/writeus" class="mail"><img src="{::index}/img/empty_topmenu.gif"></a></td>
                                            <td class="empty"></td>
                                            <td>{::_tpl:tpl_jusers:_toplogin}</td>
                                        </tr>
                                    </table>
                                    
                                    <form action="?do=search" method="post" style="margin: 0px; padding: 0px;">
			                            <div id="searchbar" style="overflow: hidden; position: absolute; top: 55px; left: 5px; display: none;">							
            			                	<div style="padding: 4px 0px 0px 4px;">
                        			 			<table><tr>
                                    				<td style="padding: 0px;"><input value="Слово для поиска" name="search_string" style="font-size: 11px; line-height: 17px; width: 134px; height: 17px; border: 1px solid rgb(191, 191, 191);" type="text"></td>
													<td style="padding-left: 4px; vertical-align: middle;"><input name="search" src="{::index}/img/search1.gif" style="width: 8px; height: 15px; border: medium none; vertical-align: top; margin-left: 6px;" type="image" align="middle"></td>
				                         		</tr></table>
			                                </div>
										</div>
									</form>
                  
                            </div>
                            <!-- Block for advisory services on-line-->
                            <div style="position:absolute; z-index:12; width:21%; min-width:268px; margin-left:-134px; height:110px; top:90px; left:81%; background:url({::index}/img/phone.gif) no-repeat left 0px"><div style="top:0px; margin-top:3px; float:right; width:93px; height:87px; background:url({::index}/img/on-line.gif) center top no-repeat;"><a href="https://siteheart.com/webconsultation/94867?byhref=1&s=1" target="siteheart_sitewindow_94867" onclick="o=window.open;o('https://siteheart.com/webconsultation/94867?s=1', 'siteheart_sitewindow_94867', 'width=600,height=450,top=30,left=30,resizable=yes'); return false;" class="on-line">консультации</a></div></div>
                            <!-- Language menu block -->
                            <div style="position:absolute; z-index:13; width:75px; height:23px; top:22px; left:83%; padding: 4px 0px 0px 40px; background:url({::index}/img/basket.gif) left top no-repeat"><a class="basket_s" href="{::index}/basket">Корзина</a></div>


                        </div>


            		</TD>
           </TR>
   	       <TR height="44px">

<!-- Second row -->

					<TD style="border-bottom:1px solid #dee1e3; background-color:#FFFFFF;">
   
   
		 <!-- Main menu  -->               
						<div style="left:13%; margin-left:-110px; width:860px; height:44px; float:left; position:relative">
							{::menu_1}
                        </div>
                        <div style="float:right; position:relative; padding-top:12px; right:7%;"><a href="{::index}/"><img src="{::index}/img/eng2.gif"></a></div>
         
            		</TD>
            </TR>
            <TR height="470px">

<!-- Third row  -->
	<!-- First column -->
            		<TD>
                    	<table width="100%" height="300px">
                        	<tr>
                            	<td width="62%" align="right" style="padding-top:30px; min-width:610px; text-align:right">
		<!-- Catalog menu1  -->
									
<table style="border-spacing:0;width: 100%; margin: 0px 0 10px 0;">
	
    <tr>
    	<td class="gallery">
        
        <script type="text/javascript"> 

$(function(){
    //Get our elements for faster access and set overlay width
    var div = $('div.sc_menu5301'),
                 ul = $('ul.sc_menu5301'),
                 // unordered list's left margin
                 ulPadding = 15;

    //Get menu width
    var divWidth = div.width();

    //Remove scrollbars
    div.css({overflow: 'hidden'});

    //Find last image container
    var lastLi = ul.find('li:last-child');

    //When user move mouse over menu
    div.mousemove(function(e){

      //As images are loaded ul width increases,
      //so we recalculate it each time
      var ulWidth = lastLi[0].offsetLeft + lastLi.outerWidth() + ulPadding;

      var left = (e.pageX - div.offset().left) * (ulWidth-divWidth) / divWidth;
      div.scrollLeft(left);
    });
});

		</script>

      
			<div id="sc_menu_id" class="sc_menu5301" style="height:180px; float:right;">
			<ul class="sc_menu5301" style="height:180px;">
		<li><a rel="g5492" href="/test/ezavod/5258"  title="Элементы мощения">
 				     <div><img src="http://xln.su/test/ezavod/uploaded/el_kat1.jpg" alt="Элементы мощения"></div>
					 <span style="width:93px;">Элементы мощения</span>
			    </a></li><li><a rel="g2337" href="5259"  title="Ограничители движения">
 				     <div><img src="http://xln.su/test/ezavod/uploaded/el_kat2.jpg" alt="Ограничители движения"></div>
					 <span style="width:93px;">Ограничители движения</span>

			    </a></li><li><a rel="g3994" href="5296"  title="Бортовые камни">
 				     <div><img src="http://xln.su/test/ezavod/uploaded/el_kat3.jpg" alt="Бортовые камни"></div>
					 <span style="width:93px;">Бортовые камни</span>
			    </a></li><li><a rel="g7411" href="5297"  title="Тумбы, цветочницы, урны, сферы и др.">
 				     <div><img src="http://xln.su/test/ezavod/uploaded/el_kat4.jpg" alt="Тумбы, цветочницы, урны, сферы и др."></div>
					 <span style="width:93px;">Тумбы, цветочницы, урны, сферы и др.</span>
			    </a></li><li><a rel="g3349" href="5298"  title="Садово-парковые элементы">
 				     <div><img src="http://xln.su/test/ezavod/uploaded/el_kat5.jpg" alt="Садово-парковые элементы"></div>

					 <span style="width:93px;">Садово-парковые элементы</span>
			    </a></li><li><a rel="g3215" href="5299"  title="Товарный бетон и раствор">
 				     <div><img src="http://xln.su/test/ezavod/uploaded/el_kat6.jpg" alt="Товарный бетон и раствор"></div>
					 <span style="width:93px;">Товарный бетон и раствор</span>
			    </a></li><li><a rel="g5229" href="5300"  title="Элементы ограждения">
 				     <div><img src="http://xln.su/test/ezavod/uploaded/el_kat7.jpg" alt="Элементы ограждения"></div>
					 <span style="width:93px;">Элементы ограждения</span>

			    </a></li><li><a rel="g6551" href="uploaded/el_kat8.jpg"  title="Плиты перекрытий БПР">
 				     <div><img src="http://xln.su/test/ezavod/uploaded/el_kat8.jpg" alt="Плиты перекрытий БПР"></div>
					 <span style="width:93px;">Плиты перекрытий БПР</span>
			    </a></li><li><a rel="g1243" href="uploaded/el_kat9.jpg"  title="Перемычки декоративные">
 				     <div><img src="http://xln.su/test/ezavod/uploaded/el_kat9.jpg" alt="Перемычки декоративные"></div>
					 <span style="width:93px;">Перемычки декоративные</span>
			    </a></li><li><a rel="g9455" href="uploaded/el_kat10.jpg"  title="Перемычки железобетонные для зданий с кирпичными стенами">
 				     <div><img src="http://xln.su/test/ezavod/uploaded/el_kat10.jpg" alt="Перемычки железобетонные для зданий с кирпичными стенами"></div>

					 <span style="width:93px;">Перемычки железобетонные для зданий с кирпичными стенами</span>
			    </a></li><li><a rel="g7531" href="uploaded/el_kat11.jpg"  title="Трехслойные железобетонные стеновые панели">
 				     <div><img src="http://xln.su/test/ezavod/uploaded/el_kat11.jpg" alt="Трехслойные железобетонные стеновые панели"></div>
					 <span style="width:93px;">Трехслойные железобетонные стеновые панели</span>
			    </a></li><li><a rel="g8020" href="uploaded/el_kat12.jpg"  title="Плиты балконные">
 				     <div><img src="http://xln.su/test/ezavod/uploaded/el_kat12.jpg" alt="Плиты балконные"></div>
					 <span style="width:93px;">Плиты балконные</span>

			    </a></li><li><a rel="g3088" href="uploaded/el_kat13.jpg"  title="Ограждения балконов">
 				     <div><img src="http://xln.su/test/ezavod/uploaded/el_kat13.jpg" alt="Ограждения балконов"></div>
					 <span style="width:93px;">Ограждения балконов</span>
			    </a></li>
        		</div>
			</ul>
        </div> 
		
        </td>
	</tr>

</table>
                                    
		<!-- News block -->

									{::news_b}

                              </td>

                              <td width="38%" style="padding-top:42px;  padding-left:50px; padding-right:40px;" align="center">

		<!-- Second column -->

   			<!-- Project Block (Picture Slider) -->            
             <div style="width:275px; height:175px; float:none; position:relative"> 
                

		             <div style="width:257px; height:292px; border:10px solid #e3e6ea; float:none; position:absolute; z-index:50">
	                    <ul id="banner">        
   							  <li><img width="257" height="292" src="{::index}/img/pic1_01.jpg"></li>
     						  <li><img width="257" height="292" src="{::index}/img/pic1_02.jpg"></li>
							  <li><img width="257" height="292" src="{::index}/img/pic1_03.jpg"></li>
							  <li><img width="257" height="292" src="{::index}/img/pic1_04.jpg"></li>
							  <li><img width="257" height="292" src="{::index}/img/pic1_05.jpg"></li>
							  <li><img width="257" height="292" src="{::index}/img/pic1_06.jpg"></li>
		  				</ul>
                     </div>
                     <div style="float:none; height:29px; text-align:left; top:340px; position:absolute; z-index:51; padding-left:40px; background:url({::index}/img/nfirst.gif) no-repeat 10px top;"><a href="{::index}/?" class="banner_link">Просмотреть<br>все наши проекты</a></div>

               </div>
                
                     
                                </td>
                            </tr>                        
                        </table>            
            		</TD>
            </TR> 
            
            <TR height="350px">
            		<TD style="padding-top:0px; padding-bottom:40px">
                       
                       
                       	<table width="100%" height="284px" style="min-width:1003px" align="center">
                        	<tr>
                            	<td width="7%" style="padding-left:50px;  padding-bottom:5px">&nbsp;</td>
                                <td width="78%" style="background:url({::index}/img/earth2.jpg) no-repeat left top; padding-top:80px; padding-left:195px; padding-right:25px;  padding-bottom:5px; line-height:17px;">
                                
                                                                    {data}
                                
                                </td>                           
                            	<td width="15%" style="padding-left:50px; padding-bottom:5px">&nbsp;</td>
                            </tr>
                        </table>                      
                       
                                  
            		</TD>
            </TR>
            
            
           <TR height="133px">



            		<TD style="background:url({::index}/img/bg_bottom2.gif) top left repeat-x;">
                    
 							<div style="background:url({::index}/img/contakts.gif) 13% 0% no-repeat; ">
                                <table height="110px" width="100%" style="background: url({::index}/img/bg_bottom.gif) no-repeat top left"><tr><td width="12%">&nbsp;</td><td style=" font-size:11px; color:#748699; padding-top:50px; padding-left:34px; padding-right:30px; white-space:nowrap; line-height:17px">
                                <a class="link_bottom" href="mailto:sales@ezavodspb.ru">sales@ezavodspb.ru</a>&nbsp;&nbsp;/&nbsp;&nbsp;<a class="link_bottom" href="mailto:info@ezavodspb.ru">info@ezavodspb.ru</a>
                                <div style="float:none; clear:both; margin-top:5px">
								© 2011, ЗАО  Экспериментальный завод<br>195279, Россия, СПб, Индустриальный пр. 44, к. 1
                                </div>
								</td>
                                <td width="68%" style="padding-top:50px;"><ul class="social_networks"><li style="background:url({::index}/img/facebook.gif) left top no-repeat"><a href="">Facebook</a></li><li  style="background:url({::index}/img/lifejournal.gif) left top no-repeat"><a href="">LiveJournal</a></li><li  style="background:url({::index}/img/vkontakte.gif) left top no-repeat"><a href="">Вконтакте</a></li><li  style="background:url({::index}/img/twiter.gif) left top no-repeat"><a href="">Twiter</a></li></ul></td>
                                <td  style="padding-top:32px; white-space:nowrap; line-height:22px; padding-left:85px">
                                <a href="http://www.xilen.spb.ru"><img src="{::index}/img/xilen.gif"></a><br>
                                <a class="link_xilen" href="http://www.xilen.spb.ru">Создание сайтов</a>
                                </td><td width="20%">&nbsp;</td>
                                
                                </tr></table>
                           </div>
            
            		</TD>

           </TR>  
                     
           
	</TABLE>


</BODY>
</HTML>