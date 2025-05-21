<?php
include('includes/config.php');

if (!isset($_SESSION['login']) || strlen($_SESSION['login']) == 0) {
    header('location:index.php');
    exit;
}

$postId = intval($_GET['pid']);

if (isset($_POST['update'])) {
    $title = mysqli_real_escape_string($con, $_POST['title']);
    $details = mysqli_real_escape_string($con, $_POST['details']);
    $category = intval($_POST['category']);

    $imageName = $_FILES['image']['name'];
    if ($imageName != '') {
        $imageTmp = $_FILES['image']['tmp_name'];
        $imagePath = "postimages/" . basename($imageName);
        move_uploaded_file($imageTmp, $imagePath);
        $imageUpdate = ", PostImage='$imageName'";
    } else {
        $imageUpdate = "";
    }

    $query = "UPDATE tblposts SET PostTitle='$title', PostDetails='$details', CategoryId='$category' $imageUpdate WHERE id=$postId";
    $result = mysqli_query($con, $query);

    $msg = $result ? "✅ Post updated successfully!" : "❌ Update failed. Try again.";
}

$postData = mysqli_query($con, "SELECT * FROM tblposts WHERE id=$postId");
$post = mysqli_fetch_assoc($postData);
$categories = mysqli_query($con, "SELECT * FROM tblcategory WHERE Is_Active=1");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Post</title>

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f7f9fc;
            margin: 0;
            padding: 0;
        }

        .container {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            max-width: 800px;
            margin: 40px auto;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }

        label {
            display: block;
            margin-top: 20px;
            font-weight: 600;
            color: #333;
        }

        input[type="text"], select, input[type="file"], textarea {
            width: 100%;
            padding: 12px;
            margin-top: 8px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
        }

        textarea {
            resize: vertical;
        }

        img.preview {
            margin-top: 10px;
            max-height: 150px;
            border: 1px solid #ddd;
            border-radius: 6px;
        }

        button {
            background: linear-gradient(135deg, #4caf50, #45a049);
            color: white;
            border: none;
            padding: 12px 30px;
            margin-top: 25px;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background: linear-gradient(135deg, #45a049, #3e8e41);
        }

        .msg {
            margin-top: 20px;
            padding: 10px;
            background: #e0ffe0;
            border-left: 5px solid #28a745;
            color: #155724;
            border-radius: 5px;
        }

    </style>
</head>
<body>

<?php include('includes/topheader.php'); ?>
<?php include('includes/leftsidebar.php'); ?>

<div class="container">
    <h2>Edit Post</h2>
    <?php if (isset($msg)) echo "<div class='msg'>$msg</div>"; ?>

    <form method="POST" enctype="multipart/form-data">
        <label>Post Title:</label>
        <input type="text" name="title" value="<?php echo htmlentities($post['PostTitle']); ?>" required>

        <label>Post Details:</label>
        <textarea name="details"><?php echo htmlentities($post['PostDetails']); ?></textarea>

        <label>Category:</label>
        <select name="category" required>
            <?php while ($cat = mysqli_fetch_assoc($categories)) { ?>
                <option value="<?php echo $cat['id']; ?>" <?php if ($cat['id'] == $post['CategoryId']) echo 'selected'; ?>>
                    <?php echo htmlentities($cat['CategoryName']); ?>
                </option>
            <?php } ?>
        </select>

        <label>Current Image:</label><br>
        <?php if (!empty($post['PostImage'])) { ?>
            <img src="postimages/<?php echo htmlentities($post['PostImage']); ?>" alt="Post Image" class="preview">
        <?php } else { ?>
            <p style="color: gray;">No image uploaded.</p>
        <?php } ?>

        <label>Upload New Image (optional):</label>
        <input type="file" name="image" accept="image/*">

        <button type="submit" name="update">Update Post</button>
    </form>
</div>

<?php include('includes/footer.php'); ?>
</body>
</html>
