# 🚌 WordPress Cashfree Bus Booking System

A complete bus booking and payment system for WordPress websites using the **Cashfree Payment Gateway**. Built for tour bus operators, travel agencies, or any business that needs online seat booking with payment collection.

---

## ✨ Features

- ✅ Online bus seat booking form
- ✅ Cashfree payment gateway integration
- ✅ Popup booking form (works on any page)
- ✅ Zone-based dynamic pricing
- ✅ Database storage of all bookings
- ✅ Admin dashboard with secure login
- ✅ Delete bookings with password protection
- ✅ Admin email notification after payment
- ✅ WhatsApp confirmation redirect
- ✅ PNG receipt download
- ✅ Mobile friendly responsive design
- ✅ Sandbox (testing) & Production (live) modes
- ✅ Duplicate payment prevention

---

## ⚙️ Requirements

- WordPress website (any theme)
- cPanel hosting with PHP 7.4+
- Cashfree merchant account
- MySQL database (already exists with WordPress)

---

## 📁 File Structure

```
📦 wordpress-cashfree-bus-booking/
│
├── 📄 README.md
├── 📄 .gitignore
├── 📄 config.example.php
├── 📄 database.sql
├── 📄 functions-snippets.php
│
├── 📄 booking-form-cashfree.html
├── 📄 booking-form-popup.html
│
├── 📄 cashfree-order.php
├── 📄 cashfree-order-sandbox.php
├── 📄 cashfree-verify.php
├── 📄 cashfree-verify-sandbox.php
│
├── 📄 payment-status.php
├── 📄 payment-status-sandbox.php
│
├── 📄 send-mail.php
│
├── 📄 admin-login.php
├── 📄 admin-dashboard.php
├── 📄 admin-logout.php
└── 📄 bookings-admin.php
```

---

## 🔄 Sandbox vs Production — What Changes

> This is the most important section.
> When switching between **testing (sandbox)** and **live (production)**,
> these are the exact files and lines you need to change.

---

### 📄 `cashfree-order-sandbox.php` → `cashfree-order.php`

| What | Sandbox | Production |
|---|---|---|
| App ID | `YOUR_SANDBOX_APP_ID` | `YOUR_PRODUCTION_APP_ID` |
| Secret Key | `YOUR_SANDBOX_SECRET_KEY` | `YOUR_PRODUCTION_SECRET_KEY` |
| API URL | `sandbox.cashfree.com/pg/orders` | `api.cashfree.com/pg/orders` |
| Return URL | `/payment-status-sandbox/` | `/payment-status/` |

```php
// SANDBOX
define('CASHFREE_APP_ID',    'YOUR_SANDBOX_APP_ID');
define('CASHFREE_SECRET_KEY','YOUR_SANDBOX_SECRET_KEY');
$ch = curl_init('https://sandbox.cashfree.com/pg/orders');
"return_url" => "https://YOUR-DOMAIN.com/payment-status-sandbox/?order_id={order_id}&token=..."

// PRODUCTION
define('CASHFREE_APP_ID',    'YOUR_PRODUCTION_APP_ID');
define('CASHFREE_SECRET_KEY','YOUR_PRODUCTION_SECRET_KEY');
$ch = curl_init('https://api.cashfree.com/pg/orders');
"return_url" => "https://YOUR-DOMAIN.com/payment-status/?order_id={order_id}&token=..."
```

---

### 📄 `cashfree-verify-sandbox.php` → `cashfree-verify.php`

| What | Sandbox | Production |
|---|---|---|
| App ID | `YOUR_SANDBOX_APP_ID` | `YOUR_PRODUCTION_APP_ID` |
| Secret Key | `YOUR_SANDBOX_SECRET_KEY` | `YOUR_PRODUCTION_SECRET_KEY` |
| API URL | `sandbox.cashfree.com/pg/orders/` | `api.cashfree.com/pg/orders/` |

```php
// SANDBOX
define('CASHFREE_APP_ID',    'YOUR_SANDBOX_APP_ID');
define('CASHFREE_SECRET_KEY','YOUR_SANDBOX_SECRET_KEY');
$ch = curl_init('https://sandbox.cashfree.com/pg/orders/' . $order_id);

// PRODUCTION
define('CASHFREE_APP_ID',    'YOUR_PRODUCTION_APP_ID');
define('CASHFREE_SECRET_KEY','YOUR_PRODUCTION_SECRET_KEY');
$ch = curl_init('https://api.cashfree.com/pg/orders/' . $order_id);
```

---

### 📄 `booking-form-cashfree.html`

| What | Sandbox | Production |
|---|---|---|
| Cashfree mode | `"sandbox"` | `"production"` |
| Fetch URL | `/cashfree-order-sandbox.php` | `/cashfree-order.php` |

```js
// SANDBOX
const cashfree = Cashfree({ mode: "sandbox" });
const res = await fetch('/cashfree-order-sandbox.php', {

// PRODUCTION
const cashfree = Cashfree({ mode: "production" });
const res = await fetch('/cashfree-order.php', {
```

---

### 📄 `booking-form-popup.html`

Same changes as `booking-form-cashfree.html`:

| What | Sandbox | Production |
|---|---|---|
| Cashfree mode | `"sandbox"` | `"production"` |
| Fetch URL | `/cashfree-order-sandbox.php` | `/cashfree-order.php` |

```js
// SANDBOX
const cashfree = Cashfree({ mode: "sandbox" });
const res = await fetch('/cashfree-order-sandbox.php', {

// PRODUCTION
const cashfree = Cashfree({ mode: "production" });
const res = await fetch('/cashfree-order.php', {
```

---

### 📄 `payment-status-sandbox.php` → `payment-status.php`

| What | Sandbox | Production |
|---|---|---|
| Verify file | `/cashfree-verify-sandbox.php` | `/cashfree-verify.php` |

```js
// SANDBOX
const verifyUrl = '/cashfree-verify-sandbox.php?order_id=' + psOrderId + ...

// PRODUCTION
const verifyUrl = '/cashfree-verify.php?order_id=' + psOrderId + ...
```

---

### 📄 WordPress Pages & Shortcodes

| What | Sandbox | Production |
|---|---|---|
| Page Title | `Payment Status Sandbox` | `Payment Status` |
| Page Slug | `payment-status-sandbox` | `payment-status` |
| Shortcode | `[payment_status_sandbox]` | `[payment_status]` |

---

## 🚀 Installation Guide

### Step 1 — Cashfree Account Setup

1. Create account at [cashfree.com](https://cashfree.com)
2. Complete KYC verification
3. Go to **Developers → API Keys**
4. Copy **Production App ID** and **Secret Key**
5. Switch to **Test Mode** → copy **Sandbox App ID** and **Secret Key**

---

### Step 2 — Database Setup

1. Go to **cPanel → phpMyAdmin**
2. Click your WordPress database in left sidebar
3. Click **SQL** tab
4. Run the query from `database.sql`

---

### Step 3 — Upload Files to Server

Upload all files to your **WordPress root folder** via **cPanel → File Manager**:

```
public_html/
├── wp-config.php              ← Already here
├── cashfree-order.php         ← Upload
├── cashfree-verify.php        ← Upload
├── cashfree-order-sandbox.php ← Upload
├── cashfree-verify-sandbox.php← Upload
├── payment-status.php         ← Upload
├── payment-status-sandbox.php ← Upload
├── send-mail.php              ← Upload
├── booking-form-cashfree.html ← Upload
├── booking-form-popup.html    ← Upload
├── admin-login.php            ← Upload
├── admin-dashboard.php        ← Upload
└── admin-logout.php           ← Upload
```

---

### Step 4 — Configure Each File

#### `cashfree-order.php` (Production)
```php
define('CASHFREE_APP_ID',    'YOUR_PRODUCTION_APP_ID');
define('CASHFREE_SECRET_KEY','YOUR_PRODUCTION_SECRET_KEY');
// Update domain in return_url
```

#### `cashfree-order-sandbox.php` (Sandbox)
```php
define('CASHFREE_APP_ID',    'YOUR_SANDBOX_APP_ID');
define('CASHFREE_SECRET_KEY','YOUR_SANDBOX_SECRET_KEY');
// Update domain in return_url
```

#### `cashfree-verify.php` & `cashfree-verify-sandbox.php`
```php
define('CASHFREE_APP_ID',    'YOUR_APP_ID');
define('CASHFREE_SECRET_KEY','YOUR_SECRET_KEY');
// Copy from wp-config.php
define('DB_HOST',     'localhost');
define('DB_NAME',     'YOUR_DATABASE_NAME');
define('DB_USER',     'YOUR_DATABASE_USER');
define('DB_PASSWORD', 'YOUR_DATABASE_PASSWORD');
```

#### `send-mail.php`
```php
require_once('/home/YOUR_CPANEL_USERNAME/public_html/wp-load.php');
$to = 'YOUR_ADMIN_EMAIL@gmail.com';
```

#### `admin-login.php`
```php
if ($username === 'admin' && $password === 'YOUR_STRONG_PASSWORD') {
```

#### `admin-dashboard.php`
```php
define('DB_NAME',     'YOUR_DATABASE_NAME');
define('DB_USER',     'YOUR_DATABASE_USER');
define('DB_PASSWORD', 'YOUR_DATABASE_PASSWORD');
```

#### `booking-form-cashfree.html` & `booking-form-popup.html`
```js
// Update WhatsApp number
window.location.href = "https://wa.me/91XXXXXXXXXX?text=" + message;
```

---

### Step 5 — WordPress Setup

Add code from `functions-snippets.php` to your theme's `functions.php`:

```php
// Load popup on every page
add_action('wp_head', function() {
    include(ABSPATH . 'booking-form-popup.html');
});

// Production shortcode
add_shortcode('payment_status', function() {
    ob_start();
    include(ABSPATH . 'payment-status.php');
    return ob_get_clean();
});

// Sandbox shortcode
add_shortcode('payment_status_sandbox', function() {
    ob_start();
    include(ABSPATH . 'payment-status-sandbox.php');
    return ob_get_clean();
});
```

#### Create WordPress Pages

| Page Title | Slug | Shortcode | Use |
|---|---|---|---|
| Payment Status | `payment-status` | `[payment_status]` | Production |
| Payment Status Sandbox | `payment-status-sandbox` | `[payment_status_sandbox]` | Testing |

---

### Step 6 — Add Book Now Buttons

Add in any Elementor HTML widget anywhere:

```html
<button class="open-booking-popup">🚌 Book Now</button>
```

---

## 🧪 Sandbox Testing Checklist

- [ ] Sandbox keys in `cashfree-order-sandbox.php`
- [ ] Sandbox keys in `cashfree-verify-sandbox.php`
- [ ] Booking form mode set to `"sandbox"`
- [ ] Booking form fetch URL → `/cashfree-order-sandbox.php`
- [ ] `Payment Status Sandbox` page created with `[payment_status_sandbox]`
- [ ] Test payment using card `4111 1111 1111 1111` / CVV `123` / OTP `123456`
- [ ] Booking saved in DB ✅
- [ ] Admin email received ✅
- [ ] WhatsApp redirect works ✅
- [ ] Receipt download works ✅

---

## 🚀 Go Live Checklist (Production)

- [ ] Production keys in `cashfree-order.php`
- [ ] Production keys in `cashfree-verify.php`
- [ ] Booking form mode set to `"production"`
- [ ] Booking form fetch URL → `/cashfree-order.php`
- [ ] `Payment Status` page created with `[payment_status]`
- [ ] Admin password changed from default
- [ ] Do a ₹1 real test payment
- [ ] Booking saved in DB ✅
- [ ] Admin email received ✅

---

## 🔧 Troubleshooting

| Problem | Solution |
|---|---|
| Payment initiation failed | Check API keys and URL (sandbox vs production) |
| Data not saving to DB | Check DB credentials in verify file |
| Empty fields in DB | Check fetch call sends all fields in booking form |
| Email not received | Check spam folder, verify `wp_mail()` works |
| Popup not opening | Check `functions.php` has `wp_head` hook |
| 404 on payment status | Check WordPress page slug matches return URL |
| Duplicate emails | Check `localStorage` email flag in payment-status.php |
| Wrong prices | Check `calculateTotal()` in booking form |
| HTTP 500 error | Enable error reporting, check PHP logs |

---

## 🔐 Admin Panel

```
https://YOUR-DOMAIN.com/admin-login.php
```

| Feature | Detail |
|---|---|
| Login | Username + Password |
| View Bookings | All bookings in table |
| Filter | By journey date |
| Stats | Total bookings, revenue, today's data |
| Delete | Password confirmation required |
| Logout | Session destroyed |

---

## 📝 License

MIT License — free to use and modify for your own projects.

---

## 🙏 Credits

Built for **Mumbai Darshan Bus** — a Mumbai city tour bus booking system.
[mumbaidarshanbus.co](https://mumbaidarshanbus.co)
