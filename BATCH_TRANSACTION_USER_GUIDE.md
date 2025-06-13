# Batch Transaction System - User Guide

## Overview

The Batch Transaction system allows users to process multiple inventory operations (receive, release, transfer) in a single transaction. This streamlines bulk inventory management and provides comprehensive audit trails.

## Features

### ✅ **Core Functionality**

- **Three Transaction Types**: RECEIVE, RELEASE, TRANSFER
- **Automated Transaction Numbers**: Format: YYYYMMDD0001 (e.g., 202506130001)
- **Batch Status Management**: DRAFT → COMPLETED/CANCELLED
- **Real-time Calculations**: Total items, quantities, and costs
- **Professional Printouts**: Detailed transaction reports

### ✅ **User Interface**

- **Mobile Responsive Design**: Works on desktop, tablet, and mobile
- **DataTables Integration**: Search, sort, export (Excel, PDF, Print)
- **Modern Card Layout**: Clean mobile interface with status badges
- **Real-time Updates**: Live totals and item management

### ✅ **Security & Permissions**

- **Role-based Access**: View, Add, Edit, Delete, Print permissions
- **Transaction Validation**: Prevents invalid operations
- **Audit Trail**: Complete history of all batch operations

## How to Use

### 1. **Create New Batch Transaction**

1. Navigate to **Inventory → Batch Transaction**
2. Click **"New Batch Transaction"** button
3. Fill in the form:
   - **Transaction Date**: Required
   - **Transaction Type**: Choose RECEIVE, RELEASE, or TRANSFER
   - **From Location**: Required for RELEASE and TRANSFER
   - **To Location**: Required for RECEIVE and TRANSFER
   - **Remarks**: Optional notes
4. Click **"Create Batch"**

### 2. **Add Items to Batch**

1. From the batch list, click **"Manage"** on your created batch
2. In the "Add New Item" section:
   - **Product**: Select from dropdown
   - **Quantity**: Enter amount
   - **Unit Cost**: Enter cost (for RECEIVE transactions)
   - **Notes**: Optional item-specific notes
3. Click **"Add Item"**
4. Repeat for all items in the batch

### 3. **Review and Edit Items**

- **Edit Items**: Click pencil icon next to any item
- **Delete Items**: Click trash icon next to any item
- **View Totals**: See real-time totals at the bottom
- **Check Details**: Review all information before processing

### 4. **Process the Batch**

1. Review all items and totals
2. Click **"Process Batch"** button
3. Confirm the action
4. The batch will:
   - Update all stock quantities
   - Create stock movement records
   - Change status to COMPLETED
   - Become read-only

### 5. **Print Transaction Report**

- Click **"Print"** button on any completed batch
- Professional report includes:
  - Company header
  - Transaction details
  - Complete item list
  - Totals and signatures

## Transaction Types Explained

### **RECEIVE Transactions**

- **Purpose**: Add stock to inventory
- **Required**: To Location
- **Effect**: Increases stock at destination
- **Use Case**: Purchasing, returns, adjustments

### **RELEASE Transactions**

- **Purpose**: Remove stock from inventory
- **Required**: From Location
- **Effect**: Decreases stock at source
- **Use Case**: Sales, disposals, consumption

### **TRANSFER Transactions**

- **Purpose**: Move stock between locations
- **Required**: From Location AND To Location
- **Effect**: Decreases source, increases destination
- **Use Case**: Relocating inventory, branch transfers

## Status Workflow

```
DRAFT → [Processing] → COMPLETED
   ↓
CANCELLED
```

- **DRAFT**: Editable, can add/remove items
- **COMPLETED**: Read-only, stock updated, movements created
- **CANCELLED**: Read-only, no stock impact

## Stock Movement Integration

Every processed batch creates detailed stock movement records:

- **Movement Type**: BATCH_RECEIVE, BATCH_RELEASE, BATCH_TRANSFER
- **Reference**: Links back to batch transaction
- **Notes**: Includes batch number and item notes
- **Audit Trail**: Complete history for compliance

## Search and Filtering

Use the advanced filters to find batches:

- **Search**: Transaction number, remarks
- **Type Filter**: RECEIVE, RELEASE, TRANSFER
- **Status Filter**: DRAFT, COMPLETED, CANCELLED
- **Date Range**: From/To dates
- **Location Filter**: Any involved location

## Export Options

**DataTables Integration** provides:

- **Excel Export**: Spreadsheet format
- **PDF Export**: Professional reports
- **Print**: Direct printing
- **Copy**: Copy to clipboard

## Mobile Features

**Responsive Design** ensures full functionality on mobile:

- **Card Layout**: Easy viewing on small screens
- **Touch-friendly**: Large buttons and touch targets
- **Auto-switching**: Desktop table ↔ Mobile cards
- **Full Features**: All functionality available

## Best Practices

### **Planning**

1. **Group Related Items**: Process similar items together
2. **Check Stock Levels**: Verify availability before RELEASE/TRANSFER
3. **Use Descriptive Remarks**: Help with future reference
4. **Double-check Locations**: Ensure correct source/destination

### **Data Entry**

1. **Accurate Quantities**: Double-check all quantities
2. **Correct Costs**: Important for RECEIVE transactions
3. **Item Notes**: Add specific details when needed
4. **Review Before Processing**: Cannot undo once processed

### **Management**

1. **Process Promptly**: Don't leave batches in DRAFT too long
2. **Print Records**: Keep physical copies for audit
3. **Regular Review**: Monitor batch transaction reports
4. **Cancel if Needed**: Better to cancel than process incorrect batch

## Error Handling

The system provides comprehensive validation:

- **Required Fields**: Clear indication of missing data
- **Stock Validation**: Prevents over-releasing stock
- **Location Validation**: Ensures proper source/destination
- **Duplicate Prevention**: Avoids duplicate transaction numbers
- **Error Messages**: Clear, actionable error descriptions

## Troubleshooting

### **Common Issues**

**"Permission Denied"**

- Contact administrator for proper permissions
- Ensure you have the required role access

**"Cannot Process Batch"**

- Check if batch is in DRAFT status
- Verify all items have valid quantities
- Ensure sufficient stock for RELEASE/TRANSFER

**"Product Not Found"**

- Verify product exists and is active
- Check product permissions

**"Location Invalid"**

- Ensure locations exist and are active
- Verify location permissions

## Technical Notes

### **Database Tables**

- `batch_transactions`: Main batch records
- `batch_transaction_items`: Individual items
- `stock_movements`: Integration with movement tracking

### **Transaction Numbers**

- Format: YYYYMMDD#### (Date + 4-digit sequence)
- Unique per day, auto-generated
- Cannot be modified once created

### **Calculations**

- **Total Cost**: Sum of (quantity × unit cost) for all items
- **Total Quantity**: Sum of all item quantities
- **Total Items**: Count of unique items in batch

---

## Support

For technical support or feature requests:

1. Check this documentation first
2. Verify permissions with administrator
3. Contact system administrator
4. Provide specific error messages when reporting issues

**Version**: 1.0  
**Last Updated**: June 13, 2025  
**System**: Clinic Inventory Management
