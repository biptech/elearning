<?php
include('includes/config.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: manage-carts.php');
    exit;
}

$id = intval($_GET['id']);
$product = $con->query("SELECT * FROM products WHERE id = $id")->fetch_assoc();
$categories = $con->query("SELECT id, CategoryName FROM tblcategory WHERE Is_Active = 1");

$msg = "";
$error = "";

if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];
    $details = $_POST['details'];

    if (strlen($details) > 500) {
        $error = "Description must not exceed 500 characters.";
    } else {
        $new_image = $_FILES['image']['name'];
        if ($new_image != "") {
            $ext = pathinfo($new_image, PATHINFO_EXTENSION);
            $rename = uniqid() . '.' . $ext;
            $image_tmp = $_FILES['image']['tmp_name'];
            move_uploaded_file($image_tmp, "uploaded_files/" . $rename);
            $con->query("UPDATE products SET name='$name', price='$price', image='$rename', category_id='$category_id', details='$details' WHERE id=$id");
        } else {
            $con->query("UPDATE products SET name='$name', price='$price', category_id='$category_id', details='$details' WHERE id=$id");
        }

        $msg = "Product updated successfully!";
        $product = $con->query("SELECT * FROM products WHERE id = $id")->fetch_assoc();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; }
        .main-container { max-width: 800px; margin: 60px auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px #ccc; }
        .form-group { margin-bottom: 20px; }
        .form-label { font-weight: bold; }
        .form-input, .form-select, textarea { width: 100%; padding: 10px; border-radius: 4px; border: 1px solid #ddd; }
        .form-buttons { display: flex; justify-content: space-between; }
        .btn { padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; }
        .btn-update { background-color: #007bff; color: white; }
        .msg { background: #d4edda; padding: 10px; border-radius: 5px; color: #155724; }
        .error { background: #f8d7da; padding: 10px; border-radius: 5px; color: #721c24; }
        .char-count { font-size: 0.9em; color: #555; text-align: right; }
    </style>
</head>
<body>
<?php include('includes/topheader.php'); ?>
<?php include('includes/leftsidebar.php'); ?>
<div class="main-container">
    <h2>Edit Product</h2>
    <?php if ($msg): ?><div class="msg"><?= htmlentities($msg); ?></div><?php endif; ?>
    <?php if ($error): ?><div class="error"><?= htmlentities($error); ?></div><?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label class="form-label">Product Name:</label>
            <input type="text" name="name" value="<?= htmlentities($product['name']) ?>" required class="form-input">
        </div>
        <div class="form-group">
            <label class="form-label">Price:</label>
            <input type="number" name="price" value="<?= htmlentities($product['price']) ?>" required class="form-input">
        </div>
        <div class="form-group">
            <label class="form-label">Category:</label>
            <select name="category_id" class="form-select" required>
                <?php while ($cat = $categories->fetch_assoc()): ?>
                    <option value="<?= $cat['id'] ?>" <?= $product['category_id'] == $cat['id'] ? 'selected' : '' ?>>
                        <?= htmlentities($cat['CategoryName']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">What will students learn in your course?</label>
            <textarea name="details" id="details" class="form-input" rows="5" maxlength="500" oninput="updateCharCount()" placeholder="Enter each point on a new line"><?= htmlentities($product['details']) ?></textarea>
            <div class="char-count"><span id="charCount">0</span>/500 characters</div>
        </div>
        <div class="form-group">
            <label class="form-label">Image (optional):</label>
            <input type="file" name="image" class="form-input">
            <br><img src="uploaded_files/<?= htmlentities($product['image']) ?>" width="80">
        </div>
        <div class="form-buttons">
            <button type="submit" name="update" class="btn btn-update">Update Product</button>
        </div>
    </form>
</div>

<script>
function updateCharCount() {
    const textarea = document.getElementById("details");
    const charCount = document.getElementById("charCount");
    charCount.textContent = textarea.value.length;
}
window.onload = updateCharCount;
</script>

<?php include('includes/footer.php'); ?>
</body>
</html>
