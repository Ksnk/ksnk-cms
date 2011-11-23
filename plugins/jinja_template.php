<?php
<%
$this->xml_read('
<config>
    <files  dstdir="$dst/engine" dir="../templater">
        <file>template_parser.class.php</file>
        <file>compiler.class.php</file>
        <file>compiler.php.php</file>
    </files>
    <files  dstdir="$dst/engine" dir="../nat2php">
        <file>nat2php.class.php</file>
    </files>
    <files  dstdir="$dst/engine" dir="../templater/templates">
        <file>tpl_base.php</file>
        <file>tpl_compiler.php</file>
    </files>
</config>
');
point_start('site_includes'); %>
include_once 'engine/compiler.class.php';
template_compiler::checktpl();

<% point_finish(); %>

