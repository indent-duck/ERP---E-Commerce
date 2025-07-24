CREATE TABLE `customers` (
   `customer_id` int(11) NOT NULL AUTO_INCREMENT,
   `first_name` varchar(45) NOT NULL,
   `last_name` varchar(45) NOT NULL,
   `address` varchar(45) NOT NULL,
   `e-mail` varchar(45) NOT NULL,
   `contact_no` bigint(10) NOT NULL,
   `total_orders` int(11) NOT NULL,
   `total_spent` decimal(2,0) NOT NULL,
   `date_created` datetime DEFAULT current_timestamp(),
   PRIMARY KEY (`customer_id`)
 ) ENGINE=InnoDB AUTO_INCREMENT=1000 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
