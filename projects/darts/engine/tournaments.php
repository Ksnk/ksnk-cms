<?php
/**
 * поддержка хранения турниров в базе и интерфейс к ним
 */

/**
 * базовый класс, определяющий интерфейс
 * @author RMO
 *
 */
class tournament {
		
	public	
	/**
	 * турнир-предок
	 */
		$parent
		
	/**
	 * базовые поля турнирной таблицы
	 */	
		,$base_fields=array('ID','PARENT','DATE','LEVEL','NAME','STATUS','RULE','SUBRULE')
		
	/**
	 * собствено данные из таблицы
	 */	
		,$changed = false
		,$tournament
		,$childs=null
	/**
	 * результаты турнира
	 */	
		,$tresult
		;
		
	/**
	 * Конструктор. Как правило вызывается из статического конструктора
	 * @param array $trn
	 */
	function __construct($trn){
		$this->tournament=$trn;
		// читаем список участников
		if(!empty($trn['ID'])){
			$this->tresult = DATABASE()->select(
				'SELECT * FROM ?_tourplayers where `ID_TOURNAMENT`=? order by `NUMBER`;'
				,$trn['ID']);
			$number = 0;	
			foreach($this->tresult as &$v) {
				$v['NUMBER']=++$number;
			}
		}	
	}
	function __destruct(){
		if($this->changed)
			$this->save();
	}
	
	function finished($val=null){// 2- идет 1 - завершен
		if(is_null($val))
			return $this->tournament['STATUS']==1;
		if($this->tournament['STATUS']!=1 && $val){
			$this->tournament['STATUS']=1;
			$this->changed = true;
		} else if ($this->tournament['STATUS']!=2 && !$val){
			$this->tournament['STATUS']=2;
			$this->changed = true;
		}
	}
	
	function set($key){
		if(empty($key)) return;
		if(!$changed)	
			foreach($key as $k=>$v){
				if (pps($this->tournament[$k])!=$v){
					$this->changed=true; break;
				}	
			};
		$this->tournament=array_merge($this->tournament,$key);
	}
	
	function getId(){
		if(!empty($this->tournament['ID']))
			return $this->tournament['ID'];
		else 
			return 0;	
	}
	
	function deleteChilds(){
		$this->prepChilds();
		$child_idx=array();
		foreach($this->childs as $child){
			$child_idx[]=$child->getId();
			$child->deleteChilds();
		}
		if(!empty($child_idx))
		DATABASE()->query(
			'DELETE FROM ?_tourplayers where `ID_TOURNAMENT` in ('.implode(',',$child_idx).');'
		);
		DATABASE()->query(
			'DELETE FROM ?_tournaments where `PARENT`=?;',$this->getId()
		);
	}
	function delete(){
		$this->deleteChilds();
		DATABASE()->query(
			'DELETE FROM ?_tournaments where `id`=?;',$this->getId()
		);
	}
	
	// очистить счетчик очков
	function clearscore(){
		DARTS::clearscore($this);
	}
	
	function recalc(){
		//$this->prepChilds();
		// вычислить собственный результат по набранным очкам
		// 1 очковое значение - 1 или 0 в 4 очковое значение. 2 и 3 значение 
		
	}
	
	function getPlayerByNumber($num){
		return $this->tresult[$num-1];
	}

	/**
	 * сохранить турнир обратно
	 */
	function &save(){
		$key=$this->tournament;
		$diff=array_diff (array_keys($key),$this->base_fields);
		//print_r($diff);
		if(!empty($diff)){
			unset($key['DESCR']);
			$descr=array();
			foreach($diff as $d){
				if(!empty($key[$d]))
					$descr[$d]=$key[$d];
				unset($key[$d]);
			};
			$key['DESCR']=serialize($descr);
		}
		if(!!($this->getId())){
			unset($key['ID']);
			DATABASE()->query('UPDATE  ?_tournaments set ?a where `ID`=?;',$key,$this->getId());
		} else {
			$this->tournament['ID']=
			DATABASE()->query('INSERT INTO ?_tournaments (?#) VALUES(?a);',array_keys($key),array_values($key));
		}
		$id=$this->getId();
		// сохранить список игроков
		/**
		 * жеребьевка, если нужно
		 */	
		$players=DATABASE()->selectCol(
			'SELECT ID_PLAYER as ARRAY_KEY FROM ?_tourplayers WHERE ID_TOURNAMENT=?;',$id);
		
		$delete=$players;
		$insert=array();
		$update=array();
		foreach ($this->tresult as &$k){
			if(isset($players[$k['ID_PLAYER']])) {
				$update[]=$k;
				unset($delete[$k['ID_PLAYER']]);
			} else {
				$k['ID_TOURNAMENT']=$this->getId();
				$insert[]=$k;
			} 
		}
		if(!empty($delete))
			DATABASE()->query('delete from ?_tourplayers where ID_PLAYER in ('.implode(',',array_keys($delete)).') and ID_TOURNAMENT=?',$id);

		foreach($insert as &$k){
			$k['ID_TOURNAMENT']=$this->getId();
			DATABASE()->query('INSERT INTO ?_tourplayers(?#) VALUES (?a)',array_keys($k),array_values($k));
		}
		
		foreach($update as $k){
			$idp=$k['ID_PLAYER']; 
			unset($k['ID_PLAYER']);unset($k['ID_TOURNAMENT']);
			DATABASE()->query('UPDATE  ?_tourplayers set ?a where `ID_PLAYER`=? AND `ID_TOURNAMENT`=?;',$k,$idp,$this->getId());
		}
		$this->changed = false;
		return $this;
	}
	/**
	 * построить чилд-турнир для игроков X и Y (номера в турнире)
	 * @param unknown_type $x
	 * @param unknown_type $y
	 */
	function getChild($x,$y){
		return null;
	}
	/**
	 * выдать результат в турнире у игрока $idx1 по индексу $idx2
	 * @param int $idx1
	 * @param int $idx2
	 */
	function &result($idx1,$idx2=null){
		$x=array();	
		if(is_array($idx1)){
			for($i=0;$i<count($this->tresult);$i++){
				if($this->tresult[$i]['ID_PLAYER']==$idx1['ID_PLAYER']){
					$idx1=$i+1; break;
				}
			};
			if(is_array($idx1))
				return $x;
		}
		if(is_null($idx2)){
			if(isset($this->tresult[$idx1-1]))
				return $this->tresult[$idx1-1];
			else {
				debug('xxx');
			}	
		} else 
			if(isset($this->tresult[$idx1-1]))
				return $this->tresult[$idx1-1]['RES'.$idx2];
			else {
				debug('xxx');
			}
		return $x;		
	}

	/**
	 * выдать результат в турнире у игрока $idx1 по индексу $idx2
	 * @param int $idx1
	 * @param int $idx2
	 */
	function setresult($idx1,$idx2,$val){
		if(!is_array($idx1)){
			$this->tresult[$idx1-1]['RES'.$idx2]=$val;
			$this->changed = true;
		} else {
			foreach($this->tresult as &$res){
				if($res['ID_PLAYER']==$idx1['ID_PLAYER']){
					$res['RES'.$idx2]=$val;
					$this->changed = true;
					break;
				}
			}
		}	
	}
	
	/**
	 * конструктор турниров по их типу
	 * читать турнир по его ID
	 * @param unknown_type $id
	 */
	static function &getTournament($id){
		static $cache;
		if(empty($cache[$id])){
			$tournament = DATABASE()->selectRow('select * from ?_tournaments where `ID`=?;',$id) ;
			//print_r($tournament);
			$cache[$id]=tournament::createTournament($tournament);
		}
		return $cache[$id];
	}
	
	static function createTournament($tournament){
		$classname='trn_'.$tournament['RULE'];
		if(!empty($tournament['DESCR'])){
			$x=@unserialize($tournament['DESCR']);
			if(!empty($x)){
				$tournament=array_merge($tournament,$x);
			}
		}
		if (class_exists($classname))
			return new $classname($tournament);
		else {
			debug('Отсутствует класс '.$classname);
			return null;
		}
	}
	
	function addplayer($player,$clear=true){
		if ($clear && isset($player['ID_PLAYER'])){
			$player=array(
				'ID_PLAYER'=>$player['ID_PLAYER'],
				'ID_TOURNAMENT'=>$this->getId(),
				'NUMBER'=>count($this->tresult)+1
			);
		} elseif (!empty($player) && is_int ($player)) {
			$player=array(
				'ID_PLAYER'=>$player,
				'ID_TOURNAMENT'=>$this->getId(),
				'NUMBER'=>count($this->tresult)+1
			);
		} else 
			return ;
		$this->tresult[]=$player;
		$this->changed = true;
	}
	
	function deleteplayer($player){
		if($this->changed)
			$this->save();
		DATABASE()->query(
			'delete FROM ?_tourplayers where `ID_TOURNAMENT`=? and `ID_PLAYER`=?;'
			,$this->getId(),$player);
	}
	
	/**
	 * Турнирная таблица
	 * @param unknown_type $x
	 * @param unknown_type $y
	 */
	function getTable(){
		return array('par'=>$this->tournament,'players'=>$this->tresult);
	}
	
	/**
	 * обеспечить чтение всех child-турниров 
	 */
	function prepChilds(){
		if(is_null($this->childs)){
			$childs = DATABASE()->select('select * from ?_tournaments where `PARENT`=? ORDER BY `NAME`;'
			,$this->getId()) ;
			foreach($childs as $ch){
				$this->childs[$ch['NAME']]=tournament::createTournament($ch);
			}
		}
//		debug('childs-!',count($this->childs));
	}
	/**
	 * обеспечить чтение парента
	 */
	function prepParent(){
		if(empty($this->parent))
			$this->parent=tournament::getTournament($this->tournament['PARENT']);
	}
	
}

/**
 * класс с турнирной таблицей
 * @author RMO
 *
 */
class trn_table extends tournament {
	
	function set($key){
		if(ppi($key['ASCORE'])!=ppi($this->tournament['ASCORE'])){
			$this->prepChilds();
			foreach($this->childs as &$child){
				$child->set(array('ASCORE'=>ppi($key['ASCORE'])));
			}
		};
		tournament::set($key);
	}
	
	/**
	 * построить чилд-турнир для игроков X и Y (номера в турнире)
	 * @param unknown_type $N - child numberf
	 * @param unknown_type $y
	 */
	function getChild($x,$y){
		$this->prepChilds();
		// поискать турнир
		$child=tournament::createTournament(array(
			'LEVEL'=>$this->tournament['LEVEL']+1
			,'RULE'=>'meeting'
			,'PARENT'=>$this->getId()
			,'ASCORE'=>ppi($this->tournament['ASCORE'],1)
			,'NAME'=>$this->childname($x-1,$y-1)));
		$child->addplayer($this->getPlayerByNumber($x));
		$child->addplayer($this->getPlayerByNumber($y));
		$child->save();
		return $child;
	}
	
	function childname($x,$y){
		return '@'.($x).'~'.($y);
	}
	
	/**
	 * Турнирная таблица
	 * @param unknown_type $x
	 * @param unknown_type $y
	 */
	function getTable(){
		// для всех турниров-наследников собираем статистику
		$this->prepChilds();
		$table = array();
		//сочиняем массив данных
		foreach($this->tresult as $row){
			$table[]=array();
		}
		$idx=0;
		foreach($this->tresult as $row){
			$idx++;
			$rown=$row['NUMBER']-1;
			foreach($this->tresult as $cell) {
				$celn=$cell['NUMBER']-1;
			// ячейка 
				// имя турнира в ячейке '@'.$row['id']~$row['id']
				$childname=$this->childname($rown,$celn);
				if(isset($this->childs[$childname])) {
					$child=$this->childs[$childname];
					$ref=sprintf('?do=showtour&id=%s',$child->getId());
					$p1=$child->result(1);$p2=$child->result(2);
					$table[$rown][$celn]=
						array('x'=>$p1['RES1'],'y'=>$p2['RES1']
						,'add'=>sprintf("%01.1f",$p1['RES2']/$p1['RES3']),'name'=>$ref);
					$table[$celn][$rown]=
						array('x'=>$p2['RES1'],'y'=>$p1['RES1']
						,'add'=>sprintf("%01.1f",$p2['RES2']/$p2['RES3']),'name'=>$ref);
				} else {
					$ref=sprintf('?do=child&id=%s&x=%s&y=%s'
							,$this->getId(),$rown+1,$celn+1);
					if ($rown==$celn) {
						$table[$celn][$rown]=array('z'=>1);
					};
					if (!isset($table[$rown][$celn])){
						$table[$rown][$celn]=array('name'=>$ref);
					};
					if (!isset($table[$celn][$rown])){
						$table[$celn][$rown]=array('name'=>$ref);
					}
				}
			}
			$res=&$this->result($idx);
			$result[$rown]=array($this->tresult[$rown]['ID_PLAYER']
				,$res['RES1']
				,$res['RES2']
				,$res['RES3']
				,$res['RES4']
				,$res['RES5']
				,$res['RES6']);
		} 
		
		return array('table'=>$table,'res'=>$result);
	}
}

/**
 * финальный турнир с несколькими легами
 * @author RMO
 *
 */
class trn_meeting extends tournament {
	/**
	 * построить чилд-турнир для игроков X и Y (номера в турнире)
	 * @param unknown_type $x
	 * @param unknown_type $y
	 */
	function getChild($x,$y){
		$this->prepChilds();
		// поискать турнир
		$child=tournament::createTournament(array(
			'LEVEL'=>$this->tournament['LEVEL']+1
			,'RULE'=>'game'
			,'PARENT'=>$this->getId()
			,'NAME'=>$this->childname($x-1,$y-1)));
		$child->addplayer($this->getPlayerByNumber($x));
		$child->addplayer($this->getPlayerByNumber($y));
		$child->save();
		return $child;
	}
	
}
/**
 * турнир разбит на несколько групп
 * как правило - отборочные, финальные и по силе игроков
 * турнирная таблица - набор турнирных таблиц кадого турнира.
 */
class trn_group extends tournament {
	function addChild(){
		$this->prepChilds();
		// поискать турнир
		$child=tournament::createTournament(array(
			'LEVEL'=>$this->tournament['LEVEL']+1
			,'RULE'=>'table'
			,'PARENT'=>$this->getId()
			,'NAME'=>'группа №'.(count($this->childs)+1)));
		$child->save();
		return $child;
	}
	
}
/**
 * финальный турнир с единичной встречей
 */
class trn_game extends tournament {
	// 2 игрока принципиально
}
/**
 * финальный турнир с единичной встречей
 */
class trn_finn extends tournament {
	
	/**
	 * Турнирная таблица
	 * @param unknown_type $x
	 * @param unknown_type $y
	 */
	function getTable(){
		// для всех турниров-наследников собираем статистику
		$this->prepChilds();
		$table = array();
		//сочиняем массив данных
		$idx=0;
		//debug('childs',count($this->childs));
		foreach($this->childs as $child){
			$x=array('idx'=>++$idx);
			
			$ref=sprintf('?do=child&id=%s&x=%s'
					,$this->getId(),$idx);
			$x['title']=$child->tournament['NAME'];
			if ($rown==$celn) {
				$table[$celn][$rown]=array('z'=>1);
			};
			if (!isset($table[$rown][$celn])){
				$table[$rown][$celn]=array('name'=>$ref);
			};
			if (!isset($table[$celn][$rown])){
				$table[$celn][$rown]=array('name'=>$ref);
			}
			$table[]=$x;
		}
		return array('table'=>$table,'res'=>$result);
	}
	
	// 8 игроков 
	function solve($pnum=8){
		// пока только для 8 игроков
		$this->deleteChilds();
		// создаем финальный турнир
		$x14=tournament::createTournament(array(
			'LEVEL'=>$this->tournament['LEVEL']+1
			,'RULE'=>'meeting'
			,'PARENT'=>$this->getId()
			,'NAME'=>'финал'))->save();
		// ветка лузеров
		// финальный турнир ветки лузеров
		$x13=tournament::createTournament(array(
			'LEVEL'=>$this->tournament['LEVEL']+1
			,'RULE'=>'meeting'
			,'PARENT'=>$this->getId()
			,'NAME'=>'финал группы2'
			// победитель уходит на следующую игру, проиграший имеет 3 место
			,'FINAL'=>'follow','param'=>'1>'.$x14->getId().';2>3place'))->save();
		// ветка лузеров
		// полуфинальный турнир ветки лузеров
		$x12=tournament::createTournament(array(
			'LEVEL'=>$this->tournament['LEVEL']+1
			,'RULE'=>'meeting'
			,'PARENT'=>$this->getId()
			,'NAME'=>'12. полуфинал группы2'
			// победитель уходит на следующую игру, проиграший вылетает
			,'FINAL'=>'follow','param'=>'1>'.$x13->getId()))->save();
		// ветка лузеров
		// полуфинальный турнир ветки лузеров
		$x11=tournament::createTournament(array(
			'LEVEL'=>$this->tournament['LEVEL']+1
			,'RULE'=>'meeting'
			,'PARENT'=>$this->getId()
			,'NAME'=>'11. полуфинал группы1'
			// победитель уходит на следующую игру, проиграший в ветку лузеров
			,'FINAL'=>'follow','param'=>'1>'.$x14->getId().';2>'.$x13->getId()))->save();
		// ветка лузеров
		// полуфинальный турнир ветки лузеров
		$x10=tournament::createTournament(array(
			'LEVEL'=>$this->tournament['LEVEL']+1
			,'RULE'=>'meeting'
			,'PARENT'=>$this->getId()
			,'NAME'=>'10. четвертьфинал группы2'
			// победитель уходит на следующую игру, проиграший в ветку лузеров
			,'FINAL'=>'follow','param'=>'1>'.$x12->getId()))->save();
		// ветка лузеров
		// полуфинальный турнир ветки лузеров
		$x9=tournament::createTournament(array(
			'LEVEL'=>$this->tournament['LEVEL']+1
			,'RULE'=>'meeting'
			,'PARENT'=>$this->getId()
			,'NAME'=>'9. четвертьфинал группы2'
			// победитель уходит на следующую игру, проиграший в ветку лузеров
			,'FINAL'=>'follow','param'=>'1>'.$x12->getId()))->save();
		// ветка винеров
		// полуфинальный турнир ветки лузеров
		$x5=tournament::createTournament(array(
			'LEVEL'=>$this->tournament['LEVEL']+1
			,'RULE'=>'meeting'
			,'PARENT'=>$this->getId()
			,'NAME'=>'5. четвертьфинал группы1'
			// победитель уходит на следующую игру, проиграший в ветку лузеров
			,'FINAL'=>'follow','param'=>'1>'.$x11->getId().';2>'.$x9->getId()))->save();
		// ветка винеров
		// полуфинальный турнир ветки лузеров
		$x6=tournament::createTournament(array(
			'LEVEL'=>$this->tournament['LEVEL']+1
			,'RULE'=>'meeting'
			,'PARENT'=>$this->getId()
			,'NAME'=>'6. четвертьфинал группы1'
			// победитель уходит на следующую игру, проиграший в ветку лузеров
			,'FINAL'=>'follow','param'=>'1>'.$x11->getId().';2>'.$x9->getId()))->save();
		// ветка лузеров
		// полуфинальный турнир ветки лузеров
		$x7=tournament::createTournament(array(
			'LEVEL'=>$this->tournament['LEVEL']+1
			,'RULE'=>'meeting'
			,'PARENT'=>$this->getId()
			,'NAME'=>'7. 1/8 финала группы2'
			// победитель уходит на следующую игру, проиграший в ветку лузеров
			,'FINAL'=>'follow','param'=>'1>'.$x10->getId()))->save();
		$x8=tournament::createTournament(array(
			'LEVEL'=>$this->tournament['LEVEL']+1
			,'RULE'=>'meeting'
			,'PARENT'=>$this->getId()
			,'NAME'=>'8. 1/8 финала группы2'
			// победитель уходит на следующую игру, проиграший в ветку лузеров
			,'FINAL'=>'follow','param'=>'1>'.$x10->getId()))->save();
		// ветка винеров
		// полуфинальный турнир ветки лузеров
		$x1=tournament::createTournament(array(
			'LEVEL'=>$this->tournament['LEVEL']+1
			,'RULE'=>'meeting'
			,'PARENT'=>$this->getId()
			,'NAME'=>'1. 1/8 группы1'
			// победитель уходит на следующую игру, проиграший в ветку лузеров
			,'FINAL'=>'follow','param'=>'1>'.$x5->getId().';2>'.$x7->getId()))->save();
		// ветка винеров
		// полуфинальный турнир ветки лузеров
		$x2=tournament::createTournament(array(
			'LEVEL'=>$this->tournament['LEVEL']+1
			,'RULE'=>'meeting'
			,'PARENT'=>$this->getId()
			,'NAME'=>'2. 1/8 группы1'
			// победитель уходит на следующую игру, проиграший в ветку лузеров
			,'FINAL'=>'follow','param'=>'1>'.$x5->getId().';2>'.$x7->getId()))->save();
		// ветка винеров
		// полуфинальный турнир ветки лузеров
		$x3=tournament::createTournament(array(
			'LEVEL'=>$this->tournament['LEVEL']+1
			,'RULE'=>'meeting'
			,'PARENT'=>$this->getId()
			,'NAME'=>'3. 1/8 группы1'
			// победитель уходит на следующую игру, проиграший в ветку лузеров
			,'FINAL'=>'follow','param'=>'1>'.$x6->getId().';2>'.$x8->getId()))->save();
		// ветка винеров
		// полуфинальный турнир ветки лузеров
		$x4=tournament::createTournament(array(
			'LEVEL'=>$this->tournament['LEVEL']+1
			,'RULE'=>'meeting'
			,'PARENT'=>$this->getId()
			,'NAME'=>'4. 1/8 группы1'
			// победитель уходит на следующую игру, проиграший в ветку лузеров
			,'FINAL'=>'follow','param'=>'1>'.$x6->getId().';2>'.$x8->getId()))->save();
	}
}
/**
 * заголовочный турнир
 */
class trn_title extends tournament {

	
}