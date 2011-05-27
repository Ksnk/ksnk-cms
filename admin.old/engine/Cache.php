<?php
/**
 * Кэширование рессурсов
 * 
 * рессурс - имя рессурса + ассоциативный массив - список параметров + массив - список имен зависимостей ;
 * зависимость - символическое имя + время последнего изменения
 * 
 * по имени рессурса и параметрам строится имя файла рессурса. По списку зависимостей получаем время последнего 
 * изменения
 * 
 * Зависимости хранятся в таблице 
 * Id, Name, mTime Name - уникальный индекс
 * 
 * Запрос на изменение данных сопровождается обновлением времен по списку зависимостей.
 */

class Cache {
	private $dependence=array();
	
/**
 * создание базы 
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
 * контроль кэш-файлов 
 */
	function register_cache($name,$dependence){
	}
	
/**
 * получить максимальное время изменения рессурсов
 * @param array_of_string $dependence
 */	
	function dependence_time($dependence){
		$sql='select max(mTime) from ?_cache where Name in("'.implode($dependence,'","').'");';

	}
	
/**
 * обновление времени зависимостей по списку зависимостей
 * @param array_of_string $dependence
 */	
	function update_dependence($dependence){
		$sql='update ?_cache set mTime=Now() where Name in("'.implode($dependence,'","').'");';
		// добавить недостающие зависимости
		$this->dependence=array_merge($this->dependence,$dependence) ;
	}
	
/**
 * добавление всех новых зависимостей в базу.
 */	
	function __destruct()
    {
 		#проверяем и добавляем все новые элементы в список зависимостей
 		$sql='insert ignore into ?_cache (Name) values ("'
 			.implode($this->dependence,'"),("').'");';
    }
    
}