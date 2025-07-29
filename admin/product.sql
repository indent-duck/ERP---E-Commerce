use erp_db;

CREATE TABLE `products` (
   `product_id` int(11) NOT NULL AUTO_INCREMENT,
   `product_name` varchar(45) NOT NULL,
   `cost_price` decimal(10,2) NOT NULL,
   `retail_price` decimal(10,2) NOT NULL,
   `specification` text NOT NULL,
   `category` text NOT NULL, 
   `quantity` int(11) NOT NULL,
   `image1` varchar(45),
   `image2` varchar(45),
   `image3` varchar(45),
   PRIMARY KEY (`product_id`)
 ) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
 
 SELECT * FROM `products`;
 
INSERT INTO `erp_db`.`products` (`product_id`, `product_name`, `cost_price`, `retail_price`, `specification`, `category`, `quantity`, `image1`, `image2`, `image3`) VALUES ('001', 'blk fresh lip oil serum','300.00', '350.00', 'Treat lips to a shot of soothing moisture with our super lightweight treatment oil that serves up serious shine with a juicy, natural tint—all without ever feeling sticky. Like silky lacquer for your lips, it smoothens with detoxifying ingredients and packs a hydrating punch with Vitamin E for an ultra-soft, plumped up finish. Pucker up and choose from six shades.', 'Makeup', '100', 'product1a.jpg', 'product1b.jpg', 'product1c.jpg');
INSERT INTO `erp_db`.`products` (`product_id`, `product_name`, `cost_price`, `retail_price`, `specification`, `category`, `quantity`, `image1`, `image2`, `image3`) VALUES ('1002', 'Miracle Solution AHA BHA','200,00',  '250.00', 'Skincare', 'Formulated to reduce acne, bumps, blackheads and clogged pores while lifting away red discoloration and scarring left from blemishes. Perfect for all skin types, this non drying deep pore cleansing gel face wash is made with three gentle exfoliation agents: salicylic acid, lactic acid and glycolic acid - revealing younger looking skin helping you achieve clean and brighter skin.','Skincare', '100', 'product2a.jpg', 'product2b.jpg', 'product2c.jpg');
