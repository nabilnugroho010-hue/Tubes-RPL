@echo off
REM Railway Deployment Setup Script for SPGFood (Windows)
REM This script helps prepare the project for Railway deployment

echo =========================================
echo SPGFood Railway Deployment Setup
echo =========================================
echo.

REM Check if .env exists
if not exist .env (
    echo [+] Creating .env file from .env.example
    copy .env.example .env
    echo [+] Please edit .env with your XAMPP credentials for local development
) else (
    echo [+] .env file already exists
)

REM Check if composer.json exists
if not exist composer.json (
    echo [X] composer.json not found
    exit /b 1
) else (
    echo [+] composer.json found
)

REM Check if Procfile exists
if not exist Procfile (
    echo [X] Procfile not found
    exit /b 1
) else (
    echo [+] Procfile found
)

REM Check if .htaccess exists
if not exist .htaccess (
    echo [X] .htaccess not found
    exit /b 1
) else (
    echo [+] .htaccess found
)

REM Check if config directory exists
if not exist config (
    echo [X] config directory not found
    exit /b 1
) else (
    echo [+] config directory found
)

REM Check if database.php exists
if not exist config\database.php (
    echo [X] config\database.php not found
    exit /b 1
) else (
    echo [+] config\database.php found
)

REM Check if uploads directory exists
if not exist gambar\bukti (
    echo [+] Creating uploads directory
    mkdir gambar\bukti
) else (
    echo [+] uploads directory already exists
)

echo.
echo =========================================
echo [+] Railway Setup Check Complete
echo =========================================
echo.
echo Next Steps:
echo 1. Push changes to GitHub
echo 2. Login to Railway.app
echo 3. Create new project from GitHub repo
echo 4. Add MySQL database service
echo 5. Configure environment variables
echo 6. Import database schema
echo 7. Deploy application
echo.
echo For detailed instructions, see README.md
echo.

pause
