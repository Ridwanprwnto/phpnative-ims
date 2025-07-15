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

if ($decid != "MSBRGINV") {
    $msg = encrypt("print-error");
    header("location: ../error.php?alert=$msg");
    exit();
}

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// $spreadsheet->getActiveSheet()->mergeCells('A1:F1');
// $spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'DRAFT BUDGET');

// sheet peratama
$sheet->setTitle('MSBRGINV');

$sheet->getStyle('A1:G1')->getFont()->setBold(true);

$sheet->setCellValue('A1', 'KODE_BARANG');
$sheet->setCellValue('B1', 'MERK_BARANG');
$sheet->setCellValue('C1', 'TIPE_BARANG');
$sheet->setCellValue('D1', 'NO_LAMBUNG');
$sheet->setCellValue('E1', 'SERIAL_NUMBER');
$sheet->setCellValue('F1', 'NOMOR_AKTIVA');
$sheet->setCellValue('G1', 'KONDISI');

$row = 2;

$sheet->setCellValue('A'.$row, '10 DIGIT');
$sheet->setCellValue('B'.$row, '-');
$sheet->setCellValue('C'.$row, '-');
$sheet->setCellValue('D'.$row, '5 DIGIT');
$sheet->setCellValue('E'.$row, 'XXXXXXXXXX');
$sheet->setCellValue('F'.$row, '10 DIGIT');
$sheet->setCellValue('G'.$row, '2 DIGIT');
    
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