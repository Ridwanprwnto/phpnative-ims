<?php

require '../includes/config/timezone.php';
require '../includes/function/func.php';
require '../includes/config/conn.php';

/*call the FPDF library*/
require '../includes/config/mc_table.php';

if (session_status()!==PHP_SESSION_ACTIVE)session_start();

if (isset($_SESSION['PRINT-ASSESSMENT'])){
    $_POST = $_SESSION['PRINT-ASSESSMENT'];
    unset($_SESSION['PRINT-ASSESSMENT']);
}
else {
    $msg = encrypt("print-error");
    header("location: ../error.php?alert=$msg");
    exit();
}

$id = $_GET["docno"];

if(isset($_GET["docno"])) {
    if($_GET["docno"] === $id) {
        $strplus = rplplus($id);
        $decid = mysqli_real_escape_string($conn, decrypt($strplus));
        if($decid == true) {
            $sql = "SELECT A.*, B.*, C.office_name, D.department_name, E.divisi_name, F.level_name, G.full_name AS leader_name, H.full_name AS junior_name FROM data_assessment AS A 
            INNER JOIN sub_data_assessment AS B ON A.docno_data_assest = B.head_docno_data_assest
            INNER JOIN office AS C ON A.office_data_assest = C.id_office
            INNER JOIN department AS D ON A.dept_data_assest = D.id_department
            INNER JOIN divisi AS E ON A.div_data_assest = E.id_divisi
            INNER JOIN level AS F ON A.lvl_data_assest = F.id_level
            INNER JOIN users AS G ON A.leader_data_assest = G.nik
            INNER JOIN users AS H ON A.junior_data_assest = H.nik
            WHERE A.docno_data_assest = '$decid'";
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

/*A4 width : 219mm*/

$pdf = new PDF_MC_Table('P','mm','A4');
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetTitle('Laporan Evaluasi Penilaian Tahunan');

/*output the result*/

$start_x = $pdf->GetX(); //initial x (start of column position)
$current_y = $pdf->GetY();
$current_x = $pdf->GetX();

/*set font to arial, bold, 14pt*/
$pdf->SetFont('Arial','B',12);

/*Cell(width , height , text , border , end line , [align] )*/
$pdf->Cell(63 ,10,'',0,0);
$pdf->Cell(63 ,10,'LAPORAN EVALUASI PENILAIAN TAHUNAN',0,0, 'C');
$pdf->Cell(63 ,10,'',0,1);

$pdf->Line(10, 24, 200, 24);

$pdf->Ln(8);

$pdf->SetFont('Arial','B',10);

$status = $header['status_data_assest'] == 'N' ? 'DRAFT' : 'FINAL';

$pdf->Cell(28 ,5,'DOCNO',0,0,'L');
$pdf->Cell(162 ,5,': '.$header['docno_data_assest'],0,1,'L');
$pdf->Cell(28 ,5,'TAHUN',0,0,'L');
$pdf->Cell(162 ,5,': '.$header['th_data_assest'],0,1,'L');
$pdf->Cell(28 ,5,'ASSESSED',0,0,'L');
$pdf->Cell(162 ,5,': '.$header['leader_data_assest']." - ".strtoupper($header['leader_name']),0,1,'L');
$pdf->Cell(28 ,5,'TANGGAL',0,0,'L');
$pdf->Cell(162 ,5,': '.$header['date_data_assest'],0,1,'L');
$pdf->Cell(28 ,5,'STATUS',0,0,'L');
$pdf->Cell(162 ,5,': '.$status,0,1,'L');

$pdf->Ln(4);

$pdf->SetFont('Arial','',10);

$pdf->Cell(28 ,5,'Kantor',0,0);
$pdf->Cell(48 ,5,': '.$header['office_data_assest']." - ".strtoupper($header['office_name']),0,0);
$pdf->Cell(38 ,5,'',0,0);
$pdf->Cell(28 ,5,'NIK',0,0);
$pdf->Cell(48 ,5,': '.$header['junior_data_assest'], 0, 1);

$pdf->Cell(28 ,5,'Departemen',0,0);
$pdf->Cell(48 ,5,': '.strtoupper($header['department_name']),0,0);
$pdf->Cell(38 ,5,'',0,0);
$pdf->Cell(28 ,5,'Nama',0,0);
$pdf->Cell(48 ,5,': '.$header['junior_name'], 0, 1);

$pdf->Cell(28 ,5,'Divisi',0,0);
$pdf->Cell(48 ,5,': '.strtoupper($header['divisi_name']),0,0);
$pdf->Cell(38 ,5,'',0,0);
$pdf->Cell(28 ,5,'Jabatan',0,0);
$pdf->Cell(48 ,5,': '.strtoupper($header['level_name']), 0, 1);

$pdf->Ln(5);

$pdf->SetFont('Arial','B',10);
/*Heading Of the table*/
$pdf->Cell(10 ,8,'No',1,0,'C');
$pdf->Cell(100 ,8,'Indikator Penilaian',1,0,'C');
$pdf->Cell(40 ,8,'Poin',1,0,'C');
$pdf->Cell(40 ,8,'Grade',1,1,'C');/*end of line*/
/*Heading Of the table end*/
$pdf->SetFont('Arial','',10);

$no = 1;
$query_d = mysqli_query($conn, $sql);
if(mysqli_num_rows($query_d) > 0 ) {

    $jmlIndikator = 0;
    $totalGrade = 0;
    $totalPoin = 0;
    while($data = mysqli_fetch_assoc($query_d)){

        $indikator = $data['indikator_sub_data_assest'];
        $poin = $data['poin_sub_data_assest'];
        $grade = $data['grade_sub_data_assest'];
        
        $pdf->SetWidths(array(10, 100, 40, 40));
        $pdf->Row(array($no++, $indikator, $poin, $grade));

        $totalGrade += $grade;
        $totalPoin += $poin;
        $jmlIndikator++;
    }

}

$pdf->SetFont('Arial','B',10);
$pdf->Cell(110 ,8,'Rata-rata :',1,0,'C');
$pdf->Cell(40 ,8,number_format(($totalPoin / $jmlIndikator),2),1,0,'L');
$pdf->Cell(40 ,8,number_format(($totalGrade / $jmlIndikator),1),1,1,'L');

$pdf->Ln(5);

$pdf->SetFont('Arial','B',10);

$pdf->Cell(190 ,5,'Catatan :',0,1);

$pdf->SetFont('Arial','',9);

$pdf->SetWidths(array(190));
$pdf->Row(array($header['note_data_assest']));

$pdf->SetFont('Arial','BI',8);

$pdf->Ln(5);

$pdf->Cell(190 ,8,'*Dokumen ini sifatnya konfidensial, harap musnahkan jika sudah tidak diperlukan! ',0,1,'R');


$pdf->Output("LAPORAN-PENILAIAN-".$header['docno_data_assest']."-".date("d-m-Y").".pdf","I");

?>
       