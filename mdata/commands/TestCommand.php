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
        $data= new CMultyData(array('table_name'=>'test'));
        print_r($data->writeRecord(array('record'=>'version','version'=>'12345')));
        print_r($data->writeRecord(array('record'=>'version','version'=>'34')));
        print_r($data->writeRecord(array('record'=>'Syspar','xx'=>'12345','yy'=>'opps')));
        print_r($data->writeRecord(array('type'=>23,'version'=>'12345')));
        print_r($data->readRecord('version'));
    }
}
