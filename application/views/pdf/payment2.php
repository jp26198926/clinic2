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
							<td align=\"right\" width=\"15%\"><b>AMOUNT PAID : </b></td>
							<td align=\"left\" width=\"45%\">{$record->amount}</td>
							<td align=\"right\" width=\"15%\"><b>PMT TYPE : </b></td>
                            <td align=\"left\" width=\"25%\">{$record->payment_type}</td>
						</tr>
                    </table>";
	return $details;
}

$remarks_msg = nl2br($record->remarks . '');

$remarks = "Received a payment amounting to <u>{$amount_paid_words}</u> (PGK {$amount_paid}) for the Invoice No. {$record->transaction_no}. Please refer to the Charge Slip.";

$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->myHeader($company_name, $company_address, $company_contact, "e-Receipt", "", 25);
$pdf->myFooter("Printed: " . date("Y-m-d H:i:s") . " - " . strtoupper($record->created), "Record ", -15, "");

$pdf->SetAutoPageBreak(true, 15);
$pdf->SetAuthor('noSystems Online');
$pdf->SetTitle('Clinic');
$pdf->SetSubject('System');
$pdf->SetKeywords('DOWNLOAD, PDF');
$pdf->SetTopMargin(25);

$pdf->AddPage('L', 'A4_ONE_FOURTH');
$pdf->SetFont('saxmono', 'N', 10);
$pdf->writeHTML(details($record), true, false, false, false, '');

$pdf->SetFont('saxmono', 'N', 10);
$pdf->writeHTML($remarks, true, false, false, false, "C");
//$pdf->writeHTML(signatory_details($record), true, false, false, false, '');

$pdf->Output($record->payment_no . '.pdf', 'I');
