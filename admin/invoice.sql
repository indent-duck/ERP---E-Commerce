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
