<%
##
## настройка плагина для проекта ezavod
##
##  действия : 
##    прописать css
##    вкюлчить javascript в site.js
##
point_start('js_body');
include("/xilen/cms/plugins/forum/forum.js");
point_finish('js_body');
##
## добавляем недостающие файлы в сборку
##<?php
	$this->xml_read('
<config>
	<files dir="/xilen/cms/plugins/forum" dstdir="$dst/admin/engine/plugins">
		<file>forum.php</file>
	</files>
	<files dir="/xilen/cms/plugins/forum" dstdir="$dst/templates">
		<file>jforum.jtpl</file>
	</files>
</config>
');
%>