<?php
/**
 * wiki-�������� ��� ������������� � �������������
 * ���������� wiki ������
 * -- ��������� ���������� ����� � ����� ���������� �����
 * -- ���� ������ ������ ������ - ���������� - ��� ������ ������ �������������
 * -- ������ ������ == ������ - ���������, ������� ��������� ������������ ����������� =
 */
class wiki_parcer{

	private $currentline,$currenttype;
	private $store=array();
	private $newline="\n";
	
	static function convert(&$s,$format){
		static $self;
		if (empty($self)) $self=new wiki_parcer();
		$self->read_string($s);
		return $self->wiki_txt();		
	}
	/**
	 * ������ ������ � wiki ��������� � ������ �� � ���������.
	 * @param string $s
	 */
	public function read_string($s){
		$offset=0;
		/**
		 * ������ � ����� ���� ������ � ����������� ������ ������
		 */
		while($offset<strlen($s) && preg_match('/(====|===|==|\t|\s\s|)\s*([^\n\r]*)($|[\n\r]+)/',$s,$m,0,$offset)){
			//var_dump($m);
			$offset+=strlen($m[0]);
			switch($m[1]){
				case "==": 
					$this->newline(preg_replace('/\s+/',' ',$m[2]),'header',1); 
					break;
				case "===": 
					$this->newline(preg_replace('/\s+/',' ',$m[2]),'header',2); 
					break;
				case "====": 
					$this->newline(preg_replace('/\s+/',' ',$m[2]),'header',3); 
					break;
				case "": 
					$this->line(preg_replace('/\s+/',' ',$m[2]),'para'); 
					break;
				default:
					$this->line(str_replace("\t",'    ',rtrim($m[0]).$this->newline),'pre'); 
			}
			if (strlen($m[3])>2)
				$this->line_complete();
		}
		$this->line_complete();
	}
	
	private function newline($s,$type="para",$level=0){
		$this->line_complete();
		$this->line($s,$type,$level); 
	}
	
	private function line($s,$type="para",$lvl=0){
		if($this->currenttype!=$type)
			$this->line_complete();
		$this->currentline[]=$s;
		$this->currenttype =$type;
		$this->currentlevel =$lvl;
	}
	
	private function line_complete(){
		if (!empty($this->currentline))
			$this->store[]=array('txt'=>implode($this->currentline,' ')
				,'type'=>$this->currenttype
				,'lvl'=>$this->currentlevel);
		$this->currentline=array();
	}
	
	/**
	 * ����� ������ � ��������� ����
	 * -- ��������� ���������� ���������� �����
	 * -- ��������� ������������� ����� �� 80 ��������
	 * -- pre �� �������������
	 */
	public function wiki_txt(){
		$result='';
		foreach($this->store as $line){
			switch($line['type']){
				case 'para':
					$result.=$this->jLeft($line['txt']).$this->newline;
					break;
				case 'pre':
					$result.=$line['txt'].$this->newline;
					break;
				case 'header':
					$result.=$this->jLeft($line['txt'],80,10,10).$this->newline;
					break;
			}
		}
		return $result;
	}
	/**
	 * ������ ����� ��������. ������ �� ����� $size ��������. ������ � ����� �������
	 * @param string $s
	 * @param integer $size
	 */
	function jLeft($s,$size=80,$left=0,$right=0) {
		$result='';
		$leftspaces="";
		if($left>0)
			$leftspaces=str_pad(' ',$left);
		$rsize=$size-$left-$right;
		while($s!=""){
			if(strlen($s)>$rsize)
				$i=strrpos(substr($s,0,$rsize),' ');
			else 
				$i=	strlen($s);
			if($i===FALSE) $i=strlen($s);
			$result.=$leftspaces.trim(substr($s,0,$i)).$this->newline;
			$s=trim(substr($s,$i+1));
		}
		return $result;
	}
}
