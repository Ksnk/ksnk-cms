<?

define('TAB_PREF','darts'); // префикс базы данных
$db_setup='SET NAMES utf8;';
//$basename="rosin";
<%
//if(trim($target_dir,' /')!='') 
$target_dir='/'.trim($target_dir,'/');
$target_host='';

if($target!='debug' && $target_host!='localhost') {
	switch ($target_host) {
		case 'xln':		%>
$basename="db_xilen_14";
$the_host="mysql.1.xilen.z8.ru";
$the_pass="gAPXQVyImXj";
$the_login="dbu_xilen_15";
		<%		break;
		case 'ksnk': 
		default:	
$target_host='http://ksnk.dpb.ru'			
			%>
$basename="ksnk";
$the_host="localhost";
$the_pass="kS52n7k";
$the_login="ksnk";
		<%		
	}

	
 } else { 
$target_host='http://localhost'	;
 	%>
$basename="tmp";
error_reporting(E_ALL);
ini_set('display_errors',false);
ini_set('log_errors',true);
$the_host="localhost";

$the_pass="";
$the_login="root";
<%}%>
$the_db=$basename."";
?>