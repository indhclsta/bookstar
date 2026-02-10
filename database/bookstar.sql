-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 10, 2026 at 03:56 AM
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
-- Database: `bookstar`
--

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`id`, `user_id`, `product_id`, `quantity`, `created_at`) VALUES
(47, 4, 4, 1, '2026-01-29 05:44:43');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `owner_role` enum('admin','seller') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_by`, `owner_role`, `created_at`, `is_active`) VALUES
(2, 'Teknologi & Komputer', NULL, 'admin', '2026-01-22 03:43:12', 0),
(3, 'Fiksi', NULL, 'admin', '2026-01-22 03:47:31', 1),
(4, 'Bisnis dan Ekonomi', NULL, 'admin', '2026-01-22 03:47:51', 0),
(9, 'Akademik & Pendidikan', NULL, 'admin', '2026-01-22 03:59:10', 1),
(10, 'Non-Fiksi', NULL, 'admin', '2026-01-22 04:13:09', 0),
(12, 'seller', 7, 'seller', '2026-01-22 11:38:58', 1),
(13, 'Teknologi & Komputer', NULL, 'admin', '2026-01-30 02:09:58', 1),
(15, 'Non-Fiksi', NULL, 'admin', '2026-01-30 02:54:32', 1),
(16, 'Teknologi & Komputer', 13, 'seller', '2026-01-30 03:10:24', 0),
(17, 'Kehidupan', 13, 'seller', '2026-01-30 04:53:23', 1),
(18, 'Matematika', NULL, 'admin', '2026-02-10 00:51:25', 0),
(19, 'Matematika', 7, 'seller', '2026-02-10 00:59:47', 1);

-- --------------------------------------------------------

--
-- Table structure for table `chats`
--

CREATE TABLE `chats` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chats`
--

INSERT INTO `chats` (`id`, `sender_id`, `receiver_id`, `message`, `created_at`) VALUES
(1, 7, 9, 'hai', '2026-02-08 03:27:03'),
(2, 7, 9, 'apa kabar', '2026-02-08 03:27:11'),
(3, 7, 8, 'p ', '2026-02-08 03:37:10'),
(4, 7, 9, 'p', '2026-02-08 03:51:15'),
(5, 8, 7, 'oke', '2026-02-08 04:10:32'),
(6, 7, 9, 'hai', '2026-02-09 01:30:32'),
(7, 7, 8, 'oke', '2026-02-09 01:30:50'),
(8, 7, 9, 'asalamualikum', '2026-02-10 01:07:04'),
(9, 8, 7, 'hai', '2026-02-10 01:15:13');

-- --------------------------------------------------------

--
-- Table structure for table `faq`
--

CREATE TABLE `faq` (
  `id` int(11) NOT NULL,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_code` varchar(50) NOT NULL,
  `checkout_code` varchar(50) DEFAULT NULL,
  `customer_id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `total_price` decimal(12,2) NOT NULL,
  `payment_method` enum('transfer','qris') NOT NULL,
  `payment_proof` varchar(255) DEFAULT NULL,
  `approval_status` enum('pending','approved','rejected') DEFAULT 'pending',
  `order_status` enum('pending','paid','shipped','refund') DEFAULT 'pending',
  `shipping_address` text NOT NULL,
  `tracking_url` text DEFAULT NULL,
  `resi` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `reject_reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_code`, `checkout_code`, `customer_id`, `seller_id`, `total_price`, `payment_method`, `payment_proof`, `approval_status`, `order_status`, `shipping_address`, `tracking_url`, `resi`, `created_at`, `updated_at`, `reject_reason`) VALUES
(1, 'ORD697718AE1C47B', NULL, 8, 7, 0.00, 'transfer', 'payment_697718ae1ba66_jaklat.jpg', 'pending', '', '', NULL, NULL, '2026-01-26 07:33:02', '2026-01-26 07:33:02', NULL),
(6, 'ORD69773D0B77027', NULL, 8, 11, 34000.00, 'transfer', NULL, 'pending', 'pending', 'bandung', NULL, NULL, '2026-01-26 10:08:11', '2026-01-26 10:08:11', NULL),
(7, 'ORD69775D6403DA5', NULL, 9, 11, 17000.00, 'qris', NULL, 'pending', 'pending', 'jkt', NULL, NULL, '2026-01-26 12:26:12', '2026-01-26 12:26:12', NULL),
(11, 'ORD697767E7C7460', NULL, 9, 7, 14.00, 'transfer', 'payment_697767e728078_1769433063.jpg', 'approved', 'shipped', 'jkt', 'ygygyg', 'ygyg', '2026-01-26 13:11:03', '2026-01-27 03:18:48', NULL),
(12, 'ORD697823C4A9A3A', NULL, 9, 7, 1213.00, 'transfer', 'payment_697823c499cce_1769481156.jpg', 'approved', 'shipped', 'jkt', 'https://share.google/n06gd4u6m1o3Hlgq6', 'wdscdsvdf222', '2026-01-27 02:32:36', '2026-01-27 03:10:16', NULL),
(13, 'ORD6978277129385', NULL, 9, 7, 14.00, 'transfer', 'payment_6978277120df3_1769482097.jpg', 'pending', 'pending', 'jkt', NULL, NULL, '2026-01-27 02:48:17', '2026-01-27 02:48:17', NULL),
(14, 'ORD69783511BB2EE', NULL, 9, 11, 34000.00, 'qris', 'payment_69783511a8c86_1769485585.png', 'pending', 'pending', 'jkt', NULL, NULL, '2026-01-27 03:46:26', '2026-01-27 03:46:26', NULL),
(15, 'ORD69783F6AB09CA', NULL, 8, 7, 1213.00, 'transfer', 'payment_69783f6aa8b53_1769488234.png', 'approved', '', 'bandung', NULL, NULL, '2026-01-27 04:30:34', '2026-01-27 04:31:23', NULL),
(17, 'ORD6978A697489FD', NULL, 8, 11, 17000.00, 'transfer', 'payment_6978a69744fd5_1769514647.jpg', 'pending', 'pending', 'bandung', NULL, NULL, '2026-01-27 11:50:47', '2026-01-27 11:50:47', NULL),
(19, 'ORD6978A90121A04', NULL, 8, 7, 1227.00, 'transfer', 'payment_6978a90120f45_1769515265.jpg', 'approved', 'shipped', 'bandung', 'https://jne.co.id/tracking-package', '1231121', '2026-01-27 12:01:05', '2026-02-10 01:31:02', NULL),
(20, 'ORD6978A90129BA9', NULL, 8, 11, 17000.00, 'transfer', 'payment_6978a90126e21_1769515265.png', 'pending', 'pending', 'bandung', NULL, NULL, '2026-01-27 12:01:05', '2026-01-27 12:01:05', NULL),
(21, 'ORD6978AA83E65FA', NULL, 8, 11, 17000.00, 'transfer', 'payment_6978aa83e2440_1769515651.png', 'pending', 'pending', 'bandung', NULL, NULL, '2026-01-27 12:07:32', '2026-01-27 12:07:32', NULL),
(22, 'ORD6978AA841C684', NULL, 8, 7, 1213.00, 'transfer', 'payment_6978aa841aae7_1769515652.png', 'pending', 'pending', 'bandung', NULL, NULL, '2026-01-27 12:07:32', '2026-01-27 12:07:32', NULL),
(23, 'ORD6978AB79AFDCE', NULL, 8, 7, 1213.00, 'transfer', 'payment_6978ab79ae518_1769515897.png', 'pending', 'pending', 'bandung', NULL, NULL, '2026-01-27 12:11:37', '2026-01-27 12:11:37', NULL),
(24, 'ORD6978AB79BC537', NULL, 8, 11, 17000.00, 'transfer', 'payment_6978ab79bbcc4_1769515897.png', 'pending', 'pending', 'bandung', NULL, NULL, '2026-01-27 12:11:37', '2026-01-27 12:11:37', NULL),
(25, 'ORD6978AB9E68C57', NULL, 8, 7, 1222222.00, 'transfer', 'payment_6978ab9e62a44_1769515934.png', 'rejected', 'refund', 'bandung', NULL, NULL, '2026-01-27 12:12:14', '2026-02-10 01:32:38', ''),
(26, 'ORD6978AD44BAB85', NULL, 8, 11, 17000.00, 'transfer', 'payment_6978ad44b9fa0_1769516356.png', 'pending', 'pending', 'bandung', NULL, NULL, '2026-01-27 12:19:16', '2026-01-27 12:19:16', NULL),
(27, 'ORD6978AD44D80EA', NULL, 8, 7, 1213.00, 'transfer', 'payment_6978ad44d7888_1769516356.png', 'approved', 'shipped', 'bandung', 'file:///C:/Users/user/Downloads/laporan-keuntungan-2.pdf', '12342323', '2026-01-27 12:19:16', '2026-02-10 01:27:56', NULL),
(28, 'ORD6978ADC109352', NULL, 8, 11, 17000.00, 'transfer', 'payment_6978adc108603_1769516481.png', 'pending', 'pending', 'bandung', NULL, NULL, '2026-01-27 12:21:21', '2026-01-27 12:21:21', NULL),
(29, 'ORD6978AEE90CAA3', NULL, 8, 11, 17000.00, 'transfer', 'payment_6978aee900883_1769516777.png', 'pending', 'pending', 'bandung', NULL, NULL, '2026-01-27 12:26:17', '2026-01-27 12:26:17', NULL),
(31, 'CHK-6978B2AE62FEE', NULL, 8, 11, 17000.00, 'transfer', 'payment_6978b2ae63a97_1769517742.png', 'pending', 'pending', 'bandung', NULL, NULL, '2026-01-27 12:42:22', '2026-01-27 12:42:22', NULL),
(33, 'CHK-6978B2DB63491', NULL, 8, 11, 0.00, 'transfer', 'payment_6978b2db60d3a_1769517787.png', 'pending', 'pending', 'bandung', NULL, NULL, '2026-01-27 12:43:07', '2026-01-27 12:43:07', NULL),
(34, 'CHK-6978B2DB65B72', NULL, 8, 7, 1213.00, 'transfer', 'payment_6978b2db65159_1769517787.png', 'approved', 'shipped', 'bandung', 'https://mail.google.com/mail/u/0/#inbox', '12345', '2026-01-27 12:43:07', '2026-02-10 01:04:09', NULL),
(35, 'ORD-6978B3F7CD02A', 'CHK-6978B3F7C0D65', 8, 11, 17000.00, 'transfer', 'payment_6978b3f7c2246_1769518071.png', 'pending', 'pending', 'bandung', NULL, NULL, '2026-01-27 12:47:51', '2026-01-27 12:47:51', NULL),
(36, 'ORD-6978B65BC15AC', 'CHK-6978B65BB9444', 8, 11, 17000.00, 'transfer', 'payment_6978b65bba177_1769518683.jpg', 'pending', 'pending', 'bandung', NULL, NULL, '2026-01-27 12:58:03', '2026-01-27 12:58:03', NULL),
(37, 'ORD-6978B8B742D79', 'CHK-6978B8B740DFA', 8, 11, 17000.00, 'transfer', 'payment_6978b8b74112c_1769519287.png', 'pending', 'pending', 'bandung', NULL, NULL, '2026-01-27 13:08:07', '2026-01-27 13:08:07', NULL),
(38, 'ORD-6978BA557F460', 'CHK-6978BA557A943', 8, 7, 1213.00, 'transfer', 'payment_6978ba557ae55_1769519701.jpg', 'rejected', 'refund', 'bandung', NULL, NULL, '2026-01-27 13:15:01', '2026-02-08 02:07:42', 'uang kurang'),
(39, 'ORD-6978BA558A243', 'CHK-6978BA557A943', 8, 11, 17000.00, 'transfer', 'payment_6978ba5588d53_1769519701.png', 'pending', 'pending', 'bandung', NULL, NULL, '2026-01-27 13:15:01', '2026-01-27 13:15:01', NULL),
(40, 'ORD-6978BDBB3010C', 'CHK-6978BDBB2D59D', 8, 11, 17000.00, 'transfer', 'payment_6978bdbb2eee3_1769520571.png', 'pending', 'pending', 'bandung', NULL, NULL, '2026-01-27 13:29:31', '2026-01-27 13:29:31', NULL),
(41, 'ORD-6978BDED2D5F3', 'CHK-6978BDED21423', 8, 7, 1213.00, 'transfer', 'payment_6978bded215f8_1769520621.png', 'rejected', '', 'bandung', NULL, NULL, '2026-01-27 13:30:21', '2026-02-08 01:45:13', NULL),
(42, 'ORD-6978BDED3C84A', 'CHK-6978BDED21423', 8, 11, 17000.00, 'transfer', 'payment_6978bded39c04_1769520621.png', 'pending', 'pending', 'bandung', NULL, NULL, '2026-01-27 13:30:21', '2026-01-27 13:30:21', NULL),
(43, 'ORD-6978CE0D1A07A', 'CHK-6978CE0CE6024', 8, 7, 14.00, 'transfer', 'payment_6978ce0d01c3b_1769524749.png', 'approved', 'shipped', 'bandung', 'dskdnskdns.com', '1323243423', '2026-01-27 14:39:09', '2026-01-29 08:57:38', NULL),
(44, 'ORD-697B23E95122B', 'CHK-697B23E8E28F4', 8, 7, 2000.00, 'transfer', 'payment_697b23e8edd75_1769677800.jpg', 'approved', 'shipped', 'bandung', 'uhfuef.vom', '903829489', '2026-01-29 09:10:01', '2026-01-29 09:11:37', NULL),
(45, 'ORD-6989396A7ECB0', 'CHK-6989396A64CE0', 8, 13, 70000.00, 'qris', 'payment_6989396a65076_1770600810.jpg', 'pending', 'pending', 'bandung', NULL, NULL, '2026-02-09 01:33:30', '2026-02-09 01:33:30', NULL),
(46, 'ORD-69893A0521AA5', 'CHK-69893A05132FF', 8, 11, 17000.00, 'transfer', 'payment_69893a0513695_1770600965.jpg', 'pending', 'pending', 'bandung', NULL, NULL, '2026-02-09 01:36:05', '2026-02-09 01:36:05', NULL),
(47, 'ORD-69893A0528682', 'CHK-69893A05132FF', 8, 13, 70000.00, 'transfer', 'payment_69893a0524c76_1770600965.jpg', 'pending', 'pending', 'bandung', NULL, NULL, '2026-02-09 01:36:05', '2026-02-09 01:36:05', NULL),
(48, 'ORD-698A8582DC628', 'CHK-698A8582D576E', 8, 13, 70000.00, 'qris', 'payment_698a8582d5a5f_1770685826.png', 'pending', 'pending', 'bandung', NULL, NULL, '2026-02-10 01:10:26', '2026-02-10 01:10:26', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_title` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_title`, `quantity`, `price`) VALUES
(1, 1, 4, 'coba', 2, 17000.00),
(2, 7, 4, 'coba', 1, 17000.00),
(6, 11, 2, 'wdwdwd', 1, 14.00),
(7, 12, 3, 'iya', 1, 1213.00),
(8, 13, 2, 'wdwdwd', 1, 14.00),
(9, 14, 4, 'coba', 2, 17000.00),
(10, 15, 3, 'iya', 1, 1213.00),
(12, 17, 4, 'coba', 1, 17000.00),
(14, 19, 3, 'iya', 1, 1213.00),
(15, 19, 2, 'wdwdwd', 1, 14.00),
(16, 20, 4, 'coba', 1, 17000.00),
(17, 21, 4, 'coba', 1, 17000.00),
(18, 22, 3, 'iya', 1, 1213.00),
(19, 23, 3, 'iya', 1, 1213.00),
(20, 24, 4, 'coba', 1, 17000.00),
(21, 25, 1, 'oke', 1, 1222222.00),
(22, 26, 4, 'coba', 1, 17000.00),
(23, 27, 3, 'iya', 1, 1213.00),
(24, 28, 4, 'coba', 1, 17000.00),
(25, 29, 4, 'coba', 1, 17000.00),
(27, 31, 4, 'coba', 1, 17000.00),
(28, 34, 3, 'iya', 1, 1213.00),
(29, 35, 4, 'coba', 1, 17000.00),
(30, 36, 4, 'coba', 1, 17000.00),
(31, 37, 4, 'coba', 1, 17000.00),
(32, 38, 3, 'iya', 1, 1213.00),
(33, 39, 4, 'coba', 1, 17000.00),
(34, 40, 4, 'coba', 1, 17000.00),
(35, 41, 3, 'iya', 1, 1213.00),
(36, 42, 4, 'coba', 1, 17000.00),
(37, 43, 2, 'wdwdwd', 1, 14.00),
(38, 44, 5, 'thv', 1, 2000.00),
(39, 45, 6, 'Perahu Kertas', 1, 70000.00),
(40, 46, 4, 'coba', 1, 17000.00),
(41, 47, 6, 'Perahu Kertas', 1, 70000.00),
(42, 48, 6, 'Perahu Kertas', 1, 70000.00);

-- --------------------------------------------------------

--
-- Table structure for table `order_logs`
--

CREATE TABLE `order_logs` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `status` varchar(50) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expired_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(12,2) NOT NULL,
  `cost_price` decimal(12,2) DEFAULT NULL,
  `stock` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `seller_id`, `category_id`, `name`, `description`, `price`, `cost_price`, `stock`, `image`, `created_at`, `is_active`) VALUES
(1, 7, 15, 'oke', 'rfrfrfrf', 1222222.00, 12222.00, 0, 'prod_7_1769163001.jpg', '2026-01-23 10:10:01', 0),
(2, 7, 3, 'wdwdwd', 'wdwdwdwd', 14.00, 12.00, 0, 'prod_7_1769163132.png', '2026-01-23 10:12:12', 0),
(3, 7, 12, 'iya', 'iya', 1213.00, 1212.00, 0, 'prod_7_1769164811.jpg', '2026-01-23 10:40:11', 0),
(4, 11, 3, 'coba', 'tst', 17000.00, 13000.00, 12, 'prod_11_1769394766.jpg', '2026-01-26 02:32:46', 1),
(5, 7, 12, 'thv', 'vht', 2000.00, 1000.00, 0, 'prod_7_1769677609.jpg', '2026-01-29 09:06:49', 0),
(6, 13, 3, 'Perahu Kertas', 'Karya Dee', 70000.00, 50000.00, 5, 'prod_13_1769747818.jpg', '2026-01-30 04:36:58', 1),
(7, 7, 9, 'Buku', 'novel', 8000.00, 5000.00, 0, 'prod_7_1769752488.jpg', '2026-01-30 05:54:48', 0),
(8, 7, 19, 'Buku Belajar', 'Buku', 20000.00, 10000.00, 12, 'prod_7_1770685299.jpg', '2026-02-10 01:01:39', 1),
(9, 7, 15, 'Perahu Kertas', 'Indah', 100000.00, 90999.00, 11, 'prod_7_1770691085.jpg', '2026-02-10 02:38:05', 1);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `role_name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role_name`) VALUES
(1, 'admin'),
(3, 'customer'),
(2, 'seller');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `no_tlp` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `nik` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `no_rekening` varchar(50) DEFAULT NULL,
  `qris_image` varchar(255) DEFAULT NULL,
  `is_online` tinyint(1) DEFAULT 0,
  `is_super_admin` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_expired` datetime DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `last_activity` datetime DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role_id`, `name`, `email`, `no_tlp`, `password`, `nik`, `address`, `no_rekening`, `qris_image`, `is_online`, `is_super_admin`, `created_at`, `updated_at`, `reset_token`, `reset_expired`, `photo`, `status`, `last_activity`, `is_deleted`) VALUES
(1, 1, 'Indah Callista Ex', 'indahcalistaexcella@gmail.com', NULL, '$2y$10$LNlITW0uDQLG3Lw53S5wVOdbkfNtSoc/ZO2sWrRE4A5DFSy5Jgxbu', '12345678', 'jakarta', NULL, NULL, 0, 0, '2026-01-18 09:35:48', '2026-02-10 02:10:36', NULL, NULL, 'user_1.png', 'aktif', '2026-02-10 09:10:36', 0),
(4, 3, 'Calandra', 'indah.callista26@smk.belajar.id', '12345566785', '$2y$10$iGurJVkR5sm1seubALIdke4iF.MQz5/6LXwxbeR5e9bdNG687EqX2', '1234567854', 'Jakarta', NULL, NULL, 0, 0, '2026-01-18 11:55:19', '2026-01-29 08:18:37', 'c2a094f804450d1f316010f7a82416715c362274ea8ee7dda798629ef6568a3c', '2026-01-29 16:18:37', '696eec8736d00.jpeg', 'aktif', '2026-01-29 13:09:18', 0),
(6, 2, 'asa', 'asa@gmail.com', NULL, '$2y$10$icw5c.7vZcf7weLp3HJZIe.bKR.w8TBpoXRbDvu8Lf.Ak1OIta/BS', '123456', 'jkt', '4323534635', NULL, 0, 0, '2026-01-20 04:52:46', '2026-01-28 12:32:19', NULL, NULL, 'seller_696f0a1c5b348.png', '', NULL, 0),
(7, 2, 'AccSellerr', 'seller@gmail.com', '08127613761', '$2y$10$CPmfeGT2lIT57rMa/eFg4uoZEg9b8X8d6FF6dVoAxnbNM1Jjvw/Jy', '123123123', 'sukabumi', '123', 'qris_7_1770689953.png', 0, 0, '2026-01-22 04:58:08', '2026-02-10 02:42:20', NULL, NULL, 'seller_7_1769168799.png', 'aktif', '2026-02-10 09:42:20', 0),
(8, 3, 'Customeraccount', 'customer@gmail.com', '12345451321', '$2y$10$Q4EfscRcRV8h1oNCRxFiSumqK3uyEvNVzlejvOCINI62IwM7ZGRmC', '12345566785', 'bandung', NULL, NULL, 0, 0, '2026-01-24 02:59:34', '2026-02-10 02:53:02', NULL, NULL, 'customer_8_1769225739.jpg', 'aktif', '2026-02-10 09:53:02', 0),
(9, 3, 'customer', 'csr@gmail.com', '2345654323543', '$2y$10$.6DaVqGfsCuM.Qm4Dn.9QO.wIc.qBuH3SjdsWZLeBo8r1wFEHZZCS', '12345432345', 'jkt', NULL, NULL, 0, 0, '2026-01-24 03:48:05', '2026-01-29 05:43:42', NULL, NULL, 'customer_9_1769228311.jpg', 'aktif', '2026-01-29 12:23:13', 1),
(10, 2, 'OkeSeller', 'okeseller@gmail.com', NULL, '$2y$10$bygwnB2XLEVYhIRVYIw8POfDK15.Qq0u215NA1lA/2T43.eXAZEP2', '9090909090', 'Surabaya', '', NULL, 0, 0, '2026-01-24 12:45:35', '2026-01-30 04:54:34', NULL, NULL, NULL, 'aktif', '2026-01-30 11:54:34', 0),
(11, 2, 'sellera', 'dummy@gmail.com', '13221323', '$2y$10$LUY.0HxS4gMDvjYT.ClGwe8YD.aOVAy2Ee2obU.owjr1AmgKfak3.', '', '', '1234567890', 'qris_11_1769400089.jpg', 0, 0, '2026-01-24 13:44:24', '2026-01-29 03:26:18', NULL, NULL, NULL, 'aktif', '2026-01-28 21:40:18', 0),
(13, 2, 'penerbit kece', 'penerbitk3c3@gmail.com', '081284421151', '$2y$10$vQYWsk3E7WmgAXG/v/jcnumWubHnSvAjittRHJVwDa0WrxOkQMfn6', '12434353434', 'Swiss', '673172618312', 'qris_1769654499.png', 0, 0, '2026-01-29 02:41:39', '2026-02-09 10:48:57', NULL, NULL, 'seller_697ac8e33dc25.jpg', 'aktif', '2026-02-09 17:48:57', 0),
(15, 2, 'sama', 'aja@gmail.com', '1212112123121', '$2y$10$ESGzzs5LLiyZrAkSSnRl7OTe6vxqKBTF/zE5VAhWDwafY49F8xaf.', '39827427368798', 'efewfewfewf', 'jkt', 'qris_1769655816.jpg', 0, 0, '2026-01-29 03:03:37', '2026-01-29 03:03:37', NULL, NULL, 'seller_697ace088d11f.jpg', 'aktif', NULL, 0),
(16, 2, 'Callista', 'calista@gmail.com', '081162415271', '$2y$10$.szdJxu0JT/6WoiY90ZpiOrwmZZO6wYPQ0ns1u71kpIbdOLkdS75q', '317500203007', 'jKARTa', '30032801289', 'qris_1770684918.png', 0, 0, '2026-02-10 00:55:18', '2026-02-10 00:55:18', NULL, NULL, NULL, 'aktif', NULL, 0),
(17, 2, 'Bu Azizah', 'azizah@gmail.com', '08127256186712', '$2y$10$IKz8s0eszLDkuuEQqyp1dObQeTAECrrY4/.cn.9d9rIvyhbkZUU6u', '31750817265', 'jakarta', '90909010', 'qris_1770686262.png', 0, 0, '2026-02-10 01:17:42', '2026-02-10 01:26:30', NULL, NULL, NULL, 'aktif', '2026-02-10 08:26:30', 0),
(20, 3, 'myname', 'name@gmail.com', '121212112', '$2y$10$pFKxcbCVXvV.zJTmSYdXRepFiaW96UKAzq3YbncRvJjN/nM/X9qo6', '121212112', 'jkt', NULL, NULL, 0, 0, '2026-02-10 02:50:34', '2026-02-10 02:50:34', NULL, NULL, NULL, 'aktif', NULL, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_product` (`user_id`,`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_category_active` (`name`,`owner_role`,`is_active`);

--
-- Indexes for table `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `faq`
--
ALTER TABLE `faq`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_code` (`order_code`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `seller_id` (`seller_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `order_logs`
--
ALTER TABLE `order_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_product_per_seller_active` (`seller_id`,`name`,`is_active`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role_name` (`role_name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `nik` (`nik`),
  ADD UNIQUE KEY `uniq_no_tlp` (`no_tlp`),
  ADD UNIQUE KEY `no_rekening` (`no_rekening`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `chats`
--
ALTER TABLE `chats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `faq`
--
ALTER TABLE `faq`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `order_logs`
--
ALTER TABLE `order_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chats`
--
ALTER TABLE `chats`
  ADD CONSTRAINT `chats_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `chats_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `order_logs`
--
ALTER TABLE `order_logs`
  ADD CONSTRAINT `order_logs_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
