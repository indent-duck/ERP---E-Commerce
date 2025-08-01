<?php
include 'db_connection.php';
session_start();

// TEMP customer_id (replace with session later)
$customer_id = 1001;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $product_ids = $_POST['product_id'];
    $quantities = $_POST['quantity'];
    $payment = $_POST['payment'];

    // Fetch customer info
    $stmt = $conn->prepare("SELECT CONCAT(first_name, ' ', last_name) AS full_name, address, contact_no FROM customers WHERE customer_id = ?");
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $customer = $stmt->get_result()->fetch_assoc();

    $store_discount = 0.00;
    $shipping_fee = 60.00;
    $grand_total = 0;

    foreach ($product_ids as $index => $product_id) {
        $qty = $quantities[$index];

        // Get price
        $stmt = $conn->prepare("SELECT retail_price FROM products WHERE product_id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $product = $stmt->get_result()->fetch_assoc();
        $price = $product['retail_price'];
        $subtotal = $price * $qty;
        $grand_total += $subtotal;

        // Insert order
        $stmt = $conn->prepare("INSERT INTO orders 
            (customer_id, product_id, item_quantity, total_amount, status, payment, discount) 
            VALUES (?, ?, ?, ?, 'Pending', ?, ?)");
        $stmt->bind_param("siidsd", $customer_id, $product_id, $qty, $subtotal, $payment, $store_discount);
        $stmt->execute();

        // Deduct product stock
        $stmt = $conn->prepare("UPDATE products SET quantity = quantity - ? WHERE product_id = ?");
        $stmt->bind_param("ii", $qty, $product_id);
        $stmt->execute();

        // Remove from cart
        $stmt = $conn->prepare("DELETE FROM cart WHERE customer_id = ? AND product_id = ?");
        $stmt->bind_param("ii", $customer_id, $product_id);
        $stmt->execute();
    }

    $grand_total += $shipping_fee - $store_discount;

    echo "<script>alert('Order placed successfully!');</script>";
    header("Location: cart.php");
    exit;
}

// Handle GET (initial checkout display)
if (!isset($_POST['product_id']) || !isset($_POST['quantity'])) {
    die("Invalid request. No items selected.");
}

$product_ids = $_POST['product_id'];
$quantities = $_POST['quantity'];

// Fetch customer info
$customer_sql = "SELECT CONCAT(first_name, ' ', last_name) AS full_name, address, contact_no FROM customers WHERE customer_id = ?";
$stmt = $conn->prepare($customer_sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$customer = $stmt->get_result()->fetch_assoc();

// Fetch product info
$placeholders = implode(',', array_fill(0, count($product_ids), '?'));
$product_sql = "SELECT product_id, product_name, retail_price, image1 FROM products WHERE product_id IN ($placeholders)";
$stmt = $conn->prepare($product_sql);
$stmt->bind_param(str_repeat('i', count($product_ids)), ...$product_ids);
$stmt->execute();
$result = $stmt->get_result();

$products = [];
foreach ($product_ids as $i => $id) {
    while ($row = $result->fetch_assoc()) {
        $qty = $quantities[$i];
        $row['quantity'] = $qty;
        $row['subtotal'] = $qty * $row['retail_price'];
        $products[] = $row;
        break;
    }
}

// Compute totals
$merch_subtotal = array_sum(array_column($products, 'subtotal'));
$shipping_fee = 60; // flat or computed later
$store_discount = 0; // apply your logic if any
$total = $merch_subtotal + $shipping_fee - $store_discount;
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="cart_style.css">
    <style>
        .fixed-checkout {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: white;
        border-top: 1px solid #ccc;
        padding: 15px;
        box-shadow: 0 -2px 10px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>

<div class="container mt-4 mb-5 pb-5">
    <h3 class="mb-3">Checkout</h3>

    <form method="POST" action="checkout.php">
    <!-- Customer Info -->
    <div class="mb-4 p-3 bg-white rounded shadow-sm" style="border: 1.5px solid black;">
        <div class="mb-3">
            <h5>Customer Information</h5>
            <p style="line-height: 0.7;"><strong>Name:</strong> <?= htmlspecialchars($customer['full_name']) ?></p>
            <p style="line-height: 0.7;"><strong>Address:</strong> <?= htmlspecialchars($customer['address']) ?></p>
            <p style="line-height: 0.7;"><strong>Phone:</strong> <?= htmlspecialchars($customer['contact_no']) ?></p>
        </div>
        
        <hr style="border: none; border-top: 1px solid #000; width: 100%; margin: 20px 0;">
        
        <h5>Order Summary</h5>
        <?php foreach ($products as $item): ?>
        <input type="hidden" name="product_id[]" value="<?= $item['product_id'] ?>">
        <input type="hidden" name="quantity[]" value="<?= $item['quantity'] ?>">
        <div class="d-flex py-2 align-items-center">
            <div style="width: 70px; height: 70px;" class="me-3">
            <img src="<?= htmlspecialchars($item['image1']) ?>" alt="Product" style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;">
            </div>
            <div class="flex-grow-1">
            <p class="mb-1"><?= htmlspecialchars($item['product_name']) ?></p>
            <p class="text-muted small">Qty: <?= $item['quantity'] ?> × ₱<?= number_format($item['retail_price'], 2) ?></p>
            </div>
            <div>
            ₱<?= number_format($item['subtotal'], 2) ?>
            </div>
        </div>
        <?php endforeach; ?>
    
        <hr style="border: none; border-top: 1px solid #000; width: 100%; margin: 20px 0;">

        <!-- Payment Method -->
        <div class="mb-4">
            <label class="form-label">Payment Method</label>
            <select class="form-select" name="payment" required>
                <option value="COD">Cash on Delivery</option>
                <option value="GCash">GCash</option>
                <option value="CreditCard">Credit Card</option>
            </select>
        </div>
    
        <hr style="border: none; border-top: 1px solid #000; width: 100%; margin: 20px 0;">

        <h5>Summary</h5>
        <div class="d-flex justify-content-between">
            <span>Merchandise Subtotal</span>
            <span>₱<?= number_format($merch_subtotal, 2) ?></span>
        </div>
        <div class="d-flex justify-content-between">
            <span>Shipping Fee</span>
            <span>₱<?= number_format($shipping_fee, 2) ?></span>
        </div>
        <div class="d-flex justify-content-between">
            <span>Store Discount</span>
            <span>-₱<?= number_format($store_discount, 2) ?></span>
        </div>
        <hr>
        <div class="d-flex justify-content-between fw-bold fs-5">
            <span>Total</span>
            <span>₱<?= number_format($total, 2) ?></span>
        </div>
    </div>
    </div>

    <!-- Place Order button -->
    <div class="fixed-bottom bg-light p-3 border-top d-flex justify-content-between align-items-center shadow">
        <div>
            <p class="mb-0 small">Total Discount: ₱<?= number_format($store_discount, 2) ?></p>
            <h5 class="mb-1">Total: ₱<?= number_format($total, 2) ?></h5>
        </div>
        <button type="submit" name="place_order" class="btn btn-danger px-4">Place Order</button>
    </div>
</form>
</div>

</body>
</html>
