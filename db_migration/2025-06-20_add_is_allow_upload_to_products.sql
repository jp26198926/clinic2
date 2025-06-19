-- Add is_allow_upload field to products table
-- Date: 2025-06-20
-- Description: Add a field to specify which products/services allow file uploads (lab results, images, etc.)

ALTER TABLE `products` 
ADD COLUMN `is_allow_upload` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1=Allow uploads, 0=No uploads' AFTER `status_id`;

-- Update some sample products to allow uploads (typically lab/diagnostic services)
-- You can modify these based on your actual product categories and needs

-- Enable upload for X-RAY services (category 6)
UPDATE `products` SET `is_allow_upload` = 1 WHERE `category_id` = 6;

-- Enable upload for laboratory services (assuming category 3 is lab - adjust as needed)
-- UPDATE `products` SET `is_allow_upload` = 1 WHERE `category_id` = 3;

-- You can also enable specific products by code or ID
-- UPDATE `products` SET `is_allow_upload` = 1 WHERE `code` IN ('LAB001', 'LAB002', 'ECHO01');

-- Add index for better performance
CREATE INDEX `idx_is_allow_upload` ON `products` (`is_allow_upload`);
