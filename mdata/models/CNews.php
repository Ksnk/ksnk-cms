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

class CNews extends Cmodel {

    /**
     * data provider
     */
    var $ar;

    
    public function attributeNames()
    {
        return array(
        );
    }
    /**
     * @param  array $news
     * @return int - идентификатор новости в терминах таблицы новостей.
     * добавить новость в базу
     */
    function addNews(&$news){
        if (!empty($news['date'])) $news['date']=time();
        return $this->ar->store($news);
    }

    /**
     * @param  array $news
     * @return void
     * удалить новость по индексу
     */
    function delNews($id){
        $this->ar->delete(array('id'=>$id));
    }

 }
