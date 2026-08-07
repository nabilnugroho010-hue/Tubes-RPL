<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "includes/auth.php";
date_default_timezone_set('Asia/Jakarta');
include "koneksi.php";
$pageTitle = "Laporan Harian";
$pageSubtitle = "Lihat laporan pendapatan harian";

// Ambil tanggal yang dipilih, kalau tidak ada pakai hari ini
$hari = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d');

$ambil = mysqli_query($conn, "SELECT id_pesanan, tgl_pesanan, kode_pelanggan, total_harga, nama_pelanggan, no_meja, status, nomor_pesanan
                                  FROM data_pesanan 
                                  WHERE DATE(tgl_pesanan) = '$hari' 
                                  ORDER BY tgl_pesanan DESC");

$total_pendapatan = 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Harian - SPGFood</title>
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
        
        .btn-success {
            background: linear-gradient(135deg, #00c853 0%, #00a843 100%) !important;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
            color: #333 !important;
            background: white !important;
        }
        
        .form-control::placeholder {
            color: #999 !important;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #667eea;
            color: #333 !important;
            background: white !important;
        }
        
        .form-control option {
            color: #333 !important;
            background: white !important;
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
        <span class="breadcrumb-item active">Laporan Harian</span>
    </nav>

    <!-- Date Filter -->
    <div class="glass-card mb-3">
        <form method="get" class="d-flex align-center gap-2" style="flex-wrap: wrap;">
            <label style="color: #333 !important;">Tanggal:</label>
            <input type="date" name="tanggal" value="<?= $hari ?>" class="form-control" style="width: auto;" required>
            <button type="submit" class="btn btn-primary">Tampilkan</button>
            <button type="button" onclick="window.print()" class="btn btn-secondary">🖨️ Cetak Laporan</button>
        </form>
    </div>

    <!-- Table -->
    <div class="table-container">
        <?php if (mysqli_num_rows($ambil) > 0): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>No. Pesanan</th>
                    <th>Tanggal & Jam</th>
                    <th>Pelanggan</th>
                    <th>No Meja</th>
                    <th>Status</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php while($data = mysqli_fetch_assoc($ambil)): ?>
                <tr>
                    <td style="font-weight: 600; color: var(--neon-cyan);"><?= $data['nomor_pesanan'] ?? '#' . $data['id_pesanan'] ?></td>
                    <td><?= date('d F Y • H:i:s', strtotime($data['tgl_pesanan'])) ?> WIB</td>
                    <td>
                        <div style="font-weight: 500;"><?= $data['nama_pelanggan'] ?></div>
                        <div style="font-size: 0.85rem; color: var(--text-muted);"><?= !empty($data['kode_pelanggan']) ? $data['kode_pelanggan'] : '-' ?></div>
                    </td>
                    <td><?= $data['no_meja'] ?></td>
                    <td>
                        <?php 
                        $statusClass = 'status-pending';
                        if (strpos($data['status'], 'diproses') !== false) $statusClass = 'status-processing';
                        elseif (strpos($data['status'], 'Dibayar') !== false) $statusClass = 'status-paid';
                        elseif (strpos($data['status'], 'Selesai') !== false) $statusClass = 'status-completed';
                        ?>
                        <span class="status-badge <?= $statusClass ?>"><?= $data['status'] ?></span>
                    </td>
                    <td style="color: var(--neon-cyan); font-weight: 500;">Rp <?= number_format($data['total_harga'], 0, ',', '.') ?></td>
                </tr>
                <?php $total_pendapatan += $data['total_harga']; endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="glass-card" style="text-align: center; padding: 40px;">
            <div style="font-size: 3rem; margin-bottom: 16px;">📭</div>
            <h3 style="color: var(--text-muted); margin-bottom: 8px;">Tidak Ada Data</h3>
            <p style="color: var(--text-muted);">Tidak ada pesanan pada tanggal ini.</p>
        </div>
        <?php endif; ?>
    </div>

    <!-- Total Card -->
    <div class="glass-card" style="background: rgba(0, 245, 255, 0.1); border-color: rgba(0, 245, 255, 0.3); margin-top: 24px;">
        <div class="d-flex justify-between align-center">
            <div>
                <p style="color: var(--text-muted); margin-bottom: 4px;">Total Pendapatan Hari Ini</p>
                <p style="font-size: 1.5rem; font-weight: 600; color: var(--neon-cyan); margin: 0;">Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></p>
            </div>
            <div style="font-size: 2rem;">💰</div>
        </div>
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