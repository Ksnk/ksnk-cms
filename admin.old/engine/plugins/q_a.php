<?php
/**
 * Страничка  Question & Answers
 * полный плагин
 * Логика:
 *  - на сайте заполняется форма вопрос с адресом юзера
 *  - в панели администратора имеется таблица - вопросы,
 *  - после получения ответа вопрос помещается на сайт, ответ посылается по почте
 */

define('QA_TPL','tpl_qa');

class qa extends ml_plugin {

	function havetheme(){
		if(!defined('QA_WITH_THEME'))
			return false;
		else {
			if($this->parent->is_ajax)				
				return array();
			else
				return false;	
		}
	}
	
	function data($what,$from='',$perpage=''){
		if(defined('QA_WITH_THEME')){
			if(pps($_GET['url']))
				$theme=$_GET['url'];
			else {
				$sm=$this->parent->export('sitemap','getSiteMap',$_GET['id']);
				$theme=$sm->v['id'];
				//debug($sm->v);
			}		
			 $where='`theme`="'.mysql_real_escape_string($theme).'"';
		} else 
			$where='';
		
		switch($what){
			case "ins":
				if(empty($from['theme']))
					$from['theme']=$theme;
				return $this->database->query('INSERT INTO ?'.$this->base.' (?#) VALUES(?a);',
		   			array_keys($from),array_values($from));
			case "cnt":
				return @$this->database->selectCell('select count(*) from ?'.$this->base.pp($where,' where ').';');
			case "data":
				return $this->database->query('select * from ?'.$this->base.' '.pp($where,' where ').
					pps($this->orderbystr).' LIMIT ?d,?d',$from,$perpage);
			default:
				return parent::data(&$what,&$from,&$perpage);
		}
	}
	
	function comment_number($id){
		$id=pps($id);
		if (empty($id)) return 0;
		return 	@$this->database->selectCell('select count(*) from ?'.$this->base.' where `theme`="'.mysql_real_escape_string($id).'"');
	}
	
	//var $unAnsweredOnly=true;
	function do_convert(){
		$this->database->selectRow('ALTER TABLE ?_qa CHANGE `theme` `theme` VARCHAR( 255 ) DEFAULT "0"') ;
		return 'field theme converted!';
	}

	function qa($parent){
		parent::ml_plugin($parent);
		$par=array(
		'title'=>'Вопрос-Ответ'
		,'fields'=>array(
					array('Автор','user','text_edit'),
					array('адрес','address','text_edit'),
					array('дата','date','text_edit'),
					array('вопрос','question','html_edit','afilter'=>30),
					array('ответ','answer','html_edit','afilter'=>30)
		)
		,'base'=>'_qa'
		,'orderbystr'=>' order by `date` DESC'
		,'prefix'=>'qa');
		
		if(defined('QA_WITH_THEME')){
			array_unshift($par['fields'],array('тема','theme','menu','xoptions'));
		}
		
		parent::_init($par);
	}

	function check_data(&$upd,&$res){
		if(empty($res['date']))
			$upd['date']=date('Y/m/d H:i:s');
		else if(isset($upd['date'])){
			$dres=strtotime ($upd['date']);
			if( $dres===-1 || $dres===false)
				$upd['date']=date('Y/m/d H:i:s');
		}
		if(isset($upd['question']))
			$upd['question']=trim(preg_replace('~^\s*<br\s*/?>|<br\s*/?>\s*$~i','',$upd['question']));
		if(isset($upd['answer']))
			$upd['answer']=trim(preg_replace('~^\s*<br\s*/?>|<br\s*/?>\s*$~i','',$upd['answer']));
		return true;
	}

	function get_parameters($par){
		$par['list'][]=array('sub'=>'Результаты поиска','title'=>'Количество результатов на страницу','name'=>'search_per_page');
		$par['list'][]=array('sub'=>'Вопрос-ответ','title'=>'Количество вопросов на страницу','name'=>'qa-perpage');
	}

	function admin_qa(){
		if (pps($_GET['qa'])) $this->orderbystr=' where answer="" order by `date` DESC';
		return
		smart_template(array(ADMIN_TPL,'theheader'),array(
		'header'=>$this->getPluginName(),
		'data'=>parent::admin_plugin()));
	}
/**
 * Вывод формы ввода нового вопроса на странице сайта
 *
 * @return unknown
 */

	function mail ($par){
		$to=pps($this->parent->getPar('mail_admin'),'art@xilen.ru');
		$subj='Сообщение с web-сайта "'.$_SERVER['SERVER_NAME'].'"';

		$mail=new html_mime_mail(
			'From: nospam@somethere.com' . "\r\n" .
			pp($par['address'],'Reply-To: ',"\r\n").
   			'X-Mailer: PHP/' . phpversion()
		);
		$mail->add_html(smart_template(array(QA_TPL,'mail'),$par));
		$mail->build_message('win');
		$msg=$mail->send(  $to, $subj);

		if ($msg) {
			return "Ваше письмо отправлено. Спасибо за участие.";
		}
        else {
        	return "К сожалению не удалось отослать Ваше письмо.";
        }
	}

	function do_create($killall=true){
		if(!$this->parent->has_rights(right_WRITE))
			return $this->parent->ffirst('_loginform');
		if($killall){
			$this->database->query('DROP TABLE IF EXISTS ?_qa;');
		}
		$this->database->query("CREATE TABLE ?_qa (
				  `id` int(11) NOT NULL auto_increment,
				  `theme` varchar(255) NOT NULL default 0,
				  `user` varchar(255) NOT NULL default '',
				  `address` varchar(255) NOT NULL default '',
				  `question` text NOT NULL,
				  `answer` text NOT NULL,
				  `date` DATETIME,
				PRIMARY KEY  (`id`), 
  				KEY `theme` (`theme`)
				);");
	}
	
/**
 * Вывод страницы Q&A на страницу сайта
 * парамеры $_GET:
 *  - pg - с такой страницы
 *
 * @return unknown
 */
	function do_qa($unAnsweredOnly=true,$tpl='qa_list',$theme=''){
		ml_plugin::setupmenu();
		$unAnsweredOnly=!$unAnsweredOnly?false:pps($_GET['qa'])=='';
		$eq_sign="!=";
		if(pps($_GET['page'])=='qa.php'){
			$eq_sign="=";
		}

		if(pps($_GET['del'])){
			$res=$this->database->selectRow('delete from ?_qa '.
				'where `id`=?;',$_GET['del']);
			$url=$this->parent->curl('del');
			$this->parent->go(substr($url,1,strlen($url)-1));
		}

		$page=ppi($_GET['pg']);
		$perpage=$this->parent->getPar('qa-perpage');
		$perpage=ppi($perpage,10);
		
		if(empty($theme)){
			$sm=&$this->parent->exports['sitemap'];
			$menu=$sm->getSiteMap();
			$el=$menu->scan($this->parent->cur_menu);
			//debug($el);
			$theme=$el->v['id'];
		}
		if(defined('QA_WITH_THEME') && !empty($theme)) { 
			$where='`theme`="'.mysql_real_escape_string($theme).'"';
			$_SESSION['theme']=$theme;
		}
		else $where='';
		//*--*/echo $where;
//		$sql='select * from ?_qa ;';
//		$res=@$this->database->select($sql);
//debug($res);

		$sql='select count(*)as cnt from ?_qa '.
			($unAnsweredOnly?'where `answer`'.$eq_sign.'"" '.pp($where,'and '):pp($where,'where ')).
			';';
		$res=@$this->database->selectRow($sql);
		if($res===null){
			return ' ';
		}
		$pages='';
		//*--*/print_r($perpage);
		if($all_cnt=pps($res['cnt'])){
			$res=$this->database->select('select * from ?_qa '.
				($unAnsweredOnly?'where `answer`'.$eq_sign.'"" '.pp($where,' and '):'where '.$where).
				' order by `date` DESC LIMIT '.($page*$perpage).','.$perpage.';');
			$pages=$this->parent->calc_Pages($all_cnt,$perpage,$page);
		} else
			$res=array();
//*--*/ print_r($pages);
/*		$locale = 'ru_RU.1251';
 		$r = setlocale( LC_CTYPE, $locale); //important
 		$r = setlocale( LC_TIME, $locale);*/
		foreach($res as $i=>$v){
			if(ctype_digit($v['date']))
				$v['date']=date('D M j G:i:s',$v['date']);
			$s=explode(', ',toRusDate($v['date']));
			$res[$i]['date']=$s[0];
			$res[$i]['year']=$s[1];
			$res[$i]['time']=substr($v['date'],-8,5);
			//print_r($v);
		}
		if(empty($res))$res='';
		$res=array('list'=>$res,
			'pages'=>$pages);
		if(pps($_GET['qa'])=='all')
			$res['all']=array();
		else
			$res['nall']=array();
		$this->getOptions();
		if(!empty($theme))
		$res['theme']='&theme='.urlencode($theme);
		
		//print_r($res);
			
		return smart_template(array(QA_TPL,$tpl),$res);
	}
	
	function getOptions(){
		static $x ; if(!empty($x)) return $x;
		//$this->parent->ns->GetBranch('');
		$themes=$this->parent->export('sitemap','getSiteMap','qa');
		$options=array();//debug($themes);

		$this->theme="";
		$tid=ppi($_GET['id']);
		if(empty($tid) && !empty($themes->el)){
			if(empty($_SESSION['qa_theme'])){
				$tid=$themes->el[0]->v['id'];
			} else
				$tid=$_SESSION['qa_theme'];	
		}
		$_SESSION['qa_theme']=$tid;
		if(!empty($themes->el))
		foreach($themes->el as $v){
			if($tid==$v->v['id'])
				$this->theme=$v->v['name'];
			$options[]=array('id'=>$v->v['id']
				,'name'=>strip_tags($v->v['name'])
				,'selected'=>$tid==$v->v['id']?' selected ':''
			);
			if (!empty($v->el)){
				foreach($v->el as $vv){
					if($tid==$vv->v['id'])
						$this->theme=$vv->v['name'];
					$options[]=array('id'=>$vv->v['id']
						,'name'=>'&nbsp;&nbsp;&nbsp;&nbsp;'.str_replace(' ','&nbsp;',strip_tags($vv->v['name']))
						,'selected'=>$tid==$vv->v['id']?' selected ':''
					);
				}
			}
		}
		//debug('xxx');//$options);
		return $x=$options;
	}
}

class writeus extends ml_plugin {
	
	function writeus(&$parent){
		parent::ml_plugin($parent);
		$this->parent->sessionstart();
		$par=array(
				"Ваше имя"=>array('user','require'=>true),
				"Контактный e-mail или телефон"=>array('address'),
				"Ваш вопрос"=>array('question','textarea','require'=>true),
			);
		if($parent->is_ajax){
			$par["Тема"]=array('theme','hidden','value'=>pps($_SESSION['theme'],'qa'));
		}
		
		parent::_init(array(
			'title'=>'Написать нам'
			,'fields'=>$par
		));
	}

	function do_writeus(){
		$this->parent->sessionstart();
		ml_plugin::setupmenu();

		if(isset($_SESSION['qa_Message'])) {
			$x=$_SESSION['qa_Message'];
			unset($_SESSION['qa_Message']);
		} else {
			$x=$this->fields;
		}
		$form=$this->parent->export('MAIN','SimpleForm',$x);
		if($this->parent->is_ajax && (!empty($this->parent->par['error']))) {
			return ' ';
		} else if(!is_string($form)) {
			$key='';
			foreach(array('theme','user','address','question') as $v){
				if(isset($form->var[$v]))
					$key[$v]=pps($form->var[$v]);
			}
			debug('xxx');
			if(empty($key['theme'])) 
				$key['theme']=$_SESSION['theme'];
			$key['date']=date('Y/m/d H:i:s');
			$this->database->query('INSERT INTO ?_qa (?#) VALUES(?a);',
	   			array_keys($key),array_values($key)
	   		);
	   		$_SESSION['qa_Message']=smart_template(array(QA_TPL,'qathanks'),
				array('url'=>pps($_SESSION['qa_returnaddress'],$this->parent->index().'/')
					,'result'=>$this->parent->export('qa','mail',$key))) ;
	   		$form->var["question"]="";
	   		$form->storevalues();
	   		$this->parent->go($this->parent->curl());
	   		$x=$_SESSION['qa_Message'];
	   		unset($_SESSION['qa_Message']);
			return $x; // ajax only
		}
		$x=pps($_SERVER['HTTP_REFERER']);
		if ($x && strpos($x,'=writeus')===false)
			$_SESSION['qa_returnaddress']=$_SERVER['HTTP_REFERER'];
		return $form;
	}

	function get_parameters($par){
		$par['list'][]=array('sub'=>'Пишите нам','title'=>'Адрес для пересылки','name'=>'mail_admin');
	}

	function admin_writeus(){
		return
		smart_template(array(ADMIN_TPL,'theheader'),array(
		'header'=>"Написать нам",
		'data'=>$this->parent->ffirst('do_siteparam',__CLASS__)));
	}

}
?>