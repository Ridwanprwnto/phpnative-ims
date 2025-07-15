<?php
require '../vendor/autoload.php';

require '../includes/config/timezone.php';
require '../includes/function/func.php';
require '../includes/config/conn.php';

/*call the FPDF library*/
require '../includes/config/mc_table.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$user = $_POST["user-plg"];
$office = $_POST["office-plg"];
$dept = $_POST["dept-plg"];
$start = $_POST["start-plg"];
$end = $_POST["end-plg"];
$status = $_POST["status-plg"];

if ($status == 'A') {
    $sql = "SELECT A.*, B.id_office, B.office_name, C.id_department, C.department_name, D.divisi_name, J.username, J.full_name, K.name_fup_plg FROM pelanggaran_cctv AS A
    INNER JOIN office AS B ON A.office_plg_cctv = B.id_office
    INNER JOIN department AS C ON A.dept_plg_cctv = C.id_department
    INNER JOIN divisi AS D ON A.div_plg_cctv = D.id_divisi
    INNER JOIN users AS J ON A.user_plg_cctv = J.nik
    INNER JOIN fup_pelanggaran AS K ON A.fup_plg_cctv = K.id_fup_plg
    WHERE A.office_plg_cctv = '$office' AND A.dept_plg_cctv = '$dept' AND DATE(A.tgl_plg_cctv) BETWEEN '$start' AND '$end' ORDER BY A.tgl_plg_cctv ASC";
}
elseif ($status == 'S') {
    $sql = "SELECT A.*, B.id_office, B.office_name, C.id_department, C.department_name, D.divisi_name, J.username, J.full_name, K.name_fup_plg FROM pelanggaran_cctv AS A
    INNER JOIN office AS B ON A.office_plg_cctv = B.id_office
    INNER JOIN department AS C ON A.dept_plg_cctv = C.id_department
    INNER JOIN divisi AS D ON A.div_plg_cctv = D.id_divisi
    INNER JOIN users AS J ON A.user_plg_cctv = J.nik
    INNER JOIN fup_pelanggaran AS K ON A.fup_plg_cctv = K.id_fup_plg
    WHERE A.office_plg_cctv = '$office' AND A.dept_plg_cctv = '$dept' AND DATE(A.tgl_plg_cctv) BETWEEN '$start' AND '$end' AND A.status_plg_cctv = 'S' ORDER BY A.tgl_plg_cctv ASC";
}
elseif ($status == 'N') {
    $sql = "SELECT A.*, B.id_office, B.office_name, C.id_department, C.department_name, D.divisi_name, J.username, J.full_name, K.name_fup_plg FROM pelanggaran_cctv AS A
    INNER JOIN office AS B ON A.office_plg_cctv = B.id_office
    INNER JOIN department AS C ON A.dept_plg_cctv = C.id_department
    INNER JOIN divisi AS D ON A.div_plg_cctv = D.id_divisi
    INNER JOIN users AS J ON A.user_plg_cctv = J.nik
    INNER JOIN fup_pelanggaran AS K ON A.fup_plg_cctv = K.id_fup_plg
    WHERE A.office_plg_cctv = '$office' AND A.dept_plg_cctv = '$dept' AND DATE(A.tgl_plg_cctv) BETWEEN '$start' AND '$end' AND A.status_plg_cctv = 'N' ORDER BY A.tgl_plg_cctv ASC";
}

elseif ($status == 'Y') {
    $sql = "SELECT A.*, B.id_office, B.office_name, C.id_department, C.department_name, D.divisi_name, J.username, J.full_name, K.name_fup_plg FROM pelanggaran_cctv AS A
    INNER JOIN office AS B ON A.office_plg_cctv = B.id_office
    INNER JOIN department AS C ON A.dept_plg_cctv = C.id_department
    INNER JOIN divisi AS D ON A.div_plg_cctv = D.id_divisi
    INNER JOIN users AS J ON A.user_plg_cctv = J.nik
    INNER JOIN fup_pelanggaran AS K ON A.fup_plg_cctv = K.id_fup_plg
    WHERE A.office_plg_cctv = '$office' AND A.dept_plg_cctv = '$dept' AND DATE(A.tgl_plg_cctv) BETWEEN '$start' AND '$end' AND A.status_plg_cctv = 'Y' ORDER BY A.tgl_plg_cctv ASC";
}

$query_h = mysqli_query($conn, $sql);
$header = mysqli_fetch_assoc($query_h);

$title = "REPORT PELANGGARAN CCTV ".$start." ".$end;

if (isset($_POST["printpdf"])) {

    class PDF extends PDF_MC_Table
    {
        // Page header
        function Header()
        {
            
            global $header;
            global $user;
            global $start;
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

            $this->Ln(3);

            $this->SetFont('Arial','B',12);

            /*Cell(width , height , text , border , end line , [align] )*/
            $this->Cell(278 ,10,'REKAP DATA PELANGGARAN CCTV',0,1, 'C');

            $this->SetFont('Arial','',10);
            $this->Cell(278, 5, 'Periode : '.$start." - ".$end, 0, 1, 'C');

            $this->Ln(3);

            $this->SetFont('Arial','B',9);
            /*Heading Of the table*/
            $this->Cell(9 ,8,'No',1,0,'C');
            $this->Cell(14 ,8,'NOP',1,0,'C');
            $this->Cell(20 ,8,'Tanggal',1,0,'C');
            $this->Cell(12 ,8,'Shift',1,0,'C');
            $this->Cell(25 ,8,'Bagian',1,0,'C');
            $this->Cell(45 ,8,'Kategori',1,0,'C');
            $this->Cell(45 ,8,'Pelanggaran',1,0,'C');
            $this->Cell(28 ,8,'Lokasi CCTV',1,0,'C');
            $this->Cell(20 ,8,'User CCTV',1,0,'C');
            $this->Cell(30 ,8,'Ket FUP',1,0,'C');
            $this->Cell(30 ,8,'Status FUP',1,1,'C');/*end of line*/
            /*Heading Of the table end*/
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
    
    $pdf->SetTitle('Laporan Data Pelanggaran CCTV');

    /*output the result*/

    $pdf->SetFont('Arial','',9);

    $no = 1;

    if (isset($office) && !empty($office) && isset($dept) && !empty($dept) && isset($start) && !empty($start) && isset($end) && !empty($end)) {

        $query_pdf = mysqli_query($conn, $sql);
        if(mysqli_num_rows($query_pdf) > 0 ) {

            while($data_pdf = mysqli_fetch_assoc($query_pdf)){

                $nop = $data_pdf['no_plg_cctv'];
                $tgl = $data_pdf['tgl_plg_cctv'];
                $shift = $data_pdf['shift_plg_cctv'];
                $div = $data_pdf['divisi_name'];
                $cat = $data_pdf['ctg_plg_cctv'];
                $plg = $data_pdf['jns_plg_cctv'];
                $lok = $data_pdf['lokasi_plg_cctv'];
                $usr = strtoupper($data_pdf['username']);
                $fup = $data_pdf['name_fup_plg'];
                $sts = $data_pdf['status_plg_cctv'];

                if ($sts == "S") {
                    $sts = 'BELUM FUP';
                }
                elseif ($sts == 'N') {
                    $sts = 'SUDAH FUP ATASAN BELUM DI APPROVE';
                }
                elseif ($sts == 'Y') {
                    $sts = 'SUDAH FUP DAN SUDAH DI APPROVE';
                }

                $pdf->SetWidths(array(9, 14, 20, 12, 25, 45, 45, 28, 20, 30, 30));
                $pdf->Row(array($no++, $nop, $tgl, $shift, $div, $cat, $plg, $lok, $usr, $fup, $sts));

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
}
elseif (isset($_POST["printexcell"])) {

    $row = 2;
    $no = 1;

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Rename worksheet
    $sheet->setTitle('Sheet 1');

    $sheet->getStyle('A1:P1')->getFont()->setBold(true);

    $sheet->setCellValue('A1', 'NO');
    $sheet->setCellValue('B1', 'NO PELANGGARAN');
    $sheet->setCellValue('C1', 'TANGGAL');
    $sheet->setCellValue('D1', 'WAKTU');
    $sheet->setCellValue('E1', 'SHIFT');
    $sheet->setCellValue('F1', 'BAGIAN');
    $sheet->setCellValue('G1', 'KATEGORI');
    $sheet->setCellValue('H1', 'PELANGGARAN');
    $sheet->setCellValue('I1', 'IP DVR');
    $sheet->setCellValue('J1', 'LOKASI CCTV');
    $sheet->setCellValue('K1', 'USER CCTV');
    $sheet->setCellValue('L1', 'KETERANGAN');
    $sheet->setCellValue('M1', 'FOLLOW UP');
    $sheet->setCellValue('N1', 'STATUS FUP');
    $sheet->setCellValue('O1', 'USER PELANGGAR');
    $sheet->setCellValue('P1', 'PENJELASAN');

    if (isset($office) && !empty($office) && isset($dept) && !empty($dept) && isset($start) && !empty($start) && isset($end) && !empty($end)) {
    
        $query_xcl = mysqli_query($conn, $sql);
        if(mysqli_num_rows($query_xcl) > 0 ) {
    
            while($record = mysqli_fetch_assoc($query_xcl)){

                $str_date = substr($record['tgl_plg_cctv'], 0, -9);
                $slash_date  =  str_replace('-"', '/', $str_date);
                $newDate  =  date( "Y/m/d", strtotime($slash_date));

                $sts_fup = $record['status_plg_cctv'];

                if ($sts_fup == "S") {
                    $sts_fup = 'BELUM FUP';
                }
                elseif ($sts_fup == 'N') {
                    $sts_fup = 'SUDAH FUP ATASAN BELUM DI APPROVE';
                }
                elseif ($sts_fup == 'Y') {
                    $sts_fup = 'SUDAH FUP DAN SUDAH DI APPROVE';
                }

                $sheet->setCellValue('A'.$row, $no++);
                $sheet->setCellValue('B'.$row, $record['no_plg_cctv']);
                $sheet->setCellValue('C'.$row, $newDate);
                $sheet->setCellValue('D'.$row, substr($record['tgl_plg_cctv'], 11, 8));
                $sheet->setCellValue('E'.$row, $record['shift_plg_cctv']);
                $sheet->setCellValue('F'.$row, $record['divisi_name']);
                $sheet->setCellValue('G'.$row, $record['ctg_plg_cctv']);
                $sheet->setCellValue('H'.$row, $record['jns_plg_cctv']);
                $sheet->setCellValue('I'.$row, $record['dvr_plg_cctv']);
                $sheet->setCellValue('J'.$row, $record['lokasi_plg_cctv']);
                $sheet->setCellValue('K'.$row, strtoupper($record['username']));
                $sheet->setCellValue('L'.$row, $record['ket_plg_cctv']);
                $sheet->setCellValue('M'.$row, $record['name_fup_plg']);
                $sheet->setCellValue('N'.$row, $sts_fup);
                $sheet->setCellValue('O'.$row, $record['tersangka_plg_cctv'] == '' ? '-' : $record['tersangka_plg_cctv']);
                $sheet->setCellValue('P'.$row, $record['penjelasan_plg_cctv'] == '' ? '-' : $record['penjelasan_plg_cctv']);
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