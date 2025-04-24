<?php
include('includes/config.php');
$msg = "";

if (isset($_GET['del']) && is_numeric($_GET['del'])) {
    $id = intval($_GET['del']);
    $stmt = $con->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $msg = "Product deleted successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Products</title>
    <style>
        body { font-family: Arial; background: #f0f0f0; }
        .main-container { max-width: 1000px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px #ccc; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f7f7f7; }
        .action-btns a { padding: 5px 10px; border-radius: 4px; text-decoration: none; color: white; margin-right: 5px; }
        .edit-btn { background: #007bff; }
        .delete-btn { background: #dc3545; }
        .msg { background: #d4edda; padding: 10px; border-radius: 5px; color: #155724; }
    </style>
</head>
<body>
<?php include('includes/topheader.php'); ?>
<?php include('includes/leftsidebar.php'); ?>
<div class="main-container">
    <h2>Manage Products</h2>
    <?php if ($msg): ?><div class="msg"><?= htmlentities($msg) ?></div><?php endif; ?>
    <table>
        <tr>
            <th>#</th>
            <th>Product</th>
            <th>Price</th>
            <th>Category</th>
            <th>Image</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php
        $result = $con->query("SELECT p.*, c.CategoryName FROM products p LEFT JOIN tblcategory c ON p.category_id = c.id");
        $count = 1;
        while ($row = $result->fetch_assoc()):
        ?>
        <tr>
            <td><?= $count++ ?></td>
            <td><?= htmlentities($row['name']) ?></td>
            <td>Rs. <?= htmlentities($row['price']) ?></td>
            <td><?= htmlentities($row['CategoryName']) ?></td>
            <td><img src="uploaded_files/<?= $row['image'] ?>" width="60"></td>
            <td>
                <?php
                // Display the status correctly
                echo $row['status'] == 'Active' ? 'Active' : 'Inactive';
                ?>
            </td>
            <td class="action-btns">
                <a href="edit-carts.php?id=<?= $row['id'] ?>" class="edit-btn">Edit</a>
                <a href="manage-carts.php?del=<?= $row['id'] ?>" class="delete-btn" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
<?php include('includes/footer.php'); ?>
</body>
</html>
