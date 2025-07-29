 CREATE TABLE `invoices` (
    `order_id` INT(11) NOT NULL,
    `customer_id` INT(11) NOT NULL,
    `product_id` INT(11) NOT NULL,
    `quantity` VARCHAR(45) NOT NULL,
    `discount_applied` DECIMAL(5 , 2) NOT NULL,
    `total_payment` DECIMAL(10 , 2 ) NOT NULL DEFAULT 0.00,
    `status` VARCHAR(15) NOT NULL,
    `payment_method` VARCHAR(50) NOT NULL,
    `date_placed` DATETIME NOT NULL,
    `date_completed` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP (),
    PRIMARY KEY (`order_id`)
)  ENGINE=INNODB DEFAULT CHARSET=UTF8MB4 COLLATE = UTF8MB4_GENERAL_CI;
 
