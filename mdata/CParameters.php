<?php
/**
 * 
 * модель для работы с параметрами в приложении.
 * НЕ СИНГЛТОН!!!
 *
 * Предположения :
 * - количество параметров невелико и вполне влезает в память.
 * - глюк с сохраннением в деструкторе решен
 *
 * Не нужно вызывать конструктор напрямую.
 *
 * Параметры - ассоциативный массив
 * Хранение параметров - MultyData
 * Date: 16.04.11
 * Time: 15:44
 *
 * @usage
 * $param = CParameters::instance(array(table=>'{flesh}','root'=>'Syspar'));
 * $param = CParameters::instance('Syspar');
 * $param = CParameters::instance();
 * $param->get();
 * $param->set();
 * 
 */

class CParameters extends CComponent
{

    /**
     * @var CMultyData
     */
    private $store;

    /**
     * @var string - идентификатор для операции кэширования
     */
    private $cache_id;

    /**
     * @var Array - весь комплект параметров приложения.
     */
    private $all;

    /**
     * @var bool - флаг - было или нет изменение полей массива
     */
    private $changed = false;

    /**
     * внешний красивый герттер класса параметров
     *
     * @static
     * @param string $tablename - имя mf,kbws базы данных
     * @param string $root - имя корневого элемента
     * @return
     */
    static function instance($tablename='{flesh}',$root='SysPar'){
        static $cache=array();
        $x=$tablename.'#'.$root;
        if(!isset($cache[$x])){
            $cache[$x]= new CParameters($tablename,$root,$x);
        }
        return $cache[$x];
    }

    /**
     * конструктор класса
     * @param  $tablename
     * @param  $root
     */
    function __construct($tablename,$root,$cache_id){
        $this->store=new CMultyData(array('table'=>$tablename));
        $this->cache_id=$cache_id;
        $this->all=Yii::app()->cache->get($cache_id);
        if (empty($this->all)){
            $this->all=$this->store->readRecord($root);
        }
    }

    /**
     * деструктор класса. Сохранение, если было изменение
     * @param  $tablename
     * @param  $root
     */
    function __destruct(){
        if($this->changed){
            $this->store->writeRecord($this->all);
        }
        $cache[$this->cache_id]=Yii::app()->cache->set($this->cache_id,$this->all);
    }

    /**
     * @param  string $name - имя параметра
     * @param  null $def - значение по умолчанию
     * @return string
     */
        function get($name,$def=null){
            if(array_key_exists($name,$this->all))
                return $this->all[$name];
            else
                return $def;
        }

    /**
     * @param  $name - имя параметра для установки либо массив параметров
     * @param  $val
     * @param  $type
     * @return void
     */
        function set($name,$val,$forcewrite=false){
            if(is_array($name)) {
                foreach($name as $k=>$v)
                    $this->set($k,$v);
                return ;
            }

            if($this->all[$name]!=$val){
                $this->all[$name]=$val;
                $this->changed=true;
                if($forcewrite)
                    $this->store->writeRecord($this->all);
            }
        }

    /**
     * @param  string $name - имя параметра для удаления
     * @return void
     */
        function delete($name,$forcewrite=false){
            unset($this->all[$name]);
            $this->changed=true;
            if($forcewrite)
                $this->store->writeRecord($this->all);
        }


}
