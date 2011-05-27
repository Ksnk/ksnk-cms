<?php
class tpl_massmail extends tpl {

function _(&$par){		
		return tpl::_a($par['mail'],array('tpl_massmail','mail'));
}

function mail(&$par){		
		return (isset($par['text'])?$par['text']:'').'
<hr>
Вы подписаны на спиcок рассылки с сайта "'.(isset($par['site'])?$par['site']:'').'".<br> 
Чтобы отказаться от рассылки, прейдите, пожалуйста по 
<a href="http://'.(isset($par['site'])?$par['site']:'').'/?do=unsub&plugin=massmail&id='.(isset($par['id'])?$par['id']:'').'&code='.(isset($par['code'])?$par['code']:'').'">этой ссылке</a>';
}}
?>