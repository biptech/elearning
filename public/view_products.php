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
        <h1 id="ourcources">
            <span>SHOP NOW <br> &darr;</span>
        </h1>
        <div class="row">
            <?php
            $select_products = $conn->prepare("SELECT * FROM products");
            if ($select_products->execute()) {
                if ($select_products->rowCount() > 0) {
                    while ($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)) {
                        $productId = htmlentities($fetch_product['id']);
                        $productImage = htmlentities($fetch_product['image'] ?? '');
                        $productName = htmlentities($fetch_product['name'] ?? 'Unknown Product');
                        $productPrice = htmlentities($fetch_product['price'] ?? '0');
            ?>
            <a href="product_detail.php?id=<?= $productId; ?>" class="box product-link">
                <img src="../admin/uploaded_files/<?= $productImage; ?>" class="image" alt="<?= $productName; ?>">
                <h3 class="name"><?= $productName; ?></h3>
                <p class="price">&#8360; <?= $productPrice; ?></p>
            </a>
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