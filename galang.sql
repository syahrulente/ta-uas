-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 14, 2021 at 05:50 AM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `id18797954_galang`
--

-- --------------------------------------------------------

--
-- Table structure for table `drone`
--

CREATE TABLE `drone` (
  `id` int(10) NOT NULL,
  `nama` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `harga_sewa` int(10) NOT NULL,
  `deskripsi` text COLLATE latin1_general_ci NOT NULL,
  `lokasi_foto` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `stok` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `drone`
--

INSERT INTO `drone` (`id`, `nama`, `harga_sewa`, `deskripsi`, `lokasi_foto`, `stok`) VALUES
(3, 'DJI Phantom 4', 1500000, 'An uprated camera is equipped with a 1-inch 20-megapixel sensor capable of shooting 4K/60fps video and Burst Mode stills at 14 fps. The adoption of titanium alloy and magnesium alloy construction increases the rigidity of the airframe and reduces weight, making the Phantom 4 Pro similar in weight to the Phantom 4. The FlightAutonomy system adds dual rear vision sensors and infrared sensing systems for a total of 5-direction of obstacle sensing and 4-direction of obstacle avoidance.', './foto/573744-x.png', 5);

-- --------------------------------------------------------

--
-- Table structure for table `pemesan`
--

CREATE TABLE `pemesan` (
  `id` int(10) NOT NULL,
  `nama` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `no_ktp` varchar(100) COLLATE latin1_general_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `pemesan`
--

INSERT INTO `pemesan` (`id`, `nama`, `no_ktp`) VALUES
(1, 'Galang', '1212300005000'),
(3, 'Yuda', '320320302932932323');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id` int(10) NOT NULL,
  `no_transaksi` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `id_user` int(10) NOT NULL,
  `id_pemesan` int(10) NOT NULL,
  `total_bayar` int(10) NOT NULL,
  `id_drone` int(10) NOT NULL,
  `status_barang` enum('Masih Disewa','Dikembalikan') COLLATE latin1_general_ci NOT NULL,
  `status_pembayaran` enum('Terbayar','Belum Terbayar') COLLATE latin1_general_ci NOT NULL,
  `tanggal` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id`, `no_transaksi`, `id_user`, `id_pemesan`, `total_bayar`, `id_drone`, `status_barang`, `status_pembayaran`, `tanggal`) VALUES
(1, 'DRNKU-20740341', 1, 1, 1500000, 3, 'Masih Disewa', 'Terbayar', '2021-07-14 00:00:00'),
(2, 'DRNKU-69808545', 1, 3, 1500000, 3, 'Masih Disewa', 'Belum Terbayar', '2021-07-14 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) NOT NULL,
  `full_name` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `username` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `password` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `last_login` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `username`, `password`, `last_login`) VALUES
(1, 'Galang Yudha', 'galang', 'yuda', '0000-00-00 00:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `drone`
--
ALTER TABLE `drone`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pemesan`
--
ALTER TABLE `pemesan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `drone`
--
ALTER TABLE `drone`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pemesan`
--
ALTER TABLE `pemesan`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
