<?php  
session_start();
include('../includes/config.php');

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    echo "<script> window.location.href='login.php';</script>";
    exit();
}

// Generating CSRF Token
if (empty($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}

if (isset($_POST['submit'])) {
    // Verifying CSRF Token
    if (!empty($_POST['csrftoken'])) {
        if (hash_equals($_SESSION['token'], $_POST['csrftoken'])) {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $comment = $_POST['comment'];
            $postid = intval($_GET['nid']);
            $st1 = '0';
            $query = mysqli_query($con, "INSERT INTO tblcomments(postId, name, email, comment, status) VALUES('$postid', '$name', '$email', '$comment', '$st1')");
            if ($query) {
                echo "<script>alert('Comment successfully submitted. Comment will be displayed after admin review');</script>";
                unset($_SESSION['token']);
                echo "<script>location.href='post-details.php?nid=$postid'</script>";
            } else {
                echo "<script>alert('Something went wrong. Please try again.');</script>";  
            }
        }
    }
}

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
    echo "no results";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Online Education & Learning System | News Details</title>
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
            <?php } ?>
        </div>

        <!-- Leave a Comment -->
        <div class="comment-form-container">
            <h5 class="form-heading">Leave a Comment</h5>
            <form name="Comment" method="post">
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

    <?php include('includes/footer.php');?>
</body>
</html>
