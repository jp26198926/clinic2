# Project Test Files

This folder contains all test files and debugging utilities for the clinic management system project.

## Folder Structure

```
tests/
├── sql/                    # SQL test files and database scripts
├── php/                    # PHP test scripts and debugging utilities
├── *.html                  # HTML test files and UI testing
├── *.xml                   # Testing configuration files
└── README files            # Documentation for each folder
```

## File Types

### 📁 SQL Test Files (`sql/` folder)

- Database setup and migration scripts
- Test data insertion scripts
- Menu and system configuration SQL
- Feature addition and update scripts

### 📁 PHP Test Files (`php/` folder)

- `test_*.php` - Feature-specific test scripts
- `debug_*.php` - Debugging and troubleshooting utilities
- `comprehensive_*.php` - Comprehensive testing scripts
- `diagnostic_*.php` - System diagnostic tools

### 📄 HTML Test Files (main folder)

- `test_*.html` - Various testing pages for different features
- `debug_*.html` - Debugging and troubleshooting pages
- `*_test.html` - Feature-specific test implementations
- `final_*.html` - Final testing and verification pages

### 📄 Implementation Verification Files

- `*_implementation_complete.html` - Implementation verification pages
- `*_ui_update_complete.html` - UI update verification pages
- `keyboard_navigation_*.html` - Keyboard navigation testing
- `pdf_template_*.html` - PDF template testing

## Purpose

These files are used for:

- Testing new features before deployment
- Debugging issues and problems
- Verifying implementations work correctly
- UI/UX testing and validation
- Performance and compatibility testing
- Database setup and migrations

## Usage

### HTML Test Files

```
http://localhost/clinic2/tests/test_filename.html
```

### PHP Test Files

```
http://localhost/clinic2/tests/php/test_filename.php
```

### SQL Files

Execute via database client or command line:

```sql
SOURCE tests/sql/filename.sql;
```

## Note

These files are for development and testing purposes only and should not be deployed to production servers.
