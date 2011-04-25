<?php
/**
 *
 * класс для работы с записями произвольной наполненности
 * запись - одномерный ассоциативный массив
 *
 * некоторые имена свойств имеют специальное значение
 * id - идентификатор записи в таблице.
 * _parent - идентификатор ссылки на поле -предок
 * record - тип поля. param|user
 * name - имена полей, которые обязательно нужно размещать в индесированном поле.
 *
 * array(x=>1,y=>'hello',z=>array(
       x=>2,y=>'world!'
    ))
 * преобразуется в
 * id,name,val
 * 1023, x,1
 * 1023, y,hello
 * 1023, z,
 *
 *
 *
 *
 *
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

/**
 * @var array - массив полей, по которым будет вестить поиски в дальнейшем
 * Обязаны быть небольшими.
 */
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
    function __construct ($options=null,$db=null){
        if(is_array($options) && !empty($options))
        foreach($options as $k=>$v){
            if(isset($this->$k)){
                $this->$k=$v;
            }
        }
        if(!empty($db))
            self::$db=$db;
        else
            self::$db=Yii::app()->getDb();
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

      private function _cellname($k,$i){
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
        // изготовление записи из переданых параметров
        if (!is_array($param))
            $param = array('record' => $param);
        if (!is_array($options))
            $options = array('cnt' => $options);
        if (empty($options['cnt']))
            $options['cnt'] = 6000;
        // строим запрос, если нам не дали строку заранее
        $sql_par=array();
        if (empty($options['sql'])) {
            //0:id,1:name,2:val, !!! 3:node,4:level,5:childs
            $sql = 'SELECT u.id,u.name, '. $this->_cellname('',0).' as `value` from ' . $this->table_name . ' as u0 ';
            $where = array();
            $ind = 1;
            if (empty($param['id'])) {
                foreach ($param as $k => $v) {
                    $sql .= sprintf('LEFT JOIN ' . $this->table_name . ' AS u%1$s ON u0.id = u%1$s.id ',
                                    $ind, $ind);
                    $where[] = sprintf('u%1$s.name = :k%1$s and ' . $this->_cellname($k, $ind) . '= :v%1$s ',
                                       $ind);
                    $sql_par['k'.$ind]=$k;
                    $sql_par['v'.$ind]=$v;
                    $ind++;
                }
                $sql .= 'where ' . implode(' and ', $where) . ' ORDER BY u0.id';
            } else {
                $sql .= 'where `id`=:id' . $param['id'];
                $sql_par['id']=$param['id'];
            }
        } else {
            $sql = $options['sql'];
        }
        print_r($sql_par);
        // проверка на дорогах
        $rcnt = 2;

        while ($rcnt-- > 0) {
           // echo $sql;
            try{
                $_qresult = self::$db->createCommand($sql)->query($sql_par);
            } catch (Exception $e) {
                if($e->errorInfo[1]==1146){
                    $this->createTable();
                    continue;
                }
            }
            $rcnt = 0; // не нужно повторять!
        }

        $_result = array();
        $result = null;
        $id = 0;
       // print_r($_qresult);
        if(!empty($_qresult))
        foreach($_qresult->readAll() as $row) {
            //debug($row);
            if ($row['id'] != $id) {
                if ($options['cnt']-- <= 0)
                    break;
                if (!empty($id)) {
                    $_result[] = $result;
                }
                $id = $row['id'];
                $result = array('id' => $id);
                /**
                 * truly special mode!!!
                if (isset($row[3])) {
                    $result['node'] = $row[3];
                    $result['level'] = $row[4];
                    if (isset($row[5]))
                        $result['childs'] = $row[5];
                } */
            }
             if (!empty($row['value']) && $row['value']{0} == 'a' && $row['value']{1} == ':') {
                $result[$row['name']] = unserialize($row['value']);
            } else {
                $result[$row['name']] = $row['value'];
            }
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
            $res=self::$db->createCommand('select * from ' . $this->table_name . ' where `id`= :id')
                    ->query(array('id'=>$param['id']));
            print_r($res);
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
                            self::$db->createCommand('update ' . $this->table_name . '
                                set '.$this->_name($v,$k).'=:val where `id`=:id and `name`=:name;')
                                    ->execute(array('id'=>$id,'val'=>$param[$name],'name'=>$name));
                        }
                        unset($param[$name]);
                    } else {
                        // удаляем отсутствующие
                        self::$db->createCommand()->delete($this->table_name, '`id`=:id and `name`=:name'
                            ,array('id'=>$id,'name'=>$name));
                    }
                };
            // вставляем оставшиеся.
                foreach($param as $key=>$val) {
                    if(is_array($val))
                        $val=serialize($val);
                    self::$db->createCommand('insert into ' . $this->table_name . ' set '.
                                   'id= :id , name= :name ,'.$this->_name($val,$key).'=:val ')
                            ->execute(array('id'=>$id,'name'=>$key,'val'=>$val));
                };
                return $id;
            }
        }
        
        // вставляем оставшиеся записи, не вставленные в прошлой жизни
        reset($param);
        if(list($key, $val) = each($param)){
            self::$db->createCommand('insert into ' . $this->table_name . ' set '.
                'name= :name ,'.$this->_name($val,$key).'=:val ')->execute(array('name'=>$key,'val'=>$val));
            $id=self::$db->getLastInsertID();
            $sql='insert into ' . $this->table_name . ' (id,name,ival,sval,tval) values ';
            $sqlp=array();
            while (list($key, $val) = each($param)) {
                if(is_array($val))
                    $val=serialize($val);
                self::$db->createCommand('insert into ' . $this->table_name . ' set '.
                               'id= :id , name= :name ,'.$this->_name($val,$key).'=:val ')
                        ->execute(array('id'=>$id,'name'=>$key,'val'=>$val));
            }
        }

        return $id;
    }

}