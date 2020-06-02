@ECHO OFF
Rem SQL Injections PHP/MySQL applications. 
Rem Tristan Price / 1503998
Rem Started:12/11/19  Finished:

CLS

Rem make folder tmp and db_backup_restore if they dont exist
if not exist "tmp" mkdir tmp
if not exist "db_backup_restore" mkdir db_backup_restore
if not exist %cd%\core\apache2\logs mkdir %cd%\core\apache2\logs

Rem if webid then alter a file.  www\includes\config.inc.php must have path the www.
set mypath=%cd%\phpapps\webid\includes\config.inc.php
set mypath2=%cd%\www\
set mypath2=%mypath2:\=\\%

echo ^<^?php $DbHost = ^"localhost^"; $DbDatabase = ^"webid^"; $DbUser	 = ^"root^"; $DbPassword = ^"hacklab2019^"; $DBPrefix	= ^"webid_^"; $main_path	= ^"%mypath2%"; $MD5_PREFIX = "7548744d014f15c0add4a958f338053c^"; ^?^> >%mypath%

ECHO ...............................................................................
ECHO ** Original Application created by Dr Colin McLean // SQL Injection vulnerable Applications - By Tristan Price 1503998.
ECHO ...............................................................................
ECHO This batch file will install a SQL injection vulnerable PHP/MySQL app under UniServerZ.
ECHO (1) The apps can be exploited manually (e.g. by walking through the app and exploiting).
ECHO (2) They can be exploited using exploits that are available on sites such as exploit-db.com and github.
ECHO You could also write your own exploit using the provided Apps.  
ECHO Please use this app on a virtual machine set up for yours and your systems safety.
ECHO This is for educational and teaching purposes only.
ECHO Not to be used by anyone not trained or studying in cyber security.
ECHO ...............................................................................
ECHO.
ECHO a - Lepton 2.2.2 
ECHO b - Movie Guide 2.0 
ECHO c - Quick and Dity Blog 0.4
ECHO d - Hong CMS Master 3.0  
ECHO e - Insanely Simple Blog 0.4/0.5
ECHO f - PHP Director Game Edition
ECHO g - Alegro 1.2.1
ECHO h - Devana (Game) 1.6.6
ECHO i - EXIT
ECHO.

CHOICE /C abcdefghijklx /N /M "Choose the PHP app that you want to install under UniServerZ or press x to EXIT."
IF ERRORLEVEL 1 SET M=Lepton & SET d=null
IF ERRORLEVEL 2 SET M=movieguide & SET d=null
IF ERRORLEVEL 3 SET M=qdblog & SET d=null
IF ERRORLEVEL 4 SET M=HongCMS-master & SET d=nul
IF ERRORLEVEL 5 SET M=insanelysimpleblog & SET d=null
IF ERRORLEVEL 6 SET M=phpdirectorgameedition & SET d=null
IF ERRORLEVEL 7 SET M=AlegroCart_1.2.1 & SET d=null
IF ERRORLEVEL 8 SET M=devana & SET d=null
IF ERRORLEVEL 9 GOTO:EOF



Rem Delete everything in the www folder.
echo ***** Deleting and re-creating the www folder.
rmdir www /S /Q
mkdir www

Rem now copy the required folder contents to the www folder.
echo ***** Copying files to www folder. This may take a minute...
xcopy .\phpapps\%M% .\www\  /s /e /q

echo ***** Ready to run Unicontroller 
Pause

Rem now start Unicontroller if not already started. 
tasklist /nh /fi "imagename eq UniController.exe" | find /i "UniController.exe" > nul || (echo Running Unicontroller - it should start Apache and MySQL automatically - unless another app is running on port 80 && start .\UniController.exe pc_win_start)

Rem Small delay to wait for services to start.
PING localhost -n 5 >NUL

echo ***** Ready to import the MySQL database.
Pause
Rem Import the database if needed. Not important that the password is here!
if NOT %d% == null ( 
echo ***** Importing MySQL database....
.\core\mysql\bin\mysql.exe -uroot %d% -phacklab2019 < sql\%d%.sql
)

echo ***** You can now browse to 127.0.0.1 to see the vulnerable application.
pause
