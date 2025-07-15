<?php

require '../includes/config/timezone.php';
require '../includes/function/func.php';
require '../includes/config/conn.php';

/*call the FPDF library*/
require '../includes/config/mc_table.php';

if (session_status()!==PHP_SESSION_ACTIVE)session_start();

if (isset($_SESSION['PRINTSJK'])){
    $_POST = $_SESSION['PRINTSJK'];
    unset($_SESSION['PRINTSJK']);
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
            $sql = "SELECT A.*, B.*, C.*, D.*, E.*, F.*, G.*, H.id_office AS id_officeto, H.office_name AS name_officeto, H.office_address AS adrs_officeto, H.office_poscode AS poscode_officeto, I.department_name AS deptto FROM surat_jalan AS A 
            INNER JOIN detail_surat_jalan AS B ON A.no_sj = B.head_no_sj
            INNER JOIN office AS C ON LEFT(A.asal_sj, 4) = C.id_office
            INNER JOIN department AS D ON RIGHT(A.asal_sj, 4) = D.id_department
            LEFT JOIN users AS E ON A.user_sj = E.nik
            INNER JOIN mastercategory AS F ON LEFT(B.pluid_sj, 6) = F.IDBarang
            INNER JOIN masterjenis AS G ON RIGHT(B.pluid_sj, 4) = G.IDJenis
            INNER JOIN office AS H ON LEFT(A.tujuan_sj, 4) = H.id_office
            INNER JOIN department AS I ON RIGHT(A.tujuan_sj, 4) = I.id_department
            WHERE A.no_sj = '$decid'";
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

$pdf->SetTitle('Surat Jalan Barang Keluar');

/*output the result*/

// $company = "PT. INDOMARCO PRISMATAMA";

// $pdf->SetFont('Arial','B',10);
// $pdf->Cell(30 ,10,'',0,1);

// $address = "Jl. Alternatif Sentul KM 46, Kel. Cijujung, Kec. Sukaraja, Kabupaten Bogor";

// $pdf->Ln(14);

/*set font to arial, bold, 14pt*/
$pdf->SetFont('Arial','B',12);

/*Cell(width , height , text , border , end line , [align] )*/
$pdf->Cell(63 ,10,'',0,0);
$pdf->Cell(63 ,10,'SURAT JALAN',0,0, 'C');
$pdf->Cell(63 ,10,'',0,1);

$pdf->Ln(2);

$pdf->SetFont('Arial','',9);

$pdf->Cell(20 ,5,'Nomor',0,0,'');
$pdf->Cell(170 ,5,': '.substr($header['no_sj'], 1, 5),0,1,'');
$pdf->Cell(20 ,5,'Proses Date',0,0,'');
$pdf->Cell(170 ,5,': '.$header['tanggal_sj'],0,1,'');

$pdf->Ln(6);

$pdf->Line(10, 35, 200, 35);

$pdf->Cell(20 ,5,'From',0,0);
$pdf->Cell(75 ,5,': '.$header['id_office']." - ".$header['office_name']." ",0,0);
$pdf->Cell(20 ,5,'To',0,0);
$pdf->Cell(75 ,5,': '.$header['id_officeto']." - ".$header['name_officeto']." ", 0, 1);

$pdf->Cell(20 ,5,'Dept Asal',0,0);
$pdf->Cell(75 ,5,': '.$header['department_name'],0,0);
$pdf->Cell(20 ,5,'Dept Tujuan',0,0);
$pdf->Cell(75 ,5,': '.$header['deptto'], 0, 1);

$pdf->Cell(20 ,5,'Alamat',0,0);

$x = $pdf->GetX();
$y = $pdf->GetY();
$pdf->MultiCell(75 ,5,': '.$header['office_address']." ".$header['office_poscode'],0,1);
$pdf->SetXY($x + 75, $y);
$pdf->Cell(20 ,5,'Alamat',0,0);
$pdf->MultiCell(75 ,5,': '.$header['adrs_officeto']." ".$header['poscode_officeto'],0,1);

$pdf->Ln(5);

$pdf->SetFont('Arial','B',9);
/*Heading Of the table*/
$pdf->Cell(8 ,8,'No',1,0,'C');
$pdf->Cell(62 ,8,'Nama Barang',1,0,'C');
$pdf->Cell(35 ,8,'Serial Number',1,0,'C');
$pdf->Cell(23 ,8,'No Aktiva',1,0,'C');
$pdf->Cell(12 ,8,'Qty',1,0,'C');
$pdf->Cell(50 ,8,'Keterangan',1,1,'C');/*end of line*/
/*Heading Of the table end*/
$pdf->SetFont('Arial','',9);

$no = 1;
$query_d = mysqli_query($conn, $sql);
if(mysqli_num_rows($query_d) > 0 ) {

    $total = 0;
    while($data = mysqli_fetch_assoc($query_d)){

        $desc = $data['NamaBarang']." ".$data['NamaJenis']." ".$data['merk_sj']." ".$data['tipe_sj'];
        $sn = $data['sn_sj'] == '' ? '-' : $data["sn_sj"];
        $at = $data['at_sj'] == '' ? '-' : $data["at_sj"];
        $qty = $data["qty_sj"];
        $ket = $data["keterangan_sj"] == '-' ? '' : $data["keterangan_sj"];
        
        $pdf->SetWidths(array(8, 62, 35, 23, 12, 50));
        $pdf->Row(array($no++, $desc, $sn, $at, $qty, $ket));
        
    }

}

$pdf->Ln(5);

$pdf->SetFont('Arial','B',9);

$pdf->Cell(20 ,5,'Keterangan',0,0);
$pdf->Cell(170 ,5,':',0,1);

$pdf->Ln(2);

$pdf->SetFont('Arial','',9);

$pdf->MultiCell(127,5,$header['ket_sj'],0,1);

// $pdf->SetXY($xPos + $cellWidth , $yPos);

$pdf->SetFont('Arial','',9);

$pdf->Cell(126 ,8,'',0,0,'');
$pdf->Cell(64 ,8,$header['office_city'].', '.date("d F Y"),0,1,'R');

$pdf->Ln(5);

$pdf->Cell(38 ,8,'Penerima,',1,0,'C');
$pdf->Cell(38 ,8,'Pengirim,',1,0,'C');
$pdf->Cell(76 ,8,'Disetujui,',1,0,'C');
$pdf->Cell(38 ,8,'Dibuat,',1,1,'C');
$pdf->Cell(38 ,28,'',1,0,'');
$pdf->Cell(38 ,28,'',1,0,'');
$pdf->Cell(38 ,28,'',1,0,'');
$pdf->Cell(38 ,28,'',1,0,'');
$pdf->Cell(38 ,28,'',1,1,'');
$pdf->Cell(38 ,8,'',1,0,'');
$pdf->Cell(38 ,8,'',1,0,'');
$pdf->Cell(38 ,8,'MGR. Department',1,0,'C');
$pdf->Cell(38 ,8,'SPV. Department',1,0,'C');
$pdf->Cell(38 ,8,'',1,1,'C');


$pdf->Output("FORM-SJK-".$header['no_sj']."-".date("d-m-Y").".pdf","I");

?>
       