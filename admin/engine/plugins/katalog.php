<?php

/**
 * Плагин - каталог
 */

define ('KATALOG_TPL','tpl_katalog');

// * определения для csv хандлинга
define ('katalog_TMP_COMPLETE',1);
define ('katalog_UPDATING',2);
define ('katalog_INSERTING',3);

class basket extends ml_plugin {
	
	function getPluginName(){
		return 	pps($this->pluginname,'Оформить заказ');
	}
	
	function clear(){
		unset($_SESSION['basket']);
	}
	
	function basket_items($subcat=1){
		$x=array();
		if (isset($_SESSION['basket']))
		foreach($_SESSION['basket'] as $i=>$v){
			if(!empty($v['n']) && (ppi($v['subcat'],1)==$subcat))  $x[$i]=ppi($v['id'],$i);
		}
		return $x;
	}
	
	function recalc(){
		$x=$this->inumber();
		$pos=$this->pnumber();
		$n=preg_replace('/\d+\s+/','',prop::sema(-($x),"товар||а|ов"));
		$nx=preg_replace('/\d+\s+/','',prop::sema(-$pos,"позици|я|и|й"));
		$cost=$this->cost()/100;
		return array(
				'number'=>$this->inumber(),
				'tovar'=>$n,
				'pos'=>$pos,
				'rcost'=>$cost,
 				'posx'=>$nx,
				'cost'=>number_format($cost, 2, ',', ''),
				'nds'=>number_format(round($cost*18)/100, 2, ',', '')
			);
	}
	
	function basket_data(){
		return array(
				'basket1'=>array(
				'title'=>'Сумма','value'=>number_format($this->cost()/100, 2, ',', '')),
			);
	}

	function do_basket() {
		//debug($this);
		$this->pluginname='Корзина';
		ml_plugin::setupmenu();
		if($this->inumber()){
			$katalogue=itemByType(type_KATALOG);
			$katalogue=&new $katalogue();
			return $katalogue->getText($keys,$this->basket_data(),'Пересчитать')
			.smart_template(array(ELEMENTS_TPL,'basket_btn'),$this->recalc());
		} else
 			return "<center style='font-size:16px;padding:60px;' class='red'><b>Корзина пуста</b></center>";
 				
	}

	function _basket() {
		$x=$this->recalc();
		if (empty($x['pos']))unset($x['pos']);
		//debug($x);
		return smart_template(array(ELEMENTS_TPL,'basket_top'),
			$x
		);
	}
	
	function replaceItem($idX,$numb=1,$par=''){
		if(!isset($_SESSION['basket'][$idX])) return ;
		$_SESSION['basket'][$idX]['n']=$numb;
		//todo: тут надо бы и параметры поменять...
		$_SESSION['basket'][$idX]['xcost']=	number_format(
			$numb*$_SESSION['basket'][$idX]['cost']/100, 2, ',', ''
		);
	}

	function addItem($id,$numb=1,$par=''){
		if(!isset($_SESSION['basket'])) $_SESSION['basket']=array();
		
		$info=$this->parent->export('katalog','itemInfo',$id);

		if(!empty($par)){
			ksort($par);
			$idX.=crc32(serialize($par))+$id;
		} else {
			$idX=$id;
		}
		//echo $idX;
		
		//debug('addItem '.$id);
		if (empty($numb))
			unset($_SESSION['basket'][$idX]);
		else {	
			$_SESSION['basket'][$idX]=
			array(
				'id'=>$id,
				'n'=>$numb,
				'cost'=>$this->_cost($info,$info['subcat']),
				'pic_small'=>pps($info['pic_small']),
				'pic_big'=>pps($info['pic_big']),
				'subcat'=>ppi($info['subcat'])
			);
			$_SESSION['basket'][$idX]['xcost']=	number_format(
				$numb*$_SESSION['basket'][$idX]['cost']/100, 2, ',', ''
			);
			if(!empty($par))
				$_SESSION['basket'][$idX]['par']=$par;
		}
	}

	function inumber(){
		$x=0;
		if (isset($_SESSION['basket']))
		foreach($_SESSION['basket'] as $i=>$v){
			if(!empty($v['n']) && $v['n']>0) $x+=$v['n'];
		}
		return $x;
	}
	
	function pnumber(){
		$x=0;
		if (isset($_SESSION['basket']))
		foreach($_SESSION['basket'] as $i=>$v){
			if(!empty($v['n']) && $v['n']>0) $x++;
		}
		return $x;
	}
	
	function cost($subcat=0){
		$x=0;
		if (isset($_SESSION['basket']))
		foreach($_SESSION['basket'] as $i=>$v){
			if(empty($subcat) || ($subcat==ppi($v['subcat'],1)))	
			if(!empty($v['n'])) $x+=$v['n']*ppi($v['realcost'],$v['cost']);
		}
		return $x;
	}
	
	function _cost(&$v){
		return ppi($v['cost']);
	}

	function get_parameters($par){
		$par['list'][]=array('sub'=>'Банковские реквизиты (квитанция)','title'=>'Название организации','name'=>'spec_ORGANISATION');
		$par['list'][]=array('title'=>'ИНН получателя платежа','name'=>'spec_INN');
		$par['list'][]=array('title'=>'КПП получателя платежа','name'=>'spec_KPP');
		$par['list'][]=array('title'=>'Номер счета получателя','name'=>'spec_ORDERNUM');
		$par['list'][]=array('title'=>'Банк получателя','name'=>'spec_BANK');
		$par['list'][]=array('title'=>'Корсчет','name'=>'spec_SORDERNUM');
		$par['list'][]=array('title'=>'БИК','name'=>'spec_BIK');
		$par['list'][]=array('sub'=>'Дополнительные реквизиты (счет)','title'=>'Дополнительные данные о организации ','name'=>'spec_ADDRESS');
		$par['list'][]=array('title'=>'телефоны (через запятую)','name'=>'spec_PHONE');
		$par['list'][]=array('title'=>'Имя руководителя (счет)','name'=>'spec_DIRECTOR');
		$par['list'][]=array('title'=>'Имя бухгалтра (счет)','name'=>'spec_BUHGALTER');
	}

	function admin_basket(){
		return
		smart_template(array(ADMIN_TPL,'theheader'),array('header'=>"Реквизиты организации",
		'data'=>$this->parent->ffirst('do_siteparam',__CLASS__)));
	}

}
if(!function_exists('fgetcsv')){
class csv_reader {
	var $buf_size=0,
		$buf='',
		$fsize=-1,
		$handle;
		
		
	function size(){
		return $this->fsize;	
	}	
		
	function seek($pos){
		if(@fseek($this->handle,$pos,SEEK_SET)===0){
			$buf_size=0;
			$this->buf='';
			$this->grant_buf();
		}
	}	
	
	function tell(){
		return ftell($this->handle)-$this->buf_size;
	}	
	
	function csv_reader($filename){
		$this->handle = @fopen($filename, "rb");
		if($this->handle){
		fseek($this->handle, 0, SEEK_END);
		$this->fsize = ftell($this->handle);
		fseek($this->handle, 0, SEEK_SET);
		} else debug($filename);
	}
	
	// private
	function grant_buf(){
		if(!!$this->handle && ($this->buf_size<1024)&&(!feof($this->handle)) ){
			$contents=fread($this->handle,1024);
			$x=strlen($contents);
			if(!$x){
				$this->close();
			}
			$this->buf_size+=$x;
			$this->buf.=$contents;
		}
	}
	
	// private
	function eof(){
		return (!$this->handle) || ($this->buf_size==0 && feof($this->handle));
	}
	
	function read_line(){
		$m=array('xx');
		$this->grant_buf();
		$res=array();
		
     // пробуем читать быстро
        if(!$this->eof()){
            $this->grant_buf();
            $pos = strpos($this->buf, "\n",0);
            $tmp=trim(substr($this->buf,0,$pos));
            if(strpos($tmp, '"',0)===false){
               //короткий импорт
               $this->buf=substr($this->buf,$pos+1);
               $this->buf_size-=$pos+1;
               return explode(';',$tmp);
            } 
        }
        
		while(!$this->eof()){
			$this->grant_buf();

			if(!preg_match('/^(?:"((?:""|[^"])*)"|([^;\r\n]*))(;|\r\n|\n|\r|$)/s',&$this->buf,$m)){
				$this->buf='';
				$this->buf_size=0;
				if (empty($res))
					continue;
				else {	
					return $res;
				}
			}
			$this->buf=substr(&$this->buf,strlen($m[0]));
			$this->buf_size-=strlen($m[0]);
			if(!empty($m[1]))
				$res[]=str_replace('""','"',trim($m[1]));
			else 	
				$res[]=trim($m[2]);
			if($m[3]!=';')
				if (empty($res))
					continue;
				else	
					return $res;
		}		
	}
	
	function close(){
		if(!!$this->handle)fclose($this->handle);
		$this->handle=null;
	}
}
} else {
class csv_reader {
	var $fsize=-1,
		$handle;
		
	function size(){
		return $this->fsize;	
	}	
		
	function seek($pos){
		@fseek($this->handle,$pos,SEEK_SET);
	}	
	
	function tell(){
		return ftell($this->handle);
	}	
	
	function csv_reader($filename){
		$this->handle = @fopen($filename, "r+b");
		if($this->handle){
			if ($this->fsize<=0 && !!$this->handle)
				$this->fsize=filesize($filename);
		} else debug($filename);
	}
	
	// private
	function grant_buf(){
	}
	
	// private
	function eof(){
		return (!$this->handle) ||  feof($this->handle);
	}
	
	function read_line(){
		if(!$this->handle || ($data = @fgetcsv($this->handle, 1000, ";"))===false){
			$this->close();
			return array();
		} else {
			return $data ;
		}	
	}
	
	function close(){
		if(!!$this->handle)fclose($this->handle);
		$this->handle=null;
	}
}
}

class csv extends ml_plugin {
	
	function do_test(){
		return $this->csv_import(TMP_DIR.pps($_GET['csv'],'test.csv'));
		//return 'complete';
	}
	
	function handle($code,&$pattern){
		
	}

	function csv_import($fname='',$cat='',$startfrom=0){
		$row = 1;
		if(!is_file($fname)) return "wrong file!($fname)";
		
		$articles=array();
		ignore_user_abort(true);
		
		$report=array('record'=>'report','name'=>'CSV Export');
		$report=$this->parent->readRecord($report);
		if(!isset($report['id'])){
			$report=array('record'=>'report','name'=>'CSV Export');
			$report['id']=$this->parent->writeRecord($report);
		}
		session_write_close();
		@set_time_limit(8*60);
		$starttime=mkt();

		$csv_reader=&new csv_reader($fname);
		$data=$csv_reader->read_line();$lnum=0;
		
		$pattern=array(); $havedata=false;$havepic=false;$havearticle=false;
	// анализ заголовков
		$patternempty=true;
		if(!empty($data))				
			foreach($data as $v){
				$row='';
				foreach($this->fields as $vv){
					//debug($v);
					if (isset($vv['csvfields']) && in_array(trim($v),$vv['csvfields']))
					{
						$row=pps($vv[1],pps($vv['name']));
						$havedata=true;
					}
				}
				$pattern[]=$row;
				$patternempty=$patternempty && empty($row); 
				if($row=='article')
					$havearticle=true;
				if($row=='pic_small')
					$havepic=true;
			}
			
		if($patternempty){
			if(!empty($this->defpattern)) {
				$pattern =$this->defpattern;
				$havearticle=true;
				$csv_reader->seek(0);
			} else {	 
				return "некорректный формат CSV";
			}
		}
		if(!$havearticle)
			$cat=0;
		/**
		 * создача таблицы
		 */	
		if (empty($startfrom)){	
		$sql=$this->database->selectRow('drop table if exists ?_temp;');
		$sql=$this->database->selectRow('show create table ?_katalog');
		$sql=
			preg_replace('/,\s*primary.*$/is',');',$sql['Create Table']);
		$sql=
			preg_replace('/table\s+`?'.TAB_PREF.'_katalog`?/i'
				,(empty($_GET['csv'])?' TEMPORARY':'').' table '.TAB_PREF.'_temp',$sql);
		$sql=
			preg_replace('/`id`.*?,\s*?/i','',$sql);	
		$this->database->query($sql);
		}
		/**
		 * Быстро читаем csv во временную таблицу 
		 */ 	
		while(!$csv_reader->eof() ){
			$data=$csv_reader->read_line();
			
			if(empty($data)) continue;
			
			if(!($num = count($data))) {
				debug($data);
				continue;
			}
			if($lnum++<$startfrom) {
				continue;
			}
			if(!($lnum % 200)){
				$x=$csv_reader->tell();
				$report['report']=$x.' '.round(($x/$csv_reader->size())*100).' '.$lnum.'...';
				$this->parent->writeRecord($report);
				
				// проверка времени и слив, если надо!
				if(mkt()-$starttime>160) {
					// 20 секунд работаем - слив защитан
					$csv_reader->close();
					return 'nave no time!';
				}
			}
					
			$key=array();
			for($i=0,$y=count($data);$i<$y;$i++){
				if(!empty($pattern[$i])){
					if(preg_match('/^[0-9]+(?:[,.][0-9]+)?$/',$data[$i])){
						$key[$pattern[$i]]=str_replace(',','.',$data[$i]);
					} else {
						$key[$pattern[$i]]=trim($data[$i]);
					}
				}
			}
			
			// Артикул - самый важный зверь в этом курятнике
			if(empty($key['articul'])) continue;
			
			// умный парсинг цены
			foreach(array('cost','cost1','cost2','cost3') as $v)
			if(isset($key[$v])){
				//debug($key[$v]);
				if(preg_match('/^\s*([\d\s]*)(?:[,\.](\d\d)|[,\.](\d)|)$/',$key[$v],$m)){
					//debug($m);
					$m[1]=str_replace(' ','',$m[1]);
					if(!empty($m[2]))
						$key[$v]=($m[1].$m[2]);
					elseif(!empty($m[3]))	
						$key[$v]=($m[1].$m[3])*10;
					else if (empty($this->costasis))	
						$key[$v]=($m[1])*100;
				} else {
					if (empty($this->costasis))
						$key[$v]=$key[$v]*100;
				}
			}
			if(!empty($key['pic_small'])){
				// долгий и мудрый поиск фотографии
				$names=array();
				if(!empty($names)){
					ksort($names);
					$key['pic_small']=TO_URL(current($names));
					end($names);
					$key['pic_big']=$key['pic_small'];
				} else {
					$key['pic_small']=toUrl(TMP_DIR.basename($key['pic_small']));
					$key['pic_big']=$key['pic_small'];
				} 
				//debug($v);
				//if(file_exists(TMP_DIR.$key['pic_small'])
			} else {
				$key['pic_big']='';
			}
			
			if(!isset($sql_header)){
				$sql_header='insert into '.TAB_PREF.'_temp  ('
					.implode(',',array_keys($key))
					.') values (';	
			}
			if(empty($key['article'])){
				if(!empty($lastarticle)){
					$key['article']=$lastarticle;	
				}
			} else {
				$lastarticle=$key['article'];	
			}
			$sqlx=array();
			foreach($key as $k=>$v){
				$sqlx[]='"'.mysql_real_escape_string(pps($v)).'"';
			}
			$sql=$sql_header.implode(',',$sqlx).');';
			//debug($sql);	
			$result=mysql_query($sql,$this->database->link);
			if (!$result) {
			   debug('Invalid query: ' . mysql_error() . "\nWhole query: " . $sql);
			}
		}
		$csv_reader->close();
		if(is_file($fname))unlink($fname);
		$report['total']=@$this->database->selectCell('select count(*) from ?_temp;');
		if (empty($report['total'])) return true;
		/**
		 * еще какое-то действие
		 */
		$this->handle(katalog_TMP_COMPLETE,$pattern);
		/**
		 * выставляем ключи, нужные для счастья
		 */
		$res=$this->database->select('ALTER TABLE ?_temp '.
			' ADD INDEX ( `article` ) ,'.
			' ADD INDEX ( `xarticle` ),'.
			' ADD INDEX ( `articul` );');
		
		/**
		 *  добавляем поле 'xarticle'
		 */
		/*
		 *  уродский saflor со своим старым mysql!!!
		 * 
		$res=$this->database->select('select `id`,`name` from ?_page where (?_page.type=8)');
		foreach($res as $v){
			if(!empty($v['name']))
			$res=$this->database->select(
				'UPDATE ?_temp '.
				 'SET ?_temp.xarticle = ?d '.
				 'WHERE (?_temp.articul =?);',$v['id'],$v['name']);
		};/**/
		
		// поиск всех "каталогов"
	/*	$katalog_ids=$this->database->selectCell(
			'select GROUP_CONCAT(`id` SEPARATOR ",") from ?_flesh 
			 WHERE ((ival=8) AND (name="type"));');*/
		// поиск всех "каталогов"
		$katalog_ids=$this->database->selectCol(
			'select `id` from ?_flesh 
			 WHERE ((ival=8) AND (name="type"));');
		$katalog_ids=join($katalog_ids,',');
		
		if($havearticle){
			$res=$this->database->select(
				'UPDATE ?_flesh LEFT JOIN ?_temp ON ?_flesh.ival = ?_temp.article 
				 SET ?_temp.xarticle = ?_flesh.id
				 WHERE ?_flesh.name="cat_type"
				 and (?_flesh.id in ('.trim($katalog_ids,', ').'))
				 and (((?_temp.articul) Is Not Null));');
			/**
			 *  Выкидываем элементы, которых нет в основной таблице
			 */
			//return;
			$res=$this->database->select(
				'delete from ?_temp where `xarticle`="";');
			$report['unused_values']=$res;
			/**
			 *  получаем список статей
			 */
			$articles=implode(',',array_keys($this->database->select(
				'select distinct `xarticle` as ARRAY_KEY from ?_temp;'
			)));			
			/**
			 *  выкидываем элементы, которые надо удалить
			 */
			if(!empty($articles)){
	/*
	  			$res=$this->database->select(
					'DELETE ?_katalog
					FROM ?_katalog LEFT JOIN ?_temp ON ?_katalog.articul = ?_temp.articul
					WHERE (?_katalog.xarticle In ('.$articles.')) AND (?_temp.articul Is Null);'
				);
	*/
			/**
			 *  выкидываем левые характеристики
			 *  TODO: Нехорошо удалять характеристики не глядя...
			 */
				
				$res=$this->database->select(
					'DELETE ?_katalog
					FROM ?_katalog LEFT JOIN ?_temp ON ?_katalog.articul = ?_temp.articul
					WHERE (?_temp.articul IS Null) and (?_katalog.xarticle In ('.$articles.')) ;'
				);
				
			}
		}
		
		/**
		 *  апдейтим элементы, которые надо апдейтить
		 */
		$update=array();
		if($havearticle) $update[]='x.xarticle=y.xarticle';
		if($havepic) $update[]='x.pic_big=y.pic_big';
		foreach($pattern as $v)
			if(!empty($v) && !in_array($v,array('articul','id','haract')))
				$update[]=sprintf('x.%s=y.%s',$v,$v);

		if(!empty($update)){
			$sql='UPDATE ?_katalog as x LEFT JOIN ?_temp as y ON x.articul = y.articul SET '.
				implode(',',$update).' where y.articul<>"";';		
			
			$this->handle(katalog_UPDATING,$sql);
			
			$res=$this->database->select($sql);
		}
		$report['updated_values']=$res;
		$res=$this->database->select(
			'delete ?_temp 
			from ?_temp LEFT JOIN ?_katalog  ON ?_katalog.articul = ?_temp.articul where ?_katalog.articul<>"";'
		);
		$report['updated_values']=sprintf('%s (%s)',$res,$report['updated_values']);
		/**
		 *  инсертим элементы, которые нужно добавить
		 */
		$insert=array();
		if($havearticle) {
			$insert[]='xarticle';
			if($havepic) $insert[]='pic_big';
			foreach($pattern as $v)
				if(!empty($v) && !in_array($v,array('id')))
					$insert[]=sprintf('%s',$v);
			$sql='insert into ?_katalog ('.implode(',',$insert).')
					select y.'.implode(',y.',$insert).' from ?_temp as y;';		
		    $this->handle(katalog_INSERTING,$sql);
			$res=$this->database->select($sql);
			$report['new_values']=mysql_affected_rows();
		}
		$report['lost_values']=$this->database->selectCell('select count(*) from ?_temp;');
		
		$report['report']='clearing';$this->parent->writeRecord($report);
		//$this->do_clear();
		$report['report']='complete';$this->parent->writeRecord($report);
		unset($report['id'],$report['name'],$report['record']);
		return "complete! \n\r".
		pp($report['total'],   "всего в CSV\t"," записей\n\r").
		pp($report['lost_values'],   "потеряно\t","\n\r").
		pp($report['unused_values'] ,"нет ссылки\t","\n\r").
		pp($report['updated_values'],"изменено\t\t","\n\r").
		pp($report['new_values']    ,"добавлено\t","\n\r").
		'----------------------------------'
		; 
	}
	
}

class katalog extends ml_plugin
{//код ;код подгруппы;характеристика;наименов;ед. изм.;цена;остаток

/**
 * Хозяин
 *
 * @var engine
 */
	var $parent;
	
	var $specs=array('new','spec','action1','action2');
	
	function do_convert2(){
		var_dump ($this->database->select($_GET['sql'])); 
	}
	
	function do_convert3(){
		$res=$this->database->select('select id,articul from ?_katalog ');
		$x=0;
		foreach ($res as $k=>$v) {
			if (trim(strip_tags($v['articul']))!=$v['articul']) {
				$x++;
				$this->database->select('update ?_katalog set articul=? where id=?'
					,trim(strip_tags($v['articul'])),$v['id']);
			}
		}
		echo $x;
	}
	

	function getContext($id){
		$key=array();
		$key=$this->database->selectRow('select * from ?_katalog where `id`=?',$id); 
		//$this->parent->error( $id );
		$res=array();
		foreach($key as $k=>$v){
			$res[$k.'_'.$id]=$v;
		}
		//print_r($res);
		return $res;
	}

 	function do_clear(){
 		/*
		$c_cat=0;
		$c_el=0;
		$pages=$this->database->select('select distinct `id` from ?_page where `type`=?d order by `id`;',type_KATALOG);
		$ids=array();
		if(!empty($pages)){
			foreach($pages as $v){
				$ids[]=$v['id'];
			}
		}	
		$this->database->query('delete from ?_katalog where `xarticle` not in ('.
			implode(',',$ids).');');

		$this->database->query('OPTIMIZE TABLE ?_katalog;');
*/
		return false;
	}

	function handlePost(&$res){
		if(!empty($res)){
			$order=array();
			foreach($res as $v){
				//$this->serialize($v,$_POST,true);
				if(isset($_POST['item_order_'.$v['id']])){
					$order[$v['id']]=intval($_POST['item_order_'.$v['id']]);
				}
			}
			if(!empty($order)){
				if(in_array($this->cat,$this->specs)){
					$sortby=$this->cat;
					$sql='select `id` AS ARRAY_KEY,'.
					'(@order:=@order+1) as `num` from ?_katalog where'.
					' `'.$sortby.'`>0 order by `'.$sortby.'`;'; 
				} else {
					$sortby=$this->sort;
					
					$sql='select `id` AS ARRAY_KEY,'.
					'(@order:=@order+1) as `num` from ?_katalog where `xarticle`='.$this->cat.
					' order by `'.$sortby.'`;'; 
				}
				if ($sortby!='descr') {
					// перенумеровать
					//set @order=0;
					$this->database->select('set @order=0;');
					$sortx=$this->database->select($sql);
					$sort=array();
					foreach($sortx as $k=>$v){
						if($order[$k]>0)
							$sort[$k]=$v['num']+$order[$k]+0.5;
						else if ($order[$k]<0)	
							$sort[$k]=$v['num']+$order[$k]-0.5;
						else	
							$sort[$k]=$v['num'];
					}
					debug($sort);
					asort($sort);
					$sort=array_keys($sort);
					if(!empty($sort))
					$this->database->select(
						'update ?_katalog set `'.$sortby.'`=field(`id`,'.implode(',',$sort)
						.') where `id` in ('.implode(',',$sort).');' 
					);
				}
			}			
		}
		if(isset($_POST['doIt'])){
			if(!empty($_POST['doIt'][1]))
				$_POST['sel_act']=pps($_POST['sel_act'][1]);
			else
				$_POST['sel_act']=pps($_POST['sel_act'][0]);
			debug('Here!! '.$_POST['sel_act']);	
			switch(pps($_POST['sel_act'])){
				case 'copy':
					//скопировать в клипборд ID'ы записей 
					$_SESSION['clip_katalog']= $_POST['aaa'];
					break;
				case 'paste':
					// переместить в новый раздел
					if(!empty($_SESSION['clip_katalog'])){
						foreach($_SESSION['clip_katalog'] as $a)
							$this->Category('add',$this->cat,$a);
						} 
					break;
				case 'action1':
				case 'action2':
				case 'spec':
				case 'new':
					if( !empty($_POST['aaa']))
					foreach($_POST['aaa'] as $a)
						$this->Category('add',$_POST['sel_act'],$a);
					break;
				case 'del':
					if( !empty($_POST['aaa']))
					foreach($_POST['aaa'] as $a)
						$this->data('del',$a);
					break;
			}
		}
	}
	
	function get_sql($cat){
		switch($cat){
			case 'null':
				$sql='select * from ?_katalog where `article`="" or `xarticle`="" ';
				break;
			case 'all':
				$sql="select * from ?_katalog ";
				break;
			case 'action1':
			case 'action2':
			case 'spec':
			case 'new':
				$spec=true;
				$sql='select * from ?_katalog where `'.$cat.'`>0 order by `'.$cat.'` ';
				if(empty($GLOBALS['kat_mod'])){
					$this->sort=true;	
					array_push($this->fields,array(' ',$cat,'dontshow'=>true));
					$GLOBALS['kat_mod']=true;
				}
				break;
			default: // вывод категорий!
				$sql='select * from ?_katalog where `xarticle`='.$cat.
				(pps($this->sort)
					?' order by `'.$this->sort.'` '
					:' order by `articul` ');
		}
		return $sql;
	}

	function data($what,$from='',$perpage=''){
		static $updated,$sqlcnt,$sql;
		$spec=false;

		$sql=$this->get_sql($this->cat);  
		
		if(empty($sqlcnt)){
			$sqlcnt=preg_replace('/select.*?from/is','select count(*) from',$sql,1);
		}
		//debug($what.' '.$cat);
		switch($what){
			case "cnt":
				return $this->database->selectCell($sqlcnt);
			case "data":
				$yy=$this->database->query($sql.' LIMIT ?d,?d',$from,$perpage);
				//debug($yy);
				$head_items=array();
				foreach($yy as $k=>$v){
					if(!isset($head_items[$v['xarticle']])){
						$head_items[$v['xarticle']]=$this->parent->nodeGet($this->parent->node($v['xarticle']));
					}
					if(isset($head_items[$v['xarticle']]) && isset($head_items[$v['xarticle']][1]))
						$root=$head_items[$v['xarticle']][1];
					else
						$root=array();	
					if(empty($v['pic_small']))
						$yy[$k]['pic_small']=pps($root['pic_small']);
					if(empty($v['pic_big']))
						$yy[$k]['pic_big']=pps($root['pic_big']);;
					if(empty($yy[$k]['pic_big']))
						$yy[$k]['nobigpic']=true;
					foreach(array('cost','cost1','cost2','cost3') as $r){
						if(!empty($yy[$k][$r]))
						$yy[$k][$r]=number_format($yy[$k][$r]/100, 2, ',', '');
					}
				}
				//debug($yy);
				return $yy;
			case "del":
				debug('del:'.$this->cat);
				if(in_array($this->cat,$this->specs)){
					$this->database->select('update ?_katalog  set `'.$this->cat.'`=0 where `id`=?d;',$from);
				} else
					$this->deleteItem($from);
				break;
			case "upd":
				if(!empty($_POST['pic_big_'.$perpage]))
					$from['pic_big']=$_POST['pic_big_'.$perpage];
				if(isset($_POST['pic_0_big_'.$perpage]))
					$from['pic_0_big']=$_POST['pic_0_big_'.$perpage];
				/**
				 * характеристики
				 */
				$row=false;
				if(isset($from['articul'])) {
					// есть изменение артикула
					// ищем "основного"
					$row=$this->database->select('select * from ?_katalog where `articul`=?',$from['articul']);
					if(count($row)>1) {
						//debug($perpage);
						$this->parent->error('Ошибка базы. Много товаров с одним артикулом '.$from['articul']);
						return;
					} else if (count($row)==1)
						$row=$row[0];
					else
						$row=false;
				}
				$this->convertcost($from,$what);
						
				parent::data($what,$from,$perpage);
				break;
			case "ins":
				$this->convertcost($from,$what);
				$id=parent::data($what,$from,$perpage);
				$this->Category('add',$this->cat,$id);
		   		break;
		}
	}
	
	function convertcost(&$from,$reson){
		foreach(array('cost','cost1','cost2','cost3') as $r){
			if(isset($from[$r]))
				if(preg_match('/^\s*(\d*)(?:[,\.](\d\d)|[,\.](\d))\s*$/s',$from[$r],$m)){
//					debug($m);
					if(!empty($m[2]))
						$from[$r]=($m[1].$m[2]);
					elseif(!empty($m[3]))	
						$from[$r]=($m[1].$m[3])*10;
					else	
						$from[$r]=($m[1])*100;
				} else {
					$from[$r]=$from[$r]*100;
				}
		}				
	}

	function do_create($killall=true){
//		debug('xxx');
		if(!$this->parent->has_rights(right_WRITE))
			return $this->parent->ffirst('_loginform');
		if($killall){
			$this->database->query('DROP TABLE IF EXISTS ?_katalog;');
		//	$this->database->query('DROP TABLE IF EXISTS ?_category;');
		}
		if(empty($votes_root)){
			$this->database->query(
			isset($this->sql_create_katalog)?$this->sql_create_katalog:
			"CREATE TABLE ?_katalog (
			  `id` int(11) NOT NULL auto_increment,
			  `parent` int(11) NOT NULL auto_increment,
			  `type` int(11) NOT NULL auto_increment,
			  `level` int(11) NOT NULL auto_increment,
			  `articul` varchar(20) NOT NULL default '',
			  `proisv` varchar(255) NOT NULL default '',
			  `name` varchar(255) NOT NULL default '',
			  `haract` varchar(50) NOT NULL default '',
			  `descr` text NOT NULL,
			  `descr2` text NOT NULL,
			  `descr7` int(11) NOT NULL,
			  `pic_small` varchar(255) NOT NULL default '',
			  `pic_big` varchar(255) NOT NULL default '',
			  `remain` int(11) NOT NULL default '0',
			  `cost` int(11) NOT NULL default '0',
			  `cost2` int(11) NOT NULL default '0',
			  `edism` varchar(50) NOT NULL default '',
			  `flag` int(10) NOT NULL default '0',
			  `article` varchar(50) NOT NULL default '',
  			  `xarticle` int(10) NOT NULL default '0',
  			  `new` int(10) NOT NULL default '0',
    		  `spec` int(10) NOT NULL default '0',
    		  `order` int(10) NOT NULL default '0',
			  PRIMARY KEY  (`id`),
			  KEY `articul` (`articul`),
			  KEY `parent` (`articul`),
  			  KEY `level` (`articul`),
  			  KEY `haract` (`haract`),
			  KEY `article` (`article`),
			  KEY `xarticle` (`xarticle`)
			  )");


	//		$ns=&new DBNestedSet($this->database,TAB_PREF.'_category');
	/*		$ns->AddNode(0,array('name'=>'category'));
			$ns->AddNode(0,array('name'=>'charact'));
			$ns->AddNode(0,array('name'=>'new'));
			$ns->AddNode(0,array('name'=>'spec'));*/
		}
	}

	function get_category($cat,$dir='')
	{
		$subcat=0;	$_item=false;
		if($cat=='basket1'){
			$subcat=1;
			$x=$this->parent->export('basket','basket_items');
			if(!empty($x)){
				$val=array();
				foreach($x as $k=>$v)if(!empty($v)){
					$val[]=sprintf('(%s,"%s")',$v,$k);
				//	$val[]=sprintf('WHEN %s THEN "%s"',$v,$k);
				}
				$this->database->select('drop table if exists ?_tmp;');
				
				$this->database->select('CREATE temporary TABLE ?_tmp (
  `id` int(11) NOT NULL ,
  `xid` varchar(40) NOT NULL 
);');
				$this->database->select('INSERT INTO ?_tmp VALUES '.
					implode(',',$val).';');
				
				$sql="select ?_katalog.*,?_tmp.xid from ?_katalog RIGHT JOIN ?_tmp on ?_tmp.id=?_katalog.id ";
/*
				$sql="select ?_katalog.*, ".
				"case `id` ".implode(' ',$val)." end as `xid` ".
				"from ?_katalog where `id` in (".implode(',',array_values($x)).") ".
				"order by field(`id`,".implode(',',array_values($x)).")"; */
			}
		} elseif($cat=='basket'){
			$subcat=0;
			$x=$this->parent->export('basket','basket_items',$subcat);
			if(!empty($x)){
				$sql="select * from ?_katalog where `id` in (".implode(',',$x).') ';
			}
		} elseif($cat=='basket2'){
			$subcat=2;
			$x=$this->parent->export('basket','basket_items',$subcat);
			if(!empty($x)){
				$sql="select * from ?_katalog where `id` in (".implode(',',$x).') ';
			}
		} elseif($cat=='basket3'){
			$subcat=3;
			$x=$this->parent->export('basket','basket_items',$subcat);
			if(!empty($x)){
				$sql="select * from ?_katalog where `id` in (".implode(',',$x).') ';
			}
		} else if (preg_match('/^select/i',$cat,$m)) {
			$sql=$cat;
			//debug($sqlcnt);
		} else if(in_array($cat,$this->specs)){
			//$spec=true;
			$sql='select * from ?_katalog where `'.$cat.'`>0 order by `'.$cat.'` ';
			//array_push($this->fields,array(' ','order','win_order','serialize'=>false));
			//break;
		} else if (preg_match('/^item_(-?\d+)$/',$cat,$m)) {
			
			$sqlx='select ?_katalog.*, ?_page.* from ?_katalog
			LEFT JOIN ?_page ON ?_katalog.xarticle=?_page.id 
			where  ?_katalog.id='.$m[1];
			$_item=true;
			
			$res=$this->database->selectRow($sqlx,$m[1]);
			
			$sql='select * from ?_katalog
			where ?_katalog.articul="'.mysql_real_escape_string($res['articul']).'"';
			//debug($res);
			$cat=substr($res['name'],1);
		} else {
			if (in_array(pps($_GET['ch']),$this->specs)) 
				$where=' and ?_katalog.'.$_GET['ch'].'>0';
			else
				$where='';	
			$sql='select ?_katalog.* from ?_katalog
			where ?_katalog.xarticle='.$cat.$where.
				($this->sort
					?' order by `'.$this->sort.'` '
					:' order by `articul` ');
/*
			$sql='select ?_katalog.*, count(x.haract)-1 as xx  from ?_katalog
			LEFT JOIN ?_katalog as x ON ?_katalog.articul=x.articul 
			where ?_katalog.haract="" and ?_katalog.xarticle="'.$cat.'" 
			group by ?_katalog.articul';
 */
			$sqlcnt='select count(*) from ?_katalog
			where ?_katalog.xarticle='.$cat ;
		}
		if(empty($sql))
			$sql='select * from ?_katalog where (1=0)';
		if(empty($sqlcnt)){
			$sqlcnt=preg_replace('/select.*?from/is','select count(*) from',$sql,1);
		}

		// 
		$cnt=$this->database->selectCell($sqlcnt);
		//debug($cnt);
		$perpage=pps($_COOKIE['perpage'],$this->parent->getPar('catalogue-perpage'));
		//debug('perpage '.$perpage.' '. $_COOKIE['perpage']);
		$perpage=ppi($perpage,60);
		$maxpg=ceil(($cnt-0.5)/$perpage);
		$page=ppi($_GET['pg']);
		//debug($cnt.' '.$perpage.' '.$page);
		if($page>=$maxpg)$page=max(0,$maxpg-1);

		$x= $this->database->query($sql.' LIMIT ?d,?d',$page*$perpage,$perpage);
		/**
		 * дорисовываем картинки и цены для корзинки
		 */
		if(preg_match('/basket\d+/',$cat)){
			foreach($x as $k=>$v){
				$x[$k]['havebasket']=false;
				if(!empty($_SESSION['basket'][$v['xid']]['par'])){
					$x[$k]=array_merge($x[$k],$_SESSION['basket'][$v['xid']]['par']);
				}
				if(empty($v['pic_small']))
					$x[$k]['pic_small']=$_SESSION['basket'][$v['xid']]['pic_small'];
				$x[$k]['xcost']=$_SESSION['basket'][$v['xid']]['xcost'];
				$x[$k]['cnt']=$_SESSION['basket'][$v['xid']]['n'];
				$x[$k]['xid']=$v['xid'];
				//
				//$x['id']=$x['xid'];
			}	
		} 
		//debug($x);
		//$xx=$res=$this->parent->nodeGet($this->parent->node($cat));
		$head_items=array();
		/**
		 * дорисовываем картинки и для остальных товаров
		 */
		if(!empty($x)){
			foreach($x as $k=>$v){
				// вычисляем top элемент
				if(!isset($head_items[$v['xarticle']])){
					$head_items[$v['xarticle']]=$this->parent->nodeGet($this->parent->node($v['xarticle']));
				}
				if(isset($head_items[$v['xarticle']]) && isset($head_items[$v['xarticle']][1]))
					$root=$head_items[$v['xarticle']][1];
				else
					$root=array();	
				if(empty($v['pic_small']))
					$yy[$k]['pic_small']=pps($root['pic_small']);
				
				if(!isset($x[$k]['havebasket'])) $x[$k]['havebasket']=true;
				if(empty($v['pic_small']))
					$x[$k]['pic_small']=pps($root['pic_small']);
				//TODO: подумать - нужно ли проверять файл на существование.
				/*
				if(!is_file($_SERVER['DOCUMENT_ROOT'].$x[$k]['pic_small']))	
					$x[$k]['pic_small']='img/1x1t.gif';
				*/
				if(empty($v['pic_big'])){
					$x[$k]['pic_big']=pps($root['pic_big']);
				}
				if(empty($x[$k]['pic_big'])){
					$x[$k]['nobigpic']=true;
				}
			}
		}
		/**
		 * прорисовываем страничную линейку
		 */
		$xx=null;
		if(is_array($dir)){
			$dir['data']=$x;
			if(empty($dir['data'])) $dir['data']='';
			$dir['headers']=$this->get_headers_from($xx,$subcat,$_item,$cat);
			//debug($res);
			$ppp=$this->parent->getPar('pages_depth');
			$dir['pages']=$this->parent->calc_Pages($cnt,$perpage,$page,ppi($ppp,3));
			$dir['perpage']=$perpage;
			$x='';
		}
		
		// проверка а не в форме ли мы.
		if(strtolower($_SERVER['REQUEST_METHOD'])=='post'){
			$this->parent->sessionstart();
			if(isset($_POST['clear_bsk'])){
				$this->parent->export('basket','clear');
			} else {
				if(!empty($_POST['perpage'])){
					setcookie('perpage',ppi($_POST['perpage'],40),time()+60*60*24*30 );
				}
				$_SESSION['perpage']=ppi($_POST['perpage'],40);
				//debug($_POST);
				$param=array();
				foreach($_POST as $k=>$v){
					if(preg_match('/^(\w+)_([-\d]+)$/',$k,$m)){
						if(!isset($param[$m[2]]))
							$param[$m[2]]=array();
						if(!in_array($k,array('item_','xitem_','id_'))){
							$param[$m[2]][$m[1]]=$v;	
						} 
					}
				}
				foreach($_POST as $k=>$v){
					if((!empty($v) || ($v==='0') || ($v===0)) && preg_match('/^(x?item)_(-?\d+)$/',$k,$m)){
						//debug($param[$m[1]]);
						$this->parent->export('basket',
							$m[1]=='item'?'addItem':'replaceItem'
							,$m[2],ppi($v),$param[$m[2]]);
					}
				}
			}

			$this->parent->go($this->parent->curl());
		}
		//debug('2');
		if(empty($x))
			$x=&$dir['data'];
		if(!empty($x))
			foreach($x as $k=>$v){
				$hd=&$dir['headers'];
				//debug($x[$k]['id']);
				$x[$k]['sign']=(ppi($v['remain'])>0);
				
				
				foreach(array('cost','cost1','cost2','cost3') as $vv)
					if(isset($x[$k][$vv]))
						$x[$k][$vv]=number_format($v[$vv]/100, 2, ',', '');
				if(!empty($v['xx'])) {
					$x[$k]['cat']=$this->parent->cur_menu;//pps($_GET['id']);
					$x[$k]['mod']='xx';
					$x[$k]['disabled']='disabled="disabled"';
				} ;
				for($vvv=0;$vvv<count($hd);$vvv++){
					if(isset($hd[$vvv]['func'])){
						$func=$hd[$vvv]['func'];
						$x[$k][$hd[$vvv]['var']['text']]=$func($v);
					}
				}
			}
		//debug($x);
		return $x;

	}

	function Category($action,$cat,$id=0){
		static $category, $ns;
		
		if($action=='add'){
			if(in_array($cat,$this->specs)) {
				$xx=ppi($this->database->selectCell('select max(`'.$cat.'`) from ?_katalog '));
				$this->database->query('update ?_katalog set `'.$cat.'`=?d where `id`=?d',$xx+1,$id);
			}
			else
				$this->database->query('update ?_katalog set `xarticle`=? where `id`=?d',$cat,$id);
		} else { // delete
			//debug($category[$cat]);
			$this->database->query('delete from ?_katalog where `xarticle`=? ',$cat);
		}
	}

	function deleteItem($id){
		$this->database->query('delete from ?_katalog where `id`=?d',$id);
	}
	
	function admin_katalog($cat=''){
		$this->cat=pps($cat,'all');
				
		if(empty($cat)){
			$this->parent->menu['head']=array('MAIN','_modules',$this->getPluginName(),get_class($this));
		} else {
			$x=$this->parent->ffirst('_getCurList');
			$s=array();
			array_shift($x);
			foreach($x as $k=>$v)
				$s[]= empty($v['descr'])?$v['name']:$v['descr'];
		}
		return
		smart_template(array(ADMIN_TPL,'theheader')
		,array(
				'header'=>empty($s)?$this->getPluginName():implode('/',$s),
				'data'=>parent::admin_plugin(false))
		);
	}

	function itemInfo($item){
		if(!$item) $item=pps($_GET['item']);
		if(empty($item)) return '';
		
		$sql='select * from ?_katalog where ?_katalog.id=?d';
		$res=$this->database->selectRow($sql,ppi($item));
		if(empty($res)) return '';
		$xx=$this->parent->nodeGet($this->parent->node($res['xarticle']));
		debug($xx);
		$res['subcat']=ppi($xx['subcat']);
		
		if(empty($res['pic_small']))
			$res['pic_small']=pps($xx['url']);
		if(empty($res['pic_big'])){
			$res['pic_big']=trim(pps($xx['text']));
		}
		if(empty($res['pic_big'])){
			$res['nobigpic']=true;
		}
//		debug($res);
		return $res;
	}
	

	function do_find($item=''){
		if(!$item) $item=pps($_GET['item']);
		if(empty($item)) return '';
		$sql='SELECT * FROM ?_katalog WHERE (`id`=?d)'; // берем первую попавшуюся категорию ...
		$res=$this->database->select($sql,ppi($item));
		if(empty($res)) return '';
		// поиск категории
		//debug(1111111111111111111);
		foreach($res as $v){
			//debug($res);
				
			if(defined('IS_ADMIN')){
				$name=array(
					'url'=>sprintf('?do=cat&id=%s&cat=%s',$v['xarticle'],$v['xarticle']),
					'name'=>$res['articul'].' '.$res['descr']
				);
			} else {
				if(!empty($v['xarticle'])){
					$name=$this->parent->export('MAIN','url_by_item',$v['xarticle']);
				} else
					return '';
			}
			if(!empty($name['url'])){
				if(defined('IS_ADMIN'))
					$sav=20;
				else
					$sav=pps($_COOKIE['perpage'],$this->parent->getPar('catalogue-perpage'));
				$_COOKIE['perpage']=0xFFFFF;
				$x=$this->get_category($v['xarticle']);
				$cnt=0;
				debug($cnt.' '.$sav);
				foreach($x as $v){
					if ($v['id']==$item){
						$this->parent->go($name['url'].'&pg='.(floor($cnt/$sav)).'#item_'.$item);
					}
					$cnt++;
				}
				$this->parent->go($name['url'].'&item='.$item);
			}
		}
		return '';
	}
	
	function search($s,$sql=''){
		if($sql=='')
			$sql=pps($this->searchSQL,'SELECT z.* '. 
				'FROM ?_katalog as z '. 
				'WHERE (LOCATE( ?, CONCAT(" ",LCASE(z.descr2)," ",LCASE(z.descr)," ",LCASE(z.articul) ) ) !=0) '. 
				'order by z.xarticle LIMIT 1000;');

		$res=$this->database->query($sql,$s,$s);
		// поиск категории
		if(empty($res)) return array();
		$result=array();
		$last=0;$lasttag='';
		foreach($res as $k=>$v){
			if($last!=$v['xarticle']){
				$name=$this->parent->url_by_item($v['xarticle']);
				$last=$v['xarticle'];$lasttag=$name['tag'];
				//debug($name);
			}	
			$result[]=array(
				'tag'=>$lasttag,
				'text'=>
					smart_template(array(ELEMENTS_TPL,'katalog_searchres'),
					$v)
			);
			//debug($result);
		}

		return $result;
	}
	
}

class spec extends plugin {

	function do_spec(){
		return xKatalogue::getText($keys,array('spec'=>array()));
	}

	function get_parameters($par){
		$par['list'][]=array('sub'=>'спецпредложения','title'=>'Количество для вывода на главной странице','name'=>'spec_numb1');
		$par['list'][]=array('title'=>'Количество столбцов для вывода на главной странице','name'=>'spec_cols1');
		$par['list'][]=array('title'=>'Количество для вывода на "широкой" странице','name'=>'spec_numb2');
	}

	function admin_spec(){
		$this->parent->menu['head']=array('MAIN','_modules','Спецпредложения',get_class($this));
		return
		$this->parent->export('katalog','admin_katalog','spec').
		$this->parent->ffirst('do_siteparam',__CLASS__);
	}

}

class novinki extends plugin {
	function do_novinki(){
		return xKatalogue::getText($keys,array('new'=>array()));
	}

	function get_parameters($par){
		$par['list'][]=array('sub'=>'Новинки','title'=>'Количество для вывода на главной странице','name'=>'novinki_numb1');
		$par['list'][]=array('title'=>'Количество для вывода на "широкой" странице','name'=>'novinki_numb2');
	}

	function admin_novinki(){
		$this->parent->menu['head']=array('MAIN','_modules','Новинки',get_class($this));
		return
		$this->parent->export('katalog','admin_katalog','new').
		$this->parent->ffirst('do_siteparam',__CLASS__);
	}

}
?>