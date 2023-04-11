:: change current directory to the batch file directory
cd /d %~dp0

:: get the current directory
set current_dir=%cd%

schtasks /create /xml "AutoBackupDB.xml" /tn "DB_AutoBackup"
echo Task added successfully. Press any key to exit.
pause > nul