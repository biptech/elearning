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
</head>
<body class="login-body">
    <section class="login-section">
        <div class="container">
            <div class="row align-items-center">
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