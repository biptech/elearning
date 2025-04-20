<?php
session_start();
include('../includes/config.php');

// Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Use session ID as user identifier if not logged in
$user_email = $_SESSION['username'] ?? session_id();

// Connect to DB
try {
    $conn = new PDO("mysql:host=$server;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Handle "Add to Cart" if submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'] ?? null;

    if ($product_id && $user_email) {
        // Check if item already exists in cart
        $check_cart = $conn->prepare("SELECT * FROM cart WHERE pid = ? AND user_id = ?");
        $check_cart->execute([$product_id, $user_email]);
        if ($check_cart->rowCount() > 0) {
            // Already in cart, just redirect
            header("Location: cart.php");
            exit();
        } else {
            // Insert into cart
            $insert_cart = $conn->prepare("INSERT INTO cart (pid, user_id, quantity) VALUES (?, ?, 1)");
            $insert_cart->execute([$product_id, $user_email]);
            header("Location: cart.php");
            exit();
        }
    } else {
        echo "<script>alert('Invalid product or user.'); window.location='index.php';</script>";
        exit();
    }
}

// Fetch cart items
$select_cart = $conn->prepare("SELECT cart.id AS cart_id, products.id AS product_id, products.name, products.price, products.image, cart.quantity 
                               FROM cart 
                               JOIN products ON cart.pid = products.id 
                               WHERE cart.user_id = ?");
$select_cart->execute([$user_email]);
$cart_items = $select_cart->fetchAll(PDO::FETCH_ASSOC);

// Calculate total
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
        background-color: #f9f9f9;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0;
        padding: 0;
    }

    .heading {
        margin-top: 40px;
        text-align: center;
        font-size: 32px;
        color: rgb(248, 189, 51);
    }

    .cart-container {
        width: 90%;
        max-width: 900px;
        margin: 40px auto;
        padding: 25px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    .cart-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        background-color: #fefefe;
        padding: 20px;
        margin-bottom: 20px;
        border: 1px solid #eee;
        border-radius: 10px;
        transition: box-shadow 0.3s ease;
    }

    .cart-item:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    .cart-item img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #ddd;
    }

    .cart-item h3 {
        flex: 1;
        margin: 0;
        font-size: 20px;
        color: #444;
    }

    .cart-item p {
        margin: 0 15px;
        font-size: 16px;
        color: #555;
    }

    .btn, .remove-btn {
        color: #fff !important;
        background: #3498db !important;
        padding: 10px 18px;
        border-radius: 5px;
        font-size: 15px;
        text-decoration: none;
        transition: background 0.3s ease;
    }

    .btn:hover, .remove-btn:hover {
        background: #e67e22 !important;
    }

    .remove-btn {
        background: #e74c3c !important;
    }

    .remove-btn:hover {
        background: #c0392b !important;
    }

    .total-price {
        text-align: right;
        font-size: 22px;
        margin-top: 30px;
        font-weight: bold;
        color: #2c3e50;
    }

    .checkout-btn {
        text-align: right;
        margin-top: 25px;
    }

    .empty-cart {
        text-align: center;
        font-size: 20px;
        color: #888;
        margin-top: 60px;
    }

    @media (max-width: 768px) {
        .cart-item {
            flex-direction: column;
            align-items: flex-start;
        }

        .cart-item img {
            width: 100%;
            max-width: 300px;
        }

        .cart-item h3, .cart-item p {
            margin: 10px 0;
        }

        .checkout-btn {
            text-align: center;
        }

        .total-price {
            text-align: center;
        }
    }
</style>

</head>
<body>
<?php include('../includes/header.php'); ?>

<h1 class="heading">Shopping Cart</h1>
<div class="cart-container">
    <?php if ($cart_items): ?>
        <?php foreach ($cart_items as $item): ?>
            <div class="cart-item">
                <img src="../admin/uploaded_files/<?= htmlentities($item['image']); ?>" alt="<?= htmlentities($item['name']); ?>">
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
            <a href="checkout.php?user_id=<?= urlencode($user_email); ?>" class="btn">Proceed to Checkout</a>
        </div>
    <?php else: ?>
        <p class="empty-cart">Your cart is empty.</p>
    <?php endif; ?>
</div>

<?php include('../includes/footer.php'); ?>
<script>
    // Confirm before removing an item from the cart
    document.addEventListener("DOMContentLoaded", function () {
        const removeButtons = document.querySelectorAll(".remove-btn");

        removeButtons.forEach(function (btn) {
            btn.addEventListener("click", function (e) {
                const confirmDelete = confirm("Are you sure you want to remove this item from your cart?");
                if (!confirmDelete) {
                    e.preventDefault(); // Stop the link from executing
                }
            });
        });
    });
</script>

</body>
</html>
