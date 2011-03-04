<% //<?php 
/**
 * Сюда будем вписывать константы, которые будут использоваться 
 * в CMS для обозначения типов элементов.
 * Любой новый элемент обязан захватить и удерживать за собой следующую точку
 */
define ('type_MENU',0);
define ('type_CELL',1);
define ('type_LINKS',3);
//define ('type_HEADER',4);
define ('type_TABLE',6);
define ('type_GALLERY',7);
define ('type_KATALOG',8);
define ('type_KATALOGEL',9);
define ('type_ARTICLE',10);
define ('type_PIC',11);
define ('type_MAINMENU',12);
define ('type_LINK',13);
define ('type_ROW',14);
define ('type_HIDDENMENU',15);
define ('type_NEWTEXTPIC',16);
define ('type_PLUGIN',17);
define ('type_ARTICLELIST',18);
define ('type_COLUMNS',19);
define ('type_ANCHOR',20);
define ('type_EMPTYCELL',21);
//новые типы
define ('type_GALLERY2',26);
define ('type_LINE',27);
define ('type_COMMENT',28);

$LINE_TYPES_ARRAY=array(0=>'',1=>'cпошная',2=>'точками',3=>'inset',4=>'outset');
/**
 *  а сюда будем вписывать функции формирования таблиц
 */ 
function pp(&$x,$pre=' ',$post='',$def='') { return (!empty($x))?$pre.$x.$post:$def;}
function pps(&$x,$def='') { return (!empty($x))?$x:$def;}
function ppi(&$x,$def=0) { return (!empty($x))?intval($x):$def;}
function ppx($x,$def='') { return (!empty($x))?$x:$def;}

/**
 *  а сюда будем вписывать функции формирования таблиц
 */ 
 
function TPL_elTable($level,$data){
// table head
	switch ($level){
	case 0: echo '<div class="arrow "></div>
<table class="pane fixed long ">
'; 
		break;
	}
// table cols
	foreach($data as $k=>$v){
		$w=trim(pps($v['width']));
		if(empty($w) && $k=='logo') 
			$w='{level??66::90}';
		if(empty($w))
			echo'<col>
';
		else	
			echo'<col width="'.$w.'">
';
	}
	echo '<col width="50"><tr>
';
// table body
	foreach($data as $k=>$v){
		if(isset($v['alt'])){
			if (strpos($v['alt'],','))
				$x=explode(',',$v['alt']);
			else
				$x=array($v['alt']);
			$v['alt']=$x[0];	
			for($i=1;$i<count($x);$i++){
				$v['alt'.$i]=$x[$i];
			}
		}
		if (isset($v['vname'])){
			if (strpos($v['vname'],','))
				$x=explode(',',$v['vname']);
			else
				$x=array($v['vname']);	
			$v['name']=$x[0].'_{id}';
			$v['value']='{'.$x[0].'>>html}';
			$v['vvalue']=$x[0];
			for($i=1;$i<count($x);$i++){
				$v['name'.$i]=$x[$i].'_{id}';
				$v['value'.$i]='{'.$x[$i].'}';
				$v['vvalue'.$i]=$x[$i];
			}
		}
		$border=isset($v['noborder'])?' noborder':'';
		$bg_position=isset($v['noborder'])?'background-position: 0 100%;':'background-position: 100% 100%;';
		switch(trim($k,'_')){
		case 'select':
			echo '<td class="bgxx'.$border.' align_center align_middle">'.pps($v['title']).'<div style="display:inline-block;text-align:left;">';
			echo '<select class="select"'.pp($v['name'],' name="','"').pp($v['alt'],' placeholder="','"').pp($v['value'],' value="','"').'>';
			foreach($v['txt'] as $k=>$vv){
				echo'<option  value="'.$k.'"'.pp($v['vvalue'],'{','='.$k.'?? selected}').'>'.$vv.'</option>';
			}
			echo '</select></div></td>';
			break;	
		case 'align':
			if(empty($v['name'])) 
				$v['name']='item_align_{id}';
			echo'<td class="bgxx'.$border.' align_center align_middle" style="vertical-align: bottom;" title="выравнивание текста"
			style="padding: 0 2px;"><input'.pp($v['name'],' name="','"').' type="text"'. 
			' class="align hidden"></td>
';	
			break;
		case 'article':
			echo'<td class="bgxx'.$border.'" style="vertical-align: bottom;"><input type="button" title=""
			class="panebutton notepane" value="'.pps($v['txt'],'Доп.Описание {article_count}').'"'
			.pp($v['data-number'],' data-number="','"')
			.'></td>
';			break;
		case 'foto':
			echo'<td class="bgxx'.$border.'" style="vertical-align: bottom;"><input type="button" title=""
			class="panebutton fotopane" value="Доб.Фото{foto_count}"></td>
';			
			break;
		case 'table':
			echo'<td class="bgxx'.$border.'" style="vertical-align: bottom;"><input type="button" title=""
			class="panebutton tablepane" value="Таблица"></td>
';			
			break;
		case 'comment':
			echo'<td class="bgxx'.$border.'" style="vertical-align: bottom;"><input type="button" title=""
			class="panebutton commentpane" value="Доб.comment{comment_count}"></td>
';			
			break;
		case 'links':
			echo'<td class="bgxx'.$border.'" style="vertical-align: bottom;"><input type="button" title="Добавить ссылку"
			class="panebutton linkpane" value="Доб.ссылку{links_count}"></td>
';			
			break;
		case 'empty':
			echo '<td class="bgxx'.$border.'">'.pps($v['txt']).'</td>';
			break;	
		case 'html':
			echo'<td class="bgxx'.$border.'" title="текст">
			<div id="'.pps($v['name'],'item_text').'_{id}" class="html_edit">'.pps($v['val'],'{text_x}').'</div>
			</td>
';			
			break;
		case 'logo':
			echo'<td class="bgxx'.$border.' align_center align_middle" style="height:67px;" title="">
				<img class="id_picture"	src="img/'.$v['img'].'"></td>';
			break;
		case 'raw':
			echo $v['data'];
			break;
		case 'direct':
			echo '<td class="bgxx'.$border.' align_middle" style="height:67px;" title="">'.
				$v['data'].'</td>';
			break;
		case 'radio':
			echo '<td class="bgxx'.$border.' align_center align_middle">'.pps($v['title']).'<div style="display:inline-block;text-align:left;">';
			foreach($v['txt'] as $k=>$vv){
				echo'<input type="radio" name="'.$v['name'].'" value="'.$k.'"'.pp($v['vvalue'],'{','='.$k.'?? checked}').'>'.$vv;
			}
			echo '</div></td>';
			break;	
		case 'color':
			echo '<td class="bgxx'.$border.' align_center align_middle">'.pps($v['txt']).'<input class="box colorbox"'.pp($v['alt'],' placeholder="','"').pp($v['value'],' value="','"').pp($v['name'],' name="','"').'></td>';
			break;
		case 'digit6':
			echo'<td class="bgxx'.$border.' align_center align_middle">'.$v['txt'].'<input class="digit6" '.pp($v['name'],' name="','"').pp($v['alt'],' placeholder="','"').pp($v['value'],' value="','"').' type="text"></td>';
			break;
		case '2xdigit6':
			echo'<td class="bgxx'.$border.' align_center align_middle">'.pps($v['txt']).'<input class="digit6"'.pp($v['name'],' name="','"').pp($v['alt'],' placeholder="','"').' type="text">x<input '.pp($v['name1'],' name="','"').pp($v['alt1'],' placeholder="','"').' class="digit6" type="text"></td>';
			break;	
		case 'columns':
			if(empty($v['name']))$v['name']='item_columns_{id}';
			echo '<td class="bgxx'.$border.' align_center align_middle" title="">'.pps($v['txt'],'
				Столбцы').'<input class="digit2"'.pp($v['name'],' name="','"').pp($v['alt'],' placeholder="','"').'></td>'	;
			break;
		case 'loadimg':
			echo '<td id="loadpic_{id}" class="bgxx'.$border.' align_center align_middle" title="">
			<div class="picture_upload"'.pp($v['title'],' title="','"').' alt="small" style="vertical-align:middle;display:inline-block;min-height:30px; min-width:30px; max-height:50px;overflow:hidden;margin-right:30px;">
			<img src="{pic_small|img/folderb.gif}"></div>
			<input name="pic_small_{id}" type="text" style="vertical-align:middle;display:none;">Значок
			</td>'	;
			break;
		}
	}
// table bottom
	switch ($level){
	case 0: echo '		<th class="bgxx noborder align_center"
			style="padding: 0 2px;"><input
			type="text" name="hidden_{id}" class="checkbox" title="скрыть элемент"></th>
	</tr>
</table>
';
		break;
	}
}
 
/**
 *  первого уровня - описание вкладки
 */ 
function TPL_elTable1($data,$id='',$class=''){
// table head
	echo '<table'.pp($id,' id="','"').' class="'.$class.'tahoma long ctext size11 fixed align_center align_middle">
'; 
// table cols
	foreach($data as $k=>$v){
		$w=trim($k=='logo'?'90':pps($v['width']));
		if(empty($w))
			echo'<col>
';
		else	
			echo'<col width="'.$w.'">
';
	}
echo '<tr>
';
// table body
	foreach($data as $k=>$v){
		if(isset($v['alt'])){
			if (strpos($v['alt'],','))
				$x=explode(',',$v['alt']);
			else
				$x=array($v['alt']);
			$v['alt']=$x[0];	
			for($i=1;$i<count($x);$i++){
				$v['alt'.$i]=$x[$i];
			}
		}
		if (isset($v['vname'])){
			if (strpos($v['vname'],','))
				$x=explode(',',$v['vname']);
			else
				$x=array($v['vname']);	
			$v['name']=$x[0].'_{id}';
			$v['value']='{'.$x[0].'>>html}';
			$v['vvalue']=$x[0];
			for($i=1;$i<count($x);$i++){
				$v['name'.$i]=$x[$i].'_{id}';
				$v['value'.$i]='{'.$x[$i].'}';
				$v['vvalue'.$i]=$x[$i];
			}
		}
		$border=isset($v['noborder'])?' noborder':'';
		$bgcolor=isset($v['light'])?'bgray':(isset($v['dark'])?'bgdray':'');
		switch(trim($k,'_')){
		case 'picture':
			echo '		<td class="'.pps($bgcolor,'bgdray').$border.'" >
		<table style="width: 95%;"
			class=" tahoma ctext size11 align_middle align_center"">
			<col width="50px">
			<col width="10px">
			<col width="130px">
			<col width="auto">
			<col width="auto">
				<tr><td rowspan=3>
		<div class="picture_upload" alt="both"><img alt="" data-size="120,100"
			title="просмотр маленькой картинки"
			src="{pic_small}"></div>
		</td>
					<td colspan=2 align="right">мал.</td>
					<td><input class="upload" type="text" name="pic_small_{id}"
						style="width: 180px;"
						title="вставьте маленькую картинку"></td>
					<td  width="100px">{swidth}x{sheight}</td>
				</tr>
				<tr>
					<td colspan=2 align="right">бол.</td>
					<td><input class="upload" type="text" name="pic_big_{id}" 
						style="width: 180px;"
						title="вставьте большую картинку"></td>
					<td  class="align_center">{bwidth}x{bheight}</td>
				</tr>
				<tr><td></td>
					<td colspan=3>
					<table class="fixed long">
						<col width="60%">
						<col width="20">
						<col width="40%">
						<tr>
							<td><input class="box"
								style="border: 1px solid gray; overflow: hidden; padding: 0; width: 100%;"
								name="pic_comment_{id}" type="text" value=""
								onkeydown="need_Save()" title="название картинки"></td>
							<td></td>
							<td><input name="item_url_{id}" type="text" placeholder="Ссылка"
								style="margin-bottom: 5px; border: 1px solid gray; overflow: hidden; padding: 0; width: 100%;"
								class="box nocontext long link_toolbox" title="Ссылка"></td>
						</tr>
					</table>
					</td>
				</tr>
		</table>
		</td>';
			break;
		case 'updn':
			echo '<th class="'.pps($bgcolor,'bgdray').$border.' nopage align_center" style="padding: 0 2px;"
			nowrap="">
		<table class="compact">
			<tbody>
				<tr>
					<td><input type="button" class="arrowdn"
						onclick="order(this,\'+\');"></td>
					<td><input name="item_order_{id}" type="text" value=""
						class="order size11" onkeydown="need_Save()" style="width: 15px;">
					</td>
					<td><input type="button" class="arrowup"
						onclick="order(this,\'-\');"></td>
				</tr>
			</tbody>
		</table>
		</th>';
			break;	
		case 'direct':
			echo '<td class="'.pps($bgcolor,'bgdray').$border.'">'.
				pp($v['data']).'</td>';
			break;
		case 'raw':
			echo pps($v['txt'],'<td></td>');
			break;	
		case 'select':
			echo '<td  class="'.pps($bgcolor,'bgdray').$border.'">'.pps($v['title']).'<div style="display:inline-block;text-align:left;">';
			echo '<select  class="select"'.pp($v['name'],' name="','"').pp($v['alt'],' placeholder="','"').pp($v['value'],' value="','"').'>';
			foreach($v['txt'] as $k=>$vv){
				echo'<option  value="'.$k.'"'.pp($v['vvalue'],'{','='.$k.'?? selected}').'>'.$vv.'</option>';
			}
			echo '</select></div></td>';
			break;	
		case 'article':
			echo'<td class="'.pps($bgcolor,'bgdray').$border.'" style="vertical-align: bottom;"><input type="button" title=""
			class="notepane" value="Доп.Описание{article_count}"></td>
';			
			break;
		case 'empty':
			echo '<td class="'.pps($bgcolor,'bgdray').$border.'">'.pps($v['txt']).'</td>';
			break;	
		case 'del':
			echo '<th class="'.pps($bgcolor,'bgdray').$border.' align_center" style="padding: 0 2px;"><input
			type="button" class="delrec"></th>';
			break;	
			
		case 'loadimg':
			echo '<td id="loadpic_{id}" class="'.pps($bgcolor,'bgray').$border.'" title="">
			<div class="picture_upload"'.pp($v['title'],' title="','"').' alt="small" style="vertical-align:middle;display:inline-block;min-height:30px; min-width:30px; max-height:55px;overflow:hidden;margin-right:30px;">
			<img src="{pic_small|img/folder.gif}"></div>
			<input name="pic_small_{id}" type="text" style="vertical-align:middle;display:none;">'.pps($v['txt'],'Значок').'
			</td>'	;
			break;
		case 'color':
			echo '<td'.pp($v['colspan'],' colspan="','"').' class="'.pps($bgcolor,'bgdray').$border.'">'.pps($v['txt']).'<input class="box colorbox"'.pp($v['alt'],' placeholder="','"').pp($v['value'],' value="','"').pp($v['name'],' name="','"').'></td>';
			break;
		case 'digit6':
			echo'<td class="'.pps($bgcolor,'bgdray').$border.'">'.$v['txt'].'<input class="digit6" name="'.$v['name'].pp($v['alt'],' placeholder="','"').'" type="text"></td>';
			break;
		case 'input':
			echo'<td class="'.pps($bgcolor,'bgdray').$border.'">'.pps($v['txt']).'<input class="input"'.pp($v['name'],' name="','"').pp($v['alt'],' placeholder="','"').' type="text"></td>';
			break;
		case '2xdigit6':
			echo'<td class="'.pps($bgcolor,'bgdray').$border.'">'.pps($v['txt']).'<input class="digit6"'.pp($v['name'],' name="','"').pp($v['alt'],' placeholder="','"').' type="text">x<input '.pp($v['name1'],' name="','"').pp($v['alt1'],' placeholder="','"').' class="digit6" type="text"></td>';
			break;	
		case 'logo':
			echo'<td'.pp($v['rowspan'],' rowspan="','"').' style="height:35px;" class="'.pps($bgcolor,'bgray').$border.'" >
		'.$v['txt'].'</td>';
			break;
		case 'break':
			echo'</tr><tr>';
			break;
		case 'check':
			echo '<td class="'.pps($bgcolor,'bgdray').$border.'">'.$v['txt']
			.'<input class="checkbox" type="text" name="'.$v['name'].'"'
			.pp($v['alt'],' placeholder="','"').'></td>
';
			break;	
		case 'radio':
			echo '<td class="radio '.pps($bgcolor,'bgdray').$border.'">';
			foreach($v['txt'] as $k=>$vv){
				echo'<input type="radio" name="'.$v['name'].'" value="'.$k.'"'.pp($v['vvalue'],'{','='.$k.'?? checked}').'>'.$vv;
			}
			echo '</td>';
			break;	
		case 'align':
			if(empty($v['name'])) $v['name']='item_align';
			echo'<td class="'.pps($bgcolor,'bgdray').$border.'" 
			title="выравнивание текста"><input name="'.$v['name'].'_{id}" type="text"
			value="{'.$v['name'].'}" class="align hidden"></td>';
			break;	
		}
	}
// table bottom
	echo '</tr>
</table>
';

}

function TPL_elDefault($data){
// table head
	echo '<div class="arrow "></div>
<table class="pane long align_center align_middle">
'; 
// table cols
	foreach($data as $k=>$v){
		$w=trim($k=='logo'?'90':pps($v['width']));
		if(empty($w))
			echo'<col>
';
		else	
			echo'<col width="'.$w.'">
';
	}
echo '<tr>
';
// table body
	foreach($data as $k=>$v){
		if (isset($v['vname'])){
			if (strpos($v['vname'],','))
				$x=explode(',',$v['vname']);
			else
				$x=array($v['vname']);	
			$v['name']=$x[0].'_{id}';
			$v['value']='{'.$x[0].'>>html}';
			$v['vvalue']=$x[0];
			for($i=1;$i<count($x);$i++){
				$v['name'.$i]=$x[$i].'_{id}';
				$v['value'.$i]='{'.$x[$i].'}';
				$v['vvalue'.$i]=$x[$i];
			}
		}
		
		switch(trim($k,'_')){
		case 'select':
			echo '<td class="bgxx">'.pps($v['title']).'<div style="display:inline-block;text-align:left;">';
			echo '<select class="select"'.pp($v['name'],' name="','"').pp($v['alt'],' placeholder="','"').pp($v['value'],' value="','"').'>';
			foreach($v['txt'] as $k=>$vv){
				echo'<option  value="'.$k.'"'.pp($v['vvalue'],'{','='.$k.'?? selected}').'>'.$vv.'</option>';
			}
			echo '</select></div></td>';
			break;	
		case 'loadimg':
			echo'<td class="bgxx">'.pp($v['txt'])
			.'<div class="picture_upload"'.pp($v['title'],' title="','"').' alt="small" style="vertical-align:middle;display:inline-block;min-height:30px; min-width:30px; max-height:50px;overflow:hidden;margin-right:30px;">
			<img src="{pic_small|img/folderb.gif}">
			<input '.pp($v['name'] ,' name="','"').pp($v['value'], ' value="','"')
			.' type="text" style="vertical-align:middle;display:none;"></div>
			</td>'	;
			break;
		case '2xdigit6':
			echo'<td class="bgxx">'.$v['txt'].'
			<input class="digit6"'.pp($v['name'] ,' name="','"').pp($v['value'], ' value="','"').' type="text">x<input class="digit6"'.pp($v['name1'],' name="','"').pp($v['value1'],' value="','"').' type="text"></td>';
			break;	
		case 'logo':
			echo'<td style="height:67px;" class="bgxx" >';
			if(!empty($v['txt'])){
				echo $v['txt'];
			} else if	(!empty($v['img'])) {
				echo '<img src="img/'.$v['img'].'">';
			}
			echo '</td>';
			break;
		case 'check':
			echo '<td class="bgxx">'.pps($v['txt'])
			.'<input class="checkbox"'.pp($v['value'],' value="','"').' type="text"'
			.pp($v['name'],' name="','"').'></td>
';
			break;	
		case 'color':
			echo '<td class="bgxx">'.pps($v['txt']).'<input class="box colorbox"'.pp($v['value'],' value="','"').pp($v['value'],' value="','"').''.pp($v['name'],' name="','"').'></td>';
			break;
		case 'font':
			echo '<td class="bgxx">'.pps($v['txt']).'<input class="box fontbox"'.pp($v['value'],' value="','"').pp($v['name'],' name="','"').'></td>';
			break;
		case 'size':
			echo '<td class="bgxx">'.pps($v['txt']).'<input class="box sizebox"'.pp($v['value'],' value="','"').pp($v['name'],' name="','"').'></td>';
			break;
		case 'bui':
			echo '<td class="bgxx">
			<input type="text" '.pp($v['name'],' name="','"').'class="_bold"'.pp($v['value'],' value="','"').'>
			<input type="text" '.pp($v['name1'],' name="','"').'class="_underline"'.pp($v['value1'],' value="','"').'>
			<input type="text" '.pp($v['name2'],' name="','"').'class="_italic"'.pp($v['value2'],' value="','"').'>
			</td>';
			break;		
		case 'radio':
			echo '<td class="bgxx">'.pps($v['title']).'<div style="display:inline-block;text-align:left;">';
			foreach($v['txt'] as $k=>$vv){
				echo'<input type="radio" name="'.$v['name'].'" value="'.$k.'"'.pp($v['vvalue'],'{','='.$k.'?? checked}').'>'.$vv;
			}
			echo '</div></td>';
			break;	

		case 'digit6':
			echo'<td class="bgxx">'.$v['txt'].'<input class="digit6" '.pp($v['name'],' name="','"').pp($v['value'],' value="','"').' type="text"></td>';
			break;
		case 'align':
			//if(empty($v['name'])) $v['name']='item_align';
			echo'<td class="bgxx" title="выравнивание текста"
			style="padding: 0 2px;"><input'.pp($v['name'],' name="','"').' type="text"
			'.pp($v['value'],' value="','"').' class="align hidden"></td>
';
			break;	

		}
	}
// table bottom
	echo '</tr>
</table>
';
}

function jTPL_elDefault($data,$role='default'){
// table head
	echo '<div class="arrow "></div>
<table class="pane long align_center align_middle">
'; 
// table cols
	foreach($data as $k=>$v){
		$w=trim($k=='logo'?'90':pps($v['width']));
		if(empty($w))
			echo'<col>
';
		else	
			echo'<col width="'.$w.'">
';
	}
echo '<tr>
';
// table body
	foreach($data as $k=>$v){
		if (isset($v['vname'])){
			if (strpos($v['vname'],','))
				$x=explode(',',$v['vname']);
			else
				$x=array($v['vname']);	
			$v['name']=$x[0].'_{{id}}';
			$v['value']='{{'.$x[0].'|e}}';
			$v['vvalue']=$x[0];
			for($i=1;$i<count($x);$i++){
				$v['name'.$i]=$x[$i].'_{{id}}';
				$v['value'.$i]='{{'.$x[$i].'}}';
				$v['vvalue'.$i]=$x[$i];
			}
		}
		
		switch(trim($k,'_')){
		case 'select':
			echo '<td class="bgxx">'.pps($v['title']).'<div style="display:inline-block;text-align:left;">';
			echo '<select class="select"'.pp($v['name'],' name="','"').pp($v['alt'],' placeholder="','"').pp($v['value'],' value="','"').'>';
			foreach($v['txt'] as $k=>$vv){
				echo'<option  value="'.$k.'" {% if '.$v['vvalue'].'=='.$k.'%} selected {% endif%}>'.$vv.'</option>';
			}
			echo '</select></div></td>';
			break;	
		case 'loadimg':
			echo'<td class="bgxx">'.pp($v['txt'])
			.'<div class="picture_upload"'.pp($v['title'],' title="','"').' alt="small" style="vertical-align:middle;display:inline-block;min-height:30px; min-width:30px; max-height:50px;overflow:hidden;margin-right:30px;">
			<img src="{{pic_small|default(\'img/folderb.gif\')}}">
			<input '.pp($v['name'] ,' name="','"').pp($v['value'], ' value="','"')
			.' type="text" style="vertical-align:middle;display:none;"></div>
			</td>'	;
			break;
		case '2xdigit6':
			echo'<td class="bgxx">'.$v['txt'].'
			<input class="digit6"'.pp($v['name'] ,' name="','"').pp($v['value'], ' value="','"').' type="text">x<input class="digit6"'.pp($v['name1'],' name="','"').pp($v['value1'],' value="','"').' type="text"></td>';
			break;	
		case 'logo':
			echo'<td style="height:67px;" class="bgxx" >';
			if(!empty($v['txt'])){
				echo $v['txt'];
			} else if	(!empty($v['img'])) {
				echo '<img src="img/'.$v['img'].'">';
			}
			echo '</td>';
			break;
		case 'check':
			echo '<td class="bgxx">'.pps($v['txt'])
			.'<input class="checkbox"'.pp($v['value'],' value="','"').' type="text"'
			.pp($v['name'],' name="','"').'></td>
';
			break;	
		case 'color':
			echo '<td class="bgxx">'.pps($v['txt']).'<input class="box colorbox"'.pp($v['value'],' value="','"').pp($v['value'],' value="','"').''.pp($v['name'],' name="','"').'></td>';
			break;
		case 'font':
			echo '<td class="bgxx">'.pps($v['txt']).'<input class="box fontbox"'.pp($v['value'],' value="','"').pp($v['name'],' name="','"').'></td>';
			break;
		case 'size':
			echo '<td class="bgxx">'.pps($v['txt']).'<input class="box sizebox"'.pp($v['value'],' value="','"').pp($v['name'],' name="','"').'></td>';
			break;
		case 'bui':
			echo '<td class="bgxx">
			<input type="text" '.pp($v['name'],' name="','"').'class="_bold"'.pp($v['value'],' value="','"').'>
			<input type="text" '.pp($v['name1'],' name="','"').'class="_underline"'.pp($v['value1'],' value="','"').'>
			<input type="text" '.pp($v['name2'],' name="','"').'class="_italic"'.pp($v['value2'],' value="','"').'>
			</td>';
			break;		
		case 'radio':
			echo '<td class="bgxx">'.pps($v['title']).'<div style="display:inline-block;text-align:left;">';
			foreach($v['txt'] as $k=>$vv){
				echo'<input type="radio" name="'.$v['name'].'" value="'.$k.'"'.pp($v['vvalue'],'{% if ','=='.$k.'%} checked{% endif %}').'>'.$vv;
			}
			echo '</div></td>';
			break;	

		case 'digit6':
			echo'<td class="bgxx">'.$v['txt'].'<input class="digit6" '.pp($v['name'],' name="','"').pp($v['value'],' value="','"').' type="text"></td>';
			break;
		case 'align':
			//if(empty($v['name'])) $v['name']='item_align';
			echo'<td class="bgxx" title="выравнивание текста"
			style="padding: 0 2px;"><input'.pp($v['name'],' name="','"').' type="text"
			'.pp($v['value'],' value="','"').' class="align hidden"></td>
';
			break;	

		}
	}
// table bottom
	echo '</tr>
</table>
';
}
function jTPL_elTable($level,$data){
// table head
	switch ($level){
	case 0: echo '<div class="arrow "></div>
<table class="pane fixed long ">
'; 
		break;
	}
// table cols
	foreach($data as $k=>$v){
		$w=trim(pps($v['width']));
		if(empty($w) && $k=='logo') 
			$w='{% if level%}90{% else%}66{%endif%}';
		if(empty($w))
			echo'<col>
';
		else	
			echo'<col width="'.$w.'">
';
	}
	echo '<col width="50"><tr>
';
// table body
	foreach($data as $k=>$v){
		if(isset($v['alt'])){
			if (strpos($v['alt'],','))
				$x=explode(',',$v['alt']);
			else
				$x=array($v['alt']);
			$v['alt']=$x[0];	
			for($i=1;$i<count($x);$i++){
				$v['alt'.$i]=$x[$i];
			}
		}
		if (isset($v['vname'])){
			if (strpos($v['vname'],','))
				$x=explode(',',$v['vname']);
			else
				$x=array($v['vname']);	
			$v['name']=$x[0].'_{{id}}';
			$v['value']='{{'.$x[0].'|e}}';
			$v['vvalue']=$x[0];
			for($i=1;$i<count($x);$i++){
				$v['name'.$i]=$x[$i].'_{{id}}';
				$v['value'.$i]='{{'.$x[$i].'}}';
				$v['vvalue'.$i]=$x[$i];
			}
		}
		$border=isset($v['noborder'])?' noborder':'';
		$bg_position=isset($v['noborder'])?'background-position: 0 100%;':'background-position: 100% 100%;';
		switch(trim($k,'_')){
		case 'select':
			echo '<td class="bgxx'.$border.' align_center align_middle">'.pps($v['title']).'<div style="display:inline-block;text-align:left;">';
			echo '<select class="select"'.pp($v['name'],' name="','"').pp($v['alt'],' placeholder="','"').pp($v['value'],' value="','"').'>';
			foreach($v['txt'] as $k=>$vv){
				echo'<option  value="'.$k.'"'.pp($v['vvalue'],'{% if ','=='.$k.'%}selected{%endif%}').'>'.$vv.'</option>';
			}
			echo '</select></div></td>';
			break;	
		case 'align':
			if(empty($v['name'])) 
				$v['name']='item_align_{{id}}';
			echo'<td class="bgxx'.$border.' align_center align_middle" style="vertical-align: bottom;" title="выравнивание текста"
			style="padding: 0 2px;"><input'.pp($v['name'],' name="','"').' type="text"'. 
			' class="align hidden"></td>
';	
			break;
		case 'article':
			echo'<td class="bgxx'.$border.'" style="vertical-align: bottom;"><input type="button" title=""
			class="panebutton notepane" value="'.pps($v['txt'],'Доп.Описание {{article_count}}').'"'
			.pp($v['data-number'],' data-number="','"')
			.'></td>
';			break;
		case 'haract':
			echo'<td class="bgxx'.$border.'" style="vertical-align: bottom;"><input type="button" title=""
			class="panebutton haractpane" value="Характер."></td>
';			
			break;
		case 'foto':
			echo'<td class="bgxx'.$border.'" style="vertical-align: bottom;"><input type="button" title=""
			class="panebutton fotopane" value="Доб.Фото{% if picture %}({{picture|length}}){%endif%}"></td>
';			
			break;
		case 'table':
			echo'<td class="bgxx'.$border.'" style="vertical-align: bottom;"><input type="button" title=""
			class="panebutton tablepane" value="Таблица"></td>
';			
			break;
		case 'comment':
			echo'<td class="bgxx'.$border.'" style="vertical-align: bottom;"><input type="button" title=""
			class="panebutton commentpane" value="Доб.comment{{comment_count}}"></td>
';			
			break;
		case 'links':
			echo'<td class="bgxx'.$border.'" style="vertical-align: bottom;"><input type="button" title="Добавить ссылку"
			class="panebutton linkpane" value="Доб.ссылку{{links_count}}"></td>
';			
			break;
		case 'empty':
			echo '<td class="bgxx'.$border.'">'.pps($v['txt']).'</td>';
			break;	
		case 'html':
			echo'<td class="bgxx'.$border.'" title="текст">
			<div id="'.pps($v['name'],'item_text').'_{{id}}" class="html_edit">'.pps($v['val'],'{{text_x}}').'</div>
			</td>
';			
			break;
		case 'logo':
			echo'<td class="bgxx'.$border.' align_center align_middle" style="height:67px;" title="">
				<img class="id_picture"	src="img/'.$v['img'].'"></td>';
			break;
		case 'raw':
			echo $v['data'];
			break;
		case 'direct':
			echo '<td class="bgxx'.$border.' align_middle" style="height:67px;" title="">'.
				$v['data'].'</td>';
			break;
		case 'radio':
			echo '<td class="bgxx'.$border.' align_center align_middle">'.pps($v['title']).'<div style="display:inline-block;text-align:left;">';
			foreach($v['txt'] as $k=>$vv){
				echo'<input type="radio" name="'.$v['name'].'" value="'.$k.'"'.pp($v['vvalue'],'{% if ','=='.$k.'%} checked{%endif%}').'>'.$vv;
			}
			echo '</div></td>';
			break;	
		case 'color':
			echo '<td class="bgxx'.$border.' align_center align_middle">'.pps($v['txt']).'<input class="box colorbox"'.pp($v['alt'],' placeholder="','"').pp($v['value'],' value="','"').pp($v['name'],' name="','"').'></td>';
			break;
		case '2input':
			echo'<td class="bgxx'.$border.' align_center align_middle">'
				.pps($v['txt'])
				.'<input '.pp($v['name'],' name="','"').pp($v['alt'],' placeholder="','"')
				.' class="input" type="text"><br>'
				.'<input '.pp($v['name1'],' name="','"').pp($v['alt1'],' placeholder="','"')
				.' class="input" type="text"></td>';
			break;
		case 'digit6':
			echo'<td class="bgxx'.$border.' align_center align_middle">'.$v['txt'].'<input class="digit6" '.pp($v['name'],' name="','"').pp($v['alt'],' placeholder="','"').pp($v['value'],' value="','"').' type="text"></td>';
			break;
		case '2xdigit6':
			echo'<td class="bgxx'.$border.' align_center align_middle">'.pps($v['txt']).'<input class="digit6"'.pp($v['name'],' name="','"').pp($v['alt'],' placeholder="','"').' type="text">x<input '.pp($v['name1'],' name="','"').pp($v['alt1'],' placeholder="','"').' class="digit6" type="text"></td>';
			break;	
		case 'columns':
			if(empty($v['name']))$v['name']='item_columns_{{id}}';
			echo '<td class="bgxx'.$border.' align_center align_middle" title="">'.pps($v['txt'],'
				Столбцы').'<input class="digit2"'.pp($v['name'],' name="','"').pp($v['alt'],' placeholder="','"').'></td>'	;
			break;
		case 'loadimg':
			echo '<td id="loadpic_{{id}}" class="bgxx'.$border.' align_center align_middle" title="">
			<div class="picture_upload"'.pp($v['title'],' title="','"').' alt="small" style="vertical-align:middle;display:inline-block;min-height:30px; min-width:30px; max-height:50px;overflow:hidden;margin-right:30px;">
			<img src="{{pic_small|default(\'img/folderb.gif\')}}"></div>
			<input name="pic_small_{{id}}" type="text" style="vertical-align:middle;display:none;">'
			.pps($v['txt'],'Значок')
			.'</td>'	;
			break;
		}
	}
// table bottom
	switch ($level){
	case 0: echo '		<th class="bgxx noborder align_center"
			style="padding: 0 2px;"><input
			type="text" name="hidden_{{id}}" class="checkbox" title="скрыть элемент"></th>
	</tr>
</table>
';
		break;
	}
}
 
/**
 *  первого уровня - описание вкладки
 */ 
function jTPL_elTable1($data,$id='',$class=''){
// table head
	echo '<table'.pp($id,' id="','"').' class="'.$class.'tahoma long ctext size11 fixed align_center align_middle">
'; 
// table cols
	foreach($data as $k=>$v){
		$w=trim($k=='logo'?'90':pps($v['width']));
		if(empty($w))
			echo'<col>
';
		else	
			echo'<col width="'.$w.'">
';
	}
echo '<tr>
';
// table body
	foreach($data as $k=>$v){
		if(isset($v['alt'])){
			if (strpos($v['alt'],','))
				$x=explode(',',$v['alt']);
			else
				$x=array($v['alt']);
			$v['alt']=$x[0];	
			for($i=1;$i<count($x);$i++){
				$v['alt'.$i]=$x[$i];
			}
		}
		if (isset($v['vname'])){
			if (strpos($v['vname'],','))
				$x=explode(',',$v['vname']);
			else
				$x=array($v['vname']);	
			$v['name']=$x[0].'_{{id}}';
			$v['value']='{{'.$x[0].'|e}}';
			$v['vvalue']=$x[0];
			for($i=1;$i<count($x);$i++){
				$v['name'.$i]=$x[$i].'_{{id}}';
				$v['value'.$i]='{{'.$x[$i].'}}';
				$v['vvalue'.$i]=$x[$i];
			}
		}
		$border=isset($v['noborder'])?' noborder':'';
		$bgcolor=isset($v['light'])?'bgray':(isset($v['dark'])?'bgdray':'');
		switch(trim($k,'_')){
		case 'picture':
			echo '		<td class="'.pps($bgcolor,'bgdray').$border.'" >
		<table style="width: 95%;"
			class=" tahoma ctext size11 align_middle align_center"">
			<col width="50px">
			<col width="10px">
			<col width="130px">
			<col width="auto">
			<col width="auto">
				<tr><td rowspan=3>
		<div class="picture_upload" alt="both"><img alt="" data-size="120,100"
			title="просмотр маленькой картинки"
			src="{{pic.pic_small}}"></div>
		</td>
					<td colspan=2 align="right">мал.</td>
					<td><input class="upload" type="text" name="pic_small_{{pic.id}}"
						style="width: 180px;"
						title="вставьте маленькую картинку"></td>
					<td  width="100px">{{pic.swidth}}x{{pic.sheight}}</td>
				</tr>
				<tr>
					<td colspan=2 align="right">бол.</td>
					<td><input class="upload" type="text" name="pic_big_{{pic.id}}" 
						style="width: 180px;"
						title="вставьте большую картинку"></td>
					<td  class="align_center">{{pic.bwidth}}x{{pic.bheight}}</td>
				</tr>
				<tr><td></td>
					<td colspan=3>
					<table class="fixed long">
						<col width="60%">
						<col width="20">
						<col width="40%">
						<tr>
							<td><input class="box"
								style="border: 1px solid gray; overflow: hidden; padding: 0; width: 100%;"
								name="pic_comment_{{pic.id}}" type="text" value=""
								onkeydown="need_Save()" title="название картинки"></td>
							<td></td>
							<td><input name="item_url_{{pic.id}}" type="text" placeholder="Ссылка"
								style="margin-bottom: 5px; border: 1px solid gray; overflow: hidden; padding: 0; width: 100%;"
								class="box nocontext long link_toolbox" title="Ссылка"></td>
						</tr>
					</table>
					</td>
				</tr>
		</table>
		</td>';
			break;
		case 'updn':
			echo '<th class="'.pps($bgcolor,'bgdray').$border.' nopage align_center" style="padding: 0 2px;"
			nowrap="">
		<table class="compact">
			<tbody>
				<tr>
					<td><input type="button" class="arrowdn"
						onclick="order(this,\'+\');"></td>
					<td><input name="item_order_{{id}}" type="text" value=""
						class="order size11" onkeydown="need_Save()" style="width: 15px;">
					</td>
					<td><input type="button" class="arrowup"
						onclick="order(this,\'-\');"></td>
				</tr>
			</tbody>
		</table>
		</th>';
			break;	
		case 'direct':
			echo '<td class="'.pps($bgcolor,'bgdray').$border.'">'.
				pp($v['data']).'</td>';
			break;
		case 'raw':
			echo pps($v['txt'],'<td></td>');
			break;	
		case 'select':
			echo '<td  class="'.pps($bgcolor,'bgdray').$border.'">'.pps($v['title']).'<div style="display:inline-block;text-align:left;">';
			echo '<select  class="select"'.pp($v['name'],' name="','"').pp($v['alt'],' placeholder="','"').pp($v['value'],' value="','"').'>';
			foreach($v['txt'] as $k=>$vv){
				echo'<option  value="'.$k.'"'.pp($v['vvalue'],'{% if ','='.$k.'%} selected {%endif%}').'>'.$vv.'</option>';
			}
			echo '</select></div></td>';
			break;	
		case 'article':
			echo'<td class="'.pps($bgcolor,'bgdray').$border.'" style="vertical-align: bottom;"><input type="button" title=""
			class="notepane" value="Доп.Описание{{article_count}}"></td>
';			
			break;
		case 'empty':
			echo '<td class="'.pps($bgcolor,'bgdray').$border.'">'.pps($v['txt']).'</td>';
			break;	
		case 'del':
			echo '<th class="'.pps($bgcolor,'bgdray').$border.' align_center" style="padding: 0 2px;"><input
			type="button" class="delrec"></th>';
			break;	
			
		case 'loadimg':
			echo '<td id="loadpic_{{id}}" class="'.pps($bgcolor,'bgray').$border.'" title="">
			<div class="picture_upload"'.pp($v['title'],' title="','"').' alt="small" style="vertical-align:middle;display:inline-block;min-height:30px; min-width:30px; max-height:55px;overflow:hidden;margin-right:30px;">
			<img src="{{pic_small|default(\'img/folder.gif\')}}"></div>
			<input name="pic_small_{{id}}" type="text" style="vertical-align:middle;display:none;">'.pps($v['txt'],'Значок').'
			</td>'	;
			break;
		case 'color':
			echo '<td'.pp($v['colspan'],' colspan="','"').' class="'.pps($bgcolor,'bgdray').$border.'">'.pps($v['txt']).'<input class="box colorbox"'.pp($v['alt'],' placeholder="','"').pp($v['value'],' value="','"').pp($v['name'],' name="','"').'></td>';
			break;
		case 'digit6':
			echo'<td class="'.pps($bgcolor,'bgdray').$border.'">'.$v['txt'].'<input class="digit6" name="'.$v['name'].pp($v['alt'],' placeholder="','"').'" type="text"></td>';
			break;
		case 'input':
			echo'<td class="'.pps($bgcolor,'bgdray').$border.'">'.pps($v['txt']).'<input class="input"'.pp($v['name'],' name="','"').pp($v['alt'],' placeholder="','"').' type="text"></td>';
			break;
		case '2xdigit6':
			echo'<td class="'.pps($bgcolor,'bgdray').$border.'">'.pps($v['txt']).'<input class="digit6"'.pp($v['name'],' name="','"').pp($v['alt'],' placeholder="','"').' type="text">x<input '.pp($v['name1'],' name="','"').pp($v['alt1'],' placeholder="','"').' class="digit6" type="text"></td>';
			break;	
		case 'logo':
			echo'<td'.pp($v['rowspan'],' rowspan="','"').' style="height:35px;" class="'.pps($bgcolor,'bgray').$border.'" >
		'.$v['txt'].'</td>';
			break;
		case 'break':
			echo'</tr><tr>';
			break;
		case 'check':
			echo '<td class="'.pps($bgcolor,'bgdray').$border.'">'.$v['txt']
			.'<input class="checkbox" type="text" name="'.$v['name'].'"'
			.pp($v['alt'],' placeholder="','"').'></td>
';
			break;	
		case 'radio':
			echo '<td class="radio '.pps($bgcolor,'bgdray').$border.'">';
			foreach($v['txt'] as $k=>$vv){
				echo'<input type="radio" name="'.$v['name'].'" value="'.$k.'"'.pp($v['vvalue'],'{% if ','=='.$k.'%} checked {%endif%}').'>'.$vv;
			}
			echo '</td>';
			break;	
		case 'align':
			if(empty($v['name'])) $v['name']='item_align';
			echo'<td class="'.pps($bgcolor,'bgdray').$border.'" 
			title="выравнивание текста"><input name="'.$v['name'].'_{{id}}" type="text"
			value="{{'.$v['name'].'}}" class="align hidden"></td>';
			break;	
		}
	}
// table bottom
	echo '</tr>
</table>
';

}
%>
