<?php
require 'vendor/autoload.php'; // Include Guzzle if you're using it

use GuzzleHttp\Client;

function createPaymentIntent($amount, $currency = 'PHP') {
    $secretKey = getenv('sk_test_Q8AjFku7zPYSw5epnJuStDAJ'); // Replace with your PayMongo secret key
    $url = 'https://api.paymongo.com/v1/payment_intents';

    // Create a new Guzzle HTTP client
    $client = new Client([
        'base_uri' => 'https://api.paymongo.com/',
    ]);

    try {
        // Prepare data to create a payment intent
        $response = $client->request('POST', $url, [
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode($secretKey . ':'),
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'json' => [
                'data' => [
                    'attributes' => [
                        'amount' => $amount * 100, // Amount in centavos (for PHP, 100 PHP = 10000 centavos)
                        'payment_method_allowed' => ['card', 'paymaya'],
                        'currency' => $currency,
                        'capture_type' => 'automatic',
                    ]
                ]
            ]
        ]);

        // Decode the JSON response
        $responseBody = (string)$response->getBody();
        $intent = json_decode($responseBody, true); // Convert JSON to array

        // Check if decoding was successful
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Failed to decode JSON response: ' . json_last_error_msg());
        }

        // Check if the necessary data exists
        if (!isset($intent['data']) || !isset($intent['data']['attributes']['client_key'])) {
            throw new \Exception('Invalid response structure or missing data.');
        }

        // Return the Payment Intent data
        return $intent['data'];
        
    } catch (\Exception $e) {
        // Log the error and return a readable message
        error_log($e->getMessage());
        return 'Error: ' . $e->getMessage();
    }
}

// Example: Create a payment intent for 500 PHP
$paymentIntent = createPaymentIntent(500);

// Safeguard in case of failure
if (is_array($paymentIntent) && isset($paymentIntent['attributes']['client_key'])) {
    $clientKey = $paymentIntent['attributes']['client_key'];
    $redirectUrl = "https://paymongo.page.link/?client_key=$clientKey"; // Example redirect URL
} else {
    $redirectUrl = null; // Handle error state
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Payment Link</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Payment Link</h1>

        <?php if ($redirectUrl): ?>
            <p>Click the link below to make a payment:</p>
            <a href="<?php echo htmlspecialchars($redirectUrl, ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-primary">
                Pay Now (PHP 500)
            </a>
        <?php else: ?>
            <p>Failed to create payment link. Please check your API credentials or server response.</p>
        <?php endif; ?>
    </div>
</body>
</html>
