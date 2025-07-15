<?php
require '../vendor/autoload.php';

// koneksi
require '../includes/config/timezone.php';
require '../includes/function/func.php';

$id = $_GET["id"];

if(!isset($id)) {
    $msg = encrypt("print-error");
    header("location: ../error.php?alert=$msg");
    exit();
}

$decid = decrypt(rplplus($id));

if($decid == FALSE) {
    $msg = encrypt("print-error");
    header("location: ../error.php?alert=$msg");
    exit();
}

if ($decid != "TABLOK-PLANO") {
    $msg = encrypt("print-error");
    header("location: ../error.php?alert=$msg");
    exit();
}

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setTitle('TABLOK-PLANO');

$sheet->getStyle('A1:M1')->getFont()->setBold(true);

$sheet->setCellValue('A1', 'PLU');
$sheet->setCellValue('B1', 'DESC');
$sheet->setCellValue('C1', 'TYPE_RAK');
$sheet->setCellValue('D1', 'TYPE_ITEM');
$sheet->setCellValue('E1', 'LINE');
$sheet->setCellValue('F1', 'ZONA');
$sheet->setCellValue('G1', 'STATION');
$sheet->setCellValue('H1', 'RAK');
$sheet->setCellValue('I1', 'SHELF');
$sheet->setCellValue('J1', 'CELL');
$sheet->setCellValue('K1', 'KEL_CARTON_IN_PALLET');
$sheet->setCellValue('L1', 'IP_DPD');
$sheet->setCellValue('M1', 'ID_DPD');

$row = 2;

$sheet->setCellValue('A'.$row, '8 DIGIT PLU');
$sheet->setCellValue('B'.$row, 'DESKRIPSI');
$sheet->setCellValue('C'.$row, 'F/BK/BF');
$sheet->setCellValue('D'.$row, 'F/NF');
$sheet->setCellValue('E'.$row, 'NUMBER/HURUF');
$sheet->setCellValue('F'.$row, '1/2 DIGIT NUMBER');
$sheet->setCellValue('G'.$row, '1/2 DIGIT NUMBER');
$sheet->setCellValue('H'.$row, '2 DIGIT NUMBER');
$sheet->setCellValue('I'.$row, '1 DIGIT NUMBER');
$sheet->setCellValue('J'.$row, '1 DIGIT NUMBER');
$sheet->setCellValue('K'.$row, 'NUMBER');
$sheet->setCellValue('L'.$row, 'XXX.XXX.XXX.XXX');
$sheet->setCellValue('M'.$row, 'NUMBER');

$sheet->setCellValue('A3', 'XXXXXXXX');
$sheet->setCellValue('B3', 'DESKRIPSI');
$sheet->setCellValue('C3', 'BF');
$sheet->setCellValue('D3', 'F');
$sheet->setCellValue('E3', '1');
$sheet->setCellValue('F3', '1');
$sheet->setCellValue('G3', '01');
$sheet->setCellValue('H3', '01');
$sheet->setCellValue('I3', '1');
$sheet->setCellValue('J3', '1');
$sheet->setCellValue('K3', '100');
$sheet->setCellValue('L3', '172.31.31.1');
$sheet->setCellValue('M3', '10');
    
// Rename worksheet
// $spreadsheet->getActiveSheet()->setTitle('SHEET NAME');

$title = $decid.'-'.date('d-m-Y');
 
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$spreadsheet->setActiveSheetIndex(0);
 
// Redirect output to a client’s web browser (Xlsx)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename='.$title.'.csv');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');
 
// If you're serving to IE over SSL, then the following may be needed
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header('Pragma: public'); // HTTP/1.0

$writer = new Csv($spreadsheet);
$writer->save('php://output');
?>