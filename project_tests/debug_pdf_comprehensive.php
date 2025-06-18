<?php
// Comprehensive PDF Debug Script
echo "<h1>PDF Export Debug - Comprehensive Test</h1>";

// Include CodeIgniter bootstrap
$system_path = 'system';
$application_folder = 'application';
define('BASEPATH', $system_path);
define('APPPATH', $application_folder.'/');

// Test 1: Check if PDF library exists
echo "<h2>1. PDF Library Test</h2>";
$pdf_lib_path = APPPATH . 'libraries/Pdf.php';
if (file_exists($pdf_lib_path)) {
    echo "✅ PDF library exists at: $pdf_lib_path<br>";
    
    // Try to include and instantiate
    try {
        require_once($pdf_lib_path);
        if (class_exists('Pdf')) {
            echo "✅ PDF class loaded successfully<br>";
            
            // Try to create instance
            $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
            echo "✅ PDF instance created successfully<br>";
        } else {
            echo "❌ PDF class not found after include<br>";
        }
    } catch (Exception $e) {
        echo "❌ Error loading PDF class: " . $e->getMessage() . "<br>";
    }
} else {
    echo "❌ PDF library not found at: $pdf_lib_path<br>";
}

// Test 2: Check PDF template file
echo "<h2>2. PDF Template Test</h2>";
$pdf_template_path = APPPATH . 'views/pdf/inventory_reports.php';
if (file_exists($pdf_template_path)) {
    echo "✅ PDF template exists at: $pdf_template_path<br>";
    
    // Check for syntax errors
    $output = [];
    $return_var = 0;
    exec("php -l \"$pdf_template_path\"", $output, $return_var);
    
    if ($return_var === 0) {
        echo "✅ PDF template has no syntax errors<br>";
    } else {
        echo "❌ PDF template has syntax errors:<br>";
        echo implode('<br>', $output);
    }
} else {
    echo "❌ PDF template not found at: $pdf_template_path<br>";
}

// Test 3: Test PDF template functions
echo "<h2>3. PDF Template Functions Test</h2>";
if (file_exists($pdf_template_path)) {
    // Include the template to test functions
    ob_start();
    include($pdf_template_path);
    $template_output = ob_get_clean();
    
    if (function_exists('inventory_report_summary')) {
        echo "✅ inventory_report_summary function exists<br>";
        
        // Test the function
        $test_filters = array(
            'location_name' => 'Test Location',
            'date_from' => '2025-01-01',
            'date_to' => '2025-06-17',
            'total_records' => 5
        );
        
        try {
            $summary = inventory_report_summary($test_filters, 'low_stock');
            echo "✅ inventory_report_summary function works<br>";
            echo "Summary length: " . strlen($summary) . " characters<br>";
        } catch (Exception $e) {
            echo "❌ Error in inventory_report_summary: " . $e->getMessage() . "<br>";
        }
    } else {
        echo "❌ inventory_report_summary function not found<br>";
    }
    
    if (function_exists('inventory_report_list')) {
        echo "✅ inventory_report_list function exists<br>";
    } else {
        echo "❌ inventory_report_list function not found<br>";
    }
    
    if (function_exists('inventory_report_summary_statistics')) {
        echo "✅ inventory_report_summary_statistics function exists<br>";
    } else {
        echo "❌ inventory_report_summary_statistics function not found<br>";
    }
}

// Test 4: Test direct PDF URL access
echo "<h2>4. Direct PDF URL Test</h2>";
$pdf_url = "http://localhost:6066/clinic2/inventory_reports/export_pdf?report_type=low_stock&location_id=0";
echo "Test URL: <a href='$pdf_url' target='_blank'>$pdf_url</a><br>";
echo "Click the link above to test direct PDF access.<br>";

// Test 5: Test with CURL
echo "<h2>5. CURL Test</h2>";
if (function_exists('curl_init')) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $pdf_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    echo "HTTP Code: $http_code<br>";
    if ($error) {
        echo "CURL Error: $error<br>";
    }
    
    if ($response) {
        // Split headers and body
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $headers = substr($response, 0, $header_size);
        $body = substr($response, $header_size);
        
        echo "Response Headers:<br>";
        echo "<pre>" . htmlspecialchars($headers) . "</pre>";
        
        echo "Response Body (first 500 chars):<br>";
        echo "<pre>" . htmlspecialchars(substr($body, 0, 500)) . "</pre>";
        
        // Check if it's PDF content
        if (strpos($headers, 'application/pdf') !== false) {
            echo "✅ Response contains PDF content type<br>";
        } else {
            echo "❌ Response does not contain PDF content type<br>";
        }
    }
} else {
    echo "❌ CURL not available<br>";
}

// Test 6: Check session and authentication
echo "<h2>6. Session & Authentication Test</h2>";
session_start();
if (isset($_SESSION)) {
    echo "✅ Session is available<br>";
    echo "Session ID: " . session_id() . "<br>";
    
    // Look for clinic session data
    foreach ($_SESSION as $key => $value) {
        if (strpos($key, 'logged_in') !== false) {
            echo "Found session key: $key<br>";
        }
    }
} else {
    echo "❌ Session not available<br>";
}

echo "<h2>Debug Complete</h2>";
echo "If the PDF URL test above shows a white page or error, check the CodeIgniter logs in application/logs/<br>";
?>
