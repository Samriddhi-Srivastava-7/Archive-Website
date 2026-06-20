@echo off

set DB_NAME=archive_portal
set DB_USER=root

set BACKUP_DIR=C:\xampp\htdocs\archive-main\backups

if not exist "%BACKUP_DIR%" (
    mkdir "%BACKUP_DIR%"
)

for /f "tokens=2 delims==" %%I in ('wmic os get localdatetime /value') do set dt=%%I

set YYYY=%dt:~0,4%
set MM=%dt:~4,2%
set DD=%dt:~6,2%
set HH=%dt:~8,2%
set MIN=%dt:~10,2%

"C:\xampp\mysql\bin\mysqldump.exe" -u %DB_USER% %DB_NAME% > "%BACKUP_DIR%\backup_%YYYY%-%MM%-%DD%_%HH%-%MIN%.sql"

echo.
echo Database Backup Completed Successfully.
pause