-- Add digital entry fields to lab_results table
-- This migration adds fields to support digital laboratory result entries

ALTER TABLE `lab_results` 
ADD COLUMN `entry_type` ENUM('upload', 'digital') NOT NULL DEFAULT 'upload' AFTER `item_id`,
ADD COLUMN `performed_by` VARCHAR(255) NULL AFTER `lab_provider`,
ADD COLUMN `lab_parameters` LONGTEXT NULL COMMENT 'JSON data for lab parameters' AFTER `notes`,
ADD COLUMN `interpretation` TEXT NULL COMMENT 'Clinical interpretation' AFTER `lab_parameters`;

-- Add indexes for better performance
ALTER TABLE `lab_results` 
ADD INDEX `idx_entry_type` (`entry_type`),
ADD INDEX `idx_performed_by` (`performed_by`);
