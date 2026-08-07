<?php
session_start();
include "koneksi.php";
include "includes/auth.php";

// Ambil data statistik
$total_menu = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM data_menu"));
$total_pesanan_hari_ini = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM data_pesanan WHERE DATE(tgl_pesanan) = CURDATE()"));
$pendapatan_hari_ini = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(SUM(total_harga), 0) as total FROM data_pesanan WHERE DATE(tgl_pesanan) = CURDATE()"));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - SPGFood</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<!-- Sidebar -->
<?php include "includes/sidebar.php"; ?>

<!-- Main Content -->
<main class="main-content">
    <!-- Header -->
    <header class="header">
        <div class="header-left">
            <div>
                <h1 class="header-title">Dashboard</h1>
                <p class="header-subtitle">Selamat datang kembali, Admin</p>
            </div>
        </div>
        <div class="header-right">
            <div class="header-user">
                <div class="header-user-avatar">👤</div>
                <div>
                    <div class="header-user-name">Admin</div>
                    <div class="header-user-role">Administrator</div>
                </div>
            </div>
        </div>
    </header>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stats-card">
            <div class="stats-card-icon blue">📋</div>
            <div class="stats-card-label">Total Menu</div>
            <div class="stats-card-value"><?= $total_menu['total'] ?></div>
            <div class="stats-card-change">Menu tersedia</div>
        </div>
        
        <div class="stats-card">
            <div class="stats-card-icon purple">🛒</div>
            <div class="stats-card-label">Pesanan Hari Ini</div>
            <div class="stats-card-value"><?= $total_pesanan_hari_ini['total'] ?></div>
            <div class="stats-card-change">Pesanan aktif</div>
        </div>
        
        <div class="stats-card">
            <div class="stats-card-icon green">💰</div>
            <div class="stats-card-label">Pendapatan Hari Ini</div>
            <div class="stats-card-value">Rp <?= number_format($pendapatan_hari_ini['total'], 0, ',', '.') ?></div>
            <div class="stats-card-change">Total pendapatan</div>
        </div>
    </div>

    <!-- Dashboard Grid -->
    <div class="grid grid-3">
        <a href="kelola_menu.php" class="glass-card dashboard-card">
            <div class="icon">📋</div>
            <div class="title">Kelola Menu</div>
            <div class="description">Tambah, edit, dan hapus menu makanan & minuman</div>
        </a>

        <a href="kelola_pesanan.php" class="glass-card dashboard-card">
            <div class="icon">🛒</div>
            <div class="title">Kelola Pesanan</div>
            <div class="description">Kelola pesanan pelanggan dan update status</div>
        </a>

        <a href="konfirmasi_pesanan.php" class="glass-card dashboard-card">
            <div class="icon">✅</div>
            <div class="title">Konfirmasi Pesanan</div>
            <div class="description">Verifikasi pembayaran dan bukti transfer pelanggan</div>
        </a>

        <a href="laporan_harian.php" class="glass-card dashboard-card">
            <div class="icon">📊</div>
            <div class="title">Laporan Harian</div>
            <div class="description">Lihat laporan pendapatan harian</div>
        </a>

        <a href="laporan_bulanan.php" class="glass-card dashboard-card">
            <div class="icon">📈</div>
            <div class="title">Laporan Bulanan</div>
            <div class="description">Lihat laporan pendapatan bulanan</div>
        </a>
    </div>
</main>

<script src="assets/js/app.js"></script>
<script>
function confirmLogout() {
    Modal.confirm('Apakah Anda yakin ingin keluar?', () => {
        window.location.href = "logout.php";
    });
}
</script>

</body>
</html>
