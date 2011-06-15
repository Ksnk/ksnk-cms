<?php

setlocale(LC_ALL ,'ru_RU.CP1251');

require_once('katalog.php');

define('KATALOG_INTO_MENU',false);

define ('USER_TYPE',false);

define('SECOND_TPL','tpl_second');

define ('QA_WITH_THEME',true);

define('SITE_CREATE_SCENARIO',5);

define('ANCHOR_STORE',false); // сохранять ANCHORS, но не показывать их в меню

//define('ARTICLE_WIDTH_IMAGE',true);

$GLOBALS['opt_array']=array(type_NEWTEXTPIC,type_LINKS,type_TABLE,type_ANCHOR,type_GALLERY,type_GALLERY2,type_LINE);
// 
class airis_users extends users {
	function airis_users(&$parent){
		parent::users($parent);
		$this->$fields=array(
			'name'=>'Ник пользователя',
			'password'=>'Пароль пользователя',
			'cust_PHONE'=>'Телефон',
			'right'=>false, // редактирование запрещено
			'type'=>false, // редактирование запрещено
			'id'=>false, // редактирование запрещено
			'record'=>false, // редактирование запрещено
			'cust_TYPE'=>USER_TYPE,
			'cust_ADDRESS'=>'Юр. адрес',
			'cust_EMAIL'=>'E-mail',
			'cust_FIO'=>'Контактное лицо',
			'cust_INN'=>'ИНН',
			'cust_KPP'=>'КПП',
			'cust_OGRN'=>'ОГРН',
			'cust_DIRECTOR'=>'ФИО ген-директора',
			'cust_ORGANISATION'=>'Название организации',
			'cust_SCHET'=>'№ счета',
			'cust_BANK'=>'Название банка',
			'cust_BANK_BIK'=>'БИК банка',
			'cust_BANK_INN'=>'ИНН банка',
			'cust_BANK_KPP'=>'КПП банка',
			'cust_CORSCHET'=>'№ корреспондентского счета',
//			'cust_MANAGER'=>'<span class="red">e-mail менеджера ЮТЕКС</span>',
		);		
	}
}

$EXPORTS['katalog']='Toyhobby_katalog';

/*<%=insert_point('plugin_body'); %>*/

// Перенесоно из Тойхобби




class Toyhobby_csv extends csv {
/**
 * Инициализирующая муть..
 *
 * @param engine $parent
 * @return massmail
 */
	function Toyhobby_csv(&$parent){
		parent::ml_plugin($parent);
		parent::_init(
		array(
		'fields'=>array(
				array('Артикул','articul','csvfields'=>array('Артикул')),
				array('Наименование','name','csvfields'=>array('Наименование')),
                array('Характеристики','the_href')),
				array('Ед. изм.','unit','csvfields'=>array('Ед. измерения', 'Единица измерения')),
                array('Цена','cost','csvfields'=>array('Цена')),
                array('Налич.','ostatok','csvfields'=>array('Наличие', 'Налич.')),
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
				// добавить в запрос условие с change
				$pattern=preg_replace('/;&/',' and x.`change`<>1;',$pattern);
				break;
		}
	}
}
$EXPORTS['csv']='Toyhobby_csv';

class Toyhobby_basket extends basket {
	function do_basket() {
		//debug($this);
		$this->pluginname='Корзина';
		ml_plugin::setupmenu();
		$res=$this->recalc();
        $x='';
		if($this->inumber()){
			$_COOKIE['perpage']=40; //XXX: !!!

			$katalogue=&new xKatalogue();
			$x.= $katalogue->getText($keys,$this->basket_data(),'Пересчитать');
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
			
			$hh=array('Упаковка'=>'edism','Оптическая сила'=>'descr1',
				'Радиус кривизны'=>'descr2',
				'Диаметр'=>'descr3',
				'Ось'=>'descr4',
				'Цилиндр'=>'descr5',
				'Цвет'=>'descr6'
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
 * Настройка столбцов таблицы товаров
 *
 * @param unknown_type $xx
 * @param unknown_type $subcat
 * @return unknown
 */	
	function get_headers_from($xx,$subcat,$_item=false,$cat=''){
		$headers=array(
			array('name'=>'Артикул','tpl'=>'td_border','var'=>array('text'=>'articul')),
			array('name'=>'Название','tpl'=>'td_border','var'=>array('text'=>'name')),
			array('name'=>'Производитель','tpl'=>'td_border','var'=>array('text'=>'manufacturer')),
			array('name'=>'Корзина','tpl'=>'td_input','var'=>array('disabled','xid','cnt')),
			array('name'=>'Цена','tpl'=>'td_cost','var'=>array('text'=>'cost')),
			array('name'=>'Сумма','tpl'=>'td_xsumm','var'=>array('xcost')),				
		);
		return $headers;
	}
	
/**
 * Список полей каталога
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
		,'title'=>'Каталог товаров'
		,'fields'=>array(
				array('<input type="checkbox" id="aaa" value="0">','check','checkbox'),
				array('Фото','pic_small','image','csvfields'=>array('Фото')),
				array('Артикул','articul','csvfields'=>array('Артикул')),
				array('Наименование','name','csvfields'=>array('Наименование')),
				array('Краткое описание','descr','html_edit','afilter'=>5, 'csvfields'=>array('Описание')),
				array('Характеристики','the_href','dbl_button','skip_subkat'=>true),
				array('Производитель','manufacturer','csvfields'=>array('Производитель')),
				array('Налич.','ostatok','csvfields'=>array('Наличие')),
				array('Цена','cost','csvfields'=>array('Цена')),
				array('Ед. изм.','unit','csvfields'=>array('Ед. измерения')),
				array('Соп. товары','soput_tov','text_edit','csvfields'=>array('Соп. товары')),
				array('Скрыть','visibility','check01')
			)
		,'sort'=>'order0'	
		,'inner'=>array(
//				'picture'=>array('width'=>125,'type'=>type_PIC,'button'=>'Доб.Фото')
				//'article'=>array('width'=>125,'type'=>type_ARTICLE,'button'=>'Доб.Описание')
			)	
		,'base'=>'_katalog'
		,'prefix'=>'kt'));
		if(defined('IS_ADMIN')){
			$this->additional=smart_template(array(KATALOG_TPL,'additional1'),array('i'=>0,
			'options'=>array(
				array('id'=>'copy','value'=>'Скопировать...'),
				array('id'=>'paste','value'=>'Вставить...'),
				array('id'=>'spec','value'=>'в Спецпредложения')
			)));
			$this->additional2=smart_template(array(KATALOG_TPL,'additional1'),array('i'=>1,
			'options'=>array(
				array('id'=>'copy','value'=>'Скопировать...'),
				array('id'=>'paste','value'=>'Вставить...'),
				array('id'=>'spec','value'=>'в Спецпредложения')
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
nameByType(1001,'Галерея-1');
array_push($GLOBALS['opt_array'],1001);

array_push($GLOBALS['opt_array'],type_GALLERY);

function get_parameters(&$par){
	$par['list'][]=array('sub'=>'Администратор','title'=>'Логин администратора','name'=>'login_admin');
	$par['list'][]=array('title'=>'Новый пароль администратора','name'=>'login_newpassword');
	$par['list'][]=array('title'=>'Старый пароль администратора','name'=>'login_oldadmin');
	$par['list'][]=array('sub'=>'Пишите нам','title'=>'Адрес для пересылки','name'=>'mail_admin');
	$par['list'][]=array('sub'=>'Дополнительный адрес для счетов','title'=>'e-mail','name'=>'mail_admin2');
	$par['list'][]=array('sub'=>'Выбор праздничной темы для сайта','title'=>'Стандартная','name'=>'theme1', 'type1'=>'radio', 'value'=>'1' );
	$par['list'][]=array('title'=>'Новогодняя','name'=>'theme1', 'type1'=>'radio', 'value'=>'2' );
	$par['list'][]=array('title'=>'8 марта','name'=>'theme1', 'type1'=>'radio', 'value'=>'3' );
	$par['list'][]=array('title'=>'23 февраля','name'=>'theme1', 'type1'=>'radio', 'value'=>'4' );



//	$par['list'][]=array('sub'=>'Каталог','title'=>'Количество товаров на странице','name'=>'catalogue-perpage');
	
}

//
?>