<?php
// ============================================================
// WordPress Cashfree Bus Booking System
// Add these snippets to your theme's functions.php file
// ============================================================

// 1. Load popup booking form on every page (invisibly in header)
add_action('wp_head', function() {
    include(ABSPATH . 'booking-form-popup.html');
});

// 2. Production payment status shortcode
// Usage: Add [payment_status] to your Payment Status page
add_shortcode('payment_status', function() {
    $order_id = $_GET['order_id'] ?? '';
    $token    = $_GET['token']    ?? '';
    ob_start();
    include(ABSPATH . 'payment-status.php');
    return ob_get_clean();
});

// 3. Sandbox payment status shortcode (for testing only)
// Usage: Add [payment_status_sandbox] to your Sandbox Payment Status page
add_shortcode('payment_status_sandbox', function() {
    $order_id = $_GET['order_id'] ?? '';
    $token    = $_GET['token']    ?? '';
    ob_start();
    include(ABSPATH . 'payment-status-sandbox.php');
    return ob_get_clean();
});

// ============================================================
// OPTIONAL: Load popup only on non-homepage pages
// Useful if homepage already has the booking form embedded
// Uncomment below and comment out the wp_head hook above
// ============================================================

// add_action('wp_head', function() {
//     if (!is_front_page()) {
//         include(ABSPATH . 'booking-form-popup.html');
//     }
// });
