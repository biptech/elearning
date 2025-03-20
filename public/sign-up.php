<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
</head>
<body>
<div class="signup-body">
    <form action="#" class="sign-up" method="POST" onsubmit="return validateForm()">
        <div class="sign-up-form">
            <p class="signup-txt"> <i class="fa fa-user-plus" aria-hidden="true"></i>
            Sign Up</p>
            <div class="s-inputs">
                <div class="input-container">
                    <input type="text" name="u_name" id="u_name" placeholder="Full Name" required>
                    <div id="error_u_name" class="error"></div>
                </div>
                <div class="input-container">
                    <input type="text" name="u_address" id="u_address" placeholder="Address" required>
                    <div id="error_u_address" class="error"></div>
                </div>
                <div class="input-container">
                    <input type="text" name="u_email" id="u_email" placeholder="Email" required>
                    <div id="error_u_email" class="error"></div>
                </div>
                <div class="input-container">
                    <input type="text" name="u_phone" id="u_phone" placeholder="Phone Number" required>
                    <div id="error_u_phone" class="error"></div>
                </div>
                <div class="input-container">
                    <select name="u_gender" id="u_gender" required>
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                    <div id="error_u_gender" class="error"></div>
                </div>
                <div class="input-container">
                    <input type="password" name="u_password" id="u_password" placeholder="New Password" required>
                    <div id="error_u_password" class="error"></div>
                </div>
                <div class="input-container">
                    <input type="password" name="u_cpassword" id="u_cpassword" placeholder="Confirm Password" required>
                    <div id="error_u_cpassword" class="error"></div>
                </div>
            </div>
            <input type="submit" name="submit" value="Sign Up">
            <p><a href="login.php">Already have an account?</a></p>
        </div>
        <div class="sign-up-term-condition-text">
            <p>By creating an account, you agree to our <a href="#">terms and conditions</a>.</p>
        </div>
    </form>
</div>
</body>
</html>