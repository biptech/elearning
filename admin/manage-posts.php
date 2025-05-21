<?php
include('includes/config.php');

// Session timeout
if (!isset($_SESSION['last_activity'])) {
    $_SESSION['last_activity'] = time();
} elseif (time() - $_SESSION['last_activity'] > 900) {
    session_unset();
    session_destroy();
    header('location:index.php?timeout=true');
    exit;
}
$_SESSION['last_activity'] = time();

// if (strlen($_SESSION['login']) == 900) {
//     header('location:index.php');
//     exit;
// }

// Delete post logic
if (isset($_GET['del'])) {
    $id = intval($_GET['del']);
    $query = mysqli_query($con, "DELETE FROM tblposts WHERE id = '$id'");
    $msg = $query ? "Post deleted successfully." : "Failed to delete post.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Posts</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .main-container {
            margin-top: 40px;
            margin-left: 250px;
            padding: 20px;
        }

        .page-heading {
            font-size: 2em;
            margin-bottom: 20px;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .action-btns a {
            text-decoration: none;
            padding: 6px 12px;
            margin-right: 5px;
            border-radius: 4px;
            font-size: 0.9em;
            color: #fff;
        }

        .edit-btn {
            background-color: #28a745;
        }

        .delete-btn {
            background-color: #dc3545;
        }

        .thumbnail {
            width: 100px;
            height: auto;
            border-radius: 4px;
        }
    </style>
</head>
<body>

<?php include('includes/topheader.php'); ?>
<?php include('includes/leftsidebar.php'); ?>

<div class="main-container">
    <h2 class="page-heading">Manage Posts</h2>

    <?php if (isset($msg)) { echo "<p style='color: green; text-align: center;'>$msg</p>"; } ?>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Image</th>
                <th>Title</th>
                <th>Category</th>
                <th>Posted By</th>
                <th>Status</th>
                <th>Post Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT tblposts.id, tblposts.PostTitle, tblposts.PostImage, tblcategory.CategoryName, tblposts.postedBy, tblposts.Is_Active, tblposts.PostingDate 
                      FROM tblposts 
                      LEFT JOIN tblcategory ON tblcategory.id = tblposts.CategoryId 
                      ORDER BY tblposts.id DESC";

            $result = mysqli_query($con, $query);
            $cnt = 1;

            while ($row = mysqli_fetch_assoc($result)) {
                $imagePath = !empty($row['PostImage']) && file_exists('postimages/' . $row['PostImage']) 
                             ? 'postimages/' . $row['PostImage'] 
                             : 'postimages/default.png';

                echo "<tr>";
                echo "<td>" . $cnt++ . "</td>";
                echo "<td><img src='" . htmlentities($imagePath) . "' alt='Post Image' class='thumbnail'></td>";
                echo "<td>" . htmlentities($row['PostTitle']) . "</td>";
                echo "<td>" . htmlentities($row['CategoryName']) . "</td>";
                echo "<td>" . htmlentities($row['postedBy']) . "</td>";
                echo "<td>" . ($row['Is_Active'] ? 'Active' : 'Inactive') . "</td>";
                echo "<td>" . date("d M Y", strtotime($row['PostingDate'])) . "</td>";
                echo "<td class='action-btns'>
                        <a href='edit-post.php?pid=" . htmlentities($row['id']) . "' class='edit-btn'>Edit</a>
                        <a href='manage-posts.php?del=" . htmlentities($row['id']) . "' class='delete-btn' onclick=\"return confirm('Are you sure you want to delete this post?');\">Delete</a>
                      </td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include('includes/footer.php'); ?>

</body>
</html>