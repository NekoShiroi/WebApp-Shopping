-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 01, 2024 at 11:55 PM
-- Server version: 8.3.0
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `suburban_outfitters`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;
CREATE TABLE IF NOT EXISTS `cart` (
  `cart_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cart_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `user_id`, `created_at`) VALUES
(1, 1, '2023-10-01 00:00:00'),
(2, 2, '2023-09-25 00:00:00'),
(3, 3, '2023-09-15 00:00:00'),
(4, 4, '2023-09-10 00:00:00'),
(5, 5, '2023-09-05 00:00:00'),
(6, 6, '2023-08-30 00:00:00'),
(7, 7, '2023-08-25 00:00:00'),
(8, 8, '2023-08-15 00:00:00'),
(9, 9, '2023-08-10 00:00:00'),
(10, 10, '2023-07-30 00:00:00'),
(11, 11, '2023-07-25 00:00:00'),
(12, 12, '2023-07-15 00:00:00'),
(13, 13, '2023-07-10 00:00:00'),
(14, 14, '2023-06-30 00:00:00'),
(15, 15, '2023-06-25 00:00:00'),
(16, 16, '2023-06-15 00:00:00'),
(17, 17, '2023-06-10 00:00:00'),
(18, 18, '2023-05-30 00:00:00'),
(19, 19, '2023-05-25 00:00:00'),
(20, 20, '2023-05-15 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `cart_item`
--

DROP TABLE IF EXISTS `cart_item`;
CREATE TABLE IF NOT EXISTS `cart_item` (
  `cart_item_id` int NOT NULL AUTO_INCREMENT,
  `cart_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `quantity` int NOT NULL,
  PRIMARY KEY (`cart_item_id`),
  KEY `cart_id` (`cart_id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `cart_item`
--

INSERT INTO `cart_item` (`cart_item_id`, `cart_id`, `product_id`, `quantity`) VALUES
(1, 1, 1, 2),
(2, 2, 3, 1),
(3, 3, 4, 3),
(4, 4, 2, 1),
(5, 5, 5, 2),
(6, 6, 6, 1),
(7, 7, 7, 4),
(8, 8, 8, 2),
(9, 9, 9, 1),
(10, 10, 10, 3),
(11, 11, 11, 1),
(12, 12, 12, 2),
(13, 13, 13, 4),
(14, 14, 14, 1),
(15, 15, 15, 2),
(16, 16, 16, 1),
(17, 17, 17, 3),
(18, 18, 18, 2),
(19, 19, 19, 1),
(20, 20, 20, 2);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `category_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  PRIMARY KEY (`category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `name`, `description`) VALUES
(1, 'Clothing', 'Fashionable clothing for men and women'),
(2, 'Footwear', 'Trendy shoes and sandals for all occasions'),
(3, 'Accessories', 'Stylish accessories to complement any outfit'),
(4, 'Outerwear', 'Coats, jackets, and more for every season'),
(5, 'Activewear', 'Comfortable and durable sportswear'),
(6, 'Formalwear', 'Elegant attire for formal events'),
(7, 'Casualwear', 'Relaxed and stylish casual clothing'),
(8, 'Beachwear', 'Chic swimwear and beach essentials'),
(9, 'Winterwear', 'Cozy and warm winter fashion'),
(10, 'Luxury', 'Premium and designer fashion items'),
(11, 'Jewelry', 'Beautiful necklaces, rings, and bracelets'),
(12, 'Handbags', 'Trendy handbags for every style'),
(13, 'Hats', 'Fashionable hats for all seasons'),
(14, 'Scarves', 'Warm and stylish scarves'),
(15, 'Sunglasses', 'Trendy sunglasses to protect your eyes'),
(16, 'Watches', 'Stylish wristwatches for every occasion'),
(17, 'Belts', 'Durable and fashionable belts'),
(18, 'Sportswear', 'Clothing and shoes for active lifestyles'),
(19, 'Kidswear', 'Fashion for kids and toddlers'),
(20, 'Vintage', 'Classic and retro fashion pieces');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

DROP TABLE IF EXISTS `customer`;
CREATE TABLE IF NOT EXISTS `customer` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `address` text,
  `phone_number` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `role` varchar(20) DEFAULT 'user',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`user_id`, `username`, `email`, `password`, `address`, `phone_number`, `created_at`, `role`) VALUES
(1, 'bsmith', 'random123@example.com', 'mysecret', '123 Elm St, City A', '9999', '2023-11-12 14:23:45', 'admin'),
(2, 'pjones', 'coder.quick@example.com', 'acrobat', '456 Pine Dr, City B', '124', '2023-06-23 08:15:33', 'customer'),
(3, 'happyGamer', 'happy.gamer@example.com', 'gamer2023!', '789 Oak Ln, City C', '3234567890', '2023-09-04 10:12:54', 'admin'),
(4, 'shopLover', 'shopper@example.com', '$2y$10$mUVOdYTcVugxiWUviuvwyeYtRaWuq1igx.KABFY4YpNCkX.zG4ymW', '101 Maple Rd, City A', '12345', '2023-01-15 19:45:12', 'customer'),
(5, 'catLover99', 'cat.lover99@example.com', 'meow1234', '222 Cedar St, City E', '5234567890', '2023-03-11 16:25:30', 'customer'),
(6, 'techGuru101', 'tech101@example.com', 'techP@ss1', '303 Walnut Blvd, City F', '6234567890', '2023-05-20 09:34:28', 'admin'),
(7, 'wanderlust', 'travel.example@example.com', 'Tr@velLust', '404 Birch Ln, City G', '7234567890', '2023-02-09 12:45:50', 'customer'),
(8, 'dreamBuilder', 'builder.dream@example.com', 'dream2023!', '555 Ash Ct, City H', '8234567890', '2023-07-14 17:29:11', 'admin'),
(9, 'codingNinja', 'ninja.code@example.com', 'N1njaC0de', '666 Cherry Cir, City I', '9234567890', '2023-08-30 14:11:05', 'admin'),
(10, 'brightSky', 'sky.bright@example.com', 'Br1ght@123', '777 Willow St, City J', '1234567891', '2023-10-21 22:07:14', 'customer'),
(11, 'greenThumb', 'garden.green@example.com', 'Gr33n@Thumb', '888 Maple Ridge, City K', '2234567891', '2023-04-25 18:45:29', 'admin'),
(12, 'fastRunner', 'runner.fast@example.com', 'RunFast2023', '999 Pine Valley, City L', '3234567891', '2023-11-01 13:55:00', 'customer'),
(13, 'bookWorm', 'worm.book@example.com', 'Read123!', '101 Spruce Dr, City M', '4234567891', '2023-06-10 11:22:36', 'customer'),
(14, 'artLover23', 'art23@example.com', 'ArtP@ss23', '202 Redwood Ln, City N', '5234567891', '2023-03-03 20:30:45', 'admin'),
(15, 'smartThinker', 'thinker.smart@example.com', 'Think123!', '303 Aspen Blvd, City O', '6234567891', '2023-05-05 08:50:20', 'customer'),
(16, 'sunnyDay99', 'sunny.day99@example.com', 'Sunny@2023', '404 Birch Cir, City P', '7234567891', '2023-09-29 09:10:50', 'admin'),
(17, 'mountainHigh', 'mountain.high@example.com', 'High@Mtn', '505 Elm Plaza, City Q', '8234567891', '2023-02-14 15:45:33', 'customer'),
(18, 'lazyCoder', 'coder.lazy@example.com', 'LazyC0de', '606 Oak Dr, City R', '9234567891', '2023-08-19 20:55:12', 'admin'),
(19, 'coffeeAddict', 'coffee.addict@example.com', 'Coff33!', '707 Cedar Way, City S', '1234567892', '2023-01-22 14:30:00', 'admin'),
(20, 'starGazer', 'stargazer@example.com', 'Stars123!', '808 Maple Ln, City T', '2234567892', '2023-10-05 11:45:37', 'customer'),
(22, 'Superhero', 'superhero@gmaill.com', '$2y$10$4sJi/7HgT.8QMZhUfZaKIuFYcHYcl6mnw9FXA7B2rBmhl5dLEjVyy', '1234', '1234', '2024-11-30 06:54:36', 'customer'),
(23, 'keyboard', 'keyboard@gmail.com', '$2y$10$/47AgeGiV4oRd2wZFv1txOxLWyBDZU6qyMeMoQ5MKodqwxTXx0Mfy', '1234', '1234', '2024-11-30 06:55:40', 'customer'),
(24, 'abc', 'abc@email.com', '$2y$10$69jRlnank0DJfvJq5tNpF.DhGKsb3WqZCyC6fZECN5PpdBDx9.ma6', '1234', '12356', '2024-11-30 22:58:35', 'customer'),
(25, 'Test', 'Test@gmail.com', '$2y$10$BD1yzaw/e1lNVR5mg4XfIOereTLYFee2XEiKiWzRJkQz371rmArfa', '1234', '1111', '2024-12-01 03:27:10', 'admin'),
(26, 'Myname', 'Test@email.com', '$2y$10$ooabCGk4WmwtwPwTu87c9.0HccrHmYynIdUlB8dCr2vHq.O3soxzK', '12345', '111', '2024-12-01 03:27:37', 'customer');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

DROP TABLE IF EXISTS `inventory`;
CREATE TABLE IF NOT EXISTS `inventory` (
  `inventory_id` int NOT NULL AUTO_INCREMENT,
  `product_id` int DEFAULT NULL,
  `location` text,
  `stock_quantity` int NOT NULL,
  `last_updated` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`inventory_id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`inventory_id`, `product_id`, `location`, `stock_quantity`, `last_updated`) VALUES
(1, 1, 'Warehouse A', 50, '2023-10-01 00:00:00'),
(2, 2, 'Warehouse B', 200, '2023-10-02 00:00:00'),
(3, 3, 'Warehouse C', 150, '2023-10-03 00:00:00'),
(4, 4, 'Warehouse A', 75, '2023-10-04 00:00:00'),
(5, 5, 'Warehouse B', 30, '2023-10-05 00:00:00'),
(6, 6, 'Warehouse C', 60, '2023-10-06 00:00:00'),
(7, 7, 'Warehouse A', 100, '2023-10-07 00:00:00'),
(8, 8, 'Warehouse B', 40, '2023-10-08 00:00:00'),
(9, 9, 'Warehouse C', 120, '2023-10-09 00:00:00'),
(10, 10, 'Warehouse A', 85, '2023-10-10 00:00:00'),
(11, 11, 'Warehouse B', 25, '2023-10-11 00:00:00'),
(12, 12, 'Warehouse C', 80, '2023-10-12 00:00:00'),
(13, 13, 'Warehouse A', 90, '2023-10-13 00:00:00'),
(14, 14, 'Warehouse B', 110, '2023-10-14 00:00:00'),
(15, 15, 'Warehouse C', 45, '2023-10-15 00:00:00'),
(16, 16, 'Warehouse A', 140, '2023-10-16 00:00:00'),
(17, 17, 'Warehouse B', 130, '2023-10-17 00:00:00'),
(18, 18, 'Warehouse C', 75, '2023-10-18 00:00:00'),
(19, 19, 'Warehouse A', 60, '2023-10-19 00:00:00'),
(20, 20, 'Warehouse B', 150, '2023-10-20 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

DROP TABLE IF EXISTS `order`;
CREATE TABLE IF NOT EXISTS `order` (
  `order_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `order_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(50) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `shipping_address` text,
  PRIMARY KEY (`order_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`order_id`, `user_id`, `order_date`, `status`, `total_amount`, `shipping_address`) VALUES
(1, 1, '2023-10-05 00:00:00', 'Pending', 129.99, '123 Vogue Ave, New York, NY'),
(2, 2, '2023-10-03 00:00:00', 'Shipped', 75.50, '456 Runway Blvd, Los Angeles, CA'),
(3, 3, '2023-10-02 00:00:00', 'Delivered', 95.00, '789 Chic St, Miami, FL'),
(4, 4, '2023-09-30 00:00:00', 'Cancelled', 55.50, '101 Glitter Rd, Chicago, IL'),
(5, 5, '2023-09-28 00:00:00', 'Pending', 145.00, '202 Style Ct, Austin, TX'),
(6, 6, '2023-09-27 00:00:00', 'Delivered', 89.99, '303 Runway Ln, Seattle, WA'),
(7, 7, '2023-09-25 00:00:00', 'Shipped', 45.50, '404 Chic Way, San Francisco, CA'),
(8, 8, '2023-09-20 00:00:00', 'Delivered', 129.99, '505 Vogue Dr, Boston, MA'),
(9, 9, '2023-09-15 00:00:00', 'Pending', 19.99, '606 Glitter Blvd, Denver, CO'),
(10, 10, '2023-09-10 00:00:00', 'Cancelled', 85.00, '707 Trendy St, Portland, OR'),
(11, 11, '2023-09-08 00:00:00', 'Shipped', 150.00, '808 Fashion Pl, Las Vegas, NV'),
(12, 12, '2023-09-05 00:00:00', 'Delivered', 95.00, '909 Vogue Cir, Houston, TX'),
(13, 13, '2023-09-03 00:00:00', 'Pending', 75.99, '1010 Chic Sq, Phoenix, AZ'),
(14, 14, '2023-08-30 00:00:00', 'Delivered', 25.99, '1111 Retro Ave, Atlanta, GA'),
(15, 15, '2023-08-28 00:00:00', 'Shipped', 35.50, '1212 Gold Rd, San Diego, CA'),
(16, 16, '2023-08-25 00:00:00', 'Delivered', 55.50, '1313 Style Ln, Dallas, TX'),
(17, 17, '2023-08-20 00:00:00', 'Pending', 89.99, '1414 Bold Blvd, Orlando, FL'),
(18, 18, '2023-08-15 00:00:00', 'Cancelled', 120.00, '1515 Glamour Dr, Raleigh, NC'),
(19, 19, '2023-08-10 00:00:00', 'Shipped', 65.00, '1616 Chic Rd, Detroit, MI'),
(20, 20, '2023-08-05 00:00:00', 'Delivered', 40.00, '1717 Trend Ct, Columbus, OH'),
(27, 21, '2024-11-30 06:22:23', 'Pending', 1723.85, '1234'),
(26, 21, '2024-11-30 06:21:50', 'Pending', 49.99, '1234'),
(34, 1, '2024-11-30 22:52:19', 'Pending', 20.00, '123 Elm St, City A'),
(36, 2, '2024-11-30 22:59:57', 'Pending', 19.99, '456 Pine Dr, City B'),
(45, 7, '2024-12-01 16:51:58', 'Pending', 129.99, 'www'),
(38, 2, '2024-11-30 23:23:49', 'Pending', 89.99, '456 Pine Dr, City B'),
(39, 2, '2024-11-30 23:25:43', 'Pending', 19.99, '456 Pine Dr, City B'),
(40, 2, '2024-11-30 23:25:59', 'Pending', 49.99, '456 Pine Dr, City B'),
(41, 2, '2024-11-30 23:26:52', 'Pending', 999.80, '456 Pine Dr, City B'),
(43, 2, '2024-11-30 23:34:04', 'Pending', 49.99, '456 Pine Dr, City B'),
(44, 2, '2024-11-30 23:35:01', 'Pending', 200.00, '456 Pine Dr, City B'),
(46, 2, '2024-12-01 16:52:23', 'Pending', 199.99, 'www');

-- --------------------------------------------------------

--
-- Table structure for table `order_item`
--

DROP TABLE IF EXISTS `order_item`;
CREATE TABLE IF NOT EXISTS `order_item` (
  `order_item_id` int NOT NULL AUTO_INCREMENT,
  `order_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`order_item_id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `order_item`
--

INSERT INTO `order_item` (`order_item_id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 1, 2, 129.99),
(2, 2, 3, 1, 25.99),
(3, 3, 4, 3, 89.99),
(4, 4, 2, 1, 49.50),
(5, 5, 5, 2, 145.00),
(6, 6, 6, 1, 75.50),
(7, 7, 7, 4, 19.99),
(8, 8, 8, 2, 85.00),
(9, 9, 9, 1, 65.00),
(10, 10, 10, 3, 95.00),
(11, 11, 11, 1, 150.00),
(12, 12, 12, 2, 55.50),
(13, 13, 13, 4, 30.00),
(14, 14, 14, 1, 75.99),
(15, 15, 15, 2, 120.00),
(16, 16, 16, 1, 35.50),
(17, 17, 17, 3, 65.00),
(18, 18, 18, 2, 40.00),
(19, 19, 19, 1, 95.00),
(20, 20, 20, 2, 25.00);

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

DROP TABLE IF EXISTS `payment`;
CREATE TABLE IF NOT EXISTS `payment` (
  `payment_id` int NOT NULL AUTO_INCREMENT,
  `order_id` int DEFAULT NULL,
  `payment_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `payment_method` varchar(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` varchar(50) NOT NULL,
  PRIMARY KEY (`payment_id`),
  KEY `order_id` (`order_id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`payment_id`, `order_id`, `payment_date`, `payment_method`, `amount`, `status`) VALUES
(1, 1, '2023-10-06 00:00:00', 'Credit Card', 129.99, 'Completed'),
(2, 2, '2023-10-04 00:00:00', 'PayPal', 75.50, 'Completed'),
(3, 3, '2023-10-03 00:00:00', 'Debit Card', 95.00, 'Completed'),
(4, 4, '2023-09-30 00:00:00', 'Credit Card', 55.50, 'Refunded'),
(5, 5, '2023-09-29 00:00:00', 'PayPal', 145.00, 'Completed'),
(6, 6, '2023-09-28 00:00:00', 'Debit Card', 89.99, 'Completed'),
(7, 7, '2023-09-26 00:00:00', 'Credit Card', 45.50, 'Completed'),
(8, 8, '2023-09-21 00:00:00', 'PayPal', 129.99, 'Completed'),
(9, 9, '2023-09-16 00:00:00', 'Debit Card', 19.99, 'Pending'),
(10, 10, '2023-09-11 00:00:00', 'Credit Card', 85.00, 'Refunded'),
(11, 11, '2023-09-09 00:00:00', 'PayPal', 150.00, 'Completed'),
(12, 12, '2023-09-06 00:00:00', 'Debit Card', 95.00, 'Completed'),
(13, 13, '2023-09-04 00:00:00', 'Credit Card', 75.99, 'Pending'),
(14, 14, '2023-08-31 00:00:00', 'PayPal', 25.99, 'Completed'),
(15, 15, '2023-08-29 00:00:00', 'Debit Card', 35.50, 'Completed'),
(16, 16, '2023-08-26 00:00:00', 'Credit Card', 55.50, 'Completed'),
(17, 17, '2023-08-21 00:00:00', 'PayPal', 89.99, 'Completed'),
(18, 18, '2023-08-16 00:00:00', 'Debit Card', 120.00, 'Refunded'),
(19, 19, '2023-08-11 00:00:00', 'Credit Card', 65.00, 'Completed'),
(20, 20, '2023-08-06 00:00:00', 'PayPal', 40.00, 'Completed');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
CREATE TABLE IF NOT EXISTS `product` (
  `product_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `stock_quantity` int NOT NULL,
  `category_id` int DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`product_id`),
  KEY `category_id` (`category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `name`, `description`, `price`, `stock_quantity`, `category_id`, `created_at`) VALUES
(1, 'Classic Denim Jacket', 'A timeless denim jacket suitable for all seasons.', 99.00, 999, 1, '2023-01-15 14:23:45'),
(2, 'Leather Tote Bag', 'A stylish and durable leather bag for daily use.', 129.99, 50, 2, '2023-02-10 11:30:00'),
(3, 'Running Shoes', 'Lightweight and comfortable running shoes.', 90.00, 2000, 3, '2023-03-05 16:15:20'),
(4, 'Graphic T-Shirt', 'Cotton t-shirt with a trendy graphic design.', 19.99, 300, 1, '2023-04-22 12:40:45'),
(5, 'Skinny Jeans', 'Slim-fit jeans with stretchable material.', 49.99, 20, 1, '2023-05-10 10:20:50'),
(6, 'Woolen Scarf', 'A warm and cozy scarf for chilly weather.', 24.99, 80, 4, '2023-06-01 14:50:15'),
(7, 'Sunglasses', 'UV-protected stylish sunglasses.', 39.99, 100, 5, '2023-07-14 09:30:40'),
(8, 'Sports Watch', 'Water-resistant sports watch with multiple features.', 69.99, 75, 6, '2023-08-21 18:00:25'),
(9, 'Designer Handbag', 'Luxury handbag with a sleek design.', 199.99, 30, 2, '2023-09-10 15:45:10'),
(10, 'Casual Sneakers', 'Comfortable and versatile casual sneakers.', 49.99, 250, 3, '2023-10-03 20:10:35'),
(11, 'Printed Maxi Dress', 'Flowy maxi dress with vibrant prints.', 39.99, 60, 7, '2023-11-07 17:20:05'),
(12, 'Knitted Cardigan', 'Soft and lightweight cardigan for layering.', 34.99, 90, 4, '2023-11-15 14:50:20'),
(13, 'Leather Belt', 'Durable leather belt with a classic buckle.', 19.99, 1200, 8, '2023-10-28 16:15:50'),
(14, 'Ankle Boots', 'Trendy ankle boots with comfortable soles.', 89.99, 70, 9, '2023-09-22 19:30:40'),
(15, 'Cotton Hoodie', 'Cozy hoodie with kangaroo pockets.', 29.99, 150, 1, '2023-08-15 10:00:50'),
(16, 'Slim Fit Blazer', 'A sharp blazer perfect for formal occasions.', 89.99, 40, 10, '2023-07-03 12:35:25'),
(17, 'Pleated Skirt', 'Elegant pleated skirt in soft pastel colors.', 29.99, 110, 7, '2023-06-19 14:10:30'),
(18, 'Wristlet Clutch', 'Compact wristlet clutch with multiple compartments.', 24.99, 50, 2, '2023-05-25 17:45:40'),
(19, 'Beanie Hat', 'Knitted beanie for casual winter wear.', 14.99, 140, 4, '2023-04-12 10:25:30'),
(20, 'Puffer Jacket', 'Water-resistant puffer jacket for cold days.', 79.99, 60, 11, '2023-03-29 13:55:10');

-- --------------------------------------------------------

--
-- Table structure for table `return`
--

DROP TABLE IF EXISTS `return`;
CREATE TABLE IF NOT EXISTS `return` (
  `return_id` int NOT NULL AUTO_INCREMENT,
  `order_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `return_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `reason` text,
  `status` varchar(50) NOT NULL,
  `refund_amount` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`return_id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `return`
--

INSERT INTO `return` (`return_id`, `order_id`, `product_id`, `return_date`, `reason`, `status`, `refund_amount`) VALUES
(1, 1, 1, '2023-10-15 00:00:00', 'Size too small', 'Processed', 129.99),
(2, 2, 2, '2023-10-10 00:00:00', 'Color not as expected', 'Refunded', 49.50),
(3, 3, 3, '2023-10-08 00:00:00', 'Product damaged', 'Refunded', 25.99),
(4, 4, 4, '2023-09-28 00:00:00', 'Changed mind', 'Processed', 89.99),
(5, 5, 5, '2023-09-25 00:00:00', 'Did not like the fit', 'Refunded', 145.00),
(6, 6, 6, '2023-09-20 00:00:00', 'Arrived late', 'Refunded', 75.50),
(7, 7, 7, '2023-09-15 00:00:00', 'Wrong item shipped', 'Processed', 19.99),
(8, 8, 8, '2023-09-10 00:00:00', 'Quality not satisfactory', 'Refunded', 85.00),
(9, 9, 9, '2023-09-05 00:00:00', 'Received duplicate order', 'Refunded', 65.00),
(10, 10, 10, '2023-08-30 00:00:00', 'Found cheaper alternative', 'Processed', 95.00),
(11, 11, 11, '2023-08-25 00:00:00', 'Did not match description', 'Refunded', 150.00),
(12, 12, 12, '2023-08-20 00:00:00', 'Material felt uncomfortable', 'Processed', 55.50),
(13, 13, 13, '2023-08-15 00:00:00', 'Wrong size shipped', 'Refunded', 30.00),
(14, 14, 14, '2023-08-10 00:00:00', 'Not needed anymore', 'Processed', 75.99),
(15, 15, 15, '2023-07-30 00:00:00', 'Wrong color shipped', 'Refunded', 120.00),
(16, 16, 16, '2023-07-25 00:00:00', 'Received damaged box', 'Processed', 35.50),
(17, 17, 17, '2023-07-20 00:00:00', 'Poor stitching quality', 'Refunded', 65.00),
(18, 18, 18, '2023-07-15 00:00:00', 'Product arrived broken', 'Refunded', 40.00),
(19, 19, 19, '2023-07-10 00:00:00', 'Fit was too loose', 'Processed', 95.00),
(20, 20, 20, '2023-07-05 00:00:00', 'Wrong product delivered', 'Refunded', 25.00);

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

DROP TABLE IF EXISTS `review`;
CREATE TABLE IF NOT EXISTS `review` (
  `review_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `rating` int NOT NULL,
  `comment` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`review_id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`)
) ;

--
-- Dumping data for table `review`
--

INSERT INTO `review` (`review_id`, `user_id`, `product_id`, `rating`, `comment`, `created_at`) VALUES
(1, 1, 1, 5, 'Love this leather jacket, fits perfectly!', '2023-10-05 00:00:00'),
(2, 2, 2, 4, 'Denim jeans are good but could be softer.', '2023-09-30 00:00:00'),
(3, 3, 3, 5, 'Graphic tee is trendy and comfy!', '2023-09-25 00:00:00'),
(4, 4, 4, 3, 'Sneakers are fine, but size ran small.', '2023-09-20 00:00:00'),
(5, 5, 5, 4, 'Trench coat looks classy, very happy!', '2023-09-15 00:00:00'),
(6, 6, 6, 5, 'Floral dress is beautiful and flowy.', '2023-09-10 00:00:00'),
(7, 7, 7, 5, 'Wool scarf is so cozy for winter.', '2023-09-05 00:00:00'),
(8, 8, 8, 4, 'Silk blouse is elegant, though pricey.', '2023-08-30 00:00:00'),
(9, 9, 9, 3, 'Cargo pants are functional, not stylish.', '2023-08-25 00:00:00'),
(10, 10, 10, 5, 'Chunky sneakers are super trendy!', '2023-08-20 00:00:00'),
(11, 11, 11, 5, 'Classic watch is minimalist and chic.', '2023-08-15 00:00:00'),
(12, 12, 12, 4, 'Ribbed sweater is comfy but delicate.', '2023-08-10 00:00:00'),
(13, 13, 13, 4, 'Wide brim hat is great for the sun.', '2023-08-05 00:00:00'),
(14, 14, 14, 5, 'Denim jacket is a wardrobe staple.', '2023-07-30 00:00:00'),
(15, 15, 15, 5, 'Leather boots are rugged and stylish.', '2023-07-25 00:00:00'),
(16, 16, 16, 3, 'Summer shorts are good but simple.', '2023-07-20 00:00:00'),
(17, 17, 17, 4, 'Graphic hoodie is comfortable.', '2023-07-15 00:00:00'),
(18, 18, 18, 5, 'Chiffon top is perfect for summer.', '2023-07-10 00:00:00'),
(19, 19, 19, 4, 'Ankle boots are stylish but tight.', '2023-07-05 00:00:00'),
(20, 20, 20, 5, 'Sunglasses are trendy and affordable.', '2023-07-01 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `shipping`
--

DROP TABLE IF EXISTS `shipping`;
CREATE TABLE IF NOT EXISTS `shipping` (
  `shipping_id` int NOT NULL AUTO_INCREMENT,
  `order_id` int DEFAULT NULL,
  `shipping_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `shipping_method` varchar(50) NOT NULL,
  `tracking_number` varchar(255) DEFAULT NULL,
  `status` varchar(50) NOT NULL,
  `estimated_delivery` datetime DEFAULT NULL,
  PRIMARY KEY (`shipping_id`),
  KEY `order_id` (`order_id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `shipping`
--

INSERT INTO `shipping` (`shipping_id`, `order_id`, `shipping_date`, `shipping_method`, `tracking_number`, `status`, `estimated_delivery`) VALUES
(1, 1, '2023-10-07 00:00:00', 'Standard', 'TRK123456789', 'In Transit', '2023-10-10 00:00:00'),
(2, 2, '2023-10-05 00:00:00', 'Express', 'TRK987654321', 'Delivered', '2023-10-06 00:00:00'),
(3, 3, '2023-10-04 00:00:00', 'Standard', 'TRK567891234', 'Delivered', '2023-10-08 00:00:00'),
(4, 4, '2023-09-30 00:00:00', 'Express', 'TRK432198765', 'Returned', '2023-10-01 00:00:00'),
(5, 5, '2023-09-29 00:00:00', 'Standard', 'TRK246813579', 'In Transit', '2023-10-03 00:00:00'),
(6, 6, '2023-09-28 00:00:00', 'Express', 'TRK135792468', 'Delivered', '2023-09-30 00:00:00'),
(7, 7, '2023-09-26 00:00:00', 'Standard', 'TRK975318642', 'Delivered', '2023-09-29 00:00:00'),
(8, 8, '2023-09-22 00:00:00', 'Express', 'TRK864213579', 'In Transit', '2023-09-25 00:00:00'),
(9, 9, '2023-09-17 00:00:00', 'Standard', 'TRK357921468', 'Pending', '2023-09-22 00:00:00'),
(10, 10, '2023-09-12 00:00:00', 'Express', 'TRK468132579', 'Returned', '2023-09-15 00:00:00'),
(11, 11, '2023-09-10 00:00:00', 'Standard', 'TRK159753486', 'Delivered', '2023-09-13 00:00:00'),
(12, 12, '2023-09-07 00:00:00', 'Express', 'TRK951753486', 'Delivered', '2023-09-09 00:00:00'),
(13, 13, '2023-09-05 00:00:00', 'Standard', 'TRK753159486', 'In Transit', '2023-09-08 00:00:00'),
(14, 14, '2023-09-01 00:00:00', 'Express', 'TRK357486159', 'Delivered', '2023-09-04 00:00:00'),
(15, 15, '2023-08-30 00:00:00', 'Standard', 'TRK753486159', 'In Transit', '2023-09-02 00:00:00'),
(16, 16, '2023-08-27 00:00:00', 'Express', 'TRK159486753', 'Delivered', '2023-08-29 00:00:00'),
(17, 17, '2023-08-22 00:00:00', 'Standard', 'TRK486753159', 'Delivered', '2023-08-25 00:00:00'),
(18, 18, '2023-08-17 00:00:00', 'Express', 'TRK132579864', 'Returned', '2023-08-20 00:00:00'),
(19, 19, '2023-08-12 00:00:00', 'Standard', 'TRK753951486', 'Delivered', '2023-08-15 00:00:00'),
(20, 20, '2023-08-07 00:00:00', 'Express', 'TRK951486753', 'Delivered', '2023-08-09 00:00:00');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
