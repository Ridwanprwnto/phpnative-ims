<?php

require '../includes/config/timezone.php';
require '../includes/function/func.php';
require '../includes/config/conn.php';

/*call the FPDF library*/
require '../vendor/fpdf/fpdf.php';

if (session_status()!==PHP_SESSION_ACTIVE)session_start();

$username = $_SESSION["user_name"];

if (isset($_SESSION['PRINTBASO'])){
    $_POST = $_SESSION['PRINTBASO'];
    unset($_SESSION['PRINTBASO']);
}
else {
    $msg = encrypt("print-error");
    header("location: ../error.php?alert=$msg");
    exit();
}

$id = $_GET["baso"];

if(isset($_GET["baso"])) {
    if($_GET["baso"] === $id) {
        $strplus = rplplus($id);
        $decid = mysqli_real_escape_string($conn, decrypt($strplus));
        if($decid == true) {
            $sql = "SELECT A.*, B.id_office, B.office_name, B.office_city, C.id_department, C.department_name, D.username, E.*, F.NamaBarang, G.NamaJenis, H.nama_satuan, COUNT(I.pluid_so_asset) AS total FROM head_stock_opname AS A 
            INNER JOIN office AS B ON A.office_so = B.id_office
            INNER JOIN department AS C ON A.dept_so = C.id_department
            INNER JOIN users AS D ON A.user_so = D.nik
            INNER JOIN detail_stock_opname AS E ON A.no_so = E.no_so_head 
            INNER JOIN mastercategory AS F ON LEFT(E.pluid_so, 6) = F.IDBarang
            INNER JOIN masterjenis AS G ON RIGHT(E.pluid_so, 4) = G.IDJenis
            INNER JOIN satuan AS H ON F.id_satuan = H.id_satuan
            INNER JOIN asset_stock_opname AS I ON E.pluid_so = I.pluid_so_asset
            WHERE A.no_so = '$decid' GROUP BY E.pluid_so";
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

$pdf = new FPDF('P','mm','A4');
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetTitle('Laporan Bukti Adjust Hasil SO');

/*output the result*/

$pdf->SetFont('Arial','',8);

$pdf->Cell(28 ,5,'Kantor',0,0);
$pdf->Cell(48 ,5,': '.$header['office_so']." - ".$header['office_name'],0,0);
$pdf->Cell(38 ,5,'',0,0);
$pdf->Cell(28 ,5,'Tanggal SO',0,0);
$pdf->Cell(48 ,5,': '.$header['tgl_so'], 0, 1);

$pdf->Cell(28 ,5,'Department',0,0);
$pdf->Cell(48 ,5,': '.$header['department_name'],0,0);
$pdf->Cell(38 ,5,'',0,0);
$pdf->Cell(28 ,5,'Tanggal Cetak',0,0);
$pdf->Cell(48 ,5,': '.date("d-m-Y H:i:s"), 0, 1);

$pdf->Cell(28 ,5,'Petugas',0,0);
$pdf->Cell(48 ,5,': '.$header['username'],0,0);
$pdf->Cell(38 ,5,'',0,0);
$pdf->Cell(28 ,5,'User Print',0,0);
$pdf->Cell(48 ,5,': '.$username, 0, 1);

$pdf->Ln(4);

/*set font to arial, bold, 14pt*/
$pdf->SetFont('Arial','B',12);

/*Cell(width , height , text , border , end line , [align] )*/
$pdf->Cell(190 ,8,'BERITA ACARA STOCK OPNAME BARANG INVENTARIS',0,1, 'C');

$pdf->SetFont('Arial','B',8);

$pdf->Cell(190 ,5,'NO REF SO : '.$header['no_so'],0,1,'C');

$pdf->Ln(2);

$pdf->SetFont('Arial','B',8);
/*Heading Of the table*/
$pdf->Cell(10 ,16,'No',1,0,'C');
$pdf->Cell(74 ,16,'Kode - Nama Barang',1,0,'C');
$pdf->Cell(16 ,16,'Satuan',1,0,'C');
$pdf->Cell(18 ,16,'Saldo',1,0,'C');
$pdf->Cell(18 ,16,'Aktiva',1,0,'C');
$pdf->Cell(18 ,16,'Fisik',1,0,'C');
$pdf->Cell(36 ,8,'Selisih',1,1,'C');
$pdf->Cell(154 ,0,'',0,0,'C');
$pdf->Cell(18 ,8,'Saldo',1,0,'C');
$pdf->Cell(18 ,8,'Aktiva',1,1,'C');


/*Heading Of the table end*/
$pdf->SetFont('Arial','',8);

$no = 1;

$nol = [ 0, 0, 0, 0, 0 ];

$query_d = mysqli_query($conn, $sql);
if(mysqli_num_rows($query_d) > 0 ) {

    while($data = mysqli_fetch_assoc($query_d)){
        
        $pdf->Cell(10 ,7,$no++,1,0,'C');
        $pdf->Cell(74 ,7,$data["pluid_so"]." - ".$data["NamaBarang"]." ".$data["NamaJenis"],1,0,'C');
        $pdf->Cell(16 ,7,$data["nama_satuan"],1,0,'C');
        $pdf->Cell(18 ,7,$stock = $data['saldo_so'],1,0,'C');
        $pdf->Cell(18 ,7,$asset = $data['total'],1,0,'C');
        $pdf->Cell(18 ,7,$fisik = $data['fisik_so'],1,0,'C');
        $pdf->Cell(18 ,7,$selisih_stock = ($fisik - $stock),1,0,'C');
        $pdf->Cell(18 ,7,$selisih_asset = ($fisik - $asset),1,1,'C');
        
        $total_stock = ($nol[0]+=$stock);
        $total_asset = ($nol[1]+=$asset);
        $total_fisik = ($nol[2]+=$fisik);
        $total_selisih_stock = ($nol[3]+=$selisih_stock);
        $total_selisih_asset = ($nol[4]+=$selisih_asset);

    }

    $pdf->SetFont('Arial','B',8);

    $pdf->Cell(100 ,8,'Total',1,0,'C');
    $pdf->Cell(18 ,8,$total_stock ,1,0,'C');
    $pdf->Cell(18 ,8,$total_asset,1,0,'C');
    $pdf->Cell(18 ,8,$total_fisik,1,0,'C');
    $pdf->Cell(18 ,8,$total_selisih_stock,1,0,'C');
    $pdf->Cell(18 ,8,$total_selisih_asset,1,1,'C');

}

$pdf->Ln(5);

$pdf->SetFont('Arial','',10);

$pdf->Cell(126 ,8,'',0,0,'');
$pdf->Cell(64 ,8,$header['office_city'].' '.date("d F Y"),0,1,'R');

$pdf->Ln(5);

$pdf->Cell(80 ,8,'Mengetahui',1,0,'C');
$pdf->Cell(60 ,8,'',0,0,'C');
$pdf->Cell(50 ,8,'Petugas SO',1,1,'C');

$pdf->Cell(40 ,28,'',1,0,'');
$pdf->Cell(40 ,28,'',1,0,'');
$pdf->Cell(60 ,28,'',0,0,'');
$pdf->Cell(50 ,28,'',1,1,'');

$pdf->Cell(40 ,8,'MGR. Department',1,0,'C');
$pdf->Cell(40 ,8,'SPV. Department',1,0,'C');
$pdf->Cell(60 ,8,'',0,0,'C');
$pdf->Cell(50 ,8,'',1,1,'C');


$pdf->Output("BASO-".$header['no_so']."-".date("d-m-Y").".pdf","I");

?>