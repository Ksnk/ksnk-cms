<?php
/**
 * Модуль для реализации простой модели списка новостей.
 * Новости с дополнительной таблицей с данными.
 * потребности
 * -- вывод списка из нескольких новостей, начиная с даты, начиная со станицы,
 * -- за период, начиная со страницы
 * -- количество новостей за период
 * admin
 * -- delNews
 * -- addNews
 */

class CNews extends CModel {

    /**
     * data provider
     */
    var $ar;
    static $db;

    function __construct($options=null){
        $this->ar=new CMultyData($options);
        self::$db=Yii::app()->getDb();
    }

    public function attributeNames()
    {
        return array(
        );
    }

    public function indexes($on=true){
        if(!$on){
           // self::$db->createCommand('ALTER TABLE {{news_date}} CHANGE `id` `id` INT( 11 ) NOT NULL;')->execute();
           // self::$db->createCommand('ALTER TABLE {{news_date}} DROP PRIMARY KEY;')->execute();
           //  self::$db->createCommand('ALTER TABLE {{news_date}} CHANGE `id` `id` INT( 11 ) NOT NULL AUTO_INCREMENT;')->execute();
           //
            self::$db->createCommand('ALTER TABLE {{flesh}} DROP INDEX `sval`;')->execute();
            self::$db->createCommand('ALTER TABLE {{flesh}} CHANGE `id` `id` INT( 11 ) NOT NULL;')->execute();
            self::$db->createCommand('ALTER TABLE {{flesh}} DROP PRIMARY KEY;')->execute();
        } else {
           // self::$db->createCommand('ALTER TABLE {{news_date}} ADD PRIMARY KEY ( `id` );')->execute();
            self::$db->createCommand('ALTER TABLE {{flesh}} ADD INDEX ( `sval` ) ;')->execute();
            self::$db->createCommand('ALTER TABLE {{flesh}} ADD PRIMARY KEY ( `id` , `name` );')->execute();
            self::$db->createCommand('ALTER TABLE {{flesh}} CHANGE `id` `id` INT( 11 ) NOT NULL AUTO_INCREMENT;')->execute();
        }
    }
    
    /**
     *
     * добавить одну запись - новость
     * @param  array $news
     * @return int - идентификатор новости в терминах таблицы новостей.
     * добавить новость в базу
     *
     */
    function addNews(&$news){
        if (empty($news['date']))
            $news['date']=time();
        self::$db->createCommand('insert {{news_date}} set date=:date')->execute(array('date'=>$news['date']));
        $news['id']=self::$db->getLastInsertID();
        unset($news['date']);
        $news['record']='news';
        return $this->ar->writeRecord($news);
    }

    /**
     * вывести список новостей за диапазон дат, отсортированный по дате
     */
    function getNews($from,$to){
        return $this->ar->readRecords(
            array(),
            array(//'where'=>'u1.name="date" and u1.ival BETWEEN :from AND :to'
                'order'=>array('z.date DESC')
                ,'fields'=> array('date')
                ,'from'=>', z.date from (select id,date from {{news_date}} where `date` BETWEEN :from AND :to order by `date` limit 50) as z left join '.$this->ar->table_name . ' as u0 on z.id=u0.id '
                ,'param'=>array('from'=>$from,'to'=>$to)
            ));
    }

    /**
     * вывести список последних
     * @param  array $news
     * @return array - идентификатор новости в терминах таблицы новостей.
     * добавить новость в базу
     *
     */
    function getLastNews(){
        return $this->ar->readRecords(
            array(),
            array(
                'order'=>array('z.date DESC')
                ,'fields'=> array('date')
                ,'from'=>', z.date from (select id,date from {{news_date}} order by `date` DESC limit :cnt) as z left join '.$this->ar->table_name . ' as u0 on z.id=u0.id '
                ,'param'=>array('cnt'=>50)
            ));
    }

    /**
     * @param  array $news
     * @return void
     * удалить новость по индексу
     */
    function delNews($id){
        $this->ar->delRecord(array('id'=>$id));
    }

 }
