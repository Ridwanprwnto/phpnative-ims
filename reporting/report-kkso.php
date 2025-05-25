<?php

require '../includes/config/timezone.php';
require '../includes/function/func.php';
require '../includes/function/tag.php';
require '../includes/config/conn.php';

/*call the FPDF library*/
require '../includes/config/mc_table.php';

if (session_status()!==PHP_SESSION_ACTIVE)session_start();

$username = $_SESSION["user_name"];

if (isset($_SESSION['PRINTKKSO'])){
    $_POST = $_SESSION['PRINTKKSO'];
    unset($_SESSION['PRINTKKSO']);
}
else {
    $msg = encrypt("print-error");
    header("location: ../error.php?alert=$msg");
    exit();
}

$id = $_GET["kkso"];

if(isset($_GET["kkso"])) {
    if($_GET["kkso"] === $id) {
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
        $this->Cell(126 ,5,'',0,0);
        $this->Cell(28 ,5,'Tanggal SO',0,0);
        $this->Cell(48 ,5,': '.$header['tgl_so'], 0, 1);

        $this->Cell(28 ,5,'Department',0,0);
        $this->Cell(48 ,5,': '.$header['department_name'],0,0);
        $this->Cell(126 ,5,'',0,0);
        $this->Cell(28 ,5,'Print Date',0,0);
        $this->Cell(48 ,5,': '.date("d-m-Y H:i:s"), 0, 1);

        $this->Cell(28 ,5,'Petugas',0,0);
        $this->Cell(48 ,5,': '.$header['username'],0,0);
        $this->Cell(126 ,5,'',0,0);
        $this->Cell(28 ,5,'User Print',0,0);
        $this->Cell(48 ,5,': '.$username, 0, 1);

        $this->Ln(2);

        /*set font to arial, bold, 14pt*/
        $this->SetFont('Arial','B',14);

        /*Cell(width , height , text , border , end line , [align] )*/
        $this->Cell(278 ,10,'KERTAS KERJA STOCK OPNAME BARANG INVENTARIS',0,0, 'C');

        $this->SetFont('Arial','B',10);

        $this->Ln(6);

        $this->Cell(278 ,10,'NO SO : '.$header['no_so'],0,1,'C');

        $this->SetFont('Arial','B',10);
        /*Heading Of the table*/
        $this->Cell(10 ,16,'No',1,0,'C');
        $this->Cell(64 ,16,'Nama Barang',1,0,'C');
        $this->Cell(47 ,16,'Serial Number',1,0,'C');
        $this->Cell(25 ,16,'No AT',1,0,'C');
        $this->Cell(84 ,8,'Status',1,0,'C');
        $this->Cell(48 ,16,'Lokasi',1,1,'C');
        $this->Cell(146 ,0,'',1,0,'C');
        $this->Cell(12 ,-8,'(B) 01',1,0,'C');
        $this->Cell(12 ,-8,'(C) 02',1,0,'C');
        $this->Cell(12 ,-8,'(R) 03',1,0,'C');
        $this->Cell(12 ,-8,'(S) 04',1,0,'C');
        $this->Cell(12 ,-8,'(P) 05',1,0,'C');
        $this->Cell(12 ,-8,'(H) 07',1,0,'C');
        $this->Cell(12 ,-8,'(M) 08',1,0,'C');
        $this->Cell(48 ,0,'',1,1,'C');
       
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

$pdf->SetAuthor('Inventory Management System');
$pdf->SetTitle('Report Kertas Kerja SO Barang');
$pdf->SetSubject('Report KKSO');
$pdf->SetKeywords('KKSO');
$pdf->SetCreator('IMS');

/*Heading Of the table end*/
$pdf->SetFont('Arial','',10);

$no = 1;

$query_d = mysqli_query($conn, $sql);
if(mysqli_num_rows($query_d) > 0 ) {

    while($data = mysqli_fetch_assoc($query_d)){

        $desc = $data['NamaBarang']." ".$data['NamaJenis']." ".$data['ba_merk']." ".$data['ba_tipe'];
        $desccut = strlen($desc) > 40 ? substr($desc, 0, 40)."..." : $desc;
        $sn = $data['sn_so_asset'];
        $at = $data["noat_so_asset"];

        $st01 = $data['kondisi_so_asset'] == "01" ? "V" : "";
        $st02 = $data['kondisi_so_asset'] == "02" ? "V" : "";
        $st03 = $data['kondisi_so_asset'] == "03" ? "V" : "";
        $st04 = $data['kondisi_so_asset'] == "04" ? "V" : "";
        $st05 = $data['kondisi_so_asset'] == "05" ? "V" : "";
        $st07 = $data['kondisi_so_asset'] == "07" ? "V" : "";
        $st08 = $data['kondisi_so_asset'] == "08" ? "V" : "";

        $lok = $data["lokasi_so_asset"];

        $pdf->SetWidths(array(10, 64, 47, 25, 12, 12, 12, 12, 12, 12, 12, 48));
        $pdf->Row(array($no++, $desccut, $sn, $at, $st01, $st02, $st03, $st04, $st05, $st07, $st08, $lok));
    }

    
    $pdf->Ln(5);

    $pdf->SetFont('Arial','B',8);

    $pdf->Cell(10 ,10,'ID',1,0,'C');
    $pdf->Cell(111 ,10,'Keterangan Status',1,1,'C');

    $sql_con = "SELECT * FROM kondisi WHERE id_kondisi NOT LIKE '$arrcond[5]'";
    $query_con = mysqli_query($conn, $sql_con);

    if(mysqli_num_rows($query_con) > 0 ) {

        $pdf->SetFont('Arial','',8);

        while($data_con = mysqli_fetch_assoc($query_con)){

            $idc = $data_con["id_kondisi"];
            $condition = $data_con["kondisi_name"];
            $nmc = $condition == 'PERBAIKAN' ? 'SERVICE / PERBAIKAN' : $condition;

            $pdf->SetWidths(array(10, 111));
            $pdf->Row(array($idc, $nmc));
        }
    
    }

}

$pdf->Output("KKSO-".$header['no_so']."-".date("d-m-Y").".pdf","I");

?>