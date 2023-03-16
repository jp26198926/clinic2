<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (!$result) {
	echo "Error: Critical Error Encountered!";
	exit();
}


// Create new PHPExcel object
$excel = new Spreadsheet();

// Set document properties
$excel->getProperties()->setCreator($current_user)
	->setLastModifiedBy($current_user)
	->setTitle("Tax Report")
	->setSubject("Summary")
	->setDescription("Report")
	->setKeywords("Summary Report Tax Excel PHP")
	->setCategory("Download Report");

// Add header data
$excel->setActiveSheetIndex(0)
	->setCellValue('A1', $app_name)
	->setCellValue('A2', 'GROUP')
	->setCellValue('A3', 'DATE START')
	->setCellValue('A4', 'DATE END')

	->setCellValue('B2', $group)
	->setCellValue('B3', $date_start)
	->setCellValue('B4', $date_end)

	->setCellValue('A6', 'DEPT')
	->setCellValue('B6', 'CC')
	->setCellValue('C6', 'EMP NO')
	->setCellValue('D6', 'EMPLOYEE')
	->setCellValue('E6', 'TAX');

foreach (range('A', 'D') as $columnID) {
	$excel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
	$excel->getActiveSheet()->getStyle($columnID)
		->getNumberFormat()
		->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
}
foreach (range('E', 'E') as $columnID) {
	$excel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
	$excel->getActiveSheet()->getStyle($columnID)
		->getNumberFormat()->setFormatCode("#,##0.00");
}
foreach (range('A', 'E') as $columnID) {
	$excel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
}
foreach (range('E', 'E') as $columnID) {
	$excel->getActiveSheet()->getStyle($columnID)->getAlignment()
		->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
		->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
}
//$excel->getActiveSheet()->getStyle('A1:S1')->getFont()->setBold(true);
//$excel->getActiveSheet()->getStyle('B3:S3')->getFont()->setBold(true);
$i = 6;

if (count($result) > 0) {
	$overall_total = 0;

	foreach ($result as $key => $row) {
		$i++;
		$dept_code = $row->dept_code;
		$cc_code = $row->cc_code;
		$emp_no = $row->emp_no;
		$employee = strtoupper($row->employee);
		$total_tax = $row->total_tax;

		$overall_total += $total_tax;

		//$total_tax = number_format($total_tax,2,".",",");

		$excel->setActiveSheetIndex(0)
			->setCellValue('A' . $i, $dept_code)
			->setCellValue('B' . $i, $cc_code)
			->setCellValue('C' . $i, $emp_no)
			->setCellValue('D' . $i, $employee)
			->setCellValue('E' . $i, $total_tax);
	}

	//$overall_total = number_format($overall_total,2,".",",");

	$i++;

	$excel->setActiveSheetIndex(0)
		->setCellValue('A' . $i, "GRAND TOTAL")
		->setCellValue('E' . $i, $overall_total);
}

//design
$excel->getActiveSheet()->freezePane('A7');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$excel->setActiveSheetIndex(0);


$writer = new Xlsx($excel); // instantiate Xlsx

$filename = 'tax-report-' . date('Ymd-His'); // set filename for excel file to be exported

header('Content-Type: application/vnd.ms-excel'); // generate excel file
header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
header('Cache-Control: max-age=0');

$writer->save('php://output');	// download file 
exit;
