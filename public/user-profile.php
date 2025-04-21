<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
</head>
<body>
<?php
    $u_id = $_GET['u_id'];
    $qry = "SELECT * FROM user_signup WHERE u_id = '$u_id'";
    include '../includes/config.php';
    $result = mysqli_query($con, $qry);
    $row = mysqli_fetch_assoc($result);
?>
    
    <!--About-->
    <section class="profile" id="profile">
        <div class="heading">
            <span>Profile</span>
            <!-- <h1><?php echo $row['u_name'];?></h1> -->
        </div>  
        <div class="profile-container">
            <div class="profile-img">
                <img src="images\<?php echo $row['u_image'];?>" alt="">
            </div>
            <div class="about-text">

            <p><b>Name:</b>&nbsp;<?php echo $row['u_name'];?></p>
                <p><b>Address:</b>&nbsp;<?php echo $row['u_address'];?></p>
                <p><b>Email:</b>&nbsp;<?php echo $row['u_email'];?></p>
                <p><b>Phone:</b>&nbsp;<?php echo $row['u_phone'];?></p>
                <p><b>Gender:</b>&nbsp;<?php echo $row['u_gender'];?></p>
                <p><b>Password:</b>&nbsp;</p>
                <a href="edit-user-profile.php?u_id=<?php echo $row['u_id'];?>" class="btn"><i class="fa-solid fa-pen"></i> Edit</a>
            </div>
        </div>
    </section>    
</body>
</html>
