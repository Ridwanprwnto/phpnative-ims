<?php

require '../includes/config/timezone.php';
require '../includes/function/func.php';
require '../includes/function/tag.php';
require '../includes/config/conn.php';

/*call the FPDF library*/
require '../includes/config/mc_table.php';

if (session_status()!==PHP_SESSION_ACTIVE)session_start();

$username = $_SESSION["user_name"];

if (isset($_SESSION['PRINTLHSO'])){
    $_POST = $_SESSION['PRINTLHSO'];
    unset($_SESSION['PRINTLHSO']);
}
else {
    $msg = encrypt("print-error");
    header("location: ../error.php?alert=$msg");
    exit();
}

$id = $_GET["lhso"];

if(isset($_GET["lhso"])) {
    if($_GET["lhso"] === $id) {
        $strplus = rplplus($id);
        $decid = mysqli_real_escape_string($conn, decrypt($strplus));
        if($decid == true) {
            $sql = "SELECT A.*, B.office_name, B.office_city, C.department_name, D.username, E.*, F.NamaBarang, G.NamaJenis, H.*, I.ba_merk, I.ba_tipe FROM head_stock_opname AS A 
            INNER JOIN office AS B ON A.office_so = B.id_office
            INNER JOIN department AS C ON A.dept_so = C.id_department
            INNER JOIN users AS D ON A.user_so = D.nik
            INNER JOIN detail_stock_opname AS E ON A.no_so = E.no_so_head
            INNER JOIN mastercategory AS F ON LEFT(E.pluid_so, 6) = F.IDBarang
            INNER JOIN masterjenis AS G ON RIGHT(E.pluid_so, 4) = G.IDJenis
            INNER JOIN asset_stock_opname AS H ON E.pluid_so = H.pluid_so_asset
            INNER JOIN barang_assets AS I ON H.sn_so_asset = I.sn_barang
            WHERE A.no_so = '$decid' GROUP BY H.id_so_asset";
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

class PDF extends PDF_MC_Table
{
    // Page header
    function Header()
    {
        
        global $header;
        global $username;
           
        /*output the result*/

        $this->SetFont('Arial','',10);

        $this->Cell(28 ,5,'Kantor',0,0);
        $this->Cell(48 ,5,': '.$header['office_so']." - ".$header['office_name'],0,0);
        $this->Cell(38 ,5,'',0,0);
        $this->Cell(28 ,5,'Tanggal SO',0,0);
        $this->Cell(48 ,5,': '.$header['tgl_so'], 0, 1);

        $this->Cell(28 ,5,'Department',0,0);
        $this->Cell(48 ,5,': '.$header['department_name'],0,0);
        $this->Cell(38 ,5,'',0,0);
        $this->Cell(28 ,5,'Print Date',0,0);
        $this->Cell(48 ,5,': '.date("d-m-Y H:i:s"), 0, 1);

        $this->Cell(28 ,5,'Petugas',0,0);
        $this->Cell(48 ,5,': '.$header['username'],0,0);
        $this->Cell(38 ,5,'',0,0);
        $this->Cell(28 ,5,'User Print',0,0);
        $this->Cell(48 ,5,': '.$username, 0, 1);

        $this->Ln(4);

        /*set font to arial, bold, 14pt*/
        $this->SetFont('Arial','B',14);

        /*Cell(width , height , text , border , end line , [align] )*/
        $this->Cell(190 ,8,'LAPORAN HASIL STOCK OPNAME BARANG INVENTARIS',0,1, 'C');

        $this->SetFont('Arial','B',10);

        $this->Cell(190 ,5,'Nomor SO : '.$header['no_so'],0,1,'C');

        $this->Ln(2);

        $this->SetFont('Arial','B',10);
        /*Heading Of the table*/
        $this->Cell(10 ,8,'No',1,0,'C');
        $this->Cell(70 ,8,'Nama Barang',1,0,'C');
        $this->Cell(46 ,8,'Serial Number',1,0,'C');
        $this->Cell(24 ,8,'No AT',1,0,'C');
        $this->Cell(40 ,8,'Status',1,1,'C');
       
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

$pdf->SetTitle('Report LHSO Barang Inventaris');

/*Heading Of the table end*/
$pdf->SetFont('Arial','',10);

$no = 1;
        
$query_d = mysqli_query($conn, $sql);
if(mysqli_num_rows($query_d) > 0 ) {

    while($data = mysqli_fetch_assoc($query_d)){
        

        if ($data["kondisi_so_asset"] == "$arrcond[0]") {
            $kondisi = "01 - BAIK";
        }
        elseif ($data["kondisi_so_asset"] == "$arrcond[1]") {
            $kondisi = "02 - CADANGAN";
        }
        elseif ($data["kondisi_so_asset"] == "$arrcond[2]") {
            $kondisi = "03 - RUSAK";
        }
        elseif ($data["kondisi_so_asset"] == "$arrcond[3]") {
            $kondisi = "04 - PERBAIKAN";
        }
        elseif ($data["kondisi_so_asset"] == "$arrcond[4]") {
            $kondisi = "05 - P3AT";
        }
        elseif ($data["kondisi_so_asset"] == "$arrcond[6]") {
            $kondisi = "07 - HILANG";
        }
        elseif ($data["kondisi_so_asset"] == "$arrcond[7]") {
            $kondisi = "08 - MUTASI";
        }
        else {
            $kondisi = "-";
        }

        $desc = $data['NamaBarang']." ".$data['NamaJenis']." ".$data['ba_merk']." ".$data['ba_tipe'];
        $sn = $data["sn_so_asset"];
        $at = $data["noat_so_asset"];

        $pdf->SetWidths(array(10, 70, 46, 24, 40));
        $pdf->Row(array($no++, $desc, $sn, $at, $kondisi));

    }

}

$pdf->Ln(8);

$pdf->SetFont('Arial','B',10);

$pdf->Cell(190 ,7,'Summary Data Stock Opname',0,1,'');

$pdf->Cell(10 ,16,'No',1,0,'C');
$pdf->Cell(70 ,16,'Nama Barang',1,0,'C');
$pdf->Cell(94.5 ,8,'Status',1,0,'C');
$pdf->Cell(16 ,16,'Total',1,1,'C');
$pdf->Cell(80 ,0,'',1,0,'C');
$pdf->Cell(13.5 ,-8,'01 (B)',1,0,'C');
$pdf->Cell(13.5 ,-8,'02 (C)',1,0,'C');
$pdf->Cell(13.5 ,-8,'03 (R)',1,0,'C');
$pdf->Cell(13.5 ,-8,'04 (S)',1,0,'C');
$pdf->Cell(13.5 ,-8,'05 (P)',1,0,'C');
$pdf->Cell(13.5 ,-8,'07 (H)',1,0,'C');
$pdf->Cell(13.5 ,-8,'08 (M)',1,0,'C');
$pdf->Cell(16 ,0,'',1,1,'C');

$pdf->SetFont('Arial','',10);
     
$nod = 1;
$nol = [0, 0, 0, 0, 0, 0, 0, 0, 0];

$sql_opnd = "SELECT A.*, E.*, F.NamaBarang, G.NamaJenis, H.*, I.ba_merk, I.ba_tipe FROM head_stock_opname AS A 
INNER JOIN detail_stock_opname AS E ON A.no_so = E.no_so_head
INNER JOIN mastercategory AS F ON LEFT(E.pluid_so, 6) = F.IDBarang
INNER JOIN masterjenis AS G ON RIGHT(E.pluid_so, 4) = G.IDJenis
INNER JOIN asset_stock_opname AS H ON E.pluid_so = H.pluid_so_asset
INNER JOIN barang_assets AS I ON H.sn_so_asset = I.sn_barang
WHERE E.no_so_head = '$decid' GROUP BY H.pluid_so_asset";

$query_opn = mysqli_query($conn, $sql_opnd);
if(mysqli_num_rows($query_opn) > 0 ) {

    while($data_opn = mysqli_fetch_assoc($query_opn)){

        $pluid = $data_opn["pluid_so_asset"];

        $data_baik = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(kondisi_so_asset) AS total_baik FROM asset_stock_opname WHERE pluid_so_asset = '$pluid' AND kondisi_so_asset = '01' "));

        $data_cadangan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(kondisi_so_asset) AS total_cadangan FROM asset_stock_opname WHERE pluid_so_asset = '$pluid' AND kondisi_so_asset = '02'"));

        $data_rusak = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(kondisi_so_asset) AS total_rusak FROM asset_stock_opname WHERE pluid_so_asset = '$pluid' AND kondisi_so_asset = '03'"));

        $data_service = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(kondisi_so_asset) AS total_service FROM asset_stock_opname WHERE pluid_so_asset = '$pluid' AND kondisi_so_asset = '04'"));

        $data_p3at = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(kondisi_so_asset) AS total_p3at FROM asset_stock_opname WHERE pluid_so_asset = '$pluid' AND kondisi_so_asset = '05'"));

        $data_hilang = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(kondisi_so_asset) AS total_hilang FROM asset_stock_opname WHERE pluid_so_asset = '$pluid' AND kondisi_so_asset = '07'"));
        
        $data_mutasi = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(kondisi_so_asset) AS total_mutasi FROM asset_stock_opname WHERE pluid_so_asset = '$pluid' AND kondisi_so_asset = '08'"));
            
        $desc_sopnd = $data_opn['pluid_so_asset']." - ".$data_opn['NamaBarang']." ".$data_opn['NamaJenis'];
        
        $tot1 = $data_baik["total_baik"];
        $tot2 = $data_cadangan["total_cadangan"];
        $tot3 = $data_rusak["total_rusak"];
        $tot4 = $data_service["total_service"];
        $tot5 = $data_p3at["total_p3at"];
        $tot7 = $data_hilang["total_hilang"];
        $tot8 = $data_mutasi["total_mutasi"];
        $subtotal = $tot1 + $tot2 + $tot3 + $tot4 + $tot5 + $tot7 + $tot8;

        $pdf->SetWidths(array(10, 70, 13.5, 13.5, 13.5, 13.5, 13.5, 13.5, 13.5, 16));
        $pdf->Row(array($nod++, $desc_sopnd, $tot1, $tot2, $tot3, $tot4, $tot5, $tot7, $tot8, $subtotal));

        $to_satu = ($nol[0]+=$tot1);
        $to_dua = ($nol[1]+=$tot2);
        $to_tiga = ($nol[2]+=$tot3);
        $to_empat = ($nol[3]+=$tot4);
        $to_lima = ($nol[4]+=$tot5);
        $to_tujuh = ($nol[5]+=$tot7);
        $to_delapan = ($nol[6]+=$tot8);
        $gd_total = ($nol[7]+=$subtotal);

    }

}

$pdf->SetFont('Arial','B',10);

$pdf->Cell(80 ,8,'Grand Total',1,0,'');
$pdf->Cell(13.5 ,8,$to_satu,1,0,'L');
$pdf->Cell(13.5 ,8,$to_dua,1,0,'L');
$pdf->Cell(13.5 ,8,$to_tiga,1,0,'L');
$pdf->Cell(13.5 ,8,$to_empat,1,0,'L');
$pdf->Cell(13.5 ,8,$to_lima,1,0,'L');
$pdf->Cell(13.5 ,8,$to_tujuh,1,0,'L');
$pdf->Cell(13.5 ,8,$to_delapan,1,0,'L');
$pdf->Cell(16 ,8,$gd_total++,1,1,'L');

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

$pdf->Output("LHSO-".$header['no_so']."-".date("d-m-Y").".pdf","I");

?>