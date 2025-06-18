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
    // Define column headers based on report type
    $headers = array();
    switch($report_type) {
        case 'low_stock':
            $headers = array(
                '#' => '4%',
                'PRODUCT CODE' => '10%',
                'PRODUCT NAME' => '20%',
                'CATEGORY' => '12%',
                'LOCATION' => '12%',
                'CURRENT STOCK' => '8%',
                'MIN LEVEL' => '8%',
                'SHORTAGE QTY' => '8%',
                'STOCK %' => '8%',
                'UOM' => '10%'
            );
            break;
        case 'stock_valuation':
            $headers = array(
                '#' => '4%',
                'PRODUCT CODE' => '10%',
                'PRODUCT NAME' => '20%',
                'CATEGORY' => '12%',
                'LOCATION' => '12%',
                'STOCK QTY' => '8%',
                'UNIT COST' => '10%',
                'TOTAL VALUE' => '12%',
                'UOM' => '12%'
            );
            break;
        case 'expiring_stock':
            $headers = array(
                '#' => '4%',
                'PRODUCT CODE' => '10%',
                'PRODUCT NAME' => '18%',
                'CATEGORY' => '10%',
                'LOCATION' => '10%',
                'STOCK QTY' => '8%',
                'EXPIRY DATE' => '10%',
                'DAYS TO EXPIRE' => '10%',
                'TOTAL VALUE' => '10%',
                'UOM' => '10%'
            );
            break;
        case 'expired_stock':
            $headers = array(
                '#' => '4%',
                'PRODUCT CODE' => '10%',
                'PRODUCT NAME' => '18%',
                'CATEGORY' => '10%',
                'LOCATION' => '10%',
                'STOCK QTY' => '8%',
                'EXPIRY DATE' => '10%',
                'DAYS EXPIRED' => '10%',
                'TOTAL VALUE' => '10%',
                'UOM' => '10%'
            );
            break;
        case 'zero_stock':
            $headers = array(
                '#' => '4%',
                'PRODUCT CODE' => '12%',
                'PRODUCT NAME' => '25%',
                'CATEGORY' => '15%',
                'LOCATION' => '15%',
                'STOCK QTY' => '10%',
                'MIN LEVEL' => '10%',
                'UOM' => '9%'
            );
            break;
        case 'movement_summary':
            $headers = array(
                '#' => '4%',
                'PRODUCT CODE' => '10%',
                'PRODUCT NAME' => '18%',
                'CATEGORY' => '10%',
                'LOCATION' => '12%',
                'MOVEMENT TYPE' => '12%',
                'TOTAL QTY' => '10%',
                'TOTAL VALUE' => '12%',
                'LAST MOVEMENT' => '12%'
            );
            break;
        case 'abc_analysis':
            $headers = array(
                '#' => '4%',
                'PRODUCT CODE' => '10%',
                'PRODUCT NAME' => '20%',
                'CATEGORY' => '12%',
                'LOCATION' => '12%',
                'ANNUAL USAGE' => '10%',
                'UNIT VALUE' => '10%',
                'ANNUAL VALUE' => '12%',
                'ABC CLASS' => '10%'
            );
            break;
        case 'turnover_analysis':
            $headers = array(
                '#' => '4%',
                'PRODUCT CODE' => '10%',
                'PRODUCT NAME' => '18%',
                'CATEGORY' => '10%',
                'LOCATION' => '12%',
                'STOCK QTY' => '8%',
                'TURNOVER RATE' => '10%',
                'DAYS SUPPLY' => '10%',
                'STATUS' => '8%',
                'TOTAL VALUE' => '10%'
            );
            break;
        default:
            $headers = array(
                '#' => '5%',
                'PRODUCT CODE' => '15%',
                'PRODUCT NAME' => '25%',
                'CATEGORY' => '15%',
                'LOCATION' => '15%',
                'STOCK QTY' => '10%',
                'UOM' => '15%'
            );
    }
    
    // Build header row
    $list = "<table cellpadding=\"1\">";
    $list .= "<tr>";
    foreach($headers as $header => $width) {
        $list .= "<th width=\"{$width}\" align=\"center\" border=\"1\"><b>{$header}</b></th>";
    }
    $list .= "</tr>";

    $total_items = 0;
    $summary_stats = array();

    if (count($report_data) > 0) {
        $row_number = 1;
        foreach ($report_data as $item) {
            $total_items++;
            
            $list .= "<tr>";
            $list .= "<td align=\"center\">{$row_number}</td>";
            
            switch($report_type) {
                case 'low_stock':
                    $product_code = htmlspecialchars($item->product_code ?? 'N/A');
                    $product_name = htmlspecialchars($item->product_name ?? 'N/A');
                    $category = htmlspecialchars($item->category ?? 'N/A');
                    $location = htmlspecialchars($item->location ?? 'N/A');
                    $current_stock = number_format($item->qty_on_hand ?? 0, 2);
                    $min_level = number_format($item->reorder_level ?? 0, 2);
                    $shortage_qty = number_format($item->shortage_qty ?? 0, 2);
                    $stock_percentage = number_format($item->stock_percentage ?? 0, 1) . '%';
                    $uom = htmlspecialchars($item->uom ?? 'N/A');
                    
                    $list .= "<td align=\"left\">{$product_code}</td>";
                    $list .= "<td align=\"left\">{$product_name}</td>";
                    $list .= "<td align=\"left\">{$category}</td>";
                    $list .= "<td align=\"left\">{$location}</td>";
                    $list .= "<td align=\"right\">{$current_stock}</td>";
                    $list .= "<td align=\"right\">{$min_level}</td>";
                    $list .= "<td align=\"right\">{$shortage_qty}</td>";
                    $list .= "<td align=\"right\">{$stock_percentage}</td>";
                    $list .= "<td align=\"center\">{$uom}</td>";
                    break;
                    
                case 'stock_valuation':
                    $product_code = htmlspecialchars($item->product_code ?? 'N/A');
                    $product_name = htmlspecialchars($item->product_name ?? 'N/A');
                    $category = htmlspecialchars($item->category ?? 'N/A');
                    $location = htmlspecialchars($item->location ?? 'N/A');
                    $stock_qty = number_format($item->qty_on_hand ?? 0, 2);
                    $unit_cost = $currency_symbol . number_format($item->unit_cost ?? 0, 2);
                    $total_value = $currency_symbol . number_format($item->total_value ?? 0, 2);
                    $uom = htmlspecialchars($item->uom ?? 'N/A');
                    
                    $list .= "<td align=\"left\">{$product_code}</td>";
                    $list .= "<td align=\"left\">{$product_name}</td>";
                    $list .= "<td align=\"left\">{$category}</td>";
                    $list .= "<td align=\"left\">{$location}</td>";
                    $list .= "<td align=\"right\">{$stock_qty}</td>";
                    $list .= "<td align=\"right\">{$unit_cost}</td>";
                    $list .= "<td align=\"right\">{$total_value}</td>";
                    $list .= "<td align=\"center\">{$uom}</td>";
                    break;
                    
                case 'expiring_stock':
                    $product_code = htmlspecialchars($item->product_code ?? 'N/A');
                    $product_name = htmlspecialchars($item->product_name ?? 'N/A');
                    $category = htmlspecialchars($item->category ?? 'N/A');
                    $location = htmlspecialchars($item->location ?? 'N/A');
                    $stock_qty = number_format($item->qty_on_hand ?? 0, 2);
                    $expiry_date = $item->expiration_date ?? 'N/A';
                    $days_to_expire = ($item->days_to_expiry ?? 0) . ' days';
                    $total_value = $currency_symbol . number_format($item->total_value ?? 0, 2);
                    $uom = htmlspecialchars($item->uom ?? 'N/A');
                    
                    $list .= "<td align=\"left\">{$product_code}</td>";
                    $list .= "<td align=\"left\">{$product_name}</td>";
                    $list .= "<td align=\"left\">{$category}</td>";
                    $list .= "<td align=\"left\">{$location}</td>";
                    $list .= "<td align=\"right\">{$stock_qty}</td>";
                    $list .= "<td align=\"center\">{$expiry_date}</td>";
                    $list .= "<td align=\"right\">{$days_to_expire}</td>";
                    $list .= "<td align=\"right\">{$total_value}</td>";
                    $list .= "<td align=\"center\">{$uom}</td>";
                    break;
                    
                case 'expired_stock':
                    $product_code = htmlspecialchars($item->product_code ?? 'N/A');
                    $product_name = htmlspecialchars($item->product_name ?? 'N/A');
                    $category = htmlspecialchars($item->category ?? 'N/A');
                    $location = htmlspecialchars($item->location ?? 'N/A');
                    $stock_qty = number_format($item->qty_on_hand ?? 0, 2);
                    $expiry_date = $item->expiration_date ?? 'N/A';
                    $days_expired = ($item->days_expired ?? 0) . ' days ago';
                    $total_value = $currency_symbol . number_format($item->total_value ?? 0, 2);
                    $uom = htmlspecialchars($item->uom ?? 'N/A');
                    
                    $list .= "<td align=\"left\">{$product_code}</td>";
                    $list .= "<td align=\"left\">{$product_name}</td>";
                    $list .= "<td align=\"left\">{$category}</td>";
                    $list .= "<td align=\"left\">{$location}</td>";
                    $list .= "<td align=\"right\">{$stock_qty}</td>";
                    $list .= "<td align=\"center\">{$expiry_date}</td>";
                    $list .= "<td align=\"right\">{$days_expired}</td>";
                    $list .= "<td align=\"right\">{$total_value}</td>";
                    $list .= "<td align=\"center\">{$uom}</td>";
                    break;
                    
                case 'movement_summary':
                    $product_code = htmlspecialchars($item->product_code ?? 'N/A');
                    $product_name = htmlspecialchars($item->product_name ?? 'N/A');
                    $category = htmlspecialchars($item->category ?? 'N/A');
                    $location = htmlspecialchars($item->location ?? 'N/A');
                    $movement_type = htmlspecialchars($item->movement_type ?? 'N/A');
                    $total_qty = number_format($item->total_qty ?? 0, 2);
                    $total_value = $currency_symbol . number_format($item->total_value ?? 0, 2);
                    $last_movement = $item->last_movement_date ?? 'N/A';
                    
                    $list .= "<td align=\"left\">{$product_code}</td>";
                    $list .= "<td align=\"left\">{$product_name}</td>";
                    $list .= "<td align=\"left\">{$category}</td>";
                    $list .= "<td align=\"left\">{$location}</td>";
                    $list .= "<td align=\"center\">{$movement_type}</td>";
                    $list .= "<td align=\"right\">{$total_qty}</td>";
                    $list .= "<td align=\"right\">{$total_value}</td>";
                    $list .= "<td align=\"center\">{$last_movement}</td>";
                    break;
                    
                case 'abc_analysis':
                    $product_code = htmlspecialchars($item->product_code ?? 'N/A');
                    $product_name = htmlspecialchars($item->product_name ?? 'N/A');
                    $category = htmlspecialchars($item->category ?? 'N/A');
                    $location = htmlspecialchars($item->location ?? 'N/A');
                    $annual_usage = number_format($item->annual_usage ?? 0, 2);
                    $unit_value = $currency_symbol . number_format($item->unit_value ?? 0, 2);
                    $annual_value = $currency_symbol . number_format($item->annual_value ?? 0, 2);
                    $abc_class = htmlspecialchars($item->abc_class ?? 'N/A');
                    
                    $list .= "<td align=\"left\">{$product_code}</td>";
                    $list .= "<td align=\"left\">{$product_name}</td>";
                    $list .= "<td align=\"left\">{$category}</td>";
                    $list .= "<td align=\"left\">{$location}</td>";
                    $list .= "<td align=\"right\">{$annual_usage}</td>";
                    $list .= "<td align=\"right\">{$unit_value}</td>";
                    $list .= "<td align=\"right\">{$annual_value}</td>";
                    $list .= "<td align=\"center\">{$abc_class}</td>";
                    break;
                    
                case 'turnover_analysis':
                    $product_code = htmlspecialchars($item->product_code ?? 'N/A');
                    $product_name = htmlspecialchars($item->product_name ?? 'N/A');
                    $category = htmlspecialchars($item->category ?? 'N/A');
                    $location = htmlspecialchars($item->location ?? 'N/A');
                    $stock_qty = number_format($item->qty_on_hand ?? 0, 2);
                    $turnover_rate = number_format($item->turnover_rate ?? 0, 2);
                    $days_supply = number_format($item->days_supply ?? 0, 0) . ' days';
                    $status = htmlspecialchars($item->turnover_status ?? 'N/A');
                    $total_value = $currency_symbol . number_format($item->total_value ?? 0, 2);
                    
                    $list .= "<td align=\"left\">{$product_code}</td>";
                    $list .= "<td align=\"left\">{$product_name}</td>";
                    $list .= "<td align=\"left\">{$category}</td>";
                    $list .= "<td align=\"left\">{$location}</td>";
                    $list .= "<td align=\"right\">{$stock_qty}</td>";
                    $list .= "<td align=\"right\">{$turnover_rate}</td>";
                    $list .= "<td align=\"right\">{$days_supply}</td>";
                    $list .= "<td align=\"center\">{$status}</td>";
                    $list .= "<td align=\"right\">{$total_value}</td>";
                    break;
                    
                case 'zero_stock':
                default:
                    $product_code = htmlspecialchars($item->product_code ?? 'N/A');
                    $product_name = htmlspecialchars($item->product_name ?? 'N/A');
                    $category = htmlspecialchars($item->category ?? 'N/A');
                    $location = htmlspecialchars($item->location ?? 'N/A');
                    $stock_qty = number_format($item->qty_on_hand ?? 0, 2);
                    $min_level = number_format($item->reorder_level ?? 0, 2);
                    $uom = htmlspecialchars($item->uom ?? 'N/A');
                    
                    $list .= "<td align=\"left\">{$product_code}</td>";
                    $list .= "<td align=\"left\">{$product_name}</td>";
                    $list .= "<td align=\"left\">{$category}</td>";
                    $list .= "<td align=\"left\">{$location}</td>";
                    $list .= "<td align=\"right\">{$stock_qty}</td>";
                    $list .= "<td align=\"right\">{$min_level}</td>";
                    $list .= "<td align=\"center\">{$uom}</td>";
                    break;
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
                   <td align=\"left\" width=\"25%\">" . $currency_symbol . number_format($total_value, 2) . "</td>";
        $stats .= "</tr><tr>
                   <td align=\"right\" width=\"25%\"><b>AVERAGE VALUE : </b></td>
                   <td align=\"left\" width=\"25%\">" . $currency_symbol . number_format(($total_items > 0 ? $total_value / $total_items : 0), 2) . "</td>
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
                   <td align=\"left\" width=\"25%\">" . $currency_symbol . number_format($total_value, 2) . "</td>";
                   
    } elseif ($report_type === 'movement_summary') {
        $total_qty = 0;
        $total_value = 0;
        foreach ($report_data as $item) {
            if (isset($item->total_qty)) {
                $total_qty += $item->total_qty;
            }
            if (isset($item->total_value)) {
                $total_value += $item->total_value;
            }
        }
        $stats .= "<td align=\"right\" width=\"25%\"><b>TOTAL QUANTITY : </b></td>
                   <td align=\"left\" width=\"25%\">" . number_format($total_qty, 2) . "</td>";
        $stats .= "</tr><tr>
                   <td align=\"right\" width=\"25%\"><b>TOTAL VALUE : </b></td>
                   <td align=\"left\" width=\"25%\">" . $currency_symbol . number_format($total_value, 2) . "</td>
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
