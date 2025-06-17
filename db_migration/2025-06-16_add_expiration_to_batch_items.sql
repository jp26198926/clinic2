-- Migration: Add expiration_date field to batch_transaction_items table
-- Date: 2025-06-16
-- Description: Adds expiration date tracking to batch transaction items

-- Add expiration_date column to batch_transaction_items table
ALTER TABLE `batch_transaction_items` 
ADD COLUMN `expiration_date` DATE NULL AFTER `unit_cost`;

-- Add index for better performance
ALTER TABLE `batch_transaction_items` 
ADD INDEX `idx_expiration_date` (`expiration_date`);

-- Add comment to table
ALTER TABLE `batch_transaction_items` COMMENT = 'Batch transaction items with expiration date tracking';

-- Verify the changes
DESCRIBE `batch_transaction_items`;
