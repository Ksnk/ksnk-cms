<?php
/**
 * to call preprocesor - type   php -f preprocessor.php file_name
 * 
 * <%=point('hat','jscomment');%>
 */
include_once ("preprocessor.class.php");
include_once ("point.ext.php");

$paths=new preprocessor();

for ($i=1;$i<$argc;$i++){
	if(preg_match('/^\/D(\w+)\=(\S+)$/',$argv[$i],$m)){
		$paths->export($m[1],$m[2]);
	} else if (is_file($argv[$i])) {
		$arg1=pathinfo($argv[$i]);
		if ($arg1['extension']=='xml'){
			$paths->xml_read($argv[$i]);
		} else {
			$xmlstr = <<<XML
<?xml version='1.0' standalone='yes'?>
<config>
	<files dstdir="build">
		<file>$argv[$i]</file>
	</files>
</config>
XML;
			$paths->xml_read($xmlstr);
		}
	} else {
		echo 'fail! wrong parameter/';
		exit;
	}
}
$paths->process();
?>