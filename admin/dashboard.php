<?php 
session_start();
include('includes/config.php');
error_reporting(0);
if (strlen($_SESSION['login']) == 0) {
    header('location:index.php');
} else {
?>
<?php include('includes/topheader.php'); ?>
<?php include('includes/leftsidebar.php'); ?>

<style>
    .admin-dashboard {
        font-family: Arial, sans-serif;
        background: #f7f9fc;
        padding: 20px;
    }

    .dashboard-content {
        margin-left: 240px;
    }

    .dashboard-title-box {
        margin-bottom: 20px;
    }

    .dashboard-title {
        font-size: 28px;
        font-weight: bold;
        margin: 0;
    }

    .dashboard-breadcrumb {
        list-style: none;
        padding: 0;
        display: flex;
        gap: 5px;
        font-size: 14px;
    }

    .dashboard-breadcrumb li::after {
        content: "/";
        margin: 0 5px;
    }

    .dashboard-breadcrumb li:last-child::after {
        content: "";
    }

    .dashboard-widgets {
        display: flex;
        gap: 20px;
        margin-bottom: 30px;
        flex-wrap: wrap;
    }

    .dashboard-link {
        text-decoration: none;
        color: inherit;
        flex: 1 1 30%;
    }

    .dashboard-widget {
        background: #ffffff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s ease;
    }

    .dashboard-widget:hover {
        transform: translateY(-5px);
    }

    .widget-icon {
        font-size: 32px;
        color: #666;
        margin-bottom: 10px;
    }

    .widget-label {
        font-weight: bold;
        font-size: 16px;
        margin-bottom: 5px;
    }

    .widget-count {
        font-size: 24px;
        color: #007bff;
    }

    .dashboard-posts {
        margin-top: 40px;
    }

    .post-card {
        background: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
    }

    .post-title {
        font-size: 20px;
        margin-bottom: 15px;
    }

    .post-table-wrapper {
        overflow-x: auto;
    }

    .post-table {
        width: 100%;
        border-collapse: collapse;
    }

    .post-table th,
    .post-table td {
        padding: 12px;
        border: 1px solid #ddd;
        text-align: left;
    }
</style>

<div class="admin-dashboard">
    <div class="dashboard-content">
        <div class="dashboard-container">
            <div class="dashboard-header-row">
                <div class="dashboard-header-col">
                    <div class="dashboard-title-box">
                        <h4 class="dashboard-title">Dashboard</h4>
                        <ol class="dashboard-breadcrumb">
                            <li><a href="#">Education</a></li>
                            <li><a href="#">Admin</a></li>
                            <li class="active">Dashboard</li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="dashboard-widgets">
                <a href="manage-posts.php" class="dashboard-link">
                    <div class="dashboard-widget">
                        <div class="widget-box">
                            <i class="widget-icon">📚</i>
                            <div class="widget-content">
                                <p class="widget-label">Courses Post</p>
                                <?php
                                $query = mysqli_query($con, "SELECT * FROM tblposts WHERE Is_Active=1");
                                $countposts = mysqli_num_rows($query);
                                ?>
                                <h2 class="widget-count"><?php echo htmlentities($countposts); ?></h2>
                            </div>
                        </div>
                    </div>
                </a>

                <a href="manage-categories.php" class="dashboard-link">
                    <div class="dashboard-widget">
                        <div class="widget-box">
                            <i class="widget-icon">📂</i>
                            <div class="widget-content">
                                <p class="widget-label">Categories Listed</p>
                                <?php
                                $query = mysqli_query($con, "SELECT * FROM tblcategory WHERE Is_Active=1");
                                $countcat = mysqli_num_rows($query);
                                ?>
                                <h2 class="widget-count"><?php echo htmlentities($countcat); ?></h2>
                            </div>
                        </div>
                    </div>
                </a>

                <a href="manage-subcategories.php" class="dashboard-link">
                    <div class="dashboard-widget">
                        <div class="widget-box">
                            <i class="widget-icon">📁</i>
                            <div class="widget-content">
                                <p class="widget-label">Listed Subcategories</p>
                                <?php
                                $query = mysqli_query($con, "SELECT * FROM tblsubcategory WHERE Is_Active=1");
                                $countsubcat = mysqli_num_rows($query);
                                ?>
                                <h2 class="widget-count"><?php echo htmlentities($countsubcat); ?></h2>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="dashboard-posts">
                <div class="post-section">
                    <div class="post-card">
                        <h2 class="post-title">Recent Post</h2>
                        <div class="post-table-wrapper">
                            <table class="post-table" id="example">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Subcategory</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = mysqli_query($con, "SELECT tblposts.id AS postid, tblposts.PostTitle AS title, tblcategory.CategoryName AS category, tblsubcategory.Subcategory AS subcategory 
                                    FROM tblposts 
                                    LEFT JOIN tblcategory ON tblcategory.id = tblposts.CategoryId 
                                    LEFT JOIN tblsubcategory ON tblsubcategory.SubCategoryId = tblposts.SubCategoryId 
                                    WHERE tblposts.Is_Active=1");
                                    $rowcount = mysqli_num_rows($query);
                                    if ($rowcount == 0) {
                                    ?>
                                        <tr>
                                            <td colspan="4"><h3>No record found</h3></td>
                                        </tr>
                                    <?php
                                    } else {
                                        while ($row = mysqli_fetch_array($query)) {
                                    ?>
                                            <tr>
                                                <td><?php echo htmlentities($row['title']); ?></td>
                                                <td><?php echo htmlentities($row['category']) ?></td>
                                                <td><?php echo htmlentities($row['subcategory']) ?></td>
                                            </tr>
                                    <?php }
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <?php include('includes/footer.php'); ?>
</div>

<?php } ?>