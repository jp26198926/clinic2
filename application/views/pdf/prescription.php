<?php

function details($record)
{
	$details = " <table>
						<tr>
                            <td align=\"right\" width=\"15%\"><b>PATIENT : </b></td>
                            <td align=\"left\" width=\"45%\">{$record->patient}</td>
                            <td align=\"right\" width=\"15%\"><b>TRANS # : </b></td>
                            <td align=\"left\" width=\"25%\">{$record->transaction_no}</td>
                        </tr>
						<tr>
                            <td align=\"right\" width=\"15%\"><b>COMPANY : </b></td>
                            <td align=\"left\" width=\"45%\">{$record->company}</td>
							<td align=\"right\" width=\"15%\"><b>DATE : </b></td>
                            <td align=\"left\" width=\"25%\">{$record->date}</td>
                        </tr>

                    </table>";
	return $details;
}

function summary_list($items)
{
	$list = "   <table cellpadding=\"1\" >
						<tr>
							<th width=\"5%\" align=\"center\" border=\"1\" ><b> # </b></th>
							<th width=\"35%\" align=\"center\" border=\"1\" ><b> PRODUCT </b></th>
							<th width=\"10%\" align=\"center\" border=\"1\" ><b> QTY </b></th>
							<th width=\"50%\" align=\"center\" border=\"1\" ><b> INSTRUCTION </b></th>
						</tr>";

	if (count($items) > 0) {
		$i = 0;
		foreach ($items as $key => $item) {
			$i++;

			$instruction = nl2br($item->instruction . '');
			$qty = floatval($item->qty);
			$product = $item->product_code . " - " . $item->product_name . " [" . $item->uom_code . "]";



			$list .= "<tr>";
			$list .= "	<td align=\"center\">{$i}</td>";
			$list .= "	<td align=\"center\">{$product}</td>";
			$list .= "	<td align=\"center\">{$qty}</td>";
			$list .= "	<td>{$instruction}</td>";
			$list .= "</tr>";
		}

	} else {
		$list .= "<tr><td colspan=\"4\" align=\"center\">No Record</td></tr>";
	}

	$list .= "  </table>";

	return $list;
}

function signatory_details($record)
{
	$requested = strtoupper($record->created);

	$details = "<table >
					<tr>
						<td >Prepared By: </td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
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
						<td valign=\"bottom\"></td>
						<td></td>
						<td valign=\"bottom\"></td>
					</tr>

				</table>";

	return $details;
}




$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->myHeader($company_name, $company_address, $company_contact, "PRISCRIPTION", "", 25);
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

$pdf->SetFont('saxmono', 'N', 8);
$pdf->writeHTML(summary_list($items), true, false, false, false, '');

$pdf->SetFont('saxmono', 'N', 10);
$pdf->writeHTML(signatory_details($record), true, false, false, false, '');

$pdf->Output('PRESCRIPTION-' . $record->transaction_no . '.pdf', 'I');
