<?php
class tpl_forum extends tpl {

function _(&$par){		
		return '<!--  ////////////////// плагин Форум /////////////////////// -->
'.tpl::_a($par['anons'],array('tpl_forum','anons')).'

'.tpl::_a($par['forum_topics'],array('tpl_forum','forum_topics')).'

'.tpl::_a($par['forum_topics_p'],array('tpl_forum','forum_topics_p')).'

'.tpl::_a($par['forum_posts'],array('tpl_forum','forum_posts')).'

'.tpl::_a($par['forum_newtopic'],array('tpl_forum','forum_newtopic')).'

'.tpl::_a($par['forumthanks'],array('tpl_forum','forumthanks')).'
'.tpl::_a($par['mail'],array('tpl_forum','mail')).'

'.tpl::_a($par['forumform'],array('tpl_forum','forumform')).'

'.tpl::_a($par['forum_list'],array('tpl_forum','forum_list'));
}

function anons(&$par){		
		return '<div id="forum_anons">
<table>
'.tpl::_a($par['line'],array('tpl_forum','anons_line')).'
</table>
<p><a href="forum">Показать все темы...</a></p>
</div>';
}

function anons_line(&$par){		
		return '<tr>
		<th class="fa_l">'.(isset($par['maxdate'])?$par['maxdate']:'').'</td>
		<th class="fa_p"></th>
	</tr>
	<tr>
		<td class="fa_l"><a href="forum/'.(isset($par['id'])?$par['id']:'').'">'.(isset($par['topic'])?$par['topic']:'').'</a></td>
		<td class="fa_p">'.(isset($par['cnt'])?$par['cnt']:'').' ответов</td>
	</tr>';
}

function forum_topics(&$par){		
		return '<div class="tahoma size12">
<table style="width:70%;margin-top:31px;">
'.tpl::_a($par['list'],array('tpl_forum','forum_topics_list')).'
</table>
</div>';
}

function forum_topics_list(&$par){		
		return '<TBODY>
<tr>
<td style="border-bottom:solid 1px #d7dee9;font-size:11px;color:#9eafc3;">'.(isset($par['date'])?$par['date']:'').' '.(isset($par['year'])?$par['year']:'').'</td><td style="border-bottom:solid 1px #eb8017;width:80px;"></td>
</tr>
<tr>
<td style="padding-top:5px;padding-bottom:16px;"><a href="javascript:show_sub('.(isset($par['id'])?$par['id']:'').')">'.(isset($par['topic'])?$par['topic']:'').'</a></td><td id="cnt_otv_'.(isset($par['id'])?$par['id']:'').'" style="font-size:11px;color:#eb8017;"><span>'.(isset($par['cnt'])?$par['cnt']:'').' ответов</span></td>
</tr>
</TBODY>
<TBODY id="subtopic_'.(isset($par['id'])?$par['id']:'').'" style="display:none;">
<tr>
<td colspan="2" style="height:17px;"></td>
</tr>
'.(isset($par['topic_p'])?$par['topic_p']:'').'
<tr>
<td style="padding-left:40px;padding-bottom:52px;font-size:14px;"><a href="'.(isset($par['href_new'])?$par['href_new']:'').'" style="color:#000000;">Создать тему</a></td><td></td>
</tr>
</TBODY>';
}

function forum_topics_p(&$par){		
		return tpl::_a($par['list_p'],array('tpl_forum','forum_topics_p_list_p'));
}

function forum_topics_p_list_p(&$par){		
		return '<tr>
<td style="padding-left:40px;padding-bottom:8px;"><a href="'.(isset($par['href'])?$par['href']:'').'">'.(isset($par['topic'])?$par['topic']:'').'</a></td><td style="font-size:11px;color:#eb8017;">'.(isset($par['cnt'])?$par['cnt']:'').' ответов</td>
</tr>';
}

function forum_posts(&$par){		
		return '<div class="tahoma size11" style="padding-top:49px;">
'.(isset($par['pages'])?$par['pages']:'').'
<table style="width:75%;margin-top:30px;">
'.tpl::_a($par['list'],array('tpl_forum','forum_posts_list')).'
</table>
'.(isset($par['pages'])?$par['pages']:'').'
<table style="width:75%;margin-top:42px;">
<tr>
<td>
<table class="tahoma">
<tr><td style="background-color:#09304d;color:#FFFFFF;height:28px;width:215px;text-align:center;vertical-align:middle;font-weight:bold;font-family:Arial;font-size:11px;">ОСТАВИТЬ КОММЕНТАРИЙ</tr></td>
<tr><td style="height:10px;background:url(img/ugol_t.gif) left top no-repeat;"></tr></td>
</table>
</td>
<td style="font-size:12px;"><a href="'.(isset($par['all_topics'])?$par['all_topics']:'').'">Показать все темы</a></td>
<td></td>
</tr>
</table>

<a name="komment"></a>
<div class="quote_div"></div>
<form method="post">
<input type="hidden" name="action" value="newpost"/>
<textarea name="newpost" style="margin:10px 0px 10px 25px;height:145px;width:425px;border:solid 1px #d7dee9;">
</textarea><br/>
<input type="hidden"  name="quote_id" value=""/>
<input type="hidden" name="quote" value=""/>
<input type="submit" value="Отправить" style="background:url(img/button2.gif) no-repeat center right #FFFFFF;border:none;width:100px;color:#225f9a;margin-left:20px;"/>
</form>
</div>';
}

function forum_posts_list(&$par){		
		return '<tr style="color:#9eafc3;">
<td style="border-bottom:solid 1px #d7dee9;padding-bottom:2px;">'.(isset($par['date'])?$par['date']:'').' '.(isset($par['year'])?$par['year']:'').'</td><td style="border-bottom:solid 1px #d7dee9;width:110px;">
<a href="#" onClick="return komment(this,'.(isset($par['id'])?$par['id']:'').');">Комментировать</a></td><td style="border-bottom:solid 1px #eb8017;width:100px;">сообщение #'.(isset($par['id'])?$par['id']:'').'</td>
</tr>
'.tpl::_a($par['quote_tr'],array('tpl_forum','forum_posts_list_quote_tr')).'
<tr>
<td class="question" colspan="2" style="padding: 20px 0px 12px 25px;">'.(isset($par['question'])?$par['question']:'').'</td><td style="'.(isset($par['style_str'])?$par['style_str']:'').'"></td>
</tr>
<tr>
<td colspan="2" style="padding: 0px 0px 33px 25px;color:#225f9a;">
<span onmouseover="showInfo(this)">'.(isset($par['sval'])?$par['sval']:'').'
<div style="display:none;">'.(isset($par['info'])?$par['info']:'').'</div>
</span></td><td></td>
</tr>';
}

function forum_posts_list_quote_tr(&$par){		
		return '<tr>
<td style="height:16px;"></td><td></td><td></td>
</tr>
<tr>
<td colspan="2" style="padding: 10px 0px 12px 25px;background-color:#d7dee9;">'.(isset($par['quote'])?$par['quote']:'').'</td><td style="background:url(img/strelka.gif) no-repeat center 8px"></td>
</tr>';
}

function forum_newtopic(&$par){		
		return '<div class="para tahoma">
<div style="margin:33px 0 8px 0;">
<form method="post">
<input type="hidden" name="action" value="newtopic"/>
<input type="hidden" name="parent" value="'.(isset($par['parent'])?$par['parent']:'').'"/>
<p style="margin-left:25px;">Тема</p>
<input type="text" name="newtopic" value="" style="margin:10px 0px 10px 25px;width:425px;border:solid 1px #d7dee9;"/><br/>
<p style="margin-left:25px;">Комментарий</p>
<textarea name="newpost" style="margin:10px 0px 10px 25px;height:145px;width:425px;border:solid 1px #d7dee9;">
</textarea><br/>
<input type="submit" value="Отправить" style="background:url(img/button2.gif) no-repeat center right #FFFFFF;border:none;width:100px;color:#225f9a;margin-left:20px;"/>
</form>
</div>
</div>';
}

function forumthanks(&$par){		
		return '<div style="padding-top:60px;" class="tahoma ctext link">
<p>Спасибо Вам за проявленный интерес. Через некоторое время администрация сайта
обязательно рассмотрит и ответит на Ваш вопрос.
</p><p>Нажмите на <a href="'.(isset($par['url'])?$par['url']:'').'">ссылку</a> для возврата.
</p>
<p class="red size11">'.(isset($par['result'])?$par['result']:'').'</p>
</div>';
}

function mail(&$par){		
		return 'Сообщение: '.(isset($par['question'])?$par['question']:'').'<br>
Дата: '.(isset($par['date'])?$par['date']:'').'<br>
Подпись: '.(isset($par['user'])?$par['user']:'').'<br>
Адрес: '.(isset($par['address'])?$par['address']:'');
}

function forumform(&$par){		
		return '<form action="" method="POST" name="forumform">
<style>
.table td.pad10 {
 padding-top:10px;
 padding-bottom:10px;
}
</style>
<div class="align_center" style="padding-top:60px;" >
<table class="table tahoma ctext" style="width:80%;height:1px;"><col align="right">
<tr>
<td colspan=2>
 <p class="red" style="font-size:16px;">'.(isset($par['error'])?$par['error']:'').'</p></td>
</tr><tr class="even" style="border: 1px solid #dddddd; border-width:1px 0 1px 0;">
 <td class="pad10 text">Ваше имя:</td><td  class="pad10 text"><input class="input" style="width:310px;" type="text" name="name"></td>
</tr><tr class="odd">
<td class="pad10 text">Ваш адрес:</td><td class="pad10 text"><input  class="input" style="width:310px;" type="text" name="address"></td>
</tr><tr class="even" style="border: 1px solid #dddddd; border-width:1px 0;">
<td class="pad10 text">Ваш вопрос:</td><td class="pad10 text"><textarea autocomplete="off" class="input" style="width:310px;height:200px;" name="text"></textarea></td>
</tr>
<tr class="odd">
<td class="pad10 text" >Введите номер, изображенный на картинке:<br></td><td class="text"><img src="captcha.php" style="float:right;" alt="" />
<input  class="input" type="pad10 text" name="captcha" /></td>
</tr>
<tr  class="even" style="border: 1px solid #dddddd; border-width:1px 0;">
<td class="text pad10" ></td><td class="pad10 text align_left" ><div style="width:100px;" class="button"><input type="submit" value="Отправить"></div></td>
</tr>
</table>
</div>
</form>';
}

function forum_list(&$par){		
		return '<div class="para">
<div style="margin:33px 0 8px 0;">
'.(isset($par['pages'])?$par['pages']:'').'
'.tpl::_a($par['list'],array('tpl_forum','forum_list_list')).'
'.(isset($par['pages'])?$par['pages']:'').'
</div>

<b class="tahoma link ctext">
<a class="blue" href="'.(isset($par[':curl:do:id'])?$par[':curl:do:id']:'').'do=menu&id=writeus" onclick="return do_load_forum(this);"
>Задать свой вопрос&raquo;</a></b>
<div id="forum_container" ></div>
</div>';
}

function forum_list_list(&$par){		
		return '<table class="long link tahoma ctext" style="border-bottom:1px solid #dddddd;margin:7px 0 7px 0;">
<tr><td class="size12 blue hand"
onclick="$(this).parent().next().next().toggle().next().toggle();"
><p><u>'.(isset($par['question'])?$par['question']:'').'</u></p></td></tr>
<tr><td style="padding:5px 0;" class="size11">'.(isset($par['date'])?$par['date']:'').' '.(isset($par['year'])?$par['year']:'').' '.(isset($par['time'])?$par['time']:'').', '.(isset($par['user'])?$par['user']:'').' </td></tr>
<tr><td  style="padding-bottom:10px;" class="hand align_right size11 blue link"
onclick="$(this).parent().toggle().next().toggle();"
>
<u class="blue size12">
посмотреть ответ</u>
&nbsp;&nbsp;<img style="border:0;" src="img/arr_red_dn.gif"></td></tr>
<tr style="display:none;"><td style="padding:10px 0 10px 40px;">
'.(isset($par['answer'])?$par['answer']:'').'
</td></tr>
</table>';
}}
?>