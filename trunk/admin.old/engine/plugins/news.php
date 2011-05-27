<?php
/**
 * плагин - бегущая строка
 */
define('VOCABULAR_TPL','tpl_vocabular');

class ml_plugin extends plugin {
	
	/**
	 * обеспечить наличие в заголовке страницы нужных скриптов и 
	 * стилей
	 */
	function get_head(){
		if(isset($this->head_tpl))
			return smart_template($this->head_tpl,array());
		else
			return '';	
	}

	function _init ($arg){
		foreach($arg as $k=>$v){
			$this->$k=$v;
		}
	}

	function getPluginName(){
		if(!empty($this->title))
			return $this->title ;
		return 	__CLASS__;
	}

	function getContext($id){
		$key=array();
		$this->serialize($id,$key,false);
		return $key;
	}

	function check_data(&$data){ return true;}

	function serialize($id,&$key,$dir=false){

		if(!is_array($id))
			$res=$this->data('row',$id);
		else
			$res=$id;
		if($dir){
			$upd=array();
			foreach ($this->fields as $vv){
				if(!isset($vv['serialize']) || $vv['serialize']){
					$v=$vv[1];
					$idx=$v.'_'.$res['id'];
					if($vv[2]=='article' && isset($key['edit_'.$vv[1].'_'.$res['id']])){
						if(empty($res[$v])) { // новая статья
							$node=$this->parent->nodeAdd(0,array('name'=>'itemarticle','type'=>type_ARTICLE));
							$x=$this->parent->nodeGet($node);
							$upd[$v]=$x[0]['id'];
							$this->newurl='do=page&id='.$upd[$v];
						} else {
							$this->newurl='do=page&id='.$res[$v];	
						}
					} elseif (isset($key[$idx]) && ($key[$idx]!=$res[$v])) {
						$upd[$v]=pps($key[$idx]);
					}
				}
			}
			
			//Переделка абсолютных адресов в относительные
			if(!empty($upd['pic_small']))
				$upd['pic_small'] = toUrl_sf($upd['pic_small']);
			if(!empty($upd['pic_big']))
				$upd['pic_big'] = toUrl_sf($upd['pic_big']);
			
			if(!empty($upd) && $this->check_data($upd,$res)){
				$this->data('upd',$upd,$res['id']);
			}
		} else {
			//debug(111);debug($res);
			foreach ($this->fields as $vv){
				if(!isset($vv['serialize']) || $vv['serialize']){
					$v=$vv[1];
					$key[$v.'_'.$res['id']]=pps($res[$v]);
				}
			}
		}
	}

	function data($what,$from='',$perpage=''){
		switch($what){
			case "cnt":
				return @$this->database->selectCell('select count(*) from ?'.$this->base.' '.$this->orderbystr.';');
			case "row":
				return $this->database->selectRow('select * from ?'.$this->base.' where `id`=?d',$from);
			case "data":
				return $this->database->query('select * from ?'.$this->base.' '.
					pps($this->orderbystr).' LIMIT ?d,?d',$from,$perpage);
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

	function handlePost(){;}
	function handlePostAfter(){
		if(isset($this->newurl))
			$this->parent->go($this->newurl);
	}
/**
 * Примеры использования:
 
 * для задания в конструкторе каталога
// поле-чекбокс в начале строчки каталога
	array('<input type="checkbox" id="aaa" value="0">','check','checkbox'),
// поле загрузки фотографии. при загрузке из одного изображения формируются 2
	array('Фото','pic_small','image','csvfields'=>array('Фото')),
// простое "текстовое" поле
	array('Артикул','articul','csvfields'=>array('Артикул')),
// текстовое поле с обрезанием на 10 слов
	array('Описание','sdescr','afilter'=>10,'csvfields'=>array('Описание'))
// ссылка на внешний рессурс
	array('Статья','the_href','link'),
// Цена. Ключ - имя cost
	array('Цена','cost','csvfields'=>array('цена','Цена')),
// Логическое значение Есть-1/Нет -0
	array('Есть','ostatok','check01'),

 * для задания в описателе полей
// поле сортировки. Обязательно в комбинации с 'sort'=>true
	array('Сорт.','item_order','dontshow'=>true)
		)
		,'sort'=>true

 * 
 * */	
	
	function admin_plugin($nomenu=true){
//		debug($this->parent);
		if(!$this->parent->has_rights(right_WRITE))
			return $this->parent->ffirst('_loginform');

		if($nomenu)
			$this->parent->menu['head']=array('MAIN','_modules',$this->getPluginName(),get_class($this));

		if(!empty($this->base)){
		$cnt=$this->data('cnt');
		if($cnt===null) return 'не инициирован плагин <a href="'
			.$this->parent->curl('do','id').'do=create&plugin='
			.get_class($this).'">'.get_class($this).'</a>';

		// администрирование списка новостей
		$perpage=ppx($this->parent->getPar('items-perpage'),ppi($this->item_perpage,20));
	//	debug($cnt);
		if(empty($cnt))
			$maxpg=1;
		else
			$maxpg=ceil(($cnt-0.5)/$perpage);
		$page=ppi($_GET['pg']);
		if($page>=$maxpg)$page=max(0,$maxpg-1);

		$res=$this->data('data',$page*$perpage,$perpage);

		$pages=$this->parent->calc_Pages($cnt,$perpage,$page);
		//debug($res);
		if(empty($res)) $res='';

		$head=array();
		$row=array();
		$plus=array();
		$colnumb=0;
		foreach ($this->fields as $vv){
			if(!empty($vv['dontshow'])) continue;
			$r=array('title'=>$vv[0]);
			if(pps($vv[2])=='checkbox')
				$r['class']='nopage';
			$head[]=$r;
		}
		foreach ($this->fields as $v){
			if(!empty($v['dontshow'])) continue;
			$colnumb++;	
			$plus[]=array('class'=>pps($v[2]),'name'=>'xx_'.$v[1]);
		}
		if (isset($this->sort) && $this->sort){
			$colnumb++;	
			$head[]=array();		
		}
		
		if(!empty($res)){
			$i=$page*$perpage+1;
			foreach($res as $k=>$v){
				$row=array();
	         $row = $this->fields($v);
				// Дополнительные характеристики
				if(isSet($v['subkat'])) {
		         foreach($v['subkat'] as $ks=>$vs){
		         	$res[$k]['subkat'][$ks]['row'] = $this->fields($vs);
		         	if (isset($this->sort) && $this->sort){
							$res[$k]['subkat'][$ks]['sort'] = array('id'=>$vs['id']);	
						}
		         }
		         if(empty($res[$k]['subkat']))
		         	$res[$k]['subkat'] = '';
		         if (isset($this->sort) && $this->sort){
						$res[$k]['sort'] = array('id'=>$v['id']);	
					}
					unset($plus_subkat);
				}
				foreach ($this->fields as $vf){
					if(!empty($vf['dontshow'])) continue;
					$plus_subkat[]=array('class'=>pps($vf[2]),'name'=>'xx_'.$vf[1].'_'.$v['id']);
				}
				$res[$k]['prefix']=pps($this->prefix);
				$res[$k]['trclass']=evenodd($i);
				$res[$k]['numb']=$i++;
				$res[$k]['row']=$row;
				$res[$k]['plus']=$plus_subkat;
			}
		}
		//$fields=array('the_header','the_text','news_date');
		if(empty($data)) $data='';

		$form=new form('admin'.$this->base);
		$form->nostore=true;
		$form->scanHtml(smart_template(array(VOCABULAR_TPL,'admin_vocabular'),array(
				'name'=>$this->base,
				'additional'=>pps($this->additional),
				'additional2'=>pps($this->additional2),
				'error'=>pps($_SESSION['errormsg']),
				'head'=>$head,
				'colnumb'=>$colnumb,
				'pages'=>$pages,
				'list'=>$res,
		      'plus'=>$plus
			)));
		
		if($form->handle()){
			$this->handlePost($res);
			foreach($res as $krks => $vrks)
				$this->handlePost($vrks['subkat'], $vrks['id']);
			if(!empty($res))
				foreach($res as $k=>$v){
					//debug($k);
					$this->serialize($res[$k],$_POST,true);
					if(isSet($res[$k]['subkat']) && is_array($res[$k]['subkat'])) {
						foreach($res[$k]['subkat'] as $krks2 => $vrks2) {
							$this->serialize($res[$k]['subkat'][$krks2],$_POST,true);
						}
					}
				}
			$key=array();
			$key_arr = Array();
			foreach ($_POST as $kpost => $vpost){		
				foreach ($this->fields as $v){
					if(ereg('xx_'.$v[1].'_[0-9]+', $kpost)) {
						$parent_id = substr($kpost, strlen($v[1])+4);
						$key_arr[$parent_id][$v[1]]=$vpost;
					}
				}
			}
			foreach($key_arr as $kpi => $vpi) {
				$key_1 = $vpi;
				$key_1['parent_id'] = $kpi;
				if($this->check_data($key_1,$key_1))
					$this->data('ins',$key_1);
			}
		
			foreach ($this->fields as $v){
				if(!empty($_POST['xx_'.$v[1]]))
					$key[$v[1]]=$_POST['xx_'.$v[1]];
			}
			if(!empty($_POST['del']) && preg_match('/^(\w+)_(\d+)$/',$_POST['del'],$m)){
				$this->data('del',$m[2]);
			}
			if(!empty($key) || !empty($_POST['newRecord'])){
				if($this->check_data($key,$key))
					$this->data('ins',$key);
			}

			$this->handlePostAfter($res);
			
			$this->parent->go($this->parent->curl());
		}

		//		$form->var=array_merge($form_var,array_diff_key($form->var,$form_var));
		//debug($res);
		if(!empty($res))
			foreach($res as $k=>$v){
				$this->serialize($res[$k],$form->var,false);
				if(isSet($res[$k]['subkat']) && is_array($res[$k]['subkat'])) {
					foreach($res[$k]['subkat'] as $krks2 => $vrks2) {
						$this->serialize($res[$k]['subkat'][$krks2],$form->var,false);
					}
				}
			}
		//debug($form->var);
		//debug()	
		$x=$form->getHtml(' ');
		} else {
			$x='';
		}
		if (method_exists($this,'get_parameters'))
			$x.=$this->parent->ffirst('do_siteparam', get_class ($this));

		return $x;
	}


function fields($v) {
			foreach ($this->fields as $vv){
				if(!empty($vv['dontshow'])) continue;
				$r=array(
					'class'=>pps($vv[2]),
					'name'=>$vv[1],
					'id'=>$v['id']
				);
				if (pps($vv[2])=='checkbox'){
					$r['class']='nopage';
					if(pps($vv[1])=='check'){
						$r['d']='h';
						//$r['style']='background:white;padding:0;';
						$r['value']='<input type="checkbox" class="select" name="aaa" value="'.$v['id'].'">';
					} else {
						//$r['style']='background:white;padding:0;';
						$r['class'].=' align_center';
						$r['value']='<input type="checkbox" class="select" name="'.pps($vv[1]).'" '.(empty($v[$vv[1]])?'checked="checked" ':'').'">';
					}
				} else if (pps($vv[2])=='picture'){
					$r['class']='nopage uploader';
					$r['value']=
					'<input type="text" style="display:none;" name="'.$vv[1].'_'.$v['id'].'">'.
					'<img style="width:80px;height:80px;" src="'.toURL_sf(TO_URL(pps($v[$vv[1]]))).'" alt="" onload="checkImg(this)">';
				} else if (pps($vv[2])=='align'){
					$r['class']='align_center';
					$r['value']='<input type="text" name="'.$vv[1].'_'.$v['id'].'" class="align hidden">';
				} else if (pps($vv[2])=='article'){
					$r['class']='nopage';
					$r['value']=
					'<input type="submit" name="edit_'.$vv[1].'_'.$v['id'].'" class="button green small" value="+">';
				} else if (pps($vv[2])=='image'){
					$r['class']='nopage uploader action_both';
					$val="../".toURL_sf(TO_URL(pps($v[$vv[1]],'img/1x1t.gif')));
					$r['value']=
					'<div style="display:none;"><div><input type="button" onclick="ReplaceImg(this)"></div></div>'.
					'<input type="text" style="display:none;" name="'.$vv[1].'_'.$v['id'].'">'.
					'<input type="text" style="display:none;" name="'.str_replace('small','big',$vv[1]).'_'.$v['id'].'">'.
					'<img src="'.$val.'" alt="" onload="checkImg(this,80,60)">';
				} else if ($vv[1]=='order'){
					$r['value']='<input class="c_order" type="text" name="'.$vv[1].'_'.$v['id'].'">';
					$r['class'].=' nopage';
					$r['d']='h';
	//				debug($r['value']);
				} else if ($vv[2]=='check01'){
					$r['value']='<input class="win_check" type="text" name="'.$vv[1].'_'.$v['id'].'">';
					$r['class'].=' align_center nopage';
					//$r['d']='h';
	//				debug($r['value']);
				} else if ($vv[2]=='menu'){
					$r['value']='<div class="wide long '.$vv[3].'"><input class="long" type="text" name="'.$vv[1].'_'.$v['id'].'"></div>'	;
				} else if (pps($vv[2])=='link'){
					$r['value']=
					'<input type="text" class="nocontext long link_toolbox" '. 
					'onkeydown="need_Save()" value="" name="'.$vv[1].'_'.$v['id'].'"/>';
					
				} else if (isset($vv['afilter'])){
					//debug($vv['afilter']);
					$s=strip_tags($v[$vv[1]]);
					if(preg_match('/(?:\S+\s+){'.$vv['afilter'].'}/',$s,$m)){//'.$vv['afilter'].'
						$s=$m[0].' ...';
					}
					$r['value']=$s;

				} else if (pps($vv[2])=='button'){
					$r['class']='nopage';
					$r['style']='width:110px;text-align:center';
					$r['value']=
					'<input type="button" name="edit_'.$vv[1].'_'.$v['id'].'" class="button green" value="Редактир." onClick="top.location.href=\''.$this->parent->curl('topic')."topic=".pps($v[$vv[1]]).'\';">';
				} else if (pps($vv[2])=='dbl_button'){
					$r['class']='nopage';
					$r['style']='width:110px;text-align:center';
					if(isSet($vv['skip_subkat']) && $vv['skip_subkat'] && $v['parent_id'] != 0)
						$r['value'] = "&nbsp;";
					else
						$r['value']=
						'<input type="button" class="button_subkat" value="Доп. описание" onClick="kat_opisanie('.$v['id'].');"><br>
						<div id="subkat_show_'.$v['id'].'"><input type="button" class="button_subkat" value="Доп. хар-ки ('.$v['subkat_cnt'].')" onclick="dop_har_show('.$v['id'].');" ></div>
						<div id="subkat_hide_'.$v['id'].'" style="display:none;"><input type="button" class="button_subkat" style="width:80px;" value="Скр. хар-ки" onclick="dop_har_hide('.$v['id'].');" ><input type="button" class="button_subkat" style="width:23px;" value="+" onclick="element.$(\'newsubkat_'.$v['id'].'\').style.display=\'table-row\';" ></div>';
						//onclick="element.$(\'newsubkat_'.$v['id'].'\').style.display=\'table-row\';this.disabled=\'disabled\';
				} else if (pps($vv[2])=='f_button'){
					if(empty($v['subkat']))
						$r['value'] = "&nbsp;";
					else
						$r['value']=
						'<input type="button" class="" value="!"  onclick="dop_har('.$v['id'].')" >';
				} else if (pps($vv[2])=='dontedit'){
					$r['class']='nopage';
					$r['value']=pps($v[$vv[1]]);
				} else {
					$r['class']='text_edit';
					$r['value']=pps($v[$vv[1]]);
				}
				$row[]=$r;
			}
			return $row;
}

	function setupmenu($name=''){
		if (empty($this->parent->cur_menu)){
			$this->parent->cur_menu=get_class($this);
		}
		$this->parent->export('sitemap','getSiteMap');
		$x=$this->parent->export('sitemap','_getCurList');
		if(empty($x)){
            if(empty($name))
			    $name=$this->getPluginName();
            $this->parent->nopoplast=$name;
			$this->parent->export('sitemap','tit',$this->parent->nopoplast);
		
			$this->parent->export('sitemap','sub',$name);
		}
		$this->parent->handle(get_class($this));
	}
}

class search extends ml_plugin{

	function search(&$parent){
		parent::ml_plugin($parent);
		parent::_init(
		array(
			'title'=>'Результаты поиска'
		));
	}

	function saveres($x){
		//debug($x);
		$_SESSION['search_result']=$x;
	}

	function get_parameters($par){
		$par['list'][]=array('sub'=>'Результаты поиска','title'=>'Количество результатов на страницу','name'=>'search_per_page');
	}

	function admin_search(){
		return
		smart_template(array(ADMIN_TPL,'theheader'),array(
		'header'=>$this->getPluginName(),
		'data'=>parent::admin_plugin()));
	}

	function result(){
		ml_plugin::setupmenu();
		if(isset($_SESSION['search_result']))
			$x=$_SESSION['search_result'];
		else
			$x=array();
		if(isset($x['katalog'])){
			$Kat=&new xKatalogue();
			$Kat->v=array('id'=>$x['katalog']);
			return $Kat->getText($x);		
		}	

		$page=ppi($_GET['pg']);
		$perpage=ppi($this->parent->getPar('search_per_page'),20);
		$cnt=count($x);
		if($page>floor($cnt/$perpage)) $page=0;
		$pages=$this->parent->calc_Pages($cnt,$perpage,$page);

		$x=array_slice($x,$page*$perpage,$perpage);
		$last='';$xx=array();
		foreach($x as $v){
			if($last!=$v['tag']){
				$last=$v['tag'];
				$xx[]=array('page'=>$v['tag'],'items'=>array());
			}
			$xx[count($xx)-1]['items'][]=array('item'=>$v['text']);
		}

		if(empty($x))
			return 'По вашему запросу ничего не было найдено!';
		else
			return smart_template(array(ELEMENTS_TPL,'searchres'),
				array(
					'id'=>$_SESSION['search_string'],
					'searchstr'=>$_SESSION['search_string'],
					'list'=>$xx,
					'pages'=>$pages
				));
	}
}

define ('NEWS_TPL','tpl_news');

class news extends ml_plugin
{
	
	function news(&$parent){
		parent::ml_plugin($parent);
		$this->head_tpl=array('tpl_news','news_head');
		parent::_init(
		array(
		'title'=>'Новости'
		,'fields'=>array(
					array('дата','news_date','text_edit'),
					array('заголовок','the_header','html_edit'),
					array('картинка','pic_small','image'),
					array('картинка','pic_big','image','dontshow'=>true),
					array('текст','the_text','html_edit','afilter'=>30)
		)
		,'base'=>'_news'
		,'orderbystr'=>' order by `news_date` DESC, `id` DESC'
		,'prefix'=>'nw'));
	}
	
	function handlePostAfter($v){
		parent::handlePostAfter($v);
		$this->parent->export('rss','do_rebuild');
	}
	
	/**
	 * Функция выводит массив-данные для шаблона - разбивка новостей по годам
	 *  - вывод всего диапазона годов в массиве
	 */
	function years(){
		static $x ; if(isset($x)) return $x;
		$res=$this->database->query('SELECT YEAR( `news_date` ) AS year
FROM ?_news
GROUP BY year
ORDER BY year;');
		$x=array();
		$today = getdate();
		$xYear=ppi($_GET['year'],$today['year']);
		foreach($res as $v){
			$x[]=array('year'=>$v['year'],'current'=>($v['year']==$xYear));
		}
		$x[count($x)-1]['last']=true;
		return $x ;
	}

	function check_data(&$upd,&$res){
		if(empty($res['news_date']))
			$upd['news_date']=date('Y/m/d H:i:s');
		else if(isset($upd['news_date'])){
			$dres=strtotime ($upd['news_date']);
			if( $dres===-1 || $dres===false)
				$upd['news_date']=date('Y/m/d H:i:s');
		}
		return true;
	}

	function do_newslist(){
		return $this->do_news('newslist',pps($_GET['id']));
	}

	function admin_news(){
		return
		smart_template(array(ADMIN_TPL,'theheader'),array(
		'header'=>$this->getPluginName(),
		'data'=>parent::admin_plugin()));
	}

	function search($s){
		$s=strtolower($s);
debug($s);
			//return array(page,item)
		$sql='SELECT *,LOCATE(?, LCASE(`the_text`)) as res, LOCATE(?, LCASE(`the_header`)) as res2
FROM ?_news
WHERE LOCATE(?,  LCASE(`the_text`))!=0 or LOCATE(?, LCASE(`the_header`))!=0
order by `id` LIMIT 50;';
		$res=$this->database->select($sql,$s,$s,$s,$s);
		if (empty($res)) return array();

		$result=array(); $lastparent=0;
		//debug($res);
		foreach($res as $v){
			if ($v['id']==$lastparent) continue;
			$lastparent=$v['id'];
			if(!empty($v['res']))
				$x=strip_tags(preg_replace('/^[^<]*>|<[^>]*$/','',substr($v['the_text'],max(0,$v['res']-200),200)));
			else
				$x=strip_tags($v['the_header']);

			$result[]=array(
				'tag'=>'<a href="?do=newslist">Новости</a>',
				'text'=>'<a href="?do=news&id="'.$v['id'].'">'.$x.'</a>'
			);
		}
		debug($result);
		return $result;
	}

	function do_create($killall=true){
		if(!$this->parent->has_rights(right_WRITE))
			return $this->parent->ffirst('_loginform');
		if($killall){
			$this->database->query('DROP TABLE IF EXISTS ?_news;');
		}
		$this->database->query("CREATE TABLE ?_news (
  `id` int(5) NOT NULL auto_increment,
  `news_date` date default NULL,
  `the_header` text,
  `the_text` text,
  `pic_small` text,
  `pic_big` text,
  PRIMARY KEY  (`id`)
);");
	}

	function news_b(){
		return $this->do_news('news_b');
	}
	
	function news_x(){
		return $this->do_news('news_x',0,1);
	}
	/**
	 * Выдать все новости после такой даты
	 *
	 * @param template_name $tpl
	 * @param date_str $date
	 */
	function getNewsAfter($date=0,$tpl='newslist'){
		$res= $this->database->select(
			'select * from ?_news '.
			'where `news_date`>=?',$date
		);
		$news=array();
		foreach($res as $v) {
			$news[]=$this->getData($v,$tpl);
		}
		return smart_template(array(NEWS_TPL,$tpl),array('news'=>$news));
	}
	/**
	 * Генерация раздела news
	 */
	
	
		function do_news($tpl='newslist',$id=0,$limit=0){
/**
 * вывести новости по дате
 * .$this->get_limit()
 */
		static $x=array(); if(isset($x[$tpl])) return $x[$tpl] ;
		$sm='';
		ml_plugin::setupmenu();
		if($tpl=='') $tpl='newslist';
		$cnt=0;
		$pages='';
		$where='';
		
		$sql='select * from ?_news';
		if($id){
			$res=$this->database->query($sql.' where `id`=?d',$id);
		} else {
			if ($tpl=='news_b' || $tpl=='news_x'){
				$res=$this->database->query($sql." ORDER BY `news_date` DESC, `id` DESC".
					' LIMIT '.ppi($limit,ppi($this->parent->getPar('news_per_list'),3)).';');
				$years = $this->years();
			} else {
				$y=ppi($_GET['year']);
				if(!empty($y))
					$where=' where YEAR(`news_date`)='.$y.' ';
				else
					$where='';
				$page=ppi($_GET['pg']);
				$res=$this->database->selectRow('select count(*)as cnt from ?_news'.$where);
				$cnt=$res['cnt'];
				$perpage=ppi($this->parent->getPar('news_per_page'),3);
				//$limit = ' LIMIT '.($page*$perpage).','.$perpage;
				$res=$this->database->select(
					$sql.$where." ORDER BY `news_date` DESC, `id` DESC".
					' LIMIT ?d,?d',$page*$perpage,$perpage);
				$pages=$this->parent->calc_Pages($cnt,$perpage,$page);
				$years = '';
			}
//*--*/ echo('/(?:\w+\W+){'.$this->get_limit(2).'}/');// LIMIT '.($page*$perpage).','.$perpage);
		}
		$news=array();
		$news2=array();
		$mas1=array();
		$i=1;
		if (!empty($res))
		foreach($res as $v) {
			$news[]=$this->getData($v,$tpl);
				$result=$i%2;
				if ($result===0) {$mas1[2]=$this->getData($v,$tpl); $mas1[1]['text_b2']=$mas1[2]['text_b']; $mas1[1]['id2']=$mas1[2]['id']; $mas1[1]['date2']=$mas1[2]['date']; $news2[]=$mas1[1];}  
				else {$mas1[1]=$this->getData($v,$tpl);}
				$i=$i+1;
		}
			$result=$i%2;
			if ($result===0) { $mas1[1]['text_b2']=''; $news2[]=$mas1[1];}
			
			
		$news[count($news)-1]['last']=true;
	//*--*/echo '<!-- ';print_r($pages);print_r(debug_backtrace());echo ' -->';
	//debug($pages);
	//debug($news);
	//debug(array(NEWS_TPL,''.$tpl));
		return $x[$tpl]=smart_template(array(NEWS_TPL,''.$tpl),array('news'=>$news,'news2'=>$news2,'pages'=>$pages,'years'=>$years));
	}

	function getData(&$v,$tpl){
		if($tpl=="news_b") {
			if(preg_match('/(?:\S+\s+){'.ppi($this->parent->getPar('news_words_at_page'),30).'}/',trim(strip_tags(str_replace('&nbsp;','',$v['the_text']))),$m)){
				$v['the_text']=trim($m[0]).' ...';
			}
		}
		$s=trim(pps($v['the_text']));
		if(preg_match('/(?:\S+\s+){15}/',trim(strip_tags($s)),$m)){//'.$vv['afilter'].'
			$s=trim($m[0]).' ...';
		}
	//	debug('"'.$s.'"');
		$text_x=$s;			
		//$title_x=htmlspecialchars(trim(strip_tags(str_replace('&nbsp;','',pps($v['the_header'])))));
		if(empty($title_x)) $title_x=$test_x;
		$curnews=array(
			'id'=>pps($v['id']),
			'date'=>pps($v['news_date']),
			'title'=>pps($v['the_header']),
			'text'=>pps($v['the_text']),
			'text_b'=>$text_x
		);
		$sm=toURL_sf(TO_URL($v['pic_small'],' '));
		//debug('xxx-"'.$sm.'" '.$v['pic_small'] );
		if(!empty($sm)){
			$pic= new xPic();
			$pic->v=array(
				'pic_comment'=>'',
				'item_url'=>$tpl=='news_b'?$this->parent->curl('do','id').'do=newslist&id='.$v['id']:'',
				'pic_small'=>$v['pic_small'],
				'pic_big'=>$v['pic_big']
			);
			$curnews['img']=$pic->getData();
			$curnews['img']['align']=xElement::aligns($v['align']);
		}
		return $curnews;
	}

	function get_parameters($par){
		$par['list'][]=array('sub'=>'Новости - дайджест новостей','title'=>'Количество слов выводимое в списке','name'=>'news_words_at_page');
		$par['list'][]=array('title'=>'Количество новостей в списке','name'=>'news_per_list');
		$par['list'][]=array('sub'=>'Новости - архив новостей','title'=>'Количество новостей на странице','name'=>'news_per_page');
		$par['list'][]=array('sub'=>'Новости - администрирование','title'=>'Количество новостей на странице','name'=>'words-perpage');
	}

}

class PhotoSizes extends plugin {

	function get_parameters($par){
		$par['list'][]=array('sub'=>'Маленькая фотография',
			'title'=>'Ширина  маленькой фотографии (в пикселях)','name'=>'pictute_xwidth');
		$par['list'][]=array('title'=>'Высота маленькой фотографии (в пикселях)','name'=>'pictute_xheight');
		$par['list'][]=array('sub'=>'Большая фотография','title'=>'Ширина большой фотографии (в пикселях)','name'=>'pictute_xxwidth');
		$par['list'][]=array('title'=>'Высота большой фотографии (в пикселях)','name'=>'pictute_xxheight');
		/*
		$par['list'][]=array('sub'=>'Оформление',
			'title'=>'Цвет заполнителя(0xFFFFFF - белый, -1 - прозрачный)','name'=>'pictute_background');
		$par['list'][]=array(
			'title'=>'Ширина по шаблону','name'=>'pictute_tpl_width');
		$par['list'][]=array(
			'title'=>'Высота по шаблону','name'=>'pictute_tpl_height');
		*/
		$par['list'][]=array('sub'=>'Рамка картинки',
			'title'=>'сверху-снизу (px)','name'=>'brd_x');
		$par['list'][]=array(
			'title'=>'слева-справа (px)','name'=>'brd_y');
		$par['list'][]=array(
			'title'=>'цвет: #FFFFFF, rgb(255,255,255), white','name'=>'brd_color');
	}

	function admin_PhotoSizes(){
		$this->parent->menu['head']=array('MAIN','_modules',$this->getPluginName(),get_class($this));
		return smart_template(array(ADMIN_TPL,'theheader'),array(
			'header'=>'Размеры фотографий',
			'descr'=>array('descr'=>'Фотографии будут автоматически вписываться в выставленные габариты.<br>Пропорции фотографии не изменяются'),
			'data'=>$this->parent->ffirst('do_siteparam', get_class ($this))
		));
	}

	function getPluginName(){
		return 	"Размер фотографий";
	}

}

class vocabular extends ml_plugin {

	function vocabular(&$parent){
		parent::ml_plugin($parent);
		parent::_init(
		array(
			'title'=>'Словарь терминов'
			,'fields'=>array(
					array('Слово','word','text_edit'),
					array('Значение','descr','text_edit'),
				)
			,'base'=>'_vocabular'
			,'orderbystr'=>' order by `word`'
		));
	}

	function admin_vocabular(){
		return
		smart_template(array(ADMIN_TPL,'theheader'),array(
		'header'=>$this->getPluginName(),
		'data'=>parent::admin_plugin()));
	}

	function do_create($killall=true){
		if(!$this->parent->has_rights(right_WRITE))
			return $this->parent->ffirst('_loginform');
		if($killall){
			$this->database->query('DROP TABLE IF EXISTS ?_vocabular;');
		}
		$this->database->query("CREATE TABLE ?_vocabular (
  `id` int(11) NOT NULL auto_increment,
  `word` varchar(255) NOT NULL default '',
  `letter` char(1) NOT NULL default '',
  `descr` text NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `word` (`word`)
);");
	}

	function do_vocabular(){
		// create list
		$letters=array('абвгдежзиклмнопрст','уфхцчэюя');
		$cur=ppi($_GET['ch']);
		$sql='SELECT DISTINCT LCASE(LEFT(`word`,1)) FROM ?_vocabular';
		$lets=$this->database->selectCol($sql);
		debug($lets);
		$str='';$start=0;$curlet='а';
		foreach(array('абвгдежзиклмнопрст','уфхцчэюя') as $v){
			$menu='';
			for($i=0; $i<strlen($v);$i++){
				if($start+$i!=$cur){
					$menu.='<td class="align_center" style="padding:8px 0 8px 16px;">';
				} else {
					$curlet=$v{$i};
					$menu.='<td style="padding:8px 8px 0 8px;"><span class="button">';
				}
				if (in_array($v{$i},$lets))
					$menu.='<a href="'.$this->parent->curl('ch').'ch='.($start+$i).'">'.$v{$i}.'</a>';
				else
					$menu.='<span>'.$v{$i}.'</span>';
				if($start+$i!=$cur){
					$menu.='</td>';
				} else {
					$menu.='</span></td>';
				}
			}
			$start+=strlen($v);
			$str.='<table width="10%"><tr>'.$menu.'</tr></table>';
		}
		$sql='SELECT * from ?_vocabular where LCASE(LEFT(`word`,1))=?';
		$res=$this->database->select($sql,$curlet);

		$str='<div style="padding:25px 0 30px 0;margin-bottom:30px;" class="borderdn menu link align_center">'.$str.'</div>';
		$str.='<table class="tahoma">';
		foreach($res as $v){
			$str.='<tr><td style="padding:20px;"><img src="img/arr_red.gif"></td>'.
				'<td class="blue" style="padding:15px 0;">'.$v['word'].'</td><td  style="padding:15px;">'.$v['descr'].'</td></tr>'.
				'<tr><td colspan=3 class="borderdn" style="height:20px;">&nbsp;</td></tr>';
		}
		return $str.'</table>';
	}
}

?>