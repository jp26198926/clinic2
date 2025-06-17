-- Manual SQL execution for adding expiration and cost fields
-- Run these commands one by one in your MySQL client if the migration didn't work

-- Step 1: Add expiration_date column
ALTER TABLE `stock` ADD COLUMN `expiration_date` DATE NULL AFTER `qty_reserved`;

-- Step 2: Add unit_cost column
ALTER TABLE `stock` ADD COLUMN `unit_cost` DECIMAL(10,2) DEFAULT 0.00 AFTER `expiration_date`;

-- Step 3: Add total_cost computed column
ALTER TABLE `stock` ADD COLUMN `total_cost` DECIMAL(12,2) GENERATED ALWAYS AS (`qty_on_hand` * `unit_cost`) STORED AFTER `unit_cost`;

-- Step 4: Add last_cost_update timestamp
ALTER TABLE `stock` ADD COLUMN `last_cost_update` TIMESTAMP NULL AFTER `total_cost`;

-- Step 5: Add indexes for performance
ALTER TABLE `stock` ADD INDEX `idx_expiration_date` (`expiration_date`);
ALTER TABLE `stock` ADD INDEX `idx_unit_cost` (`unit_cost`);

-- Step 6: Update existing records (optional)
UPDATE `stock` SET `unit_cost` = 0.00 WHERE `unit_cost` IS NULL;

-- Step 7: Add table comment
ALTER TABLE `stock` COMMENT = 'Stock table with expiration dates and cost tracking';

-- Verify the changes
DESCRIBE `stock`;
