-- SQL Script to update payments table
-- Run this in phpMyAdmin or MySQL client

USE rentbase;

-- Check if columns exist before adding
ALTER TABLE payments 
    ADD COLUMN IF NOT EXISTS payment_method VARCHAR(50) NULL AFTER amount,
    ADD COLUMN IF NOT EXISTS reference_number VARCHAR(100) NULL UNIQUE AFTER payment_method,
    MODIFY COLUMN payment_date DATETIME;

-- Verify changes
DESCRIBE payments;
