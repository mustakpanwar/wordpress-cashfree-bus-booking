<?php
header('Content-Type: application/json');

define('CASHFREE_APP_ID', 'YOUR_SANDBOX_APP_ID');
define('CASHFREE_SECRET_KEY', 'YOUR_SANDBOX_SECRET_KEY');

$order_id = $_GET['order_id'] ?? '';

$ch = curl_init('https://sandbox.cashfree.com/pg/orders/' . $order_id);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'x-client-id: ' . CASHFREE_APP_ID,
    'x-client-secret: ' . CASHFREE_SECRET_KEY,
    'x-api-version: 2023-08-01'
]);

$response = curl_exec($ch);
curl_close($ch);

echo $response;
?>
