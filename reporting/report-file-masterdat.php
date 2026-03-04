<?php
require '../vendor/autoload.php';

// koneksi
require '../includes/config/timezone.php';
require '../includes/function/func.php';
require '../includes/config/conn.php';

$id = $_GET["id"];

if(isset($_GET["id"])) {
    if($_GET["id"] === $id) {
        $strplus = rplplus($id);
        $decid = mysqli_real_escape_string($conn, decrypt($strplus));
        if($decid == true) {
            $sql = "SELECT dat.*, office.*, department.* FROM dat
            INNER JOIN office ON dat.office_dat = office.id_office
            INNER JOIN department ON dat.dept_dat = department.id_department
            WHERE dat.office_dat = LEFT('$decid', 4) AND dat.dept_dat = RIGHT('$decid', 4) ORDER BY dat.no_dat ASC";
            $query_h = mysqli_query($conn, $sql);
            $header = mysqli_fetch_assoc($query_h);
            if(!$header || empty($header)) {
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
    }
    else {
        $msg = encrypt("print-error");
        header("location: ../error.php?alert=$msg");
        exit();
    }
}
else {
    $msg = encrypt("print-error");
    header("location: ../error.php?alert=$msg");
    exit();
}
    
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$title = "CSV MASTER DAT";

// $spreadsheet->getActiveSheet()->mergeCells('A1:E1');

// Rename worksheet
$sheet->setTitle($title);

$sheet->getStyle('A1:D1')->getFont()->setBold(true);

$sheet->setCellValue('A1', 'NOMOR AKTIVA');
$sheet->setCellValue('B1', 'TGL PEROLEHAN');
$sheet->setCellValue('C1', 'QTY AKTIVA');
$sheet->setCellValue('D1', 'KODE BARANG');

$row = 2;

$query = mysqli_query($conn, $sql);
if(mysqli_num_rows($query) > 0 ) {

    while($data = mysqli_fetch_assoc($query)){
        $sheet->setCellValue('A'.$row, $data["no_dat"]);
        $sheet->setCellValue('B'.$row, $data["perolehan_dat"]);
        $sheet->setCellValue('C'.$row, $data["qty_dat"]);
        $sheet->setCellValue('D'.$row, $data["pluid_dat"]);
        $row++;
    }
}
else {
    $msg = encrypt("datanotfound");
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