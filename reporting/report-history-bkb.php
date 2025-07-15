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
$barang = $_POST["barang-cetak"];

if ($barang == "ALL") {
    $sql = "SELECT A.*, B.id_office, B.office_name, B.office_city, C.id_department, C.department_name, D.divisi_name, E.NamaBarang, F.NamaJenis, G.username AS pic FROM bkb AS A 
    INNER JOIN office AS B ON A.office_bkb = B.id_office
    INNER JOIN department AS C ON A.dept_bkb = C.id_department
    INNER JOIN divisi AS D ON A.div_bkb = D.id_divisi
    INNER JOIN mastercategory AS E ON LEFT(A.pluid_bkb, 6) = E.IDBarang
    INNER JOIN masterjenis AS F ON RIGHT(A.pluid_bkb, 4) = F.IDJenis
    INNER JOIN users AS G ON A.user_bkb = G.nik
    WHERE A.office_bkb = '$office' AND A.dept_bkb = '$dept' AND A.tgl_bkb BETWEEN '$awal' AND '$akhir' ORDER BY A.tgl_bkb ASC";
}
else {
    $sql = "SELECT A.*, B.id_office, B.office_name, B.office_city, C.id_department, C.department_name, D.divisi_name, E.NamaBarang, F.NamaJenis, G.username AS pic FROM bkb AS A 
    INNER JOIN office AS B ON A.office_bkb = B.id_office
    INNER JOIN department AS C ON A.dept_bkb = C.id_department
    INNER JOIN divisi AS D ON A.div_bkb = D.id_divisi
    INNER JOIN mastercategory AS E ON LEFT(A.pluid_bkb, 6) = E.IDBarang
    INNER JOIN masterjenis AS F ON RIGHT(A.pluid_bkb, 4) = F.IDJenis
    INNER JOIN users AS G ON A.user_bkb = G.nik
    WHERE A.office_bkb = '$office' AND A.dept_bkb = '$dept' AND A.tgl_bkb BETWEEN '$awal' AND '$akhir' AND A.pluid_bkb = '$barang' ORDER BY A.tgl_bkb ASC";
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
        $this->Cell(48 ,5,': '.$header['office_bkb']." - ".$header['office_name'],0,0);
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
        $this->Cell(63 ,7,'REKAPITULASI DATA PEMAKAIAN / KELUAR BARANG INVENTARIS',0,0, 'C');
        $this->Cell(63 ,7,'',0,1);

        $this->SetFont('Arial','',8);

        $strdate_awal  =  str_replace('-"', '/', $awal);
        $strdtstart  =  date( "d/m/Y", strtotime($strdate_awal));
        $strdate_akhir  =  str_replace('-"', '/', $akhir);
        $strdtstop  =  date( "d/m/Y", strtotime($strdate_akhir));

        $this->Cell(190 ,5,'Periode : '.$strdtstart." - ".$strdtstop,0,1,'C');

        $this->Ln(4);

        $this->SetFont('Arial','B',8);
        /*Heading Of the table*/
        $this->Cell(10 ,8,'No',1,0,'C');
        $this->Cell(13 ,8,'Docno',1,0,'C');
        $this->Cell(17 ,8,'Tanggal',1,0,'C');
        $this->Cell(26 ,8,'Nama Barang',1,0,'C');
        $this->Cell(21 ,8,'SN',1,0,'C');
        $this->Cell(21 ,8,'AT',1,0,'C');
        $this->Cell(21 ,8,'Lokasi',1,0,'C');
        $this->Cell(21 ,8,'PIC',1,0,'C');
        $this->Cell(40 ,8,'Keterangan',1,1,'C');
       
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

$pdf->SetTitle('Report Daftar Data Keluar Barang');

/*Heading Of the table end*/
$pdf->SetFont('Arial','',8);

$no = 1;

if (isset($office) && isset($dept) && isset($awal) && isset($akhir) && isset($user) && isset($barang)) {

    $query_d = mysqli_query($conn, $sql);
    if(mysqli_num_rows($query_d) > 0 ) {

        while($data = mysqli_fetch_assoc($query_d)){

            $slash_date  =  str_replace('-"', '/', $data['tgl_bkb']);
            $newDate  =  date( "d/m/Y", strtotime($slash_date));

            $docno = $data['nomor_bkb'];
            $desc = $data["pluid_bkb"]." - ".$data["NamaBarang"]." ".$data["NamaJenis"];
            $sn = $data['sn_bkb'];
            $at = $data['at_bkb'];
            $pic = $data["user_bkb"]." - ".strtoupper($data["pic"]);
            $lokasi = $data['lokasi_bkb'];
            $ket = $data['ket_bkb'];
            
            $pdf->SetWidths(array(10, 13, 17, 26, 21, 21, 21, 21, 40));
            $pdf->Row(array($no++, $docno, $newDate, $desc, $sn, $at, $lokasi, $pic, $ket));

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

$pdf->Output("REKAPITULASI DATA BKB PERIODE ".$awal." ".$akhir.".pdf","I");

?>