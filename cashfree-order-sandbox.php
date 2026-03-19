<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

define('CASHFREE_APP_ID', 'YOUR_SANDBOX_APP_ID');
define('CASHFREE_SECRET_KEY', 'YOUR_SANDBOX_SECRET_KEY');

$input = json_decode(file_get_contents('php://input'), true);
$order_id = 'MD_TEST_' . time() . '_' . rand(1000,9999);

$payload = [
    "order_id"         => $order_id,
    "order_amount"     => $input['amount'],
    "order_currency"   => "INR",
    "customer_details" => [
        "customer_id"    => 'CUST_' . $input['phone'],
        "customer_name"  => $input['name'],
        "customer_phone" => $input['phone'],
        "customer_email" => "test@mumbaidarshan.com"
    ],
    "order_meta" => [
        "return_url" => "https://YOUR-DOMAIN.com/payment-status-sandbox/?order_id={order_id}"
    ]
];

$ch = curl_init('https://sandbox.cashfree.com/pg/orders');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'x-client-id: ' . CASHFREE_APP_ID,
    'x-client-secret: ' . CASHFREE_SECRET_KEY,
    'x-api-version: 2023-08-01'
]);

echo curl_exec($ch);
curl_close($ch);
?>
