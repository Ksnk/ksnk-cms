<?php
/**
 * Форум
 * в админке топик форума задается GET[topic], на сайте - GET[id].
 */

//define('FORUM_TPL','tpl_forum');

class forum extends ml_plugin {

	function forum($parent){
		if(pps($_GET['topic'])) {
			$res_t = $parent->database->query('SELECT * FROM ?_forum_topics WHERE id = '.$_GET['topic'].';');
			if(count($res_t) > 0) {
				$this->parent_topic = $res_t[0]['parent'];
				$this->topic_name = $res_t[0]['topic'];
			}
			else {
				$this->parent_topic = -1;
			}
		}
		if(pps($_GET['topic']) && $this->parent_topic > 0) {
			parent::ml_plugin($parent);
			$par=array(
			'title'=>'Форум'
			,'fields'=>array(
						array('Автор','sval', 'dontedit'),
						//array('Автор','user', 'dontedit'),
						array('Дата','date', 'dontedit'),
						array('комментарий','question'),
                        array('цитата','quote'),
			)
			,'base'=>'_forum'
			,'orderbystr'=>' where topic = '.pps($_GET['topic']).' order by `date`'
			,'prefix'=>'forum');

			if(defined('FORUM_WITH_THEME')){
				array_unshift($par['fields'],array('тема','theme','menu','xoptions'));
			}

			parent::_init($par);
		}
		else {
			parent::ml_plugin($parent);
			$par=array(
			'title'=>'Форум'
			,'fields'=>array(
						array('Тема','topic','text_edit'),
						array('','id','button'),
			)
			,'base'=>'_forum_topics'
			,'orderbystr'=>' where parent="'.pps($_GET['topic']).'" order by `id`'
			,'prefix'=>'forum_topics');

			parent::_init($par);
		}
	}

	function check_data(&$upd,&$res){
		if(pps($this->parent_topic) == 0) {
			$upd['parent'] = pps($_GET['id']);
		}
		else {
			$upd['topic'] = pps($_GET['topic']); // для админки так. Для сайта topic будет в id
			if(empty($res['user']))
				$upd['user'] = $_SESSION['USER_ID'];

			if(empty($res['date']))
				$upd['date']=date('Y-m-d H:i:s');
			else if(isset($upd['date'])){
				$dres=strtotime ($upd['date']);
				if( $dres===-1 || $dres===false)
					$upd['date']=date('Y-m-d H:i:s');
			}
		}
		if(isset($upd['question']))
			$upd['question']=trim(preg_replace('~^\s*<br\s*/?>|<br\s*/?>\s*$~i','',$upd['question']));
		if(isset($upd['quote']))
			$upd['quote']=trim(preg_replace('~^\s*<br\s*/?>|<br\s*/?>\s*$~i','',$upd['quote']));
		return true;
	}

	function get_parameters($par){
		$par['list'][]=array('sub'=>'Результаты поиска','title'=>'Количество результатов на страницу','name'=>'search_per_page');
		$par['list'][]=array('sub'=>'Форум','title'=>'Количество сообщений на страницу','name'=>'forum-perpage');
	}

	function admin_forum(){
		if(pps($_GET['id'])) {
			$topic_name = $this->topic_name;
		}
		else
			$topic_name = $this->getPluginName();
		return
		smart_template(array(ADMIN_TPL,'theheader'),array(
		'header'=>$topic_name,
		'data'=>parent::admin_plugin()));
	}

	function do_create($killall=true){
		if(!$this->parent->has_rights(right_WRITE))
			return $this->parent->ffirst('_loginform');
		if($killall){
			$this->database->query('DROP TABLE IF EXISTS ?_forum;');
		}
		$this->database->query("CREATE TABLE ?_forum (
				  `id` int(11) NOT NULL auto_increment,
				  `topic` int(11) NOT NULL default '0',
				  `user` varchar(255) character set cp1251 NOT NULL default '',
				  `question` text character set cp1251 NOT NULL,
				  `date` datetime default NULL,
				  `quote_id` int(11) NOT NULL default '0',
				  `quote` text character set cp1251 NOT NULL,
					PRIMARY KEY  (`id`)
					);");
		if($killall){
			$this->database->query('DROP TABLE IF EXISTS ?_forum_topics;');
		}
		$this->database->query("CREATE TABLE ?_forum_topics (
				  `id` int(11) NOT NULL auto_increment,
				  `topic` varchar(255) character set cp1251 NOT NULL default '',
				  `parent` int(11) NOT NULL default '0',
					PRIMARY KEY  (`id`)
					);");
	}

/**
 * Вывод страницы форума на страницу сайта
 * парамеры $_GET:
 *  - pg - с такой страницы
 *
 * @return unknown
 */
	function do_forum($unAnsweredOnly=true,$tpl='forum_list',$theme=''){
		global $engine;
		//$engine->dop_zagl = "fdgfdg";
		ml_plugin::setupmenu();

		if(pps($_POST['action'])){
			// Новое сообщение в теме форума
			if($_POST['action'] == 'newpost') {
				if(isset($_SESSION['USER_ID']) && $engine->user['right']['*'] && $_POST['newpost']!="") {
					$key = array(
						'topic'=>$_GET['id'],
						'user'=>$_SESSION['USER_ID'],
						'question'=>post2comment($_POST['newpost']),
						'date'=>date('Y-m-d H:i:s'),
						'quote_id'=>$_POST['quote_id'],
						'quote'=>post2comment($_POST['quote'])
					);
					$id=$this->database->query(
						'INSERT INTO ?_forum (?#) VALUES(?a);',
			   			array_keys($key),array_values($key)
			   		);
					$this->parent->go('',$_SERVER["REQUEST_URI"]);
				} else
					$this->parent->go('',$_SERVER["REQUEST_URI"]);

			}
			if($_POST['action'] == 'newtopic') {
				if(isset($_SESSION['USER_ID']) && $engine->user['right']['*'] && $_POST['newpost']!="" && $_POST['newtopic']!="") {

					$key = array(
						'topic'=>$_POST['newtopic'],
						'parent'=>$_POST['parent'],
					);
					$id=$this->database->query('INSERT INTO ?_forum_topics (?#) VALUES(?a);',
			   			array_keys($key),array_values($key)
			   		);
 			    	$res_id=$this->database->select('select max(id) as maxid from ?_forum_topics '.
						'where `topic` = \''.$key['topic'].'\' '.
						'AND `parent` = '.$key['parent'].
						';');

					$key = array(
						'topic'=>$res_id[0]['maxid'],
						'user'=>$_SESSION['USER_ID'],
						'question'=>$_POST['newpost'],
						'date'=>date('Y-m-d H:i:s')
						);
					$this->database->query('INSERT INTO ?_forum (?#) VALUES(?a);',
			   			array_keys($key),array_values($key)
			   		);
			    }
                debug($id);
                if(!empty($id)){
                    $this->parent->go("",$this->parent->url(array('do'=>'forum','id'=>$id)));
                } else
                    $this->parent->go($this->parent->curl());
			/*	$url=$this->parent->curl('do','id')."do=forum&id=".$res_id[0]['maxid'];
				$this->parent->go($this->parent->curl('do','id')."do=forum&id=".$res_id[0]['maxid']);*/
			}
		}

		if(empty($_GET['id']) or $_GET['id']=='forum') {
			$res=$this->database->select('select * from ?_forum_topics '.
				'where `parent` = 0'.
				' order by `id`;');

			foreach($res as $k=>$v) {
				$res_p=$this->database->select('select * from ?_forum_topics '.
					'where `parent` = '.$v['id'].
					' order by `id`;');
				if(count($res_p) > 0) {
					foreach($res_p  as $k_p=>$v_p) {
						$res_p[$k_p]['href'] = $this->parent->curl('do','id')."do=forum&id=".$v_p['id'];
						$res_d=$this->database->select('select count(id) as cnt from ?_forum '.
							'where `topic` = '.$v_p['id'].
							';');
						$res_p[$k_p]['cnt'] = $res_d[0]['cnt'];
					}
					$res[$k]['topic_p'] = smart_template(array(FORUM_TPL,'forum_topics_p'),array('list_p' => $res_p));
				}
				else {
					$res[$k]['topic_p'] = "";
				}
				$res[$k]['href_new'] = $this->parent->curl('do','id')."do=forum&id=".$res[$k]['id'];

				$res_d=$this->database->select('select max(date) as maxdate, count(id) as cnt from ?_forum '.
					'where `topic` = ANY (select id from ?_forum_topics where parent = '.$v['id'].
					');');
				if($res_d[0]['maxdate'] == NULL) {
					$res[$k]['date']="";
					$res[$k]['year']="&nbsp;";
				}
				else {
					if(ctype_digit($res_d[0]['maxdate']))
						$res_d[0]['maxdate']=date('D M j G:i:s',$res_d[0]['maxdate']);
					$s=explode(', ',toRusDate($res_d[0]['maxdate'], "j.m.Y г."));
					$res[$k]['date']=$s[0];
					$res[$k]['year']=$s[1];
				}
				$res[$k]['cnt'] = $res_d[0]['cnt'];
			}
            return $engine->_tpl('tpl_jforum','_forum_topics',array(
                'list'=>$res
            ));
//			return smart_template(array(FORUM_TPL,'forum_topics'),array('list' => $res));
		}

		$res=$this->database->select('select * from ?_forum_topics '.
			'where `id` = '.$_GET['id']);

		if($res[0]['parent'] == 0) {
			return smart_template(
				array(FORUM_TPL,'forum_newtopic'),
				array('parent' => $_GET['id'])
			);
		}

		// Заголовок для темы форума
		$engine->dop_zagl = $res[0]['topic'];

		$page=ppi($_GET['pg']);
		$perpage=$this->parent->getPar('forum-perpage');
		$perpage=ppi($perpage,10);

		$sql='select count(*) as cnt from ?_forum '.
			'where `topic` =\''.$_GET['id'].'\';';
		$res=@$this->database->selectRow($sql);
		if($res===null){
			return ' ';
		}
		$pages='';

		if($all_cnt=pps($res['cnt'])){
				$res=$this->database->select('select ?_forum.*,  ?_flesh.sval from ?_forum '.
				'LEFT JOIN ?_flesh ON ?_forum.user = ?_flesh.id '.
				' AND ?_flesh.name = \'name\''.
				'where `topic` = '.$_GET['id'].
				' order by ?_forum.id LIMIT '.($page*$perpage).','.$perpage.';');
			$pages=$this->parent->calc_Pages($all_cnt,$perpage,$page);
		}
		else {
			$res=array();
		}

		foreach($res as $i=>$v){
			if(ctype_digit($v['date']))
				$v['date']=date('D M j G:i:s',$v['date']);
			$s=explode(', ',toRusDate($v['date'], "j.m.Y г."));
			$res[$i]['date']=$s[0];
			$res[$i]['year']=$s[1];
            $res[$i]['time']=substr($v['date'],-8,5);
            $res[$i]['quote']=$v['quote'];
		/*	в связи с переходом на новый шаблон - больше не нужно
		    if($v['quote'] != "") {
				$res[$i]['quote_tr'] = array(0 => array('quote' => $v['quote']));
				$res[$i]['style_str'] = "";
			}
			else {
				$res[$i]['style_str'] = "background:url(img/strelka.gif) no-repeat center 24px;";
			} */
			if($v['sval'] == NULL)
				$res[$i]['sval'] = $v['user'];
			$res[$i]['info'] = $this->parent->export('MAIN','userinfo',$v['user']);
		}

		if(count($res) > 0) {
            return $engine->_tpl('tpl_jforum','_forum_posts',array(
                'list'=>$res
                , 'pages' => $pages
                , 'all_topics' => $this->parent->curl('topic','pg')
            ));
//			return smart_template(array(FORUM_TPL,'forum_posts'),array('list' => $res, 'pages' => $pages, 'all_topics' => $this->parent->curl('topic','pg')));
		}
		return "В этой теме нет сообщений";
	}

	function data($what,$from='',$perpage=''){
		switch($what){
			case "cnt":
				return @$this->database->selectCell('select count(*) from ?'.$this->base.' '.$this->orderbystr.';');
			case "row":
				return $this->database->selectRow('select * from ?'.$this->base.' where `id`=?d',$from);
			case "data": {
				if($this->base == "_forum") {
					$res_n = $this->database->query('select ?'.$this->base.'.*, ?_flesh.sval from ?'.$this->base.' '.
						' LEFT JOIN ?_flesh ON ?_forum.user = ?_flesh.id '.
						' AND ?_flesh.name = \'name\''.
						pps($this->orderbystr).' LIMIT ?d,?d',$from,$perpage);
					foreach($res_n as $k=>$v) {
						if($v['sval'] == NULL)
							$res_n[$k]['sval'] = $v['user'];
					}
					return $res_n;
				}
				else {
					return $this->database->query('select * from ?'.$this->base.' '.
						pps($this->orderbystr).' LIMIT ?d,?d',$from,$perpage);
				}
				return $this->database->query('select * from ?'.$this->base.' '.
					pps($this->orderbystr).' LIMIT ?d,?d',$from,$perpage);
				}
			case "del":
				if($this->base == "_forum_topics") {
					$this->database->query('DELETE from ?'.$this->base.' where `id`=?d OR `parent`=?d;',$from,$from);
					$this->database->query('DELETE from ?_forum where `topic`=?d;',$from);
				}
				else
					$this->database->query('DELETE from ?'.$this->base.' where `id`=?d;',$from);
				break;
			case "upd":
                if($this->base == "_forum_topics") {
					$this->database->query('update ?'.$this->base.' set ?a where `id`=?;',$from,$perpage);
                }
				break;
			case "ins":
                if (isset($from['topic']))
				return $this->database->query('INSERT INTO ?'.$this->base.' (?#) VALUES(?a);',
		   			array_keys($from),array_values($from));
		}
	}
}
?>