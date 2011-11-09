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
			$this->parent->error("Sorry. you have no right to do it!");
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
			$this->parent->error("Sorry. you have no right to do it!");
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
			$this->parent->error("Sorry. you have no right to do it!");
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
			return "Sorry. you have no right to see it!" ;
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
						break;
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
		if(!empty($_GET['debug']))
			$this->database->do_log=true;
		$form=new form('tir');
		$par=array();
		if (!empty($_SESSION['regError'])){
			$par['error']=$_SESSION['regError'];
			$_SESSION['regError']='';
		}	
		debug($par);
		$par['user']=$this->parent->user;
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
			
			if ($form->var['password']!=$form->var['rpassword']){
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
			$user['password']=$form->var['password'];	
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
	
	function do_playerinfo(){
		if (!$this->parent->has_rights(right_WRITE)){
			$this->parent->error("Sorry. you have no right to do it!" );
			return ' ';
		}
		$data=DATABASE()->selectRow('select * from ?_players where id=?',ppi($_GET['id']));
		$this->parent->ajaxdata=$this->parent->_tpl('tpl_main','_PlayerInfo',array('data'=>$data));
		return 'ok';
	}
	
	function do_changeplayer(){
		if (!$this->parent->has_rights(right_WRITE)){
			$this->parent->error("Sorry. you have no right to do it!" );
			return ' ';
		}
		$data=DATABASE()->query('update ?_players set NAME=?, NAME1=? where ID=?'
			,pps($_POST['name1']),pps($_POST['name2']),ppi($_POST['id']));
		return 'ok';
	}
	
	function do_delplayer(){
		if (!$this->parent->has_rights(right_WRITE)){
			$this->parent->error("Sorry. you have no right to do it!" );
			return ' ';
		}
		$tournament=tournament::getTournament(ppi($_GET['id']));
		if($tournament) {
			$tournament->deleteplayer(ppi($_POST['id']));
		};
		
		return 'ok';
	}
	
	function do_addplayer(){
		if (!$this->parent->has_rights(right_WRITE)){
			$this->parent->error("Sorry. you have no right to do it!" );
			return ' ';
		}
		$id=pps($_GET['id']);
		$tournament=tournament::getTournament($id);
		if(!$tournament) return 'Oops!'.$id;
		// добавить юзера в адресную книгу + добавить юзера в клуб + добавить юзера в турнир
		// addrbook
		$res=DATABASE()->selectRow('select * from ?_players where NAME=? and NAME1=?'
			,trim($_POST['name1']),trim($_POST['name2']));
		if($res){
			$player=$res['ID'];
		} else {
			$player=DATABASE()->query('insert into ?_players set NAME=?,NAME1=?'
				,trim($_POST['name1']),trim($_POST['name2']));
		};
		// добавить юзера в адресную книгу + добавить юзера в клуб + добавить юзера в турнир
		$x=$tournament->result(array('ID_PLAYER'=>$player));
		if(!empty($x))
			return 'ok';
		$tournament->addplayer(intval($player));
		$t=$tournament;
		do{
			$t->prepParent();
			$t=$t->parent;
		}while(!empty($t) && $t->tournament['LEVEL']>0);
		if($t)$t->addplayer(intval($player));
		return 'ok';
	}
	
	function playerById($id){ 
		static $names;
		if(empty($names)){
			$names=DATABASE()->select(
				'SELECT * from ?_players ;'
				);
			foreach($names as $x) {
				if(isset($x['ID']))
				$names[$x['ID']]['name']=trim($x['NAME'].' '.$x['NAME1'].' '.$x['NAME2']);
			}
		}
		return $names[$id]['name'];
	}
	
	/**
	 * список доступных клубов для зарегистрированного игрока
	 * -- выводим список прав tir_(\d+)
	 * -- вычисляем список клубов
	 * -- сохраняем его в сессии
	 */
	function _clublist(){
		//debug('hello');
		static $clubs;
		if(!empty($clubs)) return $clubs;
		if ($this->parent->has_rights(right_ADMIN)){
			$clubs= DATABASE()->select('select ID,NAME,DESCR from ?_tournaments where LEVEL=0 ORDER BY `NAME`;');
				//debug($clubs);
			return $clubs;
//			return "Sorry. you have no right to see it!" ;
		} else {
			$list=$this->parent->rights->list_right();
			//debug('xxx',$list);
			$clubs=array();
			foreach($list as $k=>$v){
				if(preg_match('/^tir_(\d+)$/',$k,$m)){
					$clubs[]=$m[1];
				}
			}
			if(!empty($clubs)){
				$clubs= DATABASE()->select('select ID,NAME,DESCR from ?_tournaments where `ID` in ('.implode(',',$clubs).') ORDER BY `NAME`;');
				if((count($clubs)>0) && (empty($_SESSION['lastclub']))){
					$_SESSION['lastclub']=$clubs[0]['ID'];
					$this->parent->par['lastclub']=$_SESSION['lastclub'];
				}
				//debug($clubs);
				return $clubs;
			}
		}
		return array();
	}
	/**
	 * вернуть массив ID игроков 
	 * Enter description here ...
	 * @param unknown_type $style
	 * @param unknown_type $trn
	 * @param unknown_type $sort
	 */
	function _listid($trn=0,$sort='NUMBER'){
		static $res;
		$trn=ppi($trn,$_SESSION['lastclub']);
		if (isset($res[$trn])) return $res[$trn] ;
		return $res[$trn]=DATABASE()->selectCol(
			'SELECT ID_PLAYER FROM ?_tourplayers where `ID_TOURNAMENT`=?;'
			,$trn
			);
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
				$x=$pllist[$i];
				$pnames[$x['ID']]=$x['name'];
			};
			$res[$trn][$style]=$pnames;
		} else
			$res[$trn][$style]=$names;
		return $res[$trn][$style];
	}

	function do_player(){
		//return 'Hello!';
		if (!$this->parent->has_rights(right_VIEW)) return "sorry!! you have no right to see this!" ;
		$form=new form('player');
		$id=pps($_GET['id']);
		$this->parent->par['title']=($id?'Изменить данные игрока':'Добавить игрока');
		//photo
		$data=DATABASE()->selectRow(
					'select * from ?_players where `ID`=?;'
					,$id);
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
				DATABASE()->query('UPDATE  ?_players set ?a where `ID`=?;'
					,$key,$id);
			} else {
				$id=DATABASE()->query('INSERT INTO ?_players (?#) VALUES(?a);'
					,array_keys($key),array_values($key));
			}
			$this->parent->go();
		}
		if($id){
			$form->var=array_merge($form->var
				,DATABASE()->selectRow('select * from ?_players where `ID`=?;'
				,$id));
		}
		return $form->getHtml(' ')._export(__CLASS__,'_list_games');
	}

	/**
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
		$turn=DATABASE()->selectRow('SELECT * from ?_tournaments where ID=?;',$id);
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
		
		
	/*	
		$this->parent->par['title']=('Игры, в которые играют люди');
		for($i=1;$i<6;$i++){
			$_SESSION['FORM_game'.$i]='';
			$gm=new form('game'.$i,
				array(CNT_INPUT,'DATE'),
				array(CNT_INPUT,'ID_GAME','hidden'),
	//			array(CNT_INPUT,'RULE'),
	//201|301|501|Американский крикет|крикет|Набор очков|Полный круг
				array(CNT_SELECT,'RULE',DARTS::game_array(),'par'=>'class="long"'),
	//			array(CNT_INPUT,'SCOREGAME1'),
				array(CNT_INPUT,'SCORE1'),
				array(CNT_INPUT,'TRACE1'),
	//			array(CNT_INPUT,'SCOREGAME'),
				array(CNT_INPUT,'SCORE'),
				array(CNT_INPUT,'TRACE'),
				array(CNT_SUBMIT,'par'=>'name="xxx'.$i.'" class="long" value="&raquo;"'),
				array(CNT_SUBMIT,'par'=>'name="del'.$i.'" class="long" value="X"')
			);
			if ((pps($_REQUEST['del'.$i]))||(pps($_REQUEST['xxx'.$i])))
			if($gm->handle()){
				DATABASE()->query('DELETE from ?_games '.
				'where `ID`=?;',$gm->var['ID_GAME']);
				DATABASE()->query('DELETE from ?_gplayers '.
				'where `ID_GAME`=?;',$gm->var['ID_GAME']);
				if(pps($_REQUEST['xxx'.$i])){
		//			$par['debug'].='xxx'.$i.' pressed<br>';
		//!!!!!!!!!!!!!!!!!!!!!!!!!
					$key=array();
					$key['ID_TOURNAMENT']=$id;
					$key['RULE']=$gm->var['RULE'];
					$ID_GAME=DATABASE()->query('INSERT INTO ?_games (?#) VALUES(?a);',array_keys($key),array_values($key));
			// трасса игры
					$key=array();
					$key['ID_GAME']=$ID_GAME;
					foreach(array($x=>$gm->var['SCORE1'],$y=>$gm->var['SCORE']) as $k=>$v){
						$key['ID_PLAYER']=$k;
						$key['SCORE']=$v;
						DATABASE()->query('INSERT INTO ?_gplayers (?#) VALUES(?a);',array_keys($key),array_values($key));
					}
				}
				DARTS::getTourTable($id,true,true);

				$this->parent->go('do=play&x='.$x.'&y='.$y.'&id='.$id);
			}
			if(isset($gmlist[$i-1])){
				foreach($gmlist[$i-1] as $k=>$v){
					if(isset($gm->var[$k])){
						$gm->var[$k]=$v;
					}
				}
			}
		//	$par['debug'].=print_r($gm->var,true);
			$game[]=$gm;
		}
		// вывод таблицы
		$tabpar='style="width:600px;table-layout: fixed; ">'.
		'<COL width="70px"><COL width="20px">'.
		'<COL width="20px"><COL width="30px">'.
		'<COL width="20px"><COL width="30px">'.
		'<COL width="15px"><COL width="15px"';
		$head=new table(
			'abCCFFj',
			'@val abCFj дата|игра|'.$pnames[$x].'|'.$pnames[$y].'|&nbsp',
			$tabpar
		);
		$par.=$head->getHtml(array());

		for($i=0;$i<count($game);$i++)
		$par.=$game[$i]->getHtml( new table(
			'abcefgjh',
			$tabpar
		));
*/
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
	
// список игроков турнира
	function _listplayers($id=null,$force=false){
		static $res;
		if(empty($id))
			$id=pps($_GET['id']);
		if (!isset($res)) $res=array();
		if (isset($res[$id]) && !$force) return $res;
		$res[$id]=DATABASE()->select(
				'SELECT * FROM ?_tourplayers WHERE ID_TOURNAMENT=?;',$id);
		return $res[$id];
	}
	
	function do_recalc(){
		if (!$this->parent->has_rights(right_WRITE)){
			return "Sorry. you have no right to change tournament!" ;
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
			return "Sorry. you have no right to change tournament!" ;
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
			return "Sorry. you have no right to change tournament!" ;
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
			return "Sorry. you have no right to change tournament!" ;
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
			return "Sorry. you have no right to change tournament!" ;
		}
		//print_r($_POST);
		$id=pps($_POST['id']);
		if(empty($id))
			return 'fail';
			
		$tournament=tournament::getTournament($id);
	 	if(empty($tournament))
	 		return 'fail';
	 		
	 	$key=array(
			 'NAME'=>$_POST['NAME']
			,'STATUS'=>$_POST['STATUS']
			,'RULE'=>$_POST['RULE']
			,'ASCORE'=>$_POST['ASCORE']
			);
		$tournament->set($key);
		
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
		$tournament=tournament::getTournament($id);
		
		$this->parent->ajaxdata=$this->parent->_tpl('tpl_main','_NewTourn',array('tour'=>$tournament->tournament));	
		return ' ';

		if($form->handle()){;
			$tournament=tournament::getTournament($id);
			if(isset($_POST['clear'])){
				$tournament->finished(false);
				$tournament->clearscore();
				$tournament->deleteChilds();
				$this->parent->go($this->parent->curl());
		 	}
			if(isset($_POST['recalc'])){
				$tournament->clearscore();
				$tournament->finished(false);
				//$tournament->deleteChilds();
				$this->parent->go($this->parent->curl());
		 	}
		 	if(isset($_POST['addtrn'])){
		 		if(method_exists($tournament,'addChild'))
		 			$tournament->addChild();
				$this->parent->go($this->parent->curl());
		 	}
		 	
		 	$key=array(
				'NAME'=>$form->var['NAME']
			,	'STATUS'=>$form->var['STATUS']
			,	'RULE'=>$form->var['RULE']
			);
			
			if($id){
				DATABASE()->query('UPDATE  ?_tournaments set ?a where `ID`=?;',$key,$id);
			} else {
				$key['PARENT']=$this->parent->par['lastclub'];
				$key['LEVEL']=1;
				$id=DATABASE()->query('INSERT INTO ?_tournaments (?#) VALUES(?a);',array_keys($key),array_values($key));
			}

			/**
			 * жеребьевка, если нужно
			 */	
			$players=DATABASE()->select(
				'SELECT ID_PLAYER AS ARRAY_KEY, NUMBER  FROM ?_tourplayers WHERE ID_TOURNAMENT=?;',$id);
			$renumber=array();
			$delete=$players;
			$insert=array();
			foreach ($form->var['players'] as $k){
				if(!isset($players[$k])) {
					$insert[]=$k;
				} else {
					unset($delete[$k]);
				}	
			}
			foreach($delete as $k=>$v){
				DATABASE()->query('delete from ?_tourplayers where ID_PLAYER=? and ID_TOURNAMENT=?',$k,$id);
			}
			foreach($insert as $k){
				DATABASE()->query('INSERT INTO ?_tourplayers(ID_PLAYER,ID_TOURNAMENT) VALUES (?,?)',$k,$id);
			}
					
			/**
			 * раскидать сетку игр
			 */
			if(isset($_POST['retable'])){
				// просто построить игры турнира
				// с удалением	
				DATABASE()->query('delete * from ?_games WHERE `ID_TOURNAMENT`=?;',$id);
				DATABASE()->query('select * from ?_games WHERE `ID_TOURNAMENT`=?;',$id);
			}
				
				
			$this->parent->go($this->parent->curl());
		}
		$tp_sel=array();
		$data=DATABASE()->selectRow('select * from ?_tournaments where `ID`=?;',$id);
			
		if($id){
			$form->var=array_merge($form->var,
				DATABASE()->selectRow(
					'select * from ?_tournaments where `ID`=? order by `DATE` DESC ;'
					,$id));
			$tpllist=DATABASE()->select(
				'SELECT * FROM ?_tourplayers WHERE ID_TOURNAMENT=?;',$id);
			for($i=0;$i<count($tpllist);$i++){
				$x=$tpllist[$i];
				$tp_sel[]=$x['ID_PLAYER'];
			}
		}
		$form->var['players']=$tp_sel;
		//debug($form->var);
		if($this->parent->is_ajax){
			$this->parent->ajaxdata=$form->getHtml(' ');
			return ' ';
		} else
			return $form->getHtml(' ');
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
	
	function do_user(){
		$_POST['login_name']=$_GET['id'];
		return $this->export('darts_Auth','_check');
	}
	
	function do_deltourn(){
		if (!$this->parent->has_rights(right_WRITE)){
			$this->parent->error( "Sorry. you have no right to show this!") ;
			return  ' ';
		};
		if($tournament=tournament::getTournament($_GET['id'])){
			$tournament->delete();
		};
		return ' ';
	}
	/**
	 * сгенерировать ajax'ом общий вход
	 */
	function do_common(){
		if (!$this->parent->has_rights(right_WRITE)){
			$this->parent->error( "Sorry. you have no right to show this!") ;
			return  ' ';
		};
// регистрация аккаунта
		
		$key=array('record'=>'user');
		$right=array();
		$club='tir_'.$_SESSION['lastclub'];
		$right[$club]=array_sum($_POST['right']);
		echo $club.' '.$right[$club];
		$key['name']=md5($club.' '.$right[$club]);
		$user=$this->parent->readRecord($key);

		if(!empty($_POST['date'])){
			$user['date']=strtotime($_POST['date'].' day');
		} 
		
		// заводим аккаунт с именем
		if(!isset($user['id']))	{
			$user['right']=$right;
			$user['password']='';
			$this->parent->writeRecord($user);
		}
		$this->parent->ajaxdata='Скопируйте <a href="http://'.$_SERVER["SERVER_NAME"]
			.'/darts/user/'.$user['name'].'">ссылку</a> и перешлите ее игрокам';	
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
	 *   Выдать создать child-турнир для турнира $id
	 */
	function do_child(){
		if (!$this->has_rights(right_READ)) return '';
		$id=intval($_GET['id']);
		$x=intval($_GET['x']);
		$y=intval($_GET['y']);
		
		$tournament=tournament::getTournament($id);
		$x=$tournament->getChild($x,$y);
		debug($x);
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
			foreach($childs as &$child){
		 		$i++;
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
					 'LEVEL'=>$tournament->tournament['LEVEL']+1
					,'RULE'=>'game'
					,'PARENT'=>$tournament->getId()
					,'NAME'=>'лег '.($i) 
				));
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
			$form->var['date_'.$i]=$child->tournament['DATE'];
			$form->var['rule_'.$i]=$child->tournament['SUBRULE'];
			$form->var['score_1_'.$i]=$child->tresult[0]['RES1'];
 			$form->var['scorex_1_'.$i]=$child->tresult[0]['RES2'];
			$form->var['score_2_'.$i]=$child->tresult[1]['RES1'];
			$form->var['scorex_2_'.$i]=$child->tresult[1]['RES2'];
		}
		for($i=1;$i<=2;$i++) for($j=1;$j<=4;$j++)
			$form->var['RES'.$j.'_'.$i]=$tournament->result($i,$j);
		//debug($form->var,$tournament->childs);
		return $form->getHtml( ' ');
	}

	/**
	 * регистрация клуба - это регистрация турнира нулевого уровня
	 */
	function do_club(){
		$this->parent->sessionstart();
		if (!isset($this->parent->user['id'])) 
			return "Sorry!! you have no right to see this!" ;
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
		debug('data',$par);
		$form=new form($formname);
		if (!empty($_SESSION['regError'])){
			$par['error']=$_SESSION['regError'];
			$_SESSION['regError']='';
		}	
		if($formname=='Club')
			return $this->parent->_tpl('tpl_main','_'.$formname,$par);
		$form->scanHtml($this->parent->_tpl('tpl_main','_'.$formname,$par));
		if ($form->handle() && $this->parent->has_rights(right_READ)) {
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
				$reg=DATABASE()->query('select * from ?_tournaments where `LEVEL`=0 '
					.'and `NAME`=?;',$form->var['tirname']);
				if(!empty($reg)){
					$error['tirname']="Клуб с таким название уже существует";
					break;
				} 
				$key=array(
					'PARENT'=>0
				,	'LEVEL'=>0
				,	'RULE'=>'title'
				,	'NAME'=>$form->var['tirname']
				,	'DESCR'=>$form->var['tirdescr']
				,	'STATUS'=>-1
				);
				$id=DATABASE()->query('INSERT INTO ?_tournaments (?#) VALUES(?a);',array_keys($key),array_values($key));
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
			return "Sorry. you have no right to show this!" ;
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
		if ($tournament->tournament['RULE']=='group'){
			$tournament->prepChilds();
			foreach($tournament->childs as &$child)
				$par.=$this->parent->export('DARTS','getTourTable',$child);
			return $par;	
		} elseif ($tournament->tournament['RULE']=='meeting'){
			return $this->show_meeting($tournament);
		} elseif ($tournament->tournament['RULE']=='title' && $tournament->tournament['LEVEL']==0){
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
			debug('xxx',$this->par);
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

				if(pps($x['ASCORE'])){
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
		switch($tournament->tournament['RULE']){
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
			switch(ppi($tournament->tournament['ASCORE'],1)){
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
		if($tournament->tournament["RULE"]=='table'){
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
		//debug('xx',$x);
		if($tournament instanceof trn_finn){
			return 
				$this->parent->_tpl('tpl_trntab','_finn',	$x);
		} else 
			return 
				$this->parent->_tpl('tpl_trntab','_Trntable',	$x);
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
		$this->parent->database->query(
		"CREATE TABLE IF NOT EXISTS ?_flesh (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `ival` int(11) default NULL,
  `sval` varchar(255) NOT NULL,
  `tval` text,
  PRIMARY KEY  (`id`,`name`),
  KEY `sval` (`sval`)
);");
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
		$this->parent->database->query(
		"CREATE TABLE IF NOT EXISTS ?_tournaments (
  `ID` int(11) NOT NULL auto_increment,
  `PLACE` int(11) default '0',
  `PARENT` int(11) default '0',
  `DATE` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `LEVEL` int(3) NOT NULL,
  `NAME` varchar(255) NOT NULL default '',
  `STATUS` varchar(40) default '0',
  `RULE` int(11) default NULL,
  PRIMARY KEY  (`ID`),
  KEY `PLACE` (`PLACE`),
  KEY `PARENT` (`PARENT`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8");
			$this->parent->database->query(
		"CREATE TABLE IF NOT EXISTS ?_players (
  `ID` int(11) NOT NULL auto_increment,
  `NAME` varchar(255) NOT NULL default '',
  `NAME1` varchar(255) NOT NULL default '',
  `NAME2` varchar(255) NOT NULL default '',
  `PLACE` int(11) NOT NULL default '0',
  `PHOTO` varchar(255) default NULL,
  PRIMARY KEY  (`ID`),
  KEY `PLACE` (`PLACE`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=34 ;");
			$this->parent->database->query(
		"CREATE TABLE IF NOT EXISTS ?_games (
  `ID` int(11) NOT NULL auto_increment,
  `ID_TOURNAMENT` int(11) NOT NULL default '0',
  `DATE` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `RULE` int(11) NOT NULL default '0',
  `P1` int(11) NOT NULL default '0',
  `P2` int(11) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `ID_TOURNAMENT` (`ID_TOURNAMENT`)
) ENGINE=MyISAM AUTO_INCREMENT=557 DEFAULT CHARSET=utf8 COMMENT='одна игра в составе турнира' AUTO_INCREMENT=557 ;");
		$this->parent->database->query(
		"CREATE TABLE IF NOT EXISTS ?_gplayers (
  `ID` int(11) NOT NULL auto_increment,
  `ID_PLAYER` int(11) NOT NULL default '0',
  `ID_GAME` int(11) NOT NULL default '0',
  `SCORE` int(11) default NULL,
  `SCORE1` int(11) NOT NULL,
  `TRACE` text NOT NULL,
  PRIMARY KEY  (`ID`),
  KEY `ID_PLAYER` (`ID_PLAYER`,`ID_GAME`)
) ENGINE=MyISAM AUTO_INCREMENT=1055 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1055 ;");

		$this->parent->database->query(
		"CREATE TABLE IF NOT EXISTS ?_tplayers (
  `ID_PLAYER` int(11) NOT NULL default '0',
  `ID_TOURNAMENT` int(11) NOT NULL default '0',
  `NUMB` int(11) NOT NULL,
  KEY `ID_PLAYER` (`ID_PLAYER`,`ID_TOURNAMENT`),
  KEY `NUMB` (`NUMB`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
		$this->parent->database->query(
		"CREATE TABLE IF NOT EXISTS ?_news (
  `ID` int(11) NOT NULL auto_increment,
  `text` text NOT NULL,
  `PLACE` int(11) default NULL,
  `DATE` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;");
		
	}			
}

///****** point plugin_body */
class notifygtalk extends plugin {
	
	function do_test(){
		$subscriber=array('gtalk'=>'sergekoriakin@gmail.com');
		$this->notify($subscriber,'Hello! ������ �� ��������� ��������!');
		return 'Ok!';
	}
	
	function notify(&$subscribe,$message){
		if(!empty($subscribe['gtalk'])){

			include_once 'xmpphp/XMPP.php';
			set_time_limit (10);
			
			$conn = new XMPPHP_XMPP('talk.google.com', 5222 
				,'pepsodent.23075@gmail.com', 'ksnk17740481' // �������� ��� ��� �������� talk'�
				, 'xmpphp', 'gmail.com', $printlog=true, $loglevel=XMPPHP_Log::LEVEL_INFO);
			
			try {
			    $conn->connect();
			    $conn->processUntil('session_start');
			    $conn->presence();
			    $conn->message($subscribe['gtalk'], $message);
			    $conn->disconnect();
			} catch(XMPPHP_Exception $e) {
			    die($e->getMessage());
			}
		}
	}
}
///****finish point plugin_body *//*
