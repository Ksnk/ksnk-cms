::@echo off
call ..\env.bat

if not exist z:\home\potanin.me\www goto fin

%php_exe% -q  ../preprocessor/preprocessor.php /Ddst=z:/home/potanin.me/www/potanin config.xml
:fin

::@echo press Ctrl-Break to stop it
::pause
::goto again