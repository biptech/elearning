<?php

include '../includes/config.php'; 

if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    mysqli_query($con, "DELETE FROM user_signup WHERE u_id = $delete_id");
    header("Location: manage-users.php");
    exit();
}

$result = mysqli_query($con, "SELECT * FROM user_signup ORDER BY u_id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users</title>

    <style>

        .main-content {
            margin-left: 250px;
            margin-top: 70px;
            padding: 20px;
            background: #f9f9f9;
            min-height: calc(100vh - 70px);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #f4f4f4;
        }

        a {
            text-decoration: none;
            color: #007BFF;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<?php include 'includes/topheader.php'; ?> 
<?php include 'includes/leftsidebar.php'; ?> 

<div class="main-content">
    <h2>User Management</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Gender</th>
                <th>Status</th>
                <th>Image</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?= $row['u_id'] ?></td>
                <td><?= htmlspecialchars($row['u_name']) ?></td>
                <td><?= htmlspecialchars($row['u_email']) ?></td>
                <td><?= htmlspecialchars($row['u_phone']) ?></td>
                <td><?= $row['u_gender'] ?></td>
                <td><?= $row['is_verified'] ? 'Verified' : 'Pending' ?></td>
                <td>
                    <?php if (!empty($row['u_image'])): ?>
                        <img src="../uploads/images/<?= $row['u_image'] ?>" width="50">
                    <?php else: ?>
                        N/A
                    <?php endif; ?>
                </td>
                <td>
                    <a href="manage-users.php?delete=<?= $row['u_id'] ?>" onclick="return confirm('Delete this user?')">Delete</a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>
