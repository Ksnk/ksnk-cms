<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Serge
 * Date: 12.11.11
 * Time: 19:33
 * To change this template use File | Settings | File Templates.
 */
define('mess_your_name',0);
define('mess_sorry_you_have_no_right',1);
define('mess_page_was_build',2);
define('mess_hello_user',3);
define('mess_wrong_password',4);
define('mess_page_not_found',5);
define('mess_query',6);

 SUPER::setlang(array(
mess_your_name=>
     array('en'=>'Your are "%s"','ru'=>'Вы авторизованы как &laquo;%s&raquo;'),
mess_sorry_you_have_no_right=>
    array('en'=>"Sorry!! you have no right to see this!",'ru'=>"У вас недостаточно прав для просмотра страницы!"),
mess_page_was_build=>
    array('en'=>"\n<!-- (%s) page was built for %f sec -->",'ru'=>	 "<!-- (%s) страница генерировалась %f сек  -->"),
mess_hello_user=>
    array('en'=>'Hello "%s"',
          'ru'=>'Вы авторизованы<br>как &laquo;<a href="?do=logout" onclick="if(!confirm(\'Хотите закончить сеанс?\')) return false;">%s</a>&raquo;'),

mess_wrong_password=>
    array('en'=>'wrong password','ru'=>'Неверный пароль'),
mess_page_not_found=>
    array('en'=>'page not found, sorry!','ru'=>'Страница не найдена ;-('),
mess_query=>
    array('en'=>'quer|y|ies|ies|ies','ru'=>"запрос||а|ов"),
));
