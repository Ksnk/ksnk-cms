<%
##
## ��������� ������� ��� ������� ezavod
##
##  �������� : 
##    ��������� css
##    �������� javascript � site.js
##
point_start('js_body');
include("plugins/forum/forum.js");
point_finish('js_body');
point_start('js_main');%>

    window.showInfo=function(el){
        dd_menu.call(
            $(el).removeAttr('onmouseover').css({
                position:'relative',
                display:'inline-block'
            }).find('div:first').css({
                backgroundColor:'white',
                width:'340px',
                border:'1px solid gray',
                padding:'10px 20px',
                position:'absolute',
                'z-index':100,
                top:'1.5em',
                left:0
            })[0]
        );
        $(el).trigger('mouseover');


    };

<%point_finish('js_body');
##
## ��������� ����������� ����� � ������
##<?php
	$this->xml_read('
<config>
	<files dir="plugins/forum" dstdir="$dst/admin/engine/plugins">
		<file>forum.php</file>
	</files>
	<files dir="plugins/forum" dstdir="$dst/templates">
		<file>jforum.jtpl</file>
	</files>
</config>
');
%>