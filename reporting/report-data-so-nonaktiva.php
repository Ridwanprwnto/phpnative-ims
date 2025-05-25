<?php
require '../vendor/autoload.php';

// koneksi
require '../includes/config/timezone.php';
require '../includes/function/func.php';
require '../includes/config/conn.php';

// memanggil data POST
$noref = mysqli_real_escape_string($conn, $_POST['noref-so']);
$office = mysqli_real_escape_string($conn, $_POST['office-so']);
$dept = mysqli_real_escape_string($conn, $_POST['dept-so']);

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$title = "DRAFT-SO-".$noref;

// $spreadsheet->getActiveSheet()->mergeCells('A1:E1');

// Rename worksheet
$sheet->setTitle($title);

$sheet->getStyle('A1:C1')->getFont()->setBold(true);

$sheet->setCellValue('A1', 'PLUID');
$sheet->setCellValue('B1', 'FISIK');
$sheet->setCellValue('C1', 'KETERANGAN');

// membaca data dari mysql
if (isset($office) && isset($dept) && isset($noref)) {

    $sql = "SELECT A.*, B.*, C.NamaBarang, D.NamaJenis FROM detail_stock_opname AS A
    INNER JOIN head_stock_opname AS B ON A.no_so_head = B.no_so 
    INNER JOIN mastercategory AS C ON LEFT(A.pluid_so, 6) = C.IDBarang
    INNER JOIN masterjenis AS D ON RIGHT(A.pluid_so, 4) = D.IDJenis
    WHERE B.office_so = '$office' AND B.dept_so = '$dept' AND B.no_so = '$noref'";
    
    $query = mysqli_query($conn, $sql);
    $row = 2;

    if(mysqli_num_rows($query) > 0 ) {

        while($record = mysqli_fetch_array($query)) {
            $sheet->setCellValue('A'.$row, $record['pluid_so']);
            $sheet->setCellValue('B'.$row, $record['fisik_so']);
            $sheet->setCellValue('C'.$row, $record['keterangan_so']);
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