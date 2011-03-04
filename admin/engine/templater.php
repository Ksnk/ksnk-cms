<?php
/**
 *   ���������� ������������
 *    by ksnk <http://forum.dklab.ru/users/ksnk>
 *   ���������:
 *    ��� ������� �����-������� ����������� ���� ��� ����������� � �� ������������� � ���������� PHP-���
 *    ��� ������ � ����� ����������, ����� ����� ������������� � ����������� eval'��. 
 */

/**
 *  ��� ����� �������
 */
if(!defined('TEMPLATE_PATH')){
	define("TEMPLATE_PATH",'.');
}

/**
 * ������ �� ����
 */
if(!is_callable('pps')){
	function pps(&$x) {if(empty($x))return ''; else return $x;}
}

/**
 * ������ �� php4
 */
if(!is_callable('file_put_contents')){
	function file_put_contents($file,$contents){
		$h=fopen($file,'w');
		fwrite($h,$contents,strlen($contents));
		fclose($h);
	}
}

/**
 * ����� ��� ��������� ����������� ����������
 *
 */
class tpl {
	
	/**
	 * ��������� ������� �������� {VAR>>format}
	 */
	function _format(&$par,$tpl){
		if(empty($par)) 
			return '';
		else
			return sprintf($tpl,$par);	
	}

	/**
	 * �������� ����� �� ������ 15 ����
	 */
	function _x15($par){
		if(preg_match('/(?:\S+\s+){15}/',strip_tags($par),$m)){
			return $m[0].' ...';
		}
		return $par;
	}
	
	/**
	 * �������� �������� �� ��� ������ �����
	 */
	function _translit($par){
		return translit($par);
	}
	
	function _getpng($par){
		$tpar=str_replace(array('-',' '),'_',translit($par));
		if(file_exists("uploaded/$tpar.png"))
			return '<img src="uploaded/'.$tpar.'.png" alt="'.htmlspecialchars($par).'">';
		else
			return $par;	
	}
	
	function _html($par){
		return htmlspecialchars($par);
	}
	function _striptags($par){
		//debug($par);
		return strip_tags(htmlspecialchars_decode ($par));
	}
	function _upper($par){
		return strtoupper($par);
	}
	// ��������� ��� � ��������� ������� ������
	
	function _rusDM ($par){
		return toRusDate($par,"j F");
	}	
	function _DM ($par){
		return date("d/m",strtotime($par));
	}	
	function _DMY ($par){
		return date("d.m.Y",strtotime($par));
	}	
	function _Data ($par){
		return date("d",strtotime($par));
	}	
	function _M ($par){
		return date("m",strtotime($par));
	}	
	function _Y ($par){
		return date("Y",strtotime($par));
	}
	function _MY ($par){
		return date("m.Y",strtotime($par));
	}	
	function _rusY ($par){
		return toRusDate($par,"Y �.");
	}	
	
	/**
	 * ����� �������, ������������ �������������, �������
	 * ���������  ::func:x1:x2
	 */
	function _export($_1,$_2='',$_3='',$_4=''){
		static $cache; if(!isset($cache)) $cache=array();
		$x=$_1.$_2.$_3.$_4;
		if(isset($cache[$x]))
			return $cache[$x];
		else	
			return $cache[$x]=_export($_1,$_2,$_3,$_4);
	}

	/**
	 * ��������� ���������� ����������� 
	 * TODO: ��� ������������� �����?
	 */
	function _chk(&$x,$idx,$true='',$false=''){
		if (is_array($x)){
			if(isset($x[0])){
				$xx='';
				for($i=0;$i<count($x);$i++)
					if(isset($x[$i]))
						$xx.=call_user_func ($idx,$x[$i]);
				return $xx;		
			} else
				return call_user_func ($idx,$x);
		} else if ($x===true) return $true ;
		elseif (($x!==0)&&(!$x)) return $false ;
		else return $x;
	}

	/**
	 * ��������-������ 
	 */
	function _a(&$x,$idx){
		if (is_array($x)){
			if(isset($x[0])){
				$xx='';
				for($i=0;$i<count($x);$i++)
					if(isset($x[$i]))
						$xx.=call_user_func ($idx,$x[$i]);
				return $xx;		
			} else
				return call_user_func ($idx,$x);
		} else return $x;
	}

	/**
	 * ��������-������, �������� ��� ��������� 
	 */
	function _ax($x,$idx){
		if (is_array($x)){
			if(isset($x[0])){
				$xx='';
				for($i=0;$i<count($x);$i++)
					if(isset($x[$i]))
						$xx.=call_user_func ($idx,$x[$i]);
				return $xx;		
			} else
				return call_user_func ($idx,$x);
		} else return $x;
	}

	/**
	 * ���������� ��������� 
	 */
	function _b(&$x,$true='',$false=''){
		if ($x===true) return $true ;
		else return $false ;
	}
	function _bx($x,$true='',$false=''){
		if ($x===true) return $true ;
		else return $false ;
	}
	
	/**
	 * ��������� �� ��������� �� ��������� 
	 */
	function _d(&$x,$def=''){
		if($x===true) return '';
		elseif (($x!==0)&&(empty($x))) return $def ;
		else return $x;
	}
}

class templater {
	/**
	 * ������ �������� �� �����
	 *
	 * @var array
	 */
	var $templates =array(); // ������ �������� �� �����
	
	/**
	 * �������� ���� ��������� �������-�������
	 */
	function checktpl(){
		$templates=glob(TEMPLATE_PATH.DIRECTORY_SEPARATOR.'*.tpl');
		$xtime=filemtime(__FILE__);
		if(!empty($templates))
			foreach($templates as $v){
				$phpn='tpl_'.basename($v,".tpl");
				if( !file_exists(TEMPLATE_PATH.DIRECTORY_SEPARATOR.$phpn.'.php')
					||
				  	(max($xtime,filemtime($v))>filemtime(TEMPLATE_PATH.DIRECTORY_SEPARATOR.$phpn.'.php'))
				)
					$this->resolve(array($phpn),'',true);
			}
		$this->compile();	
	}
	/**
	 * ��������� ��������� �������
	 *
	 * @param unknown_type $x
	 * @return unknown
	 */
	function quote($x){
		if (empty($x) && ($x!=='0'))
		    return "''";	
		else if (ctype_digit($x)) 
			return $x;
		if(ctype_digit($x)) 
			return $x;
		else 
			return "'".addcslashes($x,"\\'")."'";
	}
   /**
	*  ���������� ������� ������� � ��������
	*/
	function resolve($idx,$contents='',$force=false){
		global $template_holder;
		static $cache;
		if(!is_array($idx)) // �������� �� ������ ������ 
			return 'fault!'; 
		if(is_callable($idx))
			return '';
		if(!isset($cache[$idx[0]]) && !$force){
			$cache[$idx[0]]=true;
			@include TEMPLATE_PATH.DIRECTORY_SEPARATOR.$idx[0].'.php';
			if(is_callable($idx))	return '';
		}	
		// function
		// ������ ������
		$name=implode('_',$idx);
		if (isset($this->templates[$name])) return $this->templates[$name];
		if(!$contents){
			$file=preg_replace('/^tpl_/','',$idx[0]).'.tpl';
			if(!isset($this->templates[$name]))
				if(is_file(TEMPLATE_PATH.DIRECTORY_SEPARATOR.$file)){
					$this->templates[$idx[0]]=file_get_contents(TEMPLATE_PATH.DIRECTORY_SEPARATOR.$file);
					$this->prebuild($idx[0]);
					
				}	
			// �������� �������
			if(!isset($this->templates[$name])){
				// ������� �������� ���� ������
					return '������ '.$name.' �� ������ ('.getcwd().')';
			}
		} else
			$this->templates[$name]=$contents;
		return $this->templates[$name];
	}
	
	function prebuild($idx){
		$xcontent='';
		$contents=$this->templates[$idx];
		$res='';
		while (preg_match(
			'~^(.*?)(?:<!--begin\:([\w:]+)\s*-->\s*(.*?)<!--end\:\s*\\2\s*-->)~si'
			,$contents,$m))//,$offset))
		{
			$mmm=$m[2];	
			while(isset($this->templates[$idx.'_'.$mmm])){
				$mmm=$m[2].'�'.rand(1,1000);
			}
			$this->templates[$idx.'_'.$mmm]=$m[3];
			$this->prebuild($idx.'_'.$mmm);	
			$xcontent.=$m[1].'{'.$mmm.'}';
			$contents=substr($contents,strlen($m[0]));
		}
		$this->templates[$idx]=	trim($xcontent.$contents);
	}
	
	function compile(){
		//print_r($this->templates);
		$files=array();
		
		foreach($this->templates as $k=>$v){
		$contents=$v;
		$res='';
		$function=array();
		$break=array();
		if (preg_match('/^tpl_[^_]+$/',$k)){
			$file=$k; $index='';
		} else {
			list($x,$file,$index)=explode('_',$k,3);	
			$index=str_replace(':','_',$index);
			$file='tpl_'.$file;
		}
/**
 * �������� 
 * xx 
 * xx?yy:zz - ���������� �����
 * xx|yy - ���� X ���� (������) Y
 * xx::yy:aa:bb - ����� ������� _export(xx,yy,aa,bb)
 */
		while (preg_match(
			'~^(.*?)(?:{([\w:]+)(?:}'.     #class::func   -1
						'|\|([^}]*)}'.     # ... |default  -2
						'|\?\?([^}]*?)(?:\:\:([^}]*))?}'. # ...?true:false -4,5
						'|\?([^}\:]*)(?:\:([^}]*))?}'. # ...?true:false -6,7
						'|(>>[^}]+)}'. #par>>func>>par>>par... -8
						'|\=([^\?^}]+)\?\?([^}\:]*)(?:\:\:([^}]*))?}'. #par=val?? :: } -9 10-true 11-false
						')|{\=([^}]+)}'. #12
						 '|{\!([^}]+)}'. #13
						 '|{{\s*(for|endfor|if|endif|elseif|else)\s*(.*?)\s*}}'. #14+15
					')~si' #12 
			//  1        2              3          4              5
			,$contents,$m))//,$offset))
		{
			if(!empty($m[1]))
				$function[]=templater::quote($m[1]);
//		echo('\n\rxxxxxxxxxx:'.$contents);
			$false='';
			$mmm=trim($m[2]); //$true=trim(pps($m[4])) ; $false = trim(pps($m[5])).trim(pps($m[3]));
			$rres=preg_match('~^(\w*?)::([\w]+)(?:\:(\w+)\b)?(?:\:(\w+)\b)?~i',$mmm,$mm);
			if(!empty($m[14])){
				$m[14]=strtolower($m[14]);
				$x='';
				switch($m[14]){
				case 'for': 
					if(preg_match('/^\s*`[^`]*`\s*$/',$m[15]))
						$m[15]='each('.$m[15].' as $x){';
					break;
				case 'endfor': 
					$m[14]='}';break;
				case 'if': case 'elseif':
					 $x=':'; break;
				}
				$m[14]='; '.$m[14].preg_replace('/`([^`]+)`/','$par["\\1"]',trim($m[15],';: ')).$x.' ;';
				if(empty($function))
				$break[]='""'.$m[14];
				else
				$break[]=implode('.',$function).$m[14];
				$function=array();
			}elseif(!empty($m[13])){
				if(empty($function))
				$break[]='""'.$m[13];
				else
				$break[]=implode('.',$function).$m[13];
				$function=array();
			} elseif(!empty($m[12])){
				$function[]=$m[12];
			} elseif (!empty($m[9])){
				$function[]='tpl::_bx($par[\''.$mmm.'\']==\''.trim($m[9],'=').'\','.templater::quote($m[10]).','.templater::quote(pps($m[11])).')';
			} else	if (isset($m[8])){
				$par=explode('>>',$m[8]);
				if(empty($mm))
					$exp='pps($par[\''.$mmm.'\'])';
				else {	
					foreach($mm as $kk=>$v){
						$mm[$kk]=templater::quote($v);
					}
					$exp='tpl::_ax(tpl::_export('.implode(',',$mm).'),array(\''.$file.'\',\''.pp($index,'','_').str_replace(':','_',$mmm).'\'))';
				}
				$func='';
				$funcx='';
				if(!empty($par[1])){
					$mmm=$par[0];
					$func='tpl::_'.$par[1].'(';
					if(!empty($par[2])){
						$funcx=",'".str_replace("'","\\'",$par[2])."')";	
					} else {
						$funcx=')';
					}
				} 	
				$function[]=$func.$exp.$funcx;
			} else
			if ($rres){
				// export � �����������!
					//debug($mm);
					unset($mm[0]);
					foreach($mm as $kk=>$v){
						$mm[$kk]=templater::quote($v);
					}
					//if(isset($this->templates[$k.'_'.str_replace(':','_',$mmm)]))
					if(isset($m[4])){
						$function[]='tpl::_bx(tpl::_export('.implode(',',$mm).'),'.templater::quote(trim($m[4])).','.templater::quote(trim(pps($m[5]))).')';
					}
					elseif(isset($m[6])){
						$function[]='tpl::_bx(tpl::_export('.implode(',',$mm).'),'.templater::quote(trim($m[6])).','.templater::quote(trim(pps($m[7]))).')';
					}
					else if(isset($m[3])){
						$function[]='tpl::_d(tpl::_export('.implode(',',$mm).'),'.templater::quote(trim($m[3])).')';
					}
					else {
						$function[]='tpl::_ax(tpl::_export('.implode(',',$mm).'),array(\''.$file.'\',\''.pp($index,'','_').str_replace(':','_',$mmm).'\'))';
					}
					//else {	
					//	$function[]='_export('.implode(',',$mm).')';
					//}
			} else if(isset($this->templates[$k.'_'.str_replace(':','_',$mmm)]))
				$function[]='tpl::_a($par[\''.preg_replace('/�\d+$/','',$mmm).'\'],array(\''.$file.'\',\''.pp($index,'','_').$mmm.'\'))';
			else if(isset($m[4])){		
				$function[]='tpl::_b($par[\''.$mmm.'\'],'.templater::quote(trim($m[4])).','.templater::quote(trim(pps($m[5]))).')';
			}
			else if(isset($m[6])){		
				$function[]='tpl::_b($par[\''.$mmm.'\'],'.templater::quote(trim($m[6])).','.templater::quote(trim(pps($m[7]))).')';
			}
			else if(isset($m[3])){		
				$function[]='tpl::_d($par[\''.$mmm.'\'],'.templater::quote(trim(pps($m[3]))).')';
			}
			else {
				$par=explode('>>',$mmm);
				$func='';
				$funcx='';
				if(!empty($par[1])){
					//if(function_exists(array('tpl','_'.$par[1]))){
						$func='tpl::_'.$par[1].'(';
						$funcx=')';
					/*}
					else if (function_exists($par[1])) {
						$func=$par[1].'(';
						$funcx=')';
					}*/
				} 	
				$function[]='(isset($par[\''.$par[0].'\'])?'.
					$func.
					'$par[\''.$par[0].'\']'.$funcx.':\'\')';
			}
			$contents=substr($contents,strlen($m[0]));
		}
		if(!empty($contents))
				$function[]=templater::quote($contents);
		if(!empty($break)){
			$files[$file][]='
function '.pps($index,'_').'(&$par){
	$result='.implode('
	$result.=',$break).'
	return $result'.(empty($function)?'':'.'.implode('.',$function)).';
}';			
		}else		
		$files[$file][]='
function '.pps($index,'_').'(&$par){		
		return '.implode('.',$function).';
}';
	};
		foreach($files as $k=>$v){
			file_put_contents(TEMPLATE_PATH.DIRECTORY_SEPARATOR.$k.'.php','<?php
/**
 *  this file is automatically created "'.date("F j, Y, g:i a").'" from '.str_replace('tpl_','',$k).'.tpl file
 */			
class '.$k.' extends tpl {
'			.implode("
",$v).
'}
?>');
	}
	}
}

function smart_template($idx=null,$par=null,$contents='') {
	static
		$template;
	static 
		$objects;	
	if(!isset($template)){
		$objects=array();
		$template=new templater();
	}
		
	if(empty($idx))
		return $template->checktpl();
		
	if(!is_array($idx))
		$idx=array($idx,'_');	
	$template->resolve($idx,$contents);
	if (!is_null($par)){
		if (is_callable($idx)){
			$name=$idx[0];
			if(!isset($objects[$name])){
				$objects[$name]=new $name();
			}
			return call_user_func (array($objects[$name],$idx[1]),$par);
		} else 
			debug(sprintf('have no template "%s:%s"',$idx[0],$idx[1]));
	}
	return '';
}

smart_template(); // force_compile templates

?>
