<?php

// Ensure variables have default values to prevent undefined variable errors
$report_title = $report_title ?? 'Inventory Report';
$company_name = $company_name ?? 'Company Name';
$company_address = $company_address ?? 'Company Address';
$company_contact = $company_contact ?? 'Contact Information';
$page_name = $page_name ?? 'Inventory Reports';
$app_name = $app_name ?? 'Application';
$report_data = $report_data ?? array();
$report_type = $report_type ?? 'general';
$filters = $filters ?? array();
$currency_symbol = $currency_symbol ?? 'K';

function inventory_report_summary($filters, $report_type)
{
    $report_name = ucwords(str_replace('_', ' ', $report_type)) . ' Report';
    
    $details = "<table>
                    <tr>
                        <td align=\"right\" width=\"20%\"><b>REPORT TYPE : </b></td>
                        <td align=\"left\" width=\"30%\">{$report_name}</td>
                        <td align=\"right\" width=\"20%\"><b>GENERATED DATE : </b></td>
                        <td align=\"left\" width=\"30%\">" . date('Y-m-d H:i:s') . "</td>
                    </tr>";
    
    if ($filters['location_name'] && $filters['location_name'] !== 'All Locations') {
        $details .= "<tr>
                        <td align=\"right\" width=\"20%\"><b>LOCATION : </b></td>
                        <td align=\"left\" width=\"30%\">{$filters['location_name']}</td>
                        <td align=\"right\" width=\"20%\"><b>TOTAL RECORDS : </b></td>
                        <td align=\"left\" width=\"30%\">" . ($filters['total_records'] ?: '0') . "</td>
                    </tr>";
    } else {
        $details .= "<tr>
                        <td align=\"right\" width=\"20%\"><b>LOCATION : </b></td>
                        <td align=\"left\" width=\"30%\">All Locations</td>
                        <td align=\"right\" width=\"20%\"><b>TOTAL RECORDS : </b></td>
                        <td align=\"left\" width=\"30%\">" . ($filters['total_records'] ?: '0') . "</td>
                    </tr>";
    }
    
    if ($filters['date_from'] || $filters['date_to']) {
        $date_range = '';
        if ($filters['date_from'] && $filters['date_to']) {
            $date_range = $filters['date_from'] . ' to ' . $filters['date_to'];
        } elseif ($filters['date_from']) {
            $date_range = 'From ' . $filters['date_from'];
        } elseif ($filters['date_to']) {
            $date_range = 'Until ' . $filters['date_to'];
        }
        
        if ($date_range) {
            $details .= "<tr>
                            <td align=\"right\" width=\"20%\"><b>DATE RANGE : </b></td>
                            <td align=\"left\" width=\"80%\" colspan=\"3\">{$date_range}</td>
                        </tr>";
        }
    }
    
    $details .= "</table>";
    return $details;
}

function inventory_report_list($report_data, $report_type, $currency_symbol = 'K')
{
    // Build headers dynamically from the actual data, similar to HTML generation
    $headers = array('#' => '4%'); // Start with row number column
    
    if (!empty($report_data)) {
        $first_row = (array) $report_data[0];
        $field_count = 0;
        
        // Calculate width for dynamic fields (96% remaining after row number)
        $dynamic_fields = array();
        foreach ($first_row as $key => $value) {
            if (in_array($key, ['id', 'product_id', 'location_id', 'created_by', 'updated_by', 'status_id'])) {
                continue; // Skip internal fields
            }
            $dynamic_fields[] = $key;
            $field_count++;
        }
        
        // Calculate equal width for each field
        $field_width = $field_count > 0 ? (96 / $field_count) : 10;
        
        // Build headers dynamically
        foreach ($dynamic_fields as $field) {
            $header_name = strtoupper(str_replace('_', ' ', $field));
            $headers[$header_name] = round($field_width, 1) . '%';
        }
    }

    // Build header row
    $list = "<table cellpadding=\"1\">";
    $list .= "<tr>";
    foreach($headers as $header => $width) {
        $list .= "<th width=\"{$width}\" align=\"center\" border=\"1\"><b>{$header}</b></th>";
    }
    $list .= "</tr>";

    $total_items = 0;

    if (count($report_data) > 0) {
        $row_number = 1;
        foreach ($report_data as $item) {
            $total_items++;
            
            $list .= "<tr>";
            $list .= "<td align=\"center\">{$row_number}</td>";
            
            // Build data cells dynamically to match the headers
            $item_array = (array) $item;
            foreach ($item_array as $key => $value) {
                if (in_array($key, ['id', 'product_id', 'location_id', 'created_by', 'updated_by', 'status_id'])) {
                    continue; // Skip internal fields
                }
                
                // Format values based on field type
                $formatted_value = '';
                if (strpos($key, 'date') !== false && $value) {
                    $formatted_value = date('Y-m-d', strtotime($value));
                } elseif (strpos($key, 'cost') !== false || strpos($key, 'value') !== false) {
                    $formatted_value = $currency_symbol . ' ' . number_format((float)$value, 2);
                } elseif (strpos($key, 'qty') !== false || strpos($key, 'quantity') !== false) {
                    $formatted_value = number_format((float)$value, 2);
                } elseif (strpos($key, 'percentage') !== false) {
                    $formatted_value = number_format((float)$value, 1) . '%';
                } elseif (strpos($key, 'days') !== false && is_numeric($value)) {
                    $formatted_value = number_format((float)$value, 0) . ' days';
                } else {
                    $formatted_value = htmlspecialchars($value ?: 'N/A');
                }
                
                // Determine alignment based on field type
                $alignment = 'left';
                if (strpos($key, 'qty') !== false || strpos($key, 'cost') !== false || 
                    strpos($key, 'value') !== false || strpos($key, 'percentage') !== false ||
                    strpos($key, 'days') !== false) {
                    $alignment = 'right';
                } elseif (strpos($key, 'date') !== false || strpos($key, 'uom') !== false ||
                         strpos($key, 'status') !== false || strpos($key, 'class') !== false ||
                         strpos($key, 'type') !== false) {
                    $alignment = 'center';
                }
                
                $list .= "<td align=\"{$alignment}\">{$formatted_value}</td>";
            }
            
            $list .= "</tr>";
            $row_number++;
        }
        
        // Add summary row
        $list .= "<tr>";
        $list .= "<td colspan=\"" . (count($headers) - 1) . "\" align=\"center\"><b>{$total_items} records found</b></td>";
        $list .= "<td align=\"center\"><b>" . date('Y-m-d H:i') . "</b></td>";
        $list .= "</tr>";
    } else {
        $list .= "<tr>";
        $list .= "<td align=\"center\" colspan=\"" . count($headers) . "\" style=\"padding: 20px; font-style: italic;\">No records found for this report</td>";
        $list .= "</tr>";
    }

    $list .= "</table>";
    return $list;
}

function inventory_report_summary_statistics($report_data, $report_type, $currency_symbol = 'K')
{
    $total_items = count($report_data);
    
    $stats = "<table>
                <tr>
                    <td align=\"right\" width=\"25%\"><b>TOTAL RECORDS : </b></td>
                    <td align=\"left\" width=\"25%\">" . number_format($total_items, 0) . "</td>";
    
    if ($report_type === 'low_stock') {
        $critical_count = 0;
        $low_count = 0;
        foreach ($report_data as $item) {
            if (isset($item->stock_percentage) && $item->stock_percentage < 25) {
                $critical_count++;
            } elseif (isset($item->stock_percentage) && $item->stock_percentage < 50) {
                $low_count++;
            }
        }
        $stats .= "<td align=\"right\" width=\"25%\"><b>CRITICAL ITEMS : </b></td>
                   <td align=\"left\" width=\"25%\">{$critical_count}</td>";
        $stats .= "</tr><tr>
                   <td align=\"right\" width=\"25%\"><b>LOW STOCK ITEMS : </b></td>
                   <td align=\"left\" width=\"25%\">{$low_count}</td>
                   <td align=\"right\" width=\"25%\"><b>GENERATED : </b></td>
                   <td align=\"left\" width=\"25%\">" . date('Y-m-d H:i:s') . "</td>";
                   
    } elseif ($report_type === 'stock_valuation') {
        $total_value = 0;
        foreach ($report_data as $item) {
            if (isset($item->total_value)) {
                $total_value += $item->total_value;
            }
        }
        $stats .= "<td align=\"right\" width=\"25%\"><b>TOTAL VALUE : </b></td>
                   <td align=\"left\" width=\"25%\">" . $currency_symbol . ' ' . number_format($total_value, 2) . "</td>";
        $stats .= "</tr><tr>
                   <td align=\"right\" width=\"25%\"><b>AVERAGE VALUE : </b></td>
                   <td align=\"left\" width=\"25%\">" . $currency_symbol . ' ' . number_format(($total_items > 0 ? $total_value / $total_items : 0), 2) . "</td>
                   <td align=\"right\" width=\"25%\"><b>GENERATED : </b></td>
                   <td align=\"left\" width=\"25%\">" . date('Y-m-d H:i:s') . "</td>";
                   
    } elseif ($report_type === 'abc_analysis') {
        $a_count = $b_count = $c_count = 0;
        foreach ($report_data as $item) {
            if (isset($item->abc_class)) {
                switch($item->abc_class) {
                    case 'A': $a_count++; break;
                    case 'B': $b_count++; break;
                    case 'C': $c_count++; break;
                }
            }
        }
        $stats .= "<td align=\"right\" width=\"25%\"><b>CLASS A ITEMS : </b></td>
                   <td align=\"left\" width=\"25%\">{$a_count}</td>";
        $stats .= "</tr><tr>
                   <td align=\"right\" width=\"25%\"><b>CLASS B ITEMS : </b></td>
                   <td align=\"left\" width=\"25%\">{$b_count}</td>
                   <td align=\"right\" width=\"25%\"><b>CLASS C ITEMS : </b></td>
                   <td align=\"left\" width=\"25%\">{$c_count}</td>";
                   
    } elseif ($report_type === 'turnover_analysis') {
        $fast_count = $slow_count = 0;
        $total_value = 0;
        foreach ($report_data as $item) {
            if (isset($item->turnover_status)) {
                if ($item->turnover_status === 'Fast Moving') $fast_count++;
                elseif ($item->turnover_status === 'Slow Moving') $slow_count++;
            }
            if (isset($item->total_value)) {
                $total_value += $item->total_value;
            }
        }
        $stats .= "<td align=\"right\" width=\"25%\"><b>FAST MOVING : </b></td>
                   <td align=\"left\" width=\"25%\">{$fast_count}</td>";
        $stats .= "</tr><tr>
                   <td align=\"right\" width=\"25%\"><b>SLOW MOVING : </b></td>
                   <td align=\"left\" width=\"25%\">{$slow_count}</td>
                   <td align=\"right\" width=\"25%\"><b>TOTAL VALUE : </b></td>
                   <td align=\"left\" width=\"25%\">" . $currency_symbol . ' ' . number_format($total_value, 2) . "</td>";
                   
    } elseif ($report_type === 'movement_summary') {
        $total_qty = 0;
        $total_value = 0;
        $receive_qty = 0;
        $issue_qty = 0;
        
        foreach ($report_data as $item) {
            if (isset($item->total_qty) && isset($item->movement_type)) {
                $qty = (float)$item->total_qty;
                $total_qty += $qty;
                
                // Track different movement types for better understanding
                if (strtoupper($item->movement_type) === 'RECEIVE' || strtoupper($item->movement_type) === 'PURCHASE') {
                    $receive_qty += $qty;
                } elseif (strtoupper($item->movement_type) === 'ISSUE' || strtoupper($item->movement_type) === 'SALE') {
                    $issue_qty += $qty;
                }
            }
            if (isset($item->total_value)) {
                $total_value += (float)$item->total_value;
            }
        }
        
        $stats .= "<td align=\"right\" width=\"25%\"><b>TOTAL QUANTITY : </b></td>
                   <td align=\"left\" width=\"25%\">" . number_format($total_qty, 2) . "</td>";
        $stats .= "</tr><tr>
                   <td align=\"right\" width=\"25%\"><b>TOTAL VALUE : </b></td>
                   <td align=\"left\" width=\"25%\">" . $currency_symbol . ' ' . number_format($total_value, 2) . "</td>
                   <td align=\"right\" width=\"25%\"><b>GENERATED : </b></td>
                   <td align=\"left\" width=\"25%\">" . date('Y-m-d H:i:s') . "</td>";
                   
    } else {
        $stats .= "<td align=\"right\" width=\"25%\"><b>REPORT TYPE : </b></td>
                   <td align=\"left\" width=\"25%\">" . ucwords(str_replace('_', ' ', $report_type)) . "</td>";
        $stats .= "</tr><tr>
                   <td align=\"right\" width=\"25%\"><b>GENERATED : </b></td>
                   <td align=\"left\" width=\"75%\" colspan=\"3\">" . date('Y-m-d H:i:s') . "</td>";
    }
    
    $stats .= "</tr></table>";
    return $stats;
}

// PDF Generation
$pdf = new Pdf('L', 'mm', 'A4', true, 'UTF-8', false); // Landscape orientation for better table fit

$pdf->setFontSubsetting(false);

$pdf->myHeader($company_name, $company_address, $company_contact, $report_title, "", 25);
$pdf->myFooter("Printed: " . date("Y-m-d H:i:s") . " - " . $app_name, "Record ", -15, "");

$pdf->SetAutoPageBreak(true, 15);
$pdf->SetAuthor('noSystems Online');
$pdf->SetTitle('Clinic - ' . $report_title);
$pdf->SetSubject('Inventory Report: ' . $report_type);
$pdf->SetKeywords('DOWNLOAD, PDF, INVENTORY, REPORT, ' . strtoupper($report_type));
$pdf->SetTopMargin(25);

$pdf->AddPage('L', 'A4'); // Landscape page
$pdf->SetFont('saxmono', 'N', 10);
$pdf->writeHTML(inventory_report_summary($filters, $report_type), true, false, false, false, '');

$pdf->writeHTML("REPORT SUMMARY STATISTICS", true, false, false, false, '');
$pdf->SetFont('saxmono', 'N', 9);
$pdf->writeHTML(inventory_report_summary_statistics($report_data, $report_type, $currency_symbol), true, false, false, false, '');

$pdf->writeHTML("DETAILED " . strtoupper($report_title), true, false, false, false, '');
$pdf->SetFont('saxmono', 'N', 7); // Smaller font for detailed table
$pdf->writeHTML(inventory_report_list($report_data, $report_type, $currency_symbol), true, false, false, false, '');

// Generate filename with current date, report type and filters
$filename = strtoupper(str_replace('_', '-', $report_type)) . '-REPORT-' . date('Y-m-d');
if ($filters['location_name'] && $filters['location_name'] !== 'All Locations') {
    $filename .= '-' . str_replace(' ', '-', strtoupper($filters['location_name']));
}
$filename .= '.pdf';

$pdf->Output($filename, 'I'); // I = Inline display in browser

?>
