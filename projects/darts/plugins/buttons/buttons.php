<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ������
 * Date: 16.11.11
 * Time: 22:11
 * To change this template use File | Settings | File Templates.
 */
?>
<style>
    <% point_start('css_site');%>

a.button {
  display: block;
  float: left; /* ����� ������ �� ������������� �� ��� ������ ������������� �����, � �������������� ������� */
  font: bold 12px arial, sans-serif;
  color: #555;
  text-decoration: none;
  background: url(<%=$target_dir%>/img/button_r.gif) top right no-repeat;
  padding-right: 25px; /* ������ ��� "���������� ������" */
  outline: none; /* ������� �������� ������� � FireFox, ������� ���������� ��� ����� */
}
a.button span {
  display: block;
  line-height: 13px;
  background: url(<%=$target_dir%>/img/button_l.gif) no-repeat;
  padding: 7px 0 13px 23px;
}

a.button:hover {
  background-position: 100% -33px;
}
a.button:hover span {
  background-position: 0% -33px;
  color: #222;
}
a.button:active,
a.button:focus {
  background-position: 100% -66px;
}
a.button:active span,
a.button:focus span {
  background-position: 0% -66px;
  color: #222;
  padding: 8px 0 12px 23px; /* �������� ����� �� 1px */
}
    
<% point_finish('css_site') %>
</style>

<%
	$this->xml_read('
<config>
	<files dstdir="$dst/img/">
		<copy>*.gif</copy>
	</files>
</config>
');
%>