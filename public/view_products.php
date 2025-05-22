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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Products</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/view-products.css">
</head>
<body>

<?php include('../includes/header.php'); ?>

<section class="products container">
    <h1 id="ourcources"><span>SHOP NOW <br> &darr;</span></h1>

    <div class="row">
        <?php
        $select_products = $conn->prepare("
            SELECT products.*, tblcategory.CategoryName AS category
            FROM products
            LEFT JOIN tblcategory ON products.category_id = tblcategory.id
        ");

        if ($select_products->execute() && $select_products->rowCount() > 0) {
            while ($product = $select_products->fetch(PDO::FETCH_ASSOC)) {
                $productId = (int)$product['id'];
                $productImage = !empty($product['image']) ? htmlentities($product['image']) : 'default.png';
                $productName = htmlentities($product['name'] ?? 'Unknown Product');
                $productPrice = htmlentities($product['price'] ?? '0');
                $categoryName = htmlentities($product['category'] ?? 'Uncategorized');
                $details = $product['details'] ?? '';

                $user_id = $_SESSION['u_id'] ?? null;
                $user_email = $_SESSION['u_email'] ?? null;

                $has_order = false;
                $in_cart = false;

                if ($user_email) {
                    // Check if user has purchased the product
                    $order_stmt = $conn->prepare("SELECT 1 FROM orders WHERE product_id = ? AND customer_email = ? AND payment_status = 'completed'");
                    $order_stmt->execute([$productId, $user_email]);
                    $has_order = $order_stmt->rowCount() > 0;

                    if (!$has_order && $user_id) {
                        // Check if product is in cart
                        $cart_stmt = $conn->prepare("SELECT 1 FROM cart WHERE user_id = ? AND pid = ?");
                        $cart_stmt->execute([$user_id, $productId]);
                        $in_cart = $cart_stmt->rowCount() > 0;
                    }
                }
        ?>
        <div class="box">
            <a href="product_detail.php?id=<?= $productId; ?>" class="product-link">
                <img src="../admin/uploaded_files/<?= $productImage; ?>" class="image" alt="<?= $productName; ?>">
                <h3 class="name"><?= $productName; ?></h3>
                <p class="category"><?= $categoryName; ?></p>
                <p class="price">&#8360; <?= number_format((float)$productPrice, 2); ?></p>
            </a>
            <div class="product-popup">
                <h4><strong>What you’ll learn</strong></h4>
                <ul>
                    <?php
                    $lines = array_filter(array_map('trim', explode("\n", $details)));
                    foreach (array_slice($lines, 0, 3) as $line) {
                        echo "<li>" . htmlentities($line) . "</li>";
                    }
                    ?>
                </ul>
                <div id="cart-action-<?= $productId ?>">
                    <?php if ($has_order): ?>
                        <a href="course_detail.php?id=<?= $productId ?>" class="btn cart add-to-cart-btn">Go To Course</a>
                    <?php elseif ($in_cart): ?>
                        <a href="cart.php" class="btn cart add-to-cart-btn">Go to Cart</a>
                    <?php else: ?>
                        <button type="button" class="add-to-cart-btn btn-add" onclick="addToCart(<?= $productId ?>)">Add to Cart</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
            }
        } else {
            echo '<p class="empty">No products found!</p>';
        }
        ?>
    </div>
</section>

<?php include('../includes/footer.php'); ?>

<script>
function addToCart(productId) {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "cart.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
        if (xhr.status === 200) {
            document.getElementById('cart-action-' + productId).innerHTML =
                '<a href="cart.php" class="btn cart">Go to Cart</a>';
        } else {
            alert("Error adding to cart.");
        }
    };
    xhr.send("add_to_cart=1&product_id=" + productId);
}

document.addEventListener("DOMContentLoaded", function () {
    const boxes = document.querySelectorAll(".box");
    boxes.forEach(box => {
        box.addEventListener("mouseenter", function () {
            const rect = box.getBoundingClientRect();
            if (rect.right + 250 > window.innerWidth) {
                box.classList.add("popup-left");
            } else {
                box.classList.remove("popup-left");
            }
        });
    });
});
</script>
</body>
</html>
