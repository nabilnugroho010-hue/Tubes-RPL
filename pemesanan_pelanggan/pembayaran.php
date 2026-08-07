<?php
session_start();
include "../koneksi.php";

// Cek apakah ada id_pesanan di session
if (!isset($_SESSION['id_pesanan'])) {
    header("Location: pesan_pelanggan.php");
    exit;
}

$id_pesanan = $_SESSION['id_pesanan'];

// Ambil data pesanan berdasarkan id_pesanan dari session
$pesanan = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM data_pesanan WHERE id_pesanan = '$id_pesanan'"));

if (!$pesanan) {
    header("Location: pesan_pelanggan.php");
    exit;
}

// Ambil detail pesanan
$detail = mysqli_query($koneksi, "SELECT d.*, m.nama_menu, m.harga 
                                   FROM rincian_pesanan d 
                                   JOIN data_menu m ON d.id_menu = m.id_menu 
                                   WHERE d.id_pesanan = '$id_pesanan'");

// Cek apakah pembayaran sudah dikonfirmasi
$cek_pembayaran = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM data_pembayaran WHERE id_pesanan = '$id_pesanan'"));

// Update metode pembayaran di data_pesanan jika sudah ada
if ($cek_pembayaran) {
    mysqli_query($koneksi, "UPDATE data_pesanan SET metode_pembayaran = '" . $cek_pembayaran['metode'] . "' WHERE id_pesanan = '$id_pesanan'");
    
    // Redirect ke halaman pembayaran berhasil jika pembayaran sudah selesai
    header("Location: pembayaran_berhasil.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - SPGFood</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="glass-container" style="max-width: 700px; margin: 40px auto; padding: 32px;">
    <!-- Header -->
    <div style="text-align: center; margin-bottom: 32px;">
        <h2 style="margin-bottom: 8px;">💳 Pembayaran</h2>
        <p style="color: var(--text-muted);">Selesaikan pembayaran pesanan Anda</p>
    </div>

    <?php if ($cek_pembayaran): ?>
    <!-- Sudah Bayar -->
    <div class="glass-card" style="margin-bottom: 24px; background: rgba(0, 255, 136, 0.1); border-color: rgba(0, 255, 136, 0.3);">
        <div style="text-align: center;">
            <div style="font-size: 3rem; margin-bottom: 16px;">✅</div>
            <h3 style="color: var(--success); margin-bottom: 8px;">Pembayaran Berhasil!</h3>
            <p style="color: var(--text-muted);">Pesanan Anda sedang diproses</p>
        </div>
    </div>
    <?php else: ?>
    <!-- Belum Bayar -->
    <div class="glass-card mb-3">
        <h3 style="margin-bottom: 16px; font-size: 1.1rem;">📋 Ringkasan Pesanan</h3>
        
        <!-- Informasi Pesanan -->
        <div class="glass-card" style="background: rgba(0, 245, 255, 0.05); border-color: rgba(0, 245, 255, 0.2); margin-bottom: 16px;">
            <div class="grid grid-2">
                <div>
                    <p style="color: var(--text-muted); margin-bottom: 4px; font-size: 0.85rem;">Nama Pelanggan</p>
                    <p style="font-weight: 500; margin: 0;"><?= $pesanan['nama_pelanggan'] ?></p>
                </div>
                <div>
                    <p style="color: var(--text-muted); margin-bottom: 4px; font-size: 0.85rem;">No Meja</p>
                    <p style="font-weight: 500; margin: 0;"><?= $pesanan['no_meja'] ?></p>
                </div>
                <div>
                    <p style="color: var(--text-muted); margin-bottom: 4px; font-size: 0.85rem;">Kode Pelanggan</p>
                    <p style="font-weight: 500; color: var(--neon-cyan); margin: 0;"><?= $pesanan['kode_pelanggan'] ?></p>
                </div>
                <div>
                    <p style="color: var(--text-muted); margin-bottom: 4px; font-size: 0.85rem;">Tanggal</p>
                    <p style="font-weight: 500; margin: 0;"><?= date('d F Y • H:i:s', strtotime($pesanan['tgl_pesanan'])) ?> WIB</p>
                </div>
                <div>
                    <p style="color: var(--text-muted); margin-bottom: 4px; font-size: 0.85rem;">Status</p>
                    <p style="font-weight: 500; margin: 0;"><?= $pesanan['status'] ?></p>
                </div>
            </div>
        </div>

        <!-- Daftar Menu dari Database Real-time -->
        <div style="margin-bottom: 16px;">
            <?php 
            mysqli_data_seek($detail, 0); // Reset pointer untuk loop kedua
            while ($d = mysqli_fetch_assoc($detail)): 
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
            <p style="color: var(--text-muted); margin-bottom: 4px;">Total Pembayaran</p>
            <p style="font-size: 1.8rem; font-weight: 600; color: var(--neon-cyan); margin: 0;">Rp <?= number_format($pesanan['total_harga'], 0, ',', '.') ?></p>
        </div>
    </div>

    <div class="glass-card mb-3">
        <h3 style="margin-bottom: 16px; font-size: 1.1rem;">📱 QRIS</h3>
        <div style="text-align: center; padding: 24px; background: rgba(255, 255, 255, 0.05); border-radius: var(--radius-sm);">
            <img id="qrisImage" src="../gambar/qris.jpeg" alt="QRIS" style="width: 200px; height: 200px; margin: 0 auto; border-radius: 12px; object-fit: contain;">
            <p style="color: var(--text-muted); margin-top: 16px; font-size: 0.9rem;">Scan QRIS untuk pembayaran</p>
        </div>
    </div>

    <div class="glass-card mb-3">
        <h3 style="margin-bottom: 16px; font-size: 1.1rem;">🏦 Transfer Bank BCA</h3>
        <div style="background: rgba(0, 245, 255, 0.1); border: 1px solid rgba(0, 245, 255, 0.3); border-radius: var(--radius-sm); padding: 20px;">
            <div style="margin-bottom: 12px;">
                <p style="color: var(--text-muted); margin-bottom: 4px; font-size: 0.9rem;">Nomor Rekening</p>
                <p style="font-size: 1.5rem; font-weight: 600; color: var(--neon-cyan); margin: 0; letter-spacing: 2px;">3780713479</p>
            </div>
            <div>
                <p style="color: var(--text-muted); margin-bottom: 4px; font-size: 0.9rem;">Atas Nama</p>
                <p style="font-size: 1.2rem; font-weight: 500; color: var(--text-primary); margin: 0;">NABIL NUGROHO</p>
            </div>
        </div>
    </div>

    <div class="glass-card">
        <h3 style="margin-bottom: 16px; font-size: 1.1rem;">📸 Konfirmasi Pembayaran</h3>
        <form method="post" action="konfirmasi_pembayaran.php" enctype="multipart/form-data">
            <div class="form-group">
                <label class="form-label">Metode Pembayaran</label>
                <select name="metode" class="form-control" required>
                    <option value="QRIS">QRIS</option>
                    <option value="Transfer BCA">Transfer BCA</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Upload Bukti Pembayaran</label>
                <input type="file" name="bukti" class="form-control" accept="image/*" required>
                <p style="color: var(--text-muted); font-size: 0.85rem; margin-top: 8px;">Format: JPG, PNG, JPEG (Max 5MB)</p>
            </div>
            <button type="submit" class="btn btn-success w-100">
                <span>✅ Konfirmasi Pembayaran</span>
            </button>
        </form>
    </div>
    <?php endif; ?>

    <div style="text-align: center; margin-top: 24px;">
        <div class="d-flex gap-2 justify-center">
            <a href="cek_status.php" class="btn btn-outline">🔍 Cek Status Pesanan</a>
            <a href="riwayat_pesanan.php" class="btn btn-secondary">📜 Riwayat Pesanan</a>
        </div>
    </div>
</div>

<script src="../assets/js/app.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const qrisImage = document.getElementById('qrisImage');
    if (qrisImage) {
        qrisImage.onerror = function() {
            // Jika gagal load, tampilkan placeholder
            this.style.display = 'none';
            const placeholder = document.createElement('div');
            placeholder.style.cssText = 'width: 200px; height: 200px; margin: 0 auto; background: linear-gradient(135deg, var(--neon-cyan), var(--neon-purple)); border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-direction: column;';
            placeholder.innerHTML = '<div style="font-size: 4rem; margin-bottom: 8px;">📱</div><p style="color: white; font-size: 0.9rem; margin: 0;">QRIS</p>';
            this.parentNode.insertBefore(placeholder, this.nextSibling);
        };
    }
    
    <?php if (!$cek_pembayaran): ?>
    // Start polling for payment confirmation
    const idPesanan = <?= $id_pesanan ?>;
    const poller = new StatusPoller({
        idPesanan: idPesanan,
        interval: 5000, // Check every 5 seconds
        onStatusChange: function(data) {
            const newStatus = data.status;
            if (newStatus === 'Sudah Dibayar') {
                Toast.show('Pembayaran berhasil dikonfirmasi!', 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            }
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
    <?php endif; ?>
});
</script>

</body>
</html>
