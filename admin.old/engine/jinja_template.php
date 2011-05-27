<?php
/**
 * хелпер для подключения и трансляции jinja шаблонов
 */

include_once 'nat2php.class.php';
include_once 'compiler.class.php';

$jtpl=new template_compiler();
$jtpl->checktpl();
