<?php
/**
 * поддержка хранения турниров в базе и интерфейс к ним
 */

/**
 * хелпер для работы с таблицей ?_tourplayers. Заодно и кеширует
 */
class tPlayer {
    /**
     * @var array
     */
    private static $cache=array();
    /**
     * базовые поля турнирной таблицы
     */
    private static $base_fields=array('ID_PLAYER','ID_TOURNAMENT','NUMBER','RES1','RES2');

    static function delete_from_trn($trn,$pl=0){
        if(empty($trn)) return ;
        $cond=array();
        if(is_array($trn)){
            foreach($trn as $t) unset(self::$cache[$t]);
            $cond []='`ID_TOURNAMENT` in ('.implode(',',$trn).')';
        } else {
            unset(self::$cache[$trn]);
            $cond []='`ID_TOURNAMENT`='.intval($trn);
        };
        if(empty($pl)){
        } elseif(is_array($pl)){
            $cond []='`ID_PLAYER` in ('.implode(',',$pl).')';
        } else {
            $cond []='`ID_PLAYER`='.intval($pl);
        };
        DATABASE()->query(
            'DELETE FROM ?_tourplayers where '.implode(' and ',$cond).';'
        );
    }

    static function players_by_trn($trn=0){
        $trn=ppi($trn,$_SESSION['lastclub']);
        if (isset(self::$cache[$trn])) return self::$cache[$trn] ;
        return self::$cache[$trn]=self::unpack(DATABASE()->select(
            'SELECT * FROM ?_tourplayers where `ID_TOURNAMENT`=? order by `NUMBER`;'
            ,$trn
            ));
    }

    static function pack($key){
        if(empty($key)) return null;
        $diff=array_diff (array_keys($key),self::$base_fields);
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
    }
    /**
     * переложить значения упакованных полей в сам массив
     * @static
     * @param $key
     * @return array
     */
    static function unpack($key){
        if(!empty($key['DESCR'])){
			$x=@unserialize($key['DESCR']);
			if(!empty($x)){
				$key=array_merge($key,$x);
			}
		}
        unset($key['DESCR']);
        return $key;
    }
}

/**
 * хелпер для работы с таблицей игроков
 */

class plr {
    static function select($id=0){
        if(empty($id))
            return DATABASE()->selectRow('select * from ?_players');
        else
            return DATABASE()->selectRow('select * from ?_players where id=?',$id);
    }

    /**
     * @static
     * @param string $name1
     * @param string $name2
     * @return int
     */
    static function find_by_name($name1,$name2){
        $res= DATABASE()->selectRow('select * from ?_players where NAME=? and NAME1=?'
			,trim(pps($name1)),trim(pps($name2)));
        if(!empty($res))
            return $res['ID_PLAYER'];
        else
            return 0;
    }

    static function insert($name1,$name2=''){
        if(is_array($name1)){
            return DATABASE()->query('INSERT INTO ?_players (?#) VALUES(?a);',array_keys($name1),array_values($name1));
        } else
            return DATABASE()->query('insert into ?_players set NAME=?,NAME1=?'
				,trim($name1),trim($name2));
    }

    static function update($name1,$name2,$id=0){
        if(is_array($name1))
            DATABASE()->query('UPDATE  ?_players set ?a where `ID`=?;',$name1,$name2);
        else
            DATABASE()->query('update ?_players set NAME=?, NAME1=? where ID=?'
                ,pps($name1),pps($name2),ppi($id));
    }

}
/**
  * хелпер работы с таблицей турниров
  */
class trn {

    /**
     * базовые поля турнирной таблицы
     */
    static $base_fields=array('ID','PARENT','DATE','LEVEL','NAME','STATUS','RULE');

    /**
     * переложить значения упакованных полей в сам массив
     * @static
     * @param $key
     * @return array
     */
    static function unpack(&$key){
        if(!empty($key['DESCR'])){
			$x=@unserialize($key['DESCR']);
			if(!empty($x)){
				$key=array_merge($key,$x);
			}
		}
        unset($key['DESCR']);
        return $key;
    }
    /**
     * сохранить массив данных как строчку таблицы
     * @static
     * @param null $key
     * @return array|null
     */
    static function store($key=null){
        if(empty($key)) return null;
        $diff=array_diff (array_keys($key),self::$base_fields);
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

        if(isset($key['ID'])){
            trn::update($key);
            return 0;
        } else {
            return trn::insert($key);
        }
    }

    /**
     * проверить наличие строчки 0 уровня с таким-же именем
     * @TODO: а оно мне дейстительно нужно?
     * @static
     * @param $name
     * @return bool
     */
    static function name_exists($name){
        $res=DATABASE()->selectRow('select ID from ?_tournaments where `LEVEL`=0 '
					.'and `NAME`=?;',$name);
        return !empty($res);
    }

    /**
     * выбрать турнир по ID
     * @static
     * @param $id
     * @return
     */
    static function select($id){
        return DATABASE()->selectRow('select * from ?_tournaments where `ID`=?;',$id) ;
    }

    static function select_childs($id){
        return DATABASE()->select('select * from ?_tournaments where `PARENT`=?  ORDER BY `NAME`;',$id) ;
    }

    static function delete($trn){
        $cond=array();
        if(empty($trn))
            return;
        elseif(is_array($trn)){
            $cond []='`ID` in ('.implode(',',$trn).')';
        } else {
            $cond[] ='`ID`='.intval($trn);
        };
        DATABASE()->query(
            'DELETE FROM ?_tournaments where '.implode(' and ',$cond).';'
        );
    }

    static function insert(&$key){
        return DATABASE()->query('INSERT INTO ?_tournaments (?#) VALUES(?a);',array_keys($key),array_values($key));
    }

    static function update(&$key){
        $k=$key['ID'];
        unset($key['ID']);
        DATABASE()->query('UPDATE  ?_tournaments set ?a where `ID`=?;',$key,$k);
    }
}
/**
 * базовый класс, определяющий интерфейс
 * @author RMO
 *
 */
class tournament {

    public
	/**
	 * турнир-предок
     * @var tournament	 */
 		 $parent = null,

	/**
	 * собствено данные из таблицы
     * @var boolean
	 */	
		$changed = false,
    /**
     * @var array
     */
		$data,
    /* @var array of tournaments */
		$childs=null,
	/**
	 * результаты турнира
	 */	
		$tresult
		;
		
	/**
	 * Конструктор. Как правило вызывается из статического конструктора
	 * @param array $trn
	 */
	function __construct($trn){
		$this->data=$trn;
        // читаем список участников
		if(!empty($trn['ID'])){
			$this->tresult = tPlayer::players_by_trn($trn['ID']);
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
			return $this->get('STATUS')==1;
		if($this->get('STATUS')!=1 && $val){
			$this->set("STATUS",1);
		} else if ($this->get("STATUS")!=2 && !$val){
			$this->set("STATUS",2);
		}
	}
	
    function set($key,$value=null){
        if(empty($key)) return;
        if(is_array($key)){
            if(!$this->changed)
                foreach($key as $k=>$v){
                    if ($this->get($k)!=$v){
                        $this->changed=true; break;
                    }
                };
            $this->data=array_merge($this->data,$key);
        } else {
            if ($this->get($key)!=$value){
                $this->changed=true;
                $this->data[$key]=$value;
            }
        }
    }

    /**
     * @param string $key
     * @return mixed
     */
    function get($key=null,$def=null){
        if(is_null($key))
            return $this->data;
        if(isset($this->data[$key]))
            return $this->data[$key];
        else
            return $def;
    }

    /**
     * @return int
     */
	function getId(){
		return $this->get("ID",0);
	}
	
	function deleteChilds(){
		$this->prepChilds();
		$child_idx=array();
		foreach($this->childs as $child){
			$child_idx[]=$child->getId();
			$child->deleteChilds();
		}
		if(!empty($child_idx))
            tPlayer::delete_from_trn($child_idx);
        trn::delete($child_idx);
	}
	function delete(){
		$this->deleteChilds();
        trn::delete($this->getId());
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
		$id=trn::store($this->get());
        if(is_null($id)) return ;
        if(!empty($id))
            $this->set(array("ID"=>$id));
        else
		    $id=$this->getId();
        debug($id,$this);
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
            tPlayer::delete_from_trn($id,array_keys($delete));

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
			$tournament = trn::select($id);
			//print_r($tournament);
			$cache[$id]=tournament::createTournament($tournament);
		}
		return $cache[$id];
	}
	
	static function createTournament($tournament){
		$classname='trn_'.$tournament['RULE'];
        trn::unpack($tournament);
		
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
        tPlayer::delete_from_trn($this->getId(),$player);
	}
	
	/**
	 * Турнирная таблица
	 * @param unknown_type $x
	 * @param unknown_type $y
	 */
	function getTable(){
		return array('par'=>$this->data,'players'=>$this->tresult);
	}
	
	/**
	 * обеспечить чтение всех child-турниров 
	 */
	function prepChilds(){
		if(is_null($this->childs)){
			$childs = trn::select_childs($this->getId()) ;
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
			$this->parent=tournament::getTournament($this->get("PARENT"));
	}
	
}

/**
 * класс с турнирной таблицей
 *
 * @author RMO
 *
 */
class trn_table extends tournament {
	
	function set($key){
		if(ppi($key['ASCORE'])!=ppi($this->get("ASCORE"))){
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
			'LEVEL'=>$this->get("LEVEL")+1
			,'RULE'=>'meeting'
			,'PARENT'=>$this->getId()
			,'ASCORE'=>ppi($this->get("ASCORE"),1)
			,'NAME'=>$this->childname($x-1,$y-1)));
        //debug($x,$y,$child->get());
        //$child->save();
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
			'LEVEL'=>$this->get("LEVEL")+1
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
			'LEVEL'=>$this->get("LEVEL")+1
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
			$x['title']=$child->get("NAME");
	/*		if ($rown==$celn) {
				$table[$celn][$rown]=array('z'=>1);
			};
			if (!isset($table[$rown][$celn])){
				$table[$rown][$celn]=array('name'=>$ref);
			};
			if (!isset($table[$celn][$rown])){
				$table[$celn][$rown]=array('name'=>$ref);
			}*/
			$table[]=$x;
		}
		return array('table'=>$table/*,'res'=>$result*/);
	}
	
	// 8 игроков 
	function solve($pnum=8){
		// пока только для 8 игроков
		$this->deleteChilds();
		// создаем финальный турнир
		$x14=tournament::createTournament(array(
			'LEVEL'=>$this->get("LEVEL")+1
			,'RULE'=>'meeting'
			,'PARENT'=>$this->getId()
			,'NAME'=>'финал'))->save();
		// ветка лузеров
		// финальный турнир ветки лузеров
		$x13=tournament::createTournament(array(
			'LEVEL'=>$this->get("LEVEL")+1
			,'RULE'=>'meeting'
			,'PARENT'=>$this->getId()
			,'NAME'=>'финал группы2'
			// победитель уходит на следующую игру, проиграший имеет 3 место
			,'FINAL'=>'follow','param'=>'1>'.$x14->getId().';2>3place'))->save();
		// ветка лузеров
		// полуфинальный турнир ветки лузеров
		$x12=tournament::createTournament(array(
			'LEVEL'=>$this->get("LEVEL")+1
			,'RULE'=>'meeting'
			,'PARENT'=>$this->getId()
			,'NAME'=>'12. полуфинал группы2'
			// победитель уходит на следующую игру, проиграший вылетает
			,'FINAL'=>'follow','param'=>'1>'.$x13->getId()))->save();
		// ветка лузеров
		// полуфинальный турнир ветки лузеров
		$x11=tournament::createTournament(array(
			'LEVEL'=>$this->get("LEVEL")+1
			,'RULE'=>'meeting'
			,'PARENT'=>$this->getId()
			,'NAME'=>'11. полуфинал группы1'
			// победитель уходит на следующую игру, проиграший в ветку лузеров
			,'FINAL'=>'follow','param'=>'1>'.$x14->getId().';2>'.$x13->getId()))->save();
		// ветка лузеров
		// полуфинальный турнир ветки лузеров
		$x10=tournament::createTournament(array(
			'LEVEL'=>$this->get("LEVEL")+1
			,'RULE'=>'meeting'
			,'PARENT'=>$this->getId()
			,'NAME'=>'10. четвертьфинал группы2'
			// победитель уходит на следующую игру, проиграший в ветку лузеров
			,'FINAL'=>'follow','param'=>'1>'.$x12->getId()))->save();
		// ветка лузеров
		// полуфинальный турнир ветки лузеров
		$x9=tournament::createTournament(array(
			'LEVEL'=>$this->get("LEVEL")+1
			,'RULE'=>'meeting'
			,'PARENT'=>$this->getId()
			,'NAME'=>'9. четвертьфинал группы2'
			// победитель уходит на следующую игру, проиграший в ветку лузеров
			,'FINAL'=>'follow','param'=>'1>'.$x12->getId()))->save();
		// ветка винеров
		// полуфинальный турнир ветки лузеров
		$x5=tournament::createTournament(array(
			'LEVEL'=>$this->get("LEVEL")+1
			,'RULE'=>'meeting'
			,'PARENT'=>$this->getId()
			,'NAME'=>'5. четвертьфинал группы1'
			// победитель уходит на следующую игру, проиграший в ветку лузеров
			,'FINAL'=>'follow','param'=>'1>'.$x11->getId().';2>'.$x9->getId()))->save();
		// ветка винеров
		// полуфинальный турнир ветки лузеров
		$x6=tournament::createTournament(array(
			'LEVEL'=>$this->get("LEVEL")+1
			,'RULE'=>'meeting'
			,'PARENT'=>$this->getId()
			,'NAME'=>'6. четвертьфинал группы1'
			// победитель уходит на следующую игру, проиграший в ветку лузеров
			,'FINAL'=>'follow','param'=>'1>'.$x11->getId().';2>'.$x9->getId()))->save();
		// ветка лузеров
		// полуфинальный турнир ветки лузеров
		$x7=tournament::createTournament(array(
			'LEVEL'=>$this->get("LEVEL")+1
			,'RULE'=>'meeting'
			,'PARENT'=>$this->getId()
			,'NAME'=>'7. 1/8 финала группы2'
			// победитель уходит на следующую игру, проиграший в ветку лузеров
			,'FINAL'=>'follow','param'=>'1>'.$x10->getId()))->save();
		$x8=tournament::createTournament(array(
			'LEVEL'=>$this->get("LEVEL")+1
			,'RULE'=>'meeting'
			,'PARENT'=>$this->getId()
			,'NAME'=>'8. 1/8 финала группы2'
			// победитель уходит на следующую игру, проиграший в ветку лузеров
			,'FINAL'=>'follow','param'=>'1>'.$x10->getId()))->save();
		// ветка винеров
		// полуфинальный турнир ветки лузеров
		$x1=tournament::createTournament(array(
			'LEVEL'=>$this->get("LEVEL")+1
			,'RULE'=>'meeting'
			,'PARENT'=>$this->getId()
			,'NAME'=>'1. 1/8 группы1'
			// победитель уходит на следующую игру, проиграший в ветку лузеров
			,'FINAL'=>'follow','param'=>'1>'.$x5->getId().';2>'.$x7->getId()))->save();
		// ветка винеров
		// полуфинальный турнир ветки лузеров
		$x2=tournament::createTournament(array(
			'LEVEL'=>$this->get("LEVEL")+1
			,'RULE'=>'meeting'
			,'PARENT'=>$this->getId()
			,'NAME'=>'2. 1/8 группы1'
			// победитель уходит на следующую игру, проиграший в ветку лузеров
			,'FINAL'=>'follow','param'=>'1>'.$x5->getId().';2>'.$x7->getId()))->save();
		// ветка винеров
		// полуфинальный турнир ветки лузеров
		$x3=tournament::createTournament(array(
			'LEVEL'=>$this->get("LEVEL")+1
			,'RULE'=>'meeting'
			,'PARENT'=>$this->getId()
			,'NAME'=>'3. 1/8 группы1'
			// победитель уходит на следующую игру, проиграший в ветку лузеров
			,'FINAL'=>'follow','param'=>'1>'.$x6->getId().';2>'.$x8->getId()))->save();
		// ветка винеров
		// полуфинальный турнир ветки лузеров
		$x4=tournament::createTournament(array(
			'LEVEL'=>$this->get("LEVEL")+1
			,'RULE'=>'meeting'
			,'PARENT'=>$this->getId()
			,'NAME'=>'4. 1/8 группы1'
			// победитель уходит на следующую игру, проиграший в ветку лузеров
			,'FINAL'=>'follow','param'=>'1>'.$x6->getId().';2>'.$x8->getId()))->save();
	}
}
/**
 * заголовочный турнир - клуб или название группы турниров
 *  смысл полей :
 * STATUS
 */
class trn_title extends tournament {

    function addChild($par){
        $this->prepChilds();
        // поискать турнир
        $child=tournament::createTournament(array_merge(array(
                'LEVEL'=>$this->get("LEVEL")+1
                ,'PARENT'=>$this->getId()
             ),$par));
        $child->save();
        return $child;
    }

}