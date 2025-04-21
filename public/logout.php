<?php
session_start(); // Start the session

// Unset all of the session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to the login page
echo '<script type="text/javascript"> alert("Logged Out Successfully!"); window.location.assign("index.php"); </script>';
exit;
?>