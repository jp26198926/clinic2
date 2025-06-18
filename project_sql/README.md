# Project SQL Scripts

This folder contains all SQL scripts and database-related files for the clinic2 project.

## SQL File Categories

### Database Migration Scripts

- **Database structure changes and updates**
- Schema modifications and new table creation
- Data migration and transformation scripts

### Menu & Navigation Scripts

- **System menu configuration**
- Navigation structure setup
- Module access permissions

### Feature Implementation Scripts

- **New feature database requirements**
- Table creation for specific modules
- Data seeding for new functionality

## File Descriptions

### Core Database Scripts

#### `manual_migration.sql`

- **Purpose:** Manual database migration script
- **Contains:** Essential schema updates and data fixes
- **Usage:** Run when automatic migrations are not available
- **Target:** Core database structure

#### `add_inventory_reports.sql`

- **Purpose:** Inventory reports module database setup
- **Contains:** Tables and data for inventory reporting functionality
- **Usage:** Execute to enable inventory reports feature
- **Dependencies:** Core inventory tables must exist

### Menu Configuration Scripts

#### `sql_inventory_menu.sql`

- **Purpose:** Inventory module menu configuration
- **Contains:** Menu items, permissions, and navigation structure
- **Usage:** Sets up inventory module access and navigation
- **Target:** System menu tables

#### `sql_pharmacy_menu.sql`

- **Purpose:** Pharmacy module menu configuration
- **Contains:** Pharmacy-specific menu items and permissions
- **Usage:** Enables pharmacy module navigation
- **Target:** System menu and permissions tables

### Transaction Scripts

#### `sql_batch_transaction.sql`

- **Purpose:** Batch transaction system setup
- **Contains:** Batch processing tables and procedures
- **Usage:** Enables batch transaction functionality
- **Dependencies:** Core transaction tables

## Usage Instructions

### Running SQL Scripts

#### Via MySQL Command Line:

```bash
# Navigate to SQL folder
cd /c/laragon/www/clinic2/project_sql

# Run specific script
mysql -u username -p database_name < script_name.sql
```

#### Via phpMyAdmin:

1. Open phpMyAdmin
2. Select target database
3. Go to "Import" tab
4. Choose SQL file from this folder
5. Execute import

#### Via MySQL Workbench:

1. Open MySQL Workbench
2. Connect to database
3. File → Run SQL Script
4. Select file from this folder
5. Execute script

### Execution Order

For new installations, execute in this order:

1. **`manual_migration.sql`** - Core database structure
2. **`sql_inventory_menu.sql`** - Inventory module setup
3. **`sql_pharmacy_menu.sql`** - Pharmacy module setup (if needed)
4. **`add_inventory_reports.sql`** - Inventory reports functionality
5. **`sql_batch_transaction.sql`** - Batch transaction system

### Script Dependencies

```
manual_migration.sql (base)
├── sql_inventory_menu.sql
│   ├── add_inventory_reports.sql
│   └── sql_batch_transaction.sql
└── sql_pharmacy_menu.sql
```

## Database Backup Recommendations

### Before Running Scripts:

```sql
-- Create backup
mysqldump -u username -p database_name > backup_$(date +%Y%m%d_%H%M%S).sql
```

### After Running Scripts:

```sql
-- Verify tables created
SHOW TABLES LIKE '%inventory%';
SHOW TABLES LIKE '%batch%';
SHOW TABLES LIKE '%pharmacy%';
```

## Error Handling

### Common Issues:

#### 1. **Table Already Exists**

```sql
-- Solution: Add IF NOT EXISTS
CREATE TABLE IF NOT EXISTS table_name (...);
```

#### 2. **Missing Dependencies**

```sql
-- Solution: Check foreign key references
SHOW CREATE TABLE dependent_table;
```

#### 3. **Permission Errors**

```sql
-- Solution: Grant appropriate permissions
GRANT ALL PRIVILEGES ON database_name.* TO 'username'@'localhost';
```

## Testing Scripts

After executing SQL scripts, verify with:

```sql
-- Check inventory tables
SELECT COUNT(*) FROM products;
SELECT COUNT(*) FROM stock;
SELECT COUNT(*) FROM stock_movements;

-- Check menu configuration
SELECT * FROM system_modules WHERE module_name LIKE '%inventory%';
SELECT * FROM user_permissions WHERE module_id IN (...);

-- Check batch transaction tables
SELECT COUNT(*) FROM batch_transactions;
SELECT COUNT(*) FROM batch_items;
```

## Maintenance

### Regular Tasks:

- Keep scripts updated with schema changes
- Document any manual modifications
- Test scripts on development environment first
- Maintain execution order documentation

### Version Control:

- Each script should include version/date information
- Document changes in script headers
- Keep previous versions for rollback if needed

## Security Notes

- **Never run scripts directly on production** without testing
- **Always backup database** before executing scripts
- **Review script contents** before execution
- **Use appropriate user permissions** for script execution
- **Validate data integrity** after script execution

## Contact

For issues with SQL scripts:

1. Check error logs in MySQL
2. Verify database connectivity
3. Confirm user permissions
4. Review script dependencies
5. Test on development environment first
