# Batch Transaction System - Technical Implementation Guide

## System Architecture

### **Components Overview**

```
┌─────────────────────────────────────────────────────────────┐
│                 Batch Transaction System                    │
├─────────────────────────────────────────────────────────────┤
│ Controller: Inventory_batch.php                            │
│ Model: Batch_transaction_model.php                         │
│ Views: inventory_batch/index.php, manage.php, print.php    │
│ Database: batch_transactions, batch_transaction_items      │
│ Integration: stock_movements, locations, products          │
└─────────────────────────────────────────────────────────────┘
```

## Database Schema

### **batch_transactions Table**

```sql
CREATE TABLE `batch_transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_number` varchar(20) NOT NULL,
  `transaction_date` date NOT NULL,
  `transaction_type` enum('RECEIVE','RELEASE','TRANSFER') NOT NULL,
  `from_location_id` int(11) NULL,
  `to_location_id` int(11) NULL,
  `remarks` text NULL,
  `status` enum('DRAFT','COMPLETED','CANCELLED') NOT NULL DEFAULT 'DRAFT',
  `total_items` int(11) NOT NULL DEFAULT 0,
  `total_qty` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `processed_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `transaction_number` (`transaction_number`)
);
```

### **batch_transaction_items Table**

```sql
CREATE TABLE `batch_transaction_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `batch_transaction_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `qty` decimal(10,2) NOT NULL,
  `unit_cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_cost` decimal(15,2) GENERATED ALWAYS AS (`qty` * `unit_cost`) STORED,
  `notes` text NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
);
```

## API Endpoints

### **Controller Methods**

#### **index()**

- **Route**: `/inventory_batch`
- **Permission**: view
- **Purpose**: Main listing page
- **Returns**: HTML view with batch list

#### **get_batch_list()**

- **Route**: `/inventory_batch/get_batch_list` (POST)
- **Permission**: view
- **Parameters**: search, transaction_type, status, date_from, date_to, location_id
- **Returns**: JSON array of batch records

#### **create_batch()**

- **Route**: `/inventory_batch/create_batch` (POST)
- **Permission**: add
- **Parameters**: transaction_date, transaction_type, from_location_id, to_location_id, remarks
- **Returns**: JSON with batch_id and transaction_number

#### **manage($batch_id)**

- **Route**: `/inventory_batch/manage/{id}`
- **Permission**: view
- **Purpose**: Item management page for specific batch
- **Returns**: HTML view with item management interface

#### **add_item()**

- **Route**: `/inventory_batch/add_item` (POST)
- **Permission**: add
- **Parameters**: batch_id, product_id, qty, unit_cost, notes
- **Returns**: JSON success/error

#### **update_item()**

- **Route**: `/inventory_batch/update_item` (POST)
- **Permission**: edit
- **Parameters**: item_id, qty, unit_cost, notes
- **Returns**: JSON success/error

#### **delete_item()**

- **Route**: `/inventory_batch/delete_item` (POST)
- **Permission**: delete
- **Parameters**: item_id
- **Returns**: JSON success/error

#### **process_batch()**

- **Route**: `/inventory_batch/process_batch` (POST)
- **Permission**: add
- **Parameters**: batch_id
- **Purpose**: Execute the batch transaction (create stock movements)
- **Returns**: JSON success/error

#### **cancel_batch()**

- **Route**: `/inventory_batch/cancel_batch` (POST)
- **Permission**: delete
- **Parameters**: batch_id, reason
- **Returns**: JSON success/error

#### **print_batch($batch_id)**

- **Route**: `/inventory_batch/print_batch/{id}`
- **Permission**: print
- **Returns**: HTML print view

#### **get_product_info()**

- **Route**: `/inventory_batch/get_product_info` (GET)
- **Permission**: view
- **Parameters**: product_id, location_id
- **Returns**: JSON with product details and current stock

## Model Methods

### **Core CRUD Operations**

#### **search()**

```php
search($search = "", $transaction_type = "", $status = "", $date_from = "", $date_to = "", $location_id = 0)
```

- Advanced search with multiple filters
- Joins with locations and users for complete data
- Ordered by creation date (DESC)

#### **get_by_id($id)**

- Retrieves single batch with joined location and user data
- Used for detail views and processing

#### **get_items($batch_id)**

- Retrieves all items for a batch
- Joins with products, categories, and UOMs
- Ordered by product name

### **Batch Management**

#### **create_batch($data)**

```php
create_batch([
    'transaction_date' => 'YYYY-MM-DD',
    'transaction_type' => 'RECEIVE|RELEASE|TRANSFER',
    'from_location_id' => int|null,
    'to_location_id' => int|null,
    'remarks' => string,
    'created_by' => int
])
```

- Generates unique transaction number
- Creates batch in DRAFT status
- Returns batch_id and transaction_number

#### **generate_transaction_number()**

- Format: YYYYMMDD#### (e.g., 202506130001)
- Automatically increments daily sequence
- Handles date rollover correctly

### **Item Management**

#### **add_item($batch_id, $item_data)**

- Adds item to existing batch
- Updates batch totals automatically
- Validates item data

#### **update_item($item_id, $item_data)**

- Updates existing item
- Recalculates batch totals
- Maintains data integrity

#### **delete_item($item_id)**

- Removes item from batch
- Updates batch totals
- Cannot delete from processed batches

#### **update_batch_totals($batch_id)**

- Recalculates total_items, total_qty, total_cost
- Called automatically after item changes
- Uses SQL aggregation for accuracy

### **Processing**

#### **process_batch($batch_id, $user_id)**

```php
// Transaction flow:
1. Validate batch status (must be DRAFT)
2. Validate items exist
3. Load stock_movements_model
4. For each item:
   - RECEIVE: call receive_stock()
   - RELEASE: call release_stock()
   - TRANSFER: call transfer_stock()
5. Update batch status to COMPLETED
6. Set processed_at timestamp
```

**Error Handling:**

- Database transactions ensure atomicity
- Any failure cancels entire batch
- Detailed error messages for troubleshooting

#### **cancel_batch($batch_id, $reason)**

- Changes status to CANCELLED
- Appends cancellation reason to remarks
- Preserves original data for audit

## Stock Movement Integration

### **Movement Types**

- **BATCH_RECEIVE**: Stock incoming to location
- **BATCH_RELEASE**: Stock outgoing from location
- **BATCH_TRANSFER**: Stock movement between locations

### **Movement Records Created**

```php
// For each processed item:
$movement_data = [
    'date' => $batch->transaction_date,
    'product_id' => $item->product_id,
    'location_id' => $location_id,
    'movement_type' => 'BATCH_RECEIVE|BATCH_RELEASE|BATCH_TRANSFER',
    'quantity' => $item->qty,
    'unit_cost' => $item->unit_cost,
    'reference_id' => $batch_id,
    'batch_transaction_id' => $batch_id,
    'created_by' => $user_id,
    'notes' => "Batch: {$transaction_number} - {$item->notes}"
];
```

### **Stock Updates**

- **RECEIVE**: Increases qty_on_hand at to_location
- **RELEASE**: Decreases qty_on_hand at from_location
- **TRANSFER**: Decreases from_location, increases to_location

## Frontend Architecture

### **Responsive Design**

```css
/* Desktop View */
@media (min-width: 769px) {
	.mobile-card-container {
		display: none !important;
	}
	.table-responsive {
		display: block !important;
	}
}

/* Mobile View */
@media (max-width: 768px) {
	.mobile-card-container {
		display: block !important;
	}
	.table-responsive {
		display: none !important;
	}
}
```

### **JavaScript Components**

#### **DataTables Configuration**

```javascript
$("#batchTable").DataTable({
	processing: true,
	serverSide: false,
	responsive: true,
	dom: "Bfrtip",
	buttons: ["excel", "pdf", "print"],
	order: [[0, "desc"]],
});
```

#### **AJAX Operations**

- **loadBatchList()**: Refresh batch grid
- **createBatch()**: Submit new batch form
- **addItem()**: Add item to batch
- **updateItem()**: Edit existing item
- **deleteItem()**: Remove item
- **processBatch()**: Execute batch transaction
- **cancelBatch()**: Cancel batch with reason

#### **Real-time Updates**

- Item totals calculated on change
- Status badges updated dynamically
- Form validation with immediate feedback

## Security Implementation

### **Permission Checks**

```php
// Every controller method validates permissions:
if ($this->cf->module_permission("action", $this->module_permission)) {
    // Proceed with operation
} else {
    // Deny access
}
```

### **Input Validation**

- **SQL Injection**: PDO prepared statements
- **XSS Prevention**: Input sanitization
- **CSRF Protection**: CodeIgniter CSRF tokens
- **Data Type Validation**: Integer/float casting

### **Business Logic Validation**

- Location requirements per transaction type
- Stock availability for releases/transfers
- Batch status restrictions
- User ownership verification

## Performance Considerations

### **Database Optimization**

- **Indexes**: transaction_number, transaction_date, status
- **Foreign Keys**: Proper relationships and cascading
- **Generated Columns**: Automatic total_cost calculation
- **Query Optimization**: Efficient joins and filters

### **Caching Strategy**

- **Product Data**: Cache frequently accessed products
- **Location Data**: Cache location lists
- **User Permissions**: Cache role permissions
- **Static Data**: Cache dropdown options

### **Frontend Optimization**

- **DataTables**: Client-side processing for better UX
- **AJAX Loading**: Asynchronous operations
- **Mobile Optimization**: Responsive design principles
- **Asset Minification**: Compressed CSS/JS

## Error Handling

### **Exception Types**

```php
// Database errors
throw new Exception("Error: Failed to create batch transaction");

// Validation errors
throw new Exception("Error: Invalid adjustment type!");

// Business logic errors
throw new Exception("Error: Cannot subtract more than current stock");

// Permission errors
echo json_encode(['success' => false, 'message' => 'Permission denied']);
```

### **User-Friendly Messages**

- Clear, actionable error descriptions
- No technical jargon in user interface
- Consistent error response format
- Helpful suggestions for resolution

## Testing Strategy

### **Unit Tests**

- Model method validation
- Controller permission checks
- Transaction number generation
- Calculation accuracy

### **Integration Tests**

- Stock movement creation
- Database transaction integrity
- Multi-location transfers
- Batch processing workflow

### **User Acceptance Tests**

- Complete workflow testing
- Mobile responsiveness
- Print functionality
- Error scenarios

## Deployment Checklist

### **Database**

- [ ] Run migration script
- [ ] Verify table creation
- [ ] Add menu permissions
- [ ] Test database connections

### **Files**

- [ ] Upload controller
- [ ] Upload model
- [ ] Upload views
- [ ] Verify file permissions

### **Configuration**

- [ ] Check CodeIgniter routes
- [ ] Verify autoload settings
- [ ] Test permission system
- [ ] Configure error reporting

### **Testing**

- [ ] Basic CRUD operations
- [ ] Permission enforcement
- [ ] Mobile responsiveness
- [ ] Print functionality
- [ ] Stock integration

## Maintenance

### **Regular Tasks**

- Monitor transaction volumes
- Archive old batch records
- Review error logs
- Update documentation

### **Performance Monitoring**

- Database query performance
- Page load times
- Mobile responsiveness
- User adoption metrics

### **Backup Strategy**

- Regular database backups
- Transaction log backups
- File system backups
- Recovery testing

---

**Version**: 1.0  
**Last Updated**: June 13, 2025  
**Developer**: System Administrator  
**Framework**: CodeIgniter 3.x
