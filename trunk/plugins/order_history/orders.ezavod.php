<%
##
## ��������� ������� ��� ������� ezavod
##
##  �������� :
##    ��������� css
##
point_start('js_admin');%>
setup_menu('xstate');
        
<% point_finish('js_admin');
##
## ��������� ����������� ����� � ������
##<?php
	$this->xml_read('
<config>
	<files dir="plugins/order_history" >
		<file dstdir="$dst/admin/engine/plugins">order_history.php</file>
		<file dstdir="$dst/admin/templates">jorderhistory.jtpl</file>
	</files>
</config>
')
%>