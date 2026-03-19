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

---

## 📝 License

MIT License — free to use and modify.

---

## 🙏 Credits

Developed for **Mumbai Darshan Bus Services**
Website: [mumbaidarshanbus.co](https://mumbaidarshanbus.co)
