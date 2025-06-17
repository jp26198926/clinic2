<?php

function inventory_summary($filters)
{
    $details = "<table>
                    <tr>
                        <td align=\"right\" width=\"20%\"><b>REPORT TYPE : </b></td>
                        <td align=\"left\" width=\"30%\">Stock Levels Report</td>
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
    
    $details .= "</table>";
    return $details;
}

function stock_items_list($items, $currency_symbol)
{
    $list = "<table cellpadding=\"1\">
                <tr>
                    <th width=\"8%\" align=\"center\" border=\"1\"><b>CODE</b></th>
                    <th width=\"25%\" align=\"center\" border=\"1\"><b>PRODUCT NAME</b></th>
                    <th width=\"12%\" align=\"center\" border=\"1\"><b>CATEGORY</b></th>
                    <th width=\"6%\" align=\"center\" border=\"1\"><b>UOM</b></th>
                    <th width=\"12%\" align=\"center\" border=\"1\"><b>LOCATION</b></th>
                    <th width=\"8%\" align=\"center\" border=\"1\"><b>ON HAND</b></th>
                    <th width=\"8%\" align=\"center\" border=\"1\"><b>RESERVED</b></th>
                    <th width=\"8%\" align=\"center\" border=\"1\"><b>AVAILABLE</b></th>
                    <th width=\"13%\" align=\"center\" border=\"1\"><b>EXPIRATION</b></th>
                </tr>";

    $total_items = 0;
    $total_on_hand = 0;
    $total_reserved = 0;
    $total_available = 0;

    if (count($items) > 0) {
        foreach ($items as $item) {
            $total_items++;
            $on_hand = floatval($item->qty_on_hand);
            $reserved = floatval($item->qty_reserved);
            $available = floatval($item->qty_available);
            
            $total_on_hand += $on_hand;
            $total_reserved += $reserved;
            $total_available += $available;
            
            $product_name = htmlspecialchars($item->product_name);
            $category = htmlspecialchars($item->category ?: '-');
            $uom = htmlspecialchars($item->uom ?: '-');
            $location = htmlspecialchars($item->location ?: '-');
            
            // Format expiration date and status
            $expiration_display = 'No expiry';
            if ($item->expiration_date) {
                $expiration_display = date('Y-m-d', strtotime($item->expiration_date));
                if ($item->expiration_status === 'expired') {
                    $expiration_display .= ' (EXPIRED)';
                } elseif ($item->expiration_status === 'expiring_soon') {
                    $expiration_display .= ' (EXPIRING)';
                }
            }

            $list .= "<tr>";
            $list .= "  <td align=\"left\">{$item->product_code}</td>";
            $list .= "  <td align=\"left\">{$product_name}</td>";
            $list .= "  <td align=\"center\">{$category}</td>";
            $list .= "  <td align=\"center\">{$uom}</td>";
            $list .= "  <td align=\"left\">{$location}</td>";
            $list .= "  <td align=\"right\">" . number_format($on_hand, 2, '.', ',') . "</td>";
            $list .= "  <td align=\"right\">" . number_format($reserved, 2, '.', ',') . "</td>";
            $list .= "  <td align=\"right\">" . number_format($available, 2, '.', ',') . "</td>";
            $list .= "  <td align=\"center\">{$expiration_display}</td>";
            $list .= "</tr>";
        }
        
        // Summary row
        $list .= "<tr><td colspan=\"9\"> <hr /> </td></tr>";
        $list .= "<tr>";
        $list .= "  <td colspan=\"5\" align=\"right\"><b>TOTALS</b></td>";
        $list .= "  <td align=\"right\"><b>" . number_format($total_on_hand, 2, '.', ',') . "</b></td>";
        $list .= "  <td align=\"right\"><b>" . number_format($total_reserved, 2, '.', ',') . "</b></td>";
        $list .= "  <td align=\"right\"><b>" . number_format($total_available, 2, '.', ',') . "</b></td>";
        $list .= "  <td align=\"center\"><b>{$total_items} items</b></td>";
        $list .= "</tr>";
    } else {
        $list .= "<tr>";
        $list .= "  <td align=\"center\" colspan=\"9\" style=\"padding: 20px; font-style: italic;\">No stock data found</td>";
        $list .= "</tr>";
    }

    $list .= "</table>";
    return $list;
}

function stock_summary_statistics($items, $currency_symbol)
{
    $total_value = 0;
    $expired_items = 0;
    $expiring_items = 0;
    $zero_stock_items = 0;
    $low_stock_items = 0;
    
    foreach ($items as $item) {
        $unit_cost = floatval($item->unit_cost);
        $on_hand = floatval($item->qty_on_hand);
        
        $total_value += ($on_hand * $unit_cost);
        
        if ($on_hand == 0) {
            $zero_stock_items++;
        } elseif ($on_hand <= 10) { // Assuming low stock threshold of 10
            $low_stock_items++;
        }
        
        if ($item->expiration_status === 'expired') {
            $expired_items++;
        } elseif ($item->expiration_status === 'expiring_soon') {
            $expiring_items++;
        }
    }
    
    $stats = "<table>
                <tr>
                    <td align=\"right\" width=\"25%\"><b>TOTAL INVENTORY VALUE : </b></td>
                    <td align=\"left\" width=\"25%\">{$currency_symbol}" . number_format($total_value, 2, '.', ',') . "</td>
                    <td align=\"right\" width=\"25%\"><b>TOTAL ITEMS : </b></td>
                    <td align=\"left\" width=\"25%\">" . count($items) . "</td>
                </tr>
                <tr>
                    <td align=\"right\" width=\"25%\"><b>EXPIRED ITEMS : </b></td>
                    <td align=\"left\" width=\"25%\">{$expired_items}</td>
                    <td align=\"right\" width=\"25%\"><b>EXPIRING SOON : </b></td>
                    <td align=\"left\" width=\"25%\">{$expiring_items}</td>
                </tr>
                <tr>
                    <td align=\"right\" width=\"25%\"><b>ZERO STOCK ITEMS : </b></td>
                    <td align=\"left\" width=\"25%\">{$zero_stock_items}</td>
                    <td align=\"right\" width=\"25%\"><b>LOW STOCK ITEMS : </b></td>
                    <td align=\"left\" width=\"25%\">{$low_stock_items}</td>
                </tr>
            </table>";
    
    return $stats;
}

// PDF Generation
$pdf = new Pdf('L', 'mm', 'A4', true, 'UTF-8', false); // Landscape orientation for better table fit

$pdf->setFontSubsetting(false);

$pdf->myHeader($company_name, $company_address, $company_contact, "Stock Level Report", "", 25);
$pdf->myFooter("Printed: " . date("Y-m-d H:i:s") . " - " . $page_name, "Record ", -15, "");

$pdf->SetAutoPageBreak(true, 15);
$pdf->SetAuthor('noSystems Online');
$pdf->SetTitle('Clinic - Stock Levels Report');
$pdf->SetSubject('Inventory Stock Levels');
$pdf->SetKeywords('DOWNLOAD, PDF, INVENTORY, STOCK, LEVELS');
$pdf->SetTopMargin(25);

$pdf->AddPage('L', 'A4'); // Landscape page
$pdf->SetFont('saxmono', 'N', 10);
$pdf->writeHTML(inventory_summary($filters), true, false, false, false, '');

$pdf->writeHTML("STOCK SUMMARY STATISTICS", true, false, false, false, '');
$pdf->SetFont('saxmono', 'N', 9);
$pdf->writeHTML(stock_summary_statistics($stock_data, $currency_symbol), true, false, false, false, '');

$pdf->writeHTML("DETAILED STOCK LEVELS", true, false, false, false, '');
$pdf->SetFont('saxmono', 'N', 7); // Smaller font for detailed table
$pdf->writeHTML(stock_items_list($stock_data, $currency_symbol), true, false, false, false, '');

// Generate filename with current date and filters
$filename = 'STOCK-LEVELS-' . date('Y-m-d');
if ($filters['location_name'] && $filters['location_name'] !== 'All Locations') {
    $filename .= '-' . str_replace(' ', '-', strtoupper($filters['location_name']));
}
$filename .= '.pdf';

$pdf->Output($filename, 'I');

?>
