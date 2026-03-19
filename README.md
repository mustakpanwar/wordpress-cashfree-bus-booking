<<<<<<< HEAD
# 🚌 WordPress Cashfree Bus Booking System

A complete bus booking and payment system for WordPress websites using **Cashfree Payment Gateway**. Includes booking form, payment processing, database storage, admin dashboard, email notifications, and receipt download.

---

## 📋 Table of Contents

- [Features](#features)
- [Requirements](#requirements)
- [Repository Structure](#repository-structure)
- [Installation Guide](#installation-guide)
  - [Step 1 — Cashfree Setup](#step-1--cashfree-setup)
  - [Step 2 — Database Setup](#step-2--database-setup)
  - [Step 3 — Upload Files](#step-3--upload-files)
  - [Step 4 — Configure Files](#step-4--configure-files)
  - [Step 5 — WordPress Setup](#step-5--wordpress-setup)
  - [Step 6 — Email Setup](#step-6--email-setup)
  - [Step 7 — Admin Panel](#step-7--admin-panel)
- [How to Add Book Now Buttons](#how-to-add-book-now-buttons)
- [Testing Guide](#testing-guide)
- [Going Live (Production)](#going-live-production)
- [File Reference](#file-reference)
- [Troubleshooting](#troubleshooting)

---

## ✨ Features

- ✅ Online bus seat booking form
- ✅ Cashfree payment gateway integration
- ✅ Zone-based dynamic pricing
- ✅ Popup booking form (works on any page)
- ✅ Database storage of all bookings
- ✅ Admin dashboard with login
- ✅ Delete bookings with password protection
- ✅ Admin email notification after payment
- ✅ WhatsApp confirmation redirect
- ✅ PDF/PNG receipt download
- ✅ Mobile friendly responsive design
- ✅ Sandbox & Production modes
- ✅ Duplicate payment prevention

---

## ⚙️ Requirements

- WordPress website (any theme)
- cPanel hosting with PHP 7.4+
- Cashfree merchant account
- cPanel email account (for sending emails)
- MySQL database (already exists with WordPress)

---

## 📁 Repository Structure

```
📦 wordpress-cashfree-booking
 ┣ 📄 cashfree-order.php          → Creates payment order
 ┣ 📄 cashfree-verify.php         → Verifies payment & saves to DB
 ┣ 📄 cashfree-order-sandbox.php  → Sandbox version of order file
 ┣ 📄 cashfree-verify-sandbox.php → Sandbox version of verify file
 ┣ 📄 payment-status.php          → Payment success/failure page (production)
 ┣ 📄 payment-status-sandbox.php  → Payment success/failure page (sandbox)
 ┣ 📄 send-mail.php               → Sends admin email after payment
 ┣ 📄 booking-form-cashfree.html  → Main booking form (embed in page)
 ┣ 📄 booking-form-popup.html     → Popup booking form (global)
 ┣ 📄 admin-login.php             → Admin panel login page
 ┣ 📄 admin-dashboard.php         → Bookings management dashboard
 ┣ 📄 admin-logout.php            → Admin logout
 ┣ 📄 bookings-admin.php          → Simple bookings view (password protected)
 ┣ 📄 database.sql                → Database table creation SQL
 ┗ 📄 functions-snippets.php      → Code snippets for WordPress functions.php
```

---

## 🚀 Installation Guide

### Step 1 — Cashfree Setup

1. Create account at [cashfree.com](https://cashfree.com)
2. Complete KYC verification
3. Go to **Developers → API Keys**
4. Copy your **App ID** and **Secret Key**
5. For testing, switch to **Test Mode** and copy test keys separately

---

### Step 2 — Database Setup

1. Go to **cPanel → phpMyAdmin**
2. Click your WordPress database in the left sidebar
3. Click the **SQL** tab
4. Paste and run this query:

```sql
CREATE TABLE mumbai_darshan_bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id VARCHAR(100) NOT NULL,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(15) NOT NULL,
    journey_date DATE NOT NULL,
    pickup VARCHAR(100) NOT NULL,
    package VARCHAR(100) NOT NULL,
    seats INT NOT NULL,
    total_amount INT NOT NULL,
    payment_status VARCHAR(20) DEFAULT 'PAID',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

### Step 3 — Upload Files

Upload all PHP and HTML files to your **WordPress root folder** (same level as `wp-config.php`) via **cPanel → File Manager**.

```
public_html/
 ┣ wp-config.php          ← WordPress file (already here)
 ┣ cashfree-order.php     ← Upload here
 ┣ cashfree-verify.php    ← Upload here
 ┣ payment-status.php     ← Upload here
 ┣ send-mail.php          ← Upload here
 ┣ booking-form-popup.html← Upload here
 ┣ admin-login.php        ← Upload here
 ┣ admin-dashboard.php    ← Upload here
 ┗ admin-logout.php       ← Upload here
```

---

### Step 4 — Configure Files

#### `cashfree-order.php`
```php
define('CASHFREE_APP_ID',    'YOUR_PRODUCTION_APP_ID');
define('CASHFREE_SECRET_KEY','YOUR_PRODUCTION_SECRET_KEY');
```
Also update the return URL with your domain:
```php
"return_url" => "https://YOUR-DOMAIN.com/payment-status/?order_id={order_id}&token=..."
```

#### `cashfree-verify.php`
```php
define('CASHFREE_APP_ID',    'YOUR_PRODUCTION_APP_ID');
define('CASHFREE_SECRET_KEY','YOUR_PRODUCTION_SECRET_KEY');

define('DB_HOST',     'localhost');
define('DB_NAME',     'YOUR_DATABASE_NAME');    // from wp-config.php
define('DB_USER',     'YOUR_DATABASE_USER');    // from wp-config.php
define('DB_PASSWORD', 'YOUR_DATABASE_PASSWORD');// from wp-config.php
```

#### `send-mail.php`
```php
// Update admin email
$to = 'YOUR_ADMIN_EMAIL@gmail.com';

// Update WordPress path
require_once('/home/YOUR_CPANEL_USERNAME/public_html/wp-load.php');
```

#### `admin-login.php` & `admin-dashboard.php`
```php
// Change admin credentials
if ($username === 'admin' && $password === 'YOUR_PASSWORD') {

// Update DB credentials in admin-dashboard.php
define('DB_NAME',     'YOUR_DATABASE_NAME');
define('DB_USER',     'YOUR_DATABASE_USER');
define('DB_PASSWORD', 'YOUR_DATABASE_PASSWORD');
```

#### `booking-form-cashfree.html`
Update the Cashfree mode and fetch URL:
```js
// For production
const cashfree = Cashfree({ mode: "production" });

// Fetch URL
const res = await fetch('/cashfree-order.php', {
```

Update pickup locations, pricing and WhatsApp number to match your business:
```js
// Pricing
if (packageType === "basic") pricePerPerson = 200;

// WhatsApp
window.location.href = "https://wa.me/91XXXXXXXXXX?text=" + message;
```

---

### Step 5 — WordPress Setup

Add these snippets to your theme's `functions.php` file:

```php
// 1. Load popup booking form on every page
add_action('wp_head', function() {
    include(ABSPATH . 'booking-form-popup.html');
});

// 2. Production payment status shortcode
add_shortcode('payment_status', function() {
    $order_id = $_GET['order_id'] ?? '';
    $token    = $_GET['token']    ?? '';
    ob_start();
    include(ABSPATH . 'payment-status.php');
    return ob_get_clean();
});

// 3. Sandbox payment status shortcode (for testing)
add_shortcode('payment_status_sandbox', function() {
    $order_id = $_GET['order_id'] ?? '';
    $token    = $_GET['token']    ?? '';
    ob_start();
    include(ABSPATH . 'payment-status-sandbox.php');
    return ob_get_clean();
});
```

#### Create WordPress Pages

**Payment Status Page (Production):**
1. Go to **WordPress → Pages → Add New**
2. Title: `Payment Status`
3. Slug: `payment-status`
4. Add HTML block with: `[payment_status]`
5. Publish

**Payment Status Page (Sandbox/Testing):**
1. Go to **WordPress → Pages → Add New**
2. Title: `Payment Status Sandbox`
3. Slug: `payment-status-sandbox`
4. Add HTML block with: `[payment_status_sandbox]`
5. Publish

**Booking Form Page (optional):**
1. Go to **WordPress → Pages → Add New**
2. Add HTML block
3. Paste entire content of `booking-form-cashfree.html`
4. Publish

---

### Step 6 — Email Setup

The system uses WordPress `wp_mail()` to send admin emails. No extra installation needed.

To ensure emails work:
1. Go to **cPanel → Email Accounts**
2. Create email: `booking@yourdomain.com`
3. This email will appear as the sender

To test email:
```php
// Create test-mail.php in root
<?php
require_once('/home/USERNAME/public_html/wp-load.php');
$sent = wp_mail('your@email.com', 'Test', 'Working!');
echo $sent ? 'Sent!' : 'Failed!';
?>
```
Visit: `https://yourdomain.com/test-mail.php`

---

### Step 7 — Admin Panel

Access your admin panel at:
```
https://yourdomain.com/admin-login.php
```

Default credentials (change in `admin-login.php`):
```
Username: admin
Password: YOUR_PASSWORD
```

Features:
- View all bookings
- Filter by date
- See total revenue
- See today's bookings
- Delete bookings (requires password confirmation)

---

## 🔘 How to Add Book Now Buttons

After setup, add a Book Now button **anywhere** on your WordPress site using an HTML widget:

```html
<!-- Simple button -->
<button class="open-booking-popup">🚌 Book Now</button>

<!-- As a link -->
<a href="javascript:void(0)" class="open-booking-popup">🚌 Book Now</a>

<!-- Custom text -->
<button class="open-booking-popup">⚡ Reserve Your Seat</button>
```

The popup will open automatically when clicked. No page ID or URL needed.

---

## 🧪 Testing Guide

**Step 1** — Switch to sandbox mode:

In `cashfree-order.php`:
```php
// Use sandbox URL
$ch = curl_init('https://sandbox.cashfree.com/pg/orders');

// Use test keys
define('CASHFREE_APP_ID',    'YOUR_TEST_APP_ID');
define('CASHFREE_SECRET_KEY','YOUR_TEST_SECRET_KEY');
```

In `booking-form-cashfree.html`:
```js
const cashfree = Cashfree({ mode: "sandbox" });
const res = await fetch('/cashfree-order-sandbox.php', {
```

**Step 2** — Use test card:

| Field | Value |
|---|---|
| Card Number | `4111 1111 1111 1111` |
| Expiry | Any future date |
| CVV | `123` |
| OTP | `123456` |

**Step 3** — Verify in DB:
- Go to **phpMyAdmin → mumbai_darshan_bookings**
- Check if booking was saved correctly

**Step 4** — Check email:
- Admin email should arrive at configured address

---

## 🚀 Going Live (Production)

Checklist before going live:

| File | Change |
|---|---|
| `cashfree-order.php` | Use production keys + `api.cashfree.com` URL |
| `cashfree-verify.php` | Use production keys + `api.cashfree.com` URL |
| `booking-form-cashfree.html` | `mode: "production"` + `/cashfree-order.php` |
| `send-mail.php` | Correct admin email |
| `admin-login.php` | Change default password |

Do a **₹1 test payment** in production to confirm everything works before announcing to customers.

---

## 📄 File Reference

| File | Purpose | Location |
|---|---|---|
| `cashfree-order.php` | Creates Cashfree payment order | Root |
| `cashfree-verify.php` | Verifies payment & saves to DB | Root |
| `cashfree-order-sandbox.php` | Same as above for testing | Root |
| `cashfree-verify-sandbox.php` | Same as above for testing | Root |
| `payment-status.php` | Shows success/fail page content | Root |
| `payment-status-sandbox.php` | Same as above for testing | Root |
| `send-mail.php` | Sends admin notification email | Root |
| `booking-form-cashfree.html` | Main booking form HTML | Root |
| `booking-form-popup.html` | Popup version of booking form | Root |
| `admin-login.php` | Admin panel login | Root |
| `admin-dashboard.php` | Admin bookings dashboard | Root |
| `admin-logout.php` | Admin logout | Root |

---

## 🔧 Troubleshooting

### Payment initiation failed
- Check API keys are correct
- Verify URL is correct (sandbox vs production)
- Check cURL is enabled on server

### Data not saving to DB
- Check DB credentials in `cashfree-verify.php`
- Verify table exists in phpMyAdmin
- Check booking form is sending all fields in fetch call

### Email not received
- Check spam/junk folder
- Run `test-mail.php` to verify wp_mail works
- Check admin email address in `send-mail.php`

### Popup not working
- Make sure `booking-form-popup.html` is uploaded to root
- Check `functions.php` has the `wp_head` hook
- Clear browser cache

### Admin dashboard shows blank
- Check DB credentials in `admin-dashboard.php`
- Make sure you are logged in via `admin-login.php`

### 404 on payment status page
- Check WordPress page slug matches return URL
- Go to **Settings → Permalinks → Save** to refresh

---

## 📞 Support

Built for **Mumbai Darshan Bus** — a Mumbai city tour bus booking system.

For issues or questions, open a GitHub issue.
=======
# wordpress-cashfree-bus-booking
Complete WordPress bus booking system with Cashfree payment gateway
# 🚌 WordPress Cashfree Bus Booking System

A complete **bus booking and online payment system** for WordPress websites, built with **Cashfree Payment Gateway**.

> Built for [Mumbai Darshan Bus](https://mumbaidarshanbus.co) — a full-day Mumbai city tour bus service.

---

## ✨ What It Does

- 🎟 Online seat booking form with zone-based pricing
- 💳 Cashfree payment gateway (UPI, Cards, NetBanking)
- 🗄 Saves all bookings to MySQL database
- 📧 Admin email notification after every payment
- 💬 WhatsApp confirmation redirect for customers
- 📄 PNG receipt download after payment
- 🔐 Secure admin dashboard to manage bookings
- 🪟 Popup booking form — works on any page
- 📱 Fully mobile responsive

---

## 🗂 What's Inside

```
📁 wordpress-cashfree-bus-booking/
├── booking-form-cashfree.html      → Main booking form
├── booking-form-popup.html         → Popup version of form
├── cashfree-order.php              → Creates payment order
├── cashfree-verify.php             → Verifies payment & saves to DB
├── cashfree-order-sandbox.php      → Sandbox/test version
├── cashfree-verify-sandbox.php     → Sandbox/test version
├── payment-status.php              → Success/failure page
├── payment-status-sandbox.php      → Sandbox/test version
├── send-mail.php                   → Admin email notification
├── admin-login.php                 → Admin panel login
├── admin-dashboard.php             → Bookings management
├── admin-logout.php                → Admin logout
├── bookings-admin.php              → Simple bookings view
├── database.sql                    → Database table setup
├── functions-snippets.php          → WordPress functions.php code
├── config.example.php              → Configuration template
└── README.md                       → Full setup guide
```

---

## ⚡ Quick Start

1. Create a [Cashfree](https://cashfree.com) merchant account
2. Run `database.sql` in phpMyAdmin
3. Upload all PHP files to WordPress root
4. Fill in your credentials (see `config.example.php`)
5. Add shortcodes to WordPress pages
6. Add `Book Now` buttons anywhere

📖 **Read the full setup guide:** [`README.md`](./wordpress-cashfree-bus-booking/README.md)

---

## 🛠 Tech Stack

![WordPress](https://img.shields.io/badge/WordPress-21759B?style=flat&logo=wordpress&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=flat&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=flat&logo=mysql&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=flat&logo=javascript&logoColor=black)
![Cashfree](https://img.shields.io/badge/Cashfree-00BCD4?style=flat&logoColor=white)

---

## 📋 Requirements

- WordPress website
- PHP 7.4+
- cPanel hosting
- Cashfree merchant account
- MySQL database

---

## 📸 Features Preview

| Feature | Description |
|---|---|
| Booking Form | Zone-based pricing, pickup locations, package selection |
| Payment | UPI, Cards, NetBanking via Cashfree |
| Admin Panel | View, filter and delete bookings |
| Email | HTML email notification to admin |
| Receipt | Downloadable PNG receipt for customer |
| Popup | One-click popup form on any page |
>>>>>>> ccb22007a796d2094708dd1489b0b749eaaff901

---

## 📝 License

<<<<<<< HEAD
MIT License — free to use and modify for your own projects.
=======
MIT License — free to use and modify.

---

## 🙏 Credits

Developed for **Mumbai Darshan Bus Services**
Website: [mumbaidarshanbus.co](https://mumbaidarshanbus.co)
>>>>>>> ccb22007a796d2094708dd1489b0b749eaaff901
