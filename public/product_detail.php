<?php 
session_start();
include('../includes/config.php');

if (!isset($_SESSION['u_id'])) {
    echo "<script> window.location.href='login.php';</script>";
    exit();
}

$productId = $_GET['id'] ?? null;
if (!$productId) {
    echo "<script>alert('Invalid product ID.'); window.location.href='view_products.php';</script>";
    exit();
}

try {
    $conn = new PDO("mysql:host=$server;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$productId]);

    if ($stmt->rowCount() === 0) {
        echo "<script>alert('Product not found.'); window.location.href='view_products.php';</script>";
        exit();
    }

    $product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!isset($_SESSION['viewed_items'])) {
    $_SESSION['viewed_items'] = [];
}

// Check if already viewed to prevent duplicates
$already_viewed = false;
foreach ($_SESSION['viewed_items'] as $item) {
    if ($item['id'] == $productId && $item['type'] == 'product') {
        $already_viewed = true;
        break;
    }
}

if (!$already_viewed) {
    $_SESSION['viewed_items'][] = [
        'id' => $productId,
        'type' => 'product'
    ];

    // Optional: Limit to last 10 viewed items
    if (count($_SESSION['viewed_items']) > 10) {
        array_shift($_SESSION['viewed_items']);
    }
}


    // Check if already in cart
    $user_email = $_SESSION['username'] ?? null;
    $in_cart = false;
    if ($user_email) {
        $cart_stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND pid = ?");
        $cart_stmt->execute([$user_email, $productId]);
        $in_cart = $cart_stmt->rowCount() > 0;
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlentities($product['name']) ?></title>
    <style>
        body { font-family: Arial; background-color: #f4f4f4; margin: 0; }
        .product-detail-container {
            display: flex;
            flex-wrap: wrap;
            width: 80%; margin: 50px auto;
            gap: 40px;
        }
        .product-info {
            flex: 2; background: #fff;
            border-left: 5px solid #f8bd33;
            padding: 20px; border-radius: 8px;
        }
        .product-cart-box {
            flex: 1; background: white;
            padding: 20px; border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .product-img {
            width: 100%; border-radius: 10px; margin-bottom: 15px;
        }
        .price {
            font-size: 24px; color: #e56131;
            font-weight: bold; margin: 15px 0;
        }
        .btn {
            display: block; width: 100%;
            padding: 12px; margin-bottom: 10px;
            border: none; border-radius: 6px;
            font-size: 16px; cursor: pointer;
        }
        .btn.add { background: #8e44ad; color: white; }
        .btn.buy { background: #2ecc71; color: white; }
        .btn.cart { background: #3498db; color: white; text-decoration: none; }
    </style>
</head>
<body>

<?php include('../includes/header.php'); ?>

<div class="product-detail-container">
    <div class="product-info">
        <h1><?= htmlentities($product['name']) ?></h1>
        <p class="subtitle"><?= htmlentities($product['description'] ?? 'No description available.') ?></p>

        <h3>What you'll learn</h3>
        <ul>
            <?php
            $features = explode(',', $product['features'] ?? '');
            foreach ($features as $feature) {
                echo "<li>✔ " . htmlentities(trim($feature)) . "</li>";
            }
            ?>
        </ul>
    </div>

    <div class="product-cart-box">
        <img src="../admin/uploaded_files/<?= htmlentities($product['image']) ?>" alt="<?= htmlentities($product['name']) ?>" class="product-img">
        <p class="price">Rs. <?= htmlentities($product['price']) ?></p>

        <div class="button-group" id="cart-action">
            <?php if ($in_cart): ?>
                <a href="cart.php" class="btn cart">Go to Cart</a>
            <?php else: ?>
                <button class="btn add" onclick="addToCart(<?= $productId ?>)">Add to Cart</button>
            <?php endif; ?>

            <a href="checkout.php?product_id=<?= $productId ?>" class="btn buy">Buy Now</a>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>

<!-- JavaScript to handle AJAX -->
<script>
    function addToCart(productId) {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "cart.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onload = function () {
            if (xhr.status === 200) {
                // Replace button with "Go to Cart"
                document.getElementById('cart-action').innerHTML =
                    '<a href="cart.php" class="btn cart">Go to Cart</a>' +
                    '<a href="checkout.php?product_id=' + productId + '" class="btn buy">Buy Now</a>';
            } else {
                alert("Error adding to cart.");
            }
        };

        xhr.send("add_to_cart=1&product_id=" + productId);
    }
</script>
</body>
</html>