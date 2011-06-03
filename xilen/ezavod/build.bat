::@echo off
call ..\env.bat
:again
if exist z:\home\ezavod.me\www\test\ goto work
:home
%php_exe% -q  ../preprocessor/preprocessor.php /Ddst=/xilen/ezavod/test/ezavod config.xml
goto fin
:work
%php_exe% -q  ../preprocessor/preprocessor.php /Ddst=z:/home/ezavod.me/www/test/ezavod config.xml
::%php_exe% -q  ../preprocessor/preprocessor.php /Ddst=/tmp/tmp config.xml

:fin
::@echo press Ctrl-Break to stop it
::pause
::goto again