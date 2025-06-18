# Batch Transaction System - User Guide

## Overview

The Batch Transaction System allows users to process multiple inventory operations (receive, release, transfer) in a single transaction with automated transaction numbers and comprehensive printout functionality. This system is designed to streamline bulk inventory operations while maintaining detailed audit trails.

## System Features

### Core Functionality

1. **Batch Transaction Types**

   - **RECEIVE**: Add multiple products to a destination location
   - **RELEASE**: Remove multiple products from a source location
   - **TRANSFER**: Move multiple products between locations

2. **Automated Transaction Numbers**

   - Format: YYYYMMDDXXXX (e.g., 2025061300001)
   - Auto-generated based on date and sequential numbering
   - Unique transaction tracking

3. **Real-time Totals**

   - Automatic calculation of total items, quantities, and costs
   - Live updates as items are added/removed
   - Summary displays on all views

4. **Professional Print Output**

   - Company-branded transaction documents
   - Complete item details with costs
   - Summary totals and signatures section
   - Print-optimized layout

5. **Mobile Responsive Design**
   - Card-based layout for mobile devices
   - Touch-friendly interface
   - Automatic responsive switching at 768px breakpoint

## Database Schema

### Batch Transactions Table

```sql
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
    processed_at TIMESTAMP NULL
);
```

### Batch Transaction Items Table

```sql
CREATE TABLE batch_transaction_items (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    batch_transaction_id BIGINT NOT NULL,
    product_id INT NOT NULL,
    qty DECIMAL(20,4) NOT NULL,
    unit_cost DECIMAL(10,2) DEFAULT 0,
    total_cost DECIMAL(12,2) GENERATED ALWAYS AS (qty * unit_cost) STORED,
    notes VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

## Usage Instructions

### Creating a New Batch Transaction

1. **Access the System**

   - Navigate to **Inventory → Batch Transactions**
   - Click **"New Batch Transaction"** button

2. **Configure Batch Details**

   - **Transaction Date**: Set the transaction date (defaults to today)
   - **Transaction Type**: Select RECEIVE, RELEASE, or TRANSFER
   - **Locations**:
     - RECEIVE: Select destination location only
     - RELEASE: Select source location only
     - TRANSFER: Select both source and destination locations
   - **Remarks**: Optional notes about the batch

3. **Add Items to Batch**

   - Select products from the dropdown
   - Enter quantities for each product
   - Add unit costs (optional, defaults to 0)
   - Include item-specific notes if needed
   - Items are automatically totaled

4. **Process the Batch**
   - Review all items and totals
   - Click **"Process Batch"** to execute all movements
   - Batch status changes to COMPLETED
   - Individual stock movements are created for each item

### Managing Batch Items

#### Adding Items

- Use the item form at the top of the manage page
- Product selection with search functionality
- Real-time validation and error handling
- Automatic total calculations

#### Editing Items

- Click edit button on any item (DRAFT status only)
- Modify quantities, costs, or notes
- Totals update automatically

#### Removing Items

- Click delete button with confirmation
- Item removed from batch immediately
- Totals recalculated automatically

### Batch Status Workflow

1. **DRAFT**: Newly created, items can be added/edited/removed
2. **PROCESSING**: Currently being executed (temporary status)
3. **COMPLETED**: Successfully processed, read-only, printable
4. **CANCELLED**: Cancelled with reason, no inventory impact

### Search and Filtering

#### Available Filters

- **Text Search**: Transaction number, remarks
- **Transaction Type**: RECEIVE, RELEASE, TRANSFER
- **Status**: DRAFT, COMPLETED, CANCELLED
- **Location**: Any involved location
- **Date Range**: Transaction date filtering

#### Export Options

- **Excel Export**: Full data export with formatting
- **PDF Export**: Professional report layout
- **Print**: Direct browser printing

## Mobile Interface

### Responsive Features

- **Card Layout**: Easy-to-read cards on mobile devices
- **Touch Actions**: Large buttons optimized for touch
- **Swipe Support**: Navigate between items easily
- **Auto-switching**: Automatically detects screen size

### Mobile Card Information

- Transaction number and status
- Type badge with color coding
- Key details (date, locations, totals)
- Quick action buttons
- Expandable details view

## User Permissions

### Required Permissions

- **View**: Access batch transaction listing
- **Add**: Create new batches and add items
- **Modify**: Process, cancel, and edit batches
- **Delete**: Remove items from draft batches
- **Print**: Generate batch printouts

### Role-based Access

- Super Admin: Full access to all functions
- Regular Users: View and basic operations
- Custom roles: Configurable permission sets

## Integration with Inventory System

### Stock Movement Integration

- Each batch item creates individual stock movements
- Proper movement types (RECEIVE, RELEASE, TRANSFER)
- Reference links back to batch transaction
- Maintains complete audit trail

### Location Management

- Validates location permissions
- Ensures valid source/destination combinations
- Prevents invalid transfer operations

### Product Validation

- Verifies product exists and is active
- Checks inventory availability for releases
- Maintains product-location relationships

## API Endpoints

### Main Controller Actions

```php
// Search batch transactions
GET /inventory_batch/search

// Create new batch
POST /inventory_batch/create_batch

// Add item to batch
POST /inventory_batch/add_item

// Update batch item
POST /inventory_batch/update_item

// Delete batch item
POST /inventory_batch/delete_item

// Get batch details with items
GET /inventory_batch/get_batch_details

// Process batch (execute movements)
POST /inventory_batch/process_batch

// Cancel batch with reason
POST /inventory_batch/cancel_batch

// Print batch document
GET /inventory_batch/print_batch
```

### Model Methods

```php
// Search with filtering
$this->batch_transaction_model->search($search, $type, $status, $date_from, $date_to, $location_id);

// Create new batch
$this->batch_transaction_model->create_batch($data);

// Manage items
$this->batch_transaction_model->add_item($batch_id, $item_data);
$this->batch_transaction_model->update_item($item_id, $item_data);
$this->batch_transaction_model->delete_item($item_id);

// Process operations
$this->batch_transaction_model->process_batch($batch_id, $user_id);
$this->batch_transaction_model->cancel_batch($batch_id, $reason);
```

## Error Handling

### Common Validations

- Required fields validation
- Location compatibility checks
- Product availability verification
- Quantity and cost validations
- Status transition rules

### Error Messages

- User-friendly error descriptions
- Specific validation feedback
- Clear action instructions
- Proper error logging

## Performance Considerations

### Optimizations

- Efficient database queries with proper indexing
- AJAX-based updates for responsive UI
- Pagination for large result sets
- Caching of frequently accessed data

### Scalability

- Transaction-based operations for data integrity
- Batch processing for multiple items
- Optimized search algorithms
- Memory-efficient data handling

## Security Features

### Data Protection

- Role-based access control
- Session-based authentication
- SQL injection prevention
- XSS protection

### Audit Trail

- Complete transaction logging
- User action tracking
- Timestamp recording
- Change history maintenance

## Troubleshooting

### Common Issues

1. **Permission Errors**: Check user role permissions
2. **Location Validation**: Verify location setup and access
3. **Product Not Found**: Ensure products are active
4. **Processing Failures**: Check inventory availability

### Support Information

- System logs location: `application/logs/`
- Error reporting: Built-in error handling
- Database backups: Regular automated backups
- Technical support: Contact system administrator

## Future Enhancements

### Planned Features

- Batch templates for recurring transactions
- Advanced approval workflows
- Integration with purchase orders
- Barcode scanning support
- Enhanced reporting dashboard

### Customization Options

- Custom transaction types
- Configurable approval rules
- Custom print layouts
- Extended audit capabilities

---

**Created**: June 2025  
**Version**: 1.0  
**System**: CodeIgniter 3 Clinic Application  
**Module**: Inventory Batch Transactions
