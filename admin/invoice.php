<?php
$conn = new mysqli("localhost", "root", "", "erp_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$search = '';
if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
    $stmt = $conn->prepare("SELECT * FROM invoices WHERE customer_id LIKE CONCAT('%', ?, '%') OR order_id LIKE CONCAT('%', ?, '%') ORDER BY date_created DESC");
    $stmt->bind_param("ss", $search, $search);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT * FROM invoices ORDER BY date_placed DESC";
    $result = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoices</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="invoice-design.css">
</head>
<body>

    <!-- 🔍 Search Box -->
    <div class="search-box">
        <form method="GET" action="invoice.php" class="d-flex gap-2">
            <input type="text" name="search" class="form-control" placeholder="Search by Customer ID or Order ID" value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn btn-primary">Search</button>
            <a href="invoice.php" class="btn btn-secondary">Reset</a>
        </form>
    </div>

    <div class="result-box">
        <table class= "table table-bordered">
            <colgroup>
        <col style="width: 1.5%">
        <col style="width: 1%">
        <col style="width: 6%">
        <col style="width: 0.5%">
        <col style="width: 4%">
        <col style="width: 7%">
        <col style="width: 1%">

        
            </colgroup>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer ID</th>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Total Payment</th>
                    <th>Date Completed</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['order_id']) ?></td>
                            <td><?= htmlspecialchars($row['customer_id']) ?></td>
                            <td><?= htmlspecialchars($row['product_id']) ?></td>
                            <td><?= number_format($row['quantity']) ?></td>
                            <td><?= number_format($row['total_payment'], 2) ?></td>
                            <td><?= htmlspecialchars($row['date_completed']) ?></td>
                            <td><?= htmlspecialchars($row['status']) ?></td>
                            

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
