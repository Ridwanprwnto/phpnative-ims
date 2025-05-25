<?php
require '../vendor/autoload.php';

// koneksi
require '../includes/config/timezone.php';
require '../includes/function/func.php';
require '../includes/config/conn.php';

// memanggil data POST
$submitpost = $_POST['exportdata'];

if (!isset($submitpost)) {
    $msg = encrypt("print-error");
    header("location: ../error.php?alert=$msg");
    exit();
}
    

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$title = "CSV KAETEGORI BARANG";

// $spreadsheet->getActiveSheet()->mergeCells('A1:E1');

// Rename worksheet
$sheet->setTitle($title);

$sheet->getStyle('A1:D1')->getFont()->setBold(true);

$sheet->setCellValue('A1', 'IDCAT');
$sheet->setCellValue('B1', 'PLUID');
$sheet->setCellValue('C1', 'DESC');
$sheet->setCellValue('D1', 'IDSATUAN');

$sheet->setCellValue('A2', 'A');
$sheet->setCellValue('B2', '5 DIGIT ANGKA');
$sheet->setCellValue('C2', 'NAMA KATEGORI BARANG');
$sheet->setCellValue('D2', 'KODE SATUAN BARANG');

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