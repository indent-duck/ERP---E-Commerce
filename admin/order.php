<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Orders</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="order_style.css">
</head>
<body style="background-color: white;">

<div class="flex-grow-1 m-2 py-2 px-2">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">   </h2>
        <a href="add-order.php" class="btn btn-dark">+ Add Order</a>
    </div>

    <!-- Search and Filter Form -->
    <form method="GET" class="search-box mt-4 border rounded shadow-sm p-3 d-flex flex-wrap align-items-center gap-2 justify-content-start"
      style="max-width: 100%;">
        <?php
            $currentStatus = $_GET['status'] ?? 'All';
            $searchTerm = $_GET['search'] ?? '';
        ?>
        <input 
            type="text" 
            name="search" 
            class="form-control" 
            style="border-radius: 10px; max-width: 500px;" 
            placeholder="Search by Order ID or Customer ID or Product ID" 
            value="<?= htmlspecialchars($searchTerm) ?>" 
        />

        <!-- Dropdown -->
        <div class="dropdown ms-4" style="width: 130px;">
            <button 
                class="btn btn-outline-secondary dropdown-toggle w-100" 
                style="border-radius: 10px;" 
                type="button" 
                data-bs-toggle="dropdown" 
                aria-expanded="false">
            <?= htmlspecialchars($currentStatus === 'All' ? 'All Status' : $currentStatus) ?>
            </button>
            <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="order.php?search=<?= urlencode($searchTerm) ?>">All</a></li>
            <li><a class="dropdown-item" href="order.php?status=Processing&search=<?= urlencode($searchTerm) ?>">Processing</a></li>
            <li><a class="dropdown-item" href="order.php?status=Shipped&search=<?= urlencode($searchTerm) ?>">Shipped</a></li>
            <li><a class="dropdown-item" href="order.php?status=Delivered&search=<?= urlencode($searchTerm) ?>">Delivered</a></li>
            <li><a class="dropdown-item" href="order.php?status=Cancelled&search=<?= urlencode($searchTerm) ?>">Cancelled</a></li>
            </ul>
        </div>

        <!-- Search Button (Fixed Width) -->
        <div style="width: 130px;">
            <button type="submit" class="btn btn-outline-dark w-100">Search</button>
        </div>

        <!-- Clear Button (Fixed Width) -->
        <?php if (!empty($searchTerm) || ($currentStatus !== 'All')): ?>
            <div style="width: 130px;">
                <a href="order.php" class="btn btn-outline-danger w-100">Clear</a>
            </div>
        <?php endif; ?>
    </form>

    <!-- Result Table -->
    <div class="result-box p-3 bg-white border rounded shadow-sm mt-4">
        <div class="table-responsive">
            <table class="table align-top table-hover">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer ID</th>
                        <th>Product ID</th>
                        <th>Item Qty</th>
                        <th>Total</th>
                        <th>Discount</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Payment</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $conn = new mysqli("localhost", "root", "", "erp_db");
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }

                        $search = trim($_GET['search'] ?? '');
                        $statusFilter = $_GET['status'] ?? '';

                        $sql = "SELECT * FROM orders WHERE 1";
                        $params = [];
                        $types = "";

                        if (!empty($statusFilter) && $statusFilter !== "All") {
                            $sql .= " AND status = ?";
                            $params[] = $statusFilter;
                            $types .= "s";
                        }

                        if (!empty($search)) {
                            if (is_numeric($search)) {
                                $sql .= " AND (order_id = ? OR customer_id LIKE ? OR product_id LIKE ?)";
                                $params[] = (int)$search;
                                $like = "%" . $search . "%";
                                $params[] = $like;
                                $params[] = $like;
                                $types .= "iss";
                            } else {
                                $sql .= " AND customer_id LIKE ? OR product_id LIKE ?";
                                $like = "%" . $search . "%";
                                $params[] = $like;
                                $params[] = $like;
                                $types .= "ss";
                            }
                        }

                        $sql .= " ORDER BY order_id DESC";
                        $stmt = $conn->prepare($sql);
                        if ($types && $stmt) {
                            $stmt->bind_param($types, ...$params);
                        }

                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $orderId = str_pad($row["order_id"], 5, "0", STR_PAD_LEFT);
                                $customerId = htmlspecialchars($row["customer_id"]);
                                $productId = htmlspecialchars($row["product_id"]);
                                $quantity = $row["item_quantity"];
                                $total = "₱" . number_format($row["total_amount"], 2);
                                $discount = "₱" . number_format($row["discount"], 2);
                                $date = date("Y-m-d", strtotime($row["date_created"]));
                                $status = $row["status"];
                                $payment = $row["payment"];

                                $badgeClass = match(strtolower($status)) {
                                    "processing" => "bg-warning text-light",
                                    "shipped"    => "bg-primary text-light",
                                    "delivered"  => "bg-success text-light",
                                    "cancelled"  => "bg-danger text-light",
                                    default      => "bg-secondary text-light",
                                };

                                echo "
                                <tr>
                                    <td>$orderId</td>
                                    <td>$customerId</td>
                                    <td>$productId</td>
                                    <td>$quantity</td>
                                    <td>$total</td>
                                    <td>$discount</td>
                                    <td>$date</td>
                                    <td><span class='badge $badgeClass'>$status</span></td>
                                    <td>$payment</td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7'>No orders found.</td></tr>";
                        }

                        $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
