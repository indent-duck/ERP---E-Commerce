CREATE TABLE `orders` (
   `order_id` int(11) NOT NULL AUTO_INCREMENT,
   `first_name` varchar(45) NOT NULL,
   `last_name` varchar(45) NOT NULL,
   `email` varchar(45) NOT NULL,
   `total_amount` decimal(10,2) NOT NULL,
   `date_created` datetime DEFAULT current_timestamp(),
   `status` varchar(45) NOT NULL,
   `payment` varchar(45) NOT NULL,
   PRIMARY KEY (`order_id`)
 ) ENGINE=InnoDB AUTO_INCREMENT=1000 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci

SELECT * FROM orders;