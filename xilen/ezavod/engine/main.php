<?php
if(!defined('INTERNAL')){
	header('Content-type: text/html; charset=windows-1251');
}

include_once('templater.php');
include_once('engine.php');
include_once('rights.php');
include_once('syspar.php');
include_once('html.class.php');
include_once('sitemap.php');
include_once('news.php');
include_once('q_a.php');
//include_once('articles.php');
include_once('votes.php');
include_once('classes.php');
include_once('users.php');
//include_once('runningline.php');
require_once('nestedsets.class.php');
require_once('sendmail.php');

include_once('project_core.php');
include_once('db_session.php');

/*/****** point site_includes */
include_once 'compiler.class.php';
template_compiler::checktpl();/****finish point site_includes *//*
*/

/**
 * Описание главного обьекта приложения
 */
class engine extends engine_Main
{
    var $step=0;
	
	function userinfo($id){
		static $cache=array();
		static $names=array(
			'surname'=>'Фамилия',
			'first_name'=>'Имя',
			'patronymic'=>'Отчество',
			'birthday'=>'Дата рождения',
			'address'=>'Место жительства',
			'cust_ORGANISATION'=>'Учреждение',
			'cust_ADDRESS'=>'Адрес',
			'cust_INFO'=>'Контактная информация',
			'cust_POSITION'=>'Должность',
			'cust_EMAIL'=>'Почта (e-mail)',
			'cust_PHONE'=>'Телефон',
			'cust_POSTADDR'=>'Почтовый адрес',
			'cust_ADDITIONALINFO'=>'Дополнительная информация',
			'cust_POSITION'=>'Должность',
		);		
		if(isset($cache[$id])) return $cache[$id];
		$user=$this->readRecord(array('id'=>$id));
		$visible=array();
		if (!empty($user['visible'])) {
			foreach($user['visible'] as $v) $visible[$v]=1;
		} 
		return $cache[$id]=$this->_tpl('tpl_jusers','_userinfo',array(
			'visible'=>$visible	
			,'names'=>$names
			,'user'=>$user
			));
	}

    function do_orderlist (){
        $this->sessionstart();
        $this->sessionstart();
        if (defined('SECOND_TPL')){
            $this->tpl=SECOND_TPL;
        }

        if(isset($_SESSION['USER_ID'])){
            if($this->user['right']['*']==129){
                return $this->export('customers','do_customers');
            } else{
                return $this->export('order_history','get_List');
            }
            
        } else {
            ml_plugin::setupmenu('Клиентский сервис');
            return 'Только для зарегистрированных пользователей';
        }
    }

    /**
     * подмена гостевой на форум
     */
    function do_qa(){
        if (defined('SECOND_TPL')){
            $this->tpl=SECOND_TPL;
        }
        return $this->export('forum','do_forum');
    }


	function do_ch(){
		$this->handle('katalog');	
		if(!empty($_POST))
			$_POST['perpage']=$this->getPar('catalogue-perpage');
		//debug($this);
		$this->cur_menu=pps($_GET['id']);

		//if(empty ($items)) return '';
		$keys=array();
		$info=$this->export('katalog','itemInfo',ppi($_GET['item']));
	   	$this->nopoplast=$info['descr'];
		//ml_plugin::setupmenu();
	    //debug($info);
		return 
			smart_template(array(ELEMENTS_TPL,'charact'),array(
				'first'=>$info,
				'item'=>ppi($_GET['item']),
				'list'=>
			xKatalogue::getText($keys,array(
				'item_'.ppi($_GET['item'])=>array())
			)));
 /*				
		$items=$this->export('katalog','get_category','item_'.ppi($_GET['item']));

		if(!empty($_POST)){
			$this->go($this->curl());
		}

		$this->cur_menu=$rsd=pps($_GET['id']);

		if(empty ($items)) return '';
		$itemfirst=array_shift($items);
		$x=1;
		foreach($items as $k=>$v){
			$items[$k]['even']=((($x++)%2) ==0);
			$v['sign']=$v['remain']>0?'+':'-';
			if($this->parent->has_rights(right_READ))
				$items[$k]['xright']=$v;
		}
		$this->nopoplast=$itemfirst['descr'];
		return $x=smart_template(ELEMENTS_TPL.'#charact',
				array(
					'first'=>$itemfirst,
					'list'=>$items,
					'item'=>ppi($_GET['item'])
				));
*/
	}

	function _head_menu(){
		$head=array();
	 	$sm=$this->parent->ffirst('getSiteMap','main');
	// 	debug($sm);
		$xx=1;
	 	foreach($sm->el as $v){
		$xx=1;
  // без первого элемента!!!
	 		if($v->v['name']=='catalogue')	continue;
			if(isset($v->v['skipit'])) continue;
			
		 
			if($v->v['name']=='Гостевая')	
				{$x=array(		 			
					'current'=>pps($v->v['current'])
		 			,'item'=>pps($v->v['descr'],$v->v['name'])
					,'url'=>$v->getUrl()
				);
				$x['url']='?do=menu&id=4140';
				
				}else{		$x=array(
		 			'current'=>pps($v->v['current'])
		 			,'item'=>pps($v->v['descr'],$v->v['name'])
		 			,'url'=>$v->getUrl()
		 		);};	
		 		if(((($xx)%4)==0) && ($xx<count($sm->el))) $x['break']=true;
		 		if((($xx++)%4)==1) $x['first']=true;
		 		$head[]=$x;
	 	}
	 	if(empty($head)) return '';
	 	$head[count($head)-1]['last']=true;
	 	// 	debug($head);
	 	//print_r($head);
	 	return $head;
	}

	function _novinki($par='novinki_numb2'){
		$numb=ppi($this->parent->getPar($par),4);
		$xx=$this->export('katalog','get_category','new');
		if(is_array($xx)){
			$xx=array_slice($xx,0,$numb);
		}

		return smart_template(array(ELEMENTS_TPL,'novinki'),
			array('row'=>$xx)
		);
	}

	function _spec($par='spec_numb2'){
		$numb=$this->parent->getPar($par,4);
		$cols=$this->parent->getPar('spec_cols1',2);
		$xx=$this->export('katalog','get_category','spec');
		if(is_array($xx)){
			$xx=array_slice($xx,0,$numb);
			$i=1;
			foreach($xx as $k=>$v){
				if (($i++ % $cols)==0){
					$xx[$k]['break']=true;
				}
			}
		}
		//debug($xx);
		return smart_template(array(ELEMENTS_TPL,'specpredl'),
			array('row'=>$xx)
		);
	}
	
	function kat_rec($x,&$data){
		$data[]=array(
			'val'=>$x->v['id'],
			'level'=>str_repeat('&nbsp;',4*ppi($x->v['level'])-4),
			'name'=>$x->v['name']
		);
		
		foreach($x->el as $v)
			$this->kat_rec($v,$data);
	}
	
	function _kat_select(){
		static $x; if(isset($x)) return $x;
		$x=array();
		$xx=$this->ffirst('getSiteMap','catalogue');
		$data=array();
		$this->kat_rec($xx,$data);
		array_shift($data);
		
		if (empty($data))
			return '';
		else	
			return $data; //array(array('val'=>1,'level'=>'&nbsp;','name'=>'hello!'));
	}

	function _hmenu(){
	
	
		$xx=$this->ffirst('getSiteMap','catalogue');
		return smart_template(ELEMENTS_TPL.'#leftmenu',
			array('data'=>$xx->getUlLi(5,false,1)));
	}
	
	function _rmenu(){
		$xx=$this->ffirst('getSiteMap');
		$xxx=$this->ffirst('getSiteMap', $this->cur_menu);
		if($xxx->v['name'] == 'main')
			return '';
		$data = $xx->getUlLi(5,true,3);
		$name = $xx->getrmenuLink(3,true,2);
		if($data == "") {
			return "";
		}
		return smart_template(array(ELEMENTS_TPL,'leftmenu'),
			array('data'=>$data, 'name'=>$name));
	}
	
	function _tmenu(){
		$xx=$this->ffirst('getSiteMap');
		$xxx=$this->ffirst('getSiteMap', $this->cur_menu);
		//$xxx=$this->ffirst('getSiteMap', $this->cur_menu);
		$data = $xx->getUlLi(3,false,2);
		if($xxx->v['name'] == "main")
			$class = 'class = "current"';
		else
			$class = "";
		if($data == "")
			return "";
		return smart_template(array(ELEMENTS_TPL,'topmenu'),
			array('data'=>$data, 'name'=>$name, 'class'=>$class));
	}
	
	function razmer_td_l() {
		$xxx=$this->ffirst('getSiteMap', $this->cur_menu);
		if($xxx->v['name'] == 'main')
			return '22';
		if($this->_rmenu() == "") {
			return "1";			
		}
		return "22";
	}
	
	function razmer_td_c() {
		$xxx=$this->ffirst('getSiteMap', $this->cur_menu);
		if($xxx->v['name'] == 'main')
			return '56';
		if($this->_rmenu() == "") {
			return "77";			
		}
		return "56";
	}

	function menu_1(){
		$sm=$xx=$this->ffirst('getSiteMap','main');//,'menu002','ulmenu',false
 		$li='';$i=1;$id=0;
		$index1=$this->index();
		foreach($sm->el as $k=>$v){
			if(isset($v->v['skipit'])) continue;
			$id=$id+1;
			$num_b='b'.$id;
			//if($v->v['type']==15) continue;
			$txt=pps($v->v['descr'],pps($v->v['name'],'root'));
			$class=(!empty($v->v['current'])?'current':'');
			if($k==(count($sm->el)-1))
				$class.=' last';
			$txt=pps($v->v['descr'],pps($v->v['name'],'root'));	
			$str1='';
			$ul='';
            if($v->v['url']!="qa")
			foreach($v->el as $vv){ $ul.=$vv->getUlLi(1,true,0);}
			if(!empty($ul))
				{
					$ul='<div style="position:relative; float:none; background:none;"><ul>'.$ul.'</ul></div>';
				}else{$str1='href="'.$v->getUrl().'"';}

			$li.='<li class="'.pp($class,'','').'"><div><a title="'.htmlspecialchars($txt).'" href="'.$index1.$v->getUrl().'" class="'.$num_b.'"><img alt="'.htmlspecialchars($txt).'" src="'.$index1.'/uploaded/'.translit($txt).'.gif"  onmouseover="show_block(\''.$num_b.'\');" onmouseout="hide_block(\''.$num_b.'\');"></a></div>'.$ul.'</li>';
			
				
		}
		return '<ul id="first_menu">'.$li.'</ul>';
		
	}

	
		function menu_2(){
		$sm=$xx=$this->ffirst('getSiteMap','main');//,'menu002','ulmenu',false
 		$li='';$i=1;$id=0;
		foreach($sm->el as $k=>$v){
			$id=$id+1;
			if(isset($v->v['skipit'])) continue;
			//if($v->v['type']==15) continue;
			$txt=pps($v->v['descr'],pps($v->v['name'],'root'));
			$class=(!empty($v->v['current'])?'current':'');
			if($k==(count($sm->el)-1))
				$class.=' last';
			$txt=pps($v->v['descr'],pps($v->v['name'],'root'));	
			$str1='';
			$ul='';
			foreach($v->el as $vv){ $ul.=$vv->getUlLi(1,ture,0);}
			if ((pp($class,'class="','"')=='class="current"') && (!empty($ul))) $li='<div id="menu_right"> <ul>'.$ul.'</ul></div>';
			

		}
		return $li;
		
	}

		function menu_3(){
		$sm=$xx=$this->ffirst('getSiteMap','main');//,'menu002','ulmenu',false
 		$li='';
		foreach($sm->el as $k=>$v){
			if(isset($v->v['skipit'])) continue;
			if($v->v['name']=='Продукция') continue;
			$txt=pps($v->v['descr'],pps($v->v['name'],'root'));
			$class=(!empty($v->v['current'])?'current':'');
			if($k==(count($sm->el)-1))
				$class.=' last';
			$txt=pps($v->v['descr'],pps($v->v['name'],'root'));	
			$str1='';
			$ul='';
			foreach($v->el as $vv){ $ul.=$vv->getUlLI(0,0);}
			if(!empty($ul))
				{
					$ul='<ul '.$class.'>'.$ul.'</ul>';
				}

			$li.='<td class="menu_top"><a href="'.$v->getUrl().'" class="menu_top'.pp($class,' ','').'" style="background:url(uploaded/'.translit($txt).'2.gif) no-repeat"><img alt="'.htmlspecialchars($txt).'" src="uploaded/'.translit($txt).'1.gif"></a></td><td width="4px" style="padding-top:4px" class="menu_top"><img '.pp($class,'class="','"').' src="img/menu_bg1.gif"></td>';

		}
		return '<table align="right" class="menu_top1"><tr>'.$li.'</tr></table>';
	}

	function holiday_theme(){
		$sm=$this->getPar('theme1');
		$holiday='bg3_2.gif';
	
		if ($sm==3)  $holiday='bg3_3.png';
		if ($sm==2) $holiday='bg3_5.png'; 
		if ($sm==4) $holiday='bg3_4.png'; 



		return $holiday;
	}



	function init(){
		parent::init();
		$this->menu=array();
		$this->menu['head']=array('MAIN','_head_menu');
		$this->menu['right']=array('MAIN','_novinki','novinki_numb1');
		$this->menu['spec']=array('MAIN','_spec','spec_numb1');
		$this->menu['left']=array('MAIN','_hmenu');
	}

	function _short(){
		return smart_template(array(ELEMENTS_TPL,'shortspec'),array());
	}

	function handle($h){
//		debug(1111); debug('2222'. $h);
		if($h=="katalog"){
			$this->tpl='tpl_second';
			$this->menu['spec']=array('MAIN','_short');
			unset($this->menu['right']);
		}
	}

	function _first(){
		static $x;
		if(isset($x))return $x;
		//if ($this->cur_menu == 'main')
			//return '';
		if ($this->cur_menu!=$this->getPar('first_menu')){
			// подкаткегория...
			$x=$this->ffirst('_getCurList');
			array_shift($x);
			$y=array_shift($x);
			if($x[0]['name']=='catalogue')
				array_shift($x);
			if($x[0]['name']=='Продукция')
				array_shift($x);	
			if(!isset($this->nopoplast))
				if(empty($this->dop_zagl))
					$y=array_pop($x);
				else
					$y=$x[count($x)-1];
			else
				$y=array('name'=>$this->nopoplast);
			if (empty($x)) $x='';
			return $x=smart_template(array(ELEMENTS_TPL,'nfirst'),
				array('sub'=>$y['name'],'list'=>$x));
		} else
			return $x='<div style="padding-top:35px;"></div>';
	}
	
	function zagl(){
		static $x;
		global $forum_zagl;
		//echo $this->qwerty;
		if(isset($x))return $x;
		if ($this->cur_menu == 'main')
			return '';
		if ($this->cur_menu!=$this->getPar('first_menu')){
			// подкаткегория...
			$x=$this->ffirst('_getCurList');
			array_shift($x);
			$y=array_shift($x);
			if($x[0]['name']=='catalogue')
				array_shift($x);
		   if(!isset($this->nopoplast))
				$y=array_pop($x);
			else
				$y=array('name'=>$this->nopoplast);
			if (empty($x)) $x='';
			//var_dump(debug_backtrace());
			
			//Замена заголовка для темы форума
			if(!empty($this->dop_zagl))
				$y['name'] = $this->dop_zagl;
			return $x=smart_template(array(ELEMENTS_TPL,'nfirst2'),
				array('sub'=>$y['name'],'list'=>$x));
		} else
			return $x='<div style="padding-top:35px;"></div>';
	}
	/**
	 * Генерация главного окна приложения
	 */
	function do_Default(){
		$this->cur_menu=$this->getPar('first_menu');
		//$this->parent->par['anaons_forum'] = "123";
		return $this->do_menu('main');
		//return $this->ffirst("news_b"); //"Content какой-то";
	}
	/**
	 * Генерация раздела Статьи
	 */

	function rightb($r=''){
		if($r=='') $r=right_READ;
		return $this->has_rights($r);
	}
	function rightn($r=''){
		if($r=='') $r=right_READ;
		return !$this->has_rights($r);
	}
	function right($r=''){
		if($r=='') $r=right_READ;
		return $this->has_rights($r)?array():'';
	}

	function do_error(){
		return smart_template(array(ELEMENTS_TPL,'ermess'),' ');
	}

    /**
     * @param  $tpl
     * @return mixed|string|void
     */
	function get_basketData(){

			$res=$this->user;

			foreach($this->parameters as $k=>$v){
				if (preg_match('/^spec_/',$k)){
					$res[$k]=trim($this->parameters[$k]);
				}
			}

			$pages=array();
			$data=array();
			$par=array('pages'=>&$pages,
				'data'=>&$data);
			$res['ordernum']=$this->getPar('ordernum');
			$res['Date']=date('d. m. Y');
			$res['Time']=date("H:i:s");
			$res['user']=$this->user['name'];

			$i=1;
			$summ=0;
			$inum=0;
			$bdata=$this->export('basket','basket_data');
			$res['llist']=array();
            $_SERVER['REQUEST_METHOD']='';

			foreach($bdata as $bas=>$v){
				$this->parent->export('katalog','get_category',$bas,$par);
				if(!empty($data))
                    $basket=basket::getStore()->get();
				foreach($data as $k=>$v){
					if (!empty($basket[ppi($v['xid'])])){
						$data[$k]['numb']=$i++;
						$data[$k]['cnumb']=$basket[pps($v['xid'])]['n'];
						$inum+=$data[$k]['cnumb'];
						$ccost=$basket[pps($v['xid'])]['cost']/100;
						$data[$k]['ccost']=number_format($ccost, 2, ',', '');
						$data[$k]['cccost']=number_format($ccost*$data[$k]['cnumb'], 2, ',', '');
						$summ+=$ccost*$data[$k]['cnumb'];
					/*
						$data[$k]['numb']=$i++;
						$data[$k]['cnumb']=$_SESSION['basket'][ppi($v['id'])]['n'];
						$inum+=$data[$k]['cnumb'];
						$data[$k]['ccost']=$_SESSION['basket'][ppi($v['id'])]['cost']/100;
						$data[$k]['cccost']=$data[$k]['ccost']*$data[$k]['cnumb'];
						$summ+=$data[$k]['cccost'];
**/
						$res['llist'][]=$data[$k];
					}
				}
			}
			$bas=$this->export('basket','recalc');
			$res['summ']=number_format($summ, 2, ',', '');
			$res['nds']=number_format(round($bas['cost']*18)/100, 2, ',', '');
			$prop = &new prop();
			$res['inumb']=$bas['pos'].' '.$bas['tovar'];
			$res['summprop']=$prop->num2str($summ,prop::prep("рубл|ь|я|ей","+копе|йка|йки|ек"));
			debug($res);
			return $res;
	}

    function do_login(){
        $form=new form('login');
        $form->scanHtml($this->_tpl('tpl_jusers','_login',array(
				'cansave'=>defined('LOGIN_CANSAVE')?LOGIN_CANSAVE:true,
				'error'=>pps($_SESSION['errormsg']))));
        if($form->handle()){
        }
        return $form->getHtml(' ');
    }

	/**
	 * Оформление договоров по корзине.
     * В зависимости от выбора способа оплаты, выбираем поля для вывода в форме
	 */
    /**
     * Оформление договоров по корзине.
     * В зависимости от выбора способа оплаты, выбираем поля для вывода в форме
     */
    function do_ordersave(){
        $this->step=1;
        return $this->do_order();
    }
    
    function do_orderdisplay(){
        $this->sessionstart();
        $this->tpl=array(ELEMENTS_TPL,'ajax');
        return $this->export('order_history','order_print',$_SESSION['order_id']) ;
    }
    
    function do_order(){
        $this->sessionstart();
      /*  if(!isset($_SESSION['USER_ID'])){
            return $this->do_login();
        } */
       if (defined('SECOND_TPL')){
            $this->tpl=SECOND_TPL;
        }
        /**
         * выставляем заголовок оформлялки.
         * Только для случая, если оно не включено в меню сайта
         */
        ml_plugin::setupmenu('Оформление заказа');

        /**
         * tpl_printbody - простой шаблон вывода списка товаров в виде таблицы
         */

        $tpl='tpl_printbody';
        $basketdata=$this->get_basketData();
        /**
         *  список полей для заполнения, в зависимости от способа выбора формы оплаты
         */
        $x=array(
            'Форма оплаты'=>array("cust_order","radio",//"Безналичный расчет (для юр. лиц)|Оплата квитанцией Сбербанка|Наличный расчет (в офисе компании)",
                      "Безналичный расчет (для юр. лиц)|" //1
                      ."Оплата квитанцией Сбербанка|"    //2
                      ."Наличный расчет (в офисе компании)", //3
               'onchange'=>'submitform(this);',
               'default'=>ppi($this->parent->user['cust_order'],2)
            ),
            "Ф.И.О."=>array('cust_FIO','require'=>true),
            "адрес"=>array('address','require'=>true,"rule"=>'r2 r3'),
            "телефон"=>array('cust_PHONE','require'=>true),
            "E-mail"=>array('cust_EMAIL','validate'=>'email','require'=>true),

            array("Информация для выставления счета",'text',"rule"=>'r1','noprint'=>true),
            "Название организации"=>array('cust_ORGANISATION','require'=>true,"rule"=>'r1'),
            "Юридический адрес"=>array('cust_ADDRESS','require'=>true,"rule"=>'r1'),
            array("Счет","text","rule"=>'r1'),
            "ИНН"=>array("cust_BANK_INN",'require'=>true,"rule"=>'r1'),
            "КПП"=>array("cust_BANK_KPP",'require'=>true,"rule"=>'r1'),
            array("Расчётный счёт","text","rule"=>'r1'),
            "Название банка"=>array("cust_BANK",'require'=>true,"rule"=>'r1'),
            "ИНН банка"=>array("cust_BANK_INN",'require'=>true,"rule"=>'r1'),
            "КПП банка"=>array("cust_BANK_KPP",'require'=>true,"rule"=>'r1'),
            "БИК банка"=>array('cust_BANK_BIK','require'=>true,"rule"=>'r1'),
            "№ счета"=>array('cust_BANK_OKG','require'=>true,"rule"=>'r1'),
            "№ Корр. счета"=>array('cust_BANK_OKG','require'=>true,"rule"=>'r1'),
            "Ваш заказ",
            array($this->export('order_history','order_print',$basketdata,'web'),'scrolltext'),
        );
// варианты оформления счета
        //sdebug(22222);
         if ($this->step==1){
            $err=$this->error();
            basket::getStore()->clear();
            return '<div class="link" style="padding-top:30px;">
                '.pp($err,'<div class="red">','</div>'
                    ,'Ваш заказ отправлен на указанный Вами e-mail. <br>').
                '<a class="blue tahoma" href="'.$this->curl('do','step').'do=orderdisplay" target="order">
                Печатать квитанцию</a></div>';
        }
         //debug(33333);
         if(!isset($_SESSION['USER_ID'])){
            $x=array_merge(array(
                 "Логин"=>array('newlogin','require'=>true),
                 "Пароль"=>array('newpassword','password','require'=>true),
                 "Подтверждение пароля"=>array('newpassword0','password'
                 ,'validate'=>'if($var["newpassword"]!=$var["newpassword0"]) return "Не совпадают пароли<br>";'
                 ,'require'=>true),

                           )
                ,$x);
        }
        // проверка входа в систему по первым полям
        if(isset($_POST['newlogin']) && isset($_POST['newpassword'])){
            if( $this->export('Auth','auth_check',$_POST['newlogin'],$_POST['newpassword']) ){
                $this->go($this->curl());
            }
        }
//debug(111);
        $form=$this->parent->export('MAIN','SimpleForm',$x,array('ruller'=>'cust_order'));
        //$x['Форма оплаты']['value']=ppi($this->parent->user['cust_order'],2);
//debug($form);
        if(!is_string($form)) {
//debug ('aaa');
            $key=array();
            if(!isset($_SESSION['USER_ID'])){
                $_SESSION['USER_ID']=$this->parent->writeRecord(array('record'=>'user'
                    ,'name'=>$form->var['newlogin']
                    ,'password'=>$form->var['newpassword']
                    ,'right'=>array('*'=>(right_READ))));
                $this->parent->user=$this->readRecord(array('id'=>$_SESSION['USER_ID']));
            }
            //изменяем для зарегистрированного юзера
            if(isset($_SESSION['USER_ID'])){
                //debug($this->user);
                $user_fields=$this->export('users')->fields;
                $changed=false;
                foreach($form->var as $k=>$v){
                    if (isset($user_fields[$k]) && pps($this->user[$k])!=$v){
                        $this->user[$k]=$form->var[$k];
                        $changed=true;
                    }
                }
                // сохранить юзера
                if($changed){
                    $this->writeRecord($this->user);
                }
            }
            foreach($x as $v){
                $key[$v[0]]=$form->var[$v[0]];
            }

            $_SESSION['zakaz']=smart_template(array('tpl_admin','mail_callback'),array('list'=>$this->SimpleFormPrint($x)));//$this->createTpl($tpl,$key);

            $_SESSION['orderprint']=
                    $this->_tpl('tpl_jorders','_print'.ppi($form->var['cust_order'],1),array('order'=>array_merge($this->user,$form->var),2));
            //оформление заказа и пресылка по почте
            //
            $to=pps($this->getPar('mail_admin'),'art@xilen.ru');
            $subj='Заказ с сайта "'.$_SERVER['SERVER_NAME'].'"';
            $from=pps($key['cust_EMAIL']);
            $headers=html_mime_mail::mail_header('From: ',$from,$fio).
                html_mime_mail::mail_header('Cc: ',$from,$fio);
            $cto=pps($this->getPar('mail_admin2'));
            if(!empty($cto)){
                $headers.=html_mime_mail::mail_header('Cc: ',$cto);
            };
            $_SESSION['order_id']=$this->parent->export('order_history','save',array_merge($basketdata,$key));//);
            //$fio=pps($key['cust_FIO']);
            $mail=new html_mime_mail(
                $headers.
                pp($to,'Reply-To: ',"\r\n").
                   'X-Mailer: PHP/' . trim(phpversion())."\r\n"
            );
            $mail->add_html('Вами был сделан заказ на сайте '.$_SERVER['SERVER_NAME']
                ."\n<hr>"	);
            //$mail->add_html($_SESSION['zakaz']);
            if($_GET['id']=='kvit')
                $mail->add_new_html('Квитанция.html',$this->export('order_history','print_order',$_SESSION['order_id']));
            $mail->add_html($_SESSION['zakaz']);
                $mail->build_message('win');
            if($mail->send(  $to, $subj)){
                $msg="";
            }
            else {
                $this->error("К сожалению не удалось отослать Ваше письмо.");
            }
            $this->parent->go($this->parent->curl('do').'do=ordersave');
        }

        return $form.
'<script type="text/javascript">$(function(){
         $("form[name=callback] input:checked").trigger("change");
})</script>';
    }
	
	function do_add(){
		$this->sessionstart();
		foreach($_POST as $k=>$v){
			$_POST[$k]=iconv('utf-8','cp1251//IGNORE',$_POST[$k]);
		}
		$par=$_POST;
		$cnt=ppi($_POST['item'],1);
		unset($par['item'],$par['id']);
		$this->export('basket','addItem',pps($_POST['id']),$cnt,$par);

		if($this->is_ajax){
			$this->parent->ajaxdata['basket']=$this->export('basket','_basket');
			return 'Ok';		
		} else { // Это не ajax
			$this->go(pps($_SERVER['HTTP_REFERER'],toUrl(INDEX_PATH)));
		}
	}
}
$engine=&new engine('Auth','sitemap','news','altname');
//echo $engine->tpl;
$_GLOBALS['engine']=&$engine;
DO_IT_ALL();
//echo $engine->tpl;
?>
<?php
if(!defined('INTERNAL')){
	header('Content-type: text/html; charset=windows-1251');
}

include_once('templater.php');
include_once('engine.php');
include_once('rights.php');
include_once('syspar.php');
include_once('html.class.php');
include_once('sitemap.php');
include_once('news.php');
include_once('q_a.php');
//include_once('articles.php');
include_once('votes.php');
include_once('classes.php');
include_once('users.php');
//include_once('runningline.php');
require_once('nestedsets.class.php');
require_once('sendmail.php');

include_once('project_core.php');
include_once('db_session.php');

/*/****** point site_includes */
include_once 'compiler.class.php';
template_compiler::checktpl();/****finish point site_includes *//*
*/

/**
 * Описание главного обьекта приложения
 */
class engine extends engine_Main
{
	
	function userinfo($id){
		static $cache=array();
		static $names=array(
			'surname'=>'Фамилия',
			'first_name'=>'Имя',
			'patronymic'=>'Отчество',
			'birthday'=>'Дата рождения',
			'address'=>'Место жительства',
			'cust_ORGANISATION'=>'Учреждение',
			'cust_ADDRESS'=>'Адрес',
			'cust_INFO'=>'Контактная информация',
			'cust_POSITION'=>'Должность',
			'cust_EMAIL'=>'Почта (e-mail)',
			'cust_PHONE'=>'Телефон',
			'cust_POSTADDR'=>'Почтовый адрес',
			'cust_ADDITIONALINFO'=>'Дополнительная информация',
			'cust_POSITION'=>'Должность',
		);		
		if(isset($cache[$id])) return $cache[$id];
		$user=$this->readRecord(array('id'=>$id));
		$visible=array();
		if (!empty($user['visible'])) {
			foreach($user['visible'] as $v) $visible[$v]=1;
		} 
		return $cache[$id]=$this->_tpl('tpl_jusers','_userinfo',array(
			'visible'=>$visible	
			,'names'=>$names
			,'user'=>$user
			));
	}

    function do_orderlist (){
        $this->sessionstart();
        $this->sessionstart();
        if (defined('SECOND_TPL')){
            $this->tpl=SECOND_TPL;
        }

        if(isset($_SESSION['USER_ID'])){
            if($this->user['right']['*']==129){
                return $this->export('customers','do_customers');
            } else{
                return $this->export('order_history','get_List');
            }
            
        } else {
            ml_plugin::setupmenu('Клиентский сервис');
            return 'Только для зарегистрированных пользователей';
        }
    }

    /**
     * подмена гостевой на форум
     */
    function do_qa(){
        if (defined('SECOND_TPL')){
            $this->tpl=SECOND_TPL;
        }
        return $this->export('forum','do_forum');
    }


	function do_ch(){
		$this->handle('katalog');	
		if(!empty($_POST))
			$_POST['perpage']=$this->getPar('catalogue-perpage');
		//debug($this);
		$this->cur_menu=pps($_GET['id']);

		//if(empty ($items)) return '';
		$keys=array();
		$info=$this->export('katalog','itemInfo',ppi($_GET['item']));
	   	$this->nopoplast=$info['descr'];
		//ml_plugin::setupmenu();
	    //debug($info);
		return 
			smart_template(array(ELEMENTS_TPL,'charact'),array(
				'first'=>$info,
				'item'=>ppi($_GET['item']),
				'list'=>
			xKatalogue::getText($keys,array(
				'item_'.ppi($_GET['item'])=>array())
			)));
 /*				
		$items=$this->export('katalog','get_category','item_'.ppi($_GET['item']));

		if(!empty($_POST)){
			$this->go($this->curl());
		}

		$this->cur_menu=$rsd=pps($_GET['id']);

		if(empty ($items)) return '';
		$itemfirst=array_shift($items);
		$x=1;
		foreach($items as $k=>$v){
			$items[$k]['even']=((($x++)%2) ==0);
			$v['sign']=$v['remain']>0?'+':'-';
			if($this->parent->has_rights(right_READ))
				$items[$k]['xright']=$v;
		}
		$this->nopoplast=$itemfirst['descr'];
		return $x=smart_template(ELEMENTS_TPL.'#charact',
				array(
					'first'=>$itemfirst,
					'list'=>$items,
					'item'=>ppi($_GET['item'])
				));
*/
	}

	function _head_menu(){
		$head=array();
	 	$sm=$this->parent->ffirst('getSiteMap','main');
	// 	debug($sm);
		$xx=1;
	 	foreach($sm->el as $v){
		$xx=1;
  // без первого элемента!!!
	 		if($v->v['name']=='catalogue')	continue;
			if(isset($v->v['skipit'])) continue;
			
		 
			if($v->v['name']=='Гостевая')	
				{$x=array(		 			
					'current'=>pps($v->v['current'])
		 			,'item'=>pps($v->v['descr'],$v->v['name'])
					,'url'=>$v->getUrl()
				);
				$x['url']='?do=menu&id=4140';
				
				}else{		$x=array(
		 			'current'=>pps($v->v['current'])
		 			,'item'=>pps($v->v['descr'],$v->v['name'])
		 			,'url'=>$v->getUrl()
		 		);};	
		 		if(((($xx)%4)==0) && ($xx<count($sm->el))) $x['break']=true;
		 		if((($xx++)%4)==1) $x['first']=true;
		 		$head[]=$x;
	 	}
	 	if(empty($head)) return '';
	 	$head[count($head)-1]['last']=true;
	 	// 	debug($head);
	 	//print_r($head);
	 	return $head;
	}

	function _novinki($par='novinki_numb2'){
		$numb=ppi($this->parent->getPar($par),4);
		$xx=$this->export('katalog','get_category','new');
		if(is_array($xx)){
			$xx=array_slice($xx,0,$numb);
		}

		return smart_template(array(ELEMENTS_TPL,'novinki'),
			array('row'=>$xx)
		);
	}

	function _spec($par='spec_numb2'){
		$numb=$this->parent->getPar($par,4);
		$cols=$this->parent->getPar('spec_cols1',2);
		$xx=$this->export('katalog','get_category','spec');
		if(is_array($xx)){
			$xx=array_slice($xx,0,$numb);
			$i=1;
			foreach($xx as $k=>$v){
				if (($i++ % $cols)==0){
					$xx[$k]['break']=true;
				}
			}
		}
		//debug($xx);
		return smart_template(array(ELEMENTS_TPL,'specpredl'),
			array('row'=>$xx)
		);
	}
	
	function kat_rec($x,&$data){
		$data[]=array(
			'val'=>$x->v['id'],
			'level'=>str_repeat('&nbsp;',4*ppi($x->v['level'])-4),
			'name'=>$x->v['name']
		);
		
		foreach($x->el as $v)
			$this->kat_rec($v,$data);
	}
	
	function _kat_select(){
		static $x; if(isset($x)) return $x;
		$x=array();
		$xx=$this->ffirst('getSiteMap','catalogue');
		$data=array();
		$this->kat_rec($xx,$data);
		array_shift($data);
		
		if (empty($data))
			return '';
		else	
			return $data; //array(array('val'=>1,'level'=>'&nbsp;','name'=>'hello!'));
	}

	function _hmenu(){
	
	
		$xx=$this->ffirst('getSiteMap','catalogue');
		return smart_template(ELEMENTS_TPL.'#leftmenu',
			array('data'=>$xx->getUlLi(5,false,1)));
	}
	
	function _rmenu(){
		$xx=$this->ffirst('getSiteMap');
		$xxx=$this->ffirst('getSiteMap', $this->cur_menu);
		if($xxx->v['name'] == 'main')
			return '';
		$data = $xx->getUlLi(5,true,3);
		$name = $xx->getrmenuLink(3,true,2);
		if($data == "") {
			return "";
		}
		return smart_template(array(ELEMENTS_TPL,'leftmenu'),
			array('data'=>$data, 'name'=>$name));
	}
	
	function _tmenu(){
		$xx=$this->ffirst('getSiteMap');
		$xxx=$this->ffirst('getSiteMap', $this->cur_menu);
		//$xxx=$this->ffirst('getSiteMap', $this->cur_menu);
		$data = $xx->getUlLi(3,false,2);
		if($xxx->v['name'] == "main")
			$class = 'class = "current"';
		else
			$class = "";
		if($data == "")
			return "";
		return smart_template(array(ELEMENTS_TPL,'topmenu'),
			array('data'=>$data, 'name'=>$name, 'class'=>$class));
	}
	
	function razmer_td_l() {
		$xxx=$this->ffirst('getSiteMap', $this->cur_menu);
		if($xxx->v['name'] == 'main')
			return '22';
		if($this->_rmenu() == "") {
			return "1";			
		}
		return "22";
	}
	
	function razmer_td_c() {
		$xxx=$this->ffirst('getSiteMap', $this->cur_menu);
		if($xxx->v['name'] == 'main')
			return '56';
		if($this->_rmenu() == "") {
			return "77";			
		}
		return "56";
	}

	function menu_1(){
		$sm=$xx=$this->ffirst('getSiteMap','main');//,'menu002','ulmenu',false
 		$li='';$i=1;$id=0;
		$index1=$this->index();
		foreach($sm->el as $k=>$v){
			if(isset($v->v['skipit'])) continue;
			$id=$id+1;
			$num_b='b'.$id;
			//if($v->v['type']==15) continue;
			$txt=pps($v->v['descr'],pps($v->v['name'],'root'));
			$class=(!empty($v->v['current'])?'current':'');
			if($k==(count($sm->el)-1))
				$class.=' last';
			$txt=pps($v->v['descr'],pps($v->v['name'],'root'));	
			$str1='';
			$ul='';
            if($v->v['url']!="qa")
			foreach($v->el as $vv){ $ul.=$vv->getUlLi(1,true,0);}
			if(!empty($ul))
				{
					$ul='<div style="position:relative; float:none; background:none;"><ul>'.$ul.'</ul></div>';
				}else{$str1='href="'.$v->getUrl().'"';}

			$li.='<li class="'.pp($class,'','').'"><div><a title="'.htmlspecialchars($txt).'" href="'.$index1.$v->getUrl().'" class="'.$num_b.'"><img alt="'.htmlspecialchars($txt).'" src="'.$index1.'/uploaded/'.translit($txt).'.gif"  onmouseover="show_block(\''.$num_b.'\');" onmouseout="hide_block(\''.$num_b.'\');"></a></div>'.$ul.'</li>';
			
				
		}
		return '<ul id="first_menu">'.$li.'</ul>';
		
	}

	
		function menu_2(){
		$sm=$xx=$this->ffirst('getSiteMap','main');//,'menu002','ulmenu',false
 		$li='';$i=1;$id=0;
		foreach($sm->el as $k=>$v){
			$id=$id+1;
			if(isset($v->v['skipit'])) continue;
			//if($v->v['type']==15) continue;
			$txt=pps($v->v['descr'],pps($v->v['name'],'root'));
			$class=(!empty($v->v['current'])?'current':'');
			if($k==(count($sm->el)-1))
				$class.=' last';
			$txt=pps($v->v['descr'],pps($v->v['name'],'root'));	
			$str1='';
			$ul='';
			foreach($v->el as $vv){ $ul.=$vv->getUlLi(1,ture,0);}
			if ((pp($class,'class="','"')=='class="current"') && (!empty($ul))) $li='<div id="menu_right"> <ul>'.$ul.'</ul></div>';
			

		}
		return $li;
		
	}

		function menu_3(){
		$sm=$xx=$this->ffirst('getSiteMap','main');//,'menu002','ulmenu',false
 		$li='';
		foreach($sm->el as $k=>$v){
			if(isset($v->v['skipit'])) continue;
			if($v->v['name']=='Продукция') continue;
			$txt=pps($v->v['descr'],pps($v->v['name'],'root'));
			$class=(!empty($v->v['current'])?'current':'');
			if($k==(count($sm->el)-1))
				$class.=' last';
			$txt=pps($v->v['descr'],pps($v->v['name'],'root'));	
			$str1='';
			$ul='';
			foreach($v->el as $vv){ $ul.=$vv->getUlLI(0,0);}
			if(!empty($ul))
				{
					$ul='<ul '.$class.'>'.$ul.'</ul>';
				}

			$li.='<td class="menu_top"><a href="'.$v->getUrl().'" class="menu_top'.pp($class,' ','').'" style="background:url(uploaded/'.translit($txt).'2.gif) no-repeat"><img alt="'.htmlspecialchars($txt).'" src="uploaded/'.translit($txt).'1.gif"></a></td><td width="4px" style="padding-top:4px" class="menu_top"><img '.pp($class,'class="','"').' src="img/menu_bg1.gif"></td>';

		}
		return '<table align="right" class="menu_top1"><tr>'.$li.'</tr></table>';
	}

	function holiday_theme(){
		$sm=$this->getPar('theme1');
		$holiday='bg3_2.gif';
	
		if ($sm==3)  $holiday='bg3_3.png';
		if ($sm==2) $holiday='bg3_5.png'; 
		if ($sm==4) $holiday='bg3_4.png'; 



		return $holiday;
	}



	function init(){
		parent::init();
		$this->menu=array();
		$this->menu['head']=array('MAIN','_head_menu');
		$this->menu['right']=array('MAIN','_novinki','novinki_numb1');
		$this->menu['spec']=array('MAIN','_spec','spec_numb1');
		$this->menu['left']=array('MAIN','_hmenu');
	}

	function _short(){
		return smart_template(array(ELEMENTS_TPL,'shortspec'),array());
	}

	function handle($h){
//		debug(1111); debug('2222'. $h);
		if($h=="katalog"){
			$this->tpl='tpl_second';
			$this->menu['spec']=array('MAIN','_short');
			unset($this->menu['right']);
		}
	}

	function _first(){
		static $x;
		if(isset($x))return $x;
		//if ($this->cur_menu == 'main')
			//return '';
		if ($this->cur_menu!=$this->getPar('first_menu')){
			// подкаткегория...
			$x=$this->ffirst('_getCurList');
			array_shift($x);
			$y=array_shift($x);
			if($x[0]['name']=='catalogue')
				array_shift($x);
			if($x[0]['name']=='Продукция')
				array_shift($x);	
			if(!isset($this->nopoplast))
				if(empty($this->dop_zagl))
					$y=array_pop($x);
				else
					$y=$x[count($x)-1];
			else
				$y=array('name'=>$this->nopoplast);
			if (empty($x)) $x='';
			return $x=smart_template(array(ELEMENTS_TPL,'nfirst'),
				array('sub'=>$y['name'],'list'=>$x));
		} else
			return $x='<div style="padding-top:35px;"></div>';
	}
	
	function zagl(){
		static $x;
		global $forum_zagl;
		//echo $this->qwerty;
		if(isset($x))return $x;
		if ($this->cur_menu == 'main')
			return '';
		if ($this->cur_menu!=$this->getPar('first_menu')){
			// подкаткегория...
			$x=$this->ffirst('_getCurList');
			array_shift($x);
			$y=array_shift($x);
			if($x[0]['name']=='catalogue')
				array_shift($x);
		   if(!isset($this->nopoplast))
				$y=array_pop($x);
			else
				$y=array('name'=>$this->nopoplast);
			if (empty($x)) $x='';
			//var_dump(debug_backtrace());
			
			//Замена заголовка для темы форума
			if(!empty($this->dop_zagl))
				$y['name'] = $this->dop_zagl;
			return $x=smart_template(array(ELEMENTS_TPL,'nfirst2'),
				array('sub'=>$y['name'],'list'=>$x));
		} else
			return $x='<div style="padding-top:35px;"></div>';
	}
	/**
	 * Генерация главного окна приложения
	 */
	function do_Default(){
		$this->cur_menu=$this->getPar('first_menu');
		//$this->parent->par['anaons_forum'] = "123";
		return $this->do_menu('main');
		//return $this->ffirst("news_b"); //"Content какой-то";
	}
	/**
	 * Генерация раздела Статьи
	 */

	function rightb($r=''){
		if($r=='') $r=right_READ;
		return $this->has_rights($r);
	}
	function rightn($r=''){
		if($r=='') $r=right_READ;
		return !$this->has_rights($r);
	}
	function right($r=''){
		if($r=='') $r=right_READ;
		return $this->has_rights($r)?array():'';
	}

	function do_error(){
		return smart_template(array(ELEMENTS_TPL,'ermess'),' ');
	}

    function do_login(){
        $form=new form('login');
        $form->scanHtml($this->_tpl('tpl_jusers','_login',array(
				'cansave'=>defined('LOGIN_CANSAVE')?LOGIN_CANSAVE:true,
				'error'=>pps($_SESSION['errormsg']))));
        if($form->handle()){
        }
        return $form->getHtml(' ');
    }


	function do_add(){
		$this->sessionstart();
		foreach($_POST as $k=>$v){
			$_POST[$k]=iconv('utf-8','cp1251//IGNORE',$_POST[$k]);
		}
		$par=$_POST;
		$cnt=ppi($_POST['item'],1);
		unset($par['item'],$par['id']);
		$this->export('basket','addItem',pps($_POST['id']),$cnt,$par);

		if($this->is_ajax){
			$this->parent->ajaxdata['basket']=$this->export('basket','_basket');
			return 'Ok';		
		} else { // Это не ajax
			$this->go(pps($_SERVER['HTTP_REFERER'],toUrl(INDEX_PATH)));
		}
	}
}
$engine=&new engine('Auth','sitemap','news','altname');
//echo $engine->tpl;
$_GLOBALS['engine']=&$engine;
DO_IT_ALL();
//echo $engine->tpl;
?>
