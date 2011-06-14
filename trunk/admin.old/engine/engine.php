<?php
/**
 * absolute wrapper - reflector
 */
	class plugin {

		/**
		 * ссылка на элемент-хозяин
		 * @var engine
		 */
		var $parent;
		/**
		 * ссылка на базу данных
		 * @var DbSimple_Generic_Database
		 */
		var $database;

		function plugin(&$parent){
			global $DATABASE;
			$this->parent=&$parent;
			$this->database=&$DATABASE;//DATABASE();
		}
	}
	
	$EXPORTS = array();
	
/**
 *  Base plugin-manager class
 */
	class plugins {
		var $par=array('error'=>''),
			$tpl=MAIN_TPL,
			$exports=array(),
			$req_cnt=0,
			$fn=array();

		function findUser($a='',$b=0,$c=false){
			return $this->ffirst('find_user',$a,$b,$c);
		}

		function par($par,$what){
			$this->parent->par[$par]=$what;
		}
		function title($tit){
			$this->parent->par['title']=$tit;
		}

		function plugins() {
			global $DATABASE;
			$this->RIGHT=new rights();
			$this->exports['MAIN']=&$this;
			$this->parent=&$this;
			$this->parent->par['error']=pps($_SESSION['error_msg']);
			if(isset($_SESSION['error_msg']))unset($_SESSION['error_msg']);
			$this->database=&$DATABASE;//DATABASE();
			$arg_list=func_get_args();
			foreach($arg_list as $v) $this->export($v);
		}

/* execute all methods */
		function execute($method,$par=0){
			$result=false;
			foreach($this->exports as $k=>$v)
				if(method_exists($this->exports[$k],$method)) {
					$result=$result || call_user_func(array(&$this->exports[$k],$method),$par);
				}
			return $result;
		}
/* exec till first not null */
		function ffirst($method){
			$par=func_get_args();array_shift($par);
			/*
			if(array_key_exists($method,$this->fn)){
				$f=$this->fn;
				if
				call_user_func_array$fn()
			}
			*/
			foreach(array_keys($this->exports) as $k)
				if(method_exists($this->exports[$k],$method)) {
					if(!is_null($x=call_user_func_array(array(&$this->exports[$k],$method),$par)))
						return $x;
				}
			return null;
		}
/*		function plug_function($name,$f){
			$this->fn[$name]=$f;
		}*/
/* export class as plugin */
		function export($n,$method=''){
			global $EXPORTS;
			if (isset($this->exports[$n]));
			else {
				if(isset($EXPORTS[$n])) 
					$nn=$EXPORTS[$n];
				else 
					$nn=$n;
				$this->exports[$n]=LoadClass($nn,array(&$this));
			}
			if ($method==''){
				return  $this->exports[$n];
            }
			else if (method_exists($this->exports[$n],$method)){
				$arg_list = array_slice(func_get_args(),2);
				return call_user_func_array(array(&$this->exports[$n],$method),
					$arg_list);
			}
			else
				return null;
		}
/* throw template filled by values */
		function template($par=array(),$echo=true){
			$this->parent->par=array_merge($this->parent->par,$par);
			$x=	smart_template($this->tpl,$this->parent->par); // print_r($this->par);
			if($echo)
				echo $x;
			else
				return $x;
		}

		function _tpl($name,$method,$par=array()){
			static $cache;
			if(is_array($name)){
				if($method=='_'){
					$method='_'.$name[1]; $name=$name[0];
				} else {
					$name=$name[0];
				}
			}
			if(empty($cache[$name])){
				if(!class_exists($name))
					require_once TEMPLATE_PATH.DIRECTORY_SEPARATOR.$name.".php";
				if(!class_exists($name))
					return sprintf('method %s::%s not found',$name,$method);
				$cache[$name]=new $name();
			}
			$par=array_merge($this->par,$par);
			$x=	$cache[$name]->$method($par);
			return $x;
		}
		
	}

	function _export($a,$b,$c='',$d=''){
		global $engine;
		if($a) return $engine->export($a,$b,$c,$d);
		else return $engine->ffirst($b,$c,$d);
	}

	function arr_scan(&$arr,$idx,$val,$res=null){
		foreach($arr as $k=>$v) {if ($v[$idx]==$val) return $res?$v[$res]:$v;}
		return $val;
	}

/**
 * вывод числа по русски прописью
 * @example prop::sema(-1234563,"товар||а|ов")
 *   1 234 563 товара
 * @example prop::num2str(1234.34,prop::prep("рубл|ь|я|ей","+копе|йка|йки|ек"))
 *   одна тысяча тридцать четыре рубля 34 копейки
           ' '       => prop::prep(), // число прописью - м.р.
             'ж.р.'    => prop::prep('+'), // число прописью - ж.р.
             'руб.'    => prop::prep("рубл|ь|я|ей","+копе|йка|йки|ек"),   // рубли
             'долл.'   => prop::prep("доллар||а|ов","цент||а|ов"),        // доллары
             'фунт.'   => prop::prep("фунт||а|ов","+пенни|||"),           // фунты
             'йен.'    => prop::prep("+йен|а|ы|"),                        // йены
             'грив.'   => prop::prep("+грив|на|ны|ен","+копе|йка|йки|ек"),// гривны
             'молекул.'=> prop::prep("+молекул|а|ы|"),// молекулы
 *
 */
class prop {

  var $hang,$dec,$numbf,$numb,$thau;
  
  /**
   * singleton-like самоинициализирующийся инстанс
   *
   * @return prop
   * @internal 
   */
  function &instance(){
  	static $me;
  	if(!isset($me)) { 
  		$me=&new prop();
  		$me->prop();
  	}
  	return $me; 
  }
  
  /**
   * инициализация необходимых запчастей для прописи
   *
   * @return prop
   */
  function prop() {
  	
     $this->hang=explode("|","|сто|двести|триста|четыреста|пятьсот|".
                       "шестьсот|семьсот|восемьсот|девятьсот");
     $this->dec =explode("|","||двадцать|тридцать|сорок|пятьдесят|шестьдесят|".
                       "семьдесят|восемьдесят|девяносто");
     $this->numb=explode("|","|один|два|три|четыре|пять|шесть|семь|восемь|".
                       "девять|десять|одиннадцать|двенадцать|тринадцать|".
                       "четырнадцать|пятнадцать|шестнадцать|семнадцать|".
                       "восемнадцать|девятнадцать");
     $this->numbf=explode("|","|одна|две");
     $this->thau=$this->prep(  "+тысяч|а|и|",      "миллион||а|ов",    "миллиард||а|ов",
                   "триллион||а|ов",   "квадриллион||а|ов","квинтиллион||а|ов",
                   "секстиллион||а|ов","септиллион||а|ов", "октиллион||а|ов",
                   "нониллион||а|ов",  "дециллион||а|ов"
                );
  }
  
 /**
  *  изготовление массива из входных параметров
  */
	function prep($ss=' ') {
		$res=array();
		$ss = func_get_args();
		for ($i = 0,$numargs=count($ss); $i < $numargs; $i++) {
			if(is_string($ss[$i])) {
				$bas=explode("|",trim($ss[$i],'+')."||||",5);
				$res[$i]['fem']= ($ss[$i]{0}=='+');
				for($j=1;$j<4;$j++)
					$res[$i][$j]=" ".$bas[0].$bas[$j];
			}
		}
		return $res ;
	}
  /**
   * выводит прописью число меньше 1000 с подписью, 
   * если число отрицательное - оно выводится цифрами, с разбивкой по тысячам
   */
	function sema($n,$s=' ',$last=false)     // $last - обязательно дописывать подпись
	{
	  	if(empty($this) 
	  	)
	  		$me=prop::instance();
	  	else
	  		$me=&$this;	
	   	if(!is_array($s)){
	   		$s=prop::prep($s);$s=$s[0];
	   	}
		$res="";
	
		if   ($n<0) { 
			$n=-$n; 
			if ($n>=1000){
				$res.=$me->sema(-floor($n/1000))." "; 
				$n %=1000 ;
			} 
		    $res.=sprintf('%2d',$n);while($n>20){$n %=10;}
		} else {
			if ($n>=100){ $res.=$me->hang[floor($n/100)]." "; $n %=100 ;}
			if ($n>=20) { $res.=$me->dec[floor($n/10)]." "; $n %=10 ;}
			if  ($n<3) $res.=!empty($s['fem'])?$me->numbf[$n]:$me->numb[$n];
			else $res.=$me->numb[$n];
		}

		if ($n==0) { if ($res || $last) $res.=$s[3]; }
		else if ($n==1) $res.=$s[1] ;
		else if ($n<5) $res.=$s[2] ;
		else $res.=$s[3] ;

		return $res ;
	}
 /**
  *  вывод денежной суммы прописью с сотыми по русски с соблюдением спряжения
  *  числа от 0 до 10**33
  *  запись может содержать только цифры и 1 точку
  *  число копеек отделено .
  *  значение копеек - первые 2 цифры, причем .5 == .50 , .456==.45
  *
  *  $LL - собственно строка
  *  $valute - индекс денежных подписей
  *  $kop - копейки выводить обязательно, даже если 0. или только в случае не 0!
  */
	function num2str($LL,$valute=FALSE,$kop=FALSE)
	{
		if(empty($this) || !is_a($this,'prop'))
	  		$me=prop::instance();
		else
			$me=&$this;	
		if(!$valute)$valute=$me->prep() ;
        $mm=explode('.',str_replace(',','.',$LL),2);
		if (empty($mm[1]))$mm[1]=0;
		else {
			if (strlen ($mm[1])<2) $mm[1].='00';
			$mm[1]=intval($mm[1]{0}.$mm[1]{1}) ;
		} ;
    // вместо $m=str_split(str_repeat(' ',3-strlen($mm[0])%3).$mm[0],3) приходится использовать
		$m =explode(' ',trim(chunk_split(str_repeat('0',3-strlen($mm[0])%3).$mm[0],3,' ')));
		$res = '' ;$CUR = 1;
    // reset($m);
		for ($i=count($m)-2,$j=0;$i>=0;$i--,$j++) {
			$res.=' '.$me->sema(intval($m[$j]),$me->thau[$i]);
		}
		$x=intval($m[$j]);
		if ((!$res)&&(!$x))
			$res.=' 00'.$me->sema(0,$valute[0],true);
		else
			$res.=' '.$me->sema($x,$valute[0],true);
		if (isset($valute[1])) {
			if($mm[1]) {
				$res.=' '.$me->sema(-$mm[1],$valute[1],true);// копейки цифрами
			} elseif ($kop)
				$res.=' 00'.$me->sema(0,$valute[1],true);
		}

		return trim($res);
	}
}
/*
echo prop::sema(-1234563,"товар||а|ов");
echo prop::sema(0,"товар||а|ов");
//1 234 563 товара
echo prop::num2str(1234.34,prop::prep("рубл|ь|я|ей","+копе|йка|йки|ек"));
 */
?>