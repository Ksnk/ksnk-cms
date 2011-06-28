::@echo off
call ..\env.bat
:again
set TRAV=z:\home\trav.me\www\test\
if exist %TRAV% goto nxt
set TRAV=z:\home\trav.me\www\test\
:nxt
%php_exe% -q  ../preprocessor/preprocessor.php /Ddst=%TRAV% config.xml

::@echo press Ctrl-Break to stop it
::pause
::goto again