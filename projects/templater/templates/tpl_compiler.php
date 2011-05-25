<?php
/**
 * this file is created automatically at "01 Dec 2010 17:30". Never change anything, 
 * for your changes can be lost at any time.  
 */
include_once TEMPLATE_PATH.DIRECTORY_SEPARATOR.'tpl_base.php';

class tpl_compiler extends tpl_base {
function __construct(){
$this->macro=array();
}
function _class(&$par){
$result='<?php
/**
 * this file is created automatically at "'
    .date('d M Y G:i')
    .'". Never change anything, 
 * for your changes can be lost at any time.  
 */
';
if( (isset($par['extends']) && !empty($par['extends'])) ) {
$result.='include_once TEMPLATE_PATH.DIRECTORY_SEPARATOR.\'tpl_'
    .(isset($par['extends'])?$par['extends']:"")
    .'.php\';
';
} else {
$result.='include_once TEMPLATE_PATH.DIRECTORY_SEPARATOR.\'tpl_base.php\';
';
};    	
$result.='';
$loop_array=ps($par['import']);
if (!empty($loop_array)){
foreach($loop_array as $imp){
$result.='require_once TEMPLATE_PATH.DIRECTORY_SEPARATOR.\'tpl_'
    .$imp
    .'.php\';
';
}}
;    	
$result.='
class tpl_'
    .(isset($par['name'])?$par['name']:"")
    .' extends tpl_'
    .$this->filter_default((isset($par['extends'])?$par['extends']:""),'base')
    .' {
function __construct(){
$this->macro=array();
';
$loop_array=ps($par['macro']);
if (!empty($loop_array)){
foreach($loop_array as $m){
$result.='$this->macro[\''
    .$m
    .'\']=array($this,\'_\'.\''
    .$m
    .'\');
';
}}
;    	
$result.='';
$loop_array=ps($par['import']);
if (!empty($loop_array)){
foreach($loop_array as $imp){
$result.='$'
    .$imp
    .'=new tpl_'
    .$imp
    .'();
$this->macro=array_merge($this->macro,$'
    .$imp
    .'->macro);';
}}
;    	
$result.='}
';
$loop_array=ps($par['data']);
if (!empty($loop_array)){
foreach($loop_array as $func){
$result.=$func
    .'
';
}}
;    	
$result.='}
';
return $result;
}

function _callmacro(&$par){
$result='if(!empty($this->macro[\''
    .(isset($par['name'])?$par['name']:"")
    .'\']))
  $result.=call_user_func($this->macro[\''
    .(isset($par['name'])?$par['name']:"")
    .'\'],array(';
$loop_array=ps($par['parkeys']);
if (!empty($loop_array)){
foreach($loop_array as $p){
$result.='\''
    .(isset($p["key"])?$p["key"]:"")
    .'\'=>'
    .(isset($p["value"])?$p["value"]:"")
    .',';
}}
;    	
$result.=')';
if( (isset($par['param']) && !empty($par['param'])) ) {
$result.=','
    .$this->filter_join((isset($par['param'])?$par['param']:""),', ');
};    	
$result.=')';
return $result;
}

function _set(&$par){
$result=(isset($par['id'])?$par['id']:"")
    .'='
    .(isset($par['res'])?$par['res']:"");
return $result;
}

function _for(&$par){
$result='$loop'
    .(isset($par['loopdepth'])?$par['loopdepth']:"")
    .'_array=ps('
    .(isset($par['in'])?$par['in']:"")
    .');
';
if( (isset($par['loop_index']) && !empty($par['loop_index'])) ) {
$result.='$loop'
    .(isset($par['loopdepth'])?$par['loopdepth']:"")
    .'_index=0;
';
};    	
$result.='';
if( (isset($par['loop_last']) && !empty($par['loop_last'])) ) {
$result.='$loop'
    .(isset($par['loopdepth'])?$par['loopdepth']:"")
    .'_last=count($loop'
    .(isset($par['loopdepth'])?$par['loopdepth']:"")
    .'_array);
';
};    	
$result.='';
if( (isset($par['loop_revindex']) && !empty($par['loop_revindex'])) ) {
$result.='$loop'
    .(isset($par['loopdepth'])?$par['loopdepth']:"")
    .'_revindex=$loop'
    .(isset($par['loopdepth'])?$par['loopdepth']:"")
    .'_last+1;
';
};    	
$result.='';
if( (isset($par['loop_cycle']) && !empty($par['loop_cycle'])) ) {
$result.='$loop'
    .(isset($par['loopdepth'])?$par['loopdepth']:"")
    .'_cycle='
    .(isset($par['loop_cycle'])?$par['loop_cycle']:"")
    .';
';
};    	
$result.='if (!empty($loop'
    .(isset($par['loopdepth'])?$par['loopdepth']:"")
    .'_array)){
foreach($loop'
    .(isset($par['loopdepth'])?$par['loopdepth']:"")
    .'_array as '
    .(isset($par['index'])?$par['index']:"")
    .'){
';
if( (isset($par['loop_index']) && !empty($par['loop_index'])) ) {
$result.='$loop'
    .(isset($par['loopdepth'])?$par['loopdepth']:"")
    .'_index++;
';
};    	
$result.='';
if( (isset($par['loop_revindex']) && !empty($par['loop_revindex'])) ) {
$result.='$loop'
    .(isset($par['loopdepth'])?$par['loopdepth']:"")
    .'_revindex--;
';
};    	
$result.=(isset($par['body'])?$par['body']:"")
    .'}}
';
if( (isset($par['else']) && !empty($par['else'])) ) {
$result.=' else { 
'
    .(isset($par['else'])?$par['else']:"")
    .'}';
};    	
$result.='';
return $result;
}

function _callblock(&$par){
$result='';
$x=(isset($par['name'])?$par['name']:"");    	
$result.='';
if( $x ) {
$result.='$this->_'
    .$x
    .'($par)';
};    	
$result.='';
return $result;
}

function _block(&$par){
$result='';
if( (isset($par['name']) && !empty($par['name'])) ) {
$result.='';
if( ((isset($par['tag'])?$par['tag']:""))==('macros') ) {
$result.='function _'
    .(isset($par['name'])?$par['name']:"")
    .'(&$namedpar';
$loop_array=ps($par['param']);
if (!empty($loop_array)){
foreach($loop_array as $p){
$result.=',$'
    .(isset($p["name"])?$p["name"]:"");
if( (isset($p["value"]) && !empty($p["value"])) ) {
$result.='='
    .(isset($p["value"])?$p["value"]:"");
} else {
$result.='=0';
};    	
$result.='';
}}
;    	
$result.='){
extract($namedpar);
';
} else {
$result.='function _'
    .(isset($par['name'])?$par['name']:"")
    .'(&$par){
';
};    	
$result.='';
};    	
$result.='';
$loop2_array=ps($par['data']);
$loop2_index=0;
if (!empty($loop2_array)){
foreach($loop2_array as $blk){
$loop2_index++;
$result.='';
if( (isset($blk["string"]) && !empty($blk["string"])) ) {
$result.='';
if( ($loop2_index==1) && ((isset($par['name']) && !empty($par['name']))) ) {
$result.='$result='
    .$this->filter_join((isset($blk["string"])?$blk["string"]:""),'
    .')
    .';
';
} else {
$result.='$result.='
    .$this->filter_join((isset($blk["string"])?$blk["string"]:""),'
    .')
    .';
';
};    	
$result.='';
} else {
$result.=(isset($blk["data"])?$blk["data"]:"")
    .';    	
';
};    	
$result.='';
}}
;    	
$result.='';
if( (isset($par['name']) && !empty($par['name'])) ) {
$result.='return $result;
}
';
};    	
$result.='';
return $result;
}

function _if(&$par){
$result='';
$if_index=1;    	
$result.='';
$if_last=count((isset($par['data'])?$par['data']:""));    	
$result.='';
$loop_array=ps($par['data']);
if (!empty($loop_array)){
foreach($loop_array as $d){
$result.='';
if( ($if_index)==(1) ) {
$result.='if( '
    .(isset($d["if"])?$d["if"]:"")
    .' ) {
'
    .(isset($d["then"])?$d["then"]:"")
    .'}';
} elseif( ((isset($d["if"]) && !empty($d["if"]))) || (($if_index)!=($if_last)) ) {
$result.=' elseif( '
    .(isset($d["if"])?$d["if"]:"")
    .' ) {
'
    .(isset($d["then"])?$d["then"]:"")
    .'}';
} else {
$result.=' else {
'
    .(isset($d["then"])?$d["then"]:"")
    .'}';
};    	
$result.='';
$if_index=($if_index)+(1);    	
$result.='';
}}
;    	
$result.='';
return $result;
}

function _ (&$par){
$result='
'
    .$this->_class($par)
    .'
'
    .$this->_callmacro($par)
    .$this->_set($par)
    .'
'
    .$this->_for($par)
    .'
'
    .$this->_callblock($par)
    .'
'
    .$this->_block($par)
    .$this->_if($par);
return $result;
}

}