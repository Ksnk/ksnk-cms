<?php
// остатки кода из darts.php
/**
 *  Класс для работы с рейтингом игроков
 *  Типовые задачи
 * 	- перерасчет рейтинга, в зависимости от результатов турнира.
 * 	-- батон - пересчитать рейтинг по правилу 30-26-22-18-12-9-7-5-3-2-1-1-...
 * 	-- батон - Да! Это круто, добавь обсчитанный рейтинг всем игрокам
 *  -- батон - Оопс. Не надо, какая-то хрень
 *
 *
 */
class darts_Rating extends plugin {
	/**
	 * Вывод текущего рейтинга игроков
	 */
	function do_list(){

	}
	
	function do_tourn_List(){
		// вывод списка всех турниров с кнопкой обсчета рейтинга
	// меняем шаблон у parent'а
	//$this->parent->tpl=NEWTOUR_TPL;
		if (!$this->parent->has_rights(right_WRITE)){
			return "Sorry. you have no right to change a rating!" ;
		}
	// список всех игроков

		$par=$this->parent->export('darts_Tourn','_list','');

		$form=new form('rating');
		$form->var=array();
		$form->scanHtml($this->parent->_tpl('tpl_main','_Rating',array('trn'=>$par)));
		if($form->handle()){
			$this->parent->go();
		}
		return $form->getHtml(' ');
	}
}
/**
 *	Класс для реализации календаря проведения партий
 *
 */
class darts_Calendar extends plugin{

/**
 * вывести календарь как меню
 */
	function input(){
		return array('data'=>'КалендарЪ');
	}
/**
 * вывести кнопку - "запланировать игру"
 */
	function plan_button(){
		return array('link'=>'?do=Game_planing&plugin='.str_replace('darts_','',__CLASS__),'data'=>'Запланировать');
	}
/**
 * вывести форму - отметте желаемое время проведения партии
 */
	function Game_planing(){
	// меняем шаблон у parent'а
//		$this->parent->tpl=NEWTOUR_TPL;
	// список всех игроков
		$par=array('pl1'=>array(),'pl2'=>array());
		$pllist=$this->parent->export('darts_Players','_list');
		for($i=0;$i<count($pllist);$i++){
			$x=$pllist[$i];
			$par['pl1'][]=array('id'=>$x['ID'],'val'=>$x['name']);
			$par['pl2'][]=array('id'=>$x['ID'],'val'=>$x['name']);
		}
		$tlist=$this->parent->export('darts_Tourn','_list');
		for($i=0;$i<count($tlist);$i++){
			$x=$tlist[$i];
			$par['trn'][]=array('id'=>$x['ID'],'val'=>$x['NAME']);
		}
		$plan_form=new form('game');
		$plan_form->var=array();
		$plan_form->scanHtml($this->parent->_tpl('tpl_main','_GamePlan',$par));
		if ($plan_form->handle()){

		}
		return $plan_form->getHtml(' ');
	}
}

