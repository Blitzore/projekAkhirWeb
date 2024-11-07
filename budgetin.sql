-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 07, 2024 at 07:26 AM
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
-- Database: `budgetin`
--

-- --------------------------------------------------------

--
-- Table structure for table `budgets`
--

CREATE TABLE `budgets` (
  `id` int(11) NOT NULL,
  `username` varchar(12) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `month_year` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `budgets`
--

INSERT INTO `budgets` (`id`, `username`, `amount`, `month_year`) VALUES
(1, 'Ahmad12', 3000000.00, 'November 2024'),
(2, 'Ahmad12', 600000.00, 'Oktober 2024'),
(3, 'Ahmad12', 2000000.00, 'Desember 2024'),
(4, 'Ahmad12', 20000000.00, 'September 2024');

-- --------------------------------------------------------

--
-- Table structure for table `budget_categories`
--

CREATE TABLE `budget_categories` (
  `id` int(11) NOT NULL,
  `username` varchar(12) NOT NULL,
  `category_name` varchar(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `month_year` varchar(20) NOT NULL,
  `container_id` varchar(50) DEFAULT 'budgetCategory'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `budget_categories`
--

INSERT INTO `budget_categories` (`id`, `username`, `category_name`, `amount`, `month_year`, `container_id`) VALUES
(40, 'Ahmad12', 'Kendaraan', 200000.00, 'Desember 2024', 'budgetCategory'),
(44, 'Ahmad12', 'Pendidikan', 300000.00, 'Desember 2024', 'budgetCategory_1730948632318_283'),
(51, 'Ahmad12', 'Makanan', 1500000.00, 'November 2024', 'budgetCategory'),
(53, 'Ahmad12', 'Pakaian', 300000.00, 'November 2024', 'budgetCategory_1730956891780_292'),
(54, 'Ahmad12', 'Pulsa', 40000.00, 'November 2024', 'budgetCategory_1730958812829_910');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(12) DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `username`, `is_default`) VALUES
(1, 'Asuransi', NULL, 1),
(2, 'Belanja', NULL, 1),
(3, 'Cemilan', NULL, 1),
(4, 'Elektronik', NULL, 1),
(5, 'Hadiah', NULL, 1),
(6, 'Hewan Peliharaan', NULL, 1),
(7, 'Hiburan', NULL, 1),
(8, 'Kecantikan', NULL, 1),
(9, 'Kesehatan', NULL, 1),
(10, 'Makanan', NULL, 1),
(11, 'Kendaraan', NULL, 1),
(12, 'Olahraga', NULL, 1),
(13, 'Pajak', NULL, 1),
(14, 'Pakaian', NULL, 1),
(15, 'Pendidikan', NULL, 1),
(16, 'Pulsa', NULL, 1),
(17, 'Rumah', NULL, 1),
(18, 'Sosial', NULL, 1),
(19, 'Tagihan', NULL, 1),
(20, 'Transportasi', NULL, 1),
(22, 'Belanja', 'Ahmad12', 1),
(23, 'Cemilan', 'Ahmad12', 1),
(24, 'Elektronik', 'Ahmad12', 1),
(25, 'Hadiah', 'Ahmad12', 1),
(26, 'Hewan Peliharaan', 'Ahmad12', 1),
(27, 'Hiburan', 'Ahmad12', 1),
(28, 'Kecantikan', 'Ahmad12', 1),
(29, 'Kesehatan', 'Ahmad12', 1),
(30, 'Makanan', 'Ahmad12', 1),
(31, 'Kendaraan', 'Ahmad12', 1),
(32, 'Olahraga', 'Ahmad12', 1),
(33, 'Pajak', 'Ahmad12', 1),
(34, 'Pakaian', 'Ahmad12', 1),
(35, 'Pendidikan', 'Ahmad12', 1),
(36, 'Pulsa', 'Ahmad12', 1),
(37, 'Rumah', 'Ahmad12', 1),
(38, 'Sosial', 'Ahmad12', 1),
(39, 'Tagihan', 'Ahmad12', 1),
(52, 'Transportasi', 'Ahmad12', 0),
(53, 'Pulsa', 'Ahmad12', 0),
(54, 'Pulsa', 'Ahmad12', 0),
(56, 'Pakaian', 'Ahmad12', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(12) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `created_at`) VALUES
(7, 'Ahmad12', '$2y$10$.M.G5XXIBGWCVn4ftkWzz.RrB4lcXCcpb5ORd3XHmrDD/g.e99KLi', '2024-10-29 10:18:35');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `budgets`
--
ALTER TABLE `budgets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`);

--
-- Indexes for table `budget_categories`
--
ALTER TABLE `budget_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categories_ibfk_1` (`username`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `budgets`
--
ALTER TABLE `budgets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `budget_categories`
--
ALTER TABLE `budget_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `budgets`
--
ALTER TABLE `budgets`
  ADD CONSTRAINT `budgets_ibfk_1` FOREIGN KEY (`username`) REFERENCES `users` (`username`);

--
-- Constraints for table `budget_categories`
--
ALTER TABLE `budget_categories`
  ADD CONSTRAINT `budget_categories_ibfk_1` FOREIGN KEY (`username`) REFERENCES `users` (`username`);

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`username`) REFERENCES `users` (`username`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
