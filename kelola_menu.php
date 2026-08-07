<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "includes/auth.php";
date_default_timezone_set('Asia/Jakarta');
include "koneksi.php";
$pageTitle = "Kelola Menu";
$pageSubtitle = "Kelola menu makanan & minuman";

$pesan = "";
$tampil_notif = false;

// Proses hapus data
if(isset($_GET['hapus_id'])){
    $id_hapus = mysqli_real_escape_string($conn, $_GET['hapus_id']);

    // Cek dulu apakah menu ini sudah ada di pesanan
    $cek = mysqli_query($conn, "SELECT * FROM rincian_pesanan WHERE id_menu = '$id_hapus'");
    if(mysqli_num_rows($cek) > 0){
        $pesan = "Menu ini tidak dapat dihapus karena sudah tercatat dalam pesanan!";
        $tampil_notif = true;
    } else {
        // Jika belum ada, baru hapus
        $hapus = mysqli_query($conn, "DELETE FROM data_menu WHERE id_menu = '$id_hapus'");
        if($hapus){
            $pesan = "Menu berhasil dihapus!";
            $tampil_notif = true;
        } else {
            $pesan = "Gagal menghapus menu!";
            $tampil_notif = true;
        }
    }
}

// Ambil semua data menu dari database
$menu = mysqli_query($conn, "SELECT * FROM data_menu ORDER BY id_menu DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Menu - SPGFood</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            background-attachment: fixed !important;
        }
        
        .sidebar {
            background: rgba(255, 255, 255, 0.95) !important;
            border-right: 1px solid rgba(0, 0, 0, 0.1) !important;
        }
        
        .sidebar-header {
            border-bottom: 1px solid rgba(0, 0, 0, 0.1) !important;
        }
        
        .sidebar-logo {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        }
        
        .sidebar-title {
            color: #333 !important;
        }
        
        .sidebar-menu-item {
            color: #666 !important;
        }
        
        .sidebar-menu-item:hover {
            background: rgba(102, 126, 234, 0.1) !important;
            color: #333 !important;
        }
        
        .sidebar-menu-item.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            color: white !important;
        }
        
        .main-content {
            background: transparent !important;
        }
        
        .glass-card {
            background: white !important;
            border: 1px solid rgba(0, 0, 0, 0.1) !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1) !important;
        }
        
        .glass-card:hover {
            background: white !important;
            border-color: #667eea !important;
        }
        
        .header {
            border-bottom: 1px solid rgba(255, 255, 255, 0.2) !important;
        }
        
        .header-title {
            color: white !important;
        }
        
        .header-subtitle {
            color: rgba(255, 255, 255, 0.8) !important;
        }
        
        .header-user {
            background: rgba(255, 255, 255, 0.2) !important;
        }
        
        .header-user:hover {
            background: rgba(255, 255, 255, 0.3) !important;
        }
        
        .header-user-name {
            color: white !important;
        }
        
        .header-user-role {
            color: rgba(255, 255, 255, 0.8) !important;
        }
        
        .breadcrumb-item {
            color: rgba(255, 255, 255, 0.7) !important;
        }
        
        .breadcrumb-item:hover {
            color: white !important;
        }
        
        .breadcrumb-item.active {
            color: white !important;
        }
        
        .breadcrumb-separator {
            color: rgba(255, 255, 255, 0.7) !important;
        }
        
        .table {
            background: white !important;
        }
        
        .table thead {
            background: rgba(102, 126, 234, 0.1) !important;
            border-bottom: 2px solid #667eea !important;
        }
        
        .table th {
            color: #667eea !important;
        }
        
        .table td {
            color: #333 !important;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1) !important;
        }
        
        .table tbody tr:hover {
            background: rgba(102, 126, 234, 0.05) !important;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        }
        
        .btn-danger {
            background: linear-gradient(135deg, #ff4466 0%, #cc3355 100%) !important;
        }
        
        .btn-warning {
            background: linear-gradient(135deg, #ffaa00 0%, #cc8800 100%) !important;
        }
        
        .btn-success {
            background: linear-gradient(135deg, #00c853 0%, #00a843 100%) !important;
        }
    </style>
</head>
<body>

<?php include "includes/sidebar.php"; ?>

<!-- Main Content -->
<main class="main-content">
    <?php include "includes/header.php"; ?>

    <!-- Breadcrumb -->
    <nav class="breadcrumb">
        <a href="dashboard.php" class="breadcrumb-item">Dashboard</a>
        <span class="breadcrumb-separator">/</span>
        <span class="breadcrumb-item active">Kelola Menu</span>
    </nav>

    <!-- Action Bar -->
    <div class="d-flex justify-between align-center mb-3">
        <div></div>
        <a href="tambah_menu.php" class="btn btn-success">+ Tambah Menu</a>
    </div>

    <!-- Table -->
    <div class="table-container">
        <?php if (mysqli_num_rows($menu) > 0): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Menu</th>
                    <th>Jenis</th>
                    <th>Harga</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($data = mysqli_fetch_assoc($menu)): ?>
                <tr>
                    <td><?= $data['id_menu'] ?></td>
                    <td><?= $data['nama_menu'] ?></td>
                    <td><?= $data['jenis_menu'] ?></td>
                    <td style="color: var(--neon-cyan); font-weight: 500;">Rp <?= number_format($data['harga'], 0, ',', '.') ?></td>
                    <td>
                        <?php if ($data['status'] == 'Tersedia'): ?>
                            <span class="status-badge status-available">Tersedia</span>
                        <?php else: ?>
                            <span class="status-badge status-unavailable">Tidak Tersedia</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="ubah_menu.php?id=<?= $data['id_menu'] ?>" class="btn btn-sm btn-secondary">✏️ Ubah</a>
                            <button onclick="confirmDelete(<?= $data['id_menu'] ?>)" class="btn btn-sm btn-danger">🗑️ Hapus</button>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="glass-card" style="text-align: center; padding: 40px;">
            <div style="font-size: 3rem; margin-bottom: 16px;">📭</div>
            <h3 style="color: var(--text-muted); margin-bottom: 8px;">Belum Ada Menu</h3>
            <p style="color: var(--text-muted);">Silakan tambahkan menu makanan atau minuman.</p>
            <div style="margin-top: 24px;">
                <a href="tambah_menu.php" class="btn btn-primary">+ Tambah Menu</a>
            </div>
        </div>
        <?php endif; ?>
    </div>
</main>

<script src="assets/js/app.js"></script>
<script>
<?php if($tampil_notif): ?>
    document.addEventListener('DOMContentLoaded', () => {
        Toast.show('<?= $pesan ?>', '<?= strpos($pesan, 'berhasil') !== false ? 'success' : 'error' ?>');
    });
<?php endif; ?>

function confirmDelete(id) {
    Modal.confirm('Apakah Anda yakin ingin menghapus menu ini?', () => {
        window.location.href = "kelola_menu.php?hapus_id=" + id;
    });
}

function confirmLogout() {
    Modal.confirm('Apakah Anda yakin ingin keluar?', () => {
        window.location.href = "logout.php";
    });
}
</script>

</body>
</html>