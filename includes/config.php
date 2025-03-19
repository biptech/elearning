<?php
$server = "localhost";
$username = "root";
$password = "";
$database = "elearning";

$con = mysqli_connect($server, $username, $password, $database);

if (!$con) {
    
    error_log("Database Connection Failed: " . mysqli_connect_error());

    die("Database Connection Failed: " . mysqli_connect_error());
}
?>