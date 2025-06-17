<?php

function movements_summary($filters)
{
    $details = "<table>
                    <tr>
                        <td align=\"right\" width=\"20%\"><b>REPORT TYPE : </b></td>
                        <td align=\"left\" width=\"30%\">Stock Movements Report</td>
                        <td align=\"right\" width=\"20%\"><b>GENERATED DATE : </b></td>
                        <td align=\"left\" width=\"30%\">" . date('Y-m-d H:i:s') . "</td>
                    </tr>";
    
    if ($filters['location_name']) {
        $details .= "<tr>
                        <td align=\"right\" width=\"20%\"><b>LOCATION : </b></td>
                        <td align=\"left\" width=\"30%\">{$filters['location_name']}</td>
                        <td align=\"right\" width=\"20%\"><b>SEARCH FILTER : </b></td>
                        <td align=\"left\" width=\"30%\">" . ($filters['search_text'] ?: 'None') . "</td>
                    </tr>";
    } else {
        $details .= "<tr>
                        <td align=\"right\" width=\"20%\"><b>LOCATION : </b></td>
                        <td align=\"left\" width=\"30%\">All Locations</td>
                        <td align=\"right\" width=\"20%\"><b>SEARCH FILTER : </b></td>
                        <td align=\"left\" width=\"30%\">" . ($filters['search_text'] ?: 'None') . "</td>
                    </tr>";
    }
    
    if ($filters['movement_type']) {
        $details .= "<tr>
                        <td align=\"right\" width=\"20%\"><b>MOVEMENT TYPE : </b></td>
                        <td align=\"left\" width=\"30%\">{$filters['movement_type']}</td>
                        <td align=\"right\" width=\"20%\"><b>DATE FROM : </b></td>
                        <td align=\"left\" width=\"30%\">" . ($filters['date_from'] ?: 'No limit') . "</td>
                    </tr>";
    } else {
        $details .= "<tr>
                        <td align=\"right\" width=\"20%\"><b>MOVEMENT TYPE : </b></td>
                        <td align=\"left\" width=\"30%\">All Types</td>
                        <td align=\"right\" width=\"20%\"><b>DATE FROM : </b></td>
                        <td align=\"left\" width=\"30%\">" . ($filters['date_from'] ?: 'No limit') . "</td>
                    </tr>";
    }
    
    if ($filters['date_to']) {
        $details .= "<tr>
                        <td align=\"right\" width=\"20%\"><b>DATE TO : </b></td>
                        <td align=\"left\" width=\"80%\" colspan=\"3\">{$filters['date_to']}</td>
                    </tr>";
    }
    
    $details .= "</table>";
    return $details;
}

function movements_list($movements)
{
    $list = "<table cellpadding=\"1\">
                <tr>
                    <th width=\"3%\" align=\"center\" border=\"1\"><b>#</b></th>
                    <th width=\"7%\" align=\"center\" border=\"1\"><b>DATE</b></th>
                    <th width=\"8%\" align=\"center\" border=\"1\"><b>PRODUCT CODE</b></th>
                    <th width=\"18%\" align=\"center\" border=\"1\"><b>PRODUCT NAME</b></th>
                    <th width=\"8%\" align=\"center\" border=\"1\"><b>CATEGORY</b></th>
                    <th width=\"10%\" align=\"center\" border=\"1\"><b>LOCATION</b></th>
                    <th width=\"8%\" align=\"center\" border=\"1\"><b>TYPE</b></th>
                    <th width=\"6%\" align=\"center\" border=\"1\"><b>QTY</b></th>
                    <th width=\"7%\" align=\"center\" border=\"1\"><b>UNIT COST</b></th>
                    <th width=\"8%\" align=\"center\" border=\"1\"><b>REFERENCE</b></th>
                    <th width=\"10%\" align=\"center\" border=\"1\"><b>TRANSFER</b></th>
                    <th width=\"7%\" align=\"center\" border=\"1\"><b>NOTES</b></th>
                </tr>";

    $total_movements = 0;
    $total_qty = 0;
    $by_type = array();

    if (count($movements) > 0) {
        $row_number = 1; // Initialize row counter
        foreach ($movements as $movement) {
            $total_movements++;
            $qty = floatval($movement->qty ?: 0);
            $total_qty += $qty;
            
            // Count by movement type
            $type = $movement->movement_type;
            if (!isset($by_type[$type])) $by_type[$type] = 0;
            $by_type[$type]++;
            
            $product_name = htmlspecialchars($movement->product_name);
            $category = htmlspecialchars($movement->category ?: '-');
            $location = htmlspecialchars($movement->location ?: '-');
            $unit_cost = $movement->unit_cost ? number_format(floatval($movement->unit_cost), 2, '.', ',') : '-';
            $reference = htmlspecialchars(($movement->reference_type ?: '') . ($movement->reference_id ? ' #' . $movement->reference_id : ''));
            $notes = htmlspecialchars(substr($movement->notes ?: '', 0, 30) . (strlen($movement->notes ?: '') > 30 ? '...' : ''));
            
            // Format date
            $movement_date = date('Y-m-d', strtotime($movement->date ?: $movement->created_at));
            
            // Transfer details
            $transfer_details = '';
            if ($movement->movement_type === 'TRANSFER') {
                if ($movement->transfer_from_location && $movement->transfer_to_location) {
                    $transfer_details = htmlspecialchars($movement->transfer_from_location . ' → ' . $movement->transfer_to_location);
                }
            }
            if (!$transfer_details) $transfer_details = '-';

            $list .= "<tr>";
            $list .= "  <td align=\"center\">{$row_number}</td>";
            $list .= "  <td align=\"center\">{$movement_date}</td>";
            $list .= "  <td align=\"left\">{$movement->product_code}</td>";
            $list .= "  <td align=\"left\">{$product_name}</td>";
            $list .= "  <td align=\"center\">{$category}</td>";
            $list .= "  <td align=\"left\">{$location}</td>";
            $list .= "  <td align=\"center\">{$type}</td>";
            $list .= "  <td align=\"right\">" . number_format($qty, 2, '.', ',') . "</td>";
            $list .= "  <td align=\"right\">{$unit_cost}</td>";
            $list .= "  <td align=\"center\">{$reference}</td>";
            $list .= "  <td align=\"left\">{$transfer_details}</td>";
            $list .= "  <td align=\"left\">{$notes}</td>";
            $list .= "</tr>";
            
            $row_number++; // Increment row counter
        }
        
        // Summary row
        $list .= "<tr><td colspan=\"12\"> <hr /> </td></tr>";
        $list .= "<tr>";
        $list .= "  <td colspan=\"7\" align=\"right\"><b>TOTALS</b></td>";
        $list .= "  <td align=\"right\"><b>" . number_format($total_qty, 2, '.', ',') . "</b></td>";
        $list .= "  <td colspan=\"4\" align=\"center\"><b>{$total_movements} movements</b></td>";
        $list .= "</tr>";
    } else {
        $list .= "<tr>";
        $list .= "  <td align=\"center\" colspan=\"12\" style=\"padding: 20px; font-style: italic;\">No movement data found</td>";
        $list .= "</tr>";
    }

    $list .= "</table>";
    return $list;
}

function movements_summary_statistics($movements)
{
    $total_movements = count($movements);
    $total_qty = 0;
    $by_type = array();
    $unique_products = array();
    $unique_locations = array();
    
    foreach ($movements as $movement) {
        $qty = floatval($movement->qty ?: 0);
        $total_qty += $qty;
        
        // Count by movement type
        $type = $movement->movement_type;
        if (!isset($by_type[$type])) $by_type[$type] = 0;
        $by_type[$type]++;
        
        // Track unique products and locations
        if ($movement->product_code) {
            $unique_products[$movement->product_code] = true;
        }
        if ($movement->location) {
            $unique_locations[$movement->location] = true;
        }
    }
    
    $stats = "<table>
                <tr>
                    <td align=\"right\" width=\"25%\"><b>TOTAL MOVEMENTS : </b></td>
                    <td align=\"left\" width=\"25%\">" . number_format($total_movements, 0) . "</td>
                    <td align=\"right\" width=\"25%\"><b>TOTAL QUANTITY : </b></td>
                    <td align=\"left\" width=\"25%\">" . number_format($total_qty, 2, '.', ',') . "</td>
                </tr>
                <tr>
                    <td align=\"right\" width=\"25%\"><b>UNIQUE PRODUCTS : </b></td>
                    <td align=\"left\" width=\"25%\">" . count($unique_products) . "</td>
                    <td align=\"right\" width=\"25%\"><b>LOCATIONS : </b></td>
                    <td align=\"left\" width=\"25%\">" . count($unique_locations) . "</td>
                </tr>";
    
    // Show breakdown by type
    if (count($by_type) > 0) {
        $type_breakdown = '';
        foreach ($by_type as $type => $count) {
            $type_breakdown .= "{$type}: {$count}  ";
        }
        $stats .= "<tr>
                    <td align=\"right\" width=\"25%\"><b>BY TYPE : </b></td>
                    <td align=\"left\" width=\"75%\" colspan=\"3\">{$type_breakdown}</td>
                </tr>";
    }
    
    $stats .= "</table>";
    
    return $stats;
}

// PDF Generation
$pdf = new Pdf('L', 'mm', 'A4', true, 'UTF-8', false); // Landscape orientation for better table fit

$pdf->setFontSubsetting(false);

$pdf->myHeader($company_name, $company_address, $company_contact, "Stock Movements Report", "", 25);
$pdf->myFooter("Printed: " . date("Y-m-d H:i:s") . " - " . $page_name, "Record ", -15, "");

$pdf->SetAutoPageBreak(true, 15);
$pdf->SetAuthor('noSystems Online');
$pdf->SetTitle('Clinic - Stock Movements Report');
$pdf->SetSubject('Inventory Stock Movements');
$pdf->SetKeywords('DOWNLOAD, PDF, INVENTORY, MOVEMENTS, STOCK');
$pdf->SetTopMargin(25);

$pdf->AddPage('L', 'A4'); // Landscape page
$pdf->SetFont('saxmono', 'N', 10);
$pdf->writeHTML(movements_summary($filters), true, false, false, false, '');

$pdf->writeHTML("MOVEMENT SUMMARY STATISTICS", true, false, false, false, '');
$pdf->SetFont('saxmono', 'N', 9);
$pdf->writeHTML(movements_summary_statistics($movement_data), true, false, false, false, '');

$pdf->writeHTML("DETAILED STOCK MOVEMENTS", true, false, false, false, '');
$pdf->SetFont('saxmono', 'N', 6); // Smaller font for detailed table
$pdf->writeHTML(movements_list($movement_data), true, false, false, false, '');

// Generate filename with current date and filters
$filename = 'STOCK-MOVEMENTS-' . date('Y-m-d');
if ($filters['movement_type']) {
    $filename .= '-' . str_replace(' ', '-', strtoupper($filters['movement_type']));
}
if ($filters['location_name'] && $filters['location_name'] !== 'All Locations') {
    $filename .= '-' . str_replace(' ', '-', strtoupper($filters['location_name']));
}
$filename .= '.pdf';

$pdf->Output($filename, 'I');

?>
