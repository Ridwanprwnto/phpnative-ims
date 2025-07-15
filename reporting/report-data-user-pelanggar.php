<?php

require '../vendor/autoload.php';

require '../includes/config/timezone.php';
require '../includes/function/func.php';
require '../includes/function/tag.php';
require '../includes/config/conn.php';

/*call the FPDF library*/
require '../includes/config/mc_table.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$user = mysqli_real_escape_string($conn, $_POST["user-cetak"]);
$office = mysqli_real_escape_string($conn, $_POST["office-cetak"]);
$dept = mysqli_real_escape_string($conn, $_POST["dept-cetak"]);
$start = mysqli_real_escape_string($conn, $_POST["start-cetak"]);
$end = mysqli_real_escape_string($conn, $_POST["end-cetak"]);
$pelanggar = mysqli_real_escape_string($conn, $_POST["pelanggarcetak"]);

if ($pelanggar == "ALL") {
    $sql = "SELECT A.*, B.id_office, B.office_name, C.id_department, C.department_name, D.divisi_name, J.username AS user_atasan , J.full_name, K.name_fup_plg, L.username_plg_cctv FROM pelanggaran_cctv AS A
    INNER JOIN office AS B ON A.office_plg_cctv = B.id_office
    INNER JOIN department AS C ON A.dept_plg_cctv = C.id_department
    INNER JOIN divisi AS D ON A.div_plg_cctv = D.id_divisi
    INNER JOIN users AS J ON A.proses_plg_cctv = J.nik
    INNER JOIN fup_pelanggaran AS K ON A.fup_plg_cctv = K.id_fup_plg
    INNER JOIN user_pelanggaran_cctv AS L ON A.no_plg_cctv = L.head_no_plg_cctv
    WHERE A.office_plg_cctv = '$office' AND A.dept_plg_cctv = '$dept' AND LEFT(A.tgl_plg_cctv, 10) BETWEEN '$start' AND '$end' AND A.tersangka_plg_cctv != '' AND A.status_plg_cctv != 'S' ORDER BY A.tgl_plg_cctv ASC";
}
elseif ($pelanggar == "IDENTITAS TIDAK DIKETAHUI") {
    $sql = "SELECT A.*, B.id_office, B.office_name, C.id_department, C.department_name, D.divisi_name, J.username AS user_atasan , J.full_name, K.name_fup_plg, L.username_plg_cctv FROM pelanggaran_cctv AS A
    INNER JOIN office AS B ON A.office_plg_cctv = B.id_office
    INNER JOIN department AS C ON A.dept_plg_cctv = C.id_department
    INNER JOIN divisi AS D ON A.div_plg_cctv = D.id_divisi
    INNER JOIN users AS J ON A.proses_plg_cctv = J.nik
    INNER JOIN fup_pelanggaran AS K ON A.fup_plg_cctv = K.id_fup_plg
    INNER JOIN user_pelanggaran_cctv AS L ON A.no_plg_cctv = L.head_no_plg_cctv
    WHERE A.office_plg_cctv = '$office' AND A.dept_plg_cctv = '$dept' AND LEFT(A.tgl_plg_cctv, 10) BETWEEN '$start' AND '$end' AND A.tersangka_plg_cctv != '' AND A.status_plg_cctv != 'S' AND L.username_plg_cctv = '$pelanggar' ORDER BY A.tgl_plg_cctv ASC";
}
elseif ($pelanggar == "BAGIAN LAIN") {
    $sql = "SELECT A.*, B.id_office, B.office_name, C.id_department, C.department_name, D.divisi_name, J.username AS user_atasan , J.full_name, K.name_fup_plg, L.username_plg_cctv FROM pelanggaran_cctv AS A
    INNER JOIN office AS B ON A.office_plg_cctv = B.id_office
    INNER JOIN department AS C ON A.dept_plg_cctv = C.id_department
    INNER JOIN divisi AS D ON A.div_plg_cctv = D.id_divisi
    INNER JOIN users AS J ON A.proses_plg_cctv = J.nik
    INNER JOIN fup_pelanggaran AS K ON A.fup_plg_cctv = K.id_fup_plg
    INNER JOIN user_pelanggaran_cctv AS L ON A.no_plg_cctv = L.head_no_plg_cctv
    WHERE A.office_plg_cctv = '$office' AND A.dept_plg_cctv = '$dept' AND LEFT(A.tgl_plg_cctv, 10) BETWEEN '$start' AND '$end' AND A.tersangka_plg_cctv != '' AND A.status_plg_cctv != 'S' AND L.username_plg_cctv = '$pelanggar' ORDER BY A.tgl_plg_cctv ASC";
}
else {
    $sql = "SELECT A.*, B.id_office, B.office_name, C.id_department, C.department_name, D.divisi_name, J.username AS user_atasan , J.full_name, K.name_fup_plg, L.username_plg_cctv FROM pelanggaran_cctv AS A
    INNER JOIN office AS B ON A.office_plg_cctv = B.id_office
    INNER JOIN department AS C ON A.dept_plg_cctv = C.id_department
    INNER JOIN divisi AS D ON A.div_plg_cctv = D.id_divisi
    INNER JOIN users AS J ON A.proses_plg_cctv = J.nik
    INNER JOIN fup_pelanggaran AS K ON A.fup_plg_cctv = K.id_fup_plg
    INNER JOIN user_pelanggaran_cctv AS L ON A.no_plg_cctv = L.head_no_plg_cctv
    WHERE A.office_plg_cctv = '$office' AND A.dept_plg_cctv = '$dept' AND LEFT(A.tgl_plg_cctv, 10) BETWEEN '$start' AND '$end' AND A.tersangka_plg_cctv != '' AND A.status_plg_cctv != 'S' AND LEFT(L.username_plg_cctv, 10) = '$pelanggar' ORDER BY A.tgl_plg_cctv ASC";
}
    
$result = mysqli_query($conn, $sql);
$header = mysqli_fetch_assoc($result);

$title = "LAPORAN DATA USERS PELANGGARAN CCTV";

if (isset($_POST["printpdf"])) {
    class PDF extends PDF_MC_Table
    {
        // Page header
        function Header()
        {
            
            global $header, $user, $start, $end;

            $this->SetFont('Arial','',10);

            $this->Cell(28 ,5,'Office',0,0);
            $this->Cell(48 ,5,': '.$header['id_office']." - ".$header['office_name'],0,0);
            $this->Cell(38 ,5,'',0,0);
            $this->Cell(28 ,5,'Print Date',0,0);
            $this->Cell(48 ,5,': '.date("d-m-Y H:i:s"), 0, 1);
            
            $this->Cell(28 ,5,'Department',0,0);
            $this->Cell(48 ,5,': '.$header['department_name'],0,0);
            $this->Cell(38 ,5,'',0,0);
            $this->Cell(28 ,5,'User',0,0);
            $this->Cell(48 ,5,': '.$user, 0, 1);
            
            $this->Ln(3);
            
            $this->SetFont('Arial','B',12);
            
            $this->Cell(199, 10, 'REKAP DATA APPROVAL USER TEREKAM PELANGGARAN CCTV', 0, 1, 'C');
            
            $this->SetFont('Arial','',10);
            $this->Cell(190, 5, 'Periode : '.$start." - ".$end, 0, 1, 'C');

            $this->Ln(3);
            
            $this->SetFont('Arial','B',8);
            /*Heading Of the table*/
            $this->Cell(9 ,8,'No',1,0,'C');
            $this->Cell(14 ,8,'NOP',1,0,'C');
            $this->Cell(28 ,8,'User Pelanggar',1,0,'C');
            $this->Cell(20 ,8,'Bagian',1,0,'C');
            $this->Cell(26 ,8,'Kategori',1,0,'C');
            $this->Cell(30 ,8,'Pelanggaran',1,0,'C');
            $this->Cell(20 ,8,'Tgl Kejadian',1,0,'C');
            $this->Cell(20 ,8,'Lokasi',1,0,'C');
            $this->Cell(24 ,8,'Sanksi',1,1,'C');
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
    $pdf = new PDF('P','mm','A4');
    $pdf->AliasNbPages();
    $pdf->AddPage();

    $pdf->SetAuthor('Inventory Management System');
    $pdf->SetTitle('Report Data Users Pelanggaran CCTV');
    $pdf->SetSubject('User Pelanggaran CCTV');
    $pdf->SetKeywords('CCTV');
    $pdf->SetCreator('IMS');

    $start_x = $pdf->GetX(); //initial x (start of column position)
    $current_y = $pdf->GetY();
    $current_x = $pdf->GetX();

    $pdf->SetFont('Arial','',8);

    $no = 1;
    $nol = [0, 0, 0, 0, 0, 0, 0, 0];
    $cellHeight = 10;

    if (isset($office) && isset($dept) && isset($start) && isset($end)) {

        $query = mysqli_query($conn, $sql);
        if(mysqli_num_rows($query) > 0 ) {

            while($data = mysqli_fetch_assoc($query)){

                $nop = $data['no_plg_cctv'];
                $user_p = $data['username_plg_cctv'];
                $div = $data['divisi_name'];
                $cat = $data['ctg_plg_cctv'];
                $plg = $data['jns_plg_cctv'];
                $lok = $data['lokasi_plg_cctv'];
                $tgl = $data['tgl_plg_cctv'];
                $snk = $data['name_fup_plg'];

                $pdf->SetWidths(array(9, 14, 28, 20, 26, 30, 20, 20, 24));
                $pdf->Row(array($no++, $nop, $user_p, $div, $cat, $plg, $tgl, $lok, $snk));

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

    // Nama file ketika di print
    $pdf->Output($title.".pdf","I");

}
elseif (isset($_POST["printexcell"])) {

    $row = 2;
    $no = 1;

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Rename worksheet
    $sheet->setTitle('Sheet 1');

    $sheet->getStyle('A1:J1')->getFont()->setBold(true);

    $sheet->setCellValue('A1', 'NO');
    $sheet->setCellValue('B1', 'NO PELANGGARAN');
    $sheet->setCellValue('C1', 'USER PELANGGAR');
    $sheet->setCellValue('D1', 'BAGIAN');
    $sheet->setCellValue('E1', 'KATEGORI');
    $sheet->setCellValue('F1', 'PELANGGARAN');
    $sheet->setCellValue('G1', 'TGL KEJADIAN');
    $sheet->setCellValue('H1', 'LOKASI');
    $sheet->setCellValue('I1', 'SANKSI');
    $sheet->setCellValue('J1', 'STATUS');

    if (isset($office) && !empty($office) && isset($dept) && !empty($dept) && isset($start) && !empty($start) && isset($end) && !empty($end)) {
    
        $query_xcl = mysqli_query($conn, $sql);
        if(mysqli_num_rows($query_xcl) > 0 ) {
    
            while($record = mysqli_fetch_assoc($query_xcl)){

                $nop = $record['no_plg_cctv'];
                $user_p = $record['username_plg_cctv'];
                $div = $record['divisi_name'];
                $cat = $record['ctg_plg_cctv'];
                $plg = $record['jns_plg_cctv'];
                $tgl = $record['tgl_plg_cctv'];
                $lok = $record['lokasi_plg_cctv'];
                $snk = $record['name_fup_plg'];
                $sts = $record['status_plg_cctv'] == 'N' ? 'FUP' : 'APPROVE';

                $sheet->setCellValue('A'.$row, $no++);
                $sheet->setCellValue('B'.$row, $nop);
                $sheet->setCellValue('C'.$row, $user_p);
                $sheet->setCellValue('D'.$row, $div);
                $sheet->setCellValue('E'.$row, $cat);
                $sheet->setCellValue('F'.$row, $plg);
                $sheet->setCellValue('G'.$row, $tgl);
                $sheet->setCellValue('H'.$row, $lok);
                $sheet->setCellValue('I'.$row, $snk);
                $sheet->setCellValue('J'.$row, $sts);
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

?>