<?php
session_start();
include('../includes/config.php');

// Sanitize inputs
$order_id = filter_input(INPUT_GET, 'order_id', FILTER_VALIDATE_INT);
$total_price = filter_input(INPUT_GET, 'total_price', FILTER_VALIDATE_FLOAT);

if (!$order_id || !$total_price) {
    echo "Missing or invalid payment details.";
    exit();
}

// Fetch all product names for this order_id
$query = "SELECT product_name, customer_name, customer_email, customer_phone FROM orders WHERE id = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Invalid order ID.";
    exit();
}

$productNames = [];
$customerName = $customerEmail = $customerPhone = '';

while ($row = $result->fetch_assoc()) {
    $productNames[] = $row['product_name'];
    // Assuming customer info is the same for all order rows, grab once
    if (!$customerName) {
        $customerName = $row['customer_name'];
        $customerEmail = $row['customer_email'];
        $customerPhone = $row['customer_phone'];
    }
}
$stmt->close();

$orderProducts = implode(", ", $productNames);

$postFields = [
    "return_url" => "http://localhost/elearning/payment/payment-response.php",
    "website_url" => "http://localhost/elearning",
    "amount" => intval($total_price * 100), // Khalti expects integer paisa
    "purchase_order_id" => $order_id,
    "purchase_order_name" => $orderProducts,
    "customer_info" => [
        "name" => $customerName,
        "email" => $customerEmail,
        "phone" => $customerPhone,
    ],
];

$jsonData = json_encode($postFields);

$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => 'https://a.khalti.com/api/v2/epayment/initiate/',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => $jsonData,
    CURLOPT_HTTPHEADER => [
        'Authorization: Key live_secret_key_68791341fdd94846a146f0457ff7b455', // Replace with your actual key securely
        'Content-Type: application/json',
    ],
]);

$response = curl_exec($curl);

if (curl_errno($curl)) {
    echo 'Error: ' . curl_error($curl);
} else {
    $responseArray = json_decode($response, true);

    if (isset($responseArray['error'])) {
        echo 'Error: ' . $responseArray['error'];
    } elseif (isset($responseArray['payment_url'])) {
        header('Location: ' . $responseArray['payment_url']);
        exit();
    } else {
        echo 'Unexpected response: ' . $response;
    }
}

curl_close($curl);
