<?php 
include('../includes/config.php');
$msg = "";
$error = "";

// Fetch categories for dropdown
$categories_result = mysqli_query($con, "SELECT id, CategoryName FROM tblcategory WHERE Is_Active = 1");

// Handle user cookie
if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
} else {
    setcookie('user_id', uniqid(), time() + 60 * 60 * 24 * 30, '/', '', true, true);
}

// Handle form submission
if (isset($_POST['submit'])) {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $price = filter_var($_POST['price'], FILTER_SANITIZE_STRING);
    $category_id = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
    $details = filter_var($_POST['description'], FILTER_SANITIZE_STRING);

    // Check description length
    if (strlen($details) > 500) {
        $error = 'Description must be 500 characters or fewer!';
    } else {
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
                $add_product = $con->prepare("INSERT INTO products (name, details, price, category_id, image) VALUES (?, ?, ?, ?, ?)");
                $add_product->bind_param("ssdis", $name, $details, $price, $category_id, $rename);
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
}

// Fetch all products for display
$products_result = mysqli_query($con, "SELECT p.*, c.CategoryName FROM products p LEFT JOIN tblcategory c ON p.category_id = c.id ORDER BY p.id DESC");

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Add & View Products</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        color: #333;
        margin: 0; padding: 0;
    }
    .container {
        max-width: 900px;
        margin: 40px auto;
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 12px rgba(0,0,0,0.1);
    }
    h2 {
        text-align: center;
        margin-bottom: 25px;
    }
    form {
        margin-bottom: 50px;
    }
    label {
        display: block;
        margin-bottom: 6px;
        font-weight: bold;
    }
    input[type="text"],
    input[type="number"],
    select,
    textarea,
    input[type="file"] {
        width: 100%;
        padding: 8px 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 1rem;
    }
    textarea {
        resize: vertical;
    }
    button {
        padding: 12px 20px;
        background-color: #28a745;
        border: none;
        border-radius: 5px;
        color: white;
        font-size: 1.1rem;
        cursor: pointer;
    }
    button:hover {
        background-color: #218838;
    }
    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 5px;
    }
    .alert-success {
        background-color: #d4edda;
        color: #155724;
    }
    .alert-error {
        background-color: #f8d7da;
        color: #721c24;
    }
    .char-count {
        font-size: 0.9rem;
        color: #555;
        margin-top: -12px;
        margin-bottom: 15px;
        text-align: right;
    }
</style>
</head>
<body>
<?php include('includes/topheader.php'); ?>
<?php include('includes/leftsidebar.php'); ?>
<div class="container">
    <h2>Add Product</h2>

    <?php if ($msg): ?>
        <div class="alert alert-success"><?php echo htmlentities($msg); ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-error"><?php echo htmlentities($error); ?></div>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data">
        <label for="name">Product Name:</label>
        <input type="text" name="name" id="name" required value="<?php echo isset($_POST['name']) ? htmlentities($_POST['name']) : ''; ?>" />

        <label for="price">Price:</label>
        <input type="number" name="price" id="price" required step="0.01" value="<?php echo isset($_POST['price']) ? htmlentities($_POST['price']) : ''; ?>" />

        <label for="category">Category:</label>
        <select name="category" id="category" required>
            <option value="">Select Category</option>
            <?php
            mysqli_data_seek($categories_result, 0);
            while ($cat = mysqli_fetch_assoc($categories_result)) {
                $selected = (isset($_POST['category']) && $_POST['category'] == $cat['id']) ? 'selected' : '';
                echo "<option value='{$cat['id']}' $selected>" . htmlentities($cat['CategoryName']) . "</option>";
            }
            ?>
        </select>

        <label for="description">What will students learn in your course?</label>
        <textarea name="description" id="description" placeholder="Write each point on a new line"rows="5" maxlength="500" required oninput="updateCharCount()"><?php echo isset($_POST['description']) ? htmlentities($_POST['description']) : ''; ?></textarea>
        <div class="char-count"><span id="charCount">0</span>/500 characters</div>

        <label for="image">Feature Image:</label>
        <input type="file" name="image" id="image" accept="image/png, image/jpeg, image/jpg" required />

        <button type="submit" name="submit">Add Product</button>
    </form>
</div>
<script>
function updateCharCount() {
    var textarea = document.getElementById("description");
    var count = textarea.value.length;
    document.getElementById("charCount").innerText = count;
}
window.onload = updateCharCount;
</script>
<?php include('includes/footer.php'); ?>
</body>
</html>
