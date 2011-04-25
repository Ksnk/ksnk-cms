<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ksnk
 * Date: 21.04.11
 * Time: 20:05
 * To change this template use File | Settings | File Templates.
 */
class TestCommand extends CConsoleCommand
{
    function __construct(){
    }

    /**
     * @return void - просто тестировать что под руку подвернулось.
     */
    public function actionInit() {
         $data= new CMultyData();
         print_r($data->readRecord(array('id'=>7)));
    }
    public function actionInit4() {
         $data= new CMultyData();
         print_r($data->writeRecord(array('record'=>'version','version'=>'34','_xx'=>array(1,2,3,4,5),
                                    'yy'=>array('a'=>2,'b'=>3))));
    }
    public function actionInit3() {
         $data= new CMultyData();
         print_r($data->delRecord(array('record'=>'version','version'=>'34')));
    }
    public function actionInit2() {
         $data= new CMultyData();
         print_r($data->delRecord(array('id'=>3)));
    }
    public function actionInit1() {
        $data= new CMultyData();
        print_r($data->readRecord('version'));
        print_r($data->writeRecord(array('record'=>'version','version'=>'12345')));
        print_r($data->writeRecord(array('record'=>'version','version'=>'34')));
        print_r($data->writeRecord(array('record'=>'Syspar','xx'=>'12345','yy'=>'opps')));
        print_r($data->writeRecord(array('type'=>23,'version'=>'12345')));
        print_r($data->readRecord('version'));
    }
}
