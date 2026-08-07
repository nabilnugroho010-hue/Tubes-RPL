<?php
session_start();
date_default_timezone_set('Asia/Jakarta');
include "../koneksi.php";

$status_pesanan = "";
$auto_check = false;
$detail_pesanan = null;
$data_pesanan = null;

// Auto-check status dari URL parameter (setelah pembayaran) atau session
if(isset($_GET['nomor']) && !empty($_GET['nomor'])){
    $nomor_pesanan = trim($_GET['nomor']);
    $cek = mysqli_fetch_assoc(mysqli_query($conn, 
        "SELECT * FROM data_pesanan WHERE nomor_pesanan = '$nomor_pesanan' LIMIT 1"));
    
    if($cek){
        $data_pesanan = $cek;
        $id_pesanan = $cek['id_pesanan'];
        
        // Ambil detail menu dari database
        $detail_pesanan = mysqli_query($conn, 
            "SELECT d.*, m.nama_menu, m.harga 
             FROM rincian_pesanan d 
             JOIN data_menu m ON d.id_menu = m.id_menu 
             WHERE d.id_pesanan = '$id_pesanan'");
        
        $auto_check = true;
    } else {
        $status_pesanan = "❌ Nomor pesanan tidak ditemukan!";
    }
}
// Cek dari session id_pesanan (jika ada)
elseif(isset($_SESSION['id_pesanan'])){
    $id_pesanan = $_SESSION['id_pesanan'];
    $cek = mysqli_fetch_assoc(mysqli_query($conn, 
        "SELECT * FROM data_pesanan WHERE id_pesanan = '$id_pesanan'"));
    
    if($cek){
        $data_pesanan = $cek;
        
        // Ambil detail menu dari database
        $detail_pesanan = mysqli_query($conn, 
            "SELECT d.*, m.nama_menu, m.harga 
             FROM rincian_pesanan d 
             JOIN data_menu m ON d.id_menu = m.id_menu 
             WHERE d.id_pesanan = '$id_pesanan'");
        
        $auto_check = true;
    } else {
        $status_pesanan = "❌ Pesanan tidak ditemukan!";
    }
}

// Manual check dari form
if(isset($_POST['cek_status'])){
    $kode = trim($_POST['kode_cek']);
    $cek = mysqli_fetch_assoc(mysqli_query($conn, 
        "SELECT * FROM data_pesanan WHERE kode_pelanggan = '$kode' ORDER BY id_pesanan DESC LIMIT 1"));
    
    if($cek){
        $data_pesanan = $cek;
        $id_pesanan = $cek['id_pesanan'];
        
        // Ambil detail menu dari database
        $detail_pesanan = mysqli_query($conn, 
            "SELECT d.*, m.nama_menu, m.harga 
             FROM rincian_pesanan d 
             JOIN data_menu m ON d.id_menu = m.id_menu 
             WHERE d.id_pesanan = '$id_pesanan'");
    } else {
        $status_pesanan = "❌ Kode pelanggan tidak ditemukan! Periksa kembali kode Anda.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Status Pesanan - SPGFood</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="glass-container" style="max-width: 700px; margin: 40px auto; padding: 32px;">
    <!-- Header -->
    <div style="text-align: center; margin-bottom: 32px;">
        <h2 style="margin-bottom: 8px;">🔍 Cek Status Pesanan</h2>
        <p style="color: var(--text-muted);"><?= $auto_check ? 'Status pesanan Anda' : 'Lacak status pesanan Anda' ?></p>
    </div>

    <?php if(!empty($status_pesanan) && !$data_pesanan): ?>
    <div class="glass-card mb-3" style="background: rgba(255, 68, 102, 0.1); border-color: rgba(255, 68, 102, 0.3);">
        <p style="line-height: 1.8; margin: 0; color: var(--error);"><?= $status_pesanan ?></p>
    </div>
    <?php endif; ?>

    <?php if($data_pesanan): ?>
    <!-- Ringkasan Pesanan Real-time -->
    <div class="glass-card mb-3">
        <h3 style="margin-bottom: 16px; font-size: 1.1rem;">📋 Ringkasan Pesanan</h3>
        
        <!-- Informasi Pesanan -->
        <div class="glass-card" style="background: rgba(0, 245, 255, 0.05); border-color: rgba(0, 245, 255, 0.2); margin-bottom: 16px;">
            <div class="grid grid-2">
                <div>
                    <p style="color: var(--text-muted); margin-bottom: 4px; font-size: 0.85rem;">Nama Pelanggan</p>
                    <p style="font-weight: 500; margin: 0;"><?= $data_pesanan['nama_pelanggan'] ?></p>
                </div>
                <div>
                    <p style="color: var(--text-muted); margin-bottom: 4px; font-size: 0.85rem;">No Meja</p>
                    <p style="font-weight: 500; margin: 0;"><?= $data_pesanan['no_meja'] ?></p>
                </div>
                <div>
                    <p style="color: var(--text-muted); margin-bottom: 4px; font-size: 0.85rem;">Kode Pelanggan</p>
                    <p style="font-weight: 500; color: var(--neon-cyan); margin: 0;"><?= $data_pesanan['kode_pelanggan'] ?></p>
                </div>
                <div>
                    <p style="color: var(--text-muted); margin-bottom: 4px; font-size: 0.85rem;">Tanggal</p>
                    <p style="font-weight: 500; margin: 0;"><?= date('d F Y • H:i:s', strtotime($data_pesanan['tgl_pesanan'])) ?> WIB</p>
                </div>
                <div>
                    <p style="color: var(--text-muted); margin-bottom: 4px; font-size: 0.85rem;">Status</p>
                    <p style="font-weight: 500; margin: 0;" data-status><?= $data_pesanan['status'] ?></p>
                </div>
            </div>
        </div>

        <!-- Daftar Menu dari Database Real-time -->
        <div style="margin-bottom: 16px;">
            <?php 
            while ($d = mysqli_fetch_assoc($detail_pesanan)): 
            ?>
            <div class="menu-item" style="background: rgba(255, 255, 255, 0.03);">
                <div class="menu-item-info">
                    <div class="menu-item-name"><?= $d['nama_menu'] ?></div>
                    <div class="menu-item-price">Rp <?= number_format($d['harga'], 0, ',', '.') ?></div>
                </div>
                <div class="menu-item-quantity">
                    <div style="text-align: center;">
                        <div style="font-size: 1.5rem; font-weight: 600; color: var(--neon-cyan);"><?= $d['jumlah'] ?>x</div>
                        <div style="font-size: 0.85rem; color: var(--text-muted);">Jumlah</div>
                    </div>
                </div>
                <div class="menu-item-subtotal" style="text-align: right;">
                    <div style="font-size: 1.2rem; font-weight: 600; color: var(--neon-cyan);">Rp <?= number_format($d['harga'] * $d['jumlah'], 0, ',', '.') ?></div>
                    <div style="font-size: 0.85rem; color: var(--text-muted);">Subtotal</div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>

        <!-- Total -->
        <div style="text-align: right; margin-top: 16px; padding-top: 16px; border-top: 1px solid var(--glass-border);">
            <p style="color: var(--text-muted); margin-bottom: 4px;">Total Bayar</p>
            <p style="font-size: 1.8rem; font-weight: 600; color: var(--neon-cyan); margin: 0;">Rp <?= number_format($data_pesanan['total_harga'], 0, ',', '.') ?></p>
        </div>
    </div>
    <?php endif; ?>

    <?php if(!$auto_check): ?>
    <form method="post" action="">
        <div class="form-group">
            <label class="form-label">Masukkan Kode Pelanggan</label>
            <input type="text" name="kode_cek" class="form-control" placeholder="Contoh: 118" required>
        </div>
        <button type="submit" name="cek_status" class="btn btn-primary w-100">
            <span>🔎 Cek Sekarang</span>
        </button>
    </form>
    <?php endif; ?>

    <div style="text-align: center; margin-top: 24px;">
        <?php if($auto_check): ?>
        <div class="d-flex gap-2 justify-center">
            <a href="pesan_pelanggan.php" class="btn btn-success">🍽️ Pesan Lagi</a>
            <a href="riwayat_pesanan.php" class="btn btn-secondary">📜 Riwayat Pesanan</a>
        </div>
        <?php else: ?>
        <a href="pesan_pelanggan.php" class="btn btn-outline">⬅️ Kembali ke Halaman Pemesanan</a>
        <?php endif; ?>
    </div>
</div>

<script src="../assets/js/app.js"></script>
<script>
<?php if($data_pesanan): ?>
document.addEventListener('DOMContentLoaded', function() {
    const idPesanan = <?= $data_pesanan['id_pesanan'] ?>;
    const kodePelanggan = '<?= $data_pesanan['kode_pelanggan'] ?>';
    
    // Start polling for status updates
    const poller = new StatusPoller({
        idPesanan: idPesanan,
        kodePelanggan: kodePelanggan,
        interval: 5000, // Check every 5 seconds
        onStatusChange: function(data) {
            // Update status display
            const statusElement = document.querySelector('[data-status]');
            if (statusElement) {
                statusElement.textContent = data.status || (data.data && data.data.status);
            }
            
            // Show toast notification
            const newStatus = data.status || (data.data && data.data.status);
            Toast.show(`Status pesanan diperbarui: ${newStatus}`, 'info');
            
            // Refresh page to show updated data
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        },
        onError: function(error) {
            console.error('Polling error:', error);
        }
    });
    
    poller.start();
    
    // Stop polling when leaving page
    window.addEventListener('beforeunload', () => {
        poller.stop();
    });
});
<?php endif; ?>
</script>

</body>
</html>