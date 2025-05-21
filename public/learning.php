<?php
session_start();
include('../includes/config.php');

// Fetch all orders with payment_status = 'completed'
$stmt = $con->prepare("SELECT * FROM orders WHERE payment_status = 'completed' ORDER BY order_date DESC");
$stmt->execute();
$result = $stmt->get_result();
$completedOrders = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>My Learning - Completed Payments</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
</head>
<body>

<div class="container mt-5">
    <h2 class="mb-4">My Learning - Completed Payments</h2>

    <?php if (count($completedOrders) > 0): ?>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Total Price (NPR)</th>
                    <th>Order Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($completedOrders as $order): ?>
                    <tr>
                        <td><?= htmlspecialchars($order['id']) ?></td>
                        <td><?= htmlspecialchars($order['customer_name']) ?></td>
                        <td><?= htmlspecialchars($order['product_name']) ?></td>
                        <td><?= htmlspecialchars($order['quantity']) ?></td>
                        <td><?= number_format($order['total_price'], 2) ?></td>
                        <td><?= htmlspecialchars($order['order_date']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">No completed payments found.</div>
    <?php endif; ?>

</div>

</body>
</html>