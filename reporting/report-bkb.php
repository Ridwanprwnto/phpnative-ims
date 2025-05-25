<?php

require '../includes/config/timezone.php';
require '../includes/function/func.php';
require '../includes/config/conn.php';

/*call the FPDF library*/
require '../includes/config/mc_table.php';

if (session_status()!==PHP_SESSION_ACTIVE)session_start();

if (isset($_SESSION['PRINTBKB'])){
    $_POST = $_SESSION['PRINTBKB'];
    unset($_SESSION['PRINTBKB']);
}
else {
    $msg = encrypt("print-error");
    header("location: ../error.php?alert=$msg");
    exit();
}

$id = $_GET["no"];

if(isset($_GET["no"])) {
    if($_GET["no"] === $id) {
        $strplus = rplplus($id);
        $decid = mysqli_real_escape_string($conn, decrypt($strplus));
        if($decid == true) {
            $sql = "SELECT A.*, B.*, C.*, D.*, E.*, F.* FROM bkb AS A
            INNER JOIN office AS B ON A.office_bkb = B.id_office
            INNER JOIN department AS C ON A.dept_bkb = C.id_department
            INNER JOIN divisi AS D ON A.div_bkb = D.id_divisi
            INNER JOIN mastercategory AS E ON LEFT(A.pluid_bkb, 6) = E.IDBarang
            INNER JOIN masterjenis AS F ON RIGHT(A.pluid_bkb, 4) = F.IDJenis
            WHERE A.nomor_bkb = '$decid'";
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

$pdf->SetTitle('Bukti Keluar Barang Sarana Elektrikal');

$pdf->SetFont('Arial','B',10);
$pdf->Cell(190 ,5,'BUKTI KELUAR BARANG SARANA ELEKTRIKAL',0,1,'C');

$pdf->Line(10, 18, 200, 18);

$pdf->Ln(6);

$pdf->Cell(190 ,5,'Nomor : '.$header["nomor_bkb"],0,1,'C');

$pdf->Ln(4);

$pdf->SetFont('Arial','',10);
$pdf->Cell(190 ,5 ,'Menerangkan bahwa telah dikeluarkan barang sarana elektrikal dengan keterangan sebagai berikut :',0,1);

$pdf->Ln(2);

$office = $header["office_bkb"]." - ".strtoupper($header["department_name"]);
$divisi = $header["divisi_name"];
$tgl = $header["tgl_bkb"];
$desc = $header['NamaBarang']." ".$header['NamaJenis']." ".$header['merk_bkb']." ".$header['tipe_bkb'];
$sn = $header['sn_bkb'];
$at = $header['at_bkb'];
$no = $header['no_bkb'];
$tempat = $header['lokasi_bkb'];
$ket = $header['ket_bkb'];

$pdf->Cell(8 ,5,'',0,0);
$pdf->Cell(32 ,5 ,'Depertemen',0,0);
$pdf->Cell(160 ,5 ,': '.$office,0,1);

$pdf->Cell(8 ,5,'',0,0);
$pdf->Cell(32 ,5 ,'Divisi',0,0);
$pdf->Cell(160 ,5 ,': '.$divisi,0,1);

$pdf->Cell(8 ,5,'',0,0);
$pdf->Cell(32 ,5 ,'Tanggal',0,0);
$pdf->Cell(160 ,5 ,': '.$tgl,0,1);

$pdf->Ln(4);

$pdf->Cell(8 ,5,'',0,0);
$pdf->Cell(32 ,5 ,'Nama Barang',0,0);
$pdf->Cell(160 ,5 ,': '.$desc,0,1);

$pdf->Cell(8 ,5,'',0,0);
$pdf->Cell(32 ,5 ,'Serial Number',0,0);
$pdf->Cell(160 ,5 ,': '.$sn,0,1);

$pdf->Cell(8 ,5,'',0,0);
$pdf->Cell(32 ,5 ,'Nomor Aktiva',0,0);
$pdf->Cell(160 ,5 ,': '.$at,0,1);

$pdf->Cell(8 ,5,'',0,0);
$pdf->Cell(32 ,5 ,'Nomor Lambung',0,0);
$pdf->Cell(160 ,5 ,': '.$no,0,1);

$pdf->Cell(8 ,5,'',0,0);
$pdf->Cell(32 ,5 ,'Lokasi Penempatan',0,0);
$pdf->Cell(160 ,5 ,': '.$tempat,0,1);

$pdf->Cell(8 ,5,'',0,0);
$pdf->Cell(32 ,5 ,'Keterangan',0,0);
$pdf->MultiCell(160 ,5 ,': '.$ket,0,1);
$pdf->SetFont('Arial','',10);

$pdf->Ln(2);

$pdf->MultiCell(190 ,5 ,'Demikian Berita Acara ini dibuat dengan sebenarnya untuk dipergunakan sebagaimana mestinya.',0,1);

$pdf->Ln(6);

$pdf->Cell(48 ,8,'Diterima,',1,0,'C');
$pdf->Cell(94 ,8,'Diketahui,',1,0,'C');
$pdf->Cell(48 ,8,'Dibuat,',1,1,'C');
$pdf->Cell(48 ,28,'',1,0,'');
$pdf->Cell(94 ,28,'',1,0,'');
$pdf->Cell(48 ,28,'',1,1,'');
$pdf->Cell(48 ,8,'',1,0,'C');
$pdf->Cell(47 ,8,'DCM/DDCM',1,0,'C');
$pdf->Cell(47 ,8,'SPV ADM',1,0,'C');
$pdf->Cell(48 ,8,$header["user_bkb"],1,1,'C');

$pdf->Output("BA-".$header['nomor_bkb']."-".$header['tgl_bkb'].".pdf","I");

?>
       