<?php

define ('type_MENU',0);
define ('type_CELL',1);
define ('type_LINKS',3);
//define ('type_HEADER',4);
define ('type_TABLE',6);
define ('type_GALLERY',7);
define ('type_KATALOG',8);
define ('type_ARTICLE',10);
define ('type_PIC',11);
define ('type_MAINMENU',12);
define ('type_LINK',13);
define ('type_ROW',14);
define ('type_HIDDENMENU',15);
define ('type_NEWTEXTPIC',16);
define ('type_PLUGIN',17);
define ('type_ARTICLELIST',18);
define ('type_COLUMNS',19);
define ('type_ANCHOR',20);
define ('type_EMPTYCELL',21);
//новые типы
define ('type_GALLERY2',26);
define ('type_LINE',27);
define ('type_KATALOG1',28);
define ('type_KOMMENT',29);
define ('type_ROW_COM',30);


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
		,type_CELL=>'xCell'
		,type_EMPTYCELL=>'xEMPTYCell'
//		,type_HEADER=>'xHeader'
		,type_TABLE=>'xTable'
		,type_KATALOG1=>'xKatalog1'
		,type_GALLERY=>'yGallery'
		,type_KATALOG=>'xKatalogue'
		,type_ARTICLE=>'xArticle'
		,type_LINK=>'xLink'
		,type_LINKS=>'xLinks'
		,type_PIC=>'xPic'
		,type_ROW=>'xRow'
		,type_HIDDENMENU=>'xHiddenMenu'
		,type_PLUGIN=>"xPlugin"
		,type_ARTICLELIST=>"xAList"
		,type_COLUMNS=>"x_COLUMN"
		,1000=>'x_COLUMN'
		,type_ANCHOR=>"xANCHOR"
		,type_GALLERY2=>"x_GALLERY"
		,1001=>"yGallery"
		,type_LINE=>"x_LINE"
		,1005=>"x_LINE"
//		,type_KOMMENT=>'xKomment'
		,type_ROW_COM=>'xRow_com'
/**/,29=>'xKomment'

/**/		);
	if(!$value){
		if(isset($types[$type]))
			return 	$types[$type];
		else
			return 'xElement';		
	} else
		$types[$type]=$value;	
}

function &ClassByType($type){
    $class=itemByType($type);
    return new $class();
}

function nameByType($type,$value=false){
	static $types=array(
		 type_LINKS=>'Cсылки'
		//,type_HEADER=>'Заголовок'
		,type_TABLE=>'Таблица'
		,type_KATALOG1=>'Каталог'
		,type_CELL=>'Ячейка'
		,type_GALLERY=>'Галерея'
		,type_GALLERY2=>'Галерея-2'
		,1001=>'Галерея'
		,type_ANCHOR=>'Заголовок'
		,type_KATALOG=>'Каталог'
		,type_PLUGIN=>"Модуль"
		,1000=>'Колонка'
		,type_COLUMNS=>'Колонка'
		,type_ARTICLELIST=>"Список статей"
		,1005=>'Гориз. полоса'
		,type_LINE=>'Линия'
	//	,type_KOMMENT=>'Комментарий'
/**/,29=>'Комментарий'

/**/	);
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
		return "unknown type<br>".pps($this->v['type']);
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
			case type_LINK:
				$text='Ссылка';
				break;
			case type_ROW:
				$text='Строка';
				break;
			case type_CELL:
				$text='Ячейка';
				break;
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
	function getrmenuLink(){;}
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

/**
 * Класс - якорь, отображающийся на страничке Ека
 *
 */

class xANCHOR extends xCommon {

	function serialize(&$formvar,$dir=false){
		global $engine;
		$id=$this->v['id'];
		/*    debug($this->v);
			debug($dir);
			debug(isset($_POST['item_title_'.$id]));
			debug($_POST['item_title_'.$id]!=$this->v['item_title']);*/
		if($dir && isset($_POST['item_text_'.$id]) && $_POST['item_text_'.$id]!=$this->v['item_text']){
			// find parent
			$tmp=$engine->nodeGetBackPath($engine->node($id));
			//debug($tmp);
			$engine->cache('anchor',$tmp[0]['node']);
			//debug('!!!!!!!!!!!!!!!!');
		}
		xCommon::serialize($formvar,$dir);
	}

	function xANCHOR(){
		xCommon::xCommon(
		array(
			'name'=>'anchor'
			,'fields'=>array(
				//'item_name'=>array('title'=>'50/70/100') // 50,70,100
		//		'item_title'=>array('title'=>'Название якоря для меню') // верхний заголовок
				'item_text'=>array('title'=>'Текст заголовка') // верхний заголовок
			//,'item_sub'=>array('title'=>'заголовок2')
			)
			,'align'=>true 
		) 
		);
	}	
	
	function getText(&$keys){
		//global $engine;
		//$engine->anchor_cnt++;
		if (!empty($this->v['item_text']))
			$header=array(
				'align'=>$this->aligns($this->v['item_align']),
				'header'=>$this->v['item_text']
			);
		else
			$header='';	
		//debug($header);	
		return smart_template(array(ELEMENTS_TPL,'anchor'),array(
			'xitem_name'=>$this->v['id'],
			'header'=>$header));
	}
	
}

class x_GALLERY extends xCommon {
	
	function x_GALLERY(){
		xCommon::xCommon(
		array(
			'fields'=>array(
				'item_columns'=>array('width'=>180,'type'=>'smallinput','title'=>'Количество столбцов:')
				,'item_width'=>array('title'=>'Ширина предпросмотра в пикселях')
				,'item_height'=>array('title'=>'Высота предпросмотра в пикселях')
				,'short_width'=>array('title'=>'Ширина картинок в пикселях')
				,'short_height'=>array('title'=>'Высота картинок в пикселях')
/*				,'xtype'=>array('width'=>170,'type'=>'dropdown'
						,'list'=>array(
								array('id'=>1,'text'=>'обычная галерея')
								,array('id'=>2,'text'=>'галерея с предпросмотором')
						),'xname'=>'xgallery','title'=>'тип галереи') // 50,70,100		//	,'anchors'=>array('title'=>'список якорей через /') // 50,70,100
*/
			) 
			,'inner'=>array(
				'picture'=>array('width'=>125,'type'=>type_PIC,'button'=>'Доб.Фото')
//				,'article'=>array('width'=>125,'type'=>type_ARTICLE,'button'=>'Доб.Описание')
			)
			,'align'=>true
		));
	}

	function getText(&$keys){
		global $engine;
		$key = array();
		$this->serialize($key);
		$row=array();
		$bh2=0;
		$w_width=0;
		$w_height=0;
		foreach($this->el as $v){
 			//$row[]=$res;
 			if($v->v['type']==type_PIC){
				$bh1=$v->getData('Throw_Up',ppi($this->v['short_width'],1000),ppi($this->v['short_height'],1000));
				$bh1['swidth_b']=$bh1['swidth']+18;
				$bh1['swidth_b']='width:'.$bh1['swidth_b'].'px;';
				$row[]=$bh1;
				if ($bh1['sheight']>$bh2) $bh2=$bh1['sheight'];
				if ($bh1['bwidth'] > $w_width) $w_width = $bh1['bwidth'];
 				if ($bh1['bheight'] > $w_height) $w_height = $bh1['bheight'];
 			}
 			if(count($row)>=ppi($this->v['item_columns'],1)){
				$x=array('pict'=>$row);$x['pictcom']=$row ;$x['row1']=&$x;$x['row2']=&$x;

 				$rows[]=$x ;
 				$row=array();
 			}

		}

		if(!empty($row)){
			$x=array('pict'=>$row) ;$x['pictcom']=$row ;$x['row1']=&$x;$x['row2']=&$x;
 			$rows[]=$x ;
		}

 		$xrow=$rows	;
 		$xrow['row1']=&$rows;	
 		$xrow['row2']=&$rows;	
 		$par=array('row'=>$xrow);
 		foreach(array('width','height') as $v){
	 		if (isset($this->v['item_'.$v]) && !empty($this->v['item_'.$v]))
	 			$par[$v]=$v.':'.pp($this->v['item_'.$v],'','px','200px').';';
	 		$par[$v.'x']=ppi($this->v['item_'.$v],'300');

 		}
		$bh2=$bh2+105;
		$par['rp_sheight']='height:'.$bh2.'px;';
		$par['w_width']=$w_width;
		$par['w_height']=$w_height;
		$par['pid']=$bh1['pid'];
		$align=$this->aligns(ppi($this->v['item_align']));
		if ($align!=='center')
		$par['float_p']='float:'.$align.';';
 		return smart_template(array(ELEMENTS_TPL,'katalog'),
 			array('align'=>'no','galleryX'=>$par)
 		);
	}
}

class x_LINE extends xCommon {
	function x_LINE(){
		xCommon::xCommon(
			array(
				'fields'=>array(
					'height'=>array('title'=>'толщина (20px, 5pt , 30%)'), // 50,70,100
					'width'=>array('title'=>'длина (20px, 5pt , 30%)'), // 50,70,100
					'color'=>array('title'=>'цвет #FFFFFF, rgb(255,255,255), white') // 50,70,100
				)
			,'align'=>true
			)
		);
	}		
	
	function getText(&$keys){
		$style=array();
		foreach(array('background-color'=>'color','height','width', 'float'=>'item_align') as $k=>$a){
			if(!empty($this->v[$a]))
				$style[]=sprintf('%s:%s;',is_int($k)?$a:$k,($a == 'item_align'?$this->aligns($this->v[$a]):$this->v[$a]));
		}
		$style=implode('',$style);
		$out = '<div class="xline"'.pp($style,' style="','"').'></div><div style="clear:both;"></div>';
		if ($this->aligns($this->v['item_align']) == "center")
			$out = "<center>".$out."</center>";
		return $out;
	}
}

class x_COLUMN extends xCommon {

	function x_COLUMN(){
		global $engine;
		xCommon::xCommon(
			array(
				'fields'=>array(
					'width'=>array('title'=>'ширина') // 50,70,100
					,'xtype'=>array('width'=>170,'type'=>'dropdown'
						,'list'=>array(
								array('id'=>1,'text'=>'новая колонка')
								,array('id'=>2,'text'=>'закончить колонки')
						),'xname'=>'xcolumns','title'=>'начать-продолжить/закончить') // 50,70,100
				) 
			) 
		);
	}	
	
	function getText(&$keys){
		//debug($keys);debug('xxx');
		//	debug($this->v);
		$width=	pps($this->v['width']);
		//debug($this->parent->columns);
		if(ctype_digit($width)) $width.='px';
		if(isset($this->parent->columns)){
			if( ppi($this->v['xtype'])=='2' || $width<0) { 
				
				// конец таблицы 
				unset($this->parent->columns);
				return '</td></tr></table>';
				
			} else { return '</td><td'.pp($width,' style="width:','"').'>';	}
			
			
		} else {	
			// начало таблицы
			$this->parent->columns=&$this;
			return '<table class="columns"><tr><td'.pp($width,' style="width:','"').'>';	
			// продолжение
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
			$name=trim(pps($this->v['descr'],pps($this->v['name'])));	
			$data.='<li'.$class.'><a'.$class
					.' href="'.$this->getUrl().'">'
					.ppx($name,'---').'</a>';
				
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
						).' '.pps($v['item_name'],'---')
						.'</a></li>'	;
					}
				} else {
					$name=trim(pps($v['item_title']).' '.pps($v['item_name']));
					if(!empty($name))
						$x.='<li class="green'.(ppi($engine->page_id)==$v['goto']?' current':'').'"><a '.(ppi($engine->page_id)==$v['goto']?'class="current" ':'')
						.'href="?do=page&id='.$v['goto'].'">'.
						pps($v['item_text'],$name)
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
	
		function getDDLink($level=1000,$skip=0)

{
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
			$data.='<li><a href="'.$this->getUrl().'">'.pps($this->v['descr'],pps($this->v['name'],'root')).'</a>';
			$data.='</li>';
			}	
			
		return $data;
}	
	
	
	
		function getrmenuLink($level=1000,$curonly=false,$skip=0,$catalogue='catalogue',$pclass=''){
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
			
			if(!empty($class)) {
				$class=' class="'.implode(' ',$class).'"';
				$data.='<td'.$class.'>'
					.pps($this->v['descr'],pps($this->v['name'],'root')).':';
				}
			else
				$class="";
				
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
				$x.=trim($this->el[$kk]->getrmenuLink($level-1,$curonly,$skip-1,$catalogue,
				($kk==count($this->el)-1?'last':'')));
			}
			if($skip<=0 && !empty($x))
				$data.='<ul>'.$x.'</ul>';
			else
				$data.=$x;
		}
		if($skip<=0)
			$data.='</td>';
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
				if(preg_match('~^(..)_(.+)$~i',$del,$m)){
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
		if($pg=='pg'){
			$item=$engine->readRecord(array('id'=>$id));
			if(ppi($item['type'])==type_KATALOG){
				$engine->export('katalog','Category','del',$item['id']);
			}
			$engine->nodeDelete($engine->node($item));
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
			return $engine->curl('do','id','topic').$this->v['nolink'];
		$x=trim(pps($this->v['url']));	
		if (preg_match('~https?:|ftp:|/~',$x))
			return $x;
		else if ($x && $this->v['level']<3)
			return $engine->curl('do','id','step').'do=menu&id='.$x;
		else
			return $engine->curl('do','id','step').'do=menu&id='.$this->v['id'];
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
		return $engine->curl('do','id','topic').'do=menu&id='.$this->v['id'];
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
				//echo $this->node();
				//die();
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
		$hitem=array('sname'=>'article_title_','surl'=>'article_keywords_','stext'=>'article_descr_', 'dostup'=>'dostup_');
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
/*		if(isset($this->v['url']))
			$vV['pic_url']=TO_URL($this->v['url']);
		else	
			$vV['pic_url']='';*/
		if(defined('ARTICLE_WIDTH_IMAGE'))
			$vV['image']=$vV;	
		if(!empty($this->el))
		foreach($this->el as $v){
			$data.=$v->getForm($i++);
		}
		
		$options='';
		if(!isset($GLOBALS['opt_array'])){
			if (defined('SITE_CREATE_SCENARIO') && SITE_CREATE_SCENARIO<5)
				$opt_array=array(type_NEWTEXTPIC,type_LINKS,type_TABLE,type_GALLERY,type_HEADER);
			elseif (defined('SITE_CREATE_SCENARIO') && SITE_CREATE_SCENARIO<6)
				$opt_array=array(type_NEWTEXTPIC,type_LINKS,type_TABLE,type_GALLERY,type_HEADER,type_ARTICLELIST,type_COLUMNS,type_ANCHOR);
			elseif (defined('SITE_CREATE_SCENARIO') && SITE_CREATE_SCENARIO<10)
				$opt_array=array(type_NEWTEXTPIC,type_LINKS,type_TABLE,type_GALLERY,type_HEADER,type_PLUGIN);
			elseif (defined('SITE_CREATE_SCENARIO') && SITE_CREATE_SCENARIO==11)
				$opt_array=array(type_NEWTEXTPIC,type_LINKS,type_TABLE,type_GALLERY,type_KATALOG,type_PLUGIN);
			else
				$opt_array=array(type_NEWTEXTPIC,type_LINKS,type_TABLE,type_GALLERY,type_KATALOG);
		} else {
			$opt_array=$GLOBALS['opt_array'];
		}
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
				'item_width_'=>'item_width',
				'dostup_'=>'dostup'
				)
		); 
		xElement::serialize_order_menu($formvar,$dir,'item_order_');
		if($dir){// забрать переменные
			// вставить новый элемент
			if(!empty($_POST['new_item_add']) && !empty($formvar['new_item_type']))
			{
				$x=itemByType($formvar['new_item_type']);
				$x=&new $x();
				//debug($this->node());
				$x->Create(array('type'=>$formvar['new_item_type']),$this->node());
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
				if(preg_match('~^(..)_(.+)$~i',$formvar['del'],$m)){
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
		global $engine;
		$items=$engine->nodeGetBackPath($engine->node($this->v['id']));
		foreach($items as $k=>$v){
			if($v['type']==type_ROW)
				$pid = $v['id'];
		}
		
		// для обслуживания таблицы
		return
		/*	''.
			'<table class="tahoma menu long" ><tr><td colspan=2><div style="background:#dddddd;">столбец</div></td></tr><tr>'.
			'<a href="#itemMoveUp:'.$this->v['id'].':Cells" onclick="return false;">&laquo; влево</a></td>'.
			'<td class="align_right"><a href="#itemMoveDn:'.$this->v['id'].':Cells" onclick="return false;"> вправо &raquo;</a></td>'.
			'<tr><td><a href="#newItem:'.$this->v['id'].':Cells" onclick="return false;">добавить</a></td>'.
			'<td class="align_right"><a href="#deleteItem:'.$this->v['id'].':Cells" onclick="return false;"> удалить</a></td>'.
			'</tr></table>';*/
		/*<tr><td colspan=2><div style="background:#dddddd;">строка</div></td></tr><tr>'.
			'<td><a href="#itemMoveUp:'.$this->v['id'].':Rows" onclick="return false;"> вверх</a>'.
			'<td  class="align_right"><a href="#newItem:'.$this->v['id'].':Rows" onclick="return false;">Добавить</a></tr>'.
			'<tr><td><a href="#itemMoveDn:'.$this->v['id'].':Rows" onclick="return false;"> вниз</a>'.
			'<td class="align_right"><a href="#deleteItem:'.$this->v['id'].':Rows" onclick="return false;">Удалить</a>'.
			'</tr>*/
			''.
			'<table class="tahoma menu long" ><tr>'.
			'<td><a href="#newItem:'.$pid.':Cells" onclick="return false;">добавить Столбец</a></td></tr>'.
			'<tr><td><a href="#newItem:'.$pid.':Rows" onclick="return false;">добавить Строку</a></td>'.
			'</tr></table>';

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
		//Исправление ссылок на изображения на относительные
		if(!empty($_POST['pic_small_'.$this->v['id']]))
			$_POST['pic_small_'.$this->v['id']] = toUrl_sf($_POST['pic_small_'.$this->v['id']]);
		if(!empty($_POST['pic_big_'.$this->v['id']]))
			$_POST['pic_big_'.$this->v['id']] = toUrl_sf($_POST['pic_big_'.$this->v['id']]);
		//print_r($this->v['id']);
		xElement::serialize_var($formvar,$dir,
			array(
				'item_name_'=>'name'
				,'item_text_'=>'text'
				,'item_url_'=>'url'
				,'pg_'=>'text'
				,'pic_small_'=>'pic_small'
				,'pic_big_'=>'pic_big'
				)
		);
		xElement::serialize_order_menu($formvar,$dir,'item_order_');
	}
}

class xEMPTYCell extends xCell {
	
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
        debug($this->v);
		return $engine->export(pps($this->v['item_name'],'MAIN'),$this->v['item_text'],pps($this->v['param']));
	}
}

class xKatalogue  extends xCommon {
	
	function addElement(&$key) {
		global $engine;
		if(!empty($this->el)) {
			$x=$this->el[0];
			$this->clearCache();
			$engine->nodeDelete($x->node());
		}
		xCommon::addElement($key);		
	}
	
	function xKatalogue (){
		xCommon::xCommon(
		array(
			'fields'=>array(
				'cat_type'=>array('title'=>'Код раздела для CSV'),
				'edit'=>array('width'=>125,'type'=>'button0','title'=>'Редактировать раздел каталога','button'=>'Редактир.'),
				'item_csv'=>array('width'=>125,'type'=>'csv','serialize'=>false,'button'=>'Экспорт CSV')
			) 
			,'inner'=>array(
				'picture'=>array('width'=>125,'type'=>type_PIC,'button'=>'Общее.Фото')
			)
			
		));		
	}

	function serialize(&$formvar,$dir=false){
		global $engine;
		
		if($dir){
			if(isset($_POST['goto_edit_'.$this->v['id']])){
				$engine->go('?do=cat&id='.$this->v['id'].'&cat='.$this->getCat());
				// document.location='?id=30&do=cat&cat=25'
			}
			
			if(isset($_POST['item_clear_'.$this->v['id']])){
				$engine->export('katalog'
					,'Category','del'
					,'~'.$this->v['id']);
				//debug('Here-'.$this->v['id']);
			}
			// изменение cat_type
			$article=$_POST['cat_type_'.$this->v['id']];
			if(!empty($article) && $this->v['cat_type']!=$article){
				$res=$engine->readRecords(array('type'=>type_KATALOG,'cat_type'=>$article),2);
				if((count($res)==1)&&(!isset($res[0]['id']) || $res[0]['id']==$this->v['id']))
					array_shift($res);
				if(!empty($res)){
					$s='';
					foreach($res as $v){
						$name=$engine->export('MAIN','url_by_item',$v['id']);
						$s.='<a href="'.$name['url'].'">'.$name['name'].'</a>&nbsp;';
					}
					$engine->error(sprintf('Код "%s" уже есть в базе!(%s)',$article,$s));
					unset($formvar['cat_type_'.$this->v['id']]);
					unset($_POST['cat_type_'.$this->v['id']]);
				}/* else {
					$engine->database->select(
						'update ?_katalog set `article`=? where `xarticle`=?d'
						,$article,$this->v['id']
					);
				}	*/
			}
		} 
		xCommon::serialize($formvar,$dir);
		if($dir){
			// pic big + pic small
			if(!empty($_POST['item_csv_'.$this->v['id']])){
				debug('making csv import');
				$engine->export('katalog'
					,'csv_import',TMP_DIR.$_POST['item_csv_'.$this->v['id']]
					,$this->v['id']);
				//debug('Here-'.$this->v['id']);
			}
		}
//		xElement::serialize_order_menu($formvar,$dir,'item_order_');
	}
	
	function getCat(){
		if(isset($this->v['item_name']) &&
			in_array(pps($this->v['item_name']),array('spec','news','action1','action2')
		))
			return $this->v['item_name'];
		else		
			return $this->v['id'];
	}
	
	function getText(&$keys,$ids=0,$button_val='') {
		global $engine;
		if(empty($ids)){
			$ids=$this->getCat();
		}
		if(!is_array($ids)) $ids=array($ids=>'');
		
		$engine->handle('katalog');
		$table=array();
		$this->havedata=false;
		foreach($ids as $id=>$kid){
			$pages=array();
			$data=array();
			$headers=array();
			$perpage=0;
			$par=array('pages'=>&$pages,
				'headers'=>&$headers,
				'perpage'=>&$perpage,
				'data'=>&$data);
			$engine->export('katalog','get_category',$id,$par,$kid);
			$x=0;
			if(!empty($data)){
                $basket=basket::getStore()->get();
                foreach($data as $k=>$v){
                    $data[$k]['even']=(($x=1-$x)==0);
                    $data[$k]['sdescr2']=strip_tags(pps($v['descr2'],$v['descr']));
//XXX: корзинко
                    if (!empty($basket[ppi($v['xid'])])){
                        $ii=ppi($v['xid']);
                        $data[$k]['value']=$basket[$ii]['n'];
                        $data[$k]['p_name']=pps($basket[$ii]['p_name']);
                    }

                }
                $this->havedata=true;
			}
			
			$theaders='';$i=1;
			if(!empty($data))
			foreach($headers as $v){
				$theaders.=	smart_template(
					array (
						ELEMENTS_TPL,
						'table_parts_'.pps($v['htpl'],'th_nopage').(isset($v['red'])?'_red':'')
					)
					,array('name'=>$v['name']));
			}
			//debug($headers);
			if(!empty($data))
			foreach($data as $k=>$vv){
				$d='';
				foreach($headers as $v){
					$keys=array();
					if(!empty($v['var']))
					    if(!empty($vv['mod']))
					    	$v['tpl'].=$vv['mod'];
						foreach($v['var'] as $kk=>$vvv){
							if(isset($vv[$vvv])){
								if(is_int($kk))
									$keys[$vvv]=$vv[$vvv];
								else	
									$keys[$kk]=$vv[$vvv];
							}
						}
						//debug($v);
						//echo "<br><br><br>";
						//print_r($data);
						//echo $v['tpl'];
                        $keys['value'] = $keys['cnt'];
						if ($v['name']=='Название'){
                          //  debug($vv);
                            $keys['text']=pps($vv['p_name']).' '.$keys['text'];
                        }
                        $keys['id'] = $keys['xid'];
                        $d.=smart_template(array(ELEMENTS_TPL,'table_parts_'.$v['tpl']),$keys);

				}
				$data[$k]['data']=$d;
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
//		debug($key);	
		if(!empty($button_val))
			$key['button_val']=$button_val;
        //debug($key);
		return $engine->_tpl('tpl_jelements','_catalogue',$key);
        //return smart_template(array(ELEMENTS_TPL,'catalogue'),$key);
	}

}

/*
class xHeader  extends xElement {
	function getText(&$keys){
		global $engine; 
		return smart_template(array(ELEMENTS_TPL,pps($engine->tpl_elements,'katalog')),
		   array('header'=>$this->v)
 		);
	}

	function getForm(){
		$par=$this->v;
		$par['trclass']=evenodd($numb);
		$x=smart_template(array(ELEMENTS_TPL,'header_edit_line'),$par);
		return	$x;
	}
	function serialize(&$formvar,$dir=false){
		xElement::serialize_var($formvar,$dir,
			array(
				'item_name_'=>'name'
				,'item_text_'=>'text'
				,'item_align_'=>'align'
			)
		);
		xElement::serialize_order_menu($formvar,$dir,'item_order_');
	}
}
*/
class xLink extends xElement {
	function getForm(){
		$x=smart_template(array(ELEMENTS_TPL,'link_edit_line'),$this->v);
		return	$x;
	}
	function serialize(&$formvar,$dir=false){
		xElement::serialize_var($formvar,$dir,
			array(
				'item_name_'=>'name'
				,'item_text_'=>'text'
				,'item_url_'=>'url'
			)
		);
		xElement::serialize_order_menu($formvar,$dir,'item_order_');
	}
}

/**
 * Одиночная картинка
 */
class xPic  extends xElement {
	function getData($winop='WindowO',$tpl_width=1000,$tpl_height=1000){
		global $engine;
 		$res=array();
		$number1=rand(9999, 1000);
		$res['gid']=$number1;
 		$id=$this->v['id'];
 		$res['comment']=$this->v['pic_comment'];
 		$res['href']=$this->v['item_url'];
 		if(!empty($this->v['pic_small']))
 			$res['small_pict']=ereg_replace("^/+", "", toUrl_sf(TO_URL($this->v['pic_small'])));
 		$res['pid']=$this->parent->v['id'];	
 		if(!empty($this->v['pic_big']))
 			$res['big_pict']=ereg_replace("^/+", "", toUrl_sf(TO_URL($this->v['pic_big'])));
 		$res['xhref']=pps($res['href'],$res['big_pict']);
 		//if(ereg("\.flv$", $res['xhref']))
 		//	$res['xhref'] = "javascript:flv_player_start('".$res['xhref']."');";
 		if(ereg("\.flv$", $res['xhref']))
 			$this->v['bwidth'] = 400;
 			$this->v['bheight'] = 300;	
 		if(!isset($this->v['bwidth'])) {
 			$res['bwidth']=$this->v['bwidth'];
 			$res['bheight']=$this->v['bheight'];
 			$res['swidth']=$this->v['swidth'];
 			$res['sheight']=$this->v['sheight'];
 		} else {
 			$xx=str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
 			$xx=ereg_replace('/+$', '', $xx);
	 		if(!file_exists($xx."/".BASE_FOLDER."/".$res['big_pict']))
	 			$xx=TMP_DIR;
	 		list($width,$height)=@getimagesize($xx."/".BASE_FOLDER."/".$res['big_pict']);
	 		if($width>0){
	 			$res['bwidth']=$width;
	 			$res['bheight']=$height;
	 			list($width,$height)=@getimagesize($xx."/".BASE_FOLDER."/".$res['small_pict']);
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
 		
		$res['pict']=
            '<img src="'.$res['small_pict'].'" alt="'.$res['comment'].'" class="text_picture">' ;
		$href='';	
		if(pps($res['big_pict']) && $res['big_pict']!=$res['small_pict'] )	
			$href =	$res['big_pict'];
		if ($res['href'])
			$href =	$res['big_pict'];
		if ($href){	
		$res['pict']=
            '<table class="picture_box"><tr><td class="gallery"><a rel="g'.$number1.'" href="'.$href.'">'
			.$res['pict'].'</a></td></tr></table>';
		
		$res['gid']=$number1;
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
					$xx=toURL_sf(TO_URL($_POST['pic_'.$v.'_'.$this->v['id']]));
					//debug($yy.$xx);
					$yyxx = ereg_replace('/+$', '', $yy)."/".BASE_FOLDER."/".ereg_replace('^/+', '', $xx);
					if(file_exists($yyxx)){
	 					$m=@getimagesize($yyxx) ;
	 					if(ppi($m[1])>0){
			 				$this->v['pic_'.$v]=toURL_sf(TO_URL($yyxx));
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
					$yyxx = ereg_replace('/+$', '', $yy)."/".BASE_FOLDER."/".ereg_replace('^/+', '', $xx);
					$m=@getimagesize($yyxx) ;
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

class xTable extends xElement {

	function newCellRow($id,$what){
		global $engine;
		// id = cell
		
		$nd = $engine->nodeGet($engine->node($id));
	
		$items=$engine->nodeGetBackPath($engine->node($id));
		$cell=array_pop($items);
		$row=array_pop($items);
		$table1=array_pop($items);
		$table=$engine->nodeGet($engine->node($table1));
		// считаем количество ячеек
		$row_start=-1;
		foreach ($table as $i=>$v){
			if ($v['level']==$row['level']){
				$row_start=$i;
			} else if ($v['id']==$cell['id']) {
				$cell_disp=$i-$row_start;
			} else if (($row_start>=0) && ($v['level']==$row['level'])) {
				$cell_cnt=$i-$row_start;
			}
		}
		
		$cell_cnt = 0;
		foreach($nd as $i=>$v) {
			if($v['type'] == type_CELL)
				$cell_cnt++;
		}
		
		if(empty($cell_cnt))$cell_cnt=$i-$row_start;

		if($what=='Rows'){
			$pid = $engine->node($row);
			$row=$engine->nodeAdd($engine->node($row),array('type'=>type_ROW,'name'=>'Row'));
			for($i=0;$i<$cell_cnt;$i++){
				$engine->nodeAdd($row,array('text'=>'','type'=>type_CELL));
			}
		} else {
			$row_m = $engine->nodeGet($engine->node($row));
			foreach ($row_m as $i=>$v){
				if ($v['level']==$row['level']){
					$row_start=$i;
				} else if (($v['level']==$cell['level'])) {
					$engine->nodeAdd($engine->node($v),array('type'=>type_CELL,'text'=>''));
				}
			}
		}
	}

	function delCellRow($id,$what){
		global $engine;
		// id = cell
		$items=$engine->nodeGetBackPath($engine->node($id));
		if(!is_array($items)) return ;
		$cell=array_pop($items);
		$row=array_pop($items);
		$table=array_pop($items);
		$table=$engine->nodeGet($engine->node($table));
		// считаем количество ячеек
		$row_start=-1;
		foreach ($table as $i=>$v){
			if ($v['level']==$row['level']){
				$row_start=$i;
			} else if ($v['id']==$cell['id']) {
				$cell_disp=$i-$row_start;
			} else if (($row_start>=0) && ($v['level']==$row['level'])) {
				$cell_cnt=$i-$row_start;
			}
		}
		if(empty($cell_cnt))$cell_cnt=$i-$row_start;
		//printf('cells %s; disp %s',$cell_cnt,$cell_disp);
		if($what=='Rows'){
			$engine->nodeDelete($engine->node($row));
		} else {
			foreach ($table as $i=>$v){
				if ($v['level']==$row['level']){
					$row_start=$i;
				} else if (($row_start>=0) && ($v['level']==$cell['level'])) {
					if(($i-$row_start)==$cell_cnt){
						$engine->nodeDelete($engine->node($v));
					}
				}
			}
		}
	//die;
	}

	function moveItem($id,$what,$disp){
		// id = cell
		global $engine;
		$items=$engine->nodeGetBackPath($engine->node($id));
		$cell=array_pop($items);
		if($what=='Rows'){
			$row=array_pop($items);
			if ($disp=="-1")
				$engine->nodeMoveUp($engine->node($row));
			else
				$engine->nodeMoveDn($engine->node($row));
		} else {
			$row=array_pop($items);
			$table=array_pop($items);
			$table=$engine->nodeGet($engine->node($table));
			// считаем какой мы столбец
			$cell_disp=-1;
			foreach ($table as $i=>$v){
				if ($v['id']==$row['id'])
					$row_start=$i;
				else if	($v['id']==$cell['id']){
					$cell_disp=$i-$row_start;
					break;
				}
			}
			foreach ($table as $i=>$v){
				if ($v['level']==$row['level'])
					$row_start=$i;
				else if	($v['level']==$cell['level'] && (($i-$row_start)==$cell_disp)){
					if ($disp=="-1")
						$engine->nodeMoveUp($engine->node($v));
					else
						$engine->nodeMoveDn($engine->node($v));
				}
			}
		}
	}

	function pasteData(&$data){
		global $engine;
		if(!count($data)) return;
		if ($data[0]['type']==type_ROW){
			$i=0;
			ajax::insertPage($this->v['id'],$data[0]['level'],$data,$i);
		} else
			parent::pasteData($data);
	}

	function newData($data){
		if ($data=='Cells'){
			for($i=0;$i<count($this->el);$i++){
				$engine->nodeAdd($engine->node($this->el[$i]->v),array('text'=>'','type'=>type_CELL));
			}
		} else
			parent::newData($data);
	}

	function getText(&$keys){
		//print_r($this);
		$aligns=array('left','center','right');

	// row - header
		$inputs='';
		$cnt=count($this->el[0]->el);
		$cols='';
		$isfixed=false;
		$width=array();
		if(!empty($this->el[0]->el)){
		foreach($this->el[0]->el as $k=>$v){
			// Блокировка перемещения столбцов в каталоге
			if($this->v['type'] == type_KATALOG1 && ($k == $this->img_col || $k == $this->osn_col || $k == $this->op_col || $k-1 == $this->img_col || $k-1 == $this->osn_col || $k-1 == $this->op_col)) {
				$dis1 = " disabled";
				$cl1 = "p165";
			}
			else {
				$dis1 = "";
				$cl1 = "p135";
			}
			if($this->v['type'] == type_KATALOG1 && ($k == $this->img_col || $k == $this->osn_col || $k == $this->op_col || $k+1 == $this->img_col || $k+1 == $this->osn_col || $k+1 == $this->op_col)) {
				$dis2 = " disabled";
				$cl2 = "p180";
			}
			else {
				$dis2 = "";
				$cl2 = "p150";
			}
			$inputs.='<th class="nopage" title="ширина столбца"><table class="table_hb"><tr><th><input type="button"'.$dis1.' class="win_max '.$cl1.'" onclick="order_h(\'-\','.$v->v['id'].');"></th><th><input onClick="z_width(this)" onkeydown="need_Save()" name="item_url_'.$v->v['id'].'" type="text" class="long"></th><th><input type="button"'.$dis2.' class="win_max '.$cl2.'" onclick="order_h(\'+\','.$v->v['id'].');"></th></tr></table></th>';
			$cols.='<col'.pp($v->v['url'],' width="','"',' width="auto"').'>';
			//$cols='';
			if(!empty($v->v['url']))
				$isfixed=true;
			else
				$v->v['url'] = 'Ширина (px)';	
			$width[$k]=$v->v['url'];	
		}
		//debug($this->el);		
		$header=$this->el[0]->getText($keys,'th');//,'',$width);
		}
		$rows=array();$colnumber=1;
		for($i=1;$i<count($this->el);$i++){
			$colnumber=count($this->el[$i]->el);
			$row = $this->el[$i]->getText($keys,'td');
			if($this->v['type'] == type_KATALOG1 && !ereg('.+admin/index.php', $_SERVER['SCRIPT_FILENAME']))
				$opisanie = array('colnumber'=>$colnumber, 'opisanie_text'=>$this->v['opisanie'], 'id'=>$this->el[$i]->v['id']);
			else
				$opisanie = '';
			$rows[]=array(
				 'id'=>$this->el[$i]->v['id']
				,'class'=>$i%2?'odd':'even'
				,'number'=>$i
				,'colnumber'=>$colnumber
				,'xcolnumber'=>$colnumber*2-1
				,'row'=>$row
				,'opisanie'=>$opisanie
			);
		}
		@$rows[count($rows)-1]['class'].=' last';
		
		return smart_template(array(ELEMENTS_TPL,'edit_table'),array(
			'header'=>$header,
			'cols'=>$isfixed?$cols:'',
			'inputs'=>$inputs,
			'notfixed'=>!$isfixed,
			'align'=>$aligns[ppi($this->v['align'])],
			'rows'=>$rows,'colnumber'=>$colnumber,'xcolnumber'=>$colnumber*2-1));
		//'<table border=1>'.$table.'</table>';
	}

	function getForm($numb){
		$par=$this->v;//debug($par);
		if(!empty($this->el)){
			$cell=array();
			foreach( $this->el[0]->el as $v) {
				$cell[]=$v->v;
			}
			$keys=array();
			$this->serialize($keys);
			$par['thetable']=$this->getText($keys);
			$par['trclass']=evenodd($numb);
			$par['vstavka'] = array();
		}
		$par['name']=pps($this->v['item_name'],nameByType($this->v['type']));
		return	smart_template(array(ELEMENTS_TPL,'table_edit_line'),$par);
	}

	function serialize(&$formvar,$dir=false){
		global $engine;
		xElement::serialize_var($formvar,$dir,
			array(
				'item_name_'=>'name'
				,'item_align_'=>'align'
				,'tab_width_'=>'tab_width'
				,'tab_foto_'=>'tab_foto'
				,'h_s_'=>'h_s'
				,'h_b_'=>'h_b'
				,'w_s_'=>'w_s'
				,'w_b_'=>'w_b'
				,'border_'=>'border'
			)
		);
		xElement::serialize_order_menu($formvar,$dir,'item_order_');
		if($dir){
			if(!empty($_POST['new_row_add_'.$this->v['id']]))
			{
				$engine->addNode($this->node(),array('name'=>'row','type'=>type_ROW));
			}
//			debug($formvar);
			if(isset($formvar['tab_rows_'.$this->v['id']])){
				// +1 в конце - чтобы в числе строк в форме редактирования не учитывать заголовок
				$d_row=$formvar['tab_rows_'.$this->v['id']]-count($this->el)+1;
				// Число столбцов таблицы не меньше одного
				if($formvar['tab_colls_'.$this->v['id']] < 1)
					$formvar['tab_colls_'.$this->v['id']] = 1;
				// Число столбцов каталога не меньше трёх
				if($formvar['tab_colls_'.$this->v['id']] < 3 && $this->v['type'] == type_KATALOG1)
					$formvar['tab_colls_'.$this->v['id']] = 3;
				//+1 - чтобы не учитывать в форме редактирования столбец - доп. описание
				//if($this->v['type'] == type_KATALOG1)	{$d_cell++;}
				$lastrow=$this->el[count($this->el)-1];
				$lastcell=$lastrow->parent->el[count($lastrow->parent->el)-1];
				while($d_row>0){
					$d_row--;
					$this->newCellRow($lastcell->v['id'],'Rows');
				}
				while($d_cell>0){
					$d_cell--;
					$this->newCellRow($lastcell->v['id'],'Cells');
				}
				if($d_row<0){
					$rowcnt=count($this->el);
					//$lastcell=$lastrow->el[count($lastrow)-1];
					while($d_row<0){
						$d_row++;$rowcnt--;
						$this->delCellRow($this->el[$rowcnt]->el[0]->v['id'],'Rows');
					}
				}
				if($d_cell<0){
					$cellcnt=count($this->el[0]->el);
					//$lastcell=$lastrow->el[count($lastrow)-1];
					while($d_cell<0){
						$d_cell++;$cellcnt--;
						$this->delCellRow($this->el[0]->el[$cellcnt]->v['id'],'Cells');
					}
				}
			}
//			debug('hello!');
			if(!empty($this->el))
			foreach($this->el[0]->el as $v){
				if(isset($formvar['item_url_'.$v->v['id']])) {
					if(preg_match('/^(\d+)(px|pt|%|(.*))$/i',$formvar['item_url_'.$v->v['id']],$m)){
				//debug($m);
						if(!empty($m[3]) || empty($m[2])) 	
							$m[2]='px';
						else
							$m[2]=strtolower($m[2]);
						$formvar['item_url_'.$v->v['id']]=$m[1].$m[2];
						$_POST['item_url_'.$v->v['id']]=$m[1].$m[2];
					}
					else {
						$formvar['item_url_'.$v->v['id']]='';
						$_POST['item_url_'.$v->v['id']]='';
					}
				}
			}
		} else {
			// -1 в конце - чтобы в числе строк в форме редактирования не учитывать заголовок
			$formvar['tab_rows_'.$this->v['id']]=count($this->el)-1;
			if(isset($this->el[0])) {
				$formvar['tab_colls_'.$this->v['id']]=count($this->el[0]->el);
				// -1 - чтобы не учитывать в форме редактирования столбец - доп. описание
				if($this->v['type'] == type_KATALOG1)
					$formvar['tab_colls_'.$this->v['id']]--;
			}
			else
				$formvar['tab_colls_'.$this->v['id']]=0;	
		}
		if(!empty($this->el))
		foreach($this->el as $v){
			$v->serialize($formvar,$dir);
		}
	}
	function Create($key,$parent) {
		global $engine;
		$id= $engine->nodeAdd($parent,$key);
		$row= &new xRow();
		$row->Create(array('name'=>'Header','type'=>type_ROW),$id);
		$row= &new xRow();
		$row->Create(array('name'=>'Header','type'=>type_ROW),$id);
		return $id;
	}
}

class xKatalog1 extends xTable {

	function xKatalog1() {
		$this->img_col = 0;
		$this->osn_col = 1;
		$this->op_col = 2;
	}

	function Create($key,$parent) {
		// В какой колонке изображения
		//$this->img_col = 1;
		global $engine;
		$id= $engine->nodeAdd($parent,$key);
		$row= &new xRow();
		$rid = $row->Create(array('name'=>'Header','type'=>type_ROW),$id);
		$cid = $engine->nodeAdd($rid,array('type'=>type_CELL,'text'=>''));
		$cid = $engine->nodeAdd($rid,array('type'=>type_CELL,'text'=>'Доп. описание'));
		//$engine->nodeAdd($cid,array('type'=>type_PIC, 'name'=>'picture', 'pic_small'=>'', 'pic_big'=>''));
		$row= &new xRow();
		$rid = $row->Create(array('name'=>'Header','type'=>type_ROW),$id);
		$cid = $engine->nodeAdd($rid,array('type'=>type_CELL,'text'=>''));
		$cid = $engine->nodeAdd($rid,array('type'=>type_CELL,'text'=>''));
		//$engine->nodeAdd($cid,array('type'=>type_PIC, 'name'=>'picture', 'pic_small'=>'', 'pic_big'=>''));
		return $id;
	}
	
	function getForm($numb){
		$par=$this->v;//debug($par);
		if(!empty($this->el)){
			$cell=array();
			foreach( $this->el[0]->el as $v) {
				$cell[]=$v->v;
			}
			$keys=array();
			$this->serialize($keys);
			$par['thetable']=$this->getText($keys);
			$par['trclass']=evenodd($numb);
			$par['img_options'] = array('id'=>$par['id']);
		}
		$par['name']=pps($this->v['item_name'],nameByType($this->v['type']));
		return	smart_template(array(ELEMENTS_TPL,'table_edit_line'),$par);
	}
}

class xNewTextPic extends xCommon {
	function xNewTextPic(){
		xCommon::xCommon(array(
			'fields'=>array(
				'item_text'=>array('type'=>'html','title'=>'Текст заголовка') // верхний заголовок
			)
			,'inner'=>array(
				'picture'=>array('width'=>125,'type'=>type_PIC,'button'=>'Доб.Фото')
				,'article'=>array('width'=>125,'type'=>type_ARTICLE,'button'=>'Доб.Описание')
			)
			,'align'=>true 
		));
	}
	
	function getText(&$keys,$nlast=false){
		global $engine;
		$key = array();
		$param=array(
				'the_text'=>$this->v['item_text'],
 			);
		$rows=array();
		$articles=array();
		foreach($this->el as $v){
 			if($v->v['type']==type_PIC){
 				$rows['pict'][]=$v->getData();
				}
 			else {
 				$articles['links'][]=$v->getData() ;
 			}	
  		}
 		if(!empty($rows)){
 			$rows['align']=$this->aligns($this->v['item_align']);
 			$param['row']=$rows;
 		}
 		if(!empty($articles)){
 			$param['articles']=$articles;
 		}
		

 		//debug($param);	
 		return smart_template(array(ELEMENTS_TPL,pps($engine->tpl_elements,'katalog')),
 			array('last'=>!$nlast,pps($this->tpl,'textpic')=>$param)
					
					
 		);
	}
} 

class xRow extends xElement {
	// состоит из text
	function getText(&$keys,$td='td',$class='',$width=''){
		global $engine;
		static $invert=array();
	// row - header
		if (empty($width)) $width=array();
		$cols=array();
		$num=0;
		for($i=0;$i<count($this->el);$i++){
			$num=$num+1;
			$v=&$this->el[$i]->v;
			if (isset($v['text']))
			{
				$x=array('td'=>$td,
					'id'=>$v['id'],
					//'width'=>pp($width[$k],'width:',';'),
					'text'=>$v['text'],
				);
				if(isset($v['colspan'])) {
					$x['colspan']=$v['colspan'];
				}
				if($i & 1){
					$x['class']=pp($x['class'],'',' ').'xodd';
				}
				if(!empty($invert[$i])){
					$invert[$i]=0; debug('xxx');
					$x['class']=pp($x['class'],'',' ').'invert';
				}
				if(isset($v['rowspan'])){
					$x['rowspan']=ppi($v['rowspan']);
					if(($x['rowspan']>2) && (($x['rowspan'] & 1) ==0)){
						$invert[$i]=1; debug($invert);
					}
				}
				$x['class_r'] = "text_edit";
				//echo $this->parent->v['border']."!!!";
				// Для редактирования каталога
				if($this->parent->v['type'] == type_KATALOG1 && $i==$this->parent->img_col && $x['td'] == 'td') {
					if(isset($v['pic_small']))
						$x['pic_small'] = $v['pic_small'];
					else
						$x['pic_small'] = '';
					if(isset($v['pic_big']))
						$x['pic_big'] = $v['pic_big'];
					else
						$x['pic_big'] = '';
					$x['class_r'] = "nopage uploader action_both";
					if(ereg('.+admin/index.php', $_SERVER['SCRIPT_FILENAME'])) {
						if ($x['pic_small'] == '')
							$x['pic_small'] = 'admin/img/1x1t.gif';
						$x['text'] = "<div style=\"display:none;\"><div> <input type=\"button\" onclick=\"ReplaceImg(this)\"> </div></div> <input name=\"pic_small_".$x['id']."\" type=\"text\" value=\"".$x['pic_small']."\"  style=\"display:none;\"> <input name=\"pic_big_".$x['id']."\" type=\"text\" value=\"".$x['pic_big']."\"  style=\"display:none;\"> <img src=\"../".$x['pic_small']."\" alt=\"\" onload=\"checkImg(this,80,60)\">";
					}
					else if ($x['pic_small'] != '')
						$x['text'] = "<div class=\"gallery\"><a rel=\"g".$x['id']."\" href=\"".$x['pic_big']."\"><img src=\"".$x['pic_small']."\" alt=\"\" ".$engine->brd($this->parent->v['border'])."></a></div>";
					else
						$x['text'] = "&nbsp;";
				}
				if($this->parent->v['type'] == type_KATALOG1 && $i==$this->parent->osn_col && (!ereg('.+admin/index.php', $_SERVER['SCRIPT_FILENAME']) && $x['td'] == 'td') && !empty($this->el[$this->parent->op_col]->v['text'])) {
					$x['text'] = '<a href="javascript:show_op('.$this->v['id'].');">'.$x['text'].'</a>';
				}
				if($this->parent->v['type'] == type_KATALOG1 && $i==$this->parent->op_col && !ereg('.+admin/index.php', $_SERVER['SCRIPT_FILENAME'])) {
					$this->parent->v['opisanie'] = $x['text'];
					continue;
				}
				if($this->parent->v['type'] == type_KATALOG1 && $i==$this->parent->op_col && ereg('.+admin/index.php', $_SERVER['SCRIPT_FILENAME']) && $x['td'] == 'td') {
					$x['class_r'] = "html_edit";
				}

				if(!strncasecmp($x['text'],'<div align="center"><b>',22) || !strncasecmp($x['text'],'<b>',3) || !strncasecmp($x['text'],'<strong>',8) || !strncasecmp($x['text'],'<span style="font-weight: bold;">',33) || !strncasecmp($x['text'],'<div><b>',8))  {

						$x['class22'] = "bold_header";
				}else{	$x['class22'] = "border_header"; }
				if($num == 1){$x['class22']=$x['class22']." first_td";}
				//print_r($x);
				$cols[]=$x;

			}
		}
		$cols[count($cols)-1]['class']=pp($cols[count($cols)-1]['class'],'',' ').'last';
		return smart_template(array(ELEMENTS_TPL,'edit_row'),array('cols'=>$cols));
	}

	function getForm(){
		$x=smart_template(array(ELEMENTS_TPL,'row_edit_line'),$this->v);
		if(!empty($this->el))
		foreach( $this->el as $v) {
			$x.=$v->getForm();
		}
		return	$x;
	}

	function serialize(&$formvar,$dir=false){
		global $engine;
		xElement::serialize_order_menu($formvar,$dir,'item_order_');
		if($dir){
			if(!empty($_POST['new_row_add_'.$this->v['id']]))
			{
				$engine->nodeAdd($engine->node($this->v),array('text'=>'','type'=>type_CELL));
			}
		}
		if(!empty($this->el))
		foreach($this->el as $v){
			$v->serialize($formvar,$dir);
		}
	}
	function Create($key,$parent) {
		global $engine;
		$id= $engine->nodeAdd($parent,$key);
		$cid = $engine->nodeAdd($id,array('type'=>type_CELL,'text'=>''));
		return $id;
	}

	function getContextMenu(){
		return ' ';
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
				case 'mediuminput':
					$fields[]=array(
						'mediuminput'=>array(
							'name'=>$name
							,'id'=>$this->v['id']
							,'title'=>pps($parm['title'])
						)
					);
					break;
				case 'razmer1':
					$fields[]=array(
						'razmer1'=>array(
							'name'=>$name
							,'id'=>$this->v['id']
							,'title'=>pps($parm['title'])
							,'vp'=>pps($parm['vp'])
						)
					);
					break;
				case 'razmer2':
					$fields[]=array(
						'razmer2'=>array(
							'name'=>$name
							,'id'=>$this->v['id']
							,'title'=>pps($parm['title'])
							,'vp'=>pps($parm['vp'])
						)
					);
					break;
				case 'stolb':
					$fields[]=array(
						'stolb'=>array(
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
							'name'=>$name
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
				'img'=>array(
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
				} else if($name=='row_com'){
					$x.=smart_template(array(ELEMENTS_TPL,'komment_edit_line'),$vv->v);		
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
			$par['pictures']=array(
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
		if(!empty($add['row_com'])){
			$par['komment']=array(
				'colnum'=>count($cols)+1,
				'links'=>$add['row_com']
			);
		}
		$par['trclass']=evenodd($numb);
		//print_r($fields);
		$par['fields']=$fields;
		$par['colnum']=count($this->param['fields']);
		$par['type']='тип:'.nameByType($this->v['type']);
		$par['item_name']=pps($this->v['item_name'],nameByType($this->v['type']));
		$par['minmax']=$this->v['type']==type_KATALOG || !isset($this->param['inner']);
		// Блокировка удаления модуля
		if($this->v['type'] == type_PLUGIN) {
			$par['lock1'] = "<!--";
			$par['lock2'] = "-->";
		}
			
		if(!empty($cols))
			$par['cols']=$cols;
		$x=smart_template(array(ELEMENTS_TPL,'common_line'),$par);
		return	$x;
	}
	function serialize(&$formvar,$dir=false){
		if(isset($_POST['item_text_'.$this->v['id']])) {
			// Переделка абсолютных ссылок на СМС в относительные для корректной работы в IE
			$_POST['item_text_'.$this->v['id']] = ereg_replace("href=\"http://.+/admin/", "href=\"", $_POST['item_text_'.$this->v['id']]);
		}
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
					$nd_param = array('name'=>$k,'type'=>$v['type']);
					// Время и имя пользователя у комментария
					if($v['type'] == type_ROW_COM) {
						$nd_param['date'] = date('Y-m-d H:i:s');
						if(isSet($_SESSION['FORM_login']['login_name']))
							$nd_param['username'] = $_SESSION['FORM_login']['login_name'];
					}
					$engine->nodeAdd($this->node(),$nd_param);
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

class xTP extends xCommon {
	function getText(&$keys,$nlast=false){
		global $engine;
		$key = array();
		// Переделка абсолютных ссылок на СМС в относительные для корректной работы в IE
		$this->v['item_text'] = ereg_replace("href=\"http://.+/admin/", "href=\"", $this->v['item_text']);
		$param=array(
				'the_text'=>$this->v['item_text'],
 			);
//		$this->serialize($key);
		$rows=array();
		$articles=array();
		foreach($this->el as $v){
 			if($v->v['type']==type_PIC){
 				$vv=$v->getData();
 				$vv['border']=ppi($this->v['border'])!=0;
 				$rows['pict'][]=$vv;
 			}
 			else {
 				$qwert = $v->getData();
 				$qwert['opisanie'] = $engine->ffirst('do_page', $qwert['id']);
 				$articles['links'][]=$qwert;
 			}	
  		}
 		if(!empty($rows)){
 			$rows['align']=$this->aligns($this->v['item_align']);
 			$param['row']=$rows;
 		}
 		if(!empty($articles)){
 			$param['articles']=$articles;
 		}
 		//debug($param);
 		//echo pps($engine->tpl_elements,'katalog');
 		return smart_template(array(ELEMENTS_TPL,pps($engine->tpl_elements,'katalog')),
 			array('last'=>!$nlast,
 			'ajax'=>!!$engine->is_ajax,
 			'textpic'=>$param)
 		);
 	}
		
	function xTP(){
		xCommon::xCommon(
		array(
			'fields'=>array(
				'item_text'=>array('type'=>'html','title'=>'текст для списка') // 50,70,100
				,'border'=>array('width'=>40,'type'=>'checkbox','title'=>'Рамка')
			//	,'anchors'=>array('title'=>'список якорей через /') // 50,70,100
			) 
			,'inner'=>array(
				'picture'=>array('width'=>125,'type'=>type_PIC,'button'=>'Доб.Фото')
				,'article'=>array('width'=>125,'type'=>type_ARTICLE,'button'=>'Доб.Описание')
			)
			,'align'=>true
		) 
		);
	}	
}

itemByType(type_NEWTEXTPIC,'xTP');
nameByType(type_NEWTEXTPIC,'Текст');

class yGallery extends xCommon {
	
	function yGallery(){
		xCommon::xCommon(
		array(
			'fields'=>array(
				'item_columns'=>array('type'=>'stolb','title'=>'Столбцы '),
				'w_s'=>array('type'=>'razmer1','title'=>'Мал.','vp'=>'Размер мал. фото (px)'),
				'h_s'=>array('type'=>'razmer2','title'=>'x','vp'=>'Размер мал. фото (px)'),
				'w_b'=>array('type'=>'razmer1','title'=>'Бол.','vp'=>'Размер бол. фото (px)'),
				'h_b'=>array('type'=>'razmer2','title'=>'x','vp'=>'Размер бол. фото (px)'),
				'border'=>array('type'=>'checkbox','title'=>'Рамка')
			) 
			,'inner'=>array(
				'picture'=>array('width'=>125,'type'=>type_PIC,'button'=>'Доб.Фото')
//				,'article'=>array('width'=>125,'type'=>type_ARTICLE,'button'=>'Доб.Описание')
			)
			,'align'=>true
		));
	}

	function getText(&$keys){
		global $engine;
		$key = array();
		$this->serialize($key);
		$row=array();
		$w_width = 0;
		$w_height = 0;
		foreach($this->el as $v){
 			//$row[]=$res;
 			if($v->v['type']==type_PIC){
 				$vv=$v->getData();
 				$vv['border']=ppi($this->v['border'])!=0;
 				//if($vv['comment'] == "")
 				//	$vv['comment'] = "   ";
 				//print_r($vv['xhref']);
 				if(ereg("\.flv$", $vv['xhref'])) {
 					$vv['bwidth'] = 400;
 					$vv['bheight'] = 300;
 				}
 				if($vv['bwidth'] > $w_width)
 					$w_width = $vv['bwidth'];
 				if($vv['bheight'] > $w_height)
 					$w_height = $vv['bheight'];
 				$row[]=$vv;
 			}
 			if(count($row)>=ppi($this->v['item_columns'],1)){
			$x=array('pict'=>$row) ;$x['pictcom']=$row ;$x['row1']=&$x;$x['row2']=&$x;
 			$rows[]=$x ;
 				$row=array();
 			}
		}

		if(!empty($row)){
			$x=array('pict'=>$row) ;$x['pictcom']=$row ;$x['row1']=&$x;$x['row2']=&$x;
 			$rows[]=$x ;
		}
 		return $engine->_tpl('tpl_jelements','_el_gallery',
//                              smart_template(array(ELEMENTS_TPL,'katalog'),
 			array(
 			'gallery'=>array(
 				'align'=>$this->aligns(ppi($this->v['item_align'])),
//                'variant'=>ppi($engine->par['show_news']),
 				'row'=>$rows,
 				'id'=>$this->v['id'],
 				'w_width'=>$w_width,
 				'w_height'=>$w_height
 			))
 		);
	}

}

class xLinks extends xCommon {
	
	function xLinks (){
		xCommon::xCommon(
		array(
			'fields'=>array(
				'item_columns'=>array('type'=>'smallinput','title'=>'Количество столбцов:')
			)
			,'inner'=>array(
//				'picture'=>array('width'=>125,'type'=>type_PIC,'button'=>'Доб.Фото')
				'article'=>array('width'=>125,'type'=>type_ARTICLE,'button'=>'Доб.Описание')
				,'link'=>array('width'=>125,'type'=>type_LINK,'button'=>'Доб.Ссылку')
			)
			,'align'=>true
		));		
	}

	function getText(&$keys){
		global $engine;
		$id=ppi($this->v['id']);
		$par='';
		$base='href';

  		$_cols=ppi($this->v['item_columns'],1);
  		$_align=$this->aligns(ppi($this->v['item_align']));

  		$row=array();
  		$row_article=array();
 		foreach($this->el as $v){
 			$iid=$v->v['id'];
 			if($v->v['type']==type_ARTICLE) {
 			$x=array(
 				 'url'=>'javascript:show_opisanie(\''.$v->v['id'].'\');'
 				,'class'=>'url_page'
 				,'text'=>$v->v['item_text']
 				,'id'=>$v->v['id']
 				,'opisanie'=>$engine->ffirst('do_page', $v->v['id']));
 			} else {
 				$url=TO_URL($v->v['url']);
 				$x=array(
 				 'url'=>$url
 				,'class'=>pps($v->v['class'])
 				,'text'=>$v->v['text']);
 			}
 			//debug($url);	
 			if((strpos($url,'?do=')===false)
 			  && !preg_match('/\....?$/',$url)
 			  ) {
 				$x['target']=' target="_blank"';
 			}
 			if($v->v['type']==type_ARTICLE) {
 				$x['target']=' target="_self"';
 			}
 			//else debug($keys['item_url_'.$iid]);
 			if(ereg("\.flv$", $x['url'])) {
 				$x['url'] = "javascript:flv_player_start('".$x['url']."');";
 				$x['target']=' target="_self"';
 			}
 			if($v->v['type']==type_ARTICLE) {
 				$row_article[]=$x;
 			} else {	
 				$row[]=$x;
 			}
 		}
 		$rows=array();
 		$row=array_chunk(array_merge($row_article,$row),$_cols);
 		
 		if(!empty($row))
 			$row[count($row)-1][0]['last']=true;
 		//debug($row);	
 			
 		for($i=0;$i<count($row);$i++){
 			$rows[]=array('link'=>$row[$i]);
 		}
 		
 		if(!empty($rows))
 		$par.=smart_template(array(ELEMENTS_TPL,'katalogx'),
 				array('align'=>$_align,$base=>$rows)
 			);
  		return $par;
	}
}


///****** point classes_body */
/**
  *   view комментария
  */
class xKomment extends xElement  {
	
	function getForm($numb){
		global $engine;
		$par=$this->v;
		$par['data']=comments::getByTopic($this->v['id']);
		$par['trclass']=evenodd($numb);
		$par['name']=pps($this->v['item_name'],nameByType($this->v['type']));
		return	$engine->_tpl('tpl_jvocabular','_komment_edit',$par);
	}
	
	function serialize(&$formvar,$dir=false){
		global $engine;
		xElement::serialize_var($formvar,$dir,
			array(
				'item_columns_'=>'item_columns'
				,'item_align_'=>'align'
			)
		);
		xElement::serialize_order_menu($formvar,$dir,'item_order_');
		if($dir){
			if(isset($_POST['del']) && preg_match('/del'.$this->v['id'].'\[(\d+)\]/',$_POST['del'],$m)){
				$engine->export('comments','data','del',$m[1]);
			}
			if(!empty($_POST['new_row_com_'.$this->v['id']]))
			{
				$key = array(
					'topic'=>$this->v['id']
					,'date'=>date('Y-m-d H:i:s')
				);
				comments::add($key);
			}
			$data=comments::getByTopic($this->v['id']);
			foreach ($data as &$d){
				$changed=false;
				foreach ($this->fields as $v){
					$nm=$v['name'].$d['id'].'_'.$this->v['id'];
					if(isset($_POST[$nm]) && ($d[$v['name']]!=$_POST[$nm])){
						$d[$v['name']]=$_POST[$nm];
						$changed=true;
					}
				}
				if($changed)
					$engine->export('comments','data','upd',$d,$d['id']);
			}
		} else {
			$data=comments::getByTopic($this->v['id']);
			foreach ($data as $d){
				$formvar['new'.$d['id'].'_'.$this->v['id']]=$d['new'];
			}
		}
	}
	function Create($key,$parent) {
		global $engine;
		if (isset($this->param['name']))
			$key=array_merge(array('name'=>$this->param['name']),$key);
		return $engine->nodeAdd($parent,$key);
	}
	
	function xKomment(){
		$this->fields=array(
			array('name'=>'new'),
			array('name'=>'date'),
			array('name'=>'username'),
			array('name'=>'text'),
			array('name'=>'quote'),
			array('name'=>'topicname'),
		);
				//array('','id','button'),		)
	}

	function getText(&$keys){
		global $engine;
//TODO: несколько комментов на странице - нужно разобрать какие куда 		
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

/**
 * модель хранения данных
 */

class comments extends ml_plugin {
	
	//static $table='_comment';
	
/**
 * добавить комментарий
 */	
	static function add($key){
		global $engine;
		$key['new']=0;
		return $engine->database->query('INSERT INTO ?_comment (?#) VALUES(?a);',
		   			array_keys($key),array_values($key));
	}
	
	static function getByTopic($topic)
	{
		global $engine;
		return $engine->database->query('select * from ?_comment where `topic`=? order by `date` DESC',$topic);
	}
	
/**
 * получить данные из коммента по топику
 */	
	static function get($topic){
		global $engine;
		$v=$engine->database->select('select * from ?_comment where `topic`=?d',$topic);
		if(!empty($v)){
			foreach($v as &$vv){
				$vv['info'] = $engine->export('MAIN','userinfo',$vv['user']);
				//debug($tmp);
			}
		}
		if(empty($v))
			return array();
		return 
			$v;
	}
	
/**
 * создание таблиц
 */
	function do_create($killall=true){
		if(!$this->parent->has_rights(right_WRITE))
			return $this->parent->ffirst('_loginform');
		if($killall){
			$this->database->query('DROP TABLE IF EXISTS ?_comment;');
		}
		$this->database->query("CREATE TABLE ?_comment (
		  `id` int(11) NOT NULL auto_increment,
		  `topic` int(11) NOT NULL default '0',
		  `new` varchar(80) character set cp1251 NOT NULL default '',
		  `username` varchar(80) character set cp1251 NOT NULL default '',
		  `user` int(11) NOT NULL default 0,
		  `date` datetime default NULL,
		  `quote` text character set cp1251 NOT NULL,
		  `text` text character set cp1251 NOT NULL,
		  PRIMARY KEY  (`id`),
		  KEY `topic` (`topic`)
		);");
	}
	
/**
 * конструктор - описание полей комментария, определение формы админки
 */
	function comments($parent){
		parent::ml_plugin($parent);
		$par=array(
			'title'=>'Комментарии'
			,'fields'=>array(
				array('Проверено','new','check01'),
				array('Дата','date'),
				array('Имя','username'),
				array('Текст','text'),
				array('Цитата','quote'),
				array('раздел','topicname','dontshow'=>1),
				//array('','id','button'),
			)
			,'options'=>array('noadd'=>true,'group'=>'topicname')
			,'group'=>'topicname'
			,'base'=>'_comment'
			,'prefix'=>'cm'
		);
		parent::_init($par); 	
	}
	
/**
 * Парамеры плагина
 * @param unknown_type $par
 *//*
	function get_parameters($par){
		$par['list'][]=array('sub'=>'Результаты поиска','title'=>'Количество результатов на страницу','name'=>'search_per_page');
		$par['list'][]=array('sub'=>'Форум','title'=>'Количество сообщений на страницу','name'=>'forum-perpage');
	}
*/
/**
 * форма администрирования
 */
	function admin_comments(){
		$topic_name = $this->getPluginName();
		return
		smart_template(array(ADMIN_TPL,'theheader'),array(
		'header'=>$topic_name,
		'data'=>parent::admin_form()));
	}

	function topicname($topic){
		global $engine;
		static $cache;
		if(empty($cache)) $cache=array();
		if(!empty($cache[$topic])) return $cache[$topic];
		$tmp=$engine->nodeGetBackPath($engine->node($topic));
		$name='';
		if(isset($tmp[1]))
			$name=pp($tmp[1]['item_columns'],'-',' ');
		if($tmp[0]['type']==type_ARTICLE){
			$t=$engine->readRecord(array('page'=>$tmp[0]['node']));
			if(!empty($t))
				return $cache[$topic]=$t['name'].$name;
		}	
		return '';
	}
/**
 * работа с данными
 * @see ml_plugin::data()
 */	
	function data($what,$from='',$perpage=''){
		global $engine;
		switch($what){
			case "cnt":
				return @$this->database->selectCell('select count(*) from ?'.$this->base.' where `new`=0;');
			case "row":
				return $this->database->selectRow('select * from ?'.$this->base.' where `id`=?d',$from);
			case "data": 
				$data=$this->database->query('select * from ?'.$this->base.' where `new`=0 order by `topic`,`date` LIMIT ?d,?d',$from,$perpage);
				foreach($data as $k=>$d){
					$data[$k]['topicname']=$this->topicname($d['topic']);
				}
				return $data;
			case "del":
				$this->database->query('DELETE from ?'.$this->base.' where `id`=?d;',$from);
				break;
			case "upd":
				$this->database->query('update ?'.$this->base.' set ?a where `id`=?;',$from,$perpage);
				break;
			case "ins":
				return $this->database->query('INSERT INTO ?'.$this->base.' (?#) VALUES(?a);',
		   			array_keys($from),array_values($from));
		}
	}
}

/**
 *  класс-затычка для обратной совместимости. 
 */
class xRow_com extends xElement { }

///****finish point classes_body *//*
 
?>