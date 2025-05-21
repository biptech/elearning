<?php
session_start();
include('../includes/config.php');

if (!isset($_SESSION['u_id'])) {
    http_response_code(403);
    echo "Not logged in.";
    exit();
}

$user_id = $_SESSION['u_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $pid = intval($_POST['product_id']);

    try {
        $conn = new PDO("mysql:host=$server;dbname=$database", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND pid = ?");
        $stmt->execute([$user_id, $pid]);

        echo "Removed";
    } catch (PDOException $e) {
        http_response_code(500);
        echo "Error: " . $e->getMessage();
    }
} else {
    http_response_code(400);
    echo "Invalid request.";
}
