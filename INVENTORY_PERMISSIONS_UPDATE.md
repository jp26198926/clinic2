# Inventory System - Permission Structure Update

## Changes Made

**Date**: June 13, 2025  
**Update**: Replaced "create" permissions with "add" permissions

## Current Permission Structure

### View Layer (`application/views/inventory_stock/index.php`)

```php
<?php if($this->cf->module_permission("add", $module_permission)): ?>
    <!-- Desktop action buttons -->
    <button id="btn_receive">Receive Stock</button>
    <button id="btn_release">Release Stock</button>
    <button id="btn_transfer">Transfer Stock</button>
    <button id="btn_adjust">Adjust Stock</button>

    <!-- Mobile action buttons -->
    <button id="mobile_btn_receive">Receive</button>
    <button id="mobile_btn_release">Release</button>
    <button id="mobile_btn_transfer">Transfer</button>
    <button id="mobile_btn_adjust">Adjust</button>
<?php endif; ?>
```

### Controller Layer (`application/controllers/Inventory_stock.php`)

```php
// Stock Movement Operations (use "add" permission)
function receive_stock() {
    if ($this->cf->module_permission("add", $this->module_permission)) {
        // Receive stock logic
    }
}

function release_stock() {
    if ($this->cf->module_permission("add", $this->module_permission)) {
        // Release stock logic
    }
}

function transfer_stock() {
    if ($this->cf->module_permission("add", $this->module_permission)) {
        // Transfer stock logic
    }
}

// Stock Adjustment Operation (uses "update" permission)
function adjust_stock() {
    if ($this->cf->module_permission("update", $this->module_permission)) {
        // Adjust stock logic
    }
}
```

### Database Permissions (`sql_inventory_menu.sql`)

```sql
-- Permission IDs: 1=Add, 2=Modify, 3=Delete, 14=View, 7=Print, 9=Export
INSERT INTO admin_mod_perm (module_id, role_id, permission_id) VALUES
(@stock_module_id, 1, 1),  -- Add permission
(@stock_module_id, 1, 2),  -- Update/Modify permission
(@stock_module_id, 1, 14), -- View permission
-- ... other permissions
```

## Permission Logic

### "add" Permission Required For:

- **Receive Stock**: Creating new stock entries or adding to existing stock
- **Release Stock**: Creating outbound stock movements
- **Transfer Stock**: Creating stock transfer movements between locations

### "update" Permission Required For:

- **Adjust Stock**: Modifying existing stock quantities (ADD/SUBTRACT/SET operations)

## User Role Configuration

To grant inventory access to a user role:

1. **Minimum Access** (View Only):

   - Permission ID 14 (View)

2. **Standard User** (Can perform stock movements):

   - Permission ID 14 (View)
   - Permission ID 1 (Add) - for receive, release, transfer operations

3. **Supervisor** (Can adjust stock):

   - Permission ID 14 (View)
   - Permission ID 1 (Add) - for stock movements
   - Permission ID 2 (Update) - for stock adjustments

4. **Administrator** (Full Access):
   - Permission ID 14 (View)
   - Permission ID 1 (Add)
   - Permission ID 2 (Update)
   - Permission ID 3 (Delete)
   - Permission ID 7 (Print)
   - Permission ID 9 (Export)

## Benefits of This Structure

1. **Separation of Concerns**:

   - Regular stock movements use "add" permission
   - Stock corrections/adjustments use "update" permission

2. **Granular Control**:

   - Can allow users to receive/release stock without adjustment privileges
   - Supervisors can be given adjustment rights separately

3. **Audit Trail**:

   - Clear distinction between new movements and existing stock modifications

4. **Security**:
   - Stock adjustments require higher permission level
   - Prevents accidental stock corrections by regular users

## Compatibility

✅ **Backward Compatible**: Existing database permission structure unchanged  
✅ **Role-Based**: Works with existing admin role system  
✅ **Secure**: Maintains proper permission hierarchy  
✅ **Flexible**: Allows granular permission assignment per role
