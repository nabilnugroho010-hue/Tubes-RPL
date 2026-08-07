<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "includes/auth.php";
date_default_timezone_set('Asia/Jakarta');
include "koneksi.php";
$pageTitle = "Konfirmasi Pesanan";
$pageSubtitle = "Verifikasi pembayaran pelanggan";

// Ambil semua pesanan yang sudah dibayar
$pesanan = mysqli_query($conn, "SELECT p.*, bayar.metode, bayar.bukti_url, bayar.tgl_bayar 
                                  FROM data_pesanan p 
                                  LEFT JOIN data_pembayaran bayar ON p.id_pesanan = bayar.id_pesanan 
                                  WHERE p.status = 'Sudah Dibayar' 
                                  ORDER BY p.tgl_pesanan DESC, p.id_pesanan DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pesanan - SPGFood</title>
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
        
        .btn-secondary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
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
        <span class="breadcrumb-item active">Konfirmasi Pesanan</span>
    </nav>

    <!-- Table -->
    <div class="table-container">
        <?php if (mysqli_num_rows($pesanan) > 0): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tanggal</th>
                    <th>Pelanggan</th>
                    <th>No Meja</th>
                    <th>Total</th>
                    <th>Metode</th>
                    <th>Bukti</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($p = mysqli_fetch_assoc($pesanan)): ?>
                <tr>
                    <td style="font-weight: 600;"><?= $p['id_pesanan'] ?></td>
                    <td><?= date('d F Y • H:i:s', strtotime($p['tgl_pesanan'])) ?> WIB</td>
                    <td><?= $p['nama_pelanggan'] ?></td>
                    <td><?= $p['no_meja'] ?></td>
                    <td style="color: #667eea; font-weight: 500;">Rp <?= number_format($p['total_harga'], 0, ',', '.') ?></td>
                    <td>
                        <span class="status-badge status-processing"><?= $p['metode'] ?></span>
                    </td>
                    <td>
                        <?php if ($p['bukti_url']): ?>
                        <a href="<?= htmlspecialchars($p['bukti_url']) ?>" target="_blank" class="btn btn-sm btn-secondary">📷 Lihat Bukti</a>
                        <?php else: ?>
                        <span style="color: #666;">-</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="detail_pesanan.php?id=<?= $p['id_pesanan'] ?>" class="btn btn-sm btn-primary">👁️ Detail</a>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="glass-card" style="text-align: center; padding: 40px;">
            <div style="font-size: 3rem; margin-bottom: 16px;">📭</div>
            <h3 style="color: #666; margin-bottom: 8px;">Belum Ada Pesanan</h3>
            <p style="color: #666;">Tidak ada pesanan yang menunggu konfirmasi pembayaran.</p>
        </div>
        <?php endif; ?>
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
