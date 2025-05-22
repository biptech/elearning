<?php
session_start();
include('../includes/config.php');

// Message
$transactionMsg = $_SESSION['transaction_msg'] ?? '';
unset($_SESSION['transaction_msg']);

// Get order ID from session
$order_id = $_SESSION['order_id'] ?? null;
unset($_SESSION['order_id']);

// Fetch order
$order = null;
if ($order_id) {
    $stmt = $con->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $order = $result->fetch_assoc();
    $stmt->close();
}

// Fetch order items (if any)
$order_items = [];
if ($order_id) {
    $stmt_items = $con->prepare("SELECT * FROM order_items WHERE order_id = ?");
    $stmt_items->bind_param("i", $order_id);
    $stmt_items->execute();
    $result_items = $stmt_items->get_result();
    while ($item = $result_items->fetch_assoc()) {
        $order_items[] = $item;
    }
    $stmt_items->close();
}

// Service charge
$serviceCharge = 5.65;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Confirmation</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<?php if ($transactionMsg): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Payment Successful',
            text: <?= json_encode($transactionMsg) ?>,
            confirmButtonColor: '#3085d6'
        });
    </script>
<?php endif; ?>

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-success text-white">
            <h4 class="mb-0">Payment Successful</h4>
        </div>
        <div class="card-body">
            <?php if ($order): ?>
                <p>Thank you! Your payment was processed successfully.</p>
                <h5 class="mt-4">Order Details</h5>
                <p><strong>Order ID:</strong> <?= htmlspecialchars($order['id']) ?></p>
                <p><strong>Payment Status:</strong> <?= ucfirst($order['payment_status']) ?></p>

                <?php if (!empty($order_items)): ?>
                    <table class="table table-bordered mt-3">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th>Price (NPR)</th>
                                <th>Quantity</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $subTotal = 0;
                            foreach ($order_items as $index => $item):
                                $lineTotal = $item['price'] * $item['qty'];
                                $subTotal += $lineTotal;
                            ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= htmlspecialchars($item['product_name']) ?></td>
                                <td><?= number_format($item['price'], 2) ?></td>
                                <td><?= $item['qty'] ?></td>
                                <td><?= number_format($lineTotal, 2) ?></td>
                            </tr>
                            <?php endforeach; ?>
                            <tr>
                                <td colspan="4" class="text-end"><strong>Subtotal:</strong></td>
                                <td><?= number_format($subTotal, 2) ?></td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end"><strong>Service Charge:</strong></td>
                                <td><?= number_format($serviceCharge, 2) ?></td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end"><strong>Total:</strong></td>
                                <td><?= number_format($subTotal + $serviceCharge, 2) ?></td>
                            </tr>
                        </tbody>
                    </table>
                <?php else: ?>
                <?php endif; ?>

            <?php else: ?>
            <?php endif; ?>
        </div>
        <div class="card-footer text-center">
            <a href="../public/view_products.php" class="btn btn-primary">Back to Shop</a>
        </div>
    </div>
</div>

</body>
</html>
