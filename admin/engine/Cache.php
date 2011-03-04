<?php
/**
 * ����������� ���������
 * 
 * ������� - ��� �������� + ������������� ������ - ������ ���������� + ������ - ������ ���� ������������ ;
 * ����������� - ������������� ��� + ����� ���������� ���������
 * 
 * �� ����� �������� � ���������� �������� ��� ����� ��������. �� ������ ������������ �������� ����� ���������� 
 * ���������
 * 
 * ����������� �������� � ������� 
 * Id, Name, mTime Name - ���������� ������
 * 
 * ������ �� ��������� ������ �������������� ����������� ������ �� ������ ������������.
 */

class Cache {
	private $dependence=array();
	
/**
 * �������� ���� 
 */
	function create($name,$dependence){
		$sql="CREATE TABLE  ?_cache (
 `id` INT NOT NULL AUTO_INCREMENT ,
 `Name` VARCHAR( 40 ) NOT NULL ,
 `mTime` TIMESTAMP NOT NULL ,
PRIMARY KEY (  `id` ) ,
UNIQUE (`Name`)
);";
	}
	
/**
 * �������� ���-������ 
 */
	function register_cache($name,$dependence){
	}
	
/**
 * �������� ������������ ����� ��������� ���������
 * @param array_of_string $dependence
 */	
	function dependence_time($dependence){
		$sql='select max(mTime) from ?_cache where Name in("'.implode($dependence,'","').'");';

	}
	
/**
 * ���������� ������� ������������ �� ������ ������������
 * @param array_of_string $dependence
 */	
	function update_dependence($dependence){
		$sql='update ?_cache set mTime=Now() where Name in("'.implode($dependence,'","').'");';
		// �������� ����������� �����������
		$this->dependence=array_merge($this->dependence,$dependence) ;
	}
	
/**
 * ���������� ���� ����� ������������ � ����.
 */	
	function __destruct()
    {
 		#��������� � ��������� ��� ����� �������� � ������ ������������
 		$sql='insert ignore into ?_cache (Name) values ("'
 			.implode($this->dependence,'"),("').'");';
    }
    
}