<?php
/**
 * модель для работы с параметрами в приложении.
 *
 * Предположения :
 * - количество параметров невелико и вполне влезает в память.
 * - глюк с сохраннением в деструкторе решен
 *
 * Параметры - ассоциативный массив
 * Хранение параметров - MultyData
 * User: Сергей
 * Date: 16.04.11
 * Time: 15:44
 * класс для работы с параметрами из любого места
 * @usage
 * $param = new CParameters(array(table=>'{flesh}','root'=>'Syspar'))
 * $param = newCParameters('Syspar')
 * $param->get()
 * $param->set()
 */

class CParameters extends CModel
{

    /**
     * @var CMultyData
     */
    public static $store;

    /**
     * @var Array - весь комплект параметров приложения.
     */
    private $all;

    /**
     * @var bool - флаг - было или нет изменение полей массива
     */
    private $changed = false;

    /**
     * конструктор класса
     * @param  $tablename
     * @param  $root
     */
    function __construct($tablename,$root){
        $this->store=new CMultyData(array('table'=>$tablename));
        $this->all=$this->store->readRecord($root);
    }

    /**
     * деструктор класса. Сохранение, если было изменение
     * @param  $tablename
     * @param  $root
     */
    function __construct($tablename,$root){
        if($this->changed){
            $this->store->writeRecord($this->all);
        }
    }

    /**
     * @static
     * @param string $tablename - имя таблицы для хранения параметров
     * @param string $root - корневой элемент 
     * @return void
     */
    static function getStore($tablename='{flesh}',$root='SysPar'){

    }

    /**
     *  функции работы с системными параметрами
     */
        function read(){
            CParameters::$all=$this->readRecord('SysPar');
        }

    /**
     * @param  string $name - имя параметра
     * @param  null $def - значение по умолчанию
     * @return string
     */
        function get($name,$def=null){
            if(empty(CParameters::$all)){
                $this->read_Parameters();
            }
            if(empty($this->parameters))
                return $def;
            //*--*/print_r($this->parameters);
            if(array_key_exists($name,$this->parameters))
                return $this->parameters[$name];
            else
                return $def;
        }

    /**
     * @param  string $name - имя параметра для установки
     * @param  $val
     * @param  $type
     * @return void
     */
        function set($name,$val,$type=-1){
            if(empty($this->parameters)){
                $this->read_Parameters();
            }
            if(pps($this->parameters[$name])!=$val){
                $this->parameters[$name]=$val;
                $this->writeRecord($this->parameters);
                if(!isset($this->parameters['id']))
                    $this->read_Parameters();
            }
        }

    /**
     * @param  string $name - имя параметра для удаления
     * @return void
     */
        function del($name){
            unset($this->parameters[$name]);
            $this->writeRecord($this->parameters);
        }


}
