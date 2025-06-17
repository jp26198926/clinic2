<?php

function batch_details($batch)
{
    $details = " <table>
                        <tr>
                            <td align=\"right\" width=\"20%\"><b>TRANSACTION # : </b></td>
                            <td align=\"left\" width=\"30%\">{$batch->transaction_number}</td>
                            <td align=\"right\" width=\"20%\"><b>DATE : </b></td>
                            <td align=\"left\" width=\"30%\">{$batch->transaction_date}</td>
                        </tr>
                        <tr>
                            <td align=\"right\" width=\"20%\"><b>TYPE : </b></td>
                            <td align=\"left\" width=\"30%\">{$batch->transaction_type}</td>
                            <td align=\"right\" width=\"20%\"><b>STATUS : </b></td>
                            <td align=\"left\" width=\"30%\">{$batch->status}</td>
                        </tr>
                        <tr>
                            <td align=\"right\" width=\"20%\"><b>FROM LOCATION : </b></td>
                            <td align=\"left\" width=\"30%\">" . ($batch->from_location ?: 'N/A') . "</td>
                            <td align=\"right\" width=\"20%\"><b>TO LOCATION : </b></td>
                            <td align=\"left\" width=\"30%\">" . ($batch->to_location ?: 'N/A') . "</td>
                        </tr>
                        <tr>
                            <td align=\"right\" width=\"20%\"><b>CREATED BY : </b></td>
                            <td align=\"left\" width=\"30%\">" . ($batch->created_by_name ?: 'Unknown') . "</td>
                            <td align=\"right\" width=\"20%\"><b>CREATED DATE : </b></td>
                            <td align=\"left\" width=\"30%\">" . date('Y-m-d H:i', strtotime($batch->created_at)) . "</td>
                        </tr>";
    
    if ($batch->processed_at) {
        $details .= "<tr>
                        <td align=\"right\" width=\"20%\"><b>PROCESSED DATE : </b></td>
                        <td align=\"left\" width=\"80%\" colspan=\"3\">" . date('Y-m-d H:i', strtotime($batch->processed_at)) . "</td>
                    </tr>";
    }
    
    if ($batch->remarks) {
        $details .= "<tr>
                        <td align=\"right\" width=\"20%\" valign=\"top\"><b>REMARKS : </b></td>
                        <td align=\"left\" width=\"80%\" colspan=\"3\">" . nl2br(htmlspecialchars($batch->remarks)) . "</td>
                    </tr>";
    }
    
    $details .= "</table>";
    return $details;
}

function batch_items_list($items, $batch)
{
    $list = "   <table cellpadding=\"1\" >
                        <tr>
                            <th width=\"5%\" align=\"center\" border=\"1\" ><b> # </b></th>
                            <th width=\"15%\" align=\"center\" border=\"1\" ><b> PRODUCT CODE </b></th>
                            <th width=\"30%\" align=\"center\" border=\"1\" ><b> PRODUCT NAME </b></th>
                            <th width=\"10%\" align=\"center\" border=\"1\" ><b> CATEGORY </b></th>
                            <th width=\"8%\" align=\"center\" border=\"1\" ><b> UOM </b></th>
                            <th width=\"12%\" align=\"center\" border=\"1\" ><b> QUANTITY </b></th>
                            <th width=\"20%\" align=\"center\" border=\"1\" ><b> NOTES </b></th>
                        </tr>";

    $total_qty = 0;

    if (count($items) > 0) {
        $i = 0;
        
        foreach ($items as $item) {
            $i++;
            $qty = floatval($item->qty);
            $total_qty += $qty;
            
            $product_name = htmlspecialchars($item->product_name);
            $notes = htmlspecialchars($item->notes ?: '-');
            $category = htmlspecialchars($item->category ?: '-');
            $uom = htmlspecialchars($item->uom ?: '-');

            $list .= "<tr>";
            $list .= "  <td align=\"center\">{$i}</td>";
            $list .= "  <td align=\"left\">{$item->product_code}</td>";
            $list .= "  <td align=\"left\">{$product_name}</td>";
            $list .= "  <td align=\"center\">{$category}</td>";
            $list .= "  <td align=\"center\">{$uom}</td>";
            $list .= "  <td align=\"right\">" . number_format($qty, 2, '.', ',') . "</td>";
            $list .= "  <td align=\"left\">{$notes}</td>";
            $list .= "</tr>";
        }
        
        // Summary row
        $list .= "<tr><td colspan=\"7\"> <hr /> </td></tr>";
        $list .= "<tr>";
        $list .= "  <td colspan=\"5\" align=\"right\"><b>TOTAL</b></td>";
        $list .= "  <td align=\"right\"><b>" . number_format($total_qty, 2, '.', ',') . "</b></td>";
        $list .= "  <td align=\"center\"><b>" . count($items) . " items</b></td>";
        $list .= "</tr>";
    } else {
        $list .= "<tr>";
        $list .= "  <td align=\"center\" colspan=\"7\" style=\"padding: 20px; font-style: italic;\">No items in this batch transaction</td>";
        $list .= "</tr>";
    }

    $list .= "</table>";
    return $list;
}

function signatory_details($batch)
{
    $details = "<table>
                    <tr>
                        <td width=\"33%\" align=\"center\">
                        </td>
                        <td width=\"33%\" align=\"center\">
                        </td>
                        <td width=\"34%\" align=\"center\">
                        </td>
                    </tr>
                    <tr>
                        <td valign=\"bottom\"><hr /></td>
                        <td></td>
                        <td valign=\"bottom\"><hr /></td>
                    </tr>
                    <tr>
                        <td align=\"center\"><b>PREPARED BY</b></td>
                        <td align=\"center\"></td>
                        <td align=\"center\"><b>APPROVED BY</b></td>
                    </tr>
                </table>";

    return $details;
}

$remarks_msg = nl2br($batch->remarks . '');

$remarks = "<table>
                <tr>
                    <td align=\"left\">REMARKS : {$remarks_msg}</td>
                </tr>
            </table>";

$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);

$pdf->setFontSubsetting(false);

$pdf->myHeader($company_name, $company_address, $company_contact, "Stock Transaction", "", 25);
$pdf->myFooter("Printed: " . date("Y-m-d H:i:s") . " - " . $page_name, "Record ", -15, "");

$pdf->SetAutoPageBreak(true, 15);
$pdf->SetAuthor('noSystems Online');
$pdf->SetTitle('Clinic');
$pdf->SetSubject('Batch Transaction');
$pdf->SetKeywords('DOWNLOAD, PDF, BATCH, INVENTORY');
$pdf->SetTopMargin(25);

$pdf->AddPage('P', 'A4');
$pdf->SetFont('saxmono', 'N', 10);
$pdf->writeHTML(batch_details($batch), true, false, false, false, '');

$pdf->writeHTML("ITEMS LIST", true, false, false, false, '');
$pdf->SetFont('saxmono', 'N', 8);
$pdf->writeHTML(batch_items_list($items, $batch), true, false, false, false, '');

if ($batch->remarks) {
    $pdf->SetFont('saxmono', 'N', 10);
    $pdf->writeHTML($remarks, true, false, false, false, '');
}

$pdf->SetFont('saxmono', 'N', 10);
$pdf->writeHTML(signatory_details($batch), true, false, false, false, '');

$pdf->Output('BATCH-' . $batch->transaction_number . '.pdf', 'I');

?>
