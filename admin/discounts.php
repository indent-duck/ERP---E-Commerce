<?php
include 'db_connection.php';

$search = '';
$limit = 4;
$show_all_sale = isset($_GET['view_all_sale']);
$show_all_normal = isset($_GET['view_all_normal']);

if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
    $stmt_sale = $conn->prepare("SELECT * FROM sale_discounts WHERE product_id LIKE CONCAT('%', ?) ORDER BY product_id ASC");
    $stmt_normal = $conn->prepare("SELECT * FROM normal_discounts WHERE product_id LIKE CONCAT('%', ?) ORDER BY product_id ASC");

    $stmt_sale->bind_param("s", $search);
    $stmt_normal->bind_param("s", $search);

    $stmt_sale->execute();
    $stmt_normal->execute();

    $result_sale = $stmt_sale->get_result();
    $result_normal = $stmt_normal->get_result();
} else {
    $sale_query = "SELECT * FROM sale_discounts ORDER BY discount DESC";
    $normal_query = "SELECT * FROM normal_discounts ORDER BY discount ASC";

    if (!$show_all_sale) {
        $sale_query .= " LIMIT $limit";
    }

    if (!$show_all_normal) {
        $normal_query .= " LIMIT $limit";
    }

    $result_sale = $conn->query($sale_query);
    $result_normal = $conn->query($normal_query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Discounts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="discounts_style.css">
</head>
<body>
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center my-3">
        
        <div class="d-flex gap-2">
            <button class="btn btn-dark fw-semibold px-4">Add Discount</button>
            <form method="GET">
                <input type="text" name="search" class="form-control search-bar" placeholder="Search Product ID">
            </form>
        </div>
    </div>

    <div class="section-title">Sale Discounts</div>
    <div class="custom-table">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Price</th>
                    <th>Discount (%)</th>
                    <th>Discounted Price</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result_sale->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['product_id']) ?></td>
                    <td>₱<?= number_format($row['price'], 2) ?></td>
                    <td><?= $row['discount'] ?>%</td>
                    <td>₱<?= number_format($row['price'] * (1 - $row['discount'] / 100), 2) ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php if (!$show_all_sale): ?>
        <div class="text-end mt-1 mb-4">
            <a href="?view_all_sale=1" class="view-more">View More</a>
        </div>
    <?php endif; ?>

    <div class="section-title">Normal Discounts</div>
    <div class="custom-table">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Price</th>
                    <th>Discount (%)</th>
                    <th>Discounted Price</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result_normal->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['product_id']) ?></td>
                    <td>₱<?= number_format($row['price'], 2) ?></td>
                    <td><?= $row['discount'] ?>%</td>
                    <td>₱<?= number_format($row['price'] * (1 - $row['discount'] / 100), 2) ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php if (!$show_all_normal): ?>
        <div class="text-end mt-1 mb-4">
            <a href="?view_all_normal=1" class="view-more">View More</a>
        </div>
    <?php endif; ?>
</div>
</body>
</html>

<?php $conn->close(); ?>
