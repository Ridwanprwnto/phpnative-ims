<?php
require '../includes/config/timezone.php';
require '../includes/function/func.php';
require '../includes/function/tag.php';
require '../includes/config/conn.php';

/*call the FPDF library*/
require '../includes/config/mc_table.php';

$user = mysqli_real_escape_string($conn, $_POST["user-cetak"]);
$office = mysqli_real_escape_string($conn, $_POST["office-cetak"]);
$dept = mysqli_real_escape_string($conn, $_POST["dept-cetak"]);
$awal = $_POST["awal-cetak"];
$akhir = $_POST["akhir-cetak"];

$barang = $_POST["barangcetak"];
$arrdata = implode(", ", $barang);

if ($arrdata == "ALL") {
    $sql = "SELECT A.*, B.id_office, B.office_name, B.office_city, C.id_department, C.department_name, D.username, E.*, F.NamaBarang, G.NamaJenis, H.nama_satuan FROM head_stock_opname AS A 
    INNER JOIN office AS B ON A.office_so = B.id_office
    INNER JOIN department AS C ON A.dept_so = C.id_department
    INNER JOIN users AS D ON A.user_so = D.nik
    INNER JOIN detail_stock_opname AS E ON A.no_so = E.no_so_head 
    INNER JOIN mastercategory AS F ON LEFT(E.pluid_so, 6) = F.IDBarang
    INNER JOIN masterjenis AS G ON RIGHT(E.pluid_so, 4) = G.IDJenis
    INNER JOIN satuan AS H ON F.id_satuan = H.id_satuan
    WHERE A.office_so = '$office' AND A.dept_so = '$dept' AND LEFT(A.tgl_so, 10) BETWEEN '$awal' AND '$akhir' AND A.status_so = 'Y' ORDER BY A.tgl_so ASC";
}
else {
    $sql = "SELECT A.*, B.id_office, B.office_name, B.office_city, C.id_department, C.department_name, D.username, E.*, F.NamaBarang, G.NamaJenis, H.nama_satuan FROM head_stock_opname AS A 
    INNER JOIN office AS B ON A.office_so = B.id_office
    INNER JOIN department AS C ON A.dept_so = C.id_department
    INNER JOIN users AS D ON A.user_so = D.nik
    INNER JOIN detail_stock_opname AS E ON A.no_so = E.no_so_head 
    INNER JOIN mastercategory AS F ON LEFT(E.pluid_so, 6) = F.IDBarang
    INNER JOIN masterjenis AS G ON RIGHT(E.pluid_so, 4) = G.IDJenis
    INNER JOIN satuan AS H ON F.id_satuan = H.id_satuan
    WHERE A.office_so = '$office' AND A.dept_so = '$dept' AND LEFT(A.tgl_so, 10) BETWEEN '$awal' AND '$akhir' AND E.pluid_so IN ($arrdata) AND A.status_so = 'Y' ORDER BY A.tgl_so ASC";
}

$query_h = mysqli_query($conn, $sql);
$header = mysqli_fetch_assoc($query_h);

/*A4 width : 219mm*/

class PDF extends PDF_MC_Table {
    // Page header
    function Header()
    {
        
        global $header, $user, $awal, $akhir;
        
        /*output the result*/
        $this->SetFont('Arial','',8);

        $this->Cell(28 ,5,'Kantor',0,0);
        $this->Cell(48 ,5,': '.$header['office_so']." - ".$header['office_name'],0,0);
        $this->Cell(38 ,5,'',0,0);
        $this->Cell(28 ,5,'Print Date',0,0);
        $this->Cell(48 ,5,': '.date("d-m-Y H:i:s"), 0, 1);

        $this->Cell(28 ,5,'Department',0,0);
        $this->Cell(48 ,5,': '.$header['department_name'],0,0);
        $this->Cell(38 ,5,'',0,0);
        $this->Cell(28 ,5,'User Print',0,0);
        $this->Cell(48 ,5,': '.$user, 0, 1);

        $this->Ln(4);

        /*set font to arial, bold, 14pt*/
        $this->SetFont('Arial','B',12);

        /*Cell(width , height , text , border , end line , [align] )*/
        $this->Cell(63 ,7,'',0,0);
        $this->Cell(63 ,7,'LAPORAN HISTORY DATA HASIL STOCK OPNAME',0,0, 'C');
        $this->Cell(63 ,7,'',0,1);

        $this->SetFont('Arial','',8);

        $this->Cell(190 ,5,'Periode : '.$awal." ".$akhir,0,1,'C');

        $this->Ln(4);

        $this->SetFont('Arial','B',8);
        /*Heading Of the table*/
        $this->Cell(10 ,16,'No',1,0,'C');
        $this->Cell(13 ,16,'Noref',1,0,'C');
        $this->Cell(17 ,16,'Tanggal',1,0,'C');
        $this->Cell(20 ,16,'Petugas',1,0,'C');
        $this->Cell(40 ,16,'Nama Barang',1,0,'C');
        $this->Cell(20 ,16,'Saldo Awal',1,0,'C');
        $this->Cell(20 ,16,'DAT',1,0,'C');
        $this->Cell(20 ,16,'SO Fisik',1,0,'C');
        $this->Cell(30 ,8,'Selisih',1,1,'C');
        $this->Cell(160 ,0,'',0,0,'C');
        $this->Cell(15 ,8,'Saldo',1,0,'C');
        $this->Cell(15 ,8,'DAT',1,1,'C');
       
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

$pdf = new PDF('P','mm','A4');
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetTitle('Laporan History Data SO');

/*Heading Of the table end*/
$pdf->SetFont('Arial','',8);

$no = 1;

if (isset($office) && isset($dept) && isset($awal) && isset($akhir) && isset($user) && isset($barang)) {

    $query_d = mysqli_query($conn, $sql);
    if(mysqli_num_rows($query_d) > 0 ) {

        while($data = mysqli_fetch_assoc($query_d)){

            $str_date = substr($data['tgl_so'], 0, 10);
            $slash_date  =  str_replace('-"', '/', $str_date);
            $newDate  =  date( "d/m/Y", strtotime($slash_date));

            $noso = $data['no_so'];
            $desc = $data["pluid_so"]." - ".$data["NamaBarang"]." ".$data["NamaJenis"];
            $petugas = $data["user_so"]." - ".strtoupper($data["username"]);
            $stock = $data['saldo_so'];
            $asset = $data['asset_so'];
            $fisik = $data['fisik_so'];
            $selisih_stock = ($fisik - $stock);
            $selisih_asset = ($fisik - $asset);
            
            $pdf->SetWidths(array(10, 13, 17, 20, 40, 20, 20, 20, 15, 15));
            $pdf->Row(array($no++, $noso, $newDate, $petugas, $desc, $stock, $asset, $fisik, $selisih_stock, $selisih_asset));

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

$pdf->Output("HISTORY DATA SO ".$awal." ".$akhir.".pdf","I");

?>