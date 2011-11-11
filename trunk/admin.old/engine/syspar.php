<?php
/**
 *  - storePar(name,val,type) - поместить параметр с именем name на хранение
 *  - getPar(name) - получить параметр обратно.
 */


class sysPar extends plugins {

	var $parameters,
		$special_words=array('root','record','name','password','url');

		// формирование URL из подручных материалов
	var $url=FALSE;
	/**
	 * установка недостающих параметров в URL
	 * @param $arr
	 */
	function setUrl($arr){
		if ($this->url===FALSE) {
			parse_str(htmlspecialchars_decode ($_SERVER['QUERY_STRING']),$this->url);
			$this->root=preg_replace('/index.php$/i','',$_SERVER['PHP_SELF']);
		}
		$this->url=array_merge($this->url,$arr);
	}
	/**
	 * выдать адрес, начиная от рута
	 * @param $arr - адрес от рута
	 * @param $add - список параметров для импорта из старого урла
	 */
	function getRoot($addr=''){
		return toUrl(INDEX_PATH.'/');
	}

	/**
     * построить урл? получив параметрами парамеры
     * @param array url - параметры урла.
     *
     * Чистка как для curl'а
     */
    function url($par){
        static $url;
        if (!isset($url)){
            //$this->strips($_SERVER['QUERY_STRING']);
            parse_str( htmlspecialchars_decode ($_SERVER['QUERY_STRING']),$url);
            if (!empty($_GET['do'])) $url['do']=$_GET['do'];
            if (!empty($_GET['id'])) $url['id']=$_GET['id'];
        }

        $x=array_merge($url,$par);
        unset($x['ajax'],$x['url'],$x['lang']);
        if($par=='do'){
            unset($x['item'],$x['user'],$x['year'],$x['pg'],$x['url'],$x['ch'],$x['plugin'],$x['cat'],$x['topic']);
        }

        $url=toUrl(INDEX_PATH);
        debug($x,$url);
        if(!defined('IS_ADMIN') && class_exists('altname')){
            if (isset($x['do']) && $x['do']=='menu' && isset($x['id'])){
                $url=$this->export('altname','getrealaddr',$x['id']);
                unset($x['do'],$x['id']);
            } else if(isset($x['do'])) {
                if($x['do']!='menu')
                    $url.='/'.$x['do'];
                unset($x['do']);
                if(isset($x['id'])){
                    if(!empty($x['id']))
                        $url.='/'.$x['id'];
                    unset($x['id']);
                }
            }
        }
        if(empty($x))
            return $url;
        else
            return $url.'?'.http_build_query($x);

    }

	/**
	 * выдать адрес, начиная от рута
	 * @param $arr - адрес от рута
	 * @param $add - список параметров для импорта из старого урла
	 */
	function getRootUrl($do='',$id=FALSE){
		// сокращение getUrl('search');
		if(empty($do)) $do='menu';
		$arr=array('do'=>$do);
		if(!empty($id)) $arr['id']=$id;
// ЧПУ
//		$x=array_merge($this->url;
		
/**
 * 		делаем новый массив параметров
 */		
		
/**
 *		сливаем его в один урл
 */ 
		if(empty($this->root))
			$this->root=preg_replace('/index.php$/i','',$_SERVER['PHP_SELF']);
		$x=rtrim($this->root,'/');
		if(!defined('IS_ADMIN') && class_exists('altname')){
			if ($res=$this->export('altname','getrealaddr',$id)){
				//debug($res);
			} else if(isset($arr['do'])){
				if($arr['do']!='menu')
					$x.='/'.$arr['do'];
				unset($arr['do']);	
				if(isset($arr['id'])){
					if(!empty($arr['id']))
						$x.='/'.$arr['id'];
					unset($arr['id']);	
				}
			}
		}
		if(!empty($res))
			return $x.'/'.$res;
		else if(empty($arr))
			return $x;
		else
			return $x.'?'.http_build_query($arr);
	}//curl('do','id').'do=search');

	/**
	 * Интеграция с NestedSets
	 * выдаем список ассоциативных массивов, по имени id'у ветки дерева.
	 */	
	//private function
	function nodeGetInfo($node_id=0) {
		
		if(is_int($node_id) || ctype_digit($node_id)){
			if ($node_id==0){
				return array('pid'=>0,'lid'=>0,'rid'=>0,'level'=>-1);
			}
			return $this->database->selectRow(
				'SELECT * FROM '.TAB_PREF.'_tree WHERE id = ?d',
				$node_id
			);
		} else {
			return @$this->database->selectRow(
				'SELECT * FROM '.TAB_PREF.'_tree WHERE level=0 and name=?;',
				$node_id
			);
		}
	}
	
	function nodeScanId($id){
		if(is_int($id) || ctype_digit($id)) {
			return $this->database->selectCell(
				"select `id` FROM ?_tree WHERE page=?d",$id
			);
        } else if(!isset($_POST['id'])) {
			return -1;
		} else {	
			$x=$this->parent->readRecord(array('url'=>$_POST['id']));
			if(empty($x['id'])) return -1;
			return $this->parent->nodeScanId($x['id']);
		}
	}
	
	function nodeDelete($node_id) {
		debug('node_delete '.$node_id);
		//backtrace();
		$items=$this->nodeGet($node_id);
		$row = $this->nodeGetInfo($node_id);
		if(!isset($row['id'])) return ;
		$left_key=$row['lid'];
		$right_key=$row['rid'];
		//изолировать перемещаемую ветку

		$child_cnt=2*$this->database->query(
			"DELETE FROM ?_tree WHERE lid >=$left_key AND rid <= $right_key"
		);

		$this->database->query(
			"UPDATE ?_tree SET lid = IF(lid > $left_key, lid -".$child_cnt.
			", lid), rid = rid-$child_cnt WHERE rid > $right_key"
		);
		//debug($items);
		foreach($items as $v){
			if(!empty($v['id']))
				$this->delRecord(array('id'=>$v['id']));
		}
	}
	
	function nodeMoveUp($node_id) // поставить элемент первее в списке чилдов
	{
		$row = $this->nodeGetInfo($node_id);
		$with = $this->database->selectRow(
				"SELECT * FROM ?_tree WHERE rid = ?d",
				$row['lid']-1
			);
		if($with)
			return $this->MoveNode($with,$row);
		else
			return false;
	}

	function nodeMoveDn($node_id) // поставить элемент первее в списке чилдов
	{
		$row = $this->nodeGetInfo($node_id);
		$with = $this->database->selectRow(
				"SELECT * FROM ?_tree WHERE lid = ?d",
				$row['rid']+1
			);
		if($with)
			return $this->MoveNode($row,$with);
		else
			return false;
	}
	
	function MoveNode($node_id, $parent_id) {
//		$this->db->query("START TRANSACTION");

		if(!is_array($node_id))
			$row = $this->nodeGetInfo($node_id);
		else
			$row=$node_id;

		if (!empty($parent_id)){
			// пытаемся детектировать ситуацию, когда меняем
			// местами 2 рядомстоящие позиции
			if(!is_array($parent_id))
				$ins_row = $this->nodeGetInfo($parent_id);
			else
				$ins_row=$parent_id;
			if($row['rid']+1==$ins_row['lid']) {
				$dx2=$ins_row['lid']-$row['lid'];
				$dx3=$ins_row['rid']-$row['rid'];
				$this->database->query(
					'update ?_tree set `rid`=`rid`+ IF(`lid` >='.$ins_row['lid'].',-'.$dx2.','.$dx3.'),'.
						' `lid`=`lid`+ IF(`lid` >='.$ins_row['lid'].',-'.$dx2.','.$dx3.')'.
					' WHERE `lid`>='.$row['lid'].' and `rid`<='.$ins_row['rid']
				);
//				$this->db->query("COMMIT");
				return true;
			}
		}
		$level=$row['level'];
		$left_key=$row['lid'];
		$right_key=$row['rid'];
		// изолируемсо
		$child_cnt=$right_key-$left_key+1;

	//чистим
		$child_cnt=2*$this->database->query(
			"update ?_tree set `ignored`=1 where lid >=$left_key AND rid <= $right_key"
		);

		$this->database->query(
			"UPDATE ?_tree SET lid = IF(lid > $left_key, lid -".$child_cnt.
			", lid), rid = rid-$child_cnt WHERE rid > $right_key"
		);
		if($parent_id<=0){
			// вставляем как первый элемент родителя
			$parent_id=-$parent_id;
			$ins_row = $this->nodeGetInfo($parent_id);
			$level_up=$ins_row['level'];
			$right_key_near=$ins_row['rid'];
			$left_key_near=$ins_row['lid'];
			$parent=$parent_id;
			if(!$right_key_near) $right_key_near=$this->db->selectCell('select max(`rid`) from '.$this->tbl_name)+1;
			$skew_level =$level_up-$level + 1 ;
			$skew_tree =$right_key-$left_key + 1;
			// бронируем место
			$rid=$ins_row['rid'];
			$this->database->query(
				"UPDATE ?_tree SET rid = rid + $child_cnt,  lid = IF(lid > $rid, lid + $child_cnt, lid) WHERE `ignored`=0 and rid >= $rid "
			);
		// вставляемсо
		    $child_cnt=$rid-$row['rid']+1;
			$this->database->query(
				"UPDATE ?_tree SET rid = rid + $child_cnt,  lid = lid + $child_cnt, level=level+$skew_level, `ignored`=0 WHERE `ignored`=1;"
			);
		} else {
			// подставляем под элемент
			$ins_row = $this->nodeGetInfo($parent_id);
			$ins_row['rid']+=1;
			$level_up=$ins_row['level']-1;
			//$right_key_near=$ins_row['rid']+1;
			$left_key_near=$ins_row['lid'];
			$parent=$ins_row['pid'];
			$skew_level =$level_up-$level + 1 ;
			$skew_tree =$right_key-$left_key + 1;
			// бронируем место
			$rid=$ins_row['rid'];
			$this->database->query(
				"UPDATE ?_tree SET rid = rid + $child_cnt,  lid = IF(lid >= $rid, lid + $child_cnt, lid) WHERE `ignored`=0 and rid >= $rid "
			);
		// вставляемсо
		    $child_cnt=$ins_row['rid']-$row['lid'];
			$this->database->query(
				"UPDATE ?_tree SET rid = rid + $child_cnt,  lid = lid + $child_cnt, level=level+$skew_level, `ignored`=0 WHERE `ignored`=1;"
			);
		}

		$this->database->query(
			"update ?_tree SET `pid`=$parent where `id`=".$node_id
		);
		return true ;
//		$this->db->query("COMMIT");
	}

	/**
	 * Перекрасить массив NS под уровень, парент и начальный индекс
	 *
	 * @param array $res
	 * @param int $ptr  - начальный lid
	 * @param int level - уровень.
	 */
	
	function prep_NS_array(&$res,$ptr=0,$level=0){
		$st=array(0);
		//debug($res);
		// переделываем lid-rid, parent, level
		$top=0;$lev=$res[0]['level']-1;$cur=0;
		$id=0;
		foreach($res as $k=>$v){
			//debug($v['level'].'<>'.$lev);
			if($v['level']==$lev){
				$res[$cur]['rid']=++$ptr; 
				$res[$k]['lid']=++$ptr;
				if(!isset($res[$k]['id'])) 
					$res[$k]['id']=$id++; 
				$res[$k]['level']=$lev+$level;
				$res[$k]['pid']=$res[$cur]['pid'];
				$cur=$k;
			} else if($v['level']>$lev){
				$st[]=$cur;
				$res[$k]['lid']=++$ptr;
				$res[$k]['level']=++$lev+$level;
				$res[$k]['pid']=isset($res[$cur]['id'])?$res[$cur]['id']:-1;
				if(!isset($res[$k]['id'])) 
					$res[$k]['id']=$id++; 
				$cur=$k;
			} else {
				while($v['level']<$lev){
					$res[$cur]['rid']=++$ptr;
					$cur=array_pop($st);
					$lev--;
				}
				if($res[$cur]['level']==$lev+$level)
					$res[$cur]['rid']=++$ptr;
				$res[$k]['lid']=++$ptr; 
				if(!isset($res[$k]['id'])) 
					$res[$k]['id']=$id++; 
				$res[$k]['level']=($lev)+$level;
				$res[$k]['pid']=$res[$cur]['pid'];
				$cur=$k;
				//$res[$k]['pid']=$res[$cur]['id'];
			}
		}
		while($lev>=$res[0]['level']-$level){
			$res[$cur]['rid']=++$ptr;
			$cur=array_pop($st);
			$lev--;
		}
	}
	/**
	 * Добавить целый массив последним ребенком к parent_id 
	 *
	 * @param unknown_type $parent_id
	 * @param unknown_type $res
	 */
	function nodeAddArray($parent_id,&$res) {
		if($parent_id<0){
			$par = $this->nodeParent(-$parent_id);
			$row = $this->nodeGetInfo(-$parent_id);
			//debug('!!!'.$parent_id);
			$parent_id=$par['id'];
			$this->prep_NS_array($res,$row['rid'],$par['level']);
			//debug($res);
		// shift all items
			$rid=$row['rid'];$cnt=2*count($res);
			$this->database->query(
				"UPDATE ?_tree ".
				"SET rid = rid + $cnt,  lid = IF(lid > $rid, lid + $cnt, lid) ".
				"WHERE rid > $rid "
			);
			$ids=array(-1=>-$parent_id);
		} else {
			$row = $this->nodeGetInfo($parent_id);
			if($row['rid']==0){
				$row['rid']=$this->database->selectCell(
					'select max(`rid`) from ?_tree'
				);
			}
			$this->prep_NS_array($res,$row['rid'],$row['level']+1);
			
			$ids=array(-1=>$parent_id);
		// shift all items
			$rid=$row['rid'];$cnt=2*count($res);
			$this->database->query(
				"UPDATE ?_tree ".
				"SET rid = rid + $cnt,  lid = IF(lid > $rid, lid + $cnt, lid) ".
				"WHERE rid > $rid "
			);
		}
		$ids[ppi($res[0]['pid'])]=$parent_id;
		
		foreach($res as $k=>$v){
			$sql=
				'insert into '.TAB_PREF.'_tree (lid,rid,pid,level,page,name) VALUES '
				."(".$v['lid'].",".$v['rid'].",".ppi($ids[$v['pid']])
				.",".$v['level'].",".$v['page'].",'".pps($v['name'])."')";
			$result=mysql_query($sql,$this->database->link);$this->req_cnt++;
			//debug($sql);	
			if (!$result) {
		   		debug('Invalid query: '.mysql_error() 
		   			."\nWhole query: ".$sql);
			};
			$ids[$v['id']]=mysql_insert_id ($this->database->link);
			$res[$k]['id']=$ids[$v['id']];
			//debug($ids[$v['id']]);	
		}
	}
	
	function nodeParent($pid){
		return $this->database->selectRow(
			'SELECT y.* FROM '.TAB_PREF.'_tree as x,'.TAB_PREF.'_tree as y '.
			'WHERE y.level=x.level-1 and x.id = ?d '.
				'and y.lid<x.lid and y.rid>x.rid' ,
			$pid
		);
	}
	
	function nodeAdd($parent_id,$key=array()) {
		$row=null;
		if(!isset($key['id'])){
			$id=$this->writeRecord($key);
			$key['id']=$id;
		}
		//$this->database->query('BEGIN');
		if($parent_id<0){
			$row = $this->nodeGetInfo(-$parent_id);

			$this->database->query(
				"UPDATE ?_tree".
				" SET rid = rid + 2,".
					" lid = IF(lid > ".$row['rid'].", lid + 2, lid) WHERE rid > ".$row['rid']
			);

			$keys=array(
				'pid'=>$row['pid'],'lid'=>$row['rid']+1,
				'rid'=>$row['rid']+2,'level'=>$row['level'] + 1,'page'=>$key['id']);
			if(isset($key['name']))
				$keys['name']=$key['name'];
				
			$id=$this->database->query("INSERT INTO ?_tree (?#) VALUES(?a)",
				array_keys($keys),array_values($keys)
			);
		} else {
			if($parent_id){
				$row = $this->nodeGetInfo($parent_id);
			}
			if(!$row) {
				$row = $this->nodeGetInfo();
				$rid=$this->database->selectCell('select max(`rid`) from ?_tree');
				$rid=ppi($rid)+1;
			} else {
				$rid=$row['rid'];
				$this->database->query(
					"UPDATE ?_tree".
					" SET rid = rid + 2,  lid = IF(lid > $rid, lid + 2, lid) WHERE rid >= $rid "
				);
			}

			$keys=array(
				'pid'=>$parent_id,'lid'=>$rid,
				'rid'=>$rid+1,'level'=>$row['level'] + 1,'page'=>$key['id']);
			if(isset($key['name']))
				$keys['name']=$key['name'];
			
			$id=$this->database->query("INSERT INTO ?_tree (?#) VALUES(?a)",
				array_keys($keys),array_values($keys)
			);
		}
		//$this->database->query('COMMIT');
		return $id;
	}

	function nodeGet($node_id, $deep = 0) {
		if($node_id==-1) return null;
	 	if(!($row = $this->nodeGetInfo($node_id))) return null;
	 	//0:id,1:name,2:val,3:node,4:level,5:childs
		$sql = 'SELECT z.id,z.name, if(isNull(z.ival),if(isNull(z.tval),z.sval,z.tval),z.ival) as `value`,'.
			'y.id as node, y.level as level, (y.rid-y.lid) as childs '. 
			'FROM '.TAB_PREF.'_tree AS y left join '.TAB_PREF.'_flesh as z on z.id=y.page
			WHERE y.lid >= '.$row['lid'].' AND y.rid <= '.$row['rid'].
			(!empty($deep)?' and `level`<='.($row['level']+$deep):'').
			' order by y.lid,z.id';
		//debug ($sql);	
		return $this->readRecords('',6000,10000,$sql);
	}
	
	function node(&$item){
		//if(empty($item)) return -1;
		if(is_array($item)) {
			if(isset($item['node'])) return $item['node'];
			if(isset($item['id'])) return sysPar::nodeScanId($item['id']);
			return -1;
		}
		//echo sysPar::nodeScanId($item);
		return sysPar::nodeScanId($item);
	}

	function nodeGetBackPath ($node_id) {
		if($node_id==-1) return null;
		$row = $this->nodeGetInfo($node_id);
		if(empty($row['lid'])||empty($row['rid']))
			return null;
		$sql = 'SELECT 
			z.id,z.name, if(isNull(z.ival),if(isNull(z.tval),z.sval,z.tval),z.ival) as `value`,
			y.id as node, y.level as level
			FROM '.TAB_PREF.'_tree AS y
			left join '.TAB_PREF.'_flesh as z on z.id=y.page
			WHERE y.lid <= '.$row['lid'].'
				AND y.rid >= '.$row['rid'].'
			ORDER BY y.lid ASC,z.id';

		return $this->readRecords('',6000,10000,$sql);
	}
		
	/**
	 *  найти и прочитать запись по списку предъявленых параметров
	 * 
	 */
	function readRecord($param){
		$res=$this->readRecords($param,2,6000);
		if(empty($res))return null;
		if (count($res)>0)
			return current($res);
		else
			return null;	
	}	
	/**
	 *  найти и прочитать запись по списку предъявленых параметров
	 * 
	 */
	function delRecord($param){
		$param=$this->readRecord($param);
		if(!empty($param['id'])){
			$this->database->select('delete from ?_flesh where `id`=?d',$param['id']);
            return true;
        } else
            return false;
	}
	
/**
 * Читать структурированный кэш по имени
 * (name=$name,tval - сериализованый массив, ival- индекс)
 *
 * @param unknown_type $name
 */
	function &readCache($name){
		if (!isset($this->_cache)) $this->_cache=array();
		if (isset($this->_cache[$name]))
			return $this->_cache[$name];
		$res=$this->database->select('select * from ?_flesh where `name`=?',$name);
		$result=array();
		if(!empty($res))
		foreach($res as $v){
			$result[$v['ival']]=@unserialize($v['tval']);
		}
		$this->_cache[$name]=$result;
		return $this->_cache[$name];
	}
	
/**
 * Читать структурированный кэш по имени
 * (name=$name,tval - сериализованый массив, ival- индекс)
 *
 * @param unknown_type $name
 */
	function cache($name,$id=null,$value=null){
		if(empty($id)){
			unset($this->_cache[$name]);
			$this->database->select('delete from ?_flesh where `name`=?',$name);
		} else if(is_null($value)){
			unset($this->_cache[$name][$id]);
			$this->database->select('delete from ?_flesh where `name`=? and `ival`=?d',$name,$id);
		} else {
			$this->_cache[$name][$id]=$value;
			if(is_array($value)) $value=serialize($value);
			$this->database->select('delete from ?_flesh where `name`=? and `ival`=?d',$name,$id);
			$res=$this->database->select('insert into ?_flesh (`name`,`ival`,`tval`) values (?,?d,?);',$name,$id,$value);
		}
	}
	
	function readRecords($param,$cnt=6000,$xcnt=10000,$sql=''){
		if(!is_array($param)) 
			$param=array('root'=>$param);
			// формируем первую строку записи record-name
		if (empty($sql)){
			//0:id,1:name,2:val, !!! 3:node,4:level,5:childs
			$sql='SELECT u.id,u.name, if(isNull(u.ival),if(isNull(u.tval),u.sval,u.tval),u.ival) as `value` from '.TAB_PREF.'_flesh as u ';
			$where =array();
			$ind=1;
			if(empty($param['id'])) {
				foreach($param as $k=>$v){
					$sql.=sprintf('LEFT JOIN '.TAB_PREF.'_flesh AS u%s ON u.id = u%s.id ',
					$ind,$ind);
					$where[]=sprintf('u%1$s.name="%2$s" and '.$this->_cellname($k,$ind).'="%3$s" ',
					   $ind,mysql_real_escape_string($k),mysql_real_escape_string ($v));
					$ind++;
				};
				$sql.='where '.implode(' and ',$where).' ORDER BY u.id';
			} else {
				$sql.='where `id`='.$param['id'].' ORDER BY u.id' ; 
			}	
		}
		//debug($sql);
		$_qresult=@mysql_query($sql.' LIMIT '.$xcnt);
		$this->req_cnt++;
  		
		if (!$_qresult) {
			debug('Invalid query: '.__FILE__.':'.__LINE__.' '. mysql_error() . "\n".'Whole query: ' . $sql.' ORDER BY u.id;');
		} else {
		$_result=array();
		$id=0;
		while (($row = mysql_fetch_array($_qresult,MYSQL_NUM))) {
			//debug($row);
			if($row[0]!=$id){
				if($cnt--<=0)
					break;
				if(!empty($id)) {
					$_result[]=$result;
				}	
				$id=$row[0];
				$result=array('id'=>$id);
				if(isset($row[3])){
					$result['node']=$row[3];
				}
				if(isset($row[4])){
					$result['level']=$row[4];
				}
				if(isset($row[5])){
					$result['childs']=$row[5];
				}
			}		
			if(!empty($row[2]) && $row[2]{0}=='a' && $row[2]{1}==':'){
				$result[$row[1]]=unserialize($row[2]);
			} else {
				$result[$row[1]]=$row[2];
			}
		}
		mysql_free_result($_qresult);
		}
		//debug($result);
		if(!empty($id)) {
			$_result[]=$result;
			return $_result;
		} else	
			return array($param);
	}
	
	function _cellname(&$k,$i){
		if(in_array($k,$this->special_words))
			return sprintf('u%1$s.sval',$i);
		return sprintf('if(isNull(u%1$s.ival),if(isNull(u%1$s.tval),u%1$s.sval,u%1$s.tval),u%1$s.ival)',$i);
	}
	
	function _name(&$val,&$name){
		if(in_array($name,$this->special_words)) return 'sval'; 
		if(is_int($val) || (strlen($val)<10 && ctype_digit($val))) return 'ival';
		return 'tval';
	}
	/**
	 * дстрока для вставки в запрос insert (id,  %ival, sval ,tval%)
	 * @param $val
	 * @param $name
	 * @return unknown_type
	 */
	function _db_insert(&$val,&$name){
		if(in_array($name,$this->special_words)) return 'NULL,"'.mysql_real_escape_string($val).'",NULL'; 
		if(is_int($val) || (strlen($val)<10 && ctype_digit($val))) 
			return $val.',"",NULL';
		return 
			'NULL,"","'.mysql_real_escape_string($val).'"';
	}
	
	/**
	 *  записать полную запись параметр с категорией
	 *	в ведущий параметр входят
	 *  - name:category, name- sval
	 *  все остальные записываются как дополнительные параметры записи
	 */
	function writeRecord($param){
		// вставляем новую запись
		unset($param['current'],$param['node'],$param['level'],$param['childs']);
		if(isset($param['id'])){
			// читаем запись, затем заменяем избранные куски
			$res=$this->database->select('select * from ?_flesh where `id`=?d',$param['id']);
			unset($param['id']);
			if(!empty($res)){
				$id=$res[0]['id'];
				$past=array();
				foreach($res as $v){
					$name=&$v['name'];
					if(isset($param[$name])){
						if(is_array($param[$name]))
							$param[$name]=serialize($param[$name]);
						// параметр есть!
						$tname=$this->_name($param[$v['name']],$name);
						if($v[$tname]!=$param[$name]){
							// update
							$key=array('ival'=>null,'sval'=>null,'tval'=>null);
							$key[$tname]=$param[$name];
							$this->database->select('update ?_flesh 
								set ?a where `id`=?d and `name`=?;',$key,$id,$name);
						}
						unset($param[$name]);
					} else {
						// удаляем отсутствующие
						$this->database->select('delete from ?_flesh 
							where `id`=?d and `name`=?;',$id,$name);
					}
				};
			// вставляем оставшиеся.
				foreach($param as $k=>$v) {
					if(is_array($v))
						$v=serialize($v);
					if(!empty($v)){	
						$this->database->select(
						'insert into ?_flesh (id,name,'.$this->_name($v,$k).') values (
							?d,?,?
						)',$id,$k,$v);
					}
				};
				return $id;
			}
		}
		
		reset($param);
		if(list($key, $val) = each($param)){
			$result=mysql_query('insert into '.TAB_PREF.'_flesh (name,ival,sval,tval) values '.
				'("'.mysql_real_escape_string($key).'",'.$this->_db_insert($val,$key).')'
			);
			$this->req_cnt++;
			if (!$result) {
		   		debug('Invalid query: '.mysql_error() 
		   			."\nWhole query: ".$sql);
			};
			$id=mysql_insert_id($this->database->link);
			$sql='insert into '.TAB_PREF.'_flesh (id,name,ival,sval,tval) values ';
			$sqlp=array();
			while (list($key, $val) = each($param)) {
				if(is_array($val))
						$val=serialize($val);
				$sqlp[]='('.$id.',"'
					.mysql_real_escape_string($key).'",'.$this->_db_insert($val,$key).')';		
				
			}
			if(!empty($sqlp)){
				$result=mysql_query($sql.implode(',',$sqlp),$this->database->link);$this->req_cnt++;
				if (!$result) {
			   		debug('Invalid query: '.mysql_error() 
			   			."\nWhole query: ".$sql);
				};
			}
						
		}
	   	return $id;
	}
/**
 *  функции работы с системными параметрами
 */
	function read_Parameters(){
		$this->parameters=$this->readRecord('SysPar');
	}

	function getPar($name,$def=null){
		if(empty($this->parameters)){
			$this->read_Parameters();
		}
		if(empty($this->parameters))
			return $def;
		//*--*/print_r($this->parameters);
		if(array_key_exists($name,$this->parameters))
			return $this->parameters[$name];
		else
			return $def;
	}

	function setPar($name,$val,$type=-1){
		if(empty($this->parameters)){
			$this->read_Parameters();
		}
		if(pps($this->parameters[$name])!=$val){
			$this->parameters[$name]=$val;
			$this->writeRecord($this->parameters);
			if(!isset($this->parameters['id']))
				$this->read_Parameters();
		}
	}

	function delPar($name){
		unset($this->parameters[$name]);
		$this->writeRecord($this->parameters);
	}

}

//define ('PRECESSOR','sysPar');

class engine_Main extends sysPar {
	var $rights, $defcat='*', $is_ajax=false,$ajaxdata=array();
	/**
	 * Текущий выводимый раздел
	 *
	 * @var string||int
	 */
	var $cur_menu;
	
	function do_find(){
		// do=find&id=xx
		$x=$this->url_by_item($_GET['id']);
		if(isset($x['url']))
		$this->go($x['url']);
	}
	
/*
 ******************************************************* 
 *  Общий функционал common
 ******************************************************* 
 */
	
	function gotofirst($first=''){
		if(is_null($first)) $first=0;
		if(empty($first)){
			$sm=$this->export('sitemap','getSiteMap',$this->cur_menu);
			$first=$sm->el[0]->v['id'];
		}
		
		if(ppi($first)>0 && $this->parent->cur_menu!=$first) {
			$_GET['id']=$first;
			return $this->do_menu();
			//$this->go('?do=menu&id='.$sm->el[0]->v['id']);
			//exit;
		}
		return $this->do_menu();
	}
	
/**
 * Функция - врапер для writeus'а
 *
 * @return unknown
 */
	function do_writeus(){
		if (defined('SECOND_TPL')){
			$this->tpl=SECOND_TPL;
		}
		return $this->export('writeus','do_writeus');
	}
/**
 * Функция - врапер для qa'а
 *
 * @return unknown
 */
	function do_qa(){
		if (defined('SECOND_TPL')){
			$this->tpl=SECOND_TPL;
		}
		return $this->export('qa','do_qa');
	}

	function do_forum(){
		if (defined('SECOND_TPL')){
			$this->tpl=SECOND_TPL;
		}
		return $this->export('forum','do_forum');
	}

	function do_search() {
		$this->sessionstart();
		if(!empty($_POST['search_string']) /*&& (!empty($_POST['search']) || !empty($_POST['search_x'])) */ ){
			$_SESSION['search_state']=false;
			if(!empty($_POST['search_string'])){
				$ss=strtolower(trim($_POST['search_string']));
				$_SESSION['search_string']=$ss;
				$res=array();
				if(class_exists('basket'))
					$res=$this->export('katalog','search',$ss);
				//debug($ss);	
				$this->export('search','saveres',
					array_merge(
						$this->search($ss),
						$this->export('news','search',$ss),
						$res
					));
			}	
		} else {
			if (defined('SECOND_TPL')){
				$this->tpl=SECOND_TPL;
			}
			return $this->export('search','result');
		}
		$this->go($this->parent->curl()); //
	}

	function do_newslist(){
		if (defined('SECOND_TPL')){
			$this->tpl=SECOND_TPL;
		}
		return $this->export('news','do_newslist');
	}
	function do_news(){
		if (defined('SECOND_TPL')){
			$this->tpl=SECOND_TPL;
		}
		return $this->export('news','do_news');
	}
	function do_spec(){
		return $this->export('spec','do_spec');
	}
	function do_novinki(){
		return $this->export('novinki','do_novinki');
	}
	function do_katalog(){
		return $this->export('katalog','do_find',pps($_GET['item']));
	}
	function do_basket(){
		if (defined('SECOND_TPL')){
			$this->tpl=SECOND_TPL;
		}
		return $this->export('basket','do_basket');
	}
	function do_sitemap(){
		if (defined('SECOND_TPL')){
			$this->tpl=SECOND_TPL;
		}
		return $this->export('sitemap','do_sitemap');
	}
/*
 * Враппер для online-регистрации 
 */	
	function do_onlinereg(){
		if (defined('SECOND_TPL')){
			$this->tpl=SECOND_TPL;
		}
		return $this->export('users','do_onlinereg');
	}
	/**
	 * Поиск адреса по элементу страницы
	 */
	function url_by_item($item){
		// ищем самый верхний элемент дерева
		if(is_array($item)){
			$sql='SELECT y.id,y.page '.
				'FROM ?_tree as x, ?_tree AS y '.
				'WHERE ((x.page in (?a)) AND (y.lid<x.lid) AND (y.rid>x.rid) '.
					'and (y.level=0 or y.name="article")) order by y.level desc;';
		} else {
			$sql='SELECT y.id,y.page '.
				'FROM ?_tree as x, ?_tree AS y '.
				'WHERE ((x.page=?d) AND (y.lid<x.lid) AND (y.rid>x.rid) '.
					'and (y.level=0 or y.name="article")) order by y.level desc LIMIT 1;';
		}
		$curlid=$this->database->select($sql,$item);
		if(empty($curlid)) return '';
		// у нас есть адрес do=page&id="?d";
		$cresult=array();
		for($i=0;$i<count($curlid);$i++){
			
			$res=$this->readRecord(array('page'=>$curlid[$i]['id']));
			if(isset($res['id'])){
				/**
				 * вычисляем полный путь меню.
				 */
				$back=$this->nodeGetBackPath($this->node($res));
				//debug($back);
				$name=array();
				while(!empty($back)){
					$v=array_pop($back);
					if($v['name']=='main' || $v['name']=='root menu' || $v['name']=='catalogue') break;
					array_unshift($name,pps($v['descr'],$v['name']));
				}
	
				if($res['id']==$this->getPar('first_menu'))
				$result=array(
					'url'=>'/',
				);
				else
				$result=array(
					//'url'=>'?do=menu&id='.$res['id'],
				);
				if(!empty($name))
					$result['name']=implode('<strong> / </strong>',$name);
				else
					$result['name']=pps($res['descr'],$res['name']);
			} else {
				$res=$this->readRecord(array('id'=>$curlid['page']));
			//debug($res);
				$result=array(
					'url'=>'?do=page&id='.$curlid['page'],
					'name'=>pps($res['item_text'],'Name')
				);
			}
			$result['tag']=sprintf('<a href="%s">%s</a>',$result['url'],$result['name']);
			$cresult[]=$result;
		}
		if (is_array($item)) return $cresult;
		return $result;
	}
	

function flash2($par='',$par2='',$par3=''){
	return "777";
}
	
	/**
	 * Вывод флэшки на страницу из шаблона
	 */
	function flash($n,$wh=''){
//		echo $n, $w,$h;
		if(empty($wh)){
			list($n,$wh)=split(',',$n);
		}
		list($w,$h)=split('x',$wh);
		return smart_template(array(ELEMENTS_TPL,'flash'),array('swf'=>$n,'width'=>$w,'height'=>$h));
	}
	
/*
 *******************************************************
 *  Общий функционал e-shop
 ******************************************************* 
 */
	/**
	 * Основная функция вывода контента
	 *
	 * @return unknown
	 */
	function do_menu($id=0){
		global $flag;
		static $recursion; if(!isset($recursion))$recursion=0;
		$x=$this->getPar('first_menu',1);
		$rsd=pps($id,pps($_GET['id'],$x));
        //debug($rsd);
		$this->parent->cur_menu=$rsd;
		$sm=$this->parent->export('sitemap','getSiteMap',$rsd);
		// запомним в параметрах 0-й элемент меню.
	 	if($sm->el[0]->v['id']!=$rsd)
	 		$this->parent->setPar('first_menu',$sm->el[0]->v['id']);
	 	$element=$sm->scan($rsd);
		//debug($sm);
	 	//if(empty($element)) $this->parent->go();
	 	$keys=array();
	 	
	 	if (defined('SECOND_TPL') && ($this->tpl==MAIN_TPL) &&
			($this->cur_menu!="main") && ($this->cur_menu!=4)){
                //debug($this->cur_menu);
				$this->tpl=SECOND_TPL;
			}
	 	if(!empty($element)){
	 	//	unset($_GET['id']);
		 	$this->parent->element=$element;
			$text=$element->getContent();
		 	//var_dump($element);
		 	if(isset($element->article)){
			$article=$element->article;
			$element->article->serialize($keys);
         //echo(FORUM_TPL);
         //$this->parent->par['left_col_w']=1;
         $this->parent->par['right_col_w']=77;
			$this->parent->par['desc_words']=htmlspecialchars(strip_tags(pps($keys['article_descr_'.$article->v['id']])));
			$this->parent->par['key_words']=htmlspecialchars(strip_tags(pps($keys['article_keywords_'.$article->v['id']])));
			$this->parent->par['dostup']=htmlspecialchars(strip_tags(pps($keys['dostup_'.$article->v['id']])));
			$tit=htmlspecialchars(strip_tags(pps($keys['article_title_'.$article->v['id']])));
			if(!empty($tit)) $this->parent->par['title']=$tit;
		 	}
	 	}
      // Отказ в просмотре для незарегистрированных пользователей.
      if(!$this->user['right']['*'] && isSet($keys['dostup_'.$article->v['id']]) && $keys['dostup_'.$article->v['id']]) {
			return "<div style=\"padding-top:60px;\" class='red'>
			<p><b>Эту страницу могут просматривать только зарегистрированные пользователи!</b></p>
			</div>";
		}
	 	if(!empty($element->v['url'])){
			if(class_exists($element->v['url'])){
				// plugin
				return $this->parent->export($element->v['url'],'do_'.$element->v['url']);
			} 
			// 
	 	}
	 	// поднимаемся вверх по дереву меню и вызываем первый param_url
	 	$cur=&$element;
		while(!empty($cur)) {
			//debug($cur);
			if(!empty($cur->v['url']) 
				&& method_exists($this,'param_'.$cur->v['url'])
			){
				$x='param_'.$cur->v['url'];
				$res= $this->$x();
				break;
			}
			if(!empty($cur->parent))
				$cur=&$cur->parent;
			else
				break;	
		}
	 	if(pps($_GET['ajax'])){
			$this->parent->ajaxdata['nav_bar']=$this->_first();
		}
		//print_r($article);
		if(!empty($article))
			return trim($text);
		else
			return '';
	}
	/**
	 * Основная функция вывода контента
	 *
	 * @return unknown
	 */
	function do_page($id=0){
      if (defined('SECOND_TPL')){
			$this->tpl=SECOND_TPL;
		}
		// ищем менюшный вход
		if(!$id)
			$up_items=$this->parent->nodeGetBackPath($this->parent->node(pps($_GET['id'])));
		else
			$up_items=$this->parent->nodeGetBackPath($this->parent->node(pps($id)));
		$item=array_pop($up_items); // мы!
		if(count($up_items)) // top - верхний артикл
			$top=array_shift($up_items);
		else	
			$top=$item;
		$rsd=$this->database->selectCell('select `id` from ?_flesh where `name`="page" and `ival`=?d',$top['node']);
		//$rsd=$this->database->selectCell('select `id` from ?_tree where `page`=?d',$rsd);
		$this->cur_menu=$rsd;//debug($rsd);
		$this->export('sitemap');
		$sm=&$this->exports['sitemap'];
		$menu=$sm->getSiteMap();
		$el=$menu->scan($this->cur_menu);
		$this->parent->element=&$el;
		/*
		if($el->v['name']!='main')	
			$sm->menu_push(
				array('id'=>$rsd,'menupage'=>'page','name' =>pps($el->v['descr'],$el->v['name']))
			);
		*/
		//debug($el);
		foreach($up_items as $v){
			if(ppi($v['type'])==type_ARTICLE){
				$sm->menu_push(
					array('id'=>$v['id'],'menupage'=>'page','name' => pps($v['item_text'],'доп. страница'))
				);
			}	
		}
		$sm->sub(pps($item['item_text'],'Page title'));		
		$items =$this->nodeGet($this->node($item));
		$i=0;
		$article=readElement($items,$i);
	 	
	 	$keys=array();
	 	if(!empty($article)){
	 		unset($_GET['id']);
			$article->serialize($keys);

			$this->parent->par['desc_words']=htmlspecialchars(strip_tags($keys['article_descr_'.$article->v['id']]));
			$this->parent->par['key_words']=htmlspecialchars(strip_tags($keys['article_keywords_'.$article->v['id']]));
			$tit=htmlspecialchars(strip_tags($keys['article_title_'.$article->v['id']]));
			if(!empty($tit)) $this->parent->par['title']=$tit;
	 	}

		//if (defined('SECOND_TPL')&& ($this->tpl==MAIN_TPL))$this->tpl=SECOND_TPL;
		
	 	//if($this->is_ajax){
	 		//$this->tpl=ELEMENTS_TPL.'#ajax_article';
			//$this->parent->ajaxdata['nav_bar']=$this->_first();
		//}
		if(!empty($article))
			return trim($article->getText($keys));
		else
			return '';
	}
	/**
	 * Вывод страницы подробного описания для каталог
	 * Добавляется ссылка обратно в каталог
	 */	
	function do_catalog() {
		global $engine;
		//print_r($engine);
		$return = $this->do_page();
		if($return == '')
			return '';
		$altn = $this->export('altname','getrealaddr', $this->parent->cur_menu);
		//$altn = '?do=cat&id='.$this->cur_menu.'&cat='.$this->cur_menu;

		if(!empty($this->parent->url['id']))
			$sql = "SELECT * FROM ?_katalog WHERE the_href = ".$this->parent->url['id'];
		else {
			$sql = "SELECT * FROM ?_katalog WHERE the_href = ".$_GET['id'];
		}
		$res = $this->database->selectRow($sql);
		$rln = '?do=cat&id='.$res['xarticle'].'&cat='.$res['xarticle'];
		return smart_template(array(ELEMENTS_TPL,'katalog_back'),Array('name' => $res['name'], 'addr' => $altn, 'raddr' => $rln)).$return.smart_template(array(ELEMENTS_TPL,'katalog_back_bt'),Array('addr' => $altn, 'raddr' => $rln));
	}
	
	/**
	 * Выход пользователя из системы
	 */
	function do_logout(){
		global $cache;
		$this->ffirst('_logout');
		$cache->cleanForUser();
		basket::getStore()->clear();
        $this->go($this->curl('do'));
	}
	/**
	 * Выход пользователя из системы
	 */
	function do_profile(){
		return $this->export('users','do_profile');
	}
	/**
	 * Создание страницы описания для каталога
	 */
	function do_ajax_cat_opisanie(){
		global $engine;
		$sql = 'SELECT the_href, xarticle FROM ?_katalog WHERE id = '.$_POST['id'];
		$res=$this->database->select($sql);
      if($engine->node($res[0]['the_href']) > 0)
      	return $res[0]['the_href'];	
		$nd_info = $engine->nodeGetInfo($engine->nodeAdd($engine->node($res[0]['xarticle']),array('name'=>'subarticle','type'=>type_ARTICLE)));
		$sql = 'UPDATE ?_katalog SET the_href = '.$nd_info['page'] .' WHERE id = '.$_POST['id'];
		$this->database->select($sql);
		return $nd_info['page'];
	}
	
	/*
	 * Поиск по всем 
	 */
	function search($s){
		$s=strtolower($s);
		//return array(page,item)
		$sql='SELECT z.tval as `text`,LOCATE(?, LCASE(z.tval)) as res , z.id as `parent`
FROM ?_flesh as z
WHERE (LOCATE(?, LCASE(z.tval))!=0) order by `parent`
LIMIT 100;';
		$res=$this->database->select($sql,$s,$s);
		if (empty($res)) return array();
		$result=array(); $lastparent=0;
		//debug($res);
		foreach($res as $v){
			if ($v['parent']==$lastparent) continue;
			$lastparent=$v['parent'];
			if(!empty($v['res']))
				$x=strip_tags(preg_replace('/^[^<]*>|<[^>]*$/','',substr($v['text'],max(0,$v['res']-500),500)));
			else
				$x=strip_tags($v['text']);
			$y=$this->url_by_item($v['parent']);
			if($y['name']!='Name' && preg_match('/id=(\w+)/',$y['url'])){	
				$result[]=array_merge(
					$y,
					array('text'=>$x)
				);
			} else {
				debug('found void element');
				debug($v);
			}
		}
		return $result;
	}
	/**
	 * Функция добавления товара в корзинку
	 */
	function do_add(){
		$this->sessionstart();
		$this->export('basket','addItem',pps($_POST['id']),pps($_POST['item'],1));

		if($this->is_ajax){
			$this->parent->ajaxdata['basket']=$this->export('basket','_basket');
			return 'Ok';		
		} else { // Это не ajax
			$this->go();
		}
	}
	

	function has_rights($r,$s=''){
		if($s==='') $s=$this->defcat;
		if(isset($this->rights)){
	//		debug($this->rights);
			return $this->rights->has_right($s,$r);
		}else
			return false;
	}
	function do_Default(){
		return ' ';
	}
	function do_error(){
		return _l("page not found, sorry!");
	}
	function act($do='',$act=''){
		if(is_callable('apache_request_headers'))
			$headers=apache_request_headers ();
		else 
			$headers=array();	
		$this->is_ajax=isset($headers['X-Requested-With']) || isset($_GET['ajax']) || (substr($do,0,4)=='ajax');
		if($this->is_ajax){
			ob_start();
		}
        if ($act)
			$this->par['data']=$this->export($act,'do_'.$do);
		elseif ($x=$this->ffirst('do_'.pps($do,'Default'))){
 			$this->par['data']=$x;
   //         debug($x);
        }
		elseif($x=$this->ffirst('do_error'))
			$this->par['data']=' '.$x;
		else
			exit ;
		if($this->is_ajax){
			$this->tpl=array(ELEMENTS_TPL,'ajax');
			$result=array('data'=>trim($this->template(array(),false)));
			if(!empty($this->ajaxdata))
				$result['result']=$this->ajaxdata;
			if($this->par['error'])
				$result['error']=trim($this->par['error']);
			if($x=ob_get_contents()){
				$result['debug']=trim($x);
			};
			ob_end_clean();
			if(session_id() != ""){
				$result['session']=array('name'=>session_name(),'value'=>session_id());
			}
			$result['stat']=sprintf("%s queries, %f sec spent"
				,$this->req_cnt>>1
				,mkt());
			echo php2js($result);
           // debug('tpl',$this->tpl,MAIN_TPL);
        } elseif (SUPER::option('jinja2')){
            if($this->tpl==MAIN_TPL)
                echo $this->_tpl ('tpl_jmain','_main',array_merge($this->par,array('param'=>$this->parent->parameters)));
            else
                echo $this->_tpl ('tpl_jmain','_second',array('param'=>$this->parent->par));
            //else echo '3-th temlate not supported';
        } else {
           // debug('tplx',$this->tpl,MAIN_TPL);
			$this->template();
        }
		unset($_SESSION['errormsg']);
	}

	function strips(&$el) {
	  if (is_array($el))
	    foreach($el as $k=>$v)
	      $this->strips($el[$k]);
	  else $el = stripslashes($el);
	}

	function curl($par='',$par2='',$par3=''){
		static $url;
        if (!isset($url)){
            //$this->strips($_SERVER['QUERY_STRING']);
            parse_str( htmlspecialchars_decode ($_SERVER['QUERY_STRING']),$url);
            if (!empty($_GET['do'])) $url['do']=$_GET['do'];
            if (!empty($_GET['id'])) $url['id']=$_GET['id'];
        }

        $x=$url;
        if (is_array($par)){

            $x=array_merge($url,$par);
            list($par,$par2,$par3)=array_keys($par);
 //           debug($par." ".$par2." ".$par3);
        }

		if(array_key_exists($par,$x))unset($x[$par]);
		if(array_key_exists($par2,$x))unset($x[$par2]);
		if(array_key_exists($par3,$x))unset($x[$par3]);
		unset($x['ajax'],$x['url'],$x['lang']);
		if($par=='do'){
			unset($x['item'],$x['user'],$x['year'],$x['pg'],$x['url'],$x['ch'],$x['plugin'],$x['cat'],$x['topic']);
		}
       // debug($x);
		if (empty($x)) return'?';
		return '?'.http_build_query($x).'&';
	}
	
   function brd($border = false){
   	if($border) {
   		$vzv = 'style="';
   		$vzv .= 'border-top:solid '.$this->get_param('brd_x').'px '.$this->get_param('brd_color').';';
   		$vzv .= 'border-bottom:solid '.$this->get_param('brd_x').'px '.$this->get_param('brd_color').';';
   		$vzv .= 'border-left:solid '.$this->get_param('brd_y').'px '.$this->get_param('brd_color').';';
   		$vzv .= 'border-right:solid '.$this->get_param('brd_y').'px '.$this->get_param('brd_color').';';
   		$vzv .= '"';
   		return $vzv;
   	}
		return '';
   }
	
	function sessionclose(){
		$this->session_closed=true;
		session_write_close();
	}

	function sessionstart(){
		static $sessionid;
		$this->session_closed=true;
		if (isset($sessionid)) return ;
		$x=session_id();
		if(empty($x)){
			if(class_exists('SessionManager')) {
				SessionManager::instance();
			}
			session_start();
		}
		$sessionid=true;
		$this->session_closed=false;
	}
	function error($s=null){
		if(empty($this->session_closed))
			$this->sessionstart();
			
		if(is_null($s)){
			if (isset($_SESSION['errormsg'])){
				$v=$_SESSION['errormsg'];
				unset($_SESSION['errormsg']);
				return $v;
			}
			return '';
		}
		$this->par['error'].=$s;
        return $this->par['error'];
	}
	function init(){
		//*******************************************************
		//*******************************************************
		if (get_magic_quotes_gpc()) {
		  $this->strips($_GET);
		  $this->strips($_POST);
		  $this->strips($_COOKIE);
		  $this->strips($_REQUEST);
		  if (isset($_SERVER['PHP_AUTH_USER'])) $this->strips($_SERVER['PHP_AUTH_USER']);
		  if (isset($_SERVER['PHP_AUTH_PW']))   $this->strips($_SERVER['PHP_AUTH_PW']);
		}
		
		$pl=trim($this->getPar('plugin_list'));
		if (!empty($pl)){
			$pl=preg_split('/[,\s]+/',pps($pl));
			if (empty($pl)) {
				return ;
			} else {
				foreach($pl as $k=>$v){
					$this->export($v);
				}
			}
		}
		
		//*--*/echo "hello! ".$this->getPar('ksnk-engine version');
	}

	function uploaddir(){
		return toUrl(TMP_DIR);
	}

	function index($up=''){
		if($up=='up')
			$up='/..';
		$x=	ereg_replace("^/+", "", toUrl(ROOT_PATH.$up));
		if (!empty($x)) $x='/'.$x;
		return 
			'http://'.$_SERVER["SERVER_NAME"].$x;
	}
	function get_param($param){
		return $this->parameters[$param];
	}

/**
 *   функция перехода к стартовой странице.
 */
	function go($where='',$direct='') {
		global $cache;
		if(!empty($cache)){
			if ($_SERVER['REQUEST_METHOD']!='GET'){
		// сбрасываем весь кеш своей группы
				$cache->cleanForGroup();
			} 
			$cache->setOptions(array('is_enabled'=>false));
		}
		
		if(!empty($this->is_ajax)) return;
		if($this->par['error'])
			$_SESSION['errormsg']=$this->par['error'];
		else
			unset($_SESSION['errormsg']);
		$par=preg_replace(array('/&amp;|&&+/','/^\?|&$/'),array('&',''),$where);
		if (session_id()!='' && !isset($_COOKIE[session_name()]))
			$par.=($par?'&':'').session_name().'='.session_id();
		if(!empty($direct)){	
			$par='http://'.$_SERVER["SERVER_NAME"].$direct;
		}else
			$par='http://'.$_SERVER["SERVER_NAME"]
				.toUrl(INDEX_PATH) //preg_replace('~\?.*~','',$_SERVER['REQUEST_URI'])
				.pp($par,'?');
		if(empty($_GET['debug'])){
			if (!headers_sent()){
				header("location:".$par	); exit;
			} else {
				echo "<script type=\"text/javascript\">window.location.href=\"$par\";</script>";
			}
		}
		echo "Please, press <a href='$par'>$par</a> to redirect.";
		exit ;
	}
	
	function menu_ul($name,$tpl='',$all=true,$c="catalogue"){
		if(empty($tpl))$tpl='ulmenu';
		if($xx=$this->ffirst('getSiteMap',$name)){
			$xx->v['current']=true;
			return smart_template(array(ELEMENTS_TPL,$tpl),
				array('data'=>$xx->getUlLi(5,$all,1,$c)));
		} else {
			debug('not found "'.$name.'"');
			return ' ';
		}
	}
	
	function menu_x($name,$cnt,$type=0){
		$head=array();
		if(empty($cnt))$cnt=4000;
	 	$sm=$this->export('sitemap','getSiteMap',$name);
	 	//debug($name);
	 	$xx=1;
		if(!empty($sm->el))
		foreach($sm->el as $v){ // без первого элемента!!!
			if(ppi($v->v['type'])<=$type){
				$text=pps($v->v['descr'],$v->v['name']);
		 		$x=array(
		 		    'pict'=>str_replace(array('-',' '),'_',translit($text))
		 			,'current'=>pps($v->v['current'])
		 			,'item'=>$text
		 			,'url'=>$v->getUrl()
		 			,'odd'=>(($xx & 1) !=0)
		 			,'id'=>ppi($v->v['page'],-1)
		 		);
		 		if(((($xx)%$cnt)==0) && ($xx<count($sm->el))) $x['break']=true;
		 		if((($xx++)%$cnt)==1) $x['first']=true;
		 		$x['_has_submenu']=true;
		 		// вот тут - только для текущего раздела!!!
				if(!empty($v->el) && $v->v['current']){
					$x['submenu']=array();
					foreach($v->el as $vv){ // без первого элемента!!!
						if(ppi($vv->v['type'])<=$type){
					 		$xxx=array(
					 			'current'=>pps($vv->v['current'])
					 			,'item'=>pps($vv->v['descr'],$vv->v['name'])
					 			,'url'=>$vv->getUrl()
					 		);
					 		$x['submenu'][]=$xxx;
				 		}
				 	}
				 	$x['_has_submenu']=false;
				}
		 		$head[]=$x;
	 		}
	 	}
	 	if(empty($head)) return '';
	 	$head[count($head)-1]['last']=true;
	 	 
	 	return $head;

	}	

	function menu($where,$m=''){
	//echo "!!!!!!!!!";
		static $x ;
		if($where=='has')
			return !isset($this->menu[$m]);
		if($where=='rmenu')
			$x[$where]=$this->export('MAIN','_rmenu');
		if($where=='tmenu')
			$x[$where]=$this->export('MAIN','_tmenu');
		if (!isset($x))$x=array();
		if (isset($x[$where])) return $x[$where]; 
		$x[$where]='';
		if(isset($this->menu[$where])){
			$y=$this->menu[$where];
			if(isset($y[2]))
				return
					$x[$where]=$this->export($y[0],$y[1],$y[2],pps($y[3]),pps($y[4]),pps($y[5]),pps($y[5]));
			else if (isset($y[0])) {
				//print_r($x[$where]=$this->export($y[0],$y[1]));
				return
					$x[$where]=$this->export($y[0],$y[1]);
			}
			else 
				return '';		
		}
		return '';
	}

	function handle($x){
	//	debug('handle '.$x);
	}

	function tpl($where,$tpl=''){
		static $x; if (isset($x[$where.'#'.$tpl])) return $x[$where.'#'.$tpl];
		//debug('tpl');debug($where);debug($tpl);
		if($where=='admin')
			$tpl=array(ADMIN_TPL,$tpl);
		elseif(class_exists($where) || file_exists(TEMPLATE_PATH.DIRECTORY_SEPARATOR.$where.'.php'))
			return $this->_tpl($where,$tpl);
		else
			$tpl=array(MAIN_TPL,$tpl);
		return $x[$where.'#'.$tpl]=smart_template($tpl,array());
	}

/**
 * генерировать строку страниц
 */
	function calc_Pages($all_cnt,$perpage,$page,$by=4){

		$pages=array();
		$res=array();
		$maxpg=ceil(($all_cnt-0.5)/$perpage);
		for($i=max(0,$page-$by),$y=min($page+1+$by,$maxpg);$i<$y;$i++){
			if($i==$page)
				$x=array('txt'=>array('txt'=>($i+1),'current'=>true));
			else
				$x=array('link'=>array('txt'=>($i+1),'url'=>$i));
			$pages[]=$x;
		}
		if(count($pages)<2) return '';
		else {
			if($by+$by<$page){
				$res['min']=array('m5'=>$page-$by-$by-1);
			}
			if($by<$page){
				$res['mmin']=array('m5'=>$page-$by-1);
			}
			if($by+$by+1+$page<$maxpg){
				$res['max']=array('m5'=>$page+$by+$by+1);
			}
			if($by+1+$page<$maxpg){
				$res['mmax']=array('m5'=>$maxpg-1);
			}
		}
		$res['page']=&$pages;
		return smart_template(array(ELEMENTS_TPL,'pages'),$res);
	}
	
	function SimpleFormPrint($x){
		$res=array();
		//debug($x);
		$even=0;
		foreach($x as $k=>$v){
			if(is_int($k) && is_string($v)) // типо индекс
			{
				$k='';$v=array($v,'text');
			} 
			if(is_int($k)) $k='';
			$v[1]=pps($v[1],'input');
			$xx='';
			switch($v[1]){
                case 'text':
                    $xx=array('text'=>array('text'=>$v[0]),'even'=>($even++ & 1)==0);
                    break;
                case 'password':
                    $xx=array('text'=>array('text'=>$v[0]),'even'=>($even++ & 1)==0);
                    break;
				case 'scrolltext':
					$xx=array('scrolltext'=>array('title'=>$k,'text'=>$v[0]));
					break;
				case 'input':case 'textarea':	
					if(!empty($_POST[$v[0]]))
						$xx=array($v[1]=>array('tit'=>$k,'value'=>$_POST[$v[0]]),'even'=>($even++ & 1)==0);
					break;
				case 'checkbox':
					$xx=array(); $y=explode('|',$v[2]);$z=array();
					foreach($_POST[$v[0]] as $vv){
						$z[]=$y[$vv-1];
					}
					foreach($z as $kk=>$vv)
						$xx[]=array('name'=>$v[0],'value'=>$kk,'text'=>$vv);
					if(empty($xx))$xx='';	
					$xx=array('checkbox'=>array('tit'=>$k,'check'=>$xx),'even'=>($even++ & 1)==0);
					break;
				case 'radio':
					$xx=array(); $y=explode('|',$v[2]);$z=array();
					foreach($y as $kk=>$vv)
						if(ppi($_POST[$v[0]])==($kk+1))
							$xx[]=array('name'=>$v[0],'value'=>$kk,'text'=>$vv);
					if(empty($xx))$xx='';	
					$xx=array('checkbox'=>array('tit'=>$k,'check'=>$xx),'even'=>($even++ & 1)==0);
					break;
			}
			if(empty($k)){
				$xx[key($xx)]['nocol']=true;
			}
			if(!isset($v['noprint'])&& !empty($xx))
				$res[]=$xx;
		}
		return $res;
	}
	
	function SimpleForm($fields,$options=null){
		global $cache, $pole;
		if(!empty($cache)){
			$cache->cleanForGroup();
			$cache->setOptions(array('is_enabled'=>false));
		}
		$this->sessionstart();
		if (defined('SECOND_TPL')) $this->tpl=SECOND_TPL;
		$res=array();
		$even=0;
        if(!is_array($options)) $options=array();

        $ruller=pps($options['ruller']);

		$values=array();
        $default=array();
		$hidden=array();
		if(is_array($fields)){
			foreach($fields as $k=>$v){
				if(is_numeric($k) && is_string($v)) // типо индекс
				{
                    $k='';$v=array($v,'text');
				} 
				if(is_numeric($k)) $k='';
				if(isset($v['value'])){
					$values[$v[0]]=$v['value'];
				}
                if(isset($v['default'])){
					$default[$v[0]]=$v['default'];
				}
                if(!isset($v[2]))
                    $param='';
                else {
                    $param=$v[2] ;
                    unset($v[2]);
                }
                if(!isset($v[1]))
                    $type='input';
                else {
                    $type=$v[1] ;
                    unset($v[1]);
                }
                $text=$v[0];
                unset($v[0]);
                $x=$v;
				switch($type){
                    case 'text':
                        $x['type']='text';$x['text']=$text;
                        break;
                    case 'password':
                        $x['type']='password';$x['tit']=$k;$x['name']=$text;
						break;
					case 'scrolltext':
						$x['type']='scrolltext';$x['text']=$text;
                        if($k)$x['title']=$k;
						break;
					case 'input':case 'textarea':	
						$x['type']=$type;$x['tit']=$k;$x['name']=$text;
						break;
					case 'checkbox':
						$ch=array();
						foreach(explode('|',$param) as $kk=>$vv)
							$ch[]=array('name'=>$text,'value'=>$kk+1,'text'=>$vv);
						$x['type']='checkbox';$x['check']=$ch;
                        if($k)$x['tit']=$k;
						break;
					case 'radio':
						$ch=array();
						foreach(explode('|',$param) as $kk=>$vv)
							$ch[]=array('name'=>$text,'value'=>$kk+1,'text'=>$vv);
                        $x['type']='radio';$x['check']=$ch;
                        if($k)$x['tit']=$k;
 						break;
					case 'hidden':
						$hidden[]=array('text'=>$text);
						continue 2;
					default:
						continue 2;	
				}
				$x['even']=($even++ & 1)==0;
				if(empty($k)){
					$x['nocol']=true;
				}
				if(!isset($v['require'])){
					$x['nostar']=true;
				}
				if(isset($v['comment'])){
					$x['comment']='<br><span class="comment">'.$v['comment'].'</span>';
				}
				$res[]=$x;
			}
			if(!empty($res))
				$res=array('list'=>$res);
			if(!isset($_SESSION['human_user']))	{
				$res['list'][]=array('type'=>'captcha','even'=>($even++ & 1)==0);
			}	
			$res['list'][]=array('type'=>'submit','even'=>($even++ & 1)==0);
		} else {
			$res['list']=$fields;
		}
		$res['error']=pps($_SESSION['errormsg']);
		if(!empty($hidden))
			$res['hidden']=$hidden;
		//debug($res);
		$form=new form('callback');
        $form->scanHtml($this->_tpl('tpl_jelements','_callback',$res));
		if($form->handle()){

		// проверка обязательных полей		
			$error=false;
			$encoding=false;
            //debug($fields);
			foreach($fields as $k=>$v){
				if($encoding || detectUTF8($form->var[$v[0]])){
					$encoding=true;	
					$form->var[$v[0]]=iconv('UTF-8','cp1251//IGNORE',$form->var[$v[0]]);
				}
                if(!empty($ruller) && !empty($v['rule']) && !preg_match('/\br'.$form->var[$ruller].'\b/',$v['rule']))
                    continue;
                if(!empty($v['validate'])){
                    switch ($v['validate']){
                        
                    case 'email':
                        if(isset($v['require']) && empty($form->var[$v[0]])){
                            $error=true;
						    $this->error('поле "'.$k.'" не заполнено<br>');
                        } elseif(!filter_var($form->var[$v[0]], FILTER_VALIDATE_EMAIL)){
                            $error=true;
                            $this->error('поле "'.$k.'" не является email адресом<br>');
                        };
                        break;

                    default:
                        $func=create_function('$var',$v['validate']);
                        if($func){
                            $res=$func($form->var);
                            if(!empty($res)){
                                $error=true;
                                $this->error($res);
                            };
                        }
                    }
                } else	if(!is_int($k) && isset($v['require'])) // типо индекс
				{
					if(empty($form->var[$v[0]])){
						$error=true;
						$this->error('поле "'.$k.'" не заполнено<br>');
					}
				}
			}
			if (isset($_SESSION["captcha"])){
				if(!$_SESSION['human_user'] && ($_SESSION["captcha"]!==$form->var["captcha"])) {
					$error=true;
					$this->error('Неверно введен номер');
			   		$form->storevalues();
				} else {
					unset($_SESSION['captcha']);
					$_SESSION['human_user']=true;
				}
			} 
			if(isset($_SESSION['USER_ID'])){
				$changed=false;
				$key=array();
				foreach($form->var as $k=>$v){
					if (preg_match('/^cust_/',$k) && (pps($this->user[$k])!=$v)){
						$key[$k]=$form->var[$k];
						$changed=true;
					}
				}
				if($changed)
					$this->writeRecord($this->user);
				// сохранить юзера
			} 
			if($error)	
				$this->go($this->curl());

			return $form ;		
		}
		if(isset($_SESSION['USER_ID'])){
			foreach($form->var as $k=>$v){
                if(isset($default[$k])){
                    if(empty($form->var[$k])) $form->var[$k]=$default[$k];
                } elseif (preg_match('/^cust_/',$k) && isset($this->user[$k])){
					$form->var[$k]=$this->user[$k];
				}
			}
		}
				
		foreach($values as $k=>$v){
			$form->var[$k]=$v;
		}
        foreach($default as $k=>$v){
            if(empty($form->var[$k])) $form->var[$k]=$v;
        }
		$form->var["captcha"]="";
		return $form->getHtml(' ');
	}
	
}
/**
 * ob-конвертация адресов в валидный вид
 * потенциальный рассадник ЧПУ
 *
 */

function convert_href($s){
	static $xfunc;
	global $engine,$cache;
	$root = $engine->getRoot();
	if(!isset($xfunc)) {
	if(!defined('IS_ADMIN') && (class_exists('altname'))) {
		$xfunc=create_function(
           '$matches',
           //'return \'href="\'.preg_replace(\'~(?:&amp;|&)~\',\'&amp;\',$matches[1]).\'"\';'
           '$x=trim(altname::getrealaddr0($matches[2]),\'/\');'.//$GLOBALS[\'altname\']->getrealaddr0($matches[2]);'.
           'if($x==$matches[2]){
			   $x=preg_replace(
           		array(
				\'#\\?do=(\w+)$#\',\'#\\?do=menu(?:&amp;|&)id=(\w+)$#\',\'#\\?do=(\w+)(?:&amp;|&)id=(\w+)$#\')
           		,array(\'\\1\',\'\\1\',\'\\1/\\2\'),$x);
    if(empty($matches[1]))
      return \'href="\'.preg_replace(\'~(?:&amp;|&)~\',\'&amp;\',$x) .\'"\';
    else 
      return \'href="\'.$matches[1].\'/\'.preg_replace(\'~(?:&amp;|&)~\',\'&amp;\',$x) .\'"\';
	       }'.
           'return \'href="'.$root.'\'.preg_replace(\'~(?:&amp;|&)~\',\'&amp;\',$x)
           .\'"\';'
           
    	);
	} else {
		$xfunc=create_function(
           '$matches',
           'return \'href="\'.$matches[1].preg_replace(\'~(?:&amp;|&)~\',\'&amp;\',$matches[2]).\'"\';'
    	);
	}}
	//$prop= &new prop();
	$ar=prop::prep(_l('query'));
	
	$x=preg_replace_callback('~href="(.*?)/?(\?.*?)"~',$xfunc,	$s	);
	if(is_callable(array($engine,'get_head')))
		$x=preg_replace_callback('~<!--head-->~',array($engine,'get_head'),$x	);
	if (!$engine->is_ajax) 
		$x=$x._l("\n<!-- (%s) page was built for %f sec -->"
				,array(prop::sema(-($engine->req_cnt>>1),$ar[0])
				,mkt()));
	// решение о сбросе кэша
	if(!empty($cache)){
		if( $_SERVER['REQUEST_METHOD']!='GET'){
			// сбрасываем весь кеш своей группы
			$cache->cleanForGroup();  
		} else {			
			$cache->write($x);
		}
	}
	return $x;			
}

/**
 * Поехали работать
 */
function DO_IT_ALL(){
	global $engine;
/**
 * ЧПУ для сайта
 */
//авторасшифровка
if (isset($_GET['phpinfo'])){
	phpinfo(); exit;
}
$url=pps($_GET['url']);
if (empty($url))
	$url=preg_replace('#^(/|.*/index.php/)#','',$_SERVER['REQUEST_URI']);
//printf('"%s" "%s"',$_SERVER['REQUEST_URI'],$url);

if(!empty($url)){
	DATABASE();
	//debug('url');
	if($res=$engine->export('altname','getaddr',$url)){
		$_GET['do']=$res['do'];
		$_GET['id']=$res['id'];
	}
	else if($res=$engine->export('altname','getaddr',preg_replace('#/(\d+)$#', '', $url))){
		$_GET['do']='catalog';
		$_GET['id']=preg_replace('#^(.+)/(\d+)$#', '\2', $url);
	}
	else if (preg_match('#^\d+/\d+$#i',$url)) {
		$_GET['do']='catalog';
		$_GET['id']=preg_replace('#^(\d+)/#', '', $url);
	}
	else if (preg_match('#^\d+$#i',$url)) {
		$_GET['do']='menu';
		$_GET['id']=$url;
	}
	else if(preg_match('#^[a-z]+$#i',$url)) {
		$_GET['do']=$url;
	}
	else if (preg_match('#^(\w+)/(\w+)$#',$url,$m)) {
		$_GET['do']=$m[1];
		$_GET['id']=$m[2];
	}
	//debug($_GET);
	//print_r($engine);
	//print_r($_GET);
	$engine->setUrl(array('do'=>pps($_GET['do']),'id'=>pps($_GET['id'])));
}

/**
 * 
 */
			DATABASE();
if(!defined('INTERNAL')&& empty($_GET['noconvert'])){
	ob_start('convert_href');
}

error_reporting(E_ALL ||E_STRICT);
if(isset($_GET['debug'])) {
    if($_GET['debug'] && !isset($_COOKIE['debug'])){
        setcookie('debug',$_GET['debug'],time()+10*60);
    } else {
        setcookie ("debug", "", time() - 3600);
    }
}
$engine->debug=pps($_GET['debug'])|| pps($_COOKIE['debug']);

if($engine->debug){
	function backtrace(){
		$x=debug_backtrace();
		$z=array();
		$y=array_shift($x);
//		$y=array_shift($x);
		if($x[1]['function']=='myLogger'){
			while (isset($x[0]) && $y['function']!='_query')
				$y=array_shift($x);
		}
		
		//for($i=0;$i<3;$i++){
			$y=array_shift($x);
			$z=sprintf('file:%s,line:%s,func:%s',$y['file'],$y['line'],$y['function']);
		//}
		return $z;
	}		
	$d='Debug/HackerConsole/Main.php';
	if (file_exists ($d)) require_once $d;

	if (class_exists('Debug_HackerConsole_Main') ) {
		new Debug_HackerConsole_Main(true);

		function debug()
		{
            $na=func_num_args();
            for ($i=0; $i<$na;$i++){
                $msg=func_get_arg($i);
                call_user_func(array('Debug_HackerConsole_Main', 'out'),is_string($msg)?$msg:print_r($msg,true));
            }
			call_user_func(array('Debug_HackerConsole_Main', 'out'),'>>>>>'.backtrace());
		}
	} else {
		function debug($msg){
			var_dump($msg);
		};
	}
	$engine->database->do_log=true;
	if(!empty($_POST))
		debug($_POST);
} else {
	function debug($msg){;}
	function backtrace(){;}
}
if($engine->getPar('UPLOAD_DIR')!=TMP_DIR)
	$engine->setPar('UPLOAD_DIR',TMP_DIR);

$engine->execute('init',pps($_GET['do']));
// если можно стартовать сессию - стартуем
if(isset($_POST['login_name']) && isset($_POST['login_pass']) ){
	$engine->sessionstart();
	if($engine->export('Auth','auth_check'
		,$_POST['login_name']
		,$_POST['login_pass']
		,ppx($_POST['login_save'][0])
	)){
        $engine->ffirst('onJustLogin');
		$engine->go($engine->curl());
	} else {
        $engine->error(_l('wrong password'));
    }
}
if(isset($_REQUEST[session_name()])){
	if (   isset($_GET[session_name()])
		&& isset($_COOKIE[session_name()])
		&& ($_GET[session_name()]==$_COOKIE[session_name()])
	){	
		$engine->go($engine->curl(session_name()));
	}
	//session_start();
	$engine->sessionstart();
	$engine->export('Auth','auth_check');
}
$engine->par['root']=rtrim(toUrl(ROOT_PATH),'/').'/';
if($engine->has_rights(right_READ)){
	$engine->par['userhello']= _l('Hello "%s"',$engine->user['name']);
	$engine->par['userhello2']= 'Вы авторизованы как &laquo;'.$engine->user['name'].'&raquo;';
}

// продолжение инициализации
//debug($engine->rights);
//if($engine->has_rights(right_READ)){
	$engine->menu['basket']=array('basket','_basket');
//}
$engine->par['user']=&$engine->user;
$engine->execute('init2',pps($_GET['do']));
if(!defined('INTERNAL')){
    // отсюда не возвращаются праведные запросы
	$engine->act(pps($_GET['do']),pps($_GET['plugin']));
	// а сюда отправляем остальной мусор
	
	//$engine->go();
}
	
}
?>