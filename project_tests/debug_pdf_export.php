<!DOCTYPE html>
<html>
<head>
    <title>Debug PDF Export</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .test-section { border: 1px solid #ccc; margin: 10px 0; padding: 10px; }
        .success { color: green; }
        .error { color: red; }
        .info { color: blue; }
    </style>
</head>
<body>
    <h1>Debug PDF Export for Inventory Reports</h1>
    
    <div class="test-section">
        <h3>Test 1: Direct PDF Export Link</h3>
        <p>Click the links below to test PDF export directly:</p>
        
        <h4>Low Stock Report (All Locations)</h4>
        <a href="inventory_reports/export_pdf?report_type=low_stock&location_id=0" target="_blank">
            Export Low Stock Report PDF
        </a>
        
        <h4>Stock Valuation Report (All Locations)</h4>
        <a href="inventory_reports/export_pdf?report_type=stock_valuation&location_id=0" target="_blank">
            Export Stock Valuation Report PDF
        </a>
        
        <h4>Zero Stock Report (All Locations)</h4>
        <a href="inventory_reports/export_pdf?report_type=zero_stock&location_id=0" target="_blank">
            Export Zero Stock Report PDF
        </a>
    </div>
    
    <div class="test-section">
        <h3>Test 2: AJAX Form Submission Test</h3>
        <form id="testForm">
            <label>Report Type:</label>
            <select name="report_type" id="report_type">
                <option value="low_stock">Low Stock Report</option>
                <option value="stock_valuation">Stock Valuation Report</option>
                <option value="zero_stock">Zero Stock Report</option>
                <option value="expiring_stock">Expiring Stock Report</option>
                <option value="expired_stock">Expired Stock Report</option>
                <option value="movement_summary">Movement Summary Report</option>
                <option value="abc_analysis">ABC Analysis Report</option>
                <option value="turnover_analysis">Turnover Analysis Report</option>
            </select><br><br>
            
            <label>Location ID:</label>
            <input type="number" name="location_id" value="0" /><br><br>
            
            <label>Date From:</label>
            <input type="date" name="date_from" /><br><br>
            
            <label>Date To:</label>
            <input type="date" name="date_to" /><br><br>
            
            <button type="button" onclick="testPDFExport()">Test PDF Export (New Tab)</button>
        </form>
        
        <div id="testResults"></div>
    </div>
    
    <div class="test-section">
        <h3>Test 3: Error Checking</h3>
        <button onclick="checkPDFLibrary()">Check PDF Library</button>
        <button onclick="checkPermissions()">Check Permissions</button>
        <div id="errorResults"></div>
    </div>
    
    <script>
        function testPDFExport() {
            const form = document.getElementById('testForm');
            const formData = new FormData(form);
            
            // Build URL with parameters
            const params = new URLSearchParams();
            for (let [key, value] of formData.entries()) {
                if (value) params.append(key, value);
            }
            
            const url = 'inventory_reports/export_pdf?' + params.toString();
            console.log('Opening PDF URL:', url);
            
            // Open in new tab
            window.open(url, '_blank');
            
            document.getElementById('testResults').innerHTML = 
                '<p class="info">PDF export URL opened in new tab: ' + url + '</p>';
        }
        
        function checkPDFLibrary() {
            fetch('inventory_reports/export_pdf?report_type=low_stock&location_id=0')
                .then(response => {
                    document.getElementById('errorResults').innerHTML = 
                        '<p class="info">Response Status: ' + response.status + '</p>' +
                        '<p class="info">Content Type: ' + response.headers.get('content-type') + '</p>';
                })
                .catch(error => {
                    document.getElementById('errorResults').innerHTML = 
                        '<p class="error">Error: ' + error.message + '</p>';
                });
        }
        
        function checkPermissions() {
            fetch('inventory_reports')
                .then(response => response.text())
                .then(html => {
                    if (html.includes('Access Denied')) {
                        document.getElementById('errorResults').innerHTML += 
                            '<p class="error">Permission Error: Access Denied</p>';
                    } else {
                        document.getElementById('errorResults').innerHTML += 
                            '<p class="success">Permissions: OK</p>';
                    }
                })
                .catch(error => {
                    document.getElementById('errorResults').innerHTML += 
                        '<p class="error">Permission Check Error: ' + error.message + '</p>';
                });
        }
    </script>
</body>
</html>
