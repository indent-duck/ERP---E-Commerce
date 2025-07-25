CREATE DATABASE erp_db;

USE erp_db;

 CREATE TABLE `customers` (
   `customer_id` int(11) NOT NULL AUTO_INCREMENT,
   `first_name` varchar(30) NOT NULL,
   `last_name` varchar(20) NOT NULL,
   `address` varchar(45) NOT NULL,
   `e-mail` varchar(45) NOT NULL,
   `contact_no` bigint(10) NOT NULL,
   `credit_card_no` int(15) DEFAULT NULL,
   `date_created` datetime NOT NULL DEFAULT current_timestamp(),
   PRIMARY KEY (`customer_id`)
 ) ENGINE=InnoDB AUTO_INCREMENT=1000 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
 
 CREATE TABLE `discount` (
   `order_id` int(11) NOT NULL,
   `discount` decimal(2,2) NOT NULL,
   PRIMARY KEY (`order_id`)
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
 
 CREATE TABLE `invoices` (
   `order_id` int(11) NOT NULL,
   `customer_id` int(11) NOT NULL,
   `product_id` int(11) NOT NULL,
   `quantity` varchar(45) NOT NULL,
   `discount_applied` decimal(2,2) NOT NULL,
   `total_payment` decimal(10,2) NOT NULL DEFAULT 0.00,
   `status` varchar(15) NOT NULL,
   `date_placed` datetime NOT NULL,
   `date_completed` datetime NOT NULL DEFAULT current_timestamp(),
   PRIMARY KEY (`order_id`)
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
 
 CREATE TABLE `orders` (
   `order_id` int(11) NOT NULL AUTO_INCREMENT,
   `customer_id` varchar(45) NOT NULL,
   `product_id` varchar(45) NOT NULL,
   `item_quantity` int(11) NOT NULL,
   `total_amount` decimal(10,2) NOT NULL,
   `date_created` datetime DEFAULT current_timestamp(),
   `status` varchar(45) NOT NULL,
   `payment` varchar(45) NOT NULL,
   `discount` decimal(10,2) NOT NULL,
   PRIMARY KEY (`order_id`)
 ) ENGINE=InnoDB AUTO_INCREMENT=1000 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
 
 CREATE TABLE `products` (
   `product_id` int(11) NOT NULL AUTO_INCREMENT,
   `product_name` varchar(45) NOT NULL,
   `cost_price` decimal(10,2) NOT NULL,
   `retail_price` decimal(10,2) NOT NULL,
   `specification` text NOT NULL,
   `quantity` int(11) NOT NULL,
   `image1` varchar(45) DEFAULT NOT NULL,
   `image2` varchar(45) DEFAULT NOT NULL,
   `image3` varchar(45) DEFAULT NOT NULL,
   PRIMARY KEY (`product_id`)
 ) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
 
 CREATE TABLE `return_and_refund` (
   `order_id` int(11) NOT NULL,
   `date_requested` datetime DEFAULT current_timestamp(),
   PRIMARY KEY (`order_id`)
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
