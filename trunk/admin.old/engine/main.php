<?php

// для пущей точности... Единственная сущьность, остающаяся глобальной
if(!defined('INTERNAL')){
	header('Content-type: text/html; charset=windows-1251');
//	include_once('htmlopt.php');ob_start('html_optimize');
}

// при входе в админку чистим кэш
include_once('FileCache.php');
$cache = new FileCache(array(
	'is_enabled' => false,
	'dir'   => '../cache/',
));
$cache->clean();

define ('IS_ADMIN', true);
require_once('templater.php');

include_once('engine.php');
include_once('syspar.php');
include_once('rights.php');
include_once('html.class.php');
include_once('sitemap.php');
include_once('news.php');
include_once('q_a.php');
require_once('classes.php');
//require_once('katalog.php');
require_once('users.php');
require_once('fileman.php');
require_once('sendmail.php');
//require_once('rss.php');
include_once('project_core.php');

//include_once('db_session.php');

//include_once('db_session.php');
//
include_once 'compiler.class.php';
template_compiler::checktpl();
class ajax extends plugin
{
	
	function do_ajax_sendmail(){
		if(!$this->has_rights(right_WRITE))
			return "have no right? Sorry pal...";

		$to=pps($_POST['to']);
		$cc1=pps($_POST['cc1']);
		$cc2=pps($_POST['cc2']);
		$cc3=pps($_POST['cc3']);
		$cc4=pps($_POST['cc4']);
		$subj=pps($_POST['subj']);
		$from=pps($_POST['from']);
		$fio=pps($_POST['fio']);
		$body=pps($_POST['body']);
	// TODO: помирить этот код с функциональностьтью loadClass
		$mail=new html_mime_mail(
			//html_mime_mail::mail_header('From: ',$from,$fio).
			html_mime_mail::mail_header('Reply-To: ',$from,$fio).
			html_mime_mail::mail_header('Cc: ',$from,$fio) .
		    pp($cc1,'Cc: ',"\r\n") .
			pp($cc2,'Cc: ',"\r\n") .
			pp($cc3,'Cc: ',"\r\n") .
			pp($to,'Reply-To: ',"\r\n").
			pp(gmdate("j M y H:m"),'Date: '," GMT\r\n").
			'X-Mailer: PHP/' . phpversion()
		);
		$mail->add_html($body);
		$mail->build_message('win');
		if($mail->send(  $to, $subj)){
			return "Ok!";
		}
        else {
        	return "fault!";
        }
	}
	
	function do_ajax_hide(){
		if(pps($_POST['x'])!='menu' || empty($_POST['id']))
			$this->parent->error('Ошибка в параметрах!!!!!');
		else {
			$res=$this->parent->nodeGet($this->parent->nodeScanId($_POST['id']));
			if(isset($res[0]['skipit']))
				unset($res[0]['skipit']);
			else	
				$res[0]['skipit']=1;
			$this->parent->writeRecord($res[0]);
		}
		return ' ';
	}
	
	function do_ajax_csv_export(){
		global $basename;
		if(DIRECTORY_SEPARATOR=='\\')
			$file=str_replace('/',DIRECTORY_SEPARATOR,$_POST['file']);
		else	
			$file=$_POST['file'];
		if(!@is_readable($file)){
			if(is_readable($_SERVER['DOCUMENT_ROOT'].$file)){
				$file=$_SERVER['DOCUMENT_ROOT'].$file;
			} else {
				$file=preg_replace('~^.*?uploaded[\\\\|/]?~','',$file);
				$file=TMP_DIR.$file;
			}
		} 	

		/*if($basename=='coha7862')
			return  $this->parent->export('csv','csv_import2',$file,$_POST['id'],ppi($_POST['startfrom']));
		else */
		return  $this->parent->export('csv','csv_import',$file,$_POST['id'],ppi($_POST['startfrom']));
	}
	
	function do_ajax_get_csv_list(){
		$s='<br>';
		foreach(glob(TMP_DIR.'*.*') as $v){
			if(preg_match('/\.csv$/i',$v))
			$s.='<a class="filename" href="'.toUrl($v).'">'
				.basename($v).'</a>';
		}
		return $s.'<br>';
	}
	
	function do_ajax_csv_report(){
		$report=$this->parent->readRecord(array('record'=>'report','name'=>'CSV Export'));
		$this->parent->ajaxdata=$report;
		return $report['report'];
	}
	
	function do_ajax_SelectMenuItem(){
		if(empty($_POST['x']) || empty($_POST['id']))
			$this->parent->error('Ошибка в параметрах!!!!!');
		else {
			if (pps($_POST['x'])=='menu'){
				$res=$this->parent->nodeGet($this->parent->nodeScanId($_POST['id']));
				if(isset($res[0]['selected']))
					unset($res[0]['selected']);
				else	
					$res[0]['selected']=1;
				$this->parent->writeRecord($res[0]);
			}
		}
		return ' ';
	}
	
	function splitCell($v){
		// сплитим по TD|TH
		$cell=array();
		$reg='~(.*?)<(?:td|th)([^>]*)>~si';
		$xcell=null;
		while (preg_match($reg,$v,$m)){
			$y=scanPar($m[2],array('colspan'=>1,'rowspan'=>2));
			if(!is_null($xcell))
				$xcell['text']=$m[1];
			$cell[]=array('colspan'=>ppi($y[1],1),'rowspan'=>ppi($y[2],1));
			$xcell=&$cell[count($cell)-1];
			$v=substr($v,strlen($m[0]));
		}
		if(!is_null($xcell))
			$xcell['text']=$v;
		return $cell;	
	}
	
	/**
	 * функция, выковыривающая таблицу из входящего потока данный и 
	 * вставляющая ее в "клипборд"
	 *
	 * @return unknown
	 */
	function do_ajax_clear_text(){
		// парсер таблиц
		if(isset($_REQUEST['txt'])) 
			$txt=pps($_REQUEST['txt']); 
		else 
			$txt='';
		if(detectUTF8($txt)){
				$txt=iconv('utf-8','cp1251//IGNORE',$txt);
		}
		if(preg_match('~^(.*)<table.*?>(.*?)</table\s*>(.*)$~is',$txt,$m)){
			$this->parent->ajaxdata['txt']=$m[1].$m[3];//'Hello! world';
			$contents=$txt;
			$data=array();
			$level=1;
			$data[]=array('type'=>type_TABLE,'level'=>$level++);
			$rowname='Header';
			$colspan=1;
			$rowspan=array();
			// выкидываем тег table и закрывающие /tr
			$contents=preg_replace('~^.*?<table[^>]*>|</tbody[^>]*>|</t[dhr][^>]*>|</table[^>]*>.*?$~is','',$contents);
			$contents=preg_replace('~^.*?<t[dh]~si','<td',$contents);
			// сплитим по открывающим <tr>
			$contents=preg_split('~<tr[^>]*>~i',$contents);
			if(!empty($contents)) {
				$cells=$this->splitCell(current($contents));
			// первый элемент
				$data[]=array('type'=>type_ROW,'name'=>$rowname,'level'=>$level++);
				foreach($cells as $vv){
					$d=array('text'=>trim($vv['text']),'type'=>type_CELL,'level'=>$level);
					if($vv['rowspan']>1) $d['rowspan']=$vv['rowspan'];
					if($vv['colspan']>1) $d['colspan']=$vv['colspan'];
					$data[]=$d;
					$rowspan[]=$vv['rowspan'];
					if (($xx=$vv['colspan'])>1){
						while(--$xx){
							$data[]=array('name'=>'empty','type'=>type_EMPTYCELL,'level'=>$level);
							$rowspan[]=$vv['rowspan'];
						}
					}
				}
				$level--;
				while($v=next($contents)){
					$data[]=array('type'=>type_ROW,'name'=>$rowname,'level'=>$level++);
					$cells=$this->splitCell(current($contents));
					$vv=current($cells);
					for($row=0;$row<count($rowspan);$row++){
						if($rowspan[$row]>1){
							$rowspan[$row]--;
							$data[]=array('name'=>'empty','type'=>type_EMPTYCELL,'level'=>$level);
						} else if(!empty($vv)){
							$d=array('text'=>trim($vv['text']),'type'=>type_CELL,'level'=>$level);
							if($vv['rowspan']>1) $d['rowspan']=$vv['rowspan'];
							if($vv['colspan']>1) $d['colspan']=$vv['colspan'];
							$data[]=$d;
							$rowspan[$row]=$vv['rowspan'];
							if (($xx=$vv['colspan'])>1){
								while(--$xx){
									$data[]=array('name'=>'empty','type'=>type_EMPTYCELL,'level'=>$level);
									$rowspan[++$row]=$vv['rowspan'];
								}
							}
							$vv=next($cells);
						} else 
							$data[]=array('name'=>'empty','type'=>type_EMPTYCELL,'level'=>$level);
					}
					$level--;
				}
			}
			$_SESSION['clipboard']=array('item'=>'page','data'=>$data);

			return 'таблицы сохранены! Можете воспользоваться командой "Вставка элемента"!';
		} else {
			$this->parent->ajaxdata['txt']=$txt;
			return 'Текст не нуждается в конвертировании таблиц.';
		}

		return ' ';
	}

	function do_ajax_get_contents(){
		$this->parent->sessionstart();
		// if has_right
		$id=pps($_GET['id'])  ;  // ID поля для опроса
		if(empty($id)) {
		};
		//return $_GET['var'];
		if(preg_match('/^(pg|id|kt|ar|nw|qa)_(.+)$/',$id,$m)){
			if($m[1]=='ar'){
				// articles
				$key=$this->parent->export('articles','getContext',$m[2]);
			} elseif($m[1]=='nw'){
				// articles
				$key=$this->parent->export('news','getContext',$m[2]);
			} elseif($m[1]=='qa'){
				// articles
				$key=$this->parent->export('qa','getContext',$m[2]);
			} elseif($m[1]=='kt'){
				// articles
				$key=$this->parent->export('katalog','getContext',$m[2]);
			} else {
				$res=$this->parent->nodeGet($this->parent->nodeScanId($m[2]));
				$i=0;
				if(!($element=readElement($res,$i))) {
					echo "Нету элемента!"; return ;
				};
				$key=array();
				$element->serialize($key);
			}
			if(!isset($key[pps($_GET['var'])])) {
				return ' ';
			};
		} else {
			echo "некорректный запрос!"; return ;
		}
		return '#'.pps($key[pps($_GET['var'])]);
	}
	
	function do_ajax_test1(){
		$this->parent->sessionstart();
		if(!$this->parent->has_rights(right_WRITE)){
			$this->parent->error('Нужно авторизоваться!!!!!');
			return ' ';
		}
		if(empty($_REQUEST['x']) || !isset($_REQUEST['id']))
			$this->parent->error('Ошибка в параметрах!!!!!');
$tpl=array("alpina58.gif",
"audi57.gif",
"bmw51.gif",
"cadillac53.gif",
"chevr49.gif",
"chrysler48.gif",
"ferrari5788.gif",
"fiat43.gif",
"ford39.gif",
"hyundai37.gif",
"infiniti338.gif",
"jeep36.gif",
"landrover29.gif",
"mazda1971.gif",
"moskvich25.gif",
"oldsmobile18.gif",
"opel223.gif",
"renault17.gif",
"rover1265.gif",
"rr1366.gif",
"saab1467.gif",
"skoda10.gif",
"toyota8.gif",
"uaz6.gif",
"zender2.gif",
"acura.jpg",
"am.jpg",
"ar.jpg",
"azlk.jpg",
"bently.jpg",
"buick.jpg",
"citroen.jpg",
"dacia.jpg",
"daewoo.jpg",
"daihatsu.jpg",
"daimler.jpg",
"dodge.jpg",
"doninvest.jpg",
"gaz.jpg",
"gm.jpg",
"gmc.jpg",
"hammer.jpg",
"honda.jpg",
"ij.jpg",
"isuzu.jpg",
"kamaz.jpg",
"kia.jpg",
"lamborgini.jpg",
"lancia.jpg",
"lexus.jpg",
"lincoln.jpg",
"lotus.jpg",
"maibach.jpg",
"maserati.jpg",
"mb.jpg",
"mercury.jpg",
"mg.jpg",
"mini.jpg",
"mitsubishi.jpg",
"morgan.jpg",
"mustang26.jpg",
"peugeot.jpg",
"plymouth.jpg",
"pontiac.jpg",
"porsche.jpg",
"proton.jpg",
"saturn.jpg",
"seat.jpg",
"seaz.jpg",
"smart.jpg",
"ssang_yong.jpg",
"subaru.jpg",
"suzuki.jpg",
"tata.jpg",
"vaz.jpg",
"volkswagen.jpg",
"volvo.jpg",
"zaz.jpg",
"jaguar.png",
"nissan.png");
		$x=array();
		for($i=0;$i<12;$i++){
			$y=	array ('level' => 1,'url' => '',
			'page' => array ( 
				array ('level' => 0,'type' => 10 ),
				array ('level' => 1, 'item_text' =>
					'Текст какой-то. Текст какой-то. Текст какой-то. Текст какой-то. Текст какой-то. Текст какой-то. Текст какой-то. Текст какой-то.',
					'type' => 16 ),
					array ( 'level' => 2, 'bheight' => 600,'bwidth' => 800,'name' => 'picture',
						'pic_big' => '/wizantija/uploaded/016_800x600.jpg',
						'pic_small' => '/wizantija/uploaded/016_100x80.jpg',
						'sheight' => 75,
						'swidth' => 100,
						'type' => 11 
					) 
				) 
			);
			$y['name']=preg_replace('/\.\w*$/','',$tpl[rand(0,count($tpl))]);
			//$y['name']=$y['url'];
			$x[]=$y;
		}	
		debug($x);
		$pkey=0;
		ajax::insertMenu($_REQUEST['id'],$x[0]['level'],$x,$pkey);
			
		return ' ';
	}
	
	function do_ajax_copyCTA(){
		$this->parent->sessionstart();
		if(!$this->parent->has_rights(right_WRITE)){
			$this->parent->error('Нужно авторизоваться!!!!!');
			return ' ';
		}
		if(pps($_POST['x'])!='menu' || empty($_POST['id']))
			$this->parent->error('Ошибка в параметрах!!!!!');

		return $this->parent->export('config','do_copy_menu');
				
	}
	

	function do_ajax_move(){
		$this->parent->sessionstart();
		if(!$this->parent->has_rights(right_WRITE)){
			$this->parent->error('Нужно авторизоваться!!!!!');
			return ' ';
		}
		if(empty($_POST['x']) || empty($_POST['id']))
			$this->parent->error('Ошибка в параметрах!!!!!');

		if(pps($_POST['obj'])=='Cells'){
			xTable::moveItem($_POST['id'],$_POST['obj'],$_POST['disp']);
		} else if (pps($_POST['obj'])=='Rows'){
			xTable::moveItem($_POST['id'],$_POST['obj'],$_POST['disp']);
		} else {
			$node=$this->parent->nodeScanId($_POST['id']);
			if(pps($_POST['disp'])=='-1')
				$this->parent->nodeMoveUp($node);
			else
				$this->parent->nodeMoveDn($node);
		}
		return ' ';
	}	
	
	function do_ajax_gallery_img(){
		//print_r($_POST);
		preg_match('/\d+/',pps($_GET['id']),$m);
		if(empty($m[0]))return '';
		//print_r($m);
		$items =$this->parent->nodeGet($this->parent->nodeScanId($m[0]));
		$i=0;
		$element=readElement($items,$i);
		if(!empty($element)){
			$key=array('name'=>'picture','type'=>type_PIC);
			if(isset($_POST['small'])) $key['pic_small']=toUrl_sf($_POST['small']);
			if(isset($_POST['big'])) $key['pic_big']=toUrl_sf($_POST['big']);
			$element->addElement($key);
		}
		return ' ';
	}

	function do_file_uploader (){
		if(!$this->parent->has_rights(right_WRITE)){
			$this->parent->error('Нужно авторизоваться!!!!!');
			return ' ';
		}
		$this->parent->sessionstart();
		$this->parent->tpl=array(ELEMENTS_TPL,'ajax');
		ob_start();
		$result='';
		$this->parent->ajaxdata=array();
		$form=new form('form',
				//	array(CNT_INPUT,'MAX_FILE_SIZE'),
					array(CNT_INPUT,'xaction'),
					array(CNT_INPUT,'file','file')
              );
        if(pps($_POST['xurl'])=='avatar')
        	$form->upload_dir=ROOT_PATH.DIRECTORY_SEPARATOR.'avatar'.DIRECTORY_SEPARATOR;      
       // print_r($_FILES); echo $form->upload_dir;
       // print_r($_POST);
        if($form->handle()){
        	//echo $form->upload_dir;
        	//print_r($form->files);
        	ini_set('memory_limit','128M');
			//echo'Hello!'; 
			//print_r($_FILES) ;
        	$x=array_keys($form->files);
			if(empty($x)) return 'не удалось загрузить файлы!';
			$x=pps($x[0]);
			if(basename($x)!=$form->files[$x]){
				@unlink($x);
				$x=dirname($x).'/'.$form->files[$x];
			}
			//print_r($_POST);
        	$w=ppi($this->parent->getPar('pictute_xwidth'),100);
			$h=ppi($this->parent->getPar('pictute_xheight'),80);
			$xw=ppi($this->parent->getPar('pictute_xxwidth'),800);
			$xh=ppi($this->parent->getPar('pictute_xxheight'),600);
			
			// Загрзка фотографий с размерами, установленными в каждой
			// отдельной галерее
			if(isset($_POST['xwidth']) && $_POST['xwidth'] != '')
				$w=ppi($_POST['xwidth'],$w);
			if(isset($_POST['xheight']) && $_POST['xheight'] != '')
				$h=ppi($_POST['xheight'],$h);
			if(isset($_POST['xxwidth']) && $_POST['xxwidth'] != '')
				$xw=ppi($_POST['xxwidth'],$xw);
			if(isset($_POST['xxheight']) && $_POST['xxheight'] != '')
				$xh=ppi($_POST['xxheight'],$xh);	
			
			$bg=$this->parent->getPar('pictute_background',-1);
			$width=0;
			$result=false;
			if(pps($form->var['xaction'])=='small' || pps($form->var['xaction'])=='both'){
				list($width, $height, $type, $attr) = @getimagesize($x);
				$y='';
				if(pps($width) && ($w<$width || $h<$height) ){
   					require_once('pic.inc.php');
   					$y=preg_replace('~\s*(\.[^\.]*)$~','_'.$w.'x'.$h.'\1',$x);
					$result=img_resize($x,$y,$w,$h,$bg,70);
				}
				if(!empty($result))
					$this->parent->ajaxdata['small']=toUrl(pps($y,$x));
				else	
					$this->parent->ajaxdata['small']=toUrl($x);
			}
			$result=false;
        	if(pps($form->var['xaction'])=='big' || pps($form->var['xaction'])=='both'){
        		if(empty($width))
					list($width, $height, $type, $attr) = @getimagesize($x);
				$y='';
				if(pps($width) && ($xw<$width || $xh<$height)){
   					require_once('pic.inc.php');
   					$y=preg_replace('~\s*(\.[^\.]*)$~','_'.$xw.'x'.$xh.'\1',$x);
					$result=img_resize($x,$y,$xw,$xh,$bg,70);
				}
				if(!empty($result))
					$this->parent->ajaxdata['big']=toUrl(pps($y,$x));
				else	
					$this->parent->ajaxdata['big']=toUrl($x);
			}
        	if(empty($width))
				list($width, $height, $type, $attr) = @getimagesize($x);
			if(!empty($y)) $x=$y;
			//print_r($this->parent->ajaxdata);
			$form->files=array();
			$form->storevalues();
			$result= toUrl_sf(toUrl($x));
        } else {
        	if(!empty($_POST))
				$this->parent->error('wrong!!');
        }
        $this->parent->export('fileman','do_read',true);
		unset($_SESSION['store_picture'],$_SESSION['store_files']);
        $result=array('data'=>$result);
		if($this->parent->par['error'])
			$result['error']=trim($this->parent->par['error']);
		if(!empty($this->parent->ajaxdata))
				$result['result']=$this->parent->ajaxdata;
		if($x=ob_get_contents()){
			$result['debug']=trim($x);
		};
		ob_end_clean();
		return '<script type="text/javascript">
			top.upload_OnSuccess('.php2js($result).');
		</script>'."\n//";
	}

	function do_ajax_contextmenu(){
		//$this->parent->ajaxdata[];
		if(!empty($_POST['x']))
		foreach($_POST['x'] as $v){
			if(preg_match('/^pg_(\d+)$/',$v,$m)){
				$res=$this->parent->nodeGet($this->parent->nodeScanId($m[1]));
				//print_r($res);
				$i=0;
				if(!($element=readElement($res,$i))) {
					$this->parent->ajaxdata[$v]=' '; return ;
				};

				$this->parent->ajaxdata[$v]=
					$element->getContextMenu();
			} else {
				$this->parent->ajaxdata[$v]=' ';
			}
		}

		return ' ';// print_r($_POST,true);
	}

	function do_ajax_get_menu_list(){
		if(!$this->parent->has_rights(right_WRITE)){
			$this->parent->error('Нужно авторизоваться!!!!!');
			return ' ';
		}
		$x=$this->parent->export('sitemap','getSiteMap');
		$res='';
		foreach(array('main'=>'Основные разделы','catalogue'=>'Каталог') as $k=>$v){
			if($y=$x->scan($k)){
				$res.=smart_template(array(ELEMENTS_TPL,'sitemap'),
				array('data'=>$y->getUlLi(1000,false,1),'menu'=>$v));
			}
		}
		return $res;		
	}

	function do_ajax_get_picture_list(){
		if(!$this->parent->has_rights(right_WRITE)){
			$this->parent->error('Нужно авторизоваться!!!!!');
			return ' ';
		}
		$s='<a class="filename" href="javascript:;">&lt;&lt;убрать&gt;&gt;</a>';
		foreach(glob(TMP_DIR.'*.*') as $v){
			if(preg_match('/\.png$|\.jpe?g$|.gif$/i',$v))
			$s.='<a class="filename" href="'.toUrl($v).'">'
				.basename($v).'</a>';
		}
		return $s;
	}

	function do_ajax_get_file_list(){
		$s='';
		foreach(glob(TMP_DIR.'*.*') as $v){
			if(!preg_match('/\.png$|\.jpe?g$|.gif$/i',$v))
			$s.='<a class="filename" href="'.toUrl($v).'">'
				.basename($v).'</a>';
		}
		return $s;
	}

	function do_ajax_copyItem(){
		if(!$this->parent->has_rights(right_WRITE)){
			$this->parent->error('Нужно авторизоваться!!!!!');
			return ' ';
		}
		if(empty($_POST['x']) || empty($_POST['id']))
			$this->parent->error('Ошибка в параметрах!!!!!');
		else if($_POST['x']=='menu') {
			$items=$this->parent->nodeGet($this->parent->node($_POST['id']));
			if(!empty($items))
			foreach($items as $k=>$v){
				if(!empty($v['page'])){
					//echo $v['page'];
					$items[$k]['page']=$this->parent->nodeGet($v['page']);
				}
			}
			$_SESSION['clipboard']=array('item'=>'menu','data'=>$items);
		}
		else if($_POST['x']=='page') {
			$items=$this->parent->nodeGet($this->parent->node($_POST['id']));
			$_SESSION['clipboard']=array('item'=>'page','data'=>$items);
		}
		return ' ';
	}

	function do_ajax_pasteItem(){
		global $engine;
		if(!$this->parent->has_rights(right_WRITE)){
			$this->parent->error('Нужно авторизоваться!!!!!');
			return ' ';
		}
        if(empty($_REQUEST['x']) || empty($_REQUEST['id']))
			$this->parent->error('Ошибка в параметрах(0)!!!!!');
		else {
			if( !empty($_SESSION['clipboard'])
				&&
				pps($_SESSION['clipboard']['item'])=='menu'
			){
				$x=&$_SESSION['clipboard']['data'];
				$pkey=0;
				if (is_array($x) && count($x)){
					ajax::insertMenu($engine->node(pps($_REQUEST['id'])),$x[0]['level'],$x,$pkey);
				}
			} else if( !empty($_SESSION['clipboard'])
				&&
				pps($_SESSION['clipboard']['item'])=='page'
			){
				$id=$_REQUEST['id'];	
				$items=$this->parent->nodeGet($this->parent->node($id));$i=0;
				$item=readElement($items,$i);//print_r($item);
				if(!empty($item))
					$item->pasteData($_SESSION['clipboard']['data']);
				$tmp=$this->parent->nodeGetBackPath($this->parent->node($id));
				//debug($tmp);
				$this->parent->cache('anchor',$tmp[0]['node']);
			}
		}
		return ' ';
	}
	
	function throwArray($level,&$keyid,&$keys,&$NS){
		global $engine;
		while(count($keys)>$keyid && $keys[$keyid]['level']>=$level){
			if($keys[$keyid]['level']>$level)
				ajax::throwArray($level+1,$keyid,$keys,$NS);
			if(count($keys)<=$keyid || $keys[$keyid]['level']<$level)
				return ;
			if($keys[$keyid]['level']!=$level) 
				continue; 

			$_ns=array('level'=>$level);
			$key=$keys[$keyid];
			if (empty($key['name']))
					unset($key['name']);
				else		
					$_ns['name']=$key['name'];
			unset($key['node'],$key['level'],$key['childs'],$key['id']);
			//echo '!'.ppi($key['type']);	
			if(ppi($key['type'])==type_KATALOG ){
				$key['cat_type']='';
				//if( !empty($keys[$keyid]['id'])){
					//echo($keys[$keyid]['id']);
					//$key['id']=$keys[$keyid]['id'];
				//}	
			}	
			//echo $id.',';print_r($key); return ' ';
			if(!in_array(ppi($key['type']),array(type_MENU,type_HIDDENMENU ))){
				$_ns['page']=$engine->writeRecord($key);
				$NS[]=$_ns;
			}
			$keyid++;
		}
	}
//*
	function insertPage($id,$level,&$keys,&$keyid){
		global $engine;
		$level=$keys[$keyid]['level']; $nid=0;
		//$fields=array('name','page','text','url','type','align');
		$NS =array();
		ajax::throwArray($level,$keyid,$keys,$NS);
		// собираем массив для монтажа NS
		$engine->nodeAddArray($id,$NS);
		return $NS[0]['id'];
	}

	function insertMenu($id,$level,&$keys,&$keyid){
		global $engine;
		$fields=array('name','descr','url','type','razdel');
		while(count($keys)>$keyid){
			if($keys[$keyid]['level']>$level)
				ajax::insertMenu($nid,$keys[$keyid]['level'],$keys,$keyid);
			if(count($keys)<=$keyid)
				return;
			if($keys[$keyid]['level']<$level)
				return;
			// insertPage
			if($keys[$keyid]['level']!=$level) continue;

			$key=array('page'=>0);
			if(isset($keys[$keyid]['page'])){
				$x=&$keys[$keyid]['page'];
				$pkey=0;
				if (is_array($x) && count($x))
					$key['page']=ajax::insertPage(0,$x[0]['level'],$x,$pkey);
			}
			foreach($fields as $v){
				$z=pp($keys[$keyid][$v]);
				if(!empty($z))	$key[$v]=$z;
			}
			$nid=$engine->nodeAdd($id,$key);
			$keyid++;
		}
	}

	function do_ajax_renameItem(){
		if(!$this->parent->has_rights(right_WRITE)){
			$this->parent->error('Нужно авторизоваться!!!!!');
			return ' ';
		}
		if(empty($_POST['x']) || empty($_POST['id']))
			$this->parent->error('Ошибка в параметрах!!!!!');
		else {
            $name=$_POST['name'];
			if(detectUTF8($_POST['name'])){
				$name=iconv('utf-8','cp1251//IGNORE',$name);
			}
             if (pps($_POST['x'])=='menu'){
				$nname=str_replace(array('<','>'),array('&lt;','&gt;'),pps($name,'unknown'));
				xMenuLine::rename($_POST['id'],$name);
			}
		}
		return ' ';
	}

	function do_ajax_newItem(){
		$_POST['obj']=pps($_POST['obj']);
		if(!$this->parent->has_rights(right_WRITE)){
			$this->parent->error('Нужно авторизоваться!!!!!');
			return ' ';
		}
		if(empty($_POST['x']) || empty($_POST['id']))
			$this->parent->error('Ошибка в параметрах!!!!! '.print_r($_POST,true));
		else {
			if (pps($_POST['x'])=='menu'){
				if(detectUTF8($_POST['name'])){
					$_POST['name']=iconv('utf-8','cp1251//IGNORE',$_POST['name']);
				}
				$nname=str_replace(array('<','>'),array('&lt;','&gt;'),pps($_POST['name'],'unknown'));
				$m=split('~',$_POST['name']);
				xMenuLine::newmenu($_POST['id'],$m[0],pps($m[1]),
					(isset($m[2])?type_HIDDENMENU:0));
			} else if (pps($_POST['x'])=='page'){
				if(($_POST['obj']=='Cells')||($_POST['obj']=='Rows')){
					xTable::newCellRow(ppi($_POST['id']),$_POST['obj']);
				} else {
					$items=$this->parent->nodeGet($this->parent->node($_POST['id']));$i=0;
					$item=readElement($items,$i);
					if(!empty($item))
						$item->newData($_POST['obj']);
				}
			}
		}
		return ' ';
	}

	function do_ajax_delItem(){
		if(!$this->parent->has_rights(right_WRITE)){
			$this->parent->error('Нужно авторизоваться!!!!!');
			return ' ';
		}
		$_POST['obj']=pps($_POST['obj']);
			
		if(empty($_POST['x']) || empty($_POST['id']))
			$this->parent->error('Ошибка в параметрах!!!!!');
		else {
			if (pps($_POST['x'])=='menu'){
				//debug('delete menu:'.$_POST['id']);
				xMenuLine::deleteLine($_POST['id']);
			} elseif (pps($_POST['x'])=='page') {
				//echo 'page:'.$_POST['id'];
				if(($_POST['obj']=='Cells')||($_POST['obj']=='Rows')){
					xTable::delCellRow($_POST['id'],$_POST['obj']);
				} else {
					//print($_POST['id']."!");
					$this->parent->nodeDelete($this->parent->node($_POST['id']));
				}
			}
		}
		//$this->parent->export('katalog','do_clear');
		return ' ';
	}

}

class config extends plugin {
	
	function replaceUrlExact($x,$y){
		$query='update '.TAB_PREF.'_flesh '.
			'set `tval`=REPLACE(`tval`,"'.mysql_real_escape_string($x).'","'.mysql_real_escape_string($y).
			'") where (`name`="item_url" or `name`="url" or `name`="item_text") and `tval` LIKE "%'.mysql_real_escape_string($x).'%";';
		if (!mysql_query($query)) {
		   echo 'Invalid query: ' . mysql_error() . "<br>\n".'<br>Whole query: ' . $query;
		}
	}
	
	function convert_links() {
		// конвертировать ссылки, вставленные с помощью COPY;
		$links= $this->parent->readRecords('',6000,10000,
			'select u.id,u.name, if(isNull(u.ival),if(isNull(u.tval),u.sval,u.tval),u.ival) as `value` from '
				.TAB_PREF.'_flesh as u LEFT JOIN '.TAB_PREF.'_flesh AS u1 ON u.id = u1.id '.
				'where u1.name="razdel"  '
			);
		//debug($links);	
		$x=$this->parent->getPar('convert_links');
		$this->parent->delPar('convert_links');	
		foreach($links as $v){
			//debug($x.'/?do=menu&id='.trim($v['razdel']));
			$this->replaceUrlExact($x.'/?do=menu&id='.trim($v['razdel']),'?do=menu&id='.$v['id']);
		}
		$this->database->select('delete from ?_flesh where `name`="razdel"');	
	}
	
	function convert_url(&$x){
		static $names=array('url','item_url','pic_small','pic_big');
			
		if(isset($x['id'])){
			$x['razdel']=$x['id'];
		}
		if(isset($x['page'])){
			//echo 'page!';
						
			for($i=0;$i<count($x['page']);$i++){
				$w=&$x['page'][$i];
				if(isset($w['item_text']))
					$w['item_text']=preg_replace('/([\'"])\?do=/','\1'.$this->url.$this->index.'/?do=',$w['item_text']);
				foreach($names as $vv){
					if(isset($w[$vv])){
						if (preg_match('~uploaded~i',$w[$vv])){
							$w[$vv]=$this->url.$w[$vv];
							//echo 'uploaded '.$vv;
						}elseif(preg_match('/^\?do=.*?id=(\d+)/',$w[$vv],$m)){
							//$this->links[]=array($w['id'],$vv,$m[1]);
							$w[$vv]=$this->url.$this->index.'/'.$w[$vv];
						} 
					}
				}
			} 
		}
		for($i=0;isset($x[$i]);$i++){
			$this->convert_url($x[$i]);
		}
	}
	/**
	 * Скопировать раздел ID на сайт
	 * требуется curl
	 *
	 * @param string $site
	 * @param integer $id
	 * @param integer $to 
	 */
	function do_copy(){
		if($_SERVER['REQUEST_METHOD']=='POST'){
			if(!isset($_POST['to'])){
				$this->parent->error('Нет параметра TO');
				return '';
			}
			$this->url=$_POST['url'];
			$this->index=$_POST['index'];
			$this->parent->setPar('convert_links',$this->url.$this->index);
			if(!isset($_POST['res'])){
				$this->parent->error('Нет параметра RES');
				return '';
			}
			//echo($_POST['res']);
			$id=$this->parent->nodeScanId(pps($_REQUEST['to'],'catalog'));
			if(empty($id)){
				$this->parent->error('Не найден принимающий узел');
				return ' ';
			}
			//print_r($_SERVER);
			$x=unserialize(gzuncompress(base64_decode($_POST['res'])));
			$this->convert_url($x);
			//print_r($x);
			$sm=$this->parent->export('sitemap','getSiteMap',$_REQUEST['to']);
			//var_dump($x[0]['name']);
			$todel=array();
			if(!empty($sm->el))
			foreach($sm->el as $k=>$v){
				//var_dump($v->v['name']);
				if(trim($v->v['name'])==trim($x[0]['name']))	
					$todel[]=$v->v['id'];	
			}
			//print_r($todel);
			if(!empty($todel)){
				foreach($todel as $v){
					xMenuLine::deleteLine($v);
				}
			}
			$pkey=0;
			if (is_array($x) && count($x)){
				$ajax=$this->parent->export('ajax');
				$ajax->insertMenu($this->parent->node($_REQUEST['to']),$x[0]['level'],$x,$pkey);
			}
			
			$from=$this->index;
			$to=$this->url.$this->index;
			$sqls=array(
				"update ?_flesh set tval=replace(tval,'".$from."/uploaded','".$to."/uploaded') where tval like '".$from."/uploaded%';",
				"update ?_flesh set sval=replace(sval,'".$from."/uploaded','".$to."/uploaded') where sval like '".$from."/uploaded%';",
			);
			foreach($sqls as $sql)
				$this->database->select($sql);
			
			return 'Ok!';
		}		
	}
	
	function do_copy_menu(){
		//
		$site=pps($_REQUEST['site'],$this->parent->getPar('site_to_copy'));
		if( !empty($_REQUEST['id']) )
			$items=$this->parent->nodeGet($this->parent->node($_REQUEST['id']));
		if(empty($items))
			return 'Empty';
		foreach($items as $k=>$v){
			if(!empty($v['page'])){
				$items[$k]['page']=$this->parent->nodeGet($v['page']);
			}
		}
		$result='';//$res;
		
		$to=pps($_REQUEST['to'],$this->parent->getPar('site_to'));
		if(empty($to))
			return 'нет параметра TO';
		//echo 'to='.$to;	
		// create a new cURL resource
		$ch = curl_init();
		// set URL and other appropriate options
		curl_setopt($ch, CURLOPT_URL,$site.'?do=copy&ajax=1&plugin=config');
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "index=".$this->parent->index()."&url=".urlencode('http://'.$_SERVER['HTTP_HOST'])."&to=".$to.'&res='.urlencode(
			base64_encode(gzcompress(serialize($items), 9))
		));
		
		// grab URL and pass it to the browser
		ob_start();
		curl_exec($ch);
		$s=ob_get_contents();
		// close cURL resource, and free up system resources
		ob_end_clean();
		$result.='';
		curl_close($ch);
		
		return 'result:'.$result.htmlspecialchars($s);
	}
	
	/**
	 * Перевод ссылок вида /xxx/uploaded/yyy в /uploaded/yyy
	 *
	 */
	function do_convert_links(){
		$from=pps($_GET['id']);
		$sqls=array(
			"update ?_news set pic_small=replace(pic_small,'".$from."/uploaded','".pps($_GET['to'])."/uploaded') where pic_small like '%".$_GET['id']."/uploaded%';",
			"update ?_news set pic_big=replace(pic_big,'".$from."/uploaded','".pps($_GET['to'])."/uploaded') where pic_big like '%".$_GET['id']."/uploaded%';",
		
			"update ?_flesh set tval=replace(tval,'".$from."/uploaded','".pps($_GET['to'])."/uploaded') where tval like '%".$_GET['id']."/uploaded%';",
			"update ?_flesh set sval=replace(sval,'".$from."/uploaded','".pps($_GET['to'])."/uploaded') where sval like '%".$_GET['id']."/uploaded%';",
			
		//		"ALTER TABLE ?_tree CHANGE `name` `name` VARCHAR( 40 ) CHARACTER SET cp1251 COLLATE cp1251_general_ci",
	//		"OPTIMIZE TABLE `wizantija_tree`;"
		//	"ALTER TABLE `wizantija_tree` CHANGE `level` `level` SMALLINT(3) UNSIGNED NOT NULL"
		);
		if(class_exists('fotovideo')){
			$sqls=array_merge($sqls,array(
				"update ?_fotovideo set pic_small=replace(pic_small,'".$from."/uploaded','".pps($_GET['to'])."/uploaded') where pic_small like '%".$_GET['id']."/uploaded%';",
			//	"update ?_fotovideo set pic_big=replace(pic_big,'".$from."/uploaded','".pps($_GET['to'])."/uploaded') where pic_big like '%".$_GET['id']."/uploaded%';",
			));
		}
		if(class_exists('basket')){
			$sqls=array_merge($sqls,array(
				"update ?_katalog set pic_small=replace(pic_small,'".$from."/uploaded','".pps($_GET['to'])."/uploaded') where pic_small like '%".$_GET['id']."/uploaded%';",
				"update ?_katalog set pic_big=replace(pic_big,'".$from."/uploaded','".pps($_GET['to'])."/uploaded') where pic_big like '%".$_GET['id']."/uploaded%';",
			));
		}
		foreach($sqls as $sql)
			$this->database->select($sql);
		return 'Ok';	
	}
	
	function scan4links($clear,&$listfile,&$lostfiles){
		// собрать все линки в кучу
			$this->database->query('DROP TABLE IF EXISTS ?_ftmp;');
			$this->database->query("CREATE TEMPORARY TABLE ?_ftmp (
`name` VARCHAR( 100 ) NOT NULL ,
`where` VARCHAR( 10 ) NOT NULL ,
`xid` int(11) NOT NULL ,
KEY ( `name` )
);");
			$names=array();
			
			//$reg='~uploaded/(.*)[\'"$]~';
			$reg='~uploaded/([ %\!\+\,\-\(\)\.\w]+)~';
			foreach (array(
				array('pic_small','news'),
				array('pic_big','news'),
				array('tval','flesh'),
				array('sval','flesh'),
				array('pic_small','katalog'),
				array('pic_big','katalog'),
				array('pic_small','fotovideo'),
			)
			as $xx) {
				$res=@$this->database->select('select `'.$xx[0].'`,`id` from ?_'.$xx[1].' where `'.$xx[0].'` like "%uploaded/%";');
				if(!empty($res))
				foreach($res as $v){
					if(preg_match_all($reg,$v[$xx[0]],$m)){
						foreach($m[1] as $xxx)
						$names[]=sprintf('"%s","%s","%s"',urldecode($xxx),$xx[0].'_'.$xx[1],$v['id']);
					}
				}
			};
		//debug(count($names));
			$names=array_unique($names);
			$this->database->query('insert into ?_ftmp (`name`,`where`,`xid`) values('.
				implode('),(',$names).');');
			
			// анализ!!!!!!!!!!!!!!
		/*
		 * пустые ссылки
		 */
			$listfile=$this->database->query('SELECT x.*
FROM ?_ftmp AS x
LEFT JOIN ?_fbase AS y ON x.`name` = y.`name`
WHERE y.`name` IS NULL  order by x.`name`;');
			$lostfiles=$this->database->query('SELECT x.*
FROM ?_fbase AS x
LEFT JOIN ?_ftmp AS y ON x.`name` = y.`name`
WHERE y.`name` IS NULL  order by x.`name`;');
			if($clear)
				foreach($lostfiles as $v){
					@unlink(TMP_DIR.$v['name']);
				}
	}
	
	function admin_config(){
		
		if(!$this->parent->has_rights(right_WRITE)){
			$this->parent->error('Нужно авторизоваться!!!!!');
			return ' ';
		}
		/**
		 * Копирование раздела
		 */
		//if(isset()){
			
		//}
		/**
		 * проверка загруженных файлов
		 */
		if(isset($_POST['check_uploaded'])){
			$listfile=array();
			$lostfiles=array();
			$this->scan4links(false,$listfile,$lostfiles);
		}  	
		if(isset($_POST['clear_unused'])){
			$listfile=array();
			$lostfiles=array();
			$this->scan4links(true,$listfile,$lostfiles);
		}  	
		
		/**
		 * удалить якоря
		 */
		if (isset($_POST['clear_anchors'])){
			$x=$this->database->selectCol(
				"delete FROM ?_flesh ".
				"WHERE `name`='anchor';");
		}
		
		if (isset($_POST['heal_NS'])){
			include_once 'nestedsets.class.php';
				// пересоздаем nested-set таблицу с новыми значениями. 
				$xx=$this->database->select(
					"drop table if exists ?_tmp;");
				$sql=$this->database->selectRow('show create table ?_tree');
				$sql=$sql['Create Table'];
				//echo $sql ; exit;
				//	preg_replace('/,\s*primary.*$/is',');',$sql['Create Table']);
				$sql=
					preg_replace('/table\s+`?'.TAB_PREF.'_tree`?/i'
						,'temporary  table '.TAB_PREF.'_tmp',$sql);
				//$sql=
				//	preg_replace('/AUTO_INCREMENT=\d+\s*/i','',$sql);	
				$this->database->query($sql);
				// создали заготовку для NS
				set_time_limit(120);
				$res=$this->database->select('select * from ?_tree order by `lid`');

				$this->prep_NS_array($res);
				
				$sql='insert into '.TAB_PREF.'_tmp (id,lid,rid,pid,level,page,name) VALUES';
				$sqlx='';
				foreach($res as $k=>$v){
					if(strlen($sql)>10000){
						$result=mysql_query($sql,$this->database->link);
						if (!$result) {
					   		echo('Invalid query: ' . mysql_error() . "\nWhole query: " . substr($sql,0,100).'...'.substr($sql,strlen($sql)-50,50));
						};
						$sql='insert into '.TAB_PREF.'_tmp (id,lid,rid,pid,level,page,name) VALUES';
						$sqlx='';
					}
					$sql.=$sqlx."(".$v['id'].",".$v['lid'].",".$v['rid'].",".$v['pid'].
						",".$v['level'].",".$v['page'].",'".$v['name']."')";
					$sqlx=',';
				}
				//echo $sql;
				$result=mysql_query($sql,$this->database->link);
				if (!$result) {
				   echo('Invalid query: ' . mysql_error() . "\nWhole query: " . substr($sql,0,100).'...'.substr($sql,strlen($sql)-50,50));
				};
			$sql=$this->database->select('delete from ?_tree');
				$sql=$this->database->select('insert into ?_tree select * from ?_tmp;');
				
		}
		/**
		 * проверить структуру NS
		 */
		if (isset($_POST['check_NS'])){
			$x=$this->database->selectRow(
				"SELECT COUNT(id) as cnt, MIN(lid) as mlid, MAX(rid) as mrid FROM ?_tree ;");
			if($x['mlid']!=1)
				echo 'lid слишком большой!! ';
			if($x['mrid']!=2*$x['cnt'])
				printf('rid слишком большой!! (%s--%s)',$x['mrid'],2*$x['cnt']);

			$x=$this->database->selectCol(
				"select id from ?_tree 
				where `lid`>=`rid`;");
			if(!empty($x)){
				echo 'Абзац!!!('.implode(',',$x). ') ';
			}
			$x=$this->database->selectCol(
			'SELECT id FROM ?_tree where MOD((rid-level+2),2)=1');
			if(!empty($x)){
				echo 'Абзац2!!!('.implode(',',$x). ') ';
			}
/*			
			$x=$this->database->select(
			'SELECT t1.id, COUNT(t1.id) AS rep, MAX(t3.rid) AS max_right FROM ?_tree AS t1,
			?_tree AS t2, ?_tree AS t3 WHERE t1.lid <> t2.lid AND t1.lid <> t2.rid
			AND t1.rid <> t2.lid AND t1.rid <> t2.rid 
			GROUP BY t1.id HAVING max_right <> SQRT(4 * rep + 1) + 1');
			if(!empty($x)){
				echo 'Абзац3!!!('.implode(',',$x). ') ';
			}
*/			
				$x=$this->database->selectCol("SELECT *
FROM ?_tree as x WHERE x.level=0 and x.lid+1<x.rid order by id;");
				foreach ($x as $v)if(!empty($v)){
					$y=$this->database->selectRow(
"SELECT x.*,count(t.id) as cnt FROM ?_tree as x, ?_tree as t 
WHERE x.id=?d and
t.lid>x.lid and t.rid<x.rid
group by x.id",$v);
					if (2*$y['cnt']!=$y['rid']-$y['lid']-1){
						echo "&lt;$v ".(2*$y['cnt']).' '.($y['rid']-$y['lid']-1).'&gt;';
						//break;
					}	
				}
		}
		/**
		 * удалить якоря
		 */
		if (isset($_POST['optimize'])){
			$this->optimize();
		}
		
		if (isset($_POST['clear_katalogue']) && (class_exists('basket'))){
			
			$x=$this->database->selectCol(//'delete ?_katalog where `id` not in( '.
				'select k.id from ?_katalog as k left join ?_flesh as f on k.xarticle=f.id '.
				'where isNull(f.name) or (f.name="type" and f.ival<>'.type_KATALOG.');');
			if(!empty($x)){
				print_r($this->database->select('delete from ?_katalog where `id` in ('.implode(',',$x).')'));
			}
		}
		/**
		 * чистка статей, которых нет в меню
		 * 1 - параметры
		 * 2 - пароль админа
		 * anchor - польза
		 */
		if (isset($_POST['clear_free_article'])){
			$x=$this->database->selectCol(
				"SELECT `id` FROM ?_tree ".
				"WHERE `level`=0 and `name`<>'root menu' and `name`<>'bannerlist' and `name`<>'runningline' and `name`<>'votes';");
			$x=array_diff($x,$this->database->selectCol(
				"SELECT `ival` FROM ?_flesh ".
				"WHERE `name`='page'"));
			$y=array();
			if(!empty($x))
			$y=$this->database->selectCol(
				"SELECT `page` FROM ?_tree ".
				"WHERE `id` in (".implode(',',$x).')');
			if(class_exists('basket')){
				$yy=@$this->database->selectCol(
					"SELECT `descr7` FROM ?_katalog ;");
				if(!is_null($yy))
					$y=array_diff($y,$yy);
			} 
			
			if(!empty($y))
				echo(implode(',',$y)."\n");
		}
		/**
		 * чистка откровенно пустых значений во ?_flesh
		 * 1 - параметры
		 * 2 - пароль админа
		 * anchor - польза
		 */
		if (isset($_POST['clear_empty_flesh'])){
			$x=$this->database->selectCol(
				"SELECT distinct `id` FROM ?_flesh ".
				"WHERE `id` not in ( select distinct `page` ".
				"from ?_tree ) ".
				"and `name`<>'anchor' and `id`>2");
			$y=$this->database->selectCol(
				"SELECT distinct `id` FROM ?_flesh ".
				"WHERE `name`='record' and `sval`='user';");
			$x=array_diff($x,$y);
			if(!empty($x))
				echo implode(',',$x);
		}

		if(!empty($_POST['delete']) && !empty($_POST['delete_id'])){
			$r=explode(',',$_POST['delete_id']);
			foreach($r as $v){
				$v=trim($v);
				if(empty($v)) continue;
				if(preg_match('/^~([0-9]+)$/',$v,$m)){
					$this->parent->nodeDelete($m[1]);
				} else {
					$this->parent->nodeDelete($this->parent->node($v));
					$this->parent->delRecord(array('id'=>$v));
				}
			}
			$this->parent->go($this->parent->curl());
		}
		if (empty($listfile))$listfile='';else $listfile=array('count'=>count($listfile),'list'=>$listfile);
		if (empty($lostfiles))$lostfiles='';else $lostfiles=array('count'=>count($lostfiles),'list'=>$lostfiles);
		return smart_template(array('tpl_config','config'),array(
			'listfile'=>$listfile,
			'lostfile'=>$lostfiles,
			'list'=>isset($this->parent->ajaxdata['pictures'])?$this->parent->ajaxdata['pictures']:''
		));
	}
	
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
	
	function prep_NS_array(&$res,$ptr=0,$level=0){
		$st=array(0);
		// переделываем lid-rid, parent, level
		$top=0;$lev=-1;$cur=0;
		foreach($res as $k=>$v){
			if($v['level']==$lev){
				$res[$cur]['rid']=++$ptr; 
				$res[$k]['lid']=++$ptr; 
				$res[$k]['level']=$lev+$level;
				$res[$k]['pid']=$res[$cur]['pid'];
				$cur=$k;
			} else
			if($v['level']>$lev){
				$st[]=$cur;
				$res[$k]['lid']=++$ptr;
				$res[$k]['pid']=$lev==0?$level:$res[$cur]['id'];
				$res[$k]['level']=++$lev+$level;
				$cur=$k;
			} else {
				while($v['level']<$lev){
					$res[$cur]['rid']=++$ptr;
					$cur=array_pop($st);
					$lev--;
				}
				if($res[$cur]['level']==$lev)
					$res[$cur]['rid']=++$ptr;
				$res[$k]['lid']=++$ptr; 
				$res[$k]['level']=$lev+$level;
				$res[$k]['pid']=$res[$cur]['pid'];
				$cur=$k;
				//$res[$k]['pid']=$res[$cur]['id'];
			}
		}
		while($lev>=0){
			$res[$cur]['rid']=++$ptr;
			$cur=array_pop($st);
			$lev--;
		}
	}
	
	function do_create(){
		$this->parent->database->query("DROP TABLE IF EXISTS ?_flesh;");
		$this->parent->database->query(
		"CREATE TABLE ?_flesh (
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
		$this->parent->setPar('plugin_list','photosizes, fileman');
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
		$this->parent->database->query('DROP TABLE IF EXISTS ?_tree');
		$this->parent->database->query(
			'CREATE TABLE ?_tree ('.
			' `id` int(10) NOT NULL auto_increment,'.
			' `pid` int(10) NOT NULL default "0",'.
			' `lid` int(10) NOT NULL default "0",'.
			' `rid` int(10) NOT NULL default "0",'.
			' `level` int(10) NOT NULL default "0",'.
			' `name` varchar(255) NOT NULL default "",'.
			' `page` int(11) NOT NULL default "0",'.
			' PRIMARY KEY  (`id`),'.
			' KEY `name` (`name`),'.
			' KEY `page` (`page`)'.
			')');
		$id=$this->parent->nodeAdd(0,array('name'=>'root menu','type'=>type_MAINMENU));
		//echo "11_".SITE_CREATE_SCENARIO;
		if (defined('SITE_CREATE_SCENARIO')){
			if(SITE_CREATE_SCENARIO<11){
					$this->parent->nodeAdd($id,array('name'=>'main','descr'=>'Основные разделы'));
			} else if(defined('KATALOG_INTO_MENU')){
					// каталог входит в основное меню
				$id=$this->parent->nodeAdd($id,array('name'=>'main','descr'=>'Основные разделы','url'=>'news','type'=>type_MAINMENU));
				$this->parent->nodeAdd($id,array('name'=>'catalogue','descr'=>'Каталог'));
			} else {
					// каталог не входит в основное меню
				$this->parent->nodeAdd($id,array('name'=>'main','descr'=>'Основные разделы'));
				$this->parent->nodeAdd($id,array('name'=>'catalogue','descr'=>'Каталог'));
			}
		} else {
			if(defined('KATALOG_INTO_MENU')){
				$id=$this->parent->nodeAdd($id,array('name'=>'main','descr'=>'Основные разделы','url'=>'news','type'=>type_MAINMENU));
				$this->parent->nodeAdd($id,array('name'=>'catalogue','descr'=>'Каталог'));
			} else {	
				$this->parent->nodeAdd($id,array('name'=>'main','descr'=>'Основные разделы'));
			}	
		}
	}
}

/**
 * Описание главного обьекта приложения
 */
class engine extends engine_Main
{
	
	function do_test(){
		$x=$this->getNode('root menu');
		$this->nodeAdd($x[0]['node'],array('name'=>'main','descr'=>'Основные разделы'));
	}
	
	/**
	 * выдать список плагинов из параметра - plugin_list
	 *
	 */
	function pluginlist(){
		static $x; 
		if(!$this->has_rights(right_WRITE)) return '';
		if(isset($x)) return $x; $x='';
		$pl=trim($this->getPar('plugin_list')).',altname';
		if (empty($pl)) return $x='';
		$pll=preg_split('/[,\s]+/',pps($pl));
		if (empty($pll)) {
			return $x='';
		} else {
            $pl=array();
			foreach($pll as $v){
                $plugin=$this->export($v);
                //debug($v.' '.$plugin->modulelist);
                if (!isset( $plugin->modulelist) || $plugin->modulelist)
				$pl[]=array(
					'plugin'=>$v
					,'name'=>ppx($plugin->getPluginName(),$v)
				);
            }
			$x=array('list'=>$pl);
			if (!empty($_GET['adv'])){
				$x['list'][]=array(
					'plugin'=>'config'
					,'name'=>'config'
				);
			} 
			
			if(is_callable('get_parameters'))	
				$x['param']=array();
			return $x;
		}
	}

	function do_logout(){
        $this->ffirst('_logout');
		$this->go($this->curl('do'))
	}

	function get_parameters(&$par){
		if(is_callable('get_parameters')){
			get_parameters($par);
			if (empty($_GET['adv'])) 
				return ;
		}
		$par['list'][]=array('sub'=>'Системные параметры','title'=>'Список плагинов для панели администрирования','name'=>'plugin_list');
		$par['list'][]=array('title'=>'Количество дней хранения куки с паролем','name'=>'auth_cookie_age');
	}

	function rights($what=''){
		if($what=='write')
			$x=$this->has_rights(right_WRITE);
		else
			$x=$this->has_rights(right_ADMIN);
		return $x?array():false;
	}

/**
 * Функция работает с формой ввода параметров сайта
 * Строим форму для изменения уже имеющихся параметров сайта
 *
 * @param unknown_type $tpl
 */
	function do_siteParam($param=''){
	//print_r($param);
		$this->parent->sessionstart();
		if(!$this->has_rights(right_WRITE))
			return $this->ffirst('_loginform');
		//$this->parent->read_Parameters();
		$form=new form('paramedit');
		$form->nostore=true;

		$res=array('error'=>pps($_SESSION['errormsg']),'list'=>'');
		$res['list']=array();
		if ($param!='')
			$this->parent->export($param,'get_parameters',array('list'=>&$res['list']));
		else {
			$this->parent->export('MAIN','get_parameters',array('list'=>&$res['list']));
			$this->parent->export('Auth','get_parameters',array('list'=>&$res['list']));
			$this->parent->export('search','get_parameters',array('list'=>&$res['list']));
		}
		$buttons=array();	
		foreach($res['list'] as $k=>$v){
			$v['type']=pps($v['type'],'input');
			if($v['type']=='button'){$buttons[$v['function']]=$v['plugin'];}
			$res['list'][$k][$v['type']]=array($res['list'][$k]); 
			//debug($res);
			if (pps($v['type1'])=='radio'){$v['type1']='radio';}else{$v['type1']='text';}
			if (pps($v['name'])==pps($v['value'])) $v['type2']='CHECKED';
			if (isset($v['sub']))
				$res['list'][$k]['subx']=array('sub'=>$v['sub']);
			

				
		}
		//print_r($form);
		$form->scanHtml(smart_template(array(ADMIN_TPL,'paramedit'),$res));
		if($form->handle()){
			$chkadmin=false;
			foreach($form->var as $k=>$v){
				if(!isset($buttons[$k])){
					$x=$this->getPar($k);
					//debug(array($v,$x,(!empty($v) ||(empty($v))!==empty($x)) && ($v!=$x)));
					if (($k=='login_admin' ||$k=='login_newpassword'||$k=='login_oldadmin') && !empty($x)){
						$chkadmin=true;
					} else if ((!empty($v) ||(empty($v))!==empty($x)) && ($v!=$x)){
						$this->setPar($k,$v);
					}
				}
			}
			if($chkadmin){
				$user=$this->user;//readRecord(array('id'=>$_SESSION['USER_ID']));
				//print_r($user);
 
					$user['name']=$form->var['login_admin'];
					$user['password']=$form->var['login_newpassword'];
					$this->writeRecord($user);

			}
			foreach($buttons as $k=>$v){
				if(isset($_POST[$k]))
					$this->export($buttons[$k],$k);
			}
			$this->parent->go($this->parent->curl());
		}
		if(!empty($form->var))
		foreach($form->var as $k=>$v){
			if($vv=$this->parent->getPar($k))
				$form->var[$k]=$vv;
			//$res[]=array('title'=>$k,'name'=>$v,'value'=>htmlentities($this->parent->getPar($v)));
		}
		return $form->getHtml(' ');
	}

	function _modules($moduleName,$module){
		return array(
			array('current'=>'current','name'=>$moduleName,'id'=>$module)
		);
	}

	function _head_menu(){
		$x=$this->ffirst('_getCurList');
		array_shift($x);
	    if(!isset($this->nopoplast))
			;//$y=array_pop($x);
		else
			$y=array('name'=>$this->nopoplast);
		foreach($x as $k=>$v){
			if(!empty($v['descr'])) $x[$k]['name']=$v['descr'];
		}
		$x[count($x)-1]['current']="current";
		return $x;
	}

	function _menu(){
		$x=$this->ffirst('getSiteMap');
		$res='';
		foreach(array('main'=>'Основные разделы','catalogue'=>'Каталог') as $k=>$v){
			if($y=$x->scan($k)){
				//debug($y->getUlLi(3,false,1));
				$res.=smart_template(array(ELEMENTS_TPL,'sitemap'),
				array('id'=>$y->v['id'],'data'=>$y->getUlLi(1000,false,1),'menu'=>str_replace('%id%',$y->v['id'],$v)));
			}
		}
		return $res;
	}

	function init(){
		parent::init();
//debug($_SESSION['clipboard']);
		$this->menu=array('right'=>array('MAIN','_menu'),
				'head'=>array('MAIN','_head_menu'));
		$this->par['tridtsat']=30;
		$this->login_cookie='admin-login';

	}

	function do_cat(){
		$this->parent->sessionstart();
		
		if(!$this->has_rights(right_WRITE))
			return $this->ffirst('_loginform');
		// вывод каталога внутри элемента страницы
		$this->cur_menu=ppi($_GET['id']);
		$this->xsearch();
		$item=$this->ffirst('getSiteMap',$this->cur_menu);
		$res=$this->parent->nodeGet($this->parent->node(ppi($_GET['cat'])));
		$this->nopoplast=pps($res['text'],'элемент - каталог');
		return $this->export('katalog','admin_katalog',$_GET['cat']);
	}

	/**
	 * Вывод страницы для редактирования
	 *
	 * @return unknown
	 */
	function do_page(){
		$this->parent->sessionstart();
		
		if(!$this->has_rights(right_WRITE))
			return $this->ffirst('_loginform');
		
		// ищем менюшный вход
		$this->page_id=ppi($_GET['id']);
		if(!$node=$this->node($this->page_id)){
			$item=$this->readRecord(array('id'=>$this->page_id)); // мы!
			$up_items=array();
			$top=$item;
		} else {
			$up_items=$this->parent->nodeGetBackPath($node);
		//	debug($up_items);
			$item=array_pop($up_items); // мы!
			if(count($up_items)) {// top - верхний артикл
				$top=array_shift($up_items);
				if(count($up_items)) // top - верхний артикл
					$prev=array_shift($up_items);
			} else	
				$top=$item;
		} 
		$rsd=$this->database->selectCell('select `id` from ?_flesh where `name`="page" and `ival`=?d',$top['node']);
	//	$rsd=$this->database->selectCell('select `id` from ?_tree where `page`=?d',$rsd);
		$this->cur_menu=$rsd;
		$menu=$this->export('sitemap','getSiteMap');
		$menu->scan($this->cur_menu);
		foreach($up_items as $v){
			if(ppi($v['type'])==type_ARTICLE)
			$this->export('sitemap','menu_push',
				array('id'=>$v['id'],'menupage'=>'page','name' => pps($v['item_text'],'доп. страница')));
		}
		$this->export('sitemap','menu_push',array('id'=>$item['id'],'menupage'=>'page','name' => pps($item['item_text'],'Страницо!')));
		$form=new form('menuedit');
		$form->nostore=true;
		$items =$this->nodeGet($this->node($item));
		$i=0;
		$article=readElement($items,$i);
		$res=array('name' => pps($item['item_text'],'Страницо!'));
		$res['has_menu']=array('current'=>ppi($_COOKIE['curtab'])!=1?' ':false,'addnew'=>array());
		if(!!$item && !!$article){
			//$res['has_menu']['data']=$article->getForm();
			$x=$article->getForm();
			if ($x){
				$res['has_content']=array(
					'current'=>ppi($_COOKIE['curtab'])!=2?' ':false,
					'data'=>$x);
			}
		}
		if(isset($item->v['name'])) $res['name']=$item->v['name'];
		//$res['has_menu']=array();
		$res['error']=pps($_SESSION['errormsg']);
		$form->scanHtml(smart_template(array(FORMS_TPL,'menuedit'),$res));
		if($form->handle()){
			if(!!$article){
				$article->serialize($form->var,true);
			}
			$this->parent->go($this->parent->curl());
		}
		if(!!$item && !!$article){
			$article->serialize($form->var);
		}
		return $form->getHtml(' ');
	}
	
	function _search(){
		return $this->xsearch();
	}
	
	function xsearch(){
		if(!empty($_POST['adminsearch'])){
			$_SESSION['search_state']=false;
			$ss=strtolower(trim($_POST['adminsearch']));
			
			$_SESSION['search_string']=$ss;
			if ($_GET['id']=='fileman') {
				$_SESSION['search_id']='поиски в разделе Файлы';
				$this->parent->export('search','saveres',
				//array_merge(
					$this->parent->export('fileman','search',$ss)
				//)
				);
				
			} elseif ($_GET['id']=='users') {
				$_SESSION['search_id']='поиски в разделе Пользователи';
				
				//debug($ss);	
				$this->parent->export('search','saveres',
				//array_merge(
					$this->parent->export('users','search',$ss)
				//)
				);
			} else {
				$_SESSION['search_id']='поиск в разделе Каталог товаров';
				$this->parent->export('search','saveres',
				array_merge(
						$this->export('MAIN','search',$ss),
						$this->export('news','search',$ss),
						$this->export('katalog','search',$ss)
				)
				);
			}
			$this->parent->go('?do=search'.pp($_GET['debug'],'&debug=','',''));//.$adv);
		}
	}
	/**
	 * формирование формы редактирования
	 */
	function do_menu(){
		$this->parent->sessionstart();
		
		if(!$this->has_rights(right_WRITE))
			return $this->ffirst('_loginform');
		
		$this->xsearch();
		
		$this->cur_menu=pps($_GET['id']);
		$menu=$this->export('sitemap','getSiteMap');
		if($item=$menu->scan($this->cur_menu))
	 		$element=$item->scan(pps($this->cur_menu));
		//debug($element);
	 		
	 	$plugin=$_GET['id'];
	 	if(!class_exists($plugin) && !empty($element))
	 		$plugin=pps($element->v['url']);
		if(!empty($plugin))
			if(class_exists($plugin)){
				return $this->parent->export($plugin,'admin_'.$plugin);
			}
		if(empty($item) && empty($element)){
			$this->go($this->curl('do','id'));
		}
		$form=new form('menuedit');
		$form->nostore=true;

		$res=array();
		$res['has_menu']=array('current'=>' ','addnew'=>array());
		if(!!$item){
			$res['has_menu']['data']=$item->getForm();
			$x=$item->getContent();
			if ($x){
				$res['has_content']=array(
					'current'=>ppi($_COOKIE['curtab'])!=2?' ':false,
					'data'=>$x);
			}
		}
		if(isset($item->v['name'])) $res['name']=$item->v['name'];
		//$res['has_menu']=array();
		$res['error']=pps($_SESSION['errormsg']);
		$article=$this->nodeGet($element->v['page']);
			//debug('xxxxx '.print_r($article,true));
		$article=$article[0]['id'];
		$form->scanHtml(smart_template(array(FORMS_TPL,'menuedit'),$res));
		if($form->handle()){
            //<%=point('plugin_control1') %>
			if(!!$item){
				$item->serialize($form->var,true);
			}
			$this->parent->go($this->parent->curl());
		}
		if(!!$item){
			$item->serialize($form->var);
		}
		/*/****** point plugin_control2 */
$x=$this->export('altname','getrealaddr',$item->v['id']);
  if(empty($x)){
  	$x=$item->v['id'];
  }
  $form->var['alt_name_'.$element->article->v['id']]=$x;/****finish point plugin_control2 *//*
*/
		return $form->getHtml(' ');
	}

	/**
	 * Генерация главного окна приложения
	 */
	function do_Default(){
		$this->parent->sessionstart();
		if(!$this->has_rights(right_WRITE))
			return $this->ffirst('_loginform');
		if(is_callable(array($this,'xsearch')))$this->xsearch();
		return $this->export('news','admin_news');
	}

	function do_error(){
		return smart_template(array(ELEMENTS_TPL,'ermess'),' ');
	}
	
	function do_search(){
		$this->parent->sessionstart();
		$adv='';	
		if(!empty($_REQUEST['search'])){
			$_SESSION['search_state']=false;
			if(!empty($_REQUEST['search_string'])){
				$ss=cyr_strtolower(trim($_REQUEST['search_string']));
				//debug($ss);	
				$this->export('search','saveres',
					array_merge(
						$this->export('MAIN','search',$ss),
						$this->export('news','search',$ss),
						$this->export('katalog','search',$ss)
						
					));
			}	
		} else {
			return $this->export('search','result');
		}
		$this->go('?do=search'.pp($_GET['debug'],'&debug=','','').$adv);
	}
	

}

/**
 * Поехали работать
 */
$engine=&new engine('sitemap','ajax','Auth','users');
$_GLOBALS['engine']=&$engine;

//DO_IT_ALL();
//*
$engine->sessionstart();

ob_start('convert_href');
DATABASE();
if(pps($_GET['debug'])){
	$d='Debug/HackerConsole/Main.php';
	if (file_exists ($d))
		require_once $d;
	elseif (file_exists ('../'.$d))
		require_once '../'.$d;

	if (class_exists('Debug_HackerConsole_Main') ) {
		new Debug_HackerConsole_Main(true);

        function backtrace(){
            $x=debug_backtrace();
            $z=array();
            $y=array_shift($x);
    //		$y=array_shift($x);
            if($x[1]['function']=='myLogger'){
                while (isset($x[0]) && $y['function']!='_query')
                    $y=array_shift($x);
            }

            //for($i=0;$i<3;$i++){
                $y=array_shift($x);
                $z=sprintf('file:%s,line:%s,func:%s',$y['file'],$y['line'],$y['function']);
            //}
            return $z;
        }
		function debug()
		{
            $na=func_num_args();
            for ($i=0; $i<$na;$i++){
                $msg=func_get_arg($i);
                call_user_func(array('Debug_HackerConsole_Main', 'out'),is_string($msg)?$msg:print_r($msg,true));
            }
			call_user_func(array('Debug_HackerConsole_Main', 'out'),'>>>>>'.backtrace());
		}
	} else {
		function debug($msg){
			//var_dump($msg);
		};
        function backtrace(){;}
	}
	$engine->database->do_log=true;
	if(!empty($_POST))
		debug($_POST);
} else {
		function debug($msg){;}
}
$engine->execute('init');

if(function_exists('admin_init')){
	admin_init($engine);
}

// проверка всех , включенных в pluginlist плагинов

$engine->ffirst('auth_check');

// 
if(!empty($_POST['form'])){
	$m=explode(':',$_POST['form'].'::');
	$engine->export($m[0],'_'.ppx($m[1],'handle'));
}

if(!$engine->has_rights(right_WRITE)){
	unset($engine->menu['right']);
}
$engine->par['root']=rtrim(toUrl(ROOT_PATH),'/').'/';

if(!defined('INTERNAL')){
	// отсюда не возвращаются праведные запросы
	$engine->act(pps($_GET['do']),pps($_GET['plugin']));
	// а сюда отправляем остальной мусор
	// чистка таблиц
	if( empty($engine->is_ajax) )
	{	 
		session_write_close();
		if($engine->getPar('last_clearing',0)+(7*60*60*24)<time()){
			$engine->export('config','optimize');
			
			$engine->setPar('last_clearing',time());
		}
		
		if($engine->getPar('convert_links',false)){
			debug('convert_links');
			$engine->export('config','convert_links');
		}
		
	}
	//	echo mkt();
}

