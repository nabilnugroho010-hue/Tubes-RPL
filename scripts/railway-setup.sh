#!/bin/bash

# Railway Deployment Setup Script for SPGFood
# This script helps prepare the project for Railway deployment

echo "========================================="
echo "SPGFood Railway Deployment Setup"
echo "========================================="
echo ""

# Check if .env exists
if [ ! -f .env ]; then
    echo "✓ Creating .env file from .env.example"
    cp .env.example .env
    echo "✓ Please edit .env with your XAMPP credentials for local development"
else
    echo "✓ .env file already exists"
fi

# Check if composer.json exists
if [ ! -f composer.json ]; then
    echo "✗ composer.json not found"
    exit 1
else
    echo "✓ composer.json found"
fi

# Check if Procfile exists
if [ ! -f Procfile ]; then
    echo "✗ Procfile not found"
    exit 1
else
    echo "✓ Procfile found"
fi

# Check if .htaccess exists
if [ ! -f .htaccess ]; then
    echo "✗ .htaccess not found"
    exit 1
else
    echo "✓ .htaccess found"
fi

# Check if config directory exists
if [ ! -d config ]; then
    echo "✗ config directory not found"
    exit 1
else
    echo "✓ config directory found"
fi

# Check if database.php exists
if [ ! -f config/database.php ]; then
    echo "✗ config/database.php not found"
    exit 1
else
    echo "✓ config/database.php found"
fi

# Check if uploads directory exists
if [ ! -d gambar/bukti ]; then
    echo "✓ Creating uploads directory"
    mkdir -p gambar/bukti
    chmod 755 gambar/bukti
else
    echo "✓ uploads directory already exists"
fi

echo ""
echo "========================================="
echo "✓ Railway Setup Check Complete"
echo "========================================="
echo ""
echo "Next Steps:"
echo "1. Push changes to GitHub"
echo "2. Login to Railway.app"
echo "3. Create new project from GitHub repo"
echo "4. Add MySQL database service"
echo "5. Configure environment variables"
echo "6. Import database schema"
echo "7. Deploy application"
echo ""
echo "For detailed instructions, see README.md"
echo ""
