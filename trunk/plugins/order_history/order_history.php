<?php
/**
 * ������ - ������� �������
 * �������� � ������ �����,
 * � ������� ?_menu ����������� �������� - '�����������'
 *  [TEXT, page - ���������� ������������]->{[TEXT, page->���������� �������],...}
 * � ���� ����������� ������� - "votes" � �������� ������� �����������
 */
//print_r($_REQUEST);

class order_history extends plugin {

    static $STATUS=array(0=>'','active'=>"�������",'closed'=>"���������",'complete'=>"�������") ;
    static $TYPES=array('','����������� ������','���������','������ � �����') ;

    var $table_name='?_orders',
        $perpage=20;


    function __construct($parent){
        plugin::plugin($parent);
		if(defined('IS_ADMIN')){
            $list=array('');
            $i=0;
            foreach(self::$STATUS as $k=>$v)
                if(!empty($k))
                $list[]=array('id'=>$k,'text'=>$v);
		    $this->parent->par['dd_menu'][]=array(
                'name'=>'xstate',
                'list'=>$list
            );
		}
    }
/**
 * �������� �������
 * @return string
 */
	function getPluginName(){
		return 	'������� �������';
	}

/**
 * �������� ������� ������� �������
 * data - ���� ������,
 * user - ID ������������
 * detail - ������������� � ������� ����������� ������
 * status - ������ ������, ���� ����������� � ��������
 * descr - ��������� �������� ������ � ����� � ��������� - ������ �������.
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
  `id` int(11) NOT NULL auto_increment,
  `date` date default NULL,
  `userid` int(11) NOT NULL ,
  `user` varchar(128) ,
  `status` varchar(10) ,
  `cost`  varchar(20) ,
  `type`  varchar(20) ,
  `detail`  int(11) NOT NULL ,
  `descr`  text ,
  PRIMARY KEY  (`id`)
);');
    }

    /**
     *
     * ��������� ������ � ������ � ������� �������
     * @param  $par
     * @return void
     * 
     */
    function save($keys,$id=0){
        $par=array();
        $par['descr']=serialize($keys);

        $par['cost']=pps($keys['cost']);
        $par['user']=pps($keys['username'],$this->parent->user['name']);
        $par['type']=pps($keys['type'],'���������');
        $par['userid']=pps($keys['user'],$this->parent->user['id']);
        $par['date']=pps($keys['date'],date('Y/m/d H:i:s'));
        $par['status']='active';
        if(empty($id)){
            $id=$this->database->query('INSERT INTO '.$this->table_name.' (?#) VALUES(?a);',
		   			array_keys($par),array_values($par));
        } else {
            $this->database->query('update '.$this->table_name.' set ?a where `id`=?;',$par,$id);
        }
        return $id;
    }

    function searchform(&$sql_where,$tpl='tpl_jorders'){
         $form = new form('searchform');
         $par=array();
         if(defined ('IS_ADMIN')){
             $par['users']=$this->database->select('select distinct `userid`,`user` from '.$this->table_name);
         }
         $form->scanHtml($this->parent->_tpl($tpl,'_searchform',$par));//array('users')));

         if($form->handle()){
           // debug($form->var);debug(self::$STATUS[$form->var['status']]);
            $sql_where=array();
             $status=array_keys(self::$STATUS);
            // �������� �������� ������
            if(!empty($form->var['status']) && key_exists($form->var['status'],$status))
                $sql_where[]='`status`="'.$status[$form->var['status']].'"';

            if(!empty($form->var['type']) && key_exists($form->var['type'],self::$TYPES))
                $sql_where[]='`type`="'.self::$TYPES[$form->var['type']].'"';

            if(!empty($form->var['user']))
                $sql_where[]='`userid`="'.ppi($form->var['user']).'"';

            if(!empty($form->var['period'])){
                switch($form->var['period']){
                    case 1: // ������
                                            $sql_where[]='`date`>"'.strtotime ('-1 week').'"';
                                            break;
                    case 2: // �����
                                            $sql_where[]='`date`>"'.strtotime ('-1 month').'"';
                                            break;
                    case 3: // ���
                                            $sql_where[]='`date`>"'.strtotime ('-1 year').'"';
                                            break;

                }
            }
            $sql_where=implode(' and ',$sql_where);
        };
        return $form;
    }

    function order_print($id,$tpl='') {
        if (is_array($id)){
            $par = $id;
            //����� ������� �������� �� �������
        } else {
            $rec=$this->database->selectRow('select * from '.$this->table_name." where id=?;",$id);
            $par=unserialize($rec["descr"]);
        }
        $par=array_merge($this->parent->parameters,$par);
        debug($par);
        if (empty($tpl))
            return $this->parent->_tpl('tpl_jorders','_print'.ppi($par['cust_order'],2),array('order'=>$par));
        else
            return $this->parent->_tpl('tpl_jorders','_'.$tpl,array('order'=>$par));
    }

    function get_List() {
        $this->parent->sessionstart();
        //�������� ����� ����������
        // ������ - �������
        $sql_where='';
        $sql_order='date DESC';
        $form=$this->searchform($sql_where);
        // ������ - ��������� ����������

        // ���������� ������
        $cnt=$this->database->selectCell('select count(*) from '.$this->table_name.pp($sql_where,' where ').';');
        $page=0;
        if(isset($_GET['pg'])){
            if(ppi($_GET['pg'])*$this->perpage>$cnt){
                $page=max(0,$cnt%$this->perpage-1);
            }
        }
        $result=$this->database->select(
            'select * from '.$this->table_name
            .pp($sql_where,' where ')
            .pp($sql_order,' order by ')
            .sprintf(' LIMIT %d,%d',$page*$this->perpage,$this->perpage )
        );

        $pages= $this->parent->calc_Pages($cnt,$this->perpage,$page,3);

        return
        $form->getHtml(' ').
        $this->parent->_tpl('tpl_jorders','_orderlist',array(
            'data'=>$result
            ,'pages'=>$pages
        ));
    }

    function admin_order_history(){
		if(!$this->parent->has_rights(right_WRITE))
			return $this->parent->ffirst('_loginform');
		//
        if(isset($_POST['ff'])){
			if(!empty($_POST['ff'])){
                $key=array();
				foreach($_POST['ff'] as $v){
                    if(!empty($v) && ctype_digit($v))
                        $key[]=$v;
				}
                if(!empty($key))
                    $this->database->query('delete from '.$this->table_name.' where id in ('.implode(',',$key).');');
			}
			$this->parent->go($this->parent->curl());
		}

        // ������ - �������
        $sql_where='';
        // ������ - ������� ����������
        $sql_order='';

        $form=$this->searchform($sql_where,'tpl_jorderhistory');
        // ��������� ����� ������
        if($form->handle()){
            $sql_where=array();
            if (!empty($form->var["status"])){
                $status=array_keys(self::$STATUS);
                $sql_where[]='`status`="'.$status[$form->var["status"]].'"';
            }
            if (!empty($form->var["type"])){
                $sql_where[]='`type`="'.self::$TYPES[$form->var["type"]].'"';
            }
            if (!empty($form->var["period"])){
                switch ($form->var["period"]){
                    case 1:// ������
                        $time=strtotime("-1 week");
                        break;
                    case 2:// �����
                        $time=strtotime("-1 month");
                        break;
                    case 3:// ���
                        $time=strtotime("-1 year");
                        break;
                }
                $sql_where[]='`date`>"'.date(DATE_ATOM,$time).'"';
            }
            if(is_array($sql_where))
               $sql_where=implode(' and ',$sql_where);
        } else if(!empty($_POST) ){

            foreach($_POST as $k=>$v){
                if (preg_match('/^oh_(\w+)_(\d+)$/',$k,$m)){
                    if($m[1]=='status'){
                        $this->database->query('update '.$this->table_name.' set `status`=? where id=?',$v,$m[2]);
                    }
                }
            }
            //$this->save(array('userid'=>$_POST['newuser'],'file'=>$_POST['newfile'],'descr'=>$_POST['newdescr']));
            $this->parent->go($this->parent->curl());
        }

        $this->parent->menu['head']=array('MAIN','_modules',$this->getPluginName(),get_class($this));

        // ���������� ������
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
        $users=$this->database->select(
            'select distinct `userid`,`user` from '.$this->table_name.
            ' order by user');
        

        $pages= $this->parent->calc_Pages($cnt,$this->perpage,ppi($_GET['pg']),3);

        $this->parent->ajaxdata['pages']=$pages;

        return

        smart_template(array(ADMIN_TPL,'theheader'),array(
            'header'=>$this->getPluginName(),
            'data'=>//$form->getHtml(' ').
             $this->parent->_tpl('tpl_jorderhistory','_adminlist',array(
                'users'=>$users,
                'data'=>$result
                ,'pages'=>$pages
             ))
           //  .$this->parent->ffirst('do_siteparam','basket')
        ));

	}

}
?>