<?php
session_start();
date_default_timezone_set('Asia/Jakarta');
include "../koneksi.php";

// Cek apakah ada id_pesanan di session
if (!isset($_SESSION['id_pesanan'])) {
    header("Location: pesan_pelanggan.php");
    exit;
}

$id_pesanan = $_SESSION['id_pesanan'];

// Ambil data pesanan berdasarkan id_pesanan dari session
$pesanan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM data_pesanan WHERE id_pesanan = '$id_pesanan'"));

if (!$pesanan) {
    header("Location: pesan_pelanggan.php");
    exit;
}

// Ambil detail pesanan
$detail = mysqli_query($conn, "SELECT d.*, m.nama_menu, m.harga 
                                   FROM rincian_pesanan d 
                                   JOIN data_menu m ON d.id_menu = m.id_menu 
                                   WHERE d.id_pesanan = '$id_pesanan'");

// Cek apakah pembayaran sudah dikonfirmasi
$cek_pembayaran = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM data_pembayaran WHERE id_pesanan = '$id_pesanan'"));

// Update metode pembayaran di data_pesanan jika sudah ada
if ($cek_pembayaran) {
    mysqli_query($conn, "UPDATE data_pesanan SET metode_pembayaran = '" . $cek_pembayaran['metode'] . "' WHERE id_pesanan = '$id_pesanan'");
    
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
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        
        .payment-container {
            max-width: 700px;
            margin: 40px auto;
            padding: 32px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }
        
        .payment-header {
            text-align: center;
            margin-bottom: 32px;
        }
        
        .payment-title {
            font-size: 1.8rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
        }
        
        .payment-subtitle {
            color: #666;
            font-size: 1rem;
        }
        
        .white-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border: 1px solid #e0e0e0;
        }
        
        .card-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 16px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }
        
        .info-label {
            color: #666;
            font-size: 0.85rem;
            margin-bottom: 4px;
        }
        
        .info-value {
            font-weight: 500;
            color: #333;
        }
        
        .info-value.highlight {
            color: #667eea;
            font-weight: 600;
        }
        
        .menu-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px;
            background: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 8px;
        }
        
        .menu-item-info {
            flex: 1;
        }
        
        .menu-item-name {
            font-weight: 500;
            color: #333;
            margin-bottom: 4px;
        }
        
        .menu-item-price {
            color: #666;
            font-size: 0.9rem;
        }
        
        .menu-item-quantity {
            text-align: center;
            margin: 0 16px;
        }
        
        .menu-item-subtotal {
            text-align: right;
            min-width: 100px;
        }
        
        .total-section {
            text-align: right;
            margin-top: 16px;
            padding-top: 16px;
            border-top: 2px solid #e0e0e0;
        }
        
        .total-label {
            color: #666;
            margin-bottom: 4px;
        }
        
        .total-amount {
            font-size: 1.8rem;
            font-weight: 600;
            color: #667eea;
            margin: 0;
        }
        
        .qris-container {
            text-align: center;
            padding: 24px;
            background: #f8f9fa;
            border-radius: 12px;
        }
        
        .qris-image {
            width: 200px;
            height: 200px;
            margin: 0 auto;
            border-radius: 12px;
            object-fit: contain;
        }
        
        .qris-placeholder {
            width: 200px;
            height: 200px;
            margin: 0 auto;
            border-radius: 12px;
            background: #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px dashed #ccc;
        }
        
        .bank-info {
            background: #f8f9fa;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            padding: 20px;
        }
        
        .bank-number {
            font-size: 1.5rem;
            font-weight: 600;
            color: #667eea;
            margin: 0;
            letter-spacing: 2px;
        }
        
        .form-group {
            margin-bottom: 16px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .btn-primary {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.2s;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
        }
        
        .success-card {
            background: rgba(0, 255, 136, 0.1);
            border-color: rgba(0, 255, 136, 0.3);
            margin-bottom: 24px;
        }
        
        @media (max-width: 768px) {
            .payment-container {
                padding: 20px;
                margin: 20px auto;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
            }
            
            .menu-item {
                flex-direction: column;
                text-align: center;
            }
            
            .menu-item-quantity {
                margin: 8px 0;
            }
        }
    </style>
</head>
<body>

<div class="payment-container">
    <div class="payment-header">
        <h2 class="payment-title">💳 Pembayaran</h2>
        <p class="payment-subtitle">Selesaikan pembayaran pesanan Anda</p>
    </div>

    <?php if ($cek_pembayaran): ?>
    <!-- Sudah Bayar -->
    <div class="white-card success-card">
        <div style="text-align: center;">
            <div style="font-size: 3rem; margin-bottom: 16px;">✅</div>
            <h3 style="color: #00ff88; margin-bottom: 8px;">Pembayaran Berhasil!</h3>
            <p style="color: #666;">Pesanan Anda sedang diproses</p>
        </div>
    </div>
    <?php else: ?>
    <!-- Belum Bayar -->
    <div class="white-card">
        <h3 class="card-title">📋 Ringkasan Pesanan</h3>
        
        <!-- Informasi Pesanan -->
        <div class="white-card" style="background: #f8f9fa; margin-bottom: 16px;">
            <div class="info-grid">
                <div>
                    <p class="info-label">Nama Pelanggan</p>
                    <p class="info-value"><?= htmlspecialchars($pesanan['nama_pelanggan']) ?></p>
                </div>
                <div>
                    <p class="info-label">No Meja</p>
                    <p class="info-value"><?= htmlspecialchars($pesanan['no_meja']) ?></p>
                </div>
                <div>
                    <p class="info-label">Kode Pelanggan</p>
                    <p class="info-value highlight"><?= htmlspecialchars($pesanan['kode_pelanggan']) ?></p>
                </div>
                <div>
                    <p class="info-label">Tanggal</p>
                    <p class="info-value"><?= date('d F Y • H:i:s', strtotime($pesanan['tgl_pesanan'])) ?> WIB</p>
                </div>
                <div>
                    <p class="info-label">Status</p>
                    <p class="info-value"><?= htmlspecialchars($pesanan['status']) ?></p>
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
                    <div class="menu-item-name"><?= htmlspecialchars($d['nama_menu']) ?></div>
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
        <div style="text-align: right; margin-top: 16px; padding-top: 16px; border-top: 1px solid #e0e0e0;">
            <p style="color: #666 !important; margin-bottom: 4px;">Total Pembayaran</p>
            <p style="font-size: 1.8rem; font-weight: 600; color: #667eea !important; margin: 0;">Rp <?= number_format($pesanan['total_harga'], 0, ',', '.') ?></p>
        </div>
    </div>

    <div class="glass-card mb-3">
        <h3 style="margin-bottom: 16px; font-size: 1.1rem; color: #333 !important;">📱 QRIS</h3>
        <div style="text-align: center; padding: 24px; background: #f8f9fa; border-radius: 12px; border: 1px solid #e0e0e0;">
            <?php
            $qris_path = '../gambar/qris.jpeg';
            $qris_exists = file_exists(__DIR__ . '/../gambar/qris.jpeg');
            ?>
            <?php if ($qris_exists): ?>
                <img id="qrisImage" src="<?= $qris_path ?>" alt="QRIS" style="width: 200px; height: 200px; margin: 0 auto; border-radius: 12px; object-fit: contain;">
            <?php else: ?>
                <div style="width: 200px; height: 200px; margin: 0 auto; border-radius: 12px; background: #e0e0e0; display: flex; align-items: center; justify-content: center; border: 2px dashed #ccc;">
                    <div style="text-align: center; color: #666 !important;">
                        <div style="font-size: 2rem; margin-bottom: 8px;">📱</div>
                        <div style="font-size: 0.8rem;">QRIS Image</div>
                        <div style="font-size: 0.7rem;">Not Available</div>
                    </div>
                </div>
            <?php endif; ?>
            <p style="color: #666 !important; margin-top: 16px; font-size: 0.9rem;">Scan QRIS untuk pembayaran</p>
        </div>
    </div>

    <div class="glass-card mb-3">
        <h3 style="margin-bottom: 16px; font-size: 1.1rem; color: #333 !important;">🏦 Transfer Bank BCA</h3>
        <div style="background: #f8f9fa; border: 1px solid #e0e0e0; border-radius: 12px; padding: 20px;">
            <div style="margin-bottom: 12px;">
                <p style="color: #666 !important; margin-bottom: 4px; font-size: 0.9rem;">Nomor Rekening</p>
                <p style="font-size: 1.5rem; font-weight: 600; color: #667eea !important; margin: 0; letter-spacing: 2px;">3780713479</p>
            </div>
            <div>
                <p style="color: #666 !important; margin-bottom: 4px; font-size: 0.9rem;">Atas Nama</p>
                <p style="font-size: 1.2rem; font-weight: 500; color: #333 !important; margin: 0;">NABIL NUGROHO</p>
            </div>
        </div>
    </div>

    <div class="glass-card">
        <h3 style="margin-bottom: 16px; font-size: 1.1rem; color: #333 !important;">📸 Konfirmasi Pembayaran</h3>
        <form method="post" action="konfirmasi_pembayaran.php" enctype="multipart/form-data">
            <div class="form-group">
                <label class="form-label" style="color: #333 !important;">Metode Pembayaran</label>
                <select name="metode" class="form-control" required style="color: #333 !important; background: white !important;">
                    <option value="QRIS" style="color: #333 !important; background: white !important;">QRIS</option>
                    <option value="Transfer BCA" style="color: #333 !important; background: white !important;">Transfer BCA</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label" style="color: #333 !important;">Upload Bukti Pembayaran</label>
                <input type="file" name="bukti" class="form-control" accept="image/*" required style="color: #333 !important; background: white !important;">
                <p style="color: #666 !important; font-size: 0.85rem; margin-top: 8px;">Format: JPG, PNG, JPEG (Max 5MB)</p>
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
