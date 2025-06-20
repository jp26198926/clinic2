-- Lab Results Table Migration
-- Date: 2025-06-20
-- Description: Create table for storing laboratory results and file attachments

CREATE TABLE IF NOT EXISTS `lab_results` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `test_name` varchar(255) NOT NULL,
  `test_date` date NOT NULL,
  `lab_provider` varchar(255) DEFAULT NULL,
  `notes` text,
  `files` longtext,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_transaction_id` (`transaction_id`),
  KEY `idx_item_id` (`item_id`),
  KEY `idx_test_date` (`test_date`),
  KEY `idx_created_by` (`created_by`),
  KEY `idx_deleted_at` (`deleted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add foreign key constraints (optional, uncomment if needed)
-- ALTER TABLE `lab_results` 
-- ADD CONSTRAINT `fk_lab_results_transaction` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE,
-- ADD CONSTRAINT `fk_lab_results_created_by` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`);
