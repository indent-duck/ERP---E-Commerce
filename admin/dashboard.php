<?php include 'db_connection.php';

function textColor($percentage) {
    return $percentage > 0 ? 'positive' : 'negative';
}

function getPercentage($prev_month, $current_month) {
    if ($prev_month > 0) {
        $difference = 100 * (($current_month - $prev_month) / $prev_month);
        $difference = number_format($difference, 2);
        if ($difference >= 0) {
            return "+$difference";
        } elseif ($difference < 0) {
            return "$difference";
        }
    } else {
        return "0";
    }
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
            <div class="row">
                <?php
                $total_customers = $conn->query("SELECT COUNT(customer_id) AS total_customers FROM customers;")->fetch_assoc()['total_customers'];
                $new_customers = $conn->query("SELECT COUNT(customer_id) AS new_customers FROM customers
                WHERE MONTH(date_created) = MONTH(CURRENT_DATE());")->fetch_assoc()['new_customers'];
                ?>
                <div class="col-6">
                    <div class="card" style="border: 1px solid #dee2e6; border-radius: 10px;">
                        <h5 class="text-secondary">Total Revenue</h5>
                        <h2 class="text-dark">₱ 10</h2>
                        <div class="card-text">
                            <caption class="card-caption">100</caption>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card" style="border: 1px solid #dee2e6; border-radius: 10px;">
                        <h5 class="text-secondary">Active Orders</h5>
                        <h2 class="text-dark">10</h2>
                        <div class="card-text">
                            <caption class="card-caption">100</caption>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2nd card row -->
            <div class="row">
                <div class="col-6">
                    <div class="card" style="border: 1px solid #dee2e6; border-radius: 10px;">
                        <h5 class="text-secondary">Customers</h5>
                        <h2 class="text-dark"><?= $total_customers ?></h2>
                        <div class="card-text">
                            <caption class="card-caption"><?= $new_customers ?> new this month</caption>
                        </div>
                    </div>
                </div>

                <?php
                $products_sold = $conn->query("SELECT COUNT(*) AS total_sold FROM invoices")->fetch_assoc()['total_sold'];
                $current_month_sales = $conn->query("SELECT COUNT(*) AS current_sales FROM invoices WHERE month(date_placed) = MONTH(CURRENT_DATE());")->fetch_assoc()['current_sales'];
                $prev_month_sales =  $conn->query("SELECT COUNT(*) AS prev_sales FROM invoices WHERE month(date_placed) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH);")->fetch_assoc()['prev_sales'];
                $percentage = getPercentage($prev_month_sales, $current_month_sales);
                ?>
                <div class="col-6">
                    <div class="card" style="border: 1px solid #dee2e6; border-radius: 10px;">
                        <h5 class="text-secondary">Products Sold</h5>
                        <h2 class="text-dark"><?= $products_sold ?></h2>
                        <div class="card-text <?= textColor($percentage) ?>">
                            <caption class="card-caption"><?= $percentage ?>% than previous month</caption>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </body>
</html>