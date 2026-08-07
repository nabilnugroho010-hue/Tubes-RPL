<?php
session_start();
date_default_timezone_set('Asia/Jakarta');
include "../koneksi.php";

// Cek apakah ada kode pelanggan di session
if (!isset($_SESSION['kode_pelanggan']) && !isset($_GET['kode'])) {
    // Redirect ke halaman pemesanan jika tidak ada kode
    header("Location: pesan_pelanggan.php");
    exit;
}

$kode_pelanggan = isset($_GET['kode']) ? trim($_GET['kode']) : $_SESSION['kode_pelanggan'];

// Ambil semua pesanan berdasarkan kode pelanggan
$pesanan = mysqli_query($conn, "SELECT * FROM data_pesanan WHERE kode_pelanggan = '$kode_pelanggan' ORDER BY tgl_pesanan DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan - SPGFood</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: Arial, sans-serif;
        }
        
        * {
            color: #333 !important;
        }
        
        .glass-container {
            background: white;
            border-radius: 12px;
            padding: 32px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: 40px auto;
        }
        
        .glass-container h1,
        .glass-container h2,
        .glass-container h3,
        .glass-container p {
            color: #333 !important;
        }
        
        .history-card {
            background: #f8f9fa;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 16px;
            transition: all 0.3s;
        }
        
        .history-card:hover {
            background: white;
            border-color: #667eea;
        }
        
        .history-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .history-id {
            font-weight: 600;
            color: #667eea !important;
        }
        
        .history-date {
            color: #666 !important;
            font-size: 0.85rem;
        }
        
        .history-items {
            margin-bottom: 12px;
        }
        
        .history-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .history-item:last-child {
            border-bottom: none;
        }
        
        .history-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 12px;
            border-top: 1px solid #e0e0e0;
        }
        
        .history-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }
        
        .status-pending {
            background: rgba(255, 170, 0, 0.15);
            color: #ffaa00 !important;
            border: 1px solid rgba(255, 170, 0, 0.3);
        }
        
        .status-processing {
            background: rgba(102, 126, 234, 0.15);
            color: #667eea !important;
            border: 1px solid rgba(102, 126, 234, 0.3);
        }
        
        .status-paid {
            background: rgba(0, 200, 83, 0.15);
            color: #00c853 !important;
            border: 1px solid rgba(0, 200, 83, 0.3);
        }
        
        .status-completed {
            background: rgba(118, 75, 162, 0.15);
            color: #764ba2 !important;
            border: 1px solid rgba(118, 75, 162, 0.3);
        }
    </style>
</head>
<body>

<div class="glass-container">
    <!-- Header -->
    <div style="text-align: center; margin-bottom: 32px;">
        <h1 style="margin-bottom: 8px;">📜 Riwayat Pesanan</h1>
        <p style="color: #666;">Lihat riwayat pesanan Anda</p>
    </div>

    <!-- Search by Code -->
    <div class="glass-card mb-3">
        <form method="get" action="">
            <div class="d-flex gap-2">
                <input type="text" name="kode" class="form-control" placeholder="Masukkan Kode Pelanggan" value="<?= htmlspecialchars($kode_pelanggan) ?>" style="color: #333 !important; background: white !important;">
                <button type="submit" class="btn btn-primary">Cari</button>
            </div>
        </form>
    </div>

    <?php if (mysqli_num_rows($pesanan) > 0): ?>
        <?php while ($p = mysqli_fetch_assoc($pesanan)): ?>
            <?php
            // Ambil detail pesanan
            $detail = mysqli_query($conn, "SELECT d.*, m.nama_menu, m.harga 
                                               FROM rincian_pesanan d 
                                               JOIN data_menu m ON d.id_menu = m.id_menu 
                                               WHERE d.id_pesanan = '{$p['id_pesanan']}'");
            ?>
            
            <div class="history-card">
                <div class="history-header">
                    <div>
                        <div class="history-id">#<?= htmlspecialchars($p['nomor_pesanan'] ?? $p['id_pesanan']) ?></div>
                        <div class="history-date"><?= date('d F Y • H:i', strtotime($p['tgl_pesanan'])) ?> WIB</div>
                    </div>
                    <div>
                        <?php
                        $statusClass = 'status-pending';
                        if (strpos($p['status'], 'diproses') !== false) $statusClass = 'status-processing';
                        elseif (strpos($p['status'], 'Dibayar') !== false) $statusClass = 'status-paid';
                        elseif (strpos($p['status'], 'Selesai') !== false) $statusClass = 'status-completed';
                        ?>
                        <span class="history-status <?= $statusClass ?>"><?= htmlspecialchars($p['status']) ?></span>
                    </div>
                </div>

                <div class="history-items">
                    <?php while ($d = mysqli_fetch_assoc($detail)): ?>
                    <div class="history-item">
                        <span><?= htmlspecialchars($d['nama_menu']) ?> x<?= $d['jumlah'] ?></span>
                        <span style="color: var(--neon-cyan);">Rp <?= number_format($d['harga'] * $d['jumlah'], 0, ',', '.') ?></span>
                    </div>
                    <?php endwhile; ?>
                </div>

                <div class="history-total">
                    <div>
                        <span style="color: var(--text-muted);">Total:</span>
                        <span style="color: var(--neon-cyan); font-weight: 600; font-size: 1.2rem;">Rp <?= number_format($p['total_harga'], 0, ',', '.') ?></span>
                    </div>
                    <a href="cek_status.php?nomor=<?= htmlspecialchars($p['nomor_pesanan']) ?>" class="btn btn-sm btn-outline">Lihat Detail</a>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="glass-card" style="text-align: center; padding: 40px;">
            <div style="font-size: 3rem; margin-bottom: 16px;">📭</div>
            <h3 style="color: var(--text-muted); margin-bottom: 8px;">Belum Ada Riwayat Pesanan</h3>
            <p style="color: var(--text-muted);">Anda belum memiliki pesanan dengan kode pelanggan ini.</p>
            <div style="margin-top: 24px;">
                <a href="pesan_pelanggan.php" class="btn btn-primary">🍽️ Pesan Sekarang</a>
            </div>
        </div>
    <?php endif; ?>

    <div style="text-align: center; margin-top: 24px;">
        <a href="pesan_pelanggan.php" class="btn btn-success">🍽️ Pesan Menu Baru</a>
    </div>
</div>

<script src="../assets/js/app.js"></script>

</body>
</html>
