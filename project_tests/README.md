# Project Tests

This folder contains all test scripts and debugging utilities for the clinic2 project.

## Test File Types

### HTML Test Files

- **Test interfaces and UI validation**
- Files ending with `.html`
- Can be opened directly in browser for testing

### PHP Test Scripts

- **Backend functionality testing**
- Files starting with `test_`, `debug_`, `verify_`
- Run via PHP CLI or web browser

### Utility Scripts

- **Helper scripts for development and debugging**
- Various PHP files for specific testing purposes

## Categories

### Inventory Tests

- `test_inventory*.php/html` - Inventory module testing
- `debug_inventory*.php` - Inventory debugging scripts
- `inventory_*_test.html` - Inventory UI tests

### Batch Transaction Tests

- `test_batch*.php` - Batch transaction functionality
- `batch_*_test.html` - Batch UI testing

### PDF Export Tests

- `test_pdf*.php/html` - PDF generation testing
- `pdf_*_test.html` - PDF export UI tests

### Currency & Formatting Tests

- `test_currency*.php/html` - Currency display testing
- `currency_*_test.html` - Currency UI tests

### Database Tests

- `test_db*.php` - Database connectivity and query testing
- `test_database*.php` - Database functionality tests

### System Tests

- `debug_session*.php` - Session management testing
- `test_controller*.php` - Controller access testing
- `verify_*.php` - Implementation verification scripts

## Usage

1. **HTML Files**: Open directly in web browser
2. **PHP Files**: Run via web server (http://localhost/clinic2/project_tests/filename.php)
3. **CLI Testing**: Run with `php filename.php` from this directory

## Note

These files are for development and testing purposes only. Do not use in production environment.
