<?php
class tpl_printairis extends tpl {

function _(&$par){		
		return (isset($par['Date'])?$par['Date']:'').';     (Дата)
'.(isset($par['Time'])?$par['Time']:'').';        (Время)
'.(isset($par['ordernum'])?$par['ordernum']:'').'i;          (Номер заказа) 
'.(isset($par['user'])?$par['user']:'').';        (ник пользователя)
'.(isset($par['cust_PHONE'])?$par['cust_PHONE']:'').';    (Телефон)
'.(isset($par['cust_ADDRESS'])?$par['cust_ADDRESS']:'').';  (Юр.адрес)
'.(isset($par['cust_EMAIL'])?$par['cust_EMAIL']:'').'; (E-mail)
'.(isset($par['cust_FIO'])?$par['cust_FIO']:'').';     (Контактное лицо)
'.(isset($par['spec_INN'])?$par['spec_INN']:'').';     (ИНН)
'.(isset($par['spec_KPP'])?$par['spec_KPP']:'').';   (КПП)
'.(isset($par['cust_OGRN'])?$par['cust_OGRN']:'').';        (ОГРН)
'.(isset($par['cust_DIRECTOR'])?$par['cust_DIRECTOR']:'').';(ФИО ген-директора)
'.(isset($par['spec_ORGANISATION'])?$par['spec_ORGANISATION']:'').';        (Название организации)
'.(isset($par['spec_ORDERNUM'])?$par['spec_ORDERNUM']:'').';    (№ счета)
'.(isset($par['spec_BANK'])?$par['spec_BANK']:'').';  (Название банка)
'.(isset($par['spec_BIK'])?$par['spec_BIK']:'').';    (БИК банк)
'.(isset($par['cust_BANK_INN'])?$par['cust_BANK_INN']:'').';    (ИНН банка)
'.(isset($par['cust_BANK_KPP'])?$par['cust_BANK_KPP']:'').';    (КПП банка)
'.(isset($par['spec_SORDERNUM'])?$par['spec_SORDERNUM']:'').';    (№ корреспондентского счета)
артикул товара;наименование;кол-во;Цена
'.tpl::_a($par['llist'],array('tpl_printairis','llist'));
}

function llist(&$par){		
		return (isset($par['articul'])?$par['articul']:'').';'.(isset($par['descr'])?$par['descr']:'').';'.(isset($par['cnumb'])?$par['cnumb']:'').';'.(isset($par['ccost'])?$par['ccost']:'');
}}
?>