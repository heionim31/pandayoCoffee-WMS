-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 24, 2023 at 11:26 AM
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
-- Database: `pandayocoffee_wms_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `category_list`
--

CREATE TABLE `category_list` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `delete_flag` tinyint(1) NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category_list`
--

INSERT INTO `category_list` (`id`, `name`, `description`, `status`, `delete_flag`, `date_created`, `date_updated`) VALUES
(1, 'Vegetables', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam mi tellus, vehicula in aliquet quis, euismod id est. Vestibulum eget tellus eros. ', 1, 0, '2023-02-05 09:24:50', '2023-02-22 20:08:36'),
(2, 'Seasoning', 'Sed aliquet neque diam, sit amet fringilla ante tincidunt quis. Suspendisse porta, neque eget pellentesque elementum, augue ex aliquet justo, vel bibendum risus neque in urna. In feugiat sapien vel felis finibus, vitae congue ipsum efficitur', 1, 0, '2023-02-05 09:25:52', '2023-02-05 09:25:52'),
(3, 'Dairy Products', 'Aliquam in sollicitudin eros. Fusce tortor massa, pulvinar ac nunc non, maximus elementum nunc', 1, 0, '2023-02-05 09:28:35', '2023-03-22 14:38:24'),
(4, 'Meat', 'Curabitur et ornare nisl. Sed non nulla urna. Etiam imperdiet sem turpis, nec cursus mauris malesuada quis.', 1, 0, '2023-02-05 09:30:40', '2023-02-05 09:30:40');

-- --------------------------------------------------------

--
-- Table structure for table `item_list`
--

CREATE TABLE `item_list` (
  `id` int(30) NOT NULL,
  `category_id` int(30) NOT NULL,
  `name` text NOT NULL,
  `unit` varchar(250) NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `delete_flag` tinyint(1) NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `item_type` varchar(50) NOT NULL DEFAULT 'Perishable'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `item_list`
--

INSERT INTO `item_list` (`id`, `category_id`, `name`, `unit`, `description`, `status`, `delete_flag`, `date_created`, `date_updated`, `item_type`) VALUES
(18, 3, 'Coffee blackhole', 'kg', 'dsdczeqw', 1, 0, '2023-02-24 23:54:18', '2023-03-03 16:00:01', 'Perishable'),
(30, 2, 'NOT PLASTIC', 'yy', 'SDASD', 1, 0, '2023-03-22 12:21:16', '2023-03-22 12:21:16', 'Perishable'),
(32, 3, 'global item', 'yy', 'asdasd', 1, 0, '2023-03-24 16:38:53', '2023-03-24 16:38:53', 'Perishable');

-- --------------------------------------------------------

--
-- Table structure for table `stockin_list`
--

CREATE TABLE `stockin_list` (
  `id` int(30) NOT NULL,
  `item_id` int(30) NOT NULL,
  `quantity` float(12,2) NOT NULL DEFAULT 0.00,
  `remarks` text NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `date` date DEFAULT NULL,
  `expire_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stockin_list_deleted`
--

CREATE TABLE `stockin_list_deleted` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `remarks` text DEFAULT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `date` date DEFAULT NULL,
  `expire_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stockout_list`
--

CREATE TABLE `stockout_list` (
  `id` int(30) NOT NULL,
  `item_id` int(30) NOT NULL,
  `quantity` float(12,2) NOT NULL DEFAULT 0.00,
  `remarks` text NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `date` date DEFAULT NULL,
  `expire_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_notif`
--

CREATE TABLE `stock_notif` (
  `id` int(11) NOT NULL,
  `min_stock` int(11) NOT NULL,
  `max_stock` int(11) NOT NULL,
  `date_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stock_notif`
--

INSERT INTO `stock_notif` (`id`, `min_stock`, `max_stock`, `date_updated`) VALUES
(1, 100, 500, '2023-03-20 13:09:00');

-- --------------------------------------------------------

--
-- Table structure for table `system_info`
--

CREATE TABLE `system_info` (
  `id` int(30) NOT NULL,
  `meta_field` text NOT NULL,
  `meta_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_info`
--

INSERT INTO `system_info` (`id`, `meta_field`, `meta_value`) VALUES
(1, 'name', 'Pandayo Coffee WMS'),
(6, 'short_name', 'Pandayo Coffee - WMS'),
(11, 'logo', 'uploads/logo.png?v=1676774105'),
(13, 'user_avatar', 'uploads/user_avatar.jpg'),
(14, 'cover', 'uploads/cover.png?v=1676810350'),
(17, 'phone', '456-987-1231'),
(18, 'mobile', '09123456987 / 094563212222 '),
(19, 'email', 'info@musicschool.com'),
(20, 'address', 'Here St, Down There City, Anywhere Here, 2306 -updated');

-- --------------------------------------------------------

--
-- Table structure for table `unit_list`
--

CREATE TABLE `unit_list` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `abbreviation` varchar(10) DEFAULT NULL,
  `description` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `delete_flag` tinyint(1) NOT NULL DEFAULT 0,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `unit_list`
--

INSERT INTO `unit_list` (`id`, `name`, `abbreviation`, `description`, `status`, `delete_flag`, `date_created`, `date_updated`) VALUES
(15, 'Milimeter', 'ML', 'asdsds', 0, 0, '2023-03-22 02:36:51', '2023-03-22 02:37:58'),
(16, 'tangena', 'tgn', 'asdadqasd', 1, 0, '2023-03-22 02:39:17', '2023-03-22 02:39:17'),
(17, 'Liters', 'L', 'dfsdfsdf', 1, 0, '2023-03-22 02:48:56', '2023-03-22 02:48:56'),
(18, 'eyyy', 'yy', 'asdadad', 1, 0, '2023-03-22 02:49:06', '2023-03-22 02:49:06'),
(19, 'GUSION', 'GU', 'ASDASDASDA', 1, 0, '2023-03-22 03:13:09', '2023-03-22 06:31:59'),
(20, 'KILO', 'K', 'KILOGRAM HAHA', 1, 0, '2023-03-24 08:35:00', '2023-03-24 08:35:00'),
(21, 'ANOTHA', 'AT', 'ASDA', 1, 0, '2023-03-24 08:35:27', '2023-03-24 08:35:27');

-- --------------------------------------------------------

--
-- Table structure for table `users_list`
--

CREATE TABLE `users_list` (
  `id` int(50) NOT NULL,
  `firstname` varchar(250) NOT NULL,
  `middlename` text DEFAULT NULL,
  `lastname` varchar(250) NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `avatar` text DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 0,
  `date_added` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='2';

--
-- Dumping data for table `users_list`
--

INSERT INTO `users_list` (`id`, `firstname`, `middlename`, `lastname`, `username`, `password`, `avatar`, `last_login`, `type`, `date_added`, `date_updated`) VALUES
(1, 'Manager', '', 'User', 'manager', '0795151defba7a4b5dfa89170de46277', 'uploads/avatars/1.png?v=1676554601', NULL, 1, '2023-01-20 14:02:37', '2023-02-22 20:04:44'),
(2, 'John Carlo', '', 'Moral', 'staff', 'de9bf5643eabf80f4a56fda3bbb84483', 'uploads/avatars/2.png?v=1676554943', NULL, 2, '2023-05-28 13:17:24', '2023-03-20 11:14:26'),
(6, 'Admin', '', 'User', 'admin', '0192023a7bbd73250516f069df18b500', NULL, NULL, 1, '2023-02-17 01:49:30', '2023-03-20 11:13:33'),
(7, 'Darryl', '', 'Panis', 'darryl123', '0a1871d3d800c50075a6b8806d05c0e2', NULL, NULL, 2, '2023-02-17 02:02:04', '2023-02-17 02:02:04');

-- --------------------------------------------------------

--
-- Table structure for table `waste_list`
--

CREATE TABLE `waste_list` (
  `id` int(30) NOT NULL,
  `item_id` int(30) NOT NULL,
  `date` date NOT NULL,
  `quantity` float(12,2) NOT NULL DEFAULT 0.00,
  `remarks` text NOT NULL,
  `expire_date` date DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category_list`
--
ALTER TABLE `category_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `item_list`
--
ALTER TABLE `item_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `stockin_list`
--
ALTER TABLE `stockin_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `stockin_list_deleted`
--
ALTER TABLE `stockin_list_deleted`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stockout_list`
--
ALTER TABLE `stockout_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `stock_notif`
--
ALTER TABLE `stock_notif`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_info`
--
ALTER TABLE `system_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `unit_list`
--
ALTER TABLE `unit_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_list`
--
ALTER TABLE `users_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `waste_list`
--
ALTER TABLE `waste_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category_list`
--
ALTER TABLE `category_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `item_list`
--
ALTER TABLE `item_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `stockin_list`
--
ALTER TABLE `stockin_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=149;

--
-- AUTO_INCREMENT for table `stockin_list_deleted`
--
ALTER TABLE `stockin_list_deleted`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=149;

--
-- AUTO_INCREMENT for table `stockout_list`
--
ALTER TABLE `stockout_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `stock_notif`
--
ALTER TABLE `stock_notif`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `system_info`
--
ALTER TABLE `system_info`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `unit_list`
--
ALTER TABLE `unit_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `users_list`
--
ALTER TABLE `users_list`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `waste_list`
--
ALTER TABLE `waste_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=149;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `item_list`
--
ALTER TABLE `item_list`
  ADD CONSTRAINT `category_id_fk_il` FOREIGN KEY (`category_id`) REFERENCES `category_list` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `stockin_list`
--
ALTER TABLE `stockin_list`
  ADD CONSTRAINT `item_id_fk_sl` FOREIGN KEY (`item_id`) REFERENCES `item_list` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `stockout_list`
--
ALTER TABLE `stockout_list`
  ADD CONSTRAINT `item_id_fk_sol` FOREIGN KEY (`item_id`) REFERENCES `item_list` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `waste_list`
--
ALTER TABLE `waste_list`
  ADD CONSTRAINT `item_id_fk_wl` FOREIGN KEY (`item_id`) REFERENCES `item_list` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
