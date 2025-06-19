-- Add Inventory parent menu
INSERT INTO admin_parent (parent_name, parent_description, parent_icon, parent_order) VALUES ('Inventory', 'Inventory Management', 'fa-cubes', 50);

-- Get the parent_id for Inventory
SET @inventory_parent_id = (SELECT parent_id FROM admin_parent WHERE parent_name = 'Inventory');

-- Add Inventory modules
INSERT INTO admin_module (module_name, module_description, parent_id) VALUES 
('inventory_stock', 'Stock Management', @inventory_parent_id),
('inventory_movements', 'Stock Movements', @inventory_parent_id);

-- Get module IDs
SET @stock_module_id = (SELECT id FROM admin_module WHERE module_name = 'inventory_stock');
SET @movements_module_id = (SELECT id FROM admin_module WHERE module_name = 'inventory_movements');

-- Add permissions for modules (using existing permission IDs)
-- Permission IDs: 1=Add, 2=Modify, 3=Delete, 14=View, 7=Print, 9=Export
INSERT INTO admin_mod_perm (module_id, role_id, permission_id) VALUES
(@stock_module_id, 1, 14), -- View
(@stock_module_id, 1, 1),  -- Add
(@stock_module_id, 1, 2),  -- Modify
(@stock_module_id, 1, 3),  -- Delete
(@stock_module_id, 1, 7),  -- Print
(@stock_module_id, 1, 9),  -- Export
(@movements_module_id, 1, 14), -- View
(@movements_module_id, 1, 1),  -- Add
(@movements_module_id, 1, 2),  -- Modify
(@movements_module_id, 1, 3),  -- Delete
(@movements_module_id, 1, 7),  -- Print
(@movements_module_id, 1, 9);  -- Export

-- Add basic permissions for other roles if needed (role_id 2, 3, etc.)
-- Adjust these based on your existing role structure
INSERT INTO admin_mod_perm (module_id, role_id, permission_id) VALUES
(@stock_module_id, 2, 14), -- View
(@stock_module_id, 2, 1),  -- Add
(@movements_module_id, 2, 14); -- View