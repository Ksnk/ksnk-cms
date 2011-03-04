<?php
/**
 * ������ - ������ ������
 */
//<% point_start('plugin_body') %>

define('ART_TPL',TEMPLATE_PATH.DIRECTORY_SEPARATOR.'articles.tpl');

class articles extends ml_plugin {

	function articles(&$parent){
		parent::ml_plugin($parent);
		if(defined('IS_ADMIN')){
			parent::_init(
				array(
				'title'=>'������'
				,'fields'=>array(
							array('����','date','text_edit'),
							array('�����','author','text_edit'),
							array('���������','title','html_edit'),
							array('�����','text','html_edit'
								,'afilter'=>ppi($this->parent->getPar('article-word-visible'),30)),
							array('','order','win_order'),
					)
				,'base'=>'_articles'
				,'orderbystr'=>' order by `order`'
				,'prefix'=>'ar'
				));
		} else {
			parent::_init(
				array(
				'title'=>'������'
				,'base'=>'_articles'
				));
		}
	}

	function check_data(&$upd,&$res){
		if(empty($res['date']))
			$upd['date']=date('Y/m/d H:i:s');
		else if(isset($upd['date'])){
			$dres=strtotime ($upd['date']);
			if( $dres===-1 || $dres===false)
				$upd['date']=date('Y/m/d H:i:s');
		}
		return true;
	}

	function admin_articles(){
		return
		smart_template(ADMIN_TPL.'#theheader',array(
		'header'=>$this->getPluginName(),
		'data'=>parent::admin_plugin()));
	}

	function do_create($killall=true){
		if(!$this->parent->has_rights(right_WRITE))
			return $this->parent->ffirst('_loginform');
		if($killall){
			$this->database->query('DROP TABLE IF EXISTS ?_articles;');
		}
		$this->database->query("CREATE TABLE ?_articles (
				`id` INT NOT NULL AUTO_INCREMENT ,
				`date` DATETIME NOT NULL ,
				`title` TEXT NOT NULL ,
				`text` TEXT NOT NULL ,
				`author` VARCHAR( 255 ) NOT NULL ,
				`order` int,
				PRIMARY KEY ( `id` )
				);");
	}

	function get_parameters($par){
		$par['list'][]=array('sub'=>'������ - �������� ������','title'=>'���������� ���� ��������� � ������','name'=>'article-word-visible');
		$par['list'][]=array('title'=>'���������� ������ � ������','name'=>'article-onleft');
		$par['list'][]=array('sub'=>'������ - ������ ������','title'=>'���������� �������� �� ��������','name'=>'article-perpage');
		$par['list'][]=array('sub'=>'������ - �����������������','title'=>'���������� �������� �� ��������','name'=>'words-perpage');
	}
	/**
	 * ����� ������ ������ �� �������� �����
	 *  - articles_b - ����� ������������� ������ ������ � ����� ������
	 *  - articles - ����� ������ ������ �� ����������
	 *  - articles+ ID - ����� ����� ������
	 */
	function do_articles($tpl='articles'){
/**
 * ������� ������ ������ �� ����, �� ��������
 * sort - ����������
 * id - ���������� ������
 */
//		echo'xxxx';
		//var_dump(array_slice(debug_backtrace(),0,4));//echo"<hr>";
		$cnt=0;
		parent::setupmenu();
		if($id=ppi($_GET['id'])){
			$tpl='article';
		};
		$sort=pps($_GET['sort'])=='date';
		$perleft=ppi($this->parent->getPar('article-onleft'),3);
		$perpage=ppi($this->parent->getPar('article-perpage'),20);
		$visword=ppi($this->parent->getPar('article-word-visible'),30);
		$limit='';
		if($tpl=="articles_b"){
			$limit=' LIMIT '.$perleft;
		}
		$pages='';
		if($id){
			$res=@$this->database->query('select * from ?_articles where `id`=?',$id);
		} else {
			$sql='select count(*) as cnt from ?_articles;';
			$cnt=$this->database->selectCell($sql);

			$page=ppi($_GET['pg']);
			$orderstr='order by `order` ';
			$res=$this->database->query("select * from ?_articles ".$orderstr.
			$limit.';');
		//	($tpl=="news_b"?' LIMIT '.($page*$perpage).','.$perpage:'').

			$pages=$this->parent->calc_Pages($cnt,$perpage,$page);
		}
		$list=array();
		if (!empty($res))
		foreach($res as $v) {
			if($tpl=="articles_b") {
				// �������� �������� �����
				$s=strip_tags($v['text']);
				if(preg_match('/(?:\S+\s+){'.$visword.'}/',$s,$m)){
					$s=$m[0].' ...';
				}
				$v['text']=$s;
			}
			$list[]=array(
				'id'=>pps($v['id']),
				'date'=>@toRusDate($v['date']),
				'title'=>pps($v['title']),
				'text'=>pps($v['text']),
				'author'=>pps($v['author'])
			);
		}
	//print_r($res);
		return smart_template(ART_TPL.'#'.$tpl
			,array('list'=>$list,'pages'=>$pages));
	}
}