# SPGFood - Modern Restaurant Ordering System

Sistem pemesanan makanan restoran modern dengan fitur realtime tracking untuk pelanggan dan panel admin yang lengkap.

## 📝 Update Log
- **2026-08-08**: Fixed all transparency issues across all pages - input fields, text colors, and selectors now fully visible

## 🌟 Fitur Utama

### Customer Panel
- **Menu Categorization** - Filter menu berdasarkan kategori (Makanan, Minuman, Camilan)
- **Realtime Status Tracking** - Tracking status pesanan secara realtime dengan polling
- **Payment Proof Upload** - Upload bukti pembayaran
- **Order History** - Lihat riwayat pesanan pelanggan
- **Modern UI/UX** - Glassmorphism dark theme dengan warna Blue & Cyan

### Admin Panel
- **Dashboard** - Statistik ringkas pesanan
- **Menu Management** - CRUD menu makanan/minuman
- **Order Management** - Kelola pesanan masuk
- **Payment Confirmation** - Konfirmasi pembayaran
- **Daily Reports** - Laporan pendapatan harian dengan jam realtime
- **Monthly Reports** - Laporan pendapatan bulanan dengan grafik statistik
- **Advanced Analytics** - Grafik total orders, revenue, order status, payment methods

### Technical Features
- **Single Source of Truth** - Arsitektur database yang konsisten
- **Realtime API** - API endpoint untuk cek status
- **Modern UI** - Glassmorphism design dengan neon accents
- **Toast Notifications** - Notifikasi user-friendly
- **Modal Dialogs** - Dialog interaktif
- **Loading States** - Loading spinners dan skeleton screens
- **Empty States** - Empty state yang informatif
- **Error Handling** - Error states yang jelas

## 📋 Persyaratan Sistem

### Development (XAMPP)
- PHP 7.4+
- MySQL 5.7+
- Apache Web Server
- Composer (untuk production)

### Production (Railway)
- Railway Account (Gratis/Pro)
- GitHub Repository
- MySQL Database dari Railway
- PHP 7.4+ (otomatis oleh Railway)

## 🚀 Deployment ke Railway

### Prerequisites

1. **GitHub Repository**
   - Pastikan project sudah di-push ke GitHub
   - Repository: https://github.com/nabilnugroho010-hue/Tubes-RPL

2. **Railway Account**
   - Sign up di https://railway.app/
   - Link GitHub account ke Railway

3. **XAMPP Database** (untuk mengambil credentials)
   - Buka phpMyAdmin di XAMPP
   - Catat database credentials:
     - Database name: `db_pemesanan`
     - Username: `root`
     - Password: (kosong atau password XAMPP Anda)
     - Host: `localhost`
     - Port: `3306`

### Step 1: Setup Database di Railway

1. **Login ke Railway** → https://railway.app/

2. **Create New Project**
   - Klik "New Project"
   - Pilih "Deploy from GitHub repo"
   - Pilih repository `Tubes-RPL`

3. **Add MySQL Database**
   - Klik "+ New Service"
   - Pilih "Database"
   - Pilih "MySQL"
   - Railway akan membuat database baru

4. **Get Database Credentials**
   - Klik service MySQL yang baru dibuat
   - Masuk ke tab "Variables"
   - Catat credentials berikut:
     ```
     MYSQLHOST = xxx.railway.app
     MYSQLPORT = 3306
     MYSQLUSER = xxx
     MYSQLPASSWORD = xxx
     MYSQLDATABASE = xxx
     ```

### Step 2: Setup Environment Variables di Railway

1. **Buka Project Settings**
   - Klik project settings (gear icon)
   - Masuk ke tab "Variables"

2. **Add Environment Variables**
   Tambahkan variables berikut:
   
   ```env
   DB_HOST = [MYSQLHOST dari Railway]
   DB_PORT = [MYSQLPORT dari Railway]
   DB_NAME = [MYSQLDATABASE dari Railway]
   DB_USER = [MYSQLUSER dari Railway]
   DB_PASS = [MYSQLPASSWORD dari Railway]
   
   APP_URL = [URL Railway deployment Anda]
   APP_ENV = production
   APP_DEBUG = false
   
   ADMIN_USERNAME = admin
   ADMIN_PASSWORD = [password admin yang aman]
   
   UPLOAD_MAX_SIZE = 5242880
   UPLOAD_ALLOWED_TYPES = jpeg,jpg,png
   
   APP_TIMEZONE = Asia/Jakarta
   ```

3. **Contoh Environment Variables:**
   ```env
   DB_HOST = mysql.railway.app
   DB_PORT = 3306
   DB_NAME = railway
   DB_USER = root
   DB_PASS = xxxxx
   
   APP_URL = https://spgfood-production.up.railway.app
   APP_ENV = production
   APP_DEBUG = false
   
   ADMIN_USERNAME = admin
   ADMIN_PASSWORD = SecurePassword123!
   
   UPLOAD_MAX_SIZE = 5242880
   UPLOAD_ALLOWED_TYPES = jpeg,jpg,png
   
   APP_TIMEZONE = Asia/Jakarta
   ```

### Step 3: Import Database Schema

1. **Buka MySQL Service di Railway**
   - Klik service MySQL
   - Klik "Connect" atau "Open in Browser"
   - Login dengan credentials dari Railway

2. **Import Schema**
   - Buka file `migrations/database_improvements.sql` dari local
   - Copy semua SQL content
   - Paste ke Railway MySQL interface
   - Execute SQL query

3. **Verify Tables**
   Pastikan tabel berikut tercreate:
   - `data_pesanan`
   - `data_menu`
   - `rincian_pesanan`
   - `data_pembayaran`

### Step 4: Setup Application Service

1. **Buka GitHub Service**
   - Railway akan otomatis detect repository
   - Pastikan branch yang di-deploy adalah `main`

2. **Configure Build Settings**
   - Railway akan otomatis detect PHP project
   - Pastikan `Procfile` terbaca:
     ```
     web: heroku-php-apache2
     ```

3. **Deploy**
   - Klik "Deploy"
   - Tunggu proses build dan deployment (1-2 menit)

### Step 5: Setup File Upload Directory

1. **Create Upload Directory**
   Railway menggunakan filesystem ephemeral, jadi kita perlu setup persistent storage:
   
   - Tambahkan volume untuk upload:
     - Klik project settings
     - Add "Volume"
     - Name: `uploads`
     - Mount path: `/app/gambar/bukti`

2. **Alternative: Gunakan Railway Storage**
   Untuk production, lebih baik gunakan Railway Storage atau S3-compatible storage.

### Step 6: Verify Deployment

1. **Buka URL Railway**
   - Railway akan memberikan URL seperti:
     `https://spgfood-production.up.railway.app`
   
2. **Test Features**
   - Buka halaman admin: `/login.php`
   - Login dengan credentials dari environment variables
   - Test pemesanan pelanggan: `/pemesanan_pelanggan/pesan_pelanggan.php`
   - Test cek status: `/pemesanan_pelanggan/cek_status.php`

## 📱 Setup Local Development (XAMPP)

### 1. Clone Repository
```bash
git clone https://github.com/nabilnugroho010-hue/Tubes-RPL.git
cd Tubes-RPL
```

### 2. Import Database
1. Buka phpMyAdmin: `http://localhost/phpmyadmin`
2. Create database: `db_pemesanan`
3. Import file: `migrations/database_improvements.sql`

### 3. Configure Database Connection
```bash
# Copy .env.example ke .env
cp .env.example .env

# Edit .env dengan credentials XAMPP Anda
# Default XAMPP:
DB_HOST=localhost
DB_PORT=3306
DB_NAME=db_pemesanan
DB_USER=root
DB_PASS=
```

### 4. Setup Upload Directory
```bash
# Create upload directory
mkdir -p gambar/bukti

# Set permissions (Linux/Mac)
chmod 755 gambar/bukti
```

### 5. Run Application
Buka browser:
- Admin: `http://localhost/pemesanan/login.php`
- Customer: `http://localhost/pemesanan/pemesanan_pelanggan/pesan_pelanggan.php`

**Default Credentials:**
- Username: `admin`
- Password: `1234`

## 🔧 Configuration Files

### Environment Variables (.env)
```env
# Database Configuration
DB_HOST=localhost
DB_PORT=3306
DB_NAME=db_pemesanan
DB_USER=root
DB_PASS=

# App Configuration
APP_URL=http://localhost/pemesanan
APP_ENV=development
APP_DEBUG=true

# Admin Credentials
ADMIN_USERNAME=admin
ADMIN_PASSWORD=1234

# File Upload Configuration
UPLOAD_MAX_SIZE=5242880
UPLOAD_ALLOWED_TYPES=jpeg,jpg,png

# Timezone
APP_TIMEZONE=Asia/Jakarta
```

### Database Configuration (config/database.php)
File ini meng-handle koneksi database dengan support:
- Environment variables (.env)
- Fallback ke default XAMPP credentials
- Singleton pattern untuk efisiensi
- PDO dengan error handling

### Procfile
File ini memberitahu Railway cara menjalankan aplikasi:
```
web: heroku-php-apache2
```

### .htaccess
Apache configuration untuk:
- HTTPS redirect
- Security headers
- File protection
- PHP settings
- Compression
- Cache control

## 📁 Project Structure

```
pemesanan/
├── admin/                      # Admin panel
│   ├── index.php              # Dashboard
│   ├── login.php              # Login
│   ├── kelola_menu.php        # Menu management
│   ├── kelola_pesanan.php     # Order management
│   ├── laporan_harian.php     # Daily reports
│   └── laporan_bulanan.php    # Monthly reports
├── pemesanan_pelanggan/       # Customer panel
│   ├── pesan_pelanggan.php    # Order page
│   ├── pembayaran.php         # Payment page
│   ├── cek_status.php         # Status tracking
│   └── riwayat_pesanan.php    # Order history
├── api/                       # API endpoints
│   └── cek_status_api.php     # Status API
├── config/                    # Configuration
│   └── database.php           # Database config
├── assets/                    # Static assets
│   ├── css/style.css          # Stylesheet
│   └── js/app.js              # JavaScript
├── migrations/                 # Database migrations
│   └── database_improvements.sql
├── includes/                  # Reusable components
│   ├── header.php
│   └── sidebar.php
├── gambar/                    # Uploads
│   └── bukti/                 # Payment proofs
├── composer.json              # PHP dependencies
├── Procfile                   # Railway build config
├── .env.example               # Environment template
├── .htaccess                  # Apache config
└── README.md                  # This file
```

## 🗄️ Database Schema

### Tables
- **data_pesanan** - Order information
- **data_menu** - Menu items
- **rincian_pesanan** - Order details
- **data_pembayaran** - Payment information

### Indexes
- `idx_tgl_pesanan` on `data_pesanan(tgl_pesanan)`
- `idx_status` on `data_pesanan(status)`
- `idx_kode_pelanggan` on `data_pesanan(kode_pelanggan)`

## 🎨 Design System

### Color Palette (Blue & Cyan)
- Primary: `#00f5ff` (Neon Cyan)
- Secondary: `#00d4ff` (Cyan)
- Background: `#0a0a1a` (Dark Blue)
- Glass: `rgba(255, 255, 255, 0.05)`
- Success: `#00ff88`
- Warning: `#ffaa00`
- Error: `#ff4466`

### Components
- Glassmorphism cards
- Neon accent borders
- Smooth transitions
- Hover effects
- Toast notifications
- Modal dialogs
- Loading spinners

## 🔒 Security Notes

### File Protection
- `.env` file tidak di-commit ke git
- Database credentials tidak di-commit
- Payment proofs tidak di-commit
- Config directory dilindungi via .htaccess

### Security Headers
- X-Content-Type-Options: nosniff
- X-Frame-Options: SAMEORIGIN
- X-XSS-Protection: 1; mode=block
- Referrer-Policy: strict-origin-when-cross-origin

### Best Practices
- Gunakan environment variables untuk sensitive data
- Update password admin di production
- Gunakan HTTPS di production
- Validasi semua user inputs
- Sanitize file uploads

## 🐛 Troubleshooting

### Railway Deployment Issues

**Build Failed:**
- Pastikan `composer.json` valid
- Cek Railway logs untuk error details
- Pastikan PHP version kompatibel

**Database Connection Failed:**
- Verify environment variables di Railway
- Pastikan MySQL service running di Railway
- Test connection dengan Railway MySQL interface

**File Upload Failed:**
- Pastikan upload directory ada
- Cek permissions directory
- Pastikan Railway volume ter-setup

### Local Development Issues

**Database Connection Failed:**
- Cek XAMPP MySQL service running
- Verify credentials di .env
- Pastikan database sudah dibuat

**File Upload Failed:**
- Pastikan `gambar/bukti` directory ada
- Set permissions: `chmod 755 gambar/bukti`
- Cek php.ini untuk upload settings

## 📝 License

MIT License

## 👥 Contributors

- Nabil Nugroho

## 📞 Support

Untuk support atau pertanyaan:
- GitHub Issues: https://github.com/nabilnugroho010-hue/Tubes-RPL/issues
- Email: nabilnugroho010-hue@users.noreply.github.com

---

Generated with [Devin](https://devin.ai)
