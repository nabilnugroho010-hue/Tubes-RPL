<?php
session_start();
include "koneksi.php";
$pageTitle = "Konfirmasi Pesanan";
$pageSubtitle = "Verifikasi pembayaran pelanggan";

// Cek apakah admin sudah login
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

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
</head>
<body>

<?php include "includes/sidebar.php"; ?>

<!-- Main Content -->
<main class="main-content">
    <?php include "includes/header.php"; ?>

    <!-- Breadcrumb -->
    <nav class="breadcrumb">
        <a href="index.php" class="breadcrumb-item">Dashboard</a>
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
                    <td style="color: var(--neon-cyan); font-weight: 500;">Rp <?= number_format($p['total_harga'], 0, ',', '.') ?></td>
                    <td>
                        <span class="status-badge status-processing"><?= $p['metode'] ?></span>
                    </td>
                    <td>
                        <?php if ($p['bukti_url']): ?>
                        <a href="<?= $p['bukti_url'] ?>" target="_blank" class="btn btn-sm btn-secondary">📷 Lihat Bukti</a>
                        <?php else: ?>
                        <span style="color: var(--text-muted);">-</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="detail_pesanan.php?id=<?= $p['id_pesanan'] ?>" class="btn btn-sm btn-primary">👁️ Detail</a>
                            <a href="ubah_status.php?id=<?= $p['id_pesanan'] ?>" class="btn btn-sm btn-warning">✏️ Status</a>
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
            <p style="color: var(--text-muted);">Tidak ada pesanan yang menunggu konfirmasi pembayaran.</p>
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
