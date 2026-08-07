<?php
include "includes/auth.php";
date_default_timezone_set('Asia/Jakarta');
include "koneksi.php";
$pageTitle = "Detail Pesanan";
$pageSubtitle = "Detail lengkap pesanan pelanggan";

if (!isset($_GET['id'])) {
    header("Location: kelola_pesanan.php");
    exit;
}
$id = mysqli_real_escape_string($conn, $_GET['id']);
$pesanan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM data_pesanan WHERE id_pesanan = '$id'"));
$detail = mysqli_query($conn, "SELECT d.*, m.nama_menu, m.harga
                                   FROM rincian_pesanan d
                                   JOIN data_menu m ON d.id_menu = m.id_menu
                                   WHERE d.id_pesanan = '$id'");
$bayar = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM data_pembayaran WHERE id_pesanan = '$id'"));

// Proses simpan pembayaran
if(isset($_POST['simpan_bayar'])){
    $metode = mysqli_real_escape_string($conn, $_POST['metode']);
    $bukti = mysqli_real_escape_string($conn, $_POST['bukti_url']);
    mysqli_query($conn, "INSERT INTO data_pembayaran (id_pesanan, metode, bukti_url, tgl_bayar)
                            VALUES ('$id', '$metode', '$bukti', NOW())");
    // Update status dan metode pembayaran di data_pesanan sebagai single source of truth
    mysqli_query($conn, "UPDATE data_pesanan
                            SET status = 'Sudah Dibayar', metode_pembayaran = '$metode'
                            WHERE id_pesanan = '$id'");
    header("Location: detail_pesanan.php?id=$id");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan - SPGFood</title>
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
        <a href="kelola_pesanan.php" class="breadcrumb-item">Kelola Pesanan</a>
        <span class="breadcrumb-separator">/</span>
        <span class="breadcrumb-item active">Detail Pesanan</span>
    </nav>

    <!-- Info Card -->
    <div class="glass-card mb-3">
        <h3 style="margin-bottom: 16px; font-size: 1.1rem;">Informasi Pesanan #<?= $pesanan['id_pesanan'] ?></h3>
        <div class="grid grid-2">
            <div>
                <p style="color: var(--text-muted); margin-bottom: 4px;">Tanggal</p>
                <p style="font-weight: 500;"><?= date('d F Y • H:i:s', strtotime($pesanan['tgl_pesanan'])) ?> WIB</p>
            </div>
            <div>
                <p style="color: var(--text-muted); margin-bottom: 4px;">ID Pelanggan</p>
                <p style="font-weight: 500; color: var(--neon-cyan);"><?= $pesanan['kode_pelanggan'] ?? '-' ?></p>
            </div>
            <div>
                <p style="color: var(--text-muted); margin-bottom: 4px;">Nama Pelanggan</p>
                <p style="font-weight: 500;"><?= $pesanan['nama_pelanggan'] ?></p>
            </div>
            <div>
                <p style="color: var(--text-muted); margin-bottom: 4px;">No Meja</p>
                <p style="font-weight: 500;"><?= $pesanan['no_meja'] ?></p>
            </div>
            <div>
                <p style="color: var(--text-muted); margin-bottom: 4px;">Status</p>
                <p style="font-weight: 500;"><?= $pesanan['status'] ?? '-' ?></p>
            </div>
            <div>
                <p style="color: var(--text-muted); margin-bottom: 4px;">Metode Pembayaran</p>
                <p style="font-weight: 500;"><?= $pesanan['metode_pembayaran'] ?? '-' ?></p>
            </div>
            <div>
                <p style="color: var(--text-muted); margin-bottom: 4px;">Total Bayar</p>
                <p style="font-weight: 500; color: var(--neon-cyan);">Rp <?= number_format($pesanan['total_harga'],0,',','.') ?></p>
            </div>
        </div>
    </div>

    <!-- Items Table -->
    <div class="glass-card mb-3">
        <h3 style="margin-bottom: 16px; font-size: 1.1rem;">🛍️ Daftar Item</h3>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Menu</th>
                        <th>Harga Satuan</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($d = mysqli_fetch_assoc($detail)){ ?>
                    <tr>
                        <td><?= $d['nama_menu'] ?></td>
                        <td>Rp <?= number_format($d['harga'],0,',','.') ?></td>
                        <td><?= $d['jumlah'] ?></td>
                        <td style="color: var(--neon-cyan); font-weight: 500;">Rp <?= number_format($d['harga'] * $d['jumlah'],0,',','.') ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Payment Section -->
    <?php if(!$bayar){ ?>
    <div class="glass-card">
        <h3 style="margin-bottom: 16px; font-size: 1.1rem;">💳 Proses Pembayaran</h3>
        <form method="post">
            <div class="form-group">
                <label class="form-label">Metode Pembayaran</label>
                <select name="metode" class="form-control" required>
                    <option value="Tunai">Tunai</option>
                    <option value="Transfer Bank">Transfer Bank</option>
                    <option value="E-Wallet">E-Wallet</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Bukti Pembayaran / Keterangan</label>
                <input type="text" name="bukti_url" class="form-control" placeholder="Contoh: bukti_001.jpg">
            </div>
            <button type="submit" name="simpan_bayar" class="btn btn-success w-100">
                <span>Konfirmasi Pembayaran</span>
            </button>
        </form>
    </div>
    <?php } else { ?>
    <div class="glass-card" style="background: rgba(0, 255, 136, 0.1); border-color: rgba(0, 255, 136, 0.3);">
        <h3 style="margin-bottom: 16px; font-size: 1.1rem; color: var(--success);">✅ Pembayaran Selesai</h3>
        <div class="grid grid-3">
            <div>
                <p style="color: var(--text-muted); margin-bottom: 4px;">Metode Pembayaran</p>
                <p style="font-weight: 500;"><?= $bayar['metode'] ?></p>
            </div>
            <div>
                <p style="color: var(--text-muted); margin-bottom: 4px;">Tanggal Bayar</p>
                <p style="font-weight: 500;"><?= $bayar['tgl_bayar'] ?></p>
            </div>
            <div>
                <p style="color: var(--text-muted); margin-bottom: 4px;">Bukti</p>
                <p style="font-weight: 500;"><?= $bayar['bukti_url'] ?></p>
            </div>
        </div>
    </div>
    <?php } ?>
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