<?php 
session_start();
include('includes/config.php');
$msg = "";
$error = "";

// Session timeout check
if (!isset($_SESSION['last_activity'])) {
    $_SESSION['last_activity'] = time();
} elseif (time() - $_SESSION['last_activity'] > 900) {
    session_unset();
    session_destroy();
    header('location:index.php?timeout=true');
    exit;
}
$_SESSION['last_activity'] = time();

if (strlen($_SESSION['login']) == 0) {
    header('location:index.php');
    exit;
}

if (isset($_POST['submit'])) {
    $posttitle = $_POST['posttitle'];
    $posttitle = filter_var($posttitle, FILTER_SANITIZE_STRING);
    $catid = $_POST['category'];
    $catid = filter_var($catid, FILTER_SANITIZE_NUMBER_INT);
    $postdetails = $_POST['postdescription'];
    $postdetails = filter_var($postdetails, FILTER_SANITIZE_STRING);
    $postedby = $_SESSION['login'];
    $url = implode("-", explode(" ", $posttitle));

    // Image upload
    $image = $_FILES['postimage']['name'];
    $image_tmp_name = $_FILES['postimage']['tmp_name'];
    $image_size = $_FILES['postimage']['size'];
    $image_ext = pathinfo($image, PATHINFO_EXTENSION);
    $image_rename = uniqid() . '.' . $image_ext;
    $upload_dir = 'postimages/';

    if ($image_size > 2000000) {
        $error = 'Image size is too large!';
    } else {
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        // Move uploaded file
        if (move_uploaded_file($image_tmp_name, $upload_dir . $image_rename)) {
            // Insert into database
            $add_post = $con->prepare("INSERT INTO tblposts (PostTitle, CategoryId, PostDetails, PostUrl, Is_Active, PostImage, postedBy) VALUES (?, ?, ?, ?, 1, ?, ?)");
            $add_post->bind_param("sissss", $posttitle, $catid, $postdetails, $url, $image_rename, $postedby);

            if ($add_post->execute()) {
                $msg = 'Post added successfully!';
            } else {
                $error = 'Failed to add post to the database!';
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
    <title>Add Post</title>
    <style>
        /* General Styling */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    color: #333;
    margin: 0;
    padding: 0;
}

.main-container {
    max-width: 800px;
    margin: 50px auto;
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.page-heading {
    text-align: center;
    font-size: 2em;
    margin-bottom: 20px;
}

/* Form Styling */
form {
    display: flex;
    flex-direction: column;
}

.form-group {
    margin-bottom: 20px;
}

.form-label {
    font-size: 1.1em;
    margin-bottom: 5px;
}

.form-input, .form-textarea, .form-select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 1em;
}

.form-input:focus, .form-textarea:focus, .form-select:focus {
    border-color: #0056b3;
    outline: none;
}

/* Image Upload Styling */
input[type="file"] {
    padding: 10px;
    border-radius: 4px;
    border: 1px solid #ddd;
}

input[type="file"]:hover {
    background-color: #f9f9f9;
}

/* Buttons Styling */
.form-buttons {
    display: flex;
    justify-content: space-between;
    margin-top: 20px;
}

.btn {
    padding: 10px 20px;
    border-radius: 4px;
    font-size: 1.1em;
    cursor: pointer;
    border: none;
    transition: background-color 0.3s;
}

.btn-submit {
    background-color: #28a745;
    color: white;
}

.btn-submit:hover {
    background-color: #218838;
}

.btn-reset {
    background-color: #dc3545;
    color: white;
}

.btn-reset:hover {
    background-color: #c82333;
}

/* Alert Styling */
.alert {
    padding: 15px;
    margin: 10px 0;
    border-radius: 4px;
}

.success-alert {
    background-color: #d4edda;
    color: #155724;
}

.error-alert {
    background-color: #f8d7da;
    color: #721c24;
}

    </style>
</head>
<body>

<?php include('includes/topheader.php'); ?>
<?php include('includes/leftsidebar.php'); ?>

<div class="main-container">
    <h2 class="page-heading">Add Post</h2>

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

    <form action="" method="POST" enctype="multipart/form-data" class="post-form">
        <div class="form-group">
            <label for="posttitle" class="form-label">Post Title:</label>
            <input type="text" id="posttitle" name="posttitle" placeholder="Enter post title" required class="form-input">
        </div>

        <div class="form-group">
            <label for="category" class="form-label">Category:</label>
            <select name="category" id="category" class="form-input" required>
                <option value="">Select Category</option>
                <?php
                $result = mysqli_query($con, "SELECT id, CategoryName FROM tblcategory WHERE Is_Active=1");
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='" . $row['id'] . "'>" . $row['CategoryName'] . "</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="postimage" class="form-label">Feature Image:</label>
            <input type="file" accept="image/jpg, image/png, image/jpeg" id="postimage" name="postimage" required class="form-input">
        </div>

        <div class="form-group">
            <label for="postdescription" class="form-label">Post Description:</label>
            <textarea name="postdescription" id="postdescription" class="form-input" rows="5" required></textarea>
        </div>

        <div class="form-buttons">
            <button type="submit" name="submit" class="btn btn-submit">Add Post</button>
            <button type="reset" class="btn btn-reset">Discard</button>
        </div>
    </form>
</div>

<?php include('includes/footer.php'); ?>

</body>
</html>
