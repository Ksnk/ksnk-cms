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
<script type="text/javascript" src="{::index}/js/flv_player.js"></script>
<script type="text/javascript" src="{::index}/js/site.js"> </script>
<script type="text/javascript" src="{::index}/js/opisanie.js"></script>
<script type="text/javascript" src="{::index}/js/jquery.innerfade.js"> </script>

{::tpl:admin:script}

<LINK REL="STYLESHEET" TYPE="text/css" HREF="{::index}/style/main.css">
<link rel="icon" href="{::index}/favicon.ico" type="image/x-icon">
<link rel="shortcut icon" href="{::index}/favicon.ico" >

<script>


function show_block(id)
	{
		$('a.' + id).animate({height: '40px'}, 150);
	}

function hide_block(id)
	{
		$('a.' + id).animate({height: '44px'}, 150);
	}


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
							<div style="position:absolute; z-index:10; top:87px; left:0px; background:url({::index}/img/bg3_1.gif) repeat-x bottom; width:35%; height:140px; text-align:right"><a title="На главную страницу" href="{::index}/"><img width="355px" height="140px" class="iePNG" src="{::index}/img/{::holiday_theme}"></a></div>
                            <!-- Top menu block (mail,search,sitemap,home) -->
                            <div style="position:absolute; z-index:11; margin-left:0px; top:0px; left:8%;">
                            		<table class="topmenu">
                                    	<tr>
                                        	<td><a title="Поиск" onclick="return showsearchbar();" class="search" onclick="return showsearchbar();" style="display: block; cursor: pointer;"><img src="{::index}/img/empty_topmenu.gif"></a></td>
                                        	<td class="empty"></td>
                                            <td class="home"><a title="На главную страницу"  href="{::index}/" class="home"><img class="iePNG" width="30px" height="47px" src="{::index}/img/bg5.png"></a></td>
                                            <td class="empty"></td>
                                            <td><a  title="Карта сайта"  href="{::index}/sitemap" class="sitemap"><img src="{::index}/img/empty_topmenu.gif"></a></td>
                                            <td class="empty"></td><td><a title="Обратная связь" href="{::index}/writeus" class="mail"><img src="{::index}/img/empty_topmenu.gif"></a></td>
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
                            <div style="position:absolute; z-index:12; width:21%; min-width:268px; margin-left:-134px; height:84px; top:104px; left:81%; background:url({::index}/img/phone.gif) no-repeat left 15px"><div style="top:0px; float:right; width:93px; height:87px; background:url({::index}/img/on-line.gif) center top no-repeat;"><a href="https://siteheart.com/webconsultation/94867?byhref=1&s=1" target="siteheart_sitewindow_94867" onclick="o=window.open;o('https://siteheart.com/webconsultation/94867?s=1', 'siteheart_sitewindow_94867', 'width=600,height=450,top=30,left=30,resizable=yes'); return false;" class="on-line">консультации</a></div></div>
                            <!-- Language menu block -->
                            <div style="position:absolute; z-index:13; width:75px; height:23px; top:22px; left:83%;"><div style="float:left; width:37px; height:23px;"><a href="{::index}/"><img src="{::index}/img/rus.gif"></a></div><div style="float:left; width:37px; height:23px;"><a href="{::index}/"><img src="{::index}/img/eng.gif"></a></div></div>

                        </div>


            		</TD>
           </TR>
   	       <TR height="44px">

<!-- Second row -->

					<TD style="border-bottom:1px solid #dee1e3; background-color:#FFFFFF;">
   
   
		 <!-- Main menu  -->               
						<div style="left:13%; position:relative; margin-left:-110px; width:900px; height:44px">
							{::menu_1}
                        </div>

         
            		</TD>
            </TR>
            <TR height="545px">

<!-- Third row  -->
	<!-- First column -->
            		<TD>
                    	<table width="100%" height="545px">
                        	<tr>
                            	<td width="62%" align="right" style="padding-top:17px; min-width:610px; text-align:right">
		<!-- Catalog menu1  -->
									{data}





		<!-- News block -->

			               

	{::news_b}


                
         
                               </td>

                                <td width="38%" style=" padding-left:40px; padding-right:40px;" align="center">


	<!-- Second column -->



 
 			<!--Basket Block -->            
             <div style="width:275px; height:129px; border: 1px solid #e3e6ea; background:url({::index}/img/bg_order.gif) no-repeat; margin-top:31px; position:relative; float:none"> 
             
 				<!-- Link for basket picture-->
             	<div style="position:absolute; top:37px; left:103px; height:23px; width:102px;"><a href="{::index}/basket" style="display:block; height:23px; width:102px;"></a></div>
				<!-- Block for basket text-->
             	<div style="position:absolute; top:76px; left:111px; height:40px; width:150px;" class="basket">
                	{basket::_basket}
                </div>
                             
             </div>

   			<!-- Project Block (Picture Slider) -->            
             <div style="width:275px; height:175px; margin-top:32px; float:none; position:relative"> 
                

		             <div style="width:255px; height:175px; border:10px solid #e3e6ea; float:none; position:absolute; z-index:50">
	                    <ul id="banner">        
   							  <li><img width="255" height="175" src="{::index}/uploaded/picture_slider1m.jpg"></li>
     						  <li><img width="255" height="175" src="{::index}/uploaded/picture_slider2m.jpg"></li>
							  <li><img width="255" height="175" src="{::index}/uploaded/picture_slider3m.jpg"></li>
							  <li><img width="255" height="175" src="{::index}/uploaded/picture_slider4m.jpg"></li>
							  <li><img width="255" height="175" src="{::index}/uploaded/picture_slider5m.jpg"></li>
		  				</ul>
                     </div>
                     <div style="float:none; text-align:left; top:210px; position:absolute; z-index:51; padding-left:10px;"><a href="{::index}/?" class="banner_link">Посмотреть все проекты</a></div>

               </div>
                
                     
                                
                                </td>
                            </tr>                        
                        </table>            
            		</TD>
            </TR> 
            
            <TR height="330px">
            		<TD style="padding-top:37px; padding-bottom:60px">
                       
                       
                       	<table width="100%" height="284px" style="min-width:1003px" align="center">
                        	<tr>
                            	<td width="28%" style="background:url({::index}/img/telephone.gif) no-repeat left top; padding-top:50px; padding-left:114px;  padding-bottom:5px">
                                	<div style="float:none"><img src="{::index}/img/t68.gif"></div>
	                                <div style="float:none; margin-top:5px;"><img src="{::index}/img/t68_1.gif"></div>
                                    <div style="float:none; margin-top:20px; font-size: 11px; color:#748699">195279, Россия,<br>
										 Санкт-Петербург,<br>
   										 Индустриальный пр. 44,<br>
  										 корпус 1<br><br>
										 http://www.ezavodspb.ru</div>
                                </td>
                                <td width="32%" style="background:url({::index}/img/earth.gif) no-repeat left top; padding-top:48px; padding-left:128px; padding-right:25px;  padding-bottom:5px">
                                
                                	<ul id="contacts">
                                        <li><a href="{::index}/?">О компании</a></li>
										<li><a href="{::index}/?">Ценные бумаги</a></li>
										<li><a href="{::index}/?">Референц-лист объектов</a></li>
										<li><a href="{::index}/?">Производство и инновации</a></li>
										<li><a href="{::index}/?">Политика в области качества</a></li>
										<li><a href="{::index}/?">Исследования</a></li>
										<li><a href="{::index}/?">Испытательная лаборатория</a></li>
										<li><a href="{::index}/?">Вакансии</a></li>
										<li><a href="{::index}/?">ЭЛЕКТРОЛЮКС РУС</a></li>
										<li><a href="{::index}/?">Логистические услуги</a></li>
										<li><a href="{::index}/?">По местам боевой славы</a></li>
										<li><a href="{::index}/?">Информация акционерного общества</a></li>
                                	</ul>
                                
                                </td>                           
                            	<td width="39%" style="background:url({::index}/img/certificats.gif) no-repeat left top; padding-top:48px; padding-left:138px; padding-right:35px; padding-bottom:5px">
                                
                   					<ul id="certificats">
                                        <li><a href="{::index}/?">Сертификат соответствия EN ISO 9001:2008 rus</a></li>
										<li><a href="{::index}/?">Сертификат соответствия EN ISO 9001:2008 eng</a></li>
										<li><a href="{::index}/?">Диплом за большой вклад в развитие строительного комплекса СПб</a></li>
										<li><a href="{::index}/?">Почетное свидетельство за развитие предпринимательства в СПб</a></li>
										<li><a href="{::index}/?">Свидетельство члена Санкт-Петербургского Союза строительных компаний</a></li>
                                    </ul>
                                
                                
                                </td>
                            </tr>
                        </table>                      
                       
                                  
            		</TD>
            </TR>
            
            
           <TR height="133px">



            		<TD style="background: url({::index}/img/bg_bottom.gif) no-repeat top left #eff1f4">
                    
 								<table height="110px" width="100%"><tr><td width="12%">&nbsp;</td><td style=" font-size:11px; color:#748699; padding-top:36px; padding-left:34px; white-space:nowrap; line-height:17px">
                                <a class="link_bottom" href="mailto:sales@ezavodspb.ru">sales@ezavodspb.ru</a>&nbsp;&nbsp;/&nbsp;&nbsp;<a class="link_bottom" href="mailto:info@ezavodspb.ru">info@ezavodspb.ru</a><br>
								© ЗАО "Экспериментальный завод", 2008
								</td>
                                <td width="68%">&nbsp;</td>
                                <td  style="padding-top:36px; white-space:nowrap; line-height:22px">
                                <a href="http://www.xilen.spb.ru"><img src="{::index}/img/xilen.gif"></a><br>
                                <a class="link_xilen" href="http://www.xilen.spb.ru">Создание сайтов</a>
                                </td><td width="20%">&nbsp;</td>
                                
                                </tr></table>
            
            		</TD>

           </TR>  
                     
           
	</TABLE>


</BODY>
</HTML>