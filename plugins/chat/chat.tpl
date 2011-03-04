<!DOCTYPE html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>{title|горячая линия}</title>
<meta http-equiv="content-type"
	content="text/html; charset=windows-1251">
<META name="description" content="{desc_words}">
<META name="keywords" lang="ru" content="{key_words}">

<script type="text/javascript" src="js/engine.pack.js"> </script>

<LINK  rel="stylesheet" type="text/css" href="css/chat.css">

<link rel="icon" href="{::index}/favicon.ico" type="image/x-icon">
<link rel="shortcut icon" href="{::index}/favicon.ico" >
<style type="text/css">
html, body {overflow:hidden;}
</style>
<script type='text/javascript'>
</script>
</head>
<body>

{data}
<!--begin:login-->
<form name="login" action="" method="POST">
Представьтесь, пожалуйста
{error}
<input name='chat_login' >
<input type='hidden' name="form" value='simpleChat:login'>
<input type='submit' name="Ok" value="Ok">
</form>
<!--end:login-->
<!--begin:admin-->
<input name='chat_login' value='admin'>
<!--end:admin-->
<!--begin:simple-->
<input name='chat_login' value='simple'>
<!--end:simple-->
<div id="debug"></div>

</body>

</html>

