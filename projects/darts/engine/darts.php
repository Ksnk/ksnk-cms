<?php
// no direct access! экая , однако, хрень....
defined('_DARTS') or die('Restricted access');

define('SETUP_COMPLETE',true);// после первого вызова - заменить на true навечно;

//define("right_READ",1);
//define("right_VIEW",right_READ);
//define("right_WRITE",2);
define("right_WRITENEWS",4);
define("right_ASK",8);
//define("right_TABLE",16);
define("right_TABLE",32);
//define("right_MULTY",64);
//define("right_CHAT",128);
//define("right_ADMIN",1024);
define("right_CHANGE_PASSWORD",2048);
define("right_CREATE_CLUB",4096);
define("right_CHANGE_USER",8192);

require_once('tournaments.php');

/**
 *   не смешная авторизация 
 */
class darts_Auth extends Auth {
	
	var $login;

	/**
	 * вызывается ajax'ом
	 * Enter description here ...
	 */
	function do_killuser(){
		if (!$this->parent->has_rights(right_ADMIN)){
			$this->parent->error(SUPER::_l(mess_sorry_you_have_no_right));
			return " ";
		}
		$id=pps($_GET['id']);
		if (empty($id)) return "";
		if($id==$_SESSION['USER_ID']){
			$this->parent->error("Нельзя удалить самого себя!");
		} elseif(!$this->parent->delRecord(array('id'=>$id))){
			$this->parent->error("Нету такой записи!");
			$this->parent->ajaxdata['event']="success";
		} else {
			$this->parent->ajaxdata['event']="success";
		}	
		return ' ';
	}
	/**
	 * вызывается ajax'ом
	 * Enter description here ...
	 */
	function do_setright(){
		if (!$this->parent->has_rights(right_ADMIN)){
			$this->parent->error(SUPER::_l(mess_sorry_you_have_no_right));
			return " ";
		}
		$id=pps($_GET['id']);
		if (empty($id)) return "";
		$user=$this->parent->readRecord(array('id'=>$id));
		$right=array_sum($_POST['right']);
		//echo $right;
		$user['right'][$_GET['club']]=$right;
		$this->parent->writeRecord($user);
		return ' ';
	}
	function do_remright(){
		if (!$this->parent->has_rights(right_ADMIN)){
			$this->parent->error(SUPER::_l(mess_sorry_you_have_no_right));
			return " ";
		}
		$id=pps($_GET['id']);
		if (empty($id)) return "";
		$user=$this->parent->readRecord(array('id'=>$id));
		unset($user['right'][$_GET['club']]);
		$this->parent->writeRecord($user);
		return ' ';
	}
/**
 * список юзеров сайта
 */
	function do_userlist(){
		if (!$this->parent->has_rights(right_ADMIN)){
			return SUPER::_l(mess_sorry_you_have_no_right) ;
		}
		
		return $this->parent->_tpl('tpl_main','_ListUser',array(
			'users'=>$this->parent->readRecords(array('record'=>'user'))
		));
	}
	
	/**
	 * Добавить в сессию права
	 */
	function invite($id){
		
	}
	
/**
 * Проверить юзера. Автоматический хандл формы регистрации
 * - вычисляем клуб, для посещения юзером
 */
	function _check (){
		$this->parent->sessionstart();
		if($this->auth_check($_POST['login_name']
			,$_POST['login_pass'],pps($_POST['login_save']))
		){
			// поле lastclub записи юзера позволяет выбрать текущий 
			// клуб для построения меню по умолчанию.
			
			if(isset($this->parent->user['lastclub']))
				$club=$this->parent->user['lastclub'];
			else{
				$list=$this->parent->rights->list_right();
				foreach($list as $k=>$v){
					if(preg_match('/^tir_(\d+)$/',$k,$m)){
						$club=$m[1];
                        if(!!tournament::getTournament($club)){
                        	break;
                        } 
					}
				}
			}
			// отмечаем клуб для вывода меню.
			$_SESSION['lastclub']=$club;
			$url=parse_url($_SERVER['HTTP_REFERER']);
			//if(($_GET['do']!='user') && !empty($url['query']))
			//	$this->parent->go($url['query']);
			if($_GET['do']=='user')
				$this->parent->go('');
			else
				$this->parent->go('',$_SERVER["REQUEST_URI"]);
	//		
		};
	}
	
	/**
	 * Заводим новый аккаунт и тир к нему
	 */
	function do_register(){
		$this->parent->sessionstart();
		
		// права не проверяем - любой юзер может завести новый тир
		$this->parent->read_Parameters();
		if(empty(SUPER::$engine->debug))
			$this->database->do_log=true;
		$form=new form('tir');
		$par=array();
		if (!empty($_SESSION['regError'])){
			$par['error']=$_SESSION['regError'];
			$_SESSION['regError']='';
		}	
		$par['user']=$this->parent->user;
        debug($par);
		$form->scanHtml($this->parent->_tpl('tpl_main','_Regnew',$par));
		if ($form->handle()) {
		//debug($_SESSION);
			$error = array();
			if (!isset($_SESSION["captcha"]) || $_SESSION["captcha"]!==$form->var["captcha"]) {
				$error['captcha']='Неверно введен номер';
			}
				
			foreach($form->var as $k=>$v) {
				$form->var[$k]=trim($v);
			}
			
			if ($form->var['fpassword']!=$form->var['rpassword']){
				$error['password']='пароли не совпадают';
			}
			
			// регистрация аккаунта
			$key=array('record'=>'user'
				,'name'=>trim(strip_tags($form->var['user']))
			);
			
			$user=$this->parent->readRecord($key);
			if(!empty($user) && !empty($user['id'])){
				$error['akk']='аккаунт с таким именем уже существует';
			}
			
			if(!empty($error)){
				$_SESSION['regError']=$error;
				debug($_SESSION);
				$this->parent->go($this->parent->curl());
			}
			
			// заводим аккаунт с именем
			$user['password']=$form->var['fpassword'];
            $user['type']=$form->var['aktype'];
			if($userid=$this->parent->writeRecord($user)){
			// регистрация и вход
				$_SESSION['USER_ID']=ppi($userid);
			};
			$user['ID']=$userid;
			$this->parent->export('darts_News','add_News',
				$this->parent->_tpl('tpl_main','_news_tpl'
					,array('tpl'=>'newuser','user'=>$user)
				)
			);
			$this->parent->go($this->parent->curl());
		}
		// заполняем значения
		$form->var['captcha']='';
		return $form->getHtml(' ');
	}
	
}

/**
 *   Список игроков
 *
 */
class darts_Players  extends plugin{

    /**
     * ajax. Выдать информацию о игроке
     * @return string
     */
	function do_playerinfo(){
		if (!$this->parent->has_rights(right_WRITE)){
			$this->parent->error(SUPER::_l(mess_sorry_you_have_no_right) );
			return ' ';
		}
		$data=plr::select($_GET['id']);
		$this->parent->ajaxdata=$this->parent->_tpl('tpl_main','_PlayerInfo',array('data'=>$data));
		return 'ok';
	}

    /**
     * ajax. Изменить игрока
     * @return string
     */
	function do_changeplayer(){
		if (!$this->parent->has_rights(right_WRITE)){
			$this->parent->error(SUPER::_l(mess_sorry_you_have_no_right) );
			return ' ';
		}
        plr::update($_POST['name1'],$_POST['name2'],$_POST['id']);
		return 'ok';
	}

    /**
     * ajax. удалить игрока
     * @return string
     */
	function do_delplayer(){
		if (!$this->parent->has_rights(right_WRITE)){
			$this->parent->error(SUPER::_l(mess_sorry_you_have_no_right) );
			return ' ';
		}
		$tournament=tournament::getTournament(ppi($_GET['id']));
		if($tournament) {
			$tournament->deleteplayer(ppi($_POST['id']));
		};
		
		return 'ok';
	}

    /**
     * ajax. Добавить игрока
     * @return string
     */
	function do_addplayer(){
		if (!$this->parent->has_rights(right_WRITE)){
			$this->parent->error(SUPER::_l(mess_sorry_you_have_no_right) );
			return ' ';
		}
		$id=ppi($_GET['id']);
		$tournament=tournament::getTournament($id);
		if(!$tournament) return 'Oops!'.$id;
		// добавить юзера в адресную книгу + добавить юзера в клуб + добавить юзера в турнир
		// addrbook
		if(!($player=plr::find_by_name($_POST['name1'],$_POST['name2'])))
        {
			$player=plr::insert($_POST['name1'],$_POST['name2']);
		};
		// добавить юзера в адресную книгу + добавить юзера в клуб + добавить юзера в турнир
		$x=$tournament->result(array('ID_PLAYER'=>$player));
		if(!empty($x))
			return 'ok';
		$tournament->addplayer(intval($player));
		$t=$tournament;
		do {
			$t->prepParent();
			$t=$t->parent;
		} while(!empty($t) && $t->get('LEVEL')>0);
		if($t)$t->addplayer(intval($player));
		return 'ok';
	}

    /**
     * шаблон.
     * вернуть имя игрока по его ID
     * @param $id
     * @return
     */
	function playerById($id){ 
		static $names;
		if(empty($names)){
			$names=plr::select();
			foreach($names as $x) {
				if(isset($x['ID']))
				$names[$x['ID']]['name']=trim($x['NAME'].' '.$x['NAME1'].' '.$x['NAME2']);
			}
            debug ($names);
		}
        debug ($id);
		return $names[$id]['name'];
	}

    /**
     * вернуть массив игроков из предшествующего турнира с отметками играющих
     * @param int $id
     * @return array
     */
    function _usedplayers($trn=0){
        /* @var tournament $tournament */
        if(empty($trn)) $trn=$_SESSION['lastclub'];
        $tdata=trn::select($trn);
        if(!empty($tdata['PARENT'])) {
		$names=DATABASE()->select(
            'SELECT ppp.`NUMBER`, pp.ID_PLAYER as `selected`, p.* '
            .'FROM darts_tourplayers as ppp '
            .'left join darts_players as p on p.ID=`ID_PLAYER` '
            .'left join (select * from darts_tourplayers where ID_TOURNAMENT=?) as pp on ppp.`ID_PLAYER`=pp.ID_PLAYER '
            .'where ppp.`ID_TOURNAMENT`=? order by p.NAME, p.NAME1'
			,$trn,$tdata['PARENT']
			);
        } else {
            $names=DATABASE()->select(
            'SELECT ppp.`NUMBER`, p.* '
            .'FROM darts_tourplayers as ppp '
            .'left join darts_players as p on p.ID=`ID_PLAYER` '
            .'where ppp.`ID_TOURNAMENT`=? order by p.NAME, p.NAME1'
			,$trn
			);
        }
		foreach($names as &$x) {
			$x['name']=trim($x['NAME'].' '.$x['NAME1'].' '.$x['NAME2']);
		}
		return $names;
	}
	
	/**
	 * список доступных клубов для зарегистрированного игрока
	 * -- выводим список прав tir_(\d+)
	 * -- вычисляем список клубов
	 * -- сохраняем его в сессии
	 */
	function _clublist($id=0){
		//debug('hello');
		static $clubs;
        if(!empty($id)) {
            $x=trn::select($id);
            debug(trn::unpack($x));
            return  $x;
        }
		if(!empty($clubs)) return $clubs;
		if ($this->parent->has_rights(right_ADMIN)){
            $clubs= trn::select_childs(0);
			return $clubs;
//			return self::_l(mess_sorry_you_have_no_right);
		} else {
			$list=&$this->parent->rights->list_right();
			debug('xxx',$list);
			$clubs=array();
            $changed=false;
            $user=$this->parent->user;
			foreach($list as $k=>$v){
				if(preg_match('/^tir_(\d+)$/',$k,$m)){
                    if(!tournament::getTournament($m[1])){
                        if(is_array($user['right'])){
                            unset($user['right']['tir_'.$m[1]]);
                            $changed=true;
                        }
                    } else {
					    $clubs[]=$m[1];
                    }
				}
                if($changed)
                    $this->parent->writeRecord($this->parent->user);
			}
			if(!empty($clubs)){
				$clubs= DATABASE()->select('select ID,NAME,DESCR from ?_tournaments where `ID` in ('.implode(',',$clubs).') ORDER BY `NAME`;');
				if((count($clubs)>0) && (empty($_SESSION['lastclub']))){
					$_SESSION['lastclub']=$clubs[0]['ID'];
					$this->parent->par['lastclub']=$_SESSION['lastclub'];
				}
				return $clubs;
			}
		}
		return array();
	}
	/**
	 * список доступных клубов для зарегистрированного игрока
	 * -- выводим список прав tir_(\d+)
	 * -- вычисляем список клубов
	 * -- сохраняем его в сессии
	 */
	function _list($style='',$trn=0,$sort='NUMBER'){
		static $res;
		$names=array();
		$trn=ppi($trn,$_SESSION['lastclub']);
		if (isset($res[$trn][$style])) return $res[$trn][$style] ;
		$names=DATABASE()->select(
			'SELECT `NUMBER`,  p.* FROM ?_tourplayers left join ?_players as p on p.ID=`ID_PLAYER` where `ID_TOURNAMENT`=? order by '
			.($sort=='NUMBER'?'`NUMBER`, `NAME`, `NAME1`':'`NAME`, `NAME1`').';'
			,$trn
			);
		foreach($names as $k=>$x) {
			$names[$k]['name']=trim($x['NAME'].' '.$x['NAME1'].' '.$x['NAME2']);
		}
		if($style=='keys'){
			$pnames=array();
			for($i=0;$i<count($names);$i++){
				$x=$names[$i];
				$pnames[$x['ID']]=$x['name'];
			};
			$res[$trn][$style]=$pnames;
		} else
			$res[$trn][$style]=$names;
		return $res[$trn][$style];
	}

/**
 * форма вывода информации и истории игр игрока в клубе
 * @return string
 */
	function do_player(){ //TODO: мертвая форма - исправить
		//return 'Hello!';
		if (!$this->parent->has_rights(right_VIEW)) return self::_l(mess_sorry_you_have_no_right) ;
		$form=new form('player');
		$id=pps($_GET['id']);
		$this->parent->par['title']=($id?'Изменить данные игрока':'Добавить игрока');
		//photo
		$data=plr::select($id);
		$data['photo']=toUrl($data['PHOTO']);
		$form->scanHtml($this->parent->_tpl('tpl_main','_player_form',$data));

		if($form->handle()){;
			// какой батон нажат?
			include_once('functions/pic.inc.php');
			foreach($form->files as $k=>$v){
				$vv='data/xxx'.$id.'.jpeg';
				echo $par['error'];
				ob_start();
				img_resize($k,'',150,150,-1,70);
				$s=ob_get_contents(); ob_end_clean();
				$vv=TMP_DIR.sprintf('%u',crc32($s)).'.jpeg';
				file_put_contents($vv,$s);
				if($form->var['PHOTO']) @unlink($tir->var['PHOTO']);
				$form->var['PHOTO']=$vv;
				@unlink($k);
			}

			$key=$form->var; //unset($key['players']) ;
			if($id){
				plr::update($key,$id);
			} else {
				$id=plr::insert($key);
			}
			$this->parent->go();
		}
		if($id){
			$form->var=array_merge($form->var,plr::select($id));
		}
		return $form->getHtml(' ');//._export(__CLASS__,'_list_games');
	}

	/**
     * вывод информации об играх игрока в клубе
     * todo:: мертвая форма -
	 * выдать список игр, в которых принимал участие игрок
	 */
	function _list_games(){
		$id=pps($_GET['id']);
		$plist=_export('darts_Players','_list');
		$tourn=$this->parent->export('darts_Tourn','_list','');
		$rules=_export('DARTS','game_array');
		$gmlist=DATABASE()->select(
			'SELECT t1.DATE, t1.RULE, t1.ID_TOURNAMENT, '.
			't2.ID_PLAYER as `ID_PLAYER1`,t2.SCORE as `SCOREGAME1`, t2.TRACE as `TRACE1`, t3.* '.
			'FROM ((?_tournaments LEFT JOIN ?_games as t1 '.
			'ON ?_tournaments.ID = t1.ID_TOURNAMENT) '.
			'LEFT JOIN ?_gplayers as t2 ON t1.ID = t2.ID_GAME) '.
			'LEFT JOIN ?_gplayers AS t3 ON t1.ID = t3.ID_GAME '.
			'WHERE (t2.ID_PLAYER=?) '.
			'AND ((t2.ID_PLAYER!=t3.ID_PLAYER)) '.
			'order by `DATE` DESC ;'
			,$id);

		setlocale(LC_ALL,'ru_RU.CP1251','ru_RU','rus');
		foreach($gmlist as $k=>$v) {
			$gmlist[$k]['xDATE']=$v['DATE'];
			if (preg_match('/((\\d\\d\\d\\d)-(\\d\\d)-(\\d\\d)\\s+)?((\\d\\d):(\\d\\d)(:(\\d\\d))?)?/A',
							$v['DATE'], $m))
			{
				$gmlist[$k]['DATE']=
				iconv('windows-1251','UTF-8'
					,strftime('%a, %d %b %H:%M'
						,mktime((int)@$m[6], (int)@$m[7], (int)@$m[9]
							, (int)@$m[3], (int)@$m[4], (int)@$m[2]))
				);
			}
			// rules
			if(isset($rules[$v['RULE']]))
				$gmlist[$k]['RULE']=$rules[$v['RULE']];
			$gmlist[$k]['ID_PLAYER']=arr_scan($plist,'ID',$v['ID_PLAYER'],'name');
			$gmlist[$k]['ID_TOURNAMENT']=arr_scan($tourn,'ID',$v['ID_TOURNAMENT'],'NAME');
			$gmlist[$k]['SCOREGAME1']=trim($v['SCOREGAME1']);
			$gmlist[$k]['SCOREGAME']=trim($v['SCOREGAME']);
		}

		return //*---*/print_r($gmlist);/*
			$this->parent->_tpl('tpl_main','_gamelist',array('rows'=>$gmlist));/**/
	}

//    ввести   результаты   игр   игроков   x   и   y.   SQL  для  игр  из  2-х
//  игроков!!!!!!!!!!!!!!

/**
 * переход на калькулятор с заполнением информации об игроках
 * todo: мертвая форма
 * @return string
 */
	function do_play(){
		$pnames=$this->_list('key');
		
		$y=pps($_REQUEST['y']);
		$x=pps($_REQUEST['x']);
		$id=pps($_REQUEST['id']);
		$gmlist=DATABASE()->select(
			'SELECT t1.DATE, t1.RULE, t2.ID_PLAYER as `ID_PLAYER1`,'.
			't2.SCORE as `SCORE1`, '.
			't2.TRACE as `TRACE1`, '.
			't3.SCORE as `SCORE`  '.
			'FROM ((?_tournaments LEFT JOIN ?_games as t1 '.
			'ON ?_tournaments.ID = t1.ID_TOURNAMENT) '.
			'LEFT JOIN ?_gplayers as t2 ON t1.ID = t2.ID_GAME) '.
			'LEFT JOIN ?_gplayers AS t3 ON t1.ID = t3.ID_GAME '.
			'WHERE ((?_tournaments.ID=?) '.
			'AND (t2.ID_PLAYER=?) AND (t3.ID_PLAYER=?));'
			,$id,$x,$y);
		$turn=trn::select($id);
		$game=array();
		$par='';
debug($gmlist);
		$form=new form('DoPlay');
		$par=array();
		$par['player']=$pnames[$x];
		$par['player1']=$pnames[$y];
		$par['number']=6;
		$form->scanHtml($this->parent->_tpl('tpl_main','_DoPlay',$par));
		if($form->handle()){
		};
		// вывод информации
		$i=0;
		foreach($gmlist as $v){
			$i++;
			$form->var['date_'.$i]=$v['DATE'];
			$form->var['rule_'.$i]=$v['RULE'];
			$form->var['score_'.$i]=$v['SCORE'];
			$form->var['score1_'.$i]=$v['SCORE1'];
		}
		// формируем ссылку на игру в darts

		$pl=array();
		foreach(array($x,$y) as $i){
			$pl[]="name:'".$pnames[$i]."',id:".$i ;
		}
		$_SESSION['DART_INIT']=array('realgame'=>"*/ " .
				"startgame={players:[{".implode("},{",$pl).
				"}],".pp($turn['RULE'],'rule:"','",')."tour: ".$id."};");
		$par.='<a href="?do=playdarts&id='.$id.'"> Калькулятор </a>';
		$rec=$this->parent->export('darts_Calendar','plan_button');
		$par.='&nbsp;<a href="'.$rec['link'].'&id='.$id.'">'.$rec['data'].'</a>';

		return '<fieldset>'.$form->getHtml(' ').'</fieldset>';
	}

}
/**
 *   Список турниров
 *
 */
class darts_Tourn extends plugin {
	function _title($id,$join='|'){
		$query='select d6.ID,d6.NAME,d5.ID,d5.NAME,d4.ID,d4.NAME,d3.ID,d3.NAME,d2.ID,d2.NAME,d1.ID,d1.NAME 
 from '.TAB_PREF.'_tournaments as d1 
 left join '.TAB_PREF.'_tournaments as d2 on d1.PARENT=d2.ID
 left join '.TAB_PREF.'_tournaments as d3 on d2.PARENT=d3.ID
 left join '.TAB_PREF.'_tournaments as d4 on d3.PARENT=d4.ID
 left join '.TAB_PREF.'_tournaments as d5 on d4.PARENT=d5.ID
 left join '.TAB_PREF.'_tournaments as d6 on d5.PARENT=d6.ID
where d1.ID='.intval($id).';';
		$start=mkt();
		debug($id,$query);
		$result=mysql_query($query);
		if (!$result) {
		   $message  = 'Invalid query: ' . mysql_error() . "\n";
		   $message .= 'Whole query: ' . $query;
		   debug($message);
		   return array();
		}
		if ($row = mysql_fetch_row ($result)) {
			$x=array_diff($row,array(null));
		} else 
			return array(0,'yyy');
		debug(mkt()-$start);//,$x);
		return $x;
	}
// список турниров для меню
	/**
	 * рекурсивная выкачка турниров
	 * Enter description here ...
	 * @param unknown_type $id
	 */
	function getList($id,$date=false){
		static $res; 
		if(isset($res[$id])) return $res[$id];
		$data= DATABASE()->select(
		'SELECT * FROM ?_tournaments where PARENT=? and `RULE`<>"meeting" and `RULE`<>"game" '
		.($date?'order by DATE DESC ;':'order by NAME ;')
		,$id) ;
		if(!empty($data))
			foreach($data as &$d){
				$ddata=$this->getList($d['ID']);
				if(!empty($ddata))
					$d['childs']=$ddata;	
			}
		return ($res[$id]=$data);
	}
	
	function _list($all=null,$trn=0){
		if (!$this->parent->has_rights(right_TABLE)){
			return array();	
		}
		$trn=ppi($trn,$_SESSION['lastclub']);
		return $this->getList($trn,true);
	}
	
	function do_recalc(){
		if (!$this->parent->has_rights(right_WRITE)){
			return self::_l(mess_sorry_you_have_no_right) ;
		}
		$id=ppi($_GET['id']);
		if(!($tournament=tournament::getTournament(ppi($_GET['id'])))) 
			return 'fail';		
		if($tournament->finished()){
			DARTS::rollback($tournament,true);
		}
		$tournament->clearscore();
		$tournament->finished(false);
		$tournament->prepChilds();
		foreach($tournament->childs as &$child){
			DARTS::rollback($child,false);
		}
		DARTS::checkFinish($tournament);
		return 'Ok';
	}
	// вставляем новый турнир 1 уровня 
	/**
	 * добавить турнир к групповому турниру
	 * - нажали на кнопку добавить турнир на панели турниров
	 */
	function do_addtrn(){
		if (!$this->parent->has_rights(right_WRITE)){
			return self::_l(mess_sorry_you_have_no_right) ;
		}
		//print_r($_POST);
		$id=pps($_GET['id']);
		$tournament=tournament::getTournament($id);
	 	if(!empty($tournament) && method_exists($tournament,'addChild')){
	 		$child=$tournament->addChild();
	 		$this->parent->ajaxdata=$child->getId();
			return 'ok';
	 	}
	 	return 'fail';
	}
	/**
	 * добавить турнир к групповому турниру
	 * - нажали на кнопку добавить турнир на панели турниров
	 */
	function do_deltrn(){
		if (!$this->parent->has_rights(right_WRITE)){
			return self::_l(mess_sorry_you_have_no_right) ;
		}
		if($tournament=tournament::getTournament($_GET['id'])){
			$tournament->delete();
			return 'ok';
		};
		return 'fail';
	}
	
	/**
	 * создать туринирную сетку для турниров "финка" и "олимпийская система"
	 * Enter description here ...
	 */
	function do_solve(){
		// создаем комплект турниров - meeting с 
		// условиями завершения - переход в другие турниры
		
		// финка для 8 игроков
		if (!$this->parent->has_rights(right_WRITE)){
			return self::_l(mess_sorry_you_have_no_right) ;
		}
		//print_r($_POST);
		$id=pps($_REQUEST['id']);
		if(empty($id))
			return 'fail';
			
		$tournament=tournament::getTournament($id);
		if(method_exists($tournament,'solve')){
			$tournament->solve();
		}
		return 'ok';
	}
	
	/**
	 * сохранить готовый турнир
	 * - нажали на кнопку save панели турниров
	 */
	function do_save(){
		if (!$this->parent->has_rights(right_WRITE)){
			return self::_l(mess_sorry_you_have_no_right) ;
		}
		//print_r($_POST);
		$id=pps($_POST['id']);
        $key=array(
			 'NAME'=>trim($_POST['NAME'])
			,'STATUS'=>$_POST['STATUS']
			,'RULE'=>pps($_POST['RULE'],'table')
			,'ASCORE'=>$_POST['ASCORE']
            ,'AGPARAM'=>$_POST['AGPARAM']
			);
        if(empty($key['NAME']))
            return 'fail - name';
		if(empty($id)){
            if(empty($_GET['pid']))
                return 'fail - parent';
            $tournament=tournament::getTournament($_GET['pid'])->addChild($key);
            if(!empty($tournament))
                $this->parent->ajaxdata['complete']='reload();';
            //создаем новый турнир
			//;
        } else {
			$tournament=tournament::getTournament($id);
            if($tournament->get('NAME')!=$key['NAME'])
                $this->parent->ajaxdata['complete']='reload();';
            $tournament->set($key);
        }
	 	if(empty($tournament))
	 		return 'fail - tournament ';

		// игроки 
		$players=array_flip($_POST['players']);
		$_2del=array();
		if(!empty($tournament->tresult)){
			foreach($tournament->tresult as $k=>$result){
				$_2del[$result['ID_PLAYER']]=$k;
			}
			foreach($tournament->tresult as &$result){
				if(isset($players[$result['ID_PLAYER']])){
					unset($players[$result['ID_PLAYER']]);
					unset($_2del[$result['ID_PLAYER']]);
				}	
			}
		}
		if(!empty($players))
			foreach($players as $k=>$p){
				$tournament->addplayer($k);
			};

		if(!empty($_2del))
			foreach($_2del as $p){
				unset($tournament->tresult[$p]);
			};
//		print_r($_2del);
		$tournament->save();
		return 'ok';
	}
	
	
	function do_newtour(){
		$form=new form('tour');
		$form->nostore=true;
		$par=array();

		$id=pps($_GET['id']);

		$this->parent->par['id']=$id;
        /* @var tournament $tournament */
		$tournament=tournament::getTournament($id);
		$this->parent->ajaxdata=$this->parent->_tpl('tpl_main','_NewTourn'
            ,array('pid'=>ppi($_GET['pid']),'tour'=>empty($tournament)?array():$tournament->get()));
		return ' ';
	}
}

/**
 *  Самый главный класс
 */
class darts_Main extends engine_Main {
	
	function do_writeus(){
		if (defined('SECOND_TPL')){
			$this->tpl=SECOND_TPL;
		}
		return $this->export('qa','do_writeus');
	}
/**
 * @param int $parent
 * @return string
 */
        function build1lev(&$tournament=null){
            if(is_null($tournament)) return '<li>#NULL#</li>';
            $data=$tournament->get();$data=array_diff($data,array('DESCR'=>''));
            $res='<li>'.$tournament->get('NAME','#undefined#')
                .'<br>DATA:'.$this->xprint($data)
                .'<br>TRESULT:<div style="display:inline-block;">'.$this->xprint($tournament->tresult)
                .'</div>';
            $tournament->prepChilds();
            $x='';
            foreach($tournament->childs as $v){
                $x.=$this->build1lev($v);
            }
            return $res.(empty($x)?'':'<ul>'.$x.'</ul>').'</li>';
        }


        function xprint(&$var){
            if(is_null($var)) return 'NULL';
            if(is_numeric($var)) return $var;
            if(is_string($var)) return '"'.htmlspecialchars($var).'"';
            if(is_array($var)){
                $res=array();
                foreach($var as $k=>&$v){
                    $res[]='<b>'.$k.':</b>'.$this->xprint($v);
                }
                return '['.implode(', ',$res).']';
            };
            return '#donknowtf#';
         }

    /**
     * список юзеров сайта
     */
    function do_structure(){
		if (!$this->parent->has_rights(right_ADMIN)){
			return SUPER::_l(mess_sorry_you_have_no_right) ;
		}

        $res='';
        $rows= trn::select_childs(0);
        foreach($rows as $v){
            /* @var tournament $tournament */
            $res.=$this->build1lev(tournament::getTournament($v['ID']));
        }

 		return $res;
	}
	
	function do_user(){
		$_POST['login_name']=$_GET['id'];
		return $this->export('darts_Auth','_check');
	}
	
	function do_deltourn(){
		if (!$this->parent->has_rights(right_WRITE)){
			$this->parent->error(self::_l(mess_sorry_you_have_no_right)) ;
			return  ' ';
		};
		if($tournament=tournament::getTournament($_GET['id'])){
			$tournament->delete();
		};
        if($_GET['id']==$_SESSION['lastclub']){
            $this->setup_lastclub();
        }
		return ' ';
	}
	/**
	 * сгенерировать ajax'ом общий вход
	 */
	function do_common(){
		if (!$this->parent->has_rights(right_WRITE)){
			$this->parent->error(self::_l(mess_sorry_you_have_no_right)) ;
			return  ' ';
		};
        $id=ppi($_GET['id']);
        if(empty($id)) return 'fail';
// регистрация аккаунта
        /* @var tournament $tournament */
        $tournament=tournament::getTournament($id);
        $rights=array_sum($_POST['right']);
        $linkname='LINK_'.$rights.'~'.pps($_POST['date']);
        $link=$tournament->get($linkname);
        if(empty($link)){
            $club='tir_'.$id;
            $link=md5($club.' '.$rights).'_'.base_convert(time(),'10','36');
            $user=array('record'=>'user','name'=>$link);
            
            $user=$this->parent->readRecord($user);
            if(!empty($_POST['date'])){
                $user['date']=strtotime($_POST['date'].' day');
            }

            // заводим аккаунт с именем
            if(!isset($user['id']))	{
                $user['right']=array();$user['right'][$club]=$rights;
                $user['created']=time();
                $user['password']='';
                $this->parent->writeRecord($user);
            }
            $tournament->set(array($linkname=>$link));
            debug($linkname,$link);
            $tournament->save();
        } 
		$this->parent->ajaxdata['html']='Скопируйте <a href="http://'.$_SERVER["SERVER_NAME"]
			.'<%=$target_dir%>/user/'.$link.'">ссылку</a> и перешлите ее игрокам</br>'
            .'<center><input style="width:250px" value="http://'.$_SERVER["SERVER_NAME"].'<%=$target_dir%>/user/'.$link.'"></center>';
		return ' ';			
	}
	
	function template($par=array(),$echo=true){
		$par['title']='Здесь играют в Дартс';
		$par['pleft']='200';
		$par['pright']='250';
		//debug($this->parent->par);
		echo $this->_tpl($this->tpl,'_',$par,$echo);
	}
	
	/**
	 *   Выдать создать child-турнир для турнирной таблицы турнира $id игроков X и Y
	 */
	function do_child(){
		if (!$this->has_rights(right_READ)) return '';
		$id=intval($_GET['id']);
		$x=intval($_GET['x']);
		$y=intval($_GET['y']);
		
		$tournament=tournament::getTournament($id);
		$x=$tournament->getChild($x,$y);
		//debug($x);
		if(!empty($x))
			$this->parent->go('?do=showtour&id='.$x->getId());
		return 
			'Ашипка, однако  :(';
	}
	
	function do_playdarts(){
		// заказать новую игру
		if(pps($_SESSION['DART_INIT'])){
			$data=$_SESSION['DART_INIT'];
			$data['lang']='ru';
			$data['translation']=preg_replace('~^.*<body>|</body>.*$~im','',file_get_contents('ru.html',true));
			$this->parent->go('darts.html');
			//echo $this->parent->_tpl('tpl_main','_'darts.html',$data);
			exit;
		}else
			$this->parent->go('do=showtour&id='.$id);
	}
	
	function show_meeting($tournament){
		$tpl='show_meeting';
		$form=new form($tpl);//'show_'.$tournament->tournament['RULE']);
		$form->nostore=true;
		$form->scanHtml($this->parent->_tpl('tpl_main','_'.$tpl,$tournament->getTable()));
		$tournament->prepChilds();
		if ($form->handle() && $this->parent->has_rights(right_READ)) {
			
			// попытка поднять чилдов
		 	// безусловная запись 
			$childs = array_values($tournament->childs);
			$i=0;
            $tournament->prepParent();
            /* @var $tournament tournament */
            $AGPARAM=$tournament->parent->get('AGPARAM');
            debug('AGPARAM',$AGPARAM);
			foreach($childs as &$child){
		 		$i++;
                if(empty($AGPARAM))
                    $tournament->set('AGPARAM',$_POST['rule_'.$i]);
                else
                    $tournament->set('AGPARAM',$AGPARAM);
		 		for($j=1;$j<=2;$j++){
		 			if(!empty($form->var['score_'.$j.'_'.$i])){
						$child->setresult($j,1,$form->var['score_'.$j.'_'.$i]);
		 			} 
		 			if(!empty($form->var['scorex_'.$j.'_'.$i])){
						$child->setresult($j,2,$form->var['scorex_'.$j.'_'.$i]);
		 			}
		 		}
			} ;
			for ($i=$i+1;$i<6;$i++){
	 		 	$child=tournament::createTournament(array(
					 'LEVEL'=>$tournament->get('LEVEL')+1
					,'RULE'=>'game'
					,'PARENT'=>$tournament->getId()
					,'NAME'=>'лег '.($i) 
				));
                if(empty($AGPARAM))
                    $tournament->set('AGPARAM',$_POST['rule_'.$i]);
                else
                    $tournament->set('AGPARAM',$AGPARAM);
 				$child->addplayer($tournament->getPlayerByNumber(1));
				$child->addplayer($tournament->getPlayerByNumber(2));
				for($j=1;$j<=2;$j++){
					if(!empty($form->var['score_'.$j.'_'.$i])){
						$child->setresult($j,1,$form->var['score_'.$j.'_'.$i]);
					} else {
						$child->changed=false;
						break 2;
					}
					if(!empty($form->var['scorex_'.$j.'_'.$i])){
						$child->setresult($j,2,$form->var['scorex_'.$j.'_'.$i]);
					}
				}
			}
			if(isset($_POST['recalc'])){
				if($tournament->finished()){
					DARTS::rollback($tournament,true);
				}
				$tournament->clearscore();
				$tournament->finished(false);
				foreach($tournament->childs as &$child){
					DARTS::rollback($child,false);
				}
			} else if(!isset($_POST['recalc']))
				for($i=1;$i<=2;$i++) for($j=1;$j<=4;$j++)
					$tournament->setresult($i,$j,$form->var['RES'.$j.'_'.$i]);
			DARTS::checkFinish($tournament);
			$this->parent->go($this->parent->curl());
		 	//debug($form->var);
		}
		$i= 0;
		foreach($tournament->childs as $child){
			$i++;
			$form->var['date_'.$i]=$child->get('DATE');
			$form->var['rule_'.$i]=$child->get('AGPARAM');
			$form->var['score_1_'.$i]=$child->tresult[0]['RES1'];
            $form->var['score_2_'.$i]=$child->tresult[1]['RES1'];
 			$form->var['scorex_1_'.$i]=$child->tresult[0]['RES2'];
			$form->var['scorex_2_'.$i]=$child->tresult[1]['RES2'];
        }
        for($i=1;$i<=2;$i++) for($j=1;$j<=4;$j++)
        $form->var['RES'.$j.'_'.$i]=$tournament->result($i,$j);
        //debug($form->var,$tournament->childs);
        return $form->getHtml( ' ');
    }

    /**
     * заполнение lastclub
     * @return void
     */
    function setup_lastclub(){
        $club=0;
        $list=$this->parent->rights->list_right();
        foreach($list as $k=>$v){
            if(preg_match('/^tir_(\d+)$/',$k,$m)){
                $club=$m[1];
                break;
            }
        }
        if(!empty($club)) $_SESSION['lastclub']=$club;
    }

    /**
     * хандлер для реакции на непосредственный логин
     * @return void
     */
    function onJustLogin(){
        $this->setup_lastclub();
    }

	/**
	 * регистрация клуба - это регистрация турнира нулевого уровня
	 */
	function do_club(){
		$this->parent->sessionstart();
		if (!isset($this->parent->user['id'])) 
			return self::_l(mess_sorry_you_have_no_right);
		$par=array();
		if(!empty($_GET['id'])){
			$id=intval($_GET['id']);
			$par['id']=$id;
			$formname='Club';
			$clubs = $this->parent->export('darts_Players','_clublist');
			foreach($clubs as &$c){
				if($c['ID']==$id){
					$par['par']=$c;
					break;
				}
			}
			$_SESSION['lastclub']=$id;
			$this->parent->par['lastclub']=$id;
		} else {
			$formname='Regclub';
		}	
		//debug('data',$par,$formname);
		$form=new form($formname);
		if (!empty($_SESSION['regError'])){
			$par['error']=$_SESSION['regError'];
			$_SESSION['regError']='';
		}	
		if($formname=='Club')
			return $this->parent->_tpl('tpl_main','_'.$formname,$par);
		$form->scanHtml($this->parent->_tpl('tpl_main','_'.$formname,$par));
        if ($form->handle() && ($formname=='Regclub' || $this->parent->has_rights(right_READ))) {
			$error = array();
			$form->var['tirname']=trim(strip_tags($form->var['tirname']));
			if (isset($form->var['trn'])){
				if($form->var['trn'][0]==0){
					$this->parent->go('?do=newtour');
				} else {	
					$this->parent->go('?do=newtour&id='.$form->var['trn'][0]);
				}
			}
			do{
				if(empty($form->var['tirname'])){
					$error['name']="Имя клуба не должно быть пустым";
					break;
				}
				if(trn::name_exists($form->var['tirname'])){
					$error['tirname']="Клуб с таким название уже существует";
					break;
				} 
				$key=array(
					'PARENT'=>0
				,	'LEVEL'=>0
				,	'RULE'=>'title'
				,	'NAME'=>$form->var['tirname']
				,	'PLAIN'=>$form->var['tirdescr']
				,	'STATUS'=>-1
				);
				$id=trn::store($key);
                // делаем юзера администратором тира
				$this->parent->user['right']['tir_'.$id]=1023;
				$this->parent->writeRecord($this->parent->user);
			} while(false);	
			if(!empty($error)){
				$_SESSION['regError']=$error;
				debug($_SESSION);
				$this->parent->go($this->parent->curl());
			}
            $this->parent->go();
		}
		// заполняем значения
		return $form->getHtml(' ');
	}
	
	function do_showtour(){
		if (!$this->parent->has_rights(right_TABLE)){
			debug($this->defcat,$this->parent->rights,$this->parent->user);
			return self::_l(mess_sorry_you_have_no_right) ;
		}
		
		if(!($tournament=tournament::getTournament(intval($_REQUEST['id'])))){
			// ищем первый турнир из таблицы
			$list=$this->parent->export('darts_Tourn','_list');
			if(empty($list))
				return 'Oops!';
			else {
				debug($list);
				$tournament=tournament::getTournament($list[0]['ID']);
			}
		}
		$tpl = '';
		if ($tournament->get('RULE')=='group'){
			$tournament->prepChilds();
            $par='';
			foreach($tournament->childs as &$child)
				$par.=$this->parent->export('DARTS','getTourTable',$child);
			return $par;	
		} elseif ($tournament->get('RULE')=='meeting'){
			return $this->show_meeting($tournament);
		} elseif ($tournament->get('RULE')=='title' && $tournament->get('LEVEL')==0){
			return $this->do_club($tournament);
		}
		if(!empty($tpl)){		
		$form=new form($tpl);//'show_'.$tournament->tournament['RULE']);
		$form->scanHtml($this->parent->_tpl('tpl_main','_'.$tpl,$tournament->getTable()));
		foreach ($form->controls as $k=>$i)
		{
			if ($i->c[1]=='players') {
				$form->controls[$k]->c[2]=$tp_sel ;
				$form->controls[$k]->c['options']='';
			}
		}
		$form->var['players']=array();
		if ($form->handle() && $this->parent->has_rights(right_READ)) {
			if(count($form->var['players'])==2){
				$pl=array();
				foreach($form->var['players'] as $i){
					$pl[]="name:'".$tp_sel[$i]."',id:".$i ;
				}
				$_SESSION['DART_INIT']=array('realgame'=>"*/ " .
						"startgame={players:[{".implode("},{",$pl).
						"}]".pp($turn['RULE'],',rule: ','').",tour: ".$id."};");
				$this->parent->go('do=playdarts&id='.$id);
				break;
			} else {
				$this->par["error"]='неправильное число игроков -'.count($form->var['players']);
			}
		}
		$this->par('style',".border table {width:100%;}\n.border table td,.border table th  {border: solid gray 1px;padding:1px;}	\n");
		$this->parent->par['title']=('турнир "'.$turn['NAME'].'"');
		$par=$form->getHtml( ' ');
		}else {
		// сочиняем собственно таблицу
			$par.=$this->parent->export('DARTS','getTourTable',$tournament);
		}
		return $par;
	}

	function init(){
		parent::init();
		$this->rights=new rights();
		$this->user=array('lastclub'=>0);
	}
	
	function init2(){
		$this->parent->sessionstart();
	
		if(!empty($_SESSION['lastclub'])){
			$this->defcat='tir_'.pps($_SESSION['lastclub']);
			$this->parent->par['lastclub']=$_SESSION['lastclub'];
//			debug('xxx',$this->par);
		}
		$this->parent->par['is_admin']=$this->parent->has_rights(right_ADMIN);
		$this->parent->par['is_editor']=$this->parent->has_rights(right_WRITE);
		$this->parent->par['is_player']=$this->parent->has_rights(right_READ);
		$this->parent->par['is_reader']=$this->parent->has_rights(right_TABLE);
		$this->parent->par['is_user']=isset($this->parent->user['id']);
		
		$this->parent->par['root']=rtrim(toUrl(ROOT_PATH),'/').'/';
	}
	
	function do_Default(){
		$par=' ';
		//print_r($this->rights);echo($this->defcat);
		$do=pps($_GET['do']);
		if ($this->has_rights(right_TABLE)) {
			//$this->parent->par['_news']=_export('darts_News','show_News');
			
			

			$turnlist=_export('darts_Tourn','_list');
			//debug($turnlist);
			if(count($turnlist)){
				$par.="<h3>Идущие турниры</h3>";
			}
			for($i=0;$i<count($turnlist);$i++){
				$x=$turnlist[$i];

				if(pps($x['STATUS'])==2){
					$par.='<fieldset><legend>'.$x['NAME'].'</legend>'.DARTS::getTourTable($x['ID'],$do!='show').'</fieldset>';
				}
			}
		} else {
			$this->par['start']=array();
		}
		return $par;
	}

}

/**
 *
 *  класс собирает и хранит всю околодартсную информацию
 *
 *  DARTS - неймспейс для некоторых функций
 *
 */
class DARTS extends plugin{
	
	static $curop;
	
	function oper(&$x,$val){
		if (self::$curop=='-'){
			$x-=$val;
		} else {
			$x+=$val;
		}
	}

	/**
	 * накатить или откатить счет турнира на вышестоящий
	 * @param tournament $tournament - турнир
	 * @param boolean $dir
	 */	
	static function rollback(&$tournament,$dir=true){
		switch($tournament->get('RULE')){
		case 'game':
			if($tournament->result(1,1)>$tournament->result(2,1)){
				$res=&$tournament->result(1);
				$res2=&$tournament->result(2);
			} elseif($tournament->result(1,1)<$tournament->result(2,1)) {
				$res=&$tournament->result(2);
				$res2=&$tournament->result(1);
			} else 
				return false;
			$tournament->prepParent();
			$par=&$tournament->parent->result($res);
			$par2=&$tournament->parent->result($res2);
			
			self::$curop = $dir?'-':'+';
			self::oper($par['RES1'],1);
			self::oper($par['RES2'],$res['RES1']); // количество набр очков
			self::oper($par['RES3'],$res['RES2']); // количество затр дротиков
			self::oper($par2['RES2'],$res2['RES1']);
			self::oper($par2['RES3'],$res2['RES2']);
				
			$tournament->parent->changed=true;
			return true;
		case 'meeting':
			switch(ppi($tournament->get('ASCORE'),1)){
				case 2: $cnt=2; break;
				default: $cnt=3;
			}
			//print_r(array('xxxxxxxx',$tournament->tournament,ppi($tournament->tournament['ASCORE']),$cnt));
			
			if($tournament->result(1,1)>=$cnt){
				$res=&$tournament->result(1);
				$res2=&$tournament->result(2);
			} elseif($tournament->result(2,1)>=$cnt) {
				$res=&$tournament->result(2);
				$res2=&$tournament->result(1);
			} else 
				return false;
			$tournament->prepParent();
			$par=&$tournament->parent->result($res);
			$par2=&$tournament->parent->result($res2);
			self::$curop = $dir?'-':'+';
			self::oper($par['RES1'],1);
			self::oper($par['RES2'],$res['RES2']);
			self::oper($par['RES3'],$res['RES3']);
			self::oper($par2['RES2'],$res2['RES2']);
			self::oper($par2['RES3'],$res2['RES3']);
			self::oper($par['RES4'],$res['RES1']); // проигранные- выигранные леги
			self::oper($par['RES5'],$res2['RES1']);
			self::oper($par2['RES4'],$res2['RES1']);
			self::oper($par2['RES5'],$res['RES1']);
			$tournament->parent->changed=true;
			return true;
		default :
			break;
		}
		return false;
	}	
	static function clearScore(&$tournament){
//		debug('clear ',$tournament->tournament);
		
		foreach($tournament->tresult as &$res){
			$res['RES1']=0;
			$res['RES2']=0;
			$res['RES3']=0;
			$res['RES4']=0;
			$res['RES5']=0;
			$res['RES6']=0;
		}
		$tournament->changed=true;
		$tournament->finished(false);
	}
	
	static function checkFinish(&$tournament){
		if(!$tournament) return ;
		if(!$tournament->finished()){
			if(DARTS::rollback($tournament,false))
				$tournament->finished(true);
		}
		if($tournament->get("RULE")=='table'){
			// вычисляем место согласно набранным очкам
			// пузырьковая сортировка
			// за один проход пузырька всплывет первое место, 
			// затем второе и т.д
			// RES6 - место
			$sort=array();
			foreach($tournament->tresult as &$res){
				$sort[]=array(ppi($res['RES1'])*0x10000 + (ppi($res['RES4'])-ppi($res['RES5'])));
			}
			$place=0;arsort($sort);
			$last=0;
			foreach($sort as $k=>$v){
				if($last!=$v)
					$place++;
				if($tournament->tresult[$k]['RES6']!=$place){
					$tournament->changed=true;
					$tournament->tresult[$k]['RES6']=$place;
				}
				$last=$v;
			}
		}
		DARTS::checkFinish($tournament->parent);
	}
	
	/**
	 *
	 *  Выдать массив игр, в которые мы умеем играть
	 */
	function game_array(){
		return array('','201','301','501',
				'Американский крикет','крикет',
				'Набор очков','Полный круг'
		);
	}

	function game_list(){
		$x=$this->game_array();
		$arr=array();
		foreach($x as $k=>$v){
			if($v)$arr[]=array('id'=>$k,'name'=>$v);
		}
		return $arr ;
	}

	/**
	 *   Выдать на гора таблицу турнирную для турнира $id
	 */
	function getTourTable($id){
		if(is_object($id))
			$tournament=$id;
		elseif(!($tournament=tournament::getTournament($id)))
			return '';
			
		$x=$tournament->getTable();
		$x['xid']=$tournament->getId();
		//debug('xx',$x);*/
		if($tournament instanceof trn_finn){
			return 
				$this->parent->_tpl('tpl_trntab','_finn',	$x);
		} else if($tournament instanceof trn_group){
            $tournament->prepChilds();
            $par='';
			foreach($tournament->childs as &$child)
				$par.=$this->parent->export('DARTS','getTourTable',$child);
			return $par;
        } else {
			return 
				$this->parent->_tpl('tpl_trntab','_Trntable',	$x);
        }
	}
	
}

class config extends plugin {
	
	
	function optimize(){
		// чистимся
		$res=array_merge(
			$this->parent->database->select(
				'show table status like "'.TAB_PREF.'_%";'
			),
			$this->parent->database->select(
				'show table status like "session%";'
			)
		);

		foreach($res as $v){
			if($v['Data_length']/10 < ppi($v['Data_free'])){
				// анализируем ее автоматически
				$this->parent->database->select(
					//'ANALYZE TABLE `'.$v['Name'].'`;'
					'OPTIMIZE TABLE `'.$v['Name'].'`;'
				);
			}
		}
	}
	
	/**
	 * Перекрасить массив NS под уровень, парент и начальный индекс
	 *
	 * @param array $res
	 * @param int $ptr  - начальный lid
	 * @param int level - уровень.
	 */
	
	
	
	function do_create(){
        $sql=file_get_contents('structure.sql');
        foreach(explode(';',$sql) as $s){
            $s=trim($s);
            if(!empty($s))
                RIGHT::$DATABASE->query($s);
        };

		$this->parent->read_Parameters();
		$this->parent->setPar('engine version',200);
		/**
		 * Создание администратора, если его нет
		 */
		$user=$this->parent->readRecord(array('record'=>'user','name'=>'admin'));
		//var_dump($user);
		if(!isset($user['id'])){
			// регистрация админа
			$user=array('record'=>'user'
				,'name'=>'admin'
				,'password'=>'password'
				,'right'=>array('*'=>(right_READ+right_WRITE+right_ADMIN))
			);
			$this->parent->writeRecord($user);
		}
		// создание игроков
	}
}

//<% insert_point('plugin_body');%>
