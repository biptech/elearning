<?php
session_start();
include('../includes/config.php');

if (!isset($_SESSION['u_id'])) {
    http_response_code(403);
    echo "Not logged in.";
    exit();
}

$user_id = $_SESSION['u_id'];

try {
    $conn = new PDO("mysql:host=$server;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Handle Add to Cart
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'], $_POST['product_id'])) {
        $pid = intval($_POST['product_id']);

        $stmt = $conn->prepare("SELECT id FROM products WHERE id = ?");
        $stmt->execute([$pid]);

        if ($stmt->rowCount() === 0) {
            http_response_code(400);
            echo "Invalid product.";
            exit();
        }

        $cart_check = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND pid = ?");
        $cart_check->execute([$user_id, $pid]);

        if ($cart_check->rowCount() === 0) {
            $insert = $conn->prepare("INSERT INTO cart (user_id, pid) VALUES (?, ?)");
            $insert->execute([$user_id, $pid]);
        }

        echo "Added";
        exit();
    }

    // Fetch Cart Items
    $stmt = $conn->prepare("SELECT c.pid, p.name, p.price, p.image FROM cart c JOIN products p ON c.pid = p.id WHERE c.user_id = ?");
    $stmt->execute([$user_id]);
    $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Cart</title>
    <style>
        h1 {
            text-align: center;
            color: rgb(248, 189, 51);
            margin-top: 40px;
        }
        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }
        th, td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #0077cc;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .cart-product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }
        .btn-remove {
            padding: 8px 12px;
            background-color: #e74c3c;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn-remove:hover {
            background-color: #c0392b;
        }
        .btn-checkout {
            display: block;
            width: fit-content;
            margin: 30px auto;
            padding: 12px 24px;
            background-color: #27ae60;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .btn-checkout:hover {
            background-color: #1e8449;
        }
        p {
            text-align: center;
            font-size: 18px;
        }
        a {
            color: #0077cc;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        @media screen and (max-width: 768px) {
            table, thead, tbody, th, td, tr {
                display: block;
                width: 100%;
            }
            th {
                display: none;
            }
            td {
                text-align: right;
                padding-left: 50%;
                position: relative;
            }
            td::before {
                content: attr(data-label);
                position: absolute;
                left: 10px;
                width: 45%;
                padding-left: 15px;
                font-weight: bold;
                text-align: left;
            }
            img {
                width: 50px;
                height: 50px;
            }
        }
    </style>
</head>
<body>

<?php include('../includes/header.php'); ?>

<h1>Shopping Cart</h1>

<?php if (empty($cart_items)): ?>
    <p>Your cart is empty.</p>
    <p><a href="view_products.php">Browse products</a> to add items to your cart.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Price (Rs.)</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $total = 0;
            foreach ($cart_items as $item):
                $total += $item['price'];
            ?>
                <tr id="cart-row-<?= $item['pid'] ?>">
                    <td data-label="Image">
                        <img class="cart-product-image" src="../admin/uploaded_files/<?= htmlentities($item['image']) ?>" alt="<?= htmlentities($item['name']) ?>" onerror="this.src='../images/default.png'">
                    </td>
                    <td data-label="Name"><?= htmlentities($item['name']) ?></td>
                    <td data-label="Price (Rs.)"><?= number_format($item['price'], 2) ?></td>
                    <td data-label="Action">
                        <button class="btn-remove" onclick="removeFromCart(<?= $item['pid'] ?>)">Remove</button>
                    </td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="2" style="text-align: right; font-weight: bold;">Total:</td>
                <td colspan="2" style="font-weight: bold;">Rs. <?= number_format($total, 2) ?></td>
            </tr>
        </tbody>
    </table>
    <a href="../payment/checkout.php" class="btn-checkout">Proceed to Checkout</a>
<?php endif; ?>

<?php include('../includes/footer.php'); ?>

<script>
function removeFromCart(productId) {
    if (!confirm("Remove this item from your cart?")) return;

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "remove_cart.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function () {
        if (xhr.status === 200) {
            const row = document.getElementById('cart-row-' + productId);
            if (row) row.remove();
            alert('Item removed from cart.');
            location.reload();
        } else {
            alert("Error removing item.");
        }
    };

    xhr.send("product_id=" + productId);
}
</script>

</body>
</html>