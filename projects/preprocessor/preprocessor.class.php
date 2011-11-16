<?php
/**
 * main preprocessor class + xml reader-parcer
 * <%=point('hat','jscomment');%>
 */
$stderr = fopen('php://stderr', 'w');
/** 
 * just stollen and slightly rewriten from  
 * http://ru2.php.net/manual/en/function.touch.php#88028
 * big thanks to author!
 */ 
function betouch($file, $time, $offset = 0){ 
	if(touch($file, $time)){ 
		clearstatcache(); 
		$stored_mtime = $time+$time-filemtime($file);
		if($time==$stored_mtime){ 
			return true; 
		}else{ 
			return touch($file, $stored_mtime); 
		} 
	} 
	return false; 
}
 
class preprocessor{
	var $obcnt=0,
        $debug_str='';
	/**
	 * error handling. donow what to do with them ;(
	 */
	function error ($mess){
		echo $mess."\n" ;
	}

	/**
	 * array with variables exported from outer space 
	 * (xml, command line and so on);
	 */
	private $exported_var=array();
	
	/**
	 * to hold the modification time of all evaluated files we need
	 * to store the last time source files was modified.
	 */
	private function cfg_time($n=null){
		static $cfg_time=0;
		if (!is_null($n)){
			if (is_file($n))
				$cfg_time=max($cfg_time,filemtime ($n));
			else
				$cfg_time=max($cfg_time,$n);
		}
		return $cfg_time;
	}
	
	/**
	 * here we can store our files list
	 */
	private $store=array();
	
	/**
	 * store setter  SRC - DST - ACTION
	 * @param $src - from 
	 * @param $dst - to 
	 * @param $act - what to do (eval||copy)
	 */	
    public function newpair($src,$dst='',$act='eval',$par=''){
        if(empty($par))$par=array();
        //print_r($par);
        $this->store[]=array($src,$dst,$act,$par);
    }

	/**
	 * store getter
	 */
	private function getpair(){
		return array_shift($this->store);
	}
	
	/** 
	 * eval string with dollar sign, internal function
	 */
	private function tmp_callback($m){
		if(array_key_exists($m[1],$this->exported_var)) 
			return $this->exported_var[$m[1]]; 
		else 
			return $m[0];
	}
	/** 
	 * eval string with dollar sign, with default value (just for fun)...
	 * variables stored into 'exported_var' array. 
	 */
	private function evd($s,$default=''){
		if(empty($s)) return $default;
		if(strpos($s,'$')===FALSE) return (string)$s;
		return preg_replace_callback('/\$(\w*)/',array($this,'tmp_callback'),$s);
	}
	
	/**
	 * split part of pathname into real pathname
	 * array - means first nonempty
	 * all the rest nonempty parameter just splited with / sign
	 * so you can call it width
	 *  (path,,somethere,[,, again,onesmore]) and got 'path/somethere/again' route
	 * this uses to simplify calculation of filepath with all this xlm-property  
	 */
	private function path(){
		$path=array();
		foreach(func_get_args() as $arg){
			if(is_array($arg)){	// get a first non empty arg
				$x=''; 
				foreach($arg as $a){
					if(!(empty($a) || $a=='.')){
						$x=$this->evd($a);
						break;	
					}
				};
				$arg=$x;
			} ;
			
			$v=rtrim($this->evd($arg),'/\\');
			$path_res=empty($v) || $v=='.';
			if(!$path_res) $path[]=$v;
		}
		return implode('/',$path);
	} 
	
	/**
	 * some oop handlers to simplify work with new tags
	 */
	
	/**
	 * handle VAR tag.
	 * @param xmlstring $files
	 * @example <var name='hello' default='ok'/>
	 *     to assing Ok to hello variable if no assigned awile
	 * @example <var name='hello'>Ok</var> - to assing Ok to hello variable
	 */
	function handle_var(&$files){
		if ((string)$files['name']=='') 
			$this->error('XML: there is no NAME parameter of VAR tag.') ; // faked variable
		if((string)$files =="") {// just assign a value if no values was a while
			if (isset($this->exported_var[(string)$files['name']])) 
				return ;
		}
		$val=$this->path((string)$files,(string)$files['default']);
		if(!empty($val))
		$this->export((string)$files['name'],$val);
	} 
	
	/**
	 * handle IMPORT tag.
	 * @param xmlstring $files
	 * @example <import name='/common/cms/config.xml'/>
	 */
	function handle_import(&$files){
		if ((string)$files['name']=='') 
			$this->error('XML: there is no NAME parameter of IMPORT tag.') ; // faked variable
		$this->xml_read((string)$files['name']);
	}

    private function obend (){
        ob_end_clean();
        $this->obcnt--;
        $this->debug();
    }

    private function obstart (){
        $this->obcnt++;
        ob_start();
    }

    public function debug (){
        $na=func_num_args();
        if($na>1){
            for ($i=0; $i<$na;$i++){
                $this->debug(func_get_arg($i));
            }
        } else {
            if ($na==1){
                if ($this->obcnt){
                    $this->debug_str.= "\n".func_get_arg(0);
                } else {
                    echo ''.func_get_arg(0);
                }
            } else {
                echo $this->debug_str;
                $this->debug_str='';
            }
        }
    }

	
	/**
	 * read xml file and parse information. 
	 * Look at path function for miracles
	 * @param $xml
	 */
	public function xml_read($xml,$insertbefore=false){
        //$this->debug('xml_read:',getcwd());
		$oldcwd= getcwd() ;
		if (is_file($xml)){
			chdir(dirname($xml));//$this->debug('xml_read:',getcwd());
			$this->cfg_time($xml);
			$config=simplexml_load_file($xml);
		} else {
			$config=new SimpleXMLElement($xml);
		}
		if($insertbefore){
			$sav=$this->store; $this->store=array();
		}
		foreach($config->children() as $files){
			$name='handle_'.strtolower($files->getName());
			if(method_exists($this,$name)){
				call_user_func_array(array($this,$name),array(&$files));
			} else
			if ($files->getName()=='files'){
                foreach ($files->children() as $file){
					$dst=$this->path(array((string)$file['dstdir'],(string)$files['dstdir']));
					if(!empty($dst)) $dst=$this->path($dst,dirname((string)$file));
                    $attributes=array();
                    foreach($file->attributes() as $k=>$v){
                        $attributes[$k]=(string)$v;
                    }
					if ($file->getName()=='echo'){
                        $this->newpair(
							(string)$file,
							!empty($dst)?$this->path($dst,(string)$file['name']):'',
							$file->getName()
                            ,$attributes);
					} else
					foreach(glob($this->path(array((string)$file['dir'],(string)$files['dir']),(string)$file)) as $a){
						$this->newpair(
							realpath ($a),
							!empty($dst)?$this->path($dst,array((string)$file['name'],basename($a))):'',
							$file->getName()
                            ,$attributes);
					}
				}
			}
		}
		if($insertbefore){
			$this->store=array_merge(sav,$this->store);
		}
        //$this->debug(print_r($this,true));
		chdir($oldcwd);
	}
	/**
	 * export variable from outer space (command line)
	 * @param unknown_type $var
	 * @param unknown_type $val
	 */
	public function export($var,$val){
		$this->exported_var[$var]=$val;
	}

	/**
	 * prepare the file to be evaluated
	 * 
	 * read and switch php-tags
	 * @param $src - file name
	 */
	private function prep_file($src,$isfile=true){
		if ($isfile){
			if(!is_file($src)) return '';
			$s=file_get_contents($src);
			if(strpos($s,'<'.'%')===FALSE) return null;
		} else {
			$s=$src;
		}
		$s=str_replace(
			array('<?','?>','<'.'%=','<'.'%','%'.'>'),
			array('<@','@>','<'.'?php echo ','<'.'?php ','?'.'>'),$s
		);
		$this->obstart();
		return '?'.'>'.$s;
	}
	
	/**
	 * switch php tags back
	 * @param $dst - file to store evaluated result
	 */
	private function post_process($dst='',$time=0){
		$s=ob_get_contents();
		$s=str_replace( // + final linefeed correcion
		// replace LF with intel LF,
		// all MAC's LF replaced on Intel
		// Replace Intel LF with Windows LF for my editor glitched with different  :(
			array('<@','@>','%/>','</%', "\r\n","\r","\n"),
			array('<?','?'.'>','<'.'%','%'.'>',"\n","\n","\r\n"),$s
		);
		$this->obend();
		if(!empty($dst)){
			$x=pathinfo($dst);
			if(!is_dir($x['dirname']))mkdir($x['dirname'], 0777 ,true);
			if(!is_file($dst) || (filemtime($dst)<max($time,$this->cfg_time()))){
				file_put_contents($dst,$s);
				betouch ($dst,max($time,$this->cfg_time() ));
				return true;
			}
		}
		return false;
	}


	public function _handleNotice($errno, $errstr, $errfile, $errline)
    {
    	if(error_reporting()) return ;
        $trace = debug_backtrace();
        array_shift($trace);
        printf('notice %s,%s,%s,%s,%s'
        	,$errno, $errstr, $errfile, $errline, print_r($trace,true));
    }
    
    public function _handleFatal()
    {
        $error = error_get_last();
        if ( !is_array($error) || !in_array($error['type'], array(E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR))) {
            return;
        }
        //if (!empty($GLOBAL['evaluated']))
        print_r($this);
        $error['file']=$this->srcfile;
						
        printf('fatal %s,%s,%s,%s'
        ,$error['type'], $error['message'], $error['file'], $error['line']);
     }
	
	/**
	 * execute all file-pairs in a row
	 */
	public function process(){
		if(!empty($this->exported_var)) 
			extract($this->exported_var);
		$___total_cnt=0;
		$___all_cnt=0;
		while($___m=$this->getpair()){
			$error = error_get_last();
			if(is_array($error)){
				break;
			}
			$srcfile=$___m[0];
			$dstfile=$___m[1];
			$___all_cnt++;
			switch($___m[2]){
				case 'eval':
				case 'file':
				case 'echo':
                    $filemtime=0;
                    if(is_file($srcfile))
                        $filemtime=filemtime ($srcfile);
                    if(!empty($___m[3]))
                        if(!empty($___m[3]['force']))
                            $filemtime=time();
                    $___s=$this->prep_file($srcfile,$___m[2]!='echo');
					if ($___m[2]=='echo')
						$srcfile='';
					if(!is_null($___s)){
                        $oldcwd= getcwd() ;
                        if (is_file($srcfile))
                            chdir(dirname($srcfile));//$this->debug('xml_read:',getcwd());
                        eval($___s);
                        chdir($oldcwd)  ;
						if (empty($dstfile)){
							$this->cfg_time($filemtime);
						}
                        if($this->post_process($dstfile,$filemtime)){
							echo "e>$srcfile";
							if (strlen($srcfile)+strlen($dstfile)>75){
                                echo "\n\r  ";
                            }
                            echo "-->$dstfile";
							$___total_cnt++;
                            echo "\n\r";
						}

						break;
					}
				case 'copy':
					if(empty($dstfile))break;
					$___s=pathinfo($dstfile);//echo '"'.$dstfile.'" ';print_r($___s);
					if(!empty($___s['dirname']) && !is_dir($___s['dirname']))
						mkdir($___s['dirname'], 0777 ,true);
					if(!is_file($dstfile) || (filemtime($dstfile)<filemtime($srcfile))){
						echo "c>$srcfile";	
						copy($srcfile,$dstfile);
						betouch ($dstfile,filemtime($srcfile));
                        if (strlen($srcfile)+strlen($dstfile)>75){
                           echo "\n\r  ";
                        }
						echo "-->$dstfile"."\n\r";//  was last modified: " . date ("F d Y H:i:s.", filectime($srcfile));
						$___total_cnt++;
					} 
					break;	
			}
		}
		$error = error_get_last();
		if(is_array($error)){
			fwrite($GLOBALS['stderr'],
	    	sprintf('Error: %s(%s) module raised "%s" '."\n\r"
	        	,realpath($srcfile), $error['line'], $error['message']));
		}		
		printf("
total %s of %s files copied.\n\r",$___total_cnt,$___all_cnt);
	}
}