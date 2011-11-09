<?php
/**
 *  Класс для хранения игровых новостей
 *  - новости категории 0 - служебная информация для админа
 *  - новости с другой категорией - новости клубов
 */

class darts_News extends plugin {
/**
 *  добавить новость
 */
	function add_News($text,$date=null,$place=0,$id='') {
		
		if (!$this->parent->has_rights(right_WRITE) && place!=0) return ;
		$key=array('text'=>$text);
		if($date) $key['DATE']=$date;
		if($place) $key['CATEGORY']=$place;
		if($id)
			DATABASE()->query('UPDATE ?_news set ?a where `ID`=?;',$key,$id);
		else
			DATABASE()->query('INSERT INTO ?_news (?#) VALUES(?a);',array_keys($key),array_values($key));
	}
/**
 *  выкинуть новость
 */
	function do_killnews($id=''){
		if (!$this->parent->has_rights(right_WRITE)) 
			return "Sorry. you have no right to kill this news!" ;
		if(!$id) $id=pps($_GET['nid']);
		if($id)
			DATABASE()->query('DELETE from ?_news where `ID`=?;',$id);
		$this->parent->go();
	}
/**
 *  выдать форму добавления нового сообщения
 */
	function do_addnews($id=''){
	// меняем шаблон у parent'а
	//$this->parent->tpl=NEWTOUR_TPL;
		if (!$this->parent->has_rights(right_WRITE)){
			return "Sorry. you have no right to add new news!" ;
		}
		if(!$id) $id=pps($_GET['nid']);
	// список всех игроков

		$news_form=new form('news');
		$news_form->var=array();
		$news_form->scanHtml($this->parent->_tpl('tpl_main','_AddNews',Array()));
		if($news_form->handle()){
			debug($news_form->var);
			//if(!empty($news_form->var['PLACE']))$news_form->var['PLACE']=$this->parent->user['lastclub'];
			$this->add_News($news_form->var['text'],
					$news_form->var['DATE'],
					$news_form->var['PLACE'],
					$news_form->var['ID']);
			$this->parent->go();
		}
		if($id){
			$par=DATABASE()->selectrow('select * from ?_news where `ID`=?;',$id);
		} else {
			$par=array('ID'=>'','DATE'=>date('Y/m/d H:i:s'));
		}
		$news_form->var=array_merge($news_form->var,$par);
		return $news_form->getHtml(' ');
	}
/**
 *  Показать новости
 */
	function show_News($id=null,$limit=null){
		//debug('news',$id);
		if (is_null($id)) return array();
		$where=' where CATEGORY='.intval($id);
		if (is_null($limit)) $limit=3;
		$res=DATABASE()->query('select * from ?_news'.$where.' ORDER BY `DATE` DESC LIMIT '.$limit.';');
		setlocale(LC_ALL,'ru_RU.CP1251','ru_RU','rus');
		//setlocale(LC_ALL,'ru_RU.UTF-8','ru_RU','rus');
		foreach($res as $k=>&$v) {
			$v['DATE']=iconv('windows-1251','UTF-8',strftime('%a, %d %b %H:%M',strtotime ($v['DATE'])));
		}
		return $res;
	}
}
