<?php
$conn = new mysqli("localhost", "root", "", "erp");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$search = '';
if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
    $stmt = $conn->prepare("SELECT * FROM orders WHERE customer_name LIKE CONCAT('%', ?, '%') OR customer_email LIKE CONCAT('%', ?, '%') ORDER BY order_date DESC");
    $stmt->bind_param("ss", $search, $search);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT * FROM orders ORDER BY order_date DESC";
    $result = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoices</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="invoice_design.css">
</head>
<body>

    <!-- 🔍 Search Box -->
    <div class="search-box">
        <form method="GET" action="invoice.php" class="d-flex gap-2">
            <input type="text" name="search" class="form-control" placeholder="Search by customer name or email..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn btn-primary">Search</button>
            <a href="invoice.php" class="btn btn-secondary">Reset</a>
        </form>
    </div>

    <div class="result-box">
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Email</th>
                    <th>Total (₱)</th>
                    <th>Payment</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['order_id']) ?></td>
                            <td><?= htmlspecialchars($row['customer_name']) ?></td>
                            <td><?= htmlspecialchars($row['customer_email']) ?></td>
                            <td><?= number_format($row['total'], 2) ?></td>
                            <td><?= htmlspecialchars($row['payment_method']) ?></td>
                            <td><?= htmlspecialchars($row['order_date']) ?></td>
                            <td>
                                <?php
                                    $status = strtolower(trim($row['status']));
                                    $badgeClass = match($status) {
                                        'delivered' => 'status-delivered',
                                        'shipped' => 'status-shipped',
                                        'processing' => 'status-processing',
                                        'cancelled' => 'status-cancelled',
                                        'pending' => 'status-pending',
                                        'refunded' => 'status-refunded',
                                        'completed' => 'status-completed',
                                        default => 'status-default'
                                    };
                                ?>
                                <span class="badge <?= $badgeClass ?>"><?= ucfirst($status) ?></span>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align: center;">No invoice records found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php $conn->close(); ?>
