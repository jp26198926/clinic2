<?php

function batch_summary($filters)
{
    $details = "<table>
                    <tr>
                        <td align=\"right\" width=\"20%\"><b>REPORT TYPE : </b></td>
                        <td align=\"left\" width=\"30%\">Batch Transactions Report</td>
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
    
    if ($filters['transaction_type']) {
        $details .= "<tr>
                        <td align=\"right\" width=\"20%\"><b>TRANSACTION TYPE : </b></td>
                        <td align=\"left\" width=\"30%\">{$filters['transaction_type']}</td>
                        <td align=\"right\" width=\"20%\"><b>STATUS FILTER : </b></td>
                        <td align=\"left\" width=\"30%\">" . ($filters['status'] ?: 'All Status') . "</td>
                    </tr>";
    } else {
        $details .= "<tr>
                        <td align=\"right\" width=\"20%\"><b>TRANSACTION TYPE : </b></td>
                        <td align=\"left\" width=\"30%\">All Types</td>
                        <td align=\"right\" width=\"20%\"><b>STATUS FILTER : </b></td>
                        <td align=\"left\" width=\"30%\">" . ($filters['status'] ?: 'All Status') . "</td>
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
        
        $details .= "<tr>
                        <td align=\"right\" width=\"20%\"><b>DATE RANGE : </b></td>
                        <td align=\"left\" width=\"80%\" colspan=\"3\">{$date_range}</td>
                    </tr>";
    }
    
    $details .= "</table>";
    return $details;
}

function batch_transactions_list($transactions)
{
    $list = "<table cellpadding=\"1\">
                <tr>
                    <th width=\"4%\" align=\"center\" border=\"1\"><b>#</b></th>
                    <th width=\"12%\" align=\"center\" border=\"1\"><b>TRANSACTION #</b></th>
                    <th width=\"10%\" align=\"center\" border=\"1\"><b>DATE</b></th>
                    <th width=\"8%\" align=\"center\" border=\"1\"><b>TYPE</b></th>
                    <th width=\"15%\" align=\"center\" border=\"1\"><b>FROM LOCATION</b></th>
                    <th width=\"15%\" align=\"center\" border=\"1\"><b>TO LOCATION</b></th>
                    <th width=\"8%\" align=\"center\" border=\"1\"><b>ITEMS</b></th>
                    <th width=\"10%\" align=\"center\" border=\"1\"><b>TOTAL QTY</b></th>
                    <th width=\"8%\" align=\"center\" border=\"1\"><b>STATUS</b></th>
                    <th width=\"10%\" align=\"center\" border=\"1\"><b>CREATED</b></th>
                </tr>";

    $total_transactions = 0;
    $total_items = 0;
    $total_qty = 0;
    $by_type = array();
    $by_status = array();

    if (count($transactions) > 0) {
        $row_number = 1; // Initialize row counter
        foreach ($transactions as $transaction) {
            $total_transactions++;
            $items_count = intval($transaction->total_items ?: 0);
            $qty = floatval($transaction->total_qty ?: 0);
            
            $total_items += $items_count;
            $total_qty += $qty;
            
            // Count by type and status
            $type = $transaction->transaction_type;
            $status = $transaction->status;
            
            if (!isset($by_type[$type])) $by_type[$type] = 0;
            if (!isset($by_status[$status])) $by_status[$status] = 0;
            
            $by_type[$type]++;
            $by_status[$status]++;
            
            $transaction_number = htmlspecialchars($transaction->transaction_number);
            $from_location = htmlspecialchars($transaction->from_location ?: '-');
            $to_location = htmlspecialchars($transaction->to_location ?: '-');
            
            // Format dates
            $transaction_date = date('Y-m-d', strtotime($transaction->transaction_date));
            $created_date = date('Y-m-d', strtotime($transaction->created_at));

            $list .= "<tr>";
            $list .= "  <td align=\"center\">{$row_number}</td>";
            $list .= "  <td align=\"left\">{$transaction_number}</td>";
            $list .= "  <td align=\"center\">{$transaction_date}</td>";
            $list .= "  <td align=\"center\">{$type}</td>";
            $list .= "  <td align=\"left\">{$from_location}</td>";
            $list .= "  <td align=\"left\">{$to_location}</td>";
            $list .= "  <td align=\"right\">" . number_format($items_count, 0) . "</td>";
            $list .= "  <td align=\"right\">" . number_format($qty, 2, '.', ',') . "</td>";
            $list .= "  <td align=\"center\">{$status}</td>";
            $list .= "  <td align=\"center\">{$created_date}</td>";
            $list .= "</tr>";
            
            $row_number++; // Increment row counter
        }
        
        // Summary row
        $list .= "<tr><td colspan=\"10\"> <hr /> </td></tr>";
        $list .= "<tr>";
        $list .= "  <td colspan=\"6\" align=\"right\"><b>TOTALS</b></td>";
        $list .= "  <td align=\"right\"><b>" . number_format($total_items, 0) . "</b></td>";
        $list .= "  <td align=\"right\"><b>" . number_format($total_qty, 2, '.', ',') . "</b></td>";
        $list .= "  <td colspan=\"2\" align=\"center\"><b>{$total_transactions} transactions</b></td>";
        $list .= "</tr>";
    } else {
        $list .= "<tr>";
        $list .= "  <td align=\"center\" colspan=\"10\" style=\"padding: 20px; font-style: italic;\">No batch transactions found</td>";
        $list .= "</tr>";
    }

    $list .= "</table>";
    return $list;
}

function batch_summary_statistics($transactions)
{
    $total_transactions = count($transactions);
    $total_items = 0;
    $total_qty = 0;
    $by_type = array();
    $by_status = array();
    $completed_transactions = 0;
    $cancelled_transactions = 0;
    
    foreach ($transactions as $transaction) {
        $items_count = intval($transaction->total_items ?: 0);
        $qty = floatval($transaction->total_qty ?: 0);
        
        $total_items += $items_count;
        $total_qty += $qty;
        
        // Count by type and status
        $type = $transaction->transaction_type;
        $status = $transaction->status;
        
        if (!isset($by_type[$type])) $by_type[$type] = 0;
        if (!isset($by_status[$status])) $by_status[$status] = 0;
        
        $by_type[$type]++;
        $by_status[$status]++;
        
        if ($status === 'COMPLETED') $completed_transactions++;
        if ($status === 'CANCELLED') $cancelled_transactions++;
    }
    
    $stats = "<table>
                <tr>
                    <td align=\"right\" width=\"25%\"><b>TOTAL TRANSACTIONS : </b></td>
                    <td align=\"left\" width=\"25%\">" . number_format($total_transactions, 0) . "</td>
                    <td align=\"right\" width=\"25%\"><b>TOTAL ITEMS : </b></td>
                    <td align=\"left\" width=\"25%\">" . number_format($total_items, 0) . "</td>
                </tr>
                <tr>
                    <td align=\"right\" width=\"25%\"><b>TOTAL QUANTITY : </b></td>
                    <td align=\"left\" width=\"25%\">" . number_format($total_qty, 2, '.', ',') . "</td>
                    <td align=\"right\" width=\"25%\"><b>COMPLETED : </b></td>
                    <td align=\"left\" width=\"25%\">{$completed_transactions}</td>
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
    
    // Show cancelled if any
    if ($cancelled_transactions > 0) {
        $stats .= "<tr>
                    <td align=\"right\" width=\"25%\"><b>CANCELLED : </b></td>
                    <td align=\"left\" width=\"75%\" colspan=\"3\">{$cancelled_transactions}</td>
                </tr>";
    }
    
    $stats .= "</table>";
    
    return $stats;
}

// PDF Generation
$pdf = new Pdf('L', 'mm', 'A4', true, 'UTF-8', false); // Landscape orientation for better table fit

$pdf->setFontSubsetting(false);

$pdf->myHeader($company_name, $company_address, $company_contact, "Batch Transactions Report", "", 25);
$pdf->myFooter("Printed: " . date("Y-m-d H:i:s") . " - " . $page_name, "Record ", -15, "");

$pdf->SetAutoPageBreak(true, 15);
$pdf->SetAuthor('noSystems Online');
$pdf->SetTitle('Clinic - Batch Transactions Report');
$pdf->SetSubject('Inventory Batch Transactions');
$pdf->SetKeywords('DOWNLOAD, PDF, INVENTORY, BATCH, TRANSACTIONS');
$pdf->SetTopMargin(25);

$pdf->AddPage('L', 'A4'); // Landscape page
$pdf->SetFont('saxmono', 'N', 10);
$pdf->writeHTML(batch_summary($filters), true, false, false, false, '');

$pdf->writeHTML("TRANSACTION SUMMARY STATISTICS", true, false, false, false, '');
$pdf->SetFont('saxmono', 'N', 9);
$pdf->writeHTML(batch_summary_statistics($batch_data), true, false, false, false, '');

$pdf->writeHTML("DETAILED BATCH TRANSACTIONS", true, false, false, false, '');
$pdf->SetFont('saxmono', 'N', 7); // Smaller font for detailed table
$pdf->writeHTML(batch_transactions_list($batch_data), true, false, false, false, '');

// Generate filename with current date and filters
$filename = 'BATCH-TRANSACTIONS-' . date('Y-m-d');
if ($filters['transaction_type']) {
    $filename .= '-' . str_replace(' ', '-', strtoupper($filters['transaction_type']));
}
if ($filters['location_name'] && $filters['location_name'] !== 'All Locations') {
    $filename .= '-' . str_replace(' ', '-', strtoupper($filters['location_name']));
}
$filename .= '.pdf';

$pdf->Output($filename, 'I');

?>
