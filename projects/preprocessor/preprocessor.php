<?php
/**
 * to call preprocesor - type   php -f preprocessor.php file_name
 * 
 * <%=point('hat','comment');%>
 */
include_once ("preprocessor.class.php");
include_once ("point.ext.php");

function pps(&$p,$def){return empty($p)?$def:$p;}

$preprocessor=new preprocessor();

foreach($_ENV as $k=>$v){
    $preprocessor->export('env_'.$k,$v);
};

echo '<%=$version%>


';
$arg='';
for ($i=1;$i<$argc;$i++)
    $arg.=' '.$argv[$i];
//echo $arg;

while(!empty($arg)){
    if(preg_match('/^\/force\s/',$arg,$m)){
	    $preprocessor->cfg_time(time());
        $arg=trim(substr($arg,strlen($m[0])));
    } elseif(preg_match('/^\s*\/D([\.\w]+)\=\'([^\']+)\'/',$arg,$m)){
        $preprocessor->export($m[1],$m[2]);
        //echo 'export '.$m[1].'='.$m[2];
        $arg=trim(substr($arg,strlen($m[0])));
    } elseif(preg_match('/^\s*\/D([\.\w]+)\=(\S+)/',$arg,$m)){
        if(!empty($m[2]))
            $preprocessor->export($m[1],$m[2]);
        else
		    $preprocessor->export($m[1],$m[3]);
        $arg=trim(substr($arg,strlen($m[0])));
	} else if (preg_match('~^([\.\\/\w]+)~',$arg,$m)){//is_file($argv[$i])) {
        $arg=trim(substr($arg,strlen($m[0])));
        if(is_file($m[0])){
		$arg1=pathinfo($m[0]);
		if ($arg1['extension']=='xml'){
            echo "making ".$m[0]."
";
			$preprocessor->xml_read($m[0]);
		} else {
			$xmlstr = <<<XML
<?xml version='1.0' standalone='yes'?>
<config>
	<files dstdir="build">
		<file>${argv[$i]}</file>
	</files>
</config>
XML;
			$preprocessor->xml_read($xmlstr);
		}
        }else {
           echo 'fail! wrong parameter-'.$m[0];
		    exit;
        }
	} else {
		echo 'fail! wrong parameter-'.$arg;
		exit;
	}
}
$preprocessor->process();
?>