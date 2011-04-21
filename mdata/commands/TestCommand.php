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
    public function actionInit() {
        $data= new CMultyData(array('table_name'=>'test'));
        echo 'hello!';
        echo($data->readRecord('version'));
    }
}
