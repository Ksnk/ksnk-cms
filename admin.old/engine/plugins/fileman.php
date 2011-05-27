<?php
/**
 * ����� ��������� ��������
 */

define ('FILES_TPL','tpl_files');

class fileman extends ml_plugin{

	function fileman(&$parent){
		parent::ml_plugin($parent);
		parent::_init(array(
			'title'=>'����������� �����'
			,'perpage'=>40
			,'columns'=>2
			,'prefix'=>'fl')
		);
	}
	
	function search($s){
		$s=strtolower($s);
		$_SESSION['file_filter']=$s;
		$s=str_replace(array('*','?'),array('%','_'),$s);
		$res=$this->database->query(
			'select * from ?_fbase where name LIKE "'.mysql_real_escape_string(rtrim($s,' %')).'%" order by name'
		);
		
		if(!empty($res)){
			$this->parent->go(
				$this->parent->curl('do').'do=menu&id=fileman&item=3'
			);
		}
		
		return $result;
	}
	
	function do_create($killall=true){
		if(!$this->parent->has_rights(right_WRITE))
			return $this->parent->ffirst('_loginform');
		if($killall){
			$this->database->query('DROP TABLE IF EXISTS ?_fbase;');
		}
		$this->database->query("CREATE TABLE ?_fbase (
`name` VARCHAR( 100 ) NOT NULL ,
`size` INT( 11 ) NOT NULL ,
`info` VARCHAR( 15)  NOT NULL ,
`type` INT( 5 ) NOT NULL ,
PRIMARY KEY ( `name` ),
KEY `type` (`type`)
);");
	}
	
	function do_read($force=false){
		if ($force) 
			$this->parent->delPar('fm_lastScan');
	}
	
	function _read(){
		$cnt=@$this->database->selectCell('select count(*) from ?_fbase');
		if(is_null($cnt)){
			$this->do_create();
			$cnt=$this->database->selectCell('select count(*) from ?_fbase');
		}
		$sql_start="replace into ".TAB_PREF."_fbase (`name`,`size`,`type`) values ";
		if(!$xd=@dir(TMP_DIR)) return;
		$x=array();
		while (false !== ($entry=$xd->read())) {
			if($entry!='.' && $entry!='..'){
				$x[]=TMP_DIR.$entry;
			}
		}
		$xd->close();
		if (count($x)==$cnt) {
			return ;
		}
		$this->parent->setPar('fm_lastScan',time());
		$this->database->select('update ?_fbase set `type`=`type`+1000;');
		$sql_val=array();
		$xcnt=0;
		foreach($x as $v){
			$info='';
			$type=2;
			if(preg_match('/\.jpe?g$|.gif$|.png$/i',$v)){
					$type=1;
			}
			$sqlval[]=sprintf('("%s","%s",%s)',basename($v),@filesize ($v),$type);
			if($xcnt++>1000){
				$this->database->select($sql_start.implode(',',$sqlval));
				$sqlval=array();
				$xcnt=0;
			}		 
		}
		if(!empty($sqlval))
			$this->database->select($sql_start.implode(',',$sqlval));
		
			$this->database->select('delete from ?_fbase where `type`>=1000;');
			
		$res=$this->database->select('create temporary table ?_tmp(
`name` VARCHAR( 100 ) NOT NULL ,
`info` VARCHAR( 15)  NOT NULL ,
`type` INT( 5 ) NOT NULL, 
PRIMARY KEY ( `name` )
);');
		$sql_start="insert into ".TAB_PREF."_tmp (`name`,`info`,`type`) values ";
		$sqlval=array();	
		$res=$this->database->select('select * from ?_fbase where `type`=1 and (`info` is null or `info`="");');
		if(!empty($res)){
			foreach($res as $v){
				$image=@getimagesize (TMP_DIR.$v['name']);
				if($image) {
					$sqlval[]=sprintf('("%s","%s",1)'
						,mysql_real_escape_string(basename($v['name']))
						,$image[0].'x'.$image[1]);
				} else {
					$sqlval[]=sprintf('("%s","",2)',mysql_real_escape_string(basename($v['name'])));
				}
				if(count($sqlval)>1000){
					$this->database->select($sql_start.implode(',',$sqlval));
					$sqlval=array();
				}
			}
			if(count($sqlval)>0){
				$this->database->select($sql_start.implode(',',$sqlval));
			}
			$this->database->select(
				'update ?_fbase as x join ?_tmp as y on x.name=y.name '.
				'set x.info=y.info,x.type=y.type');
		}
		
	}
	
	function do_getFiles(){
		$lasttime=$this->parent->getPar('fm_lastScan',0);
		if($lasttime<time()-1*60) {
			$this->_read() ;
		} else 
			debug($this->parent->getPar('fm_lastScan',0)>time()-1*60?'yes':'no');
		
		$item=ppi($_GET['item'],1);
		$this->do_read();
		
		if($item==3 && isset($_SESSION['file_filter'])){
			$s=str_replace(array('*','?'),array('%','_'),$_SESSION['file_filter']);
			$sqlx='from ?_fbase where name LIKE "'.mysql_real_escape_string(rtrim($s,'%')).'%" order by name';
		} else if($item==2 || $item==4){
			$sqlx='from ?_fbase  where type=1 order by name ';
		} else {
			$sqlx='from ?_fbase  where type=2 order by name ';
		}
		$cnt=$this->database->selectCell('select count(*) '.$sqlx);
		if(!isset($_GET['pg']))
			$_GET['pg'] = 0;
		if($_GET['pg']*$this->perpage>$cnt){
			$_GET['pg']=max(0,$cnt%$this->perpage-1);
		}	
		$result=$this->database->select('select `name` as `url`,`size`,`info` '.$sqlx.
			' LIMIT ?d,?d',
			ppi($_GET['pg'])*$this->perpage,$this->perpage );

		$this->parent->ajaxdata['pages']=$this->parent->calc_Pages($cnt,$this->perpage,ppi($_GET['pg']),3);
		
		if (empty($result)) { 
			$result[]=array('url'=>'','size'=>'','info'=>'');
		}
		$this->parent->ajaxdata['list']=$result;
		return $item;
	}

	function admin_fileman(){
		if(!$this->parent->has_rights(right_WRITE)){
			$this->parent->error('����� ��������������!!!!!');
			return ' ';
		}
		
		if(isset($_POST['ff'])){
			if(!empty($_POST['ff'])){
				foreach($_POST['ff'] as $v){
					$v=urldecode ($v);
					if(detectUTF8($v)){
						$v=iconv('utf-8','cp1251//IGNORE',$v);
					}
					echo($v);
					unlink(TMP_DIR.urldecode ($v));
				}
				$this->do_read(true);
			}	
			$this->parent->go($this->parent->curl());
		}
		$pictures=array();
		$files=array();
		
		if($this->do_getFiles()==4){
			$this->columns=5;
			$this->colsize=8;
		}
		return $this->parent->_tpl('tpl_jfiles','_fileman',array(
			'list'=>$this->parent->ajaxdata['list']
			,'pages'=>$this->parent->ajaxdata['pages']
			,'type'=>ppi($_GET['item'],1)
			,'columns'=>$this->columns
			,'colsize'=>ppi($this->colsize,20)
			,'filter'=>pps($_SESSION['file_filter'])
		));
	}
}

?>