<?php
session_start(); // Start the session
include('includes/config.php');

// Error Reporting for Debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    echo "<script>alert('Please log in to view your cart.'); window.location.href='login.php';</script>";
    exit();
}

// Database Connection
try {
    $conn = new PDO("mysql:host=$server;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}

// Get User Email from Session (not cookies for security)
$user_email = $_SESSION['username'] ?? null;

if (!$user_email) {
    echo "<script>alert('Your cart is empty! Please add some items.'); window.location='index.php';</script>";
    exit();
}

// Fetch Cart Items
$select_cart = $conn->prepare("SELECT cart.id AS cart_id, products.id AS product_id, products.name, products.price, products.image, cart.quantity 
                               FROM cart 
                               JOIN products ON cart.pid = products.id 
                               WHERE cart.user_id = ?");
$select_cart->execute([$user_email]);
$cart_items = $select_cart->fetchAll(PDO::FETCH_ASSOC);

// Calculate total price
$total_price = 0;
foreach ($cart_items as $item) {
    $total_price += $item['price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopping Cart</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/add.css">
    <style>
        body {
            background-color: #ecececdb !important;
        }
        .heading {
            margin-top: 100px;
            text-align: center;
        }
        .cart-container {
            width: 80%;
            max-width: 800px;
            margin: auto;
            padding: 20px;
        }
        .cart-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #fff;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .cart-item img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
        }
        .cart-item h3 {
            flex: 1;
            margin-left: 20px;
            font-size: 18px;
        }
        .cart-item p {
            margin: 0 20px;
            font-size: 16px;
        }
        .btn, .remove-btn {
            color: #fff !important;
            background-color: #000 !important;
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            transition: background 0.3s ease;
        }
        .btn:hover, .remove-btn:hover {
            background-color: #e56131 !important;
        }
        .total-price {
            text-align: right;
            font-size: 20px;
            margin-top: 20px;
            font-weight: bold;
        }
        .checkout-btn {
            text-align: right;
            margin-top: 20px;
        }
        .empty-cart {
            text-align: center;
            font-size: 18px;
            color: #555;
        }
    </style>
</head>
<body>
    <?php include('includes/header.php'); ?>
    <h1 class="heading">Shopping Cart</h1>
    <div class="cart-container">
        <?php if ($cart_items): ?>
            <?php foreach ($cart_items as $item): ?>
                <div class="cart-item">
    <img src="admin/uploaded_files/<?= htmlentities($item['image']); ?>" alt="<?= htmlentities($item['name']); ?>">
    <h3><?= htmlentities($item['name']); ?></h3>
    <p>Price: &#8360; <?= htmlentities($item['price']); ?></p>
    <p>Quantity: <?= htmlentities($item['quantity']); ?></p>
    
    <a href="remove_cart.php?cart_id=<?= $item['cart_id']; ?>" class="remove-btn">Remove</a>
    </div>
            <?php endforeach; ?>
            
            <div class="total-price">
                Total Price: &#8360; <?= number_format($total_price, 2); ?>
            </div>
            <div class="checkout-btn">
    <?php if (isset($_SESSION['username']) && count($cart_items) > 0): ?>
        <!-- Pass the user email as 'user_id' parameter to checkout.php -->
        <a href="checkout.php?user_id=<?= urlencode($user_email); ?>" class="btn">Proceed to Checkout</a>
    <?php else: ?>
        <a href="login.php" class="btn">Log In to Checkout</a>
    <?php endif; ?>
</div>


        <?php else: ?>
            <p class="empty-cart">Your cart is empty.</p>
        <?php endif; ?>
    </div>
    <?php include('includes/footer.php'); ?>
</body>
</html>
