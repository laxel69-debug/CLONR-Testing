-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 27, 2025 at 06:40 AM
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
-- Database: `clonr_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `pid` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `size` varchar(20) NOT NULL,
  `quantity` int(100) NOT NULL,
  `price` float(10,2) NOT NULL,
  `image` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `number` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `method` varchar(50) NOT NULL,
  `address` varchar(500) NOT NULL,
  `total_products` varchar(1000) NOT NULL,
  `total_price` float(10,2) NOT NULL,
  `placed_on` varchar(50) NOT NULL,
  `payment_status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category` varchar(20) NOT NULL,
  `details` varchar(500) NOT NULL,
  `price` int(100) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `user_type` varchar(20) NOT NULL DEFAULT 'user',
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
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
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;
COMMIT;

--
-- Table structure and contents for table `products`
--

CREATE TABLE `products` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `category` varchar(100) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `products` (`id`, `name`, `description`, `price`, `category`, `image_url`, `created_at`, `updated_at`) VALUES
(1, 'GRAND PRIX ENAMEL PIN', NULL, 300.00, 'Accessories', 'https://dbtkco.com/cdn/shop/files/GRANDPRIXENAMELPIN.png?v=1733378380', '2025-05-17 05:51:44', '2025-05-17 08:26:52'),
(2, 'HYPER GARAGE STICKER PACK', NULL, 350.00, 'Accessories', 'https://dbtkco.com/cdn/shop/files/HYPERGARAGESTICKERPACK.jpg?v=1732590086', '2025-05-17 07:54:52', '2025-05-17 07:54:52'),
(3, 'HYPER GARAGE WOVEN KEYCHAIN', NULL, 300.00, 'Accessories', 'https://dbtkco.com/cdn/shop/files/HYPERGARAGEWOVENTAG1.jpg?v=1732589996', '2025-05-17 07:57:29', '2025-05-17 07:57:29'),
(4, 'HYPER GARAGE METAL KEYCHAIN', NULL, 350.00, 'Accessories', 'https://dbtkco.com/cdn/shop/files/HYPERGARAGEMETALKEYCHAIN.jpg?v=1732589925', '2025-05-17 07:57:29', '2025-05-17 07:57:29'),
(5, 'DBTK HOLOGRAPHIC STICKER PACK', NULL, 300.00, 'Accessories', 'https://dbtkco.com/cdn/shop/products/2_2_1.jpg?v=1677252535', '2025-05-17 08:23:29', '2025-05-17 08:23:29'),
(6, 'DBTK EVERMORE SLING BAG - BLACK', NULL, 950.00, 'Accessories', 'https://dbtkco.com/cdn/shop/files/DBTKEVERMORESLINGBAG1.jpg?v=1734082111', '2025-05-17 08:24:56', '2025-05-17 08:24:56'),
(7, 'WOODLAND CIPHER FLASK - BLACK/GRAY', NULL, 1100.00, 'Accessories', 'https://dbtkco.com/cdn/shop/files/WOODLAND_CIPHER_FLASK_1.jpg?v=1721960065', '2025-05-17 08:24:56', '2025-05-17 08:24:56'),
(8, 'DBTK x MHA ENAMEL PIN', NULL, 300.00, 'Accessories', 'https://dbtkco.com/cdn/shop/products/enamel.jpg?v=1661509910', '2025-05-17 08:25:29', '2025-05-17 08:25:29'),
(9, 'WOODLAND CIPHER UMBRELLA', NULL, 900.00, 'Accessories', 'https://dbtkco.com/cdn/shop/files/WOODLANDCIPHERUMBRELLA1.jpg?v=1721958145', '2025-05-17 08:26:22', '2025-05-17 08:26:22'),
(10, 'DBTK SLANT BODY BAG', NULL, 1350.00, 'Accessories', 'https://dbtkco.com/cdn/shop/files/BAG1_9bd676b9-3db7-4ea8-adf7-190d5d446be7.jpg?v=1716898728', '2025-05-17 08:26:22', '2025-05-17 08:26:22'),
(11, 'CIPHER STREAK CREWNECK - MID GRAY/ OFF WHITE', NULL, 2300.00, 'Jackets', 'https://dbtkco.com/cdn/shop/files/CIPHER_STREAK_CREWNECK_1.jpg?v=1728695354', '2025-05-17 12:49:20', '2025-05-17 12:49:20'),
(12, 'CIPHER STREAK CREWNECK - BROWN CREAM', NULL, 2300.00, 'Jackets', 'https://dbtkco.com/cdn/shop/files/CIPHERSTREAKCREWNECK3.jpg?v=1728695291', '2025-05-17 12:49:20', '2025-05-17 12:49:20'),
(13, 'D-SPARK PANELED JACKET - CREAM BEIGE', NULL, 3000.00, 'Jackets', 'https://dbtkco.com/cdn/shop/files/D-SPARKPANELEDJACKET5.jpg?v=1728696742', '2025-05-17 12:49:20', '2025-05-17 12:49:20'),
(14, 'OAKSHADE WORKWEAR JACKET', NULL, 2800.00, 'Jackets', 'https://dbtkco.com/cdn/shop/files/OAKSHADEWORKWEARJACKET1.jpg?v=1737095917', '2025-05-17 12:49:20', '2025-05-17 12:49:20'),
(15, 'SPARK PANELED JACKET - BLACK', NULL, 3300.00, 'Jackets', 'https://dbtkco.com/cdn/shop/files/FULL-ZIP_SPARK_3.jpg?v=1737099889', '2025-05-17 12:49:20', '2025-05-17 12:49:20'),
(16, 'COMPILATION HOODIE - OFF-WHITE', NULL, 3800.00, 'Jackets', 'https://dbtkco.com/cdn/shop/files/Artboard3_c03fdea3-ed94-4eb1-9c79-da90934744c5.jpg?v=1734081482', '2025-05-17 12:49:20', '2025-05-17 12:49:20'),
(17, 'SPARK PANELED JACKET - BLUEBERRY', NULL, 3300.00, 'Jackets', 'https://dbtkco.com/cdn/shop/files/FULL-ZIPSPARK1.jpg?v=1737100793', '2025-05-17 12:49:20', '2025-05-17 12:49:20'),
(18, 'CIPHER SPLICED SHORTS - KHAKI/CREAM', NULL, 1100.00, 'Shorts', 'https://dbtkco.com/cdn/shop/files/CIPHERSPLICEDSHORTS3.jpg?v=1734574023', '2025-05-17 13:17:55', '2025-05-17 13:17:55'),
(19, 'CIPHER SPLICED SHORTS - WHITE/GREY', NULL, 1100.00, 'Shorts', 'https://dbtkco.com/cdn/shop/files/CIPHERSPLICEDSHORTS5.jpg?v=1734573884', '2025-05-17 13:17:55', '2025-05-17 13:17:55'),
(20, 'SWIFT SHORTS - MULTI TONAL BLACK GRAY', NULL, 1100.00, 'Shorts', 'https://dbtkco.com/cdn/shop/files/SWIFTSHORTS1.jpg?v=1728698184', '2025-05-17 13:17:55', '2025-05-17 13:17:55'),
(21, 'CIPHER SPLICED SHORTS - BLACK/GRAY', NULL, 1100.00, 'Shorts', 'https://dbtkco.com/cdn/shop/files/CIPHERSPLICEDSHORTS1.jpg?v=1734573884', '2025-05-17 13:17:55', '2025-05-17 13:17:55'),
(22, 'CIPHER FLOCK SHORTS - ACID WASHED BLACK', NULL, 1500.00, 'Shorts', 'https://dbtkco.com/cdn/shop/files/CIPHER_FLOCK_SHORTS_1.jpg?v=1724923454', '2025-05-17 13:17:55', '2025-05-17 13:17:55'),
(23, 'CIPHER FLOCK SHORTS - ACID DARK GRAY', NULL, 1500.00, 'Shorts', 'https://dbtkco.com/cdn/shop/files/CIPHERFLOCKSHORTS2_d69e22f6-75e3-4bc8-99a9-161f48937d9c.jpg?v=1733981325', '2025-05-17 13:17:55', '2025-05-17 13:17:55'),
(24, 'GRAND PRIX SHORTS - CREAM', NULL, 1800.00, 'Shorts', 'https://dbtkco.com/cdn/shop/files/GRANDPRIXSHORTS3.jpg?v=1733377627', '2025-05-17 13:17:55', '2025-05-17 13:17:55'),
(25, 'CIPHER FLOCK SHORTS - ACID WASHED PINK', NULL, 1500.00, 'Shorts', 'https://dbtkco.com/cdn/shop/files/CIPHERFLOCKSHORTS2.jpg?v=1724922721', '2025-05-17 13:17:55', '2025-05-17 13:17:55'),
(26, 'CIPHER TEE 2025 - BLACK AND NEON GREEN', NULL, 1000.00, 'T-shirts', 'https://dbtkco.com/cdn/shop/files/SidePocketBlackShirtFront.jpg?v=1742283606', '2025-05-17 13:50:44', '2025-05-17 13:50:44'),
(27, 'CIPHER TEE 2025 - BLACK AND WHITE', NULL, 1000.00, 'T-shirts', 'https://dbtkco.com/cdn/shop/files/Cipher_Black_and_White_Shirt_Front.jpg?v=1742283658', '2025-05-17 13:50:44', '2025-05-17 13:50:44'),
(28, 'SLANT TEE 2025 - CREAM AND BLACK', NULL, 1100.00, 'T-shirts', 'https://dbtkco.com/cdn/shop/files/Slant_Cream_Shirt_Front.jpg?v=1742283821', '2025-05-17 13:50:44', '2025-05-17 13:50:44'),
(29, 'CIPHER TEE 2025 - BLACK AND WHITE', NULL, 1000.00, 'T-shirts', 'https://dbtkco.com/cdn/shop/files/Slant_Black_White_Shirt_Front.jpg?v=1742283943', '2025-05-17 13:50:44', '2025-05-17 13:50:44'),
(30, 'MOB V2 TEE - BROWN', NULL, 1500.00, 'T-shirts', 'https://dbtkco.com/cdn/shop/files/MOBV21.jpg?v=1735981287', '2025-05-17 13:50:44', '2025-05-17 13:50:44'),
(31, 'DBTK ARC TEE - WHITE', NULL, 1450.00, 'T-shirts', 'https://dbtkco.com/cdn/shop/files/DBTKARCTEE3.jpg?v=1715245989', '2025-05-17 13:50:44', '2025-05-17 13:50:44'),
(32, 'COUPE TEE - WHITE', NULL, 1000.00, 'T-shirts', 'https://dbtkco.com/cdn/shop/files/COUPE3.jpg?v=1732697599', '2025-05-17 13:50:44', '2025-05-17 13:50:44'),
(33, 'Nationals Fruits Tee - White', NULL, 1600.00, 'T-shirts', 'https://dbtkco.com/cdn/shop/files/NationalsFruitsTee1.jpg?v=1733130710', '2025-05-17 13:50:44', '2025-05-17 13:50:44'),
(34, 'INFINITE CHASE TEE - WHITE', NULL, 950.00, 'T-shirts', 'https://dbtkco.com/cdn/shop/files/INFINITECHASE3.jpg?v=1733898703', '2025-05-17 13:50:44', '2025-05-17 13:50:44'),
(35, 'OAKSHADE WIDE PANTS', NULL, 2500.00, 'Pants', 'https://dbtkco.com/cdn/shop/files/OAKSHADEWIDEPANTS1.jpg?v=1737096791', '2025-05-17 14:08:46', '2025-05-17 14:08:46'),
(36, 'D-SPARK PANELED PANTS - CREAM BEIGE', NULL, 2700.00, 'Pants', 'https://dbtkco.com/cdn/shop/files/D-SPARKPANELEDPANTS3.jpg?v=1728696742', '2025-05-17 14:08:46', '2025-05-17 14:08:46'),
(37, 'RACING PANTS', NULL, 5995.00, 'Pants', 'https://dbtkco.com/cdn/shop/files/RACING_PANTS.jpg?v=1741421203', '2025-05-17 14:08:46', '2025-05-17 14:08:46'),
(38, 'MERGE WIDE PANTS - BLACK', NULL, 2300.00, 'Pants', 'https://dbtkco.com/cdn/shop/files/MERGE_WIDE_PANTS_1.jpg?v=1737099107', '2025-05-17 14:08:46', '2025-05-17 14:08:46'),
(39, 'MERGE WIDE PANTS - LIGHT GRAY', NULL, 2300.00, 'Pants', 'https://dbtkco.com/cdn/shop/files/MERGEWIDEPANTS3.jpg?v=1737099509', '2025-05-17 14:08:46', '2025-05-17 14:08:46'),
(40, 'SPARK PANELED WIDE PANTS - BLACK', NULL, 2500.00, 'Pants', 'https://dbtkco.com/cdn/shop/files/SPARKPANELEDWIDEPANTS3.jpg?v=1737103509', '2025-05-17 14:08:46', '2025-05-17 14:08:46');



--
-- Table structure and contents for table `product_images`
--

CREATE TABLE `product_images` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `sort_order` int(10) UNSIGNED DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `product_images` (`id`, `product_id`, `image_url`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 11, 'https://dbtkco.com/cdn/shop/files/CIPHERSTREAKCREWNECK2.jpg?v=1728695291', 0, '2025-05-17 12:57:56', '2025-05-17 12:57:56'),
(2, 11, 'https://dbtkco.com/cdn/shop/files/Artboard122.jpg?v=1728695292', 1, '2025-05-17 12:57:56', '2025-05-17 12:57:56'),
(3, 12, 'https://dbtkco.com/cdn/shop/files/CIPHERSTREAKCREWNECK4.jpg?v=1728695292', 0, '2025-05-17 12:57:56', '2025-05-17 12:57:56'),
(4, 12, 'https://dbtkco.com/cdn/shop/files/Artboard119.jpg?v=1728695292', 1, '2025-05-17 12:57:56', '2025-05-17 12:57:56'),
(5, 13, 'https://dbtkco.com/cdn/shop/files/D-SPARKPANELEDJACKET6.jpg?v=1728696743', 0, '2025-05-17 12:57:56', '2025-05-17 12:57:56'),
(6, 13, 'https://dbtkco.com/cdn/shop/files/Artboard91.jpg?v=1728696742', 1, '2025-05-17 12:57:56', '2025-05-17 12:57:56'),
(7, 14, 'https://dbtkco.com/cdn/shop/files/OAKSHADEWORKWEARJACKET2.jpg?v=1737095917', 0, '2025-05-17 12:57:57', '2025-05-17 12:57:57'),
(8, 14, 'https://dbtkco.com/cdn/shop/files/OAKSHADEPOLO.webp?v=1737095917', 1, '2025-05-17 12:57:57', '2025-05-17 12:57:57'),
(9, 15, 'https://dbtkco.com/cdn/shop/files/FULL-ZIP_SPARK_4.jpg?v=1737099889', 0, '2025-05-17 12:57:57', '2025-05-17 12:57:57'),
(10, 15, 'https://dbtkco.com/cdn/shop/files/FULL_ZIP_BLACK.webp?v=1737099889', 1, '2025-05-17 12:57:57', '2025-05-17 12:57:57'),
(11, 16, 'https://dbtkco.com/cdn/shop/files/Artboard4_313ff247-c6af-4876-b63d-2fb07b4176be.jpg?v=1734081482', 0, '2025-05-17 12:57:57', '2025-05-17 12:57:57'),
(12, 16, 'https://dbtkco.com/cdn/shop/files/DBT04646-Enhanced-NR.webp?v=1734081674', 1, '2025-05-17 12:57:57', '2025-05-17 12:57:57'),
(13, 17, 'https://dbtkco.com/cdn/shop/files/FULL-ZIPSPARK2.jpg?v=1737100793', 0, '2025-05-17 12:57:57', '2025-05-17 12:57:57'),
(14, 17, 'https://dbtkco.com/cdn/shop/files/SPARK_PANELED_JACKET_BLACK_3a5f5ec0-0682-4669-9d04-6e33d60290f5.webp?v=1737100793', 1, '2025-05-17 12:57:57', '2025-05-17 12:57:57');




/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
