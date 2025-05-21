<?php
session_start();
include('../includes/config.php');

// Check if 'pidx' is provided
if (isset($_GET['pidx'])) {
    $pidx = $_GET['pidx'];

    // Call Khalti's payment lookup API
    $lookup_payload = json_encode(["pidx" => $pidx]);
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://a.khalti.com/api/v2/epayment/lookup/',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => array(
            'Authorization: Key live_secret_key_68791341fdd94846a146f0457ff7b455', // Replace with your actual key
            'Content-Type: application/json',
        ),
        CURLOPT_POSTFIELDS => $lookup_payload,
    ]);

    $response = curl_exec($curl);

    // Log cURL errors
    if (curl_errno($curl)) {
        file_put_contents('khalti_lookup_error_log.txt', curl_error($curl));
        echo "<script>alert('cURL error: Check khalti_lookup_error_log.txt');</script>";
        curl_close($curl);
        exit();
    }

    curl_close($curl);

    // Parse and log the response
    $responseArray = json_decode($response, true);
    file_put_contents('khalti_lookup_response_log.txt', print_r($responseArray, true));

    if (isset($responseArray['status']) && $responseArray['status'] === 'Completed') {
        // Update payment status in the database
        $stmt = $con->prepare("UPDATE `orders` SET payment_status = 'completed' WHERE id = ?");
        $stmt->bind_param("s", $responseArray['purchase_order_id']);
        $stmt->execute();
        $stmt->close();

        echo "<script>alert('Payment Successful! Thank you for your purchase.'); window.location.href='view_products.php';</script>";
    } else {
        // Handle failed or pending payment
        echo "<script>alert('Payment Failed or Pending. Please try again.'); window.location.href='view_products.php';</script>";
    }
} else {
    echo "<script>alert('Invalid Payment Request.'); window.location.href='view_products.php';</script>";
}
?>