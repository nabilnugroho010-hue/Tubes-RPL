<?php
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
                            <a href="ubah_status.php?id=<?= $p['id_pesanan'] ?>" class="btn btn-sm btn-warning">Ubah Status</a>
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