<?php
/**
 * to call preprocesor - type   php -f preprocessor.php file_name
 * 
 * <%=point('hat','jscomment');%>
 */
include_once ("preprocessor.class.php");
include_once ("point.ext.php");

$preprocessor=new preprocessor();

foreach($_ENV as $k=>$v){
    $preprocessor->export('env_'.$k,$v);
}

echo "PHP Preprocessor, written by Ksnk (sergekoriakin@gmail.com). Ver : 1.0(beta)

";

for ($i=1;$i<$argc;$i++){
	if(preg_match('/^\/D([\.\w]+)\=(\S+)$/',$argv[$i],$m)){
		$preprocessor->export($m[1],$m[2]);
	} else if (is_file($argv[$i])) {
		$arg1=pathinfo($argv[$i]);
		if ($arg1['extension']=='xml'){
            echo "making ".$argv[$i]."
";
			$preprocessor->xml_read($argv[$i]);
		} else {
			$xmlstr = <<<XML
<?xml version='1.0' standalone='yes'?>
<config>
	<files dstdir="build">
		<file>$argv[$i]</file>
	</files>
</config>
XML;
			$preprocessor->xml_read($xmlstr);
		}
	} else {
		echo 'fail! wrong parameter/';
		exit;
	}
}
$preprocessor->process();
?>