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

    private $sql_cache=array();
    private $sql_lastid=0;

    /**
     * @var string - дефолтное имя сохраняемой таблицы
     */
    public $table_name='{{flesh}}';

    /**
     * @var array - массив полей, по которым будет вестиcь поиски в дальнейшем
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

    private function flush(){
        if(!empty($this->sql_cache)){
            $sql='insert into '. $this->table_name . ' (id,name,ival,sval,tval) values '.
                implode(',',$this->sql_cache);
            //echo $sql;
            self::$db->createCommand($sql)->execute();
            $this->sql_cache=array();
        }
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
        // snatch a database somewhere.
        if(!empty($db))
            self::$db=$db;
        else
            self::$db=Yii::app()->getDb();

    }
    /**
     * конструктор
     * @var array options - массив параметров для установки
     */
    function __destruct (){
        // flood up all parameters at once!
        $this->flush();
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
     public function readRecord($param,$options=null){
         if(empty($options))
            $options=array('cnt'=>2);
         $res=$this->readRecords($param,$options);
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
             $sql_par=array(':id'=>$param['id']);
             $res=self::$db->createCommand('select ival from '.$this->table_name
                                      .' where id=:id and not isNull(ival) and sval="link"')
                     ->queryColumn($sql_par);
             if(!empty($res)){
                foreach($res as $v){
                    $this->delRecord(array('id'=>$v));
                }
             }

             self::$db->createCommand()
                 ->delete($this->table_name,'id=:id',$sql_par);
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
        if(!empty($this->sql_cache)){
            $this->flush();
        }
        // изготовление записи из переданых параметров
        if (!is_array($param))
            $param = array('record' => $param);
        if (!is_array($options))
            $options = array('cnt' => $options);
        if (empty($options['cnt']))
            $options['cnt'] = 6000;
        // строим запрос, если нам не дали строку заранее
        $sql_par=array();
        if (!empty($options['param']))
            $sql_par=$options['param'];

        if (empty($options['sql'])) {
            //0:id,1:name,2:val, !!! 3:node,4:level,5:childes
            $sql = 'SELECT u0.id,u0.name, '. $this->_cellname('',0).' as `value`, u0.sval as type '
                   .(empty($options['from'])?'from '.$this->table_name . ' as u0 ':$options['from'])
                  ;
            $where = array();
           // if (!empty($options['where']) && !is_array($options['where']))
           //     $options['where']=array($options['where']);
            $ind = 1;
            if (empty($param['id'])) {
                if(!empty($options['where_last'])){
                    $where[] = $options['where_last'];
                }
                if(!empty($options['where'])){
                    $sql .= sprintf('LEFT JOIN ' . $this->table_name . ' AS u%1$s ON u0.id = u%1$s.id ',
                                   $ind, $ind);
                    $where[] = sprintf($options['where'],$ind);
                    $ind++;
                }
                foreach ($param as $k => $v) {
                    $sql .= sprintf('LEFT JOIN ' . $this->table_name . ' AS u%1$s ON u0.id = u%1$s.id ',
                                    $ind, $ind);
                    $where[] = sprintf('u%1$s.name = :k%1$s and ' . $this->_cellname($k, $ind) . '= :v%1$s ',
                                       $ind);
                    $sql_par['k'.$ind]=$k;
                    $sql_par['v'.$ind]=$v;
                    $ind++;
                }
                if(!empty($where))
                    $sql .= 'where ' . implode(' and ', $where);
                if(!isset($options['order']))
                    $options['order'][]='u0.id';
                if(!empty($options['order']))
                    $sql .= ' ORDER BY '.implode(', ', $options['order']);
            } else {
                $sql .= 'where u0.id=:id';
                $sql_par['id']=$param['id'];
            }
        } else {
            $sql = $options['sql'];
        }
        //print_r($sql);
        // проверка на дорогах
        //Yii::beginProfile('query');
        $_qresult=null;
        try {
            $_qresult = self::$db->createCommand($sql . pp($options['limit'], ' LIMIT '))->query($sql_par);
        } catch (Exception $e) {
            if($e->errorInfo[1]==1146){
                echo $sql;
                $this->createTable();
                $_qresult = self::$db->createCommand($sql . pp($options['limit'], ' LIMIT '))->query($sql_par);
            }
        }
        //Yii::endProfile('query');

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
                if(!empty($options['fields']))
                foreach($options['fields'] as $v){
                    if (isset($row[$v])) {
                        $result[$v] = $row[$v];
                    }
                }
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
    function writeRecord($param,$wait=true){
        // вставляем новую запись
        unset($param['current'],$param['node'],$param['level'],$param['childs']);
        
        if(isset($param['id'])){
            // заказывается обновление существующей записи
            // проверяем наличие веточек, изменившихся от прошлого запуска
            $res=self::$db->createCommand('select * from ' . $this->table_name . ' where `id`= :id')
                    ->query(array('id'=>$param['id']));
            //print_r($res);
            $id=$param['id'];
           // unset($param['id']);
            if(!empty($res)){
                foreach($res->readAll() as $v){
                    $id=$v['id'];
                    $name=&$v['name'];
                    if(isset($param[$name])){
                        $sql_par=$this->build_par($name,$param[$name],$id);
                        // параметр есть!
                        $tname=$this->_name($param[$v['name']],$name);
                        if($v[$tname]!=$param[$name]){
                            // update
                            self::$db->createCommand('update ' . $this->table_name . ' set '
                                                     .$this->_name($val,$key).'=:val '
                                                     .(!isset($sql_par['type'])?'':', sval=:type')
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
                    if($key!='id')
                        $this->insert_internal($key,$val,$id,true);
                };
                //$this->insert_internal('',$val,$id,false);
                return $id;
            }
        } else {
            if($this->sql_lastid==0){
                $res=self::$db->createCommand('SHOW TABLE STATUS FROM `ODBC` LIKE "'.$this->table_name.'";')->queryRow();
                $this->sql_lastid=$res['Auto_increment'];//1+self::$db->createCommand('select max(id) from '.$this->table_name.';')->queryScalar();
            }
            $id=$this->sql_lastid++;
        }
        
        // вставляем оставшиеся записи, не вставленые в прошлой жизни
        reset($param);
        if(!empty($param)){
            while (list($key, $val) = each($param)) {
                if($key!='id')
                    $this->insert_internal($key, $val,$id,true);
            }
        }

        return $id;
    }

    private function build_par($key,&$val,$id=0){
        $sql_par=array('name'=>$key);
        if(!empty($id))
            $sql_par['id']=$id;
        else {
            Yii::getLogger()->log('empty Id!!!','error');
            $sql_par['id']='null';
        }
        if(is_array($val))
            if($key{0}=='_')
            {
                $sql_par['val']=serialize($val);
                $sql_par['type']='serialize';
            } else {
                if(!empty($id)) $val['_parent']=$id;
                $sql_par['val']=$this->writeRecord($val);
                $val=$sql_par['val'];
                $sql_par['type']='link';
            }
        else
            $sql_par['val']=$val;
        return $sql_par;
    }

    private function insert_internal($key,&$val,$id=0,$wait=false){

        if(empty($key)) return;

        if($wait && !empty($id)){
            $vals=array($id, self::$db->quoteValue($key));
            $sql_par=$this->build_par($key,$val,$id);
            if(in_array($key,$this->special_words)){
                $vals[]='null';$vals[]=self::$db->quoteValue($val);$vals[]='null';
            } else if(is_int($val) || (strlen($val)<12 && ctype_digit($val))){
                $vals[]=$val;$vals[]=(empty($sql_par['type'])?"''":self::$db->quoteValue($sql_par['type']));$vals[]='null';
            } else {
                $vals[]='null';$vals[]=(empty($sql_par['type'])?"''":self::$db->quoteValue($sql_par['type']));$vals[]=self::$db->quoteValue($sql_par['val']);
            }
            $this->sql_cache[]='('.implode(',',$vals).')';
            if(count($this->sql_cache)>100){
                $this->flush();
            }
            return;
        }
        $sql_par=$this->build_par($key,$val,$id);
        self::$db->createCommand('insert into ' . $this->table_name . ' set '
                                 .(empty($sql_par['id'])?'':'id= :id ,')
                                 .' name= :name ,'.$this->_name($val,$key).'=:val '
                                 .(empty($sql_par['type'])?'':', sval=:type'))
                ->execute($sql_par);
        if(!$wait) $this->flush();
    }


    private function _cellname($k,$i){
      if(!empty($k) && in_array($k,&$this->special_words))
          return sprintf('u%1$s.sval',$i);
      return sprintf('if(isNull(u%1$s.ival),if(isNull(u%1$s.tval),u%1$s.sval,u%1$s.tval),u%1$s.ival)',$i);
   }

   private function _name(&$val,&$name){
      if(in_array($name,$this->special_words)) return 'sval';
      if(is_int($val) || (strlen($val)<12 && ctype_digit($val))) return 'ival';
      return 'tval';
   }

}