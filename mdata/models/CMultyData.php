<?php
/**
 *
 * класс для работы с записями произвольной наполненности
 * запись - одномерный ассоциативный массив
 *
 * некоторые имена свойств имеют специальное значение
 * id - идентификатор записи в таблице.
 * record - тип поля. param|user
 * name - имена полей, которые обязательно нужно размещать в индесированном поле.
 * Created by JetBrains PhpStorm.
 * User: ksnk
 * Date: 12.04.11
 * Time: 20:57
 * To change this template use File | Settings | File Templates.
 */
 
class CMultyData extends CModel
{
    /**
     * @var CDbConnection the default database connection for all active record classes.
     * By default, this is the 'db' application component.
     * @see getDbConnection
     */
    public static $db;
    
    public $table_name='{flesh}';

    private $special_words=array('root','record','name','password','url');

    public function attributeNames()
    {
        return array(
        );
    }

    /**
     * конструктор
     * @var array options - массив параметров для установки
     */
    function __construct ($options=null){
        $this->getDbConnection();
    }

    /**
     * приватные методы
     */

    /**
     * создание таблицы, если очень нужно.
     * функция вызывается в аварийном обработчике
     * @return void
     */

    private function createTable(){
        self::$db->createCommand()->createTable($this->table_name,
                array(
                    'id'=>'int(11) NOT NULL auto_increment',
                    'name'=>'varchar(60) NOT NULL',
                    'ival'=>'int(11) default NULL',
                    'sval'=>'varchar(255) NOT NULL',
                    'tval'=>'text',
                    'PRIMARY KEY  (`id`,`name`)',
                    'KEY `sval` (`sval`)'
                ),'Engine=InnoDB'
        );
    }

    /**
     *  прочитать одну (первую) запись
     */
    public function readRecord($param){
        $res=$this->readRecords($param,2,6000);
        if(!empty($res) && count($res)>0)
            return current($res);
        else
            return null;
    }
    
    /**
     * Returns the database connection used by active record.
     * By default, the "db" application component is used as the database connection.
     * You may override this method if you want to use a different database connection.
     * @return CDbConnection the database connection used by active record.
     */
    public function getDbConnection()
    {
        if(self::$db!==null)
            return self::$db;
        else
        {
            self::$db=Yii::app()->getDb();
            if(self::$db instanceof CDbConnection)
                return self::$db;
            else
                throw new CDbException(Yii::t('yii','Active Record requires a "db" CDbConnection application component.'));
        }
    }

    /**
     *  найти и удалить
     *
     */
     function delRecord($param){
        if (!isset($param['id']))
            $param=$this->readRecord($param);
        if(!empty($param['id'])){
            self::$db->createCommand()
                ->delete($this->table_name,'id=:id',array(':id'=>$param['id']));
            return true;
        } else
            return false;
     }

     //todo: переделать на более Yii-шный вид
     private function _cellname(&$k,$i){
        if(in_array($k,$this->special_words))
            return sprintf('u%1$s.sval',$i);
        return sprintf('if(isNull(u%1$s.ival),if(isNull(u%1$s.tval),u%1$s.sval,u%1$s.tval),u%1$s.ival)',$i);
     }

     private function _name(&$val,&$name){
        if(in_array($name,$this->special_words)) return 'sval';
        if(is_int($val) || (strlen($val)<10 && ctype_digit($val))) return 'ival';
        return 'tval';
     }

    /**
     * @param  $param
     * @param array $options
     *      $options.sql - выполнение sql, который предьявлен.
     *      $options.cnt - количество записей,
     *      $options.limit - максимальное количество просматриваемых строчек таблицы
     * @return array
     */
    function readRecords($param, $options)
    { //$cnt=6000,$xcnt=10000,$sql=''){
        // изготовление записи из переданных параметров
        if (!is_array($param))
            $param = array('record' => $param);
        if (!is_array($options))
            $options = array('cnt' => $options);
        if (empty($options['cnt']))
            $options['cnt'] = 6000;
        // строим запрос, если нам не дали строку заранее
        if (empty($options['sql'])) {
            //0:id,1:name,2:val, !!! 3:node,4:level,5:childs
            $sql = 'SELECT u.id,u.name, if(isNull(u.ival),if(isNull(u.tval),u.sval,u.tval),u.ival) as `value` from ' . $this->table_name . ' as u ';
            $where = array();
            $ind = 1;
            if (empty($param['id'])) {
                foreach ($param as $k => $v) {
                    $sql .= sprintf('LEFT JOIN ' . $this->table_name . ' AS u%s ON u.id = u%s.id ',
                                    $ind, $ind);
                    $where[] = sprintf('u%1$s.name="%2$s" and ' . $this->_cellname($k, $ind) . '="%3$s" ',
                                       $ind, mysql_real_escape_string($k), mysql_real_escape_string($v));
                    $ind++;
                }
                $sql .= 'where ' . implode(' and ', $where) . ' ORDER BY u.id';
            } else {
                $sql .= 'where `id`=' . $param['id'];
            }
        } else {
            $sql = $options['sql'];
        }
        
        // проверка на дорогах
        $rcnt = 2;
        while ($rcnt-- > 0) {
            if (!($_qresult = @mysql_query($sql . pps($options['limit'], ' LIMIT ')))) {
                // попытка залечить проблему, если ошибка исправима
                if (mysql_errno() == 1146) { // Table '' doesn't exist
                    $this->createTable();
                    continue;
                }
            }
            $rcnt = 0; // не нужно повторять!
        }

        if (!$_qresult) {
            debug('Invalid query: ' . __FILE__ . ':' . __LINE__ . ' ' . mysql_error() . "\n" . 'Whole query: ' . $sql . pps($options['limit'], ' LIMIT '));
        } else {
            $_result = array();
            $result = null;
            $id = 0;
            while (($row = mysql_fetch_array($_qresult, MYSQL_NUM))) {
                //debug($row);
                if ($row[0] != $id) {
                    if ($options['cnt']-- <= 0)
                        break;
                    if (!empty($id)) {
                        $_result[] = $result;
                    }
                    $id = $row[0];
                    $result = array('id' => $id);
                    if (isset($row[3])) {
                        $result['node'] = $row[3];
                        $result['level'] = $row[4];
                        if (isset($row[5]))
                            $result['childs'] = $row[5];
                    }
                }
                if (!empty($row[2]) && $row[2]{0} == 'a' && $row[2]{1} == ':') {
                    $result[$row[1]] = unserialize($row[2]);
                } else {
                    $result[$row[1]] = $row[2];
                }
            }
            mysql_free_result($_qresult);
        }
        //debug($result);
        if (!empty($id)) {
            $_result[] = $result;
            return $_result;
        } else
            return array($param);
    }

    /**
     *  записать полную запись параметр с категорией
     *	в ведущий параметр входят
     *  - name:category, name- sval
     *  все остальные записываются как дополнительные параметры записи
     * поля с именем, начинающиеся с символа подчеркивания, не записываются в базу.
     */
    function writeRecord($param){
        // вставляем новую запись
        unset($param['current'],$param['node'],$param['level'],$param['childs']);
        if(isset($param['id'])){
            // заказывается обновление существующей записи
            // проверяем наличие веточек, изменившихся от прошлого запуска
            $res=$this->database->select('select * from ' . $this->table_name . ' where `id`=?d',$param['id']);
            unset($param['id']);
            if(!empty($res)){
                $id=$res[0]['id'];
                $past=array();
                foreach($res as $v){
                    $name=&$v['name'];
                    if(isset($param[$name])){
                        if(is_array($param[$name]))
                            $param[$name]=serialize($param[$name]);
                        // параметр есть!
                        $tname=$this->_name($param[$v['name']],$name);
                        if($v[$tname]!=$param[$name]){
                            // update
                            $key=array('ival'=>null,'sval'=>null,'tval'=>null);
                            $key[$tname]=$param[$name];
                            $this->database->select('update ' . $this->table_name . '
                                set ?a where `id`=?d and `name`=?;',$key,$id,$name);
                        }
                        unset($param[$name]);
                    } else {
                        // удаляем отсутствующие
                        mysql_query('delete from ' . $this->table_name . '
                            where `id`=?d and `name`=?;',$id,$name);
                    }
                };
            // вставляем оставшиеся.
                foreach($param as $k=>$v) {
                    if(is_array($v))
                        $v=serialize($v);
                    if(!empty($v)){
                        mysql_query(
                        'insert into ' . $this->table_name . ' (id,name,'.$this->_name($v,$k).') values (
                            ?d,?,?
                        )',$id,$k,$v);
                    }
                };
                return $id;
            }
        }
        
        // вставляем оставшиеся записи, не вставленные в прошлой жизни
        reset($param);
        if(list($key, $val) = each($param)){
            $result=mysql_query('insert into ' . $this->table_name . ' (name,ival,sval,tval) values '.
                '("'.mysql_real_escape_string($key).'",'.$this->_db_insert($val,$key).')'
            );
            $this->req_cnt++;
            if (!$result) {
                   debug('Invalid query: '.mysql_error()
                       ."\nWhole query: ".$sql);
            };
            $id=mysql_insert_id($this->database->link);
            $sql='insert into ' . $this->table_name . ' (id,name,ival,sval,tval) values ';
            $sqlp=array();
            while (list($key, $val) = each($param)) {
                if(is_array($val))
                    $val=serialize($val);
                $sqlp[]='('.$id.',"'
                    .mysql_real_escape_string($key).'",'.$this->_db_insert($val,$key).')';

            }
            if(!empty($sqlp)){
                $result=mysql_query($sql.implode(',',$sqlp),$this->database->link);$this->req_cnt++;
                if (!$result) {
                   debug('Invalid query: '.mysql_error()
                       ."\nWhole query: ".$sql);
                };
            }

        }

           return $id;
    }

}