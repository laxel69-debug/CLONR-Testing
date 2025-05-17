-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 17, 2025 at 03:55 PM
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
-- Table structure for table `admin_logs`
--

CREATE TABLE `admin_logs` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `action` text NOT NULL,
  `timestamp` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_logs`
--

INSERT INTO `admin_logs` (`id`, `admin_id`, `action`, `timestamp`) VALUES
(1, 13, 'Updated order #3 status to completed', '2025-05-16 19:55:59'),
(2, 13, 'Updated order #4 status to pending with notes: shabu', '2025-05-16 21:02:45'),
(3, 13, 'Updated order #4 status to completed with notes: Your order will ship soon', '2025-05-16 21:20:43'),
(4, 13, 'Updated order #6 status to cancelled with notes: Unknown user', '2025-05-16 21:36:08'),
(5, 13, 'Updated order #7 status to cancelled', '2025-05-16 21:36:20'),
(6, 13, 'Updated order #8 status to completed with notes: Your payment has receive you may now wait till you...', '2025-05-16 21:59:46'),
(7, 13, 'Updated order #10 status to completed', '2025-05-17 03:02:37');

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

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `pid`, `name`, `size`, `quantity`, `price`, `image`) VALUES
(2, 1, 1, 'CIPHER SPLICED SHORTS - KHAKI/CREAM', 'Small', 1, 1100.00, 'https://dbtkco.com/cdn/shop/files/CIPHERSPLICEDSHORTS3.jpg'),
(5, 4, 1, 'CIPHER SPLICED SHORTS - KHAKI/CREAM', 'Small', 5, 1100.00, 'https://dbtkco.com/cdn/shop/files/CIPHERSPLICEDSHORTS3.jpg'),
(6, 4, 1, 'CIPHER SPLICED SHORTS - KHAKI/CREAM', 'Large', 5, 1100.00, 'https://dbtkco.com/cdn/shop/files/CIPHERSPLICEDSHORTS3.jpg'),
(7, 1, 1, 'CIPHER SPLICED SHORTS - KHAKI/CREAM', 'Small', 1, 1100.00, 'https://dbtkco.com/cdn/shop/files/CIPHERSPLICEDSHORTS3.jpg'),
(8, 1, 1, 'CIPHER SPLICED SHORTS - KHAKI/CREAM', 'Small', 1, 1100.00, 'https://dbtkco.com/cdn/shop/files/CIPHERSPLICEDSHORTS3.jpg'),
(13, 11, 2, 'CIPHER STREAK CREWNECK - BROWN CREAM', 'Small', 1, 2300.00, 'https://dbtkco.com/cdn/shop/files/CIPHERSTREAKCREWNECK3.jpg?v=1728695291'),
(14, 11, 4, 'HYPER GARAGE METAL KEYCHAIN', 'Small', 1, 350.00, 'https://dbtkco.com/cdn/shop/files/HYPERGARAGEMETALKEYCHAIN.jpg?v=1732589925');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `sender_type` enum('admin','user') NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `is_archived` tinyint(1) DEFAULT 0,
  `is_important` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
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
  `placed_on` datetime NOT NULL DEFAULT current_timestamp(),
  `payment_status` varchar(20) NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `name`, `number`, `email`, `method`, `address`, `total_products`, `total_price`, `placed_on`, `payment_status`) VALUES
(1, 12, 'Ryan Lozana', 1, 'Zekaido123@gmail.com', 'cash on delivery', '112213 13awcw langka Meycauayan Bulacan Phillipines', ', CIPHER SPLICED SHORTS - KHAKI/CREAM ( 1 )', 1100.00, '0000-00-00 00:00:00', 'Pending'),
(2, 13, 'Zeck', 12345, 'Zekaido123@gmail.com', 'cash on delivery', '1 paz Tugatog Malabon Manila Phillippines', ', CIPHER SPLICED SHORTS - KHAKI/CREAM ( 1 ), CIPHER SPLICED SHORTS - KHAKI/CREAM ( 1 )', 2200.00, '0000-00-00 00:00:00', 'Pending'),
(3, 13, 'Zeck', 2147483647, 'Zekaido123@gmail.com', 'cash on delivery', '112213 13awcw langka Meycauayan Bulacan Phillipines', ', CIPHER TEE 2025 - BLACK AND WHITE ( 1 ), CIPHER SPLICED SHORTS - BLACK/GRAY ( 1 )', 2100.00, '0000-00-00 00:00:00', 'completed'),
(4, 11, 'Ryan Lozana', 2147483647, 'ral531715@gmail.com', 'gcash', '146 Langka Modesta st., langka Meycauayan Bulacan Philippines', 'RACING PANTS (1), CIPHER TEE 2025 - BLACK AND WHITE (1)', 6995.00, '0000-00-00 00:00:00', 'completed'),
(5, 15, 'Ryan Lozana', 2147483647, 'ral531715@gmail.com', 'gcash', '146 Langka Modesta st., langka Meycauayan Bulacan Philippines', ', CIPHER TEE 2025 - BLACK AND WHITE ( 1 )', 1000.00, '0000-00-00 00:00:00', 'Pending'),
(6, 15, '', 0, '', '', '    ', 'D-SPARK PANELED PANTS - CREAM BEIGE (1)', 2700.00, '0000-00-00 00:00:00', 'cancelled'),
(7, 15, '', 0, '', '', '    ', 'CIPHER STREAK CREWNECK - BROWN CREAM (1)', 2300.00, '0000-00-00 00:00:00', 'cancelled'),
(8, 15, 'Ryan Lozana', 2147483647, 'ral531715@gmail.com', 'gcash', '146 Langka Modesta st., langka Meycauayan Bulacan Philippines', 'CIPHER STREAK CREWNECK - BROWN CREAM (1)', 2300.00, '0000-00-00 00:00:00', 'completed'),
(9, 15, 'Ryan Lozana', 2147483647, 'ral531715@gmail.com', 'gcash', '146 Langka Modesta st., langka Meycauayan Bulacan Philippines', 'RACING PANTS (1), SWIFT SHORTS - MULTI TONAL BLACK GRAY (1)', 7095.00, '0000-00-00 00:00:00', 'canceled'),
(10, 16, 'Ryan Lozana', 2147483647, 'ral531715@gmail.com', 'gcash', '146 Langka Modesta st., langka Meycauayan Bulacan Philippines', 'CIPHER STREAK CREWNECK - MID GRAY/ OFF WHITE (1), D-SPARK PANELED JACKET - CREAM BEIGE (1), OAKSHADE WIDE PANTS (1), GRAND PRIX ENAMEL PIN (1), CIPHER SPLICED SHORTS - KHAKI/CREAM (1)', 9200.00, '0000-00-00 00:00:00', 'completed'),
(11, 16, 'Ryan Lozana', 91234567, 'ral531715@gmail.com', 'gcash', '146 Langka Modesta st., langka Meycauayan Bulacan Philippines', 'D-SPARK PANELED JACKET - CREAM BEIGE (1)', 3000.00, '0000-00-00 00:00:00', 'Pending'),
(12, 16, 'Ryan Lozana', 912645698, 'ral531715@gmail.com', '', '146 Langka Modesta st., langka Meycauayan Bulacan Philippines', 'CIPHER TEE 2025 - BLACK AND WHITE (1)', 1000.00, '0000-00-00 00:00:00', 'Pending'),
(13, 16, 'Ryan Lozana', 2147483647, 'ral531715@gmail.com', 'Gcash', '146 Langka Modesta st., langka Meycauayan Bulacan Philippines', 'D-SPARK PANELED JACKET - CREAM BEIGE (1)', 3000.00, '0000-00-00 00:00:00', 'Pending'),
(14, 16, 'Ryan Lozana', 2147483647, 'ral531715@gmail.com', 'Gcash', '146 Langka Modesta st., langka Meycauayan Bulacan Philippines', 'CIPHER STREAK CREWNECK - BROWN CREAM (1)', 2300.00, '0000-00-00 00:00:00', 'Pending'),
(15, 16, 'Ryan Lozana', 2147483647, 'ral531715@gmail.com', 'Gcash', '146 Langka Modesta st., langka Meycauayan Bulacan Philippines', 'CIPHER TEE 2025 - BLACK AND WHITE (1), CIPHER TEE 2025 - BLACK AND WHITE (1)', 2000.00, '0000-00-00 00:00:00', 'Pending'),
(16, 16, 'Ryan Lozana', 2147483647, 'ral531715@gmail.com', 'Gcash', '146 Langka Modesta st., langka Meycauayan Bulacan Philippines', 'D-SPARK PANELED JACKET - CREAM BEIGE (1)', 3000.00, '0000-00-00 00:00:00', 'Pending'),
(17, 16, 'Ryan Lozana', 2147483647, 'ral531715@gmail.com', 'Gcash', '146 Langka Modesta st., langka Meycauayan Bulacan Philippines', 'CIPHER STREAK CREWNECK - BROWN CREAM (1)', 2300.00, '0000-00-00 00:00:00', 'Pending'),
(19, 16, 'Ryan Lozana', 2147483647, 'ral531715@gmail.com', 'Gcash', '146 Langka Modesta st., langka Meycauayan Bulacan Philippines', 'CIPHER STREAK CREWNECK - BROWN CREAM (1)', 2300.00, '2025-05-17 09:26:07', 'Pending'),
(20, 16, 'Ryan Lozana', 2147483647, 'ral531715@gmail.com', 'Gcash', '146 Langka Modesta st., langka Meycauayan Bulacan Philippines', 'D-SPARK PANELED JACKET - CREAM BEIGE (1)', 3000.00, '2025-05-17 15:40:43', 'Pending'),
(21, 16, 'Ryan Lozana', 2147483647, 'ral531715@gmail.com', 'Gcash', '146 Langka Modesta st., langka Meycauayan Bulacan Philippines', 'CIPHER STREAK CREWNECK - BROWN CREAM (1)', 2300.00, '2025-05-17 15:59:42', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `order_history`
--

CREATE TABLE `order_history` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `status` varchar(20) NOT NULL,
  `notes` text DEFAULT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_history`
--

INSERT INTO `order_history` (`id`, `order_id`, `status`, `notes`, `updated_by`, `updated_at`) VALUES
(1, 3, 'completed', '', 13, '2025-05-16 19:55:59'),
(2, 4, 'pending', 'shabu', 13, '2025-05-16 21:02:45'),
(3, 4, 'completed', 'Your order will ship soon', 13, '2025-05-16 21:20:43'),
(4, 6, 'cancelled', 'Unknown user', 13, '2025-05-16 21:36:08'),
(5, 7, 'cancelled', '', 13, '2025-05-16 21:36:20'),
(6, 8, 'completed', 'Your payment has receive you may now wait till your order shipped', 13, '2025-05-16 21:59:46'),
(7, 10, 'completed', '', 13, '2025-05-17 03:02:37');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL
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
  `image` varchar(100) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `created_acc` timestamp NOT NULL DEFAULT current_timestamp(),
  `Status` varchar(10) NOT NULL DEFAULT 'Active',
  `balance` decimal(65,0) NOT NULL,
  `money` decimal(65,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `user_type`, `image`, `phone`, `created_acc`, `Status`, `balance`, `money`) VALUES
(6, 'Mi', 'a@a', '0cc175b9c0f1b6a831c399e269772661', 'user', 'Ace.jpg', '0', '2025-05-16 07:21:33', 'Active', 0, 0),
(11, 'Ryan Lozana', 'ral531715@gmail.com', 'e807f1fcf82d132f9bb018ca6738a19f', 'user', '68272061a3dde.png', '0', '2025-05-16 07:21:33', 'Active', 0, 3005),
(12, 'aaa', 'a@a.c', '12f9cf6998d52dbe773b06f848bb3608', 'user', 'IMG_7372 (1).jpg', '0', '2025-05-16 07:21:33', 'banned', 0, 0),
(13, 'Zeck', 'Zekaido123@gmail.com', '247f8b5944da561cd6c2cda1748fb081', 'admin', '6826ec2ff24dc.jpg', '0', '2025-05-16 07:21:33', 'Active', 0, 0),
(14, 'Ryan Lozana', 'ral5131715@gmail.com', '25d55ad283aa400af464c76d713c07ad', 'user', 'Ace.jpg', '09166245138', '2025-05-16 07:21:33', 'Active', 0, 0),
(15, 'Ryan', 'kazutokir@gmail.com', '25f9e794323b453885f5181f1b624d0b', 'user', '205d97f5-14af-4ff4-b3d8-dadcb3bc217c.jfif', '9606202043', '2025-05-16 07:37:25', 'Active', 0, 9992700),
(16, 'Zeck', 'Zekaido13@gmail.com', '247f8b5944da561cd6c2cda1748fb081', 'user', 'Ace.jpg', '0921420397', '2025-05-16 18:52:03', 'Active', 0, 9965600);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_logs`
--
ALTER TABLE `admin_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_history`
--
ALTER TABLE `order_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

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
-- AUTO_INCREMENT for table `admin_logs`
--
ALTER TABLE `admin_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `order_history`
--
ALTER TABLE `order_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_logs`
--
ALTER TABLE `admin_logs`
  ADD CONSTRAINT `admin_logs_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`parent_id`) REFERENCES `messages` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_history`
--
ALTER TABLE `order_history`
  ADD CONSTRAINT `order_history_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_history_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
