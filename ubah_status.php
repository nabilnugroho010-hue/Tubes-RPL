<?php
session_start();
include "koneksi.php";
$pageTitle = "Ubah Status Pesanan";
$pageSubtitle = "Update status pesanan pelanggan";

// Proses simpan hanya ubah status saja
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['simpan_status'])) {
    $id_pesanan = $_POST['id_pesanan'];
    $status_baru = $_POST['status_pesanan'];

    // Hanya update kolom yang ADA di tabel kamu
    mysqli_query($koneksi, "UPDATE data_pesanan 
        SET status = '$status_baru' 
        WHERE id_pesanan = '$id_pesanan'");

    // Alihkan agar refresh tidak ulang simpan
    header("Location: ubah_status.php?id=$id_pesanan&sukses=1");
    exit;
}

// Ambil data pesanan
$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM data_pesanan WHERE id_pesanan = '$id'"));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Status Pesanan - SPGFood</title>
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
        <a href="kelola_pesanan.php" class="breadcrumb-item">Kelola Pesanan</a>
        <span class="breadcrumb-separator">/</span>
        <span class="breadcrumb-item active">Ubah Status</span>
    </nav>

    <!-- Form Container -->
    <div class="glass-container" style="max-width: 600px;">
        <div style="text-align: center; margin-bottom: 24px;">
            <h3 style="margin-bottom: 8px;">✏️ Ubah Status Pesanan #<?= $data['id_pesanan'] ?></h3>
            <p style="color: var(--text-muted);">Update status pesanan pelanggan</p>
        </div>

        <?php if(isset($_GET['sukses'])): ?>
        <div class="glass-card" style="margin-bottom: 24px; background: rgba(0, 255, 136, 0.1); border-color: rgba(0, 255, 136, 0.3);">
            <p style="color: var(--success); text-align: center; margin: 0;">✅ Status berhasil diubah menjadi: <b><?= $data['status'] ?></b></p>
        </div>
        <?php endif; ?>

        <form method="post" action="">
            <input type="hidden" name="id_pesanan" value="<?= $data['id_pesanan'] ?>">

            <div class="form-group">
                <label class="form-label">Status Pesanan</label>
                <select name="status_pesanan" class="form-control">
                    <option value="Menunggu diproses" <?= $data['status'] == 'Menunggu diproses' ? 'selected' : '' ?>>Menunggu diproses</option>
                    <option value="Sedang diproses" <?= $data['status'] == 'Sedang diproses' ? 'selected' : '' ?>>Sedang diproses</option>
                    <option value="Sudah Dibayar" <?= $data['status'] == 'Sudah Dibayar' ? 'selected' : '' ?>>Sudah Dibayar</option>
                    <option value="Selesai" <?= $data['status'] == 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                </select>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" name="simpan_status" class="btn btn-primary">
                    <span>Simpan Perubahan</span>
                </button>
                <a href="kelola_pesanan.php" class="btn btn-outline">
                    <span>Batal</span>
                </a>
            </div>
        </form>
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