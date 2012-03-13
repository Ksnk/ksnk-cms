::@echo off
call ..\env.bat

if exist z:\home\potanin.me\www goto nocreate
if not exist z:\home goto fin
if not exist z:\home\potanin.me md z:\home\potanin.me
if not exist z:\home\potanin.me\wwww md z:\home\potanin.me\www

:nocreate

%PHPBIN% -q  ../preprocessor/preprocessor.php /Ddst=z:/home/potanin.me/www ../potanin/config.xml
:create

::@echo press Ctrl-Break to stop it
::pause
::goto again