<?php
session_start();
date_default_timezone_set('Asia/Jakarta');
include "../koneksi.php";

// Cek apakah ada id_pesanan di session
if (!isset($_SESSION['id_pesanan'])) {
    header("Location: pesan_pelanggan.php");
    exit;
}

$id_pesanan = mysqli_real_escape_string($conn, $_SESSION['id_pesanan']);

// Ambil data pesanan berdasarkan id_pesanan dari session
$pesanan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM data_pesanan WHERE id_pesanan = '$id_pesanan'"));

if (!$pesanan) {
    header("Location: pesan_pelanggan.php");
    exit;
}

// Proses konfirmasi pembayaran
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $metode = mysqli_real_escape_string($conn, $_POST['metode']);
    $tgl_bayar = date('Y-m-d H:i:s');
    
    // Handle file upload
    $bukti_file = $_FILES['bukti'];
    $bukti_name = $bukti_file['name'];
    $bukti_tmp = $bukti_file['tmp_name'];
    $bukti_size = $bukti_file['size'];
    $bukti_error = $bukti_file['error'];
    
    // Validasi file
    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/pjpeg', 'image/x-png'];
    $allowed_extensions = ['jpg', 'jpeg', 'png'];
    $max_size = 5 * 1024 * 1024; // 5MB
    
    if ($bukti_error === UPLOAD_ERR_OK) {
        $file_ext = strtolower(pathinfo($bukti_name, PATHINFO_EXTENSION));
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $detected_type = finfo_file($finfo, $bukti_tmp);
        finfo_close($finfo);
        
        if ((in_array($bukti_file['type'], $allowed_types) || in_array($detected_type, $allowed_types)) && 
            in_array($file_ext, $allowed_extensions) && 
            $bukti_size <= $max_size) {
            // Generate nama file unik
            $file_ext = pathinfo($bukti_name, PATHINFO_EXTENSION);
            $new_filename = 'bukti_' . $id_pesanan . '_' . time() . '.' . $file_ext;
            
            // Use absolute path for upload (works for both XAMPP and Railway)
            $upload_dir = __DIR__ . '/../gambar/bukti/';
            $upload_path = $upload_dir . $new_filename;
            
            // Buat folder jika belum ada
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            // Upload file
            if (move_uploaded_file($bukti_tmp, $upload_path)) {
                // Use relative path for database storage and serving
                $bukti_url = 'gambar/bukti/' . $new_filename;
                
                // Cek apakah pembayaran sudah ada
                $cek = mysqli_query($conn, "SELECT * FROM data_pembayaran WHERE id_pesanan = '$id_pesanan'");

                if (mysqli_num_rows($cek) == 0) {
                    // Insert pembayaran baru
                    mysqli_query($conn, "INSERT INTO data_pembayaran (id_pesanan, metode, bukti_url, tgl_bayar)
                                            VALUES ('$id_pesanan', '$metode', '$bukti_url', '$tgl_bayar')");

                    // Update status pesanan dan metode pembayaran (single source of truth)
                    mysqli_query($conn, "UPDATE data_pesanan SET status = 'Sudah Dibayar', metode_pembayaran = '$metode' WHERE id_pesanan = '$id_pesanan'");
                    
                    // Simpan kode_pelanggan ke session untuk riwayat pesanan
                    $_SESSION['kode_pelanggan'] = $pesanan['kode_pelanggan'];
                }
                
                // Redirect otomatis ke halaman pembayaran berhasil
                header("Location: pembayaran_berhasil.php");
                exit;
            } else {
                $error = "Gagal mengupload file.";
            }
        } else {
            $error = "Format file tidak valid atau ukuran terlalu besar (Max 5MB).";
        }
    } else {
        $error = "Error saat upload file.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pembayaran - SPGFood</title>
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
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            margin: 40px auto;
        }
        
        .glass-container h2,
        .glass-container h3,
        .glass-container p {
            color: #333 !important;
        }
        
        .glass-card {
            background: #f8f9fa;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 20px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white !important;
        }
    </style>
</head>
<body>

<div class="glass-container">
    <div style="text-align: center; margin-bottom: 32px;">
        <h2 style="margin-bottom: 8px;"> Konfirmasi Pembayaran</h2>
        <p style="color: #666;">Upload bukti pembayaran Anda</p>
    </div>

    <?php if (isset($error)): ?>
    <div class="glass-card" style="margin-bottom: 24px; background: rgba(255, 68, 102, 0.1); border-color: rgba(255, 68, 102, 0.3);">
        <p style="line-height: 1.8; margin: 0; color: #ff4466 !important;"><?= $error ?></p>
    </div>
    <?php endif; ?>

    <form method="post" action="" enctype="multipart/form-data">
        <div class="form-group">
            <label class="form-label">Metode Pembayaran</label>
            <select name="metode" class="form-control" required style="color: #333 !important; background: white !important;">
                <option value="QRIS" style="color: #333 !important; background: white !important;">QRIS</option>
                <option value="Transfer BCA" style="color: #333 !important; background: white !important;">Transfer BCA</option>
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Upload Bukti Pembayaran</label>
            <input type="file" name="bukti" class="form-control" accept="image/*" required style="color: #333 !important; background: white !important;">
            <p style="color: #666 !important; font-size: 0.85rem; margin-top: 8px;">Format: JPG, PNG, JPEG (Max 5MB)</p>
        </div>
        <button type="submit" class="btn btn-success w-100">
            <span> Konfirmasi Pembayaran</span>
        </button>
    </form>

    <div style="text-align: center; margin-top: 24px;">
        <a href="pembayaran.php" class="btn btn-outline">⬅️ Kembali</a>
    </div>
</div>

<script src="../assets/js/app.js"></script>

</body>
</html>
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Upload Bukti Pembayaran</label>
            <input type="file" name="bukti" class="form-control" accept="image/*" required>
            <p style="color: var(--text-muted); font-size: 0.85rem; margin-top: 8px;">Format: JPG, PNG, JPEG (Max 5MB)</p>
        </div>
        <button type="submit" class="btn btn-success w-100">
            <span> Konfirmasi Pembayaran</span>
        </button>
    </form>

    <div style="text-align: center; margin-top: 24px;">
        <a href="pembayaran.php" class="btn btn-outline">⬅️ Kembali</a>
    </div>
</div>

<script src="../assets/js/app.js"></script>

</body>
</html>
