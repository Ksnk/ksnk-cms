<?php
/**
 * плагин - голосование
 * в таблицу ?_menu добавл€ютс€ элементы - 'голосование'
 *  [TEXT, page - количество голосовавших]->{[TEXT, page->количество ответов],...}
 * в меню добавл€етс€ элемент - "votes" с линейным списком голосований
 */
//print_r($_REQUEST);
define('VOTES_TPL','tpl_votes');

class votes extends plugin {

/**
 * “ипо кеш
 *
 * @return unknown
 */
	function getNs($clear=true){
		static $node; if ($clear && !empty ($node)) return $node ;
		$x=$this->parent->readRecord(array('name'=>'votes'));
		$node=$this->parent->nodeGet($this->parent->node($x));
		if(empty($node)) return null;
		$i=0;
		return $node=readElement($node,$i);
	}
	
	function getPluginName(){
		return 	'√олосование';
	}

	function votes_list($clear=false){
		$votes=$this->getNs();
		if(empty($votes)) return 'не инициирован плагин <a href="'
			.$this->parent->curl('do','id').'do=create&plugin='
			.__CLASS__.'">'.__CLASS__.'</a>';
		$data=array();
		//debug($votes);
		foreach($votes->el as $v){
			$data[]=array('name'=>$v->v['descr']
			,'url'=>$this->parent->curl('vote').'vote='.$v->v['id']);
		}

		$res=smart_template(array(VOTES_TPL,'votes_list'),array('list'=>$data));
		return $res;
	}

	function admin_votes(){
		if(!$this->parent->has_rights(right_WRITE))
			return $this->parent->ffirst('_loginform');
		//
		$this->parent->menu['head']=array('MAIN','_modules',$this->getPluginName(),get_class($this));
		$voteid=pps($_GET['vote']);
		if ($voteid=='new') {
			$vote=$this->new_vote('...введите сюда ваш вопрос...');
			$this->parent->go($this->parent->curl('vote').'vote='.$vote);
		}
		if (empty($voteid)) return smart_template(array(ADMIN_TPL,'theheader'),array(
		'header'=>'√олосование',
		'data'=>$this->votes_list(true)
		));

		$vote=$this->getNs();
		foreach($vote->el as $v){
			if(ppi($v->v['id'])==$voteid){
				$vote=$v ; break;
			}
		}
		
		if(empty($vote)) return $this->votes_list(true);
		$this->parent->menu['left']=array(__CLASS__,'votes_list');
		$data=array();
		//debug($votes);
		$i=1;
		foreach($vote->el as $v){
			$data[]=array(
				'trclass'=>($i++)%2==0?'even':'odd',
				'id'=>$v->v['id'],'descr'=>$v->v['descr'],'page'=>$v->v['total']);
		}

		$form=new form('admin_vote');
		$form->nostore=true;
		//$fields=array('the_header','the_text','news_date');
		if(empty($data)) $data='';

		$form->scanHtml(smart_template(array(VOTES_TPL,'admin_vote'),array(
				'error'=>pps($_SESSION['errormsg'])
				,'descr'=>$vote->v['descr']
				,'page'=>$vote->v['total']
				,'id'=>$vote->v['id']
				,'list'=>$data)));
		if($form->handle()){
			if(($vote->v['name']=='vote')&& !empty($_POST['active_'.$vote->v['id']]))
				$this->database->query(
					"update ?_flesh set `sval`='active' where `name`='name' and `id`=?d;",
					$vote->v['id']
				);
			elseif(($vote->v['name']=='active')&& empty($_POST['active_'.$vote->v['id']]))
				$this->database->query(
					"update ?_flesh set `sval`='vote' where `name`='name' and `id`=?d;",
					$vote->v['id']
				);

			if(!!$vote){
				$vote->serialize($form->var,true);
			}
			$this->parent->go($this->parent->curl());
		}
		$form->var['active_'.$vote->v['id']]=array(
			$vote->v['name']=='active'?1:0
		);
		if(!!$vote){
			$vote->serialize($form->var);
		}
		return smart_template(array(ADMIN_TPL,'theheader'),array(
		'header'=>'√олосование',
		'data'=>$form->getHtml(' ')
		));

	}

	function do_create($killall=true){
		if(!$this->parent->has_rights(right_WRITE))
			return $this->parent->ffirst('_loginform');
			
		$votes_root=$this->getNs();
		if(!empty($votes_root) && $killall){
			$x=$this->parent->nodeDelete($this->parent->node('votes'));
			$votes_root=null;
			$votes_root=$this->getNs(false);
		}	
		if(empty($votes_root)){
			$votes_root=$this->parent->nodeAdd(0,array('name'=>'votes','type'=>type_MAINMENU));
			//
			$this->new_vote(' ак вы узнали о нашем »нтернет-магазине?',
				array('через поисковые системы'
					,'через каталоги/рейтинги'
					,'через другие сайты'
					,'из рекламы'
					,'от знакомых'
				));
		}
		// create all
	}
	/**
	 * —оздать новое голосование
	 *
	 */
	function new_vote($ask,$questions=array()){
		$votes=$this->getNs();
		
		$id=$this->parent->nodeAdd($votes->node(),
			array('name'=>'vote','descr'=>$ask,'type'=>type_MAINMENU));
		if (!empty($questions))
		foreach($questions as $v){
			$this->parent->nodeAdd($id,
			array('name'=>'vote','descr'=>$v,'type'=>type_MENU));
		}
		return $id;
	}

	function vote($id=0){
		static $x; if(isset($x)) return $x;
		$x='';
		// вывести голосование по ID или первое
//		debug('echo!');
		$votes=$this->getNs();
		
		$all_vote=explode('|',trim(pps($_COOKIE['vote']),'|'));
		$i=0;

		while($i<count($votes->el)
			&&
				(
					in_array($votes->el[$i]->v['id'],$all_vote)
				||
					$votes->el[$i]->v['name']!='active'
				)
			)
		{
			$i++;
		}

		if($i>=count($votes->el)) return $this->vote_result();
		$vote=$votes->el[$i++];

		$list=array();
		foreach($vote->el as $v){
			$list[]=array('id'=>$v->v['id'],'asc'=>$v->v['descr']);
		}
		$form=new form("vote");
		$form->scanHtml(smart_template(array(ELEMENTS_TPL,'vote'),array(
			'question'=>$vote->v['descr'],
			'list'=>$list)
		));
		if($form->handle()){
			if(!empty($_POST['aaa']))
				foreach($_POST['aaa'] as $v){
					$x=$this->parent->readRecord(array('id'=>$v));
					$x['total']=ppi($x['total'])+1;
					$this->parent->writeRecord($x);
				}
			if (empty($all_vote)) $all_vote=array();
			$all_vote[]=$vote->v['id'];
			setcookie("vote", implode('|',array_unique($all_vote)), time()+24*3600);
			$this->parent->go($this->parent->curl());
		}
		$form->var['aaa']=array();
		return $x=$form->getHtml(' ');
	}
	
	function vote_result($id=0){
		static $x; if(isset($x)) return $x;
		$x='';
		$votes=$this->getNs();
		$vote=$votes->el[0];

		$list=array();
		foreach($vote->el as $v){
			$list[]=array('id'=>$v->v['id'],
				'cnt'=>$v->v['total'],
				'asc'=>$v->v['descr']);
		}
		return smart_template(array(ELEMENTS_TPL,'vote_result'),array(
			'question'=>$vote->v['descr'],
			'list'=>$list)
		);
	}
}
?>