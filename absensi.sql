-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 19, 2024 at 11:51 AM
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
-- Database: `absensi`
--

-- --------------------------------------------------------

--
-- Table structure for table `absensi`
--

CREATE TABLE `absensi` (
  `id` int(11) NOT NULL,
  `nim` varchar(10) DEFAULT NULL,
  `mata_kuliah_id` int(11) DEFAULT NULL,
  `keterangan` varchar(50) DEFAULT NULL,
  `tanggal` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `absensi`
--

INSERT INTO `absensi` (`id`, `nim`, `mata_kuliah_id`, `keterangan`, `tanggal`) VALUES
(6, '2457201053', 3, 'OFFLINE', '2024-11-19'),
(7, '2457201053', 3, 'ONLINE', '2024-11-19'),
(8, '2457201053', 3, 'SAKIT', '2024-11-19'),
(9, '2457201053', 3, 'IZIN', '2024-11-19');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_harian`
--

CREATE TABLE `jadwal_harian` (
  `id` int(11) NOT NULL,
  `hari` varchar(10) NOT NULL,
  `mata_kuliah_id` int(11) NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jadwal_harian`
--

INSERT INTO `jadwal_harian` (`id`, `hari`, `mata_kuliah_id`, `jam_mulai`, `jam_selesai`) VALUES
(1, 'Senin', 1, '17:00:00', '18:00:00'),
(2, 'Senin', 2, '18:20:00', '19:20:00'),
(3, 'Selasa', 3, '17:00:00', '18:00:00'),
(4, 'Selasa', 4, '18:20:00', '19:20:00'),
(5, 'Rabu', 5, '17:00:00', '18:00:00'),
(6, 'Rabu', 6, '18:20:00', '19:20:00'),
(7, 'Rabu', 7, '19:20:00', '20:20:00'),
(8, 'Jumat', 8, '18:20:00', '19:20:00'),
(9, 'Jumat', 9, '18:20:00', '19:20:00'),
(10, 'Jumat', 10, '19:20:00', '20:20:00');

-- --------------------------------------------------------

--
-- Table structure for table `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `nim` varchar(10) NOT NULL,
  `nama` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mahasiswa`
--

INSERT INTO `mahasiswa` (`nim`, `nama`) VALUES
('2457201053', 'Muhammad Zacky Alghifari');

-- --------------------------------------------------------

--
-- Table structure for table `mata_kuliah`
--

CREATE TABLE `mata_kuliah` (
  `id` int(11) NOT NULL,
  `nama_mata_kuliah` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mata_kuliah`
--

INSERT INTO `mata_kuliah` (`id`, `nama_mata_kuliah`) VALUES
(1, 'Pendidikan Anti Korupsi'),
(2, 'Ilmu Fiqih'),
(3, 'Kewarganegaraan'),
(4, 'Ilmu Alamiah Sosial dan Budaya Dasar'),
(5, 'Bahasa Inggris 1'),
(6, 'Keaswajaan'),
(7, 'Konsep Sistem Informasi'),
(8, 'Konsep Basis Data'),
(9, 'Dasar Pemrograman'),
(10, 'Bahasa Indonesia');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absensi`
--
ALTER TABLE `absensi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nim` (`nim`),
  ADD KEY `mata_kuliah_id` (`mata_kuliah_id`);

--
-- Indexes for table `jadwal_harian`
--
ALTER TABLE `jadwal_harian`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mata_kuliah_id` (`mata_kuliah_id`);

--
-- Indexes for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`nim`);

--
-- Indexes for table `mata_kuliah`
--
ALTER TABLE `mata_kuliah`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absensi`
--
ALTER TABLE `absensi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `jadwal_harian`
--
ALTER TABLE `jadwal_harian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `mata_kuliah`
--
ALTER TABLE `mata_kuliah`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `absensi`
--
ALTER TABLE `absensi`
  ADD CONSTRAINT `absensi_ibfk_1` FOREIGN KEY (`nim`) REFERENCES `mahasiswa` (`nim`),
  ADD CONSTRAINT `absensi_ibfk_2` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliah` (`id`);

--
-- Constraints for table `jadwal_harian`
--
ALTER TABLE `jadwal_harian`
  ADD CONSTRAINT `jadwal_harian_ibfk_1` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliah` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
