<?php
include 'db_connection.php';
session_start();

// TEMP user_id for demo — replace with $_SESSION['user_id'] in real app
$customer_id = 1001;

// Check if search term exists
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

$sql = "SELECT ci.product_id AS cart_id, p.quantity, p.product_id AS product_id, p.product_name, p.retail_price, p.image1
        FROM cart ci
        JOIN products p ON ci.product_id = p.product_id
        WHERE ci.customer_id = ?";

if (!empty($searchTerm)) {
    $sql .= " AND p.product_name LIKE ?";
}

$stmt = $conn->prepare($sql);

if (!empty($searchTerm)) {
    $like = '%' . $searchTerm . '%';
    $stmt->bind_param("is", $customer_id, $like);
} else {
    $stmt->bind_param("i", $customer_id);
}

$stmt->execute();
$result = $stmt->get_result();

$cart_items = [];
while ($row = $result->fetch_assoc()) {
    $cart_items[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Cart</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="cart_style.css">
</head>
<body style="background-color: white;">

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">   </h2>
        <form method="GET" class="d-flex">
        <input 
            type="text" 
            name="search" 
            class="form-control me-2" 
            style="border-radius: 10px; max-width: 300px;" 
            placeholder="Search"
            value="<?= htmlspecialchars($searchTerm) ?>"
        />
        </form>
    </div>
    
    <div class="container">
        <!-- Product Card 1 -->
        <div class="container">
            <?php foreach ($cart_items as $item): ?>
                <div class="result-box p-3 bg-white rounded shadow-sm mt-4 mx-auto" style="max-width: 600px;">
                    <h5>Store Name</h5> <!-- You can later replace this with dynamic store name if needed -->
                    <div class="product-card d-flex">
                        <input type="checkbox" class="mt-2 me-3" name="selected_products[]" value="<?= htmlspecialchars($item['product_id']) ?>">
                        
                        <!-- Rounded image box -->
                        <div class="image-box me-3" style="width: 100px; height: 100px; overflow: hidden; border-radius: 10px;">
                            <?php if (!empty($item['image1'])): ?>
                                <img src="<?= htmlspecialchars($item['image1']) ?>" alt="Product Image" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.parentNode.innerHTML = '<span class=\'text-muted\'>No Image</span>';">
                            <?php else: ?>
                                <span class="text-muted">No Image</span>
                            <?php endif; ?>
                        </div>

                        <div class="product-info flex-grow-1">
                            <p class="mb-1 fw-bold"><?= htmlspecialchars($item['product_name']) ?></p>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <p class="mb-0">₱ <?= number_format($item['retail_price'], 2) ?></p>
                                <div class="qty-controls d-flex align-items-center">
                                    <button type="button" class="btn-minus btn btn-outline-secondary btn-sm">−</button>
                                    <span class="qty-count mx-2"> 1 </span>
                                    <button type="button" class="btn-plus btn btn-outline-secondary btn-sm">+</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if (empty($cart_items)): ?>
                <div class="alert alert-dark mt-4">No products found.</div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- CHECKOUT PANEL (fixed-bottom, hidden by default) -->
    <div id="checkout-panel" class="checkout-panel fixed-bottom bg-white border-top p-3 d-none shadow">
    <div class="container d-flex justify-content-between align-items-center">
        <div>
        <input type="checkbox" id="select-all"> 
        <label for="select-all" class="ms-1">Select All</label>
        <span class="ms-3">
            Subtotal: <strong>₱<span id="subtotal">0.00</span></strong>
        </span>
        </div>
        <button class="btn btn-danger" id="checkout-btn">
        Check Out (<span id="count">0</span>)
        </button>
    </div>
    </div>
</div>


<script>
    document.addEventListener("DOMContentLoaded", function () {
        const cards = document.querySelectorAll('.qty-controls');

        cards.forEach(card => {
        const plusBtn = card.querySelector('.btn-plus');
        const minusBtn = card.querySelector('.btn-minus');
        const qtySpan = card.querySelector('.qty-count');

        plusBtn.addEventListener('click', () => {
            let currentQty = parseInt(qtySpan.textContent);
            qtySpan.textContent = currentQty + 1;
        });

        minusBtn.addEventListener('click', () => {
            let currentQty = parseInt(qtySpan.textContent);
            if (currentQty > 1) {
            qtySpan.textContent = currentQty - 1;
            }
        });
        });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
    const panel     = document.getElementById("checkout-panel");
    const selectAll = document.getElementById("select-all");
    const subtotal  = document.getElementById("subtotal");
    const count     = document.getElementById("count");
    const checkout  = document.getElementById("checkout-btn");

    // query all product checkboxes
    const items = document.querySelectorAll('input[name="selected_products[]"]');

    function recalc() {
        let sum = 0, cnt = 0;
        items.forEach(cb => {
        if (!cb.checked) return;
        cnt++;
        const card = cb.closest(".product-card");
        // grab price text, remove non-digits
        let price = parseFloat(
            card.querySelector(".product-info p.mb-0")
                .textContent.replace(/[^0-9.]/g, "")
        );
        let qty = parseInt(card.querySelector(".qty-count").textContent);
        sum += price * qty;
        });

        count.textContent    = cnt;
        subtotal.textContent = sum.toFixed(2);

        // show/hide panel
        panel.classList.toggle("d-none", cnt === 0);
        if (cnt === 0) selectAll.checked = false;
    }

    // watch each checkbox
    items.forEach(cb => cb.addEventListener("change", recalc));

    // re-calc when qty changes
    document.querySelectorAll(".btn-plus, .btn-minus")
            .forEach(btn =>
        btn.addEventListener("click", () => setTimeout(recalc, 0))
    );

    // select-all toggle
    selectAll.addEventListener("change", function() {
        items.forEach(cb => cb.checked = selectAll.checked);
        recalc();
    });

    // on checkout, build & submit a POST form
    checkout.addEventListener("click", function() {
        const form = document.createElement("form");
        form.method = "POST";
        form.action = "checkout.php";

        items.forEach(cb => {
        if (!cb.checked) return;
        // product_id[]
        const idI = document.createElement("input");
        idI.type  = "hidden";
        idI.name  = "product_id[]";
        idI.value = cb.value;
        form.appendChild(idI);

        // quantity[]
        const card = cb.closest(".product-card");
        const qI   = document.createElement("input");
        qI.type    = "hidden";
        qI.name    = "quantity[]";
        qI.value   = card.querySelector(".qty-count").textContent;
        form.appendChild(qI);
        });

        document.body.appendChild(form);
        form.submit();
    });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
