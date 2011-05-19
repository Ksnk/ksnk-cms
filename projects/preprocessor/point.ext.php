<?php
/**
 * POINTS (MACRO OOP) mechanism for PHP preperocessor
 * <%=point('hat','jscomment');%>
 */
$points=array();
$cur_point='';
$ob_count=0;
function point_start($point_name){
	global $ob_count,$cur_point,$points;
	//echo "ob_start\n\r";
	point_finish();
	$cur_point=$point_name;
	ob_start();
	$ob_count++;
}

function point_finish(){
	global $ob_count,$cur_point,$points;
	//echo "ob_finish\n\r";
	if($ob_count==0) return;
	$ob_count--;
	//$contents=preg_replace('/^\s+|\s+$/','',ob_get_contents()); 
	$contents=ob_get_contents();
	ob_end_clean();
	//echo $contents;
	if (empty($contents)) return;
	if(!isset($points[$cur_point]))
	  $points[$cur_point]=array();
	$points[$cur_point][]=preg_replace('/^\s+|\s+$/','',$contents);  
}

function point($point_name, $filter=''){
	global $ob_count,$cur_point,$points;
	//echo "insert_point $point_name */\n\r";
	$s='';
	if(isset($points[$point_name]))
	  $s= join($points[$point_name],"\r\n");
	switch($filter){
		case 'wiki-txt':
			include_once("wiki.ext.php");
			return wiki_parcer::convert($s,'txt');
			break;
		case 'wiki-html':
			include_once("wiki.ext.php");
			return wiki_parcer::convert($s,'html');
			break;
		case 'jscomment':
			return trim(preg_replace(
				array('/\n/'),
				array("\n * "),
				$s))."\r\n";
			break;
		case 'php_comment':	
			// выводим php код в окружении закрывающего - открывающего комментария
			return '*/
			'.$s.'
			/*';
		case 'html2js':
			// выводим html для вставки в изображение строки с двойными кавычками.
			// TODO: добавить резку текста по длине строки 
			// TODO: работа со скриптами и стилями нужна? 
			return preg_replace(
				array('/"/','/\\\\/','/\\s\\s+/','/^\\s+|\\s+$/m'),
				array('\"','\\\\',' ',''),
				$s);
		case 'css2js':
			// выводим css для вставки в изображение строки с двойными кавычками.
			return preg_replace(
				array('/\/\*.*\*\//ms','/\/\/.*?/','/"/','/\\\\/','/\\s\\s+/','/^\\s+|\\s+$/m'),
				array('',' ','\"','\\\\',' ',''),
				$s);
	}
	return $s;  
}

function insert_point($point_name){
	echo "/****** point $point_name */\r\n".point($point_name)."/****finish point $point_name *//*\r\n";
}
