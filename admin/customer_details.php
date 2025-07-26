<?php include 'db_connection.php';?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="customer_details.css">
        <title>Customer Management</title>
    </head>
    <body>
        <div class="container-fluid">

            <div class="return-container">
                <a href="customer.php" class="btn btn-success mb-3" id="return-button">Back to Customers</a>
            </div>

            <div class="details-container">
                <h3>Account Details</h3>
                <div class="row">

                    <div class="col-6">
                    
                    <!-- customer details table -->
                        <?php
                        $customer_id = $_GET['id'];
                        $result = $conn->query("SELECT * FROM customers WHERE customer_id = '$customer_id'");
                        $post = $result->fetch_assoc();
                        $credit_card = $post['credit_card_no'];
                        if (!isset($credit_card)) {
                            $credit_card = "--";
                        }
                        ?>
                        <table class="table table-sm">
                            <colgroup>
                                <col style="width: 30%;">
                                <col style="width: 70%;">
                            </colgroup>
                            <tbody>
                                <tr>
                                    <th scope="col">Customer ID:</th>
                                    <td><?= $post['customer_id']; ?></td>
                                </tr>
                                <tr>
                                    <th scope="col">First Name:</th>
                                    <td><?= $post['first_name']; ?></td>
                                </tr>
                                <tr>
                                    <th scope="col">Last Name:</th>
                                    <td><?= $post['last_name']; ?></td>
                                </tr>
                                <tr>
                                    <th scope="col">Address:</th>
                                    <td><?= $post['address']; ?></td>
                                </tr>
                                <tr>
                                    <th scope="col">E-mail:</th>
                                    <td><?= $post['e-mail']; ?></td>
                                </tr>
                                <tr>
                                    <th scope="col">Phone:</th>
                                    <td>+63 <?= $post['contact_no']; ?></td>
                                </tr>
                                <tr>
                                    <th scope="col">Credit Card:</th>
                                    <td><?= $credit_card; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <?php
                    $invoice_orders = $conn->query("SELECT COALESCE(COUNT(i.customer_id), 0) as total FROM customers c 
                        INNER JOIN invoices i ON i.customer_id = c.customer_id
                        WHERE c.customer_id = $customer_id;")->fetch_assoc()['total'];
                    $order_orders = $conn->query("SELECT COALESCE(COUNT(o.customer_id), 0) as total FROM customers c 
                        INNER JOIN orders o ON o.customer_id = c.customer_id
                        WHERE c.customer_id = $customer_id;")->fetch_assoc()['total'];
                    $total_orders = $invoice_orders + $order_orders;
                    $invoice_sum_total = $conn->query("SELECT SUM(total_payment) as total FROM invoices WHERE status != 'returned' AND customer_id = $customer_id;")->fetch_assoc()['total'];
                    $orders_sum_total = $conn->query("SELECT SUM(total_amount) as total FROM orders WHERE payment != 'COD' AND customer_id = $customer_id;")->fetch_assoc()['total'];
                    $total_spent = $invoice_sum_total + $orders_sum_total;
                    ?>
                    <div class="col-6">
                        <table class="table table-sm">
                            <colgroup>
                                <col style="width: 35%;">
                                <col style="width: 65%;">
                            </colgroup>
                            <tbody>
                                <tr>
                                    <th scope="col">Total Orders:</th>
                                    <td><?= $total_orders ?></td>
                                </tr>
                                <tr>
                                    <th scope="col">Total Amount Spent:</th>
                                    <td>₱ <?= number_format($total_spent, 2) ?></td>
                                </tr>
                                <tr>
                                    <th scope="col">Date Created:</th>
                                    <td><?= $post['date_created']; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-container">
                        <h5><span class="badge bg-secondary">Order History</span></h5>
                        <?php
                        $order_history = $conn->query(
                        "SELECT
                            o.order_id as order_id,
                            p.product_name as item,
                            o.item_quantity as qty,
                            p.retail_price as item_price,
                            o.discount as discount,
                            o.total_amount as sum_total,
                            o.date_created as date_placed,
                            'processing' as date_completed,
                            o.status as status
                        FROM orders o
                        INNER JOIN products p ON o.product_id = p.product_id
                        WHERE o.customer_id = $customer_id
                        UNION
                        SELECT
                            i.order_id,
                            p.product_name,
                            i.quantity,
                            p.retail_price,
                            i.discount_applied,
                            i.total_payment,
                            i.date_placed,
                            i.date_completed,
                            i.status
                        FROM invoices i
                        INNER JOIN products p ON p.product_id = i.product_id
                        WHERE i.customer_id = $customer_id ORDER BY order_id DESC;");

                        ?>
                        <table class="table table-hover">
                            <colgroup>
                                <col style="width: 8%">
                                <col style="width: 17%">
                                <col style="width: 13.5%">
                                <col style="width: 13.5%">
                                <col style="width: 16.75%">
                                <col style="width: 16.75%">
                                <col style="width: 10%">
                                <col style="width: 2.5%">
                            </colgroup>
                            <thead class="table-light">
                                <th>Order Id</th>
                                <th>Item</th>
                                <th>Amount</th>
                                <th>Total Amount</th>
                                <th>Date Placed</th>
                                <th>Date Received</th>
                                <th>Status</th>
                                <th></th>
                            </thead>
                            
                            <?php
                                if (mysqli_num_rows($order_history) > 0) {
                                    while ($invoice = $order_history->fetch_assoc()) {
                                        $status_color = "";
                                        if (isset($invoice['status'])) {
                                            switch ($invoice['status']) {
                                                case "processing":
                                                    $status_color = 'bg-warning';
                                                    break;
                                                case "shipped":
                                                    $status_color = 'bg-primary';
                                                    break;
                                                case "completed":
                                                    $status_color = 'bg-success';
                                                    break;
                                                case "returned":
                                                    $status_color = 'bg-danger';
                                                    break;
                                                case "refunded":
                                                    $status_color = 'bg-danger';
                                                    break;
                                            }
                                        }
                            ?>

                            <tbody>
                                <tr>
                                    <th><?= $invoice['order_id'] ?></th>
                                    <td><?= $invoice['item'] ?> <label class="text-secondary" style="font-size: 14px;">x<?= $invoice['qty']?></label></td>
                                    <td>₱ <?= $invoice['item_price'] ?></td>
                                    <td>₱ <?= $invoice['sum_total'] ?></td>
                                    <td><?= $invoice['date_placed'] ?></td>
                                    <td><?= $invoice['date_completed'] ?></td>
                                    <td> <span class="badge <?= $status_color?>"> <?= $invoice['status']; ?></span></td>
                                    <td class="text-secondary">▼</td>
                                </tr>
                            </tbody>

                            <?php }
                            } else {
                            ?>
                            <tbody>
                                <tr>
                                    <td colspan="8" class="text-center">No orders yet</td>
                                </tr>
                            </tbody>
                            <?php } ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>