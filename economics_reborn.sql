-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 07, 2024 at 07:48 AM
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
-- Database: `economics_reborn`
--

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customer_id` int(11) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `user_type` enum('admin','user') DEFAULT NULL,
  `image` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customer_id`, `username`, `first_name`, `last_name`, `email`, `password`, `user_type`, `image`) VALUES
(1, 'test', 'test', 'test', 'test@gmail.com', '098f6bcd4621d373cade4e832627b4f6', 'user', 'aiperson.png'),
(3, 'test', 'test', 'test', 'test2@gmail.com', '098f6bcd4621d373cade4e832627b4f6', 'user', 'coc.jpg'),
(4, 'admin', 'admin', 'admin', 'admin@gmail.com', '0192023a7bbd73250516f069df18b500', 'admin', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `orderdetails`
--

CREATE TABLE `orderdetails` (
  `orderdetail_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orderdetails`
--

INSERT INTO `orderdetails` (`orderdetail_id`, `order_id`, `start_time`, `end_time`, `price`, `description`) VALUES
(4, 5, '2024-05-07 07:15:47', '2024-05-07 07:27:10', 70000, 'test'),
(5, 6, '2024-05-07 12:15:00', '2024-05-07 12:15:00', NULL, 'test'),
(6, 7, '2024-05-07 07:45:18', '2024-05-07 07:45:40', 100, 'test');

-- --------------------------------------------------------

--
-- Table structure for table `ordermaster`
--

CREATE TABLE `ordermaster` (
  `order_id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `order_date` date DEFAULT NULL,
  `order_category` enum('pairing','meeting') DEFAULT NULL,
  `order_status` enum('completed','pending') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ordermaster`
--

INSERT INTO `ordermaster` (`order_id`, `customer_id`, `order_date`, `order_category`, `order_status`) VALUES
(5, 3, '2024-05-07', 'pairing', 'completed'),
(6, 3, '2024-05-07', 'meeting', 'completed'),
(7, 3, '2024-05-07', 'pairing', 'completed');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `payment_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `payment_method` varchar(100) DEFAULT NULL,
  `transaction_date` datetime DEFAULT NULL,
  `payment_status` enum('completed','pending') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`payment_id`, `order_id`, `payment_method`, `transaction_date`, `payment_status`) VALUES
(4, 5, NULL, NULL, 'completed'),
(5, 6, NULL, NULL, 'completed'),
(6, 7, NULL, NULL, 'completed');

-- --------------------------------------------------------

--
-- Table structure for table `topicissue`
--

CREATE TABLE `topicissue` (
  `topicissue_id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` varchar(2000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `topicissue`
--

INSERT INTO `topicissue` (`topicissue_id`, `customer_id`, `image`, `description`) VALUES
(1, 1, 'arcade-game-world-pixel-scene_24640-45908.jpg', 'itutuh restart dulu bang'),
(2, 3, '1689391542821-images_(22).jpeg', 'test');

-- --------------------------------------------------------

--
-- Table structure for table `topicissuedetails`
--

CREATE TABLE `topicissuedetails` (
  `topicissuedetails_id` int(11) NOT NULL,
  `topicissue_id` int(11) DEFAULT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `topicissuedetails`
--

INSERT INTO `topicissuedetails` (`topicissuedetails_id`, `topicissue_id`, `comment`, `image`, `customer_id`) VALUES
(1, 1, 'itutuh restart dulu bang', NULL, 1),
(2, 1, 'twte', NULL, 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `orderdetails`
--
ALTER TABLE `orderdetails`
  ADD PRIMARY KEY (`orderdetail_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `ordermaster`
--
ALTER TABLE `ordermaster`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `topicissue`
--
ALTER TABLE `topicissue`
  ADD PRIMARY KEY (`topicissue_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `topicissuedetails`
--
ALTER TABLE `topicissuedetails`
  ADD PRIMARY KEY (`topicissuedetails_id`),
  ADD KEY `topicissue_id` (`topicissue_id`),
  ADD KEY `fk_cst` (`customer_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orderdetails`
--
ALTER TABLE `orderdetails`
  MODIFY `orderdetail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `ordermaster`
--
ALTER TABLE `ordermaster`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `topicissue`
--
ALTER TABLE `topicissue`
  MODIFY `topicissue_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `topicissuedetails`
--
ALTER TABLE `topicissuedetails`
  MODIFY `topicissuedetails_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orderdetails`
--
ALTER TABLE `orderdetails`
  ADD CONSTRAINT `orderdetails_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `ordermaster` (`order_id`);

--
-- Constraints for table `ordermaster`
--
ALTER TABLE `ordermaster`
  ADD CONSTRAINT `ordermaster_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`);

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `ordermaster` (`order_id`);

--
-- Constraints for table `topicissue`
--
ALTER TABLE `topicissue`
  ADD CONSTRAINT `topicissue_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`);

--
-- Constraints for table `topicissuedetails`
--
ALTER TABLE `topicissuedetails`
  ADD CONSTRAINT `fk_cst` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`),
  ADD CONSTRAINT `topicissuedetails_ibfk_1` FOREIGN KEY (`topicissue_id`) REFERENCES `topicissue` (`topicissue_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
