-- Create result_sets table for laboratory result parameters
-- Date: 2025-06-21
-- Purpose: Store configurable result parameters for different products/services

CREATE TABLE IF NOT EXISTS `result_sets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `result_label` varchar(255) NOT NULL COMMENT 'Name of the parameter (e.g., Hemoglobin, WBC Count)',
  `unit` varchar(50) DEFAULT NULL COMMENT 'Unit of measurement (e.g., g/dL, cells/µL)',
  `reference` varchar(255) DEFAULT NULL COMMENT 'Reference range (e.g., 12-16 g/dL)',
  `description` text DEFAULT NULL COMMENT 'Additional information about this parameter',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `deleted_reason` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_product_id` (`product_id`),
  KEY `idx_deleted_at` (`deleted_at`),
  KEY `idx_result_label` (`result_label`),
  UNIQUE KEY `unique_product_result` (`product_id`, `result_label`, `deleted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Laboratory result parameters configuration';

-- Create lab_result_parameters table for storing actual result values
CREATE TABLE IF NOT EXISTS `lab_result_parameters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lab_result_id` int(11) NOT NULL COMMENT 'References lab_results.id',
  `result_set_id` int(11) NOT NULL COMMENT 'References result_sets.id',
  `result_value` varchar(255) NOT NULL COMMENT 'Actual result value entered',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_lab_result_id` (`lab_result_id`),
  KEY `idx_result_set_id` (`result_set_id`),
  UNIQUE KEY `unique_lab_result_set` (`lab_result_id`, `result_set_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Laboratory result parameter values';

-- Add foreign key constraints if the referenced tables exist
-- Note: Uncomment these if the referenced tables exist and you want to enforce referential integrity

-- ALTER TABLE `result_sets` 
--   ADD CONSTRAINT `fk_result_sets_product` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE,
--   ADD CONSTRAINT `fk_result_sets_created_by` FOREIGN KEY (`created_by`) REFERENCES `user` (`user_id`);

-- ALTER TABLE `lab_result_parameters` 
--   ADD CONSTRAINT `fk_lab_result_parameters_lab_result` FOREIGN KEY (`lab_result_id`) REFERENCES `lab_results` (`id`) ON DELETE CASCADE,
--   ADD CONSTRAINT `fk_lab_result_parameters_result_set` FOREIGN KEY (`result_set_id`) REFERENCES `result_sets` (`id`) ON DELETE CASCADE,
--   ADD CONSTRAINT `fk_lab_result_parameters_created_by` FOREIGN KEY (`created_by`) REFERENCES `user` (`user_id`);

-- Insert some sample result sets for common laboratory tests
-- Note: Replace product_ids with actual values from your product table

-- Example for Complete Blood Count (CBC)
-- INSERT INTO `result_sets` (`product_id`, `result_label`, `unit`, `reference`, `description`, `created_by`) VALUES
-- (1, 'Hemoglobin', 'g/dL', '12.0-16.0 (Female), 14.0-18.0 (Male)', 'Measures oxygen-carrying protein in red blood cells', 1),
-- (1, 'Hematocrit', '%', '36-46 (Female), 41-53 (Male)', 'Percentage of blood volume occupied by red blood cells', 1),
-- (1, 'White Blood Cell Count', 'cells/µL', '4,000-11,000', 'Total number of white blood cells', 1),
-- (1, 'Platelet Count', 'cells/µL', '150,000-450,000', 'Number of platelets for blood clotting', 1);

-- Example for Basic Metabolic Panel (BMP)
-- INSERT INTO `result_sets` (`product_id`, `result_label`, `unit`, `reference`, `description`, `created_by`) VALUES
-- (2, 'Glucose', 'mg/dL', '70-100 (Fasting)', 'Blood sugar level', 1),
-- (2, 'Sodium', 'mEq/L', '136-145', 'Electrolyte balance', 1),
-- (2, 'Potassium', 'mEq/L', '3.5-5.0', 'Electrolyte balance', 1),
-- (2, 'Chloride', 'mEq/L', '98-107', 'Electrolyte balance', 1),
-- (2, 'Creatinine', 'mg/dL', '0.6-1.2', 'Kidney function marker', 1);
