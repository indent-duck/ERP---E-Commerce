use erp;
CREATE TABLE orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(100),
    customer_email VARCHAR(100),
    total DECIMAL(10, 2),
    payment_method VARCHAR(20),
    order_date DATE,
    status VARCHAR(20)
);

INSERT INTO orders (customer_name, customer_email, total, payment_method, order_date, status)
VALUES 
('John Doe', 'john@email.com', 15000.00, 'Credit Card', '2025-07-16', 'Completed'),
('Anna Smith', 'anna@email.com', 11000.00, 'Cash', '2025-07-14', 'Refunded'),
('Mike Winston', 'mike@email.com', 21000.00, 'Cash', '2025-07-12', 'Completed'),
('Emily Davis', 'emily@email.com', 0.00, 'Cash', '2025-07-11', 'Completed'),
('Name Surname', 'name@email.com', 200.00, 'Credit Card', '2025-07-11', 'Completed'),
('Name Surname', 'name@email.com', 1000.00, 'Cash', '2025-07-10', 'Refunded');
