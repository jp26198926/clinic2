-- Batch Transaction System Database Schema

-- Create batch transaction table
CREATE TABLE batch_transactions (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    transaction_number VARCHAR(50) NOT NULL UNIQUE,
    transaction_date DATE NOT NULL,
    transaction_type ENUM('RECEIVE', 'RELEASE', 'TRANSFER') NOT NULL,
    from_location_id INT NULL,
    to_location_id INT NULL,
    remarks TEXT,
    total_items INT DEFAULT 0,
    total_qty DECIMAL(20,4) DEFAULT 0,
    total_cost DECIMAL(12,2) DEFAULT 0,
    status ENUM('DRAFT', 'PROCESSING', 'COMPLETED', 'CANCELLED') DEFAULT 'DRAFT',
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    processed_at TIMESTAMP NULL,
    FOREIGN KEY (from_location_id) REFERENCES locations(id) ON DELETE SET NULL,
    FOREIGN KEY (to_location_id) REFERENCES locations(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES user(id) ON DELETE RESTRICT,
    INDEX idx_transaction_number (transaction_number),
    INDEX idx_transaction_date (transaction_date),
    INDEX idx_transaction_type (transaction_type),
    INDEX idx_status (status)
);

-- Create batch transaction items table
CREATE TABLE batch_transaction_items (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    batch_transaction_id BIGINT NOT NULL,
    product_id INT NOT NULL,
    qty DECIMAL(20,4) NOT NULL,
    unit_cost DECIMAL(10,2) DEFAULT 0,
    expiration_date DATE NULL,
    total_cost DECIMAL(12,2) GENERATED ALWAYS AS (qty * unit_cost) STORED,
    notes VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (batch_transaction_id) REFERENCES batch_transactions(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT,
    INDEX idx_batch_transaction (batch_transaction_id),
    INDEX idx_product (product_id),
    INDEX idx_expiration_date (expiration_date)
);

-- Add menu for Batch Transaction
INSERT INTO admin_parent (parent_name, parent_description, parent_icon, parent_order) 
VALUES ('Inventory', 'Inventory Management', 'fa-cubes', 50)
ON DUPLICATE KEY UPDATE parent_description = 'Inventory Management';

-- Get the parent_id for Inventory
SET @inventory_parent_id = (SELECT parent_id FROM admin_parent WHERE parent_name = 'Inventory');

-- Add Batch Transaction module
INSERT INTO admin_module (module_name, module_description, parent_id) 
VALUES ('inventory_batch', 'Batch Transactions', @inventory_parent_id);

-- Get module ID
SET @batch_module_id = (SELECT id FROM admin_module WHERE module_name = 'inventory_batch');

-- Add permissions for batch transactions module (role_id 1 = Super Admin)
INSERT INTO admin_mod_perm (module_id, role_id, permission_id) VALUES
(@batch_module_id, 1, 14), -- View
(@batch_module_id, 1, 1),  -- Add
(@batch_module_id, 1, 2),  -- Modify
(@batch_module_id, 1, 3),  -- Delete
(@batch_module_id, 1, 7),  -- Print
(@batch_module_id, 1, 9);  -- Export

-- Add basic permissions for other roles if needed (role_id 2, 3, etc.)
INSERT INTO admin_mod_perm (module_id, role_id, permission_id) VALUES
(@batch_module_id, 2, 14), -- View
(@batch_module_id, 2, 1),  -- Add
(@batch_module_id, 2, 7);  -- Print

-- Create function to generate transaction number
DELIMITER $$

CREATE FUNCTION generate_transaction_number() 
RETURNS VARCHAR(50)
READS SQL DATA
DETERMINISTIC
BEGIN
    DECLARE seq_num INT DEFAULT 1;
    DECLARE date_prefix VARCHAR(8);
    DECLARE result VARCHAR(50);
    
    SET date_prefix = DATE_FORMAT(NOW(), '%Y%m%d');
    
    -- Get the next sequence number for today
    SELECT COALESCE(MAX(CAST(SUBSTRING(transaction_number, 10) AS UNSIGNED)), 0) + 1 
    INTO seq_num
    FROM batch_transactions 
    WHERE transaction_number LIKE CONCAT(date_prefix, '%');
    
    SET result = CONCAT(date_prefix, LPAD(seq_num, 4, '0'));
    
    RETURN result;
END$$

DELIMITER ;
