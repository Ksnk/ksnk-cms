<?php
if(!defined('INTERNAL')){
    if(!defined('CHARSET')) define('CHARSET','windows-1251');
	header('Content-type: text/html; charset='.CHARSET);
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
//include_once('db_session.php');

/*/****** point site_includes */
include_once 'compiler.class.php';
template_compiler::checktpl();/****finish point site_includes *//*
*/
SUPER::option(array('jinja2'=>true));

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

    function do_menu($id=0){
        $id=ppi($id,$_GET['id']);
        switch($id){
            case 18:
                $this->par('color','gray');
                break;
            case 17:
            case 62:
                $this->par('color','dgreen');
                break;
            case 16:
                 $this->par('color','magenta');
                 break;
            case 15:
                 $this->par('color','violet');
                 break;
            case 14:
                $this->par('color','rose');
                break;
            default:
                $this->par('color','green');
        }
        if($id!=4)
            $this->tpl==SECOND_TPL;
       // debug($id);
        return parent::do_menu($id);
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

	function menu_1($tpl='',$color='gray'){
        // менюшка линейная с картиками вместо названий
        if(!$tpl) $tpl='main_menu';
		$sm=$this->ffirst('getSiteMap','main');
        $data = array();
		foreach($sm->el as $v){
			if(isset($v->v['skipit'])) continue;
			$data[]=$v->v;
		} 
  		return  $this->_tpl('tpl_jmain','_'.$tpl,array('menu'=>$data,'color'=>$color));
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
			return $x=
                    $this->_tpl('tpl_jelements','_nfirst',
                                array('sub'=>$y['name'],'list'=>$x)
                    );
		} else
			return $x='<div style="padding-top:35px;"></div>';
	}
	
	/**
	 * Генерация главного окна приложения
	 */
	function do_Default(){
		$this->cur_menu=$this->getPar('first_menu');
        //$this->tpl=SECOND_TPL;
		return $this->do_menu(4);
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

 }
$engine=&new engine('Auth','sitemap','news','altname');
DATABASE();
$engine->setPar('charset',CHARSET);
//echo $engine->tpl;
$_GLOBALS['engine']=&$engine;
DO_IT_ALL();
//echo $engine->tpl;
