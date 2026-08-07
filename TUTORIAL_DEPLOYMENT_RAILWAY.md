# 🚀 TUTORIAL MANUAL DEPLOYMENT KE RAILWAY

Tutorial langkah-demi-langkah untuk deploy SPGFood ke Railway.

---

## 📋 PERSIAPAN (10 Menit)

### Step 1: Ambil Database Credentials dari XAMPP

#### Cara Manual:
1. Buka browser: `http://localhost/phpmyadmin`
2. Login dengan:
   - Username: `root`
   - Password: (kosong, biarkan kosong)
3. Pastikan database `db_pemesanan` ada
4. Catat credentials:
   ```
   Host: localhost
   Port: 3306
   User: root
   Pass: (kosong)
   Database: db_pemesanan
   ```

#### Cara Otomatis (Windows):
```powershell
# Buka PowerShell sebagai Administrator
cd C:\xampp\htdocs\pemesanan\scripts
.\get-xampp-credentials.ps1
```

### Step 2: Verify Project di GitHub

1. Buka: https://github.com/nabilnugroho010-hue/Tubes-RPL
2. Pastikan semua file sudah terbaru
3. Pastikan branch `main` adalah yang aktif

---

## 🚀 SETUP RAILWAY (15 Menit)

### Step 3: Login ke Railway

1. Buka: https://railway.app/
2. Klik **"Login"** di pojok kanan atas
3. Login dengan GitHub account Anda
4. Authorize Railway untuk akses GitHub

### Step 4: Create New Project

1. Setelah login, klik **"New Project"** (tombol besar di tengah)
2. Pilih **"Deploy from GitHub repo"**
3. Railway akan menampilkan list repository GitHub Anda
4. Cari dan pilih: **Tubes-RPL**
5. Pilih branch: **main**
6. Klik **"Deploy Now"**

Railway akan mulai clone repository dan setup build environment.

---

## 🗄️ SETUP DATABASE (10 Menit)

### Step 5: Add MySQL Database

1. Di dashboard Railway project Anda, klik **"+ New Service"** (tombol di atas)
2. Pilih **"Database"**
3. Pilih **"MySQL"**
4. Klik **"Add MySQL"**
5. Tunggu sampai MySQL service ter-create (1-2 menit)

### Step 6: Get Railway MySQL Credentials

1. Klik service MySQL yang baru dibuat (biasanya bernama "MySQL")
2. Masuk ke tab **"Variables"** (di sebelah kiri)
3. Anda akan melihat list environment variables
4. Copy nilai dari variables berikut:
   - `MYSQLHOST` (contoh: mysql.railway.app)
   - `MYSQLPORT` (contoh: 3306)
   - `MYSQLUSER` (contoh: root)
   - `MYSQLPASSWORD` (contoh: random string)
   - `MYSQLDATABASE` (contoh: railway)

**PENTING:** Simpan credentials ini di tempat aman!

---

## ⚙️ CONFIGURE ENVIRONMENT VARIABLES (5 Menit)

### Step 7: Add Environment Variables di Railway

1. Klik **Settings** (ikon gear di pojok kanan atas project)
2. Masuk ke tab **"Variables"**
3. Klik **"New Variable"** untuk menambahkan variables satu per satu

#### Tambahkan variables berikut:

**Database Configuration:**
```
Name: DB_HOST
Value: [paste MYSQLHOST dari Step 6]

Name: DB_PORT
Value: 3306

Name: DB_NAME
Value: [paste MYSQLDATABASE dari Step 6]

Name: DB_USER
Value: [paste MYSQLUSER dari Step 6]

Name: DB_PASS
Value: [paste MYSQLPASSWORD dari Step 6]
```

**Application Configuration:**
```
Name: APP_URL
Value: [ Railway akan memberikan URL nanti, kosongkan dulu ]

Name: APP_ENV
Value: production

Name: APP_DEBUG
Value: false
```

**Admin Configuration:**
```
Name: ADMIN_USERNAME
Value: admin

Name: ADMIN_PASSWORD
Value: [Password aman Anda, minimal 8 karakter]
```

**Upload Configuration:**
```
Name: UPLOAD_MAX_SIZE
Value: 5242880

Name: UPLOAD_ALLOWED_TYPES
Value: jpeg,jpg,png
```

**Timezone Configuration:**
```
Name: APP_TIMEZONE
Value: Asia/Jakarta
```

4. Setelah semua variables ditambahkan, klik **"Save Changes"**

---

## 🗄️ IMPORT DATABASE SCHEMA (5 Menit)

### Step 8: Buka Railway MySQL Interface

1. Kembali ke service MySQL (klik nama service MySQL)
2. Klik **"Connect"** atau **"Open in Browser"**
3. Login dengan credentials dari Step 6:
   - Username: [MYSQLUSER]
   - Password: [MYSQLPASSWORD]
   - Database: [MYSQLDATABASE]

### Step 9: Import Database Schema

#### Option A: Copy-Paste (Rekomendasi)

1. Buka file local: `C:\xampp\htdocs\pemesanan\migrations\database_improvements.sql`
2. Copy semua content SQL
3. Di Railway MySQL interface, buka tab **"SQL"**
4. Paste SQL content ke editor
5. Klik **"Go"** atau **"Execute"**

#### Option B: Upload File

1. Di Railway MySQL interface, cari tombol **"Import"**
2. Upload file: `migrations/database_improvements.sql`
3. Klik **"Go"** untuk import

### Step 10: Verify Tables

Di Railway MySQL interface, jalankan query:
```sql
SHOW TABLES;
```

Pastikan tabel berikut ada:
- `data_pesanan`
- `data_menu`
- `rincian_pesanan`
- `data_pembayaran`

---

## 🚢 DEPLOY APPLICATION (5 Menit)

### Step 11: Monitor Deployment

1. Kembali ke tab **"Deployments"** di Railway
2. Railway akan otomatis mendeteksi perubahan
3. Deployment akan berjalan otomatis
4. Tunggu 1-2 menit

### Step 12: Cek Deployment Status

- **Building:** Railway sedang build aplikasi
- **Deploying:** Railway sedang deploy
- **Success:** Deployment berhasil ✅
- **Failed:** Deployment gagal (lihat logs)

Jika deployment berhasil, Anda akan melihat:
- Status: **Active**
- URL deployment (contoh: https://spgfood-production.up.railway.app)

### Step 13: Update APP_URL

1. Copy URL deployment dari Railway
2. Kembali ke **Settings** → **Variables**
3. Update variable `APP_URL` dengan URL deployment:
   ```
   APP_URL = https://[your-app].up.railway.app
   ```
4. Klik **"Save Changes"**
5. Railway akan otomatis redeploy

---

## ✅ TESTING DEPLOYMENT (10 Menit)

### Step 14: Test Admin Panel

1. Buka URL deployment: `https://[your-app].up.railway.app/login.php`
2. Login dengan credentials dari environment variables:
   - Username: `admin`
   - Password: [ADMIN_PASSWORD yang Anda set]
3. Verify:
   - Dashboard muncul
   - Stats cards menampilkan data
   - Menu navigation berfungsi
   - Tidak ada error

### Step 15: Test Customer Panel

1. Buka: `https://[your-app].up.railway.app/pemesanan_pelanggan/pesan_pelanggan.php`
2. Test features:
   - Menu categorization (Makanan, Minuman, Camilan)
   - Add menu to cart
   - Quantity adjustment
   - View cart summary

### Step 16: Test Order Flow

1. Buat order baru:
   - Isi nama pelanggan
   - Isi nomor meja
   - Pilih menu
   - Klik "Kirim Pesanan"
2. Verify redirect ke halaman pembayaran
3. Test status tracking:
   - Buka halaman cek status
   - Masukkan kode pelanggan dari order
   - Verify status berfungsi

### Step 17: Test Realtime Features

1. Buat order baru sebagai customer
2. Login sebagai admin
3. Update status order di admin panel
4. Cek status sebagai customer
5. Verify status berubah secara realtime

---

## 🔧 TROUBLESHOOTING

### Problem: Build Failed

**Solution:**
1. Klik deployment yang gagal
2. Buka tab **"Logs"**
3. Cari error message
4. Perbaiki error di local
5. Push ke GitHub
6. Railway akan otomatis redeploy

### Problem: Database Connection Failed

**Solution:**
1. Verify environment variables di Railway
2. Pastikan MySQL service running
3. Test connection via Railway MySQL interface
4. Cek apakah database schema sudah di-import

### Problem: File Upload Failed

**Solution:**
1. Pastikan upload directory ada
2. Cek permissions directory
3. Verify upload size settings
4. Setup volume untuk uploads di Railway

### Problem: HTTPS Redirect Loop

**Solution:**
1. Buka file `.htaccess` di local
2. Comment out HTTPS redirect:
   ```apache
   # RewriteCond %{HTTPS} off
   # RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
   ```
3. Push ke GitHub
4. Railway akan redeploy

---

## 📝 CHECKLIST DEPLOYMENT

Sebelum deploy, pastikan:

- [ ] XAMPP credentials sudah dicatat
- [ ] Railway account sudah dibuat
- [ ] GitHub repository sudah linked
- [ ] MySQL service sudah dibuat
- [ ] Railway MySQL credentials sudah dicatat
- [ ] Environment variables sudah di-set
- [ ] Database schema sudah di-import
- [ ] Tables sudah ter-verify
- [ ] Application sudah di-deploy
- [ ] APP_URL sudah di-update
- [ ] Admin panel sudah di-test
- [ ] Customer panel sudah di-test
- [ ] Order flow sudah di-test
- [ ] Realtime features sudah di-test

---

## 🎯 SUMMARY LANGKAH

| Step | Aktivitas | Waktu |
|------|-----------|-------|
| 1-2 | Persiapan credentials | 10 min |
| 3-4 | Setup Railway project | 15 min |
| 5-6 | Setup MySQL database | 10 min |
| 7 | Configure environment variables | 5 min |
| 8-10 | Import database schema | 5 min |
| 11-13 | Deploy application | 5 min |
| 14-17 | Testing deployment | 10 min |
| **TOTAL** | **Complete Deployment** | **~60 min** |

---

## 📞 BANTUAN

Jika mengalami masalah:

1. **Cek Logs:** Railway → Deployments → [Click deployment] → Logs
2. **Baca Documentation:** `docs/RAILWAY_DEPLOYMENT_GUIDE.md`
3. **GitHub Issues:** https://github.com/nabilnugroho010-hue/Tubes-RPL/issues
4. **Railway Docs:** https://docs.railway.app/

---

## 🎉 SELAMAT DEPLOYING!

Ikuti tutorial ini langkah-demi-langkah dan Anda akan berhasil deploy SPGFood ke Railway dalam ~1 jam!

**Generated with [Devin](https://devin.ai)**
