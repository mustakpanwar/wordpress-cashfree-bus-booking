<?php
// ============================================================
// CONFIGURATION FILE
// Copy this file to config.php and fill in your values
// Never commit config.php to GitHub!
// ============================================================

// ---- CASHFREE PRODUCTION ----
define('CASHFREE_PROD_APP_ID',     'YOUR_PRODUCTION_APP_ID');
define('CASHFREE_PROD_SECRET_KEY', 'YOUR_PRODUCTION_SECRET_KEY');

// ---- CASHFREE SANDBOX (Testing) ----
define('CASHFREE_TEST_APP_ID',     'YOUR_SANDBOX_APP_ID');
define('CASHFREE_TEST_SECRET_KEY', 'YOUR_SANDBOX_SECRET_KEY');

// ---- DATABASE ----
// Copy these values from your wp-config.php
define('DB_HOST',     'localhost');
define('DB_NAME',     'YOUR_DATABASE_NAME');
define('DB_USER',     'YOUR_DATABASE_USERNAME');
define('DB_PASSWORD', 'YOUR_DATABASE_PASSWORD');

// ---- ADMIN PANEL ----
define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD', 'YOUR_STRONG_PASSWORD');

// ---- BUSINESS DETAILS ----
define('BUSINESS_NAME',      'Your Business Name');
define('BUSINESS_DOMAIN',    'https://YOUR-DOMAIN.com');
define('WHATSAPP_NUMBER',    '91XXXXXXXXXX'); // With country code, no +
define('ADMIN_EMAIL',        'admin@YOUR-DOMAIN.com');
define('BOOKING_EMAIL',      'booking@YOUR-DOMAIN.com');
define('TABLE_NAME',         'bookings'); // DB table name

// ---- WORDPRESS PATH ----
define('WP_PATH', '/home/YOUR_CPANEL_USERNAME/public_html/wp-load.php');
