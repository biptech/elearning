<?php
session_start();
include('../includes/config.php');

// Error Reporting for Debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Ensure user is logged in
if (!isset($_SESSION['username'])) {
    echo "<script>alert('Please log in to remove items from your cart.'); window.location.href='login.php';</script>";
    exit();
}

// Database Connection
try {
    $conn = new PDO("mysql:host=$server;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}

// Get User ID from Session
$user_id = $_SESSION['username'] ?? null;

if (!$user_id) {
    echo "<script>alert('You must be logged in to remove items.'); window.location='index.php';</script>";
    exit();
}

// Ensure cart_id is provided and valid
if (isset($_GET['cart_id'])) {
    $cart_id = intval($_GET['cart_id']);
    
    // Ensure this cart item belongs to the logged-in user
    $check_item = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND id = ?");
    $check_item->execute([$user_id, $cart_id]);
    
    if ($check_item->rowCount() > 0) {
        // Remove the specific cart item
        $remove_item = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
        $remove_item->execute([$cart_id, $user_id]);
        
        // Redirect to cart page after removal
        echo "<script>window.location.href='cart.php';</script>";
    } else {
        echo "<script>alert('Invalid cart item or access denied.'); window.location.href='cart.php';</script>";
    }
} else {
    echo "<script>alert('No cart item selected.'); window.location.href='cart.php';</script>";
}
?>