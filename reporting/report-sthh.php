<?php
require '../vendor/autoload.php';

require '../includes/config/timezone.php';
require '../includes/function/func.php';
require '../includes/config/conn.php';

/*call the FPDF library*/
require '../includes/config/mc_table.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$office = mysqli_real_escape_string($conn, $_POST['office']);
$dept = mysqli_real_escape_string($conn, $_POST['dept']);
$from = mysqli_real_escape_string($conn, $_POST['from']);
$end = mysqli_real_escape_string($conn, $_POST['end']);
$user = mysqli_real_escape_string($conn, $_POST['user']);
$sts = $_POST['status'];

if ($sts == "A") {
    $sql = "SELECT sthh.*, office.*, department.* FROM sthh
    INNER JOIN office ON sthh.id_office = office.id_office
    INNER JOIN department ON sthh.id_department = department.id_department
    WHERE sthh.id_office = '$office' AND sthh.id_department = '$dept' AND sthh.dateout BETWEEN '$from' AND '$end' ORDER BY sthh.no_pinjam ASC";
}
elseif ($sts == "Y") {
    $sql = "SELECT sthh.*, office.*, department.* FROM sthh
    INNER JOIN office ON sthh.id_office = office.id_office
    INNER JOIN department ON sthh.id_department = department.id_department
    WHERE sthh.id_office = '$office' AND sthh.id_department = '$dept' AND sthh.dateout BETWEEN '$from' AND '$end' AND sthh.datein IS NOT NULL AND sthh.penerima IS NOT NULL ORDER BY sthh.no_pinjam ASC";
}
elseif ($sts == "N") {
    $sql = "SELECT sthh.*, office.*, department.* FROM sthh
    INNER JOIN office ON sthh.id_office = office.id_office
    INNER JOIN department ON sthh.id_department = department.id_department
    WHERE sthh.id_office = '$office' AND sthh.id_department = '$dept' AND sthh.dateout BETWEEN '$from' AND '$end' AND sthh.datein IS NULL AND sthh.penerima IS NULL ORDER BY sthh.no_pinjam ASC";
}

$query = mysqli_query($conn, $sql);
$header = mysqli_fetch_assoc(mysqli_query($conn, $sql));

$title = "LAP-STHH ".$from." ".$end;

if (isset($_POST["printexcell"])) {

    $row = 2;

    if(mysqli_num_rows($query) > 0 ) {
        
        $no_exl = 1;

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Rename worksheet
        $sheet->setTitle($title);

        $sheet->getStyle('A1:N1')->getFont()->setBold(true);

        $sheet->setCellValue('A1', 'NO');
        $sheet->setCellValue('B1', 'ID');
        $sheet->setCellValue('C1', 'UNIT');
        $sheet->setCellValue('D1', 'NO HH - SN');
        $sheet->setCellValue('E1', 'KETERANGAN KIRIM');
        $sheet->setCellValue('F1', 'DEPT');
        $sheet->setCellValue('G1', 'BAGIAN');
        $sheet->setCellValue('H1', 'PEMINJAM');
        $sheet->setCellValue('I1', 'PIC SUPPORT');
        $sheet->setCellValue('J1', 'DATE OUT');
        $sheet->setCellValue('K1', 'YANG MENGEMBALIKAN');
        $sheet->setCellValue('L1', 'YANG MENERIMA');
        $sheet->setCellValue('M1', 'DATE IN');
        $sheet->setCellValue('N1', 'KETERANGAN TERIMA');

        while($record = mysqli_fetch_array($query)) {
            $sheet->setCellValue('A'.$row, $no_exl++);
            $sheet->setCellValue('B'.$row, $record['no_pinjam']);
            $sheet->setCellValue('C'.$row, substr($record['pluid'], 0, 2) == 'HH' ? 'HANDHELD' : (substr($record['pluid'], 0, 2) == 'BT' ? 'BATTERY HH' : "HANDY TALKY"));
            $sheet->setCellValue('D'.$row, $record['pluid']);
            $sheet->setCellValue('E'.$row, $record['keterangan']);
            $sheet->setCellValue('F'.$row, $record['id_divisi']);
            $sheet->setCellValue('G'.$row, $record["id_sub_divisi"]);
            $sheet->setCellValue('H'.$row, $record['nik']);
            $sheet->setCellValue('I'.$row, strtoupper($record['pic']));
            $sheet->setCellValue('J'.$row, $record['dateout'].' '.$record['jamkeluar']);
            $sheet->setCellValue('K'.$row, isset($record['pengembali']) ? $record['pengembali'] : "-");
            $sheet->setCellValue('L'.$row, isset($record['penerima']) ? $record['penerima'] : "-");
            $sheet->setCellValue('M'.$row, isset($record['datein']) ? $record['datein'] : "-");
            $sheet->setCellValue('N'.$row, $record['ket_terima']);
            $row++;
        }
        
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $spreadsheet->setActiveSheetIndex(0);
        
        // Redirect output to a client’s web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename='.$title.'.xlsx');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        
        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');

    }
    else {
        $msg = encrypt("datanotfound");
        header("location: ../error.php?alert=$msg");
        exit();
    }
}
elseif (isset($_POST["printpdf"])) {

    class PDF extends PDF_MC_Table
    {
        // Page header
        function Header()
        {
            
            global $header;
            global $user;
            global $from;
            global $end;

            $this->SetFont('Arial','',10);

            $this->Cell(28 ,5,'Office',0,0);
            $this->Cell(48 ,5,': '.$header['id_office']." - ".$header['office_name'],0,0);
            $this->Cell(126 ,5,'',0,0);
            $this->Cell(28 ,5,'Print Date',0,0);
            $this->Cell(48 ,5,': '.date("d-m-Y H:i:s"), 0, 1);
            
            $this->Cell(28 ,5,'Department',0,0);
            $this->Cell(48 ,5,': '.$header['department_name'],0,0);
            $this->Cell(126 ,5,'',0,0);
            $this->Cell(28 ,5,'User Print',0,0);
            $this->Cell(48 ,5,': '.$user, 0, 1);
            
            $this->Ln(2);
            
            $this->SetFont('Arial','B',14);
            
            $this->Cell(278, 10, 'REKAP DATA SERAH TERIMA HANDHELD', 0, 1, 'C');

            $this->SetFont('Arial','',10);
            $this->Cell(278, 5, 'Periode : '.$from." - ".$end, 0, 1, 'C');
    
            $this->Ln(2);

            $this->SetFont('Arial','B',8);
            /*Heading Of the table*/
            $this->Cell(10 ,10,'NO',1,0,'C');
            $this->Cell(20 ,10,'UNIT',1,0,'C');
            $this->Cell(14 ,10,'NOMOR',1,0,'C');
            $this->Cell(42 ,10,'BAGIAN',1,0,'C');
            $this->Cell(40 ,10,'PEMINJAM',1,0,'C');
            $this->Cell(20 ,10,'PIC',1,0,'C');
            $this->Cell(30 ,10,'TGL KELUAR',1,0,'C');
            $this->Cell(48 ,10,'PENGEMBALI',1,0,'C');
            $this->Cell(20 ,10,'PENERIMA',1,0,'C');
            $this->Cell(30 ,10,'TGL MASUK',1,1,'C');
            /*end of line*/
        }

        // Page footer
        function Footer()
        {
            // Position at 1.5 cm from bottom
            $this->SetY(-15);
            // Arial italic 8
            $this->SetFont('Arial','I',8);
            // Page number
            $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
        }
    }

    /*A4 width : 219mm*/
    $pdf = new PDF('L','mm','A4');
    $pdf->AliasNbPages();
    $pdf->AddPage();

    $pdf->SetAuthor('Inventory Management System');
    $pdf->SetTitle('Rekap Data Serah Terima Handheld');
    $pdf->SetSubject('Serah Terima Handheld');
    $pdf->SetKeywords('STHH');
    $pdf->SetCreator('IMS');

    $pdf->SetFont('Arial','',8);
    
    $no_pdf = 1;

    if(mysqli_num_rows($query) > 0 ) {

        while($data = mysqli_fetch_assoc($query)){

            $unit = substr($data['pluid'], 0, 2) == 'HH' ? 'HANDHELD' : (substr($data['pluid'], 0, 2) == 'BT' ? 'BATTERY HH' : "HANDY TALKY");
            $nomor = substr($data['pluid'], 0, 5);
            $div = $data["id_divisi"]." - ".$data["id_sub_divisi"];
            $peminjam = strtoupper($data['nik']);
            $pic = strtoupper($data['pic']);
            $dout = $data['dateout'].' '.$data['jamkeluar'];
            $pengembali = isset($data['pengembali']) ? $data['pengembali'] : "-";
            $penerima = isset($data['penerima']) ? $data['penerima'] : "-";
            $din = isset($data['datein']) ? $data['datein'] : "-";

            $pdf->SetWidths(array(10, 20, 14, 42, 40, 20, 30, 48, 20, 30));
            $pdf->Row(array($no_pdf++, $unit, $nomor, $div, $peminjam, $pic, $dout, $pengembali, $penerima, $din));
            
        }
        
        $pdf->Output($title.".pdf","I");

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
?>