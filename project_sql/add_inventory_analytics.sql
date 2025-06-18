-- Add Inventory Analytics module to existing Inventory parent menu
-- This script adds the Inventory Analytics module under the existing Inventory parent menu

-- Get the parent_id for Inventory (should already exist)
SET @inventory_parent_id = (SELECT parent_id FROM admin_parent WHERE parent_name = 'Inventory');

-- Add Inventory Analytics module
INSERT INTO admin_module (module_name, module_description, parent_id) 
VALUES ('inventory_analytics', 'Inventory Analytics', @inventory_parent_id);

-- Get module ID
SET @analytics_module_id = (SELECT id FROM admin_module WHERE module_name = 'inventory_analytics');

-- Add permissions for inventory analytics module
-- Permission IDs: 1=Add, 2=Modify, 3=Delete, 14=View, 7=Print, 9=Export

-- Administrator (role_id 1) - Full permissions (matching other inventory modules)
INSERT INTO admin_mod_perm (module_id, role_id, permission_id) VALUES
(@analytics_module_id, 1, 14), -- View
(@analytics_module_id, 1, 7),  -- Print  
(@analytics_module_id, 1, 9);  -- Export

-- VIP (role_id 2) - View permission (matching inventory_movements pattern)
INSERT INTO admin_mod_perm (module_id, role_id, permission_id) VALUES
(@analytics_module_id, 2, 14); -- View

-- Note: Inventory Analytics is primarily a read-only module for viewing and exporting analytics
-- Add/Modify/Delete permissions are not needed for analytics modules

-- Verify the installation
SELECT 'Inventory Analytics Module Added' AS status;
SELECT m.id, m.module_name, m.module_description, p.parent_name 
FROM admin_module m 
JOIN admin_parent p ON m.parent_id = p.parent_id 
WHERE m.module_name = 'inventory_analytics';

SELECT 'Permissions Added' AS status;
SELECT m.module_name, r.role_name, pe.permission
FROM admin_mod_perm mp
JOIN admin_module m ON mp.module_id = m.id
JOIN admin_role r ON mp.role_id = r.id
JOIN admin_permission pe ON mp.permission_id = pe.id
WHERE m.module_name = 'inventory_analytics'
ORDER BY r.id, pe.id;
