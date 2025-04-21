<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <style>

body {
    font-family: 'Poppins', sans-serif;
    margin: 0;
    padding: 0;
    background-color: black;
    color: #fff;
}

.profile {
    max-width: 400px;
    margin: 50px auto;
    background: #111;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.profile .heading span {
    display: block;
    font-size: 24px;
    font-weight: bold;
    color: #fff;
    margin-bottom: 10px;
}

.profile-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 20px;
}

.profile-img img {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #ffc107;
}

.about-text p {
    font-size: 16px;
    line-height: 1.6;
    background: #fff;
    color: #000;
    padding: 10px;
    border-radius: 5px;
    width: 100%;
    max-width: 500px;
    text-align: left;
    box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
    margin: 20px;
}

.about-text p b {
    color: #343a40;
}

.btn {
    display: inline-block;
    background-color: #ffc107;
    color: #333;
    text-decoration: none;
    padding: 10px 20px;
    border-radius: 5px;
    font-weight: 600;
    transition: background 0.3s ease;
    margin-top: 10px;
}

.btn:hover {
    background-color: #e0a800;
}

/* Responsive */
@media (max-width: 768px) {
    .profile {
        width: 90%;
        padding: 20px;
    }

    .about-text p {
        font-size: 14px;
    }
}

    </style>
</head>
<body>
<?php include '../includes/header.php'; ?>

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
    <?php include '../includes/footer.php'; ?>
</body>
</html>
