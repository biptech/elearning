<?php
session_start();
include '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u_email = mysqli_real_escape_string($con, $_POST['u_email']);
    $u_password = $_POST['u_password'];

    $qry = "SELECT u_id, u_password FROM user_signup WHERE u_email = '$u_email'";
    $result = mysqli_query($con, $qry);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        if (password_verify($u_password, $row['u_password'])) {
            $_SESSION['loggedin'] = true;
            $_SESSION['u_id'] = $row['u_id'];
            $_SESSION['username'] = $u_email;

            $redirect_to = $_SESSION['redirect_to'] ?? 'index.php';
            echo '<script>alert("Logged in Successfully!"); window.location.href="' . $redirect_to . '";</script>';
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
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="login-body">
        <form action="login.php" method="POST" class="login-form">
            <p class="login-txt"><i class="fa-solid fa-right-to-bracket"></i> LOG IN</p>
            
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
            <p><a href="sign-up.php" class="e-option">Create New Account</a></p>
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
