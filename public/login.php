<?php
session_start();

include '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $u_email = mysqli_real_escape_string($con, $_POST['u_email']);  // Sanitize user input
    $u_password = $_POST['u_password'];

    $qry = "SELECT u_id, u_password FROM user_signup WHERE u_email = '$u_email'";
    $result = mysqli_query($con, $qry);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);

        if (password_verify($u_password, $row['u_password'])) {
            $_SESSION['loggedin'] = true;
            $_SESSION['u_id'] = $row['u_id']; 
            $_SESSION['username'] = $u_email;
            
            $redirect_to = isset($_SESSION['redirect_to']) ? $_SESSION['redirect_to'] : 'index.php';
            echo '<script type="text/javascript"> alert("Logged in Successfully!"); window.location.assign("' . $redirect_to . '");</script>';
            exit();
        } else {
            echo '<script type="text/javascript"> alert("Invalid Credentials!");</script>';
        }
    } else {
        echo '<script type="text/javascript"> alert("User not found!");</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css\sign-up.css">
    <script src="https://kit.fontawesome.com/ae61999827.js"></script>
    <title>Login</title>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="login-body">
        <form action="login.php" class="login-form" method="POST">
            <p class="login-txt"><i class="fa-solid fa-right-to-bracket"></i> LOG IN</p>
            <div class="l-inputs">
                <input type="text" name="u_email" placeholder="USERNAME" required>
            </div>
            <div class="login-password-box">
                <input type="password" name="u_password" placeholder="PASSWORD" id="loginpassword" required>
            </div>
            <div class="l-inputs">
                <input type="submit" name="submit" value="Login">
            </div>

            <p><a href="get-email-update-password.php" class="e-option"> FORGET PASSWORD?</a></p>
            <p><a href="sign-up.php" class="e-option ">CREATE NEW ACCOUNT</a></p>
        </form>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>