<?php
/**
 * плагин - Оптовые покупатели
 * Для посетителей сайта с ролью "оптовый покупатель"
 * -- при первох входе на сайт перекидываем на страницу "оптовые покупатели"
 * -- страница содержит список файлов, доступных для загрузки именно этому пользователю
 */
//print_r($_REQUEST);

class customers extends ml_plugin {

    var $table_name='?_customers',
        $perpage=20;

/**
 * название плагина
 * @return string
 */
	function getPluginName(){
		return 	'Файлообменник';
	}

    function onJustLogin(){
        if($this->parent->user['right']['*']==129){
            $this->parent->go('',$this->parent->getRootUrl('customers'));
        }
        return false;
    }

    function do_customers(){
        ml_plugin::setupmenu();
        if (defined('SECOND_TPL')) $this->parent->tpl=SECOND_TPL;
		        
        //вывод списка файлов, закрепленных для пользователя
        $pages=array();
        $myuser=array();

//        $form=$this->searchform($sql_where,'tpl_jorderhistory');

        $result=$this->get(&$pages,&$myuser,$_SESSION['USER_ID']);
 //debug($result);
        return 
        $this->parent->_tpl('tpl_jorders','_customer_list',array(
                'data'=>$result
                ,'users'=>$myuser
                ,'pages'=>$pages
            ));
    }

/**
 * создание таблицы истории заказов
 * data - дата заказа,
 * user - ID пользователя
 * detail - идентификатор с точными параметрами заказа
 * status - статус заказа, если понадобится в будудщем
 * descr - текстовое описание заказа с ценой и табличкой - список товаров.
 *
 * @param bool $killall
 * @return mixed|null
 */
    function do_create($killall=true){
		if(!$this->parent->has_rights(right_WRITE))
			return $this->parent->ffirst('_loginform');
		if($killall){
			$this->database->query('DROP TABLE IF EXISTS '.$this->table_name);
		}
		$this->database->query('CREATE TABLE '.$this->table_name.' (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY,
`userid` INT( 11 ) NOT NULL ,
`filename` VARCHAR( 255 ) NOT NULL ,
`filedescr` VARCHAR( 255 ) NOT NULL ,
INDEX ( `userid` )
);');
    }

    /**
     *
     * сохранить запись о заказе в истории заказов
     * @param  $par
     * @return void
     */
    function save($keys,$id=0){
        $par=array();
        $par['userid']=pps($keys['userid']);
//        debug($keys);
        $par['filename']=pps($keys['file']);
        if(empty($keys['descr']))
            $par['filedescr']=basename($par['filename']);
        else
            $par['filedescr']=$keys['descr'];

        if(empty($id)){
            $this->database->query('INSERT INTO '.$this->table_name.' (?#) VALUES(?a);',
		   			array_keys($par),array_values($par));
        } else {
            $this->database->query('update '.$this->table_name.' set ?a where `id`=?;',$par,$id);
        }

    }

    /**
     *
     * получить список
     * @param  $par
     * @return void
     */
    function get(&$pages,&$myuser,$par=''){
        // запрос - условие
        if(empty($par))
            $sql_where='';
        else {
            $sql_where='`userid`='.$par;
        }
       // запрос - порялодок сортировки
        $sql_order='userid, filename';
        // страничный сервис
        $cnt=$this->database->selectCell('select count(*) from '.$this->table_name.pp($sql_where,' where ').';');
        if(!isset($_GET['pg']))
            $_GET['pg'] = 0;
        if($_GET['pg']*$this->perpage>$cnt){
            $_GET['pg']=max(0,$cnt%$this->perpage-1);
        }
        $result=$this->database->select('select * from '.$this->table_name
                                        .pp($sql_where,' where ')
                                        .pp($sql_order,' order by ')
                                        .' LIMIT ?d,?d',
                        ppi($_GET['pg'])*$this->perpage,$this->perpage );

        // список пользователей
        if(empty($this->optusers)){
            $users=$this->parent->export('users','getUserList');
            $this->optusers=&$users;
        } else {
            $users=&$this->optusers;
        }
        $myuser=array();$xuser=array();
        //debug($users);
        foreach($users as $user){
            if(ppi($user['right']['*'])==129){
                $myuser[]=$user;
                $xuser[$user['id']]=$user['name'];
            }
        }
       // debug($xuser);
        foreach($result as $k=>$v){
            $result[$k]['name']=$xuser[$v['userid']];
        }
        $pages= $this->parent->calc_Pages($cnt,$this->perpage,ppi($_GET['pg']),3);
        return $result;
    }

    function searchform(&$sql_where,$tpl='tpl_jorders'){
         $form = new form('searchform');
         $par=array();
         if(defined ('IS_ADMIN')){
             $par['users']=$this->database->select('select distinct `userid` from '.$this->table_name);
         }
         $form->scanHtml($this->parent->_tpl($tpl,'_searchform',$par));//array('users')));

         if($form->handle()){
            debug($form->var);debug(self::$STATUS[$form->var['status']]);
            $sql_where=array();
            // изменяем критерии поиска
            if(!empty($form->var['status']) && key_exists($form->var['status'],self::$STATUS))
                $sql_where[]='`status`="'.self::$STATUS[$form->var['status']].'"';

            if(!empty($form->var['type']) && key_exists($form->var['type'],self::$TYPES))
                $sql_where[]='`type`="'.self::$TYPES[$form->var['type']].'"';

            if(!empty($form->var['user']))
                $sql_where[]='`userid`="'.ppi($form->var['user']).'"';

            if(!empty($form->var['period'])){
                switch($form->var['period']){
                    case 1: // неделя
                                            $sql_where[]='`date`>"'.strtotime ('-1 week').'"';
                                            break;
                    case 2: // месяц
                                            $sql_where[]='`date`>"'.strtotime ('-1 month').'"';
                                            break;
                    case 3: // год
                                            $sql_where[]='`date`>"'.strtotime ('-1 year').'"';
                                            break;

                }
            }
            $sql_where=implode(' and ',$sql_where);
        };
        return $form;
    }

    function admin_customers(){
		if(!$this->parent->has_rights(right_WRITE))
			return $this->parent->ffirst('_loginform');
		// обработка удаения
        if(isset($_POST['delete'])){
            $x=implode(',',$_POST['ff']);
            if(preg_match('/^[\d,]*$/',$x)){
                $this->database->query('delete from '.$this->table_name.' where `id` in ('.$x.')');
            }
            $this->parent->go($this->parent->curl());
        }
        // обработка новой записи
        if(!empty($_POST['newuser']) && !empty($_POST['newfile'])){

            $this->save(array('userid'=>$_POST['newuser'],'file'=>$_POST['newfile'],'descr'=>$_POST['newdescr']));
            $this->parent->go($this->parent->curl());
        }


        $this->parent->menu['head']=array('MAIN','_modules',$this->getPluginName(),get_class($this));

        $pages=array();
        $myuser=array();

//        $form=$this->searchform($sql_where,'tpl_jorderhistory');

        $result=$this->get(&$pages,&$myuser);

        return
        smart_template(array(ADMIN_TPL,'theheader'),array(
            'header'=>$this->getPluginName(),
            'data'=>//$form->getHtml(' ').
             $this->parent->_tpl('tpl_jorderhistory','_admincust',array(
                'data'=>$result
                ,'users'=>$myuser
                ,'pages'=>$pages
            ))
        ));
	}

}
?>