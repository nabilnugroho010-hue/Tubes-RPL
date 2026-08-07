<?php
session_start();
include "../koneksi.php";

// Cek apakah ada kode pelanggan di session
if (!isset($_SESSION['kode_pelanggan']) && !isset($_GET['kode'])) {
    // Redirect ke halaman pemesanan jika tidak ada kode
    header("Location: pesan_pelanggan.php");
    exit;
}

$kode_pelanggan = isset($_GET['kode']) ? trim($_GET['kode']) : $_SESSION['kode_pelanggan'];

// Ambil semua pesanan berdasarkan kode pelanggan
$pesanan = mysqli_query($koneksi, "SELECT * FROM data_pesanan WHERE kode_pelanggan = '$kode_pelanggan' ORDER BY tgl_pesanan DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan - SPGFood</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .history-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: var(--radius-md);
            padding: 20px;
            margin-bottom: 16px;
            transition: var(--transition);
        }
        
        .history-card:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(0, 245, 255, 0.2);
        }
        
        .history-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--glass-border);
        }
        
        .history-id {
            font-weight: 600;
            color: var(--neon-cyan);
        }
        
        .history-date {
            color: var(--text-muted);
            font-size: 0.85rem;
        }
        
        .history-items {
            margin-bottom: 12px;
        }
        
        .history-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .history-item:last-child {
            border-bottom: none;
        }
        
        .history-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 12px;
            border-top: 1px solid var(--glass-border);
        }
        
        .history-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }
        
        .status-pending {
            background: rgba(255, 170, 0, 0.15);
            color: var(--warning);
            border: 1px solid rgba(255, 170, 0, 0.3);
        }
        
        .status-processing {
            background: rgba(0, 245, 255, 0.15);
            color: var(--neon-cyan);
            border: 1px solid rgba(0, 245, 255, 0.3);
        }
        
        .status-paid {
            background: rgba(0, 255, 136, 0.15);
            color: var(--success);
            border: 1px solid rgba(0, 255, 136, 0.3);
        }
        
        .status-completed {
            background: rgba(191, 0, 255, 0.15);
            color: var(--neon-purple);
            border: 1px solid rgba(191, 0, 255, 0.3);
        }
    </style>
</head>
<body>

<div class="glass-container" style="max-width: 800px; margin: 40px auto; padding: 32px;">
    <!-- Header -->
    <div style="text-align: center; margin-bottom: 32px;">
        <h1 style="margin-bottom: 8px;">📜 Riwayat Pesanan</h1>
        <p style="color: var(--text-muted);">Lihat riwayat pesanan Anda</p>
    </div>

    <!-- Search by Code -->
    <div class="glass-card mb-3">
        <form method="get" action="">
            <div class="d-flex gap-2">
                <input type="text" name="kode" class="form-control" placeholder="Masukkan Kode Pelanggan" value="<?= htmlspecialchars($kode_pelanggan) ?>">
                <button type="submit" class="btn btn-primary">Cari</button>
            </div>
        </form>
    </div>

    <?php if (mysqli_num_rows($pesanan) > 0): ?>
        <?php while ($p = mysqli_fetch_assoc($pesanan)): ?>
            <?php
            // Ambil detail pesanan
            $detail = mysqli_query($koneksi, "SELECT d.*, m.nama_menu, m.harga 
                                               FROM rincian_pesanan d 
                                               JOIN data_menu m ON d.id_menu = m.id_menu 
                                               WHERE d.id_pesanan = '{$p['id_pesanan']}'");
            ?>
            
            <div class="history-card">
                <div class="history-header">
                    <div>
                        <div class="history-id">#<?= $p['nomor_pesanan'] ?? $p['id_pesanan'] ?></div>
                        <div class="history-date"><?= date('d F Y • H:i', strtotime($p['tgl_pesanan'])) ?> WIB</div>
                    </div>
                    <div>
                        <?php
                        $statusClass = 'status-pending';
                        if (strpos($p['status'], 'diproses') !== false) $statusClass = 'status-processing';
                        elseif (strpos($p['status'], 'Dibayar') !== false) $statusClass = 'status-paid';
                        elseif (strpos($p['status'], 'Selesai') !== false) $statusClass = 'status-completed';
                        ?>
                        <span class="history-status <?= $statusClass ?>"><?= $p['status'] ?></span>
                    </div>
                </div>
                
                <div class="history-items">
                    <?php while ($d = mysqli_fetch_assoc($detail)): ?>
                    <div class="history-item">
                        <span><?= $d['nama_menu'] ?> x<?= $d['jumlah'] ?></span>
                        <span style="color: var(--neon-cyan);">Rp <?= number_format($d['harga'] * $d['jumlah'], 0, ',', '.') ?></span>
                    </div>
                    <?php endwhile; ?>
                </div>
                
                <div class="history-total">
                    <div>
                        <span style="color: var(--text-muted);">Total:</span>
                        <span style="color: var(--neon-cyan); font-weight: 600; font-size: 1.2rem;">Rp <?= number_format($p['total_harga'], 0, ',', '.') ?></span>
                    </div>
                    <a href="cek_status.php?nomor=<?= $p['nomor_pesanan'] ?>" class="btn btn-sm btn-outline">Lihat Detail</a>
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
