-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 05, 2026 at 04:54 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `website_shoegaze`
--

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `area` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `detail` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `full` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `size` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` int UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`id`, `user_id`, `product_id`, `size`, `quantity`, `created_at`, `updated_at`) VALUES
(3, 7, 11, NULL, 1, '2026-04-02 19:22:24', '2026-04-02 19:22:24'),
(5, 3, 12, NULL, 1, '2026-04-03 08:47:54', '2026-04-03 08:47:54'),
(31, 3, 11, '40', 1, '2026-04-05 09:43:49', '2026-04-05 09:43:49'),
(32, 3, 12, '41', 1, '2026-04-05 09:44:43', '2026-04-05 09:44:43');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2024_01_01_000000_add_role_to_users_table', 1),
(6, '2026_02_22_040631_add_phone_and_address_to_users_table', 2),
(7, '2026_02_22_051718_create_products_table', 3),
(8, '2026_02_24_000000_create_cart_items_table', 4),
(9, '2026_02_28_000000_add_stock_to_products_table', 5),
(10, '2026_02_28_000002_create_transactions_table', 6),
(11, '2026_03_01_000000_add_status_to_users_table', 7),
(12, '2026_03_01_000001_create_transaction_items_table', 8),
(13, '2026_03_01_000000_modify_products_for_multiple_images_and_stock', 9),
(14, '2026_03_01_120000_add_images_and_convert_stock_in_products', 9),
(16, '2026_03_01_010000_create_addresses_table', 10),
(17, '2026_03_01_010500_add_address_and_shipping_to_transactions', 11),
(18, '2026_03_01_020000_add_shipping_option_to_transactions', 11),
(19, '2026_03_28_174425_add_stock_to_products_table', 12),
(20, '2026_03_28_190513_add_payment_details_to_transactions_table', 13),
(21, '2026_03_29_120000_add_size_to_cart_items_table', 13),
(22, '2026_03_29_123000_add_stock_json_to_products_table', 13),
(23, '2026_04_03_152202_add_status_to_products_table', 13),
(24, '2026_04_04_173623_add_shipping_to_transactions_table', 14),
(25, '2026_04_05_000001_add_checkout_details_to_transactions_table', 15),
(26, '2026_04_05_000002_add_recipient_fields_to_transactions_table', 16);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` int NOT NULL,
  `stock` text COLLATE utf8mb4_unicode_ci,
  `images` json DEFAULT NULL,
  `status` enum('Tersedia','Habis') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Tersedia',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `stock`, `images`, `status`, `created_at`, `updated_at`) VALUES
(11, 'HAZE FLOW ORANGE/PINK/UNGU HITAM', 'Haze Flow merupakan seri sepatu lari terbaru dari Nineten. Sepatu ini diperuntukkan untuk kegiatan lari Long Run baik digunakan saat Raceday dengan jarak tempuh 10k-42K. Haze Flow dilengkapi dengan mesh yang breathable yang ringan dengan support Hyperweb untuk menjaga kontruksi sepatu dan juga menambah nilai estetika sepatu. Pada midsole sepatu diterapkan teknologi BubbleInfused dengan karakteristik midsole yang responsif dan juga TPU Plate untuk menambah daya lontar sebagai daya dorong tambahan\r\n\r\n\r\nProduct Detail\r\n\r\n\r\nCategories : Road Running\r\n\r\n\r\nBest for : Raceday [10K-42K]\r\n\r\n\r\nWeight : 224g [41 UK]', 899900, '\"{\\\"39\\\":4,\\\"40\\\":7,\\\"41\\\":1,\\\"42\\\":2,\\\"43\\\":3}\"', '[\"products/62RN98mqz20c1LX4w4qKqGlVoJOU76bVTuNaPPak.png\", \"products/5AZMNBdnrrV7Ds822lBxSBOopuovPdV1sUSsANwv.png\", \"products/lffExdNn9mFg2CfOCWzXNqh67YZggGRsQ0SFVCRY.png\", \"products/kixnO2FLTlyzFHhXUxYo0lwQ0FN5aBMmKALgAIXf.png\", \"products/pLG6pnfL1X8Z0j5mM5ehAgQFP0ytlEnfAPijByT6.png\"]', 'Tersedia', '2026-03-01 01:53:15', '2026-04-04 03:53:57'),
(12, 'KANZAKI 2.0 BURGUNDY/MERAH/BIRU', 'Sepatu lari harian (daily trainer) yang dirancang khusus untuk pelari pemula. Sangat nyaman digunakan untuk lari santai (easy run) jarak 5km hingga 10km.\r\nFitur Utama:\r\nNyaman & Sejuk: Menggunakan bahan mesh satu lapis yang sangat berpori agar kaki tidak panas.\r\nEmpuk & Responsif: Teknologi Rapid Foam memberikan bantalan empuk di tumit dan dorongan bertenaga di bagian depan.\r\nAwet & Tidak Licin: Alas bawah dilapisi karet penuh (full rubber) yang tahan lama dan mencengkeram kuat di jalan aspal.\r\nDesain Sporty: Perpaduan warna merah burgundi dan biru dengan motif camo pada sol yang memberikan kesan tangguh.\r\nSpesifikasi Singkat:\r\nBerat: ±300 gram (Ringan).\r\nSaran Ukuran: Untuk kaki lebar, disarankan naik 1 nomor (upsize).', 579900, '\"{\\\"39\\\":\\\"4\\\",\\\"40\\\":\\\"0\\\",\\\"41\\\":\\\"3\\\",\\\"42\\\":\\\"6\\\",\\\"43\\\":\\\"8\\\"}\"', '[\"products/3yDMpPfYhpKS3wEDobVlX8Kqtq3gaYmAmSzwd8WW.png\", \"products/lJ0vhKacuSg0mlSTaC4S5hTs5JLskoIdRkJ7O1Z8.png\"]', 'Tersedia', '2026-03-01 02:24:50', '2026-04-03 08:24:48');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `address_id` bigint UNSIGNED DEFAULT NULL,
  `total` int NOT NULL,
  `shipping_cost` int NOT NULL DEFAULT '0',
  `shipping_option` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `proof_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'waiting',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'qris',
  `payment_proof` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'reguler',
  `selected_address_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `selected_address_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `selected_address_jalan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recipient_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `full_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `address_id`, `total`, `shipping_cost`, `shipping_option`, `method`, `proof_image`, `status`, `created_at`, `updated_at`, `payment_method`, `payment_proof`, `shipping_method`, `selected_address_name`, `selected_address_phone`, `selected_address_jalan`, `recipient_name`, `phone_number`, `full_address`) VALUES
(1, 3, NULL, 3279600, 0, NULL, 'qris', NULL, 'valid', '2026-04-03 08:43:03', '2026-04-04 09:49:50', 'qris', NULL, 'reguler', NULL, NULL, NULL, NULL, NULL, NULL),
(2, 3, NULL, 1479800, 0, NULL, 'qris', NULL, 'valid', '2026-04-03 09:46:13', '2026-04-04 09:49:36', 'qris', NULL, 'reguler', NULL, NULL, NULL, NULL, NULL, NULL),
(3, 3, NULL, 579900, 0, NULL, 'cod', NULL, 'rejected', '2026-04-03 20:54:32', '2026-04-04 09:49:46', 'cod', NULL, 'reguler', NULL, NULL, NULL, NULL, NULL, NULL),
(4, 3, NULL, 587900, 0, NULL, 'qris', NULL, 'pending', '2026-04-04 10:39:07', '2026-04-04 10:39:07', 'qris', NULL, 'reguler', NULL, NULL, NULL, NULL, NULL, NULL),
(5, 3, NULL, 1807800, 0, NULL, 'qris', NULL, 'pending', '2026-04-04 10:39:25', '2026-04-04 10:39:25', 'qris', NULL, 'reguler', NULL, NULL, NULL, NULL, NULL, NULL),
(6, 3, NULL, 907900, 0, NULL, 'qris', NULL, 'pending', '2026-04-04 10:40:29', '2026-04-04 10:40:29', 'qris', NULL, 'reguler', NULL, NULL, NULL, NULL, NULL, NULL),
(7, 3, NULL, 907900, 0, NULL, 'qris', NULL, 'pending', '2026-04-04 10:40:59', '2026-04-04 10:40:59', 'qris', NULL, 'reguler', NULL, NULL, NULL, NULL, NULL, NULL),
(8, 3, NULL, 1807800, 0, NULL, 'qris', NULL, 'pending', '2026-04-05 07:26:06', '2026-04-05 07:26:06', 'qris', NULL, 'reguler', NULL, NULL, NULL, NULL, NULL, NULL),
(9, 3, NULL, 907900, 0, NULL, 'qris', NULL, 'pending', '2026-04-05 07:35:38', '2026-04-05 07:35:38', 'qris', NULL, 'reguler', NULL, NULL, NULL, NULL, NULL, NULL),
(10, 3, NULL, 594900, 15000, NULL, 'cod', NULL, 'diproses', '2026-04-05 08:11:41', '2026-04-05 08:11:41', 'cod', NULL, 'ekspres', 'uje', '+62 851-9163-7802', 'Komplek Griya Asri Blok C3 No. 15, Jl. Pinus Wilis, RT 04/RW 12, Kelurahan Isola, Kecamatan Sukasari, Kota Bandung, Jawa Barat, 40154', 'uje', '+62 851-9163-7802', 'Komplek Griya Asri Blok C3 No. 15, Jl. Pinus Wilis, RT 04/RW 12, Kelurahan Isola, Kecamatan Sukasari, Kota Bandung, Jawa Barat, 40154');

-- --------------------------------------------------------

--
-- Table structure for table `transaction_items`
--

CREATE TABLE `transaction_items` (
  `id` bigint UNSIGNED NOT NULL,
  `transaction_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `quantity` int UNSIGNED NOT NULL DEFAULT '1',
  `price` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transaction_items`
--

INSERT INTO `transaction_items` (`id`, `transaction_id`, `product_id`, `quantity`, `price`, `created_at`, `updated_at`) VALUES
(1, 1, 11, 3, 899900, '2026-04-03 08:43:03', '2026-04-03 08:43:03'),
(2, 1, 12, 1, 579900, '2026-04-03 08:43:03', '2026-04-03 08:43:03'),
(3, 2, 11, 1, 899900, '2026-04-03 09:46:13', '2026-04-03 09:46:13'),
(4, 2, 12, 1, 579900, '2026-04-03 09:46:13', '2026-04-03 09:46:13'),
(5, 3, 12, 1, 579900, '2026-04-03 20:54:32', '2026-04-03 20:54:32'),
(6, 4, 12, 1, 579900, '2026-04-04 10:39:07', '2026-04-04 10:39:07'),
(7, 5, 11, 1, 899900, '2026-04-04 10:39:25', '2026-04-04 10:39:25'),
(8, 5, 11, 1, 899900, '2026-04-04 10:39:25', '2026-04-04 10:39:25'),
(9, 6, 11, 1, 899900, '2026-04-04 10:40:29', '2026-04-04 10:40:29'),
(10, 7, 11, 1, 899900, '2026-04-04 10:40:59', '2026-04-04 10:40:59'),
(11, 8, 11, 2, 899900, '2026-04-05 07:26:06', '2026-04-05 07:26:06'),
(12, 9, 11, 1, 899900, '2026-04-05 07:35:38', '2026-04-05 07:35:38'),
(13, 10, 12, 1, 579900, '2026-04-05 08:11:41', '2026-04-05 08:11:41');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Aktif',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `phone`, `address`, `email_verified_at`, `password`, `role`, `status`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin', 'admin@example.com', NULL, NULL, NULL, '$2y$12$PvCApVJTyKCn7B4JZP2HKenMc26pVtQEhNz1bAnm/vOvf8dgRVUJW', 'admin', 'Aktif', NULL, '2026-02-21 18:39:44', '2026-02-21 18:39:44'),
(2, 'Petugas', 'petugas', 'petugas1@example.com', NULL, NULL, NULL, '$2y$12$cCdzeC6vk869.wKpERnPx.VC8PCL3P1eb7u47ilgazstNBJ0ZSBOq', 'petugas', 'Aktif', NULL, '2026-02-21 18:43:47', '2026-02-21 18:43:47'),
(3, 'uje', 'uje', 'uje@example.com', '+62 851-9163-7802', 'Jl. Kebon Sirih Raya No. 124, RT 005/RW 002, Kelurahan Gambir, Kecamatan Gambir, Kota Jakarta Pusat, Daerah Khusus Ibukota Jakarta, 10110', NULL, '$2y$12$oPSIzN1TxehDIqNkq4C6Deuu9sEFSl.LTpYzJUtmwCE8powt4bACm', 'user', 'Aktif', NULL, '2026-02-21 18:55:51', '2026-04-05 06:56:43'),
(7, 'fauzan', 'fauzan', 'fauzan@gmail.com', NULL, NULL, NULL, '$2y$12$ywJWlUeTH0wxtD2gRi0AM.0TtUu/CG31Nnemc8KHAXlv4hSpiYfvy', 'user', 'Aktif', NULL, '2026-04-02 19:20:42', '2026-04-02 19:20:42');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `addresses_user_id_foreign` (`user_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cart_items_user_id_product_id_size_unique` (`user_id`,`product_id`,`size`),
  ADD KEY `cart_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

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
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transactions_user_id_foreign` (`user_id`),
  ADD KEY `transactions_address_id_foreign` (`address_id`);

--
-- Indexes for table `transaction_items`
--
ALTER TABLE `transaction_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaction_items_transaction_id_foreign` (`transaction_id`),
  ADD KEY `transaction_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `transaction_items`
--
ALTER TABLE `transaction_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `addresses`
--
ALTER TABLE `addresses`
  ADD CONSTRAINT `addresses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_address_id_foreign` FOREIGN KEY (`address_id`) REFERENCES `addresses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transaction_items`
--
ALTER TABLE `transaction_items`
  ADD CONSTRAINT `transaction_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaction_items_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
