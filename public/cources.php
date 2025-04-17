<?php  
   session_start();
   include('../includes/config.php');

   // Reconnect if MySQL connection is lost
   if (!isset($con) || !$con || !$con->ping()) {
       $con = mysqli_connect("localhost", "root", "", "elearning");
       if (!$con) {
           die("Database connection failed: " . mysqli_connect_error());
       }
   }
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
    <style>
        .products {
    text-align: center;
    padding: 50px 20px;

}
        .ourcources {
            text-align: center;
            color: rgb(248, 189, 51);
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .box {
            background-color: #fff;
            border-radius: 10px;
            padding: 15px;
            width: 250px;
            text-align: center;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .box:hover {
            transform: translateY(-5px);
            box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.2);
        }

        .image {
    width: 100%;
    height: auto;
    border-radius: 10px;
}

        .btn {
            display: inline-block;
            padding: 10px 15px;
            background-color: #000;
            color: white;
            text-decoration: none;
            transition: background 0.3s;
        }

        .btn:hover {
            background-color: #e56131;
        }

        @media (max-width: 768px) {
            .box {
                width: calc(50% - 20px);
            }
        }

        @media (max-width: 480px) {
            .box {
                width: 100%;
            }
        }

    </style>

</head>

<body>
    <?php include('../includes/header.php'); ?>

    <section class="body">
        <div class="container products">
            <h1 class="ourcources">
                <span>LEARN MORE <br> &darr;</span>
            </h1>

            <div class="row">
                <?php 
                    // Pagination setup
                    $pageno = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
                    $no_of_records_per_page = 8;
                    $offset = ($pageno - 1) * $no_of_records_per_page;

                    // Total pages
                    $total_pages_sql = "SELECT COUNT(*) FROM tblposts";
                    $result = mysqli_query($con, $total_pages_sql);
                    $total_rows = mysqli_fetch_array($result)[0];
                    $total_pages = ceil($total_rows / $no_of_records_per_page);

                    // Fetch posts with category
                    $query = mysqli_query($con, "
                        SELECT 
                            tblposts.id as pid, 
                            tblposts.PostTitle as posttitle, 
                            tblposts.PostImage, 
                            tblcategory.CategoryName as category, 
                            tblcategory.id as cid, 
                            tblposts.PostDetails as postdetails, 
                            tblposts.PostingDate as postingdate, 
                            tblposts.PostUrl as url 
                        FROM tblposts 
                        LEFT JOIN tblcategory ON tblcategory.id = tblposts.CategoryId 
                        WHERE tblposts.Is_Active = 1 
                        ORDER BY tblposts.id DESC 
                        LIMIT $offset, $no_of_records_per_page
                    ");

                    while ($row = mysqli_fetch_array($query)) { 
                ?>
                <div class="box">
                    <img src="../admin/postimages/<?php echo htmlentities($row['PostImage']); ?>" class="image" alt="<?php echo htmlentities($row['posttitle']); ?>">
                    <p>
                        <a href="category.php?catid=<?php echo htmlentities($row['cid']) ?>">
                            <?php echo htmlentities($row['category']); ?>
                        </a>
                    </p>
                    <p><small>Posted on <?php echo htmlentities($row['postingdate']); ?></small></p>
                    <h5><?php echo htmlentities($row['posttitle']); ?></h5>
                    <a class="btn" href="<?php echo isset($_SESSION['username']) ? 'news-details.php?nid=' . htmlentities($row['pid']) : 'login.php'; ?>">
                        <h5>Read More</h5>
                    </a>
                </div>
                <?php } ?>
            </div>

            <!-- Pagination Links -->
            <div class="pagination">
                <a href="?pageno=1">First</a>
                <a href="?pageno=<?php echo max(1, $pageno - 1); ?>">Prev</a>
                <a href="?pageno=<?php echo min($total_pages, $pageno + 1); ?>">Next</a>
                <a href="?pageno=<?php echo $total_pages; ?>">Last</a>
            </div>
        </div>
    </section>

    <?php include('../includes/footer.php'); ?>
</body>
</html>
