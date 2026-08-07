# 🚀 Railway Deployment Manual Guide

## 📋 Prerequisites Checklist

Sebelum memulai deployment ke Railway, pastikan Anda memiliki:

- [ ] GitHub Account
- [ ] Railway Account (https://railway.app/)
- [ ] XAMPP installed di local
- [ ] Project sudah di-push ke GitHub
- [ ] Database credentials dari XAMPP

---

## 🔑 Step 1: Ambil Database Credentials dari XAMPP

### Cara Mengambil Credentials XAMPP

#### 1. Buka phpMyAdmin
```
http://localhost/phpmyadmin
```

#### 2. Login ke phpMyAdmin
- **Username:** `root` (default XAMPP)
- **Password:** Kosong (default XAMPP) atau password yang Anda set

#### 3. Catat Database Information

Buka tab **Variables** atau **Server Info** untuk melihat konfigurasi:

**Default XAMPP Configuration:**
```
Server: localhost
Port: 3306
Username: root
Password: (kosong)
Database Name: db_pemesanan
```

#### 4. Export Database Schema

1. Pilih database `db_pemesanan`
2. Klik tab **Export**
3. Pilih format **SQL**
4. Klik **Go** untuk download
5. Simpan file sebagai `database_schema.sql`

**Alternative:** Gunakan file yang sudah ada:
```
migrations/database_improvements.sql
```

#### 5. Cek Database Tables

Pastikan tabel berikut ada di database:
- `data_pesanan`
- `data_menu`
- `rincian_pesanan`
- `data_pembayaran`

---

## 🚀 Step 2: Setup Project di Railway

### 2.1 Login ke Railway

1. Buka https://railway.app/
2. Login dengan GitHub account
3. Klik **"New Project"**

### 2.2 Deploy dari GitHub

1. Pilih **"Deploy from GitHub repo"**
2. Pilih repository: `Tubes-RPL`
3. Pilih branch: `main`
4. Klik **"Deploy Now"**

Railway akan otomatis:
- Clone repository
- Detect PHP project
- Setup build environment

---

## 🗄️ Step 3: Setup MySQL Database di Railway

### 3.1 Add MySQL Service

1. Di dashboard Railway project, klik **"+ New Service"**
2. Pilih **"Database"**
3. Pilih **"MySQL"**
4. Railway akan membuat database baru

### 3.2 Get Database Credentials dari Railway

1. Klik service MySQL yang baru dibuat
2. Masuk ke tab **"Variables"**
3. Copy credentials berikut:

**Railway MySQL Credentials:**
```
MYSQLHOST     = xxx.railway.app
MYSQLPORT     = 3306
MYSQLUSER     = xxx
MYSQLPASSWORD = xxx
MYSQLDATABASE = xxx
```

**Simpan credentials ini untuk step selanjutnya!**

### 3.3 Import Database Schema ke Railway

#### Option A: Via Railway MySQL Interface

1. Klik service MySQL
2. Klik **"Connect"** atau **"Open in Browser"**
3. Login dengan credentials dari Railway
4. Buka tab **SQL**
5. Copy content dari file `migrations/database_improvements.sql`
6. Paste ke SQL editor
7. Klik **"Go"** atau **"Execute"**

#### Option B: Via Command Line

```bash
# Install MySQL client jika belum ada
# Windows: Download dari https://dev.mysql.com/downloads/mysql/
# Mac: brew install mysql-client
# Linux: sudo apt-get install mysql-client

# Connect ke Railway MySQL
mysql -h MYSQLHOST -P MYSQLPORT -u MYSQLUSER -pMYSQLPASSWORD MYSQLDATABASE

# Import schema
source migrations/database_improvements.sql

# Verify tables
SHOW TABLES;
```

#### Option C: Via phpMyAdmin di Local (Export → Import)

1. Export dari XAMPP:
   - Buka phpMyAdmin XAMPP
   - Select database `db_pemesanan`
   - Export semua tabel ke SQL file

2. Import ke Railway:
   - Buka Railway MySQL interface
   - Import SQL file yang tadi di-export

---

## ⚙️ Step 4: Configure Environment Variables di Railway

### 4.1 Buka Project Settings

1. Klik project settings (gear icon di pojok kanan atas)
2. Masuk ke tab **"Variables"**

### 4.2 Add Environment Variables

Tambahkan variables berikut satu per satu:

#### Database Configuration
```
DB_HOST     = [MYSQLHOST dari Railway]
DB_PORT     = [MYSQLPORT dari Railway]
DB_NAME     = [MYSQLDATABASE dari Railway]
DB_USER     = [MYSQLUSER dari Railway]
DB_PASS     = [MYSQLPASSWORD dari Railway]
```

#### Application Configuration
```
APP_URL     = [URL Railway deployment Anda nanti]
APP_ENV     = production
APP_DEBUG   = false
```

#### Admin Configuration
```
ADMIN_USERNAME = admin
ADMIN_PASSWORD = [Password admin yang aman, minimal 8 karakter]
```

#### Upload Configuration
```
UPLOAD_MAX_SIZE      = 5242880
UPLOAD_ALLOWED_TYPES = jpeg,jpg,png
```

#### Timezone Configuration
```
APP_TIMEZONE = Asia/Jakarta
```

### 4.3 Contoh Lengkap Environment Variables

```
DB_HOST               = mysql.railway.app
DB_PORT               = 3306
DB_NAME               = railway
DB_USER               = root
DB_PASS               = AbCdEf123456

APP_URL               = https://spgfood-production.up.railway.app
APP_ENV               = production
APP_DEBUG             = false

ADMIN_USERNAME        = admin
ADMIN_PASSWORD        = SecurePassword123!

UPLOAD_MAX_SIZE       = 5242880
UPLOAD_ALLOWED_TYPES  = jpeg,jpg,png

APP_TIMEZONE          = Asia/Jakarta
```

### 4.4 Save Variables

Klik **"Save Changes"** setelah semua variables ditambahkan.

---

## 🚢 Step 5: Deploy Application

### 5.1 Monitor Deployment

1. Kembali ke tab **"Deployments"**
2. Railway akan otomatis mendeteksi perubahan
3. Deployment akan berjalan otomatis
4. Tunggu 1-2 menit untuk proses build

### 5.2 Cek Deployment Status

- **Building:** Railway sedang build aplikasi
- **Deploying:** Railway sedang deploy
- **Success:** Deployment berhasil
- **Failed:** Deployment gagal (cek logs)

### 5.3 View Deployment Logs

Jika deployment gagal:
1. Klik deployment yang gagal
2. Buka tab **"Logs"**
3. Cari error message
4. Fix error dan push perubahan ke GitHub

---

## 📁 Step 6: Setup File Upload Directory

### 6.1 Problem dengan File Upload di Railway

Railway menggunakan filesystem ephemeral (temporary), artinya:
- File yang di-upload akan hilang setelah redeploy
- Tidak ada persistent storage secara default

### 6.2 Solution: Add Volume

1. Klik project settings
2. Scroll ke **"Volumes"**
3. Klik **"+ New Volume"**
4. Configure:
   ```
   Name: uploads
   Mount Path: /app/gambar/bukti
   ```
5. Klik **"Create"**

### 6.3 Alternative: Gunakan Railway Storage

Untuk production, lebih baik gunakan Railway Storage:

1. Install Railway CLI:
```bash
npm install -g @railway/cli
```

2. Login:
```bash
railway login
```

3. Link project:
```bash
railway link
```

4. Create storage:
```bash
railway volume create
```

5. Update code untuk menggunakan Railway Storage API

---

## ✅ Step 7: Verify Deployment

### 7.1 Buka Application URL

Railway akan memberikan URL seperti:
```
https://spgfood-production.up.railway.app
```

### 7.2 Test Admin Panel

1. Buka: `https://[your-app].up.railway.app/login.php`
2. Login dengan credentials dari environment variables:
   - Username: `admin`
   - Password: `[ADMIN_PASSWORD dari Railway]`
3. Verify:
   - Dashboard muncul
   - Menu management berfungsi
   - Order management berfungsi

### 7.3 Test Customer Panel

1. Buka: `https://[your-app].up.railway.app/pemesanan_pelanggan/pesan_pelanggan.php`
2. Test features:
   - Menu categorization (Makanan, Minuman, Camilan)
   - Add to cart
   - Place order
   - Payment upload
   - Status tracking

### 7.4 Test Realtime Features

1. Buat order baru sebagai customer
2. Login sebagai admin
3. Update status order
4. Cek status sebagai customer
5. Verify polling berfungsi

---

## 🔧 Troubleshooting

### Database Connection Failed

**Error:** `SQLSTATE[HY000] [2002] Connection refused`

**Solution:**
1. Verify environment variables di Railway
2. Pastikan MySQL service running di Railway
3. Test connection via Railway MySQL interface
4. Cek firewall/network settings

### Build Failed

**Error:** Build process fails

**Solution:**
1. Cek deployment logs di Railway
2. Pastikan `composer.json` valid
3. Pastikan PHP version kompatibel (7.4+)
4. Cek syntax error di PHP files

### File Upload Failed

**Error:** Cannot upload payment proof

**Solution:**
1. Pastikan volume ter-setup untuk uploads
2. Cek permissions directory
3. Pastikan upload directory ada: `gambar/bukti`
4. Cek php.ini settings untuk upload

### Environment Variables Not Working

**Error:** Config tidak terbaca

**Solution:**
1. Pastikan variables disimpan di Railway
2. Pastikan nama variables tepat (case-sensitive)
3. Restart deployment setelah mengubah variables
4. Cek logs untuk error messages

### Deployment Stuck

**Error:** Deployment tidak selesai

**Solution:**
1. Cancel deployment yang stuck
2. Push perubahan kosong ke GitHub untuk trigger redeploy
3. Cek Railway status page untuk outages
4. Contact Railway support jika masih stuck

---

## 📝 Maintenance Tips

### Update Application

1. Make changes di local
2. Push ke GitHub
3. Railway akan otomatis redeploy

### Update Database

1. Jangan lupa backup database sebelum perubahan
2. Export schema dari Railway MySQL
3. Import ke Railway MySQL setelah perubahan
4. Test di staging environment dulu

### Monitor Application

1. Cek Railway logs secara berkala
2. Monitor database size
3. Monitor disk usage untuk uploads
4. Set up alerts untuk errors

### Backup Strategy

1. Backup database secara berkala:
   - Export dari Railway MySQL
   - Simpan di lokasi aman (S3, local)
2. Backup code di GitHub sudah cukup
3. Backup uploads jika menggunakan Railway Storage

---

## 🎯 Quick Reference

### XAMPP Credentials (Local)
```
Host: localhost
Port: 3306
User: root
Pass: (kosong)
DB: db_pemesanan
```

### Railway Credentials (Production)
```
Host: [MYSQLHOST dari Railway]
Port: 3306
User: [MYSQLUSER dari Railway]
Pass: [MYSQLPASSWORD dari Railway]
DB: [MYSQLDATABASE dari Railway]
```

### Environment Variables Template
```
DB_HOST     = [Railway MYSQLHOST]
DB_PORT     = 3306
DB_NAME     = [Railway MYSQLDATABASE]
DB_USER     = [Railway MYSQLUSER]
DB_PASS     = [Railway MYSQLPASSWORD]

APP_URL     = [Railway App URL]
APP_ENV     = production
APP_DEBUG   = false

ADMIN_USERNAME = admin
ADMIN_PASSWORD = [Secure Password]

UPLOAD_MAX_SIZE = 5242880
UPLOAD_ALLOWED_TYPES = jpeg,jpg,png

APP_TIMEZONE = Asia/Jakarta
```

---

## 📞 Support

Jika mengalami masalah:

1. **Railway Documentation:** https://docs.railway.app/
2. **Railway Support:** support@railway.app
3. **GitHub Issues:** https://github.com/nabilnugroho010-hue/Tubes-RPL/issues
4. **Project README:** Lihat README.md untuk detail teknis

---

## ✨ Deployment Checklist

Sebelum deploy ke production:

- [ ] Database credentials dari XAMPP sudah dicatat
- [ ] Railway MySQL service sudah dibuat
- [ ] Database schema sudah di-import ke Railway
- [ ] Environment variables sudah di-set di Railway
- [ ] Admin password sudah di-set (gunakan password yang aman)
- [ ] File upload directory sudah di-setup
- [ ] Application sudah di-deploy
- [ ] Admin panel sudah di-test
- [ ] Customer panel sudah di-test
- [ ] Realtime features sudah di-test
- [ ] HTTPS sudah aktif (otomatis di Railway)
- [ ] Backup strategy sudah dipikirkan

---

Selamat deploying! 🚀

Generated with [Devin](https://devin.ai)
