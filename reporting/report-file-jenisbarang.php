<?php
require '../vendor/autoload.php';

// koneksi
require '../includes/config/timezone.php';
require '../includes/function/func.php';
require '../includes/config/conn.php';

// memanggil data POST
$submitpost = $_POST['exportdatajenis'];

if (!isset($submitpost)) {
    $msg = encrypt("print-error");
    header("location: ../error.php?alert=$msg");
    exit();
}
    

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$title = "CSV JENIS BARANG";

// $spreadsheet->getActiveSheet()->mergeCells('A1:E1');

// Rename worksheet
$sheet->setTitle($title);

$sheet->getStyle('A1:C1')->getFont()->setBold(true);

$sheet->setCellValue('A1', 'PLUID');
$sheet->setCellValue('B1', 'DESC');
$sheet->setCellValue('C1', 'EST HARGA');

$sheet->setCellValue('A2', '6 DIGIT ANGKA');
$sheet->setCellValue('B2', 'NAMA JENIS BARANG');
$sheet->setCellValue('C2', 'NILAI HARGA IDR');

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