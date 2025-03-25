<?php 
include '../includes/config.php';

// Initialize variables to store user input and errors
$u_name = $u_address = $u_email = $u_phone = $u_gender = $u_password = '';
$errors = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Function to sanitize input data
    function sanitizeInput($data) {
        return trim(htmlspecialchars($data));
    }

    // Sanitize and validate each input field
    $u_name = sanitizeInput($_POST['u_name']);
    $u_address = sanitizeInput($_POST['u_address']);
    $u_email = sanitizeInput($_POST['u_email']);
    $u_phone = sanitizeInput($_POST['u_phone']);
    $u_gender = $_POST['u_gender'];
    $u_password = $_POST['u_password'];
    $u_cpassword = $_POST['u_cpassword'];

    // Validation
    if (empty($u_name)) {
        $errors[] = "Full Name is required.";
    }
    if (empty($u_email) || !filter_var($u_email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid Email is required.";
    }
    if (empty($u_phone) || !preg_match('/^(98|97)\d{8}$/', $u_phone)) {
        $errors[] = "Phone number must start with 98 or 97 and be exactly 10 digits.";
    }
    if (empty($u_gender)) {
        $errors[] = "Please select your gender.";
    }
    if (empty($u_password) || !preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,}$/', $u_password)) {
        $errors[] = "Password must contain at least one lowercase, one uppercase, one digit, one special character, and be at least 6 characters long.";
    }
    if ($u_password !== $u_cpassword) {
        $errors[] = "Passwords do not match.";
    }

    // If no errors, proceed to insert into database
    if (empty($errors)) {
        // Hash the password before storing it
        $hashed_password = password_hash($u_password, PASSWORD_DEFAULT);
    
        $sql = "INSERT INTO user_signup (u_name, u_address, u_email, u_phone, u_gender, u_password)
                VALUES ('$u_name', '$u_address', '$u_email', '$u_phone', '$u_gender', '$hashed_password')";
    
        if (mysqli_query($con, $sql)) {
            // Display success message and redirect
            echo '<script type="text/javascript">
                    alert("Sign Up Successfully!\nNow Log in With Your Email and Password");
                    window.location.assign("login.php");
                  </script>';
            exit(); // Prevent further execution of PHP code
        } else {
            echo "Error: " . mysqli_error($con);
        }
    }    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <script src="js/main.js"></script>
    <link rel="stylesheet" href="../css/sign-up.css">
    <style>
        .input-container { position: relative; margin-bottom: 20px; }
        .error { color: red; font-size: 14px; position: absolute; top: -20px; width: 100%; text-align: center; }
    </style>
</head>
<body>

<?php include '../includes/header.php'; ?>

<div class="signup-body">
    <form action="sign-up.php" class="sign-up" method="POST" onsubmit="return validateForm()">
        <div class="sign-up-form">
            <p class="signup-txt"> <i class="fa fa-user-plus" aria-hidden="true"></i>
            Sign Up</p>
            <div class="s-inputs">
                <div class="input-container">
                    <input type="text" name="u_name" id="u_name" placeholder="Full Name" value="<?= htmlspecialchars($u_name); ?>" required>
                    <div id="error_u_name" class="error"></div>
                </div>
                <div class="input-container">
                    <input type="text" name="u_address" id="u_address" placeholder="Address" value="<?= htmlspecialchars($u_address); ?>" required>
                    <div id="error_u_address" class="error"></div>
                </div>
                <div class="input-container">
                    <input type="text" name="u_email" id="u_email" placeholder="Email" value="<?= htmlspecialchars($u_email); ?>" required>
                    <div id="error_u_email" class="error"></div>
                </div>
                <div class="input-container">
                    <input type="text" name="u_phone" id="u_phone" placeholder="Phone Number" value="<?= htmlspecialchars($u_phone); ?>" required>
                    <div id="error_u_phone" class="error"></div>
                </div>
                <div class="input-container">
                    <select name="u_gender" id="u_gender" required>
                        <option value="">Select Gender</option>
                        <option value="Male" <?= $u_gender === 'Male' ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?= $u_gender === 'Female' ? 'selected' : ''; ?>>Female</option>
                        <option value="Other" <?= $u_gender === 'Other' ? 'selected' : ''; ?>>Other</option>
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

<script src="../js/validation.js"></script>

<?php include '../includes/footer.php'; ?>
</body>
</html>