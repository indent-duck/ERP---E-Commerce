<?php
$conn = new mysqli("localhost", "root", "", "erp_db");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form values (must match form field names)
    $product_name = $_POST['product_name'] ?? '';
    $cost_price = (float) $_POST['cost_price'] ?? 0;
    $retail_price = (float) $_POST['retail_price'] ?? 0;
    $specification = $_POST['specification'] ?? '';
    $category = $_POST['category'] ?? '';
    $quantity = $_POST['quantity'] ?? 0;

    // Validate required fields
    if (empty($product_name)) {
        die("Product name is required.");
    }

    // Upload directory
    $uploadDir = "uploads/";

    // Handle image uploads
    $image1 = $_FILES['image1']['name'] ?? '';
    $image2 = $_FILES['image2']['name'] ?? '';
    $image3 = $_FILES['image3']['name'] ?? '';

    if (!empty($image1)) {
        move_uploaded_file($_FILES['image1']['tmp_name'], $uploadDir . $image1);
    }
    if (!empty($image2)) {
        move_uploaded_file($_FILES['image2']['tmp_name'], $uploadDir . $image2);
    }
    if (!empty($image3)) {
        move_uploaded_file($_FILES['image3']['tmp_name'], $uploadDir . $image3);
    }

    $stmt = $conn->prepare("INSERT INTO products (product_id, product_name, cost_price, retail_price, specification, category, quantity, image1, image2, image3) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?,?,?)");

    if (!$stmt) {
        die("Database error: " . $conn->error);
    }

    $stmt->bind_param("isddssisss", $product_id, $product_name, $cost_price, $retail_price, $specification, $category, $quantity, $image1, $image2, $image3);

    if ($stmt->execute()) {
        header("Location: products.php");
        exit;
    } else {
        echo "Error inserting product: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Product</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container-fluid">
  <div class="d-flex">

    <?php include 'sidebar.php'; ?>

    <div class="right-col">
      <div class="flex-grow-1 py-5 px-2">
        <h3>Add New Product</h3>

        <form action="add-products.php" method="POST" enctype="multipart/form-data">
          <div class="mb-3">
            <label class="form-label">Product Name</label>
            <input type="text" name="product_name" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Cost Price</label>
            <input type="number" name="cost_price" class="form-control" step="0.01" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Retail Price</label>
            <input type="number" name="retail_price" class="form-control" step="0.01" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Specification</label>
            <textarea name="specification" class="form-control" rows="3" required></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label">Category</label>
            <input type="text" name="category" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Quantity</label>
            <input type="number" name="quantity" class="form-control" required>
          </div>

          <label>Image 1</label>
          <input type="file" name="image1"><br>

          <label>Image 2</label>
          <input type="file" name="image2"><br>

          <label>Image 3</label>
          <input type="file" name="image3"><br>

          <button type="submit" class="btn btn-dark mt-3">Add Product</button>
          <a href="products.php" class="btn btn-outline-secondary mt-3">Cancel</a>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
