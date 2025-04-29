<?php
session_start();
include '../includes/config.php';

if (isset($_SESSION['u_id'])) {
    header("Location: " . ($_SESSION['redirect_to'] ?? 'index.php'));
    exit();
}

if (isset($_GET['token'])) {
    $token = mysqli_real_escape_string($con, $_GET['token']);
} elseif (isset($_POST['verify_token'])) {
    $token = mysqli_real_escape_string($con, $_POST['verify_token']);
} else {
    $token = '';
}

if ($token) {
    $stmt = $con->prepare("SELECT u_id FROM user_signup WHERE verify_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $u_id = $row['u_id'];

        // Update user as verified
        $update_stmt = $con->prepare("UPDATE user_signup SET is_verified = 1, verify_token = NULL WHERE u_id = ?");
        $update_stmt->bind_param("i", $u_id);
        $update_stmt->execute();

        echo '<script>alert("Email Verified Successfully! Please Login."); window.location.href="login.php";</script>';
        exit();
    } else {
        echo '<script>alert("Invalid or expired verification code.");</script>';
    }
}

$errors = [];

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($con, trim($_POST['u_email']));
    $password = mysqli_real_escape_string($con, trim($_POST['u_password']));

    $check_user = mysqli_query($con, "SELECT * FROM user_signup WHERE u_email = '$email' LIMIT 1");

    if (mysqli_num_rows($check_user) > 0) {
        $user = mysqli_fetch_assoc($check_user);

        if ($user['is_verified'] == 0) {
            $errors[] = "Please verify your email first!";
        } elseif (password_verify($password, $user['u_password'])) {
            // Password is correct and email is verified
            $_SESSION['u_id'] = $user['u_id'];
            $_SESSION['u_email'] = $user['u_email'];
            $_SESSION['u_name'] = $user['u_name'];

            $redirect_to = $_SESSION['redirect_to'] ?? 'index.php';

            echo '<script>
                    alert("Logged in Successfully!");
                    window.location.href = "' . $redirect_to . '";
                  </script>';
            exit();
        } else {
            echo '<script>alert("Invalid Credentials!");</script>';
        }
    } else {
        echo '<script>alert("User not found!");</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <link rel="stylesheet" href="../css/signup.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .error-messages {
            color: white;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            text-align: center;
        }
        .error-messages p {
            margin: 0;
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="login-body">
        <form action="login.php" method="POST" class="login-form">
            <p class="login-txt"><i class="fa-solid fa-right-to-bracket"></i> LOG IN</p>

            <?php if (!empty($errors)): ?>
                <div class="error-messages">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="l-inputs">
                <input type="text" name="u_email" placeholder="Email" required />
            </div>
            <div class="login-password-box password-container">
                <input type="password" name="u_password" id="loginpassword" placeholder="Password" required />
                <i class="fa-solid fa-eye" onclick="togglePassword('loginpassword', this)"></i>
            </div>

            <div class="l-inputs">
                <input type="submit" name="submit" value="Login" />
            </div>

            <p><a href="get-email-update-password.php" class="e-option">Forgot Password?</a></p>
            <p><a href="signup.php" class="e-option">Create New Account</a></p>
        </form>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script>
        function togglePassword(id, icon) {
            const field = document.getElementById(id);
            if (field.type === "password") {
                field.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                field.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }
    </script>
</body>
</html>
