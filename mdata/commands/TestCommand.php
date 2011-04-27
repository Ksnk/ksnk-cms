<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ksnk
 * Date: 21.04.11
 * Time: 20:05
 * To change this template use File | Settings | File Templates.
 */

function random_string($l = 10){
    shuffle(TestCommand::$c);
    return substr(implode (TestCommand::$c),0,$l);
}


class TestCommand extends CConsoleCommand
{

    public static $c;

    private function status(){
        $dbStats = Yii::app()->db->getStats();
        echo '
query executed: '.$dbStats[0].' (for '.round($dbStats[1], 5).' sec)';

        $memory = round(Yii::getLogger()->memoryUsage/1024/1024, 3);
        $time = round(Yii::getLogger()->executionTime, 3);
        echo 'Memory usage: '.$memory.' Mb
';
        echo 'Time spent: '.$time.' s
';

    }

    function __construct(){
        self::$c=str_split(" ABCDEFGHIJKLMNOPQRS TUVWXYZabcdefgh ijklmnopqrs tuvwxwz012345 6789",1);
    }

    function actionNewsGetLast(){
        // выдать несколько ньюсов
        $news=new CNews();
        echo count($news->getLastNews()).'
';
        $this->status();
    }

    function actionNewsGet($id=10193){
         // выдать несколько ньюсов
         $news=new CNews();
         print_r($news->getNewsById($id));
         for ($i=0;$i<50;$i++)
             $news->getNewsById($id);
         $this->status();
     }

    function actionReadNews(){
         // выдать несколько ньюсов
         $news=new CNews();
         echo count($news->getNews(mktime(20, 00, 0, 3, 24, 2000),mktime(20, 49, 0, 4, 25, 2011)));
         for($i=0;$i<100;$i++)
             $news->getNews(mktime(20, 00, 0, 3, 24, 2000),mktime(20, 49, 0, 4, 25, 2011));
         $this->status();
     }

    function actionNewsCreate100500(){
        /**
         * for($i=0;$i<10000;$i++) // MyIsam
         * query executed: 298 (for 0.26599 sec)Memory usage: 2.676 Mb
         *  Time spent: 24.427 s
         *
         */
        // создание множества ньюсов
        $news=new CNews();
        //$news->indexes(false);
        for($i=0;$i<10000;$i++){
            $date=time()-rand(0,5* 365)* 24 * 60 * 60;
            $newsbody=array(
                'title'=>$date.' '.random_string(5+rand(0,10)),
                'text'=>$date.' '.random_string(100+rand(0,1024)),
                'date'=>$date
            );
            $news->addNews($newsbody);
        }
        //$news->indexes();
        $this->status();
    }
    function actionNewsIndexes(){
        // создание множества ньюсов
        $news=new CNews();
        $news->indexes();
    }

    /**
     * @return void - просто тестировать что под руку подвернулось.
     */
    public function actionInit() {
         $data= new CMultyData();
         print_r($data->delRecord(array('id'=>16)));
    }
    public function actionInit5() {
         $data= new CMultyData();
         print_r($data->readRecord(array('id'=>10193)));
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
