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

SELECT * FROM orders;

INSERT INTO orders (customer_id, product_id, item_quantity, total_amount, date_created, status, payment, discount)
VALUES 
('1001', '1001', '15', 15000.00, '2025-07-16', 'Processing' 'Credit Card', 0.00),
('1002', '1001', '12', 12000.00, '2025-07-16', 'Shipped' 'Cash', 0.00),
('1003', '1002', '5', 1000.00, '2025-07-16', 'Delivered' 'GCash', 0.00);