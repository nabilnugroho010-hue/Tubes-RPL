<?php
// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}
?>

<!-- Sidebar -->
<aside class="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">🍽️</div>
        <div class="sidebar-title">SPGFood</div>
    </div>
    
    <nav class="sidebar-menu">
        <a href="index.php" class="sidebar-menu-item <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">
            <span class="sidebar-menu-icon">🏠</span>
            <span class="sidebar-menu-text">Home</span>
        </a>
        <a href="dashboard.php" class="sidebar-menu-item <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>">
            <span class="sidebar-menu-icon">📊</span>
            <span class="sidebar-menu-text">Dashboard</span>
        </a>
        <a href="kelola_menu.php" class="sidebar-menu-item <?= basename($_SERVER['PHP_SELF']) == 'kelola_menu.php' ? 'active' : '' ?>">
            <span class="sidebar-menu-icon">📋</span>
            <span class="sidebar-menu-text">Kelola Menu</span>
        </a>
        <a href="kelola_pesanan.php" class="sidebar-menu-item <?= basename($_SERVER['PHP_SELF']) == 'kelola_pesanan.php' ? 'active' : '' ?>">
            <span class="sidebar-menu-icon">🛒</span>
            <span class="sidebar-menu-text">Kelola Pesanan</span>
        </a>
        <a href="konfirmasi_pesanan.php" class="sidebar-menu-item <?= basename($_SERVER['PHP_SELF']) == 'konfirmasi_pesanan.php' ? 'active' : '' ?>">
            <span class="sidebar-menu-icon">✅</span>
            <span class="sidebar-menu-text">Konfirmasi Pesanan</span>
        </a>
        <a href="ubah_status.php" class="sidebar-menu-item <?= basename($_SERVER['PHP_SELF']) == 'ubah_status.php' ? 'active' : '' ?>">
            <span class="sidebar-menu-icon">🔄</span>
            <span class="sidebar-menu-text">Update Status</span>
        </a>
        <a href="laporan_harian.php" class="sidebar-menu-item <?= basename($_SERVER['PHP_SELF']) == 'laporan_harian.php' ? 'active' : '' ?>">
            <span class="sidebar-menu-icon">📊</span>
            <span class="sidebar-menu-text">Laporan Harian</span>
        </a>
        <a href="laporan_bulanan.php" class="sidebar-menu-item <?= basename($_SERVER['PHP_SELF']) == 'laporan_bulanan.php' ? 'active' : '' ?>">
            <span class="sidebar-menu-icon">📈</span>
            <span class="sidebar-menu-text">Laporan Bulanan</span>
        </a>
    </nav>
    
    <div class="sidebar-footer">
        <button onclick="confirmLogout()" class="btn btn-danger w-100">
            <span>🚪 Logout</span>
        </button>
    </div>
</aside>
