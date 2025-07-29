USE erp_db;


CREATE TABLE invoices (
    order_id VARCHAR(10) PRIMARY KEY,
    customer_id VARCHAR(10) NOT NULL,
    product_id VARCHAR(10) NOT NULL,
    quantity INT NOT NULL,
    discount DECIMAL(10 , 2 ) DEFAULT 0,
    total_payment DECIMAL(10 , 2 ) NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    date_placed DATE NOT NULL,
    date_completed DATE NOT NULL,
    status VARCHAR(50) NOT NULL
);

