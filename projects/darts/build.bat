::@echo off
call ..\env.bat

if exist z:\home\localhost\www\darts goto nocreate
if not exist z:\home goto fin
if not exist z:\home\localhost\www md z:\home\localhost\www\darts

:nocreate

%php_exe% -q  ../preprocessor/preprocessor.php /Dtarget_dir=darts /Dtarget_host=localhost /Dtarget=debug /Ddst=z:/home/localhost/www/darts ../darts/config.xml
:create

::@echo press Ctrl-Break to stop it
::pause
::goto again