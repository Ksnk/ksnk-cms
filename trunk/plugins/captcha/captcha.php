<?php

/* 

w3captcha - php-скрипт для генерации изображений CAPTCHA
версия: 1.1 от 08.02.2008
разработчики: http://w3box.ru
тип лицензии: freeware
w3box.ru © 2008

*/
error_reporting(E_ALL);
ini_set('display_errors',false);
ini_set('log_errors',true);
//include_once('project.php');
if(!defined('ADMIN'))
	define ('ADMIN','admin');
//*
if(include_once(ADMIN.'/engine/db_session.php')){
include_once(ADMIN.'/engine/Generic.php');
include_once('engine/hosts.php');
$connect="mysql://$the_login:$the_pass@$the_host/$the_db";
$db_setup='set NAMES "cp1251";';
function ppi(&$x,$def=0) { return (!empty($x))?intval($x):$def;}
function pps(&$x,$def='') { return (!empty($x))?$x:$def;}

function DATABASE(){
	global $DATABASE,$connect,$db_setup;
	if(isset($DATABASE)) return $DATABASE;
	$DATABASE = DbSimple_Generic::connect($connect);
	$DATABASE->setIdentPrefix(TAB_PREF.'_');
	@$DATABASE->select($db_setup);// High  magic!!!!!!!!!!!!!!!!!
	return $DATABASE;
}

	if(class_exists('SessionManager'))
		SessionManager::instance();
}
//*/
$count=5;	/* количество символов */
$width=100; /* ширина картинки */
$height=48; /* высота картинки */
$font_size_min=32; /* минимальная высота символа */
$font_size_max=32; /* максимальная высота символа */
$font_file="Comic_Sans_MS.ttf"; /* путь к файлу относительно w3captcha.php */
$char_angle_min=-10; /* максимальный наклон символа влево */
$char_angle_max=10;	/* максимальный наклон символа вправо */
$char_angle_shadow=5;	/* размер тени */
$char_align=40;	/* выравнивание символа по-вертикали */
$start=5;	/* позиция первого символа по-горизонтали */
$interval=16;	/* интервал между началами символов */
$chars="0123456789"; /* набор символов */
$noise=10; /* уровень шума */

//echo ('10');
$image=imagecreatetruecolor($width, $height);
//echo (1);

$background_color=imagecolorallocate($image, 255, 255, 255); /* rbg-цвет фона */
$font_color=imagecolorallocate($image, 32, 64, 96); /* rbg-цвет тени */

imagefill($image, 0, 0, $background_color);

$str="";

$num_chars=strlen($chars);
for ($i=0; $i<$count; $i++)
{
	$char=$chars[rand(0, $num_chars-1)];
	$font_size=rand($font_size_min, $font_size_max);
	$char_angle=rand($char_angle_min, $char_angle_max);
	imagettftext($image, $font_size, $char_angle, $start, $char_align, $font_color, $font_file, $char);
	imagettftext($image, $font_size, $char_angle+$char_angle_shadow*(rand(0, 1)*2-1), $start, $char_align, $background_color, $font_file, $char);
	$start+=$interval;
	$str.=$char;
}
//echo (2);

if ($noise)
{
	for ($i=0; $i<$width; $i++)
	{
		for ($j=0; $j<$height; $j++)
		{
			$rgb=imagecolorat($image, $i, $j);
			$r=($rgb>>16) & 0xFF;
			$g=($rgb>>8) & 0xFF;
			$b=$rgb & 0xFF;
			$k=rand(-$noise, $noise);
			$rn=$r+255*$k/100;
			$gn=$g+255*$k/100;		
			$bn=$b+255*$k/100;
			if ($rn<0) $rn=0;
			if ($gn<0) $gn=0;
			if ($bn<0) $bn=0;
			if ($rn>255) $rn=255;
			if ($gn>255) $gn=255;
			if ($bn>255) $bn=255;
			$color=imagecolorallocate($image, $rn, $gn, $bn);
			imagesetpixel($image, $i, $j , $color);					
		}
	}
}

session_start();
$_SESSION["captcha"]=$str;
session_write_close();

if (function_exists("imagepng"))
{
	header("Content-type: image/png");
	imagepng($image);
//  echo 'xxx';
}
elseif (function_exists("imagegif"))
{
	header("Content-type: image/gif");
	imagegif($image);
}
elseif (function_exists("imagejpeg"))
{
	header("Content-type: image/jpeg");
	imagejpeg($image);
}

imagedestroy($image);

?>
