<?php
session_start();
require_once 'stripe_config.php'; 

// Prepare line items
$lineItems = [];
foreach ($_SESSION['tickets'] as $id => $quantity) {
    if ($quantity > 0) {
        $price = $_SESSION['ticket_price'][$id] * 100;
        $productName = $_SESSION['ticket_type'][$id];
        $lineItems[] = [
            'price_data' => [
                'currency' => 'LKR',
                'product_data' => ['name' => $productName],
                'unit_amount' => $price,
            ],
            'quantity' => $quantity,
        ];
    }
}

$params = [
    'payment_method_types[]' => 'card',
    'mode' => 'payment',
    'success_url' => 'http://localhost/ADFEntertainments/payment_success.php',
    'cancel_url' => 'http://localhost/ADFEntertainments/payment_cancel.php',
    'customer_email' => $_SESSION['buyer_email']
];

// Add line items to parameters
foreach ($lineItems as $i => $item) {
    $params["line_items[$i][price_data][currency]"] = 'usd';
    $params["line_items[$i][price_data][product_data][name]"] = $item['price_data']['product_data']['name'];
    $params["line_items[$i][price_data][unit_amount]"] = $item['price_data']['unit_amount'];
    $params["line_items[$i][quantity]"] = $item['quantity'];
}

// Send request to Stripe
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => 'https://api.stripe.com/v1/checkout/sessions',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query($params),
    CURLOPT_HTTPHEADER => [
        'Authorization: Bearer ' . STRIPE_SECRET_KEY,
        'Content-Type: application/x-www-form-urlencoded'
    ]
]);

$response = curl_exec($ch);
$error = curl_error($ch);
curl_close($ch);

if ($error) {
    http_response_code(500);
    echo json_encode(['error' => $error]);
} else {
    $session = json_decode($response, true);
    echo json_encode(['id' => $session['id']]);
}
?>