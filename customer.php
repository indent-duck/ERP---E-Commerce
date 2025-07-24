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
            $total_spent = $conn->query("SELECT SUM(total_spent) as total FROM customers")->fetch_assoc()['total'];
            $avg_spent = $total_spent / ($total_customers > 0 ? $total_customers : 1);

            ?>
            <div class="row">
                <div class="col-4">
                    <div class="card" style="border: 1px solid #dee2e6; border-radius: 10px;">
                        <h2 class="text-success"><?= $total_customers ?></h2>
                        <div class="card-text">
                            <h6>Total Customers</h6>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card" style="border: 1px solid #dee2e6; border-radius: 10px;">
                        <h2 class="text-primary"><?= $new_customers ?></h2>
                        <div class="card-text">
                            <h6>New this Month</h6>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card" style="border: 1px solid #dee2e6; border-radius: 10px;">
                        <h2 class="text-danger">₱<?= $avg_spent ?></h2>
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
                    $result = $conn->query("SELECT * FROM customers WHERE CONCAT(first_name, ' ', last_name) LIKE  '%$customer_search%'");
                } else {
                    $result = $conn->query("SELECT * FROM customers");
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
                            <table class="table table-hover">
                                <colgroup>
                                    <col style="width:15%">
                                    <col style="width:25%">
                                    <col style="width:25%">
                                    <col style="width:20%">
                                    <col style="width:15%">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Customer</th>
                                        <th scope="col">Total Orders</th>
                                        <th scope="col">Total Spent</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <?php

                                if ($result && $result-> num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                ?>

                                <tbody>
                                    <tr>
                                        <th scope="col"><?= htmlspecialchars($row['customer_id']) ?></th>
                                        <td><?= htmlspecialchars($row['first_name']) ?> <?= htmlspecialchars($row['last_name']) ?></td>
                                        <td><?= htmlspecialchars($row['total_orders']) ?></td>
                                        <td>₱<?= htmlspecialchars($row['total_spent']) ?></td>
                                        <td>
                                            <a href="customer_details.php?id=<?= $row['customer_id'] ?>" class="btn btn-primary btn-sm" id="detail-button">View Details</a>
                                        </td>
                                    </tr>
                                </tbody>
                                <?php }
                                } else { ?>
                                    <tbody>
                                        <tr>
                                            <td colspan="5" class="text-center">No results found</td>
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