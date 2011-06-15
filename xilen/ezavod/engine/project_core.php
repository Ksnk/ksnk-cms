<?php

setlocale(LC_ALL ,'ru_RU.CP1251');

require_once('katalog.php');

define('KATALOG_INTO_MENU',false);

define ('USER_TYPE',false);

define('SECOND_TPL','tpl_second');

define ('QA_WITH_THEME',true);

define('SITE_CREATE_SCENARIO',5);

define('ANCHOR_STORE',false); // ��������� ANCHORS, �� �� ���������� �� � ����

//define('ARTICLE_WIDTH_IMAGE',true);

$GLOBALS['opt_array']=array(type_NEWTEXTPIC,type_LINKS,type_TABLE,type_ANCHOR,type_GALLERY,type_GALLERY2,type_LINE);
// 
class airis_users extends users {
	function airis_users(&$parent){
		parent::users($parent);
		$this->$fields=array(
			'name'=>'��� ������������',
			'password'=>'������ ������������',
			'cust_PHONE'=>'�������',
			'right'=>false, // �������������� ���������
			'type'=>false, // �������������� ���������
			'id'=>false, // �������������� ���������
			'record'=>false, // �������������� ���������
			'cust_TYPE'=>USER_TYPE,
			'cust_ADDRESS'=>'��. �����',
			'cust_EMAIL'=>'E-mail',
			'cust_FIO'=>'���������� ����',
			'cust_INN'=>'���',
			'cust_KPP'=>'���',
			'cust_OGRN'=>'����',
			'cust_DIRECTOR'=>'��� ���-���������',
			'cust_ORGANISATION'=>'�������� �����������',
			'cust_SCHET'=>'� �����',
			'cust_BANK'=>'�������� �����',
			'cust_BANK_BIK'=>'��� �����',
			'cust_BANK_INN'=>'��� �����',
			'cust_BANK_KPP'=>'��� �����',
			'cust_CORSCHET'=>'� ������������������ �����',
//			'cust_MANAGER'=>'<span class="red">e-mail ��������� �����</span>',
		);		
	}
}

$EXPORTS['katalog']='Toyhobby_katalog';

/*<%=insert_point('plugin_body'); %>*/

// ���������� �� ��������




class Toyhobby_csv extends csv {
/**
 * ���������������� ����..
 *
 * @param engine $parent
 * @return massmail
 */
	function Toyhobby_csv(&$parent){
		parent::ml_plugin($parent);
		parent::_init(
		array(
		'fields'=>array(
				array('�������','articul','csvfields'=>array('�������')),
				array('������������','name','csvfields'=>array('������������')),
                array('��������������','the_href')),
				array('��. ���.','unit','csvfields'=>array('��. ���������', '������� ���������')),
                array('����','cost','csvfields'=>array('����')),
                array('�����.','ostatok','csvfields'=>array('�������', '�����.')),
			)
		);
		$this->defpattern=array(
			'articul','name','cost'
		);
	}
	function handle($code,&$pattern){
		switch ($code){
			case katalog_TMP_COMPLETE:
				$this->database->select('update ?_katalog set `ostatok`=0');
				$this->database->select('update ?_temp set `ostatok`=1');
				$pattern[]='ostatok';
				break;
			case katalog_UPDATING:
				// �������� � ������ ������� � change
				$pattern=preg_replace('/;&/',' and x.`change`<>1;',$pattern);
				break;
		}
	}
}
$EXPORTS['csv']='Toyhobby_csv';

class Toyhobby_basket extends basket {
	function do_basket() {
		//debug($this);
		$this->pluginname='�������';
		ml_plugin::setupmenu();
		$res=$this->recalc();
        $x='';
		if($this->inumber()){
			$_COOKIE['perpage']=40; //XXX: !!!

			$katalogue=&new xKatalogue();
			$x.= $katalogue->getText($keys,$this->basket_data(),'�����������');
		}
 		return $x.$this->parent->_tpl('tpl_jelements','_basket_btn',$res);

	}
}

$EXPORTS['basket']='Toyhobby_basket';


class xToyhobbyKatalogue extends xKatalogue {
	
	function getText(&$keys,$ids=0,$button_val='',$tpl='xcatalogue'){
		global $engine;
		if(empty($ids))$ids=$this->v['id'];
		if(!is_array($ids)){
			$real_id=$ids;
			$ids=array($ids=>'');
		} else  {
			$x=array_keys($ids);
			$real_id=$x[0];
		}
		//debug($ids);
		
		$engine->handle('katalog');
		$table=array();
		foreach($ids as $id=>$kid){
			$this->havedata=false;
		
			$pages=array();
			$data=array();
			$headers=array();
			$perpage=0;
			$par=array('pages'=>&$pages,
				'headers'=>&$headers,
				'perpage'=>&$perpage,
				'data'=>&$data);
			$engine->export('katalog','get_category',$id,$par);
			$x=0;
			if(!empty($data)){
                $basket=basket::getStore()->get();

			foreach($data as $k=>$v){
				$data[$k]['even']=(($x=1-$x)==0);
				$data[$k]['sdescr2']=strip_tags(pps($v['descr2'],$v['descr']));
				if (!empty($basket[ppi($v['xid'])]))
					$data[$k]['value']=$basket[ppi($v['xid'])]['n'];
				
			};
			$this->havedata=true;
		
			}
			$theaders='';$i=1;
			
			$hh=array('��������'=>'edism','���������� ����'=>'descr1',
				'������ ��������'=>'descr2',
				'�������'=>'descr3',
				'���'=>'descr4',
				'�������'=>'descr5',
				'����'=>'descr6'
			);			
			
			if(!empty($data)){
                $basket=basket::getStore()->get();
			foreach($data as $k=>$vv){
				$data[$k]['xdata']=array();	

				$res=$vv;
				$pic= &new xPic();
				$pic->v['pic_small']=$vv['pic_small'];
				$pic->v['pic_big']=$vv['pic_big'];
				$data[$k]=array_merge($data[$k],$pic->getData());
				
				if (!empty($basket[ppi($vv['xid'])]))
					$data[$k]['value']=$basket[ppi($vv['xid'])]['n'];
                
				foreach($hh as $kk=>$vvv){
					$vv[$vvv]=trim($vv[$vvv],'/');
					if (strpos($vv[$vvv],'/')!==false){
						$opt=explode ('/',$vv[$vvv]);
						foreach($opt as $key=>$val){
							$opt[$key]=array('value'=>$val,'txt'=>$val);
						}
						$data[$k]['xdata'][]=array(
							'title'=>$kk,
							'select'=>array(
								'class'=>($vvv=='descr6'?'widthfix':'widthsel'),
								'name'=>$vvv,
								'id'=>$vv['id'],
								'option'=>$opt
						)
						);	
					} else if (!empty($vv[$vvv])){
						$data[$k]['xdata'][]=array(
							'title'=>$kk,
							'input'=>array('name'=>$vvv,
								'id'=>$vv['id'],'value'=>$vv[$vvv])
						);	
					}
				};
				$data[$k]['ost']=!empty($vv['ostatok']);
				if(!empty($vv['descr7'])){
					// readhim
					if($node=$engine->node($vv['descr7'])){
						$nodes=$engine->nodeGet($node);
						if(count($nodes)>1){
							$data[$k]['add']=array('descr7'=>$vv['descr7']);
						}
					}
		
				}
				if(!empty($vv['the_href']) && $vv['the_href']!='?do=menu&id=0'){
					$data[$k]['info']=array('the_href'=>$vv['the_href']);
				}
			}
            }
			if(!empty($kid))
				$kid['colspan']=count($headers)+4;
			if(!empty($data))
			$table[]=array(
				'subtitle'=>$kid,
 				'headers'=>$theaders,'data'=>$data,'colspan'=>count($headers)+4,
 			);
		};
		$key=array(
 				//'colspan'=>count($headers)+4,
 				'_table'=>$table,
 				'pages'=>$pages,
 				'perpage'=>$perpage//ppx($pages['perpage'],ppx($engine->getPar('catalogue-perpage'),20))
			);
		if(!empty($button_val))
			$key['button_val']=$button_val;	
		$key['nobasket']=!preg_match('/basket\d+/',$real_id);
		return smart_template(array(ELEMENTS_TPL,$tpl),$key);
	}
}

itemByType(type_KATALOG,'xToyhobbyKatalogue');

//define ('type_ANCHOR',20);
//define ('type_ANCHORS',21);
class Toyhobby_katalog extends katalog {
	
	function convertcost(&$from,$reson){
		if($reson=='ins'){
			if(!isset($from['order0']))
				$from['order0']=999999;
		}
		parent::convertcost($from,$reson);		
	}
	
/**
 * ��������� �������� ������� �������
 *
 * @param unknown_type $xx
 * @param unknown_type $subcat
 * @return unknown
 */	
	function get_headers_from($xx,$subcat,$_item=false,$cat=''){
		$headers=array(
			array('name'=>'�������','tpl'=>'td_border','var'=>array('text'=>'articul')),
			array('name'=>'��������','tpl'=>'td_border','var'=>array('text'=>'name')),
			array('name'=>'�������������','tpl'=>'td_border','var'=>array('text'=>'manufacturer')),
			array('name'=>'�������','tpl'=>'td_input','var'=>array('disabled','xid','cnt')),
			array('name'=>'����','tpl'=>'td_cost','var'=>array('text'=>'cost')),
			array('name'=>'�����','tpl'=>'td_xsumm','var'=>array('xcost')),				
		);
		return $headers;
	}
	
/**
 * ������ ����� ��������
 *
 * @param unknown_type $parent
 */
	function Toyhobby_katalog(&$parent){
	global $engine;
	//print_r($engine);
		parent::ml_plugin($parent);
		parent::_init(
		array(
		'searchSQL'=>'SELECT z.* '. 
				'FROM ?_katalog as z '.
				'WHERE (LOCATE( ?, LCASE( CONCAT(" ",z.descr," ",z.articul ) )) !=0) '. 
				'order by z.articul LIMIT 100;'
		,'title'=>'������� �������'
		,'fields'=>array(
				array('<input type="checkbox" id="aaa" value="0">','check','checkbox'),
				array('����','pic_small','image','csvfields'=>array('����')),
				array('�������','articul','csvfields'=>array('�������')),
				array('������������','name','csvfields'=>array('������������')),
				array('������� ��������','descr','html_edit','afilter'=>5, 'csvfields'=>array('��������')),
				array('��������������','the_href','dbl_button','skip_subkat'=>true),
				array('�������������','manufacturer','csvfields'=>array('�������������')),
				array('�����.','ostatok','csvfields'=>array('�������')),
				array('����','cost','csvfields'=>array('����')),
				array('��. ���.','unit','csvfields'=>array('��. ���������')),
				array('���. ������','soput_tov','text_edit','csvfields'=>array('���. ������')),
				array('������','visibility','check01')
			)
		,'sort'=>'order0'	
		,'inner'=>array(
//				'picture'=>array('width'=>125,'type'=>type_PIC,'button'=>'���.����')
				//'article'=>array('width'=>125,'type'=>type_ARTICLE,'button'=>'���.��������')
			)	
		,'base'=>'_katalog'
		,'prefix'=>'kt'));
		if(defined('IS_ADMIN')){
			$this->additional=smart_template(array(KATALOG_TPL,'additional1'),array('i'=>0,
			'options'=>array(
				array('id'=>'copy','value'=>'�����������...'),
				array('id'=>'paste','value'=>'��������...'),
				array('id'=>'spec','value'=>'� ���������������')
			)));
			$this->additional2=smart_template(array(KATALOG_TPL,'additional1'),array('i'=>1,
			'options'=>array(
				array('id'=>'copy','value'=>'�����������...'),
				array('id'=>'paste','value'=>'��������...'),
				array('id'=>'spec','value'=>'� ���������������')
			)));			
		}
	}
}

$EXPORTS['katalog']='Toyhobby_katalog';

class Toyhobby_spec extends spec {

	function do_spec(){
		$katalogue=itemByType(type_KATALOG);
		$katalogue=&new $katalogue();
		return $katalogue->getText($keys,array('spec'=>array()));
	}
}
$EXPORTS['spec']='Toyhobby_spec';


itemByType(1001,'yGallery');
nameByType(1001,'�������-1');
array_push($GLOBALS['opt_array'],1001);

array_push($GLOBALS['opt_array'],type_GALLERY);

function get_parameters(&$par){
	$par['list'][]=array('sub'=>'�������������','title'=>'����� ��������������','name'=>'login_admin');
	$par['list'][]=array('title'=>'����� ������ ��������������','name'=>'login_newpassword');
	$par['list'][]=array('title'=>'������ ������ ��������������','name'=>'login_oldadmin');
	$par['list'][]=array('sub'=>'������ ���','title'=>'����� ��� ���������','name'=>'mail_admin');
	$par['list'][]=array('sub'=>'�������������� ����� ��� ������','title'=>'e-mail','name'=>'mail_admin2');
	$par['list'][]=array('sub'=>'����� ����������� ���� ��� �����','title'=>'�����������','name'=>'theme1', 'type1'=>'radio', 'value'=>'1' );
	$par['list'][]=array('title'=>'����������','name'=>'theme1', 'type1'=>'radio', 'value'=>'2' );
	$par['list'][]=array('title'=>'8 �����','name'=>'theme1', 'type1'=>'radio', 'value'=>'3' );
	$par['list'][]=array('title'=>'23 �������','name'=>'theme1', 'type1'=>'radio', 'value'=>'4' );



//	$par['list'][]=array('sub'=>'�������','title'=>'���������� ������� �� ��������','name'=>'catalogue-perpage');
	
}

//
?>