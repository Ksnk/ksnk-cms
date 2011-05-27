<?php

define("CNT_LABEL"        ,1);
define("CNT_INPUT"        ,2);
define("CNT_SUBMIT"       ,3);
define("CNT_BUTTON"       ,4);
define("CNT_TEXTAREA"     ,5);
define("CNT_SELECT"       ,8);
define("CNT_TEXT"         ,9);
define("CNT_GROUP"	      ,100);

if (!defined('TMP_DIR')) define ('TMP_DIR','data/');

function fileKb($filename){
	if(is_readable($filename)){
		$x=filesize($filename); 
	} else {
		$x=ppi($filename,1);
	}
	if($x<1024){
		return $x." б";
	} else if ($x<1024*1024){
		return number_format($x/1024,1).' Кб';
	} else if ($x<1024*1024*1024){
		return number_format($x/(1024*1024),2).' Mб';
	} else if ($x<1024*1024*1024*1024){
		return number_format($x/(1024*1024*1024),2).' Гб';
	}
}


// form holder

class control {
	var $c; //= пїЅпїЅпїЅпїЅпїЅпїЅ пїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅ - пїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅ пїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅ
	function control($c)
	{
		foreach (array(1,2,3,4,5,'pre','place','post','val','values','par','ipar')as $i)
		if (!isset($c[$i]))
			$c[$i]='';
		$this->c=$c;
	}
	function IsHidden()
	{
		return ($this->c[0]==CNT_INPUT)&&($this->c[2]=='hidden');
	}
	function PrepValue($c)
	{
		if (($this->c[0]==CNT_INPUT)&&($this->c[2]=='checkbox')) {
			$y=0;
			if (is_array($c))
				foreach($c as $i) $y+=$i ;
			else
				$y=$c;
			return $y;
		} else
			return $c ;
	}
	function dropOptions(&$c){
		$s='';
		foreach ($c as $k=>$v)
		{
			if (is_array($v))
				$s.='<optgroup label="'.$k.'">'.$this->dropOptions($v).'</optgroup>';
			else
				$s.='<option value="'.$k.'">'.$v ;
		}
		return $s;
	}
	function getHtml()
	{
		$c=$this->c;
		$s='';
		switch ($c[0]) {
			case CNT_SELECT:
				$s.='<select '.pp($c[1],' name="',(is_array($c['val'])?'[]"':'"'))
						.pp($c['ipar']).pp($c['par']).'>' ;
				if (empty($c['options']))$c['options']=$this->dropOptions($c[2]);
				if(is_array($c['val']))
					$val=$c['val'];
				elseif($c['val'])
					$val=array($c['val']);
				else
					$val='';
				//*---*/echo ' !'; print_r(implode('|',$val)) ;print_r($c['val']);echo '! ';
				if($val) {
					$c['options']=preg_replace('~value="(?:'.str_replace('*','\\\\*',implode('|',$val)).')"~i','\\0 selected',$c['options']);
				}
				$s.=$c['options'].'</select>' ;
				break;
			case CNT_TEXT:
				$s=pps($c[1],"&nbsp;");
				break;
			case CNT_LABEL:
				$s=pps($c[1],"&nbsp;");
				break;
			case CNT_GROUP:
				break;
			case CNT_TEXTAREA:
				$s='<TEXTAREA'.
					pp($c[1],' name="','"').pp($c['par']).pp($c['ipar']).">".
					htmlspecialchars(pp($c['val'],'')).'</TEXTAREA>' ;
				break ;
			case CNT_INPUT:
				$s='';
				if ($c[2]=='radio') {
					foreach (explode('|',$c['values'])as $i){
						if ($x=strpos($i,':')) {
							$v = substr($i,0,$x);
							$i= substr($i,$x+1);
						} else {
							$v = $i ;
						}
						$s.='<input type="radio"'.pp($c[1],' name="','"')
							.pp(htmlspecialchars ($v),' value="','"')
                            .pp($c[4],' onchange="','"');
						if ($v == $c['val']) $s.=' checked';
						$s.='>'.$i;
					}
				} else if ($c[2]=='checkbox') {// пїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅ!
					foreach (explode('|',$c['values'])as $i){
						if ($x=strpos($i,':')) {
							$v = substr($i,0,$x);
							$i= substr($i,$x+1);
						} else {
							$v = $i ;
						}
						$s.='<input type="checkbox"'.pp($c[1],' name="','[]"');
						if (is_array($c['val'])) {
							if (in_array($v,$c['val'])) $s.=' checked';
						} else {
							if (intval($v) & intval($c['val'])) $s.=' checked';
						}

						$s.=pp($v,' value="','"').pp($c['par'],' ').'>'.$i.'<br>';
					}
					$s=substr($s,0,strlen($s)-4);
				} else {
					$s='<input'.
						pp($c[1],' name="','"').
						pp($c[2],' type="','"',' type="text"');
					switch ($c[2]) {
						case 'checkbox':
							if ($c[1]) $s.=' checked'; break ;
						default:
							if(isset($c['val']))
								$s.=' value="'.($c['val']===0 ||$c['val']==='0'?'0':htmlspecialchars($c['val'])).'"';
					}
					$s.= pp($c['par']).pp($c['ipar']).'>';
				}
				break;
			case CNT_SUBMIT:
				$s= '<input type="submit"'.pp($c[1]).pp($c['par']).pp($c['ipar']).'>';
				break;
			case CNT_BUTTON:
				$s= '<input type="button"'.pp($c[1]).pp($c['par']).pp($c['ipar']).'>';
				break;
			default:
				$s=$c[1];
		}
		$s= pp($c['pre'],'').$s;
		$s.= pp($c['post'],'');
		return $s;
	}
}
//пїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅ пїЅпїЅпїЅпїЅпїЅпїЅпїЅ
class cell  {
	var $td,
		$par='',
		$colspan=1,
		$rowspan=1,
		$val='',        // пїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅ пїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅ пїЅпїЅпїЅпїЅпїЅпїЅ
		$ipar='';

	function addvar($var,$par) {
		$this->$var.=pp($par);
	}
	function cell($td='td') {
		$this->td=$td;
	}
	function getHtml(&$fillup,&$ind) {
		if   ($this->val) $s=$this->val;
		else {
			$s='';
			$fds=0;
			while(true){
				if ($ind>=count($fillup)) break ;
				$i=$fillup[$ind++];
				while ($i->IsHidden() || pps($i->c['place']))$i=$fillup[$ind++];
//          	if (!empty($i->c[0]))
				if ($this->ipar) {
					$i->c['ipar']=$this->ipar;
				}
				if ($i->c[0]==CNT_LABEL) {
					$ss=$i->getHtml();  $i->c['next']=1;
					if ($ss) {$s.='<fieldset><legend>'.$ss.'</legend>';$fds=1;}
				} else
					$s.=$i->getHtml();

				if (empty($i->c['next'])) break ;
			}
			if($fds){
				$s.='</fieldset>';
			};
		}

		return '<'.$this->td.pp($this->par).
			($this->colspan>1?' colspan="'.$this->colspan.'"':'').
			($this->rowspan>1?' rowspan="'.$this->rowspan.'"':'').
			'>'.$s.'</'.$this->td.'>'  ;
	}
} ;

class row  {
	var   $par,$cells ;
	function row(&$index,$s) {
##
##  пїЅпїЅпїЅпїЅпїЅпїЅ пїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅ пїЅ пїЅпїЅпїЅпїЅпїЅ lance10t
##
		$pastchar=' ';
		for($i=0;$i<strlen($s);$i++) {
			$c=$s{$i};
			if(!isset($index[$c])) {
				$index[$c]=new cell($c<='Z'?'th':'td');
				$this->cells[]=&$index[$c];
			} else {
				if($pastchar==$c) {
					if ($index[$c]->rowspan==1)$index[$c]->colspan++;
				} else $index[$c]->rowspan++;
			}
			$pastchar=$c;
		}
	}
	function getHtml(&$fillup,&$ind) {
		$s='';
		if(is_array($this->cells)){
		foreach($this->cells as $cell) $s.=$cell->getHtml($fillup,$ind)  ;
		}
		return sprintf('<tr%s>%s</tr>',pp($this->par),$s);
	}

}
class table   {
	var $rows, // пїЅпїЅпїЅпїЅпїЅ пїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅ пїЅпїЅпїЅпїЅпїЅ.
		$index,// пїЅпїЅпїЅпїЅпїЅпїЅ пїЅпїЅпїЅпїЅпїЅ, пїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅ пїЅпїЅ пїЅпїЅпїЅпїЅпїЅпїЅпїЅ пїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅ
		$tab_par='' ; // пїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅ
	function getHtml($fillup) { // пїЅ пїЅпїЅпїЅпїЅпїЅпїЅ пїЅпїЅпїЅпїЅпїЅпїЅ пїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅ пїЅпїЅпїЅпїЅпїЅпїЅпїЅ пїЅпїЅпїЅпїЅпїЅпїЅпїЅ $fillup
//echo print_r($this->index);
		$s='' ;  $ind =0;
		$fillup[]=new control(array(CNT_GROUP));
		// пїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅ пїЅпїЅпїЅпїЅпїЅпїЅпїЅ, пїЅпїЅпїЅпїЅпїЅпїЅпїЅ пїЅпїЅпїЅпїЅпїЅ пїЅпїЅпїЅпїЅ пїЅпїЅпїЅпїЅпїЅ
		foreach ($fillup as $k=>$v){
			if(pps($v->c['place'])){
				if(!empty($this->index[$v->c['place']]))
					$this->index[$v->c['place']]->addvar('val',$v->getHtml());
			}
		}
		//reset($fillup);
		foreach ($this->rows as $row) {
			$s.=$row->getHtml($fillup,$ind) ;
		}
		$ss='';
		while($ind<(count($fillup)-1)){
			$i=$fillup[$ind++];
			if (!($i->IsHidden() || pps($i->c['place'])))
				$ss.=$i->getHtml();
		}
		return '<table'.pp($this->tab_par).'>'.$s.'</table>'.$ss ;
	}
##
##  пїЅпїЅпїЅпїЅпїЅпїЅ пїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅ пїЅ пїЅпїЅпїЅпїЅпїЅ lance10t
##
	function apply_par($s) {
		foreach ($s as $i)
		{
			if     ($i{0}!='@') $this->tab_par.=pp($i);
			elseif (preg_match('/^@(val|ipar|par)\s+([^\s]*)(.*)/s',$i,$m)) {
				$mm=explode('|',$m[3]);
				for($j=0;$j<strlen($m[2]);$j++) {
					$s=isset($mm[$j])?$mm[$j]:$mm[count($mm)-1];
					if (isset($this->index[$m[2]{$j}])) {
						$this->index[$m[2]{$j}]->addvar($m[1],trim($s));
					}
				}
			}
		}
	}
	function table($s=''){
		foreach (explode("\n",$s) as $ss) {
			$this->rows[]=new row($this->index,trim($ss));
		}
		$args=func_get_args();
		array_shift($args);
		$this->apply_par($args);
	}
}
function my_move_uploaded_file($d,$s) {
	/*if (file_exists($s)) {
		if (preg_match('~(.*)\.([^\.]*)$~',$s,$m)){
			$s=$m[1] ; $ext = '.'.$m[2];
		} else  $ext = '';
		$i=1 ;
		while (file_exists($s."[$i]".$ext)) $i++;
		$s=$s."[$i]".$ext ;
	}*/
	if (move_uploaded_file($d,$s)) return $s;
	return FALSE ;
}

class form{
	var $controls=array(),
		$files=array(), // пїЅпїЅпїЅпїЅпїЅпїЅ пїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅ пїЅпїЅпїЅпїЅпїЅпїЅ
		$var,$tit,$uri,
		$name='',
		$method='POST',
		$par='',
		$upload_dir=TMP_DIR,
		$action ='';
	function newcontrol($arg) {
		static $canget = true; ;
//        if (empty($arg) || is_bool($arg))$canget=$arg;
		if (!is_array($arg))$canget=$arg;
//        else if (is_string($arg))$canget=$arg;
		else if ($canget) {
			$this->controls[] =&new control($arg);
			if ($arg[0]!=CNT_LABEL)
			if ($arg[0]!=CNT_TEXT)
			if ($arg[0]!=CNT_GROUP)
			if (isset($arg[1])) {
				$this->var[$arg[1]]='';
		   // load values from session  or from 'val'
				if (isset($arg['val']))
					$this->var[$arg[1]]= $arg['val'];
				else if (isset($_SESSION)) {
					if (isset($_SESSION['FORM_'.$this->name][$arg[1]]))
						$this->var[$arg[1]]= $_SESSION['FORM_'.$this->name][$arg[1]];
					else if(isset($arg['default']))
						$this->var[$arg[1]]=$arg['default'];
				}
			}
		}
	}

	function scanHtml($html){
		// ковыряем последовательно
		// начало
		//debug(strlen($html));
		if (($htlen=strlen($html))>100000){
			$found=preg_match('~<form\b([^>]*)name=[\'"]'.$this->name.'[\'"]([^>]*)>~ims',substr($html,0,1000),$m);
			$found=$found && preg_match('~</form>(.*)$~ims',substr($html,90000),$mm);
			$m[3]=substr($html,strlen($m[0]),$htlen-strlen($mm[0]));
		} else {
			$found=preg_match('~<form\b([^>]*)name=[\'"]'.$this->name.'[\'"]([^>]*)>(.*?)</form>~ims',$html,$m);
		}
		
		if($found){
			// выковыриваем параметры из строк
			$x=scanPar($m[1].$m[2],array('method'=>0,'action'=>1));
			$this->method=pps($x[0]);
			$this->action=pps($x[1]);
			$this->par=pps($x['par']);
			// сканируем контролы
			while(preg_match(
				"~^(.*)<(input|select|textarea)\b([^>]*)/?>~Umis"
				, $m[3],$x))
			{
				if (trim($x[1])) {
					//print_r(htmlspecialchars($x[1]));
					$this->newcontrol(array(CNT_TEXT,$x[1])) ;
				}
				$x[2]=strtolower($x[2]);
				if ($x[2]=='input'){
					$y=scanPar($x[3],array('type'=>0,'name'=>1,'value'=>2,'checked'=>3,'onchange'=>4));
					if(($y[0]=='button')||(empty($y[1]))) {
						$y[0]=CNT_TEXT;$y[1]=substr($x[0],strlen($x[1]));
					} else if($y[0]=='text') $y[0]=CNT_INPUT ;
					else if($y[0]=='submit') {
						$y[0]=CNT_SUBMIT ;
						if(!empty($y[1])){
							$y['par'].=' name="'.$y[1].'"';
							unset($y[1]);
						}
						if(!empty($y[2])){
							$y['par'].=' value="'.$y[2].'"';
							unset($y[2]);
						}
					}else if($y[0]=='checkbox') {
						$m[3]=substr($m[3],strlen($x[0]));
						preg_match('~([^<]*)~ms',$m[3],$z);
						if(isset($y[3])){
							$y['val']=pps($y[2],1);
						}
						$y['values']=(isset($y[3])?'1:':pp($y[2],'',':')).trim($z[1]);
						//*-----*/print_r('!!!!!!!!!!'.htmlspecialchars($z[1]));
						$m[3]=substr($m[3],strlen($z[0]));$x[0]='';
						$y[2]=$y[0];
						$y[0]=CNT_INPUT ;
					}else if($y[0]=='radio') {
						$m[3]=substr($m[3],strlen($x[0]));
						preg_match('~([^<]*)~ms',$m[3],$z);
						if(isset($y[3])){
							$y['val']=pps($y[2],1);
						}
                        if(isset($y[4])){
							$y['onchange']=$y[4];
						}
						$y['values']=(isset($y[3])?'1:':pp($y[2],'',':')).trim($z[1]);
						//*-----*/print_r('!!!!!!!!!!'.htmlspecialchars($z[1]));
						$m[3]=substr($m[3],strlen($z[0]));$x[0]='';
						$y[2]=$y[0];
						$y[0]=CNT_INPUT ;
					}else{
						$y['val']=pps($y[2]);
						$y[2]=$y[0];
						$y[0]=CNT_INPUT;
					}
					//print_r($y);
					$this->newcontrol($y) ;
					$m[3]=substr($m[3],strlen($x[0]));
				} else if ($x[2]=='textarea') {
					$y=scanPar($x[3],array('name'=>1));
					$y[0]=CNT_TEXTAREA ;
					$m[3]=preg_replace('~^.*?</textarea>~is','',$m[3]);
					$this->newcontrol($y) ;
				} else if ($x[2]=='select') {
					$y=scanPar($x[3],array('name'=>1,'multiple'=>2));
					if(!empty($y[2])){
						$y['val']=array();
						unset($y[2]);
						$y['par'].=' multiple="multiple"';
					}
					$y[0]=CNT_SELECT ;
					$m[3]=substr($m[3],strlen($x[0]));
					preg_match('~^(.*)</select>~imsU',$m[3],$z);
					$y['options']=$z[1];
					//*-----*/print_r('!!!!!!!!!!'.htmlspecialchars($z[1]));
					$m[3]=substr($m[3],strlen($z[0]));
					$this->newcontrol($y) ;
				}
			}
			if (trim($m[3])) $this->newcontrol(array(CNT_TEXT,$m[3])) ;

			//print_r($this);
		} else {
			// oops конфуз
			//debug($html);
			//file_put_contents('test.txt',$html);
			//backtrace();
			echo 'form not found '.$this->name;
		}
	}

	function form() {
		$no_more_par = 0;
		foreach(func_get_args() as $arg) {
			if (!is_string($arg)){
				$no_more_par=1 ;
				$this->newcontrol($arg);
			} else if ($no_more_par){
				$this->newcontrol(CNT_GROUP,$arg);
			} else {
				$arg=trim($arg);
				if (preg_match('/^(POST|GET)$/i',$arg)) $this->method=strtoupper($arg);
				elseif (preg_match('~^(http|ftp|/)~i',$arg)) $this->action=strtolower($arg);
				elseif(!$this->name)$this->name=$arg;
				else {
					$this->par=$arg;
					$no_more_par=1 ;
				}        
			}
		}
		if (isset($_SESSION)){
			if (isset($_SESSION['FORM_'.$this->name]['_files_']))
			$this->files=$_SESSION['FORM_'.$this->name]['_files_'];
			if (isset($_SESSION['FORM_'.$this->name]['_uri_']))
			$this->uri=$_SESSION['FORM_'.$this->name]['_uri_'];
		}
	}
	function handle() {
//        пїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅ пїЅпїЅпїЅпїЅпїЅпїЅ пїЅпїЅпїЅпїЅпїЅпїЅпїЅ пїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅ
		$this->havenewfile=false;
		$res=FALSE ;
		$x=$GLOBALS["_".$this->method];
		foreach ($this->controls as $i)
		{
			if (isset($this->var[$i->c[1]]))
			if (isset($x[$i->c[1]])){
				$this->var[$i->c[1]] = $i->PrepValue($x[$i->c[1]]);
				$res=TRUE ;
			}
		}
		// store values into session
		if ($res) {
			if (!empty($_FILES))
			{
				foreach ($_FILES as $k=>$f) {
					if (is_array($f['error']))
					foreach ($f["error"] as $key => $error) {
						if ($error == UPLOAD_ERR_OK) {
							$tmp_name = $f["tmp_name"][$key];
							$name = str_replace(array('-',' '),'_',translit($f["name"][$key]));
							if ($tmp_name=my_move_uploaded_file($tmp_name, $this->upload_dir.$name)){
								@chmod($this->upload_dir.$name,0644);
								$this->files[$tmp_name]=$name;
								$this->var['~'.$k]=$tmp_name;
								$this->havenewfile=true;
							}
						}
					}
					else
					if ($f['error'] == UPLOAD_ERR_OK) {
						$tmp_name = $f["tmp_name"];
						$name = str_replace(array('-',' '),'_',translit($f["name"]));
						if(isSet($_GET['do']) && $_GET['do'] == "onlinereg") {
							$name1 = $name;
							for($i=1; $i<9999; $i++) {
								if(!file_exists($this->upload_dir.$name))
									break;
								$name = $i."_".$name1;
							}
						}
						if ($tmp_name=my_move_uploaded_file($tmp_name, $this->upload_dir.$name)){
							@chmod($this->upload_dir.$name,0644);
							$this->files[$tmp_name]=$name;
							$this->var['~'.$k]=$tmp_name;
							$this->havenewfile=true;
						}
					}
				}
			}
		}
        if($res) $this->storevalues();
		return $res;
	}

	function storevalues() {
		unset($_SESSION['FORM_'.$this->name]);
		if (!isset($this->nostore) 
			&& isset($_SESSION) &&($this->var)
		) {
			foreach ($this->var as $k=>$v) {
				$_SESSION['FORM_'.$this->name][$k]=$v;
			}
			$_SESSION['FORM_'.$this->name]['_files_']=$this->files;
			$_SESSION['FORM_'.$this->name]['_uri_']=$this->uri;
		}
	}
	
	function clearvalues() {
		unset($_SESSION['FORM_'.$this->name]);
	}
	
	function getHtml($table=0) {
		// пїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅ пїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅ пїЅпїЅ пїЅпїЅпїЅпїЅпїЅпїЅпїЅ
		//print_r ($this);
		$this->storevalues();
		$hidden_elm='';
		foreach ($this->controls as $k=>$i)
		{
			if (isset($this->var[$i->c[1]]))
				$this->controls[$k]->c['val']=$this->var[$i->c[1]];
			if ($i->IsHidden())$hidden_elm.=$i->getHtml();
		}
		$s='';
		if (is_object($table))
			$s=$table->getHtml($this->controls);
		else { $del = ' '; if (is_string ($table)) $del = $table ;
			$fds=0;
			foreach ($this->controls as $i) {
				if (empty($i->c['next'])) {
					if (($fds==1) && empty($i->c['next'])){
						$s.='</fieldset>'; $fds=0;
					};
					$fds--;
				}

				if (!empty($i->c[0])&&(!$i->IsHidden()))
					if ($i->c[0]==CNT_LABEL) {
						$ss=$i->getHtml();
						if ($ss) {$s.='<fieldset><legend>'.$ss.'</legend>';$fds=2;}
					}  else
						$s.=$del.$i->getHtml();
			};
		};
		if ($table){
			$s='<form action="'.pps($this->action).'"'.
				pp($this->par).
				pp($this->method,' method="','"').
				pp($this->name,' name="','"'). '>'.pps($hidden_elm).$s."</form>";
			return $s;
		}
	}
	function HandleInput() {;}
}

?>