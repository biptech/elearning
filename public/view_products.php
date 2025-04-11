<?php
session_start();
include('../includes/config.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    $conn = new PDO("mysql:host=$server;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}

// Ensure user is logged in
if (!isset($_SESSION['username'])) {
    echo "<script>alert('Please log in to shop.'); window.location.href='login.php';</script>";
    exit();
}

$user_id = $_SESSION['username'];

// Handle Add to Cart
if (isset($_POST['add_to_cart'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = max(1, intval($_POST['qty']));

    // Check if the item already exists in the cart
    $check_cart = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND pid = ?");
    $check_cart->execute([$user_id, $product_id]);

    if ($check_cart->rowCount() > 0) {
        // Update quantity if the product already exists
        $update_cart = $conn->prepare("UPDATE cart SET quantity = quantity + ? WHERE user_id = ? AND pid = ?");
        $update_cart->execute([$quantity, $user_id, $product_id]);
    } else {
        // Insert new item into the cart
        $add_to_cart = $conn->prepare("INSERT INTO cart (user_id, pid, quantity) VALUES (?, ?, ?)");
        $add_to_cart->execute([$user_id, $product_id, $quantity]);
    }

    echo "<script>alert('Item added to cart!'); window.location.href='cart.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Products</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/view_products.css">
</head>
<body>
    <?php include('../includes/header.php'); ?>
    <section class="products container">
        <h1 id="ourcources" style="text-align: center; margin-top: 60px;">
            <span>SHOP NOW <br> &darr;</span>
        </h1>
        <div class="row">
            <?php
            $select_products = $conn->prepare("SELECT * FROM products");
            if ($select_products->execute()) {
                if ($select_products->rowCount() > 0) {
                    while ($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)) {
                        $productImage = htmlentities($fetch_product['image'] ?? '');
                        $productName = htmlentities($fetch_product['name'] ?? 'Unknown Product');
                        $productPrice = htmlentities($fetch_product['price'] ?? '0');
                        $productId = htmlentities($fetch_product['id'] ?? '0');
            ?>
                        <form action="" method="POST" class="box">
                            <img src="admin/uploaded_files/<?= $productImage; ?>" class="image" alt="<?= $productName; ?>">
                            <h3 class="name"><?= $productName; ?></h3>
                            <input type="hidden" name="product_id" value="<?= $productId; ?>">
                            <div class="flex">
                                <p class="price">&#8360; <?= $productPrice; ?></p>
                                <input type="number" name="qty" required min="1" value="1" class="qty" readonly hidden>
                            </div>
                            <?php
                            if (isset($_SESSION['username'])) {
                                $check_cart = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND pid = ?");
                                $check_cart->execute([$user_id, $productId]);

                                if ($check_cart->rowCount() > 0) {
                                    echo '<a href="cart.php" class="btn">Go to Cart</a>';
                                } else {
                                    echo '<input type="submit" name="add_to_cart" value="Add to Cart" class="btn">';
                                }
                                echo '<a href="checkout.php?product_id=' . $productId . '" class="btn buy-btn">Buy Now</a>';
                            } else {
                                echo '<a href="login.php" class="btn">Add to Cart</a>';
                                echo '<a href="login.php" class="btn buy-btn">Buy Now</a>';
                            }
                            ?>
                        </form>
            <?php
                    }
                } else {
                    echo '<p class="empty">No products found!</p>';
                }
            } else {
                echo '<p class="empty">Failed to load products!</p>';
            }
            ?>
        </div>
    </section>
    <?php include('../includes/footer.php'); ?>
</body>
</html>