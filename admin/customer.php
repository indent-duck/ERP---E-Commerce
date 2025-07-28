<?php include 'db_connection.php';?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="customer.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <title>Customer Management</title>
    </head>
    <body>
        <div class="container-fluid">

            <!-- customer dashboard details -->
            <?php

            $total_customers = $conn->query("SELECT COUNT(*) as count FROM customers")->fetch_assoc()['count'];
            $new_customers = $conn->query("SELECT COUNT(*) as count FROM customers WHERE MONTH(date_created) = MONTH(CURRENT_DATE())")->fetch_assoc()['count'];
            $invoice_sum_total = $conn->query("SELECT SUM(total_payment) as total FROM invoices WHERE status != 'returned';")->fetch_assoc()['total'];
            $orders_sum_total = $conn->query("SELECT SUM(total_amount) as total FROM orders WHERE payment != 'COD';")->fetch_assoc()['total'];
            $avg_spent = 0;
            if (isset($invoice_sum) || isset($orders_sum_total)) {
                $avg_spent = ($invoice_sum_total + $orders_sum_total) / $total_customers;
            }
            ?>
            <div class="row">
                <div class="col-4">
                    <div class="card" style="border: 1px solid #dee2e6; border-radius: 10px;">
                        <h2 class="text-primary"><?= $total_customers ?></h2>
                        <div class="card-text">
                            <h6>Total Customers</h6>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card" style="border: 1px solid #dee2e6; border-radius: 10px;">
                        <h2 class="text-info"><?= $new_customers ?></h2>
                        <div class="card-text">
                            <h6>New this Month</h6>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card" style="border: 1px solid #dee2e6; border-radius: 10px;">
                        <h2 class="text-success">₱ <?= number_format($avg_spent, 2) ?></h2>
                        <div class="card-text">
                            <h6>Avg. value spent per Customer</h6>
                        </div>
                    </div>
                </div>
            </div>

            <div class="customers-table-container">

                <!-- search bar -->
                <?php

                if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['search'])) {
                    $customer_search = $_POST['search'];
                    $result = $conn->query("SELECT * FROM customers WHERE first_name LIKE '$customer_search%' OR last_name LIKE '$customer_search%'
                    ORDER BY
                    CASE WHEN first_name LIKE '$customer_search%' THEN 0
                    ELSE 1 END, first_name, last_name");
                } else {
                    $result = $conn->query("SELECT * FROM customers ORDER BY customer_id DESC");
                }

                ?>
                <div class="form-container" id="search-bar">
                    <form method="POST" class="row">
                        <label for="search-bar">Search Customer</label>
                        <div class="col-8">
                            <input type="text" class="form-control-sm" placeholder="customer name" id="search-bar" name="search">
                        </div>
                        <div class="col-2">
                            <input type="submit" value="search" class="btn btn-secondary w-100" id="top-button">
                        </div>
                        <div class="col-2">
                            <a href="customer.php" class="btn btn-success" id="top-button">reset</a>
                        </div>
                    </form>
                </div>

                <!-- table -->
                <div class="row">
                    <div class="col">
                        <div class="table-container">
                            <table class="table table-hover table-md text-center">
                                <colgroup>
                                    <col style="width: 12%">
                                    <col style="width: 21.32%">
                                    <col style="width: 16.66%">
                                    <col style="width: 16.66%">
                                    <col style="width: 16.66%">
                                    <col style="width: 16.66%">
                                </colgroup>
                                <thead table class="table-light table-bordered">
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Customer</th>
                                        <th scope="col">Active Orders</th>
                                        <th scope="col">Total Orders</th>
                                        <th scope="col">Total Spent</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <?php

                                if ($result && $result-> num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $customer_id = $row['customer_id'];
                                        $active_total = $conn->query("SELECT COUNT(*) as active_total FROM orders WHERE customer_id = $customer_id")->fetch_assoc()['active_total'];
                                        $invoice_total = $conn->query("SELECT COUNT(*) as invoice_total FROM invoices WHERE customer_id = $customer_id;")->fetch_assoc()['invoice_total'];
                                        $total_orders = $active_total + $invoice_total;
                                        $textColor = $active_total > 0 ? 'success' : '';
                                        
                                        $total_spent_orders = $conn->query("SELECT COALESCE(SUM(o.total_amount), 0) AS total FROM customers c
                                            LEFT JOIN orders o ON c.customer_id = o.customer_id
                                            AND o.payment != 'COD' WHERE c.customer_id = $customer_id;")->fetch_assoc()['total'];
                                        $total_spent_invoice = $conn->query("SELECT COALESCE(SUM(i.total_payment), 0) AS total FROM customers c
                                            LEFT JOIN invoices i ON c.customer_id = i.customer_id
                                            AND i.status != 'returned'
                                            WHERE c.customer_id = $customer_id;;")->fetch_assoc()['total'];
                                        $total_spent = $total_spent_orders + $total_spent_invoice;
                                ?>

                                <tbody>
                                    <tr>
                                        <th scope="col"><?= $customer_id ?></th>
                                        <td class="text-start"><?= htmlspecialchars($row['first_name']) ?> <?= htmlspecialchars($row['last_name']) ?></td>
                                        <td class="text-<?=$textColor?>"><?= $active_total ?></td>
                                        <td><?= $total_orders ?></td>
                                        <td class="text-start">₱ <?= number_format($total_spent, 2) ?></td>
                                        <td>
                                            <a href="customer_details.php?id=<?= $row['customer_id'] ?>" class="btn btn-primary btn-sm" id="detail-button">View Details</a>
                                        </td>
                                    </tr>
                                </tbody>
                                <?php }
                                } else { ?>
                                    <tbody>
                                        <tr>
                                            <td colspan="6" class="text-center">No results found</td>
                                        </tr>
                                    </tbody>
                                <?php } ?>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </body>
</html>