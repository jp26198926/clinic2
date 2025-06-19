# PHP Test Files

This folder contains all PHP test scripts, debugging utilities, and testing tools.

## File Categories

### Test Scripts

- `test_*.php` - Feature-specific test scripts
- `*_test.php` - Component and functionality tests
- `comprehensive_*.php` - Comprehensive testing scripts

### Debug Scripts

- `debug_*.php` - Debugging and troubleshooting utilities
- `diagnostic_*.php` - System diagnostic scripts

### Feature Testing

- `test_inventory_*.php` - Inventory system testing
- `test_batch_*.php` - Batch transaction testing
- `test_currency_*.php` - Currency system testing
- `test_pdf_*.php` - PDF export testing
- `test_excel_*.php` - Excel export testing

### Access and Security Testing

- `test_controller_access.php` - Controller access testing
- `test_inventory_access.php` - Inventory access verification
- `final_access_test.php` - Final access verification

### Database Testing

- `test_database_*.php` - Database connection and query testing
- `test_db_connection.php` - Database connectivity tests

## Usage

Execute PHP test files via:

### Web Browser

```
http://localhost/clinic2/tests/php/filename.php
```

### Command Line

```bash
php tests/php/filename.php
```

### CodeIgniter Environment

Most test files are designed to run within the CodeIgniter framework environment.

## Test Categories

### Unit Tests

- Individual component testing
- Function and method validation
- Data processing verification

### Integration Tests

- Feature integration testing
- System component interaction
- End-to-end functionality

### Debug Tools

- Error diagnostic utilities
- Performance monitoring
- System state inspection

## Development Notes

- Test files should not be deployed to production
- Some tests require specific database states
- Debug scripts provide detailed output for troubleshooting
- Always review test results before making changes to production code
