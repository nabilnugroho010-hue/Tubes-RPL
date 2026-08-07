-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 07, 2026 at 05:28 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_pemesanan`
--

-- --------------------------------------------------------

--
-- Table structure for table `data_menu`
--

CREATE TABLE `data_menu` (
  `id_menu` int(11) NOT NULL,
  `nama_menu` varchar(100) NOT NULL,
  `jenis_menu` varchar(50) NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Tersedia'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `data_menu`
--

INSERT INTO `data_menu` (`id_menu`, `nama_menu`, `jenis_menu`, `harga`, `status`) VALUES
(1, 'Nasi Ayam Goreng Serundeng', 'Makanan', 19000.00, 'Tersedia'),
(2, 'Nasi Ikan Nila', 'Makanan', 19000.00, 'Tersedia'),
(3, 'Es Teh Manis', 'Minuman', 6000.00, 'Tersedia'),
(4, 'Es Jeruk', 'Minuman', 7000.00, 'Tersedia'),
(5, 'Nasi Ayam Bakar', 'Makanan', 19000.00, 'Tersedia'),
(6, 'Perkedel Kentang', 'Camilan', 2000.00, 'Tersedia'),
(7, 'Bakwan', 'Camilan', 2000.00, 'Tersedia'),
(8, 'Tempe Mendoan', 'Camilan', 2000.00, 'Tersedia');

-- --------------------------------------------------------

--
-- Table structure for table `data_pembayaran`
--

CREATE TABLE `data_pembayaran` (
  `id_pembayaran` int(11) NOT NULL,
  `id_pesanan` int(11) NOT NULL,
  `metode` varchar(30) NOT NULL,
  `bukti_url` varchar(255) DEFAULT NULL,
  `tgl_bayar` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `data_pembayaran`
--

INSERT INTO `data_pembayaran` (`id_pembayaran`, `id_pesanan`, `metode`, `bukti_url`, `tgl_bayar`) VALUES
(1, 5, 'Transfer BCA', 'gambar/bukti/bukti_5_1785961534.jpeg', '2026-08-05 15:25:34'),
(2, 9, 'QRIS', 'gambar/bukti/bukti_9_1785962083.jpeg', '2026-08-05 15:34:43'),
(3, 10, 'QRIS', 'gambar/bukti/bukti_10_1785962122.jpeg', '2026-08-05 15:35:22'),
(4, 12, 'Transfer BCA', 'gambar/bukti/bukti_12_1785963336.png', '2026-08-05 15:55:36'),
(5, 13, 'QRIS', 'gambar/bukti/bukti_13_1785963687.png', '2026-08-05 16:01:27'),
(6, 14, 'QRIS', 'gambar/bukti/bukti_14_1785964041.png', '2026-08-05 16:07:21'),
(7, 15, 'QRIS', 'gambar/bukti/bukti_15_1785964080.png', '2026-08-05 16:08:00'),
(8, 16, 'QRIS', 'gambar/bukti/bukti_16_1785964273.png', '2026-08-05 16:11:13'),
(9, 18, 'QRIS', 'gambar/bukti/bukti_18_1785964460.png', '2026-08-05 16:14:20'),
(10, 19, 'QRIS', 'gambar/bukti/bukti_19_1785964505.png', '2026-08-05 16:15:05'),
(11, 20, 'QRIS', 'gambar/bukti/bukti_20_1785964583.png', '2026-08-05 16:16:23'),
(12, 22, 'QRIS', 'gambar/bukti/bukti_22_1785964752.png', '2026-08-05 16:19:12'),
(13, 23, 'QRIS', 'gambar/bukti/bukti_23_1785964803.png', '2026-08-05 16:20:03'),
(14, 24, 'QRIS', 'gambar/bukti/bukti_24_1785964959.png', '2026-08-05 16:22:39'),
(15, 25, 'QRIS', 'gambar/bukti/bukti_25_1785965220.png', '2026-08-05 16:27:00'),
(16, 26, 'QRIS', 'gambar/bukti/bukti_26_1785965251.png', '2026-08-05 16:27:31'),
(17, 27, 'QRIS', 'gambar/bukti/bukti_27_1785965323.png', '2026-08-05 16:28:43'),
(18, 28, 'QRIS', 'gambar/bukti/bukti_28_1785965457.png', '2026-08-05 16:30:57'),
(19, 29, 'QRIS', 'gambar/bukti/bukti_29_1785965617.png', '2026-08-05 16:33:37'),
(20, 30, 'QRIS', 'gambar/bukti/bukti_30_1785965736.png', '2026-08-05 16:35:36'),
(21, 31, 'QRIS', 'gambar/bukti/bukti_31_1785966002.png', '2026-08-05 16:40:02'),
(22, 32, 'QRIS', 'gambar/bukti/bukti_32_1785966392.png', '2026-08-05 16:46:32'),
(23, 33, 'QRIS', 'gambar/bukti/bukti_33_1785966415.png', '2026-08-05 16:46:55'),
(24, 35, 'QRIS', 'gambar/bukti/bukti_35_1785966706.png', '2026-08-05 16:51:46'),
(25, 36, 'QRIS', 'gambar/bukti/bukti_36_1785966728.png', '2026-08-05 16:52:08'),
(26, 37, 'QRIS', 'gambar/bukti/bukti_37_1785966759.png', '2026-08-05 16:52:39'),
(27, 38, 'QRIS', 'gambar/bukti/bukti_38_1785966935.png', '2026-08-05 16:55:35'),
(28, 41, 'QRIS', 'gambar/bukti/bukti_41_1785968232.png', '2026-08-05 22:17:12'),
(29, 42, 'QRIS', 'gambar/bukti/bukti_42_1785968616.png', '2026-08-05 22:23:36'),
(30, 43, 'QRIS', 'gambar/bukti/bukti_43_1785968641.png', '2026-08-05 22:24:01'),
(31, 44, 'Transfer BCA', 'gambar/bukti/bukti_44_1786010399.png', '2026-08-06 09:59:59'),
(32, 48, 'QRIS', 'gambar/bukti/bukti_48_1786025674.png', '2026-08-06 14:14:34'),
(33, 50, 'QRIS', 'gambar/bukti/bukti_50_1786027384.png', '2026-08-06 14:43:04'),
(34, 52, 'Transfer BCA', 'gambar/bukti/bukti_52_1786028482.png', '2026-08-06 15:01:22'),
(35, 53, 'Transfer BCA', 'gambar/bukti/bukti_53_1786028774.png', '2026-08-06 15:06:14'),
(36, 54, 'Transfer BCA', 'gambar/bukti/bukti_54_1786029110.png', '2026-08-06 15:11:50'),
(38, 56, 'QRIS', 'gambar/bukti/bukti_56_1786032673.png', '2026-08-06 16:11:13'),
(39, 57, 'QRIS', 'gambar/bukti/bukti_57_1786033366.png', '2026-08-06 16:22:46'),
(40, 58, 'QRIS', 'gambar/bukti/bukti_58_1786111804.png', '2026-08-07 14:10:04');

-- --------------------------------------------------------

--
-- Table structure for table `data_pesanan`
--

CREATE TABLE `data_pesanan` (
  `id_pesanan` int(11) NOT NULL,
  `tgl_pesanan` datetime DEFAULT NULL,
  `nama_pelanggan` varchar(100) DEFAULT NULL,
  `no_meja` varchar(20) DEFAULT NULL,
  `kode_pelanggan` varchar(50) DEFAULT NULL,
  `id_pelanggan` int(11) DEFAULT NULL,
  `total_harga` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` varchar(30) NOT NULL DEFAULT 'Menunggu',
  `metode_pembayaran` varchar(50) DEFAULT NULL,
  `nomor_pesanan` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `data_pesanan`
--

INSERT INTO `data_pesanan` (`id_pesanan`, `tgl_pesanan`, `nama_pelanggan`, `no_meja`, `kode_pelanggan`, `id_pelanggan`, `total_harga`, `status`, `metode_pembayaran`, `nomor_pesanan`) VALUES
(1, '2026-08-05 00:00:00', 'mn', '1', '101', NULL, 53000.00, 'Menunggu diproses', NULL, NULL),
(2, '2026-08-05 00:00:00', 'a', '1', '102', NULL, 7000.00, 'Menunggu diproses', NULL, NULL),
(3, '2026-08-05 00:00:00', 'a', '1', '103', NULL, 0.00, 'Menunggu diproses', NULL, NULL),
(4, '2026-08-05 00:00:00', 'a', '1', '104', NULL, 7000.00, 'Menunggu diproses', NULL, NULL),
(5, '2026-08-05 00:00:00', 'a', '1', '105', NULL, 13000.00, 'Sudah Dibayar', NULL, NULL),
(6, '2026-08-05 00:00:00', 'a', '1', '106', NULL, 0.00, 'Menunggu diproses', NULL, NULL),
(7, '2026-08-05 00:00:00', 'a', '1', '107', NULL, 0.00, 'Menunggu diproses', NULL, NULL),
(8, '2026-08-05 00:00:00', 'a', '1', '108', NULL, 0.00, 'Menunggu diproses', NULL, NULL),
(9, '2026-08-05 00:00:00', 'a', '1', '109', NULL, 7000.00, 'Sudah Dibayar', NULL, NULL),
(10, '2026-08-05 00:00:00', 'mn', '1', '110', NULL, 7000.00, 'Sudah Dibayar', NULL, NULL),
(11, '2026-08-05 00:00:00', 'a', '1', '111', NULL, 7000.00, 'Menunggu pembayaran', NULL, NULL),
(12, '2026-08-05 00:00:00', 'a', '22', '112', NULL, 7000.00, 'Sudah Dibayar', NULL, NULL),
(13, '2026-08-05 00:00:00', 'fas', '1', '113', NULL, 6000.00, 'Sudah Dibayar', NULL, NULL),
(14, '2026-08-05 00:00:00', 'a', '22', '114', NULL, 7000.00, 'Sudah Dibayar', NULL, 'ORD-20260805-0001'),
(15, '2026-08-05 00:00:00', 'a', '21', '115', NULL, 6000.00, 'Sudah Dibayar', NULL, 'ORD-20260805-0001'),
(16, '2026-08-05 00:00:00', 'a', '21', '116', NULL, 18000.00, 'Sudah Dibayar', NULL, 'ORD-20260805-0001'),
(17, '2026-08-05 00:00:00', 'ds', '23', '117', NULL, 7000.00, 'Menunggu pembayaran', NULL, 'ORD-20260805-0001'),
(18, '2026-08-05 00:00:00', 'ada', '1', '118', NULL, 7000.00, 'Sudah Dibayar', NULL, 'ORD-20260805-0001'),
(19, '2026-08-05 00:00:00', 'ads', '2', '119', NULL, 6000.00, 'Sudah Dibayar', NULL, 'ORD-20260805-0001'),
(20, '2026-08-05 00:00:00', 'asd', '7', '120', NULL, 7000.00, 'Sudah Dibayar', NULL, 'ORD-20260805-0001'),
(21, '2026-08-05 00:00:00', 'asd', '7', '121', NULL, 7000.00, 'Menunggu pembayaran', NULL, 'ORD-20260805-0001'),
(22, '2026-08-05 00:00:00', 'a', '1', '122', NULL, 7000.00, 'Sudah Dibayar', NULL, 'ORD-20260805-0001'),
(23, '2026-08-05 00:00:00', 'adads', '4', '123', NULL, 7000.00, 'Sudah Dibayar', NULL, 'ORD-20260805-0001'),
(24, '2026-08-05 00:00:00', 'adad', '12', '124', NULL, 7000.00, 'Sudah Dibayar', NULL, 'ORD-20260805-0001'),
(25, '2026-08-05 00:00:00', 'nabil', '2', '125', NULL, 7000.00, 'Sudah Dibayar', NULL, 'ORD-20260805-0001'),
(26, '2026-08-05 00:00:00', 'nabil', '2', '126', NULL, 7000.00, 'Sudah Dibayar', NULL, 'ORD-20260805-0001'),
(27, '2026-08-05 00:00:00', 'nabil', '2', '127', NULL, 7000.00, 'Sudah Dibayar', NULL, 'ORD-20260805-0001'),
(28, '2026-08-05 00:00:00', 'nabil', '2', '128', NULL, 6000.00, 'Sudah Dibayar', NULL, 'ORD-20260805-0001'),
(29, '2026-08-05 00:00:00', 'nabil', '2', '129', NULL, 7000.00, 'Sudah Dibayar', NULL, 'ORD-20260805-0001'),
(30, '2026-08-05 00:00:00', 'nabil', '2', '130', NULL, 7000.00, 'Sudah Dibayar', NULL, 'ORD-20260805-0001'),
(31, '2026-08-05 00:00:00', 'ojan', '2', '131', NULL, 13000.00, 'Sudah Dibayar', NULL, 'ORD-20260805-0001'),
(32, '2026-08-05 00:00:00', 'ojan', '2', '101', NULL, 6000.00, 'Sudah Dibayar', NULL, 'ORD-20260805-0001'),
(33, '2026-08-05 00:00:00', 'nabil', '1', '101', NULL, 7000.00, 'Sudah Dibayar', NULL, 'ORD-20260805-0001'),
(34, '2026-08-05 00:00:00', 'nabil', '1', '101', NULL, 18000.00, 'Menunggu pembayaran', NULL, 'ORD-20260805-0001'),
(35, '2026-08-05 00:00:00', 'nabil', '1', '101', NULL, 7000.00, 'Sudah Dibayar', NULL, 'ORD-20260805-0001'),
(36, '2026-08-05 00:00:00', 'nabil', '3', '101', NULL, 7000.00, 'Sudah Dibayar', NULL, 'ORD-20260805-0001'),
(37, '2026-08-05 00:00:00', 'adad', '12', '101', NULL, 7000.00, 'Sudah Dibayar', NULL, 'ORD-20260805-0001'),
(38, '2026-08-05 00:00:00', 'ada', '2', '2147483647', NULL, 7000.00, 'Sudah Dibayar', NULL, 'ORD-20260805-0001'),
(39, '2026-08-05 00:00:00', 'a', '4', '2147483647', NULL, 18000.00, 'Menunggu pembayaran', NULL, 'ORD-20260805-0001'),
(40, '2026-08-06 00:00:00', 'ada', '24', '0', NULL, 7000.00, 'Menunggu pembayaran', NULL, 'ORD-20260806-0001'),
(41, '2026-08-06 00:00:00', 'ada', '24', '0', NULL, 7000.00, 'Sudah Dibayar', NULL, 'ORD-20260806-0041'),
(42, '2026-08-06 00:00:00', 'ada', '24', '0', NULL, 7000.00, 'Sudah Dibayar', NULL, 'ORD-20260806-0042'),
(43, '2026-08-06 00:00:00', 'nabil', '7', '0', NULL, 14000.00, 'Sudah Dibayar', NULL, 'ORD-20260806-0043'),
(44, '2026-08-06 00:00:00', 'ada', '24', '0', NULL, 13000.00, 'Sudah Dibayar', NULL, 'ORD-20260806-0044'),
(45, '2026-08-06 00:00:00', 'ada', '24', '0', NULL, 6000.00, 'Menunggu pembayaran', NULL, 'ORD-20260806-0045'),
(46, '2026-08-06 00:00:00', 'a', '32', '0', NULL, 7000.00, 'Menunggu pembayaran', NULL, 'ORD-20260806-0046'),
(47, '2026-08-06 21:14:20', 'a', '2', 'CUST-6A7496BC47D07', NULL, 7000.00, 'Menunggu pembayaran', NULL, 'ORD-20260806-0047'),
(48, '2026-08-06 21:14:27', 'a', '2', 'CUST-6A7496C31BA1D', NULL, 7000.00, 'Sudah Dibayar', 'QRIS', 'ORD-20260806-0048'),
(50, '2026-08-06 21:42:50', 'a', '44', 'CUST-6A749D6A515C8', NULL, 7000.00, 'Sudah Dibayar', 'QRIS', 'ORD-20260806-0050'),
(51, '2026-08-06 21:43:16', 'nabil', '3', 'CUST-6A749D843F195', NULL, 7000.00, 'Sudah Dibayar', NULL, 'ORD-20260806-0051'),
(52, '2026-08-06 22:00:23', 'ada', '3', 'CUST-6A74A187122FE', NULL, 7000.00, 'Sudah Dibayar', 'Transfer BCA', 'ORD-20260806-0052'),
(53, '2026-08-06 22:06:05', 'fas', '2', 'CUST-6A74A2DD9335D', NULL, 28000.00, 'Sudah Dibayar', 'Transfer BCA', 'ORD-20260806-0053'),
(54, '2026-08-06 22:11:36', 'nabil', '3', 'CUST-6A74A42886B03', NULL, 7000.00, 'Selesai', 'Transfer BCA', 'ORD-20260806-0054'),
(56, '2026-08-06 23:10:16', 'coy', '4', 'CUST-6A74B1E81DF6B', NULL, 27000.00, 'Selesai', 'QRIS', 'ORD-20260806-0055'),
(57, '2026-08-06 23:21:18', 'Fauzan', '12', 'CUST-6A74B47E5C83B', NULL, 23000.00, 'Sudah Dibayar', 'QRIS', 'ORD-20260806-0057'),
(58, '2026-08-07 21:09:54', 'nabil', '3', 'CUST-6A75E732D67A8', NULL, 4000.00, 'Sudah Dibayar', 'QRIS', 'ORD-20260807-0001'),
(59, '2026-08-07 22:18:00', 'fas', '23', 'CUST-6A75F7281BC03', NULL, 25000.00, 'Menunggu pembayaran', NULL, 'ORD-20260807-0059');

-- --------------------------------------------------------

--
-- Table structure for table `rincian_pesanan`
--

CREATE TABLE `rincian_pesanan` (
  `id_rincian` int(11) NOT NULL,
  `id_pesanan` int(11) NOT NULL,
  `id_menu` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rincian_pesanan`
--

INSERT INTO `rincian_pesanan` (`id_rincian`, `id_pesanan`, `id_menu`, `jumlah`) VALUES
(1, 1, 4, 1),
(2, 1, 3, 1),
(3, 1, 2, 1),
(4, 1, 1, 1),
(5, 2, 4, 1),
(6, 4, 4, 1),
(7, 5, 4, 1),
(8, 5, 3, 1),
(9, 9, 4, 1),
(10, 10, 4, 1),
(11, 11, 4, 1),
(12, 12, 4, 1),
(13, 13, 3, 1),
(14, 14, 4, 1),
(15, 15, 3, 1),
(16, 16, 2, 1),
(17, 17, 4, 1),
(18, 18, 4, 1),
(19, 19, 3, 1),
(20, 20, 4, 1),
(21, 21, 4, 1),
(22, 22, 4, 1),
(23, 23, 4, 1),
(24, 24, 4, 1),
(25, 25, 4, 1),
(26, 26, 4, 1),
(27, 27, 4, 1),
(28, 28, 3, 1),
(29, 29, 4, 1),
(30, 30, 4, 1),
(31, 31, 4, 1),
(32, 31, 3, 1),
(33, 32, 3, 1),
(34, 33, 4, 1),
(35, 34, 2, 1),
(36, 35, 4, 1),
(37, 36, 4, 1),
(38, 37, 4, 1),
(39, 38, 4, 1),
(40, 39, 2, 1),
(41, 40, 4, 1),
(42, 41, 4, 1),
(43, 42, 4, 1),
(44, 43, 4, 2),
(45, 44, 4, 1),
(46, 44, 3, 1),
(47, 45, 3, 1),
(48, 46, 4, 1),
(49, 47, 4, 1),
(50, 48, 4, 1),
(52, 50, 4, 1),
(53, 51, 4, 1),
(54, 52, 4, 1),
(55, 53, 3, 1),
(56, 53, 1, 1),
(57, 54, 4, 1),
(59, 56, 7, 1),
(60, 56, 2, 1),
(61, 56, 3, 1),
(62, 57, 7, 2),
(63, 57, 5, 1),
(64, 58, 7, 2),
(65, 59, 1, 1),
(66, 59, 3, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `data_menu`
--
ALTER TABLE `data_menu`
  ADD PRIMARY KEY (`id_menu`);

--
-- Indexes for table `data_pembayaran`
--
ALTER TABLE `data_pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD KEY `id_pesanan` (`id_pesanan`);

--
-- Indexes for table `data_pesanan`
--
ALTER TABLE `data_pesanan`
  ADD PRIMARY KEY (`id_pesanan`),
  ADD KEY `idx_tgl_pesanan` (`tgl_pesanan`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_kode_pelanggan` (`kode_pelanggan`);

--
-- Indexes for table `rincian_pesanan`
--
ALTER TABLE `rincian_pesanan`
  ADD PRIMARY KEY (`id_rincian`),
  ADD KEY `id_pesanan` (`id_pesanan`),
  ADD KEY `id_menu` (`id_menu`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `data_menu`
--
ALTER TABLE `data_menu`
  MODIFY `id_menu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `data_pembayaran`
--
ALTER TABLE `data_pembayaran`
  MODIFY `id_pembayaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `data_pesanan`
--
ALTER TABLE `data_pesanan`
  MODIFY `id_pesanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `rincian_pesanan`
--
ALTER TABLE `rincian_pesanan`
  MODIFY `id_rincian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `data_pembayaran`
--
ALTER TABLE `data_pembayaran`
  ADD CONSTRAINT `data_pembayaran_ibfk_1` FOREIGN KEY (`id_pesanan`) REFERENCES `data_pesanan` (`id_pesanan`) ON DELETE CASCADE;

--
-- Constraints for table `rincian_pesanan`
--
ALTER TABLE `rincian_pesanan`
  ADD CONSTRAINT `rincian_pesanan_ibfk_1` FOREIGN KEY (`id_pesanan`) REFERENCES `data_pesanan` (`id_pesanan`) ON DELETE CASCADE,
  ADD CONSTRAINT `rincian_pesanan_ibfk_2` FOREIGN KEY (`id_menu`) REFERENCES `data_menu` (`id_menu`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
