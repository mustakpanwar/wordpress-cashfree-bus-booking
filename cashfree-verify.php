<?php
header('Content-Type: application/json');

define('CASHFREE_APP_ID',    'YOUR_CASHFREE_APP_ID');
define('CASHFREE_SECRET_KEY','YOUR_CASHFREE_SECRET_KEY');

define('DB_HOST',     'localhost');
define('DB_NAME',     'YOUR_DB_NAME');
define('DB_USER',     'YOUR_DB_USER');
define('DB_PASSWORD', 'YOUR_DB_PASSWORD');

$order_id = $_GET['order_id'] ?? '';

// Verify payment with Cashfree Production
$ch = curl_init('https://api.cashfree.com/pg/orders/' . $order_id);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'x-client-id: '    . CASHFREE_APP_ID,
    'x-client-secret: '. CASHFREE_SECRET_KEY,
    'x-api-version: 2023-08-01'
]);
$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

// If PAID save to database
if (isset($data['order_status']) && $data['order_status'] === 'PAID') {

    $name    = $_GET['name']    ?? '';
    $phone   = $_GET['phone']   ?? '';
    $date    = $_GET['date']    ?? '';
    $pickup  = $_GET['pickup']  ?? '';
    $package = $_GET['package'] ?? '';
    $seats   = intval($_GET['seats'] ?? 1);
    $total   = intval($_GET['total'] ?? 0);

    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if (!$conn->connect_error) {
        // Prevent duplicate entries
        $check = $conn->prepare("SELECT id FROM mumbai_darshan_bookings WHERE order_id = ?");
        $check->bind_param("s", $order_id);
        $check->execute();
        $check->store_result();

        if ($check->num_rows === 0) {
            $stmt = $conn->prepare("INSERT INTO mumbai_darshan_bookings
                (order_id, name, phone, journey_date, pickup, package, seats, total_amount, payment_status)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'PAID')");
            $stmt->bind_param("ssssssii",
                $order_id, $name, $phone, $date, $pickup, $package, $seats, $total
            );
            $stmt->execute();
            $stmt->close();
        }

        $check->close();
        $conn->close();
    }
}

echo json_encode($data);
?>
