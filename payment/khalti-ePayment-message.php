<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <?php
    session_start();
    $message = "Your payment has been successfully processed. Thank you for shopping with us."; // Default message
    if (isset($_SESSION['transaction_msg'])) {
        $message = $_SESSION['transaction_msg'];
        unset($_SESSION['transaction_msg']);
    } else {
        header("Location: checkout.php"); // Redirect if no transaction message
        exit();
    }
    ?>

    <script>
        Swal.fire({
            icon: 'success',
            title: 'Payment Successful',
            text: '<?= htmlspecialchars($message); ?>',
            confirmButtonText: 'OK',
            confirmButtonColor: '#3085d6'
        });
    </script>

    <div class="mt-5 d-flex justify-content-center">
        <div class="mb-3">
            <img src="assets/images/payment-success.jpg" class="img-fluid" alt="Payment Successful">
            <div class="card">
                <div class="card-body text-white bg-success">
                    <h5 class="card-title">Dear Customer,</h5>
                    <p class="card-text">
                        <?= htmlspecialchars($message); ?>
                    </p>
                </div>
                <div class="card-footer">
                    <a href="checkout.php" class="btn btn-primary">Back to Checkout</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>