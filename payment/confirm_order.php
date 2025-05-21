<?php
session_start();
include('../includes/config.php');

// Check if the user is logged in
if (!isset($_SESSION['u_id'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

// Fetch user details for auto-filling
$u_id = $_SESSION['u_id']; // Get the user ID from the session
$query = "SELECT u_name, u_address, u_email, u_phone FROM user_signup WHERE u_id = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $u_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Assign user details to variables
$u_name = $user['u_name'] ?? '';
$u_address = $user['u_address'] ?? '';
$u_email = $user['u_email'] ?? '';
$u_phone = $user['u_phone'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Confirm Order</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include('../includes/header.php'); ?>

<div class="order-form">
    <h2>Confirm Your Order</h2>
    <form action="place_order.php" method="POST">
        <div class="form-group">
            <label for="name">Full Name:</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($u_name); ?>" required>
        </div>
        <div class="form-group">
            <label for="address">Address:</label>
            <textarea id="address" name="address" required><?= htmlspecialchars($u_address); ?></textarea>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($u_email); ?>" required>
        </div>
        <div class="form-group">
            <label for="phone">Phone Number:</label>
            <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($u_phone); ?>" required>
        </div>
        <button type="submit" name="confirm_order">Place Order</button>
    </form>
</div>

<?php include('../includes/footer.php'); ?>
</body>
</html>