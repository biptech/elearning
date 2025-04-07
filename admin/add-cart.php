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