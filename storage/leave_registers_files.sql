-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 13, 2023 at 10:53 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `zigma_tender_dev`
--

-- --------------------------------------------------------

--
-- Table structure for table `leave_registers_files`
--

CREATE TABLE `leave_registers_files` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mainid` bigint(20) UNSIGNED NOT NULL,
  `filename` varchar(255) DEFAULT NULL,
  `filetype` varchar(255) DEFAULT NULL,
  `filesize` varchar(10) NOT NULL,
  `hasfilename` varchar(255) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `edited_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `leave_registers_files`
--

INSERT INTO `leave_registers_files` (`id`, `mainid`, `filename`, `filetype`, `filesize`, `hasfilename`, `created_by`, `edited_by`, `created_at`, `updated_at`) VALUES
(35, 93, 'repsol.jpg', 'image/jpeg', '174.192', '1683967973524repsol.jpg', 2, NULL, '2023-05-13 03:22:53', '2023-05-13 03:22:53'),
(36, 93, 'win10.jpg', 'image/jpeg', '262.951', '1683967973554win10.jpg', 2, NULL, '2023-05-13 03:22:53', '2023-05-13 03:22:53');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `leave_registers_files`
--
ALTER TABLE `leave_registers_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `leave_registers_files_mainid_foreign` (`mainid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `leave_registers_files`
--
ALTER TABLE `leave_registers_files`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `leave_registers_files`
--
ALTER TABLE `leave_registers_files`
  ADD CONSTRAINT `leave_registers_files_mainid_foreign` FOREIGN KEY (`mainid`) REFERENCES `leave_registers` (`id`) ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
