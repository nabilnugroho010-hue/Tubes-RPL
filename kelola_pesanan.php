<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "includes/auth.php";
date_default_timezone_set('Asia/Jakarta');
include "koneksi.php";
$pageTitle = "Kelola Pesanan";
$pageSubtitle = "Kelola dan update status pesanan";

// Proses hapus satu data saja
if(isset($_GET['hapus_id'])){
    $id_hapus = mysqli_real_escape_string($conn, $_GET['hapus_id']);
    // Hapus dulu rincian pesanan yang terhubung
    mysqli_query($conn, "DELETE FROM rincian_pesanan WHERE id_pesanan = '$id_hapus'");
    // Baru hapus data pesanan utamanya
    mysqli_query($conn, "DELETE FROM data_pesanan WHERE id_pesanan = '$id_hapus'");
    // Alihkan agar tidak hapus ulang saat refresh
    header("Location: kelola_pesanan.php?sukses_hapus=1");
    exit;
}

// Ambil data pesanan seperti biasa (semua status)
$pesanan = mysqli_query($conn, "SELECT * FROM data_pesanan ORDER BY tgl_pesanan DESC, id_pesanan DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pesanan - SPGFood</title>
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
            border-bottom: 1px solid rgba(0, 0, 0, 0.1) !important;
        }
        
        .header-title {
            color: #333 !important;
        }
        
        .header-subtitle {
            color: #666 !important;
        }
        
        .header-user {
            background: rgba(255, 255, 255, 0.95) !important;
        }
        
        .header-user:hover {
            background: white !important;
        }
        
        .header-user-name {
            color: #333 !important;
        }
        
        .header-user-role {
            color: #666 !important;
        }
        
        .breadcrumb-item {
            color: #666 !important;
        }
        
        .breadcrumb-item:hover {
            color: #333 !important;
        }
        
        .breadcrumb-item.active {
            color: #333 !important;
        }
        
        .breadcrumb-separator {
            color: #666 !important;
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
        <span class="breadcrumb-item active">Kelola Pesanan</span>
    </nav>

    <!-- Table -->
    <div class="table-container">
        <?php if (mysqli_num_rows($pesanan) > 0): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>No Meja</th>
                    <th>Tanggal</th>
                    <th>Total Bayar</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($p = mysqli_fetch_assoc($pesanan)): ?>
                <tr>
                    <td style="font-weight: 600; color: var(--neon-cyan);"><?= $p['kode_pelanggan'] ?></td>
                    <td><?= $p['nama_pelanggan'] ?></td>
                    <td><?= $p['no_meja'] ?></td>
                    <td><?= date('d F Y • H:i:s', strtotime($p['tgl_pesanan'])) ?> WIB</td>
                    <td style="color: var(--neon-cyan); font-weight: 500;">Rp <?= number_format($p['total_harga'], 0, ',', '.') ?></td>
                    <td>
                        <?php 
                        $statusClass = 'status-pending';
                        if (strpos($p['status'], 'diproses') !== false) $statusClass = 'status-processing';
                        elseif (strpos($p['status'], 'Dibayar') !== false) $statusClass = 'status-paid';
                        elseif (strpos($p['status'], 'Selesai') !== false) $statusClass = 'status-completed';
                        ?>
                        <span class="status-badge <?= $statusClass ?>"><?= $p['status'] ?></span>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <button onclick="confirmDelete(<?= $p['id_pesanan'] ?>)" class="btn btn-sm btn-danger">🗑️ Hapus</button>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="glass-card" style="text-align: center; padding: 40px;">
            <div style="font-size: 3rem; margin-bottom: 16px;">📭</div>
            <h3 style="color: var(--text-muted); margin-bottom: 8px;">Belum Ada Pesanan</h3>
            <p style="color: var(--text-muted);">Belum ada pesanan masuk saat ini.</p>
        </div>
        <?php endif; ?>
    </div>
</main>

<script src="assets/js/app.js"></script>
<script>
<?php if(isset($_GET['sukses_hapus'])): ?>
    document.addEventListener('DOMContentLoaded', () => {
        Toast.show('Data pesanan berhasil dihapus!', 'success');
    });
<?php endif; ?>

function confirmDelete(id) {
    Modal.confirm('Yakin ingin menghapus pesanan ini? Data di laporan juga akan ikut hilang!', () => {
        window.location.href = "kelola_pesanan.php?hapus_id=" + id;
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