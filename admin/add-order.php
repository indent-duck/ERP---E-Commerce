<?php
$conn = new mysqli("localhost", "root", "", "erp");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_id   = $_POST['customer_id'];
    $item_quantity = $_POST['item_quantity'];
    $total_amount  = $_POST['total_amount'];
    $status        = $_POST['status'];
    $payment       = $_POST['payment'];

    $conn->query("INSERT INTO orders (customer_id, item_quantity, total_amount, status, payment) 
                  VALUES ('$customer_id', '$item_quantity', '$total_amount', '$status', '$payment')");

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
<body>

<div class="container-fluid py-4">
  <div class="search-box">
    <h2 class="mb-3 text-center">Add New Order</h2>

    <form method="post" class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Customer ID</label>
        <input type="text" name="customer_id" class="form-control" required>
      </div>

      <div class="col-md-6">
        <label class="form-label">Item Quantity</label>
        <input type="number" name="item_quantity" class="form-control" required>
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
          <option value="Credit Card">Credit Card</option>
          <option value="Cash">Cash on Delivery</option>
        </select>
      </div>

      <div class="col-12 d-flex justify-content-end">
        <button type="submit" class="btn btn-primary">Save Order</button>
        <a href="order.php" class="btn btn-secondary ms-2">Back</a>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
