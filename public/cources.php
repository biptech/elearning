<?php 
   session_start();
   include('../includes/config.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="images/favicon.png" type="image/x-icon">
    <title>Online Education & Learning System</title>
    
</head>
<body>
    <?php include('../includes/header.php');?>
<section class="body">
    <div class="container products">
        <h1 class ="ourcources">
            <span>LEARN MORE <br> &darr;</span>
        </h1>

        <div class="row">
            <?php 
                if (isset($_GET['pageno'])) {
                    $pageno = $_GET['pageno'];
                } else {
                    $pageno = 1;
                }
                $no_of_records_per_page = 8;
                $offset = ($pageno-1) * $no_of_records_per_page;

                $total_pages_sql = "SELECT COUNT(*) FROM tblposts";
                $result = mysqli_query($con, $total_pages_sql);
                $total_rows = mysqli_fetch_array($result)[0];
                $total_pages = ceil($total_rows / $no_of_records_per_page);

                $query = mysqli_query($con, "SELECT tblposts.id as pid, tblposts.PostTitle as posttitle, tblposts.PostImage, tblcategory.CategoryName as category, tblcategory.id as cid, tblsubcategory.Subcategory as subcategory, tblposts.PostDetails as postdetails, tblposts.PostingDate as postingdate, tblposts.PostUrl as url FROM tblposts LEFT JOIN tblcategory ON tblcategory.id=tblposts.CategoryId LEFT JOIN tblsubcategory ON tblsubcategory.SubCategoryId=tblposts.SubCategoryId WHERE tblposts.Is_Active=1 ORDER BY tblposts.id DESC LIMIT $offset, $no_of_records_per_page");

                while ($row = mysqli_fetch_array($query)) { 
            ?>
            <div class="box">
                <img src="admin/postimages/<?php echo htmlentities($row['PostImage']); ?>" class="image" alt="<?php echo htmlentities($row['posttitle']); ?>">
                <p>
                    <a href="category.php?catid=<?php echo htmlentities($row['cid']) ?>">
                        <?php echo htmlentities($row['category']); ?>
                    </a> 
                    <a>
                        <?php echo htmlentities($row['subcategory']); ?>
                    </a>
                </p>
                <p><small>Posted on <?php echo htmlentities($row['postingdate']); ?></small></p>
                <h5><?php echo htmlentities($row['posttitle']); ?></h5>
                <a class="btn" href="<?php echo isset($_SESSION['username']) ? 'news-details.php?nid=' . htmlentities($row['pid']) : 'login.php'; ?>"  <h5>Read More</h5>
                </a>
            </div>
            <?php } ?>
        </div>
    </div>
    </body>
    </section>
    <?php include('../includes/footer.php');?>
</html>