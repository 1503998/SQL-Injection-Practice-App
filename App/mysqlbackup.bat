
:: Name of the database user with rights to all tables
set dbuser=root

:: Password for the database user
set dbpass=hacklab2019

:: MySQL EXE Path
set mysqldumpexe="F:\Year-4\Tristan-Price-1503998-SQL-Injection-App\core\mysql\bin\mysqldump.exe"

:: Path to data folder which may differ from install dir
set datafldr="F:\Year-4\Tristan-Price-1503998-SQL-Injection-App\core\mysql\data"


:: Error log path
set backupfldr="F:\Year-4\Tristan-Price-1503998-SQL-Injection-App\sql"


:: GO FORTH AND BACKUP EVERYTHING!

:: Switch to the data directory to enumerate the folders
pushd %datafldr%

echo "Pass each name to mysqldump.exe and output an individual .sql file for each"

:: Thanks to Radek Dolezel for adding the support for dashes in the db name
:: Added --routines thanks for the suggestion Angel

:: turn on if you are debugging
@echo off

FOR /D %%F IN (*) DO (

IF NOT [%%F]==[performance_schema] (
SET %%F=!%%F:@002d=-!
%mysqldumpexe% --user=%dbuser% --password=%dbpass% --databases %%F --routines > "%%F.sql"
) ELSE (
echo Skipping DB backup for performance_schema
)
)

echo "done"

::return to the main script dir on end
popd