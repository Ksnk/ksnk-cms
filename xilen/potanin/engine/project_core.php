<?php

setlocale(LC_ALL ,'ru_RU.CP1251');

define ('USER_TYPE',false);

define('SECOND_TPL','tpl_second');

//define ('QA_WITH_THEME',true);

define('SITE_CREATE_SCENARIO',5);

define('ANCHOR_STORE',false); // ��������� ANCHORS, �� �� ���������� �� � ����

//define('ARTICLE_WIDTH_IMAGE',true);

$GLOBALS['opt_array']=array(type_NEWTEXTPIC,type_LINKS,type_TABLE,type_ANCHOR,type_GALLERY,type_GALLERY2,type_LINE);
// 

itemByType(1001,'yGallery');
nameByType(1001,'�������-1');
array_push($GLOBALS['opt_array'],1001);

array_push($GLOBALS['opt_array'],type_GALLERY);

function get_parameters(&$par){
	$par['list'][]=array('sub'=>'�������������','title'=>'����� ��������������','name'=>'login_admin');
	$par['list'][]=array('title'=>'����� ������ ��������������','name'=>'login_newpassword');
	$par['list'][]=array('title'=>'������ ������ ��������������','name'=>'login_oldadmin');
	$par['list'][]=array('sub'=>'������ ���','title'=>'����� ��� ���������','name'=>'mail_admin');
//	$par['list'][]=array('sub'=>'�������������� ����� ��� ������','title'=>'e-mail','name'=>'mail_admin2');
//	$par['list'][]=array('sub'=>'����� ����������� ���� ��� �����','title'=>'�����������','name'=>'theme1', 'type1'=>'radio', 'value'=>'1' );
//	/$par['list'][]=array('title'=>'����������','name'=>'theme1', 'type1'=>'radio', 'value'=>'2' );
//	$par['list'][]=array('title'=>'8 �����','name'=>'theme1', 'type1'=>'radio', 'value'=>'3' );
//	$par['list'][]=array('title'=>'23 �������','name'=>'theme1', 'type1'=>'radio', 'value'=>'4' );


//	$par['list'][]=array('sub'=>'�������','title'=>'���������� ������� �� ��������','name'=>'catalogue-perpage');
	
}

/*<%=insert_point('plugin_body'); %>*/

//
?>