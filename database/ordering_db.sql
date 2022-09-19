-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 10, 2022 at 07:23 AM
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
-- Table structure for table `admin_tb`
--

CREATE TABLE `admin_tb` (
  `ID` int(11) NOT NULL,
  `admin` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin_tb`
--

INSERT INTO `admin_tb` (`ID`, `admin`, `password`) VALUES
(1, 'admin', '$2y$10$5R6eishHi3nmIgh8f13ErObE2YUIV/aWbVvE3CophTDF1GlrllDdG');

-- --------------------------------------------------------

--
-- Table structure for table `dishes_tb`
--

CREATE TABLE `dishes_tb` (
  `orderType` int(11) NOT NULL,
  `dish` varchar(255) DEFAULT NULL,
  `cost` int(11) DEFAULT NULL,
  `picName` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `dishes_tb`
--

INSERT INTO `dishes_tb` (`orderType`, `dish`, `cost`, `picName`) VALUES
(1, 'float', 45, '631c1b48cba780.97984343.png'),
(2, 'fries', 45, '631c1c818d82a3.51679666.png');

-- --------------------------------------------------------

--
-- Table structure for table `orderlist_tb`
--

CREATE TABLE `orderlist_tb` (
  `ID` int(11) NOT NULL,
  `proofOfPayment` varchar(255) DEFAULT NULL,
  `linkId` int(11) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `ordersLinkId` varchar(255) DEFAULT NULL,
  `totalAmount` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orderlist_tb`
--

INSERT INTO `orderlist_tb` (`ID`, `proofOfPayment`, `linkId`, `status`, `ordersLinkId`, `totalAmount`) VALUES
(1, '631c1b7d29cfa0.00933220.png', 1, 0, '631c1b7d29cf5', 90),
(3, '631c1f3cec3bb6.62285806.png', 1, 1, '631c1f3cec3b7', 135);

-- --------------------------------------------------------

--
-- Table structure for table `order_tb`
--

CREATE TABLE `order_tb` (
  `id` int(11) NOT NULL,
  `orderName` varchar(255) DEFAULT NULL,
  `ordersLinkId` varchar(255) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `orderType` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `order_tb`
--

INSERT INTO `order_tb` (`id`, `orderName`, `ordersLinkId`, `quantity`, `orderType`) VALUES
(1, 'float', '631c1b7d29cf5', 2, 1),
(3, 'float', '631c1f3cec3b7', 1, 1),
(4, 'fries', '631c1f3cec3b7', 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `user_tb`
--

CREATE TABLE `user_tb` (
  `linkId` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `otp` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_tb`
--

INSERT INTO `user_tb` (`linkId`, `username`, `name`, `email`, `otp`, `password`) VALUES
(1, 'kakashi', 'kakashi', 'custommemory072@gmail.com', '', '$2y$10$ck2CNbUG20NfipBi0wR0lOGiHU0ERHTtUgt6cP8hQOxQlOkCPGS86');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_tb`
--
ALTER TABLE `admin_tb`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `dishes_tb`
--
ALTER TABLE `dishes_tb`
  ADD PRIMARY KEY (`orderType`);

--
-- Indexes for table `orderlist_tb`
--
ALTER TABLE `orderlist_tb`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `order_tb`
--
ALTER TABLE `order_tb`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_tb`
--
ALTER TABLE `user_tb`
  ADD PRIMARY KEY (`linkId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_tb`
--
ALTER TABLE `admin_tb`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `dishes_tb`
--
ALTER TABLE `dishes_tb`
  MODIFY `orderType` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `orderlist_tb`
--
ALTER TABLE `orderlist_tb`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `order_tb`
--
ALTER TABLE `order_tb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_tb`
--
ALTER TABLE `user_tb`
  MODIFY `linkId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
