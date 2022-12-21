-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 21, 2022 at 11:15 AM
-- Server version: 10.4.20-MariaDB
-- PHP Version: 8.0.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ordering_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `weboms_company_tb`
--

CREATE TABLE `weboms_company_tb` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `tel` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `weboms_company_tb`
--

INSERT INTO `weboms_company_tb` (`id`, `name`, `address`, `tel`, `description`) VALUES
(1, 'companyName', 'address', '0000', 'description');

-- --------------------------------------------------------

--
-- Table structure for table `weboms_feedback_tb`
--

CREATE TABLE `weboms_feedback_tb` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `feedback` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `weboms_menu_tb`
--

CREATE TABLE `weboms_menu_tb` (
  `orderType` int(11) NOT NULL,
  `dish` varchar(255) DEFAULT NULL,
  `price` float DEFAULT NULL,
  `picName` varchar(255) DEFAULT NULL,
  `stock` int(11) DEFAULT NULL,
  `lastModifiedBy` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `weboms_ordersdetail_tb`
--

CREATE TABLE `weboms_ordersdetail_tb` (
  `id` int(11) NOT NULL,
  `order_id` int(255) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `orderType` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `weboms_order_tb`
--

CREATE TABLE `weboms_order_tb` (
  `ID` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `or_number` int(11) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `date` datetime NOT NULL,
  `totalOrder` int(11) DEFAULT NULL,
  `payment` int(11) DEFAULT NULL,
  `staffInCharge` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `weboms_topup_tb`
--

CREATE TABLE `weboms_topup_tb` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `proofOfPayment` varchar(255) DEFAULT NULL,
  `date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `weboms_userinfo_tb`
--

CREATE TABLE `weboms_userinfo_tb` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `picName` varchar(255) DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `phoneNumber` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `otp` varchar(255) DEFAULT NULL,
  `forgetPasswordOtp` varchar(255) DEFAULT NULL,
  `balance` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `weboms_userinfo_tb`
--

INSERT INTO `weboms_userinfo_tb` (`id`, `user_id`, `name`, `picName`, `gender`, `age`, `phoneNumber`, `address`, `email`, `otp`, `forgetPasswordOtp`, `balance`) VALUES
(1, 1, 'admin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `weboms_user_tb`
--

CREATE TABLE `weboms_user_tb` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `accountType` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `weboms_user_tb`
--

INSERT INTO `weboms_user_tb` (`id`, `user_id`, `username`, `password`, `accountType`) VALUES
(1, 1, 'admin', '$2y$10$jB/TAUwZ6kpI0FPZgukzA.gNKVxwf5WhbRlHs3X5AVzX.Gx/XWqi6', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `weboms_company_tb`
--
ALTER TABLE `weboms_company_tb`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `weboms_feedback_tb`
--
ALTER TABLE `weboms_feedback_tb`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `weboms_menu_tb`
--
ALTER TABLE `weboms_menu_tb`
  ADD PRIMARY KEY (`orderType`);

--
-- Indexes for table `weboms_ordersdetail_tb`
--
ALTER TABLE `weboms_ordersdetail_tb`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `weboms_order_tb`
--
ALTER TABLE `weboms_order_tb`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `weboms_topup_tb`
--
ALTER TABLE `weboms_topup_tb`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `weboms_userinfo_tb`
--
ALTER TABLE `weboms_userinfo_tb`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `weboms_user_tb`
--
ALTER TABLE `weboms_user_tb`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `weboms_company_tb`
--
ALTER TABLE `weboms_company_tb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `weboms_feedback_tb`
--
ALTER TABLE `weboms_feedback_tb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `weboms_menu_tb`
--
ALTER TABLE `weboms_menu_tb`
  MODIFY `orderType` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `weboms_ordersdetail_tb`
--
ALTER TABLE `weboms_ordersdetail_tb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `weboms_order_tb`
--
ALTER TABLE `weboms_order_tb`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `weboms_topup_tb`
--
ALTER TABLE `weboms_topup_tb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `weboms_userinfo_tb`
--
ALTER TABLE `weboms_userinfo_tb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `weboms_user_tb`
--
ALTER TABLE `weboms_user_tb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
