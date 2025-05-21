<?php
session_start(); // Start session

// Check if user is logged in and session is valid
if (!isset($_SESSION['user_profile_id'])) {
    // Redirect to login or show an error if session is not set
    header('Location: login.php');
    exit();
}

// Retrieve user_profile_id from session
$user_profile_id = $_SESSION['user_profile_id'];

// PayMongo integration for PHP 2000 fixed downpayment
$description = "Downpayment for registration (User ID: $user_profile_id)"; // Include user_profile_id in description
$amount = 2000; // Fixed amount of 2000 PHP

$remarks = isset($_GET['remarks']) ? $_GET['remarks'] : 'Registration'; // Default to registration if no remarks
$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => "https://api.paymongo.com/v1/links",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => json_encode([
        "data" => [
            "attributes" => [
                "amount" => intval($amount * 100), // Convert to cents
                "description" => $description, // Updated description with user_profile_id
                "remarks" => $remarks,
                // Optionally, you can pass user_profile_id in metadata
                "metadata" => [
                    "user_profile_id" => $user_profile_id
                ]
            ]
        ]
    ]),
    CURLOPT_HTTPHEADER => [
        "accept: application/json",
        "authorization: Basic c2tfdGVzdF9ROEFqRmt1N3pQWVN3NWVwbkp1U3REQUo6a2Vuc2tpZTEyMw==", // Change with your actual API key
        "content-type: application/json"
    ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);

if ($err) {
    echo "cURL Error #:" . $err;
} else {
    $result = json_decode($response, true);
    if (isset($result['data']['attributes']['checkout_url'])) {
        // Redirect to PayMongo checkout page
        header("Location: " . $result['data']['attributes']['checkout_url']);
        exit;
    } else {
        echo "Error creating payment link. Please try again.";
    }
}
?>
