# Inventory Management System - User Guide

## Overview

The Inventory Management System has been successfully integrated into your CodeIgniter 3 clinic application. This system provides comprehensive stock tracking, movements monitoring, and inventory control across multiple locations.

## System Components

### 1. Database Schema

#### Stock Table (`stock`)

- **Purpose**: Maintains current stock levels for each product at each location
- **Key Features**:
  - Auto-calculated available quantity (on_hand - reserved)
  - Unique constraint per product-location combination
  - Automatic timestamp updates

#### Stock Movements Table (`stock_movements`)

- **Purpose**: Records all stock transactions and movements
- **Movement Types**: RECEIVE, RELEASE, TRANSFER, ADJUSTMENT, RESERVE
- **Reference Types**: PURCHASE, SALE, TRANSFER, ADJUSTMENT, RETURN, RESERVE

### 2. Application Structure

#### Models

- **Stock_model**: Manages stock levels, reservations, and low stock alerts
- **Stock_movements_model**: Handles all stock movement transactions

#### Controllers

- **Inventory_stock**: Main stock management interface
- **Inventory_movements**: Stock movement tracking and reporting

#### Views

- **inventory_stock/index.php**: Stock management dashboard
- **inventory_movements/index.php**: Movement history and new transaction entry

## Key Features

### Stock Management

1. **Real-time Stock Levels**: View current stock across all locations
2. **Low Stock Alerts**: Automatic alerts when stock falls below reorder levels
3. **Stock Reservations**: Reserve stock for pending orders
4. **Multi-location Support**: Separate stock tracking per location

### Stock Movements

1. **Transaction Recording**: All stock changes are automatically logged
2. **Movement Types**:
   - **RECEIVE**: Stock incoming (purchases, returns)
   - **RELEASE**: Stock outgoing (sales, consumption)
   - **TRANSFER**: Moving stock between locations
   - **ADJUSTMENT**: Manual corrections
   - **RESERVE**: Temporarily allocate stock

### Search and Filtering

- Search by product name, code, or category
- Filter by location, date range, movement type
- Export capabilities for reporting

## Usage Instructions

### Accessing the System

1. Navigate to **Inventory → Stock Management** for current stock levels
2. Navigate to **Inventory → Stock Movements** for transaction history

### Common Operations

#### Viewing Stock Levels

1. Go to Stock Management
2. Use search to find specific products
3. Filter by location if needed
4. View available, on-hand, and reserved quantities

#### Recording Stock Movements

1. Go to Stock Movements
2. Click "Add New Movement"
3. Select product and location
4. Choose movement type and reference type
5. Enter quantity and cost (if applicable)
6. Add notes if needed
7. Save the transaction

#### Stock Transfers

1. Create a TRANSFER movement for the source location (negative quantity)
2. Create a corresponding TRANSFER movement for the destination location (positive quantity)
3. Use the same transfer_batch_id to link the transactions

### Reports Available

- Current stock levels by location
- Stock movement history
- Low stock alerts
- Stock valuation reports
- Movement summary by type

## Security and Permissions

### Role-Based Access

- **Admin (Role 1)**: Full access to all inventory functions
- **Staff (Role 2)**: View and basic entry permissions
- **Custom Roles**: Can be configured as needed

### Audit Trail

- All stock movements are tracked with user ID and timestamp
- Complete history of all stock changes
- Cannot delete stock movements (audit integrity)

## Technical Integration

### Model Usage Examples

```php
// Get current stock for a product at a location
$stock = $this->stock_model->search_by_product_location($product_id, $location_id);

// Record a stock movement
$this->stock_movements_model->add([
    'product_id' => $product_id,
    'location_id' => $location_id,
    'movement_type' => 'RECEIVE',
    'qty' => 100,
    'reference_type' => 'PURCHASE',
    'reference_id' => $purchase_order_id
]);

// Update stock levels
$this->stock_model->update_stock($product_id, $location_id, $qty_change);

// Get low stock alerts
$low_stock = $this->stock_model->get_low_stock($location_id);
```

### AJAX Endpoints

- `inventory_stock/search` - Search stock levels
- `inventory_movements/search` - Search movements
- `inventory_stock/get_low_stock` - Get low stock alerts
- `inventory_movements/add` - Add new movement

## Future Enhancements

### Planned Features

1. **Barcode Integration**: Scan products for quick entry
2. **Automated Reordering**: Generate purchase orders for low stock
3. **Cost Tracking**: FIFO/LIFO cost calculation
4. **Expiry Management**: Track expiration dates for medical supplies
5. **Batch/Lot Tracking**: Track specific batches of products

### Integration Points

- **Purchase Orders**: Automatic stock updates when orders are received
- **Sales/Billing**: Automatic stock deduction on sales
- **Location Transfers**: Streamlined inter-department transfers

## Support and Maintenance

### Regular Tasks

1. Monitor low stock alerts daily
2. Reconcile physical stock monthly
3. Review movement reports weekly
4. Update reorder levels as needed

### Troubleshooting

- Check stock movements for discrepancies
- Verify product-location combinations
- Ensure proper user permissions
- Review error logs for system issues

## Contact Information

For technical support or feature requests, contact your system administrator.

---

_Last Updated: June 13, 2025_
_Version: 1.0_
