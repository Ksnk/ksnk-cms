<?php
/**
 * base class to build sintax analizator.
 * <%=point('hat','jscomment');
  
  
  
  
 %>
 */

/**
 * 
 * ���������� ����� ��� �������� ��������� �� ������ 
 *
 */
class operand {
	var 
		  $val  // �������� ��������
		, $type  // ��� ��������
//		, $pos	 // ������� ������� 
//		, $prio	// ��������� �������� ��� ��������
//		, $unop // ������� ������� �������� ��� ������� ��������
		;
		
	function __construct($val,$type='TYPE_NONE',$pos=0){
		$this->val=$val;
		$this->type=$type;
		$this->pos=$pos;
	}	
	
	function __toString(){
		return $this->val;
	}
		
	// ������� ��������������� �������� � ����� ���
	function &_to($type){
		return $this;
	}
}

/**
 * ������������������� ������ ��������� �� ��������
 * � ��������� ���������� .
 * @author ksnk
 *
 */
class parser {
	
	protected 
		$t_conv=array(
			'*'=>'TYPE_OPERAND',
			'B'=>'TYPE_XBOOLEAN',
			'D'=>'TYPE_XDIGIT',
			'S'=>'TYPE_XSTRING',
			'I'=>'TYPE_XID',
			'L'=>'TYPE_XLIST', // ������ �������� ����� �������
		);
	
	protected 

	/**
	 * ���� ���������
	 */  
		  $operand=array()

	/**
	 * ���� ��������
	 */  
		, $operation=array()
		
	/**
	 * ������� ������
	 */  
		, $curlex=0
		
	/**
	 * ������� ��������
	 */  
		, $op=null
		
	/**
	 * ������ ������
	 */  
		, $lex=array()
		
	/**
	 * ������ ��� ��������
	 */  
//		, $script='' // ���������, ��� �� �����...
		
	/**
	 * ���������� ��������
	 */	
		, $prio=array('('=>-1,','=>-1,')'=>0,"\x1b"=>-10)// ���������� �������� ��������
		
	/**
	 * ����������� �������� ��������
	 */	
		, $binop=array()
		
	/**
	 * ����������� �������
	 */	
		, $func=array()
		
	/**
	 * ����������� ������� ��������
	 */	
		, $unop=array()
		
	/**
	 * ����������� ������� ��������-���������
	 */	
		, $suffop=array()
		
	/**
	 * ����������� ���������������
	 */	
		, $ids=array()
		
	/**
	 * ��������� �� ������� - ����� �� ����������� ��������� �� �������
	 */	
		, $error_msg=array(
	 #### ���������� ������ ������
		# ������ ����� ��������� ������ - ������ ������ ��� ������ ������ ��������������� �����������
			 'toofar'=>'empty script' 
		# ������� ����� �������� �� �����, ������ ������ �������
			,'error happen'=>'error happen when executing'
		# �������������� ������������� - ������ ������������� �������
			,'unknown id'=>'unknown id '
		# �������������� �������� - ������ ������������� �������
			,'undefined operation'=>'undefined operation'
			
	 #### �������������� ������ �������
		# wtf
			,'wtf'=>'wtf'
		# �������� �� ����� ��������
			,'undefined operation'=>'undefined operation'
		# ��� ����������� ������
			,'closed brackets missed'=>'closed brackets missed'
		# �������������� ������ - ����� �������� � ���� ���������
			,'nooperand'=>'sintax error'
		# �� ������� ������ ��� ������ ������, �������������� ������ �������
			,'error with brackets'=>'error with brackets'
		# ����������� ������� �������� - �� ����� ����� ����?
			,'misplased operation'=>'misplased operation' 	
		# ������� ����� ��������� �� �����, �������������� ������ �������
			,'something wrong'=>'something wrong with expression'
		# ������� ����� �����������-����������� ������, �������������� ������ �������
			,'open bracker found'=>'open bracker found'
		)
		
	/**
	 * ������� ��� ������ ��������� �������� ������
	 * ���������� ��� ����������� ��������������� ��������
	 */  
		, $cake=array(
			'WORD_OP'=>array(), // �������� �������� �������
			'MC_JUST_OP'=>array(), // �������������� �������� ��������
			'JUST_OP'=>array(), // ������������� �������� ��������
		)
		
	/**
	 * ������ ����� ����� ��� �������� ������ ������
	 */
		, $lines = array()
	   
	/**
	 * ������ ����� ��� ��������� �� ����
	 */
		, $options= array(
            // Enviroment setting
          )
		;

	/**
	 * ����������� ���������� ����� � ���������� � ����������� ���������������� ������������ 
	 * ������� ������� �� ��������  
	 * � �������� ������� � ������� ����������� ���������� 
	 */
	function __construct($options=null){
		$this
			->newOp2('_scratch_',11,array($this,'function_scratch'))
			->newOp2('_stack_',10,array($this,'function_callstack'));
		if(!empty($options)){
			$this->options=array_merge($this->options,$options);
		}	
	}	

	/**
	 * ��������� ������ �������
	 * @param unknown_type $op1
	 * @param unknown_type $op2
	 */
	function function_callstack($op1,$op2){
		//������� �� �������
		if($op1->type=='TYPE_OBJECT'){
			$op=call_user_func($op1->handler,$op1,$op2,'call');
			if($op)
				$this->pushOp($op);
		} else
			$this->error('have no function '.$op1);
		return $op1;
	}
	
	/**
	 * ������� �� �������
	 * @param unknown_type $op1
	 * @param unknown_type $op2
	 */ 
	function function_scratch($op1,$op2){
		//������� �� �������
		$this->to('I',$op1);
		$this->to('L',$op2);
		$op1->val.='['.$op2->val.']';
		$op1->type='TYPE_XID';
		return $op1;
	}

	/**
	 * just skip
	 */
	function function_skip($op1){
		// �������
		return $op1;
	}
	
	/**
	 * �������� ������ ��������. ��� ����������� ��������������� ������ ��������
	 * ������������ �����, ��� ����������� ����� operand - ��� ������
	 */	
	protected function oper($value,$type='TYPE_NONE',$pos=0){
		return new operand($value,$type,$pos);
	}	
	
	/**
	 * �������� ����� ��������. 
	 */	
	protected function operat($value,$prio=10,$pos=0){
		$op=$this->oper($value,'TYPE_NONE',pos);
		$op->prio=$prio;
		return $op;
	}	
	
	/**
	 * getter-setter ��� ����� ���������
	 * @param $op
	 */
	protected function &pushOp($op,$type='TYPE_OPERAND'){
		if(is_string ($op)){
			$op=$this->oper($op,$type);
		}
		array_push($this->operand,$op);
		return $op;
	}
	
	/**
	 * ������� ������� � ������ �����
	 */
	function popOp($safe=false){
		if(is_null($op=array_pop($this->operand))){
			if ($safe){
				$this->error('nooperand');
			} else {
				$op=false;
			}
		}
		return $op;		
	}
	
	/**
	 * ������������� ������ �������-�������� ��������
	 */
	private function &new_Op($op,$prio,&$array,$phpeq,$types='*'){
		if(strpos($op, ' ')!==false){
			foreach(explode(' ',$op) as $oop)
				$this->new_Op($oop,$prio,&$array,$phpeq,$types);
			return $this;	
		}
		$ww='JUST_OP';
		if(preg_match('/^\w+$/',$op)){
			$ww='WORD_OP';
		} else if(strlen($op)>1) {
			$ww='MC_JUST_OP';
		} 
		if($prio<10 || !isset($this->prio[$op])) $this->prio[$op]=$prio;
		if(is_string($phpeq)){
			$array[$op]=$this->oper(str_replace('~~~',$op,$phpeq));
		} else	{
			$array[$op]=$this->oper($phpeq);
		}
		$array[$op]->types=$types.'****';
		$op=preg_quote ($op);
		if($op!='' && $op{0}!='_'){
			if(!in_array($op,$this->cake[$ww]))
				$this->cake[$ww][]=$op;
		}
		return $this;
	}	
	
	/**
	 * ���������� ������� � �����������
	 * @param $op - ��� ��������
	 * @param $phpeq - PHP ���������� - ������� ��� sprintf'� � ����� ����������
	 */	
	function &newFunc($op,$phpeq='~~~(%s)',$types='*'){
		$this->func[$op]=$this->oper(str_replace('~~~',$op,$phpeq));
		return $this;
		//return $this->new_Op($op,10,&$this->func,pps($phpeq,'~~~(%s)'),$types);
	}	
	
	/**
	 * ���������� ������� ���������
	 * @param $op - ��� ��������
	 * @param $phpeq - PHP ���������� - ������� ��� sprintf'� � ����� ����������
	 */	
	function &newOp1($op,$phpeq=null,$types='*'){
		return $this->new_Op($op,10,&$this->unop,pps($phpeq,'~~~(%s)'),$types);
	}	
	
	/**
	 * ���������� ������� ��������� - �������
	 * @param $op - ��� ��������
	 * @param $phpeq - PHP ���������� - ������� ��� spritf'� � ����� ����������
	 */	
	function &newOpS($op,$phpeq=null,$prio=10,$types='*'){
		return $this->new_Op($op,$prio,&$this->suffop,pps($phpeq,'%s~~~'),$types);
	}	
	
	/**
	 * ���������� �������
	 * @param $op - ��� ��������
	 * @param $phpeq - PHP ���������� 
	 */	
	function &newOpr($op,$phpeq=null,$type='TYPE_OPERAND'){
		if(strpos($op, ' ')!==false){
			foreach(explode(' ',$op) as $oop)
				$this->newOpr($oop,$phpeq);
			return $this;	
		}
		$this->ids[$op]=$this->oper($phpeq,$type);
		return $this;
	}	
	
	/**
	 * ���������� �������� ���������
	 * @param $op - ��� ��������
	 * @param $prio - ��������� ��������
	 * @param $phpeq - PHP ���������� - ������� ��� spritf'� � ����� ����������
	 */	
	function &newOp2($op,$prio=10,$phpeq=null,$types='*'){
		return $this->new_Op($op,$prio,&$this->binop,pps($phpeq,'(%s)~~~(%s)'),$types);
	}	

	/**
	 * ������ ���������, ���������� ����� �� ������ ���������� ������
	 * ������ ��� �������� ����������� ������������ 
	 * + �������� �� ������ SQL �����������
	 * @param array $types ��������� ������� ����� ������ 
	 * 	��� ��������������� ��������
	 */
	protected function get_reg(&$types){
/**
 * ������ �����, ������������ ����������. ������� �� ���-����� � ���������
 */
		$types=	array(0 //0 - just skip
			,0,'TYPE_STRING'
			,'TYPE_DIGIT'
			,'TYPE_OPERATION' //5
			,'TYPE_ID' //6  TYPE_OPERAND
			,'TYPE_OPERATION'  //7
			,'TYPE_COMMA' //8
		);	
		return '#[\n\r\s]*' // # - ������� ��������
			.'(?:'	#1#2 - ����������� ��� ����� �� ����� - TYPE_OPERAND
				.'([\'`"])((?:[^\\1\\\\]|\\\\.)*?)\\1'  
			.'|(\d\w*)'//	#6 - ����� ��� �����, ������������ � ����� - TYPE_DIGITS
			.'|'	#4,5 - �������������� �������� - TYPE_OPERATION
				.'((?:'.implode('|',$this->cake['WORD_OP']).')(?=\b)|'.implode('|',$this->cake['MC_JUST_OP']).')'
			.'|(\w+)'#6 - ������ ����� TYPE_OPERAND			
			.'|'	#7 - ������������� �������� - TYPE_OPERATION
				.'(['.implode('',$this->cake['JUST_OP']).'\!])' 
			.'|(.)'#8 - ������������� ����� ���������� - TYPE_COMMA 
			.')#si';

	}
	
	/**
	 * ������������ ������� ��� ����������� ����� �����
	 * @param string $script
	 */
	protected function scanNl(&$script){
		preg_match_all('/$/m',$script,$m,PREG_OFFSET_CAPTURE);
		foreach($m[0] as $k=>$v){
		  $this->lines[$v[1]]=$k;
		}
	}
	
	/**
	 * ������ ������ ����������� - ������� ������
	 * @param $script
	 */
	function makelex($script){
		//$this->script=$script;
		$this->operand=array();
		$this->operation=array();
		$this->lex=array();
		$this->curlex=0;
		$types=array();
		if (preg_match_all($this->get_reg($types),$script,$m))
		{
			$curptr=0;
			$vc=count($m[0]);
			for($i=0;$i<$vc;$i++){
				if(empty($m[0][$i])) continue;
				$curptr+=strlen($m[0][$i]);
				if (!empty($m[1][$i])) {
					$op=$this->oper(stripslashes($m[2][$i]),'TYPE_STRING',$curptr);
					if ($m[1][$i]=="'") 
						$op->type.='1';
					elseif ($m[1][$i]=="`") 
						$op->type.='2';
				} else {
					for($x=count($types)-1;$x>2;$x--){
						if(isset($m[$x][$i]) && $m[$x][$i]!=""){
							$op=$this->oper(strtolower($m[$x][$i]),$types[$x],$curptr);
							break;
						}
					}
					//TODO: ���� �� ��������� ������ - ��� ���������� �������... ������� - wtf?
					if($x==2) continue;
				}
				$this->lex[]=$op;
			} 
			$this->lex[]=$this->oper("\x1b",'TYPE_EOF',$curptr);
		}
	}
	
	/**
	 * ������������� ��������� �� ������ � �������� ��� ������������
	 * @param $msgId
	 * @param $lex
	 */
	protected function error($msgId,$lex=null){
		$mess=pps($this->error_msg[$msgId],$msgId);
		if(is_null($lex)){
			$lex=$this->op;
		}
		if(!is_null($lex)){
			$mess.=sprintf(' pos:%s lex:"%s"',pps($lex->pos,-1),pps($lex->val,-1));
		}
		throw new Exception($mess);
	}	

	/**
	 * �����, ��������� ���� �������
	 */
	protected function back(){
		if($this->curlex>0) $this->curlex--;
		$this->op=&$this->lex[$this->curlex];
	}
	/**
	 * ��� ��������� �������
	 */
	protected function getNext(){
		if($this->curlex>=count($this->lex))
			$this->error('toofar');
		$this->op=&$this->lex[$this->curlex++];
		return $this->op->type;
	}
	
	/**
	 *  ��������� ���������� ����������� ��������� � ���������� � ��������
	 *  Expr === {Operand} [{Operation} {Expr}]*
	 */
	protected function getExpression(){
		$this->newop('(');
		$place=1;	
		do{
			if($place==1){
				switch($this->getNext()){
					case 'TYPE_DIGIT':
					case 'TYPE_OPERAND':
						$this->pushOp($this->op);
						$place=2;
						break;
					case 'TYPE_STRING':
					case 'TYPE_STRING1':
						$this->pushOp($this->op->val,'TYPE_STRING');
						$place=2;
						break;
					case 'TYPE_STRING2':
					case 'TYPE_ID':
						if(isset($this->ids[$this->op->val])){
							$x=$this->ids[$this->op->val];
							if(is_string($x->val)) {
								$op=$this->pushOp($x->val,$x->type);
							} else if($op=call_user_func($x->val,$this->op)) {
								$this->pushOp($op);
							}
						} elseif (method_exists($this,'resolve_id')) {
							$op=$this->resolve_id($this->op);
						} else {
							$this->error('unknown id');
						}
						$place=2;
						break;
					case 'TYPE_OPERATION': // ������� � ����������� � �������
						$op=$this->op;
						$this->getNext();
						if ($this->op->val=='(') {
							//$this->get_Comma_separated_list();
							$xop=$this->pushOp('[]','TYPE_LIST');
							$xop->value=$this->get_Parameters_list();;
							$this->getNext();	
							if($this->op->val!=')')
								$this->error('closed brackets missed'); // �������������� - �������
							$this->execute($op);
						
						} else { // ������� �������� 
							$this->back();	
							$this->calc($op,true);
							continue 2;
						}
						$place=2;
						break;
					case 'TYPE_COMMA':	
						if ($this->op->val=='(') { // ��� - ������ ������
							$this->getExpression();
							$this->getNext();
							if($this->op->val!=')')$this->error('closed brackets missed'); 
						} elseif ($this->op->val=='[') { // ��� - ������ ������
							// �������� ������
							$xop=$this->pushOp('[]','TYPE_LIST');
							$xop->value=$this->get_Parameters_list();
							//$num=$this->get_Comma_separated_list();
							$this->getNext();
							if($this->op->val!=']')
								$this->error('missed ]');
						} else {
							$this->back();
							break 2;
						}
						$place=2;
						break;
					default:	
						$this->error('wtf');
				}	
				
			} else if($place==2){
				switch($this->getNext()){
				case 'TYPE_OPERATION':	
					if(isset($this->suffop[$this->op->val])){
						$this->calc($this->op,'suff'); // �������� ��� �� ����� - ��������!
						continue ;
					} else {
						$this->calc($this->op);
						$place=1;
						break;
					}
				case 'TYPE_COMMA':
					if ($this->op->val=='(') {
						$this->newop('_stack_');
						$arr=$this->get_Parameters_list('=');
						$this->getNext();
						if($this->op->val!=')')
							$this->error('missed )');
						$op=$this->pushOp('[]','TYPE_LIST');
						$op->value=$arr;
						break;
					} elseif ($this->op->val=='['){ // ��������� ��������
						$xop=$this->pushOp('[]','TYPE_LIST');
						$xop->value=$this->get_Parameters_list();
						//	$num=$this->get_Comma_separated_list();
						$this->getNext();
						if($this->op->val!=']')
							$this->error('missed ]');
						$this->newop('_scratch_');
						continue 2;
					} ;
				default:
					$this->back();	
					break 2;
				}	
			} 	
		} while(true);
		$this->newop(')');	
	}

	protected function newop( $f ){
		if($f=='(')
			array_push($this->operation,$this->operat($f,$this->prio['(']));
		else {
			$this->calc($this->operat($f,isset($this->prio[$f])?$this->prio[$f]:10));
		}
	}
	
/*	protected function get_Comma_separated_list(){
		$depth=count($this->operand);
		do {
			$this->getExpression();
			$this->getNext(); 
			if($this->op->val==',') {
				//$this->newop(',');
			} else {
				$this->back();
				break;
			}
		} while(true);
		return count($this->operand)-$depth;
	}*/
	/**
	 * ������ ���������� ����� �������, � �������, ����������� = ��� :
	 * ���������� �������������� ������
	 */
	protected function get_Parameters_list($sign=':'){
		$depth=count($this->operand);
		$arr=array();
		$keys=array();
        if(!empty($this->storeparams)){
        	$keys[]=$this->popOp();
	        $arr[]=null;
	        $this->storeparams=false;
        }
		do {
			$type=$this->getNext();
			if(in_array($type,array('TYPE_ID','TYPE_STRING3'))){
				$id=$this->op;
				$this->getNext();
				if ($this->op->val==$sign) {
					//������� ����!
					$this->getExpression();
					$keys[]=$id;
					$arr[]=$this->popOp();
				} elseif ($this->op->val==',') {
					$this->back();
					$keys[]=$id;
					$arr[]=null;
				} else{
					$this->back();
					$this->back();
					$this->getExpression();
					$keys[]=$this->popOp();
					$arr[]=null;
				}
			} else { // it's a name
				$this->back();
				$opdepth=count($this->operand);
				$this->getExpression();
				if($opdepth<count($this->operand)){
					$keys[]=$this->popOp();
					$arr[]=null;
				}
			}
       
			$this->getNext();
			if($this->op->val!=','){
				$this->back();
				break;
			}
		} while(true);
		return array('value'=>$arr,'keys'=>$keys);
	}
	
	/**
	 * ������ ������ ����������� - ������ �� ����� ��������� ������� �����������
	 * + ���������� ������ 
	 */ 
	function mathcalc(){
		$this->getExpression();
		if(count($this->operation)>1){
			$op=array_pop($this->operation);
			$this->error('error happen',$op);
		}
		$res=$this->popOp();
		if(!empty($this->operand)){
			$this->error('something wrong');
		}
		return $res->val;	
	}
	
	/**
	 * �������(����������) ������������ ��������, ������������ �� ���� ����� �������� 
	 * @param $op
	 * @param $unop
	 */
	protected function execute($last){
		//echo 'exec "'.$last[0].'" ';
		if(empty($last->unop)) 
			$last->unop=!isset($this->binop[$last->val]);
		if($last->val=='(') {
			$this->error('open brackes found',$last);
		} else if($last->type=='TYPE_OBJECT'){
			$op=call_user_func($last->handler,$last,$last->param,'call');
			if($op)
				$this->pushOp($op);
		} else if(!$last->unop && isset($this->binop[$last->val])){
			if(is_string($this->binop[$last->val]->val)){
				$op1=$this->popOp();
				$op2=$this->popOp();
				$opr=$this->binop[$last->val];
				$this->pushOp(sprintf($this->binop[$last->val]->val
						,$this->to(array($opr->types{2},'value'),$op2)
						,$this->to(array($opr->types{1},'value'),$op1)
					),$this->t_conv[$opr->types{0}]);
			} elseif(is_callable($this->binop[$last->val]->val)) {
				$op2=$this->popOp();
				$op1=$this->popOp();
				$op=call_user_func($this->binop[$last->val]->val,$op1,$op2);
				if($op)
				$this->pushOp($op);
			} else $this->error('x3');
		} else if($last->unop==='suff' && isset($this->suffop[$last->val])){
			if(is_string($this->suffop[$last->val]->val)){
				$op1=$this->popOp();
				$opr=$this->suffop[$last->val];
				$op1=$this->to($opr->types{1},$op1);
				$this->pushOp(sprintf($this->suffop[$last->val]->val
						,$op1->val),$this->t_conv[$opr->types{0}]);
			} else {
				$op=call_user_func($this->suffop[$last->val]->val,$this->popOp());
				if($op)
					$this->pushOp($op);
			}
		} else if($last->unop && isset($this->unop[$last->val])){
			if(is_string($this->unop[$last->val]->val)){
				$op=$this->popOp();
				$opr=$this->unop[$last->val];
				$this->pushOp(sprintf($this->unop[$last->val]->val
						,$this->to(array($opr->types{1},'value'),$op)),$opr->types{0});
			} else {
				$op=call_user_func($this->unop[$last->val]->val,$this->popOp());
				if($op)
					$this->pushOp($op);
			}
		} else {
			$this->error('undefined operation',$last);
		}	
	}
	/**
	 * �������� ���������� � "����������� type", ��� ���������� ������� � ������ ���� 
	 * @param $type
	 * @param $res
	 * 
	 * to('TYPE_ID' - ���������������� � "����������� ����������" ��� ������������ 
	 * 		����������� ���������, � ���������
	 */
    protected function to($type,&$res){
    	if(!is_array($type)) $type=array($type);
    	foreach($type as $t){
    		switch($t){
    			case 'value':
    				return $res->val;
			case '*':	
	       	case 'S':	
	       	case 'D':	
	       	case 'TYPE_OPERAND':
	    	case 'TYPE_STRING':
	    		
	    	case 'L':
	    	case 'TYPE_XLIST':
	    		if($res->type=='TYPE_LIST'){
	     			$arr=array();
	     			if(isset($res->value['keys'])){
						for($i=0;$i<count($res->value['keys']);$i++){
							$arr[]=$this->to('S',$res->value['keys'][$i])->val;
						}
	     			} else {
		     			for($i=0;$i<count($res->value);$i++){
							$arr[]=$this->to('S',$res->value[$i])->val;
						}
	     			}
					$res->val=implode(',',$arr);
					$res->type='TYPE_XLIST';	
	     		}
	       		break;
	    	}
    	}
    	return $res;
    }
    
	protected function calc($op,$unop=false){
		$prio=$unop?10:0;
		$prio=max($prio,isset($this->prio[$op->val])?$this->prio[$op->val]:10);
		while (count($this->operation)>0) {
			$last=&$this->operation[count($this->operation)-1];
			if($last->prio>$prio || ($last->prio==$prio && !$last->unop)){
				$this->execute(array_pop($this->operation));
			} elseif(!empty($this->storeparams) && $op->val!='_stack_'){
				// ������ ������� ����������� ������
				$this->storeparams=false;
				$this->execute($this->operat('_stack_'));
			} else {
				break;
			}
		};
		if($op->val!=')'){
			$op->prio=$prio;
			$op->unop=$unop;
			array_push($this->operation,$op);
		} else {
			$op=array_pop($this->operation);
			//TODO: ���������, ��� ������� - ������
		}
	}
}