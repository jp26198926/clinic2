<?php

function details($record)
{
	$details = " <table>
						<tr>
                            <td align=\"right\" width=\"15%\"><b>PATIENT : </b></td>
                            <td align=\"left\" width=\"45%\">{$record->patient}</td>
                            <td align=\"right\" width=\"15%\"><b>PMT NO. : </b></td>
                            <td align=\"left\" width=\"25%\">{$record->payment_no}</td>
                        </tr>
						<tr>
                            <td align=\"right\" width=\"15%\"><b>COMPANY : </b></td>
                            <td align=\"left\" width=\"45%\">{$record->company}</td>
							<td align=\"right\" width=\"15%\"><b>PMT DATE : </b></td>
                            <td align=\"left\" width=\"25%\">{$record->date}</td>
                        </tr>
						<tr>
                            <td align=\"right\" width=\"15%\"><b>TRANSACTION : </b></td>
                            <td align=\"left\" width=\"45%\">{$record->trans_type}</td>
							<td align=\"right\" width=\"15%\"><b>FOR INVOICE : </b></td>
                            <td align=\"left\" width=\"25%\">{$record->transaction_no}</td>
                        </tr>
						<tr>
                            <td align=\"right\" width=\"15%\"><b>AMOUNT DUE : </b></td>
                            <td align=\"left\" width=\"45%\">{$record->amount_due}</td>
							<td align=\"right\" width=\"15%\"><b>PMT TYPE : </b></td>
                            <td align=\"left\" width=\"25%\">{$record->payment_type}</td>
						</tr>
						<tr>
                            <td align=\"right\" width=\"15%\"><b>AMOUNT PAID : </b></td>
                            <td align=\"left\" width=\"45%\">{$record->amount}</td>
						</tr>
                    </table>";
	return $details;
}

function summary_list($items, $record, $total_paid=0)
{
	$list = "   <table cellpadding=\"1\" >
						<tr>
							<th width=\"5%\" align=\"center\" border=\"1\" ><b> # </b></th>
							<th width=\"10%\" align=\"center\" border=\"1\" ><b> QTY </b></th>
							<th width=\"10%\" align=\"center\" border=\"1\" ><b> UOM </b></th>
							<th width=\"25%\" align=\"center\" border=\"1\" ><b> ITEM </b></th>
							<th width=\"10%\" align=\"center\" border=\"1\" ><b> PRICE </b></th>
							<th width=\"10%\" align=\"center\" border=\"1\" ><b> AMOUNT </b></th>
							<th width=\"10%\" align=\"center\" border=\"1\" ><b> COMM </b></th>
							<th width=\"10%\" align=\"center\" border=\"1\" ><b> INSUR </b></th>
							<th width=\"10%\" align=\"center\" border=\"1\" ><b> TOTAL </b></th>
						</tr>";

	$subtotal_amount = 0;
	$subtotal_commission = 0;
	$subtotal_insurance = 0;
	$subtotal_total = 0;


	if (count($items) > 0) {
		$i = 0;
		foreach ($items as $key => $item) {
			$i++;

			$item_description = nl2br($item->product_name . '');
			$qty = floatval($item->qty);

			$subtotal_amount += $item->amount;
            $subtotal_commission += $item->commission_amount;
            $subtotal_insurance += $item->insurance_amount;
            $subtotal_total += $item->total;

			$list .= "<tr>";
			$list .= "	<td align=\"center\">{$i}</td>";
			$list .= "	<td align=\"center\">{$qty}</td>";
			$list .= "	<td align=\"center\">{$item->uom_code}</td>";
			$list .= "	<td align=\"center\">{$item_description}</td>";
			$list .= "	<td align=\"right\">{$item->price}</td>";
			$list .= "	<td align=\"right\">{$item->amount}</td>";
			$list .= "	<td align=\"right\">{$item->commission_amount}</td>";
			$list .= "	<td align=\"right\">{$item->insurance_amount}</td>";
			$list .= "	<td align=\"right\">{$item->total}</td>";
			$list .= "</tr>";
		}

		// show totals
		$previous_payment = $subtotal_total - $record->amount_due;
		$balance = $record->amount_due - $record->amount;

		$list .= "<tr><td colspan=\"9\"> <hr /> </td></tr>";
		$list .= "<tr>";
        $list .= "	<td colspan=\"5\" align=\"right\"><b>Sub-Total</b></td>";
        $list .= "	<td align=\"right\">" . number_format($subtotal_amount, 2, '.', ',') . "</td>";
        $list .= "  <td align=\"right\">" . number_format($subtotal_commission, 2, '.', ',') . "</td>";
        $list .= "  <td align=\"right\">" . number_format($subtotal_insurance, 2, '.', ',') . "</td>";
        $list .= "  <td align=\"right\">" . number_format($subtotal_total, 2, '.', ',') . "</td>";
        $list .= "</tr>";
		$list .= "<tr>";
        $list .= "	<td colspan=\"5\" align=\"right\"><b>PREVIOUS PAYMENT</b></td>";
        $list .= "  <td colspan=\"4\" align=\"right\">" . number_format($previous_payment, 2, '.', ',') . "</td>";
        $list .= "</tr>";
        $list .= "<tr>";
        $list .= "	<td colspan=\"5\" align=\"right\"><b>TOTAL AMOUNT DUE</b></td>";
        $list .= "	<td colspan=\"4\" align=\"right\">" . number_format($record->amount_due, 2, '.', ',') . "</td>";
        $list .= "</tr>";
		$list .= "<tr>";
        $list .= "	<td colspan=\"5\" align=\"right\"><b>AMOUNT PAID</b></td>";
        $list .= "	<td colspan=\"4\" align=\"right\">" . number_format($record->amount, 2, '.', ',') . "</td>";
        $list .= "</tr>";
		$list .= "<tr>";
        $list .= "	<td colspan=\"5\" align=\"right\"></td>";
        $list .= "	<td colspan=\"4\" align=\"right\"><hr/></td>";
        $list .= "</tr>";
		$list .= "<tr>";
        $list .= "	<td colspan=\"5\" align=\"right\"><b>REMAINING BALANCE</b></td>";
        $list .= "	<td colspan=\"4\" align=\"right\">" . number_format($balance, 2, '.', ',') . "</td>";
        $list .= "</tr>";

	} else {
		$list .= "<tr><td colspan=\"7\" align=\"center\">No Record</td></tr>";
	}

	$list .= "  </table>";

	return $list;
}

function signatory_details($record)
{
	$requested = strtoupper($record->created);

	$details = "<table >
					<tr>
						<td >Requested By: </td>
						<td></td>
						<td>Dept Approved: </td>
						<td></td>
						<td>GM Approved: </td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td></td>
						<td>&nbsp;</td>
						<td></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td align=\"center\">{$requested}</td>
						<td></td>
						<td align=\"center\"></td>
						<td></td>
						<td align=\"center\"></td>
					</tr>
					<tr>
						<td valign=\"bottom\"><hr /></td>
						<td></td>
						<td valign=\"bottom\"><hr /></td>
						<td></td>
						<td valign=\"bottom\"><hr /></td>
					</tr>

				</table>";

	return $details;
}

$remarks_msg = nl2br($record->remarks . '');

$remarks = "<table>
				<tr>
                    <td align=\"left\">REMARKS : {$remarks_msg}</td>
                </tr>
			</table>";

$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->myHeader($company_name, $company_address, $company_contact, "e-Receipt", "", 25);
$pdf->myFooter("Printed: " . date("Y-m-d H:i:s") . " - " . strtoupper($record->created), "Record ", -15, "");

$pdf->SetAutoPageBreak(true, 15);
$pdf->SetAuthor('noSystems Online');
$pdf->SetTitle('Clinic');
$pdf->SetSubject('System');
$pdf->SetKeywords('DOWNLOAD, PDF');
$pdf->SetTopMargin(25);

$pdf->AddPage('P', 'A4');
$pdf->SetFont('saxmono', 'N', 10);
$pdf->writeHTML(details($record), true, false, false, false, '');

$pdf->writeHTML("SUMMARY", true, false, false, false, '');
$pdf->SetFont('saxmono', 'N', 8);
$pdf->writeHTML(summary_list($items, $record, ($total_paid - $record->amount)), true, false, false, false, '');

// $pdf->SetFont('saxmono', 'N', 10);
// $pdf->writeHTML($remarks, true, false, false, false, '');
//$pdf->writeHTML(signatory_details($record), true, false, false, false, '');

$pdf->Output($record->payment_no . '.pdf', 'I');
