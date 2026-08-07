<?php
header('Content-Type: application/json');
include "../koneksi.php";

if (isset($_GET['id_pesanan'])) {
    $id_pesanan = $_GET['id_pesanan'];
    $query = mysqli_query($koneksi, "SELECT status, metode_pembayaran FROM data_pesanan WHERE id_pesanan = '$id_pesanan'");
    $data = mysqli_fetch_assoc($query);
    
    if ($data) {
        echo json_encode([
            'success' => true,
            'status' => $data['status'],
            'metode_pembayaran' => $data['metode_pembayaran']
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Pesanan tidak ditemukan']);
    }
} elseif (isset($_GET['kode_pelanggan'])) {
    $kode = $_GET['kode_pelanggan'];
    $query = mysqli_query($koneksi, "SELECT id_pesanan, status, metode_pembayaran, nama_pelanggan, no_meja, total_harga, tgl_pesanan, kode_pelanggan, nomor_pesanan FROM data_pesanan WHERE kode_pelanggan = '$kode' ORDER BY id_pesanan DESC LIMIT 1");
    $data = mysqli_fetch_assoc($query);
    
    if ($data) {
        // Ambil detail pesanan
        $id_pesanan = $data['id_pesanan'];
        $detail_query = mysqli_query($koneksi, "SELECT d.*, m.nama_menu, m.harga 
                                                 FROM rincian_pesanan d 
                                                 JOIN data_menu m ON d.id_menu = m.id_menu 
                                                 WHERE d.id_pesanan = '$id_pesanan'");
        $detail = [];
        while ($row = mysqli_fetch_assoc($detail_query)) {
            $detail[] = $row;
        }
        
        echo json_encode([
            'success' => true,
            'data' => $data,
            'detail' => $detail
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Kode pelanggan tidak ditemukan']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Parameter tidak valid']);
}
?>
