<?php
$conn = new mysqli("localhost", "root", "", "erp_db");

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id     = $_POST['order_id'];
    $reason       = $_POST['reason'];
    $status       = $_POST['status'];

    // Check if the order_id exists in the orders table
    $check = $conn->query("SELECT * FROM orders WHERE order_id = '$order_id'");

    if ($check && $check->num_rows > 0) {
        // Proceed to insert into return_refund (or your returns table)
        $conn->query("INSERT INTO return_and_refund (order_id, reason, status)
                      VALUES ('$order_id', '$reason', '$status')");

        header("Location: returns_refund.php");
        exit();
    } else {
        $message = "❌ Order ID does not exist in the Orders table.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Add Returned Order</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container py-5">
  <div class="border rounded p-4 shadow">
    <h2 class="text-center mb-4">Add Returned Order</h2>

    <?php if (!empty($message)): ?>
      <div class="alert alert-danger"><?= $message ?></div>
    <?php endif; ?>

    <form method="post" class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Order ID</label>
        <input type="text" name="order_id" class="form-control" required>
      </div>

      <div class="col-md-6">
        <label class="form-label">Status</label>
        <select name="status" class="form-select" required>
          <option value="Pending">Pending</option>
          <option value="Approved">Approved</option>
          <option value="Rejected">Rejected</option>
          <option value="Returned">Returned</option>
        </select>
      </div>

      <div class="col-12">
        <label class="form-label">Reason for Return</label>
        <textarea name="reason" class="form-control" rows="3" required></textarea>
      </div>

      <div class="col-12 d-flex justify-content-end">
        <button type="submit" class="btn btn-success">Save Return</button>
        <a href="returns_refund.php" class="btn btn-secondary ms-2">Back</a>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
