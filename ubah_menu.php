<?php
include "includes/auth.php";
date_default_timezone_set('Asia/Jakarta');
include "koneksi.php";
$pageTitle = "Ubah Menu";
$pageSubtitle = "Edit informasi menu yang ada";

// Ambil data menu
if (!isset($_GET['id'])) {
    header("Location: kelola_menu.php");
    exit;
}
$id = mysqli_real_escape_string($conn, $_GET['id']);
$ambil = mysqli_query($conn, "SELECT * FROM data_menu WHERE id_menu = '$id'");
$menu = mysqli_fetch_assoc($ambil);

// Proses simpan perubahan
$pesan = "";
$tampil_notif = false;
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['simpan_perubahan'])) {
    $id_menu = mysqli_real_escape_string($conn, $_POST['id_menu']);
    $nama_menu = mysqli_real_escape_string($conn, trim($_POST['nama_menu']));
    $jenis_menu = mysqli_real_escape_string($conn, $_POST['jenis_menu']);
    $harga = mysqli_real_escape_string($conn, $_POST['harga']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    mysqli_query($conn, "UPDATE data_menu SET
                            nama_menu = '$nama_menu',
                            jenis_menu = '$jenis_menu',
                            harga = '$harga',
                            status = '$status'
                            WHERE id_menu = '$id_menu'");

    $pesan = "Data menu berhasil diperbarui!";
    $tampil_notif = true;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Data Menu - SPGFood</title>
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
        <a href="kelola_menu.php" class="breadcrumb-item">Kelola Menu</a>
        <span class="breadcrumb-separator">/</span>
        <span class="breadcrumb-item active">Ubah Menu</span>
    </nav>

    <!-- Form Container -->
    <div class="glass-container" style="max-width: 600px;">
        <form method="post" action="">
            <input type="hidden" name="id_menu" value="<?= $menu['id_menu'] ?>">

            <div class="form-group">
                <label class="form-label">Nama Menu</label>
                <input type="text" name="nama_menu" class="form-control" value="<?= $menu['nama_menu'] ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label">Jenis Menu</label>
                <select name="jenis_menu" class="form-control" required>
                    <option value="Makanan" <?= $menu['jenis_menu'] == 'Makanan' ? 'selected' : '' ?>>Makanan</option>
                    <option value="Minuman" <?= $menu['jenis_menu'] == 'Minuman' ? 'selected' : '' ?>>Minuman</option>
                    <option value="Camilan" <?= $menu['jenis_menu'] == 'Camilan' ? 'selected' : '' ?>>Camilan</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Harga (Rp)</label>
                <input type="number" name="harga" class="form-control" value="<?= $menu['harga'] ?>" min="0" required>
            </div>

            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-control">
                    <option value="Tersedia" <?= $menu['status'] == 'Tersedia' ? 'selected' : '' ?>>Tersedia</option>
                    <option value="Tidak Tersedia" <?= $menu['status'] == 'Tidak Tersedia' ? 'selected' : '' ?>>Tidak Tersedia</option>
                </select>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" name="simpan_perubahan" class="btn btn-warning">
                    <span>Simpan Perubahan</span>
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