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
    public function actionIndex($type, $limit=5) {

     }
    public function actionInit() {
        $data= new CMultyData();
         echo($data->readRecord('version'));
    }
}
