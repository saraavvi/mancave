-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: May 15, 2021 at 07:32 AM
-- Server version: 5.7.32
-- PHP Version: 7.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `mancaveshop_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_swedish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`) VALUES
(1, 'LEGO'),
(2, 'Second'),
(3, 'Third');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(250) COLLATE utf8_swedish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Hobbies'),
(2, 'Books'),
(3, 'Interior Decoration'),
(4, 'Health & Beauty');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) COLLATE utf8_swedish_ci NOT NULL,
  `last_name` varchar(50) COLLATE utf8_swedish_ci NOT NULL,
  `email` varchar(50) COLLATE utf8_swedish_ci NOT NULL,
  `password` varchar(50) COLLATE utf8_swedish_ci NOT NULL,
  `address` varchar(150) COLLATE utf8_swedish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `order_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status_id` int(11) NOT NULL,
  `shipped_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_contents`
--

CREATE TABLE `order_contents` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL,
  `price_each` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(150) COLLATE utf8_swedish_ci NOT NULL,
  `price` int(10) UNSIGNED NOT NULL,
  `description` varchar(10000) COLLATE utf8_swedish_ci NOT NULL,
  `category_id` int(11) NOT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `stock` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `image` varchar(250) COLLATE utf8_swedish_ci NOT NULL,
  `specification` varchar(10000) COLLATE utf8_swedish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `description`, `category_id`, `brand_id`, `stock`, `image`, `specification`) VALUES
(1, 'Colosseum', 549, 'Nowhere on Earth compares to the majesty of the Colosseum of Rome. So, get ready to escape your everyday life as you take on the biggest ever LEGO® build (as at November 2020) yet. This epic 9,036-piece Colosseum model depicts each part of the famous structure in great detail. Authentic detailing shows the northern part of the outer wall’s facade and its iconic arches. The model features 3 stories, adorned with columns of the Doric, Ionic and Corinthian orders while the attic is decorated with Corinthian pilasters.', 1, 1, 20, 'https://www.pricerunner.se/product/640x640/3000782206/Lego-Creator-Colosseum-10276.jpg', 'Item: 10276,\r\n      Pieces: 9036,\r\n      Dimensions:\r\n      H: 11&quot; (27cm)\r\n      W: 21&quot; (52cm)\r\n      D: 24&quot; (59cm)'),
(2, 'NASA Space Shuttle Discovery', 199, 'Celebrate the wonders of space with this LEGO® NASA Space Shuttle Discovery (10283) model building set for adults. With 2,354 pieces, this engaging challenge lets you build the Space Shuttle Discovery, plus the Hubble Space Telescope, launched on NASA’s STS-31 mission in 1990.', 1, 1, 20, 'https://www.pricerunner.se/product/640x640/3001653943/Lego-NASA-Space-Shuttle-Discovery-10283.jpg', 'Item: 10283,\r\n      Pieces: 2354,\r\n      Dimensions:\r\n      H: 9&quot; (21cm)\r\n      W: 14&quot; (34cm)\r\n      D: 22&quot; (54cm)'),
(3, 'Old Trafford - Manchester United', 299, 'Manchester United fans will love building this big LEGO® set model of Old Trafford (10272) to show their allegiance to one of the world’s most famous football clubs. The building set for this LEGO football stadium provides a fun challenge to create a spectacular showpiece model.\r\n\r\n      The ultimate Manchester United gift\r\n      Manchester United fans can show off this Old Trafford replica at home or the office. New for February 2020, this LEGO® Creator sports set coincides with the stadium’s 110th anniversary. This building kit for adults features several evocative details including the players’ tunnel and the statue of the United Trinity.', 1, 1, 20, 'https://www.pricerunner.se/product/640x640/3000133656/Lego-Creator-Old-Trafford-Manchester-United-10272.jpg', 'Item: 10272,\r\n      Pieces: 3898,\r\n      Dimensions:\r\n      H: 8&quot; (19cm)\r\n      W: 16&quot; (39cm)\r\n      D: 19&quot; (47cm)'),
(4, 'Ghostbusters™ ECTO-1', 199, 'If you’re a Ghostbusters™ fan, we’ve got just the thing for you – the LEGO® Ghostbusters ECTO-1! Bust the stress out of everyday life and indulge in some quality me time as you build a LEGO version of the converted 1959 Cadillac Miller-Meteor ambulance from the Ghostbusters movies.\r\n\r\n      Spookily good details\r\n      Based on the Ghostbusters: Afterlife movie version, this ECTO-1 model features working steering, a trapdoor, ghost trap, an extending rear gunner seat, proton pack and cool details from the original car such as the iconic Ghostbusters logo.', 1, 1, 20, 'https://www.pricerunner.se/product/640x640/3000783873/Lego-Creator-Ghostbusters-ECTO-1-10274.jpg', 'Item: 10274,\r\n      Pieces: 2352,\r\n      Dimensions:\r\n      H: 10&quot; (23cm)\r\n      W: 7&quot; (17cm)\r\n      D: 19&quot; (47cm)');

-- --------------------------------------------------------

--
-- Table structure for table `statuses`
--

CREATE TABLE `statuses` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_swedish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `order_contents`
--
ALTER TABLE `order_contents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `brand_id` (`brand_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `statuses`
--
ALTER TABLE `statuses`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_contents`
--
ALTER TABLE `order_contents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `statuses`
--
ALTER TABLE `statuses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`);

--
-- Constraints for table `order_contents`
--
ALTER TABLE `order_contents`
  ADD CONSTRAINT `order_contents_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_contents_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`),
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
