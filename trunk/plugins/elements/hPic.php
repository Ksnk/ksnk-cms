<?php

/**
 * хелпер-класс для хранения картинок в галлерее
 *
 */
class hPic {
	
	/**
	 * Выдать катинку по ID
	 * @param int $gal - индекс галлереи
	 * @param int $limit - количество картинок в результате
	 */
	static function get($id){
		$sql='select * from ?_pictures where `id`=?';
		return DATABASE()->selectRow($sql,$id);
	}
	
	/**
	 * Выдать катинку по ID
	 * @param int $gal - индекс галлереи
	 * @param int $limit - количество картинок в результате
	 */
	static function del($id){
		$sql='delete from ?_pictures where `id`=?';
		return DATABASE()->select($sql,$id);
	}
	
	/**
	 * Выдать галерею по индексу галереи
	 * + автосоздание таблицы, если ее нет
	 * @param int $gal - индекс галлереи
	 * @param int $limit - количество картинок в результате
	 */
	static function getGal($gal,$limit=0){
		$sql='select * from ?_pictures where `gal`=? order by `order`';
		if($limit) $sql.=' limit '.$limit;
		if(is_null($x=@DATABASE()->select($sql,$gal))){
			self::create();
			$x=DATABASE()->select($sql,$gal);
		};
		return $x;
	}
	
	/**
	 * вставить картинку в галлерею
	 * @param array $key - комплект параметров строки таблицы
	 * @return индекс галлереи
	 */
	static function store($key){
		// проверка - изменяем или нет?
		if(!isset($key['id'])){ // добавить
			$key['id']=DATABASE()->select(
				'insert INTO ?_pictures (?#) VALUES(?a);'
				,array_keys($key)
				,array_values($key)
			);
			$x=self::get($key['id']);
			return $x['gal'];
		} else { // изменить 
			$k=$key['id'];unset($key['id']);
			DATABASE()->select(
				'UPDATE ?_pictures set ?a where `id`=?;'
				,$key,$k
			);
			return $key['gal'];
		}
	}
	
	static function create(){
		return DATABASE()->select('CREATE TABLE ?_pictures (
  `id` int(11) NOT NULL auto_increment,
  `gal` int(11) NOT NULL auto_increment,
  `small` varchar(120) NOT NULL,
  `big` varchar(120) NOT NULL,
  `link` varchar(120) NOT NULL,
  `descr` varchar(120) NOT NULL,
  `order` int NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `gal` (`gal`)
  );');
	}
}
