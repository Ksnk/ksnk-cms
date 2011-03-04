<?php
		
define ('type_MENU',0);
//define ('type_CELL',1);
//define ('type_LINKS',3);
//define ('type_HEADER',4);
//define ('type_TABLE',6);
//define ('type_GALLERY',7);
define ('type_KATALOG',8);
define ('type_ARTICLE',10);
define ('type_PIC',11);
define ('type_MAINMENU',12);
//define ('type_LINK',13);
//define ('type_ROW',14);
define ('type_HIDDENMENU',15);
//define ('type_NEWTEXTPIC',16);
define ('type_PLUGIN',17);
define ('type_ARTICLELIST',18);
//define ('type_EMPTYCELL',21);
//новые типы
//define ('type_GALLERY2',26);

/*<% if(!empty($elements)){ echo "*"."/";
	foreach($elements as $k=>$e){printf("define('%s',%s);\n\r",$e['type_name'],$k);}
	echo "/"."*";
}%>*/ 

function TO_URL($x,$def=''){
 	if(strpos($x,'?')!==false)
 		return toUrl($x);
 	else if(strpos($x,'/')===false)
 	    if (empty($def))
 			return toUrl(TMP_DIR.$x);
 		else {
 			if (is_file(TMP_DIR.$x)){
 				return toUrl(TMP_DIR.$x);
 			} else 
 				return trim($def); 
 		}
 	else
 	    if (empty($def))
 			return toUrl($x);
 		else if(($y=strpos($x,'uploaded'))!==false){
 			$y=TMP_DIR.substr($x,$y+9);
 			//debug(file_exists($y).'--'.$y);
 			if (is_file($y)){
 				return toUrl($y);
 			} else 
 				return trim($def); 
 		} else
 			return toUrl($x);				
 	
}

function evenodd($i){
	return ($i & 1)==0?'even':'odd';
}

function itemByType($type,$value=false){
	static $types=array(
		type_MENU=>'xMenuLine'
		,type_MAINMENU=>'xMainMenu'
//		,type_CELL=>'xCell'
//		,type_EMPTYCELL=>'xEMPTYCell'
//		,type_HEADER=>'xHeader'
//		,type_TABLE=>'xTable'
//		,type_GALLERY=>'yGallery'
//		,type_KATALOG=>'xKatalogue'
		,type_ARTICLE=>'xArticle'
//		,type_LINK=>'xLink'
//		,type_LINKS=>'xLinks'
		,type_PIC=>'xPic'
		,type_ROW=>'xRow'
		,type_HIDDENMENU=>'xHiddenMenu'
		,type_PLUGIN=>"xPlugin"
		,type_ARTICLELIST=>"xAList"
//		,type_GALLERY2=>"x_GALLERY"
//		,1001=>"yGallery"
/*<% if(!empty($elements)){ echo "*"."/";
	foreach($elements as $k=>$e){printf(",%s=>'%s'\n\r",$k,$e['class_name']);}
	echo "/"."*";
}%>*/ 		
		);
	if(!$value){
		if(isset($types[$type]))
			return 	$types[$type];
		else
			return 'xElement';		
	} else
		$types[$type]=$value;	
}

function nameByType($type,$value=false){
	static $types=array(
//		 type_LINKS=>'Cсылки и файлы'
		//,type_HEADER=>'Заголовок' 
		type_TABLE=>'Таблица'
//		,type_CELL=>'Ячейка'
		,1001=>'Галерея'
		,type_KATALOG=>'Каталог'
		,type_PLUGIN=>"Модуль"
		,1000=>'Колонка'
		,type_ARTICLELIST=>"Список статей"
/*<% if(!empty($elements)){ echo "*"."/";
	foreach($elements as $k=>$e){printf(",%s=>'%s'\n\r",$k,$e['name']);}
	echo "/"."*";
}%>*/ 	);
	if(empty($type))
		return;	
	if(!$value)	
		return pps($types[$type],'тип:'.$type);
	else
		$types[$type]=$value;	
}

function & readElement(&$res,&$i){
	$row=null;
	//printf('line %d "%s" var_dump($res):',__LINE__,$i);var_dump($res);
	if($i<count($res))
	{
		$x=itemByType(ppi($res[$i]['type']));
		if($x=='xGallery') $x='yGallery'; // XXX: затычка! 
		$row=&new $x();
		$row->readFrom($res,$i);
	}
	return $row;
}

/**
 * Базовый класс для реализации всех элементов
 */
class xElement { // интерфейс
	
	function clearCache(){
		global $engine;
		$tmp=$engine->nodeGetBackPath($this->node());
		$engine->cache('anchor',$tmp[0]['node']);
	}
	
	function delete(){
		global $engine;
		$engine->nodeDelete($this->node());
	}
	
	function addElement(&$key){
		global $engine;
		$engine->nodeAdd($this->node(),$key);
	}
	
	function writeAncors(&$anc,&$obsolete){
		global $engine;
		if ($this->v['type']==type_ARTICLE && $this->v['level']>0 ){
			$x=$this->v;
			$x['goto']=$this->v['id'];
			$anc[]=$x;
			$obsolete[$this->v['id']]=1;
		}
		elseif (defined('ANCHOR_STORE')&&($this->v['type']==type_ANCHOR) )
		{ 
			$x=$this->v;
			$x['goto']='';
			$anc[]=$x;
		}
		for($i=0;$i<count($this->el);$i++){
			if(method_exists($this->el[$i],'writeAncors'))
				$this->el[$i]->writeAncors($anc,$obsolete);
		}
	}
	
	function getData(){
		return $this->v;
	}
	
	function getForm(){
		return smart_template(array(ELEMENTS_TPL,'header_edit_line'),$this->v);
	}
	
	function serialize(){
		return ;
	}
	
	function node(){
		global $engine;
		return $engine->node($this->v);
	}

	function Create($key,$parent) {
		global $engine;
		return $engine->nodeAdd($parent,$key);
	}

	function getText(){
		debug( "unknown type ",$this->v);
		return '';
	}

	function aligns(&$a){
		if (empty($a)) return 'left';
		else if ($a==1) return 'center';
		return 'right';
	}

	function pasteData(&$data){
		global $engine;
		if(empty($data)) return;
		//print_r($data);echo -$this->node().';'.$this->v['id'];
//		if ($data[0]['level']>1)
//			$engine->error('Нельзя вставить такой элемент');
//		else if	($data[0]['level']==1){
			// вставляем как сиблинга
			$i=0;
			ajax::insertPage(-$this->node(),1,$data,$i);
//		}
	}

	function getContextMenu(){
		switch($this->v['type']){
			case type_ARTICLE:
				$text='Статья';
				break;
			case type_PIC:
				$text='Картинка';
				break;
/*			case type_LINK:
				$text='Ссылка';
				break;*/
			case type_ROW:
				$text='Строка';
				break;
/*			case type_CELL:
				$text='Ячейка';
				break;*/
			default:
				$text=ppx(nameByType($this->v['type']),'type '.$this->v['type']);
		}
		return sprintf('<div style="background:#dddddd;">элемент: %s</div>'.
			'<a href="#cutItem:'.$this->v['id'].'" onclick="return false;">Вырезать элемент</a>'.
			'<a href="#copyItem:'.$this->v['id'].'" onclick="return false;">Копировать элемент</a>'.
			'<a href="#pasteItem:'.$this->v['id'].'" onclick="return false;">Вставить элемент</a>'.
			'<hr>'.
			'<a href="#deleteItem:'.$this->v['id'].'" onclick="return false;">Удалить элемент</a>',
			$text);
	}

	function newData(){
		global $engine;
		$engine->error('Вставка данных невозможна');
		return ' ';
	}

	function getUlLi(){;}
	function readFrom(&$res,&$i){
		$lev=ppi($res[$i]['level']);
		//printf('line %d "%s" var_dump($res):',__LINE__,$i);var_dump($res);
		$this->v=$res[$i++];
//echo count($res),' ';
		$this->el=array();
		while (($i<count($res)) && ($lev<$res[$i]['level'])) {
			$x=&readElement($res,$i);
			$x->parent=&$this;
			$this->el[]=&$x;
		} ;
	}
	function serialize_var(&$formvar,$dir,$var){
		global $engine;
		$id=$this->v['id'];
		if(!$dir) // выложить переменные в форму
		{
			foreach($var as $k=>$vv)
				if(isset($this->v[$vv]))
					$formvar[$k.$id]=$this->v[$vv];
		}
		else
		{
			$key=$this->v;
			$changed=false;
			foreach($var as $k=>$vv)
				if(	isset($_POST[$k.$id])
					&& (pps($this->v[$vv])!=trim($_POST[$k.$id])))
				{
					$this->v[$vv]=trim($_POST[$k.$id]);
					$key[$vv]=$this->v[$vv];
					$changed=true;
				}
			if($changed)
				$engine->writeRecord($key);
		}
	}
	function serialize_order_menu(&$formvar,$dir,$var='order_'){
		// переместить внутри дерева вверх-вниз и по номеру
		global $engine;
		if(empty($this->v['id'])) return;
		$id=$this->v['id'];
		if(!$dir) // выложить переменные в форму
		{
			$formvar[$var.$id]='';
		}
		else
		{
			if(!empty($formvar[$var.$id])){
				// ренумерация!! $parent - тот массив, в котором мы сидим
				if(preg_match('~(\+?|-)(\d+)~',$formvar[$var.$id],$m)){
					if($m[1]=="-"){
						$x=0-$formvar[$var.$id];
						//debug($this->v);
						while($x-- && $engine->nodeMoveUp($this->node())) ;
					} else {
						$x=0+$formvar[$var.$id];
						while($x-- && $engine->nodeMoveDn($this->node())) ;
					}
				}
			}
		}
	}
}

class xMenuLine extends xElement	{
	var $v;
	function xMenuLine(){
		$this->v=array();
	}
	function getUlLi($level=1000,$curonly=false,$skip=0,$catalogue='catalogue',$pclass=''){
		if($level<=0) return '';
		global $engine ;
		$data='';
		if(!defined('IS_ADMIN') && isset($this->v['skipit'])) return '';
		//var_dump($this);
		if($skip<=0 ){
			// is the page empty?
			if(!is_array($this->v)) $this->v=$this->parent->v;
			$class=array();
			if(!empty($pclass)) $class[]=$pclass;
			if(!empty($this->v['current']))$class[]="current";
			if(!empty($this->v['selected']))$class[]="selected";
			
			if(isset($this->v['skipit']))$class[]="hmenu";
			
			if(!empty($class)) 
				$class=' class="'.implode(' ',$class).'"';
			else 
				$class="";
			$data.='<li'.$class.'><a'.$class
					.' href="'.$this->getUrl().'">'
					.pps($this->v['descr'],pps($this->v['name'],'root')).'</a>';
				
		}
		if(isset($engine->sitemap->anchors))
		{
			if(isset($this->v['page']) && !empty($this->v['page']))
			if(!isset($engine->sitemap->anchors[$this->v['page']])){
				//debug('hello!'.$this->v['page']);
				// заполняем одну ветку "anchors"
				$obsolete=array();
	            $tmp=$engine->nodeGet($this->v['page']);
		        $x=0; $tmp=readElement(&$tmp,$x);
		        //debug($tmp);
		        $anc=array();
		        if(!empty($tmp)){
		        	$tmp->writeAncors($anc,$obsolete);
				}
				$engine->cache('anchor',$this->v['page'],$anc);
				$engine->sitemap->anchors[$this->v['page']]=$anc;
			}
			$x='';
			$vv=&$engine->sitemap->anchors[ppi($this->v['page'],-1)];
			$showanc=(defined('ANCHOR_STORE') && ANCHOR_STORE) || $engine->is_ajax;
			if(!empty($vv) && is_array($vv))
			//debug('xxx');
			foreach($vv as $v)if(isset($v['goto']))
			{
				if($v['type']==type_ANCHOR){
					if($showanc){
						$anc=$v['id'];
						$x.='<li class="blue'.(ppi($engine->page_id,-1)==$v['goto']?' current':'').'"><a '.(ppi($engine->page_id)==$v['goto']?'class="current" ':'')
						.'href="'.$this->getUrl().pp($anc,'#anc_').'">'.
						ppx(pps($v['item_text']),
							'якорь#'.$anc
						).' '.pps($v['item_name'])
						.'</a></li>'	;
					}
				} else {	
					$x.='<li class="green'.(ppi($engine->page_id)==$v['goto']?' current':'').'"><a '.(ppi($engine->page_id)==$v['goto']?'class="current" ':'')
					.'href="?do=page&id='.$v['goto'].'">'.
					pps($v['item_text'],
					ppx(pps($v['item_title']).' '.pps($v['item_name']),
					'xxx'
					))
					.'</a></li>'	;
				}
			}
//			$vv=null;
			 
			if(!empty($x))
				$data.='<ul>'.$x.'</ul>';
		}
		//debug('$curonly :'.$curonly);
		//debug('$skip :'.$skip);
		//debug($this);
		if(!empty($this->el)// && is_array($this->v)
			&& (!$curonly || !empty($this->v['current']))
			&& ($skip>0 || ($this->v['name']!=$catalogue) ||$this->v['level']<2)
		){
			$x='';
			for($kk=0;$kk<count($this->el);$kk++)
			{
				$x.=trim($this->el[$kk]->getUlLi($level-1,$curonly,$skip-1,$catalogue,
				($kk==count($this->el)-1?'last':'')));
			}
			if($skip<=0 && !empty($x))
				$data.='<ul>'.$x.'</ul>';
			else
				$data.=$x;
		}
		if($skip<=0)
			$data.='</li>';
		return $data;
	}

	function rename($id,$nname,$pg='id'){
		global $engine;
		if($pg=='id'){
			$engine->database->query('update ?_flesh set `sval`=? where `name`="name" and `id`=?;',
	   			$nname,$id);
		}
	}

	function newmenu($id,$nname,$url='',$type=0){
		global $engine;
		$x=$engine->ffirst('getSiteMap');
		$x=$x->scan($id);
		if(!empty($x->v['id']))	{
			$key=array('name'=>$nname,'url'=>$url,'type'=>$type);
			if(!empty($x->v['url']) && $x->v['level']>1){
				$key['url']=class_exists($x->v['url'])?$x->v['url']:'';
			} 
			$engine->nodeAdd($engine->node($x->v),$key);
		}
	}
	
	function serialize(&$formvar,$dir=false,$level=3){
		global $engine;
		if($level<=0) return '';
		if(!empty($this->v['id'])){
			xElement::serialize_order_menu($formvar,$dir,'order_');
			xElement::serialize_var($formvar,$dir,
				array(
					'name_'=>'name'
					,'descr_'=>'descr'
					,'page_'=>'page'
					,'url_'=>'url'
					,'type_'=>'type'
					),'?_menu'
			);
		}
		if(!$dir)
		{
			$formvar['new_line']='';
			$formvar['new_url']='';
			$formvar['new_descr']='';
			$formvar['new_page']='';
			$formvar['del']='';
		}
		else
		{
			if( (!empty($formvar['new_line'])|| !empty($formvar['new_descr']))){
				$key=array();
				foreach(array('descr','page','url') as $vv){
					if(!empty($formvar['new_'.$vv]))
						$key[$vv]=$formvar['new_'.$vv];
				}
				unset($formvar['new_line'],$formvar['new_descr']);
				$key['name']=pps($formvar['new_line']);
				$id=$engine->ns->nodeAdd(ppi($v['id']),$key);
	   			$formvar['new_line']=null;
	   			$formvar['new_url']=null;
			}
		}
		//printf('line %d  var_dump($this->article):',__LINE__);var_dump($this->article);
		if($level==3 && isset($this->article)){
			$this->article->serialize($formvar,$dir);
		}

		if (!empty($this->el))
		foreach($this->el as $k=>$v)
			$this->el[$k]->serialize($formvar,$dir,$level-1);
		if($dir)
		{
			if(!empty($formvar['del'])){ // so, del button pressed!
				$del=$formvar['del'];
				if(preg_match('~^(.+)_([^_]+)$~i',$del,$m)){
					$tmp=$engine->nodeGetBackPath($engine->node($m[2]));
					$engine->cache('anchor',$tmp[0]['node']);
					
					if($this->deleteLine($m[2],$m[1]))
						unset($formvar['del'],$_POST['del']);
				}
			}
		}
	}

	function deleteLine($id,$pg='id'){
		global $engine;
		//backtrace();
			
		if(empty($id)) return false;
		if($pg!='id'){
			$item=$engine->readRecord(array('id'=>$id));
			$x=itemByType(ppi($item['type']));
			$x=&new $x();
			$x->v=$item;
			$x->delete($pg);  
			return true;
		} else {
			$menu=$engine->export('sitemap','getSiteMap');
			$item=$menu->scan($id);
			if(empty($item)) return false;
			$items=$engine->nodeGet($item->node());
			if(!empty($items))
			foreach($items as $v){
				if(
					in_array($v['type'],array(type_MAINMENU,type_HIDDENMENU,type_MENU)) 
					&&
					!empty($v['page'])
				){
					//echo('  delete node:'.$v['page']);
				
					$engine->nodeDelete($v['page']);
				}
			}
			//echo('  delete node:'.$item->node());
			$engine->nodeDelete($item->node());
			return true;
		}
	}

	function getUrl(){
		global $engine;
		if(!defined('IS_ADMIN')&& isset($this->v['nolink'])) 
			return $engine->curl('do','id').$this->v['nolink'];
		$x=trim(pps($this->v['url']));	
		if (preg_match('~https?:|ftp:|/~',$x))
			return $x;
		else if ($x && $this->v['level']<3){
			return $engine->getRoot().'/'.$x;
		} else {
			//debug('xxx '.$this->v['id']);
			return $engine->getRootUrl('',$this->v['id']);
		}
	}
	/**
	 * Читать статью и выдать заголовки
	 */
	function getContent(){
		global $engine ;
		if (ppi($this->v['type'])!=type_MAINMENU) {
			$id=ppi($this->v['page']);
			if($id!=0){
				if(defined('IS_ADMIN'))
					$res=$engine->nodeGet($id);
				else
					$res=$engine->nodeGet($id,3);
			}	
			if (empty($res)){
				$key=array('type'=>type_ARTICLE);
				$id=$engine->nodeAdd(0,$key);
				$this->v['page']=$id;
				$engine->writeRecord($this->v);
				$res=$engine->nodeGet($id);
			}
			$i=0;
			$keys=array();
			$this->article=readElement($res,$i);
			if(defined('IS_ADMIN'))
				return $this->article->getForm();
			else
				return $this->article->getText($keys);
		}
		return false;
	}

	function getForm($level=2){
		if($level<=0) return '';
		//$this->v['prefix']=str_repeat('&nbsp;',4*(3-$level));
		if($level==2)
			$x='';
		else
			$x=smart_template(array(ELEMENTS_TPL,'menu_edit_line'),$this->v);
		//var_dump($this);
		if(!empty($this->el))
		foreach($this->el as $v){
			$x.=$v->getForm($level-1);
		}
		if($level==2){
			$x.=smart_template(array(ELEMENTS_TPL,'menu_edit_addnew'),array());
		}

		return	$x;
	}
	
	function &scan($id){
		static $null=false;
		if (empty($id)) return $this;
		if(is_int($id) || ctype_digit($id)){
			if(ppi($this->v['id'])==$id) return $this ;
		} else if(is_array($this->v)) {
			if (pps($this->v['name'])==$id || pps($this->v['url'])==$id)
				return $this ;
		} 
		if(!empty($this->el))
		foreach($this->el as $v)
			if(method_exists($v,'scan'))
			if($x=$v->scan($id)) return $x;
		return $null ;
	}
	
	function scan_callback($id,$callback){
		//debug($id);
		if (empty($id)) return true;
		if(is_int($id) || ctype_digit($id)){
			//debug(pps($this->v['url']).'<>'.$id);
			if($this->v['id']==$id) { 
				$callback($this) ;
				return true;
			}
		} else {
			//debug(pps($this->v['url']).'<>'.$id);
			if(pps($this->v['url'])==$id) { $callback($this) ;return true;}
		}
		if(!empty($this->el))
		foreach($this->el as $k=>$v){
			if(method_exists($this->el[$k],'scan_callback'))
			if($this->el[$k]->scan_callback($id,$callback)){
				$callback($this);
				return true;
			}
		}
		return false ;
	}
}

class xMainMenu extends xMenuLine {

}

class xHiddenMenu extends xMenuLine {
function getUrl(){
		global $engine;
		return $engine->curl('do','id').'do=menu&id='.$this->v['id'];
	}
}
/**
 * список статей с форматом отображения
 *
 */
class xAList extends xElement {
	function getText(&$keys){
		global $engine;
		$t= '';
		$anchors=array();
		$key=array('list'=>array());
		if(!empty($this->el)){
			$sav=$engine->tpl_elements;
			$engine->tpl_elements='katalog_alist';
			foreach($this->el as $k=>$v){
				if(isset($this->el[$k]->v['item_url'])){
					$anchors[$this->el[$k]->v['item_url']]=1;
				}
				$key['list'][]=array(
				    'title'=>array_merge(array('sub_title'=>$engine->export('sitemap','sub')),$this->el[$k]->v),
					'data'=>$this->el[$k]->getText($keys));
			}
			$engine->tpl_elements=$sav;
		}
		if (!empty($anchors)){
			$anchors=array_keys($anchors); sort($anchors);
			$key['anchor']=array();
			foreach($anchors as $k){
				$key['anchor'][]=array('anchor'=>$k);
			}
		}
		$key['list'][count($key['list'])-1]['last']=' ';
		return smart_template(array(ELEMENTS_TPL,'alist0'),$key);
	}

	function getForm($numb){
		$x='';
		if(!empty($this->el))
		foreach( $this->el as $v) {
			$x.=smart_template(array(ELEMENTS_TPL,'article_line'),$v->v);
		}
		$par=$this->v;
		$par['links']=$x;
		$par['trclass']=evenodd($numb);
		$x=smart_template(array(ELEMENTS_TPL,'article_list'),$par);
		return	$x;
	}
	function serialize(&$formvar,$dir=false){
		global $engine;
		xElement::serialize_var($formvar,$dir,
			array(
				'item_name_'=>'name'
				,'item_align_'=>'align'
				,'item_text_'=>'text'
				,'item_url_'=>'url'
				,'item_columns_'=>'page'
			)
		);
		xElement::serialize_order_menu($formvar,$dir,'item_order_');
		if($dir){
			if(!empty($_POST['new_article_'.$this->v['id']]))
			{
				$engine->nodeAdd($this->node(),array('name'=>'subarticle','type'=>type_ARTICLE));
			}
		}
		if(!empty($this->el))
		foreach($this->el as $v){
			$v->serialize($formvar,$dir);
		}
	}
}

/**
 * Контейнер для всех остальных элементов.
 */
class xArticle extends xElement {

	function getForm(){
		
		$data='';//if($level<=0) return '';
		//echo __LINE__."\$this->el" ;var_dump($this->v);
		$keys=array();
		$this->serialize($keys,false,true);
		//debug($keys);
		$hitem=array('sname'=>'article_title_','surl'=>'article_keywords_','stext'=>'article_descr_');
		$i=1;
		$vV=$this->v;
		foreach($hitem as $k=>$v)
			if(isset($keys[$v]))
			{
				$s=strip_tags($keys[$v]);
				if(preg_match('/(?:\S+\s+){2}/',$s,$m)){//'.$vv['afilter'].'
					$s=$m[0].' ...';
				}
				$vV[$k]=$s;
			}
		if(defined('ARTICLE_WIDTH_IMAGE'))
			$vV['image']=$vV;	
		if(!empty($this->el))
		foreach($this->el as $v){
			$data.=$v->getForm($i++);
		}
		
		$options='';
		$opt_array=$GLOBALS['opt_array'];
		//debug($GLOBALS['opt_array']);
			
		foreach($opt_array as $v){
			$x=nameByType($v);
			$options.=sprintf('<option value="%s">%s</option>',$v,$x);
		}
		if(ppi($_GET['adv']))
			foreach(array(type_PLUGIN) as $v){
				$x=nameByType($v);
				$options.=sprintf('<option value="%s">%s</option>',$v,$x);
			}
		return smart_template(array(ELEMENTS_TPL,'article_edit'),array_merge($vV,array('data'=>$data,'options'=>$options)));
	}

	function getText(&$keys){
		global $engine;
		$t= '';$k=null;
		if(!empty($this->el)){
			$el=&$this->el;
			$i=count($el);
			for($j=0;$i>0;$j++,$i--){
				$el[$j]->parent=&$this;
				$t.=$el[$j]->getText($keys);
				if(!empty($engine->__complete)){
					break;
				}
			}
			if(isset($this->columns)){
				//debug('yyy');
				$t.=$this->columns->getText(&$k);
			}
		}
		return $t;
	}

	/**
	 * вставить новый элемент в статью xTP
	 * Enter description here ...
	 * @param $tp
	 */
	function insertInto($tp){
		global $engine;
		$article=$this;
		$artnode = $article->node();
 		$x=itemByType($tp);
		$x=&new $x();
		//debug($this->node());
		$nodex=$x->Create(array('type'=>$tp),$artnode);
//		error_log(__FILE__.' '.__LINE__."\n".print_r($article,true).$tp,3,'z:/logs/debug.log');
		return ' ';
	}
	
	function serialize(&$formvar,$dir=false,$shortform=false){
		global $engine;
		if($shortform)
			$id='';
		else	
			$id=$this->v['id'];
			
		//debug($dir.'--'.$id.'->'.$_POST['item_text_'.$id]);
		//if($id=='1902') debug($_POST);
		if($dir && isset($_POST['item_text_'.$id]) && $_POST['item_text_'.$id]!=$this->v['item_text']){
			// find parent
			$tmp=$engine->nodeGetBackPath($engine->node($id));
			//debug($tmp);
			$engine->cache('anchor',$tmp[0]['node']);
			//debug('!!!!!!!!!!!!!!!!');
		}
		xElement::serialize_var($formvar,$dir,
			array(
				'pic_title_'=>'pic_title',
				'article_title_'=>'article_title',
				'article_keywords_'=>'article_keywords',
				'article_descr_'=>'article_descr',
				'pic_big_'=>'pic_big',
				'pic_small_'=>'pic_small',
				'item_text_'=>'item_text',
				'item_url_'=>'item_url',
				'item_width_'=>'item_width'
				)
		); 
		xElement::serialize_order_menu($formvar,$dir,'item_order_');
		if($dir){// забрать переменные
			// вставить новый элемент
			if(!empty($_POST['new_item_add']) && !empty($formvar['new_item_type']))
			{
				$x=itemByType($formvar['new_item_type']);
				$x=&new $x();
				//debug($this->v);
				$nodex=$x->Create(array('type'=>$formvar['new_item_type']),$this->node(),$this->v['id']);
				
				 
				if(!empty($_POST['insert_after'])) {
					$nodey=$engine->nodeScanId($_POST['insert_after']);
					//debug("xxx>>$nodex>>$nodey");
					//todo: поправить вставку в начало списка элементов - переместить в начало списка с конца при nodey==0
					if($nodex>=0 && $nodey>=0){
					$engine->MoveNode($nodex,$nodey); 
					}
				}
				unset($_POST['new_item_add'],$formvar['new_item_type']);
  			}
		}
		if(!$shortform && !empty($this->el))
		foreach($this->el as $v){
			$v->serialize($formvar,$dir);
		}
		if($dir){// забрать переменные
			// вставить новый элемент
			if(!empty($formvar['del'])){ // so, del button pressed!
				if(preg_match('~^(.+)_([^_]+)$~i',$formvar['del'],$m)){
					$tmp=$engine->nodeGetBackPath($engine->node($m[2]));
					//debug($tmp);
					$engine->cache('anchor',$tmp[0]['node']);
					//debug($m[2].'~~ ');
					if(xMenuLine::deleteLine($m[2],'pg'))
						unset($formvar['del'],$_POST['del']);
				}
				$formvar['del']='';
				
			}	
		}
	}
}

class xCell  extends xElement {
	function getText(&$keys){
		$id=$this->v['id'];
		return smart_template(array(ELEMENTS_TPL,'katalog'),
 			array('text'=>array(
 				'the_text'=>$keys['item_text_'.$id]
			))
 		);
	}

	function getContextMenu(){
		// для обслуживания таблицы
		return
			''.
			'<table class="tahoma menu long" ><tr><td colspan=2><div style="background:#dddddd;">столбец</div></td></tr><tr>'.
			'<a href="#itemMoveUp:'.$this->v['id'].':Cells" onclick="return false;">&laquo; влево</a></td>'.
			'<td class="align_right"><a href="#itemMoveDn:'.$this->v['id'].':Cells" onclick="return false;"> вправо &raquo;</a></td>'.
			'<tr><td><a href="#newItem:'.$this->v['id'].':Cells" onclick="return false;">добавить</a></td>'.
			'<td class="align_right"><a href="#deleteItem:'.$this->v['id'].':Cells" onclick="return false;"> удалить</a></td>'.
			'</tr></table>';
		/*<tr><td colspan=2><div style="background:#dddddd;">строка</div></td></tr><tr>'.
			'<td><a href="#itemMoveUp:'.$this->v['id'].':Rows" onclick="return false;"> вверх</a>'.
			'<td  class="align_right"><a href="#newItem:'.$this->v['id'].':Rows" onclick="return false;">Добавить</a></tr>'.
			'<tr><td><a href="#itemMoveDn:'.$this->v['id'].':Rows" onclick="return false;"> вниз</a>'.
			'<td class="align_right"><a href="#deleteItem:'.$this->v['id'].':Rows" onclick="return false;">Удалить</a>'.
			'</tr>*/

	}

	function getForm($numb=1){
		$v=strip_tags(pps($this->v['text']));
		if(preg_match('/(?:\S+\s+){30}/',$v,$m)){
			$v=$m[0].' ...';
		}
		$this->v['text_breath']=$v ;
		$this->v['trclass']=evenodd($numb);

		$x=smart_template(array(ELEMENTS_TPL,'text_edit_line'),$this->v);
		return	$x;
	}
	function serialize(&$formvar,$dir=false){
		xElement::serialize_var($formvar,$dir,
			array(
				'item_name_'=>'name'
				,'item_text_'=>'text'
				,'item_url_'=>'url'
				,'pg_'=>'text'
				)
		);
		xElement::serialize_order_menu($formvar,$dir,'item_order_');
	}
}

class xPlugin  extends xCommon {
	function xPlugin (){
		xCommon::xCommon(
		array(
			'fields'=>array(
				'item_text'=>array('title'=>'имя плагина'),
				'param'=>array('title'=>'параметры плагина'),
			) 
		));		
	}
	function getText(){
		global $engine;
		return $engine->export(pps($this->v['name'],'MAIN'),$this->v['item_text'],pps($this->v['param']));	
	}
}


/**
 * Одиночная картинка
 */
class xPic  extends xElement {
	function getData($winop='WindowO',$tpl_width=1000,$tpl_height=1000){
		global $engine;
 		$res=array();
		if(preg_match('/(\w+)\.swf$/i',$this->v['pic_small'],$m)){
			//debug($this);
			$res['swidth']=$this->v['swidth'];
 			$res['sheight']=$this->v['sheight'];
			$res['pict']=
				smart_template(array(ELEMENTS_TPL,'flash'),array(
					'swf'=>$m[1],
					'dir'=>'uploaded',
					'width'=>ppi($this->v['swidth'],150),
					'height'=>ppi($this->v['sheight'],150),
				)
			);
			//debug('SWF');
			//debug($res);
			return $res;
		};
 		$id=$this->v['id'];
 		$res['comment']=$this->v['pic_comment'];
 		$res['href']=$this->v['item_url'];
 		if(!empty($this->v['pic_small']))
 			$res['small_pict']=TO_URL($this->v['pic_small']);
 		$res['pid']=$this->parent->v['id'];	
 		if(!empty($this->v['pic_big']) && $this->v['pic_big']!=$this->v['pic_small'])
 			$res['big_pict']=TO_URL($this->v['pic_big']);
 		$res['xhref']=pps($res['href'],$res['big_pict']);
 		if(!isset($this->v['bwidth'])) {
 			$res['bwidth']=$this->v['bwidth'];
 			$res['bheight']=$this->v['bheight'];
 			$res['swidth']=$this->v['swidth'];
 			$res['sheight']=$this->v['sheight'];
 		} else {
 			$xx=str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
	 		if(!file_exists($xx.$res['big_pict']))
	 			$xx=TMP_DIR;
	 		list($width,$height)=@getimagesize($xx.$res['big_pict']);
	 		if($width>0){
	 			$res['bwidth']=$width;
	 			$res['bheight']=$height;
	 			list($width,$height)=@getimagesize($xx.$res['small_pict']);
	 			$res['swidth']=$width;
	 			$res['sheight']=$height;
	 		}
 		}
 		$xet=600;$yet=600;
 		$res['xheight']=$yet;
 		$res['xwidth']=$xet;
 		if((ppi($width)>0) && (ppi($height)>0)) {
 			$x_factor=1; //min($xet/$width,$yet/$height);
 			$res['xheight']=ceil($x_factor*$height);
 			$res['xwidth']=ceil($x_factor*$width);
 		}
 		
 		$res['pict']='';
		$tpl_width=min($tpl_width,$res['xwidth'],$engine->getPar('pictute_tpl_width',1000));//200
 		$tpl_height=min($tpl_height,$res['xheight'],$engine->getPar('pictute_tpl_height',1000));//115
 			 			 			
 		if ($tpl_width>=$res['bwidth'] &&
 			$res['small_pict']==$res['big_pict']){
 			$res['big_pict']='';
 		}	
 		
 		if ($res['small_pict'])
		$res['pict']=
            '<img class="iePNG" onload="checkImg(this)" alt="'.$res['comment']
			.'" style="width:'.$tpl_width
			.'px;height:'.$tpl_height
			.'px;" src="'.$res['small_pict'].'">' ;
		$href='';	
		if(pps($res['big_pict']) && $res['big_pict']!=$res['small_pict'] )	
			$href =	$res['big_pict'];
		if ($res['href'])
			$href =	$res['big_pict'];
		if ($href){	
		$res['pict']=
            '<a href="'.$href.'" rel="g'.$this->parent->v['id'].'">'
			.$res['pict'].'</a>';
		}
			
		return $res;
	}
	function getForm(){
		$k=array();
		$this->serialize($k,false);
		$x=smart_template(array(ELEMENTS_TPL,'piconly_edit_line'),$this->v);
		return	$x;
	}
	function serialize(&$formvar,$dir=false){
		global $engine;
		xElement::serialize_var($formvar,$dir,
			array(
				'pic_small_'=>'pic_small'
				,'pic_big_'=>'pic_big'
				,'pic_comment_'=>'pic_comment'
				,'item_url_'=>'item_url'
			)
		);
		$changed=false;
		if($dir){ // принимаем из поста
			$yy=str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
			foreach(array('s'=>'small','b'=>'big') as $k=>$v){
				if (isset($_POST['pic_'.$v.'_'.$this->v['id']])){
					$xx=TO_URL($_POST['pic_'.$v.'_'.$this->v['id']]);
					//debug($yy.$xx);
					if(file_exists($yy.$xx)){
	 					$m=@getimagesize($yy.$xx) ;
	 					if(ppi($m[1])>0){
			 				$this->v['pic_'.$v]=TO_URL($yy.$xx);
			 				$this->v[$k.'height']=ppi($m[1]);
			 				$this->v[$k.'width']=ppi($m[0]);
			 				$changed=true;
	 					}
					} else {
		 				$this->v['pic_'.$v]=$_POST['pic_'.$v.'_'.$this->v['id']];
		 				unset($this->v[$k.'height'],$this->v[$k.'width']);
		 				$changed=true;
					}
				}
			}
			
		} else {
			$yy=str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
			foreach(array('s'=>'small','b'=>'big') as $k=>$v)
			if(!isset($this->v[$k.'height'])){
				if($xx=pps($this->v['pic_'.$v])){
					$m=@getimagesize($yy.$xx) ;
			 		if(ppi($m[1])>0){
				 		$formvar[$k.'height']=$this->v[$k.'height']=ppi($m[1]);
			 			$formvar[$k.'width']=$this->v[$k.'width']=ppi($m[0]);
			 			$changed=true;
			 		}
				}
			}
		} 			
		if($changed)
			$engine->writeRecord($this->v);
		xElement::serialize_order_menu($formvar,$dir,'item_order_');
	}
}


/**
 * Универсальный класс для создания контролов
 */
class xCommon extends xElement{
	var $param;
	
	function getUlLi(){
		$x='';
		if(!empty($this->el))
			foreach( $this->el as $vv)
				if(method_exists($vv,'getUrl')){
					$x.='<li>'.$vv->getUrl().'<li>';
					$x.=$vv->getUlLi();
				} 		
		if (!empty($x))
			return '<ul>'.$x.'</ul>';
		else
			return '';			
	} 
	
	function xCommon($param){
		$this->param=$param;
	}
	
	function Create($key,$parent) {
		global $engine;
		if (isset($this->param['name']))
			$key=array_merge(array('name'=>$this->param['name']),$key);
		return $engine->nodeAdd($parent,$key);
	}
	
	function getForm($numb=1){
		global $engine;
		$par=$this->v;
		$cols=array();
		$fields=array();
		foreach($this->param['fields'] as $k=>$v){
			if(is_int($k)){
				$name=$v; $parm=array();
			} else {
				$name=$k; $parm = &$v;
			};
			$cols[]=array('width'=>pp($parm['width'],'','px','auto'));
			switch(pps($parm['type'])){
				case 'html':
					$tmp=strip_tags(pps($this->v[$name]));
					if(preg_match('/(?:\S+\s+){15}/',$tmp,$m)){
						$tmp=$m[0].' ...';
					}					
					$fields[]=array(
						'html'=>array(
							'name'=>$name
							,'id'=>$this->v['id']
							,'text_breath'=>$tmp
							,'title'=>pps($parm['title'])
						)
					);
					break;
				case 'smallinput':
					$fields[]=array(
						'smallinput'=>array(
							'name'=>$name
							,'id'=>$this->v['id']
							,'title'=>pps($parm['title'])
						)
					);
					break;
				case 'dropdown':
					if(defined('IS_ADMIN'))
					if(!isset($GLOBALS['x_menu_sema_'.pps($parm['xname'])])){
						$GLOBALS['x_menu_sema_'.pps($parm['xname'])]=true;
						
						if(!isset($engine->par['js_string']))
							$engine->par['js_string']='';
						$engine->par['js_string'].=',"'.pps($parm['xname']).'"';
						
						$engine->par['dd_menu'][]=array(
							'name'=>pps($parm['xname']),
							'list'=>$parm['list']
						);
					}
					
					$fields[]=array(
						'dropdown'=>array(
							'name'=>$name
							,'xname'=>pps($parm['xname'])
							,'title'=>pps($parm['title'])
							,'id'=>$this->v['id']
						)
					);
					break;
				case 'button0':
					$fields[]=array(
						'button0'=>array(
							'name'=>$name
							,'id'=>$this->v['id']
							,'text'=>pps($parm['button'])
							,'title'=>pps($parm['title'])
						)
					);
					break;
				case 'button':
					$fields[]=array(
						'button'=>array(
							'name'=>$name
							,'id'=>$this->v['id']
							,'text'=>pps($parm['button'])
							,'title'=>pps($parm['title'])
						)
					);
					break;
				case 'link':
					$fields[]=array(
						'link'=>array(
							'name'=>$name
							,'id'=>$this->v['id']
							,'title'=>pps($parm['title'])
						)
					);
					break;
				case 'checkbox':
					$fields[]=array(
						'checkbox'=>array(
							'name'=>$name
							,'id'=>$this->v['id']
							,'text'=>pps($parm['button'])
							,'title'=>pps($parm['title'])
						)
					);
					break;
				case 'csv':
					$fields[]=array(
						'csv'=>array(
							'name'=>$name
							,'id'=>$this->v['id']
							,'text'=>pps($parm['button'])
							,'title'=>pps($parm['title'])
						)
					);
					break;
				case 'txt':
				default:
					$fields[]=array(
						'txt'=>array(
							'item_image'=>'img/el_text.gif'
							,'name'=>$name
							,'text'=>strip_tags(pps($this->v[$name]))
							,'title'=>pps($parm['title'])
							,'id'=>$this->v['id']
						)
					);
			}
		}
		$add=array();
		if(isset($this->param['inner']))	
		foreach($this->param['inner'] as $k=>$v){
			if(is_int($k)){
				$name=$v; $parm=array();
			} else {
				$name=$k; $parm = &$v;
			};
			$cols[]=array('width'=>pp($parm['width'],'','px','auto'));
			$tmp=strip_tags(pps($this->v[$name]));
			if($name=='picture')
			$fields[]=array(
				'addfoto'=>array(
					'name'=>$name
					,'id'=>$this->v['id']
					,'text'=>pps($parm['button'])
					,'serialize'=>false
					)
			);
			else if($name=='article')
			$fields[]=array(
				'addnote'=>array(
					'name'=>$name
					,'id'=>$this->v['id']
					,'text'=>pps($parm['button'])
					,'serialize'=>false
					)
			);
			else
			$fields[]=array(
				'button'=>array(
					'name'=>$name
					,'id'=>$this->v['id']
					,'text'=>pps($parm['button'])
					,'title'=>pps($parm['title'])
					,'serialize'=>false
				)
			);
			$x='';$i=0;
			if(!empty($this->el))
			foreach( $this->el as $vv) 
			if($vv->v['type']==$v['type']){
				if($name=='article' || $name=='column'){
					$x.=smart_template(array(ELEMENTS_TPL,$name.'_line'),$vv->v);
				} else if($name=='link'){
					$x.=smart_template(array(ELEMENTS_TPL,'link_edit_line'),$vv->v);	
				} else {
					$x.=$vv->getForm($i++,true);
				}
			}
			if(!isset($add[$name])){
				$add[$name]=array();
			}
			$add[$name]=$x;
		}
		//debug($add);
		if (isset($this->param['align'])){
			$cols[]=array('width'=>'25px');
			$fields[]=array(
						'align'=>array(
							'name'=>$name
							,'id'=>$this->v['id']
						)
					);
		}
		
		if(!empty($add['article'])){
			$par['article']=array(
				'colnum'=>count($cols)+1,
				'articles'=>$add['article']
			);
		}
		if(!empty($add['column'])){
			$par['column']=array(
				'colnum'=>count($cols)+1,
				'articles'=>$add['column']
			);
		}
		if(!empty($add['picture'])){
			$par['addfoto']=array(
				'colnum'=>count($cols)+1,
				'links'=>$add['picture']
			);
		}
		if(!empty($add['link'])){
			$par['links']=array(
				'colnum'=>count($cols)+1,
				'links'=>$add['link']
			);
		}
		
		$par['trclass']=evenodd($numb);
		//print_r($fields);
		$par['fields']=$fields;
		$par['colnum']=count($this->param['fields']);
		$par['type']='тип:'.nameByType($this->v['type']);
		$par['item_name']=pps($this->v['item_name'],nameByType($this->v['type']));
		$par['minmax']=$this->v['type']==type_KATALOG || !isset($this->param['inner']);
		if(!empty($cols))
			$par['cols']=$cols;
		$x=smart_template(array(ELEMENTS_TPL,'common_line'),$par);
		return	$x;
	}
	function serialize(&$formvar,$dir=false){
		global $engine;
		$key=array();
		foreach($this->param['fields'] as $k=>$v){
			if(is_int($k)){
				$name=$v; $par=array();
			} else {
				$name=$k; $par = &$v;
			};
			if(!isset($v['serialize']))
				$key[$name.'_']=$name;
		}
		if(isset($this->param['align']))
			$key['item_align_']='item_align';
		$key['name_']='item_name';	 
		if(!empty($key))
			xElement::serialize_var($formvar,$dir,$key);
		xElement::serialize_order_menu($formvar,$dir,'item_order_');
		if(!empty($this->el))
		foreach($this->el as $v){
			$v->serialize($formvar,$dir);
		}
		if($dir){
			if(isset($this->param['inner']))
				foreach($this->param['inner'] as $k=>$v)
				if(!empty($_POST['new_'.$k.'_'.$this->v['id']]))
				{
					$engine->nodeAdd($this->node(),array('name'=>$k,'type'=>$v['type']));
					unset($_POST['new_'.$k.'_'.$this->v['id']]);
				}
		}
	}
	
}

class xCols extends xCommon {
	function getText(&$keys){
		$data=array();
		if(!empty($this->el)){
			foreach($this->el as $k=>$v){
				$data[]=$this->el[$k]->getText($keys);
			}
		}
		return '<table class="long columns"><tr><td>'.implode('</td><td>',$data).'</td></tr></table>';
	}
	
	function xCols(){
		xCommon::xCommon(
		array(
			'fields'=>array(
			//	'item_name'=>array('title'=>'текст для списка') // 50,70,100
			//	,'anchors'=>array('title'=>'список якорей через /') // 50,70,100
			) 
			,'inner'=>array(
				'column'=>array('type'=>type_ARTICLE,'button'=>'Доб.колонку')
			)
		) 
		);
	}	
	
}


//<% insert_point('classes_body'); %>

?>