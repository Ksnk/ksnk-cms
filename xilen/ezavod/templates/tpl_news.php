<?php
class tpl_news extends tpl {

function _(&$par){		
		return '<!--  ////////////////// плагин Новости /////////////////////// -->
'.tpl::_a($par['news_b'],array('tpl_news','news_b')).'

'.tpl::_a($par['news_x'],array('tpl_news','news_x')).'



'.tpl::_a($par['newslist'],array('tpl_news','newslist'));
}

function news_b(&$par){		
		return '<div style="float:none; clear:both; width:100%; background: url('.tpl::_ax(tpl::_export('','index'),array('tpl_news','news_b___index')).'/img/bg_news.gif) repeat-x top #eff1f4; margin-top:20px;">

				<div style="float:none; clear:both; width:600px; right:0px; background:url('.tpl::_ax(tpl::_export('','index'),array('tpl_news','news_b___index')).'/img/logo_news.gif) no-repeat 15% 0%; padding: 43px 30px 0px 0px; margin-left:auto">


<div id="examples" align="right">
<div id="slider">
						<ul>
'.tpl::_a($par['news2'],array('tpl_news','news_b_news2')).'
  						</ul>
</div>
</div>

                </div>
			</div>
            

            <div style="float:right; clear:both; width:600px; right:0px; height:40px; text-align:left; background:url('.tpl::_ax(tpl::_export('','index'),array('tpl_news','news_b___index')).'/img/news_arch.gif) top left no-repeat;">
            <div style="margin-left:130px; padding-top:11px; color:#5586ba; ">


'.tpl::_ax(tpl::_export('news','years'),array('tpl_news','news_b_news__years')).'
			
            </div>
            </div>';
}

function news_b_news2(&$par){		
		return '<li>                    
                            	<table style="background:url('.tpl::_ax(tpl::_export('','index'),array('tpl_news','news_b_news2___index')).'/img/line_news.gif) no-repeat center 5px">
 					               <tr><td style="padding: 4px 17px 0px 0px;">
                                   		<div class="news_date1">'.tpl::_Day(pps($par['date'])).'</div>
                                   		<div class="news_year1">'.tpl::_M(pps($par['date'])).'/'.tpl::_Y(pps($par['date'])).'</div>
                                   </td><td width="50%" align="left" style="padding: 0px 10px 25px 0px">
                                    	<a href="?do=newslist&id='.(isset($par['id'])?$par['id']:'').'" class="news_link1">'.(isset($par['text_b'])?$par['text_b']:'').'</a>
                                   </td><td style="padding: 4px 17px 0px 0px;">
                                   		<div class="news_date1">'.tpl::_Day(pps($par['date2'])).'</div>
                                   		<div class="news_year1">'.tpl::_M(pps($par['date2'])).'/'.tpl::_Y(pps($par['date2'])).'</div>
                                   </td><td width="50%" align="left" style="padding: 0px 10px 25px 0px">
                                    	<a href="?do=newslist&id='.(isset($par['id2'])?$par['id2']:'').'" class="news_link1">'.(isset($par['text_b2'])?$par['text_b2']:'').'</a>
                                   </td></tr>
                  				</table>  

                    		</li>';
}

function news_b_news__years(&$par){		
		return '<a class="news_year2" href="'.tpl::_ax(tpl::_export('','curl','do','id'),array('tpl_news','news_b_news__years___curl_do_id')).'do=newslist&year='.(isset($par['year'])?$par['year']:'').'">'.(isset($par['year'])?$par['year']:'').'</a></span> '.tpl::_d($par['last'],'/&nbsp;');
}

function news_x(&$par){		
		return '<div style="padding:0 0 0px 60px;">
'.tpl::_a($par['news'],array('tpl_news','news_x_news')).'
<div class="size11 menu" style="clear:both;color:#bb580e;padding:0px 0 10px 5px;">
Архив новостей</div>
<div class="link size12" style="color:#fdee9a;padding:7px 0 10px 5px;">
'.tpl::_ax(tpl::_export('news','years'),array('tpl_news','news_x_news__years')).'</div>

</div>';
}

function news_x_news(&$par){		
		return '<div class="ctext link tahoma" style="float:left;width:245px;">


<div style="background:url(/img/newsdate.gif) no-repeat;padding:12px 10px 15px 22px;" class="size16 "><b>'.tpl::_Day(pps($par['date'])).'/'.tpl::_M(pps($par['date'])).'</b>
<span class="size11" style="padding-left:40px">'.tpl::_Y(pps($par['date'])).'</span>
</div>
<div style="padding-left:5px; padding-right:30px;"><br><a href="?do=newslist&id='.(isset($par['id'])?$par['id']:'').'" class="news_header">'.(isset($par['title'])?$par['title']:'').'</a></div>
<div style="padding:20px 30px 30px 5px;">
<p style="color:#ffe6cc" class="size12">'.(isset($par['text_b'])?$par['text_b']:'').'</p>
</div>
</div>';
}

function news_x_news__years(&$par){		
		return '<span class="link" style=";margin-top:7px;">
<a href="'.tpl::_ax(tpl::_export('','curl','do','id'),array('tpl_news','news_x_news__years___curl_do_id')).'do=newslist&year='.(isset($par['year'])?$par['year']:'').'">'.(isset($par['year'])?$par['year']:'').'</a></span> '.tpl::_d($par['last'],'/');
}

function newslist(&$par){		
		return '<div class="tahoma ctext">
'.tpl::_a($par['news'],array('tpl_news','newslist_news')).'

<div style="padding-top:30px;clear:both;">
'.(isset($par['pages'])?$par['pages']:'').'
</div>
</div>';
}

function newslist_news(&$par){		
		return '<table style="background:url('.tpl::_ax(tpl::_export('','index'),array('tpl_news','newslist_news___index')).'/img/line_news.gif) no-repeat center 5px; margin-bottom: 50px">
 					               <tr><td style="padding: 4px 27px 0px 0px;">
                                   		<div class="news_date1">'.tpl::_Day(pps($par['date'])).'</div>
                                   		<div class="news_year1">'.tpl::_M(pps($par['date'])).'/'.tpl::_Y(pps($par['date'])).'</div>
                                   </td><td align="left" style="padding: 0px 0px 25px 0px">
                                    	


'.tpl::_a($par['img'],array('tpl_news','newslist_news_img')).'
								   </td><td width="100%" style="padding: 4px 0px 0px 10px;">
 <div style="padding: 0px 0px 25px 0px; float:none; font-size:15px; font-weight:600">'.(isset($par['title'])?$par['title']:'').'</div>
 
 <div style="">'.(isset($par['text'])?$par['text']:'').'</div>
                                   </td></tr>

</td></tr></table>';
}

function newslist_news_img(&$par){		
		return '<div class="float_left box">
		'.(isset($par['pict'])?$par['pict']:'').'
</div>';
}}
?>