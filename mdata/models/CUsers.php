<?php
/**
 * Пример использования EMultyData для реализации модели USERS
 * Дополнительных таблиц не используется
 *
 * - потенциальные возможности
 * -- хранение самой разной информации о пользователях
 * -- проверка имени-пароля для системы авторизации
  *
 * - реализованные потребности
 * -- проверка имени-пароля,
 * -- поиск пользователя по имени
 * -- вывод списка пользователей постранично
 * - для панели администрирования
 * -- Доьавить пользователя
 * -- удалить пользователя
 *
 */

class CUsers extends CModel {

    /**
     * data provider
     */
    static $db;
    
    private $md=null;

    public function tableName()
    {
        return $this->base_name;
    }

    function __construct($options=null){
        $this->md= new EMultyDataBehavior($options);
        $this->attachBehavior('data',$this->md);
    }

    public function attributeNames()
    {
        return array(
        );
    }

    /**
     * поддержка постраничного вывода списка пользователей
     * @param bool $drop
     * @return void
     */
    function createTempTable($drop=false){
        if(empty(self::$db))
            self::$db=Yii::app()->getDb();
        if($drop)
            self::$db->createCommand('drop table if exists {{user_list_temp}};')->execute();
        self::$db->createCommand('create temporary table {{user_list_temp}} if not exists ('
            .'id `id` int(11) NOT NULL'
            .'PRIMARY KEY (`id`)) '
            .'select distinct `id`,`sval` from '.$this->md->multyDataName.' where `name`="name";'
        )->execute();
    }

    /**
     * изменение записи пользователя
     * @param  $user - массив параметров пользователя
     * @return array
     */
    function store(&$user){
        $user['record']='user';
        return $user=$this->md->writeRecord($user);
    }

    /**
     * удалить пользователя из системы
     * @param  $id
     * @return void
     */
    function del($id){
        if(!is_array($id))
            $id=array('id'=>$id);
        $this->delRecord($id);
    }

    /**
     * найти пользователя
     * @param mixed &$user - информация о пользователе.
     * @return bool - true - пользователь найден. false - больше одного пользователя найдено, null - не найден
     *
     * пример проверки на имя-пароль
     * $user=array('name'=>'Ivan IV', 'password'=>md5('the mad'));
     * if($this->get($user)){
 ... user found and valid
     * }
     */
    function get(&$user){
        $user= $this->readRecord($user);
        if(isset($user['id']))
            return true;
        else
            return false;
    }

 }
