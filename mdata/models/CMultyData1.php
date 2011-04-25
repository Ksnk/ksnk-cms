<?php
/**
 *
 * тестовый класс для проверки нагрузки на базу
 *
 */
 
class CMultyData1 extends CModel
{
    /**
     * @var CDbConnection установлено явно после инициализации или взято из yii::app{}->getDb()
     */
    private static $db;

    /**
     * @var string - дефолтное имя сохраняемой таблицы
     */
    private $table_name='{{news}}';

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
    {
        $sql = 'SELECT * from ' . $this->table_name .' as u1 ' ;
        if(!empty($options['where'])){
                     
                     $where[] = sprintf($options['where'],$ind);
                     $ind++;
        }
        $sql .= 'where ' . implode(' and ', $where) .' ORDER BY '.(empty($options['order'])?'u1.id':$options['order']);

       // print_r($sql);
        $sql_par=$options['param'];
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
            $_result[]=$row;
        }
        //debug($result);
        if (!empty($_result)) {
            return $_result;
        } else
            return array($param);
    }

    /**
     *  сохранить полную запись
     */
    function writeRecord($param){
        // просто вставляем запись
        self::$db->createCommand('insert ' . $this->table_name . ' set '
                                 .'record=:record,'
                                 .'title=:title,'
                                 .'text=:text,'
                                 .'date=:date')->execute($param);

        $id=self::$db->getLastInsertID();
        return $id;
    }

    private function build_par($key,&$val,$id=0){
        $sql_par=array('name'=>$key);
        if(!empty($id))
            $sql_par['id']=$id;
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