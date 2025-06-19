# SQL Test Files

This folder contains all SQL test files, migration scripts, and database setup files.

## File Categories

### Database Setup Scripts

- `add_inventory_*.sql` - Inventory system setup scripts
- `sql_*.sql` - Menu and system configuration SQL
- `manual_migration.sql` - Manual database migration scripts

### Test Data Scripts

- Database schema updates
- Test data insertion scripts
- Development database configurations

### Migration Scripts

- Database structure updates
- Feature addition scripts
- System enhancement SQL

## Usage

These SQL files can be executed against the database for:

- Setting up test environments
- Adding new features to the database
- Creating test data
- Database migrations and updates

## Execution

Execute SQL files using your preferred database client:

```sql
-- Example execution in MySQL
SOURCE path/to/file.sql;
```

Or via command line:

```bash
mysql -u username -p database_name < file.sql
```

## Note

Always backup your database before executing any SQL scripts, especially in production environments.
