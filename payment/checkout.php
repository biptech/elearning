<?php
session_start();
include('../includes/config.php');

// Redirect if user not logged in
if (!isset($_SESSION['u_id'])) {
    echo "<script>alert('Please log in to proceed to checkout.'); window.location.href='../public/login.php';</script>";
    exit();
}

// Fetch user details
$userId = $_SESSION['u_id'];
$query = "SELECT u_name, u_address, u_email, u_phone FROM user_signup WHERE u_id = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "<script>alert('User not found! Redirecting to login.'); window.location.href='../public/login.php';</script>";
    exit();
}
$stmt->close();

// Variables
$productId = $_GET['product_id'] ?? null;
$products = [];
$totalPrice = 0;
$serviceCharge = 5.65;

// Redirect if neither single product nor cart purchase
if (!$productId && !isset($_SESSION['u_id'])) {
    header("Location: ../public/view_products.php");
    exit();
}

// Single Product Checkout
if ($productId) {
    $query = "SELECT * FROM products WHERE id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        $products[] = $product;
        $totalPrice = $product['price'];
    } else {
        echo "<script>alert('Invalid product ID.'); window.location.href='../public/view_products.php';</script>";
        exit();
    }
    $stmt->close();
} 
// Cart Purchase Checkout — **use SESSION user ID here**
else {
    $query = "SELECT cart.id AS cart_id, products.id AS product_id, products.name, products.price, products.image, cart.quantity 
              FROM cart 
              JOIN products ON cart.pid = products.id 
              WHERE cart.user_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
            $totalPrice += $row['price'] * $row['quantity'];
        }
    } else {
        echo "<script>alert('Cart is empty.'); window.location.href='../public/view_products.php';</script>";
        exit();
    }
    $stmt->close();
}

// Handle Order Placement
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customerName = $user['u_name'];
    $customerEmail = $user['u_email'];
    $customerPhone = $user['u_phone'];
    $customerAddress = $user['u_address'];

    foreach ($products as $product) {
        $quantity = $productId ? 1 : $product['quantity'];
        $productTotalPrice = $product['price'] * $quantity;
        $productIdValue = $product['product_id'] ?? $product['id'];
        $productName = $product['name'];
        $productPrice = $product['price'];

        $insertOrder = $con->prepare("
            INSERT INTO orders (product_id, product_name, price, quantity, total_price, customer_name, customer_email, customer_phone, customer_address, payment_status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')
        ");
        $insertOrder->bind_param(
            "isdiissss",
            $productIdValue,
            $productName,
            $productPrice,
            $quantity,
            $productTotalPrice,
            $customerName,
            $customerEmail,
            $customerPhone,
            $customerAddress
        );
        $insertOrder->execute();
        $insertOrder->close();
    }

    $orderId = $con->insert_id;
    header("Location: payment-request.php?order_id=$orderId&total_price=$totalPrice");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <style>
        body {
            background-color: #ecececdb;
            font-family: Arial, sans-serif;
        }
        .checkout-title {
            text-align: center;
            color: rgb(248, 189, 51);
            margin-top: 60px;
        }
        .checkout-container {
            display: flex;
            gap: 20px;
            padding: 20px;
            max-width: 1100px;
            margin: 0 auto;
        }
        .order-details, .order-summary {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .order-details { flex: 2; }
        .order-summary { flex: 1; }
        .section-title {
            font-size: 20px;
            margin-bottom: 20px;
        }
        .product-item {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .product-item img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 10px;
            margin-right: 20px;
        }
        .product-name {
            margin: 0;
            font-size: 18px;
        }
        .product-price, .product-quantity {
            margin: 0;
            font-size: 14px;
            color: #666;
        }
        .total-price {
            font-size: 20px;
            font-weight: bold;
            margin-top: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .input-field {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        .submit-button {
            width: 100%;
            padding: 12px;
            border: none;
            background-color: #5c67f2;
            color: white;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
        }
        .submit-button:hover {
            background-color: #4a54e1;
        }
    </style>
</head>
<body>
<?php include('../includes/header.php'); ?>
<h1 class="checkout-title">Checkout</h1>
<div class="checkout-container">
    <div class="order-details">
        <h3 class="section-title">Order Details</h3>
        <?php foreach ($products as $product): ?>
            <div class="product-item">
                <img src="../admin/uploaded_files/<?= htmlentities($product['image']); ?>" alt="<?= htmlentities($product['name']); ?>">
                <div>
                    <h5 class="product-name"><?= htmlentities($product['name']); ?></h5>
                    <p class="product-price">Price: &#8360; <?= htmlentities($product['price']); ?></p>
                    <p class="product-quantity">Quantity: <?= $productId ? 1 : $product['quantity']; ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="order-summary">
        <h3 class="section-title">Order Summary</h3>
        <p>Total Items: <?= count($products); ?></p>
        <p>Subtotal: &#8360; <?= number_format($totalPrice, 2); ?></p>
        <p>Service Charge: &#8360; <?= number_format($serviceCharge, 2); ?></p>
        <p class="total-price">Total Payable: &#8360; <?= number_format($totalPrice + $serviceCharge, 2); ?></p>

        <form method="POST">
            <div class="form-group">
                <label for="inputName">Name</label>
                <input type="text" class="input-field" name="inputName" id="inputName" value="<?= htmlspecialchars($user['u_name']); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="inputEmail">Email</label>
                <input type="email" class="input-field" name="inputEmail" id="inputEmail" value="<?= htmlspecialchars($user['u_email']); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="inputPhone">Phone</label>
                <input type="text" class="input-field" name="inputPhone" id="inputPhone" value="<?= htmlspecialchars($user['u_phone']); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="inputAddress">Address</label>
                <input type="text" class="input-field" name="inputAddress" id="inputAddress" value="<?= htmlspecialchars($user['u_address']); ?>" readonly>
            </div>
            <button type="submit" class="submit-button">Proceed to Payment</button>
        </form>
    </div>
</div>
</body>
<?php include('../includes/footer.php'); ?>
</html>