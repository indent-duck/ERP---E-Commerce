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
                <a href="customer.php" class="btn btn-secondary mb-3" id="return-button">Back to Customers</a>
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
                        ?>
                        <table class="table">
                            <colgroup>
                                <col style="width: 30%;">
                                <col style="width: 70%;">
                            </colgroup>
                            <tbody>
                                <tr>
                                    <th scope="col">Customer ID:</th>
                                    <td><?php echo $post['customer_id']; ?></td>
                                </tr>
                                <tr>
                                    <th scope="col">First Name:</th>
                                    <td><?php echo $post['first_name']; ?></td>
                                </tr>
                                <tr>
                                    <th scope="col">Last Name:</th>
                                    <td><?php echo $post['last_name']; ?></td>
                                </tr>
                                <tr>
                                    <th scope="col">Address:</th>
                                    <td><?php echo $post['address']; ?></td>
                                </tr>
                                <tr>
                                    <th scope="col">E-mail:</th>
                                    <td><?php echo $post['e-mail']; ?></td>
                                </tr>
                                <tr>
                                    <th scope="col">Phone:</th>
                                    <td>+63 <?php echo $post['contact_no']; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-6">
                        <table class="table">
                            <colgroup>
                                <col style="width: 30%;">
                                <col style="width: 70%;">
                            </colgroup>
                            <tbody>
                                <tr>
                                    <th scope="col">Total Orders:</th>
                                    <td><?php echo $post['total_orders']; ?></td>
                                </tr>
                                <tr>
                                    <th scope="col">Total Amount Spent:</th>
                                    <td>₱<?php echo $post['total_spent']; ?></td>
                                </tr>
                                <tr>
                                    <th scope="col">Date Created:</th>
                                    <td><?php echo $post['date_created']; ?></td>
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
                            
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>