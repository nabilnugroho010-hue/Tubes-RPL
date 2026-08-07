# SPGFood - Documentation

## Project Overview
SPGFood adalah aplikasi pemesanan makanan restoran modern dengan fitur realtime tracking untuk pelanggan dan panel admin yang lengkap.

## Database Structure

### Tabel Utama
- **data_pesanan** - Tabel utama pesanan
  - `id_pesanan` (INT, Primary Key, Auto Increment)
  - `tgl_pesanan` (DATETIME) - Tanggal dan jam pesanan
  - `nama_pelanggan` (VARCHAR)
  - `no_meja` (VARCHAR)
  - `kode_pelanggan` (VARCHAR) - Kode unik pelanggan (format: CUST-XXXX)
  - `total_harga` (DECIMAL)
  - `status` (VARCHAR) - Status pesanan
  - `metode_pembayaran` (VARCHAR) - Metode pembayaran (single source of truth)
  - `nomor_pesanan` (VARCHAR) - Nomor pesanan (format: ORD-YYYYMMDD-XXXX)

- **data_menu** - Tabel menu makanan/minuman
  - `id_menu` (INT, Primary Key, Auto Increment)
  - `nama_menu` (VARCHAR)
  - `jenis_menu` (VARCHAR) - Makanan/Minuman/Camilan
  - `harga` (DECIMAL)
  - `status` (VARCHAR) - Tersedia/Tidak Tersedia

- **rincian_pesanan** - Tabel relasi pesanan-menu
  - `id_rincian` (INT, Primary Key, Auto Increment)
  - `id_pesanan` (INT, Foreign Key)
  - `id_menu` (INT, Foreign Key)
  - `jumlah` (INT)

- **data_pembayaran** - Tabel pembayaran
  - `id_pembayaran` (INT, Primary Key, Auto Increment)
  - `id_pesanan` (INT, Foreign Key)
  - `metode` (VARCHAR)
  - `bukti_url` (VARCHAR)
  - `tgl_bayar` (TIMESTAMP)

### Indeks Database
- `idx_tgl_pesanan` pada `data_pesanan(tgl_pesanan)`
- `idx_status` pada `data_pesanan(status)`
- `idx_kode_pelanggan` pada `data_pesanan(kode_pelanggan)`

## File Structure

### Admin Panel (Root)
- `index.php` - Dashboard admin
- `login.php` - Login admin
- `logout.php` - Logout admin
- `kelola_menu.php` - Kelola menu makanan/minuman
- `tambah_menu.php` - Tambah menu baru
- `ubah_menu.php` - Edit menu
- `kelola_pesanan.php` - Kelola pesanan masuk
- `ubah_status.php` - Update status pesanan
- `konfirmasi_pesanan.php` - Konfirmasi pembayaran
- `detail_pesanan.php` - Detail pesanan
- `laporan_harian.php` - Laporan pendapatan harian (dengan jam realtime)
- `laporan_bulanan.php` - Laporan pendapatan bulanan (dengan jam realtime)

### Pelanggan Panel (pemesanan_pelanggan/)
- `pesan_pelanggan.php` - Halaman pemesanan menu dengan kategori (Makanan, Minuman, Camilan)
- `pembayaran.php` - Halaman pembayaran
- `pembayaran_berhasil.php` - Halaman sukses pembayaran
- `konfirmasi_pembayaran.php` - Upload bukti pembayaran
- `cek_status.php` - Cek status pesanan realtime
- `riwayat_pesanan.php` - Riwayat pesanan pelanggan

### API
- `api/cek_status_api.php` - API endpoint untuk cek status (JSON response)

### Assets
- `assets/css/style.css` - Stylesheet utama (Glassmorphism dark theme)
- `assets/js/app.js` - JavaScript utama (Toast, Modal, Loading, StatusPoller)

### Includes
- `includes/header.php` - Header admin panel
- `includes/sidebar.php` - Sidebar admin panel

### Config
- `koneksi.php` - Database connection (wrapper for config/database.php)
- `config/database.php` - Modular database configuration with environment support

### Deployment Files
- `composer.json` - PHP dependencies and build configuration
- `Procfile` - Railway build instructions
- `.env.example` - Environment variables template
- `.htaccess` - Apache configuration for production
- `README.md` - Project documentation and deployment guide

### Scripts
- `scripts/railway-setup.sh` - Railway setup script (Linux/Mac)
- `scripts/railway-setup.bat` - Railway setup script (Windows)
- `scripts/get-xampp-credentials.ps1` - XAMPP credentials helper (PowerShell)

### Documentation
- `docs/RAILWAY_DEPLOYMENT_GUIDE.md` - Detailed Railway deployment manual

## Alur Transaksi

1. **Pemesanan**
   - Pelanggan memilih menu di `pesan_pelanggan.php`
   - Masukkan nama dan nomor meja
   - Generate kode pelanggan unik (CUST-XXXX)
   - Generate nomor pesanan (ORD-YYYYMMDD-XXXX)
   - Simpan ke `data_pesanan` dan `rincian_pesanan`
   - Redirect ke `pembayaran.php`

2. **Pembayaran**
   - Tampilkan ringkasan pesanan
   - Tampilkan QRIS dan info rekening
   - Pelanggan upload bukti pembayaran
   - Simpan ke `data_pembayaran`
   - Update status di `data_pesanan` menjadi "Sudah Dibayar"
   - Redirect ke `pembayaran_berhasil.php` (halaman sukses)
   - Dari halaman sukses, auto-redirect ke `cek_status.php` setelah 5 detik

3. **Konfirmasi Admin**
   - Admin lihat pesanan di `konfirmasi_pesanan.php`
   - Admin cek bukti pembayaran
   - Admin update status di `ubah_status.php`

4. **Tracking Realtime**
   - Pelanggan cek status di `cek_status.php`
   - Polling setiap 5 detik via `api/cek_status_api.php`
   - Auto-refresh saat status berubah
   - Toast notification saat update

5. **Riwayat Pesanan**
   - Pelanggan lihat riwayat di `riwayat_pesanan.php`
   - Filter berdasarkan kode pelanggan
   - Lihat detail setiap pesanan

6. **Laporan Admin**
   - **Laporan Harian** (`laporan_harian.php`)
     - Menampilkan semua pesanan per hari dengan jam realtime
     - Kolom: No. Pesanan, Tanggal & Jam, Pelanggan, No Meja, Status, Total
     - Filter berdasarkan tanggal
     - Fitur cetak laporan
     - Menggunakan `DATE(tgl_pesanan)` untuk filter tanggal yang tepat
     - Format waktu: `d F Y • H:i:s WIB` (contoh: 06 August 2026 • 14:30:45 WIB)

   - **Laporan Bulanan** (`laporan_bulanan.php`)
     - Menampilkan semua pesanan per bulan dengan jam realtime
     - Kolom: No. Pesanan, Tanggal & Jam, Pelanggan, No Meja, Status, Total
     - Filter berdasarkan bulan dan tahun
     - Fitur cetak laporan
     - Format waktu: `d F Y • H:i:s WIB` (contoh: 06 August 2026 • 14:30:45 WIB)

   - **Realtime Timestamps**
     - Semua laporan menggunakan `tgl_pesanan` yang bertipe DATETIME
     - Menampilkan jam presisi (detik) sesuai waktu pemesanan
     - Diurutkan dari yang terbaru (DESC) berdasarkan `tgl_pesanan`
     - Konsisten dengan waktu yang tersimpan di database

## Fitur Realtime

### StatusPoller Class
JavaScript class untuk polling status pesanan:
```javascript
const poller = new StatusPoller({
    idPesanan: 123,
    kodePelanggan: 'CUST-ABC123',
    interval: 5000, // 5 seconds
    onStatusChange: function(data) {
        // Handle status change
    },
    onError: function(error) {
        // Handle error
    }
});
poller.start();
```

### API Endpoint
`api/cek_status_api.php` accepts:
- `id_pesanan` - Cek status berdasarkan ID pesanan
- `kode_pelanggan` - Cek status berdasarkan kode pelanggan

Returns JSON:
```json
{
    "success": true,
    "status": "Sudah Dibayar",
    "metode_pembayaran": "QRIS"
}
```

## Single Source of Truth

Semua data transaksi diambil dari `data_pesanan`:
- Status pesanan → `data_pesanan.status`
- Metode pembayaran → `data_pesanan.metode_pembayaran`
- Total harga → `data_pesanan.total_harga`
- Tanggal/jam → `data_pesanan.tgl_pesanan`

Tabel `data_pembayaran` hanya menyimpan bukti pembayaran dan detail tambahan.

## Design System

### Color Palette (Biru & Cyan)
- Primary: `#00f5ff` (Neon Cyan)
- Secondary: `#00d4ff` (Cyan)
- Background: `#0a0a1a` (Dark Blue)
- Glass: `rgba(255, 255, 255, 0.05)`
- Success: `#00ff88`
- Warning: `#ffaa00`
- Error: `#ff4466`

### Components
- Glassmorphism cards dengan backdrop blur
- Neon accent borders
- Smooth transitions
- Hover effects
- Toast notifications
- Modal dialogs
- Loading spinners

## Testing Checklist

### Database
- [x] Tipe data `tgl_pesanan` sudah DATETIME
- [x] Tipe data `kode_pelanggan` sudah VARCHAR
- [x] Kolom `metode_pembayaran` sudah ada
- [x] Indeks sudah ditambahkan
- [x] Tabel yang tidak digunakan sudah dihapus

### Backend
- [x] Single source of truth diimplementasikan
- [x] API endpoint untuk cek status sudah ada
- [x] Data realtime dari database
- [x] Session dependency sudah dihapus

### Frontend
- [x] Polling untuk realtime status
- [x] UI pelanggan sudah redesign
- [x] Halaman riwayat pesanan sudah ada
- [x] Empty states di admin panel
- [x] Cart summary di halaman pemesanan

### Alur Transaksi
- [x] Pemesanan → Pembayaran → Konfirmasi → Status
- [x] Notifikasi realtime saat status berubah
- [x] Konsistensi data di seluruh halaman

## Deployment Notes

### Requirements
- PHP 7.4+
- MySQL 5.7+
- Web server (Apache/Nginx)
- XAMPP (untuk development)
- Railway account (untuk production deployment)

### Local Development (XAMPP)
1. Import database `db_pemesanan`
2. Copy `.env.example` ke `.env`
3. Edit `.env` dengan credentials XAMPP
4. Pastikan folder `gambar/bukti` writable
5. Jalankan di browser: `http://localhost/pemesanan/`

### Production Deployment (Railway)
1. Push code ke GitHub
2. Setup project di Railway
3. Add MySQL database service
4. Configure environment variables
5. Import database schema
6. Deploy application

**See:** `docs/RAILWAY_DEPLOYMENT_GUIDE.md` for detailed Railway deployment instructions

### Default Credentials
- Admin Username: `admin`
- Admin Password: `1234` (change in production!)

## Troubleshooting

### Pesanan tidak muncul di riwayat
- Pastikan kode pelanggan benar
- Cek database di tabel `data_pesanan`

### Status tidak update realtime
- Pastikan API endpoint bisa diakses
- Cek console browser untuk error JavaScript
- Pastikan tidak ada error di `api/cek_status_api.php`

### Upload bukti gagal
- Pastikan folder `gambar/bukti` ada dan writable
- Cek ukuran file (max 5MB)
- Pastikan format file sesuai (JPG, PNG, JPEG)

## Future Improvements

- [ ] WebSocket untuk realtime yang lebih efisien
- [ ] Mobile app native
- [ ] Fitur review/rating menu
- [ ] Diskon dan promo
- [ ] Multi-restaurant support
- [ ] Integration dengan payment gateway
- [ ] Email notification
- [ ] WhatsApp notification
