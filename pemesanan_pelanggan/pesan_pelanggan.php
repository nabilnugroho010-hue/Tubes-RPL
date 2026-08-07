<?php
session_start();
include "../koneksi.php"; // Tetap pakai path asli sesuai posisi file

$hari_ini = date('Y-m-d H:i:s');
$pesan = "";

// Hanya jalankan proses simpan jika tombol KIRIM ditekan
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['kirim_pesanan'])) {
    $nama = trim($_POST['nama_lengkap']);
    $meja = trim($_POST['nomor_meja']);
    $total = 0;
    $ada_menu = false;

    // Cek apakah ada menu yang dipilih
    foreach ($_POST['jumlah'] as $id_menu => $jumlah) {
        if ((int)$jumlah > 0) {
            $ada_menu = true;
            break;
        }
    }

    if (!$ada_menu) {
        $error = "Silakan pilih minimal satu menu sebelum melanjutkan ke pembayaran.";
    } else {
        // Generate nomor pesanan otomatis yang unik per pesanan
        // Format: ORD-YYYYMMDD-XXXX (4 digit untuk menampung hingga 9999 pesanan per hari)
        $tanggal = date('Ymd');
        
        // Gunakan MAX id_pesanan hari ini + 1 untuk memastikan unik
        $cek = mysqli_query($koneksi, "SELECT MAX(id_pesanan) as max_id FROM data_pesanan WHERE DATE(tgl_pesanan) = CURDATE()");
        $data_cek = mysqli_fetch_assoc($cek);
        $max_id = $data_cek['max_id'] ?? 0;
        
        // Ambil 4 digit terakhir dari id_pesanan + 1
        $nomor_urut = str_pad(($max_id % 10000) + 1, 4, '0', STR_PAD_LEFT);
        $nomor_pesanan = "ORD-" . $tanggal . "-" . $nomor_urut;

        // Generate kode pelanggan unik untuk setiap pesanan (format pendek)
        // Menggunakan uniqid() yang sudah jamin unik tanpa perlu loop check
        $kode_pelanggan = "CUST-" . strtoupper(uniqid());

        // Simpan data utama pesanan dengan nomor pesanan otomatis
        $simpan = mysqli_query($koneksi, "INSERT INTO data_pesanan 
            (tgl_pesanan, nama_pelanggan, no_meja, kode_pelanggan, total_harga, status, nomor_pesanan) 
            VALUES ('$hari_ini', '$nama', '$meja', '$kode_pelanggan', 0, 'Menunggu pembayaran', '$nomor_pesanan')");

        if ($simpan) {
            $id_pesanan = mysqli_insert_id($koneksi);

            // Simpan rincian menu yang dipesan
            foreach ($_POST['jumlah'] as $id_menu => $jumlah) {
                $jumlah = (int)$jumlah;
                if ($jumlah > 0) {
                    $harga = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT harga FROM data_menu WHERE id_menu = '$id_menu'"));
                    $subtotal = $harga['harga'] * $jumlah;
                    $total += $subtotal;
                    mysqli_query($koneksi, "INSERT INTO rincian_pesanan (id_pesanan, id_menu, jumlah) VALUES ('$id_pesanan', '$id_menu', '$jumlah')");
                }
            }

            // Update total harga pesanan
            mysqli_query($koneksi, "UPDATE data_pesanan SET total_harga = '$total' WHERE id_pesanan = '$id_pesanan'");

            // Simpan id_pesanan ke session untuk redirect ke pembayaran
            $_SESSION['id_pesanan'] = $id_pesanan;
            header("Location: pembayaran.php");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemesanan Makanan & Minuman - SPGFood</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 16px;
            margin-top: 16px;
        }
        
        .menu-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: var(--radius-md);
            padding: 16px;
            transition: var(--transition);
        }
        
        .menu-card:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(0, 245, 255, 0.3);
            transform: translateY(-2px);
        }
        
        .menu-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
        }
        
        .menu-card-name {
            font-weight: 600;
            color: var(--text-primary);
            font-size: 1rem;
        }
        
        .menu-card-price {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--neon-cyan);
            margin-bottom: 12px;
        }
        
        .menu-card-quantity {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .qty-btn {
            width: 36px;
            height: 36px;
            border: 1px solid var(--glass-border);
            background: rgba(255, 255, 255, 0.05);
            color: var(--text-primary);
            border-radius: var(--radius-sm);
            cursor: pointer;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }
        
        .qty-btn:hover {
            background: rgba(0, 245, 255, 0.1);
            border-color: var(--neon-cyan);
        }
        
        .qty-input {
            width: 60px;
            text-align: center;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--glass-border);
            border-radius: var(--radius-sm);
            color: var(--text-primary);
            font-size: 1rem;
            padding: 8px;
        }
        
        .qty-input:focus {
            outline: none;
            border-color: var(--neon-cyan);
        }
        
        .cart-summary {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: rgba(0, 245, 255, 0.1);
            border: 1px solid rgba(0, 245, 255, 0.3);
            border-radius: var(--radius-lg);
            padding: 16px 24px;
            backdrop-filter: blur(20px);
            z-index: 1000;
            display: none;
        }
        
        .cart-summary.show {
            display: block;
        }
        
        @media (max-width: 768px) {
            .cart-summary {
                left: 20px;
                right: 20px;
                bottom: 10px;
            }
        }
        
        .menu-item {
            display: block;
        }
        
        .menu-item.hidden {
            display: none;
        }
        
        .no-menu-message {
            text-align: center;
            padding: 40px;
            color: var(--text-muted);
        }
        
        .no-menu-message div {
            font-size: 3rem;
            margin-bottom: 16px;
        }
    </style>
</head>
<body>

<div class="glass-container" style="max-width: 900px; margin: 40px auto; padding: 32px;">
    <!-- Header -->
    <div style="text-align: center; margin-bottom: 32px;">
        <h1 style="margin-bottom: 8px;">🍽️ Pemesanan Makanan & Minuman</h1>
        <p style="color: var(--text-muted);">Pesan menu favorit Anda dengan mudah</p>
    </div>

    <?php if (!empty($error)): ?>
    <div class="glass-card" style="margin-bottom: 24px; background: rgba(255, 68, 102, 0.1); border-color: rgba(255, 68, 102, 0.3);">
        <p style="color: var(--error); text-align: center; margin: 0; line-height: 1.6;"><?= $error ?></p>
    </div>
    <?php endif; ?>

    <form method="post" action="" id="orderForm">
        <div class="glass-card mb-3">
            <h3 style="margin-bottom: 16px; font-size: 1.1rem;">👤 Informasi Pelanggan</h3>
            <div class="grid grid-2">
                <div class="form-group">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" class="form-control" placeholder="Masukkan nama Anda" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Nomor Meja</label>
                    <input type="number" name="nomor_meja" class="form-control" placeholder="Contoh: 1, 2, 3" min="1" required>
                </div>
            </div>
        </div>

        <div class="glass-card">
            <h3 style="margin-bottom: 16px; font-size: 1.1rem;">📋 Daftar Menu</h3>

            <!-- Category Tabs -->
            <div class="category-tabs">
                <button type="button" class="category-tab active" data-category="all" onclick="filterCategory('all')">
                    <span>🍽️ Semua</span>
                </button>
                <button type="button" class="category-tab" data-category="Makanan" onclick="filterCategory('Makanan')">
                    <span>🍱 Makanan</span>
                </button>
                <button type="button" class="category-tab" data-category="Minuman" onclick="filterCategory('Minuman')">
                    <span>🥤 Minuman</span>
                </button>
                <button type="button" class="category-tab" data-category="Camilan" onclick="filterCategory('Camilan')">
                    <span>🍿 Camilan</span>
                </button>
            </div>

            <?php
            $ambil_menu = mysqli_query($koneksi, "SELECT * FROM data_menu WHERE status = 'Tersedia' ORDER BY jenis_menu ASC, nama_menu ASC");
            if (mysqli_num_rows($ambil_menu) > 0):
            ?>
            <div class="menu-grid">
                <?php while ($menu = mysqli_fetch_assoc($ambil_menu)): ?>
                <div class="menu-card menu-item" data-category="<?= $menu['jenis_menu'] ?>">
                    <div class="menu-card-header">
                        <div class="menu-card-name"><?= $menu['nama_menu'] ?></div>
                        <span class="menu-card-type"><?= $menu['jenis_menu'] ?></span>
                    </div>
                    <div class="menu-card-price">Rp <?= number_format($menu['harga'], 0, ',', '.') ?></div>
                    <div class="menu-card-quantity">
                        <button type="button" class="qty-btn" onclick="updateQty(<?= $menu['id_menu'] ?>, -1)">−</button>
                        <input type="number" name="jumlah[<?= $menu['id_menu'] ?>]" class="qty-input" id="qty-<?= $menu['id_menu'] ?>" min="0" value="0" data-price="<?= $menu['harga'] ?>" onchange="updateCart()">
                        <button type="button" class="qty-btn" onclick="updateQty(<?= $menu['id_menu'] ?>, 1)">+</button>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
            
            <button type="submit" name="kirim_pesanan" class="btn btn-primary w-100" style="margin-top: 24px; padding: 16px;">
                <span style="font-size: 1.1rem;">➡️ Lanjutkan ke Pembayaran</span>
            </button>
            <?php else: ?>
            <div class="no-menu-message">
                <div>📭</div>
                <p>Belum ada menu yang tersedia saat ini.</p>
            </div>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Cart Summary -->
<div class="cart-summary" id="cartSummary">
    <div style="display: flex; justify-content: space-between; align-items: center; gap: 16px;">
        <div>
            <p style="color: var(--text-muted); margin: 0; font-size: 0.85rem;">Total</p>
            <p style="color: var(--neon-cyan); font-weight: 600; font-size: 1.2rem; margin: 0;" id="cartTotal">Rp 0</p>
        </div>
        <div style="font-size: 1.5rem;">🛒</div>
    </div>
</div>

<script src="../assets/js/app.js"></script>
<script>
function updateQty(id, change) {
    const input = document.getElementById('qty-' + id);
    let value = parseInt(input.value) + change;
    if (value < 0) value = 0;
    input.value = value;
    updateCart();
}

function updateCart() {
    const inputs = document.querySelectorAll('.qty-input');
    let total = 0;
    let hasItems = false;
    
    inputs.forEach(input => {
        const qty = parseInt(input.value) || 0;
        const price = parseFloat(input.dataset.price) || 0;
        if (qty > 0) {
            total += qty * price;
            hasItems = true;
        }
    });
    
    const cartSummary = document.getElementById('cartSummary');
    const cartTotal = document.getElementById('cartTotal');
    
    if (hasItems) {
        cartSummary.classList.add('show');
        cartTotal.textContent = 'Rp ' + total.toLocaleString('id-ID');
    } else {
        cartSummary.classList.remove('show');
    }
}

function filterCategory(category) {
    // Update active tab
    document.querySelectorAll('.category-tab').forEach(tab => {
        tab.classList.remove('active');
        if (tab.dataset.category === category) {
            tab.classList.add('active');
        }
    });
    
    // Filter menu items
    const menuItems = document.querySelectorAll('.menu-item');
    let visibleCount = 0;
    
    menuItems.forEach(item => {
        if (category === 'all' || item.dataset.category === category) {
            item.classList.remove('hidden');
            visibleCount++;
        } else {
            item.classList.add('hidden');
        }
    });
    
    // Show no menu message if needed
    const menuGrid = document.querySelector('.menu-grid');
    let noMenuMessage = document.querySelector('.no-menu-message');
    
    if (visibleCount === 0 && !noMenuMessage) {
        noMenuMessage = document.createElement('div');
        noMenuMessage.className = 'no-menu-message';
        noMenuMessage.innerHTML = '<div>📭</div><p>Tidak ada menu di kategori ini.</p>';
        menuGrid.parentNode.insertBefore(noMenuMessage, menuGrid.nextSibling);
        menuGrid.style.display = 'none';
    } else if (visibleCount > 0) {
        if (noMenuMessage) {
            noMenuMessage.remove();
        }
        menuGrid.style.display = 'grid';
    }
}

document.getElementById('orderForm').addEventListener('submit', function(e) {
    const inputs = document.querySelectorAll('.qty-input');
    let hasItems = false;
    
    inputs.forEach(input => {
        if (parseInt(input.value) > 0) {
            hasItems = true;
        }
    });
    
    if (!hasItems) {
        e.preventDefault();
        Toast.show('Silakan pilih minimal satu menu', 'warning');
    }
});
</script>

</body>
</html>
