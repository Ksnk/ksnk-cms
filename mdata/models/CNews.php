<?php
/**
 * Пример использования EMultyData для реализации моделти NEWS
 *
 * - потенциальные возможности
 * -- "расширяемость" формата новостей позволяет свободно изменять
 *    структуру новости без изменения таблицы данных.
 *
 * - реализованные потребности
 * -- вывод списка из нескольких новостей, начиная с даты, начиная со страницы,
 * -- за период, начиная со страницы
 * -- количество новостей за период
 * -- удаление новости
 * - для панели администрирования
 * -- delNews
 * -- addNews
 *
 * - подход к реализации:
 * -- используется CactiveRecorв, однако пользоваться обычными для него save нельзя!
 * -- для работы с SQL используется CDBCriteria
 *
 */

class CNews extends CModel {

    /**
     * data provider
     */
    static $db;
    
    private $md=null;
    private $base_name='{{news_date}}';

    public function tableName()
    {
        return $this->base_name;
    }

    function __construct($options=null){
        self::$db=Yii::app()->getDb();
        $this->md= new EMultyDataBehavior(array('multyDataName'=>$this->base),self::$db);
        $this->attachBehavior('data',$this->md);
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
        self::$db->createCommand('insert '.self::$db->quoteTableName($this->base).' set date=:date')->execute(array('date'=>$news['date']));
        $news['id']=self::$db->getLastInsertID();
        unset($news['date']);
        $news['record']='news';
        return $this->md->writeRecord($news);
    }

    /**
     * вывести список новостей за диапазон дат, отсортированный по дате
     */
    function getNews($from,$to){
        return $this->readRecords(
            array(),
            array(//'where'=>'u1.name="date" and u1.ival BETWEEN :from AND :to'
                'order'=>array('z.date DESC')
                ,'fields'=> array('date')
                ,'from'=>', z.date from (select id,date from '.self::$db->quoteTableName($this->base).' where `date` BETWEEN :from AND :to order by `date` limit 50) as z left join '.$this->multyDataName . ' as u0 on z.id=u0.id '
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
        //print_r($this);
        return $this->readRecords(
            array(),
            array(
                'order'=>array('z.date DESC')
                ,'fields'=> array('date')
                ,'from'=>', z.date from (select id,date from '.self::$db->quoteTableName($this->base).' order by `date` DESC limit :cnt) as z left join '.$this->multyDataName . ' as u0 on z.id=u0.id '
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
        return $this->readRecord(
            array(),
            array(
                'order'=>''//array('z.date DESC')
                ,'fields'=> array('date')
                ,'from'=>', z.date from (select id,date from '.self::$db->quoteTableName($this->base).' where id=:xid) as z left join '.$this->multyDataName . ' as u0 on z.id=u0.id '
                ,'param'=>array('xid'=>$id)
            ));
    }

    /**
     * @param  array $news
     * @return void
     * удалить новость по индексу
     */
    function delNews($id){
        if(!is_array($id))
            $id=array('id'=>$id);
        $this->delRecord($id);
    }

 }
