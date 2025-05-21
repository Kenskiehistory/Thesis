<?php

function getPaymentDetails($paymentId) {
    $secretKey = 'sk_test_Q8AjFku7zPYSw5epnJuStDAJ'; // Replace with your actual PayMongo secret key
    $url = 'https://api.paymongo.com/v1/payments/' . $paymentId;

    // Initialize cURL
    $ch = curl_init();

    // Set the options for the cURL request
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Basic ' . base64_encode($secretKey . ':'),
        'Accept: application/json',
    ]);

    // Execute the request
    $response = curl_exec($ch);

    // Handle cURL errors
    if(curl_errno($ch)) {
        return 'Error: ' . curl_error($ch);
    }

    // Close cURL resource
    curl_close($ch);

    // Parse the response
    $paymentDetails = json_decode($response, true);
    return $paymentDetails;
}

// Replace with the actual payment ID you want to view
$paymentId = 'pay_tns5h3G9NPs8UDPQjNaRmUnZ'; 

$paymentDetails = getPaymentDetails($paymentId);

// Output payment details
echo '<pre>';
print_r($paymentDetails);
echo '</pre>';
