<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../images/favicon.png" type="image/x-icon">
    <title>Edit Profile</title>
    <style>
        /* General Styles */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #000;
}

/* Heading */
.heading {
    text-align: center;
    font-size: 24px;
    font-weight: bold;
    padding: 15px;
    color: white;
    margin: 20px;
}

/* Edit Profile Container */
.edit-profile-container {
    max-width: 400px;
    background: #111;
    color: white;
    padding: 25px;
    margin-bottom: 20px;
    margin: auto auto 20px auto;
    border-radius: 10px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
   

}

/* Form Fields */
.profile-edit-container {
    max-width: auto;
    display: flex;
    flex-direction: column;
    align-item:center;

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

/* Error Messages */
.error {
    color: red;
    font-size: 12px;
    height: 15px;
}

/* Save and Cancel Buttons */
.saveorcancel {
    display: flex;
    justify-content: space-between;
    margin-top: 20px;
    align-items: center;
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

/* Responsive Design */
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
<?php include '../includes/header.php'; ?>
<body>
    <?php
        $u_id = $_GET['u_id'];
        $qry = "SELECT * FROM user_signup WHERE u_id = '$u_id'";
        include '../includes/config.php';
        $result = mysqli_query($con, $qry);
        $row = mysqli_fetch_assoc($result);

        // Handle form submission
        if(isset($_POST['submit'])) {
            $u_name = mysqli_real_escape_string($con, $_POST['u_name']);
            $u_address = mysqli_real_escape_string($con, $_POST['u_address']);
            $u_email = mysqli_real_escape_string($con, $_POST['u_email']);
            $u_phone = mysqli_real_escape_string($con, $_POST['u_phone']);
            $u_gender = mysqli_real_escape_string($con, $_POST['u_gender']);
            $u_password = mysqli_real_escape_string($con, $_POST['u_password']);

            $qry2 = "UPDATE user_signup SET
                        u_name = '$u_name', 
                        u_address = '$u_address', 
                        u_email = '$u_email', 
                        u_phone = '$u_phone',
                        u_gender = '$u_gender',
                        u_password = '$u_password' 
                    WHERE u_id = '$u_id'";

            if(mysqli_query($con, $qry2)) {
                echo '<script type="text/javascript"> alert("Profile Updated Successfully"); window.location.assign("user-profile.php?u_id=' . $u_id . '");</script>';
                exit();
            } else {
                echo '<script type="text/javascript"> alert("Something Went Wrong!") </script>';
            }
        }
    ?> 

    <!--About-->
    <section class="edit-profile" id="edit-profile">
        <div class="heading">
            <span>Edit <?php echo $row['u_name'];?> Profile</span>
        </div>  
        <div class="edit-profile-container">
            <form action="" class="profile-detail" method="POST" onsubmit="return validateForm()">
                <div class="profile-edit-container">
                    <label for="u_name">Name:</label>
                    <span id="error_u_name" class="error"></span>
                    <input type="text" name="u_name" id="u_name" placeholder="Full Name" value="<?php echo $row['u_name'];?>" oninput="validateName()" onblur="validateName()"><br>

                    <label for="u_address">Address:</label>
                    <span id="error_u_address" class="error"></span>
                    <input type="text" name="u_address" id="u_address" placeholder="Address" value="<?php echo $row['u_address'];?>" oninput="validateAddress()" onblur="validateAddress()"><br>

                    <label for="u_email">Email:</label>
                    <span id="error_u_email" class="error"></span>
                    <input type="email" name="u_email" id="u_email" placeholder="Email" value="<?php echo $row['u_email'];?>" oninput="validateEmail()" onblur="validateEmail()"><br>

                    <label for="u_phone">Phone:</label>
                    <span id="error_u_phone" class="error"></span>
                    <input name="u_phone" id="u_phone" placeholder="Phone" value="<?php echo $row['u_phone'];?>" oninput="validatePhone()" onblur="validatePhone()"><br>

                    <label for="u_gender">Gender:</label>
                    <span id="error_u_gender" class="error"></span>
                    <select name="u_gender" id="u_gender" onchange="validateGender()" onblur="validateGender()">
                        <option value="Male" <?php if ($row['u_gender'] == 'Male') echo 'selected'; ?>>Male</option>
                        <option value="Female" <?php if ($row['u_gender'] == 'Female') echo 'selected'; ?>>Female</option>
                        <option value="Other" <?php if ($row['u_gender'] == 'Other') echo 'selected'; ?>>Other</option>
                    </select><br>

                    <label for="u_password">Password:</label>
                    <span id="error_u_password" class="error"></span>
                    <input style="width: 100%;" type="password" name="u_password" id="u_password" placeholder="New Password" value="<?php echo $row['u_password'];?>" oninput="validatePassword()" onblur="validatePassword()"><br>

                    <div class="saveorcancel">
                        <div class="save-btn">
                            <button type="submit" name="submit" id="savebtn">
                                <i class="fa-solid fa-check"></i>
                                Save
                            </button>
                        </div>
                        <div class="cancel-btn">
                            <a href="user-profile.php?u_id=<?php echo $row['u_id']; ?>"><i class="fa-solid fa-xmark"></i> Cancel</a>
                        </div>
                    </div>
                </div>
            </form>
        </div> 
    </section>
    <script src="../js/validation.js"></script>
    <?php include '../includes/footer.php'; ?>
</body>
</html>