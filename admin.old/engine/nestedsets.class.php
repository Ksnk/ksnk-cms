<?php
/**
 * @author Alexey Tokar aka (azazel.tap@gmail.com), © 2008
 * @version 1.0
 * @requirements PHP5 MySQL5
 *
CREATE TABLE  `ns` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `lid` int(10) unsigned NOT NULL default '0',
  `rid` int(10) unsigned NOT NULL default '0',
  `level` int(10) unsigned NOT NULL default '0',
  `ignored` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB;
 */
class DBNestedSet {
	var $tbl_name = "ns", $db;

	function DBNestedSet(&$db,$tab='ns') {
		$this->db=$db ; $this->tbl_name=$tab;
	}

	//private function
	function GetNodeInfo($node_id=0) {
		if(is_int($node_id) || ctype_digit($node_id)){
			if ($node_id==0){
				return array('pid'=>0,'lid'=>0,'rid'=>0,'level'=>-1);
			}
			return $this->db->selectRow(
				"SELECT * FROM ".$this->tbl_name." WHERE id = ?d",
				$node_id
			);
		} else {
			return $this->db->selectRow(
				"SELECT * FROM ".$this->tbl_name." WHERE level=0 and name=?;",
				$node_id
			);
		}
	}

	function DeleteNode($node_id,$clear=true) {
//		$this->db->query("START TRANSACTION");

		$row = $this->GetNodeInfo($node_id);
		if(!isset($row['id'])) return ;
		$left_key=$row['lid'];
		$right_key=$row['rid'];
		//изолировать перемещаемую ветку

		$child_cnt=2*$this->db->query(
			"DELETE FROM ".$this->tbl_name.
			" WHERE lid >=$left_key AND rid <= $right_key"
		);

		$this->db->query(
			"UPDATE ".$this->tbl_name.
			" SET lid = IF(lid > $left_key, lid -".$child_cnt.
			", lid), rid = rid-$child_cnt WHERE rid > $right_key"
		);

//		$this->db->query("COMMIT");
	}
	function EmptyNode($node_id,$clear=true) {
//		$this->db->query("START TRANSACTION");

		$row = $this->GetNodeInfo($node_id);
		if(!isset($row['id'])) return ;
		$left_key=$row['lid'];
		$right_key=$row['rid'];
		if($left_key+1==$right_key) return ;
		//изолировать перемещаемую ветку

		$child_cnt=2*$this->db->query(
			"DELETE FROM ".$this->tbl_name.
			" WHERE lid >$left_key AND rid < $right_key"
		);

		$this->db->query(
			"UPDATE ".$this->tbl_name.
			" SET lid = IF(lid > $left_key, lid -".$child_cnt.
			", lid), rid = rid-$child_cnt WHERE rid >= $right_key"
		);

//		$this->db->query("COMMIT");
	}
	
	function AddNode($parent_id,$key=array()) {
		global $engine;
//		$this->db->query("START TRANSACTION");

		$row=null;
		if(!isset($key['id'])){
			$id=$engine->writeRecord($key);
			$key['id']=$id;
		}
		if($parent_id<0){
			$row = $this->GetNodeInfo(-$parent_id);

			$this->db->query(
				"UPDATE ".$this->tbl_name.
				" SET rid = rid + 2,".
					" lid = IF(lid > ".$row['rid'].", lid + 2, lid) WHERE rid > ".$row['rid']
			);

			$keys=array(
				'pid'=>$row['pid'],'lid'=>$row['rid']+1,
				'rid'=>$row['rid']+2,'level'=>$row['level'],'page'=>ppi($key['id'],$key['_id']));
			if(isset($key['name']))
				$keys['name']=$key['name'];
			if(isset($key['_xid']))
				$keys['id']=$key['_xid'];
				
			$xid=$this->db->query("INSERT INTO ".$this->tbl_name." (?#) VALUES(?a)",
				array_keys($keys),array_values($keys)
			);
		} else {
			if($parent_id){
				$row = $this->GetNodeInfo($parent_id);
			}
			if(!$row) {
				$row = $this->GetNodeInfo();
				$rid=ppi($this->db->selectCell('select max(`rid`) from '.$this->tbl_name))+1;
			} else {
				$rid=$row['rid'];
				$this->db->query(
					"UPDATE ".$this->tbl_name.
					" SET rid = rid + 2,  lid = IF(lid > $rid, lid + 2, lid) WHERE rid >= $rid "
				);
			}

			$keys=array(
				'pid'=>$parent_id,'lid'=>$rid,
				'rid'=>$rid+1,'level'=>$row['level'] + 1,'page'=>ppi($key['id'],$key['_id']));
			if(isset($key['name']))
				$keys['name']=$key['name'];
			if(isset($key['_xid']))
				$keys['id']=$key['_xid'];
				
			$xid=$this->db->query("INSERT INTO ".$this->tbl_name." (?#) VALUES(?a)",
				array_keys($keys),array_values($keys)
			);
		}
//		$this->db->query("COMMIT");

		return $xid;
	}

	function MoveUp($node_id) // поставить элемент первее в списке чилдов
	{
		$row = $this->GetNodeInfo($node_id);
		$with = $this->db->selectRow(
				"SELECT * FROM ".$this->tbl_name." WHERE rid = ?d",
				$row['lid']-1
			);
		if($with)
			return $this->MoveNode($with,$row);
		else
			return false;
	}

	function MoveDn($node_id) // поставить элемент первее в списке чилдов
	{
		$row = $this->GetNodeInfo($node_id);
		$with = $this->db->selectRow(
				"SELECT * FROM ".$this->tbl_name." WHERE lid = ?d",
				$row['rid']+1
			);
		if($with)
			return $this->MoveNode($row,$with);
		else
			return false;
	}

	function SwitchNodes($n1,$n2) // поменять местами ноды
	{
		$n1=$this->GetNodeInfo($n1);
		$n2=$this->GetNodeInfo($n2);
		//проверко!
		if($n1['pid']!=$n2['pid']) return;
		if($n1['lid']>$n2['lid']) {
			$n=&$n1;
			$n1=&$n2;
			$n2=&$n;
		}
		//debug($n1);debug($n2);
		$n1_p=$n1['rid']-$n1['lid']+1;
		$n2_p=$n2['rid']-$n2['lid']+1;
		$all=$n2['rid']-$n1['lid']+1;
		$diff=$n2_p-$n1_p;

		$this->db->query(
			'update '.$this->tbl_name.
			' set `rid`=`rid`+ IF(`lid`<'.$n1['rid'].','.($all-$n2_p).','.
						'IF(`lid`<'.$n2['lid'].','.$diff.',-'.($all-$n1_p).')),'.
				' `lid`=`lid`+ IF(`lid`<'.$n1['rid'].','.($all-$n2_p).','.
						'IF(`lid`<'.$n2['lid'].','.$diff.',-'.($all-$n1_p).'))'.
			' WHERE `lid`>='.$n1['lid'].' and `rid`<='.$n2['rid']
		);
	}

	function MoveNode($node_id, $parent_id) {
//		$this->db->query("START TRANSACTION");

		if(!is_array($node_id))
			$row = $this->GetNodeInfo($node_id);
		else
			$row=$node_id;

		if (!empty($parent_id)){
			// пытаемся детектировать ситуацию, когда меняем
			// местами 2 рядомстоящие позиции
			if(!is_array($parent_id))
				$ins_row = $this->GetNodeInfo($parent_id);
			else
				$ins_row=$parent_id;
			if($row['rid']+1==$ins_row['lid']) {
				$dx2=$ins_row['lid']-$row['lid'];
				$dx3=$ins_row['rid']-$row['rid'];
				$this->db->query(
					'update '.$this->tbl_name.
					' set `rid`=`rid`+ IF(`lid` >='.$ins_row['lid'].',-'.$dx2.','.$dx3.'),'.
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
		$child_cnt=2*$this->db->query(
			"update ".$this->tbl_name.
			" set `ignored`=1 where lid >=$left_key AND rid <= $right_key"
		);

		$this->db->query(
			"UPDATE ".$this->tbl_name.
			" SET lid = IF(lid > $left_key, lid -".$child_cnt.
			", lid), rid = rid-$child_cnt WHERE rid > $right_key"
		);
		if($parent_id<=0){
			// вставляем как первый элемент родителя
			$parent_id=-$parent_id;
			$ins_row = $this->GetNodeInfo($parent_id);
			$level_up=$ins_row['level'];
			$right_key_near=$ins_row['rid'];
			$left_key_near=$ins_row['lid'];
			$parent=$parent_id;
			if(!$right_key_near) $right_key_near=$this->db->selectCell('select max(`rid`) from '.$this->tbl_name)+1;
			$skew_level =$level_up-$level + 1 ;
			$skew_tree =$right_key-$left_key + 1;
			// бронируем место
			$rid=$ins_row['rid'];
			$this->db->query(
				"UPDATE ".$this->tbl_name.
				" SET rid = rid + $child_cnt,  lid = IF(lid > $rid, lid + $child_cnt, lid) WHERE `ignored`=0 and rid >= $rid "
			);
		// вставляемсо
		    $child_cnt=$rid-$row['rid']+1;
			$this->db->query(
				"UPDATE ".$this->tbl_name.
				" SET rid = rid + $child_cnt,  lid = lid + $child_cnt, level=level+$skew_level, `ignored`=0 WHERE `ignored`=1;"
			);
		} else {
			// подставляем под элемент
			$ins_row = $this->GetNodeInfo($parent_id);
			$ins_row['rid']+=1;
			$level_up=$ins_row['level']-1;
			//$right_key_near=$ins_row['rid']+1;
			$left_key_near=$ins_row['lid'];
			$parent=$ins_row['pid'];
			$skew_level =$level_up-$level + 1 ;
			$skew_tree =$right_key-$left_key + 1;
			// бронируем место
			$rid=$ins_row['rid'];
			$this->db->query(
				"UPDATE ".$this->tbl_name.
				" SET rid = rid + $child_cnt,  lid = IF(lid >= $rid, lid + $child_cnt, lid) WHERE `ignored`=0 and rid >= $rid "
			);
		// вставляемсо
		    $child_cnt=$ins_row['rid']-$row['lid'];
			$this->db->query(
				"UPDATE ".$this->tbl_name.
				" SET rid = rid + $child_cnt,  lid = lid + $child_cnt, level=level+$skew_level, `ignored`=0 WHERE `ignored`=1;"
			);
		}

		$this->db->query(
			"update ".$this->tbl_name.
				" SET `pid`=$parent where `id`=".$node_id
		);
		return true ;
//		$this->db->query("COMMIT");
	}

	function GetBranch($node_id, $deep = 0) {
//*
	 	if(is_int($node_id) || ctype_digit($node_id)){
			$level=!empty($deep)?" and x.level< y.level+".$deep:"";
			$sql = sprintf(
				"SELECT x.* FROM %s as y, %s as x
where (y.id=%d) and (x.lid>=y.lid) and (x.rid<=y.rid)".$level."
ORDER BY x.lid ;",
				$this->tbl_name,
				$this->tbl_name,
				$node_id
			);
		} else {//*/
			if(!($row = $this->GetNodeInfo($node_id))) return null;
			$sql = sprintf(
				"SELECT *
				FROM %s
				WHERE lid >= %d
					AND IF(%d, rid <= %d, 1)
					AND IF(%d, level < %d, 1)
				ORDER BY lid",
				$this->tbl_name,
				ppi($row['lid']),
				ppi($row['rid']),
				ppi($row['rid']),
				$deep,
				ppi($row['level']) + $deep
			);
		}
		return $this->db->query($sql);

	}

	function GetBackPath($node_id) {
		$row = $this->GetNodeInfo($node_id);

		$sql = sprintf(
			"SELECT *, tree.id as id
			FROM %s AS tree
			WHERE tree.lid <= %d
				AND tree.rid >= %d
			ORDER BY tree.lid ASC",
			$this->tbl_name,
			ppi($row['lid']),
			ppi($row['rid'])
		);

		return $this->db->query($sql);
	}

}
?>