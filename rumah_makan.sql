-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 05, 2023 at 08:45 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rumah_makan`
--

-- --------------------------------------------------------

--
-- Table structure for table `detail_pesanan`
--

CREATE TABLE `detail_pesanan` (
  `id_detail_pesanan` int(11) NOT NULL,
  `id_pesanan` int(11) NOT NULL,
  `id_menu` int(11) NOT NULL,
  `harga_tertentu` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_pesanan`
--

INSERT INTO `detail_pesanan` (`id_detail_pesanan`, `id_pesanan`, `id_menu`, `harga_tertentu`, `jumlah`) VALUES
(217, 120, 12, 3000, 2),
(219, 121, 10, 20000, 1),
(226, 123, 11, 20000, 2),
(227, 123, 12, 3000, 3),
(228, 125, 10, 20000, 1),
(229, 130, 11, 20000, 1),
(232, 131, 11, 20000, 2),
(233, 131, 12, 3000, 1);

-- --------------------------------------------------------

--
-- Table structure for table `karyawan`
--

CREATE TABLE `karyawan` (
  `id_karyawan` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `nama_karyawan` varchar(50) NOT NULL,
  `level` enum('karyawan','admin') NOT NULL,
  `no_telepon` varchar(16) DEFAULT NULL,
  `alamat` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `karyawan`
--

INSERT INTO `karyawan` (`id_karyawan`, `email`, `password`, `nama_karyawan`, `level`, `no_telepon`, `alamat`) VALUES
(4, 'ardiansyahlatif642@gmail.com', '$2y$12$ibOp4VJ6Vr6L1spVOhhBweM8ljNEtExDXu1QlowT7gk0UrUnDgEYi', 'ardiansyah latif', 'admin', '+6285244749346', 'btn batumarupa'),
(6, 'ardiansyahlatif6420@gmail.com', '$2y$12$oZ6l6VcWRQ6ZIYMFFdbEtumLzgoDLsWAhdbWLf97WUlEHBTy9qlLK', 'ardiansyah latif', 'admin', '+6285244749346', ''),
(11, 'ardiansyahlatif123@gmail.com', '$2y$12$ibOp4VJ6Vr6L1spVOhhBweM8ljNEtExDXu1QlowT7gk0UrUnDgEYi', 'ardi', 'karyawan', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `label_pengeluaran`
--

CREATE TABLE `label_pengeluaran` (
  `id_label` int(11) NOT NULL,
  `label` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `label_pengeluaran`
--

INSERT INTO `label_pengeluaran` (`id_label`, `label`) VALUES
(3, 'air'),
(2, 'listrik');

-- --------------------------------------------------------

--
-- Table structure for table `label_pesanan`
--

CREATE TABLE `label_pesanan` (
  `id_label` int(11) NOT NULL,
  `label` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `label_pesanan`
--

INSERT INTO `label_pesanan` (`id_label`, `label`) VALUES
(4, 'listrik'),
(2, 'offline'),
(3, 'online');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id_menu` int(11) NOT NULL,
  `kategori` enum('makanan','minuman') NOT NULL,
  `nama_menu` varchar(30) NOT NULL,
  `harga` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id_menu`, `kategori`, `nama_menu`, `harga`) VALUES
(10, 'makanan', 'kepiting bakar', 20000),
(11, 'makanan', 'ayam bakar', 20000),
(12, 'minuman', 'es teh', 3000),
(13, 'makanan', 'nasi goreng', 13000);

-- --------------------------------------------------------

--
-- Table structure for table `pengeluaran`
--

CREATE TABLE `pengeluaran` (
  `id_pengeluaran` int(11) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `nominal` int(11) NOT NULL,
  `waktu` datetime NOT NULL,
  `id_label` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengeluaran`
--

INSERT INTO `pengeluaran` (`id_pengeluaran`, `keterangan`, `nominal`, `waktu`, `id_label`) VALUES
(1, 'bayar listrik', 20000, '2023-07-05 10:54:41', 2),
(3, 'bayar listrik', 30000, '2023-07-05 22:53:50', 2),
(4, 'bayar air', 50000, '2023-06-19 00:57:32', 3),
(5, 'bayar air', 100000, '2023-07-25 17:23:16', 3);

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--

CREATE TABLE `pesanan` (
  `id_pesanan` int(11) NOT NULL,
  `uang_pelanggan` int(11) NOT NULL,
  `uang_kembalian` int(11) NOT NULL,
  `total_bayar` int(11) NOT NULL,
  `waktu` datetime NOT NULL,
  `waktu_update` datetime DEFAULT NULL,
  `id_label` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pesanan`
--

INSERT INTO `pesanan` (`id_pesanan`, `uang_pelanggan`, `uang_kembalian`, `total_bayar`, `waktu`, `waktu_update`, `id_label`) VALUES
(120, 8000, 2000, 6000, '2023-07-05 07:48:35', NULL, 2),
(121, 50000, 30000, 20000, '2023-07-05 07:49:19', '2023-07-03 07:49:47', 3),
(123, 100000, 51000, 49000, '2023-06-19 14:45:52', '2023-07-19 14:53:26', 4),
(125, 50000, 30000, 20000, '2023-07-25 22:51:21', NULL, 2),
(130, 200000, 180000, 20000, '2023-07-26 12:42:46', NULL, 3),
(131, 50000, 7000, 43000, '2023-07-26 14:37:15', '2023-07-26 14:37:57', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD PRIMARY KEY (`id_detail_pesanan`),
  ADD KEY `FK__pesanan` (`id_pesanan`),
  ADD KEY `FK__menu` (`id_menu`);

--
-- Indexes for table `karyawan`
--
ALTER TABLE `karyawan`
  ADD PRIMARY KEY (`id_karyawan`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `label_pengeluaran`
--
ALTER TABLE `label_pengeluaran`
  ADD PRIMARY KEY (`id_label`),
  ADD UNIQUE KEY `label` (`label`);

--
-- Indexes for table `label_pesanan`
--
ALTER TABLE `label_pesanan`
  ADD PRIMARY KEY (`id_label`),
  ADD UNIQUE KEY `label` (`label`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id_menu`);

--
-- Indexes for table `pengeluaran`
--
ALTER TABLE `pengeluaran`
  ADD PRIMARY KEY (`id_pengeluaran`),
  ADD KEY `FK__label_pengeluaran` (`id_label`);

--
-- Indexes for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id_pesanan`),
  ADD KEY `FK_pesanan_label_pesanan` (`id_label`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  MODIFY `id_detail_pesanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=234;

--
-- AUTO_INCREMENT for table `karyawan`
--
ALTER TABLE `karyawan`
  MODIFY `id_karyawan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `label_pengeluaran`
--
ALTER TABLE `label_pengeluaran`
  MODIFY `id_label` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `label_pesanan`
--
ALTER TABLE `label_pesanan`
  MODIFY `id_label` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id_menu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `pengeluaran`
--
ALTER TABLE `pengeluaran`
  MODIFY `id_pengeluaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id_pesanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=132;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD CONSTRAINT `FK__menu` FOREIGN KEY (`id_menu`) REFERENCES `menu` (`id_menu`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK__pesanan` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan` (`id_pesanan`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pengeluaran`
--
ALTER TABLE `pengeluaran`
  ADD CONSTRAINT `FK__label_pengeluaran` FOREIGN KEY (`id_label`) REFERENCES `label_pengeluaran` (`id_label`) ON UPDATE CASCADE;

--
-- Constraints for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD CONSTRAINT `FK_pesanan_label_pesanan` FOREIGN KEY (`id_label`) REFERENCES `label_pesanan` (`id_label`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
