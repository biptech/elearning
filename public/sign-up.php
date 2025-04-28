<?php 
include '../includes/config.php';

// Initialize variables
$u_name = $u_address = $u_email = $u_phone = $u_gender = $u_password = '';
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
    $u_gender = $_POST['u_gender'];
    $u_password = $_POST['u_password'];
    $u_cpassword = $_POST['u_cpassword'];

    // Image upload handling
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

    } else {
        $image_name = ''; 
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

    // If valid, insert into database
    if (empty($errors)) {
        $hashed_password = password_hash($u_password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO user_signup (u_name, u_address, u_email, u_phone, u_gender, u_password, u_image)
                VALUES ('$u_name', '$u_address', '$u_email', '$u_phone', '$u_gender', '$hashed_password', '$image_name')";

        if (mysqli_query($con, $sql)) {
            echo '<script type="text/javascript">
                    alert("Sign Up Successfully!\nNow Log in With Your Email and Password");
                    window.location.assign("login.php");
                  </script>';
            exit();
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
    <link rel="stylesheet" href="../css/signup.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<?php include '../includes/header.php'; ?>

<div class="signup-body">
<form action="sign-up.php" class="sign-up" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
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
<script>
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

<script src="../js/valid.js"></script>
<?php include '../includes/footer.php'; ?>
</body>
</html>
