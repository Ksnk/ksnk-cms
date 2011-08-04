::@echo off
call ..\env.bat
:again
%php_exe% -q  ../preprocessor/preprocessor.php /Ddst=build config_js.xml

::@echo press Ctrl-Break to stop it
::pause
::goto again