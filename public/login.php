<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <div class="login-body">
        <form class="login-form" onsubmit="return validateLoginForm()">
            <p class="login-txt"><i class="fa-solid fa-right-to-bracket"></i> LOG IN</p>
            <div class="l-inputs">
                <input type="text" name="u_email" id="u_email" placeholder="USERNAME" required>
                <div id="error_u_email" class="error"></div>
            </div>
            <div class="login-password-box">
                <input type="password" name="u_password" id="u_password" placeholder="PASSWORD" required>
                <div id="error_u_password" class="error"></div>
            </div>
            <div class="l-inputs">
                <input type="submit" value="Login">
            </div>

            <p><a href="get-email-update-password.php" class="e-option"> FORGET PASSWORD?</a></p>
            <p><a href="sign-up.php" class="e-option">CREATE NEW ACCOUNT</a></p>
        </form>
    </div>
</body>
</html>