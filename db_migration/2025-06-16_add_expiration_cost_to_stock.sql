-- Migration: Add expiration_date and cost fields to stock table
-- Date: 2025-06-16
-- Description: Adds expiration date tracking and cost fields to the stock table

-- Add new columns to stock table
ALTER TABLE `stock` 
ADD COLUMN `expiration_date` DATE NULL AFTER `qty_reserved`,
ADD COLUMN `unit_cost` DECIMAL(10,2) DEFAULT 0.00 AFTER `expiration_date`,
ADD COLUMN `total_cost` DECIMAL(12,2) GENERATED ALWAYS AS (`qty_on_hand` * `unit_cost`) STORED AFTER `unit_cost`,
ADD COLUMN `last_cost_update` TIMESTAMP NULL AFTER `total_cost`;

-- Add indexes for better performance
ALTER TABLE `stock` 
ADD INDEX `idx_expiration_date` (`expiration_date`),
ADD INDEX `idx_unit_cost` (`unit_cost`);

-- Update existing stock records with default values (if needed)
-- This will set unit_cost to 0 for existing records
UPDATE `stock` SET `unit_cost` = 0.00 WHERE `unit_cost` IS NULL;

-- Add comment to table
ALTER TABLE `stock` COMMENT = 'Stock table with expiration dates and cost tracking';
