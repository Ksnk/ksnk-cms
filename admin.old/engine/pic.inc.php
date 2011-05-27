<?php
/*
 *   набор функций для работы с изображениями
 */

  function wrimg($fname,$MAXX,$MAXY,$im_q=70,$dstfile="")
  {
/*
 *   изготовить thumbnail без рамки и вписать его в [MAXX x MAXY]
 */
    return img_resize($fname,$dstfile,$MAXX,$MAXY,-1,$im_q);
  }
//Функция img_resize(): генерация thumbnails
//Параметры:
// $src - имя исходного файла
// $dest - имя генерируемого файла
// $width, $height - ширина и высота генерируемого изображения, в пикселях
//Необязательные параметры:
// $rgb - цвет фона, по умолчанию - белый
// $quality - качество генерируемого JPEG, по умолчанию - максимальное (100)
//***********************************************************************************/
function img_resize($src, $dest, $width, $height, $rgb=0xFFFFFF, $quality=100)
{
	if (!file_exists($src)) return false;

	if(!($size = getimagesize($src))) return false;

	// Определяем исходный формат по MIME-информации, предоставленной
	// функцией getimagesize, и выбираем соответствующую формату
	// imagecreatefrom-функцию.
	$format = strtolower(substr($size['mime'], strpos($size['mime'], '/')+1));
	$icfunc = "imagecreatefrom" . $format;
	if (!function_exists($icfunc)) return false;

	$x_ratio = $width / $size[0];
	$y_ratio = $height / $size[1];

	$ratio = min($x_ratio, $y_ratio);
	$use_x_ratio = ($x_ratio == $ratio);

	$new_width = $use_x_ratio ? $width : floor($size[0] * $ratio);
	$new_height = !$use_x_ratio ? $height : floor($size[1] * $ratio);
	$isrc = $icfunc($src);
	if ($rgb==-1) {
		$idest = imagecreatetruecolor($new_width, $new_height);
		$new_left = 0;
		$new_top = 0;
	} else {
		$idest = imagecreatetruecolor($width, $height);
		$new_left = $use_x_ratio ? 0 : floor(($width - $new_width) / 2);
		$new_top = !$use_x_ratio ? 0 : floor(($height - $new_height) / 2);
		imagefill($idest, 0, 0, $rgb);
	}
	imagecopyresampled($idest, $isrc, $new_left, $new_top, 0, 0,
	$new_width, $new_height, $size[0], $size[1]);

	imagejpeg($idest, $dest, $quality);

	imagedestroy($isrc);
	imagedestroy($idest);

	return true;
}

if (function_exists('iconv')) {
	function win2uni($s) { return iconv('', 'UTF-8', $s); }
} else {
	function win2uni ($winline){
	    for ($i=0; $i < strlen($winline); $i++){
	        $thischar=substr($winline,$i,1);
	        $charcode=ord($thischar);
	        $uniline.=($charcode>175) ? "&#" . (1024+($charcode-176)). ";" : $thischar;
	    }
	    return $uniline;
	}
}

function write_text($img,$x,$xs=0,$ys=0,$align='cc',$size=20,$col=0xFF,$font='arial.ttf')
// possible aling lcr-left/center/right bct-bot/center/top
{
  $bounds=imagettfbbox ( $size,0,$font,win2uni($x));
  $bounds['width']=abs($bounds[4]-$bounds[0]);
  $bounds['heigth']=abs($bounds[5]-$bounds[1]);
  $bounds['x']=min($bounds[4],$bounds[0]);
  $bounds['y']=min($bounds[5],$bounds[1]);
//  print_r($bounds);
  if ($img) {
	switch ($align{0}) {
		 case 'c': $xs=(imagesx($img)-$bounds['width'])/2;break;
		 case 'r': $xs=(imagesx($img)-$bounds['width'])-$xs;break;
	}
	switch ($align{1}) {
		 case 'c': $ys=(imagesy($img)-$bounds['heigth'])/2;break;
		 case 'b': $ys=(imagesy($img)-$bounds['heigth'])-$ys;break;
	}
    imagettftext($img, $size,0,$xs-$bounds['x'],$ys-$bounds['y'],
                            $col, $font, win2uni($x));
  }
  else
    return $bounds;
}

function imagegetcolor($im, $r, $g, $b) {
$c=imagecolorexact($im, $r, $g, $b);
if ($c!=-1) return $c;
$c=imagecolorallocate($im, $r, $g, $b);
if ($c!=-1) return $c;
return imagecolorclosest($im, $r, $g, $b);
} # EBD imagegetcolor()


function fill(&$img,$startcol,$fincol) // 0 - черный 0xff0000 - красный
// gradient fill
{
	foreach(array($startcol,$fincol) as $k=>$v) {
		$c[$k]['r'] = ($v >> 16) & 0xFF;
		$c[$k]['g'] = ($v >> 8) & 0xFF;
		$c[$k]['b'] = $v & 0xFF;
	}
	$c['d']['r'] = ($c[1]['r']-$c[0]['r']);
	$c['d']['g'] = ($c[1]['g']-$c[0]['g']);
	$c['d']['b'] = ($c[1]['b']-$c[0]['b']);

	for($x=0;$x<imagesx($img);$x++){
		$xc=sin((10+$x)*0.05);
		$x1=sin($x*0.05);
		$col=imagegetcolor($img,
			    $c[1]['r']-($c['d']['r']*$xc),
			    $c[1]['g']-($c['d']['g']*$xc),
			    $c[1]['b']-($c['d']['b']*$xc)) ;
		$col1=imagegetcolor($img,
			    $c[0]['r']+($c['d']['r']*$x1),
			    $c[0]['g']+($c['d']['g']*$x1),
			    $c[0]['b']+($c['d']['b']*$x1)) ;
		//if($x>(imagesx($img)/2)) {
		//	$c = $col1; $col1 = $col ; $col = $c ;
		//}
	    for ($y=0;$y<imagesy($img);$y++) {
			if (imagecolorat($img,$x,$y) & 0xff) { // синеет!
				imagesetpixel($img,$x,$y,$col1);
			} else {
				imagesetpixel($img,$x,$y,$col);
			}
		}
	}
}

?>