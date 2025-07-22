<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Order Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container-fluid">
    <div class="d-flex" style="min-height: 100vh;">
        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>

        <!-- Main Content -->
        <div class="col-md-10 py-4">
            <div class="d-flex justify-content-between align-items-center px-4 mb-3">
                <h3 class="mb-0">Order Management</h3>
                <button class="btn btn-dark"> <a href="add-order.php"> + Add Order </a></button>
            </div>

            <!-- Search & Filter Form -->
            <form method="GET" class="search-box p-3 bg-white border rounded shadow-sm d-flex gap-2 align-items-center justify-content-between">
                <?php
                  $currentStatus = isset($_GET['status']) ? $_GET['status'] : 'All';
                  $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
                ?>

                <!-- Search Input -->
                <input 
                    type="text" 
                    name="search" 
                    class="form-control" 
                    style="border-radius: 10px; max-width: 500px;" 
                    placeholder="Search Orders" 
                    value="<?= htmlspecialchars($searchTerm) ?>" 
                />

                <!-- Dropdown Button -->
                <div class="dropdown" style="width: 150px;">
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
            </form>

            <!-- Result Box -->
            <div class="result-box p-3 bg-white border rounded shadow-sm">
                <div class="table-responsive">
                    <table class="table align-top table-hover">
                    <thead>
                        <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Payment</th>
                        </tr>
                    </thead>
                    <tbody>
                      <?php
                        // Database connection
                        $conn = new mysqli("localhost", "root", "", "erp");

                        // Check connection
                        if ($conn->connect_error) {
                          die("Connection failed: " . $conn->connect_error);
                        }

                        // Get filters
                        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
                        $statusFilter = isset($_GET['status']) ? $_GET['status'] : '';

                        // Base query
                        $sql = "SELECT * FROM orders WHERE 1";
                        $params = [];
                        $types = "";

                        // Filter: Status
                        if (!empty($statusFilter) && $statusFilter !== "All") {
                            $sql .= " AND status = ?";
                            $params[] = $statusFilter;
                            $types .= "s";
                        }

                        // Filter: Search term (name, email, or order_id)
                        if (!empty($search)) {
                          if (is_numeric($search)) {
                              // Search by exact order_id OR like first/last/email
                              $sql .= " AND (
                                  order_id = ? OR 
                                  first_name LIKE ? OR 
                                  last_name LIKE ? OR 
                                  email LIKE ?
                              )";
                              $params[] = (int)$search;
                              $likeSearch = "%$search%";
                              $params[] = $likeSearch;
                              $params[] = $likeSearch;
                              $params[] = $likeSearch;
                              $types .= "isss";
                          } else {
                              // Just search name/email
                              $sql .= " AND (
                                  first_name LIKE ? OR 
                                  last_name LIKE ? OR 
                                  email LIKE ?
                              )";
                              $likeSearch = "%$search%";
                              $params[] = $likeSearch;
                              $params[] = $likeSearch;
                              $params[] = $likeSearch;
                              $types .= "sss";
                          }
                        }

                        $sql .= " ORDER BY order_id DESC";

                        // Prepare and bind
                        $stmt = $conn->prepare($sql);
                        if ($types && $stmt) {
                            $stmt->bind_param($types, ...$params);
                        }

                        $stmt->execute();
                        $result = $stmt->get_result();

                        // Output rows
                        if ($result->num_rows > 0) {
                          while ($row = $result->fetch_assoc()) {
                            $fullname = $row["first_name"] . " " . $row["last_name"];
                            $email = $row["email"];
                            $order_id = str_pad($row["order_id"], 5, "0", STR_PAD_LEFT);
                            $total = number_format($row["total_amount"], 2);
                            $date = date("Y-m-d", strtotime($row["date_created"]));
                            $status = $row["status"];
                            $payment = $row["payment"];

                            // Status badge styling
                            $badgeClass = match(strtolower($status)) {
                              "processing" => "bg-warning text-light",
                              "shipped"    => "bg-primary text-light",
                              "delivered"  => "bg-success text-light",
                              "cancelled"  => "bg-danger text-light",
                              default      => "bg-secondary text-light",
                            };

                            echo "
                            <tr>
                              <td>$order_id</td>
                              <td>$fullname <br><span style='font-size: 12px; color: #6c757d;'>$email</span></td>
                              <td>₱$total</td>
                              <td>$date</td>
                              <td><span class='badge $badgeClass'>$status</span></td>
                              <td>$payment</td>
                            </tr>";
                          }
                        } else {
                          echo "<tr><td colspan='6'>No orders found.</td></tr>";
                        }

                        $conn->close();
                      ?>
                      </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
