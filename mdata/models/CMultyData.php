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
 * 1023, z,1024,link
 * 1024,_parent,1024
 * 1024, x,2
 * 1024, y,world!!
 *
 */
 
class CMultyData extends CModel
{
    /**
     * @var CDbConnection установлено явно после инициализации или взято из yii::app{}->getDb()
     */
    private static $db;

    /**
     * @var string - дефолтное имя сохраняемой таблицы
     */
    private $table_name='{{flesh}}';

    /**
     * @var array - массив полей, по которым будет вестить поиски в дальнейшем
     * Обязаны быть небольшими.
     */
    private $special_words=array('root','record','name','password','url');

    /**
     * @return array - dont know a while why i need this method...
     */
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
        // flood up all parameters at once!
        if(is_array($options) && !empty($options))
            foreach($options as $k=>$v){
                if(isset($this->$k)){
                    $this->$k=$v;
                }
            }
        // snatch a database somethere.
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
                    'name'=>'string',
                    'ival'=>'integer',
                    'sval'=>'string',
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
     *  найти и обезвредить, вместе с линками
     */
     function delRecord($param){
         if (!isset($param['id']))
             $param=$this->readRecord($param);
         if(!empty($param['id'])){
             $res=self::$db->createCommand('select ival from '.$this->table_name
                                      .' where id=:id and not isNull(ival) and sval="link"')
                     ->queryColumn();
             if(!empty($res)){
                foreach($res as $v){
                    $this->delRecord(array('id'=>$v));
                }
             }

             self::$db->createCommand()
                 ->delete($this->table_name,'id=:id',array(':id'=>$param['id']));
             return true;
         } else
             return false;
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
            $sql = 'SELECT u0.id,u0.name, '. $this->_cellname('',0).' as `value`, u0.sval as type from ' . $this->table_name . ' as u0 ';
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
                $sql .= 'where u0.id=:id';
                $sql_par['id']=$param['id'];
            }
        } else {
            $sql = $options['sql'];
        }
        //print_r($sql_par);
        // проверка на дорогах
        $_qresult=null;
        try {
            $_qresult = self::$db->createCommand($sql . pp($options['limit'], ' LIMIT '))->query($sql_par);
        } catch (Exception $e) {
            if($e->errorInfo[1]==1146){
                $this->createTable();
                $_qresult = self::$db->createCommand($sql . pp($options['limit'], ' LIMIT '))->query($sql_par);
            }
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
            if ($row['type']=='serialize') {
                $result[$row['name']] = unserialize($row['value']);
            } else if ($row['type']=='link') {
                $result[$row['name']] = $this->readRecord(array('id'=>$row['value']));
            } else {
                $result[$row['name']] =$row['value'];
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
     *  сохранить полную запись
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
                foreach($res as $v){
                    $name=&$v['name'];
                    if(isset($param[$name])){
                        $sql_par=$this->build_par($name,$param[$name],$id);
                        // параметр есть!
                        $tname=$this->_name($param[$v['name']],$name);
                        if($v[$tname]!=$param[$name]){
                            // update
                            self::$db->createCommand('update ' . $this->table_name . ' set '
                                                     .$this->_name($val,$key).'=:val '
                                                     .(empty($sql_par['type'])?'':', sval=:type')
                                                     .' where `id`=:id and `name`=:name;')
                                    ->execute($sql_par);
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
                    $this->insert_internal($key,$val,$id);
                };
                return $id;
            }
        }
        
        // вставляем оставшиеся записи, не вставленные в прошлой жизни
        reset($param);
        $id=false;
        if(list($key, $val) = each($param)){
            $this->insert_internal($key, $val);
            $id=self::$db->getLastInsertID();
            while (list($key, $val) = each($param)) {
                $this->insert_internal($key, $val,$id);
            }
        }

        return $id;
    }

    private function build_par($key,&$val,$id=0){
        $sql_par=array('name'=>$key);
        if(!empty($id))
            $sql_par['id']=$id;
        if(is_array($val))
            if($key{0}!='_')
            {
                $sql_par['val']=serialize($val);
                $sql_par['type']='serialize';
            } else {
                if(!empty($id)) $val['_parent']=$id;
                $sql_par['val']=$this->writeRecord($val);
                $sql_par['type']='link';
            }
        else
            $sql_par['val']=$val;
        return $sql_par;
    }

    private function insert_internal($key,&$val,$id=0){
        $sql_par=$this->build_par($key,$val,$id);
        self::$db->createCommand('insert into ' . $this->table_name . ' set '
                                 .(empty($sql_par['id'])?'':'id= :id ,')
                                 .' name= :name ,'.$this->_name($val,$key).'=:val '
                                 .(empty($sql_par['type'])?'':', sval=:type'))
                ->execute($sql_par);
    }


    private function _cellname($k,$i){
      if(!empty($k) && in_array($k,$this->special_words))
          return sprintf('u%1$s.sval',$i);
      return sprintf('if(isNull(u%1$s.ival),if(isNull(u%1$s.tval),u%1$s.sval,u%1$s.tval),u%1$s.ival)',$i);
   }

   private function _name(&$val,&$name){
      if(in_array($name,$this->special_words)) return 'sval';
      if(is_int($val) || (strlen($val)<10 && ctype_digit($val))) return 'ival';
      return 'tval';
   }

}