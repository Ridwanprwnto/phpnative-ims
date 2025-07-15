<?php
require '../vendor/autoload.php';

require '../includes/config/timezone.php';
require '../includes/function/func.php';
require '../includes/function/tag.php';
require '../includes/config/conn.php';

/*call the FPDF library*/
require '../includes/config/mc_table.php';

/*call the EXCELL library*/
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$user = mysqli_real_escape_string($conn, $_POST["user-cetak"]);
$office = mysqli_real_escape_string($conn, $_POST["office-cetak"]);
$dept = mysqli_real_escape_string($conn, $_POST["dept-cetak"]);
$tahun = mysqli_real_escape_string($conn, $_POST["tahun-cetak"]);
$divisi = mysqli_real_escape_string($conn, $_POST["divisi-cetak"]);

$iddiv = substr($divisi, 0, 4);
$namediv = substr($divisi, 7);

$arrdiv = array();

$query_headiv = mysqli_query($conn, "SELECT id_divisi FROM divisi WHERE id_head_divisi = '$iddiv'");
if(mysqli_num_rows($query_headiv) > 0 ) {
    while($data_headiv = mysqli_fetch_assoc($query_headiv)){
        $arrdiv[] = isset($data_headiv["id_divisi"]) ? "'".$data_headiv["id_divisi"]."'" : NULL;
    }
}

$strdiv = implode(", ", $arrdiv);

$sql = "SELECT A.*, B.office_name, C.department_name, E.*, F.divisi_name, G.full_name AS leader_name, H.full_name AS junior_name, I.docno_data_assest, I.date_data_assest, I.poin_data_assest, I.mutu_data_assest, I.avg_data_assest, I.note_data_assest, I.status_data_assest FROM statusassessment AS A
INNER JOIN office AS B ON A.office_sts_assest = B.id_office
INNER JOIN department AS C ON A.dept_sts_assest = C.id_department
INNER JOIN divisi_assessment AS D ON A.code_sts_assest = D.head_code_sts_assest
INNER JOIN leader_assessment AS E ON A.code_sts_assest = E.head_code_sts_assest
INNER JOIN divisi AS F ON D.head_id_divisi = F.id_divisi
INNER JOIN users AS G ON E.officer_leader_assest = G.nik
INNER JOIN users AS H ON E.junior_leader_assest = H.nik
LEFT JOIN data_assessment AS I 
ON E.officer_leader_assest = I.leader_data_assest
AND E.junior_leader_assest = I.junior_data_assest
AND E.head_code_sts_assest = I.head_code_sts_assest
WHERE A.office_sts_assest = '$office' AND A.dept_sts_assest = '$dept' AND A.tahun_sts_assest = '$tahun' AND E.div_leader_assest IN ($strdiv) GROUP BY E.id_leader_assest ORDER BY E.junior_leader_assest ASC";

$query_h = mysqli_query($conn, $sql);
$header = mysqli_fetch_assoc($query_h);

$title = $office." - LAPORAN PENILAIAN TAHUN ".$tahun;

if (isset($_POST["printexcell"])) {

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // sheet peratama
    $sheet->setTitle('Sheet 1');

    $sheet->getStyle('A1:U2')->getFont()->setBold(true);
    $sheet->mergeCells('A1:A2');
    $sheet->mergeCells('B1:B2');
    $sheet->mergeCells('C1:C2');
    $sheet->mergeCells('D1:D2');
    $sheet->mergeCells('E1:Q1');
    $sheet->mergeCells('R1:R2');
    $sheet->mergeCells('S1:S2');
    $sheet->mergeCells('T1:T2');
    $sheet->mergeCells('U1:U2');

    $sheet->setCellValue('A1', 'NO');
    $sheet->setCellValue('B1', 'OFFICER');
    $sheet->setCellValue('C1', 'JUNIOR');
    $sheet->setCellValue('D1', 'DOCNO');
    $sheet->setCellValue('E1', 'INDIKATOR PENILAIAN');
    $sheet->setCellValue('E2', '1');
    $sheet->setCellValue('F2', '2');
    $sheet->setCellValue('G2', '3');
    $sheet->setCellValue('H2', '4');
    $sheet->setCellValue('I2', '5');
    $sheet->setCellValue('J2', '6');
    $sheet->setCellValue('K2', '7');
    $sheet->setCellValue('L2', '8');
    $sheet->setCellValue('M2', '9');
    $sheet->setCellValue('N2', '10');
    $sheet->setCellValue('O2', '11');
    $sheet->setCellValue('P2', '12');
    $sheet->setCellValue('Q2', '13');
    $sheet->setCellValue('R1', 'POIN');
    $sheet->setCellValue('S1', 'AVERAGE');
    $sheet->setCellValue('T1', 'GRADE');
    $sheet->setCellValue('U1', 'STATUS');

    $row_xcl = 3;
    $no = 1;

    if (isset($office) && isset($dept) && isset($tahun) && isset($divisi)) {
    
        $query_xcl = mysqli_query($conn, $sql);
        if(mysqli_num_rows($query_xcl) > 0 ) {
    
            while($data_xcl = mysqli_fetch_assoc($query_xcl)){

                $docno_lapor = isset($data_xcl["docno_data_assest"]) ? $data_xcl["docno_data_assest"] : NULL;

                $poin1 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT poin_sub_data_assest AS poin_1 FROM sub_data_assessment WHERE head_docno_data_assest = '$docno_lapor' AND LEFT(indikator_sub_data_assest, 1) = '1'"));
                $poin2 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT poin_sub_data_assest AS poin_2 FROM sub_data_assessment WHERE head_docno_data_assest = '$docno_lapor' AND LEFT(indikator_sub_data_assest, 1) = '2'"));
                $poin3 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT poin_sub_data_assest AS poin_3 FROM sub_data_assessment WHERE head_docno_data_assest = '$docno_lapor' AND LEFT(indikator_sub_data_assest, 1) = '3'"));
                $poin4 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT poin_sub_data_assest AS poin_4 FROM sub_data_assessment WHERE head_docno_data_assest = '$docno_lapor' AND LEFT(indikator_sub_data_assest, 1) = '4'"));
                $poin5 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT poin_sub_data_assest AS poin_5 FROM sub_data_assessment WHERE head_docno_data_assest = '$docno_lapor' AND LEFT(indikator_sub_data_assest, 1) = '5'"));
                $poin6 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT poin_sub_data_assest AS poin_6 FROM sub_data_assessment WHERE head_docno_data_assest = '$docno_lapor' AND LEFT(indikator_sub_data_assest, 1) = '6'"));
                $poin7 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT poin_sub_data_assest AS poin_7 FROM sub_data_assessment WHERE head_docno_data_assest = '$docno_lapor' AND LEFT(indikator_sub_data_assest, 1) = '7'"));
                $poin8 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT poin_sub_data_assest AS poin_8 FROM sub_data_assessment WHERE head_docno_data_assest = '$docno_lapor' AND LEFT(indikator_sub_data_assest, 1) = '8'"));
                $poin9 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT poin_sub_data_assest AS poin_9 FROM sub_data_assessment WHERE head_docno_data_assest = '$docno_lapor' AND LEFT(indikator_sub_data_assest, 1) = '9'"));
                $poin10 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT poin_sub_data_assest AS poin_10 FROM sub_data_assessment WHERE head_docno_data_assest = '$docno_lapor' AND LEFT(indikator_sub_data_assest, 2) = '10'"));
                $poin11 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT poin_sub_data_assest AS poin_11 FROM sub_data_assessment WHERE head_docno_data_assest = '$docno_lapor' AND LEFT(indikator_sub_data_assest, 2) = '11'"));
                $poin12 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT poin_sub_data_assest AS poin_12 FROM sub_data_assessment WHERE head_docno_data_assest = '$docno_lapor' AND LEFT(indikator_sub_data_assest, 2) = '12'"));
                $poin13 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT poin_sub_data_assest AS poin_13 FROM sub_data_assessment WHERE head_docno_data_assest = '$docno_lapor' AND LEFT(indikator_sub_data_assest, 2) = '13'"));

                $data_poin1 = isset($poin1["poin_1"]) ? $poin1["poin_1"] : "-";
                $data_poin2 = isset($poin2["poin_2"]) ? $poin2["poin_2"] : "-";
                $data_poin3 = isset($poin3["poin_3"]) ? $poin3["poin_3"] : "-";
                $data_poin4 = isset($poin4["poin_4"]) ? $poin4["poin_4"] : "-";
                $data_poin5 = isset($poin5["poin_5"]) ? $poin5["poin_5"] : "-";
                $data_poin6 = isset($poin6["poin_6"]) ? $poin6["poin_6"] : "-";
                $data_poin7 = isset($poin7["poin_7"]) ? $poin7["poin_7"] : "-";
                $data_poin8 = isset($poin8["poin_8"]) ? $poin8["poin_8"] : "-";
                $data_poin9 = isset($poin9["poin_9"]) ? $poin9["poin_9"] : "-";
                $data_poin10 = isset($poin10["poin_10"]) ? $poin10["poin_10"] : "-";
                $data_poin11 = isset($poin11["poin_11"]) ? $poin11["poin_11"] : "-";
                $data_poin12 = isset($poin12["poin_12"]) ? $poin12["poin_12"] : "-";
                $data_poin13 = isset($poin13["poin_13"]) ? $poin13["poin_13"] : "-";

                $sheet->setCellValue('A'.$row_xcl, $no++);
                $sheet->setCellValue('B'.$row_xcl, $data_xcl["officer_leader_assest"]." - ".strtoupper($data_xcl["leader_name"]));
                $sheet->setCellValue('C'.$row_xcl, $data_xcl["junior_leader_assest"]." - ".strtoupper($data_xcl["junior_name"]));
                $sheet->setCellValue('D'.$row_xcl, isset($data_xcl["docno_data_assest"]) ? $data_xcl["docno_data_assest"] : "-");
                $sheet->setCellValue('E'.$row_xcl, $data_poin1);
                $sheet->setCellValue('F'.$row_xcl, $data_poin2);
                $sheet->setCellValue('G'.$row_xcl, $data_poin3);
                $sheet->setCellValue('H'.$row_xcl, $data_poin4);
                $sheet->setCellValue('I'.$row_xcl, $data_poin5);
                $sheet->setCellValue('J'.$row_xcl, $data_poin6);
                $sheet->setCellValue('K'.$row_xcl, $data_poin7);
                $sheet->setCellValue('L'.$row_xcl, $data_poin8);
                $sheet->setCellValue('M'.$row_xcl, $data_poin9);
                $sheet->setCellValue('N'.$row_xcl, $data_poin10);
                $sheet->setCellValue('O'.$row_xcl, $data_poin11);
                $sheet->setCellValue('P'.$row_xcl, $data_poin12);
                $sheet->setCellValue('Q'.$row_xcl, $data_poin13);
                $sheet->setCellValue('R'.$row_xcl, isset($data_xcl["poin_data_assest"]) ? $data_xcl["poin_data_assest"] : "-");
                $sheet->setCellValue('S'.$row_xcl, isset($data_xcl["avg_data_assest"]) ? $data_xcl["avg_data_assest"] : "-");
                $sheet->setCellValue('T'.$row_xcl, isset($data_xcl["mutu_data_assest"]) ? $data_xcl["mutu_data_assest"] : "-");
                $sheet->setCellValue('U'.$row_xcl, !isset($data_xcl["status_data_assest"]) ? "-" : ($data_xcl["status_data_assest"] == "N" ? "DRAFT" : "FINAL"));
                $row_xcl++;
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
elseif (isset($_POST["printpdf"])) {

    class PDF extends PDF_MC_Table {
        // Page header
        function Header()
        {
            
            global $header;
            global $user;
            global $tahun;
            global $namediv;
    
            $this->SetFont('Arial','',10);
    
            $this->Cell(28 ,5,'Office',0,0);
            $this->Cell(48 ,5,': '.$header['office_sts_assest']." - ".$header['office_name'],0,0);
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
            
            $this->Cell(190, 8, 'LAPORAN PENILAIAN TAHUNAN', 0, 1, 'C');
            
            $this->SetFont('Arial','',10);

            $this->Cell(190, 6, 'Tahun : '.$tahun, 0, 1, 'C');
            $this->Cell(190, 6, 'Divisi : '.$namediv, 0, 1, 'C');
            
            $this->Ln(2);
            
            $this->SetFont('Arial','B',8);
            /*Heading Of the table*/
            $this->Cell(10 ,8,'NO',1,0,'C');
            $this->Cell(40 ,8,'OFFICER',1,0,'C');
            $this->Cell(40 ,8,'JUNIOR',1,0,'C');
            $this->Cell(20 ,8,'DOCNO',1,0,'C');
            $this->Cell(20 ,8,'POIN',1,0,'C');
            $this->Cell(20 ,8,'AVERAGE',1,0,'C');
            $this->Cell(20 ,8,'GRADE',1,0,'C');
            $this->Cell(20 ,8,'STATUS',1,1,'C');
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
    $pdf->SetTitle('Report Penilaian Tahunan');
    $pdf->SetSubject('Assestment');
    $pdf->SetKeywords('IMS');
    $pdf->SetCreator('IMS');
    
    $start_x = $pdf->GetX(); //initial x (start of column position)
    $current_y = $pdf->GetY();
    $current_x = $pdf->GetX();
    
    $pdf->SetFont('Arial','',8);
    
    $no = 1;
    if (isset($office) && isset($dept) && isset($tahun) && isset($divisi)) {
    
        $query_pdf = mysqli_query($conn, $sql);
        if(mysqli_num_rows($query_pdf) > 0 ) {
    
            while($data_pdf = mysqli_fetch_assoc($query_pdf)){
    
                $senior = $data_pdf['officer_leader_assest']." - ".strtoupper($data_pdf["leader_name"]);
                $junior = $data_pdf['junior_leader_assest']." - ".strtoupper($data_pdf["junior_name"]);
                $docno = isset($data_pdf["docno_data_assest"]) ? $data_pdf["docno_data_assest"] : "-";
                $poin = isset($data_pdf["poin_data_assest"]) ? $data_pdf["poin_data_assest"] : "-";
                $avg = isset($data_pdf["avg_data_assest"]) ? $data_pdf["avg_data_assest"] : "-";
                $grade = isset($data_pdf["mutu_data_assest"]) ? $data_pdf["mutu_data_assest"] : "-";
                $status = !isset($data_pdf["status_data_assest"]) ? "-" : ($data_pdf["status_data_assest"] == "N" ? "DRAFT" : "FINAL");

                $pdf->SetWidths(array(10, 40, 40, 20, 20, 20, 20, 20));
                $pdf->Row(array($no++, $senior, $junior, $docno, $poin, $avg, $grade, $status));
    
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
else {
    $msg = encrypt("print-error");
    header("location: ../error.php?alert=$msg");
    exit();
}
?>