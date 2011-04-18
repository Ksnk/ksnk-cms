<?php
/**
 *  ласс дл€ работы с параметрами в приложении.
 * ѕараметры - ассоциативный массив
 * ’ранение параметров - MultyData
 * User: —ергей
 * Date: 16.04.11
 * Time: 15:44
 * класс дл€ работы с параметрами из любого места
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
     * @var Array
     */
        public static $all;

    /**
     *  функции работы с системными параметрами
     */
        function read(){
            CParameters::$all=$this->readRecord('SysPar');
        }

    /**
     * @param  string $name - им€ параметра
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
     * @param  string $name - им€ параметра дл€ установки
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
     * @param  string $name - им€ параметра дл€ удалени€
     * @return void
     */
        function del($name){
            unset($this->parameters[$name]);
            $this->writeRecord($this->parameters);
        }


}
