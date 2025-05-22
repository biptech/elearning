<?php 
session_start(); // Important: Start session at the very top!

include '../includes/config.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php';

// Initialize variables
$u_name = $u_address = $u_email = $u_phone = $u_gender = '';
$errors = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    function sanitizeInput($data) {
        return trim(htmlspecialchars($data));
    }

    $u_name = sanitizeInput($_POST['u_name']);
    $u_address = sanitizeInput($_POST['u_address']);
    $u_email = sanitizeInput($_POST['u_email']);
    $u_phone = sanitizeInput($_POST['u_phone']);
    $u_gender = sanitizeInput($_POST['u_gender']);
    $u_password = $_POST['u_password'];
    $u_cpassword = $_POST['u_cpassword'];

    $u_image = $_FILES['u_image'];
    $image_name = '';
    $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

    if ($u_image['error'] === 0) {
        $image_tmp = $u_image['tmp_name'];
        $image_size = $u_image['size'];
        $image_ext = strtolower(pathinfo($u_image['name'], PATHINFO_EXTENSION));

        if (!in_array($image_ext, $allowed_ext)) {
            $errors[] = "Only JPG, JPEG, PNG, and GIF files are allowed.";
        } elseif ($image_size > 2 * 1024 * 1024) {
            $errors[] = "Image size must be less than 2MB.";
        } else {
            $image_name = uniqid("IMG_", true) . "." . $image_ext;
            $upload_path = "../uploads/images/" . $image_name;
            move_uploaded_file($image_tmp, $upload_path);
        }
    }

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

    if (empty($errors)) {
        $hashed_password = password_hash($u_password, PASSWORD_DEFAULT);
        $verify_token = bin2hex(random_bytes(16)); // Token for link verification
        $verify_code = rand(100000, 999999);        // 6-digit manual code verification

        // Insert into database
        $stmt = mysqli_prepare($con, "INSERT INTO user_signup (u_name, u_address, u_email, u_phone, u_gender, u_password, u_image, verify_token, is_verified) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0)");
        mysqli_stmt_bind_param($stmt, "ssssssss", $u_name, $u_address, $u_email, $u_phone, $u_gender, $hashed_password, $image_name, $verify_token);

        if (mysqli_stmt_execute($stmt)) {
            $verify_link = "http://localhost/elearning/public/verify.php?token=$verify_token";

            $mail = new PHPMailer(true);

            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'bipinchapai2059@gmail.com'; 
                $mail->Password   = 'opkd dklo rzuo ldtc';     
                $mail->SMTPSecure = 'tls';
                $mail->Port       = 587;

                // Recipients
                $mail->setFrom('bipinchapai2059@gmail.com', 'Bipin Chapai');
                $mail->addAddress($u_email, $u_name);

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Verify Your Email';
                $mail->Body    = "
                    <h1>Email Verification</h1>
                    <p>Hi <b>$u_name</b>,</p>
                    <p>Please verify your email by clicking the link below:</p>
                    <p><a href='$verify_link'>Verify Email</a></p>
                    <hr>
                    <p>Or use this 6-digit verification code:</p>
                    <h2>$verify_code</h2>
                ";

                $mail->send();

                // Store verification code in session
                $_SESSION['email_verify_code'] = $verify_code;
                $_SESSION['email_to_verify'] = $u_email;

                echo '<script>
                        alert("Sign Up Successful! Please check your email to verify your account.");
                        window.location.href = "verify.php";
                      </script>';
                exit();

            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            echo "Database Error: " . mysqli_error($con);
        }

        mysqli_stmt_close($stmt);
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
    <link rel="stylesheet" href="../css/signup.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<?php include '../includes/header.php'; ?>

<div class="signup-body">
<form action="signup.php" class="sign-up" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
        <div class="sign-up-form">
            <p class="signup-txt"> <i class="fa fa-user-plus" aria-hidden="true"></i> Sign Up</p>
            <div class="s-inputs">

<div class="profile-upload-container">
    <label for="u_image" class="profile-label">
<div id="preview-image" class="profile-img no-img">
    <span class="profile-letter">+</span>
</div>        <input type="file" name="u_image" id="u_image" accept="image/*" style="display: none;" onchange="loadPreview(this)">
    </label>
</div>

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

                <div class="input-container password-container">
    <input type="password" name="u_password" id="u_password" placeholder="New Password" required>
    <i class="fa-solid fa-eye toggle-password" toggle="#u_password"></i>
    <div id="error_u_password" class="error"></div>
</div>
<div class="input-container password-container">
    <input type="password" name="u_cpassword" id="u_cpassword" placeholder="Confirm Password" required>
    <i class="fa-solid fa-eye toggle-password" toggle="#u_cpassword"></i>
    <div id="error_u_cpassword" class="error"></div>
</div>
            </div>
            
            <input type="submit" name="submit" class="submit" value="Sign Up">
            <p><a href="login.php">Already have an account?</a></p>
        </div>
        <div class="sign-up-term-condition-text">
            <p>By creating an account, you agree to our <a href="#">terms and conditions</a>.</p>
        </div>
    </form>
</div>

<?php include '../includes/footer.php'; ?>

<script src="../js/form_validation.js"></script>

<script>
// Preview uploaded image
function loadPreview(input) {
    var file = input.files[0];
    if (file) {
        var reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('preview-image');
            preview.innerHTML = '<img src="' + e.target.result + '" alt="Profile Preview">';
        };
        reader.readAsDataURL(file);
    }
}

// Toggle password visibility
document.querySelectorAll('.toggle-password').forEach(function (eyeIcon) {
    eyeIcon.addEventListener('click', function () {
        const input = document.querySelector(this.getAttribute('toggle'));
        const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
        input.setAttribute('type', type);
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });
});
</script>

</body>
</html>