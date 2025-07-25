<?php include 'db_connection.php';

function textColor($percentage) {
    return $percentage > 0 ? 'positive' : 'negative';
}

function numberSign($percentage) {
    return $percentage > 0 ? "+" : "";
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Dashboard</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="dashboard.css">
    </head>
    <body>
        <div class="container-fluid">

            <!-- 1st card row -->
            <?php
            $total_revenue = $conn->query("SELECT SUM(a.total_payment) - SUM(a.quantity * b.cost_price) as total FROM invoices a
                INNER JOIN products b ON a.product_id = b.product_id;")->fetch_assoc()['total'];
            $revenue_percentage = $conn->query("SELECT 100 * ((SELECT SUM(p.cost_price * i.quantity) FROM invoices i
                INNER JOIN products p ON p.product_id = i.product_id
                WHERE MONTH(i.date_placed) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH))
                - (SELECT SUM(p.cost_price * i.quantity) FROM invoices i
                INNER JOIN products p ON p.product_id = i.product_id
                WHERE MONTH(i.date_placed) = MONTH(CURRENT_DATE())))
                / (SELECT SUM(p.cost_price * i.quantity) FROM invoices i
                INNER JOIN products p ON p.product_id = i.product_id
                WHERE MONTH(i.date_placed) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH)) AS percentage;")->fetch_assoc()['percentage'];
            ?>
            <div class="row">
                <div class="col-6">
                    <div class="card">
                        <h5 class="text-secondary">Total Revenue</h5>
                        <h2 class="text-dark">₱ <?= $total_revenue ?></h2>
                        <div class="card-text <?= textColor($revenue_percentage) ?>">
                            <caption class="card-caption"><?= numberSign($revenue_percentage).number_format($revenue_percentage, 0) ?>% than last month</caption>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card">
                        <h5 class="text-secondary">Active Orders</h5>
                        <h2 class="text-dark">10</h2>
                        <div class="card-text">
                            <caption class="card-caption">100</caption>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2nd card row -->
            <?php
            $total_customers = $conn->query("SELECT COUNT(customer_id) AS total_customers FROM customers;")->fetch_assoc()['total_customers'];
            $new_customers = $conn->query("SELECT COUNT(customer_id) AS new_customers FROM customers
                    WHERE MONTH(date_created) = MONTH(CURRENT_DATE());")->fetch_assoc()['new_customers'];
            ?>
            <div class="row">
                <div class="col-6">
                    <div class="card">
                        <h5 class="text-secondary">Customers</h5>
                        <h2 class="text-dark"><?= $total_customers ?></h2>
                        <div class="card-text"" style="color: #27AC49">
                            <caption class="card-caption"><?= $new_customers ?> new this month</caption>
                        </div>
                    </div>
                </div>

                <?php
                $products_sold = $conn->query("SELECT COUNT(*) AS total_sold FROM invoices")->fetch_assoc()['total_sold'];
                $products_percentage = $conn->query("SELECT 100 * ((SELECT COUNT(*) FROM invoices WHERE MONTH(date_placed) = MONTH(CURRENT_DATE()))
                    - (SELECT COUNT(*) FROM invoices WHERE MONTH(date_placed) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH)))
                    / (SELECT COUNT(*) FROM invoices WHERE MONTH(date_placed) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH)) as percentage;")->fetch_assoc()['percentage'];
                ?>
                <div class="col-6">
                    <div class="card">
                        <h5 class="text-secondary">Products Sold</h5>
                        <h2 class="text-dark"><?= $products_sold ?></h2>
                        <div class="card-text <?= textColor($products_percentage) ?>">
                            <caption class="card-caption"><?= numberSign($products_percentage).number_format($products_percentage, 0) ?>% than previous month</caption>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </body>
</html>