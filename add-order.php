<?php
$conn = new mysqli("localhost", "root", "", "erp");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name    = $_POST['first_name'];
    $last_name     = $_POST['last_name'];
    $email         = $_POST['email'];
    $total_amount  = $_POST['total_amount'];
    $status        = $_POST['status'];
    $payment       = $_POST['payment'];

    $conn->query("INSERT INTO orders (first_name, last_name, email, total_amount, status, payment) 
                  VALUES ('$first_name', '$last_name', '$email', '$total_amount', '$status', '$payment')");

    header("Location: order.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Add Order</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="order_style.css">
</head>
<body class="bg-light">

<div class="container-fluid">
  <div class="d-flex" style="min-height: 100vh;">
    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <div class="col-md-10 py-4">
      <div class="d-flex justify-content-between align-items-center px-4 mb-3">
        <h3 class="mb-0">Order Management</h3>
      </div>

      <div class="px-4">
        <div class="result-box p-4 bg-white border rounded shadow-sm">
          <h4 class="text-center mb-4">Add New Order</h4>

          <form method="post" class="row g-3">
            <div class="col-md-6">
              <label class="form-label">First Name</label>
              <input type="text" name="first_name" class="form-control" required>
            </div>

            <div class="col-md-6">
              <label class="form-label">Last Name</label>
              <input type="text" name="last_name" class="form-control" required>
            </div>

            <div class="col-md-12">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" required>
            </div>

            <div class="col-md-6">
              <label class="form-label">Total Amount</label>
              <input type="number" step="0.01" name="total_amount" class="form-control" required>
            </div>

            <div class="col-md-6">
              <label class="form-label">Status</label>
              <select name="status" class="form-select" required>
                <option value="Processing">Processing</option>
                <option value="Shipped">Shipped</option>
                <option value="Delivered">Delivered</option>
                <option value="Cancelled">Cancelled</option>
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label">Payment Method</label>
              <select name="payment" class="form-select" required>
                <option value="GCash">GCash</option>
                <option value="Bank Transfer">Credit Card</option>
                <option value="COD">Cash on Delivery</option>
              </select>
            </div>

            <div class="col-12 d-flex justify-content-end">
              <button type="submit" class="btn btn-primary">Save Order</button>
              <a href="order.php" class="btn btn-secondary ms-2">Back</a>
            </div>
          </form>
        </div>
      </div>

    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
