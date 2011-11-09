<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//Strict" >
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{title} </title>
<style>
.pagegen {font-size:9px;float:right;}
INPUT[type="text"],textarea, .long  {border:0px; background:#f0f0f0 ;width:100%;}
INPUT[type="button"],INPUT[type="submit"] {width:auto;}
table{
	border:0px; /* border="0" */
	border-collapse:collapse; /* cellspacing="0" */
	padding:0px;
}
table td,table th{
	padding:1px; /* cellspadding="0" */
}
.green {background:#C0ffC0; }

form table {width:100%;}
{style}
</style>
</head>
<body>

<h1>{title}</h1>
  {error}
<table style="width:100%;table-layout:fixed;">
  <col width="200px"><col  class='green' width="0*"><col width="200px">
  <tr><td/><td>{descr}</td><td/></tr>
  <tr><td/><td >{newtour}{data}</td><td/></tr>
</table>

<div>{new_tour2}
</div>
<div id="debug"><pre>{debug}
</pre></div>
<span class='pagegen'>{pagegen_time}</span>
</body>
</html>
