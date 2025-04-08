<?php 

include('includes/config.php');
$msg = "";
$error = "";

if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
} else {
    setcookie('user_id', uniqid(), time() + 60 * 60 * 24 * 30, '/', '', true, true);
}

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $price = $_POST['price'];
    $price = filter_var($price, FILTER_SANITIZE_STRING);
    $details = "Product details placeholder";
    $image = $_FILES['image']['name'];
    $image = filter_var($image, FILTER_SANITIZE_STRING);
    $ext = pathinfo($image, PATHINFO_EXTENSION);
    $rename = uniqid() . '.' . $ext;
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_size = $_FILES['image']['size'];
    $upload_dir = 'uploaded_files/';

    if ($image_size > 2000000) {
        $error = 'Image size is too large!';
    } else {
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        $image_folder = $upload_dir . $rename;
        if (move_uploaded_file($image_tmp_name, $image_folder)) {
            $add_product = $con->prepare("INSERT INTO products (name, details, price, image) VALUES (?, ?, ?, ?)");
            $add_product->bind_param("ssss", $name, $details, $price, $rename);
            if ($add_product->execute()) {
                $msg = 'Product added successfully!';
            } else {
                $error = 'Failed to add product to the database!';
            }
        } else {
            $error = 'Failed to upload the image!';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
</head>
<body>

<?php include('includes/topheader.php'); ?>
<?php include('includes/leftsidebar.php'); ?>

<div class="main-container">
    <h2 class="page-heading">Add Product</h2>

    <?php if ($msg): ?>
        <div class="alert success-alert">
            <strong>Success:</strong> <?php echo htmlentities($msg); ?>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert error-alert">
            <strong>Error:</strong> <?php echo htmlentities($error); ?>
        </div>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data" class="product-form">
        <div class="form-group">
            <label for="itemName" class="form-label">Product Name:</label>
            <input type="text" id="itemName" name="name" placeholder="Enter product name" required class="form-input">
        </div>

        <div class="form-group">
            <label for="itemPrice" class="form-label">Price:</label>
            <input type="number" id="itemPrice" name="price" placeholder="Enter product price" required class="form-input">
        </div>

        <div class="form-group">
            <label for="postimage" class="form-label">Feature Image:</label>
            <input type="file" accept="image/jpg, image/png, image/jpeg" id="postimage" name="image" required class="form-input">
        </div>

        <div class="form-buttons">
            <button type="submit" name="submit" class="btn btn-submit">Add Product</button>
            <button type="reset" class="btn btn-reset">Discard</button>
        </div>
    </form>
</div>

<?php include('includes/footer.php'); ?>

</body>
</html>
