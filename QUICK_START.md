# 🚀 Quick Start Guide - SPGFood Railway Deployment

## ⚡ Quick Summary

This guide provides the fastest path to deploy SPGFood to Railway.

---

## 📋 Before You Start

### What You Need:
- ✅ GitHub account
- ✅ Railway account (https://railway.app/)
- ✅ XAMPP installed locally
- ✅ Project pushed to GitHub: `https://github.com/nabilnugroho010-hue/Tubes-RPL`

---

## 🔑 Step 1: Get XAMPP Credentials (5 minutes)

### Option A: Use Helper Script (Windows)
```powershell
# Run PowerShell as Administrator
cd C:\xampp\htdocs\pemesanan\scripts
.\get-xampp-credentials.ps1
```

### Option B: Manual Method
1. Open phpMyAdmin: `http://localhost/phpmyadmin`
2. Login with username: `root`, password: (empty)
3. Verify database `db_pemesanan` exists

**XAMPP Credentials:**
```
Host: localhost
Port: 3306
User: root
Pass: (empty)
DB: db_pemesanan
```

---

## 🚀 Step 2: Setup Railway (10 minutes)

### 2.1 Create Project
1. Login to https://railway.app/
2. Click "New Project"
3. Select "Deploy from GitHub repo"
4. Choose repository: `Tubes-RPL`
5. Click "Deploy Now"

### 2.2 Add MySQL Database
1. In Railway project, click "+ New Service"
2. Select "Database" → "MySQL"
3. Wait for MySQL to be created

### 2.3 Get Railway MySQL Credentials
1. Click the MySQL service
2. Go to "Variables" tab
3. Copy these values:
   - `MYSQLHOST`
   - `MYSQLPORT`
   - `MYSQLUSER`
   - `MYSQLPASSWORD`
   - `MYSQLDATABASE`

---

## ⚙️ Step 3: Configure Environment Variables (5 minutes)

1. Go to project settings (gear icon)
2. Click "Variables" tab
3. Add these variables:

```
DB_HOST     = [MYSQLHOST from Railway]
DB_PORT     = [MYSQLPORT from Railway]
DB_NAME     = [MYSQLDATABASE from Railway]
DB_USER     = [MYSQLUSER from Railway]
DB_PASS     = [MYSQLPASSWORD from Railway]

APP_URL     = [Your Railway app URL]
APP_ENV     = production
APP_DEBUG   = false

ADMIN_USERNAME = admin
ADMIN_PASSWORD = [Secure password, min 8 chars]

UPLOAD_MAX_SIZE = 5242880
UPLOAD_ALLOWED_TYPES = jpeg,jpg,png

APP_TIMEZONE = Asia/Jakarta
```

4. Click "Save Changes"

---

## 🗄️ Step 4: Import Database Schema (5 minutes)

### Via Railway MySQL Interface:
1. Click MySQL service
2. Click "Connect" or "Open in Browser"
3. Login with Railway credentials
4. Open SQL tab
5. Copy content from: `migrations/database_improvements.sql`
6. Paste and execute

### Verify Tables:
```sql
SHOW TABLES;
```

Should show:
- `data_pesanan`
- `data_menu`
- `rincian_pesanan`
- `data_pembayaran`

---

## 🚢 Step 5: Deploy (2 minutes)

1. Go to "Deployments" tab
2. Railway will auto-deploy
3. Wait 1-2 minutes
4. Check status: "Success"

---

## ✅ Step 6: Test Deployment (5 minutes)

### Test Admin Panel:
1. Open: `https://[your-app].up.railway.app/login.php`
2. Login with credentials from environment variables
3. Verify dashboard loads

### Test Customer Panel:
1. Open: `https://[your-app].up.railway.app/pemesanan_pelanggan/pesan_pelanggan.php`
2. Test menu categorization
3. Test order placement
4. Test status tracking

---

## 🎯 Total Time: ~30 minutes

---

## 📁 File Structure for Deployment

```
pemesanan/
├── composer.json              ✅ Required for Railway
├── Procfile                   ✅ Required for Railway
├── .env.example               ✅ Environment template
├── .htaccess                  ✅ Apache configuration
├── config/
│   └── database.php           ✅ Database config
├── migrations/
│   └── database_improvements.sql  ✅ Database schema
├── scripts/
│   ├── railway-setup.sh       ✅ Setup helper (Linux/Mac)
│   ├── railway-setup.bat      ✅ Setup helper (Windows)
│   └── get-xampp-credentials.ps1  ✅ Credentials helper
└── docs/
    └── RAILWAY_DEPLOYMENT_GUIDE.md  ✅ Detailed guide
```

---

## 🔧 Troubleshooting

### Build Failed:
- Check deployment logs in Railway
- Verify `composer.json` is valid
- Ensure PHP version is 7.4+

### Database Connection Failed:
- Verify environment variables in Railway
- Test connection via Railway MySQL interface
- Check MySQL service is running

### File Upload Failed:
- Setup volume for uploads in Railway
- Ensure `gambar/bukti` directory exists
- Check directory permissions

---

## 📚 Additional Resources

- **Detailed Guide:** `docs/RAILWAY_DEPLOYMENT_GUIDE.md`
- **Full Documentation:** `README.md`
- **Railway Docs:** https://docs.railway.app/
- **GitHub Issues:** https://github.com/nabilnugroho010-hue/Tubes-RPL/issues

---

## ✨ Deployment Checklist

- [ ] XAMPP credentials obtained
- [ ] Railway project created
- [ ] MySQL database added
- [ ] Environment variables configured
- [ ] Database schema imported
- [ ] Application deployed
- [ ] Admin panel tested
- [ ] Customer panel tested
- [ ] Realtime features tested

---

**Ready to deploy? Start with Step 1!** 🚀

Generated with [Devin](https://devin.ai)
