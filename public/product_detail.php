<?php   
session_start();
include('../includes/config.php');

// Redirect if not logged in
if (!isset($_SESSION['u_id'])) {
    echo "<script>window.location.href='login.php';</script>";
    exit();
}

$productId = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : null;
if (!$productId) {
    echo "<script>alert('Invalid product ID.'); window.location.href='view_products.php';</script>";
    exit();
}

try {
    $conn = new PDO("mysql:host=$server;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch product
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$productId]);

    if ($stmt->rowCount() === 0) {
        echo "<script>alert('Product not found.'); window.location.href='view_products.php';</script>";
        exit();
    }

    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    // Track viewed items
    if (!isset($_SESSION['viewed_items'])) {
        $_SESSION['viewed_items'] = [];
    }

    $already_viewed = false;
foreach ($_SESSION['viewed_items'] as $item) {
    if (is_array($item) && isset($item['id'], $item['type']) && $item['id'] == $productId && $item['type'] == 'product') {
        $already_viewed = true;
        break;
    }
}


    if (!$already_viewed) {
        $_SESSION['viewed_items'][] = ['id' => $productId, 'type' => 'product'];
        if (count($_SESSION['viewed_items']) > 10) {
            array_shift($_SESSION['viewed_items']);
        }
    }

    // Check if product is already in cart
    $user_id = $_SESSION['u_id'];
    $in_cart = false;
    $cart_stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND pid = ?");
    $cart_stmt->execute([$user_id, $productId]);
    $in_cart = $cart_stmt->rowCount() > 0;

    // Generate CSRF token
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
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
    <meta name="description" content="<?= htmlentities(substr($product['details'], 0, 160)) ?>">
    <meta property="og:title" content="<?= htmlentities($product['name']) ?>" />
    <meta property="og:image" content="../admin/uploaded_files/<?= htmlentities(basename($product['image'])) ?>" />
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .product-name {
            color: rgb(248, 189, 51);
            margin: 20px;
        }
        .product-detail-container {
            display: flex;
            flex-wrap: wrap;
            width: 85%;
            margin: 50px auto;
            gap: 40px;
        }
        .product-info {
            flex: 2;
            background: #fff;
            border-left: 5px solid #f8bd33;
            padding: 25px;
            border-radius: 8px;
        }
        .product-cart-box {
            flex: 1;
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .product-img {
            width: 100%;
            border-radius: 10px;
            margin-bottom: 15px;
        }
        .price {
            font-size: 24px;
            color: #e56131;
            font-weight: bold;
            margin: 15px 0;
        }
        .btn {
            display: block;
            width: 100%;
            padding: 12px;
            margin-bottom: 10px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }
        .btn.add { background: #8e44ad; color: white; }
        .btn.buy { background: #2ecc71; color: white; }
        .btn.cart { background: #3498db; color: white; text-decoration: none; }
        .learning-points li {
            margin: 5px 0;
        }
    </style>
</head>
<body>

<?php include('../includes/header.php'); ?>

<div class="product-detail-container">
    <!-- Left Section -->
    <div class="product-info">
        <h1 class="product-name"><?= htmlentities($product['name']) ?></h1>
        <?php if (!empty($product['details'])): ?>
            <h3>What you'll learn</h3>
            <ul class="learning-points">
                <?php 
                    $points = preg_split('/\r\n|\r|\n/', $product['details']);
                    foreach ($points as $point):
                        if (trim($point) !== ''):
                ?>
                    <li><?= htmlentities(trim($point)) ?></li>
                <?php endif; endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

    <!-- Right Section -->
    <div class="product-cart-box">
        <img src="../admin/uploaded_files/<?= htmlentities(basename($product['image'])) ?>" 
             onerror="this.src='../images/default.png'" 
             alt="<?= htmlentities($product['name']) ?>" class="product-img">
        <p class="price">Rs. <?= htmlentities($product['price']) ?></p>

        <div class="button-group" id="cart-action">
            <?php if ($in_cart): ?>
                <a href="cart.php" class="btn cart">Go to Cart</a>
            <?php else: ?>
                <button class="btn add" onclick="addToCart(<?= $productId ?>)">Add to Cart</button>
            <?php endif; ?>
            <a href="../payment/checkout.php?product_id=<?= $productId ?>" class="btn buy">Buy Now</a>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>

<script>
function addToCart(productId) {
    fetch("cart.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "add_to_cart=1&product_id=" + productId + "&csrf_token=<?= $_SESSION['csrf_token'] ?>"
    })
    .then(response => {
        if (response.ok) {
            document.getElementById('cart-action').innerHTML =
                '<a href="cart.php" class="btn cart">Go to Cart</a>' +
                '<a href="../payment/checkout.php?product_id=' + productId + '" class="btn buy">Buy Now</a>';
        } else {
            alert("Error adding to cart.");
        }
    });
}
</script>

</body>
</html>
