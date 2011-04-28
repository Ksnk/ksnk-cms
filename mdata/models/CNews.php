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

class CNews extends CActiveRecord {

    /**
     * data provider
     */
    static $db;
    private $base_name='{{news}}';

    public function tableName()
    {
        return $this->base_name;
    }

    function __construct($options=null){
        self::$db=Yii::app()->getDb();
    }

    public function behaviors(){
        return array(
            'tree' => array(
                'class' => 'EMultyDataBehavior',
                // имя дополнительной таблицы
                'multyDataName' => '{{news_data}}',
            ),
        );
    }

    public function attributeNames()
    {
        return array(
        );
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
        return $this->writeRecord($news);
    }

    /**
     * вывести список новостей за диапазон дат, отсортированный по дате
     */
    function getNews($from,$to){
        return self::model(__CLASS__)->readRecords(
            array(),
            array(//'where'=>'u1.name="date" and u1.ival BETWEEN :from AND :to'
                'order'=>array('z.date DESC')
                ,'fields'=> array('date')
                ,'from'=>', z.date from (select id,date from {{news_date}} where `date` BETWEEN :from AND :to order by `date` limit 50) as z left join '.self::model(__CLASS__)->multyDataName . ' as u0 on z.id=u0.id '
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
        return self::model(__CLASS__)->readRecords(
            array(),
            array(
                'order'=>array('z.date DESC')
                ,'fields'=> array('date')
                ,'from'=>', z.date from (select id,date from {{news_date}} order by `date` DESC limit :cnt) as z left join '.self::model(__CLASS__)->multyDataName . ' as u0 on z.id=u0.id '
                ,'param'=>array('cnt'=>50)
            ));
    }

    /**
     * вывести список новостей по шв
     * @param  array $news
     * @return array - идентификатор новости в терминах таблицы новостей.
     * добавить новость в базу
     *
     */
    function getNewsById($id){
        return self::model(__CLASS__)->readRecord(
            array(),
            array(
                'order'=>''//array('z.date DESC')
                ,'fields'=> array('date')
                ,'from'=>', z.date from (select id,date from {{news_date}} where id=:xid) as z left join '.self::model(__CLASS__)->multyDataName . ' as u0 on z.id=u0.id '
                ,'param'=>array('xid'=>$id)
            ));
    }

    /**
     * @param  array $news
     * @return void
     * удалить новость по индексу
     */
    function delNews($id){
        self::model(__CLASS__)->delRecord(array('id'=>$id));
    }

 }
