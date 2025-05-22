<?php
session_start();
include '../includes/config.php';

if (!isset($_SESSION['login'])) {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Online Education & Learning System</title>
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/themify-icons@0.1.2/css/themify-icons.css">
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
            height: 70px;
            background: #3c8dbc;
            color: #fff;
            display: flex;
            justify-content: space-between;
            z-index: 9999;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .custom-logo-img {
            height: 60px;
        }

        .custom-navbar {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            padding-right: 20px;
        }

        .custom-user-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .custom-user-dropdown {
            list-style: none;
            padding: 10px;
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: absolute;
            top: 60px;
            right: 10px;
            display: none;
            width: 180px;
            border-radius: 8px;
            animation: fadeIn 0.3s ease;
        }

        .custom-user-box:hover .custom-user-dropdown {
            display: block;
        }

        .custom-dropdown-link {
            display: block;
            padding: 8px 12px;
            color: #333;
            text-decoration: none;
            font-size: 14px;
            border-radius: 5px;
            transition: background 0.3s ease;
        }

        .custom-dropdown-link:hover {
            background: #f0f0f0;
        }

        .custom-welcome-text {
            margin: 0;
            font-size: 16px;
            color: #333;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            margin-left: 20px;
        }

        .topbar-left a {
            text-decoration: none;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body class="custom-body">
<div id="wrapper" class="custom-wrapper">
    <div class="topbar custom-topbar">
        <div class="topbar-left">
            <a href="dashboard.php" class="logo">
                <img src="../images/logo1.png" alt="Logo" class="custom-logo-img">
            </a>
        </div>
        <div class="navbar custom-navbar">
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown user-box custom-user-box">
                    <a href="#" class="dropdown-toggle user-link custom-user-link">
                        <img src="assets/images/users/avatar-1.jpg" alt="User Avatar" class="custom-user-img">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right user-list custom-user-dropdown">
                        <li>
                            <h5 class="custom-welcome-text">Hi, <?php echo $adminUserName; ?></h5>
                        </li>
                        <li><a href="change-password.php" class="custom-dropdown-link"><i class="ti-settings"></i> Change Password</a></li>
                        <li><a href="logout.php" class="custom-dropdown-link"><i class="ti-power-off"></i> Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
