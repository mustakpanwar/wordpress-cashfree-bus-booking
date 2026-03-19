-- ============================================================
-- WordPress Cashfree Bus Booking System
-- Database Setup SQL
-- ============================================================
-- Run this in phpMyAdmin → SQL tab
-- Make sure you have selected your WordPress database first
-- ============================================================

CREATE TABLE IF NOT EXISTS bookings (
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

-- ============================================================
-- Notes:
-- 1. Replace 'bookings' with your preferred table name
-- 2. Update DB_NAME in all PHP files to match your WP database
-- ============================================================
