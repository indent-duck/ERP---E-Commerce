<?php
$conn = new mysqli("localhost", "root", "", "erp");

if (isset($_GET['id'])) {
  $id = (int)$_GET['id']; 
  $result = $conn->query("SELECT * FROM products WHERE product_id = $id");
  $product = $result->fetch_assoc();

  // Use image1, image2, image3 from DB
  $images = array_filter([
    $product['image1'],
    $product['image2'],
    $product['image3']
  ]);

  if (empty($images)) {
  $images = ['default.jpg'];
}

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Product Details</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="product_details.css">
</head>
<body>
  <div class="container-fluid">
    <div class="d-flex full-height"></div>

    <div class="right-col">
      <div class="flex-grow-1 py-5 px-2">
        <a href="products.php" class="btn btn-secondary mb-4">Back to Products</a>

        <div class="card-body">
          <h3 class="card-title"><?= htmlspecialchars($product['product_name']) ?></h3>
          <p><strong>Price:</strong> ₱<?= number_format($product['price'], 2) ?></p>
          <p><strong>Stock:</strong> <?= $product['stock'] ?></p>
          <p><strong>Category:</strong> <?= htmlspecialchars($product['category']) ?></p>

          <div class="description-container">
                <div class="description-label">Product Description</div>
                <div class="description-box">
                    <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
                </div>
                <div class="image-container">
                    <div class="image-label">Images</div>
                    <div class="row gx-3">
                        <?php foreach ($images as $img): ?>
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="image-box">
                            <img src="uploads/<?= htmlspecialchars($img) ?>" alt="Product Image" class="img-fluid rounded">
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>