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
    <style>
    body {
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
  margin: 0;
  padding: 0;
  background-color: #f7f7f7;
}

.products {
  text-align: center;
  padding: 60px 20px;
}

.products h1 span {
  font-size: 32px;
  color: rgb(248, 189, 51);
  letter-spacing: 1px;
}

.row {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 30px;
  margin-top: 30px;
  position: relative;
  overflow: visible !important;
  z-index: 0;
}

.box {
  background-color: #fff;
  border-radius: 15px;
  padding: 20px;
  width: 260px;
  text-align: center;
  box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
  transition: all 0.3s ease-in-out;
  cursor: pointer;
  position: relative;
  overflow: visible;
  z-index: 1;
}

.box:hover {
  transform: translateY(-8px);
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
  z-index: 999;
}

.product-link {
  text-decoration: none;
  color: inherit;
}

.image {
  width: 100%;
  height: 200px;
  object-fit: cover;
  border-radius: 12px;
}

.name {
  font-size: 20px;
  font-weight: 600;
  margin: 15px 0 8px;
  color: #222;
}

.price {
  font-size: 18px;
  color: #e65100;
  font-weight: 700;
}

.product-popup {
  position: absolute;
  top: 50%;
  left: 105%;
  transform: translateY(-50%);
  background-color: #fff8e1;
  border-radius: 10px;
  padding: 15px;
  width: 250px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  border: 2px solid rgb(248, 189, 51);
  opacity: 0;
  pointer-events: none;
  transition: all 0.3s ease;
  z-index: 99999;
}

/* Cutout arrow */
.product-popup::before {
  content: "";
  position: absolute;
  top: 50%;
  left: -10px;
  transform: translateY(-50%);
  width: 0;
  height: 0;
  border-top: 10px solid transparent;
  border-bottom: 10px solid transparent;
  border-right: 10px solid #fff8e1;
}

.box:hover .product-popup {
  opacity: 1;
  pointer-events: auto;
}

.product-popup h4 {
  font-size: 16px;
  margin-bottom: 10px;
  color: #e65100;
  text-align: left;
}

.product-popup ul {
  list-style-type: disc;
  padding-left: 20px;
  margin: 0 0 10px;
  text-align: left;
}

.product-popup ul li {
  font-size: 14px;
  color: #333;
  margin-bottom: 6px;
}

.add-to-cart-btn,
.btn.cart {
  display: inline-block;
  background-color: #ff9800;
  color: white;
  padding: 8px 16px;
  border: none;
  border-radius: 8px;
  text-decoration: none;
  cursor: pointer;
  font-weight: 600;
  transition: background-color 0.3s ease;
}

.add-to-cart-btn:hover,
.btn.cart:hover {
  background-color: #f57c00;
}

/* ....................................................................................... */
/* This handles default popup */

.popup-left .product-popup {
  left: auto;
  right: 105%;
}

.popup-left .product-popup::before {
  left: auto;
  right: -10px;
  border-left: 10px solid #fff8e1;
  border-right: none;
}

/* .......................................................................... */

/* For Down */

/*
.product-popup {
  position: absolute;
  top: 100%;
  left: 0;
  width: 100%;
  background-color: #fff8e1;
  border-radius: 10px;
  padding: 15px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.08);
  opacity: 0;
  max-height: 0;
  overflow: hidden;
  transition: all 0.4s ease;
  z-index: 99;
}

.box:hover .product-popup {
  opacity: 1;
  max-height: 500px;
}

.product-popup::before {
  content: '';
  position: absolute;
  top: -10px;
  left: 30px;
  border-left: 10px solid transparent;
  border-right: 10px solid transparent;
  border-bottom: 10px solid #fff8e1;
}

.box.popup-left .product-popup {
  left: auto;
  right: 0;
}

.box.popup-left .product-popup::before {
  left: auto;
  right: 30px;
} */
</style>
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