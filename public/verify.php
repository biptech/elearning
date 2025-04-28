<?php
session_start();
include '../includes/config.php';

if (!isset($_SESSION['email_to_verify'])) {
    header("Location: signup.php");
    exit();
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entered_code = trim($_POST['verify_code']);
    $email = $_SESSION['email_to_verify'];
    $actual_code = $_SESSION['email_verify_code'];

    if ($entered_code == $actual_code) {
        // Update user to verified
        $update = mysqli_query($con, "UPDATE user_signup SET is_verified = 1 WHERE u_email = '$email'");

        if ($update) {
            unset($_SESSION['email_verify_code']);
            unset($_SESSION['email_to_verify']);

            echo '<script>
                    alert("Email verified successfully! You can now log in.");
                    window.location.href = "login.php";
                  </script>';
            exit();
        } else {
            $errors[] = "Failed to verify account. Try again later.";
        }
    } else {
        $errors[] = "Incorrect verification code. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify Email</title>
    <link rel="stylesheet" href="../css/signup.css">
</head>
<body>

<?php include '../includes/header.php'; ?>

<div class="signup-body">
    <form action="verify.php" method="POST" class="sign-up">
        <div class="sign-up-form">
            <p class="signup-txt"><i class="fa fa-envelope" aria-hidden="true"></i> Email Verification</p>

            <?php if (!empty($errors)): ?>
                <div class="error-messages">
                    <?php foreach ($errors as $error): ?>
                        <p><?= htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="input-container">
                <input type="text" name="verify_code" placeholder="Enter 6-digit Verification Code" required maxlength="6">
            </div>

            <input type="submit" class="submit" value="Verify Email">
        </div>
    </form>
</div>

<?php include '../includes/footer.php'; ?>

</body>
</html>
