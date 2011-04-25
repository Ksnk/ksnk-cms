<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ksnk
 * Date: 13.04.11
 * Time: 18:39
 * Модуль для реализации простой модели списка новостей.
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

    function __construct($options=null){
        $this->ar=new CMultyData($options);
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
        $news['record']='news';
        return $this->ar->writeRecord($news);
    }

    /**
     * вывести список новостей за диапазон дат, отсортированный по дате
     * @param  array $news
     * @return array - идентификатор новости в терминах таблицы новостей.
     * добавить новость в базу
     *
     */
    function getNews($from,$to){
        return $this->ar->readRecords(
            'news',
            array('where'=>'u1.name="date" and u1.ival BETWEEN :from AND :to'
                 ,'order'=>'u1.ival'
                //'where'=>'u1.date BETWEEN :from AND :to'
                //,'order'=>'u1.date'//'u1.ival'

                //,'cnt'=>50
                ,'param'=>array('from'=>$from,'to'=>$to)
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
