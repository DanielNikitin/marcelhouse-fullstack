@echo off
echo Starting Marcel House Website...
echo.

REM Check if PHP is installed
where php >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo PHP is not installed. Using HTML-only mode.
    echo.
    echo Opening website in browser...
    start "" "marcelhouse.ee\index.html"
    echo.
    echo Note: Some features may not work properly without PHP.
    echo For full functionality, please install PHP from https://windows.php.net/download/
    pause
    exit /b 0
)

REM Check if port 8080 is already in use
netstat -ano | findstr :8080 >nul
if %ERRORLEVEL% EQU 0 (
    echo Port 8080 is already in use. Trying port 8081...
    set PORT=8081
) else (
    set PORT=8080
)

REM If PHP is installed, use the PHP server
echo Opening browser to http://localhost:%PORT%
start http://localhost:%PORT%

echo.
echo Running PHP server on port %PORT%...
echo Press Ctrl+C to stop the server
echo.

php -S localhost:%PORT% -t marcelhouse.ee marcelhouse.ee/router.php

pause