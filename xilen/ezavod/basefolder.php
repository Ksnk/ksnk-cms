<?php
$basefolder = str_replace('\\', '/', __FILE__);
$dl_dr = strlen($_SERVER['DOCUMENT_ROOT']);
//$drc = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
//$basefolder = str_ireplace($drc, '', $basefolder);
$basefolder = substr_replace($basefolder, '', 0, $dl_dr);
$basefolder = ereg_replace('basefolder\.php$', '', $basefolder);
$basefolder = ereg_replace('^/+', '', $basefolder);
$basefolder = ereg_replace('/+$', '', $basefolder);
define('BASE_FOLDER',$basefolder);
//echo BASE_FOLDER;
?>