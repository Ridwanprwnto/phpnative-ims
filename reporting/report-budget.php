<?php
require '../vendor/autoload.php';

// koneksi
require '../includes/config/timezone.php';
require '../includes/function/func.php';
require '../includes/config/conn.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// $spreadsheet->getActiveSheet()->mergeCells('A1:F1');
// $spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'DRAFT BUDGET');

// sheet peratama
$sheet->setTitle('Sheet 1');

$sheet->getStyle('A1:C1')->getFont()->setBold(true);

$sheet->setCellValue('A1', 'KODE BARANG');
$sheet->setCellValue('B1', 'NAMA BARANG');
$sheet->setCellValue('C1', 'QTY BUDGET');

// memanggil data POST
$year = mysqli_real_escape_string($conn, $_POST['tahun']);
$office = mysqli_real_escape_string($conn, $_POST['office']);
$dept = mysqli_real_escape_string($conn, $_POST['department']);

// membaca data dari mysql
if (isset($year) && isset($office) && isset($dept)) {

    $result = "SELECT budget.*, mastercategory.*, masterjenis.*, office.*, department.* FROM budget
    INNER JOIN mastercategory ON LEFT(budget.plu_id, 6) = mastercategory.IDBarang
    INNER JOIN masterjenis ON RIGHT(budget.plu_id, 4) = masterjenis.IDJenis
    INNER JOIN office ON budget.id_office = office.id_office
    INNER JOIN department ON budget.id_department = department.id_department
    WHERE budget.id_office = '$office' AND budget.id_department = '$dept' AND budget.tahun_periode = '$year'";

    $query = mysqli_query($conn, $result);
    $row = 2;

    if(mysqli_num_rows($query) > 0 ) {

        while($record = mysqli_fetch_array($query)) {
            $sheet->setCellValue('A'.$row, $record['plu_id']);
            $sheet->setCellValue('B'.$row, $record['NamaBarang']." ".$record['NamaJenis']);
            $sheet->setCellValue('C'.$row, $record['stock_budget']);
            $row++;
        }

    }
    else {
        $msg = encrypt("datanotfound");
        header("location: ../error.php?alert=$msg");
        exit();
    }
}
else {
    $msg = encrypt("print-error");
    header("location: ../error.php?alert=$msg");
    exit();
}
// Rename worksheet
// $spreadsheet->getActiveSheet()->setTitle('SHEET NAME');

$title = "DRAFT-BUDGET ".date('d-m-Y');
 
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