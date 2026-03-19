<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Load WordPress
require_once('/home/YOUR_CPANEL_USERNAME/public_html/wp-load.php');

// Get POST data
$input = json_decode(file_get_contents('php://input'), true);

$order_id = $input['order_id'] ?? '';
$name     = $input['name']     ?? '';
$phone    = $input['phone']    ?? '';
$date     = $input['date']     ?? '';
$pickup   = $input['pickup']   ?? '';
$package  = $input['package']  ?? '';
$seats    = $input['seats']    ?? '';
$total    = $input['total']    ?? '';

// Email settings
$to      = 'YOUR_ADMIN_EMAIL@gmail.com';
$subject = 'NEW BOOKING - ' . $name . ' | Rs.' . $total;
$headers = [
    'Content-Type: text/html; charset=UTF-8',
    'From: Mumbai Darshan Bus <booking@YOUR-DOMAIN.com>'
];

// Email body
$body = "
<html>
<head><meta charset='UTF-8'></head>
<body style='font-family:Arial,sans-serif; background:#f5f5f5; padding:20px; margin:0;'>
<div style='max-width:500px; margin:0 auto; background:white; border-radius:16px; overflow:hidden; box-shadow:0 5px 15px rgba(0,0,0,0.1);'>
    <div style='background:#d35400; padding:25px; text-align:center;'>
        <h2 style='color:white; margin:0; font-size:22px;'>New Booking Received!</h2>
        <p style='color:#ffe0cc; margin:8px 0 0; font-size:14px;'>Mumbai Darshan Bus</p>
    </div>
    <div style='padding:25px;'>
        <table width='100%' cellpadding='12' style='border-collapse:collapse; font-size:14px;'>
            <tr style='background:#fff9f0;'>
                <td style='font-weight:bold; color:#555; border-bottom:1px dashed #eee; width:40%;'>Order ID</td>
                <td style='color:#d35400; font-weight:bold; border-bottom:1px dashed #eee;'>{$order_id}</td>
            </tr>
            <tr>
                <td style='font-weight:bold; color:#555; border-bottom:1px dashed #eee;'>Name</td>
                <td style='border-bottom:1px dashed #eee;'>{$name}</td>
            </tr>
            <tr style='background:#fff9f0;'>
                <td style='font-weight:bold; color:#555; border-bottom:1px dashed #eee;'>Phone</td>
                <td style='border-bottom:1px dashed #eee;'>{$phone}</td>
            </tr>
            <tr>
                <td style='font-weight:bold; color:#555; border-bottom:1px dashed #eee;'>Journey Date</td>
                <td style='border-bottom:1px dashed #eee;'>{$date}</td>
            </tr>
            <tr style='background:#fff9f0;'>
                <td style='font-weight:bold; color:#555; border-bottom:1px dashed #eee;'>Pickup</td>
                <td style='border-bottom:1px dashed #eee;'>{$pickup}</td>
            </tr>
            <tr>
                <td style='font-weight:bold; color:#555; border-bottom:1px dashed #eee;'>Package</td>
                <td style='border-bottom:1px dashed #eee;'>{$package}</td>
            </tr>
            <tr style='background:#fff9f0;'>
                <td style='font-weight:bold; color:#555; border-bottom:1px dashed #eee;'>Passengers</td>
                <td style='border-bottom:1px dashed #eee;'>{$seats}</td>
            </tr>
            <tr>
                <td style='font-weight:bold; color:#555;'>Total Paid</td>
                <td style='color:#d35400; font-weight:bold; font-size:20px;'>&#8377;{$total}</td>
            </tr>
        </table>
    </div>
    <div style='background:#d35400; padding:15px; text-align:center; font-size:12px; color:white;'>
        Mumbai Darshan Bus &nbsp;|&nbsp; YOUR-DOMAIN.com
    </div>
</div>
</body>
</html>
";

// Send email using WordPress wp_mail
$sent = wp_mail($to, $subject, $body, $headers);

echo json_encode([
    'admin_email' => $sent ? 'sent' : 'failed'
]);
?>
