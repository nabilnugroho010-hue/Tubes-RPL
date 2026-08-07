<?php
session_start();
include "koneksi.php";
$pageTitle = "Tambah Menu";
$pageSubtitle = "Tambah menu makanan atau minuman baru";

$pesan = "";
$tampil_notif = false;

// Proses simpan menu
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['simpan_menu'])) {
    $nama_menu = trim($_POST['nama_menu']);
    $jenis_menu = $_POST['jenis_menu'];
    $harga = $_POST['harga'];
    $status = $_POST['status'];

    mysqli_query($koneksi, "INSERT INTO data_menu (nama_menu, jenis_menu, harga, status) 
                            VALUES ('$nama_menu', '$jenis_menu', '$harga', '$status')");

    $pesan = "Menu baru berhasil ditambahkan!";
    $tampil_notif = true;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Menu Baru - SPGFood</title>
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
        <a href="kelola_menu.php" class="breadcrumb-item">Kelola Menu</a>
        <span class="breadcrumb-separator">/</span>
        <span class="breadcrumb-item active">Tambah Menu</span>
    </nav>

    <!-- Form Container -->
    <div class="glass-container" style="max-width: 600px;">
        <form method="post" action="">
            <div class="form-group">
                <label class="form-label">Nama Menu</label>
                <input type="text" name="nama_menu" class="form-control" placeholder="Contoh: Nasi Goreng" required>
            </div>

            <div class="form-group">
                <label class="form-label">Jenis Menu</label>
                <select name="jenis_menu" class="form-control" required>
                    <option value="">-- Pilih Jenis --</option>
                    <option value="Makanan">Makanan</option>
                    <option value="Minuman">Minuman</option>
                    <option value="Camilan">Camilan</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Harga (Rp)</label>
                <input type="number" name="harga" class="form-control" placeholder="Contoh: 15000" min="0" required>
            </div>

            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-control">
                    <option value="Tersedia" selected>Tersedia</option>
                    <option value="Tidak Tersedia">Tidak Tersedia</option>
                </select>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" name="simpan_menu" class="btn btn-success">
                    <span>Simpan Menu</span>
                </button>
                <a href="kelola_menu.php" class="btn btn-outline">
                    <span>Batal</span>
                </a>
            </div>
        </form>
    </div>
</main>

<script src="assets/js/app.js"></script>
<script>
<?php if($tampil_notif): ?>
    document.addEventListener('DOMContentLoaded', () => {
        Toast.show('<?= $pesan ?>', 'success', 2000);
        setTimeout(() => {
            window.location.href = "kelola_menu.php";
        }, 2000);
    });
<?php endif; ?>

function confirmLogout() {
    Modal.confirm('Apakah Anda yakin ingin keluar?', () => {
        window.location.href = "logout.php";
    });
}
</script>

</body>
</html>