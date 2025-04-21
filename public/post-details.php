<?php   
session_start();
include('../includes/config.php');

// Initialize post and product IDs
$post_id = isset($_GET['nid']) ? intval($_GET['nid']) : 0;
$product_id = isset($_GET['pid']) ? intval($_GET['pid']) : 0;

// Handle viewed items in session
if (!isset($_SESSION['viewed_items'])) {
    $_SESSION['viewed_items'] = []; // Initialize viewed_items session if it doesn't exist
}

// Avoid adding duplicate entries for posts
if ($post_id && !in_array($post_id, array_column($_SESSION['viewed_items'], 'id'))) {
    $_SESSION['viewed_items'][] = ['id' => $post_id, 'type' => 'post'];  
}

// Avoid adding duplicate entries for products
if ($product_id && !in_array($product_id, array_column($_SESSION['viewed_items'], 'id'))) {
    $_SESSION['viewed_items'][] = ['id' => $product_id, 'type' => 'product'];  
}

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    echo "<script> window.location.href='login.php';</script>";
    exit();
}

// Generating CSRF Token if not already set
if (empty($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}

// Handling comment submission
if (isset($_POST['submit'])) {
    // Verifying CSRF Token
    if (!empty($_POST['csrftoken'])) {
        if (hash_equals($_SESSION['token'], $_POST['csrftoken'])) {
            $name = mysqli_real_escape_string($con, $_POST['name']);
            $email = mysqli_real_escape_string($con, $_POST['email']);
            $comment = mysqli_real_escape_string($con, $_POST['comment']);
            $postid = intval($_GET['nid']);
            $st1 = '0';
            $query = mysqli_query($con, "INSERT INTO tblcomments(postId, name, email, comment, status) VALUES('$postid', '$name', '$email', '$comment', '$st1')");
            if ($query) {
                unset($_SESSION['token']);
                echo "<script>alert('Comment successfully submitted. Comment will be displayed after admin review');</script>";
                echo "<script>location.href='post-details.php?nid=$postid'</script>";
            } else {
                echo "<script>alert('Something went wrong. Please try again.');</script>";  
            }
        }
    }
}

// Updating post view counter
$postid = intval($_GET['nid']);
$sql = "SELECT viewCounter FROM tblposts WHERE id = '$postid'";
$result = $con->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $visits = $row["viewCounter"];
        $sql = "UPDATE tblposts SET viewCounter = $visits+1 WHERE id ='$postid'";
        $con->query($sql);
    }
} else {
    echo "No results found";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Online Education & Learning System | Post Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #000;
        }
        .main-container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            background-color: #000;
        }
        .post-section {
            margin-bottom: 40px;
            color: #fff;
        }
        .category-badge { 
            color: #000;
            text-decoration: none;
            background: rgb(248, 189, 51);
            padding: 2px;
            border-radius: 5px;
        }
        .post-card {
            background-color: #000;
            border: 1px solid rgb(248, 189, 51);
            padding: 20px;
            margin-bottom: 20px;
        }
        .post-title {
            font-size: 28px;
            margin: 0;
        }
        .post-meta {
            font-size: 14px;
            color: #777;
            margin-bottom: 20px;
        }
        .post-details-container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-top: 20px;
        }
        .post-image-container {
            flex: 1;
            margin-right: 20px;
        }
        .post-image {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }
        .post-text-container {
            flex: 2;
        }
        .comments-container {
            margin-top: 40px;
        }
        .comment-box {
            display: flex;
            margin-bottom: 20px;
        }
        .comment-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .comment-body {
            flex-grow: 1;
        }
        .comment-author {
            font-weight: bold;
        }
        .comment-date {
            font-size: 12px;
            color: #777;
        }
        .comment-text {
            margin-top: 10px;
        }
        .comment-form-container {
            margin-top: 40px;
            background-color: #111;
            padding: 20px;
            border-radius: 5px;
        }
        .form-heading {
            font-size: 20px;
            margin-bottom: 20px;
            color: rgb(248, 189, 51);
        }
        .form-group {
            margin-bottom: 15px;
        }
        .input-field, .textarea-field {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid rgb(248, 189, 51);
            border-radius: 5px;
            box-sizing: border-box;
            background: #000;
        }
        .submit-btn {
            background-color: rgb(248, 189, 51);
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .submit-btn:hover {
            background-color: #45a049;
        }
    </style>
    <script>
        function validateForm() {
            var name = document.Comment.name.value;
            var email = document.Comment.email.value;
            var comment = document.Comment.comment.value;

            if (name == "" || email == "" || comment == "") {
                alert("All fields are required.");
                return false;
            }

            var emailPattern = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
            if (!emailPattern.test(email)) {
                alert("Please enter a valid email address.");
                return false;
            }

            return true;
        }
    </script>
</head>
<body>
    <?php include('../includes/header.php');?>

    <div class="main-container">

        <div class="post-section">
            <?php
            $pid = intval($_GET['nid']);
            $query = mysqli_query($con, "SELECT tblposts.PostTitle AS posttitle, tblposts.PostImage, tblcategory.CategoryName AS category, tblcategory.id AS cid, tblposts.PostDetails AS postdetails, tblposts.PostingDate AS postingdate, tblposts.PostUrl AS url, tblposts.postedBy FROM tblposts LEFT JOIN tblcategory ON tblcategory.id = tblposts.CategoryId WHERE tblposts.id = '$pid'");
            while ($row = mysqli_fetch_array($query)) {
            ?>
            <div class="post-card">
                <div class="post-card-body">
                    <a class="category-badge" href="category.php?catid=<?php echo htmlentities($row['cid'])?>">
                        <?php echo htmlentities($row['category']);?>
                    </a>
                    <h1 class="post-title"><?php echo htmlentities($row['posttitle']);?></h1>
                    <p class="post-meta">
                        by <?php echo htmlentities($row['postedBy']);?> | <?php echo htmlentities($row['postingdate']);?>
                    </p>
                    <div class="post-details-container">
                        <div class="post-image-container">
                            <img class="post-image" src="../admin/postimages/<?php echo htmlentities($row['PostImage']);?>" alt="<?php echo htmlentities($row['posttitle']);?>">
                        </div>
                        <div class="post-text-container">
                            <p><?php echo htmlentities($row['postdetails']); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>

        <!-- Approved Comments -->
        <div class="comments-container">
            <?php 
            $sts = 1;
            $query = mysqli_query($con, "SELECT name, comment, postingDate FROM tblcomments WHERE postId = '$pid' AND status = '$sts'");
            if(mysqli_num_rows($query) > 0) {
                while ($row = mysqli_fetch_array($query)) {
            ?>
            <div class="comment-box">
                <img class="comment-avatar" src="images/usericon.png" alt="user-icon">
                <div class="comment-body">
                    <h5 class="comment-author"><?php echo htmlentities($row['name']);?></h5>
                    <small class="comment-date"><?php echo htmlentities($row['postingDate']);?></small>
                    <p class="comment-text"><?php echo htmlentities($row['comment']);?></p>
                </div>
            </div>
            <?php 
                }
            } else {
                echo "<p>No comments yet. Be the first to comment!</p>";
            }
            ?>
        </div>

        <!-- Leave a Comment -->
        <div class="comment-form-container">
            <h5 class="form-heading">Leave a Comment</h5>
            <form name="Comment" method="post" onsubmit="return validateForm()">
                <input type="hidden" name="csrftoken" value="<?php echo htmlentities($_SESSION['token']); ?>" />
                <div class="form-group">
                    <input type="text" name="name" class="input-field" placeholder="Enter your full name" required>
                </div>
                <div class="form-group">
                    <input type="email" name="email" class="input-field" placeholder="Enter your Valid email" required>
                </div>
                <div class="form-group">
                    <textarea class="textarea-field" name="comment" rows="3" placeholder="Comment" required></textarea>
                </div>
                <button type="submit" class="submit-btn" name="submit">Submit</button>
            </form>
        </div>

    </div>

    <?php include('../includes/footer.php');?>
</body>
</html>