<?php
/**
 * Jinja language parcer
 * ----------------------------------------------------------------------------
 * $Id:Jinja4PHP. Jinja2 language template, written by Ksnk (sergekoriakin@gmail.com)
 * Ver : 0.8(beta), Rev: 1376, Modified: 2010/12/02 17:07:47$
 * ----------------------------------------------------------------------------
 * License GNU/LGPL - Serge Koriakin - october 2010
 * documentation mostly same as http://jinja.pocoo.org/docs/templates/
 * ----------------------------------------------------------------------------
 */

if(!defined('TEMPLATE_PATH')) define('TEMPLATE_PATH',dirname(__FILE__).DIRECTORY_SEPARATOR.'templates');

class tpl_parser extends parser {
	protected 
	
	/**
	 * ������ ����� ����� ��� �������� ������ ������
	 */
	   $lines = array(),
	   
  /**
   * ������ ����� ��� ��������� �� ����
   */
	   $options= array(
            // Enviroment setting
            'BLOCK_START'       =>      '{%',
            'BLOCK_END'         =>      '%}',
            'VARIABLE_START'    =>      '{{',
            'VARIABLE_END'      =>      '}}',
            'COMMENT_START'     =>      '{#',
            'COMMENT_END'       =>      '#}',
			'COMMENT_LINE'      =>      '##',
			'trim'				=>		true,
          )
          
        , $opensentence=array() // �������� �������� �����, ��� ������������ 
        						// � ��� ������������� ����������
        
     	, $locals=array() // ���� ��������������� � �������� ���������
    	, $ids_low=0   // ������ ������� ������� ���������
    	; 
        
	function __construct($class='compiler'){
		parent::__construct();
		$this->t_conv['E']='TYPE_SENTENSE';
		$this->error_msg['unexpected construction']='something strange catched.'	;
	}
	
	
	function function_callstack($op1,$op2){
		//������� �� �������
		if($op1->type=='TYPE_OBJECT'){
			$op=call_user_func($op1->handler,$op1,$op2,'call');
			if($op)
				$this->pushOp($op);
		} elseif(isset($this->func[$op1->val])){
				//$op2 �����
				$x=$this->func[$op1->val]->val;
				if (is_callable($x)){
					$op=call_user_func($x,$op1,$op2);
					if($op)
						$this->pushOp($op);	
				} elseif (is_string($x)){
					$op1->val=sprintf($x,$this->to('S',$op2)->val);
					$op1->type='TYPE_OPERAND';
				} else 
					$this->error('wtf3!!!');
		} else {
			//����� ������������
			if($op2->type=='TYPE_LIST'){
				// call macro
				$arr=array();$arrkeys=array();
				for($i=0,$max=count($op2->value['value']);$i<$max;$i++){
					if(is_null($op2->value['value'][$i])){
						$arr[]=$this->to('S',$op2->value['keys'][$i])->val;
					} else {
						$arrkeys[]=array(
							'key'=>$op2->value['keys'][$i]->val,
							'value'=>$this->to('S',$op2->value['value'][$i])->val
						);
					}
				}
				$op1->val=$this->template('callmacro',array('name'=>$op1,'param'=>$arr,'parkeys'=>$arrkeys));
				$op1->type='TYPE_SENTENSE';
				return $op1;
			} else
				$this->error('have no function '.$op1);
		}
		return $op1;
	}
	
	/**
	 * ��������� ���� block, ���� �������� ������ �������� "��������" ���������
	 */
	function block_internal( $tag_waitingfor=null,&$tag=null ){
		if(empty($tag))
			$tag=array('tag'=>'block','operand'=>count($this->operand));
		$data=array();	
    	$this->newop('(');
		do switch($this->getNext()){
			case 'TYPE_STRING':
			case 'TYPE_STRING1':
				$arr=array('TYPE_STRING','value');
				do{
					if($this->curlex>1){
						$lex=$this->lex[$this->curlex-2];
						if(empty($lex->trim))
							$lex->trim=true;
						else
							break;	
						if($lex->val=='-'.$this->options['BLOCK_END']){
							array_unshift($arr,'triml');
							break;
						}
					}
					if($this->options['trim']){
						array_unshift($arr,'trimln');
					}
				} while(false);
				// �������� �� ���� ��������� ����������� � ������������ ���������
				$data[]=array('string'=>$this->to($arr, $this->op));
				break;
			case 'TYPE_EVAL':
				$this->getNext();
				if(!empty($tag_waitingfor) && in_array($this->op->val,$tag_waitingfor)){
					$this->back();
					break 2;
				}
				if (method_exists($this,'tag_'.$this->op->val)){
					call_user_func(array($this,'tag_'.$this->op->val));
					$op=$this->popOp();
					if(!empty($op)){
						$op=$this->to('S',$op);
						if($op->type=='TYPE_SENTENSE')
							$data[]=array('data'=>$op->val);
						else	
							$data[]=array('string'=>$op->val);
					}
					break;
				} else {
					$this->back(); // goto TYPE_ECHO
				}
			case 'TYPE_ECHO':
				$this->getExpression();
				if($this->op->val!=$this->options['VARIABLE_END']) {
					$this->error('unexpected construction2');
				}
				$op=$this->popOp();
				$op=$this->to('S',$op);
				if($op->type=='TYPE_SENTENSE')
					$data[]=array('data'=>$op->val);
				else	
					$data[]=array('string'=>$op->val);
				$this->getNext();
				break;
			case 'TYPE_EOF':
				$this->back();
				break 2;
			default:
				$this->error('unexpected construction5');
				break 2;
		} while(true);
		$this->newop(')'); // �������� ��� ��������
		
		/*
		 * ����������� ������ ��� ������ ����� � �������
		 */
		array_unshift($data,array('string'=>"''"));
		array_push($data,array('string'=>"''"));
		$tag['data']=array();
		$laststring=false;
		$lastidx=-1;
		foreach	($data as $d){
			if(empty($d['data'])){
				if($laststring){
					if($d['string']!='' && $d['string']!="''"){
						if(is_array($tag['data'][$lastidx]['string'])){
							array_push($tag['data'][$lastidx]['string'],$d['string']);	
						} else {
							if($tag['data'][$lastidx]['string']=="''")
							$tag['data'][$lastidx]['string']=array(
								$d['string']
							);
							else
							$tag['data'][$lastidx]['string']=array(
								$tag['data'][$lastidx]['string'],
								$d['string']
							);
						}
					}
				} else {
					$laststring=true;
					$tag['data'][]=$d;
					$lastidx++;
				}
			} else {
				$laststring=false;
				$tag['data'][]=$d;
				$lastidx++;
			}
		}
		
		if(!empty($tag['name'])){
			$t=&$this->opensent('class');
			$t['data'][]=$this->template('block',$tag);
			$this->pushOp($this->oper($this->template('callblock',$tag),'TYPE_OPERAND'));
		} else	
    		$this->pushOp($this->oper($this->template('block',$tag),'TYPE_SENTENSE'));
	}
	
	/**
	 * ������� ��������� �������� ��������� ���������� 
	 * @param $id
	 */
	function checkId($id){
		for($i=$this->ids_low;$i<count($this->locals);$i++){
			if($this->locals[$i]==$id)
				return true;
		}
		return false;
	}
	
	protected function get_reg(&$types){
/**
 * ������ �����, ������������ ����������. ������� �� ���-����� � ���������
 */
		$types=	array(0 //0 - just skip
			,0,'TYPE_STRING'
			,'TYPE_DIGIT'
			,'TYPE_COMMA'
			,'TYPE_OPERATION' //5
			,'TYPE_ID' //6  TYPE_OPERAND
			,'TYPE_OPERATION'  //7
			,'TYPE_COMMA' //8
		);	
		return '#[\n\r\s]*' // # - ������� ��������
			.'(?:'	#1#2 - ����������� ��� ����� �� ����� - TYPE_OPERAND
				.'([\'`"])((?:[^\\1\\\\]|\\\\.)*?)\\1'  
			.'|(\d\w*)'//	#6 - ����� ��� �����, ������������ � ����� - TYPE_DIGITS
			.'|('.$this->options['VARIABLE_END'].'|-?'.$this->options['BLOCK_END'].')'//	������ �����
			.'|'	#4,5 - �������������� �������� - TYPE_OPERATION
				.'((?:'.implode('|',$this->cake['WORD_OP']).')(?=\b)|'.implode('|',$this->cake['MC_JUST_OP']).')'
			.'|(\w+)'#6 - ������ ����� TYPE_OPERAND			
			.'|'	#7 - ������������� �������� - TYPE_OPERATION
				.'(['.implode('',$this->cake['JUST_OP']).'\!])' 
			.'|(.)'#8 - ������������� ����� ���������� - TYPE_COMMA 
			.')#si';

	}
	
	/**
	 * ������ ������ ����������� - ������� ������;
	 * ����� � ������ ����� ������������ ������� ��� ���������� ����� �������������;
	 * ���������� ������ ���� ������ �����
	 * @param $script
	 */
	function makelex($script){
		$this->script=$script;
		$this->operand=array();
		$this->operation=array();
		$this->lex=array();
		$this->curlex=0;
		$types=array();
		$curptr=0;
		// ��������� �������
		preg_match_all('/$/m',$script,$m,PREG_OFFSET_CAPTURE);
		foreach($m[0] as $k=>$v){
		  $this->lines[$v[1]]=$k;
		}
		
		// �������� ��������� ���������
		$reg = $this->get_reg($types);
		$reg0="~(.*?)(";
		foreach(array('COMMENT_LINE','VARIABLE_START','COMMENT_START')as $r)
			$reg0.=preg_quote($this->options[$r],'#~').'|';
		$reg0.=preg_quote($this->options['BLOCK_START'],'#~').'\-?|$)~si';
		//echo $reg0;
		$total=strlen($script);
		
		// ����� ������ ���������� ���� �������������
		while ($curptr<$total && preg_match($reg0,&$script,$m,0,$curptr))
		{
			if($m[0]=='') break; // ���-�� ������������ � ����
			
			if($m[1]!==''){ 
				$this->lex[]=$this->oper($m[1],'TYPE_STRING',$curptr);
			}
			
			$curptr+=strlen($m[0]);
			
			if ($m[2]=="") break; // ����� ��������� �����
			
			if ($m[2]==$this->options['COMMENT_LINE']){ // ����������� �� ��� �����
				if (preg_match('~(.*?)[\r\n]~i',&$script,$m,0,$curptr)){
					$curptr+=strlen($m[1]);
					continue;
				}
			} elseif ($m[2]==$this->options['COMMENT_START']) { // �����������? - ���� ���� � ���������� ����
				//$rreg='~.*?'.preg_quote($this->options['COMMENT_END'],'#~').'~si';
				if (preg_match('~.*?'.preg_quote($this->options['COMMENT_END'],'#~').'~si',&$script,$m,0,$curptr)){
					$curptr+=strlen($m[0]);
					continue;
				}
			} elseif ($m[2]==$this->options['BLOCK_START']){
				$this->lex[]=$this->oper('','TYPE_EVAL',$curptr);
			} elseif ($m[2]==$this->options['BLOCK_START'].'-'){
				//���� ���������� �������
				if(!empty($this->lex)){
					$lex=$this->lex[count($this->lex)-1];
					if($lex->type=="TYPE_STRING" ){
						$lex->val=preg_replace('/\s+$/s','',$lex->val);
					}
				}
				
				$this->lex[]=$this->oper('','TYPE_EVAL',$curptr);
			} else {
				$this->lex[]=$this->oper('','TYPE_ECHO',$curptr);
			};

			// �������� ��������� ������� �������������
			$first=true;
			while ($curptr<$total && preg_match($reg,&$script,$m,0,$curptr))
			{
				$pos=$curptr;
				$curptr+=strlen($m[0]);
				if(!empty($m[1])){
					$op=$this->oper(stripslashes($m[2]),'TYPE_STRING',$pos);
					if ($m[1]=="'") 
						$op->type.='1';
					elseif ($m[1]=="`") 
						$op->type.='2';

					$this->lex[]=$op;
				} else
				for($x=count($types)-1;$x>2;$x--){
					if(isset($m[$x]) && $m[$x]!=""){
						$op=$this->oper(strtolower($m[$x]),$types[$x],$pos);
						$op->orig=$m[$x];
						$this->lex[]=$op;
						if($types[$x]=='TYPE_COMMA' && strlen($m[$x])>1)
							break 2;
						// ����������� � ����� RAW
						elseif ($first && $m[$x]=='raw'){
							// ���� ����������� ��� raw
							if(!preg_match('~.*?'
							.preg_quote($this->options['BLOCK_END'],'#~')
							.'(.*?)'
							.preg_quote($this->options['BLOCK_START'],'#~')
							.'\s*endraw\s*'
							.preg_quote($this->options['BLOCK_END'],'#~')
							.'~si',
							&$script,$m,0,$curptr))
							$this->error('endraw missed');
							$curptr+=strlen($m[0]);
							array_pop($this->lex);array_pop($this->lex);
							$this->lex[]=$this->oper($m[1],'TYPE_STRING',$curptr);
							break 2;
						}
						else	
							break;
					}
				};
				$first = false;
			} 
		}
		$this->lex[]=$this->oper("\x1b",'TYPE_EOF',$curptr);
	}
	
    function newId($op){
    	// ���������� ID ��� ����� �������������
    	if(is_string($op))
    		$this->locals[]=$op;
    	else	
    		$this->locals[]=$op->val;
    	return $op;
    }
    
	function &opensent($sent){
		for($i=count($this->opensentence)-1;$i>=0;$i--){
			if($this->opensentence[$i]['tag']==$sent)
				return $this->opensentence[$i];
		}
		return null;
	}
	
	function function_filter($op1,$op2){
		$this->pushOp($op2);
		$this->pushOp($op1);
		$this->storeparams=1;
		return false;
	}
		
	function function_point($op1,$op2){
		//������� �� �������
		if($op1->type=='TYPE_OBJECT'){
			return call_user_func($op1->handler,$op1,$op2,'attr');
		}
		$this->to('I',$op1);
		if($op2->type='TYPE_ID') $op2->type='TYPE_STRING';
		$this->to('S',$op2);
		$op1->val.='["'.pps($op2->orig,$op2->val).'"]';
		$op1->type='TYPE_XID';
		return $op1;
	}

	/**
	 * ��������� ����������� ID. 
	 * @param $id
	 */ 
    function &resolve_id(&$op){
    	return $this->pushOp($op);
    } 
	
/**
 * �����o��� ���� macros
 * @example
 * // ������� - �������� ������������
 * function _macroname($namedpar, //noname section
 * 		$par1=null,$par2=1,,) {
 *   if(!empty($namedpar)) export($namedpar);
 *   ... 
 * }
 * @example
 * // ����� ������������
 * if(!empty($this->macros[macroname]))
 * cal_user_func($this->macros[macroname],$namedpar,$par1,$par2,...);
 * 
 * �������������� ������� ������������� ���������� �������! �������������� �� ����
 * 
 * @example
 * // ����������� ������
 * if(!empty($this->macros[macroname]))
 * $this->macros[macroname]=array($this,'_'.macroname);
 * 
 * @example
 * // ������
 * $this->imported['TEMPLATENAME']=new TEMPLATENAME();
 * array_merge($this->macros,$this->imported['TEMPLATENAME']->macros)
 * 
 */ 
    function tag_macro(){
		$tag=array('tag'=>'macros','operand'=>count($this->operand),'data'=>array());
		$this->getNext(); // name of macros
		// ���������������� ��� �������
		$tag['name']=$this->to('TYPE_LITERAL',$this->op)->val;
		$this->getNext(); // name of macros
		if($this->op->val!='(') {
			$this->error('expected macro parameters');
		}
		$par=$this->get_Parameters_list('=');
		$arr=array();
		for($i=0,$max=count($par['value']);$i<$max;$i++){
			if(is_null($par['value'][$i])){
				$arr[]=array('name'=>$this->to('TYPE_LITERAL',$par['keys'][$i])->val);
			} else {
				$v=$this->to('S',$par['value'][$i])->val;
				if(!$v) $v='0 ';
				$arr[]=array(
					'name'=>$this->to('TYPE_LITERAL',$par['keys'][$i])->val,
					'value'=>$v
				);
			}
		}
		$tag['param']=$arr;
    	$this->getNext();
    	if($this->op->val!=')')
    		$this->error('expected )');
		$this->getNext();
		$id_count=count($this->locals);
		foreach($tag['param'] as $v){
			$this->newId($v['name']);
		}
    	$this->block_internal(array('endmacro'),$tag);
    	$op=$this->popOp();
    	$tag['body']=$this->template('block',$tag);
    	array_splice($this->locals, $id_count);
    	$this->getNext();
    	if ($this->op->type!='TYPE_COMMA') 
    		$this->getNext();
    	$sent=&$this->opensent('class');
    	if(!empty($sent)){
    		if(empty($sent['macro'])){
    			$sent['macro']=array();
    		}
    		$sent['macro'][]=$tag['name'];
    	}
 	}
/**
 * �����o��� ���� block
 */ 
    function tag_block(){
		$tag=array('tag'=>'block','operand'=>count($this->operand),'data'=>array());
		$this->getExpression(); // �������� ��� ��������������
    	$tag['name']=$this->popOp()->val;
    	$this->getNext();
    	$this->block_internal(array('endblock'),$tag);
    	$this->getNext();
    	if ($this->op->type!='TYPE_COMMA') 
    		$this->getNext();
	}
	
/**
 * �����o��� ���� for
 * @param $id
 */ 
    function tag_for(){
    	// ������� ���� for
    	// ������ �����:
    	// for OPERAND in EXPRESSION [if EXPRESSION]
    	// ������������� else 
    	// �������� endfor
    	$for =array('tag'=>'for','operand'=>count($this->operand));
    	$this->opensentence[]= &$for;
    	//$this->newop('(');
    	$this->getExpression(); // �������� ��� ��������������
    	$for['index']=$this->newId($this->popOp());
    	$this->newId($this->oper('loop','TYPE_ID'));
		$for['index']=$this->to(array('I','value'),$for['index']);
    	do {
    		$this->getNext();
    		switch(strtolower($this->op->val)){
    			case 'in':
    				$this->getExpression();
    				$for['in']=$this->popOp();
					$for['in']=$this->to(array('I','value'),$for['in']);
    				break;
    			case 'if':	
    				$this->getExpression();
    				$for['if']=$this->to(array('*','value'),$this->popOp());
    				break;
    			case 'recursive':	
	   				$for['recursive']=true;
    				break;
    			default:
    				if($this->op->type== 'TYPE_COMMA')
    					break 2;
    				else
    					$this->error('unexpected construction1');	
    		}
    	} while(true);
    	//$this->opensentence[]=$for;
    	
    	$for['else']=false;
    	do {
    		$this->block_internal(array('else','endfor'));
    		$this->getNext();
        $op=$this->popOp();
     		if($this->op->val=='else') {
     			$for['body']=$this->to(array('TYPE_SENTENSE','value'),$op);
    			$for['else']=true;
          $this->getNext(); // ����� ������, ����������� ��� 
     		} elseif ($this->op->val=='endfor'){
          $this->getNext(); // ����� ������, ����������� ��� 
     			if($for['else']){
    				$for['else']=$this->to(array('TYPE_SENTENSE','value'), $op);
    			}else{
    				$for['body']=$this->to(array('TYPE_SENTENSE','value'),$op);
    			}
    			// ���������� ��� ��� �����
    			$this->pushOp($this->oper($this->template('for',$for),'TYPE_SENTENSE'));
    			do{
    				$op=array_pop($this->opensentence);
    				if($op['tag']=='for') break;
    			} while(!empty($op) && true);
			
    			break;
     		}
		} while ( true );
		//$this->getNext(); // ����� ������, ����������� ��� 
    }  

    function tag_extends(){
    	$this->getExpression();
    	$op=$this->popOp();
    	$sent=&$this->opensent('class');
    	if(!empty($sent)){
    		$sent['extends']=preg_replace('~\..*$~','',basename($op->val));
    	}
		$this->getNext(); // ����� ������, ����������� ��� 
    } 
     
    /**
     * �����o��� ���� if
     * @param $id
     */
    function tag_if(){
    	// ������� ���� for
    	// ������ �����:
    	// if EXPRESSION
    	// elif EXPRESSION
    	// elif EXPRESSION
    	// else EXPRESSION
    	// endif
    	$tag =array('tag'=>'if','operand'=>count($this->operand),'data'=>array());
    	 
    	do {
    		// ���� ������ � ��� ��������� ����� if ��� elif
    		$this->getExpression();
    		$op=$this->popOp();
    		$data= array (
    			'if'=>$this->to(array('B','value'),$op)
    		);
    		$this->getNext(); // ������ ���
    		$this->block_internal(array('elseif','elif','else','endif'));
    		$op=$this->popOp();
    		$data['then']= $this->to(array('TYPE_SENTENSE','value'),$op);
    		$tag['data'][]=$data;
    		$this->getNext(); // ������ ���
    		if ( $this->op->val=='endif' )
    		break;
    		if ( $this->op->val=='else' ) {
    			$this->getNext();
    			$this->block_internal(array('endif'));
    			$op=$this->popOp();
    			$data=array(
					'if'=>false,
					'then'=>$this->to(array('TYPE_SENTENSE','value'),$op)
    			);
    			$tag['data'][]=$data;
    			$this->getNext(); // ������ ���
    			break;
    		}
    	} while (true);
    	$this->getNext(); // ����� ������, ����������� ���
    	$this->pushOp($this->oper($this->template('if',$tag),'TYPE_SENTENSE'));
    	return;
    }

    function tag_import(){
    	$set =array('tag'=>'import','operand'=>count($this->operand));
    	$this->getExpression(); // �������� ��� ����� ��� �������
    	$op=$this->popOp();
    	$t=&$this->opensent('class');
      $t['import'][]=basename($op->val,'.jtpl');
      $this->getNext();
    	return false;
    }
    
    /**
     *
     *  ��� SET
     *
     */
    function tag_set(){
    	$set =array('tag'=>'set','operand'=>count($this->operand));
    	$this->getExpression(); // �������� ��� ��������������
    	$set['id']=$this->newId($this->popOp());
    	$set['id']=$this->to(array('I','value'),$set['id']);
    	$this->getNext();
    	if($this->op->val!='=')
    	$this->error('unexpected construction9');
    	$this->getExpression();
    	$set['res']=$this->popOp();
    	if($set['res']->type=='TYPE_LIST')
    		$set['res']=$this->to('TYPE_XLIST',$set['res'])->val;
    	else
    		$set['res']=$this->to('*',$set['res'])->val;
    	$this->getNext();
    	$this->pushOp($this->oper($this->template('set',$set),'TYPE_SENTENSE'));
    	return;
    }
    
       
/**
 * ������� ���������� ������ ����������. 
 * + ������ �� ����� ��������� ������� �����������
 * + ����������� ������ 
 */ 
	function tplcalc($class='compiler'){
		$tag=array('tag'=>'class','import'=>array(),'macro'=>array(),'name'=>$class,'data'=>array());
		$this->opensentence[]= &$tag;

		$tagx =array('tag'=>'block','name'=>' ','operand'=>count($this->operand),'data'=>array());
		
		$this->block_internal(array(),$tagx);
		
		array_pop($this->opensentence);
		return $this->template('class',$tag);	
	}
	
/**
 * ���������� � ����� �������� ...
 * @param string $idx - ��� ���������� "" - �������� ���������
 * @param array $par - ������ ��� ����������
 * @param string $tpl_class - ��� �������� �������
 */	
	function template($idx=null,$par=null,$tpl_class='compiler'){
		static $tpl_compiler;
		if(!is_null($tpl_class) || empty($tpl_compiler)) {
			$tpl_compiler='tpl_'.pps($tpl_class,'compiler');
			if(!class_exists($tpl_compiler)){ 
				// ������� �������� ����
				include_once 'templates/'.$tpl_compiler.'.php';
			};		
			$tpl_compiler=new $tpl_compiler();
		}	
			
		if (!is_null($par)){
			if (method_exists($tpl_compiler,'_'.$idx))
				return call_user_func (array($tpl_compiler,'_'.$idx),&$par);
			else 
				printf('have no template "%s:%s"','tpl_compiler','_'.$idx);
		}
		return '';
	}
}
	