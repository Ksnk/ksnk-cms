
<!--  ////////////////// плагин Q&A /////////////////////// -->
<!--begin:qathanks-->
<div style="padding-top:60px;" class="tahoma ctext link">
<p>Спасибо Вам за проявленный интерес. Через некоторое время администрация сайта
обязательно рассмотрит и ответит на Ваш вопрос.
</p><p>Нажмите на <a href="{url}">ссылку</a> для возврата.
</p>
<p class="red size11">{result}</p>
</div>
<!--end:qathanks-->

<!--begin:qaform-->
<form action="" method="POST" name="qaform">
<style>
.table td.pad10 {
 padding-top:10px;
 padding-bottom:10px;
}
</style>
<div class="align_center" style="padding-top:60px;" >
<table class="table tahoma ctext" style="width:80%;height:1px;"><col align="right">
<tr>
<td colspan=2>
 <p class="red" style="font-size:16px;">{error}</p></td>
</tr>
<!--begin:qa::havetheme-->
<tr class="even" style="border: 1px solid #d67022; border-width:1px 0 1px 0;">
 <td class="pad10 text">Тема вопроса:</td><td  class="pad10 text">
 <select style="width:310px;" type="text" name="theme">
<!--begin:qa::getOptions-->
<option value="{id}">{name}</option>
<!--end:qa::getOptions-->
 </select></td>
</tr>
<!--end:qa::havetheme-->
<tr class="even" style="border: 1px solid #d67022; border-width:1px 0 1px 0;">
 <td class="pad10 text">Ваше имя:</td><td  class="pad10 text"><input class="input" style="width:310px;" type="text" name="name"></td>
</tr><tr class="odd">
<td class="pad10 text">Ваш адрес:</td><td class="pad10 text"><input  class="input" style="width:310px;" type="text" name="address"></td>
</tr><tr class="even" style="border: 1px solid #d67022; border-width:1px 0;">
<td class="pad10 text">Ваш вопрос:</td><td class="pad10 text"><textarea  class="input" style="width:310px;height:200px;" name="text"></textarea></td>
</tr>
<tr class="odd">
<td class="pad10 text" >Введите номер, изображенный на картинке111:<br></td><td class="text" >
<table><tr><td style=" vertical-align:middle"><input  class="input" type="text" name="captcha" /></td><td><img src="captcha.php" style="float:right;" alt="" /></td></tr></table></td>
</tr>
<tr  class="even" style="border: 1px solid #d67022; border-width:1px 0;">
<td class="text pad10" ></td><td class="pad10 text align_left" ><div style="width:100px;" class="button"><input type="submit" value="Отправить"></div></td>
</tr>
</table>
</div>
</form>
<!--end:qaform-->

<!--begin:qa_list-->
<div style="margin:0px; float:none">
		<table style="margin:0px; min-width: 630px" width="100%">
        		<col width="130px"><col width="auto">
			<tr><td colspan="2">
				<div style="padding:20px 40px 20px 0px; float:none">{pages}</div>
			</td></tr>
<!--begin:list-->
			<tr>
					<td style="vertical-align:bottom; padding-bottom:2px;">
							<div style="float:none; padding-left:30px; padding-bottom:5px"><img src="img/quest.gif" alt=""></div>
								<a class="yellow menu hand" href="writeus">Задать вопрос</a>
  				 	</td>
					<td class="size12 ctext hand" style="padding:24px 0px;"><p>{question}</p></td>
			</tr>
			<tr>
            		<td></td>
					<td>		
						<table onclick="$(this).parent().parent().toggle().next().toggle();" class="long hand" width="100%"  style="height:30px; background:url(img/answerbg.gif) bottom repeat-x;">
                        	<col width="auto"><col width="245px">
                            <tr><td style="padding-bottom:10px; padding-right:5px;">
								<b class="size11 menu">{user}</b>
							</td><td class="hand" style="background:url(img/openanswer.gif) no-repeat bottom;"> 
							</td></tr>
                        </table>
					</td>
			</tr>
			<tr style="display:none;"><td></td><td onclick="$(this).parent().toggle().prev().toggle();" style="padding:10px 0 10px 0px;">
				<b class="size11 menu">{user}</b>
                
                <div style="padding:24px 0 0 0px; float:none">
						<div style="color:#fdee9a; padding-left:20px">{answer}</div>

					<table class="long" width="100%" style="margin-top:20px;height:30px; background:url(img/answerbg.gif) bottom repeat-x"><col width="auto"><col width="245px">
						<tr><td style="white-space:nowrap; padding-right:5px; color:#fdee9a; padding-left:20px">
						<b class="size12 yellow">Ваш Григорий Демидовцев</b>
						</td><td class="hand" style="background:url(img/closeanswer.gif) right bottom no-repeat; min-width:220px;">
						</td></tr>
                    </table>
                 </div>   
                    
			</td></tr>
<!--end:list-->
			</table>
				<div style="padding:20px 40px 20px 0px; float:none">{pages}</div>
</div>

<div id="qa_container" ></div>

<script type="text/javascript">
var $x=null;
function do_load_qa(){
	if($x)
		$('#qa_container').slideToggle();
	else
	$.getJSON("writeus?ajax=json",
		function(data){
			$('#qa_container').hide().html(data.data).slideToggle();
			setTimeout(function(){
				if($x=document.forms['callback'])
				$x.onsubmit=function(){
					$.post('writeus?ajax=json',$($x).serialize(),function(data){
						if(data.debug)
							alert(data.debug);
						
						if (data.error) 
							alert(data.error.replace('<br>','\n\r'));
						else if (data.data){
							$("#qa_container").html(data.data);
							$x = null;
							window.location.reload();
						}
					},'json');
					return false;
				}
			},500)
        });
    return false;
}
</script>

<!--end:qa_list-->


