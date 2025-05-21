<?php
if (isset($_POST['email'])) {
    $email = $_POST['email'];
    $key = "vQrkeOQAO5UA9MDB4AgWd"; // Your API key
    $url = "https://apps.emaillistverify.com/api/verifyEmail?secret=".$key."&email=".$email;

    // Initialize cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    echo $response; // Respond with the raw response (either 'ok' or something else)
}
