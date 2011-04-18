<?php
/**
 * ����� ��� ������ � ����������� � ����������.
 * ��������� - ������������� ������
 * �������� ���������� - MultyData
 * User: ������
 * Date: 16.04.11
 * Time: 15:44
 * ����� ��� ������ � ����������� �� ������ �����
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
     *  ������� ������ � ���������� �����������
     */
        function read(){
            CParameters::$all=$this->readRecord('SysPar');
        }

    /**
     * @param  string $name - ��� ���������
     * @param  null $def - �������� �� ���������
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
     * @param  string $name - ��� ��������� ��� ���������
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
     * @param  string $name - ��� ��������� ��� ��������
     * @return void
     */
        function del($name){
            unset($this->parameters[$name]);
            $this->writeRecord($this->parameters);
        }


}
