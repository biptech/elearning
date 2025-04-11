<?php
$qry = "SELECT * FROM tbladmin WHERE id = 2";
include '../includes/config.php';
$result = mysqli_query($con, $qry);
$row = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Online Education & Learning System</title>
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.png">
    <style>

        body.custom-body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f9;
    

        }

        .custom-wrapper {
            display: flex;
            flex-direction: column;
        }

        .custom-topbar {
            position: fixed;
            width: 100%;
            background: #3c8dbc;
            color: #fff;
            display: flex;
            justify-content: space-between;
            z-index: 99999;
        }

        .custom-logo-img {
            height: 60px;
        }

        .custom-navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .custom-user-img {
            width: 40px;
            border-radius: 50%;
        }
.custom-welcome-text{
    color: black;
}
        .custom-user-dropdown {
            list-style: none;
            padding: 10px;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            position: absolute;
            top: 60px;
            right: 10px;
            display: none;
        }

        .custom-user-box:hover .custom-user-dropdown {
            display: block;
        }

        .custom-dropdown-link {
            display: block;
            padding: 8px 12px;
            color: #333;
            text-decoration: none;
        }

        .custom-dropdown-link:hover {
            background: #f0f0f0;
        }

        .custom-menu-button {
            background: transparent;
            border: none;
            color: white;
            font-size: 20px;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    function checkAvailability() {
        $("#loaderIcon").show();
        jQuery.ajax({
            url: "check_availability.php",
            data: 'username=' + $("#sadminusername").val(),
            type: "POST",
            success: function(data) {
                $("#user-availability-status").html(data);
                $("#loaderIcon").hide();
            },
            error: function() {}
        });
    }
    </script>
</head>
<body class="custom-body">
<div id="wrapper" class="custom-wrapper">
    <div class="topbar custom-topbar">
        <div class="topbar-left custom-topbar-left">
            <a href="index.php" class="logo custom-logo">
                <img src="../images/logo.png" alt="Logo" class="custom-logo-img">
            </a>
        </div>
        <div class="navbar custom-navbar">
            <!-- <ul class="nav navbar-nav navbar-left custom-navbar-left">
                <li>
                    <button class="button-menu-mobile custom-menu-button">
                        ☰
                    </button>
                </li>
            </ul> -->
            <ul class="nav navbar-nav navbar-right custom-navbar-right">
                <li class="dropdown user-box custom-user-box">
                    <a href="#" class="dropdown-toggle user-link custom-user-link">
                        <img src="assets/images/users/avatar-1.jpg" alt="User" class="img-circle user-img custom-user-img">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right user-list custom-user-dropdown">
                        <li>
                            <h5 class="custom-welcome-text">Hi, <?php echo $row['AdminUserName']; ?></h5>
                        </li>
                        <li><a href="change-password.php" class="custom-dropdown-link"><i class="ti-settings"></i> Change Password</a></li>
                        <li><a href="logout.php" class="custom-dropdown-link"><i class="ti-power-off"></i> Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>