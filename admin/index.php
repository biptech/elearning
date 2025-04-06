<?php
 session_start();
//Database Configuration File
include('includes/config.php');
//error_reporting(0);
if(isset($_POST['login']))
  {
 
    // Getting username/ email and password
     $uname=$_POST['username'];
    $password=md5($_POST['password']);
    // Fetch data from database on the basis of username/email and password
$sql =mysqli_query($con,"SELECT AdminUserName,AdminEmailId,AdminPassword,userType FROM tbladmin WHERE (AdminUserName='$uname' && AdminPassword='$password')");
 $num=mysqli_fetch_array($sql);
if($num>0)
{

$_SESSION['login']=$_POST['username'];
$_SESSION['utype']=$num['userType'];
    echo "<script type='text/javascript'> document.location = 'dashboard.php'; </script>";
  }else{
echo "<script>alert('Invalid Details');</script>";
  }
 
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="101 + News Station Portal.">
    <meta name="author" content="xyz">
    <title>Online Education & Learning System</title>
    <style>
        .login-body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .login-section {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
        }

        .login-bg-image {
            max-width: 100%;
            height: auto;
        }

        .login-wrapper {
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            width: 100%;
        }

        .account-logo-box {
            text-align: center;
        }

        .logo-title {
            font-size: 24px;
            font-weight: bold;
        }

        .logo-link img {
            max-width: 250px;
        }

        .login-description {
            font-size: 16px;
            color: #555;
        }

        .sign-in-title {
            font-size: 22px;
            font-weight: 600;
            margin-top: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .forgot-password-link {
            text-align: right;
        }

        .forgot-password {
            color: #007bff;
            text-decoration: none;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }

        .login-btn-group {
            margin-top: 20px;
        }

        .login-btn {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        .login-btn:hover {
            background-color: #218838;
        }

        .back-home-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-home {
            color: #007bff;
            text-decoration: none;
        }

        .back-home:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body class="login-body">
    <section class="login-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8 text-center">
                    <img src="../images/login-bg1.jpg" alt="Login Image" class="login-bg-image">
                </div>
                <div class="col-md-4">
                    <div class="login-wrapper">
                        <div class="account-pages">
                            <div class="account-logo-box">
                                <h2 class="logo-title">
                                    <a href="index.php" class="logo-link">
                                        <img src="../images/logo.png" alt="Logo" class="logo-img">
                                    </a>
                                </h2>
                                <p class="login-description">Please sign-in to your account and start the adventure</p>
                                <h4 class="sign-in-title">Sign In</h4>
                            </div>
    
                            <div class="account-content">
                                <form class="login-form" method="post">
                                    <div class="form-group">
                                        <input class="form-control username-input" type="text" required="" name="username" placeholder="Username or email" autocomplete="off">
                                    </div>
                                    <div class="forgot-password-link">
                                        <a href="forgot-password.php" class="forgot-password">Forgot your password?</a>
                                    </div>

                                    <div class="form-group">
                                        <input class="form-control password-input" type="password" name="password" required="" placeholder="Password" autocomplete="off">
                                    </div>

                                    <div class="form-group login-btn-group">
                                        <button class="login-btn" type="submit" name="login">Log In</button>
                                    </div>
                                </form>
                                <div class="back-home-link">
                                    <a href="../index.php" class="back-home">Back Home</a>
                                </div>
                            </div>
                        </div>   
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
