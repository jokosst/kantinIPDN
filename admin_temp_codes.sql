-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 04, 2026 at 02:56 PM
-- Server version: 5.7.33
-- PHP Version: 8.3.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kasir`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_temp_codes`
--

CREATE TABLE `admin_temp_codes` (
  `id` int(11) NOT NULL,
  `code` varchar(11) NOT NULL,
  `purpose` varchar(50) NOT NULL,
  `generated_by` int(11) NOT NULL,
  `used_by` int(11) NOT NULL,
  `expires_at` timestamp NOT NULL,
  `used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin_temp_codes`
--

INSERT INTO `admin_temp_codes` (`id`, `code`, `purpose`, `generated_by`, `used_by`, `expires_at`, `used_at`, `created_at`, `updated_at`) VALUES
(1, 'RTR-841795', 'retur', 1, 6, '2026-04-11 11:46:49', '2026-04-11 11:42:54', '2026-04-11 11:41:49', '2026-04-11 11:41:49'),
(2, 'RTR-074292', 'retur', 1, 0, '2026-04-11 11:48:35', NULL, '2026-04-11 11:43:35', '2026-04-11 11:43:35'),
(3, 'RTR-194856', 'retur', 1, 0, '2026-09-04 15:00:27', NULL, '2026-09-04 14:55:27', '2026-09-04 14:55:27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_temp_codes`
--
ALTER TABLE `admin_temp_codes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_temp_codes`
--
ALTER TABLE `admin_temp_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
