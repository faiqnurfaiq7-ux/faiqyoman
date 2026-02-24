-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 13, 2026 at 03:44 AM
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
-- Database: `kasirr`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_10_22_011729_create_produk_table', 1),
(5, '2025_10_23_035647_create_transactions_table', 1),
(6, '2025_10_23_035840_create_transaction_details_table', 1),
(7, '2025_11_07_020631_add_pelanggan_id_to_transactions_table', 1),
(8, '2025_11_10_014614_create_pelanggans_table', 1),
(9, '2025_11_10_022519_create_penjualans_table', 1),
(10, '2025_11_10_042932_add_foto_to_produk_table', 1),
(11, '2025_11_13_000000_add_payment_method_to_transactions', 1),
(12, '2025_11_13_create_penjuals_table', 1),
(13, '2025_11_14_123456_add_provider_fields_to_users_table', 1),
(14, '2025_11_14_125500_create_social_accounts_table', 1),
(15, '2025_11_14_153000_add_qris_payload_to_transactions', 1),
(16, '2025_11_14_160000_modify_harga_precision', 1),
(17, '2025_11_14_200000_add_cash_to_payment_method', 1),
(18, '2026_01_30_052345_add_foto_to_users_table', 2),
(19, '2026_02_02_011508_add_foto_to_penjuals_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pelanggans`
--

CREATE TABLE `pelanggans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `telepon` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pelanggans`
--

INSERT INTO `pelanggans` (`id`, `nama`, `alamat`, `telepon`, `created_at`, `updated_at`) VALUES
(1, 'Danta', 'Bali,Indonesia', '01454842147848', '2026-01-29 19:19:59', '2026-01-29 19:19:59');

-- --------------------------------------------------------

--
-- Table structure for table `penjualans`
--

CREATE TABLE `penjualans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pelanggan_id` bigint(20) UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `penjuals`
--

CREATE TABLE `penjuals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telepon` varchar(255) NOT NULL,
  `alamat` text NOT NULL,
  `kota` varchar(255) NOT NULL,
  `provinsi` varchar(255) NOT NULL,
  `kode_pos` varchar(255) NOT NULL,
  `bank_name` varchar(255) NOT NULL,
  `bank_account` varchar(255) NOT NULL,
  `bank_account_name` varchar(255) NOT NULL,
  `komisi_persen` double NOT NULL DEFAULT 5,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `penjuals`
--

INSERT INTO `penjuals` (`id`, `nama`, `email`, `telepon`, `alamat`, `kota`, `provinsi`, `kode_pos`, `bank_name`, `bank_account`, `bank_account_name`, `komisi_persen`, `status`, `foto`, `created_at`, `updated_at`) VALUES
(1, 'muhammad nur faiq', 'faiq17@gmail.com', '087445699874', 'Jln.OrangStyle,Tekez,OdgjStyle Nigeria', 'NewYork', 'USA', '52565', 'BRI', '0251456842', 'FAIQBRO', 10, 'aktif', 'penjual/h5k4k0bjEKrHwIo153fyQqGcKkWHFTrlixbJeBvz.jpg', '2026-01-13 21:36:54', '2026-02-01 18:20:15');

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `harga` decimal(15,2) NOT NULL,
  `stok` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id`, `nama`, `harga`, `stok`, `created_at`, `updated_at`, `foto`) VALUES
(1, 'snsb', '150000.00', 50, '2026-01-13 21:41:53', '2026-01-13 21:41:53', 'produk/YBNHnRnz6LkhvwXsmEqcEbBuROOTnwv7doOOKhBY.jpg'),
(2, 'noah', '5000000.00', 21, '2026-01-13 21:42:20', '2026-02-01 19:29:31', 'produk/ndNkkLK5RQG5xEhExIYZGi1RToQj1qnNfci3OoAF.jpg'),
(3, 'adidas', '25000000.00', 29, '2026-01-13 21:43:03', '2026-02-01 19:29:40', 'produk/dEam3g5yvcGCZmeoxsgJTzoev0IbTJ5EtoOJSa7l.jpg'),
(4, 'Noah Red', '50000000.00', 48, '2026-01-14 18:26:03', '2026-02-01 18:48:22', 'produk/pBv1MNymdDY9JTEVABuDFNrPXXGrvDdrenCC5NWe.jpg'),
(5, 'JERSEY SNSB WORLD UNITED COLLABORATION 407 X SORRYNOTSORRYBITCH ORIGINAL 407SNSB LIMITED EDITION', '25000000.00', 51, '2026-01-14 18:26:31', '2026-01-18 18:50:44', 'produk/eAB1uCAnzdratuKMZwUaIP1RrOYZ2ebXu4rLxUVn.jpg'),
(6, 'Stone island Jersy', '2500000.00', 29, '2026-01-14 18:27:14', '2026-01-18 18:50:37', 'produk/aqVHzhe1xC4szBvdZWiVqH7ZjhiIQ6TNpBXRFKpA.jpg'),
(7, 'Vuitton Louie hoodie Monogram', '2000000.00', 31, '2026-01-14 18:28:14', '2026-01-18 18:50:54', 'produk/j6vH3FFliKmj6ndTwEX0oLYPOI0Y3OAYTrHACIIg.jpg'),
(8, 'danta', '15000.00', 50, '2026-02-12 19:43:37', '2026-02-12 19:43:37', 'produk/9thPr6yh2pSBIT4FaThwbPkJz8b7t4OozUo9HN5E.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('Iq3lokVYyyXdMm3K40c4c1pxzXBKaU4tOqyFegxY', 4, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiazZha0hzWjg2VElqRGpjTjF2aGFzOUY1OGVsVkZvVmlnUWxOalBFTiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyMToiaHR0cDovLzEyNy4wLjAuMTo4MDAwIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wcm9kdWsiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo0O30=', 1770950617);

-- --------------------------------------------------------

--
-- Table structure for table `social_accounts`
--

CREATE TABLE `social_accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `provider` varchar(255) NOT NULL,
  `provider_id` varchar(255) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `access_token` text DEFAULT NULL,
  `refresh_token` text DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pelanggan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `invoice_number` varchar(255) NOT NULL,
  `total_amount` int(11) NOT NULL,
  `payment_method` enum('QRIS','BANK','PAYLETTER','CASH') NOT NULL DEFAULT 'QRIS',
  `qris_payload` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `pelanggan_id`, `invoice_number`, `total_amount`, `payment_method`, `qris_payload`, `created_at`, `updated_at`) VALUES
(1, NULL, 'INV-20260127021520-001', 75000, 'QRIS', NULL, '2026-02-01 19:15:20', '2026-02-01 19:15:20'),
(2, NULL, 'INV-20260128021520-002', 45000, 'BANK', NULL, '2026-02-01 19:15:20', '2026-02-01 19:15:20'),
(3, NULL, 'INV-20260128021520-003', 30000, 'PAYLETTER', NULL, '2026-02-01 19:15:20', '2026-02-01 19:15:20'),
(4, NULL, 'INV-20260129021520-004', 120000, 'QRIS', NULL, '2026-02-01 19:15:20', '2026-02-01 19:15:20'),
(5, NULL, 'INV-20260130021520-005', 60000, 'BANK', NULL, '2026-02-01 19:15:20', '2026-02-01 19:15:20'),
(6, NULL, 'INV-20260131021520-006', 90000, 'PAYLETTER', NULL, '2026-02-01 19:15:20', '2026-02-01 19:15:20'),
(7, NULL, 'INV-20260201021520-007', 50000, 'QRIS', NULL, '2026-02-01 19:15:20', '2026-02-01 19:15:20'),
(8, NULL, 'INV-20260202021520-008', 105000, 'BANK', NULL, '2026-02-01 19:15:20', '2026-02-01 19:15:20'),
(9, NULL, 'INV-20260202021520-009', 45000, 'PAYLETTER', NULL, '2026-02-01 19:15:20', '2026-02-01 19:15:20'),
(10, NULL, 'INV-20260202001520-010', 80000, 'QRIS', NULL, '2026-02-01 19:15:20', '2026-02-01 19:15:20'),
(11, NULL, 'INV-20260202022931', 5000000, 'QRIS', NULL, '2026-02-01 19:29:31', '2026-02-01 19:29:31'),
(12, NULL, 'INV-20260202022940', 25000000, 'QRIS', NULL, '2026-02-01 19:29:40', '2026-02-01 19:29:40');

-- --------------------------------------------------------

--
-- Table structure for table `transaction_details`
--

CREATE TABLE `transaction_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transaction_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transaction_details`
--

INSERT INTO `transaction_details` (`id`, `transaction_id`, `product_id`, `quantity`, `price`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 5, 15000, '2026-02-01 19:15:20', '2026-02-01 19:15:20'),
(2, 2, 1, 3, 15000, '2026-02-01 19:15:20', '2026-02-01 19:15:20'),
(3, 3, 2, 2, 15000, '2026-02-01 19:15:20', '2026-02-01 19:15:20'),
(4, 4, 1, 4, 15000, '2026-02-01 19:15:20', '2026-02-01 19:15:20'),
(5, 4, 2, 4, 15000, '2026-02-01 19:15:20', '2026-02-01 19:15:20'),
(6, 5, 1, 4, 15000, '2026-02-01 19:15:20', '2026-02-01 19:15:20'),
(7, 6, 2, 6, 15000, '2026-02-01 19:15:20', '2026-02-01 19:15:20'),
(8, 7, 1, 3, 15000, '2026-02-01 19:15:20', '2026-02-01 19:15:20'),
(9, 7, 2, 1, 15000, '2026-02-01 19:15:20', '2026-02-01 19:15:20'),
(10, 8, 1, 7, 15000, '2026-02-01 19:15:20', '2026-02-01 19:15:20'),
(11, 9, 2, 3, 15000, '2026-02-01 19:15:20', '2026-02-01 19:15:20'),
(12, 10, 1, 4, 15000, '2026-02-01 19:15:20', '2026-02-01 19:15:20'),
(13, 10, 2, 2, 15000, '2026-02-01 19:15:20', '2026-02-01 19:15:20'),
(14, 11, 2, 1, 5000000, '2026-02-01 19:29:31', '2026-02-01 19:29:31'),
(15, 12, 3, 1, 25000000, '2026-02-01 19:29:40', '2026-02-01 19:29:40');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `provider` varchar(255) DEFAULT NULL,
  `provider_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `foto`, `email_verified_at`, `password`, `remember_token`, `provider`, `provider_id`, `created_at`, `updated_at`) VALUES
(1, 'faiq', 'faiq@gmail.com', NULL, NULL, '$2y$12$OU8dzpWjjAU5KEIf/cKs9OBW.kfV6dcF2GN.1rC8BsWx7Bzz.hVW.', NULL, NULL, NULL, '2026-01-13 19:48:24', '2026-01-13 19:48:24'),
(2, 'iq', 'faiqqq@gmail.com', NULL, NULL, '$2y$12$YV98ZuIlGAkf395gjALUZu5vqDrgRSpp19pqIp1EuOzOmBhUCtVAe', NULL, NULL, NULL, '2026-01-14 17:49:33', '2026-01-14 17:49:33'),
(3, 'muhammad nur faiq', 'nurfaiq@gmail.com', NULL, NULL, '$2y$12$J314MeqZ4Mq6zakWyyxkwOGFjHzhDxlto2dq3ZoVC1XrSIZ6l38WO', NULL, NULL, NULL, '2026-01-28 19:26:43', '2026-01-28 19:26:43'),
(4, 'faiqqqqq', 'faiqqqq@gmail.com', NULL, NULL, '$2y$12$t/6nRK.a4ozDczmtK4DRKelIAjalbXlCPrLLeeUqQfdgeESXbUsta', NULL, NULL, NULL, '2026-02-12 19:40:34', '2026-02-12 19:40:34');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `pelanggans`
--
ALTER TABLE `pelanggans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `penjualans`
--
ALTER TABLE `penjualans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `penjualans_pelanggan_id_foreign` (`pelanggan_id`);

--
-- Indexes for table `penjuals`
--
ALTER TABLE `penjuals`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `penjuals_email_unique` (`email`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `social_accounts`
--
ALTER TABLE `social_accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `social_accounts_provider_provider_id_unique` (`provider`,`provider_id`),
  ADD KEY `social_accounts_user_id_foreign` (`user_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transactions_invoice_number_unique` (`invoice_number`);

--
-- Indexes for table `transaction_details`
--
ALTER TABLE `transaction_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaction_details_transaction_id_foreign` (`transaction_id`),
  ADD KEY `transaction_details_product_id_foreign` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `pelanggans`
--
ALTER TABLE `pelanggans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `penjualans`
--
ALTER TABLE `penjualans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `penjuals`
--
ALTER TABLE `penjuals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `social_accounts`
--
ALTER TABLE `social_accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `transaction_details`
--
ALTER TABLE `transaction_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `penjualans`
--
ALTER TABLE `penjualans`
  ADD CONSTRAINT `penjualans_pelanggan_id_foreign` FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggans` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `social_accounts`
--
ALTER TABLE `social_accounts`
  ADD CONSTRAINT `social_accounts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transaction_details`
--
ALTER TABLE `transaction_details`
  ADD CONSTRAINT `transaction_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `produk` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaction_details_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
