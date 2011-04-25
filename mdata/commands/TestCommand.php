<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ksnk
 * Date: 21.04.11
 * Time: 20:05
 * To change this template use File | Settings | File Templates.
 */

function random_string($l = 10){
    $s='';
    $c = " ABCDEFGHIJKLMNOPQRS TUVWXYZabcdefgh ijklmnopqrs tuvwxwz012345 6789";
    for(;$l > 0;$l--) $s .= $c{rand(0,strlen($c))};
    return str_shuffle($s);
}


class TestCommand extends CConsoleCommand
{
    function __construct(){
    }

    function actionNewsGet(){
        // выдать несколько ньюсов
        $news=new CNews();
         echo count($news->getNews(mktime(20, 00, 0, 3, 24, 2011),mktime(20, 49, 0, 4, 25, 2011)));
        print_r(Yii::app()->db->getStats() );
    }

    function actionNewsCreate100500(){
        // создание множества ньюсов
        $news=new CNews();
        for($i=0;$i<4000;$i++){
            $newsbody=array(
                'title'=>random_string(5+rand(0,10)),
                'text'=>random_string(100+rand(0,1024)),
                'date'=>time()-rand(0,5* 365)* 24 * 60 * 60
            );
            $news->addNews($newsbody);
        }
    }

    /**
     * @return void - просто тестировать что под руку подвернулось.
     */
    public function actionInit() {
         $data= new CMultyData();
         print_r($data->delRecord(array('id'=>11)));
    }
    public function actionInit5() {
         $data= new CMultyData();
         print_r($data->readRecord(array('id'=>7)));
    }
    public function actionInit4() {
         $data= new CMultyData();
         print_r($data->writeRecord(array('record'=>'version','version'=>'34'
                                         ,'_xx'=>array(1,2,3,4,5)
                                         ,'yy'=>array('a'=>2,'b'=>3)
                                    )
                 )
         );
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
