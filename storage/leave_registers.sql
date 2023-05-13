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
-- Table structure for table `leave_registers`
--

CREATE TABLE `leave_registers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `attendance_type_id` tinyint(3) UNSIGNED NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `start_time` time NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `edited_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `leave_registers`
--

INSERT INTO `leave_registers` (`id`, `user_id`, `attendance_type_id`, `from_date`, `to_date`, `start_time`, `reason`, `created_by`, `edited_by`, `created_at`, `updated_at`) VALUES
(93, 2, 1, '2023-05-12', '2023-05-12', '08:10:14', 'file wo arr', 2, NULL, '2023-05-13 03:22:53', '2023-05-13 03:22:53');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `leave_registers`
--
ALTER TABLE `leave_registers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `leave_registers_user_id_foreign` (`user_id`),
  ADD KEY `leave_registers_attendance_type_id_foreign` (`attendance_type_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `leave_registers`
--
ALTER TABLE `leave_registers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `leave_registers`
--
ALTER TABLE `leave_registers`
  ADD CONSTRAINT `leave_registers_attendance_type_id_foreign` FOREIGN KEY (`attendance_type_id`) REFERENCES `attendance_types` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `leave_registers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
