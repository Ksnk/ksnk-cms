<?php
/*
<%
// * необходимая часть шаблона модуля

// включение плагина в список автоматических плагинов
$auto_plugins[]='altname';

// участок шаблона админки статьи с дополнительным полем ввода адреса
	POINT::start('plugin_control0');
%>
<input type="text" class="tahoma fills size11" style="width:100px;" title="Название раздела в адресной строке"
	alt="Название раздела в адресной строке" onchange="need_Save()" placeholder="адрес" name="alt_name_{id}">
<% POINT::start('plugin_control1')
// обработка дополнительного поля ввода в форме администрирования статьи
%>
if(!!$element->article && $form->var['alt_name_'.$element->article->v['id']]){
   $this->export('altname','update_page',$form->var['alt_name_'.$element->article->v['id']],$res['name'],$item->v['id']);
}
<% POINT::start('plugin_control2')
// обработка дополнительного поля ввода в форме администрирования статьи
%>
  $x=$this->export('altname','getrealaddr',$item->v['id']);
  if(empty($x)){
  	$x=$item->v['id'];
  }
  $form->var['alt_name_'.$element->article->v['id']]=$x;
<% POINT::start('plugin_body') %>

/**
 * плагин для реализации альтернативных имен страниц
 * -- создается таблица
 * -- в таблицу заносятся альтернативный адрес и "настоящий"
 * -- быстрый поиск alt-real и real-alt
 */
class altname extends ml_plugin {

	function altname(&$parent){
		parent::ml_plugin($parent);
		if(defined('IS_ADMIN')){
			parent::_init(
				array(
				'title'=>'Имена разделов'
				,'fields'=>array(
							array('смс адрес','realadr','text_edit'),
							array('адрес','altaddr','text_edit'),
                            array('Раздел','name','text_edit'),
							array('','order','win_order'),
					)
				,'base'=>'_altnames'
				,'orderbystr'=>' order by `realadr`'
				,'prefix'=>'al'
				));
		} else {
			parent::_init(
				array(
				'title'=>'Имена'
				,'base'=>'_altnames'
				));
		}
		$GLOBALS['altname']=&$this;
	}

	/**
	 * получить адрес по меню. null, если нету
	 * @param $menu
	 */
	function getrealaddr($menu){
		$sel_sql='select * from '.TAB_PREF.'_altnames'.' where `realadr`=?';
		$res=@$this->database->selectRow($sel_sql,'?do=menu&id='.$menu);
		if(empty($res))
			return null;
		else
			return $res['altaddr'];
	}

	/**
	 * получить адрес по меню. null, если нету
	 * @param $menu
	 */
	function getrealaddr0($addr) {
		static $cache;
		if(empty($cache)){
			$cache=array();
			$result=mysql_query('select * from '.TAB_PREF.'_altnames');
			while($row=mysql_fetch_assoc($result)){
				$cache[$row['realadr']]=$row['altaddr'];
			}
			mysql_free_result($result);
			//error_log(print_r($cache,true));
		}
		$addr=htmlspecialchars_decode($addr);
		if(isset($cache[$addr]))
			return '/'.$cache[$addr];
		else
			return $addr;
	}

	/**
	 * получить реальный адрес
	 * @param $menu
	 */
	function getaddr($addr){
		$sel_sql='select * from '.TAB_PREF.$this->base.' where `altaddr`=?';
		$res=@$this->database->selectRow($sel_sql,$addr);
		if(empty($res))
			return null;
		else {
			if (preg_match('/do=(.+)\&id=(\w+)/',$res['realadr'],$m)){
				return array('do'=>$m[1],'id'=>$m[2]);
			}
			return false;
		}
	}

	/**
	 * update both system addresses do=page & do=menu with different ID's
	 * @param $page
	 * @param $menu
	 */
	function update_page($addr,$name,$menu){
		// try to found new record
		$sel_sql='select * from '.TAB_PREF.$this->base.' where `realadr`=?';
		$ins_sql='INSERT INTO '.TAB_PREF.$this->base.' (?#) VALUES(?a);';
		$upd_sql='update '.TAB_PREF.$this->base.' set ?a where `realadr`=?;';

		// select & update
		$data=array('altaddr'=>$addr,'realadr'=>'?do=menu&id='.$menu);
        if(!empty($name)) $data['name']=$name;
		$res=$this->database->query($sel_sql,$data['realadr']);
		if(empty($res)){
			$this->database->query($ins_sql,array_keys($data),array_values($data));
		} else {
			$this->database->query($upd_sql,$data,$data['realadr']);
		}
	}

	function admin_altname(){
		return
		smart_template(array(ADMIN_TPL,'theheader'),array(
		'header'=>$this->getPluginName(),
		'data'=>parent::admin_plugin()));
	}

    function do_convert(){
        if(!$this->parent->has_rights(right_WRITE))
            return $this->parent->ffirst('_loginform');
        $this->database->query('ALTER TABLE '.TAB_PREF.$this->base.' ADD `name` VARCHAR( 255 ) NOT NULL AFTER `altaddr` ;');
    }

	function do_create($killall=true){
		if(!$this->parent->has_rights(right_WRITE))
			return $this->parent->ffirst('_loginform');
		if($killall){
			$this->database->query('DROP TABLE IF EXISTS '.TAB_PREF.$this->base.';');
		}
		$this->database->query('CREATE TABLE '.TAB_PREF.$this->base.' (
				`altaddr` VARCHAR( 255 ) NOT NULL ,
				`realadr` VARCHAR( 255 ) NOT NULL ,
				`name` VARCHAR( 255 ) NOT NULL ,
				`order` int,
				PRIMARY KEY ( `altaddr` ),
				INDEX (`realadr`)
				);');
	}
}
<% point_finish('plugin_body') %>