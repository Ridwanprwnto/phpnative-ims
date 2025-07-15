<?php

use Fpdf\Fpdf;

require '../includes/config/timezone.php';
require '../includes/function/func.php';
require '../includes/config/conn.php';

/*call the FPDF library*/
require '../includes/config/mc_table.php';

if (session_status()!==PHP_SESSION_ACTIVE)session_start();

if (isset($_SESSION['PRINTPTBLK'])){
    $_POST = $_SESSION['PRINTPTBLK'];
    unset($_SESSION['PRINTPTBLK']);
}
else {
    $msg = encrypt("print-error");
    header("location: ../error.php?alert=$msg");
    exit();
}

$id = $_GET["tbno"];

if(isset($_GET["tbno"])) {
    if($_GET["tbno"] === $id) {
        $strplus = rplplus($id);
        $decid = mysqli_real_escape_string($conn, decrypt($strplus));
        if($decid == true) {
            $sql = "SELECT A.*, B.*, C.nik, C.username FROM st_dpd_head AS A
            INNER JOIN st_dpd_detail AS B ON A.id_st_dpd = B.id_st_dpd_head
            INNER JOIN users AS C ON A.req_st_dpd = C.nik
            WHERE A.id_st_dpd = '$decid'";
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

$pdf->SetTitle('Form Pengajuan Tablok Barang');

/*set font to arial, bold, 14pt*/
$pdf->SetFont('Arial','B',12);

/*Cell(width , height , text , border , end line , [align] )*/
$pdf->Cell(63 ,10,'',0,0);
$pdf->Cell(63 ,10,'FORM PENGAJUAN TABLOK',0,0, 'C');
$pdf->Cell(63 ,10,'',0,1);

$pdf->Line(10, 20, 200, 20);

$pdf->Ln(4);

$pdf->SetFont('Arial','',9);

$pdf->Cell(28 ,5,'No',0,0);
$pdf->Cell(162 ,5,': '.$header['id_st_dpd'],0,1);

$pdf->Cell(28 ,5,'Tanggal',0,0);
$pdf->Cell(162 ,5,': '.$header['date_st_dpd'],0,1);

$pdf->Ln(4);

$pdf->SetFont('Arial','B',8);
/*Heading Of the table*/
$pdf->Cell(10 ,8,'No',1,0,'C');
$pdf->Cell(50 ,8,'PLU - Desc',1,0,'C');
$pdf->Cell(15 ,8,'Item',1,0,'C');
$pdf->Cell(15 ,8,'Type',1,0,'C');
$pdf->Cell(10 ,8,'Ctn',1,0,'C');
$pdf->Cell(10 ,8,'Zona',1,0,'C');
$pdf->Cell(10 ,8,'Line',1,0,'C');
$pdf->Cell(10 ,8,'St',1,0,'C');
$pdf->Cell(10 ,8,'Rak',1,0,'C');
$pdf->Cell(10 ,8,'Shelf',1,0,'C');
$pdf->Cell(10 ,8,'Cell',1,0,'C');
$pdf->Cell(20 ,8,'IP DPD',1,0,'C');
$pdf->Cell(10 ,8,'ID',1,1,'C');
/*Heading Of the table end*/
$pdf->SetFont('Arial','',8);

$no = 1;
$query_d = mysqli_query($conn, $sql);
if(mysqli_num_rows($query_d) > 0 ) {

    $total = 0;
    while($data = mysqli_fetch_assoc($query_d)){

        $desc = $data['plu_st_dpd_detail']." - ".$data['nama_st_dpd_detail'];
        $item = $data['item_st_dpd_detail'];
        $type = $data['type_st_dpd_detail'];
        $ctn = $data['carton_st_dpd_detail'];
        $zona = $data['zona_st_dpd_detail'];
        $line = $data['line_st_dpd_detail'];
        $station = $data['station_st_dpd_detail'];
        $rak = $data['rak_st_dpd_detail'];
        $shelf = $data['shelf_st_dpd_detail'];
        $cell = $data['cell_st_dpd_detail'];
        $ip = $data['ip_st_dpd_detail'];
        $dpd = $data['dpd_st_dpd_detail'];
        
        $pdf->SetWidths(array(10, 50, 15, 15, 10, 10, 10, 10, 10, 10, 10, 20, 10));
        $pdf->Row(array($no++, $desc, $item, $type, $ctn, $zona, $line, $station, $rak, $shelf, $cell, $ip, $dpd));

    }
}

$pdf->Ln(5);

$pdf->SetFont('Arial','B',9);

$pdf->Cell(28 ,5,'Keterangan',0,0);
$pdf->Cell(48 ,5,':',0,0);
$pdf->Cell(114 ,5,'',0,1);

$pdf->Ln(2);

$pdf->SetFont('Arial','',9);

$xPos=$pdf->GetX();
$yPos=$pdf->GetY();

$cellWidth = 121;
$cellHeight = 5;

$pdf->MultiCell($cellWidth,$cellHeight,$header['ket_st_dpd'],0,1);

$pdf->SetFont('Arial','',9);

$pdf->Ln(6);

$pdf->Cell(55 ,8,'Diketahui',1,0,'C');
$pdf->Cell(135 ,8,'Disetujui',1,1,'C');
$pdf->Cell(55 ,28,'',1,0,'');
$pdf->Cell(45 ,28,'',1,0,'');
$pdf->Cell(45 ,28,'',1,0,'');
$pdf->Cell(45 ,28,'',1,1,'');
$pdf->Cell(55 ,8,'DCM/DDCM',1,0,'C');
$pdf->Cell(45 ,8,'SPV Admin',1,0,'C');
$pdf->Cell(45 ,8,'SPV Warehouse',1,0,'C');
$pdf->Cell(45 ,8,'SPV Receiving',1,1,'C');


$pdf->Output("FORM-PENGAJUAN-TABLOK-".$header['id_st_dpd']."-".date("d-m-Y").".pdf","I");

?>