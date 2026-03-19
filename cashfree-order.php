<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

define('CASHFREE_APP_ID',    'YOUR_CASHFREE_APP_ID');
define('CASHFREE_SECRET_KEY','YOUR_CASHFREE_SECRET_KEY');

$input    = json_decode(file_get_contents('php://input'), true);
$order_id = 'MD_' . time() . '_' . rand(1000,9999);

$name    = $input['name']    ?? '';
$phone   = $input['phone']   ?? '';
$date    = $input['date']    ?? '';
$pickup  = $input['pickup']  ?? '';
$package = $input['package'] ?? '';
$seats   = $input['seats']   ?? 1;
$total   = $input['total']   ?? 0;

// Encode booking data into token
$bookingData = base64_encode(json_encode([
    'name'    => $name,
    'phone'   => $phone,
    'date'    => $date,
    'pickup'  => $pickup,
    'package' => $package,
    'seats'   => $seats,
    'total'   => $total
]));

$payload = [
    "order_id"         => $order_id,
    "order_amount"     => $input['amount'],
    "order_currency"   => "INR",
    "customer_details" => [
        "customer_id"    => 'CUST_' . $phone,
        "customer_name"  => $name,
        "customer_phone" => $phone,
        "customer_email" => "customer@YOUR-DOMAIN.com"
    ],
    "order_meta" => [
        "return_url" => "https://YOUR-DOMAIN.com/payment-status/?order_id={order_id}&token=" . urlencode($bookingData)
    ]
];

$ch = curl_init('https://api.cashfree.com/pg/orders');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'x-client-id: '    . CASHFREE_APP_ID,
    'x-client-secret: '. CASHFREE_SECRET_KEY,
    'x-api-version: 2023-08-01'
]);

echo curl_exec($ch);
curl_close($ch);
?>
