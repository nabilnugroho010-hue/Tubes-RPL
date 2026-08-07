# XAMPP Credentials Helper Script for SPGFood Railway Deployment
# This script helps you extract XAMPP database credentials

Write-Host "=========================================" -ForegroundColor Cyan
Write-Host "SPGFood XAMPP Credentials Helper" -ForegroundColor Cyan
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host ""

# Check if XAMPP is installed
$xamppPath = "C:\xampp"
if (-not (Test-Path $xamppPath)) {
    Write-Host "[ERROR] XAMPP not found at C:\xampp" -ForegroundColor Red
    Write-Host "Please install XAMPP or modify the path in this script" -ForegroundColor Yellow
    exit 1
}

Write-Host "[OK] XAMPP found at C:\xampp" -ForegroundColor Green
Write-Host ""

# Check MySQL configuration
$myIniPath = "C:\xampp\mysql\bin\my.ini"
if (Test-Path $myIniPath) {
    Write-Host "[OK] MySQL configuration found" -ForegroundColor Green
    Write-Host "   Path: $myIniPath" -ForegroundColor Gray
} else {
    Write-Host "[WARNING] MySQL configuration not found at default location" -ForegroundColor Yellow
    Write-Host "   Will use default XAMPP credentials" -ForegroundColor Gray
}

Write-Host ""

# Default XAMPP credentials
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host "Default XAMPP MySQL Credentials" -ForegroundColor Cyan
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Host:     localhost" -ForegroundColor White
Write-Host "Port:     3306" -ForegroundColor White
Write-Host "Username: root" -ForegroundColor White
Write-Host "Password: (empty - leave blank)" -ForegroundColor White
Write-Host "Database: db_pemesanan" -ForegroundColor White
Write-Host ""

# Check if phpMyAdmin is accessible
$phpMyAdminUrl = "http://localhost/phpmyadmin"
Write-Host "You can verify these credentials by:" -ForegroundColor Yellow
Write-Host "1. Opening phpMyAdmin: $phpMyAdminUrl" -ForegroundColor Gray
Write-Host "2. Login with the credentials above" -ForegroundColor Gray
Write-Host "3. Check if database 'db_pemesanan' exists" -ForegroundColor Gray
Write-Host ""

# Ask to open phpMyAdmin
$openPhpMyAdmin = Read-Host "Do you want to open phpMyAdmin now? (Y/N)"
if ($openPhpMyAdmin -eq 'Y' -or $openPhpMyAdmin -eq 'y') {
    Start-Process $phpMyAdminUrl
    Write-Host "[OK] Opening phpMyAdmin in default browser..." -ForegroundColor Green
}

Write-Host ""
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host "Next Steps for Railway Deployment" -ForegroundColor Cyan
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "1. Copy these credentials for Railway:" -ForegroundColor Yellow
Write-Host "   - DB_HOST: localhost" -ForegroundColor Gray
Write-Host "   - DB_PORT: 3306" -ForegroundColor Gray
Write-Host "   - DB_NAME: db_pemesanan" -ForegroundColor Gray
Write-Host "   - DB_USER: root" -ForegroundColor Gray
Write-Host "   - DB_PASS: (empty)" -ForegroundColor Gray
Write-Host ""
Write-Host "2. Export database schema:" -ForegroundColor Yellow
Write-Host "   - Open phpMyAdmin" -ForegroundColor Gray
Write-Host "   - Select database 'db_pemesanan'" -ForegroundColor Gray
Write-Host "   - Go to Export tab" -ForegroundColor Gray
Write-Host "   - Export as SQL file" -ForegroundColor Gray
Write-Host ""
Write-Host "3. Follow Railway deployment guide:" -ForegroundColor Yellow
Write-Host "   - See: docs/RAILWAY_DEPLOYMENT_GUIDE.md" -ForegroundColor Gray
Write-Host ""

# Ask to save credentials to file
$saveToFile = Read-Host "Do you want to save these credentials to a file? (Y/N)"
if ($saveToFile -eq 'Y' -or $saveToFile -eq 'y') {
    $credentialsPath = "xampp-credentials.txt"
    $credentialsContent = @"
# XAMPP MySQL Credentials for SPGFood Railway Deployment
# Generated on: $(Get-Date -Format "yyyy-MM-dd HH:mm:ss")

# Database Configuration
DB_HOST=localhost
DB_PORT=3306
DB_NAME=db_pemesanan
DB_USER=root
DB_PASS=

# phpMyAdmin URL
PHPMYADMIN_URL=http://localhost/phpmyadmin

# Database Export
# Use file: migrations/database_improvements.sql
# Or export manually from phpMyAdmin
"@
    
    Set-Content -Path $credentialsPath -Value $credentialsContent
    Write-Host "[OK] Credentials saved to: $credentialsPath" -ForegroundColor Green
    Write-Host "[WARNING] Remember to delete this file after deployment!" -ForegroundColor Red
}

Write-Host ""
Write-Host "Press any key to exit..." -ForegroundColor Gray
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")
