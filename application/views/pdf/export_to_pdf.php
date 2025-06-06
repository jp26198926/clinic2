<?php

function details($record)
{
	$details = " <table>
						<tr>
                            <td align=\"right\" width=\"15%\">DATE : </td>
                            <td align=\"left\" width=\"15%\">{$record->date}</td>
							<td align=\"right\" width=\"15%\">STATUS : </td>
                            <td align=\"left\" width=\"15%\">{$record->status}</td>
                            <td align=\"right\" width=\"15%\">DIVISION : </td>
                            <td align=\"left\" width=\"25%\">{$record->division_name}</td>
                        </tr>
						<tr>
                            <td align=\"right\" width=\"15%\">NOTIFY : </td>
                            <td align=\"left\" width=\"45%\">{$record->notify}</td>
							<td align=\"right\" width=\"15%\">DEPT : </td>
                            <td align=\"left\" width=\"25%\">{$record->department_code}</td>
                        </tr>
                    </table>";
	return $details;
}

function summary_list($items)
{
	$list = "   <table cellpadding=\"1\" >
						<tr>
							<th width=\"10%\" align=\"center\" border=\"1\" ><b> NO </b></th>
							<th width=\"10%\" align=\"center\" border=\"1\" ><b> SERIES # </b></th>
							<th width=\"10%\" align=\"center\" border=\"1\" ><b> QTY </b></th>
							<th width=\"10%\" align=\"center\" border=\"1\" ><b> UOM </b></th>
							<th width=\"40%\" align=\"center\" border=\"1\" ><b> ITEM </b></th>
							<th width=\"10%\" align=\"center\" border=\"1\" ><b> TYPE</b></th>
							<th width=\"10%\" align=\"center\" border=\"1\" ><b> STATUS </b></th>
						</tr>";

	if (count($items) > 0) {
		$i = 0;
		foreach ($items as $key => $item) {
			$i++;

			$item_description = nl2br($item->description . '');
			$qty = floatval($item->qty);

			$list .= "<tr>";
			$list .= "	<td align=\"center\">{$i}</td>";
			$list .= "	<td align=\"center\">{$item->series_no}</td>";
			$list .= "	<td align=\"center\">{$qty}</td>";
			$list .= "	<td align=\"center\">{$item->uom_code}</td>";
			$list .= "	<td align=\"center\">{$item_description}</td>";
			$list .= "	<td align=\"center\">{$item->type}</td>";
			$list .= "	<td align=\"center\">{$item->status}</td>";
			$list .= "</tr>";
		}
	} else {
		$list .= "<tr><td colspan=\"7\" align=\"center\">No Record</td></tr>";
	}

	$list .= "  </table>";

	return $list;
}

function signatory_details($record)
{
	$requested = strtoupper($record->created);
	$dept_approved = intval($record->status_id) > 5 ? strtoupper($record->noted) : "";
	$gm_approved = intval($record->status_id) > 7 ? strtoupper($record->approved) : "";

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
						<td align=\"center\">{$dept_approved}</td>
						<td></td>
						<td align=\"center\">{$gm_approved}</td>
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

$pdf->setFontSubsetting(false);

$pdf->myHeader($company_name, $company_address, $company_contact, $request_no, "", 25);
$pdf->myFooter("Printed: " . date("Y-m-d H:i:s") . " - " . $app_name, "Record ", -15, "");

$pdf->SetAutoPageBreak(true, 15);
$pdf->SetAuthor('MIG PNG');
$pdf->SetTitle('HRMS');
$pdf->SetSubject('PAYROLL');
$pdf->SetKeywords('DOWNLOAD, PDF');
$pdf->SetTopMargin(25);

$pdf->AddPage('P', 'A4');
$pdf->SetFont('saxmono', 'N', 10);
$pdf->writeHTML(details($record), true, false, false, false, '');

$pdf->SetFont('saxmono', 'N', 8);
$pdf->writeHTML(summary_list($items), true, false, false, false, '');

$pdf->SetFont('saxmono', 'N', 10);
$pdf->writeHTML($remarks, true, false, false, false, '');
$pdf->writeHTML(signatory_details($record), true, false, false, false, '');

$pdf->Output('RMS-' . $record->request_no . '.pdf', 'I');
