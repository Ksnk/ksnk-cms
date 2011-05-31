<?php
class tpl_second extends tpl {

function _(&$par){		
		return '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
            "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>'.tpl::_d($par['title'],'').'</title>
<meta http-equiv="content-type"	content="text/html; charset=windows-1251">
<META name="description" content="'.tpl::_d($par['desc_words'],'').'">
<META name="keywords" lang="ru" content="'.tpl::_d($par['key_words'],'').'">
<base href="'.tpl::_ax(tpl::_export('','index'),array('tpl_second','__index')).'/">
<script type="text/javascript" src="'.tpl::_ax(tpl::_export('','index'),array('tpl_second','__index')).'/js/jquery.pack.js"> </script>
<script type="text/javascript" src="'.tpl::_ax(tpl::_export('','index'),array('tpl_second','__index')).'/js/flv_player.js"></script>
<script type="text/javascript" src="'.tpl::_ax(tpl::_export('','index'),array('tpl_second','__index')).'/js/site.js"> </script>
<script type="text/javascript" src="'.tpl::_ax(tpl::_export('','index'),array('tpl_second','__index')).'/js/opisanie.js"></script>

'.tpl::_ax(tpl::_export('','tpl','admin','script'),array('tpl_second','__tpl_admin_script')).'

<LINK REL="STYLESHEET" TYPE="text/css" HREF="'.tpl::_ax(tpl::_export('','index'),array('tpl_second','__index')).'/style/main.css">
<link rel="icon" href="'.tpl::_ax(tpl::_export('','index'),array('tpl_second','__index')).'/favicon.ico" type="image/x-icon">
<link rel="shortcut icon" href="'.tpl::_ax(tpl::_export('','index'),array('tpl_second','__index')).'/favicon.ico" >

<script>


function show_block(id)
	{
		$(\'a.\' + id).animate({height: \'40px\'}, 150);
	}

function hide_block(id)
	{
		$(\'a.\' + id).animate({height: \'44px\'}, 150);
	}


</script>

</HEAD>

<BODY  BGCOLOR="#ffffff" TEXT="#ffffff" TOPMARGIN="0" LEFTMARGIN="0" RIGHTMARGIN="0" BOTTOMMARGIN="0"  MARGINWIDTH="0" MARGINHEIGHT="0">

	<TABLE width="100%" height="100%" style="background:#FFFFFF" class="main">

<!-- First row -->

			<TR height="227px">
            		<TD style=" background-color:#495663; background:url('.tpl::_ax(tpl::_export('','index'),array('tpl_second','__index')).'/img/bg1.gif) repeat;">
            
						<div style="position:relative; padding:0px; width:100%; height:227px;">
							
                            <!-- Flash block  -->   
							<div class="iePNG" style="position:absolute; z-index:9; margin-left:-275px; left:35%; background:url('.tpl::_ax(tpl::_export('','index'),array('tpl_second','__index')).'/img/bg2.png); width:631px; height:227px">
                            
<!--	                          <object vspace="0px" hspace="0px" style="margin:0px 0px 0px 0xp; padding:0px; display:block; border:none; background:none" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0" width="304px" height="227px"> 
										<param name=movie value="'.tpl::_ax(tpl::_export('','index'),array('tpl_second','__index')).'/flash/logo04.swf"> 
										<param name=quality value=high>
			                            <param name=wmode value=transparent>  
			                			<embed src="'.tpl::_ax(tpl::_export('','index'),array('tpl_second','__index')).'/flash/logo04.swf" wmode=transparent style="margin:0px 0px 0px 0px; padding:0px; border:none; display:block;" vspace="0px" hspace="0px"  width="304px" height="227px" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer"></embed>
		     					  </object>  
-->
                            
                            </div>
                            <!-- Left banner block  -->
							<div style="position:absolute; z-index:10; top:87px; left:0px; background:url('.tpl::_ax(tpl::_export('','index'),array('tpl_second','__index')).'/img/bg3_1.gif) repeat-x bottom; width:35%; height:140px; text-align:right"><a title="На главную страницу" href="'.tpl::_ax(tpl::_export('','index'),array('tpl_second','__index')).'/"><img width="355px" height="140px" class="iePNG" src="'.tpl::_ax(tpl::_export('','index'),array('tpl_second','__index')).'/img/'.tpl::_ax(tpl::_export('','holiday_theme'),array('tpl_second','__holiday_theme')).'"></a></div>
                            <!-- Top menu block (mail,search,sitemap,home) -->
                            <div style="position:absolute; z-index:11; margin-left:0px; top:0px; left:8%;">
                            		<table class="topmenu">
                                    	<tr>
                                        	<td><a title="Поиск" onclick="return showsearchbar();" class="search" onclick="return showsearchbar();" style="display: block; cursor: pointer;"><img src="'.tpl::_ax(tpl::_export('','index'),array('tpl_second','__index')).'/img/empty_topmenu.gif"></a></td>
                                        	<td class="empty"></td>
                                            <td class="home"><a title="На главную страницу"  href="'.tpl::_ax(tpl::_export('','index'),array('tpl_second','__index')).'/" class="home"><img class="iePNG" width="30px" height="47px" src="'.tpl::_ax(tpl::_export('','index'),array('tpl_second','__index')).'/img/bg5.png"></a></td>
                                            <td class="empty"></td>
                                            <td><a  title="Карта сайта"  href="'.tpl::_ax(tpl::_export('','index'),array('tpl_second','__index')).'/sitemap" class="sitemap"><img src="'.tpl::_ax(tpl::_export('','index'),array('tpl_second','__index')).'/img/empty_topmenu.gif"></a></td>
                                            <td class="empty"></td><td><a title="Обратная связь" href="'.tpl::_ax(tpl::_export('','index'),array('tpl_second','__index')).'/writeus" class="mail"><img src="'.tpl::_ax(tpl::_export('','index'),array('tpl_second','__index')).'/img/empty_topmenu.gif"></a></td>
                                            <td>'.tpl::_ax(tpl::_export('','_tpl','tpl_jusers','_toplogin'),array('tpl_second','___tpl_tpl_jusers__toplogin')).'</td>
                                       </tr>
                                    </table>
                                    
                                    <form action="?do=search" method="post" style="margin: 0px; padding: 0px;">
			                            <div id="searchbar" style="overflow: hidden; position: absolute; top: 55px; left: 5px; display: none;">							
            			                	<div style="padding: 4px 0px 0px 4px;">
                        			 			<table><tr>
                                    				<td style="padding: 0px;"><input value="Слово для поиска" name="search_string" style="font-size: 11px; line-height: 17px; width: 134px; height: 17px; border: 1px solid rgb(191, 191, 191);" type="text"></td>
													<td style="padding-left: 4px; vertical-align: middle;"><input name="search" src="'.tpl::_ax(tpl::_export('','index'),array('tpl_second','__index')).'/img/search1.gif" style="width: 8px; height: 15px; border: medium none; vertical-align: top; margin-left: 6px;" type="image" align="middle"></td>
				                         		</tr></table>
			                                </div>
										</div>
									</form>
                  
                            </div>
                            <!-- Block for advisory services on-line-->
                            <div style="position:absolute; z-index:12; width:21%; min-width:268px; margin-left:-134px; height:84px; top:104px; left:81%; background:url('.tpl::_ax(tpl::_export('','index'),array('tpl_second','__index')).'/img/phone.gif) no-repeat left 15px"><div style="top:0px; float:right; width:93px; height:87px; background:url('.tpl::_ax(tpl::_export('','index'),array('tpl_second','__index')).'/img/on-line.gif) center top no-repeat;"><a href="https://siteheart.com/webconsultation/94867?byhref=1&s=1" target="siteheart_sitewindow_94867" onclick="o=window.open;o(\'https://siteheart.com/webconsultation/94867?s=1\', \'siteheart_sitewindow_94867\', \'width=600,height=450,top=30,left=30,resizable=yes\'); return false;" class="on-line">консультации</a></div></div>
                            <!-- Language menu block -->
                            <div style="position:absolute; z-index:13; width:75px; height:23px; top:22px; left:83%;"><div style="float:left; width:37px; height:23px;"><a href="'.tpl::_ax(tpl::_export('','index'),array('tpl_second','__index')).'/"><img src="'.tpl::_ax(tpl::_export('','index'),array('tpl_second','__index')).'/img/rus.gif"></a></div><div style="float:left; width:37px; height:23px;"><a href="'.tpl::_ax(tpl::_export('','index'),array('tpl_second','__index')).'/"><img src="'.tpl::_ax(tpl::_export('','index'),array('tpl_second','__index')).'/img/eng.gif"></a></div></div>

                        </div>


            		</TD>
           </TR>
   	       <TR height="44px">

<!-- Second row -->

					<TD style="border-bottom:1px solid #dee1e3; background-color:#FFFFFF; min-width:950px">
   
   
		 <!-- Main menu  -->               
						<div style="left:13%; margin-left:-110px; width:860px; height:44px; float:left; position:relative">
							'.tpl::_ax(tpl::_export('','menu_1'),array('tpl_second','__menu_1')).'
                        </div>
                        <div style="float:right; position:relative; padding-top:11px; right:3%;"><a href="'.tpl::_ax(tpl::_export('','index'),array('tpl_second','__index')).'/basket"><img src="'.tpl::_ax(tpl::_export('','index'),array('tpl_second','__index')).'/img/basket2.gif"></a></div>
         
            		</TD>
            </TR>
            <TR height="545px">

<!-- Third row  -->
	<!-- First column -->
            		<TD>
                    	<table width="100%" height="545px">
                        	<tr>
                            	<td width="5%" style="padding-left:30px;  background:url('.tpl::_ax(tpl::_export('','index'),array('tpl_second','__index')).'/img/nfirst.gif) no-repeat 0px 51px;">&nbsp;</td>
                            	<td width="70%" style="min-width:610px;">
									
                                    <div style="padding: 35px 0px 50px 0px">
	                                    '.tpl::_ax(tpl::_export('','_first'),array('tpl_second','___first')).'  
            							'.tpl::_ax(tpl::_export('','zagl'),array('tpl_second','__zagl')).'
                                        <div style="float:none; font-size:12px; line-height:17px; padding-top:10px">'.(isset($par['data'])?$par['data']:'').'</div>
                                    </div>
        
                               </td>

                                <td width="25%" align="center" style="padding-right:40px;padding-left:40px;">

	<!-- Second column -->
								'.tpl::_ax(tpl::_export('','menu_2'),array('tpl_second','__menu_2')).'

 
                     
                                
                                </td>
                            </tr>                        
                        </table>            
            		</TD>
           </TR> 
               
           <TR height="133px">



            		<TD style="background: url('.tpl::_ax(tpl::_export('','index'),array('tpl_second','__index')).'/img/bg_bottom.gif) no-repeat top left #eff1f4">
                    
 								<table height="110px" width="100%"><tr><td width="12%">&nbsp;</td><td style=" font-size:11px; color:#748699; padding-top:36px; padding-left:34px; white-space:nowrap; line-height:17px">
                                <a class="link_bottom" href="mailto:sales@ezavodspb.ru">sales@ezavodspb.ru</a>&nbsp;&nbsp;/&nbsp;&nbsp;<a class="link_bottom" href="mailto:info@ezavodspb.ru">info@ezavodspb.ru</a><br>
								© ЗАО "Экспериментальный завод", 2008
								</td>
                                <td width="68%">&nbsp;</td>
                                <td  style="padding-top:36px; white-space:nowrap; line-height:22px">
                                <a href="http://www.xilen.spb.ru"><img src="'.tpl::_ax(tpl::_export('','index'),array('tpl_second','__index')).'/img/xilen.gif"></a><br>
                                <a class="link_xilen" href="http://www.xilen.spb.ru">Создание сайтов</a>
                                </td><td width="20%">&nbsp;</td>
                                
                                </tr></table>
            
            		</TD>

           </TR>  
                     
           
	</TABLE>



</BODY>
</HTML>';
}}
?>