<?php
/**
 * ������ SiteMap
 * ��������� �������� ���� � ���������� ��� ���������� (getSiteMap)
 * 
 * + 16.10.2008
 *   ��������� ������� � ��������� ��� �������� "navbar", ������ nfirst...
 * 
 * note: �������������� ������ � ���������� ��������� ������� ?_menu  
 */

$curlist=array();

class sitemap extends plugin {
	var $cur='';
	
	/**
	 * ������ ������� ���� � �������� ����������� ���������
	 *
	 * @param $id - ������� ������� ����
	 * @return ������ - ��������� xMenuLine
	 */

	function getSiteMap($id=0){
		global $curlist;
	//debug('getSiteMap '.$id);
		if (!empty($this->sitemap)){
			return $this->sitemap->scan($id);
		}
		$this->sitedir=array();
		$this->dirlist=array();
	 	$this->parent->sitemap=&$this;
		$i=0;
		$x=$this->parent->nodeGet("root menu");
		//debug($x);
		$this->sitemap=readElement(&$x,$i);
		if(defined('IS_ADMIN')){
			$this->anchors=$this->parent->readCache('anchor');
		}
		$curlist=array();	
		$this->sitemap->scan_callback(pps($this->parent->cur_menu),
			create_function('&$a','global $curlist; array_unshift($curlist,$a->v);$a->v["current"]="current";'));
		return $this->getSiteMap($id);
	}
	
	function menu_push($a){
		global $curlist;
		//print_r($curlist);
		if(!isset($this->navbar) || !$this->navbar)
			$this->navbar=array('list'=>array());
		array_push($this->navbar['list'],$a);
		array_push($curlist,$a);
	}

	/**
	 * ������� ������ ��� ������ $curlist ������...
	 *
	 * @return unknown
	 */
	//XXX: ���! �����������... ����� ������� �������... 
	function _getCurList(){
		global $curlist;
		//if (empty($this->sitemap)) $this->getSiteMap();
		return $curlist;
	}
	
	function getPluginName(){
		return '����� �����';
	}
	
/**
 * ������� - ��������� ������
 */	
	function /* private */ init_em_all(){
		$this->sitemap=null;
		$this->getSiteMap();
		$x=$this->_getCurList();
		if(empty($x)){
			// ������� ����� ���� ������
			return ' ';
		}
		//	debug($x);
		array_shift($x); // ���������� root menu
		$y=array_shift($x); // ���������� ���� "Main" - �������
		$this->last=array_pop($x); // ���������� ��������� �������
		$this->navbar=array();
		$this->sub(pps($this->last['descr'],$this->last['name']));
		
		if (count($x)){// && ('catalogue'==pps($y['name']))){
			$this->tit(pps($x[0]['descr'],$x[0]['name']));
			if(!empty($x[0]['url'])) $this->titpic($x[0]['url']);
			$this->navbar=array();
			foreach($x as $k=>$v){
				if($v['descr']) 
					$x[$k]['name']=$v['descr'];
			}	
			$this->navbar=array('list'=>$x);
		} else {
		    $this->navbar=false;
			$this->tit(pps($this->last['descr'],$this->last['name']));
		}
	}
	/**
	 * 
	 * ��������� navbar'�
	 *
	 */
	function navbar($_navbar=null){
		if(!empty($_navbar)) {
			$this->navbar=$_navbar;
		}
		if (!isset($this->navbar)) 
			$this->init_em_all();
		return $this->navbar;
	}
	/**
	 * ��������� ��������� SUB �������
	 *
	 * @param unknown_type $sub
	 * @return unknown
	 */
	function sub($_sub=''){
		if (!isset($this->navbar))  
			$this->init_em_all();
		if($_sub!='') {
			$this->sub=pps($_sub,' ');
		}
		return $this->sub;
	}
	/**
	 * ��������� ��������� TIT �������
	 *
	 * @param unknown_type $sub
	 * @return unknown
	 */
	function tit($_tit=''){
		if (!isset($this->navbar))  
			$this->init_em_all();
		if($_tit!='') {
			$this->tit=pps($_tit,' ');
		}
		return $this->tit;
	}
	
	function titpic($_titpic=''){
		if (!isset($this->navbar))  
			$this->init_em_all();
		if($_titpic!='') {
			$this->titpic=pps($_titpic,' ');
		}
	debug('xxx '.TMP_DIR.'t_'.$this->titpic.'.gif');	
		if(empty($this->titpic)) 
			return $this->tit;	
		if(is_file(TMP_DIR.'t_'.$this->titpic.'.gif')){
			return sprintf('<img alt="%s" src="%s">',$this->tit(),TO_URL('t_'.$this->titpic.'.gif'));
		} else
			return 	$this->tit;
	}
	
/**
 * ����� ������ ����������� � ul-li ����
 *
 * @return unknown
 */
	function do_sitemap($recurs=true,$curopen=true){
		// ���������� ������� ������
		ml_plugin::setupmenu();
		$x=$this->getSiteMap();
		//debug($x);
		if($recurs) { // ����� �����
			return smart_template(array(ELEMENTS_TPL,'sitemap'),
				array('data'=>$x->getUlLi(1000,false,1)));
		} else { // ����� �����
			// ���� ������� ������
			$x=trim($x->getUlLi(1000,true,2,15));
			if (!empty($x))
				return smart_template(array(ELEMENTS_TPL,'subcat'),
				array('data'=>$x));
			else
				return '';
		}
	}
	function admin_sitemap(){
		return '�������� �� ����� �������������� ��������';
	}

}
?>