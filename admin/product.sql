USE erp;

CREATE TABLE `products` (
    `product_id` INT(11) NOT NULL AUTO_INCREMENT,
    `product_name` VARCHAR(45) NOT NULL,
    `price` DECIMAL(10,2) NOT NULL,
    `stock` INT(11) NOT NULL,
    `category` VARCHAR(45) NOT NULL,
    `description` TEXT,
    `image1` VARCHAR(225),
    `image2` VARCHAR(225),
    `image3` VARCHAR(225),
    PRIMARY KEY (`Product ID`)
)ENGINE=InnoDB AUTO_INCREMENT=1000 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

SELECT * FROM `products`;

INSERT INTO `erp`.`products` (`product_id`, `product_name`, `price`, `stock`, `category`) VALUES ('1001', 'blk fresh lip oil serum', '350.00', '100', 'Makeup', 'Treat lips to a shot of soothing moisture with our super lightweight treatment oil that serves up serious shine with a juicy, natural tint—all without ever feeling sticky. Like silky lacquer for your lips, it smoothens with detoxifying ingredients and packs a hydrating punch with Vitamin E for an ultra-soft, plumped up finish. Pucker up and choose from six shades.', 'product1a.jpg', 'product1b.jpg', 'product1c.jpg');
INSERT INTO `erp`.`products` (`product_id`, `product_name`, `price`, `stock`, `category`) VALUES ('1002', 'Miracle Solution AHA BHA', '250.00', '100', 'Skincare', 'Formulated to reduce acne, bumps, blackheads and clogged pores while lifting away red discoloration and scarring left from blemishes. Perfect for all skin types, this non drying deep pore cleansing gel face wash is made with three gentle exfoliation agents: salicylic acid, lactic acid and glycolic acid - revealing younger looking skin helping you achieve clean and brighter skin.', 'product2a.jpg', 'product2b.jpg', 'product2c.jpg');
INSERT INTO `erp`.`products` (`product_id`, `product_name`, `price`, `stock`, `category`) VALUES ('1003', 'The Saem Concealer', '200.00', '0', 'Makeup', 'This is a worldwide best seller! A liquid type concealer that covers small flaws like dark under eyes & freckles. This best-selling Cover Perfection Tip Concealer protects the skin from harmful UV rays with SPF 28 PA++ and comes in 12 different colors to provide coverage for different skin tones', 'product3a.jpg', 'product3b.jpg', 'product3c.jpg');

--- inserted using add product --
INSERT INTO `erp`.`products` (`product_id`, `product_name`, `price`, `stock`, `category`) VALUES (1004, 'Azla Setting Spray', 155.00, 100, 'Makeup', 'Product Name: AZLA Makeup Setting Spray. Capacity: 115ML. Applicable people: everyone (especially dry skin types)...', 'product4a.jpg', 'product4b.jpg', 'product4c.jpg');