<?php include 'db_connection.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Returns & Refunds</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="order_style.css">
</head>
<body style="background-color: white;">

<div class="flex-grow-1 m-2 py-2 px-2">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Return and Refund Requests</h2>
        <a href="add-return.php" class="btn btn-dark">+ Add Returned Order</a>
    </div>

    <!-- Search and Filter Form -->
    <form method="GET" class="search-box mt-4 border rounded shadow-sm d-flex gap-2 align-items-center justify-content-between">
        <?php
          $statusFilter = $_GET['status'] ?? 'All';
          $searchTerm = $_GET['search'] ?? '';
        ?>
        <input 
            type="text" 
            name="search" 
            class="form-control" 
            style="border-radius: 10px; max-width: 500px;" 
            placeholder="Search by Order ID" 
            value="<?= htmlspecialchars($searchTerm) ?>" 
        />

        <!-- Dropdown -->
        <div class="dropdown" style="width: 150px;">
            <button 
                class="btn btn-outline-secondary dropdown-toggle w-100" 
                style="border-radius: 10px;" 
                type="button" 
                data-bs-toggle="dropdown" 
                aria-expanded="false">
              <?= htmlspecialchars($statusFilter === 'All' ? 'All Status' : $statusFilter) ?>
            </button>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="return_refund.php?search=<?= urlencode($searchTerm) ?>">All</a></li>
              <li><a class="dropdown-item" href="return_refund.php?status=Pending&search=<?= urlencode($searchTerm) ?>">Pending</a></li>
              <li><a class="dropdown-item" href="return_refund.php?status=Approved&search=<?= urlencode($searchTerm) ?>">Approved</a></li>
              <li><a class="dropdown-item" href="return_refund.php?status=Rejected&search=<?= urlencode($searchTerm) ?>">Rejected</a></li>
            </ul>
        </div>
    </form>

    <!-- Result Table -->
    <div class="result-box p-3 bg-white border rounded shadow-sm mt-4">
        <div class="table-responsive">
            <table class="table align-top table-hover">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Date Requested</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $conn = new mysqli("localhost", "root", "", "erp_db");
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }

                        $sql = "SELECT * FROM return_and_refund WHERE 1";
                        $params = [];
                        $types = "";

                        if (!empty($statusFilter) && $statusFilter !== "All") {
                            $sql .= " AND status = ?";
                            $params[] = $statusFilter;
                            $types .= "s";
                        }

                        if (!empty($searchTerm)) {
                            if (is_numeric($searchTerm)) {
                                $sql .= " AND order_id = ?";
                                $params[] = (int)$searchTerm;
                                $types .= "i";
                            }
                        }

                        $sql .= " ORDER BY date_requested DESC";
                        $stmt = $conn->prepare($sql);
                        if ($types && $stmt) {
                            $stmt->bind_param($types, ...$params);
                        }

                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $orderId = str_pad($row["order_id"], 5, "0", STR_PAD_LEFT);
                                $date = date("Y-m-d H:i", strtotime($row["date_requested"]));
                                $status = htmlspecialchars($row["status"]);

                                $badgeClass = match(strtolower($status)) {
                                    "pending" => "bg-warning text-light",
                                    "approved" => "bg-success text-light",
                                    "rejected" => "bg-danger text-light",
                                    default => "bg-secondary text-light",
                                };

                                echo "
                                    <tr>
                                        <td>$orderId</td>
                                        <td>$date</td>
                                        <td><span class='badge $badgeClass'>$status</span></td>
                                    </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3'>No return or refund requests found.</td></tr>";
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
