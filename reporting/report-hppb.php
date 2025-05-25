<?php

require '../includes/config/timezone.php';
require '../includes/function/func.php';
require '../includes/config/conn.php';

/*call the FPDF library*/
require '../includes/config/mc_table.php';

if (session_status()!==PHP_SESSION_ACTIVE)session_start();

if (isset($_SESSION['PRINTBPHP3'])){
    $_POST = $_SESSION['PRINTBPHP3'];
    unset($_SESSION['PRINTBPHP3']);
}
else {
    $msg = encrypt("print-error");
    header("location: ../error.php?alert=$msg");
    exit();
}

$id = $_GET["noref"];

if(isset($_GET["noref"])) {
    if($_GET["noref"] === $id) {
        $strplus = rplplus($id);
        $decid = mysqli_real_escape_string($conn, decrypt($strplus));
        if($decid == true) {
            $sql = "SELECT A.*, B.*, C.*, D.*, E.*, F.username, G.* FROM detail_surat_jalan AS A
            INNER JOIN office AS B ON LEFT(A.from_sj, 4) = B.id_office
            INNER JOIN department AS C ON RIGHT(A.from_sj, 4) = C.id_department
            INNER JOIN mastercategory AS D ON LEFT(A.pluid_sj, 6) = D.IDBarang
            INNER JOIN masterjenis AS E ON RIGHT(A.pluid_sj, 4) = E.IDJenis
            INNER JOIN users AS F ON A.penerima_sj = F.nik
            INNER JOIN kondisi AS G ON A.kondisi_perbaikan = G.id_kondisi
            WHERE A.detail_no_sj = '$decid'";
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

$pdf->SetTitle('Report Bukti HPPB');

$pdf->SetFont('Arial','B',12);
$pdf->Cell(190 ,5,'BUKTI PENERIMAAN HASIL PEMERIKSAAN PERBAIKAN BARANG',0,1,'C');

$pdf->Ln(2);

$pdf->SetFont('Arial','',10);

$pdf->Cell(190 ,5,'Noref SJ : '. $nomor = substr($header["head_no_sj"], 1),0,1,'C');

$pdf->Line(10, 24, 200, 24);

$pdf->Ln(4);

$pdf->SetFont('Arial','',10);
$pdf->MultiCell(190 ,5 ,'Menerangkan bahwa telah dilakukan penerimaan peralatan sarana elektrikal atas perbaikan dengan keterangan sebagai berikut :',0);

$pdf->Ln(2);

$office = substr($header["from_sj"], 0, 4)." - ".strtoupper($header["department_name"]);
$tgl = $header["tgl_penerimaan"];
$desc = $header['NamaBarang']." ".$header['NamaJenis']." ".$header['merk_sj']." ".$header['tipe_sj'];
$sn = $header['sn_sj'];
$at = $header['at_sj'];

$kondisi = $header['kondisi_name'];
$keterangan = $header['ket_penerimaan_sj'];

$pdf->Cell(8 ,5,'',0,0);
$pdf->Cell(30 ,5 ,'Depertemen',0,0);
$pdf->Cell(2 ,5,':',0,0);
$pdf->Cell(150 ,5 ,$office,0,1);

$pdf->Cell(8 ,5,'',0,0);
$pdf->Cell(30 ,5 ,'Tanggal Terima',0,0);
$pdf->Cell(2 ,5,':',0,0);
$pdf->Cell(150 ,5 ,$tgl,0,1);

$pdf->Ln(4);

$pdf->Cell(8 ,5,'',0,0);
$pdf->Cell(30 ,5 ,'Nama Barang',0,0);
$pdf->Cell(2 ,5,':',0,0);
$pdf->Cell(150 ,5 ,$desc,0,1);

$pdf->Cell(8 ,5,'',0,0);
$pdf->Cell(30 ,5 ,'Serial Number',0,0);
$pdf->Cell(2 ,5,':',0,0);
$pdf->Cell(150 ,5 ,$sn,0,1);

$pdf->Cell(8 ,5,'',0,0);
$pdf->Cell(30 ,5 ,'Nomor Aktiva',0,0);
$pdf->Cell(2 ,5,':',0,0);
$pdf->Cell(150 ,5 ,$at,0,1);

$pdf->Ln(2);

$pdf->MultiCell(190 ,5 ,'Untuk peralatan tersebut telah diterima dengan kondisi :',0);

$pdf->Ln(2);

$pdf->Cell(8 ,5,'',0,0);
$pdf->Cell(30 ,5 ,'Status',0,0);
$pdf->Cell(2 ,5,':',0,0);

$pdf->SetFont('Arial','B',10);
$pdf->MultiCell(150 ,5 ,$kondisi,0,1);

$pdf->SetFont('Arial','',10);

$pdf->Cell(8 ,5,'',0,0);
$pdf->Cell(30 ,5 ,'Keterangan',0,0);
$pdf->Cell(2 ,5,':',0,0);

$pdf->MultiCell(150 ,5 ,$keterangan,0,1);

$pdf->Ln(2);

$pdf->MultiCell(190 ,5 ,'Demikian Berita Acara ini dibuat dengan sebenarnya untuk dipergunakan sebagaimana mestinya.',0,1);

$pdf->Ln(6);

$pdf->Cell(120 ,8,'Mengetahui,',1,0,'C');
$pdf->Cell(70 ,8,'Penerima,',1,1,'C');
$pdf->Cell(120 ,28,'',1,0,'');
$pdf->Cell(70 ,28,'',1,1,'');
$pdf->Cell(120 ,8,'DDCM / SPV ADM',1,0,'C');
$pdf->Cell(70 ,8,$header["penerima_sj"]." - ".strtoupper($header["username"]),1,1,'C');

$pdf->Output("BPHP3-".$nomor."-".$header['sn_sj'].".pdf","I");

?>
       