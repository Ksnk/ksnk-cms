<?php
class tpl_printairis extends tpl {

function _(&$par){		
		return (isset($par['Date'])?$par['Date']:'').';     (����)
'.(isset($par['Time'])?$par['Time']:'').';        (�����)
'.(isset($par['ordernum'])?$par['ordernum']:'').'i;          (����� ������) 
'.(isset($par['user'])?$par['user']:'').';        (��� ������������)
'.(isset($par['cust_PHONE'])?$par['cust_PHONE']:'').';    (�������)
'.(isset($par['cust_ADDRESS'])?$par['cust_ADDRESS']:'').';  (��.�����)
'.(isset($par['cust_EMAIL'])?$par['cust_EMAIL']:'').'; (E-mail)
'.(isset($par['cust_FIO'])?$par['cust_FIO']:'').';     (���������� ����)
'.(isset($par['spec_INN'])?$par['spec_INN']:'').';     (���)
'.(isset($par['spec_KPP'])?$par['spec_KPP']:'').';   (���)
'.(isset($par['cust_OGRN'])?$par['cust_OGRN']:'').';        (����)
'.(isset($par['cust_DIRECTOR'])?$par['cust_DIRECTOR']:'').';(��� ���-���������)
'.(isset($par['spec_ORGANISATION'])?$par['spec_ORGANISATION']:'').';        (�������� �����������)
'.(isset($par['spec_ORDERNUM'])?$par['spec_ORDERNUM']:'').';    (� �����)
'.(isset($par['spec_BANK'])?$par['spec_BANK']:'').';  (�������� �����)
'.(isset($par['spec_BIK'])?$par['spec_BIK']:'').';    (��� ����)
'.(isset($par['cust_BANK_INN'])?$par['cust_BANK_INN']:'').';    (��� �����)
'.(isset($par['cust_BANK_KPP'])?$par['cust_BANK_KPP']:'').';    (��� �����)
'.(isset($par['spec_SORDERNUM'])?$par['spec_SORDERNUM']:'').';    (� ������������������ �����)
������� ������;������������;���-��;����
'.tpl::_a($par['llist'],array('tpl_printairis','llist'));
}

function llist(&$par){		
		return (isset($par['articul'])?$par['articul']:'').';'.(isset($par['descr'])?$par['descr']:'').';'.(isset($par['cnumb'])?$par['cnumb']:'').';'.(isset($par['ccost'])?$par['ccost']:'');
}}
?>