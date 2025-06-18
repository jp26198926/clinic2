-- Add Inventory Reports module to existing Inventory parent menu
-- This script adds the Inventory Reports module under the existing Inventory parent menu

-- Get the parent_id for Inventory (should already exist)
SET @inventory_parent_id = (SELECT parent_id FROM admin_parent WHERE parent_name = 'Inventory');

-- Add Inventory Reports module
INSERT INTO admin_module (module_name, module_description, parent_id) 
VALUES ('inventory_reports', 'Inventory Reports', @inventory_parent_id);

-- Get module ID
SET @reports_module_id = (SELECT id FROM admin_module WHERE module_name = 'inventory_reports');

-- Add permissions for inventory reports module
-- Permission IDs: 1=Add, 2=Modify, 3=Delete, 14=View, 7=Print, 9=Export

-- Super Admin (role_id 1) - Full permissions
INSERT INTO admin_mod_perm (module_id, role_id, permission_id) VALUES
(@reports_module_id, 1, 14), -- View
(@reports_module_id, 1, 7),  -- Print  
(@reports_module_id, 1, 9);  -- Export

-- Staff/Manager (role_id 2) - Basic permissions
INSERT INTO admin_mod_perm (module_id, role_id, permission_id) VALUES
(@reports_module_id, 2, 14), -- View
(@reports_module_id, 2, 7);  -- Print

-- Note: Inventory Reports is primarily a read-only module for viewing and exporting reports
-- Add/Modify/Delete permissions are not typically needed for reports modules
