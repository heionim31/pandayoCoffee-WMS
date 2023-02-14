-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 28, 2022 at 07:34 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `isms_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `category_list`
--

CREATE TABLE `category_list` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL,
  `unit` varchar(250) NOT NULL DEFAULT 'pcs',
  `description` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `delete_flag` tinyint(1) NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `category_list`
--

INSERT INTO `category_list` (`id`, `name`, `unit`, `description`, `status`, `delete_flag`, `date_created`, `date_updated`) VALUES
(1, 'Vegetables', 'pcs', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam mi tellus, vehicula in aliquet quis, euismod id est. Vestibulum eget tellus eros. ', 1, 0, '2022-05-28 09:24:50', '2022-05-28 09:24:50'),
(2, 'Seasoning', 'pcs', 'Sed aliquet neque diam, sit amet fringilla ante tincidunt quis. Suspendisse porta, neque eget pellentesque elementum, augue ex aliquet justo, vel bibendum risus neque in urna. In feugiat sapien vel felis finibus, vitae congue ipsum efficitur', 1, 0, '2022-05-28 09:25:52', '2022-05-28 09:25:52'),
(3, 'Dairy Products', 'pcs', 'Aliquam in sollicitudin eros. Fusce tortor massa, pulvinar ac nunc non, maximus elementum nunc.', 1, 0, '2022-05-28 09:28:35', '2022-05-28 09:28:35'),
(4, 'Meat', 'pcs', 'Curabitur et ornare nisl. Sed non nulla urna. Etiam imperdiet sem turpis, nec cursus mauris malesuada quis.', 1, 0, '2022-05-28 09:30:40', '2022-05-28 09:30:40'),
(5, 'Test', 'pcs', 'test123', 1, 1, '2022-05-28 09:36:45', '2022-05-28 09:37:28');

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
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `item_list`
--

INSERT INTO `item_list` (`id`, `category_id`, `name`, `unit`, `description`, `status`, `delete_flag`, `date_created`, `date_updated`) VALUES
(1, 1, 'Onion Large', 'pcs', 'Duis nec nulla egestas, porta nibh vitae, interdum massa. Duis blandit quam mauris, vel fermentum libero pulvinar ac. Sed vel tempor urna.', 1, 0, '2022-05-28 09:56:19', '2022-05-28 09:56:19'),
(2, 1, 'String Onions', 'pcs', 'Morbi ligula lorem, blandit ac nisl non, facilisis eleifend nunc. Nunc placerat sem dolor, eu bibendum mauris tincidunt et. Suspendisse est ex, vehicula sed cursus nec, pulvinar eu massa.', 1, 0, '2022-05-28 09:57:51', '2022-05-28 09:57:51'),
(3, 1, 'Garlic Large', 'pcs', 'Sed sollicitudin, est at semper pellentesque, arcu elit malesuada ex, vel pulvinar nisi quam sed ante.', 1, 0, '2022-05-28 09:59:26', '2022-05-28 09:59:26'),
(4, 2, 'Black Pepper (Powder)', 'Pack', 'Praesent posuere tortor sit amet faucibus commodo. Ut luctus sem sit amet turpis ullamcorper, ut ultricies tortor sollicitudin.', 1, 0, '2022-05-28 10:00:05', '2022-05-28 10:00:05');

-- --------------------------------------------------------

--
-- Table structure for table `stockin_list`
--

CREATE TABLE `stockin_list` (
  `id` int(30) NOT NULL,
  `item_id` int(30) NOT NULL,
  `date` date NOT NULL,
  `quantity` float(12,2) NOT NULL DEFAULT 0.00,
  `remarks` text NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `stockin_list`
--

INSERT INTO `stockin_list` (`id`, `item_id`, `date`, `quantity`, `remarks`, `date_created`, `date_updated`) VALUES
(2, 4, '2022-05-28', 25.00, 'Sample', '2022-05-28 10:48:35', '2022-05-28 10:50:30'),
(4, 4, '2022-05-13', 35.00, 'Test #101', '2022-05-28 10:57:15', '2022-05-28 10:57:15'),
(5, 3, '2022-05-19', 35.00, 'Sample', '2022-05-28 11:27:48', '2022-05-28 11:27:48');

-- --------------------------------------------------------

--
-- Table structure for table `stockout_list`
--

CREATE TABLE `stockout_list` (
  `id` int(30) NOT NULL,
  `item_id` int(30) NOT NULL,
  `date` date NOT NULL,
  `quantity` float(12,2) NOT NULL DEFAULT 0.00,
  `remarks` text NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `stockout_list`
--

INSERT INTO `stockout_list` (`id`, `item_id`, `date`, `quantity`, `remarks`, `date_created`, `date_updated`) VALUES
(2, 4, '2022-05-28', 10.00, 'Used', '2022-05-28 10:59:58', '2022-05-28 10:59:58'),
(3, 3, '2022-05-27', 2.00, 'test', '2022-05-28 11:27:58', '2022-05-28 11:27:58');

-- --------------------------------------------------------

--
-- Table structure for table `system_info`
--

CREATE TABLE `system_info` (
  `id` int(30) NOT NULL,
  `meta_field` text NOT NULL,
  `meta_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `system_info`
--

INSERT INTO `system_info` (`id`, `meta_field`, `meta_value`) VALUES
(1, 'name', 'Ingredients Stock Management System'),
(6, 'short_name', 'ISMS - PHP'),
(11, 'logo', 'uploads/logo.png?v=1653699689'),
(13, 'user_avatar', 'uploads/user_avatar.jpg'),
(14, 'cover', 'uploads/cover.png?v=1653710421'),
(17, 'phone', '456-987-1231'),
(18, 'mobile', '09123456987 / 094563212222 '),
(19, 'email', 'info@musicschool.com'),
(20, 'address', 'Here St, Down There City, Anywhere Here, 2306 -updated');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='2';

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `middlename`, `lastname`, `username`, `password`, `avatar`, `last_login`, `type`, `date_added`, `date_updated`) VALUES
(1, 'Adminstrator', '', 'Admin', 'admin', '0192023a7bbd73250516f069df18b500', 'uploads/avatars/1.png?v=1649834664', NULL, 1, '2021-01-20 14:02:37', '2022-05-16 14:17:49'),
(2, 'John', 'D', 'Smith', 'jsmith', '1254737c076cf867dc53d60a0364f38e', 'uploads/avatars/2.png?v=1653715045', NULL, 2, '2022-05-28 13:17:24', '2022-05-28 13:17:25');

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
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `waste_list`
--

INSERT INTO `waste_list` (`id`, `item_id`, `date`, `quantity`, `remarks`, `date_created`, `date_updated`) VALUES
(1, 4, '2022-05-28', 5.00, 'Rotten', '2022-05-28 11:03:06', '2022-05-28 11:03:06');

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
-- Indexes for table `stockout_list`
--
ALTER TABLE `stockout_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `system_info`
--
ALTER TABLE `system_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
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
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `item_list`
--
ALTER TABLE `item_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `stockin_list`
--
ALTER TABLE `stockin_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `stockout_list`
--
ALTER TABLE `stockout_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `system_info`
--
ALTER TABLE `system_info`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `waste_list`
--
ALTER TABLE `waste_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
