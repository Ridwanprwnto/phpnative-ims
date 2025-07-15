<?php

require '../includes/config/timezone.php';
require '../includes/function/func.php';
require '../includes/config/conn.php';

/*call the FPDF library*/
require '../includes/config/mc_table.php';

$nik = $_POST["nik-kejadian"];
$fullname = $_POST["user-kejadian"];
$office = $_POST["office-kejadian"];
$dept = $_POST["dept-kejadian"];
$tgl = $_POST["tgl-kejadian"];
$div = $_POST["div-kejadian"];

if ($div == "ALL") {
    $sql = "SELECT A.*, B.id_office, B.office_name, B.office_shortname, C.id_department, C.department_name, C.department_initial, D.divisi_name, I.username, I.full_name, K.name_fup_plg FROM pelanggaran_cctv AS A
    INNER JOIN office AS B ON A.office_plg_cctv = B.id_office
    INNER JOIN department AS C ON A.dept_plg_cctv = C.id_department
    INNER JOIN divisi AS D ON A.div_plg_cctv = D.id_divisi
    INNER JOIN users AS I ON A.user_plg_cctv = I.nik
    INNER JOIN fup_pelanggaran AS K ON A.fup_plg_cctv = K.id_fup_plg
    WHERE A.office_plg_cctv = '$office' AND A.dept_plg_cctv = '$dept' AND DATE(A.tgl_plg_cctv) = DATE('$tgl') ORDER BY A.tgl_plg_cctv ASC";
}
else {
    $sql = "SELECT A.*, B.id_office, B.office_name, B.office_shortname, C.id_department, C.department_name, C.department_initial, D.divisi_name, I.username, I.full_name, K.name_fup_plg FROM pelanggaran_cctv AS A
    INNER JOIN office AS B ON A.office_plg_cctv = B.id_office
    INNER JOIN department AS C ON A.dept_plg_cctv = C.id_department
    INNER JOIN divisi AS D ON A.div_plg_cctv = D.id_divisi
    INNER JOIN users AS I ON A.user_plg_cctv = I.nik
    INNER JOIN fup_pelanggaran AS K ON A.fup_plg_cctv = K.id_fup_plg
    WHERE A.office_plg_cctv = '$office' AND A.dept_plg_cctv = '$dept' AND DATE(A.tgl_plg_cctv) = DATE('$tgl') AND A.div_plg_cctv = '$div' ORDER BY A.tgl_plg_cctv ASC";    
}

$query_h = mysqli_query($conn, $sql);
$header = mysqli_fetch_assoc($query_h);

/*A4 width : 219mm*/

$pdf = new PDF_MC_Table('P','mm','A4');
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetTitle('Laporan Kejadian Terekam CCTV');

/*output the result*/

// $company = "PT. INDOMARCO PRISMATAMA";

// $pdf->SetFont('Arial','B',10);
// $pdf->Cell(30 ,10,'',0,1);

// $address = "Jl. Alternatif Sentul KM 46, Kel. Cijujung, Kec. Sukaraja, Kabupaten Bogor";

// $pdf->Ln(14);
/*set font to arial, bold, 14pt*/

// $pdf->SetFont('Arial','',10);

// $pdf->Cell(28 ,5,'Kantor',0,0);
// $pdf->Cell(57 ,5,': '.$header['id_office']." - ".$header['office_name'],0,0);
// $pdf->Cell(20 ,5,'',0,0);
// $pdf->Cell(28 ,5,'Departemen',0,0);
// $pdf->Cell(57 ,5,': '.$header['department_name'],0,1);

// $pdf->Ln(3);

$pdf->SetFont('Arial','B',10);

/*Cell(width , height , text , border , end line , [align] )*/
$pdf->Cell(63 ,10,'',0,0);
$pdf->Cell(63 ,10,'REKAP KEJADIAN TEREKAM CCTV',0,0, 'C');
$pdf->Cell(63 ,10,'',0,1);

$pdf->Line(10, 20, 200, 20);

$pdf->Ln(3);

$pdf->SetFont('Arial','',8);

$pdf->Cell(28 ,5,'Bagian',0,0);
$pdf->Cell(162 ,5,': ADM CCTV',0,1);

$pdf->Cell(28 ,5,'Kantor',0,0);
$pdf->Cell(162 ,5,': '.$header['department_initial']." ".$header['office_shortname']." - ".$header['id_office'],0,1);

$tgl_kej  =  date( "d F Y", strtotime($tgl));

$pdf->Ln(1);

if ($div == "ALL") {
    $bag = $div;
}
else {
    $bag = $header['divisi_name'];
}

$pdf->Cell(190 ,5,'Berikut adalah kejadian menyimpang / tidak SOP yang terekam kamera CCTV',0,1);
$pdf->Cell(190 ,5,'Untuk Bagian '.ucwords(strtolower($bag)).' Pada tanggal '.$tgl_kej,0,1);

$pdf->Ln(2);

$pdf->SetFont('Arial','B',8);

// Colors of frame, background and text
// $pdf->SetDrawColor(0,80,180);
$pdf->SetFillColor(230,230,0);
// $pdf->SetTextColor(220,50,50);

/*Heading Of the table*/
$pdf->Cell(9 ,8,'NO',1,0,'C', true);
$pdf->Cell(38 ,8,'KATEGORI',1,0,'C', true);
$pdf->Cell(55 ,8,'PELANGGARAN',1,0,'C', true);
$pdf->Cell(34 ,8,'LOKASI',1,0,'C', true);
$pdf->Cell(14 ,8,'JAM',1,0,'C', true);
$pdf->Cell(20 ,8,'FOLLOW UP',1,0,'C', true);
$pdf->Cell(20 ,8,'PELANGGAR',1,1,'C', true);
/*Heading Of the table end*/
$pdf->SetFont('Arial','',8);

$no = 1;

if (isset($office) && !empty($office)) {

    $query_d = mysqli_query($conn, $sql);
    if(mysqli_num_rows($query_d) > 0 ) {

        while($data = mysqli_fetch_assoc($query_d)){

            // $nop = $data['no_plg_cctv'];
            $cat = $data['ctg_plg_cctv'];
            $plg = $data['jns_plg_cctv'];
            $lok = $data['lokasi_plg_cctv'];
            $wkt = substr($data["tgl_plg_cctv"], 11, 5);

            $pdf->SetWidths(array(9, 38, 55, 34, 14, 20, 20));
            $pdf->Row(array($no++, $cat, $plg, $lok, $wkt, '', ''));

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

$pdf->Ln(1);

$pdf->SetFont('Arial','B',8);

$pdf->Cell(190 ,8,'Note :',0,1);

$pdf->SetFont('Arial','',8);
$pdf->MultiCell(190 ,5,'Rekaman cctv bisa di lihat di ruang cctv dengan membawa kertas ini.',0,1);

$pdf->Ln(4);

$pdf->Cell(55 ,6,'Di Follow Up Oleh :',1,0,'C');
$pdf->Cell(80 ,6,'Mengetahui',1,0,'C');
$pdf->Cell(55 ,6,'Dibuat',1,1,'C');
$pdf->Cell(55 ,28,'',1,0,'');
$pdf->Cell(80 ,28,'',1,0,'');
$pdf->Cell(55 ,28,'',1,1,'');
$pdf->Cell(55 ,6,'',1,0,'C');
$pdf->Cell(40 ,6,'DCM / DDCM',1,0,'C');
$pdf->Cell(40 ,6,'SPV ADM',1,0,'C');
$pdf->Cell(55 ,6,'ADM CCTV',1,1,'C');


$pdf->Output("LAPORAN KEJADIAN TEREKAM CCTV ".$tgl_kej.".pdf","I");

?>