<?php
// process_payment.php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $paymentType = $_POST['paymentType'];
    $details = [];
    $billing = [
        'name' => $_POST['name'],
        'email' => $_POST['email'],
        'phone' => $_POST['phone'],
        'address' => [
            'line1' => $_POST['address1'],
            'line2' => $_POST['address2'],
            'city' => $_POST['city'],
            'state' => $_POST['state'],
            'postal_code' => $_POST['postalCode'],
            'country' => $_POST['country']
        ]
    ];

    if ($paymentType === 'card') {
        $details = [
            'card_number' => $_POST['cardNumber'],
            'exp_month' => (int)$_POST['expMonth'],
            'exp_year' => (int)$_POST['expYear'],
            'cvc' => $_POST['cvc']
        ];
    }

    $result = createPaymentMethod($paymentType, $details, $billing);
    
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;
}

function createPaymentMethod($paymentType, $details, $billing) {
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.paymongo.com/v1/payment_methods",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode([
            'data' => [
                'attributes' => [
                    'type' => $paymentType,
                    'details' => $details,
                    'billing' => $billing
                ]
            ]
        ]),
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "accept: application/json",
            "authorization: Basic " . base64_encode("sk_test_DGRY7JJHe7Vxt9RV26ncNGoa:")
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    if ($err) {
        return ["error" => "cURL Error #:" . $err];
    } else {
        return json_decode($response, true);
    }
}