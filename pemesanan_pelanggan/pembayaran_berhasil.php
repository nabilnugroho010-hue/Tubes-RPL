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

// Cek pembayaran
$cek_pembayaran = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM data_pembayaran WHERE id_pesanan = '$id_pesanan'"));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Berhasil - SPGFood</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .success-animation {
            animation: successPulse 2s ease-in-out infinite;
        }
        
        @keyframes successPulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
        }
        
        .checkmark {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #00ff88 0%, #00cc6a 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            font-size: 2.5rem;
            box-shadow: 0 8px 32px rgba(0, 255, 136, 0.3);
        }
        
        .countdown {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--neon-cyan);
        }
        
        .progress-bar {
            height: 4px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 2px;
            margin: 24px 0;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #00ff88, #00f5ff);
            border-radius: 2px;
            transition: width 1s linear;
        }
    </style>
</head>
<body>

<div class="glass-container" style="max-width: 600px; margin: 40px auto; padding: 40px; text-align: center;">
    <!-- Success Animation -->
    <div class="checkmark success-animation">
        ✓
    </div>
    
    <!-- Success Message -->
    <h1 style="margin-bottom: 16px; color: var(--success);">Pembayaran Berhasil!</h1>
    <p style="color: var(--text-muted); margin-bottom: 32px; font-size: 1.1rem;">
        Terima kasih! Pembayaran Anda telah berhasil dikonfirmasi.
    </p>
    
    <!-- Order Summary -->
    <div class="glass-card mb-3" style="background: rgba(0, 255, 136, 0.05); border-color: rgba(0, 255, 136, 0.2);">
        <h3 style="margin-bottom: 16px; font-size: 1.1rem;">📋 Ringkasan Pesanan</h3>
        
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
                    <p style="color: var(--text-muted); margin-bottom: 4px; font-size: 0.85rem;">Nomor Pesanan</p>
                    <p style="font-weight: 500; color: var(--neon-cyan); margin: 0;"><?= $pesanan['nomor_pesanan'] ?? '#' . $pesanan['id_pesanan'] ?></p>
                </div>
            </div>
        </div>
        
        <!-- Payment Info -->
        <?php if ($cek_pembayaran): ?>
        <div style="margin-bottom: 16px;">
            <div class="glass-card" style="background: rgba(0, 255, 136, 0.1); border-color: rgba(0, 255, 136, 0.3); padding: 16px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                    <span style="color: var(--text-muted);">Metode Pembayaran</span>
                    <span style="font-weight: 500; color: var(--success);"><?= $cek_pembayaran['metode'] ?></span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: var(--text-muted);">Tanggal Bayar</span>
                    <span style="font-weight: 500;"><?= date('d F Y • H:i:s', strtotime($cek_pembayaran['tgl_bayar'])) ?> WIB</span>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Total -->
        <div style="text-align: right; margin-top: 16px; padding-top: 16px; border-top: 1px solid var(--glass-border);">
            <p style="color: var(--text-muted); margin-bottom: 4px;">Total Pembayaran</p>
            <p style="font-size: 1.8rem; font-weight: 600; color: var(--neon-cyan); margin: 0;">Rp <?= number_format($pesanan['total_harga'], 0, ',', '.') ?></p>
        </div>
    </div>
    
    <!-- Auto Redirect Info -->
    <div class="glass-card" style="background: rgba(0, 245, 255, 0.05); border-color: rgba(0, 245, 255, 0.2);">
        <p style="color: var(--text-muted); margin-bottom: 8px;">Anda akan dialihkan ke halaman cek status dalam:</p>
        <div class="countdown" id="countdown">5</div>
        <div class="progress-bar">
            <div class="progress-fill" id="progressFill" style="width: 100%;"></div>
        </div>
        <p style="color: var(--text-muted); font-size: 0.9rem; margin: 0;">Atau klik tombol di bawah untuk segera cek status</p>
    </div>
    
    <!-- Action Buttons -->
    <div style="margin-top: 24px;">
        <a href="cek_status.php" class="btn btn-primary w-100" style="padding: 16px;">
            <span style="font-size: 1.1rem;">🔍 Cek Status Pesanan Sekarang</span>
        </a>
        <div style="margin-top: 16px;">
            <a href="pesan_pelanggan.php" class="btn btn-outline w-100">
                <span>🍽️ Pesan Menu Lain</span>
            </a>
        </div>
    </div>
</div>

<script src="../assets/js/app.js"></script>
<script>
// Countdown timer
let countdown = 5;
const countdownElement = document.getElementById('countdown');
const progressFill = document.getElementById('progressFill');

const timer = setInterval(() => {
    countdown--;
    countdownElement.textContent = countdown;
    progressFill.style.width = (countdown / 5 * 100) + '%';
    
    if (countdown <= 0) {
        clearInterval(timer);
        window.location.href = 'cek_status.php';
    }
}, 1000);

// Manual redirect on button click
document.querySelector('.btn-primary').addEventListener('click', function(e) {
    clearInterval(timer);
});
</script>

</body>
</html>
