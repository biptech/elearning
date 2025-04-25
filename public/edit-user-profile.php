<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="shortcut icon" href="images/favicon.png" type="image/x-icon">
    <style>
 
        .heading {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            background-color: #111;
            color: white;
        }

        .name{
            color: rgb(248, 189, 51);
        }
        .edit-profile-container {
            max-width: 400px;
            background: #111;
            color: white;
            padding: 25px;
            margin: 20px auto;
            border-radius: 10px;
        }

        .profile-edit-container {
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: bold;
            margin-top: 10px;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        input:focus, select:focus {
            outline: none;
            border-color: #007bff;
        }

        .error {
            color: red;
            font-size: 12px;
            height: 15px;
        }

        .saveorcancel {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            gap: 20px;
        }

        button, .cancel-btn a {
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
        }

        .save-btn button {
            background-color: #28a745;
            color: white;
            border: none;
        }

        .save-btn button:hover {
            background-color: #218838;
        }

        .cancel-btn a {
            background-color: #dc3545;
            color: white;
            border: none;
        }

        .cancel-btn a:hover {
            background-color: #c82333;
        }

        @media (max-width: 600px) {
            .edit-profile-container {
                width: 90%;
                padding: 20px;
            }

            .saveorcancel {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>
<body>

<?php
    include '../includes/config.php';
    $u_id = $_GET['u_id'];
    $qry = "SELECT * FROM user_signup WHERE u_id = '$u_id'";
    $result = mysqli_query($con, $qry);
    $row = mysqli_fetch_assoc($result);

    if (isset($_POST['submit'])) {
        $u_name = mysqli_real_escape_string($con, $_POST['u_name']);
        $u_address = mysqli_real_escape_string($con, $_POST['u_address']);
        $u_email = mysqli_real_escape_string($con, $_POST['u_email']);
        $u_phone = mysqli_real_escape_string($con, $_POST['u_phone']);
        $u_gender = mysqli_real_escape_string($con, $_POST['u_gender']);
        $old_password = $_POST['old_password'];

        if (!password_verify($old_password, $row['u_password'])) {
            echo '<script>alert("Incorrect current password.");</script>';
        } else {
            $raw_password = $_POST['u_password'];
            $u_password = !empty($raw_password) ? password_hash($raw_password, PASSWORD_DEFAULT) : $row['u_password'];

            $image = $_FILES['u_image']['name'];
            $temp_name = $_FILES['u_image']['tmp_name'];
            $updateImage = "";

            if (!empty($image)) {
                // If the user uploads a new image
                $folder = "../uploads/images/" . $image;
                move_uploaded_file($temp_name, $folder);
                $updateImage = ", u_image = '$image'";
            }

            $qry2 = "UPDATE user_signup SET
                        u_name = '$u_name', 
                        u_address = '$u_address', 
                        u_email = '$u_email', 
                        u_phone = '$u_phone',
                        u_gender = '$u_gender',
                        u_password = '$u_password'
                        $updateImage
                    WHERE u_id = '$u_id'";

            if (mysqli_query($con, $qry2)) {
                echo '<script>alert("Profile Updated Successfully"); window.location.href = "user-profile.php?u_id=' . $u_id . '";</script>';
                exit();
            } else {
                echo '<script>alert("Something went wrong.");</script>';
            }
        }
    }

    // Handle profile image removal
    if (isset($_POST['remove_image'])) {
        // Get the current profile image from the database
        $current_image = $row['u_image'];
        if (!empty($current_image)) {
            $image_path = '../uploads/images/' . $current_image;
            // Delete the image from the server
            if (file_exists($image_path)) {
                unlink($image_path);
            }
            // Update the database to set the profile image to NULL
            $qry_remove_image = "UPDATE user_signup SET u_image = NULL WHERE u_id = '$u_id'";
            mysqli_query($con, $qry_remove_image);
            echo '<script>window.location.href = "edit-user-profile.php?u_id=' . $u_id . '";</script>';
            exit();
        }
    }
?> 

<?php include '../includes/header.php'; ?>

<section class="edit-profile" id="edit-profile">
    <div class="edit-profile-container">
    <div class="heading">
    <p>Edit <span class = "name"> <?php echo $row['u_name'];?></span> Profile </p>
    </div>  
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="profile-edit-container">
                <label for="u_name">Name:</label>
                <input type="text" name="u_name" id="u_name" value="<?php echo $row['u_name']; ?>" required>

                <label for="u_address">Address:</label>
                <input type="text" name="u_address" id="u_address" value="<?php echo $row['u_address']; ?>" required>

                <label for="u_email">Email:</label>
                <input type="email" name="u_email" id="u_email" value="<?php echo $row['u_email']; ?>" required>

                <label for="u_phone">Phone:</label>
                <input type="text" name="u_phone" id="u_phone" value="<?php echo $row['u_phone']; ?>" required>

                <label for="u_gender">Gender:</label>
                <select name="u_gender" id="u_gender">
                    <option value="Male" <?php if ($row['u_gender'] == 'Male') echo 'selected'; ?>>Male</option>
                    <option value="Female" <?php if ($row['u_gender'] == 'Female') echo 'selected'; ?>>Female</option>
                    <option value="Other" <?php if ($row['u_gender'] == 'Other') echo 'selected'; ?>>Other</option>
                </select>

                <label for="u_image">Change Profile Picture:</label>
                <?php if (!empty($row['u_image'])): ?>
                    <div>
                        <img src="../uploads/images/<?php echo $row['u_image']; ?>" alt="Profile Image" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
                        <button type="submit" name="remove_image" style="background-color: red; color: white; border: none; padding: 5px 10px; margin-top: 10px;">Remove Image</button>
                    </div>
                <?php endif; ?>
                <input type="file" name="u_image" id="u_image" accept="image/*">
                <label for="old_password">Current Password:</label>
                <input type="password" name="old_password" id="old_password" required>

                <label for="u_password">New Password:</label>
                <input type="password" name="u_password" id="u_password" placeholder="Leave empty to keep current password">           

                <div class="saveorcancel">
                    <div class="save-btn">
                        <button type="submit" name="submit"><i class="fa-solid fa-check"></i> Save</button>
                    </div>
                    <div class="cancel-btn">
                        <a href="user-profile.php?u_id=<?php echo $row['u_id']; ?>"><i class="fa-solid fa-xmark"></i> Cancel</a>
                    </div>
                </div>
            </div>
        </form>
    </div> 
</section>

<?php include '../includes/footer.php'; ?>

</body>
</html>
