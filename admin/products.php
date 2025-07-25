<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Product Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="product_style.css">
</head>
<body>

<div class="container-fluid">
  <div class="d-flex full-height">
  
    <div class="right-col">
      <div class="flex-grow-1 m-2 py-2 px-2">
          <div class="d-flex justify-content-between align-items-center mb-3">
              <h2 class="mb-0">Product Management</h2>
              <a href="add-products.php" class="btn btn-dark">+ Add Product</a>
          </div>

      <form method="GET" class="search-box p-3 bg-white border rounded shadow-sm d-flex gap-2 align-items-center justify-content-between">
        <?php
          $currentStatus = isset($_GET['status']) ? $_GET['status'] : 'All';
          $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
        ?>

        <input 
          type="text" 
          name="search" 
          class="form-control search-input" 
          placeholder="Search Product" 
          value="<?= htmlspecialchars($searchTerm) ?>" 
        />

        <div class="dropdown filter-dropdown">
          <button 
            class="btn btn-outline-secondary dropdown-toggle w-100" 
            type="button" 
            data-bs-toggle="dropdown" 
            aria-expanded="false">
            <?= htmlspecialchars($currentStatus === 'All' ? 'Filter' : $currentStatus) ?>
          </button>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="?status=All&search=<?= urlencode($searchTerm) ?>">All</a></li>
            <li><a class="dropdown-item" href="?status=Available&search=<?= urlencode($searchTerm) ?>">Available</a></li>
            <li><a class="dropdown-item" href="?status=Out of Stock&search=<?= urlencode($searchTerm) ?>">Out of Stock</a></li>
          </ul>
        </div>
      </form>

      <div class="result-box p-3 bg-white border rounded-5 shadow-sm mt-3 flex-grow-1">
        <div class="table-responsive">
          <table class="table align-top table-hover">
            <thead>
              <tr>
                <th>Product ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Category</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
                $conn = new mysqli("localhost", "root", "", "erp");

                if ($conn->connect_error) {
                  die("Connection failed: " . $conn->connect_error);
                }

                $where = "1";
                if (!empty($searchTerm)) {
                  $searchTermEscaped = $conn->real_escape_string($searchTerm);
                  $where .= " AND (`product_name` LIKE '%$searchTermEscaped%' OR `category` LIKE '%$searchTermEscaped%')";
                }

                if ($currentStatus === 'Available') {
                  $where .= " AND `Stock` > 0";
                } elseif ($currentStatus === 'Out of Stock') {
                  $where .= " AND `Stock` = 0";
                }

                $query = "SELECT * FROM products WHERE $where ORDER BY `product_id` ASC";
                $result = $conn->query($query);

                if ($result->num_rows > 0) {
                  while ($row = $result->fetch_assoc()) {
                    $id = $row["product_id"];
                    $name = htmlspecialchars($row["product_name"]);
                    $price = number_format($row["price"], 2);
                    $stock = $row["stock"];
                    $category = htmlspecialchars($row["category"]);

                    echo "
                      <tr class='align-middle product-row'>
                        <td>$id</td>
                        <td>$name</td>
                        <td>₱$price</td>
                        <td>$stock</td>
                        <td>$category</td>
                        <td>
                          <a href='product_details.php?id=$id' class='see-more-link'>See More...</a>
                        </td>
                      </tr>";
                  }
                } else {
                  echo "<tr><td colspan='6'>No products found.</td></tr>";
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
