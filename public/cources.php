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
    <title>Online Education & Learning System</title>
    <link rel="shortcut icon" href="images/favicon.png" type="image/x-icon">
    <style>
        .products {
            text-align: center;
            padding: 60px 20px;
        }

        .products h1 span {
            font-size: 32px;
            color: rgb(248, 189, 51);
            letter-spacing: 1px;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
        }

        .box {
            background-color: #fff;
            border-radius: 15px;
            padding: 20px;
            width: 260px;
            text-align: center;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease-in-out;
            cursor: pointer;
            position: relative;
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

        .box a {
            color: #333;
            text-decoration: none;
        }

        .box a:hover {
            text-decoration: underline;
        }

        .box::after {
            content: '';
            position: absolute;
            inset: 0;
            z-index: 1;
        }

        .box-content * {
            position: relative;
            z-index: 2;
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

        .pagination {
            margin-top: 40px;
        }

        .pagination a {
            margin: 0 5px;
            text-decoration: none;
            padding: 8px 12px;
            background-color: #eee;
            color: #333;
            border-radius: 5px;
        }

        .pagination a:hover {
            background-color: #e56131;
            color: #fff;
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
                    $pageno = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
                    $no_of_records_per_page = 8;
                    $offset = ($pageno - 1) * $no_of_records_per_page;

                    $total_pages_sql = "SELECT COUNT(*) FROM tblposts";
                    $result = mysqli_query($con, $total_pages_sql);
                    $total_rows = mysqli_fetch_array($result)[0];
                    $total_pages = ceil($total_rows / $no_of_records_per_page);

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
                        $link = isset($_SESSION['username']) 
                            ? 'post-details.php?nid=' . htmlentities($row['pid']) 
                            : 'login.php';
                ?>
                <div class="box" onclick="window.location.href='<?php echo $link; ?>'">
                    <div class="box-content">
                        <img src="../admin/postimages/<?php echo htmlentities($row['PostImage']); ?>" class="image" alt="<?php echo htmlentities($row['posttitle']); ?>">
                        <p>
                            <a href="category.php?catid=<?php echo htmlentities($row['cid']) ?>" onclick="event.stopPropagation();">
                                <?php echo htmlentities($row['category']); ?>
                            </a>
                        </p>
                        <p><small>Posted on <?php echo htmlentities($row['postingdate']); ?></small></p>
                        <h5><?php echo htmlentities($row['posttitle']); ?></h5>
                    </div>
                </div>
                <?php } ?>
            </div>

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