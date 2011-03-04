<?php
/*
<%  
// плагин, реализующий окно "параметры по умолчанию"
// перечисление всех элементов и вывод окошка с формой установки параметров.


// включение плагина в список автоматических плагинов
$auto_admin_plugins[]='defaultValues';
	
 point_start('plugin_body') %>
		
/**
 * плагин для реализации альтернативных имен страниц
 * -- создается таблица
 * -- в таблицу заносятся альтернативный адрес и "настоящий"
 * -- быстрый поиск alt-real и real-alt
 */
class defaultValues extends ml_plugin {

	private $_defVal=null;
	
	function defaultValues(&$parent){
		parent::ml_plugin($parent);
		parent::_init(
			array(
			'title'=>'<span class="lgray">Параметры по умолчанию</span>' //
			,'fields'=>array(
						array('смс адрес','realadr','text_edit'),
						array('адрес','altaddr','text_edit'),
						array('','order','win_order'),
				)
			,'base'=>'_altnames'
			,'orderbystr'=>' order by `realadr`'
			,'prefix'=>'al'
		));
//		$this->readDefVal();
	}
	
	function change($id,$key,$val){
		if(isset($this->_defVal[$id]))
		{
			$this->_defVal[$id][$key]=$val;
			$this->parent->writeRecord($this->_defVal[$id]);
			$this->readDefVal(true);
			return ;
		}
		$v=array('root'=>'defVal','url'=>$id,$key=>$val);
		$this->parent->writeRecord($v);
		$this->readDefVal(true);
	}

	private function readDefVal($forse=false){
		if(is_null($this->_defVal) || $forse){
			$this->_defVal=array();
			$x=$this->parent->readRecords(array('root'=>'defVal'));
			if(!isset($x[0]['url'])) $x=array();
			
			foreach($x as $v){
				$this->_defVal[$v['url']]=$v;
			}
		}
	}
	
	function defVal($key){
		$this->readDefVal();
		$res=array('id'=>$key);
		if(isset($this->_defVal[$key]))
			$res=array_merge($this->_defVal[$key],$res);
		return $res;
	}
	
	function _handle(){
		$this->readDefVal();
		$el_id=array();
		if(isset($_POST['form'])&& $_POST['form']=='defaultValues') {
			foreach($_POST as $k=>$v){
				if(preg_match('/^(\w+)_(\d+)$/',$k,$m)){
					$el_id[$m[2]]=1;	
					$this->change($m[2],$m[1],$v);
				}
			}
			$style=file_get_contents(ROOT_PATH.'/elements.css');
			
			foreach($el_id as $k=>$v){
				$x=itemByType(ppi($k));
					
				if (method_exists($x,'style_update')){
					$row=&new $x();
					$row->style_update($style,$this->_defVal[$k]);
				}
			}
			file_put_contents(ROOT_PATH.'/elements.css',$style);
			$this->parent->go($this->parent->curl());
		}
	}
	
	function admin_defaultValues(){
		$this->readDefVal();
		return
		smart_template(array('tpl_elements','_defvalues'),
			array('tabs'=>array(
/* <% echo '*'."/\n"; 
	foreach($elements as $k=>$e) if(empty($e['internal']))
	   if(!empty($e['deftpl']))
		$v[]=sprintf("\t\t\tarray('title'=>'%s'
			,'data'=>smart_template(array('%s','_def_%s'),\$this->defVal(%s)))"
				,$e['name'],$e['deftpl'],$e['class_name'],$k);
	   else
		$v[]=sprintf("\t\t\tarray('title'=>'%s'
			,'data'=>smart_template(array('%s','def_%s'),\$this->defVal(%s)))"
				,$e['name'],'tpl_def',$e['class_name'],$k);
	echo implode("\n,",$v).'/*';				
%>*/			
		)));
	}

}
//<% point_finish('plugin_body') %>
?><style>
<% point_start('admin_css'); %>

div.selectbox { 
	position:relative; float: left ; width: 140px;
}
div.selectbox input{ 
	width: 120px;
	background: url(../img/alert_x.gif) white 100px -120px no-repeat;
}
div.selectbox div.menu{ 
	position:absolute; top:20px ; left:0px; 
}

<% point_finish('admin_css'); %>
</style>
