# Enhanced Stock Adjustment System - User Guide

## Overview

The stock adjustment system has been enhanced to provide comprehensive stock management capabilities with three adjustment types, real-time preview, and improved user experience.

## Stock Adjustment Types

### 1. Add Stock (+)

- **Purpose**: Increase current stock levels
- **Use Cases**:
  - Found missing inventory
  - Correcting undercount errors
  - Adding stock from discovered sources
- **Validation**: Only positive quantities allowed
- **Example**: Current stock: 50, Add: 10, Result: 60

### 2. Subtract Stock (-)

- **Purpose**: Decrease current stock levels
- **Use Cases**:
  - Damaged goods removal
  - Expired items disposal
  - Lost/stolen items
  - Correcting overcount errors
- **Validation**: Cannot subtract more than current stock
- **Example**: Current stock: 50, Subtract: 5, Result: 45

### 3. Set Exact Quantity

- **Purpose**: Set stock to a specific amount
- **Use Cases**:
  - Physical count reconciliation
  - Major stock corrections
  - Initial stock setup corrections
- **Validation**: Quantity cannot be negative
- **Example**: Current stock: 47, Set to: 50, Result: 50

## Features

### Real-Time Preview

- Shows current stock level
- Displays the change amount (+ or -)
- Shows final stock quantity
- Color-coded indicators:
  - Blue: Current stock
  - Green: Positive changes (+)
  - Orange: Negative changes (-)
  - Red: Error messages

### Predefined Reasons

- **DAMAGE**: Damaged Items
- **EXPIRED**: Expired Items
- **LOST**: Lost/Missing Items
- **FOUND**: Found Items
- **RECOUNT**: Physical Recount
- **SYSTEM_ERROR**: System Error Correction
- **RETURN**: Supplier Return
- **OTHER**: Other reasons

### Enhanced Validation

- Product and location selection required
- Adjustment type must be selected
- Reason must be specified
- Quantity validation based on adjustment type
- Real-time error display

### Mobile Responsive Design

The stock adjustment modal is fully responsive and provides an optimal experience on mobile devices with:

- Touch-friendly controls
- Responsive layout
- Proper form validation
- Mobile-optimized dropdowns using Chosen plugin

## Usage Instructions

### Desktop/Web Interface

1. **Open Stock Adjustment Modal**

   - Click the "Adjust Stock" button in the toolbar
   - Or use the mobile action buttons on smaller screens

2. **Select Product and Location**

   - Choose the product from the searchable dropdown
   - Select the location where the adjustment will occur
   - Current stock level will be displayed automatically

3. **Choose Adjustment Type**

   - **Add Stock**: Enter quantity to add
   - **Subtract Stock**: Enter quantity to subtract
   - **Set Exact**: Enter the new total quantity

4. **Preview Changes**

   - The system will show a real-time preview
   - Displays current stock, change amount, and final quantity
   - Color-coded for easy understanding

5. **Select Reason and Add Notes**

   - Choose from predefined reasons
   - Add optional additional notes for documentation

6. **Apply Adjustment**
   - Click "Apply Adjustment" to save changes
   - The system will update stock levels and create movement records

### Mobile Interface

The mobile interface provides the same functionality with touch-optimized controls:

- Responsive form layout
- Mobile-friendly dropdowns
- Touch-friendly buttons
- Optimized for smaller screens

## Technical Implementation

### Backend Processing

- **Controller**: `Inventory_stock::adjust_stock()`
- **Validation**: Server-side validation for all inputs
- **Stock Updates**: Direct stock model updates
- **Movement Logging**: Comprehensive movement records

### Movement Records

Each adjustment creates a detailed movement record including:

- Adjustment type (ADD/SUBTRACT/SET)
- Reason category
- Previous and final quantities
- User who made the adjustment
- Timestamp and additional notes

### Security

- Role-based permissions required
- Session validation
- Input sanitization
- SQL injection protection

## Error Handling

### Common Validation Errors

- **"Please select a product"**: Product not selected
- **"Please select a location"**: Location not selected
- **"Please select an adjustment type"**: Adjustment type not chosen
- **"Please select a reason for adjustment"**: Reason not specified
- **"Please enter a valid quantity"**: Invalid quantity for ADD/SUBTRACT
- **"New quantity cannot be negative"**: Negative value in SET mode
- **"Cannot subtract more than current stock"**: Insufficient stock for subtraction

### System Errors

- Session timeout handling
- Database connection errors
- Permission denied errors
- AJAX communication errors

## Integration with Other Systems

### Stock Movements

- All adjustments appear in the stock movements report
- Filterable by adjustment type and reason
- Export capabilities (Excel, PDF, Print)

### Low Stock Alerts

- Adjusted quantities affect low stock calculations
- Real-time updates to stock level indicators

### Reporting

- Adjustment history tracking
- Audit trail for compliance
- Export functionality for external reporting

## Best Practices

### When to Use Each Type

1. **Add Stock**: When you discover additional inventory
2. **Subtract Stock**: When removing damaged or lost items
3. **Set Exact**: When doing physical counts or major corrections

### Documentation

- Always select the most appropriate reason
- Add detailed notes for significant adjustments
- Regular physical counts to verify system accuracy

### Workflow Recommendations

1. Perform physical counts regularly
2. Document all adjustments with proper reasons
3. Review adjustment reports for patterns
4. Train staff on proper adjustment procedures

## Troubleshooting

### Common Issues

- **Dropdown not loading**: Check network connection and permissions
- **Save button disabled**: Ensure all required fields are filled
- **Preview not updating**: Check quantity values and format
- **Permission errors**: Contact administrator for proper role assignment

### Support

For technical issues or questions about the stock adjustment system, contact your system administrator or refer to the main inventory system documentation.
