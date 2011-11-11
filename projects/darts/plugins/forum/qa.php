<?php
/**
 * класс - модельная оболочка над tree
 */
class qa {
	
	static $tree;
	var $root,$wu_root;
	
	
	/**
	 * найти корень QA или вставить новый
	 */ 
	function __construct(){
		global $engine;
		self::$tree=$engine->export('tree');
		$this->parent=$engine;
		if (!($this->root=$engine->getPar('qa_root'))){
			$this->root=self::$tree->insertRoot();
			$engine->setPar('qa_root',$this->root);
		}
		if (!($this->wu_root=$engine->getPar('wu_root'))){
			$this->wu_root=self::$tree->insertRoot();
			$engine->setPar('wu_root',$this->wu_root);
		}
	}
	
	/**
	 * выдать все комментарии по сохраненному ID
	 */
	function do_qa(){
		$id=ppi($_GET['id'],$this->root);
		$page=ppi($_GET['pg']);
		if(!empty($page)) $page=ppi($page[$this->root]);
		if(isset($_POST['newpost'])){
			$key = array(
				'date'=>date('Y-m-d H:i:s')
				,'username'=>strip_tags($_POST['username'])
				,'quote_id'=>intval($_POST['quote_id'])
				,'user'=>ppi($this->parent->user['id'])
				,'text'=>post2comment($_POST['newpost'])
			);
			$parent=ppi($_POST['quote_id'],$this->root);
			self::$tree->insertAsChild($parent,$key);
			//$this->parent->go('',$_SERVER["REQUEST_URI"]);	
			return ' ';
		}
		$total=0;
		// qa при id=0 - вывод всего дерева вопрос-ответ с темами.
		//$par=self::$tree->getChilds($id,$page,ppi($this->options['perpage'],40),$total);
		$par=self::$tree->getDeep2($id,$page,ppi($this->options['perpage'],40),$total);
	//	debug($par);
		return $this->parent->_tpl('tpl_main','_qa',array('list'=>$par));
	}
	
	function _check(){
		// задать вопрос
		$key = array(
			'date'=>date('Y-m-d H:i:s')
			,'username'=>strip_tags($_POST['username'])
			,'user'=>ppi($this->parent->user['id'])
			,'text'=>post2comment($_POST['newpost'])
		);
		self::$tree->insertAsChild($_POST['parent'],$key);
		//$this->parent->go('',$_SERVER["REQUEST_URI"]);	
		return ' ';
	}
	
	function do_writeus(){
		$this->parent->sessionstart();
		
		if(isset($_POST['newpost'])){
			$key = array(
				'date'=>date('Y-m-d H:i:s')
				,'username'=>strip_tags($_POST['username'])
				,'user'=>ppi($this->parent->user['id'])
				,'text'=>post2comment($_POST['newpost'])
			);
			$parent=ppi($this->wu_root);
			self::$tree->insertAsChild($parent,$key);
			return ' ';
		}
		$form=$this->parent->_tpl('tpl_main','_writeus');
		if($this->parent->is_ajax && (!empty($this->parent->par['error']))) {
			return ' ';
		} else if(!is_string($form)) {
			$key='';
			foreach(array('theme','user','address','question') as $v){
				if(isset($form->var[$v]))
					$key[$v]=pps($form->var[$v]);
			}
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

	/**
	 * вставить комментарий
	 * @param int $id - рут комментария
	 * @param array $key - комплект параметров комментария
	 */
	function insertComment($id,$key){
		return tree::getChilds($id,$page,$this->options['perpage'],$total);
	}
	
/*****************************************************************************/
	
	
	/**
	 * интерфейсная часть
	 */	
	function getText(){
		global $engine;
		if(!empty($_POST['action']) && $_POST['action'] == 'newkomment'
			&& (!isset($_POST['comment']) || $_POST['comment']==$this->v['id'])
		) {
			$key = array(
				'topic'=>$this->v['id']
				,'date'=>date('Y-m-d H:i:s')
				,'username'=>strip_tags($_POST['username'])
				,'user'=>intval($_POST['user'])
				,'text'=>post2comment($_POST['newpost'])
				,'quote'=>post2comment($_POST['quote'])
			);
			comments::add($key);
			//debug($engine->curl());
			
			$engine->go('',$_SERVER["REQUEST_URI"]);
		}
		
		return $engine->_tpl('tpl_jelements','_komment',array(
			'par'=>$this->v,
			'data'=>comments::get($this->v['id'])
		));
	}
	
}